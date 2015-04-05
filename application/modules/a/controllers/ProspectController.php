<?php
class ProspectController extends Ez_Action
{
	
	function array2csv(array &$array)
	{
		if (count($array) == 0) {
			return null;
		}
		ob_start();
		$df = fopen("php://output", 'w');
		fputcsv($df, array_keys(reset($array)));
		foreach ($array as $row) {
			fputcsv($df, $row);
		}
		fclose($df);
		return ob_get_clean();
	}
	function download_send_headers($filename) {
		// disable caching
		$now = gmdate("D, d M Y H:i:s");
		header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
		header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
		header("Last-Modified: {$now} GMT");
	
		// force download
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
	
		// disposition / encoding on response body
		header("Content-Disposition: attachment;filename={$filename}");
		header("Content-Transfer-Encoding: binary");
	}
	
	
	public function init()
	{
		$this->view->fields = array(
			0 => 'Ignore',
			1 => 'Date',
			2 => 'Agent',
			3 => 'Agency',
			4 => 'Phone Number',
			5 => 'Email',
			6 => 'Notes',
			7 => 'POC',
			8 => 'Fax',
			9 => 'Address',
				
			10 => 'First Name',
			11 => 'Last Name',
			12 => 'City',
			13 => 'State',
			14 => 'Zip Code',
				
			
		);
		
		$this->view->sectionMenu = array(
			1 => array(
					'name' 	 => 'List prospects',
					'url' => '/prospect/',
					'items' => array(
						11 => array('name' => '+ Add new prospect', 'url' => '/prospect/add'),
						
			)
			),
			2 => array(
					'name' 	 => 'List Imports',
					'url' => '/prospect/list-import/',
					'items' => array(
							21 => array('name' => '+ Upload new import', 'url' => '/prospect/add-import'),
					),
				),
				
		);
		
		$this->setSectionTitle('Prospects');
	}
	
	public function indexAction()
	{
		$p = $this->_request->getParams();
		$p['rows'] = 25;
		$p['page'] = isset($p['page']) ? $p['page'] : 1;
		
		
		$this->setSectionId(1);
		if(isset($p['id']) && $p['id'] > 0)
		{
			$modelImport = new Application_Model_Import();
			$imp = $modelImport->select($p['id']);

			$this->view->pageTitle = 'Prospects processed for file '.$imp['imp_name'];
			$this->view->bdcp = array(
				array(
					'url' => '/',
					'name' => 'Home'
				),
					
				array(
					'name' => 'List prospects',
					'url' => '/prospects/'
				),
				array(
					'name' => 'Prospects proccesed for '.$imp['imp_name'],
			)
					
			);
			$p['imp_id'] = $p['id'];
			$this->view->imp = $imp;
			
		}
		else 
		{
			$this->view->pageTitle = 'Prospects';
			$this->view->bdcp = array(
				array(
					'url' => '/',
					'name' => 'Home'
				),
			
				array(
					'name' => 'List prospects',
				),
			
			);
		}
		
		
		$modelList = new Application_Model_Prospect_List(array_merge(array('adm_id' => Sid_Auth_Admin::getAdminId(), 'status_not_in' => 4), $p));
		$this->view->data = $modelList->find();
		$this->view->p = $p;
	}
	
	public function addImportAction()
	{
		$this->view->pageTitle = 'New import';
		$this->setSectionId(21);
		$this->view->bdcp = array(
			array(
				'url' => '/',
				'name' => 'Home'
			),
		
			array(
				'name' => 'Prospects',
				'url' => '/prospect'
			),
				
			array(
				'name' => 'Import new file',
		
			),
	
		);
	}
	
	public function downloadAction()
	{
		$impId = $this->_request->getParam('id');
		$modelImp = new Application_Model_Import();
		$imp = $modelImp->select($impId);
		if($imp['imp_adm_id'] != Sid_Auth_Admin::getAdminId())
		{
			die('No access !');
		}
		
		$toCsv = array();
		$r = $modelImp->fetchAll("SELECT *, dsp_name FROM prs_prospect
					LEFT JOIN dsp_disposition ON ( dsp_id = prs_dsp_id )
				WHERE prs_imp_id = ".$impId);

		$cnt = 0;
		if(count($r))
		{
			foreach ($r as $v)
			{
				$date = App_Date::fromMysqlDate($v['prs_date']);
				$toCsv[$cnt]['Date'] =  $date != '00/00/0000' ? $date : '' ;
				$toCsv[$cnt]['Agent'] = $v['prs_agent'];
				$toCsv[$cnt]['Agency'] = $v['prs_agency'];
				$toCsv[$cnt]['Phone'] = $v['prs_phone'];
				$toCsv[$cnt]['Email'] = $v['prs_email'];
				$toCsv[$cnt]['Notes'] = $v['prs_notes'];
				$toCsv[$cnt]['POC'] =$v['prs_poc'];
				$toCsv[$cnt]['Fax'] = $v['prs_fax'];
				$toCsv[$cnt]['Address'] = $v['prs_address'];
				$toCsv[$cnt]['Disposition'] = $v['dsp_name'];
				
				$cnt++;
			}
		}
		
		$this->download_send_headers("prospects-export-".$impId."-".date('m-d-Y').".csv");
		echo $this->array2csv($toCsv);
		die();
	}
	
	public function importFormAction()
	{
		$this->_helper->layout()->disableLayout();
		$this->view->error = '';
		if($this->_request->isPost())
		{
			$mimes = array('application/vnd.ms-excel','text/plain','text/csv','text/tsv');
			
			$row = 1;
			if (($handle = fopen($_FILES['csvfile']['tmp_name'], "r")) !== FALSE)
			{
			    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)
			     {
			     	if($row >= 1)
			     	{
			     		$num = count($data);
			     		if($num < 4)
			     		{
			     			$this->view->error = 'Invalid file format!';
			     		}
			     		
			     		break;
			     	}
			     	$row++;

			    }
			    fclose($handle);
			}
			else 
			{
				$this->view->error = 'Invalid file format!';
			}
			
			if(empty($this->view->error))
			{
				// move the file into data folder
				$newFileName = md5($_FILES['csvfile']['name']."-".time()).'.csv';
				move_uploaded_file($_FILES['csvfile']['tmp_name'], APPLICATION_PATH."/../data/imports/".$newFileName);
				//insert the data into imp
				$modelImport = new Application_Model_Import();
				$this->view->csvTmpId = $modelImport->insert(array(
					'imp_name' => $_FILES['csvfile']['name'],
					'imp_file' => $newFileName,
					'imp_adm_id' => Sid_Auth_Admin::getAdminId(),
					'imp_date_in' => date('Y-m-d H:i:s'),
					'imp_data' => '',
					'imp_status' => Application_Model_Import::IMP_STATUS_NEW,
					'imp_type' => Application_Model_Import::IMP_TYPE_PROSPECTS
					
				));
			}

		}
	}
	
	public function importHeadAction()
	{

		$modelAdmin = new Application_Model_Admin_List();
		$this->view->admins = $modelAdmin->find();
		
		$this->_helper->layout()->disableLayout();
		$impId = $this->_request->getParam('imp_id',0);
		$this->view->imp_id = $impId;
		$modelImp = new Application_Model_Import();
		$imp = $modelImp->select($impId);
		if(!isset($imp['imp_id']))
		{
			die('I can`t find this import!');
		}
		
		if(!file_exists(APPLICATION_PATH."/../data/imports/".$imp['imp_file']))
		{
			echo APPLICATION_PATH."/../data/imports/".$imp['imp_file'];
			die('I can`t find the file');
		}
		
		$row = 1;
		$this->view->sel = array();
		if (($handle = fopen(APPLICATION_PATH."/../data/imports/".$imp['imp_file'], "r")) !== FALSE)
		{
			while (($data = fgetcsv($handle, 2048, ",")) !== FALSE)
			{
				if($row == 1)
				{
					$fields = array();
					foreach ($this->view->fields as $k=>$v)
					{
						$fields[$k] = strtolower(trim($v));
					}
					foreach ($data as $k => $v)
					{
						$v = strtolower(trim($v));
						if(in_array($v, $fields))
						{
							$this->view->sel[$k] = array_search($v, $fields);
						}
						else 
						{

						}

					}

				}
				if($row >= 2)
				{
					$num = count($data);
					$this->view->data = $data;
		
					break;
				}
				$row++;
		
			}
			fclose($handle);
		}
	}
	
	public function saveImportAction()
	{
		$p = $this->_request->getParams();

		$this->_helper->layout()->disableLayout();
		$translate = array();
		$this->view->error = '';
		if(isset($p['data']) && count($p['data']))
		{
			foreach ($p['data'] as $k => $v)
			{
				if(array_key_exists($v, $this->view->fields))
				{

						if($v == 1)
						{
							if(!isset($translate['impd_date']))
							{
								$translate['impd_date'] =  $k;
							}
							else 
							{
								$this->view->error = 'Date field allready has been assigned!';
							}
							
						}
						
						if($v == 2)
						{
							
							if(!isset($translate['impd_agent']))
							{
								$translate['impd_agent'] =  $k;
							}
							else
							{
								$this->view->error = 'Agent field allready has been assigned!';
							}
						}
						
						if($v == 3)
						{
							if(!isset($translate['impd_agency']))
							{
								$translate['impd_agency'] =  $k;
							}
							else
							{
								$this->view->error = 'Agency field allready has been assigned!';
							}
						}
						
						if($v == 4)
						{
							if(!isset($translate['impd_phone']))
							{
								$translate['impd_phone'] =  $k;
							}
							else
							{
								$this->view->error = 'Phone field allready has been assigned!';
							}
						}
						
						if($v == 5)
						{
							if(!isset($translate['impd_email']))
							{
								$translate['impd_email'] =  $k;
							}
							else
							{
								$this->view->error = 'Email field allready has been assigned!';
							}
						}
						
						
						if($v == 6)
						{
							if(!isset($translate['impd_notes']))
							{
								$translate['impd_notes'] =  $k;
							}
							else
							{
								$this->view->error = 'Notes field allready has been assigned!';
							}
							
							
						}
						
						if($v == 7)
						{
							if(!isset($translate['impd_poc']))
							{
								$translate['impd_poc'] =  $k;
							}
							else
							{
								$this->view->error = 'POC field allready has been assigned!';
							}
						}
						
						if($v == 8)
						{
							if(!isset($translate['impd_fax']))
							{
								$translate['impd_fax'] =  $k;
							}
							else
							{
								$this->view->error = 'FAX field allready has been assigned!';
							}
						}
						
						if($v == 9)
						{
							if(!isset($translate['impd_address']))
							{
								$translate['impd_address'] =  $k;
							}
							else
							{
								$this->view->error = 'Address field allready has been assigned!';
							}
						}
						
						if($v == 10)
						{
							if(!isset($translate['impd_firs_name']))
							{
								$translate['impd_firs_name'] =  $k;
							}
							else
							{
								$this->view->error = 'First name field allready has been assigned!';
							}
						}
						
						if($v == 11)
						{
							if(!isset($translate['impd_last_name']))
							{
								$translate['impd_last_name'] =  $k;
							}
							else
							{
								$this->view->error = 'Last name field allready has been assigned!';
							}
						}
						
						if($v == 12)
						{
							if(!isset($translate['impd_city']))
							{
								$translate['impd_city'] =  $k;
							}
							else
							{
								$this->view->error = 'City field allready has been assigned!';
							}
						}
						
						if($v == 13)
						{
							if(!isset($translate['impd_state']))
							{
								$translate['impd_state'] =  $k;
							}
							else
							{
								$this->view->error = 'State field allready has been assigned!';
							}
						}
						
						if($v == 14)
						{
							if(!isset($translate['impd_zip']))
							{
								$translate['impd_zip'] =  $k;
							}
							else
							{
								$this->view->error = 'Zip field allready has been assigned!';
							}
						}
				}
			}
			if(empty($this->view->error))
			{
				$modelImp = new Application_Model_Import();
				$impId = $this->_request->getParam('imp_id');
				if(isset($p['imp_adm_id']) && $p['imp_adm_id']> 0 )
				{

					$modelImp->update(array('imp_adm_id' => $p['imp_adm_id']), 'imp_id = '.$impId );
				}
				$imp = $modelImp->select($impId);
				$modelImpd = new Application_Model_Imported();
				$row = 0;
				if (($handle = fopen(APPLICATION_PATH."/../data/imports/".$imp['imp_file'], "r")) !== FALSE)
				{
					while (($data = fgetcsv($handle, 2048, ",")) !== FALSE)
					{
						if($row >= 1)
						{

							$agent = isset($translate['impd_agent']) ? $data[$translate['impd_agent']] : '';
							$agent .= isset($translate['impd_firs_name']) ? $data[$translate['impd_firs_name']] : '';
							$agent .= isset($translate['impd_last_name']) ? " ".$data[$translate['impd_last_name']]: '';
							
							$address = isset($translate['impd_address']) ? $data[$translate['impd_address']] : '';
							$address .= isset($translate['impd_city']) ? $data[$translate['impd_city']] : '';
							$address .= isset($translate['impd_state']) ? ', '.$data[$translate['impd_state']] : '';
							$address .= isset($translate['impd_zip']) ? ', '.$data[$translate['impd_zip']] : '';
							
			
							
							
							$f = array(
								'impd_imp_id' => $impId,
								'impd_date' => isset($translate['impd_date']) ? App_Date::toMysqlDate($data[$translate['impd_date']]) : '',
								'impd_agent' => $agent ,
								'impd_agency' => isset($translate['impd_agency']) ? $data[$translate['impd_agency']] : '',
								'impd_phone' => isset($translate['impd_phone']) ? $data[$translate['impd_phone']] : '',
								'impd_email' => isset($translate['impd_email']) ? $data[$translate['impd_email']] : '',
								'impd_notes' => isset($translate['impd_notes']) ? $data[$translate['impd_notes']] : '',
								'impd_poc' => isset($translate['impd_poc']) ? $data[$translate['impd_poc']] : '',
								'impd_fax' => isset($translate['impd_fax']) ? $data[$translate['impd_fax']] : '',
								'impd_address' => $address,
							
							);

							$modelImpd->insert(
								$f
									
							);

						}
						$row++;
				
					}
					fclose($handle);
					$this->view->imp_id = $impId;
				}
			}
				
		}

	}
	
	
	public function processAction()
	{
		$this->setSectionId(2);
		$modelDspList = new Application_Model_Disposition_List();
		$this->view->dsp = $modelDspList->find();
		$impId = $this->_request->getParam('id');
		$modelImp = new Application_Model_Import();
		$imp = $modelImp->select($impId);
		if($imp['imp_adm_id'] != Sid_Auth_Admin::getAdminId())
		{
			header('Location:/prospect');
			die();
		}
		
		$modelImpd = new Application_Model_Imported();
		$this->view->impd = $modelImpd->getNextImported($impId);
		
		$modelPrqlList = new Application_Model_Prequalification_List();
		$this->view->prqs = $modelPrqlList->find();
		
		
	}
	        
        public function getAgencyAction( $name, $phoneNumber ) {
            Bootstrap::logConsole("-->getAgencyAction()");
            $agency = null;
            // quick exit
            if ( $name == null && $phoneNumber == null ) {
                return $agency;
            }
            
            $modelLead = new Application_Model_Lead();
            if ( $name != null && $phoneNumber == null ) {
                $agency = $modelLead->fetchAll("Select * from prs_prospect where Upper( prs_agency ) = Upper('".$name."')");
            } else if ( $name == null && $phoneNumber != null ) {
                $agency = $modelLead->fetchAll("select * from prs_prospect where replace( prs_phone, '-','') = replace( '".$phoneNumber."','-','')");
            } else if ( $name != null && $phoneNumber != null ) {
                $agency = $modelLead->fetchAll("select * from prs_prospect where Upper( prs_agency ) = Upper('".$name."') AND replace( prs_phone, '-','') = replace( '".$phoneNumber."','-','')");
            } 
                
            return $agency;
        }
        
        // validateAgencyAction()
        // validation rules:
        // rule 1:  if agency does not exist then its currently not valid in the system (good thing)
        // rule 2: if both the agency name and number are being validated then both
        //          must match in order for the agency to be valid.
        // rule 3: if just the agency name has been provided then if the name exists then
        //          the agency is considered valid.
        // rule 4:  if just the phone number has been provided then if the number exists in 
        //          in the system then consider the agency valid.
        // if for some reason we get through all the rules above without a match then consider
        // the agency invalid.
        // input:
        //  http request - in particular looking for prs_agency and prs_phone.
        // output:
        //  boolean
        //      valid : true
        //      invalid : false
        //
        public function validateAgencyAction() {
            Bootstrap::logConsole("-->validateAgencyAction()");
            
            $p = $this->_request->getParams();
            $prsAgency = array_key_exists('prs_agency', $p)?trim($p['prs_agency']):null;
            $prsPhone = array_key_exists('prs_phone', $p)?trim($p['prs_phone']):null;
            
            // quick escape
            if ( strlen( $prsAgency ) < 1 && strlen( $prsPhone ) < 1 ) {
                die('false');
            }
            
            $agency = $this->getAgencyAction( $prsAgency, $prsPhone );
            
            // validation rules:
            // rule 1:  
            if ( $agency == null  || count($agency) == 0 ) {
                die('false');
            }
            
            foreach ( $agency as $item ) {
                $itemPhoneStripped = str_replace('-', '', trim($item['prs_phone']));
                $prsPhoneStripped = str_replace('-','', $prsPhone );
                
                // rule 2: 
                if ( strcasecmp(trim($item['prs_agency']), $prsAgency) == 0 && strcasecmp($prsPhoneStripped,$itemPhoneStripped) == 0 ) {
                    die( 'true' );
                }
                // rule 3: 
                if ( strcasecmp(trim($item['prs_agency']), $prsAgency) == 0 ) {
                    die( 'true' );
                }
                // rule 4:  
                if ( strcasecmp($itemPhoneStripped, $prsPhoneStripped) == 0 && strlen($itemPhoneStripped) > 0 && strlen($prsPhoneStripped) > 0 ) {
                    die( 'true' );
                }
            }
            // default rule:
            die('false');
        }
        
        // validateAgentAction()
        // Purpose:  determine whether an agent already exists in the system.
        // Rules:
        // Rule 1:  If the agents name is found in the system then return true.
        // Input:
        //      HTTP Request -- looking for prs_agent
        // Output:
        //  boolean
        //      True : found in system
        //      False : NOT found in system
        public function validateAgentAction() {
            $p = $this->_request->getParams();
            $prsAgent = array_key_exists('prs_agent', $p)?trim($p['prs_agent']):null;
                       
            // quick escape
            if ( strlen( $prsAgent ) < 1 ) {
                die('false');
            }
            
            $agents = null;
            $modelLead = new Application_Model_Lead();
            if ( $prsAgent != null ) {
                $agents = $modelLead->fetchAll("Select * from prs_prospect where Upper( prs_agent ) = Upper('".$prsAgent."')");
            } 
            
            if ( $agents == null || count($agents) == 0 ) {
                die('false');
            }
            
            foreach ( $agents as $a ) {
                // Rule 1:
                if ( strcasecmp(trim($a['prs_agent']),trim($prsAgent)) == 0 ) {
                    die('true');
                }
            }
            // default rule
            die('false');
        }
        
        // validateAgentNameAction()
        // Purpose:
        //  Determine whether the name of the agent currently exists in the system.
        // Rules:
        //  Rule 1: If both the first and last name are provided and the combo is found in the system then return true.
        //  Rule 2: If only the first-name is provided and it is found in the system then return true.
        //  Rule 3: If only the last-name is provided and it is found in the system then return true.
        // Input:
        //  HTTP request.  Expecting to find prs_first_name and prs_last_name in the request.
        // Output:
        //  boolean
        //      True : found in system
        //      False : NOT found in system
        public function validateAgentNameAction() {
            $p = $this->_request->getParams();
            $prsFName = array_key_exists('prs_first_name', $p)?trim($p['prs_first_name']):null;
            $prsLName = array_key_exists('prs_last_name', $p)?trim($p['prs_last_name']):null;
            
            // quick escape
            if ( strlen($prsFName) < 1 && strlen($prsLName) < 1 ) {
                die('false');
            }
            
            $agents = null;
            $modelLead = new Application_Model_Lead();
            if ( $prsLName != null && $prsFName == null ) {
                $agents = $modelLead->fetchAll("Select * from prs_prospect where Upper( prs_last_name ) = Upper('".$prsLName."')");
            } else if ( $prsFName != null && $prsLName == null ) {
                $agents = $modelLead->fetchAll("Select * from prs_prospect where Upper( prs_first_name ) = Upper('".$prsFName."')");
            } else if ( $prsFName != null && $prsLName != null ) {
                $agents = $modelLead->fetchAll("Select * from prs_prospect where Upper( prs_first_name ) = Upper('".$prsFName."') AND Upper( prs_last_name ) = Upper('".$prsLName."')");
            }
            
            if ( $agents == null || count($agents) == 0 ) {
                die('false');
            }
            
            foreach ( $agents as $a ) {
                // rule 1:
                if ( $prsLName != null && $prsFName != null ) {
                    if ( strcasecmp($a['prs_last_name'], $prsLName) == 0 && strcasecmp($a['prs_first_name'], $prsFName) == 0 ) {
                        die('true');
                    }
                } else if ( $prsFName != null && $prsLName == null ) {
                    // Rule 2:
                    if ( strcasecmp($a['prs_first_name'], $prsFName) == 0 ) {
                        die('true');
                    }
                } else if ( $prsLName != null && $prsFName == null ) {
                    // Rule 3:
                    if ( strcasecmp($a['prs_last_name'], $prsLName) == 0 ) {
                        die('true');
                    }
                }
            }
            // default rule:
            die('false');
            
        }
        
	public function postAction()
	{
		$p  = $this->_request->getParams();


		$p['ntf_type'] = isset($p['ntf_type']) ? $p['ntf_type'] : array();
		
		

		$modelProspect = new Application_Model_Prospect();
		$prsStage =  Application_Model_Prospect::PRS_STAGE_NEW ;
		if(!isset($p['id']))
		{
			if(!isset($p['s_module']))
			{
				$p['s_module'] = Application_Model_Module::MODULE_TYPE_BOTH;
			}
			$prsId = $modelProspect->insert(
					array(
						'prs_date' => App_Date::toMysqlDate($p['prs_date']),
						'prs_agent' => $p['prs_agent'],
						'prs_agency' => $p['prs_agency'],
						'prs_phone' => $p['prs_phone'],
						'prs_email' => $p['prs_email'],
						'prs_notes' => $p['prs_notes'],
						'prs_poc' => $p['prs_poc'],
						'prs_fax' => $p['prs_fax'],
						'prs_address' => $p['prs_address'],
						'prs_date_in' => date("Y-m-d H:i:s"),
						'prs_type' => Application_Model_Prospect::PRS_IMPORTED,
						'prs_adm_id' => Sid_Auth_Admin::getAdminId(),
						'prs_imp_id' => $p['imp_id'],
						'prs_dsp_id' => intval($p['prs_dsp_id']),
						'prs_stage' => $prsStage,
						'prs_status' => $p['prs_status'],
						'prs_call_attempt' => $p['prs_call_attempt'],
						'prs_module' => $p['s_module']
			
					)
			);
		}
		else 
		{
			$prsId = $p['id'];
			if(!isset($p['s_module']))
			{
				$p['s_module'] = Application_Model_Module::MODULE_TYPE_BOTH;
			}
			$modelProspect->update(
					array(
						'prs_date' => App_Date::toMysqlDate($p['prs_date']),
						'prs_agent' => $p['prs_agent'],
						'prs_agency' => $p['prs_agency'],
						'prs_phone' => $p['prs_phone'],
						'prs_email' => $p['prs_email'],
						'prs_notes' => $p['prs_notes'],
						'prs_poc' => $p['prs_poc'],
						'prs_fax' => $p['prs_fax'],
						'prs_address' => $p['prs_address'],
						'prs_dsp_id' => intval($p['prs_dsp_id']),
						'prs_status' => $p['prs_status'],
						'prs_call_attempt' => $p['prs_call_attempt'],
						'prs_module' => $p['s_module']
					), 'prs_id = '.$prsId
			);
		}

		if(isset($p['ntf_date']) && preg_match('/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}/', $p['ntf_date']))
		{
			if(!isset($p['ntf_note']) || empty($p['ntf_note']))
			{
				die('In order to set reminder you have to make a note first!');
			}
			else
			{
				$modelNotification = new Application_Model_Notification();
				$modelNotification->insert(
						array(
							'ntf_adm_id' => Sid_Auth_Admin::getAdminId(),
							'ntf_date' => $p['ntf_date'],
							'ntf_note' => $p['ntf_note'],
							'ntf_notify' => Zend_Json::encode($p['ntf_type']),
							'ntf_ref_id' => $prsId,
							'ntf_ref_type' => Application_Model_Notification::NOTE_REF_TYPE_PROSPECT
						)
		
				);
		
			}
		}
		
		if(isset($p['ntf_date2']) && preg_match('/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}/', $p['ntf_date2']))
		{
			if(!isset($p['ntf_note2']) || empty($p['ntf_note2']))
			{
				die('In order to set reminder you have to make a note first!');
			}
			else
			{
				$modelNotification = new Application_Model_Notification();
				$modelNotification->insert(
						array(
								'ntf_adm_id' => Sid_Auth_Admin::getAdminId(),
								'ntf_date' => $p['ntf_date2'],
								'ntf_note' => $p['ntf_note2'],
								'ntf_notify' => Zend_Json::encode($p['ntf_type2']),
								'ntf_ref_id' => $prsId,
								'ntf_ref_type' => Application_Model_Notification::NOTE_REF_TYPE_PROSPECT
						)
		
				);
		
			}
		}
		
		// if this was promoted
		
		if(!isset($p['id']))
		{
			$modelImpd = new Application_Model_Imported();
			$modelImpd->delete($p['impd_id']);
		}
		if(isset($p['getid']))
		{
			echo $prsId;
			die();
		}
		die('done');

	}
	
	function listImportAction()
	{
		$this->view->pageTitle = 'List imported prospects';
		$this->view->bdcp = array(
			array(
				'url' => '/',
				'name' => 'Home'
			),
		
			array(
				'name' => 'Prospects',
				'url' => '/prospect'
			),
		
			array(
				'name' => 'List imports',
		
			),
		);
		
		$modelLead = new Application_Model_Lead();
		$this->view->imports = $modelLead->fetchAll("SELECT *,
									( SELECT COUNT(impd_id) as cnt_to_do FROM impd_imported WHERE impd_imp_id = imp_id ) as cnt_to_do,
									( SELECT COUNT(prs_id) as cnt_do FROM prs_prospect WHERE prs_imp_id = imp_id ) as cnt_do
								FROM imp_import
					WHERE imp_adm_id = ".Sid_Auth_Admin::getAdminId()." AND imp_type = ".Application_Model_Import::IMP_TYPE_PROSPECTS." ORDER BY imp_id DESC
									
				");	
		
		$this->view->sectionId = 2;
	}
	
	public function editAction()
	{
		$this->setSectionId(1);
		$prsId = $this->_request->getParam('id');
		$modelProspect = new Application_Model_Prospect();
		$prs = $modelProspect->select($prsId);
		if($prs['prs_adm_id'] != Sid_Auth_Admin::getAdminId())
		{
			die('No access!');
		}
		$this->view->prs = $prs;
		
		$modelDspList = new Application_Model_Disposition_List();
		$this->view->dsp = $modelDspList->find();
		
		$this->view->pageTitle = 'Edit Prospect';
		$this->view->bdcp = array(
			array(
				'url' => '/',
				'name' => 'Home'
			),
		
			array(
				'name' => 'Prospects',
				'url' => '/prospect'
			),
		
			array(
				'name' => 'Edit Prospect',
		
			),
		
		
		);
	}
	
	public function addAction()
	{
		$modelDspList = new Application_Model_Disposition_List();
		$this->view->dsp = $modelDspList->find();
		
		$this->setSectionId(11);
		
		$this->setPageTitle('Add Prospect');
		$this->view->bdcp = array(
				array(
						'url' => '/',
						'name' => 'Home'
				),
		
				array(
						'name' => 'Prospects',
						'url' => '/prospect/'
				),
		
				array(
						'name' => 'Add new Prospect',
		
				),
		
		
		);
		
	}
	
	public function addPostAction()
	{
		$p = $this->_request->getParams();
		$modelProspect = new Application_Model_Prospect();
		
		$prsId = $modelProspect->insert(
				array(
					'prs_date' => App_Date::toMysqlDate($p['prs_date']),
					'prs_agent' => $p['prs_agent'],
					'prs_agency' => $p['prs_agency'],
					'prs_phone' => $p['prs_phone'],
					'prs_email' => $p['prs_email'],
					'prs_notes' => $p['prs_notes'],
					'prs_poc' => $p['prs_poc'],
					'prs_fax' => $p['prs_fax'],
					'prs_address' => $p['prs_address'],
					'prs_date_in' => date("Y-m-d H:i:s"),
					'prs_type' => Application_Model_Prospect::PRS_NEW,
					'prs_adm_id' => Sid_Auth_Admin::getAdminId(),
					'prs_imp_id' => 0,
					'prs_dsp_id' => intval($p['prs_dsp_id']),
					'prs_stage' => Application_Model_Prospect::PRS_STAGE_NEW,
					'prs_status' => $p['prs_status'],
					'prs_call_attempt' => $p['prs_call_attempt'],
					'prs_module' => $p['s_module']
						
				)
		);
		
		if(isset($p['ntf_date']) && preg_match('/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}/', $p['ntf_date']))
		{
			if(!isset($p['ntf_note']) || empty($p['ntf_note']))
			{
				die('In order to set reminder you have to make a note first!');
			}
			else
			{
				$modelNotification = new Application_Model_Notification();
				$modelNotification->insert(
						array(
							'ntf_adm_id' => Sid_Auth_Admin::getAdminId(),
							'ntf_date' => $p['ntf_date'],
							'ntf_note' => $p['ntf_note'],
							'ntf_notify' => Zend_Json::encode($p['ntf_type']),
							'ntf_ref_id' => $prsId,
							'ntf_ref_type' => Application_Model_Notification::NOTE_REF_TYPE_PROSPECT
						)
		
				);
		
			}
		}
		
		die('done');
	}
	
	public function deleteImportAction()
	{
		$id = $this->_request->getParam('id',0);
		$modelImp = new Application_Model_Import();
		$imp = $modelImp->select($id);
		if($imp['imp_adm_id'] != Sid_Auth_Admin::getAdminId())
		{
			die('You don`t have access to this entry!');
		}
		$modelImp->delete($id);
		$modelImp->db->query('DELETE FROM impd_imported WHERE impd_imp_id = ?', array($id));
		die('done');
		
	}
}
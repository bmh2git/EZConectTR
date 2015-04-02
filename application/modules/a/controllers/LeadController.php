<?php
class LeadController extends Zend_Controller_Action
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
			
		);
	}
	
	public function indexAction()
	{
		$p = $this->_request->getParams();
		if(isset($p['id']) && $p['id'] > 0)
		{
			$modelImport = new Application_Model_Import();
			$imp = $modelImport->select($p['id']);

			$this->view->pageTitle = 'Leads processed for file '.$imp['imp_name'];
			$this->view->bdcp = array(
				array(
					'url' => '/',
					'name' => 'Home'
				),
					
				array(
					'name' => 'List leads',
					'url' => '/lead/'
				),
				array(
					'name' => 'Leads proccesed for '.$imp['imp_name'],
			)
					
			);
			$p['imp_id'] = $p['id'];
			$this->view->imp = $imp;
			
		}
		else 
		{
			$this->view->pageTitle = 'Leads';
			$this->view->bdcp = array(
				array(
					'url' => '/',
					'name' => 'Home'
				),
			
				array(
					'name' => 'List Leads',
				),
			
			);
		}
		
		
		$modelList = new Application_Model_Lead_List(array_merge(array('adm_id' => Sid_Auth_Admin::getAdminId(), 'status_not_in' => 4), $p));
		$this->view->data = $modelList->find();
	}
	
	public function addImportAction()
	{
		$this->view->pageTitle = 'New import';
		$this->view->bdcp = array(
			array(
				'url' => '/',
				'name' => 'Home'
			),
		
			array(
				'name' => 'Leads',
				'url' => '/lead'
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
		$r = $modelImp->fetchAll("SELECT *, dsp_name FROM lead_lead
					LEFT JOIN dsp_disposition ON ( dsp_id = lead_dsp_id )
				WHERE lead_imp_id = ".$impId);

		$cnt = 0;
		if(count($r))
		{
			foreach ($r as $v)
			{
				$date = App_Date::fromMysqlDate($v['lead_date']);
				$toCsv[$cnt]['Date'] =  $date != '00/00/0000' ? $date : '' ;
				$toCsv[$cnt]['Agent'] = $v['lead_agent'];
				$toCsv[$cnt]['Agency'] = $v['lead_agency'];
				$toCsv[$cnt]['Phone'] = $v['lead_phone'];
				$toCsv[$cnt]['Email'] = $v['lead_email'];
				$toCsv[$cnt]['Notes'] = $v['lead_notes'];
				$toCsv[$cnt]['POC'] =$v['lead_poc'];
				$toCsv[$cnt]['Fax'] = $v['lead_fax'];
				$toCsv[$cnt]['Address'] = $v['lead_address'];
				$toCsv[$cnt]['Disposition'] = $v['dsp_name'];
				
				$cnt++;
			}
		}
		
		$this->download_send_headers("leads-export-".$impId."-".date('m-d-Y').".csv");
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
						print_r($data);
			     			$this->view->error = "row: $row : num: $num : Invalid file format!";
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
					'imp_type' => Application_Model_Import::IMP_TYPE_LEAD
					
				));
			}

		}
	}
	
	public function importHeadAction()
	{

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

				}
			}
			if(empty($this->view->error))
			{
				$modelImp = new Application_Model_Import();
				$impId = $this->_request->getParam('imp_id');
				$imp = $modelImp->select($impId);
				$modelImpd = new Application_Model_Imported();
				$row = 0;
				if (($handle = fopen(APPLICATION_PATH."/../data/imports/".$imp['imp_file'], "r")) !== FALSE)
				{
					while (($data = fgetcsv($handle, 2048, ",")) !== FALSE)
					{
						if($row >= 1)
						{

							$f = array(
								'impd_imp_id' => $impId,
								'impd_date' => isset($translate['impd_date']) ? App_Date::toMysqlDate($data[$translate['impd_date']]) : '',
								'impd_agent' => isset($translate['impd_agent']) ? $data[$translate['impd_agent']] : '',
								'impd_agency' => isset($translate['impd_agency']) ? $data[$translate['impd_agency']] : '',
								'impd_phone' => isset($translate['impd_phone']) ? $data[$translate['impd_phone']] : '',
								'impd_email' => isset($translate['impd_email']) ? $data[$translate['impd_email']] : '',
								'impd_notes' => isset($translate['impd_notes']) ? $data[$translate['impd_notes']] : '',
								'impd_poc' => isset($translate['impd_poc']) ? $data[$translate['impd_poc']] : '',
								'impd_fax' => isset($translate['impd_fax']) ? $data[$translate['impd_fax']] : '',
								'impd_address' => isset($translate['impd_address']) ? $data[$translate['impd_address']] : '',
							
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
		$modelDspList = new Application_Model_Disposition_List();
		$this->view->dsp = $modelDspList->find();
		$impId = $this->_request->getParam('id');
		$modelImp = new Application_Model_Import();
		$imp = $modelImp->select($impId);
		if($imp['imp_adm_id'] != Sid_Auth_Admin::getAdminId())
		{
			die('You don`t have access to this page!');
		}
		
		$modelImpd = new Application_Model_Imported();
		$this->view->impd = $modelImpd->getNextImported($impId);
		
	}
	
	public function postAction()
	{
		$p  = $this->_request->getParams();


		$p['ntf_type'] = isset($p['ntf_type']) ? $p['ntf_type'] : array();
		
		

		$modelLead = new Application_Model_Lead();
		$prsStage =  Application_Model_Lead::LEAD_STAGE_NEW ;
		if(!isset($p['id']))
		{
			$leadId = $modelLead->insert(
					array(
						'lead_date' => App_Date::toMysqlDate($p['lead_date']),
						'lead_agent' => $p['lead_agent'],
						'lead_agency' => $p['lead_agency'],
						'lead_phone' => $p['lead_phone'],
						'lead_email' => $p['lead_email'],
						'lead_notes' => $p['lead_notes'],
						'lead_poc' => $p['lead_poc'],
						'lead_fax' => $p['lead_fax'],
						'lead_address' => $p['lead_address'],
						'lead_date_in' => date("Y-m-d H:i:s"),
						'lead_type' => Application_Model_Lead::LEAD_IMPORTED,
						'lead_adm_id' => Sid_Auth_Admin::getAdminId(),
						'lead_imp_id' => $p['imp_id'],
						'lead_dsp_id' => intval($p['lead_dsp_id']),
						'lead_stage' => $prsStage,
						'lead_status' => $p['lead_status'],
						'lead_due_date' => App_Date::toMysqlDate($p['lead_date']),
						'lead_call_attempt' => $p['lead_call_attempt'],
						'lead_module' => $p['s_module'],
						'lead_inspection' => isset($p['lead_inspection']) && !empty($p['lead_inspection']) ? App_Date::toMysqlDate($p['lead_inspection']) : '0000-00-00'
			
					)
			);
		}
		else 
		{
			
			$leadId = $p['id'];
			
			$modelLead->update(
					array(
						'lead_date' => App_Date::toMysqlDate($p['lead_date']),
						'lead_agent' => $p['lead_agent'],

						'lead_agency' => $p['lead_agency'],
						'lead_phone' => $p['lead_phone'],
						'lead_email' => $p['lead_email'],
						'lead_notes' => $p['lead_notes'],
						'lead_poc' => $p['lead_poc'],
						'lead_fax' => $p['lead_fax'],
						'lead_address' => $p['lead_address'],
						'lead_dsp_id' => intval($p['lead_dsp_id']),
						'lead_call_attempt' => $p['lead_call_attempt'],
						'lead_due_date' => App_Date::toMysqlDate($p['lead_due_date']),
						'lead_module' => $p['s_module']
							
					), 'lead_id = '.$leadId);
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
							'ntf_ref_id' => $leadId,
							'ntf_ref_type' => Application_Model_Notification::NOTE_REF_TYPE_LEAD
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
			echo $leadId;
			die();
		}
		
		die('done');
	}
	
	function listImportAction()
	{
		$this->view->pageTitle = 'List imported leads';
		$this->view->bdcp = array(
			array(
				'url' => '/',
				'name' => 'Home'
			),
		
			array(
				'name' => 'Leads',
				'url' => '/lead'
			),
		
			array(
				'name' => 'List imports',
		
			),
		
		
		);
		
		$modelLead = new Application_Model_Lead();
		$this->view->imports = $modelLead->fetchAll("SELECT *,
									( SELECT COUNT(impd_id) as cnt_to_do FROM impd_imported WHERE impd_imp_id = imp_id ) as cnt_to_do,
									( SELECT COUNT(lead_id) as cnt_do FROM lead_lead WHERE lead_imp_id = imp_id ) as cnt_do
								FROM imp_import
					WHERE imp_adm_id = ".Sid_Auth_Admin::getAdminId()." AND imp_type = ".Application_Model_Import::IMP_TYPE_LEAD." ORDER BY imp_id DESC
									
				");	
	}
	
	public function editAction()
	{
		

		$leadId = $this->_request->getParam('id');
		$modelLead = new Application_Model_Lead();
		$lead = $modelLead->select($leadId);
		if($lead['lead_adm_id'] != Sid_Auth_Admin::getAdminId())
		{
			die('No access!');
		}
		$this->view->lead = $lead;
		
		$modelDspList = new Application_Model_Disposition_List();
		$this->view->dsp = $modelDspList->find();
		
		$this->view->pageTitle = 'Edit Lead';
		$this->view->bdcp = array(
			array(
				'url' => '/',
				'name' => 'Home'
			),
		
			array(
				'name' => 'Leads',
				'url' => '/lead'
			),
		
			array(
				'name' => 'Edit Lead',
		
			),

		);
	}
	

	public function addAction()
	{
		$modelDspList = new Application_Model_Disposition_List();
		$this->view->dsp = $modelDspList->find();
		
		
		
	}
	
	public function addPostAction()
	{
		$p = $this->_request->getParams();
		$modelLead = new Application_Model_Lead();
	
		$leadId = $modelLead->insert(
				array(
					'lead_date' => App_Date::toMysqlDate($p['lead_date']),
					'lead_agent' => $p['lead_agent'],
					'lead_agency' => $p['lead_agency'],
					'lead_phone' => $p['lead_phone'],
					'lead_email' => $p['lead_email'],
					'lead_notes' => $p['lead_notes'],
					'lead_poc' => $p['lead_poc'],
					'lead_fax' => $p['lead_fax'],
					'lead_address' => $p['lead_address'],
					'lead_date_in' => date("Y-m-d H:i:s"),
					'lead_type' => Application_Model_Lead::LEAD_NEW,
					'lead_adm_id' => Sid_Auth_Admin::getAdminId(),
					'lead_imp_id' => 0,
					'lead_dsp_id' => intval($p['lead_dsp_id']),
					'lead_stage' => Application_Model_Lead::LEAD_STAGE_NEW,
					'lead_status' => $p['lead_status'],
					'lead_due_date' => App_Date::toMysqlDate($p['lead_date']),
					'lead_call_attempt' => $p['lead_call_attempt'],
					'lead_module' => $p['s_module']
	
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
							'ntf_ref_id' => $leadId,
							'ntf_ref_type' => Application_Model_Notification::NOTE_REF_TYPE_LEAD
						)
	
				);
	
			}
		}
	
		die('done');
	}
}

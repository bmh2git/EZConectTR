<?php
class IndexController extends Zend_Controller_Action
{
	public function indexAction()
	{
			$this->view->pageTitle = 'Home';
			$session = Bootstrap::getSession();
			$condPrs = '';
			$condPrtPrs = '';
			if($session->admin['adm_lvl'] == 0)
			{
				
					$condPrs = ' AND prs_adm_id = '.	Sid_Auth_Admin::getAdminId();
					$condPrtPrs = ' AND prt_adm_id = '.	Sid_Auth_Admin::getAdminId();
			}
			
			if($session->admin['adm_lvl'] == 1)
			{

					
					if(isset($session->graph_filter_adm_id) && $session->graph_filter_adm_id > 0 )
					{
						$condPrs = ' AND prs_adm_id = '.	$session->graph_filter_adm_id;
						$condPrtPrs = ' AND prt_adm_id = '.	$session->graph_filter_adm_id;
						$this->view->selectedAdmin = $session->graph_filter_adm_id;
					}

			}
			
			if($session->admin['adm_lvl'] == 8)
			{
			
					
				
					$condPrs = ' AND prs_adm_id IN ( SELECT adm_id FROM adm_admin WHERE adm_adm_id = '.$session->admin['adm_id'].' ) ';
					$condPrtPrs = ' AND prt_adm_id IN ( SELECT adm_id FROM adm_admin WHERE adm_adm_id = '.$session->admin['adm_id'].' ) ';
					//$this->view->selectedAdmin = $session->graph_filter_adm_id;
				
			
			}
			
			
			
			/*
			 * TODO: after we determine all data that ez need for reports we will make a nice report model
			 */
			$modelProspect = new Application_Model_Prospect();
			$this->view->dspData = $modelProspect->fetchAll("
					SELECT COUNT(prs_id) AS value, dsp_name AS object
						 FROM prs_prospect
							INNER JOIN dsp_disposition ON ( prs_dsp_id = dsp_id )	
						WHERE prs_stage < ".Application_Model_Prospect::PRS_STAGE_PROMOTED." 
						$condPrs GROUP BY prs_dsp_id
					");
			
		
			$sqlPrt = "SELECT COUNT(prt_id) AS value, prt_type 
						 FROM prt_partner
						WHERE prt_ref_type = ".Application_Model_Import::IMP_TYPE_PROSPECTS."
					$condPrtPrs GROUP BY prt_type";
			
			$promotedPrt = $modelProspect->fetchAll($sqlPrt);
			$this->view->promotedPrt = array();
			if(count($promotedPrt))
			{
				foreach ($promotedPrt as $k => $data)
				{
					$promotedPrt[$k]['object'] = Application_Model_Promote::getPartnerTypeName($data['prt_type']);
				}
			}
			$this->view->promotedPrt =$promotedPrt;
			
			$sqlCalls = "SELECT COUNT(prs_id) AS calls, DATE_FORMAT(prs_date_in, '%m/%d/%Y') AS day 
									FROM prs_prospect
								WHERE prs_date_in BETWEEN CURDATE() - INTERVAL 30 DAY AND CURDATE() $condPrs
								GROUP BY day 
							ORDER BY prs_date_in
								
					";
			
			
			$this->view->calls = $modelProspect->fetchAll($sqlCalls);
			
			$sqlCallsByUser = "SELECT COUNT(prs_id) AS calls, adm_fullname AS name
				
			FROM prs_prospect
			INNER JOIN adm_admin ON ( prs_adm_id = adm_id )
			WHERE prs_date_in BETWEEN CURDATE() - INTERVAL 30 DAY AND CURDATE()
					$condPrs
			GROUP BY adm_id
			
			";
				
				
			$this->view->callsByUser = $modelProspect->fetchAll($sqlCallsByUser);
			
			$modelAdmList = new Application_Model_Admin_List();
			$this->view->adm = $modelAdmList->find();
	}
	
	public function detailsAction()
	{
		
	}
	
	public function setUserAction()
	{
		$id = $this->_request->getParam('id',0);
		$session = Bootstrap::getSession();
		$session->graph_filter_adm_id = $id;
		die('done');
	}
	
	
	
	
	

}
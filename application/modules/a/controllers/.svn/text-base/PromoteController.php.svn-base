<?php
class PromoteController extends Ez_Action
{
	public function addAction()
	{
		$p = $this->_request->getParams();
		$this->view->p = $p;
		
		if($p['type'] ==Application_Model_Import::IMP_TYPE_PROSPECTS)
		{
			$modelProspect = new Application_Model_Prospect();
			$data = $modelProspect->select($p['ref_id']);
			$this->view->data = $data;
			$this->view->tableKey = 'prs_';
		}
		
		if($p['type'] ==Application_Model_Import::IMP_TYPE_LEAD)
		{
			$modelLead = new Application_Model_Lead();
			$data = $modelLead->select($p['ref_id']);
			$this->view->data = $data;
			$this->view->tableKey = 'lead_';
		}
		
		
		if($p['promote-type'] == Application_Model_Promote::PROMOTE_TYPE_CLIENT_SECURE24)
		{
			return $this->_forward('client');
		}
		
		return $this->_forward('promote');
		
		
		if($p['type'] == Application_Model_Import::IMP_TYPE_PROSPECTS && $p['promote-type'] == Application_Model_Promote::PROMOTE_TYPE_HOME_INSPECTOR)
		{
			return $this->_forward('promote-prospect-home-inspector');
		}
		if($p['type'] == Application_Model_Import::IMP_TYPE_LEAD && $p['promote-type'] == Application_Model_Promote::PROMOTE_TYPE_HOME_INSPECTOR)
		{
			return $this->_forward('promote-lead-home-inspector');
		}
		
		if($p['type'] == Application_Model_Import::IMP_TYPE_PROSPECTS && $p['promote-type'] == Application_Model_Promote::PROMOTE_TYPE_INSURANCE_AGENT)
		{
			return $this->_forward('promote-prospect-home-inspector');
		}
		
		
		if($p['type'] == Application_Model_Import::IMP_TYPE_PROSPECTS && $p['promote-type'] == Application_Model_Promote::PROMOTE_TYPE_REAL_ESTATE)
		{
			return $this->_forward('promote-prospect-real-estate');
		}
		
		
		if($p['type'] == Application_Model_Import::IMP_TYPE_LEAD && $p['promote-type'] == Application_Model_Promote::PROMOTE_TYPE_REAL_ESTATE)
		{
			return $this->_forward('promote-lead-real-estate');
		}
		
		
		
		die('Can`t find this promoted path');
	}
	
	public function promoteAction()
	{
		$p = $this->_request->getParams();
		
		if($p['type'] ==Application_Model_Import::IMP_TYPE_PROSPECTS)
		{
			$modelProspect = new Application_Model_Prospect();
			$data = $modelProspect->select($p['ref_id']);
			$this->view->module = intval($data['prs_module']);
		}
		
		if($p['type'] ==Application_Model_Import::IMP_TYPE_LEAD)
		{
			$modelLead = new Application_Model_Lead();
			$data = $modelLead->select($p['ref_id']);
			$this->view->module = intval($data['lead_module']);
		}
		
		
	}
	
	public function clientAction()
	{
		
	}
	
	public function postAction()
	{
		$p = $this->_request->getParams();
		$files = array();
		if(isset($_FILES) && count($_FILES))
		{
			foreach ($_FILES as $name => $file)
			{
				$files[$name] = Ez_Interface_Files_Manager::addFormFile($file);
			}
		}
		if($p['type'] ==Application_Model_Import::IMP_TYPE_PROSPECTS)
		{
			$modelProspect = new Application_Model_Prospect();
			$modelProspect->update(array(
						'prs_stage' => Application_Model_Prospect::PRS_STAGE_PROMOTED,
						'prs_module' => intval($p['s_module'])
					), 'prs_id = '.intval($p['ref_id']));
		}
		
		if($p['type'] ==Application_Model_Import::IMP_TYPE_LEAD)
		{
			$modelLead = new Application_Model_Lead();
			$modelLead->update(array(
						'lead_stage' => Application_Model_Lead::LEAD_STAGE_PROMOTED,
						'lead_module' => intval($p['s_module'])
			), 'lead_id = '.intval($p['ref_id']));
		}
		

		$modelPartner = new Application_Model_Partner();
		$f = array(
			'prt_data' => Zend_Json::encode($p),
			'prt_file' => Zend_Json::encode($files),
			'prt_ref_type' => intval($p['type']),
			'prt_ref_id' => $p['ref_id'],
			'prt_type' => 	$p['promote-type'],
			'prt_adm_id' => Sid_Auth_Admin::getAdminId(),
			'prt_date_in' => date('Y-m-d- H:i:s')
		);
		$modelPartner->insert($f);
		$this->getResponse()->setRedirect($p['next_url']);
		return;
	}
	
	public function findNetworkAction()
	{
		$this->_helper->layout()->disableLayout();
		$this->view->return = array(
			'error' => 1,
			'message' => 'Unable to determinate!'	
		);
		$p = $this->_request->getParams();
		
		if(empty($p['address']) || strlen($p['address']) < 5)
		{
			$this->view->return = array(
					'error' => 1,
					'message' => 'Address is to short!'
			);
			return;
		}
		$geo = Ez_Interface_Geolocation::getCoords($p['address']);
		if($geo['errorCode'] > 0)
		{
			$this->view->return['message'] = $geo['message'];
			return;
		}
		
		$coords = $geo['results'];
		
		$distances = array();
		
		$modelNetworkList = new Application_Model_Network_List();
		$networks = $modelNetworkList->find();
		foreach ($networks['rows'] as $network)
		{
			$distance = Ez_Interface_Geolocation::getDistanceBetweenPointsNew($coords['lat'], $coords['lng'], $network['ntw_lat'], $network['ntw_lng']);
			$distances[$distance] = $network['ntw_name'].' at '.$distance .' miles';

		}
		$minimalDistance = min(array_keys($distances));
		$closeCenter =  $distances[$minimalDistance];	
		if($minimalDistance <= 80 )
		{
			$this->view->return = array(
					'error' => 0,
					'message' => 'This address is <b>IN</b> network. Closest center is  '.$closeCenter.'!',
					'js' => "$.uniform.update($('#in_network').prop('checked',true));"
			);
		}
		else 
		{
			$this->view->return = array(
					'error' => 0,
					'message' => 'This address is <b>NOT IN</b> network. Closest center is  '.$closeCenter.'!',
					'js' => "$.uniform.update($('#not_in_network').prop('checked',true));"
			);
		}	
	
	}
	
}
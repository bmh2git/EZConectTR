<?php
class PartnerController extends Ez_Action
{
	public function indexAction()
	{
		$sqlParams = array('adm_id' => Sid_Auth_Admin::getAdminId());
		if(Sid_Auth_Admin::getAdminLvl() == Sid_Auth_Admin::SUPER_USER)
		{
			$sqlParams = array('with_adm_name' => true);
			$this->view->with_adm_name = true;
		}
		$modelPrtList = new Application_Model_Partner_List($sqlParams);
		$this->view->prt = $modelPrtList->find();
	}
	
	public function detailsAction()
	{
		$id = $this->_request->getParam('id');
		$modelPartner = new Application_Model_Partner();
		$prt = $modelPartner->select($id);
		
		if(!isset($prt['prt_id']))
		{
			die('invalid partner entry');
		}
		if($prt['prt_adm_id'] != Sid_Auth_Admin::getAdminId())
		{
			die('You don`t have access to access this entry!');
		}
		
		$this->view->prt = $prt;
		
		$modelDspList = new Application_Model_Disposition_List();
		$this->view->dsp = $modelDspList->find();
		
		//daca avem lead
		if($prt['prt_ref_type'] == Application_Model_Import::IMP_TYPE_PROSPECTS)
		{
			$model = new Application_Model_Prospect();
			$this->view->prs = $model->select($prt['prt_ref_id']);
			$this->view->tabTitle = 'Prospect';
		}
		
		if($prt['prt_ref_type'] == Application_Model_Import::IMP_TYPE_LEAD)
		{
			$model = new Application_Model_Lead();
			$this->view->tabTitle = 'Lead';
			$this->view->lead = $model->select($prt['prt_ref_id']);
		}

	}
}
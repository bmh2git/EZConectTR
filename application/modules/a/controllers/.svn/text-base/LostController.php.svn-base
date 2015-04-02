<?php
class LostController extends Ez_Action
{
	public function indexAction()
	{
		$p = $this->_request->getParams();
		
		if(Sid_Auth_Admin::getAdminLvl() == Sid_Auth_Admin::SUPER_USER)
		{
			$this->view->with_adm_name = true;
		}
		else 
		{
			$p['adm_id'] = Sid_Auth_Admin::getAdminId();
		}
		$modelList = new Application_Model_Prospect_List(array_merge(array( 'status' => Application_Model_Imported::TYPE_LOST), $p));
		$this->view->data = $modelList->find();
	}
}
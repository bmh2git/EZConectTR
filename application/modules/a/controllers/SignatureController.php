<?php
class SignatureController extends Ez_Action
{
	public function indexAction()
	{
		
		
		$modelScrList = new Application_Model_Signature_List(array('adm_id' => Sid_Auth_Admin::getAdminId()));
		$this->view->data = $modelScrList->find();

		
	}
	
	public function addAction()
	{
		if($this->_request->isPost())
		{
			$p = $this->_request->getParams();
			if(empty($p['name']))
			{
				//die('You have to chose a name for your signature');
			}
			$modelSignature = new Application_Model_Signature();
			$modelSignature->insert(array(
					'sgn_name' => $p['name'],
					'sgn_adm_id' => Sid_Auth_Admin::getAdminId(),
					'sgn_content' => $p['signature']
			
			));
			header('Location: /signature');
			die();
			
		}
	}
	
	public function postAction()
	{
		
	}
	
	public function deleteAction()
	{
		$id = $this->_request->getParam('id',0);
		$modelSgn = new Application_Model_Signature();
		$sgn = $modelSgn->select($id);
		if($sgn['sgn_adm_id'] != Sid_Auth_Admin::getAdminId())
		{
			die('no access');
		}
		
		$sgn = $modelSgn->delete($id);
		die();
	}
	
	public function editAction()
	{
		$id = $this->_request->getParam('id',0);
		$modelSgn = new Application_Model_Signature();
		$this->view->sgn  = $modelSgn->select($id);
		
		if($this->view->sgn['sgn_adm_id'] != Sid_Auth_Admin::getAdminId())
		{
			die('You don`t have access here');
		}
		
		if($this->_request->isPost())
		{
			$p = $this->_request->getParams();
			if(empty($p['name']))
			{
				//die('You have to chose a name for your signature');
			}
			$modelSgn->update(array(
					'sgn_name' => $p['name'],
					'sgn_content' => $p['signature']
						
			), 'sgn_id = '.$id);
			header('Location: /signature');
			die();
				
		}

		
	}
	
}
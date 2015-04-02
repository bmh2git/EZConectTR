<?php
class LoginController extends Zend_Controller_Action
{
	
	public function indexAction()
	{
		$this->_helper->layout()->disableLayout();
		

		
		if($this->_request->isPost())
		{
		
			$this->view->data = $this->_request->getParams();
			$params = $this->_request->getParams();

			if(isset($params['username']) && isset($params['password']))
			{

				Sid_Auth_Admin::login($params['username'], $params['password']);
			}
			else 
			{

			}
			
		}
		else 
		{
			//echo "Ana are mere!";
		}
	}
	public function ajaxAction()
	{
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(TRUE);
		
		$p = $this->_request->getParams();
		$resp['submitted_data'] = $p;
		$resp['login_status'] = 'invalid';
		
		if(isset($p['adm_user']) && isset($p['adm_pass']))
		{
		
			if(Sid_Auth_Admin::login($p['adm_user'], $p['adm_pass']))
			{
				$resp['login_status'] = 'success';
				$resp['redirect_url'] = '/';
			}
		}
		else
		{
		
		}
		
		echo json_encode($resp);
		return;
	}
}
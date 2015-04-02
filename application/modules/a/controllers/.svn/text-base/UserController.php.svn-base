<?php
class UserController extends Zend_Controller_Action
{
	
	public function init()
	{
		$session = Bootstrap::getSession();
		if($session->admin['adm_lvl'] < 1)
		die('restricted access!');
	}
	public function indexAction()
	{
		$p = $this->_request->getParams();
		$this->view->pageTitle = 'Users';
		$this->view->bdcp = array(
			array(
			'url' => '/',
			'name' => 'Home'
		),
			
			array(
				'name' => 'Users'
			),
			
		);
		$admActive = isset($p['inactive']) ? 0 : 1;
		$modelAdminList = new Application_Model_Admin_List(array('adm_lvl' => 0, 'adm_active' => $admActive));
		$this->view->user = $modelAdminList->find();
		
		if(isset($p['inactive']))
		{
			$this->view->active = $modelAdminList->getActiveCount();
		}
		else 
		{
			$this->view->inactive = $modelAdminList->getInactiveCount();
		}
		
		$this->view->p = $p;
	}
	
	public function deactivateAction()
	{
		$admId = $this->_request->getParam('id');
		$modelAdmin = new Application_Model_Admin();
		$adm = $modelAdmin->select($admId);
		if(!isset($adm['adm_id']))
		{
			die('I can`t find the user!');
		}
		$modelAdmin->update(array(
			'adm_active' => 0
		), 'adm_id = '.$adm['adm_id']);
		die('done');
	}
	
	
	public function activateAction()
	{
		$admId = $this->_request->getParam('id');
		$modelAdmin = new Application_Model_Admin();
		$adm = $modelAdmin->select($admId);
		if(!isset($adm['adm_id']))
		{
			die('I can`t find the user!');
		}
		$modelAdmin->update(array(
			'adm_active' => 1
		), 'adm_id = '.$adm['adm_id']);
		die('done');
	}
	
	
	public function addAction()
	{
		$this->view->pageTitle = 'New User';
		$this->view->bdcp = array(
			array(
				'url' => '/',
				'name' => 'Home'
			),
				
			array(
				'name' => 'Users',
				'url' => '/user'
			),
			
			array(
				'name' => 'New user',
				
			),
			
			
				
		);
	}
	
	public function postAction()
	{
		$p = $this->_request->getParams();
		$this->_helper->layout()->disableLayout();
		$err = '';
		if(!isset($p['full_name']) || empty($p['full_name']))
		{
			$err = 'You didn`t fill the Full name';	
		}
		
		if(empty($err) && (!isset($p['email']) || empty($p['email'])))
		{
			$err = 'You didn`t fill the email address';	
		}
		
		if(empty($err) && !filter_var($p['email'], FILTER_VALIDATE_EMAIL))
		{
			$err = 'You didn`t provide a valid email address';	
		}
		
		if(empty($err) && (!isset($p['email']) || empty($p['user'])))
		{
			$err = 'You didn`t chose a username for '.$p['full_name'];
		}
		
		if(empty($err) && (!isset($p['pass']) || empty($p['pass'])))
		{
			$err = 'You didn`t provide a password for '.$p['full_name'];	
		}
		elseif(empty($err) && preg_match("/\\s/", $p['pass']) )
		{
			$err = 'The password for '.$p['full_name'].' cannot contain spaces!';
		}
		elseif (empty($err) && strlen($p['pass']) < 4)
		{
			$err = 'The password for '.$p['full_name'].' is too short!';
		}
		
		if(empty($err) && $p['pass'] != $p['re-pass'])
		{
			$err = "Passwords don`t match!";
		}
		
		$modelAdmin = new Application_Model_Admin();
		
		if(empty($err))
		{
			if($modelAdmin->emailExist($p['email']))
			{
				$err = "The email address is already in the system!";
			}
			elseif ($modelAdmin->userExist($p['user']))
			{
				$err = "The username is already in the system!";
			}
				
		}
		
		
		if(empty($err))
		{
			$modelAdmin->insert(array(
				'adm_user' => $p['user'],
				'adm_pass' => $p['pass'],
				'adm_fullname' => $p['full_name'],
				'adm_lvl' => 0,
				'adm_email' => $p['email']
				
			));
			$this->view->data = $p;
		}
		else 
		{
			$this->view->error = $err;
		}

	}
	
	public function changePasswdAction()
	{
		die('done');
	}
	
	public function detailsAction()
	{
		$admId =$this->_request->getParam('id');
		$modelAdmin = new Application_Model_Admin();
		
		$user = $modelAdmin->select($admId);
		if(!isset($user['adm_id']))
		{
			die('I can`t find this user!');
		}
		
		$this->view->user = $user;
		
		$this->view->pageTitle = 'About '.$user['adm_fullname'];
		$this->view->bdcp = array(
			array(
				'url' => '/',
				'name' => 'Home'
			),
		
			array(
				'name' => 'Users',
				'url' => '/user'
			),
		
			array(
				'name' => 'About '.$user['adm_fullname'],
		
			),
		
		
		);
	}
}
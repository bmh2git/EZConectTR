<?php
class ProfileController extends Ez_Action
{
	public function indexAction()
	{
		
	}
	
	public function changePasswordAction()
	{
		
	}
	
	public function changePictureAction()
	{
		$this->view->pageTitle = 'Edit Profile';
		$this->view->bdcp = array(
				array(
						'url' => '/',
						'name' => 'Home'
				),
		
				array(
						'name' => 'Profile',
						'url' => '/profile'
				),
		
				array(
						'name' => 'Change Picture',
		
				),
		
		);
		$this->view->done = false; 
		if($this->_request->isPost())
		{
			if(!empty($_FILES['file']))
			{
				$file = $_FILES['file'];
			}
			$this->view->error = '';
			if(!in_array(Ez_Interface_Files_Manager::getFileExtension($file['name']), array('jpg')))
			{
				$this->view->error = 'Invalid format! The profile image must be in jpg format!';
			}
			if(empty($this->view->error))
			{
				move_uploaded_file($file['tmp_name'], DATA_DIR."/files/profile/".Sid_Auth_Admin::getAdminId().".jpg");
				$this->view->done = true;
			}
			
		}
		
		$modelAdm = new Application_Model_Admin();
		$this->view->adm = $modelAdm->select(Sid_Auth_Admin::getAdminId());
	}
	
	public function editAction()
	{
		$this->view->pageTitle = 'Edit Profile';
		$this->view->bdcp = array(
				array(
						'url' => '/',
						'name' => 'Home'
				),
		
				array(
						'name' => 'Profile',
						'url' => '/profile'
				),
		
				array(
						'name' => 'Edit Profile',
		
				),
		
		);
		
		$modelAdm = new Application_Model_Admin();
		$this->view->adm = $modelAdm->select(Sid_Auth_Admin::getAdminId());
	}
	
	public function postEditAction()
	{
		$p = $this->_request->getParams();
		if(empty($p['full_name']))
		{
			die('You didn`t provide the full name');
		}
		
		if(strlen($p['full_name']) < 5)
		{
			die('If think that your full name is too short!');
		}

		if(empty($p['phone_number']))
		{
			die('Cmon ... just give me your number!');
		}
		
		if(!preg_match("/^[0-9]+/", $p['phone_number']))
		{
			die('Please use inly numbers into phone number!');
		}
		
		$modelAdm = new Application_Model_Admin();
		$modelAdm->update(array(
									'adm_fullname' => $p['full_name'],
									'adm_phone' => $p['phone_number']
						), 'adm_id = '.Sid_Auth_Admin::getAdminId());
		
		die('done');
		
	}
	
	public function postChangePasswordAction()
	{
		$p = $this->_request->getParams();
		$err = '';
		$modelAdmin = new Application_Model_Admin();
		$adm = $modelAdmin->select(Sid_Auth_Admin::getAdminId());
		
		if(Sid_Auth_Admin::hashPassword($p['old_pass']) != $adm['adm_pass'])
		{
			$err = 'Your old password dosen`t match the current password!';
		}
		
		if(empty($err) && (!isset($p['pass']) || empty($p['pass'])))
		{
			$err = 'You didn`t provide a password ';
		}
		elseif(empty($err) && preg_match("/\\s/", $p['pass']) )
		{
			$err = 'The new password cannot contain spaces!';
		}
		elseif (empty($err) && strlen($p['pass']) < 4)
		{
			$err = 'The new password  is too short!';
		}
		
		if(empty($err) && $p['pass'] != $p['re-pass'])
		{
			$err = "Passwords don`t match!";
		}
		
		if(!empty($err))
		{
			echo $err;
			die();
		}
		
		$modelAdmin->update(array('adm_pass' => Sid_Auth_Admin::hashPassword($p['pass'])), 'adm_id = '.Sid_Auth_Admin::getAdminId());
		
		die('done');
	}
}
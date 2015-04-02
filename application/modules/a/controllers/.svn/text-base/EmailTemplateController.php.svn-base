<?php
class EmailTemplateController extends Ez_Action
{
	public function indexAction()
	{
		$this->view->pageTitle = 'Email Templates';
		$this->view->bdcp = array(
				array(
						'url' => '/',
						'name' => 'Home'
				),
					
				
				array(
						'name' => 'Email templates',
				)
					
		);
		
		$session = Bootstrap::getSession();
		if(!isset($session->module))
		{
			$session->module = 0;
		}
		
		$this->view->module = $session->module;
		
		
		$modelEmtList = new Application_Model_Email_Template_List(array('module' => $this->view->module));
		$this->view->emt = $modelEmtList->find();
	}
	
	public function addAction()
	{
		$this->view->pageTitle = 'Add New Email Templates';
		$this->view->bdcp = array(
				array(
						'url' => '/',
						'name' => 'Home'
				),
					
		
				array(
						'name' => 'Email templates',
						'url' => '/email-template/',
				),
				array(
						'name' => 'New Email templates',
				)
					
		);
	}
	
	public function editAction()
	{
		$id = $this->_request->getParam('id',0);
		$modelEmt = new Application_Model_Email_Template();
		$emt = $modelEmt->select($id);
		if(!isset($emt['emt_id']))
		{
			die('Unable to find email template!');
		}
		
		$this->view->emt = $emt;
		
		$this->view->pageTitle = 'Edit Email Templates';
		$this->view->bdcp = array(
				array(
						'url' => '/',
						'name' => 'Home'
				),
					
	
				array(
						'name' => 'Email templates',
						'url' => '/email-template/',
				),
				array(
						'name' => 'Edit Email templates',
				)
					
		);
	}
	
	public function postAction()
	{
		$p = $this->_request->getParams();
		
		if(!isset($p['emt_name']) || empty($p['emt_name']))
		{
			die('You must enter the Template name');
		}
		
		
		if(!isset($p['emt_subject']) || empty($p['emt_subject']))
		{
			die('You must enter the Subject');
		}
		
		if(!isset($p['emt_message']) || empty($p['emt_message']))
		{
			die('You must enter the Message');
		}
		
		$f = array(
			'emt_name' => $p['emt_name'],
			'emt_subject' => $p['emt_subject'],
			'emt_message' => $p['emt_message'],
			'emt_module' => $p['emt_module']
				
		);
		
		$modelEmt = new Application_Model_Email_Template();
		if(isset($p['id']))
		{
			$modelEmt->update($f, 'emt_id = '.intval($p['id']));
		}
		else 
		{
			$modelEmt->insert($f);
		}
		
		die('done');
	}
	
	public function loadDataAction()
	{
		$id = $this->_request->getParam('id',0);
		$modelEmt = new Application_Model_Email_Template();
		$emt = $modelEmt->select($id);
		echo Zend_Json::encode($emt);
		die();
	}
	
	public function sendAction()
	{
		$p = $this->_request->getParams();

		if(!isset($p['to']))
		{
			die('Where do you want to send this email?');
		}
		$validator = new Zend_Validate_EmailAddress();
		if(!$validator->isValid($p['to']))
		{
			die('Invalid email address!');
		}
		$mail = new Zend_Mail();
		
		$mail->addTo($p['to']);
		$mail->setFrom('ezconect@sidmedia.ro', 'EzConect -  Testing');
		$mail->setReplyTo('info@osconnects.com', null);
		$mail->setSubject($p['subject']);
		$mail->setBodyText($p['message']);
		
		if(isset($p['sgn']) && $p['sgn'] > 0)
		{
			$modelSgn = new Application_Model_Signature();
			$sgn = $modelSgn->select($p['sgn']);
			$mail->setBodyHtml("<div>".nl2br($p['message'])."</div><div>".$sgn['sgn_content']."</div>");
		}
		else 
		{
			
		}
		
		$mail->send(Ez_Interface_Mail_Settings::getSmtpTransport());
		$modelEms = new Application_Model_Email_Sent();
		$modelEms->insert(array(
			'ems_to' => $p['to'],
			'ems_adm_id' => Sid_Auth_Admin::getAdminId(),
			'ems_subject' => $p['subject'],
			'ems_content' => $p['message'],
			'ems_date_in' => date('Y-m-d- H:i:s')
		));
		die('done');
	}
	
	public function getHtmlOptionsAction()
	{
		$this->_helper->layout()->disableLayout();
		$mdl = $this->_request->getParam('id',0);
		$this->view->module = $mdl;
		$modelEmlList = new Application_Model_Email_Template_List(array('module' => $mdl));
		$this->view->eml = $modelEmlList->find();
	}
	
	public function deleteAction()
	{
		$emtId = $this->_request->getParam('emt_id',0);
		$modelEmt = new Application_Model_Email_Template();
		$modelEmt->delete($emtId);
		die('done');
	}
	
	
}
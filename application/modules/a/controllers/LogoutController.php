<?php
class LogoutController extends Zend_Controller_Action
{
	public function init()
	{
		Sid_Auth_Admin::logOut();
		header("location:/");
		die();
	}
	public function indexAction()
	{
		
	}
}
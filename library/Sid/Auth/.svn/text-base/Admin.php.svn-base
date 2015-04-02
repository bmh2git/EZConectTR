<?php
class Sid_Auth_Admin
{
	const SUPER_USER = 1;
	
	public static function login($user, $pass)
	{
		$modelAdmin = new Application_Model_Admin();
		$row = $modelAdmin->selectLogin($user, $pass);
		if(isset($row['adm_id']) && $row['adm_id'] > 0)
		{
			$session = Bootstrap::getSession();
			$session->admin = $row;
			header('Location:/index');
			die();
			return true;

		}
		else
		{
			return false;
		}
	}
	
	
	public static function getAdminId()
	{
		$session = Bootstrap::getSession();
		return $session->admin['adm_id'];
	}
	
	public static function getAdminLvl()
	{
		$session = Bootstrap::getSession();
		return $session->admin['adm_lvl'];
	}
	
	public static function hashPassword($pwd)
	{
		return $pwd;
	}
	
	public static function logOut()
	{
		$session = Bootstrap::getSession();
		unset($session->admin);
	}
}
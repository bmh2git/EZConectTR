<?php
class Sid_Auth_Client
{
	public static function login($user, $pass)
	{
		$modelClient = new Application_Model_Client();
		$row = $modelClient->selectLogin($user, $pass);
		if(isset($row['cli_id']) && $row['cli_id'] > 0)
		{
			$session = Bootstrap::getSession();
			$session->client = $row;
			header("location: /");
			die();
		}
		else
		{
			
		}
	}
	
	public static function hashPassword($password)
	{
		return $password;
	}
	
	public static function forceLogin($cliId, $cliName)
	{
			$session = Bootstrap::getSession();
			$session->client = array('cli_id'=>$cliId, 'cli_name' => $cliName);
	}
	
	
	public static function getClientId()
	{
		$session = Bootstrap::getSession();
		return $session->client['cli_id'];
	}
	
	public static function logOut()
	{
		$session = Bootstrap::getSession();
		unset($session->client);
	}
	
	public static function compareClientPassword($password, $cliId)
	{
		$modelClient = new Application_Model_Client();
		return $modelClient->isClientPassword($password, $cliId);
	}
	
	public static function changePassword($password, $cliId)
	{
		$modelClient = new Application_Model_Client();
		$modelClient->update(array("cli_password" => $password), "cli_id = $cliId");
	}
}
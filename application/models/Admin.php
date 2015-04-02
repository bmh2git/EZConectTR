<?php
class Application_Model_Admin extends Sid_Db_Row
{
	
	var $tableName = 'adm_admin';
	var $pkId = 'adm_id';
	
	public function selectLogin($user, $pass)
	{
		return $this->fetch("SELECT 
							*
								FROM adm_admin
									WHERE
										adm_active = 1
										AND adm_user = :adm_user
									AND adm_pass = :adm_pass",array('adm_user' => $user,"adm_pass" => $pass));
	}
	
	public function emailExist($email)
	{
		$r = $this->fetch("SELECT * FROM adm_admin WHERE LOWER(adm_email) = ?", array(strtolower($email)));
		return isset($r['adm_id']);
	}
	
	public function userExist($user)
	{
		$r = $this->fetch("SELECT * FROM adm_admin WHERE LOWER(adm_user) = ?", array(strtolower($user)));
		return isset($r['adm_id']);
	}
	
	
	
}
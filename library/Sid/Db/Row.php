<?php

class Sid_Db_Row extends Sid_Db
{


	public function insert($data, $table = '')
	{
		if($table == '')
		{
			$table = $this->tableName;
		}
		$this->db->insert($table, $data);
		return $this->db->lastInsertId();
	}
	
	public function select($pkId)
	{
		$sql = "SELECT * FROM $this->tableName WHERE $this->pkId = $pkId";
		$r =  $this->db->fetchAll("SELECT * FROM $this->tableName WHERE $this->pkId = ?",array($pkId));
		if(isset($r[0]))
		{
			return $r[0];
		}
		return array();
		
	}
	
	public function delete($pkId)
	{
		$this->db->query("DELETE FROM $this->tableName WHERE $this->pkId = ?", array($pkId));
	}
	
}

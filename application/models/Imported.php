<?php
class Application_Model_Imported extends Sid_Db_Row
{
	const IMPD_TYPE_NEW = 0x00;

	const TYPE_DEFAULT = 0x01;
	const TYPE_LOST = 0x02;
	const TYPE_PROMOTED = 0x03;
	
	
	static $status = array(
		self::TYPE_DEFAULT => 'Prospect',
		self::TYPE_LOST => 'Lost',
		self::TYPE_PROMOTED => 'Promoted',
	);
	
	static $leadStatus = array(
		self::TYPE_DEFAULT => 'Prospect',
		self::TYPE_LOST => 'Lost',
		self::TYPE_PROMOTED => 'Promoted',
	);
	
	static $grades = array(
		0 => 'None',
		1 => 'A',
		2 => 'B',
		3 => 'C',
	);
	
	static $callAttempt = array(
		1 => 'First',
		2 => 'Second',
		3 => 'Third'
	);
	
	
	var $tableName = "impd_imported";
	var $pkId = "impd_id";
	
	public function getNextImported($impId)
	{
		$sql = "SELECT * FROM impd_imported WHERE impd_imp_id = ? AND impd_flag = ".self::IMPD_TYPE_NEW."  ORDER BY impd_id ASC LIMIT 1";
		return $this->fetch($sql, array($impId));
	}
	
}
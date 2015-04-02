<?php
class Application_Model_Lead extends Sid_Db_Row
{
	const LEAD_NEW = 0x00;
	const LEAD_IMPORTED = 0x01;
	
	const LEAD_STAGE_NEW = 0x01;
	const LEAD_STAGE_PROMOTED = 0x02;
	
	
	
	var $tableName = "lead_lead";
	var $pkId = "lead_id";

}
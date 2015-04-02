<?php
class Application_Model_Notification extends Sid_Db_Row
{
	const STS_TYPE_NEW = 0x00;
	const STS_TYPE_NOTIFIED = 0x01;
	
	const STS_TYPE_DONE = 0x09;
	
	const NOTE_REF_TYPE_LEAD = 0x01;
	const NOTE_REF_TYPE_PROSPECT = 0x02;
	
	var $tableName = "ntf_notification";
	var $pkId = "ntf_id";
	
	
}
<?php
class Application_Model_Script extends Sid_Db_Row
{
	const SCRIPT_TYPE_OPENING = 0x01;
	const SCRIPT_TYPE_CLOSING = 0x02;
	const SCRIPT_TYPE_OBJECTIONS = 0x03;
	const SCRIPT_TYPE_ASP = 0x04;
	
	const SCRIPT_SECTION_BOTH = 1;
	const SCRIPT_SECTION_PROSPECT = 2;
	const SCRIPT_SECTION_LEAD = 3;
	
	
	var $tableName = "scr_script";
	var $pkId = "scr_id";

}
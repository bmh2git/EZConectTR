<?php
class Application_Model_Library extends Sid_Db_Row
{
	const LBR_TYPE_FOLDER  = 1;
	const LBR_TYPE_FILE  = 2;
	
	var $tableName = "lbr_library";
	var $pkId = "lbr_id";
	
	
}
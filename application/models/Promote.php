<?php
class Application_Model_Promote extends Sid_Db_Row
{

	const PROMOTE_TYPE_HOME_INSPECTOR = 0x01;

	const PROMOTE_TYPE_REAL_ESTATE = 0x02;
	
	const PROMOTE_TYPE_INSURANCE_AGENT = 0x03;
	
	const PROMOTE_TYPE_LARGE_ORGANIZATION = 0x04;
	
	const PROMOTE_TYPE_CLIENT_OSC = 11;
	const PROMOTE_TYPE_CLIENT_SECURE24 = 12;
	
	public static function getPartnerTypeName($type)
	{

		if($type == self::PROMOTE_TYPE_HOME_INSPECTOR)
		{
			return 'Home Inspector';
		}
		
		if($type == self::PROMOTE_TYPE_REAL_ESTATE)
		{
			return 'Real Estate';
		}
		
		if($type == self::PROMOTE_TYPE_INSURANCE_AGENT)
		{
			return 'Insurance Agent';
		}
		
		if($type == self::PROMOTE_TYPE_LARGE_ORGANIZATION)
		{
			return 'Large Organization';
		}
		
		if($type == self::PROMOTE_TYPE_CLIENT_OSC)
		{
			return 'OSC Client';
		}
		
		if($type == self::PROMOTE_TYPE_CLIENT_SECURE24)
		{
			return 'Secure24 Client';
		}
		
		
		
		
		return '';
		
	}

}
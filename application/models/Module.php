<?php
class Application_Model_Module
{
	const MODULE_TYPE_BOTH = 0;
	const MODULE_TYPE_OSC = 1;
	const MODULE_TYPE_SECURE24 = 2;
	
	const MODULE_TYPE_WITH_BOTH = 10;
	const MODULE_TYPE_ONLY_OSC = 11;
	const MODULE_TYPE_ONLY_SECURE24 = 12;
	
	
	
	static $modules = array(
				self::MODULE_TYPE_BOTH => 'Both',
				self::MODULE_TYPE_OSC => 'OSC',
				self::MODULE_TYPE_SECURE24 => 'Secure24',
				
		);
	
	static $extendedModules = array(
			self::MODULE_TYPE_WITH_BOTH => 'With Both',
			self::MODULE_TYPE_ONLY_OSC => 'Only OSC',
			self::MODULE_TYPE_ONLY_SECURE24 => 'Only Secure24',
	);
	
	public static function getModulesArray()
	{
		return self::$modules;
	}
	
	public static function getModuleName($moduleId)
	{
		if(isset(self::$modules[$moduleId]))
		{
			return self::$modules[$moduleId];
		}
		
		if(isset(self::$extendedModules[$moduleId]))
		{
			return self::$extendedModules[$moduleId];
		}
		
	}
}
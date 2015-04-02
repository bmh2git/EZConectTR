<?php
class Ez_Interface_Form extends Ez_Interface
{
	public static function getFormValue($value, $values)
	{
		$value = self::sanitizeInput($value,"_");

		if(isset($values[$value]))
		{
			return $values[$value];
		}
	}
}
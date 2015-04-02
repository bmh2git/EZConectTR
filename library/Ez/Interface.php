<?php
class Ez_Interface
{
	public static function sanitizeInput($string, $separator = "-")
	{
		$string = trim($string);
		if(empty($string)) return $separator;
		$string = preg_replace("/[^a-zA-Z0-9]/"," ",$string);
		$string = trim($string);
		while (strpos("  ",$string)) {
			$string = str_replace("  "," ",$string);
		}
		$string = str_replace(" ",$separator,$string);

		return trim( strtolower( $string ) );
	}
}
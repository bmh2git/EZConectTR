<?php
class App_Date
{
	
	static  $roMonthDates = array(
		1 => 'Ianuarie',
		2 => 'Februarie',
		3 => 'Martie',
		4 => 'Aprilie',
		5 => 'Mai',
		6 => 'Iunie',
		7 => 'Iulie',
		8 => 'August',
		9 => 'Septembrie',
		10 => 'Octombrie',
		11 => 'Noiembrie',
		12 => 'Decembrie',
	);
	
	public static function toMysqlDate($inpDate)
	{
		$inpDate = trim($inpDate);
		if(!preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})/',$inpDate,$b))
		{
			return false;
			die('invalid date format!');
		}


		return $b[3]."-".$b[1]."-".$b[2];

	}
	
	public static function fromMysqlDate($inpDate, $options=array())
	{
		if(!preg_match('/^([0-9]{4})-([0-9]{2})-([0-9]{2})/',$inpDate,$b))
		{
			return false;
		}
		
		if(isset($options['delimiter']))
		{
			 return $b[2]."$options[delimiter]".$b[3]."$options[delimiter]".$b[1];
		}

		return $b[2]."/".$b[3]."/".$b[1];
	}
	
	public static function fromMysqlDateTime($inpDate)
	{
		if(!preg_match("/^(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})$/",$inpDate,$b))
		{

			return false;
		}

		return $b[3]."/".$b[2]."/".$b[1]." ".$b[4].":".$b[5];
	}
	
	public static function isMysqlDate($date)
	{
		$date = str_replace("'", "", $date);
		if($date == '0000-00-00')
		{
			return false;
		}
		
		return preg_match('/^([0-9]{4})-([0-9]{2})-([0-9]{2})/',$date);

	}
}
<?php
class App_Word
{
		public static function renderText($text)
		{
			$text = self::forceDecode($text);
			return nl2br($text);
		}

		public static function forceDecode($text)
		{
			$text =  preg_replace('/\\\u([0-9]+)/', '&#x$1;',$text);
			return $text;
		}
		
		public static function resume($text, $size)
		{
			$text = self::forceDecode($text);
			if(strlen($text) <= $size)
			{
				return $text;
			}
			
			$text = substr($text, 0, $size);
			$continue = true;
			while ($continue && strlen($text) > 1)
			{
				$textLocation = strlen($text) - 1;
				if($text{$textLocation} != ' ')
				{
					$size--;
					$text = substr($text, 0, $size);
				}
				else 
				{
					$continue = false;
				}
			}
			return $text."...";
		}
}
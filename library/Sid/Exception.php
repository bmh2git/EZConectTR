<?php
class Sid_Exception extends Exception
{
	public function __construct($params)
	{
		
		if($_SERVER['REMOTE_ADDR'] != '127.0.0.1')
		{
			$mail = new Zend_Mail();
			$mail->addTo("contact@elitetours.ro");
			$mail->addTo("mdh@sidmedia.eu");
			$mail->setFrom('system@elitetours.ro', 'Eroare Elite');
			$mail->setSubject("Eroare Elite");
			$message = debug_backtrace(3);
			$message .= print_r($params, true);
			
			header("HTTP/1.1 301 Moved Permanently");
			header("Location: /");
			die();
		}
		print_r($params);
		print_r($this);
		echo $this->getMessage();
		die();
    	
		
		//$mail->send($transport);
		
		
		
		//$mail->send($transport);
		header("Location:/");
	}
}
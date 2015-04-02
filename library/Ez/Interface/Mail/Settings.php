<?php
class Ez_Interface_Mail_Settings
{
	public static function getSmtpTransport()
	{
		$mail = new Zend_Mail();
		$config = array('auth' => 'login',
				'username' => 'ezconect@sidmedia.ro',
				'password' => 'stefanaremere#123',
				'port' => 26
		);
		$transport = new Zend_Mail_Transport_Smtp('mail.sidmedia.ro', $config);
	
		return $transport;
	}
}
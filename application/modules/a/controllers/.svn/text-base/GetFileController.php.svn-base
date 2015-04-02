<?php
class GetFileController extends Ez_Action
{
	public function imageAction()
	{
		$this->_helper->layout()->disableLayout();
		$file = $this->_request->getParam('file','');
		$default = $this->_request->getParam('default','default.jpg');
		if(empty($file))
		{
			header('Content-type: image/jpg');
			echo file_get_contents(DATA_DIR."/files/".$default);
			die();
		}
		$ext = Ez_Interface_Files_Manager::getFileExtension($file);
		if(!in_array($ext, array('jpg', 'png')))
		{
			header('Content-type: image/jpg');
			echo file_get_contents(DATA_DIR."/files/".$default);
			die();
		}
		
		if(!file_exists(DATA_DIR."/files".$file))
		{
			header('Content-type: image/jpg');
			echo file_get_contents(DATA_DIR."/files/".$default);
			die();
		}
		switch( $ext ) {
			case "gif": $ctype="image/gif"; break;
			case "png": $ctype="image/png"; break;
			case "jpeg":
			case "jpg": $ctype="image/jpg"; break;
			default:
		}
		
		header('Content-type: ' . $ctype);
		
		echo file_get_contents(DATA_DIR."/files".$file);
		die();
	}
	
	public function w9Action()
	{
		$id = $this->_request->getParam('id',0);
		$modelPrt = new Application_Model_Partner();
		$prt = $modelPrt->select($id);
		if(!isset($prt['prt_id']))
		{
			die('No W9 was found!');
		}
		
		if($prt['prt_adm_id'] != Sid_Auth_Admin::getAdminId())
		{
			die('You have no access to this file!');
		}
		$files = Zend_Json::decode($prt['prt_file']);
		if(isset($files['w-9']))
		{
			$fileToSend = $id.".".Ez_Interface_Files_Manager::getFileExtension($files['w-9']);
			Ez_Interface_Files_Manager::sendFile(DATA_DIR."/files".$files['w-9'], $fileToSend);

		}
		
		if(!isset($prt['prt_id']))
		{
			die('No W9 was found!');
		}
	}
	
	public function libraryAction()
	{
		$id = $this->_request->getParam('id',0);
		$modelLibrary = new Application_Model_Library();
		$lbr = $modelLibrary->select($id);
		
		if(!isset($lbr['lbr_id']))
		{
			throw new Ez_Exception('Invalid file');
		}
		Ez_Interface_Files_Manager::sendFile(DATA_DIR."/library".$lbr['lbr_content'], $lbr['lbr_name']);

	}

}
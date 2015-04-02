<?php
class Ez_Interface_Files_Manager 
{
	public static function getFileExtension($fileName)
	{
		$ext = pathinfo($fileName, PATHINFO_EXTENSION);
		return $ext;
	}
	public static function addFormFile($file, $dataDir = 'files')
	{

		$baseFolder = DATA_DIR.'/'.$dataDir.'/';
		$newFilename = '';
		if(!is_dir($baseFolder.date('Y')))
		{
			
			mkdir($baseFolder.date('Y'));
			$baseFolder .= date('Y')."/";
			
		}
		$newFilename = '/'.date('Y');
		if(!is_dir($baseFolder.date('m')))
		{

			mkdir($baseFolder.date('m'));
			$baseFolder .= date('m')."/";
			
		}
		$newFilename .= '/'.date('m');

		
		$fileName = md5(time(). $file['name']).'.'.self::getFileExtension($file['name']);

		move_uploaded_file($file['tmp_name'],  DATA_DIR.'/'.$dataDir.$newFilename."/".$fileName);
		return $newFilename."/".$fileName;
		
		
		
	}
	
	public static function getFileIcon($file)
	{
		$ext = self::getFileExtension($file);

		
		if($ext == 'xls' || $ext == 'xlsx' )
		{
			return 'fa  fa-file-excel-o';
		}
		
		if($ext == 'doc' || $ext == 'docx' )
		{
			return 'fa   fa-file-word-o';
		}
		
		if($ext == 'pdf' )
		{
			return 'fa   fa-file-pdf-o';
		}
		
		if($ext == 'jpg' || $ext == 'png')
		{
			return 'fa   fa-file-image-o';
		}
		
		if($ext == 'rar' || $ext == 'zip')
		{
			return 'fa  fa-file-archive-o';
		}

		return 'fa fa-file-o';;
	}
	
	public static function sendFile($file, $fileToSend)
	{
		
		readfile($file);

		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		
		// disposition / encoding on response body
		header("Content-Disposition: attachment;filename=".str_replace(" ","-",$fileToSend)."");
		header("Content-Transfer-Encoding: binary");
		die();
	}
	
	public static function deleteFile($file)
	{
		try {
			unlink($file);
		}
		catch (Exception $e)
		{
			return false;
		}
		return true;
	}
}
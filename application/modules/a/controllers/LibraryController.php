<?php
class LibraryController extends Ez_Action
{
	public function indexAction()
	{
		$p = $this->_request->getParams();
		$p['id'] = isset($p['id']) ? intval($p['id']) : 0;
		$p['lbr_main_id'] = $p['id'];
		
		$this->view->p = $p;
		
		$modelLibrary = new Application_Model_Library();
		
		if($this->_request->isPost())
		{
				
			$file = $_FILES['file'];
		
			$dbFile = Ez_Interface_Files_Manager::addFormFile($file,'library');
			if(empty($dbFile))
			{
				$this->view->error = 'Unable to upload this file!';
			}
			else
			{
				$modelLibrary->insert(array(
							
						'lbr_name' => $file['name'],
						'lbr_main_id' => $p['id'],
						'lbr_type' => Application_Model_Library::LBR_TYPE_FILE,
						'lbr_date_in' => date("Y-m-d H:i:s"),
						'lbr_content' => $dbFile
				));
			}
			
			$this->getResponse()->setRedirect('/library/index/id/'.$p['id']);
			return;
		
		}
		
		
		$modelLibraryList = new Application_Model_Library_List($p);
		$this->view->lbrs = $modelLibraryList->find();
		
		
		if($p['id'] == 0)
		{
			$this->view->folderName = 'Root Folder';
		}
		else 
		{
			$lbr = $modelLibrary->select($p['id']);
			if(!isset($lbr['lbr_id']))
			{
				throw new Ez_Exception('Invalid Folder Id');
			}
			$this->view->folderName = $lbr['lbr_name'];
			$this->view->lbr = $lbr;

		}
		
		
	}
	
	public function addFolderAction()
	{
		$p = $this->_request->getParams();
		
		$modelLibrary = new Application_Model_Library();
		$modelLibrary->insert(array(
			'lbr_name' => $p['folder_name'],
			'lbr_main_id' => $p['root_id'],
			'lbr_type' => Application_Model_Library::LBR_TYPE_FOLDER,
			'lbr_date_in' => date("Y-m-d H:i:s")
		));
		
		die('done');
	}
	
	public function renameAction()
	{
		$p = $this->_request->getParams();
		$id = $this->_request->getParam('id',0);
		$modelLbr = new Application_Model_Library();
		
		$lbr = $modelLbr->select($id);
		if($lbr['lbr_type'] == Application_Model_Library::LBR_TYPE_FOLDER)
		{
			$modelLbr->update(array('lbr_name' => $p['new_name']), 'lbr_id = '.intval($id));
			echo $p['new_name'];
			die();
		}
		
		if($lbr['lbr_type'] == Application_Model_Library::LBR_TYPE_FILE)
		{
			$ext = Ez_Interface_Files_Manager::getFileExtension($lbr['lbr_content']);
			$modelLbr->update(array('lbr_name' => $p['new_name'].'.'.$ext), 'lbr_id = '.intval($id));
			echo $p['new_name'].".".$ext;
			die();
		}
		
	}
	
	public function deleteAction()
	{
		$id = $this->_request->getParam('id',0);
		$modelLbr = new Application_Model_Library();
		$lbr = $modelLbr->select($id);
		
		if($lbr['lbr_type'] == Application_Model_Library::LBR_TYPE_FOLDER)
		{
			//we will verify if has child.
			$modelLbrList = new Application_Model_Library_List(array('lbr_main_id' => $id));
			$lbrs = $modelLbrList->find();
			if(count($lbrs['rows']))
			{
				die('You can only delete an empty folder!');
			}
			$modelLbr->delete($id);
			die('done');
			
		}
		
		if($lbr['lbr_type'] == Application_Model_Library::LBR_TYPE_FILE)
		{
			//unlink file
			
			Ez_Interface_Files_Manager::deleteFile(DATA_DIR."/library".$lbr['lbr_content']);
			$modelLbr->delete($id);
			die('done');
				
		}
		
		die('Unable to delete!');
		
	}
	
	public function initLoadAction()
	{
		$this->_helper->layout()->disableLayout();
		
		$modelLibraryList = new Application_Model_Library_List(array('lbr_main_id' => 0));
		$this->view->lbr = $modelLibraryList->find();
	}
	
	public function loadListContentAction()
	{
		$id = $this->_request->getParam('id',0);
		$this->_helper->layout()->disableLayout();
		$modelLbr = new Application_Model_Library();
		$this->view->lbr = $modelLbr->select($id);
		
		if(!isset($this->view->lbr['lbr_name']))
		{
			$this->view->lbr['lbr_name'] = 'Root';
		}
		

		
		$modelLibraryList = new Application_Model_Library_List(array('lbr_main_id' => $id));
		$this->view->lbrs = $modelLibraryList->find();
	}
}
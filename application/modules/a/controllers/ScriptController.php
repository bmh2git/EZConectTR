<?php
class ScriptController extends Ez_Action
{
	public function init()
	{
		$session = Bootstrap::getSession();
		if($session->admin['adm_lvl'] < 1)
			die('restricted access!');
		
		$id = $this->_request->getParam('id', 1);
		
		if($id == 1)
		{
			$this->view->sectionName = 'Opening';
		}
		
		if($id == 2)
		{
			$this->view->sectionName = 'Closing';
		}
		
		if($id == 3)
		{
			$this->view->sectionName = 'Objections';
		}
		
		if($id == 4)
		{
			$this->view->sectionName = 'ASP';
		}
		
		$this->view->id = $id;
		
		$this->view->cnt = array(
			1 => 0,
			2 => 0,
			3 => 0,
			4 => 0
		);
		$modelScript = new Application_Model_Script();
		$r = $modelScript->fetchAll("SELECT scr_type, COUNT(scr_id) AS cnt FROM scr_script GROUP BY scr_type");
		if(count($r))
		{
			foreach ($r as $v)
			{
				$this->view->cnt[$v['scr_type']] = $v['cnt'];
			}
		}
	}
	public function indexAction()
	{
		$id = $this->_request->getParam('id', 1);
		
		$session = Bootstrap::getSession();
		if(!isset($session->module))
		{
			$session->module = 0;
		}
		
		$this->view->module = $session->module;
		
		$modelScript = new Application_Model_Script_List(
			array(
			'scr_type' => $id,
			'scr_module' => $this->view->module
		)
		);
		$data = $modelScript->find();
		$this->view->data = $data['rows'];
		
		
		$this->view->bdcp = array(
				array(
						'url' => '/',
						'name' => 'Home'
				),
		
				array(
						'name' => 'Scripts',
						'url' => '/scripts/'
				),
		
				
		
		
		);
		
		if($id == Application_Model_Script:: SCRIPT_TYPE_OPENING)
		{
			$this->setPageTitle('Opening scripts');
			$this->view->bdcp[] = array('name' => 'Opening scripts');
		}
		
		if($id == Application_Model_Script:: SCRIPT_TYPE_CLOSING)
		{
			$this->setPageTitle('Closing scripts');
			$this->view->bdcp[] = array('name' => 'Closing scripts');
		}
		
		if($id == Application_Model_Script:: SCRIPT_TYPE_OBJECTIONS)
		{
			$this->setPageTitle('Closing scripts');
			$this->view->bdcp[] = array('name' => 'Objections scripts');
		}
		
		if($id == Application_Model_Script:: SCRIPT_TYPE_ASP)
		{
			$this->setPageTitle('ASP scripts');
			$this->view->bdcp[] = array('name' => 'ASP scripts');
		}
		
		
		
		
	}
	
	public function addAction()
	{
		$id = $this->_request->getParam('id',1);
		
		$this->view->bdcp = array(
				array(
						'url' => '/',
						'name' => 'Home'
				),
		
				array(
						'name' => 'Scripts',
						'url' => '/scripts/'
				),
		
		
		
		
		);
		
		if($id == Application_Model_Script:: SCRIPT_TYPE_OPENING)
		{
			$this->setPageTitle('New Opening scripts');
			$this->view->bdcp[] = array('name' => 'Opening scripts', 'url' => '/script/index/id/'.$id);
			$this->view->bdcp[] = array('name' => 'New Opening scripts');
			
		}
		
		if($id == Application_Model_Script:: SCRIPT_TYPE_CLOSING)
		{
			$this->setPageTitle('New Closing script');
			$this->view->bdcp[] = array('name' => 'Closing scripts', 'url' => '/script/index/id/'.$id);
			$this->view->bdcp[] = array('name' => 'New Closing scripts');

		}
		
		if($id == Application_Model_Script:: SCRIPT_TYPE_OBJECTIONS)
		{
			$this->setPageTitle('New Objection script');
			$this->view->bdcp[] = array('name' => 'Objection scripts', 'url' => '/script/index/id/'.$id);
			$this->view->bdcp[] = array('name' => 'New Objection scripts');
		}
		
		if($id == Application_Model_Script:: SCRIPT_TYPE_ASP)
		{
			$this->setPageTitle('New ASP script');
			$this->view->bdcp[] = array('name' => 'ASP scripts', 'url' => '/script/index/id/'.$id);
			$this->view->bdcp[] = array('name' => 'New ASP scripts');
		}
	}
	
	public function editAction()
	{
		$p = $this->_request->getParams();
		$modelScript = new Application_Model_Script();
		$this->view->scr = $modelScript->select($p['scr_id']);
		$id = $this->_request->getParam('id',1);
		
		$this->view->bdcp = array(
				array(
						'url' => '/',
						'name' => 'Home'
				),
		
				array(
						'name' => 'Scripts',
						'url' => '/scripts/'
				),
		
		
		
		
		);
		
		if($id == Application_Model_Script:: SCRIPT_TYPE_OPENING)
		{
			$this->setPageTitle('Edit Opening scripts');
			$this->view->bdcp[] = array('name' => 'Opening scripts', 'url' => '/script/index/id/'.$id);
			$this->view->bdcp[] = array('name' => 'Edit Opening scripts');
				
		}
		
		if($id == Application_Model_Script:: SCRIPT_TYPE_CLOSING)
		{
			$this->setPageTitle('Edit Closing script');
			$this->view->bdcp[] = array('name' => 'Closing scripts', 'url' => '/script/index/id/'.$id);
			$this->view->bdcp[] = array('name' => 'Edit Closing scripts');
		
		}
		
		if($id == Application_Model_Script:: SCRIPT_TYPE_OBJECTIONS)
		{
			$this->setPageTitle('Edit Objection script');
			$this->view->bdcp[] = array('name' => 'Objection scripts', 'url' => '/script/index/id/'.$id);
			$this->view->bdcp[] = array('name' => 'Edit Objection scripts');
		}
		
		if($id == Application_Model_Script:: SCRIPT_TYPE_OBJECTIONS)
		{
			$this->setPageTitle('Edit ASP script');
			$this->view->bdcp[] = array('name' => 'ASP scripts', 'url' => '/script/index/id/'.$id);
			$this->view->bdcp[] = array('name' => 'Edit ASP scripts');
		}
		
	}
	
	public function postAction()
	{
		$p = $this->_request->getParams();
		if(empty($p['scr_title']))
		{
			die('Please provide the title!');
		}
		
		if(empty($p['scr_content']))
		{
			die('Please provide the content!');
		}
		
		$modelScript = new Application_Model_Script();
		
		if(isset($p['scr_id']))
		{
			$modelScript->update(array(
				'scr_title' => $p['scr_title'],
				'scr_content' => $p['scr_content'],
				'scr_section' => $p['scr_section'],
				'scr_module' => $p['scr_module']
					
			), 'scr_id = '.$p['scr_id']);
		}
		else 
		{
			$modelScript->insert(array(
				'scr_title' => $p['scr_title'],
				'scr_type' => $p['scr_type'],
				'scr_content' => $p['scr_content'],
				'scr_section' => $p['scr_section'],
				'scr_module' => $p['scr_module']
					
			));
		}
		
		die('done');
	}
	
	public function deleteAction()
	{
		$modelScr = new Application_Model_Script();
		$modelScr->delete($this->_request->getParam('scr_id',0));
		die('done');
		
	}
	
	public function setOrderAction()
	{
		$p = $this->_request->getParams();
		$modelScript = new Application_Model_Script();
		$modelScript->update(array(
			'scr_order' => intval($p['order'])
		), 'scr_id = '. intval($p['id']));
		
		$request = $this->getRequest();
		$backTo = $request->getHeader('referer');
		 $this->getResponse()->setRedirect($backTo);
		return;
	}
	
	public function setModuleAction()
	{
		$session = Bootstrap::getSession();
		$session->module = $this->_request->getParam('module',0);
		die('done');
	}
}
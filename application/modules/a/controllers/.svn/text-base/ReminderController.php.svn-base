<?php
class ReminderController extends Ez_Action
{
	public function indexAction()
	{
		$modelNotificationList = new Application_Model_Notification_List(array(
			'ntf_adm_id' => Sid_Auth_Admin::getAdminId(),
			'ntf_status' => array(
				Application_Model_Notification::STS_TYPE_NEW,
				Application_Model_Notification::STS_TYPE_NOTIFIED,
				
		) 
		));
		
		$this->view->date = $this->_request->getParam('date','');
		
		$this->view->days = $modelNotificationList->getDays();
		
		if(!count($this->view->days))
		{
			return $this->_forward('no-reminders');
		}
		
		if($this->view->date == '')
		{
			$this->view->date = $this->view->days[0]['date'];
			foreach ($this->view->days as $day)
			{
				if($day == date('m/d/y'))
				{
					$this->view->date = $day;
				}
			}
		}
		//let`s get all reminders by that day

		if(!preg_match('/^([0-9]{2})\/([0-9]{2})\/([0-9]{4})/', $this->view->date, $dateArray))
		{
			throw new Ez_Exception('Invalid date!');
		}
		else 
		{

			$dateParams = array(
				'month' => $dateArray[1],
				'day' => $dateArray[2],
				'year' => $dateArray[3],
					
			);
		}
		
		$modelNotificationList = new Application_Model_Notification_List(array(
				'ntf_adm_id' => Sid_Auth_Admin::getAdminId(),
				'date_params'	=> $dateParams,
				'ntf_status' => array(
						Application_Model_Notification::STS_TYPE_NEW,
						Application_Model_Notification::STS_TYPE_NOTIFIED,
				)
		));
		$this->view->ntfs = $modelNotificationList->find();
	}
	
	public function noRemindersAction()
	{
		
	}
	
	public function loadTaskAction()
	{
		$this->_helper->layout()->disableLayout();
		$id = $this->_request->getParam('id',0);
		
		$modelNtf = new Application_Model_Notification();
		$ntf = $modelNtf->select($id);
		if($ntf['ntf_adm_id'] != Sid_Auth_Admin::getAdminId())
		{
			die('You don`t have access to this task');
		}
		if($ntf['ntf_ref_type'] == Application_Model_Import::IMP_TYPE_LEAD)
		{
			$modelLead = new Application_Model_Lead();
			$this->view->lead = $modelLead->select($ntf['ntf_ref_id']);
			$this->view->showLead = true;
		}
		
		if($ntf['ntf_ref_type'] == Application_Model_Import::IMP_TYPE_PROSPECTS)
		{
			$modelPrs = new Application_Model_Prospect();
			$this->view->prs = $modelPrs->select($ntf['ntf_ref_id']);
			$this->view->showPrs = true;
		}
		$modelDspList = new Application_Model_Disposition_List();
		$this->view->dsp = $modelDspList->find();
		$this->view->ntf = $ntf;
	}
	
	
	public function doneAction()
	{
		$id = $this->_request->getParam('id',0);
		$modelNtf = new Application_Model_Notification();
		$ntf = $modelNtf->select($id);
		if($ntf['ntf_adm_id'] != Sid_Auth_Admin::getAdminId())
		{
			die('You don`t have access to this task');
		}
		$modelNtf->update(array(
			'ntf_status' => Application_Model_Notification::STS_TYPE_DONE
		), 'ntf_id = '. $id);
		die('done');
	}
}
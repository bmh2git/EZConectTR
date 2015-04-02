<?php
class Application_Model_Notification_List extends Sid_Db_List
{
	public function __construct($params = array())
	{
		parent::__construct($params);
	}
	private function setSql()
	{
		
		
		$sql = '';

		if(isset($this->params['ntf_adm_id']))
		{
			$sql .= " AND ntf_adm_id  = ".$this->params['ntf_adm_id'];
		}
		
		if(isset($this->params['date_params']))
		{
			if(isset($this->params['date_params']['year']))
			{
				$sql .= " AND  YEAR(ntf_date) = ".$this->params['date_params']['year'];
			}
			
			if(isset($this->params['date_params']['month']))
			{
				$sql .= " AND MONTH(ntf_date) = ".$this->params['date_params']['month'];
			}
			
			if(isset($this->params['date_params']['day']))
			{
				$sql .= " AND DAY(ntf_date) = ".$this->params['date_params']['day'];
			}
			
		}
		
		if(isset($this->params['ntf_status']))
		{
			if(is_array($this->params['ntf_status']))
			{
				$sql .= " AND ntf_status IN ( ".implode(", ", $this->params['ntf_status']).")";
			}
			else 
			{
				$sql .= " AND ntf_status  = ".$this->params['ntf_status'];
			}
			
		}
		
		
		!isset($this->params['page']) && $this->params['page'] = null;
		!isset($this->params['rows']) && $this->params['rows'] = 25;
		

		
		if(isset($this->params['group']))
		{
			$sql .= $this->params['group'];
		}
	
		
		
		if(isset($this->params['sidx']) && $this->params['sidx'] != '' && $this->params['sidx'] != "''" )
    	{
			
    		isset($this->params['sord']) && $this->params['sord'] = str_replace("'", "", $this->params['sord']);
    		$sql .= " ORDER BY ".str_replace("'", "", $this->params['sidx']);
    		if(isset($this->params['sord']) && $this->params['sord'] == "desc")
    		{
    			$sql .= " DESC";
    		}
    		else 
    		{
    			$sql .= " ASC";
    		}
    	}
    	else 
    	{
    		$sql .= " ORDER BY ntf_date ASC ";
    	}
    	
		if($this->params['page'] > 1)
    	{
    		$this->params['rows'] = str_replace("'", "", $this->params['rows']);
    		$this->params['rows'] = $this->params['rows'] > 1 ? $this->params['rows'] : 25;
    		$sql .= " LIMIT ".(($this->params['page']-1)*$this->params['rows']).", ".$this->params['rows'];
    	}
    	elseif ($this->params['page'] != -100)
    	{	
    		$sql .= " LIMIT ".$this->params['rows'];
    	}

    	return $sql;
	}
	
	public function find()
	{
		$fields = '*';
		if(isset($this->params['fields']))
		{
			$fields = implode(", ", $this->params['fields']); 
		}
		$sql = "SELECT $fields FROM ntf_notification
					WHERE 1 ".$this->setSql();

		$this->data['rows'] = $this->fetchAll($sql);
        $cnt = $this->fetchAll("SELECT FOUND_ROWS() as cnt");
        $this->data['count'] = $cnt[0]['cnt'];
        return $this->data;
	}
	
	public function getDays()
	{
		$this->params['group'] = ' GROUP BY date ';
		$sql = "SELECT DATE_FORMAT(ntf_date, '%m/%d/%Y') AS date, COUNT(ntf_id) AS cnt FROM ntf_notification WHERE 1 ".$this->setSql();
		return $this->fetchAll($sql);
	}
	
	
	
	
}
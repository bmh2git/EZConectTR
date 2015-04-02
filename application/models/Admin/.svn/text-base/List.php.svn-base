<?php
class Application_Model_Admin_List extends Sid_Db_List
{
	public function __construct($params =array())
	{
		parent::__construct($params);
	}
	private function setSql()
	{
		$sql = '';
		if(isset($this->params['adm_lvl']))
		{
			$sql .= ' AND adm_lvl = '.intval($this->params['adm_lvl']);
		}
		
		if(isset($this->params['adm_active']))
		{
			$sql .= ' AND adm_active = '.intval($this->params['adm_active']);
		}
		
		
		!isset($this->params['page']) && $this->params['page'] = null;
		!isset($this->params['rows']) && $this->params['rows'] = 50;
		
		if(isset($this->params['sidx']) && $this->params['sidx'] != '' && $this->params['sidx'] != "''" )
    	{
			
    		$this->params['sord'] = str_replace("'", "", $this->params['sord']);
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
    		$sql .= " ORDER BY adm_user ";
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
		$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM adm_admin
					WHERE 1 ".$this->setSql();
		
		$this->data['rows'] = $this->fetchAll($sql);
        $cnt = $this->fetchAll("SELECT FOUND_ROWS() as cnt");
        $this->data['count'] = $cnt[0]['cnt'];
        return $this->data;
	}
	
	public function getInactiveCount()
	{
		$r = $this->fetch("SELECT COUNT(adm_id) AS cnt FROM adm_admin WHERE adm_active = 0");
		return $r['cnt'];
	}
	
	public function getActiveCount()
	{
		$r = $this->fetch("SELECT COUNT(adm_id) AS cnt FROM adm_admin WHERE adm_active = 1 AND adm_lvl = 0");
		return $r['cnt'];
	}
	
	
}
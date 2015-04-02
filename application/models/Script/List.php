<?php
class Application_Model_Script_List extends Sid_Db_List
{
	public function __construct($params = array())
	{
		parent::__construct($params);
	}
	private function setSql()
	{
		
		
		$sql = '';

		if(isset($this->params['scr_section']) && $this->params['scr_section'] > 1)
		{
			$sql .= ' AND ( scr_section = '.$this->params['scr_section'].' OR scr_section = '.Application_Model_Script::SCRIPT_SECTION_BOTH.')';
		}
		
		if(isset($this->params['scr_type']) && $this->params['scr_type'] > 0)
		{
			$sql .= ' AND scr_type ='.$this->params['scr_type'];
		}
		
		if(isset($this->params['scr_module']) && $this->params['scr_module'] > 0)
		{
			if($this->params['scr_module'] >= 10)
			{
				$sql .= ' AND scr_module = '.($this->params['scr_module'] - 10);
			}
			else 
			{
				$sql .= ' AND ( scr_module = '.$this->params['scr_module'].' OR scr_module = '.Application_Model_Module::MODULE_TYPE_BOTH.')';
			}
			
		}
		
		
		!isset($this->params['page']) && $this->params['page'] = null;
		!isset($this->params['rows']) && $this->params['rows'] = 999;
		

		
	
		
		
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
    		$sql .= " ORDER BY scr_order DESC ";
    	}
    	
		if($this->params['page'] > 1)
    	{
    		$this->params['rows'] = str_replace("'", "", $this->params['rows']);
    		$this->params['rows'] = $this->params['rows'] > 1 ? $this->params['rows'] : 999;
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
		
		$sql = "SELECT * FROM scr_script
					WHERE 1 ".$this->setSql();
		
		$this->data['rows'] = $this->fetchAll($sql);
       
        $this->data['count'] = 0;
        return $this->data;
	}
	
	
	
	
}
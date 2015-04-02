<?php
class Application_Model_Imported_List extends Sid_Db_List
{
	public function __construct($params = array())
	{
		parent::__construct($params);
	}
	private function setSql()
	{
		
		
		$sql = '';

		
		
		!isset($this->params['page']) && $this->params['page'] = null;
		!isset($this->params['rows']) && $this->params['rows'] = 25;
		

		
	
		
		
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
    		$sql .= " ORDER BY meal_order DESC ";
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
		$sql = "SELECT $fields FROM dsp_disposition
					WHERE 1 ".$this->setSql();

		$this->data['rows'] = $this->fetchAll($sql);
        $cnt = $this->fetchAll("SELECT FOUND_ROWS() as cnt");
        $this->data['count'] = $cnt[0]['cnt'];
        return $this->data;
	}
	
	
	
	
}
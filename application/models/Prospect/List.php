<?php
class Application_Model_Prospect_List extends Sid_Db_List
{
	public function __construct($params = array())
	{
		parent::__construct($params);
	}
	private function setSql()
	{
		
		$sql = '';
	
		if(isset($this->params['imp_id']))
		{
			$sql .= ' AND prs_imp_id = '.$this->params['imp_id'];
		}

		if(isset($this->params['adm_id']))
		{
			$sql .= " AND prs_adm_id = ".$this->params['adm_id'];
		}
		
		if(isset($this->params['imp_id']))
		{
			$sql .= " AND prs_imp_id = ".$this->params['imp_id'];
		}
		
		if(isset($this->params['status_not_in']))
		{
			$sql .= " AND prs_status != ".$this->params['status_not_in'];
		}
		if(isset($this->params['status']))
		{
			$sql .= " AND prs_status = ".$this->params['status'];
		}
		else 
		{
			$sql .= ' AND prs_stage < '.Application_Model_Prospect::PRS_STAGE_PROMOTED;
		}
		
		
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
    		$sql .= " ORDER BY prs_id DESC ";
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
		$sql = "SELECT SQL_CALC_FOUND_ROWS  $fields, dsp_name, adm_user FROM prs_prospect
						INNER JOIN dsp_disposition ON (prs_dsp_id = dsp_id)
						LEFT JOIN adm_admin ON ( prs_adm_id = adm_id )
					WHERE 1 ".$this->setSql();

		$this->data['rows'] = $this->fetchAll($sql);
        $cnt = $this->fetchAll("SELECT FOUND_ROWS() as cnt");
        $this->data['count'] = $cnt[0]['cnt'];
        return $this->data;
	}
	
	
	
	
}
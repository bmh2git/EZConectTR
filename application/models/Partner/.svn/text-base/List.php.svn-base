<?php
class Application_Model_Partner_List extends Sid_Db_List
{
	public function __construct($params = array())
	{
		parent::__construct($params);
	}
	private function setSql()
	{
		
		
		$sql = '';

		if(isset($this->params['adm_id']))
		{
			$sql .= ' AND prt_adm_id = '.$this->params['adm_id'];
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
    		$sql .= " ORDER BY prt_id DESC ";
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
		$fields = '*';
		if(isset($this->params['fields']))
		{
			$fields = implode(", ", $this->params['fields']); 
		}
		$sql = "SELECT prt_id, prt_ref_type, prt_type, lead.*, prs.*, adm_user
							FROM prt_partner
								LEFT JOIN lead_lead lead ON ( prt_ref_type = ".Application_Model_Import::IMP_TYPE_LEAD." AND prt_ref_id = lead_id )
								LEFT JOIN prs_prospect prs ON ( prt_ref_type = ".Application_Model_Import::IMP_TYPE_PROSPECTS." AND prt_ref_id = prs_id )
								INNER JOIN adm_admin ON ( 
											(prt_ref_type = ".Application_Model_Import::IMP_TYPE_LEAD." AND adm_id = lead_adm_id)
														OR
											( prt_ref_type = ".Application_Model_Import::IMP_TYPE_PROSPECTS." AND adm_id = prs_adm_id )
	)
					WHERE 1 ".$this->setSql();

		$this->data['rows'] = $this->fetchAll($sql);
        $cnt = $this->fetchAll("SELECT FOUND_ROWS() as cnt");
        $this->data['count'] = $cnt[0]['cnt'];
        return $this->data;
	}
	
	
	
	
}
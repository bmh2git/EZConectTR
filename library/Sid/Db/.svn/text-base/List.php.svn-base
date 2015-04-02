<?php

class Sid_Db_List extends Sid_Db
{

    var $params;
    var $data;

    public function __construct($params = array())
    {

        parent::__construct();
        if(is_array($params))
        {

            $this->setParams($params);
        }
    }

    public function cleanParams($params)
    {
        $return = array();
        
        if(is_array($params))
        {
            foreach($params as $k=>$v)
            {
            	if($k != 'fields')
            	{
	            	if(is_array($v))
	                {
	                    $return[$k] = $this->cleanParams($v);
	                }
	                else
	                {
	                	if(preg_match("/^[\d]+$/", $v))
	                	{
	                		$v = intval($v);
	                	}
	                    $return[$k] = $this->db->quote($v);
	                }
            	}
            	else 
            	{
            		$return['fields'] = $v;
            	}

            }
        }
        return  $return;
    }

    public function setParams($params)
    {

        $params = $this->cleanParams($params);
        $this->params = $params;
        
    }
}

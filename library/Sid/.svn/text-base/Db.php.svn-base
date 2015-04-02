<?php

class Sid_Db
{

    const FLAG_ACTIVE = 0x01;
    const FLAG_DELETED = 0x02;
	const LIFE_TIME = 1;
    public $db;
    var $stmt;
    public $vars;
    static $olddb;
    var $backendOptions =  array(
    'cache_dir' => '/tmp/'
);
    
    
	public function __construct()
	{
		$this->db = Bootstrap::getDb();
	}
	
	public function allQuery($sql)
	{
		$this->query($sql);
	}
	
	protected function query($sql, $vars = array())
	{

		if(isset($this->vars))
		{
			$vars = $this->vars;
		}

		if(count($vars) && is_array($vars))
		{
			foreach ($vars as $k => $v)
			{
				$vars[$k] = trim($v,"'");
			}
		}
		
		try
		{
			$this->stmt = $this->db->query($sql, $vars);	
		}
		catch (Exception $e)
		{
			if($_SERVER['REMOTE_ADDR'] == "127.0.0.1")
			{
				echo $sql;
				die("<b>DB ERROR</b>");
			}

			throw new Sid_Exception($e);
		}
		
		unset($this->vars);
	}
	
	private function setVars($vars)
	{
		if(count($vars))
		{
			$this->vars = $vars;
		}
		
	}
	
	private function __fetch($sql,$vars = array())
	{
			$this->setVars($vars);
			$this->query($sql);
			$return = $this->stmt->fetch();
			return $return;
	}
	
	public function regexpName($name)
	{
		return preg_replace("/[^a-zA-Z0-9]/","",$name);
	}
	
	public function fetch($sql,$vars = array())
	{
		
		//return $this->__fetch($sql,$vars);

		if($_SERVER['REMOTE_ADDR'] == "127.0.0.1" || (defined('APPLICATION_ENV') && APPLICATION_ENV == 'admin') || (defined('APPLICATION_ENV') && APPLICATION_ENV == 'a'))
		{
			return $this->__fetch($sql,$vars);
		}
		
		$cache_id = md5('sql_' . md5($sql."".serialize($vars)));
		$frontendOptions = array(
        'lifetime' => self::LIFE_TIME  ,                 // cached object will expire after this many seconds
        /* this ROCKS because you don't spend time writing code to check if the cache is expired */
        'automatic_serialization' => true   // we'll need this on to store the object, also for an array
   		 );
   	 	$cache = Zend_Cache::factory('Core', 'File', $frontendOptions, $this->backendOptions);
   	 	
   	 	if(!($obj = $cache->load($cache_id)) )
   	 	{
			$return = $this->__fetch($sql,$vars);
			$cache->save($return);
			return $return;
   	 	}
   	 	else 
   	 	{
   	 		return $obj;
   	 	}
	}
	
	private function __fetchAll($sql, $vars = array())
	{

			$this->setVars($vars);
			$this->query($sql);

				$return = $this->stmt->fetchAll();
		
			
			
			return $return;
	}
	
	public function fetchAll($sql,$vars = array())
	{

		//return $this->__fetchAll($sql,$vars);
		
		if($_SERVER['REMOTE_ADDR'] == "127.0.0.1" || (defined('APPLICATION_ENV') && APPLICATION_ENV == 'admin') || (defined('APPLICATION_ENV') && APPLICATION_ENV == 'a'))
		{
			return $this->__fetchAll($sql,$vars);
		}
		else
		{
			
		}
		$cache_id = md5('sql_' . md5($sql."".serialize($vars)));
		
		$frontendOptions = array(
        'lifetime' => 28800 ,                
        'automatic_serialization' => true
   	 );
   	 	$cache = Zend_Cache::factory('Core', 'File', $frontendOptions, $this->backendOptions);
    
   	 	if(!($obj = $cache->load($cache_id)) )
   	 	{
   	 		$return = $this->__fetchAll($sql,$vars);
			$cache->save($return);
			return $return;
   	 	}
   	 	else 
   	 	{
   	 		return $obj;
   	 	}
   	 	
		
	}
	
	
	public function update($params = array(), $condition)
	{
		if(!isset($condition))
		{
			throw new Exception("Invalid conition for update");
			return;
		}

		$this->db->update($this->tableName, $params, $condition);
	}
	public static function getAll($sql)
	{
		$return = array();
		 self::$olddb = Bootstrap::getOldDb();
		
	    $stmt = self::$olddb->query($sql);
     
	    while ($row = $stmt->fetch())
	    {
	        $return[] =  $row;
	    }
	    return $return;
	}
    
    
}

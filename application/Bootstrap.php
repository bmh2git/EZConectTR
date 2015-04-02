<?php
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    static $_db = null;
    static $logger = null;
    static $_config = null;
    
	static $_front;
	static $_session;
	static $_db_old = null;

	public static function develop()
	{
		
	}
	
 	public function _initConfig()
    {
        Bootstrap::$_config = $this->getOptions();
    }
	public function _initSession()
	{
		//Zend_Session::start();
		if(APPLICATION_ENV != 'bin')
		{
			self::$_session = new Zend_Session_Namespace(Bootstrap::getEnvironment());
			
		}
		
	}
    
	public static function _getResources($zone = null)
    {

        if($zone)
        {
            return Bootstrap::$_config['resources'][$zone];

        }
    }

    public function _initNamespace()
    {

        $autoloader = Zend_Loader_Autoloader::getInstance();
        $autoloader->registerNamespace('Sid_');
        $autoloader->registerNamespace('Form_');
        $autoloader->registerNamespace('App_');
        $autoloader->registerNamespace('Ez_');
    }
    
   
    public function _initDb()
    {
		
    	$dbConfig = Bootstrap::_getResources('db');
    	$dbConfig['params']['driver_options'] = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8;');
        Bootstrap::$_db = Zend_Db::factory( $dbConfig['adapter'],$dbConfig['params'] );
        $dbConfig['params']['dbname'] = "oltex_old";
        Bootstrap::$_db_old = Zend_Db::factory( $dbConfig['adapter'],$dbConfig['params'] );
        
        if($dbConfig['profiler']['enabled'])
        {
        	// Instantiate the profiler in your bootstrap file 
			$profiler = new Zend_Db_Profiler_Firebug('All Database Queries:');
			// Enable it
			$profiler->setEnabled(true);
			// Attach the profiler to your db adapter 
			Bootstrap::$_db->setProfiler($profiler);
 
        }
    }
    
	public function _initFront()
	{
		self::$_front = Zend_Controller_Front::getInstance();

	}
	
	public function _initRoutes()
	{
	
		$frontController = Zend_Controller_Front::getInstance();
        $router = $frontController->getRouter();

		switch (Bootstrap::getEnvironment())
    	{
    		case 'admin' :
    			$session = self::getSession();

    			if(!isset($session->client) || !isset($session->client['cli_id']) || $session->client['cli_id'] < 1)
    			{

		    			$route = new Zend_Controller_Router_Route_Regex(
		          				  '(.*)',
		           		 array('controller' => 'login', 
		                  'action' => 'index')
		       			 );
		        		$router->addRoute('login', $route);
		        		
		    			$route = new Zend_Controller_Router_Route_Static(
		          				  'register',
		           		 array('controller' => 'register', 
		                  'action' => 'index')
		       			 );
		        		$router->addRoute('register', $route);
		        		
		        		
    				
    			
    			}
    			break;
    			
    		case 'a' :
    			Zend_Loader_Autoloader::getInstance()->pushAutoloader(new Sid_Loader_PhpThumb());
    			$session = self::getSession();

    			if(!isset($session->admin) || !isset($session->admin['adm_id']) || $session->admin['adm_id'] < 1)
    			{
    				if(!isset($_GET['a']) || $_GET['a'] != 'nologin')
    				{
    					$route = new Zend_Controller_Router_Route_Regex(
    							'(.*)',
    							array('controller' => 'login',
    									'action' => 'index')
    					);
    					$router->addRoute('login', $route);
    					
    					$route = new Zend_Controller_Router_Route(
    							'ajax-login',
    							array('controller' => 'login',
    								'action' => 'ajax')
    					);
    					$router->addRoute('ajax-login', $route);
    				}
    				else
    				{
    					
    				}
    			}
    			break;
	
    		default:
    			break;
    	}

	}
	
	
	
	public static function getSession($nameSpace = NULL)
	{
	
			return self::$_session;

	}
	
	public static function logConsole($log, $type = Zend_Log::INFO)
	{
		$debugFrom = array("127.0.0.1");
		if(!in_array($_SERVER['REMOTE_ADDR'], $debugFrom))
		{
			return;
		}
		$logger = new Zend_Log();
		$writer = new Zend_Log_Writer_Firebug();
		$logger->addWriter($writer);
		if(!is_array($log))
		{
			$backTrace = debug_backtrace(null, 1);
			$logger->log($backTrace,Zend_Log::INFO);
		}
		else 
		{
			$log['location'] = debug_backtrace(null, 1);
		}
		
		$logger->log($log, $type);
	}
    
	public static function getFront()
	{
		return self::$_front;
	}
	
    public function _initPlugins()
    {
    	
    }
    
    
    public static function getDb()
    {
    	return self::$_db;
    }
    
    public static function getOldDb()
    {
    	return self::$_db_old;
    }
    
    public static function getUploadPath()
    {
    	
    }
    
    public static function getPublicPath()
    {
    	
    }
    
    
}


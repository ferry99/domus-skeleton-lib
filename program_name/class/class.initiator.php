<?php 

namespace QA\dokumen_perijinan\class;

class initiator{

	public static function run(){
		self::initConfig();
		self::autoLoad();
	}

	public static function initConfig(){
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);

		$config = $_SERVER['DOCUMENT_ROOT'].'/domuscom/f_lib_domuscom/config.php';
		require_once($config);

		if (!DEFINED('PROJ_ROOT')) DEFINE('PROJ_ROOT' , PCS_DEPT.'/dok_perijinan');
		if (!DEFINED('PROJ_CLASS')) DEFINE('PROJ_CLASS' , PCS_DEPT.'/dok_perijinan/class');
		if (!DEFINED('PROJ_PAGE')) DEFINE('PROJ_PAGE' , PCS_DEPT.'/dok_perijinan/page');
	}

	public static function autoLoad(){
		spl_autoload_register(array(__CLASS__,'load'));
	}	

	private static function load($className){
		$className = strtolower($className);
		$file = '/class.' . $className . '.php';
	    if(file_exists(CLASS_ROOT . '/core' .$file)){
	        require_once CLASS_ROOT . '/core' . $file;
	    }else if(file_exists(PROJ_CLASS . $file)){
	        require_once PROJ_CLASS . $file;
	    }else{
	    	die('NO FILE');
	    }
	}
}

initiator::run();
initiator::run();


?>
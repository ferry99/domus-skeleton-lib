<?php

$config = $_SERVER['DOCUMENT_ROOT'].'/domuscom/f_lib_domuscom/config.php';
require_once($config);
require_once(CLASS_ROOT . '/core/class.db.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/domuscom/includes/sap.inc.php');


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

 	
class crudSytro extends Database{

	public $conn;
	private $dbname;
	private $ip;

	function __construct(){
		$myConn = new Database();
		$this->dbname = 'qc_2_dbo';
		$this->tablename = 'spare_m_styrofoam';
		$this->ip = $_SERVER["REMOTE_ADDR"];
		$this->conn = $myConn->conn;
	}


	function autoCommit($bool){
		mysqli_autocommit($this->conn, $bool);
	}

	
  	function insert($dbname,$tablename,$arrToInsert){
		$sql = "INSERT INTO $dbname.$tablename (`".implode("`, `" , array_keys($arrToInsert))."`) VALUES ('".implode("', '" , $arrToInsert)."')";
		//echo $sql;
		if($this->conn->query($sql) == TRUE){
			return true;
		}else{
			return false;
			die();
		}		
	} 
}

?>
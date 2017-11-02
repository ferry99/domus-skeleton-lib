<?php

$config = $_SERVER['DOCUMENT_ROOT'].'/domuscom/f_lib_domuscom/config.php';
require_once($config);
require_once(CLASS_ROOT . '/core/class.db.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/domuscom/includes/sap.inc.php');


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

 	
class tempStyro extends Database{

	public $conn;
	private $dbname;
	private $ip;
	public $debug = false;

	function __construct(){
		$myConn = new Database();
		$this->dbname = 'qc_2_dbo';
		$this->tablename = 'spare_temp_styrofoam';
		$this->ip = $_SERVER["REMOTE_ADDR"];
		$this->conn = $myConn->conn;
	}


	function autoCommit($bool){
		mysqli_autocommit($this->conn, $bool);
	}


	function insertTempStyro($dbname,$tablename,$arrToInsert){
		$sql = "INSERT INTO $dbname.$tablename (`".implode("`, `" , array_keys($arrToInsert))."`) VALUES ('".implode("', '" , $arrToInsert)."')";
		if($this->debug) echo $sql.'</br>';
		if($this->conn->query($sql) == TRUE){
			return true;
		}else{
			if($this->debug) echo mysqli_error($this->conn);
			return false;
			die();
		}		
	}


	function selectSumStyro($po){
		$dbname = $this->dbname;
		$sql = "SELECT  no_PO,kode,supplier,`desc`,sum(qty_order) as qty_total 
				FROM $dbname.spare_temp_styrofoam
				WHERE no_PO = '$po'
				GROUP BY kode,supplier ORDER BY EBELP";
		if($this->debug) echo $sql.'</br>';
		$result = $this->conn->query($sql);
		return $result;
	}

	function getTempStyroByPO($po , $tablename){
		$dbname = $this->dbname;
		$sql = "SELECT * FROM $dbname.$tablename WHERE no_PO = '$po'";
		if($this->debug) echo $sql.'</br>';
		$result = $this->conn->query($sql);
		return $result;
	}

	function checkTempStyroByPO($po , $tablename){
		$dbname = $this->dbname;
		$sql = "SELECT * FROM $dbname.$tablename WHERE no_PO = '$po'";
		if($this->debug) echo $sql.'</br>';
		$result = $this->conn->query($sql);
		if(mysqli_num_rows($result) == 0)
			return false;
		else
			return true;
	}


	function getStyroByID($id){
		$dbname = $this->dbname;
		$tablename = $this->tablename;
		$sql = "SELECT * FROM $dbname.$tablename WHERE id_styrofoam = $id";
		$result = $this->conn->query($sql); 
		return $result;
	}	



	function getLastId(){
		$dbname = $this->dbname;
		$tablename = $this->tablename;
		$sql = "SELECT * FROM $dbname.$tablename ORDER BY id_temp_styrofoam DESC LIMIT 1";
		$result = $this->conn->query($sql); 
		$row = $result->fetch_assoc();	
		if($row["id_temp_styrofoam"] == ""){
			return 1;
		}else{
			return $row["id_temp_styrofoam"]+1;
		}	
	}

	function getLastIdTempSum(){
		$dbname = $this->dbname;
		$sql = "SELECT * FROM $dbname.spare_temp_styrofoam_sum ORDER BY id_temp_styrofoam_sum DESC LIMIT 1";
		$result = $this->conn->query($sql); 
		$row = $result->fetch_assoc();	
		if($row["id_temp_styrofoam_sum"] == ""){
			return 1;
		}else{
			return $row["id_temp_styrofoam_sum"]+1;
		}	
	}

}

?>	
<?php

$config = $_SERVER['DOCUMENT_ROOT'].'/domuscom/f_lib_domuscom/config.php';
require_once($config);
require_once(CLASS_ROOT . '/core/class.db.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/domuscom/includes/sap.inc.php');


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

 	
class styro extends Database{

	public $conn;
	private $dbname;
	private $ip;
	public $debug = false;

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


	function insertStyro($dbname,$tablename,$arrToInsert){
		$sql = "INSERT INTO $dbname.$tablename (`".implode("`, `" , array_keys($arrToInsert))."`) VALUES ('".implode("', '" , $arrToInsert)."')";
		if($this->debug) echo $sql;
		if($this->conn->query($sql) == TRUE){
			return true;
		}else{
			if($this->debug) echo mysqli_error($this->conn);
			return false;
			die();
		}		
	}


	function findStyroByMutipleFilter($arrFilter){
		$dbname = $this->dbname;
		$tablename = $this->tablename;
		$sql = "SELECT * FROM $dbname.$tablename WHERE 1=1 AND is_active = 'Y' ";

		if($arrFilter['no_PO'] !== ''){
			$sql .= "AND no_PO = ".$arrFilter['no_PO']."";
		}

		if($arrFilter['desc'] !== 'all'){
			$sql .= "AND `desc` LIKE '".$arrFilter['desc']."%'";
		}

		if($arrFilter['start_date'] !== ''){
			$arrFilter['start_date'] =  date("Y-m-d", strtotime($arrFilter['start_date']));
			$sql .= " AND date_defined >= '".$arrFilter['start_date']."'";
		}

		if($arrFilter['end_date'] !== ''){
		    $arrFilter['end_date'] =  date("Y-m-d", strtotime($arrFilter['end_date']));
			$sql .= " AND date_defined <= '".$arrFilter['end_date']."'";
		}
		//echo $sql;
		$result = $this->conn->query($sql); 
		return $result;
	}

	function getStyroByPO($po){
		$ip = Dbfunction::getClientIP();
		$isipr[0] = array("SIGN"  =>"I","OPTION"=>'EQ',"LOW"=>$po);
		$rsPO = $this->_getPO($isipr); 
		//print_r($rsPO);
		$arrRs = array();
		$arr['ip'] = $ip;
		$arr['no_PO'] = $po;
		foreach($rsPO['TA_PRPO'] as $item ){				
			$arr['kode'] = $item['MATNR'];//idmat
			if(strlen($arr['kode'])==18) $arr['kode'] = round($arr['kode']);
			$arr['desc']     = $item['TXZ01'];//nm_mat
			$arr['qty_order']      = $item['MENGE'];
			$arr['supplier'] = $item['NAME3'];
			$arr['EBELP'] = $item['EBELP'];
			// $arr['matkl']    = $item['MATKL'];
			//INSERT TO TEMP
			//$statusInsertTemp = $this->_insertToTemp('qc_2_dbo' , 'spare_temp_styrofoam' , $arr);
			array_push($arrRs, $arr);
		}
			//print_r($arr);
			return $arrRs;
		//print_r($rs);
	}



	private function _getTempByPOandIP($po , $ip){
		$dbname = $this->dbname;
		$tablename = 'spare_temp_styrofoam';
		$sql = "SELECT * FROM $dbname.$tablename WHERE po = '$po' AND ip = '$ip'";
		$result = $this->conn->query($sql); 
		return $result;
	}

	private function _insertToTemp($dbname , $tablename , $arrToInsert){
		$sql = "INSERT INTO $dbname.$tablename (`".implode("`, `" , array_keys($arrToInsert))."`) VALUES ('".implode("', '" , $arrToInsert)."')";
		//echo $sql;
		if($this->conn->query($sql) == TRUE){
			return true;
		}else{
			return false;
			die();
		}		
	}

	function checkStyroByPO($po){
		$dbname = $this->dbname;
		$tablename = $this->tablename;
		$sql = "SELECT * FROM $dbname.$tablename WHERE no_PO = '$po'";
		$result = $this->conn->query($sql);
		if(mysqli_num_rows($result) == 0)
			return false;
		else
			return true;
	}


	private function _getPO($po){ 
		global $sap;
	    //echo "masuk sini";
	   	// Call-function
		$result=$sap->callfunction("ZF_READ_PRT", 
				array(
					array("IMPORT","IM_FLG","PO"),
					array("TABLE","TA_PRPO",array()),
					array("TABLE","TA_GR",array()),
					array("TABLE","RA_PO",$po),
	                array("TABLE","TA_SGR",array()) 
				)
			);
		//print_r($result)."<br>";
	  // Call successfull?
	    if(is_array($result)){
	        if ($sap->getStatus() == SAPRFC_OK){ 
		  		return $result;
		}else{
	       // Gagal Connect
		}
			$sap->logoff();
	    }else{
			echo "TIDAK ADA PURCHASE REQUESITION PADA BULAN INI";
	    }
	}



	function getStyroByID($id){
		$dbname = $this->dbname;
		$tablename = $this->tablename;
		$sql = "SELECT * FROM $dbname.$tablename WHERE id_styrofoam = $id";
		$result = $this->conn->query($sql); 
		return $result;
	}	

	public function updateStyro($dbname , $tablename , $arrToUpdate , $where){
		$dbname = $dbname;
		$tablename = $tablename;
		$pairValue = array();
		foreach($arrToUpdate as $field => $value){
			$pairValue[] = "`$field`" . ' = '. "'$value'";	
		}

		$sql = "UPDATE $dbname.$tablename  SET ".implode(", " , $pairValue)." WHERE $where";
		//echo $sql;
		$this->conn->query($sql);
		if(mysqli_affected_rows($this->conn) >= 0){			
			return true;
		}else{
			if($this->debug) echo mysqli_error($this->conn);
			return false;
		}		
	}
	
	function setNonAktifStyro($id_styrofoam){
		$dbname = $this->dbname;
		$tablename = $this->tablename;
		$sql = "UPDATE $dbname.$tablename  
				SET `is_active` = 'T'
				WHERE id_styrofoam = '$id_styrofoam'";
		//echo $sql;
		$this->conn->query($sql);
		if(mysqli_affected_rows($this->conn) >= 0){
			//echo 'updated';
			return true;
		}else{
			if($this->debug) echo mysqli_error($this->conn);
			return false;
		}
	}

	function getLastId(){
		$dbname = $this->dbname;
		$tablename = $this->tablename;
		$sql = "SELECT * FROM $dbname.$tablename ORDER BY id_styrofoam DESC LIMIT 1";
		$result = $this->conn->query($sql); 
		$row = $result->fetch_assoc();	
		if($row["id_styrofoam"] == ""){
			return 1;
		}else{
			return $row["id_styrofoam"]+1;
		}	
	}


	function getAllStyroByParam($param=null, $param2){
		$dbname = $this->dbname;
		$tablename = $this->tablename;
		if($param != null){
			$sql = "SELECT $param FROM $dbname.$tablename WHERE 1=1 AND is_active = 'Y' ";
		}else{
			$sql = "SELECT * FROM $dbname.$tablename WHERE 1=1 AND is_active = 'Y' ";
		}

		if($param2['po'] !== '') $sql .= 'AND no_PO = "'.$param2['po'].'"';
		if($param2['start_date'] !== '') $sql .= 'AND date_defined >= "'.$param2['start_date'].'"';
		if($param2['end_date'] !== '') $sql .= 'AND date_defined <= "'.$param2['end_date'].'"';
		
		$sql .= " ORDER BY id_styrofoam";
		//echo $sql;

		$result = $this->conn->query($sql); 
		return $result;
	}

}

?>	
<?php

	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ERROR | E_PARSE);

	$config = $_SERVER['DOCUMENT_ROOT'].'/domuscom/f_lib_domuscom/config.php';
	require_once($_SERVER['DOCUMENT_ROOT'].'/domuscom/includes/sap.inc.php');
	require_once($config);  

	$proj_config = GD_SPAREPART_DEPT.'/check_styrofoam/proj_config.php';
	require_once($proj_config); 

	require_once(HELPER_ROOT . '/myfunc.php');
	require_once(CLASS_ROOT . '/core/class.db.php');
	require_once(CLASS_ROOT . '/core/class.dbfunction.php');  
	require_once(PROJ_CLASS . '/class.gsp.styrofoam.php'); 

	$filterArr = array();
	parse_str($_POST['mydata'], $filterArr);

	//print_r($filterArr);
  	$myStyro = new styro();
    $rsFilter = $myStyro -> findStyroByMutipleFilter($filterArr);

    // print_r($rsFilter);
    $arrRs = array();
    // print_r($rs);
    while($row = $rsFilter -> fetch_assoc()){
		$arrRow['id_styrofoam'] = $row['id_styrofoam'];
		$arrRow['kode']         = $row['kode'];
		$arrRow['supplier']     = $row['supplier'];
		$arrRow['desc']         = $row['desc'];
		$arrRow['qty_order']    = $row['qty_order'];
		$arrRow['qty_pc']       = $row['qty_pc'];
		$arrRow['status']       = $row['status'];
		$arrRow['M3']           = $row['M3'];
		$arrRow['KG']           = $row['KG'];
		$arrRow['qty_coly']     = $row['qty_coly'];
		$arrRow['PSI']          = $row['PSI']; 
		$arrRow['thickness']    = $row['thickness'];               
		$arrRow['petugas']      = $row['petugas'];
        if(strtotime($row['date_defined']) < 0){
            $arrRow['date_defined'] = "";
        }else{
            $arrRow['date_defined'] = $row['date_defined'];
        }
       
        array_push($arrRs , $arrRow);
    }

    $result["rows"] = $arrRs;
    
    echo json_encode($result);   


	

?>

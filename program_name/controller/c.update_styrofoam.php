<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR | E_PARSE);

if(isset($_POST['kode'])){
    $config = $_SERVER['DOCUMENT_ROOT'].'/domuscom/f_lib_domuscom/config.php';
    require_once($config);  

    $proj_config = GD_SPAREPART_DEPT.'/check_styrofoam/proj_config.php';
    require_once($proj_config); 

    require_once(HELPER_ROOT . '/myfunc.php');
    require_once(CLASS_ROOT . '/core/class.db.php');
    require_once(CLASS_ROOT . '/core/class.dbfunction.php');  
    require_once(PROJ_CLASS . '/class.gsp.styrofoam.php');  
    //print_r($_POST);

    $myStyro = new styro();    
    $idx = 0;

    $myStyro -> autoCommit(false);

    $arr = array();
    $flag = true;
    $arr['date_defined'] = Dbfunction::dateToFormatNonAsia($_POST["date_defined"]);
    $arr['petugas']      = $_POST["petugas"];
    foreach($_POST["kode"] as $idx=>$value){
        foreach ($_POST as $key => $value){    
            if(is_array($_POST[$key]))
            $arr[$key] = $_POST[$key][$idx];
        }
        $statusUpdate = $myStyro -> updateStyro('qc_2_dbo','spare_m_styrofoam',$arr , 'id_styrofoam='.$_POST['id_styrofoam'][$idx]);
        if($statusUpdate == false){
            $flag = false;
            break;
        }
        //print_r($arr);        
    }

    if($flag == false){
        $myStyro->rollback();
        $result = array("success" => false , "status" => "Error DB Happend" );
    }else{
        $myStyro->commit();
        $result = array("success" => true , "status" => "Insert Success" );
    }


     echo json_encode($result);   
}

?>
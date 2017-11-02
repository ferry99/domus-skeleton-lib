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
    require_once(PROJ_CLASS . '/class.gsp.temp_styrofoam.php');  

    //print_r($_POST);

    $myStyro = new styro();
    $myTempStyro = new tempStyro();
    // $myStyro ->debug = true;
   // $myTempStyro ->debug = true;

    $idx = 0;
    $myStyro -> autoCommit(false);
    $myTempStyro -> autoCommit(false);

    $arr = array();
    $flag = true;

    $arr['date_defined'] = Dbfunction::dateToFormatNonAsia($_POST["date_defined"]);
    $arr['petugas']      = $_POST["petugas"];
    //print_r($_POST);

    $lastIdStyro = $myStyro -> getLastId();
    //INSERT TO MASTER ONLY FOR CHECKED
    foreach($_POST["kode"] as $idx => $value){
        $arr['id_styrofoam'] = $lastIdStyro;
        if(isset($_POST["checkbox"][$idx])){//GET IF CHECKED
            //unset($_POST["checkbox"][$idx]);
            //echo $_POST["checkbox"][$idx];
            foreach ($_POST as $key => $value){//CHECK ALL POST DATA IF ARRAY LOOP IT
                if(is_array($_POST[$key])){
                    if($key != 'checkbox')
                        $arr[$key] = $_POST[$key][$idx];
                }
            }
            //print_r($arr);
            $arr['date_created'] = date('Y-m-d H:i:s');
            $statusInsertMaster = $myStyro -> insertStyro('qc_2_dbo','spare_m_styrofoam',$arr);
            if($statusInsertMaster == false){
                $flag = false;
                break;
            }
            $lastIdStyro++;
        }
          
    }


    $lastTempIdStyro = $myTempStyro -> getLastId();
    //INSERT TO TEMP IF NEW KODE PO FOR TEMPLATE
    $arr = array();
    $flagTemp = true;
    // $excludedField = array('status' , 'M3' , 'KG' , 'qty_coly' , 'PSI' , 'checkbox' , 'keterangan');

    // if(!$is_PO_exist){//IF DONT HAVE PO CREATE NEW TEMPLATE
    //     foreach($_POST["kode"] as $idx=>$value){
    //         $arr['id_temp_styrofoam'] = $lastTempIdStyro;
    //         foreach ($_POST as $key => $value){//CHECK ALL POST DATA IF ARRAY LOOP IT
    //             if(is_array($_POST[$key])){
    //                 if(!in_array($key, $excludedField))
    //                     $arr[$key] = $_POST[$key][$idx];
    //             }
    //         }
    //         //print_r($arr);
    //         $arr['date_created'] = date('Y-m-d H:i:s');
    //         $statusInsertTemp = $myTempStyro -> insertTempStyro('qc_2_dbo','spare_temp_styrofoam',$arr);
    //         if($statusInsertTemp == false){
    //             $flagTemp = false;
    //             break;
    //         }
    //         $lastTempIdStyro++;
    //     }
    // }

    
    if($flag == false || $flagTemp == false){
        $myTempStyro->rollback();
        $myStyro->rollback();
        $result = array("success" => false , "status" => "Error DB Happend" );
    }else if($flag && $flagTemp){
        $myTempStyro->commit();
        $myStyro->commit();
        $result = array("success" => true , "status" => "Insert Success" );
    }


    echo json_encode($result);   
}

?>
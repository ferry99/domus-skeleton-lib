<?php

if(isset($_POST['id_styrofoam'])){
    $config = $_SERVER['DOCUMENT_ROOT'].'/domuscom/f_lib_domuscom/config.php';
    require_once($config);  

    $proj_config = GD_SPAREPART_DEPT.'/check_styrofoam/proj_config.php';
    require_once($proj_config); 

    require_once(HELPER_ROOT . '/myfunc.php');
    require_once(CLASS_ROOT . '/core/class.db.php');
    require_once(CLASS_ROOT . '/core/class.dbfunction.php');  
    require_once(PROJ_CLASS . '/class.gsp.styrofoam.php');  

    $myStyro = new styro();
    $id_styrofoam = $_POST["id_styrofoam"];
    //$myStyro->debug = true;
    $rs = $myStyro -> setNonAktifStyro($id_styrofoam);

    if ($rs == true) {
        $result = array("success" => true , "status" => "Success" ); 
    }else{
        $result = array("success" => false , "status" => "Error DB Happend" ); 
    }

    echo json_encode($result);   
}

?>
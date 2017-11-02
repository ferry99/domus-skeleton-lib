<?php


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if(isset($_GET)){
    ob_start(); 
    $config = $_SERVER['DOCUMENT_ROOT'].'/domuscom/f_lib_domuscom/config.php';
    require_once($config);  

    $proj_config = GD_SPAREPART_DEPT.'/check_styrofoam/proj_config.php';
    require_once($proj_config); 
    require_once('class/class.gsp.styrofoam.php');
    require_once(LIB_ROOT . '/Classes/PHPExcel.php');
    
    $myStyro = new styro();

    $param2['po']         = ($_GET["po"]!== '') ? $_GET["po"] : '';
    $param2['start_date'] = ($_GET["start_date"]!== '') ? date("Y-m-d", strtotime($_GET["start_date"])) : '';
    $param2['end_date']   = ($_GET["end_date"]!== '') ? date("Y-m-d", strtotime($_GET["end_date"])) : '';

    $param = "date_defined,supplier,`kode`,`desc` ,`qty_pc`,`status`,M3 , KG , qty_coly , PSI , thickness ,petugas";
    $rs = $myStyro->getAllStyroByParam($param , $param2);

     //print_r($res);
    $excel = new PHPExcel();
    $excel->setActiveSheetIndex(0);

    $boldLine = array(
        'borders' => array(
            'allborders' => array(
                'style' => PHPExcel_Style_Border::BORDER_THICK,
            ),
        ),
    );

    $boldCenter = array(
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        ),
        'font'  => array(
            'bold'  => true,
        )
    );


    function col2chr($a){
        $b = 0;
        if($a<27){
            return strtoupper(chr($a+96));
        }else{
            while($a > 26){
                $b++;
                $a = $a-26;
            }
            $b = strtoupper(chr($b+96));
            $a = strtoupper(chr($a+96));
            return $b.$a;
        }
    }

    $excel->getActiveSheet()->setTitle('LIST DATA'); //renaming it
    $excel->getDefaultStyle()->getFont()->setName('Calibari');

    //Write Column A1
    $excel->getDefaultStyle()->getFont()->setSize(8);
    $excel->getDefaultStyle()->getAlignment()->setWrapText(true); 

    $excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(14);
    $excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
    $excel->getActiveSheet()->getRowDimension(1)->setRowHeight(20);
    $excel->getActiveSheet()->setCellValue('A1', 'LIST DOCUMENT');
    $excel->getActiveSheet()->mergeCells('A1:H1');

    //WRITE HEADER
    $excel->getActiveSheet()->setCellValue('A4', 'Tanggal');
    $excel->getActiveSheet()->mergeCells('A4:A5');
    $excel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
    $excel->getActiveSheet()->getStyle('A4')->applyFromArray($boldCenter);

    $excel->getActiveSheet()->setCellValue('B4', 'Supplier');
    $excel->getActiveSheet()->mergeCells('B4:B5');
    $excel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
    $excel->getActiveSheet()->getStyle('B4')->applyFromArray($boldCenter);

    $excel->getActiveSheet()->setCellValue('C4', 'Kode');
    $excel->getActiveSheet()->mergeCells('C4:C5');
    $excel->getActiveSheet()->getColumnDimension('C')->setWidth(13  );    
    $excel->getActiveSheet()->getStyle('C4')->applyFromArray($boldCenter);

    $excel->getActiveSheet()->setCellValue('D4', 'Description');
    $excel->getActiveSheet()->mergeCells('D4:D5');
    $excel->getActiveSheet()->getColumnDimension('D')->setWidth(30);    
    $excel->getActiveSheet()->getStyle('D4')->applyFromArray($boldCenter);

    $excel->getActiveSheet()->setCellValue('E4', 'QTY/PC');
    $excel->getActiveSheet()->mergeCells('E4:E5');
    $excel->getActiveSheet()->getColumnDimension('E')->setWidth(10);    
    $excel->getActiveSheet()->getStyle('E4')->applyFromArray($boldCenter);

    $excel->GetActiveSheet()->setCellValue('F4', 'STATUS');
    $excel->getActiveSheet()->mergeCells('F4:F5');
    $excel->getActiveSheet()->getColumnDimension('F')->setWidth(10);    
    $excel->GetActiveSheet()->getStyle('F4')->applyFromArray($boldCenter);

    $excel->getActiveSheet()->setCellValue('G4', 'M3');
    $excel->getActiveSheet()->mergeCells('G4:G5');
    $excel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
    $excel->getActiveSheet()->getStyle('G4')->applyFromArray($boldCenter);

    $excel->getActiveSheet()->setCellValue('H4', 'KG');
    $excel->getActiveSheet()->mergeCells('H4:H5');
    $excel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
    $excel->getActiveSheet()->getStyle('H4')->applyFromArray($boldCenter);

    $excel->getActiveSheet()->setCellValue('I4', 'QTY/1 COLY');
    $excel->getActiveSheet()->mergeCells('I4:I5');
    $excel->getActiveSheet()->getColumnDimension('I')->setWidth(10);
    $excel->getActiveSheet()->getStyle('I4')->applyFromArray($boldCenter);

    $excel->getActiveSheet()->setCellValue('J4', 'PSI');
    $excel->getActiveSheet()->mergeCells('J4:J5');
    $excel->getActiveSheet()->getColumnDimension('J')->setWidth(10);
    $excel->getActiveSheet()->getStyle('J4')->applyFromArray($boldCenter);

    $excel->getActiveSheet()->setCellValue('K4', 'Thickness');
    $excel->getActiveSheet()->mergeCells('K4:K5');
    $excel->getActiveSheet()->getColumnDimension('K')->setWidth(10);
    $excel->getActiveSheet()->getStyle('K4')->applyFromArray($boldCenter);

   $excel->getActiveSheet()->setCellValue('L4', 'Petugas');
    $excel->getActiveSheet()->mergeCells('L4:L5');
    $excel->getActiveSheet()->getColumnDimension('L')->setWidth(10);
    $excel->getActiveSheet()->getStyle('L4')->applyFromArray($boldCenter);
    //END WRITE

    $currCol = 1;
    $currRow = 6;
    $excel->getDefaultStyle()->getFont()->setSize(8);

    $prevGroup = '';
    $prevJenisMaterial = '';
    while($row = $rs->fetch_assoc()){
        foreach ($row as $key => $value) {
            $excel->getActiveSheet()->setCellValue(col2chr($currCol).$currRow, $row[$key]);
            $currCol++;
            if($currCol == 13){//if lastcol move to next row
                $currCol = 1;
                $currRow++;
            }
        }
    }


    $nm_file = 'STYROFOAM2';
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename='.$nm_file);
    header('Cache-Control: max-age=0');   

    $objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
    ob_end_clean();
    $objWriter->save(PROJ_ROOT . '/' . $nm_file .'.xls');
    $objWriter->save('php://output');


    // $response = array(
    //     'success' => true,
    //     'url' => 'f_lib_domuscom/dept/purcashing/dok_perijinan/'. $nm_file .'.xls'
    //     //'url' => '/f_lib_domuscom/dept/purcashing/dok_perijinan/render_excel.php'

    // );

    //echo json_encode($response);

   exit;
   
}

?>
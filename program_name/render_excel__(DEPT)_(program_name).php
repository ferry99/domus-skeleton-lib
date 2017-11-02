<head>

    <style>
        body{
          font-family : sans-serif;
          font-size: small;
        }

        table{
          font-size: x-small;
          border-collapse: collapse;
        }
        table th{
          border-collapse: collapse;
        /*  border-right : 1px solid #555;
          border-top : 1px solid #555;
          border-bottom : 1px solid #555;*/
        }
        table td{  
            border-collapse: collapse;
          /*border-right : 1px solid #555;
          border-bottom : 1px solid #555;*/
        }
        
        #fixed1 { position: fixed; top: 10px; left: 85%; font-family:sans-serif; font-size:small; }

        .button {
            border: 1px solid #006;
            background: #ccf;
        }
        .button:hover {
            border: 1px solid #f00;
            background: #eef;
        }
        /*
           @media screen
           {
              p.bodyText {font-family:verdana, arial, sans-serif;}
           }

           @media print
           {
              p.bodyText {font-family:georgia, times, serif;}
           }
           @media screen, print
           {
              p.bodyText {font-size:6pt}
           }
        */
        </style>
</head>
<body>
    <?php

        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        $config = $_SERVER['DOCUMENT_ROOT'].'/domuscom/f_lib_domuscom/config.php';
        require_once($config);  

        $proj_config = GD_SPAREPART_DEPT.'/check_styrofoam/proj_config.php';
        require_once($proj_config); 
        require_once(CLASS_ROOT . '/core/class.db.php');  
        require_once(PROJ_CLASS . '/class.gsp.styrofoam.php');
        require_once(LIB_ROOT . '/Classes/PHPExcel.php');

        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=styrofoam.xls");
        header("Pragma: no-cache");
        header("Expires: 0"); 

        if(isset($_GET)){
            $myStyro = new styro();
            $param2['po']         = ($_GET["po"]!== '') ? $_GET["po"] : '';
            $param2['start_date'] = ($_GET["start_date"]!== '') ? date("Y-m-d", strtotime($_GET["start_date"])) : '';
            $param2['end_date']   = ($_GET["end_date"]!== '') ? date("Y-m-d", strtotime($_GET["end_date"])) : '';


            $param = "date_defined,supplier,`kode`,`desc` ,`qty_pc`,`status`,M3 , KG , qty_coly , PSI , petugas";
            $rs = $myStyro->getAllStyroByParam($param , $param2);
        }

     
    ?>

    <table border="1px" style="font-family:Courier New; font-size: 80%">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Supplier</th>                
                <th>Kode</th>
                <th>Description</th>
                <th>Qty/pc</th>
                <th>Status</th>
                <th>M3</th>
                <th>KG</th>
                <th>Qty/1coly</th>
                <th>PSI</th>
                <th>Petugas</th>
            </tr>
        </thead>
        <tbody>
           <?php 
           while($row = $rs->fetch_assoc()){
                echo "<tr>";
                foreach ($row as $key => $value) {
                    echo "<td>".$row[$key]."</td>";
                }
                echo "</tr>";
            } 
    ?>
        </tbody>
    </table> 
</body>



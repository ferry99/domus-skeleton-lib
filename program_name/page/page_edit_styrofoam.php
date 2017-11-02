<?php
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ERROR | E_PARSE);
	
	$config = $_SERVER['DOCUMENT_ROOT'].'/domuscom/f_lib_domuscom/config.php';
	require_once($config);	

	$proj_config = GD_SPAREPART_DEPT.'/check_styrofoam/proj_config.php';
	require_once($proj_config);	
	require_once(CLASS_ROOT . '/core/class.db.php');
    require_once(CLASS_ROOT . '/core/class.dbfunction.php');
    require_once(PROJ_CLASS . '/class.gsp.styrofoam.php');

    $myStyro = new styro();

    $obj = json_decode($_POST['obj']);

    $id_styrofoam = $obj->id_styrofoam;
    $rsStyro = array();
    $arrRow = array();
    $arrResult = array();
    if(isset($id_styrofoam)){
		$rsStyro = $myStyro -> getStyroByID($id_styrofoam);  
	   	while($rowRs = $rsStyro -> fetch_assoc()){
			$arrRow['id_styrofoam'] = $rowRs['id_styrofoam'];
			$arrRow['po']        = $rowRs['no_PO'];
			$arrRow['supplier']     = $rowRs['supplier']; 
			$arrRow['desc']         = $rowRs['desc'];
			$arrRow['kode']         = $rowRs['kode'];
			$arrRow['qty_order']    = $rowRs['qty_order'];
			$arrRow['qty_pc']       = $rowRs['qty_pc'];
			$arrRow['status']       = $rowRs['status'];
			$arrRow['M3']           = $rowRs['M3'];
			$arrRow['KG']           = $rowRs['KG'];   
			$arrRow['thickness']    = $rowRs['thickness'];             
			$arrRow['qty_coly']     = $rowRs['qty_coly'];
			$arrRow['PSI']           = $rowRs['PSI'];
			$arrRow['keterangan']   = $rowRs['keterangan'];
			$arrRow['petugas']      = $rowRs['petugas'];
	        if(strtotime($rowRs['date_defined']) < 0){
	            $arrRow['date_defined']      = "";
	        }else{
	            $arrRow['date_defined']      = $rowRs['date_defined'];
	        }
			$arrRow['date_defined']     = Dbfunction::dateToFormatAsia($rowRs['date_defined']);   
			array_push($arrResult, $arrRow);
	    }   
   	}
   
	$path_project = 'f_lib_domuscom/dept/gd_sparepart';
?>

<head>
	<meta http-equiv="Cache-control" content="no-cache">
    <meta http-equiv="Expires" content="-1">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

  	<link rel="stylesheet" type="text/css" href="f_lib_domuscom/lib/jq135/themes/bootstrap/easyui.css"> 
  	<link rel="stylesheet" type="text/css" href="f_lib_domuscom/lib/jq135/themes/icon.css">
  	<link rel="stylesheet" type="text/css" href="f_lib_domuscom/lib/jq135/themes/color.css">
	<link rel="stylesheet" type="text/css" href="f_lib_domuscom/lib/jq135/demo/demo.css">
    <link rel="stylesheet" type="text/css" href="f_lib_domuscom/dept/purchasing/dok_perijinan/assets/css/pcs_dok_css.css"> 
    <link rel="stylesheet" type="text/css" href="f_lib_domuscom/lib/select2-master/dist/css/select2.min.css">
    <link rel="stylesheet" type="text/css" href="f_lib_domuscom/assets/plugins/sweetalert-master/dist/sweetalert.css">

    <script src="f_lib_domuscom/assets/plugins/sweetalert-master/dist/sweetalert.min.js"></script>
	<script type="text/javascript" src="f_lib_domuscom/lib/jq135/jquery.min.js"></script> 
	<script type="text/javascript" src="f_lib_domuscom/lib/jq135/jquery.easyui.min.js"></script>
	<script type="text/javascript" src="f_lib_domuscom/dept/gd_sparepart/check_styrofoam/assets/js/js_gsp_styrofoam.js"></script> 	
	<script type="text/javascript" src="f_lib_domuscom/assets/js/jquery.validate.min.js"></script>
    <script type="text/javascript" src="f_lib_domuscom/assets/js/formValidation.js"></script> 
	<script type="text/javascript" src="f_lib_domuscom/assets/js/framework/bootstrap.js"></script>  
	<script type="text/javascript" src="f_lib_domuscom/lib/select2-master/dist/js/select2.full.min.js"></script> 

</head>

<body>	
		<div id="content" class="easyui-panel" style="width:100%">
			<h1 style="margin-bottom:30px;color:#000000b3;text-align:center">Edit Styrofoam</h1>

			<form id="form_edit_foam" class="easyui-form" method="post" enctype="multipart/form-data" data-options="novalidate:false">			

				<table border="1px solid black" style="margin-top:30px;border-collapse:collapse;border:none;table-layout:fixed;margin-left:auto;margin-right:auto">
					<thead>
						<?php if(!empty($rsStyro)): ?>
							<tr>
								<td style="border:none">PIC : <input class="easyui-textbox" data-options="required:true" type="text" name="petugas" style="width:100px" value="<?= $arrRow['petugas'] ?>">
								</br>Tanggal : <input class="easyui-datebox" type="text" name="date_defined" style="width:100px" value="<?= $arrRow['date_defined'] ?>" data-options="required:true,formatter:myformatter,parser:myparser"></td>
							</tr>
						<?php endif; ?>
						<tr>
							<td style="border:none">&nbsp</td>
						</tr>
						<tr>
							<th style="text-align:center">SUPPLIER</th>
							<th style="text-align:center">KODE</th>
							<th style="text-align:center">DESCRIPTION</th>
							<th style="text-align:center">QTY ORDER</th>
							<th style="text-align:center">QTY/PC</th>
							<th style="text-align:center">STATUS</th>
							<th style="text-align:center">M3</th>
							<th style="text-align:center">KG</th>
							<th style="text-align:center">QTY/1 COLY</th>
							<th style="text-align:center">PSI</th>		
							<th style="text-align:center">Thickness</th>
							<th style="text-align:center">Keterangan</th>																				
<!-- 							<th>Petugas Gd</th>
 -->					</tr>
					</thead>
					<tbody>
						<?php 
							if(!empty($rsStyro)):
								foreach ($arrResult as $idx => $row) : 
									echo "<tr>"; ?>
									<td style="display:none">
										<input class="easyui-textbox" data-options="required:false" type="text" name="id_styrofoam[]" value="<?= $row['id_styrofoam'] ?>" style="width:400px" >
									</td>
									<td style="display:none">
										<input class="easyui-textbox" data-options="required:false" type="text" name="no_PO[]" value="<?= $row['po'] ?>" style="width:400px">
									</td>
									<td>
										<input class="easyui-textbox" data-options="required:false" type="text" name="supplier[]" value="<?= $row['supplier'] ?>" style="width:250px" readonly>
									</td>
									<td>
										<input class="easyui-textbox" data-options="required:false" type="text" name="kode[]" value="<?= $row['kode'] ?>" style="width:80px" readonly>
									</td>
									<td>
										<input class="easyui-textbox" data-options="required:false" type="text" name="desc[]" value="<?= $row['desc'] ?>" style="width:200px" readonly>
									</td>
									<td>
										<input class="easyui-textbox" data-options="required:false" type="text" name="qty_order[]" value="<?= $row['qty_order'] ?>" style="width:75px" readonly>
									</td>
									<td>
										<input class="easyui-textbox" data-options="required:false" type="text" name="qty_pc[]" value="<?= $row['qty_pc'] ?>" style="width:60px">
									</td>
									<td>
										<select name="status[]" style="width:60px;">
											<option value=""></option>
					                        <option value="pass"<?=$row['status'] == 'pass' ? ' selected="pass"' : '';?>>Pass</option>
											<option value="fail"<?=$row['status'] == 'fail' ? ' selected="fail"' : '';?>>Fail</option>
										</select>									
									</td>
									<td>
										<input class="easyui-textbox" data-options="required:false" type="text" name="M3[]" value="<?= $row['M3'] ?>" style="width:40px">
									</td>
									<td>
										<input class="easyui-textbox" data-options="required:false" type="text" name="KG[]" value="<?= $row['KG'] ?>" style="width:40px">
									</td>
									<td>
										<input class="easyui-textbox" data-options="required:false" type="text" name="qty_coly[]" value="<?= $row['qty_coly'] ?>" style="width:80px">
									</td>					
									<td>
										<input class="easyui-textbox" data-options="required:false" type="text" name="PSI[]" value="<?= $row['PSI'] ?>" style="width:80px">
									</td>
									<td>
										<input class="easyui-textbox" data-options="required:false" type="text" name="thickness[]" value="<?= $row['thickness'] ?>" style="width:80px">
									</td>	
									<td>
										<input class="easyui-textbox" data-options="required:false" type="text" name="keterangan[]" value="<?= $row['keterangan'] ?>" style="width:100px">
									</td>	
								<?php
									echo "</tr>";
								endforeach;
							endif;
						?>
														
					</tbody>
				</table>
			<?php 
			if(!empty($rsStyro)): ?>
			  	<div style="margin: auto;width: 25%; text-align:center" id="btn-box">
					<div style="display:inline;text-align:center;padding:5px">
					    <button class="btn btn-md btn-primary" type="submit" id="go-edit-foam" value="Submit">Edit</button>
					</div>	
					<div style="display:inline;text-align:center;padding:5px">
		    			<a class="btn btn-md btn-back" onclick="backToMain()">Back</a>
					</div>
			    </div>	
			<?php endif; ?>
			</form>
	  	</div>
</body>




<script type="text/javascript">	

	function myformatter(date){
        var y = date.getFullYear();
        var m = date.getMonth()+1;
        var d = date.getDate();
        return (d<10?('0'+d):d)+'-'+(m<10?('0'+m):m)+'-'+y;
    }

    function myparser(s){
        if (!s) return new Date();
        var ss = (s.split('-'));
        var y = parseInt(ss[2],10);
        var m = parseInt(ss[1],10);
        var d = parseInt(ss[0],10);
        if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
            return new Date(y,m-1,d);
        } else {
            return new Date();
        }
    }
</script>
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
    require_once(PROJ_CLASS . '/class.gsp.temp_styrofoam.php');

    $myStyro = new styro();
    $myTempStyro = new tempStyro();
    $myTempStyro->autoCommit(false);
    // $myTempStyro->debug = true;

    //print_r($_POST);
   
    $rsStyro = array();
    if(isset($_POST['no_PO'])){
    	//CHECK IF EXIST ON TEMP SUMARRY
    	//$isTempStyroExist = $myTempStyro -> checkTempStyroByPO($_POST['no_PO']);
    	$isTempSumStyroExist = $myTempStyro -> checkTempStyroByPO($_POST['no_PO'] , 'spare_temp_styrofoam_sum');
    	if($isTempSumStyroExist){
    		//echo 'Get from temp sumarry';
    		//$rsStyro = $myTempStyro -> getTempStyroByPO($_POST['no_PO']); \
    		$rsStyro = $myTempStyro -> getTempStyroByPO($_POST['no_PO'] , 'spare_temp_styrofoam_sum');     		
    	}else{
    		//echo 'Get from SAP';
    		$rsStyro = $myStyro -> getStyroByPO($_POST['no_PO']);//GET FROM SAP
    		if(!empty($rsStyro)){
				$lastTempIdStyro = $myTempStyro -> getLastId(); // GET LAST ID TEMP_STYRO
				$date_created    = date('Y-m-d H:i:s');
			    $flag = true;
	            foreach ($rsStyro as $idx => $row) {//INSERT TO TEMP_STYRO
					$row['id_temp_styrofoam'] = $lastTempIdStyro;
					$row['date_created']      = $date_created;
					$statusInsertTemp         = $myTempStyro -> insertTempStyro('qc_2_dbo','spare_temp_styrofoam',$row);//INSERT TO TEMP
					if(!$statusInsertTemp){
		            	$flag = false;
		            	break;
		            }
		            $lastTempIdStyro++;
	        	}

	            if($flag){//SELECT AND INSERT SUMMARRY TO TEMP_SUM_STYRO
	            	$rsSum = $myTempStyro -> selectSumStyro($_POST['no_PO']);
	            	//IF SELECT THEN INSERT TO SUM TABLE
		    	    $lastTempSumIdStyro = $myTempStyro -> getLastIdTempSum();
	            	if(!empty($rsSum)){
	            		foreach ($rsSum as $idx => $row){
	            			$row['id_temp_styrofoam_sum'] = $lastTempSumIdStyro;
							$statusInsertSum = $myTempStyro -> insertTempStyro('qc_2_dbo','spare_temp_styrofoam_sum',$row);
		            		$lastTempSumIdStyro++;
	            		}
	            	}
	            	if($statusInsertSum == true){
	            		//echo 'success';
	            		$rsStyro = $rsSum;//FOR DISPLAY
	            		$myTempStyro->commit();
	            	}else{
	            		$myTempStyro->rollback();
	            	}
	            }else{
	        		$myTempStyro->rollback();
	            }
	        }else{
	        	echo '<script language="javascript">';
				echo 'alert("PO tidak ditemukan")';
				echo '</script>';
	        }
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
	<script type="text/javascript">
		

	    function doAdd(){
            $('#content').panel({
		        href:'f_lib_domuscom/dept/purchasing/dok_perijinan/page/page_add_new_doc.php',
		        onLoad:function(){
		            //alert('loaded successfully');
		        }
		    });
    	}
  


    	function backToMain(){
      		window.location = "http://192.168.0.8/domuscom/index.php?page=gudangsparepart&action=pengecekan_styrofoam";
    	}

	</script>

	<style type="text/css">
		.mycontainer{	
				width: 95%;
				border-right: 4px solid rgb(98,199,191);
				border-left: 4px solid rgb(98,199,191);
				border-bottom: 4px solid rgb(98,199,191);
				border-top: 2px dashed rgb(98,199,191);
				position: absolute;
				left: 0px;
				right: 0px;
				margin: 10px auto;
				background-color: rgb(175,226,222);
				top: 13%;
				padding: 15px;
	   		}

	</style>
</head>

<body>	
	<div class="mycontainer">
		<div id="content" class="easyui-panel" style="width:100%">
			<h1 style="margin-bottom:30px;color:#000000b3;text-align:center">Check Supp Material</h1>
			
			<div class="filter-box" style="padding:2px 5px;">
				<form id="filter-form-spr" method="post" action="index.php?page=gudangsparepart&action=pengecekan_styrofoam&act2=adding">					
					<div class="form-group">
						<label>PO :</label>	
						<input type="text" id="in-PO-number" name="no_PO" value="<?= isset($_POST['no_PO']) ? $_POST['no_PO'] : '' ?>"></input>	
			    		<button class="btn btn-md btn-primary" type="submit" id="go-add-styrofoam" value="Submit">Search</button>
				    </div>	

				</form>
			</div>

			<form id="form_add_foam" class="easyui-form" method="post" enctype="multipart/form-data" data-options="novalidate:false">
			

				<table border="1px solid black" style="margin-top:30px;border-collapse:collapse;border:none;table-layout:fixed;margin-left:auto;margin-right:auto">
					<thead>
						<?php if(!empty($rsStyro)): ?>
							<tr>
								<td style="border:none"></td>
								<td style="border:none">PIC : <input class="easyui-textbox" data-options="required:true" type="text" name="petugas" value="" style="width:80px">
								Tanggal : <input class="easyui-datebox" data-options="required:true,formatter:myformatter,parser:myparser" type="text" name="date_defined" value="" style="width:80px"></td>
							</tr>
						<?php endif; ?>
						<tr>
							<td style="border:none">&nbsp</td>
						</tr>
						<tr>	
							<th style="text-align:center"></th>
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
<!-- 						<th>Petugas Gd</th>
 -->					</tr>
					</thead>
					<tbody>
						<?php 
							if(!empty($rsStyro)):
								foreach ($rsStyro as $idx => $row) : 
									echo "<tr>"; 
									$row['qty_order'] = (isset($row['qty_order']) ? $row['qty_order'] : $row['qty_total']);
								?>
									<td style="display:none"><input class="easyui-textbox" data-options="required:false" type="text" name="no_PO[]" value="<?= $row['no_PO'] ?>" style="width:350px"></td>
									<td><input data-options="required:false" type="checkbox" name="checkbox[<?= $idx ?>]" value="Y" style="width:20px"></td>
									<td><input class="easyui-textbox" data-options="required:false" type="text" name="supplier[]" value="<?= $row['supplier'] ?>" style="width:250px" readonly></td>
									<td><input class="easyui-textbox" data-options="required:false" type="text" name="kode[]" value="<?= $row['kode'] ?>" style="width:80px" readonly></td>
									<td><input class="easyui-textbox" data-options="required:false" type="text" name="desc[]" value="<?= $row['desc'] ?>" style="width:250px" readonly></td>
									<td><input class="easyui-textbox" data-options="required:false" type="text" name="qty_order[]" value="<?= $row['qty_order'] ?>" style="width:60px" readonly></td>
									<td><input class="easyui-textbox" data-options="required:false" type="text" name="qty_pc[]" value="" style="width:60px" ></td>
									<td><select name="status[]" id="is-active" style="width:60px;">
											<option value="pass">pass</option>
											<option value="fail">fail</option>
										</select>
<!-- 										<input class="easyui-textbox" data-options="required:false" type="text" name="status[]" value="" style="width:60px"></td>
 -->								<td ><input class="easyui-textbox" data-options="required:false" type="text" name="M3[]" value="" style="width:50px"></td>
									<td ><input class="easyui-textbox" data-options="required:false" type="text" name="KG[]" value="" style="width:50px"></td>									
									<td ><input class="easyui-textbox" data-options="required:false" type="text" name="qty_coly[]" value="" style="width:70px"></td>	
									<td ><input class="easyui-textbox" data-options="required:false" type="text" name="PSI[]" value="" style="width:70px"></td>
									<td ><input class="easyui-textbox" data-options="required:false" type="text" name="thickness[]" value="" style="width:70px"></td>
									<td ><input class="easyui-textbox" data-options="required:false" type="text" name="keterangan[]" value="" style="width:100px"></td>	
								<?php
									echo "</tr>";
								endforeach;
							endif;
						?>
						<!-- <tr>
							<td><input class="easyui-textbox" data-options="required:false" type="text" name="supplier[]" value="" style="width:70%"></td>
							<td><input class="easyui-textbox" data-options="required:false" type="text" name="kode[]" value="" style="width:70%"></td>
							<td><input class="easyui-textbox" data-options="required:false" type="text" name="desc[]" value="" style="width:70%"></td>
							<td><input class="easyui-textbox" data-options="required:false" type="text" name="qty_pc[]" value="" style="width:70%"></td>
							<td><input class="easyui-textbox" data-options="required:false" type="text" name="status[]" value="" style="width:70%"></td>
							<td><input class="easyui-textbox" data-options="required:false" type="text" name="M3[]" value="" style="width:70%"></td>
							<td><input class="easyui-textbox" data-options="required:false" type="text" name="KG[]" value="" style="width:70%"></td>
							<td><input class="easyui-textbox" data-options="required:false" type="text" name="qty_coly[]" value="" style="width:70%"></td>												
 						</tr>	
						<tr>
							<td><input class="easyui-textbox" data-options="required:false" type="text" name="supplier[]" value="" style="width:70%"></td>
							<td><input class="easyui-textbox" data-options="required:false" type="text" name="kode[]" value="" style="width:70%"></td>
							<td><input class="easyui-textbox" data-options="required:false" type="text" name="desc[]" value="" style="width:70%"></td>
							<td><input class="easyui-textbox" data-options="required:false" type="text" name="qty_pc[]" value="" style="width:70%"></td>
							<td><input class="easyui-textbox" data-options="required:false" type="text" name="status[]" value="" style="width:70%"></td>
							<td><input class="easyui-textbox" data-options="required:false" type="text" name="M3[]" value="" style="width:70%"></td>
							<td><input class="easyui-textbox" data-options="required:false" type="text" name="KG[]" value="" style="width:70%"></td>
							<td><input class="easyui-textbox" data-options="required:false" type="text" name="qty_coly[]" value="" style="width:70%"></td>												
 						</tr>		 -->								
					</tbody>
				</table>
			<?php 
			if(!empty($rsStyro)): ?>
			  	<div style="margin: auto;width: 25%;" id="btn-box">
					<div style="display:inline;text-align:center;padding:5px">
					    <button class="btn btn-md btn-primary" id="go-add-foam" >Add</button>
					</div>	
					<div style="display:inline;text-align:center;padding:5px">
		    			<a class="btn btn-md btn-back" onclick="backToMain()">Back</a>
					</div>
			    </div>	
			<?php endif; ?>
			</form>
	  	</div>
</div>
</body>




<script type="text/javascript">	

	$(function(){
	   $( "input[type=checkbox]" ).on("change", function(){
	        if($(this).is(':checked'))
	            $(this).closest('tr').css('background-color', '#FB8206');
	        else
	            $(this).closest('tr').css('background-color', '');
	    });		
	})

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
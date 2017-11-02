<?php
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ERROR | E_PARSE);
	
	$config = $_SERVER['DOCUMENT_ROOT'].'/domuscom/f_lib_domuscom/config.php';
	require_once($config);	

	$proj_config = GD_SPAREPART_DEPT.'/check_styrofoam/proj_config.php';
	require_once($proj_config);	
	//require_once(CLASS_ROOT . '/core/class.db.php');

	$path_project = 'f_lib_domuscom/dept/gd_sparepart/check_styrofoam';
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
		
	  	function backToMain(){
	  		$('#content').panel({
	        	href:'f_lib_domuscom/dept/gd_sparepart/check_styrofoam/main_modul.php',
		        method: 'post',
		         onLoad:function(){
		            //alert('loaded successfully');
		            $('#no_PO').val(myLib.po);
		            $('#start_date').datebox('setValue' , myLib.start_date);
		            $('#end_date').datebox('setValue' , myLib.end_date);
		            // $('#end_date').datebox('setValue' , '17-8-2017');
		            doSearch();
		        }     
	    	});	
		}


	    function doAdd(){ 	
            window.location = "http://192.168.0.8/domuscom/index.php?page=gudangsparepart&action=pengecekan_styrofoam&act2=adding";
    	}

    	function doSearch(){
    		window.myLib = {};
    		myLib.po = $('#no_PO').val();
    		myLib.start_date = $('#start_date').datebox('getValue');
    		myLib.end_date = $('#end_date').datebox('getValue');
			filterData = $('#filter-form-spr').serialize();
			console.log(filterData);
			$('#dg_styrofoam').datagrid({
	            url: proj_url + "/controller/c.get_styrofoam.php",
				method : 'post',
				queryParams : {'mydata' : filterData},
				onLoadSuccess : function(data){
					if(data.rows.length == 0)
					alert("Data PO belum ditambahkan");
				}
		    });
		}

    	function doEdit(){
    		var field = $('#dg_styrofoam').datagrid('getSelected');
			if(field != null){
				obj = JSON.stringify(field);
				console.log(obj);
	    		myParam = {'obj' : obj};
					$('#content').panel({
			        	href:'f_lib_domuscom/dept/gd_sparepart/check_styrofoam/page/page_edit_styrofoam.php',
				        method: 'post',
				        queryParams: myParam, 
				         onLoad:function(){
				            //alert('loaded successfully');
				        }     
			    	});		
	    	}else{
	    		alert('pilih data');
	    	}
    	}

    	function doDelete(){
    		var field = $('#dg_styrofoam').datagrid('getSelected');
			if(field == null || field == ''){
				alert('tidak ada record');
			}else{
	    		if (confirm("Are you sure you want to delete this data?"))
	        	{
					id_styrofoam = field.id_styrofoam;

					 $.ajax({
		               type: "POST",
		               url: "f_lib_domuscom/dept/gd_sparepart/check_styrofoam/controller/c.delete_styrofoam.php",
		               cache: false,
		               async : false,
		               data : {'id_styrofoam' : id_styrofoam},
		               success: function(response)
		               {    
		                    console.log(response);	
							try{
								rs = JSON.parse(response);
								if(rs.success == true){
									swal({ 
						            	title: "success",
						            	text: "Data Deleted",
						            	type: "success" 
							        },
							          function(){		                      
							          	$('#dg_styrofoam').datagrid('reload');
							        });
								 
								}else{
									swal({ 
										title: "error",
										text: "Delete Error",
										type: "error" 
									},
										function(){
								        });
								}
							}catch(e){
								swal({ 
									title: "error",
									text: "Something Went Error",
									type: "error" 
								},
								function(){
								});
							}
		                }
		            }); 
				}	
			}	
    	}
  
    	function saveToExcel(){
			get        = 'all'; 
			po         = $('#po').val();   		
			start_date = $('#start_date').datebox('getValue');  
			end_date   = $('#end_date').datebox('getValue');   		
			console.log(po);
			console.log(start_date);
			console.log(end_date);

    		path = 'http://192.168.0.8/domuscom/f_lib_domuscom/dept/gd_sparepart/check_styrofoam/render_excel_gsp.php?get='+get+'&po='+po+'&start_date='+start_date+'&end_date='+end_date;
				 window.open(''+path+'#','mywindow','width=700,height=1200,scrollbars=1');    		
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
<tbody>

<body>	

	<div id="content" class="easyui-panel" style="width:100%">
		<h1 style="margin-bottom:30px;color:#000000b3;text-align:center">Check Supp Material</h1>

		<div class="filter-box" style="padding:2px 5px;">
			<form id="filter-form-spr" method="post" action="">	
				<table>
					<tr>
						<td><label><b>PO :</b></label></td>
						<td><input type="text" id="po" name="no_PO" value=""></input></td>
						<td><label><b>Bahan :</b></label></td>
						<td>
							<select name="desc" id="is-active" style="width:100px;">
								<option value="all">All</option>
								<option value="STY">STY</option>
								<option value="PE">PE</option>
								<option value="KARTON">KARTON</option>
							</select>
						</td>
						<td><label><b>Start Date :</b></label></td>
						<td><input name="start_date" id="start_date" style="width:100px;" class="easyui-datebox" autocomplete="on" data-options="required:false,formatter:myformatter,parser:myparser"></td>
						<td><label><b>End Date :</b></label></td>
						<td><input name="end_date" id="end_date" style="width:100px;"  autocomplete="on" class="easyui-datebox" data-options="required:false,formatter:myformatter,parser:myparser"></td>
						<td><a class="btn btn-md btn-primary" id="go-search" onClick="doSearch(); return false;">Search</a></td>
					</tr>
				</table>				
			</form>
		</div>
		<div id="dg-wrapper" style="margin-left:30px">
			<table id="dg_styrofoam" class="easyui-datagrid" title="Data Document" style="width:1175px;height:350px;margin-left:50px"
					data-options="autoRowHeight:false,resizable:true,rownumbers:true,toolbar:'#tb',footer:'#ft',singleSelect:true">
		        <thead frozen="true">
		            <tr>
		                <th data-options="field:'kode',width:80" >KODE</th>
		            </tr>
		        </thead>			
		        <thead>
		            <tr>
		                <th data-options="field:'id_styrofoam',width:20,hidden:true" >Id</th>
		                <th data-options="field:'supplier',width:170" >SUPPLIER</th>
		                <th data-options="field:'desc',width:160" >DESC</th>
		                <th data-options="field:'qty_order',width:80" >QTY ORDER</th>
		                <th data-options="field:'qty_pc',width:60" >QTY/PC</th>
		                <th data-options="field:'status',width:60" >STATUS</th>
		                <th data-options="field:'M3',width:60" >M3</th>
		                <th data-options="field:'KG',width:60" >KG</th>
		                <th data-options="field:'qty_coly',width:75">QTY/1COLY</th>
		                <th data-options="field:'PSI',width:50">PSI</th>
		                <th data-options="field:'thickness',width:70">Thickness</th>
	     	            <th data-options="field:'petugas',width:80">Petugas Gd</th>
	     	            <th data-options="field:'date_defined',width:100">Date Created</th>
		            </tr>
		        </thead>
			</table>

			<div id="ft" style="padding:2px 5px;">
				<a href="#" class="easyui-linkbutton" iconCls="icon-add" onclick="doAdd()" plain="true"></a>
			 	<a href="#" class="easyui-linkbutton" iconCls="icon-remove"  onclick="doDelete()" plain="true"></a>
			 	<a href="#" class="easyui-linkbutton" iconCls="icon-edit"  onclick="doEdit()" plain="true"></a>
				<a href="#" class="easyui-linkbutton c4" iconCls="icon-save" onclick="saveToExcel()" plain="true">save to excel</a>	
			</div>	
		</div>
    </div>	 
</body>



<script type="text/javascript">	
	$(function(){
		var po = getParameterByName('po');
		if(po){
			$('#po').val(po);
			console.log(po);
			doSearch();
		}	
	})

	function getParameterByName(name, url) {
	    if (!url) url = window.location.href;
	    name = name.replace(/[\[\]]/g, "\\$&");
	    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
	        results = regex.exec(url);
	    if (!results) return null;
	    if (!results[2]) return '';
	    return decodeURIComponent(results[2].replace(/\+/g, " "));
	}

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
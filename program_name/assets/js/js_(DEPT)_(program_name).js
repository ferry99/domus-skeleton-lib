var ajaxPostCall = function(data , url ,callback){
	  // Return the $.ajax promise
    $.ajax({
        data: data,
        url: url,
        method: 'POST',
		cache: false,
        contentType: false,
        processData: false,
	    beforeSend: function() {
       		onStartAjaxRequest();
    	},
        success:function(response){
            //console.log(data);
            if(typeof callback == "function"){
                callback(response);  
            }else{
                console.log('not callback');
            }               
        },
        error:function(){
            console.log('asd');
        },
	    complete: function (XMLHttpRequest, textStatus) {
    	    onEndAjaxRequest();
      	}
	});
}

var ajaxGetCall = function(data , url ,callback){
      // Return the $.ajax promise
    $.ajax({
        url: url,
        dataType: 'json',
        method: 'GET',
        beforeSend: function() {
            onStartAjaxRequest();
        },
        success:function(data){
            //console.log(data);
            if(typeof callback == "function"){
                callback();  
            }else{
                console.log('not callback');
            }          
        },
        complete: function (XMLHttpRequest, textStatus) {
            onEndAjaxRequest();
        }
    });
}


function onStartAjaxRequest(){
    $('#spinner').show();
}
 
function onEndAjaxRequest(){
    $('#spinner').hide();
}

$(document).ready(function(){

	proj_url = 'f_lib_domuscom/dept/gd_sparepart/check_styrofoam';

	var adminSession;	
	$('body').on('click' , '#go-add-foam' , function(e){
		if(!$('#form_add_foam input[type="checkbox"]').is(':checked')){
    		alert("Please check at least one.");
      		return false;
    	}else{
	        if (confirm("Are you sure you want to save item?"))
		    {	
		       $('#form_add_foam').form({
		        url: 'f_lib_domuscom/dept/gd_sparepart/check_styrofoam/controller/c.add_styrofoam.php',
			        onSubmit:function(){
						return $(this).form('enableValidation').form('validate');
						//return true;
			        },
			        success:function(response){
			        	console.log(response);
				    	try{
					        rs = JSON.parse(response);
					        if(rs.success == true){
					        	swal({ 
			                           title: "success",
			                           text: "Document Added",
			                           type: "success" 
			                        },
			                          function(){		
	                      	            window.location = "http://192.168.0.8/domuscom/index.php?page=gudangsparepart&action=pengecekan_styrofoam";
			                        });
						     
					        }else{
					        	swal({ 
									title: "error",
									text: "Comment Error",
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
			}else{
		        e.preventDefault();
			}	
    	}
	});	


	$('body').on('click' , '#go-edit-foam' , function(e){
        if (confirm("Are you sure you want to edit item?"))
        {	
	       $('#form_edit_foam').form({
	        url: proj_url + '/controller/c.update_styrofoam.php',
		        onSubmit:function(){
					//return $(this).form('enableValidation').form('validate');
					return true;
		        },
		        success:function(response){
		        	console.log(response);
			    	try{
				        rs = JSON.parse(response);
				        if(rs.success == true){
				        	swal({ 
		                           title: "success",
		                           text: "Document Updated",
		                           type: "success" 
		                        },
		                          function(){
		                        });
					     
				        }else{
				        	swal({ 
								title: "error",
								text: "Update Error",
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
		}else{
	        event.preventDefault();
		}
	});	

});


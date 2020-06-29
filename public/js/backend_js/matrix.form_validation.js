
$(document).ready(function(){
	$("#current_pwd").keyup(function(){  //jquery selector of setting.blade.php id of new_pwd
		var current_pwd = $("#current_pwd").val();
		$.ajax({
			type:'get',
			url:'/admin/check-pwd',
			data:{current_pwd:current_pwd},
			success:function(resp){
				//alert(resp);			
				if(resp=='false'){
					$('#chkpwk').html("<font color='red'> Current Password is Incorret</font>");

				}else if(resp=='true'){
					$('#chkpwk').html("<font color='green'>Current Password is Correct</font>");
				}
			},error:function(){
				alert('error');
			}
		});

    });
	

	$('input[type=checkbox],input[type=radio],input[type=file]').uniform();
	
	$('select').select2();
	
	// Form Validation
    $("#basic_validate").validate({
		rules:{
			required:{
				required:true
			},
			email:{
				required:true,
				email: true
			},
			date:{
				required:true,
				date: true
			},
			url:{
				required:true,
				url: true
			}
		},
		errorClass: "help-inline",
		errorElement: "span",
		highlight:function(element, errorClass, validClass) {
			$(element).parents('.control-group').addClass('error');
		},
		unhighlight: function(element, errorClass, validClass) {
			$(element).parents('.control-group').removeClass('error');
			$(element).parents('.control-group').addClass('success');
		}
	});
	// Add Category Validation
    $("#add_category").validate({
		rules:{
			category_name:{
				required:true
			},
			description:{
				required:true,				
			},
			
			url:{
				required:true,	
			}
		},
		errorClass: "help-inline",
		errorElement: "span",
		highlight:function(element, errorClass, validClass) {
			$(element).parents('.control-group').addClass('error');
		},
		unhighlight: function(element, errorClass, validClass) {
			$(element).parents('.control-group').removeClass('error');
			$(element).parents('.control-group').addClass('success');
		}
	});
	
	// Add Product Validation
    $("#add_product").validate({
		rules:{
			category_id:{
				required:true,
			},
			product_name:{
				required:true,
				
			},
			
			product_code:{
				required:true,
				
			},
		
			product_color:{
				required:true,
				
			},
			price:{
				required:true,
				number:true
			},
			image:{
				required:true,
				
			}

		},
		errorClass: "help-inline",
		errorElement: "span",
		highlight:function(element, errorClass, validClass) {
			$(element).parents('.control-group').addClass('error');
		},
		unhighlight: function(element, errorClass, validClass) {
			$(element).parents('.control-group').removeClass('error');
			$(element).parents('.control-group').addClass('success');
		}
	});
	// edit Product Validation
    $("#edit_product").validate({
		rules:{
			category_id:{
				required:true,
			},
			product_name:{
				required:true,
				
			},
			
			product_code:{
				required:true,
				
			},
		
			product_color:{
				required:true,
				
			},
			price:{
				required:true,
				number:true
			}
		

		},
		errorClass: "help-inline",
		errorElement: "span",
		highlight:function(element, errorClass, validClass) {
			$(element).parents('.control-group').addClass('error');
		},
		unhighlight: function(element, errorClass, validClass) {
			$(element).parents('.control-group').removeClass('error');
			$(element).parents('.control-group').addClass('success');
		}
	});
	$("#number_validate").validate({
		rules:{
			min:{
				required: true,
				min:10
			},
			max:{
				required:true,
				max:24
			},
			number:{
				required:true,
				number:true
			}
		},
		errorClass: "help-inline",
		errorElement: "span",
		highlight:function(element, errorClass, validClass) {
			$(element).parents('.control-group').addClass('error');
		},
		unhighlight: function(element, errorClass, validClass) {
			$(element).parents('.control-group').removeClass('error');
			$(element).parents('.control-group').addClass('success');
		}
	});
	
	$("#password_validate").validate({
		rules:{
			current_pwd:{
				required: true,
				minlength:6,
				maxlength:20
			},
			new_pwd:{
				required: true,
				minlength:6,
				maxlength:20

			},
			confirm_pwd:{
				required:true,
				minlength:6,
				maxlength:20,
				equalTo:"#confirm_pwd"
			}
		},
		errorClass: "help-inline",
		errorElement: "span",
		highlight:function(element, errorClass, validClass) {
			$(element).parents('.control-group').addClass('error');
		},
		unhighlight: function(element, errorClass, validClass) {
			$(element).parents('.control-group').removeClass('error');
			$(element).parents('.control-group').addClass('success');
		}
	});
	// $("#delcat").click(function(){
	// 	if(confirm('Are you sure you want to delete this Category?')){
	// 		return true;
	// 	}
	// 	return false;
	// });
	
	// $("#delproduct").click(function(){
	// 	if(confirm('Are you sure you want to delete this Product?')){
	// 		return true;
	// 	}
	// 	return false;
	// });

	$(".deleterecord").click(function(){
		var id = $(this).attr('rel');
		var deletefunction = $(this).attr('rel1');
		swal({
			 title: "<strong>Are you sure?</strong>",
			 text: "You won't be able to revert this!",
			 type: "warning",
			  icon: 'info',
			  html:
			    'You can use <b>bold text</b>, ' +
			    '<a href="//sweetalert2.github.io">links</a> ' +
			    'and other HTML tags',
						 
			 showCancelButton: true,
			 confirmButtonColor:'#3085d6',
			 cancelButtonColor:'#d33',
			  confirmButtonclass: "btn btn-danger",
			  confirmButtonText:
			    'Yes, Delete it!'
			

		},
		function(){
			window.location.href="/admin/"+deletefunction+"/"+id;
		});
	});

	$(document).ready(function(){
    var maxField = 10; //Input fields increment limitation
    var addButton = $('.add_button'); //Add button selector
    var wrapper = $('.field_wrapper'); //Input field wrapper
    var fieldHTML = '<div style = "margin-left:180px;"><input type="text" name="sku[]"  id="sku" placeholder="SKU" style="width:120px; margin-right:3px; margin-top:5px;"/><input type="text" name="size[]"  id="size" placeholder="Size" style="width:120px; margin-right:3px; margin-top:5px;"/><input type="text" name="price[]"  id="price" placeholder="Price" style="width:120px; margin-right:3px; margin-top:5px;"/>  <input type="text" name="stock[]"  id="stock" placeholder="Stock" style="width:120px; margin-right:3px; margin-top:5px;"/><a href="javascript:void(0);" class="remove_button">Remove</a></div>'; //New input field html 
    var x = 1; //Initial field counter is 1
    
    //Once add button is clicked
    $(addButton).click(function(){
        //Check maximum number of input fields
        if(x < maxField){ 
            x++; //Increment field counter
            $(wrapper).append(fieldHTML); //Add field html
        }
    });
    
    //Once remove button is clicked
    $(wrapper).on('click', '.remove_button', function(e){
        e.preventDefault();
        $(this).parent('div').remove(); //Remove field html
        x--; //Decrement field counter
    });
});


});
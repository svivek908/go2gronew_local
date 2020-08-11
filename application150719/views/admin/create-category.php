<?php
$user = $this->session->get_userdata('go2groadmin_session');
$api_key = $user['go2groadmin_session']['logged_user_api_key'];
?>

            <div class="content-w">
                <!--------------------
START - Breadcrumbs
-------------------->
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a>
                    </li>
                    <li class="breadcrumb-item"><a href="index.html">Products</a>
                    </li>
                    <li class="breadcrumb-item"><span>Create category </span>
                    </li>
                </ul>
                <!--------------------
END - Breadcrumbs
-------------------->
                <!--<div class="content-panel-toggler"><i class="os-icon os-icon-grid-squares-22"></i><span>Sidebar</span>
                </div>-->
                <?php //include 'inpage_store_select.php'; ?>
                <div class="content-i">
                    <div class="content-box">
						<div class="row">
                            <div class="col-sm-12">
								<div class="element-wrapper">
									<h6 class="element-header">Create category</h6>
									<div class="col-md-12 element-box" style="border:solid 1px #efefef; padding:3% 5%;">
										<form id="catgoryForm">
											<div>
												 <div class="form-group">
													<label for="">Department Name</label>
													<select class="form-control" id="department">
														<option value="">Select Department</option>
													</select>
													<span id="error1"></span>
												</div>
											</div>

											<div class="form-group">
												<label for=""> Category Name</label>
												<input class="form-control" placeholder="Category Name" type="text" id="catName">
												<span id="error2"></span>
											</div>

											<!--<div class="row no-margin">
												<div class="col-md-2 no-pl" id="cat_image" style="display:none;">
													<div class="thumbnail no-margin" style="min-height:125px;">

													</div>
												</div>

												<div class="dropzone no-pad col-md-4" style="min-height:120px !important; margin-bottom:10px;" id="my-awesome-dropzone">
													<div class="dz-message no-pad">
														<div>
															<h5>Click here to upload category image.</h5>
															<input type="file" onchange="return ValidateFileUpload();" name="catImage" id="catImage">
														</div>
													</div>
												</div>
											</div>
											<span id="error3"></span>-->
											<!--<div class="form-check">
												<label class="form-check-label">
													<input class="form-check-input" type="checkbox"><span style="position:relative; top:-1px;"> Status </span></label>
											</div>-->
											<div class="form-buttons-w">
												<button class="btn btn-primary" type="button" id="save_cat"> Submit</button>
											</div>
										</form>

										<div class="table-responsive order-table" >
											<table class="table table-lightborder" id="catTable">
												<thead>
												<tr>


													<th>Category Name</th>
													<th>Department Name</th>
													<th class="text-center">Status</th>
													<th colspan="2" class="text-center">Action</th>


												</tr>
												</thead>
												<tbody id="cat-list">

												</tbody>
											</table>
										</div>
									</div>
								</div>
							
                               
                            </div>
                           
						</div>
                    
                    </div>
                </div>
            </div>
        </div>
        <div class="display-type"></div>

<div aria-hidden="true" aria-labelledby="exampleModalLabel" class="modal fade" id="editsubDepartModal" role="dialog" tabindex="-1" >
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header"><h5 class="modal-title" id="exampleModalLabel">Edit Category</h5>
				<button aria-label="Close" class="close reset-edit-form" data-dismiss="modal" type="button">
					<span aria-hidden="true"> &times;</span></button>
			</div>
			<div class="modal-body">
				<form id="edit-subdepartform">
					<input class="form-control" placeholder="Enter Id" id="departmentId" type="Text" style="display: none">
					<input class="form-control" placeholder="Enter Id" id="subdepartId" type="Text" style="display: none">
					<div class="form-group"><label for=""> Department Name</label>
						<input class="form-control" id="subdepartName" placeholder="Enter Category Name" type="Text">
						<span class="validate-err" id="error-edit1"></span>
					</div>
				</form></div>
			<div class="modal-footer">
				<button class="btn btn-secondary reset-edit-form" data-dismiss="modal" type="button"> Close</button><button class="btn btn-primary" type="button" id="update-subdepartment"> Update</button>
			</div>
		</div>
	</div>
</div>

    </div>
<script>
	$(document).ready(function(){
		var api_key = "<?php echo $api_key; ?>";
		$.ajax({
			type: 'GET',
			url: '<?php echo base_url();?>Admin/getDepartmentActive',
			data: {'Authorization': api_key},
			success: function (res) {

				var department=res.category;
				console.log(department);
				var jsonlength=department.length;
				var html = '';
				for (var i = 0; i < jsonlength; i++) {
					var result = department[i];
					html += '<option value="'+result.id+'">'+result.cat_name+'</option>';
				}
				$("#department").append(html);
			}
		});

		getCategoryList();
	});

	function getCategoryList(){
		var api_key="<?php echo $api_key;?>";
		var catstatus=0;
		$.ajax({
			type: 'GET',
			url: '<?php echo base_url();?>Admin/getCatgoryList',
			data: {'Authorization': api_key},
			success: function (res) {
				var categorylist=res.subdepartment;
				var jsonlength=categorylist.length;
				var html = '';

				for (var i = 0; i < jsonlength; i++) {

					var result = categorylist[i];
					html += '<tr>\
								<td class="sorting_1">' + result.sub_name + '</td>\
								 <td class="sorting_1">' + result.cat_name + '</td>\
								 <td class="text-center"><div class="status-pill ' + (result.sab_status == 0 ? 'green' : 'red') + '" data-title="Complete" id="status_'+result.sub_id+'" data-toggle="tooltip"></div></td>\
								<td class="text-center"> ' + (result.sab_status == 0 ? '<button id="btn_'+result.sub_id+'" class="edit-item-btn btn-danger" ' +
						'onclick="category_status(\''+result.sub_id+'\', 1)"> Deactive </button>' : '<button id="btn_'+result.sub_id+'" ' +
						' class="edit-item-btn green-bg" onclick="category_status(\''+result.sub_id+'\', 0)"> Active </button>') + '</td>\
								 <td class="text-center"><button class="edit-item-btn btn-primary" data-target="#editsubDepartModal" data-toggle="modal" onclick="editsubDepartment(\''+result.sub_id+'\', \''+result.sub_name+'\', '+result.cat_id+')" type="button">Edit</button></td>\
							</tr>';
				}
				$('#cat-list').html(html);
				$('#catTable').dataTable();
			}
		});
	}

	function ValidateFileUpload() {
		var fuData = document.getElementById('catImage');
		var FileUploadPath = fuData.value;
		var Extension = FileUploadPath.substring(
			FileUploadPath.lastIndexOf('.') + 1).toLowerCase();
		//The file uploaded is an image
		if ( Extension == "png" || Extension == "jpeg" || Extension == "jpg") {
// To Display
			if (fuData.files && fuData.files[0]) {
				var reader = new FileReader();
				reader.onload = function(e) {
					$('#cat_image').attr('src', e.target.result);
					$('#cat_image').css("display", "block");
					var imgHtml = '<img  src="'+e.target.result+'" alt="Lights" style="width:100%">';
					$('#cat_image').children().html(imgHtml);
				}
				reader.readAsDataURL(fuData.files[0]);
			}
		}
//The file upload is NOT an image
		else {
			$("#error3").html("Image only allows file types of PNG, JPG, JPEG and BMP");
		}

	}

	$("#save_cat").click(function(){
		var api_key="<?php echo $api_key;?>";
		var departName = $("#department").val();
		var catName = $("#catName").val();
		//var extArray = ['jpg','jpeg','png'];
		//  alert(extArray);
		//var filename = $("#catImage").val();
		//var ext = filename.split('.').pop().toLowerCase();
		//var result=extArray.indexOf(ext);
		if(departName==""){
			$("#error1").html("* Please select Department!");
			return false;
		}
		if(catName==""){
			$("#error2").html("* Please Enter category!");
			return false;
		}
		/*if(filename==""){

			$("#error3").html("Please upload category image");
			return false;

		}
		if(result==-1){
			//swal("Oops!", "Image only allows file types of PNG, JPG, JPEG and BMP.", "error");
			$("#error3").html("Image only allows file types of PNG, JPG, JPEG and BMP");
			return false;

		}*/
		//var catImage = $("input[name^='catImage']")[0].files[0];
		var formdata = new FormData();
		formdata.append('depart_name',departName);
		//formdata.append('image',catImage);
		formdata.append('cat_name',catName);
		formdata.append('Authorization',api_key);
		$("#save_cat").prop('disabled', true);
		jQuery("#fullLoader").show();
		$.ajax({
			method: "post",
			url:"<?php echo base_url();?>Admin/addCategory",
			data:formdata,
			contentType: false,
			processData: false,
			success:function(data){
				jQuery("#fullLoader").hide();
				if(data.error){
					$("#save_cat").prop('disabled', false);
					swal("Oops!", data.message, "error");
				}else{
					$('#catgoryForm')[0].reset();
					swal({
							title:"Success",
							type:'success',
							text: "Category created successfully.",
							closeOnConfirm: false,
							animation: "slide-from-top"
						},
						function(){
							location.reload();
						});
				}
			}
		});
		
	});
	$("#department").change(function()
		{
			$("#error1").html(" ");
		});

	$("#catName").focus(function()
	{
		$("#error2").html(" ");
	});

	$("#catImage").click(function()
	{
		$("#error3").html(" ");
	});

	function editsubDepartment(id, subdepartname, departId){
		jQuery('#subdepartId').val(id);
		jQuery('#subdepartName').val(subdepartname);
		jQuery('#departmentId').val(departId);
	}
	$(".reset-edit-form").click(function(){
		$("#edit-subdepartform")[0].reset();
		$("#error-edit1").html("");
	});

	$("#subdepartName").focus(function()
	{
		$("#error-edit1").html("");
	});
	$("#update-subdepartment").click(function(){
		var api_key="<?php echo $api_key;?>";
		var subdepartName = $("#subdepartName").val();
		var subdepartId = $("#subdepartId").val();
		var departId = $('#departmentId').val();
		if(subdepartName==""){
			$("#error-edit1").html("* Please enter category name");
			return false;
		}
		$("#update-subdepartment").prop('disabled', true);
		jQuery("#fullLoader").show();
		$.ajax({
			method: "post",
			url:"<?php echo base_url();?>Admin/editSubDepartment",
			data:{"subdept_name":subdepartName, "subdept_id":subdepartId, "Authorization":api_key, "departId":departId},
			success:function(data){
				jQuery("#fullLoader").hide();
				if(data.error){
					swal("Error!", data.message, "error");
					$("#update-subdepartment").prop('disabled', false);
				}else{
					$('#edit-subdepartform')[0].reset();
					swal({
							title:"Success",
							type:'success',
							text: "Category updated successfully.",
							closeOnConfirm: false,
							animation: "slide-from-top"
						},
						function(){
							location.reload();
						});
				}
			}
		});
		return false;
	});

	function category_status(CategoryId, status){


		var api_key = "<?php echo $api_key;?>";
		var message;
		var successMsg;
		var btn;
		var addclassName;
		var removeClassName;
		var message = (status == 0)? "You want active this category":"You want dective this category";
		swal({
			title:"Are you sure?",
			text: message,
			showCancelButton: true,
			closeOnConfirm: false,
			animation: "slide-from-top",
			showLoaderOnConfirm: true
		},function(){
			jQuery.ajax({
				type: 'POST',
				url: '<?php echo base_url();?>Admin/admchangecategorystatus',
				data: {'Authorization': api_key, 'CategoryId':CategoryId, 'status':status},
				success: function (res) {
					if(!res.error){
						if(status == 1){
							addclassName = "red";
							removeClassName="green";
							successMsg = "Category deactive sucessfully";
							btn = '<button id="btn_'+CategoryId+'"  class="edit-item-btn green-bg" onclick="category_status(\''+CategoryId+'\', 0)"> Active </button>';
						}else{
							addclassName = "green";
							removeClassName="red";
							successMsg = "category active sucessfully";
							btn = '<button id="btn_'+CategoryId+'" class="edit-item-btn btn-danger" onclick="category_status(\''+CategoryId+'\', 1)"> Deactive </button>';
						}
						swal('Success!', successMsg, 'success');

						jQuery('#btn_'+CategoryId).replaceWith(btn);
						jQuery('#status_'+CategoryId).removeClass(removeClassName);
						jQuery('#status_'+CategoryId).addClass(addclassName);
					}else{
						swal('Error!', 'Please try again.', 'error');
					}
				}
			});
		});

	}
</script>
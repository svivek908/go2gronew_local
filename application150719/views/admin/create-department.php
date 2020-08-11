
<!-- <style>
	div#departTable_wrapper {
		padding-top: 7%;
	}
</style> -->
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
                    <li class="breadcrumb-item"><span>Create Department</span>
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
									<h6 class="element-header">Create Department</h6>
									<div class="col-md-12 element-box" style="border:solid 1px #efefef; padding:3% 5%;">
										<form id="departForm">
											<input type="hidden"  name="select_old_upoaded_img"  id="select_old_upoaded_img" >
											<div class="form-group">
												<label for=""> Department Name</label>
												<input class="form-control" id="depart_name" placeholder="Department Name" type="text">
												<span id="error1"></span>
											</div>
											<div class="form-group">
												<label for=""> Disclaimer</label>
												<textarea class="form-control" id="disclaimer" placeholder="Disclaimer"></textarea>
											</div>
                                            <!--
                                            <div class="form-group">
                                                <input class="form-control" id="max_orderable_qty_check" type="checkbox">
                                                <label for=""> Enter maximum items allowed per order?</label>
                                                <input style="display:none" class="form-control" id="max_orderable_qty" placeholder="Maximum items allowed per order (Ex. 12)" type="text">
                                            </div>
                                            -->
                                            <span>Only  One Uploaded</span>
                                            <div class="row no-margin" >
                                            	
												<div class="col-md-2 no-pl" id="resimg" style="display:none;">
													<div class="thumbnail no-margin" style="min-height:125px;">

														<!--<p class="text-center" style="line-height:100px;"> Default image </p>-->

													</div>
												</div>
												<div class="dropzone no-pad col-md-4" style="min-height:120px !important; margin-bottom:10px;" id="my-awesome-dropzone">
													<div class="dz-message no-pad">
														<div>
															<h5>click to upload department image.</h5>
															<input onchange="return ValidateFileUpload()" type="file" id="departimage" name="image" value="">
														</div>
													</div>
												</div>
												&nbsp;&nbsp;&nbsp;&nbsp;
												<div class="dropzone no-pad col-md-4" style="min-height:120px !important; margin-bottom:10px;" id="upload_from_uploded">
													<div class="dz-message no-pad">
														<div>
															<h5>Select from uploaded images</h5>
															<a href="javascript:void" class="btn btn-primary" data-toggle="modal" data-target="#uploaded_imglist_mdal" id=""> Click here</a>
														</div>
													</div>
												</div>
												<div class="container">
												  <!-- Trigger the modal with a button -->
												  

												  <!-- Modal -->
												  <div class="modal fade" id="uploaded_imglist_mdal" role="dialog">
												    <div class="modal-dialog  modal-lg">
												    
												      <!-- Modal content-->
												      <div class="modal-content">
												        <div class="modal-header">
												         <!--  <button type="button" class="close" data-dismiss="modal"  >&times;</button> -->
												          
												        </div>
												        <div class="modal-body" style="overflow-y: scroll; max-height:250px	;  margin-top: 50px; margin-bottom:50px;">
												         	<div class="row" id="depart-imagelist"></div>
												        </div>
												        <div class="modal-footer">
		        											<button type="button" class="btn btn-default" data-dismiss="modal" >Close</button>
		     												</div>
												      </div>
												    </div>
												  </div>
												</div>
											</div>
											
											<span id="error2"></span>
											<!--<div class="form-check">
												<label class="form-check-label">
													<input class="form-check-input" type="checkbox"><span style="position:relative; top:-1px;"> Status </span></label>
											</div>-->
											<div class="form-buttons-w">
												<button class="btn btn-primary" id="save_depart" type="button"> Submit</button>
											</div>
										</form>
										<hr>
										<div class="table-responsive order-table" >
											<table class="table table-lightborder" id="departTable">
												
												<tbody id="depart-list" >
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
        </div>
        <div class="display-type"></div>

<div aria-hidden="true" aria-labelledby="exampleModalLabel" class="modal fade" id="editDepartModal" role="dialog" tabindex="-1">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header"><h5 class="modal-title" id="exampleModalLabel">Edit Department</h5>
				<button aria-label="Close" class="close reset-edit-form" data-dismiss="modal" type="button">
					<span aria-hidden="true"> &times;</span></button>
			</div>
			<div class="modal-body">
				<form id="edit-departform">

					<input class="form-control" placeholder="Enter email" id="departId" type="Text" style="display: none">
					<div class="form-group"><label for=""> Department Name</label>
						<input class="form-control" id="departName" placeholder="Enter Department Name" type="Text">
						<span class="validate-err" id="error-edit1"></span>
					</div>
					<div class="form-group">
						<label for=""> Disclaimers</label>
						<textarea class="form-control" id="disclaimerEdit" placeholder="Disclaimer"></textarea>
					</div>

<!--                    <div class="form-group">-->
<!--                       <input class="form-control" id="max_orderable_qty_check_edit" type="checkbox">-->
<!--                        <label for=""> Enter maximum items allowed per order?</label>-->
<!--                        <input style="" class="form-control" id="max_orderable_qty_edit" placeholder="Maximum items allowed per order (Ex. 12)" type="text">-->
<!--                    </div>-->
					<div class="row">
						<div class="col-sm-4">
							<div id="departImage">
								<div class="thumbnail no-margin" style="min-height:100px;" >

									<!--<p class="text-center" style="line-height:100px;"> Default image </p>-->

								</div>
							</div>
						</div>
						<div class="col-sm-8"><div class="form-group">
								<label for="">Department Image</label>
								<input id="editdepartimage" type="file" name="image-edit" onchange="return ValidateImageEditDepart();">
								<span class="validate-err" id="error-edit2"></span>
							</div>
						</div>
					</div>
				</form></div>
			<div class="modal-footer">
				<button class="btn btn-secondary reset-edit-form" data-dismiss="modal" type="button"> Close</button><button class="btn btn-primary" type="button" id="update-department"> Update</button>
			</div>
		</div>
	</div>
</div>
        
    </div>

	<script>
		$(document).ready(function(){

			getDepartmentList();
			getDepartmentimageList();

			$("#depart_name").focus(function()
            {
                $("#error1").html("");
            });

			$("#departimage").click(function()
            {
                $("#error2").html("");
            });

            /*
            $("#max_orderable_qty_check").click(function () {
                if ($(this).is(":checked")) {
                    $("#max_orderable_qty").show();
                } else {
                    $("#max_orderable_qty").hide();
                }
            });
            */


			function getDepartmentList(){
				var api_key="<?php echo $api_key;?>";

				$.ajax({
					method: "GET",
					url:"<?php echo base_url();?>Admin/getDepartmentList",
					data: {'Authorization':api_key},
					success:function(res){
						var html = '';
						var departlist=res.category;
						var jsonlength=departlist.length;
						for (var i = 0; i < jsonlength; i++) {
							var result = departlist[i];
							html += '<tr>\
                                        <td class="nowrap sorting_1">' + result.cat_name + '</td>\
                                        <td class="word-wrap">' + result.disclaimer + '</td>\
                                        <td>\
                                            <div class="cell-image-list"  data-target=".product-image" data-toggle="modal">\
                                               <div class="cell-img" style="background-image: url(<?php echo $this->config->item('api_url_image');?>' + result.image + ')"></div>\
                                               </div>\
                                        </td>\
                                        <td class="text-center"><button style="height: 11px;line-height: 11px;" class="btn btn-primary" data-target="#editDepartModal" data-toggle="modal" onclick="editDepartment(\''+result.id+'\', \''+result.cat_name+'\', \''+result.image+'\', \''+result.disclaimer+'\',\''+result.max_orderable_qty+'\')" type="button">Edit</button></td>\
                                    </tr>';
						}
						$('#depart-list').html(html);
						$('#departTable').dataTable();
					}
				});
			}
		});

		function ValidateFileUpload() {
			var fuData = document.getElementById('departimage');
			var FileUploadPath = fuData.value;
			var Extension = FileUploadPath.substring(
				FileUploadPath.lastIndexOf('.') + 1).toLowerCase();
			//The file uploaded is an image
			if ( Extension == "png" || Extension == "jpeg" || Extension == "jpg") {
			// To Display
				if (fuData.files && fuData.files[0]) {
					var reader = new FileReader();
					reader.onload = function(e) {
						$('#resimg').css("display", "block");
						var imgHtml = '<img  src="'+e.target.result+'" alt="Lights" style="width:100%">';
						$('#resimg').children().html(imgHtml);
					}
					reader.readAsDataURL(fuData.files[0]);
				}
			}
			//The file upload is NOT an image
			else {
				/*swal("Oops!", "Photo only allows file types of PNG, JPG and JPEG. ", "error");*/
				$("#error2").html("Photo only allows file types of PNG, JPG and JPEG");
			}
		}
		$("#departName").focus(function()
		{
			$("#error-edit1").html("");
		});
		$("#editdepartimage").click(function()
		{
			$("#error-edit2").html("");
		});
		function ValidateImageEditDepart() {
			var fuData = document.getElementById('editdepartimage');
			var FileUploadPath = fuData.value;
			var Extension = FileUploadPath.substring(
				FileUploadPath.lastIndexOf('.') + 1).toLowerCase();
			//The file uploaded is an image
			if ( Extension == "png" || Extension == "jpeg" || Extension == "jpg") {
// To Display
				if (fuData.files && fuData.files[0]) {
					var reader = new FileReader();
					reader.onload = function(e) {

						var imgHtml = '<img  src="'+e.target.result+'" alt="Lights" style="width:100%">';
						$('#departImage').children().html(imgHtml);
					}
					reader.readAsDataURL(fuData.files[0]);
				}
			}
//The file upload is NOT an image
			else {
				/*swal("Oops!", "Photo only allows file types of PNG, JPG and JPEG. ", "error");*/
				$("#error-edit2").html("Photo only allows file types of PNG, JPG and JPEG");

			}

		}

        $("#save_depart").click(function(){
            var api_key="<?php echo $api_key;?>";
            var disclaimer = $("#disclaimer").val();
            var departImage = $("input[name^='image']")[0].files[0];
            var select_old_upoaded_img = $("#select_old_upoaded_img").val();
            var filename = $("#departimage").val();

            var departName = $("#depart_name").val();
            if (select_old_upoaded_img!=null && departImage!=null ) {
            	var departImage = $("input[name^='image']")[0].files[0];
            	var select_old_upoaded_img='';
            } else if(departImage!=null){
            		 var departImage = $("input[name^='image']")[0].files[0];
            }else{
            	var select_old_upoaded_img = $("#select_old_upoaded_img").val();
            	console.log(select_old_upoaded_img);
            }
            if(departName==""){
                $("#error1").html("* Please enter department name");
                return false;
            }
            if(select_old_upoaded_img == ""){
            	if(filename==""){
	                $("#error2").html("* Please upload department image");
	                return false;
	            }

	            var extArray = ['jpg','jpeg','png'];
	            var ext = filename.split('.').pop().toLowerCase();
	            var result=extArray.indexOf(ext);
	            if(result==-1){
	                $("#error2").html("Photo only allows file types of PNG, JPG and JPEG");
	                return false;
	            }
            }
            var max_orderable_qty = -1;
            /*
            if ($('#max_orderable_qty_check').is(':checked')) {
                // Input box should not be empty
                if($("#max_orderable_qty").val() == "" || isNaN($("#max_orderable_qty").val())){
                    swal("Error!", "Please enter a valid max limit", "error");
                    return;
                } else {
                    var max_orderable_qty = $("#max_orderable_qty").val();
                }
            }
            */
            var formdata = new FormData();
            formdata.append('select_old_upoaded_img',select_old_upoaded_img);
            formdata.append('depart_name',departName);
            formdata.append('image',departImage);
            formdata.append('disclaimer', disclaimer);
            formdata.append('Authorization',api_key);
            formdata.append('max_orderable_qty',max_orderable_qty);

            $("#save_depart").prop('disabled', true);
            jQuery("#fullLoader").show();
            $.ajax({
             method: "post",
             url:"<?php echo base_url();?>Admin/addDepartment",
             data:formdata,
             contentType: false,
             processData: false,
             success:function(data){
                 jQuery("#fullLoader").hide();
             if(data.error){
                 swal("Error!", data.message, "error");
                 $("#save_depart").prop('disabled', false);
             }else{
                 $('#departForm')[0].reset();
                 swal({
                         title:"Success",
                         type:'success',
                         text: "Department created successfully.",
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

		$(".reset-edit-form").click(function(){
			$("#edit-departform")[0].reset();
			$("#error-edit1").html("");
			$("#error-edit2").html("");
		});
		$("#update-department").click(function(event){
			event.preventDefault();
			var api_key="<?php echo $api_key;?>";
			var departName = $("#departName").val();
			var departId = $("#departId").val();
			var disclaimer = $("#disclaimerEdit").val();
            var max_orderable_qty = -1; // -1 when no limit is entered, unlimited max qty
            /*
            if($("#max_orderable_qty_edit").val() != "" ){
                if(isNaN($("#max_orderable_qty_edit").val())){
                    swal("Error!", "Please enter a valid max limit", "error");
                    return;
                } else {
                    max_orderable_qty = $("#max_orderable_qty_edit").val();
                }
            }
            */
			var departImage = $("input[name^='image-edit']")[0].files[0];
			var extArray = ['jpg','jpeg','png'];
			var filename = $("#editdepartimage").val();
			var ext = filename.split('.').pop().toLowerCase();
			var result=extArray.indexOf(ext);
			if(departName==""){
				$("#error-edit1").html("* Please enter department name");
				return false;
			}

			var formdata = new FormData();

			if(filename != ""){
				if(result==-1){
					$("#error-edit2").html("Photo only allows file types of PNG, JPG and JPEG");
					return false;
				}
				formdata.append('image-edit',departImage);
			}
			formdata.append('depart_name',departName);
			formdata.append('dept_id',departId);
			formdata.append('disclaimer', disclaimer);
            formdata.append('max_orderable_qty', max_orderable_qty);
			formdata.append('Authorization',api_key);

			$("#update-department").prop('disabled', true);
			jQuery("#fullLoader").show();
			$.ajax({
				method: "post",
				url:"<?php echo base_url();?>Admin/editDepartment",
				data:formdata,
				contentType: false,
				processData: false,
				success:function(data){
					jQuery("#fullLoader").hide();
					if(data.error){
						swal("Error!", "Department not updated", "error");
						$("#update-department").prop('disabled', false);
					}else{
						$('#edit-departform')[0].reset();
						swal({
								title:"Success",
								type:'success',
								text: "Department updated successfully.",
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

        function editDepartment(id, departname, departImage, disclaimer, max_orderable_qty){
            jQuery('#departId').val(id);
            jQuery('#departName').val(departname);
            jQuery('#disclaimerEdit').val(disclaimer);
            if(max_orderable_qty != 'null') {
                jQuery('#max_orderable_qty_edit').val(max_orderable_qty);
            }

            jQuery('#departImage div').html('<img src="<?php echo $this->config->item('api_url_image'); ?>'+departImage+'" alt="Lights" style="width:100%" height="100px;">');
        }


        function getDepartmentimageList(){

				var api_key="<?php echo $api_key;?>";

				$.ajax({
					method: "GET",
					url:"<?php echo base_url();?>Admin/getDepartmentimageList",
					data: {'Authorization':api_key},
					success:function(res){
						
						var html = '';
						var departlist=res.category;

						var jsonlength=departlist.length;
						for (var i = 0; i < jsonlength; i++) {
							var result = departlist[i];
							html += '<div class="col-md-3">\
							<input type="hidden" class="close" value="'+result.image+'" name="image" id="imagename_'+i+'">\
							<a href="javascript:void(0)" onclick="select_img_uploded_folder('+i+');"><img  src="<?php echo $this->config->item('api_url_image');?>' + result.image + '" width=50%><br><span>'+result.cat_name+'</span></a><br></div>';
						}

						$('#depart-imagelist').html(html);
					}
				});
			}
		function select_img_uploded_folder(id)
		{
			if(id)
		    {
		        alert("You  Select this Image!");
		    }
		    var selected_img_name = $('#imagename_'+id).val();
		       var selected =  selected_img_name.replace("upload/product_cat/", '');
		    parent.document.getElementById('select_old_upoaded_img').value = selected;
		    //console.log('selected_img_name => '+ selected_img_name.replace("upload/product_cat/", ''));
		}

		
	</script>

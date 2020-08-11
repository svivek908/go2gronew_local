<?php
//$user = $this->session->get_userdata('user');
//$api_key = $user['user']->user->api_key;
?>

<script>
    $(document).ready(function () {

        var store_id = "<?= $store_id; ?>";
        getMotherCategory(store_id);
        var selDiv = "";
        var storedFiles = [];
        $("#files").on("change", handleFileSelect);
        $("#marge").on("click", ".selFile", removeFile);
        selDiv = $("#marge");

        function handleFileSelect(e) {
            var files = e.target.files;
            var filesArr = Array.prototype.slice.call(files);
            filesArr.forEach(function (f) {

                if (!f.type.match("image.*")) {
                    return;
                }
                storedFiles.push(f);

                var reader = new FileReader();
                reader.onload = function (e) {
                    var html = '<div class="col-md-2 no-pl upload-item-img ">\
				<div class="dropzone no-pad">\
				<div class="dz-message no-pad">\
					<div class="thumbnail no-margin" >\
					   <img class="selFile" data-file="' + f.name + '" src="' + e.target.result + '" title="Click to remove" alt="Lights" style="width:100%">\
					</div>\
				</div>\
				<!--<span class="img-close" >x</span>-->\
				</div>\
			</div>';
                    selDiv.append(html);

                }
                reader.readAsDataURL(f);
            });

        }

        function removeFile(e) {
            console.log('sddd');
            var file = $(this).data("file");
            for (var i = 0; i < storedFiles.length; i++) {
                if (storedFiles[i].name === file) {
                    storedFiles.splice(i, 1);
                    break;
                }
            }
            $(this).parent().parent().parent().parent().remove();
        }

        $("#click").click(function () {

            var name = $("#name").val();
            var Description = $("#Description").val();
            var fulldescription = $("#fulldescription").val();
            var itemsize = $("#size").val();
            var tax = $("#tax").val();
            var price = $("#price").val();
            var product_image = $("#files").val();
            var rackno = $("#rack").val();
            var fluid_ounce = -1;

            if($('#fluid_ounce').val() != ''){
                if (isNaN($('#fluid_ounce').val())){
                    $("#error8").html("* Enter a valid numeric value");
                    return false;
                } else {
                    fluid_ounce = $('#fluid_ounce').val();
                }
            }

            if (name == "") {
                $("#error1").html("*Item name required");
                return false;

            }
            /*if (Description == "") {
                $("#error2").html("*Short Description required");
                return false;
            }
            if (fulldescription == 0) {
                $("#error3").html("*Full Description required");
                return false;
            }*/
            if (tax == "") {
                $("#error4").html("*Enter only Numbric value");
                return false;
            }
            if (isNaN(tax)) {
                $("#error4").html("*Enter only Numbric value");
                return false;
            }
            if (price == "") {
                $("#error5").html("*Price must be required");
                return false;
            }
            if (isNaN(price)) {
                $("#error5").html("* Enter only Numeric value");
                return false;
            }
            if (rackno == "") {
                $("#error7").html("*Please Select Category");
                return false;
            }
            if (storedFiles.length <= 0) {
                $("#error6").html("* Please upload images");
                return false;
            }
            if (storedFiles.length > 10) {
                $("#error6").html("* Please upload only 10 images");
                return false;
            }

            var formdata = new FormData();

            formdata.append('item_name', name);
            formdata.append('item_size', itemsize);
            formdata.append('item_sdesc', Description);
            formdata.append('item_fdesc', fulldescription);
            formdata.append('item_price', price);
            formdata.append('Sales_tax', tax);
            formdata.append('rackno', rackno);
            formdata.append('fluid_ounce', fluid_ounce);
            formdata.append('store_id', store_id);

            jQuery.each(storedFiles, function (i, file) {
                formdata.append('image[' + i + ']', file);
            });
            jQuery("#fullLoader").show();
            $.ajax({
                method: "POST",
                url: "<?php echo base_url('Admin/addnewitem');?>",
                data: formdata,
                contentType: false,
                processData: false,
                success: function (data) {
                    jQuery("#fullLoader").hide();
                    // console.log(data);
                    if (data.error==true) {
                        swal("Oops!", data.message, "error");
                    } else {
                        swal({
                                title: "Success",
                                type: 'success',
                                text: "Product added successfully.",
                                closeOnConfirm: false,
                                animation: "slide-from-top"
                            },
                        function () {
                            location.reload();
                        });
                    }
                }
            });
        });

        $("#name").focus(function () {
            $("#error1").html("");
        });
        $("#Description").focus(function () {
            $("#error2").html("");
        });
        $("#fulldescription").focus(function () {
            $("#error3").html("");
        });
        $("#tax").focus(function () {
            $("#error4").html("");
        });
        $("#price").focus(function () {
            $("#error5").html("");
        });
        $("#rack").focus(function () {
            $("#error7").html("");
        });
        $("#fluid_ounce").focus(function () {
            $("#error8").html("");
        });
        $("#files").click(function () {
            $("#error6").html("");
        });
    });

    function getMotherCategory(store_id) {

        $.ajax({
            type: 'POST',
            url: '<?php echo base_url();?>Admin/getMothersCategory',
            data: {'Store':store_id},
            success: function (res) {
                $("#rack").html(res);
            }
        });
    }

    function showindex() {
        alert($(this).index());
    }

    /*function add_file()
     {
     $("#file_div").append("<div><img src='' ><input type='file' name='file[]'><input type='button' class='btn btn-info1'  value='REMOVE' onclick=remove_file(this);></div>");
     }
     function remove_file(ele)
     {
     $(ele).parent().remove();
     }*/

</script>
<Style>
    input[type="file"] {

        display: block;
    }

    .imageThumb {
        max-height: 75px;
        border: 2px solid;
        margin: 10px 10px 0 0;
        padding: 1px;
    }
</Style>
<div class="content-w">
    <!--------------------
START - Breadcrumbs
-------------------->
    <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>/Admin">Home</a>
        </li>
        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>/Admin/viewproduct">Products</a>
        </li>
        <li class="breadcrumb-item"><span>Add product</span>
        </li>
    </ul>
    <!--------------------
END - Breadcrumbs
-------------------->
    <!-- <div class="content-panel-toggler"><i class="os-icon os-icon-grid-squares-22"></i><span>Sidebar</span>
     </div>-->
     <?php $this->load->view('admin/include/inpage_store_select'); ?>
    <div class="content-i">
        <div class="content-box">
            <div class="row">
                <div class="col-sm-12">
                    <div class="element-wrapper">
                        <h6 class="element-header">Add Product</h6>

                        <div class="col-md-12 element-box" style="border:solid 1px #efefef; padding:3% 5%;">
                            <form action="" method="post" id="validation">
                                <div class="form-group">
                                    <label for=""> Item Name <span class="text-danger">*</span></label>
                                    <input class="form-control" placeholder="Item Name" id="name" type="text"
                                           required="">
                                    <span id="error1"></span>
                                </div>
                                <div class="form-group">
                                    <label for=""> Item Size <span class="text-danger">*</span></label>
                                    <input class="form-control" placeholder="Item Size" id="size" type="text"
                                           required="">
                                    <span id="error8"></span>
                                </div>
                                <div class="form-group">
                                    <label> Short Description <span class="text-danger">*</span></label>
                                    <textarea class="form-control" rows="3" id="Description" required=""></textarea>
                                    <span id="error2"></span>
                                </div>
                                <div class="form-group">
                                    <label> Full Description <span class="text-danger">*</span></label>
                                    <textarea class="form-control" rows="3" id="fulldescription" required=""></textarea>
                                    <span id="error3"></span>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for=""> Sales Tax <span class="text-danger">*</span></label>
                                            <input class="form-control" placeholder="Sales Tax" type="text" id="tax">
                                            <span id="error4"></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group no-margin">
                                            <label for="">Price <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="input-group">
                                            <div class="input-group-addon">$</div>
                                            <input class="form-control" placeholder="price" type="text" id="price" required=""/>
                                            <span id="error5"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group no-margin">
                                            <label for="">Mother's category. <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="input-group"><select class="form-control" id="rack" required="">
                                                <option value="">Select Category</option>
                                            </select>
                                            <span id="error7"></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for=""> Fluid Ounce</label>
                                            <input class="form-control" placeholder="Fluid Ounce" type="text" id="fluid_ounce">
                                            <span id="error8"></span>
                                        </div>
                                    </div>
                                </div>
                        </div>
                                <div class="row no-margin" id="marge">
                                    <!-- div class="col-md-2 no-pl upload-item-img" >
                                    <div class="dropzone no-pad">
                                    <div class="dz-message no-pad">
                                        <div class="thumbnail no-margin">
                                           <label class="btn-bs-file btn btn-primary upload-btn">
                                                upload image
                                                <input type="file" id="image[]"/>
                                            </label>
                                        </div>
                                        </div>
                                    </div>
                                </div> -->
                                    <div class="col-md-2 no-pl upload-item-img">
                                        <div class="dropzone no-pad">
                                            <div class="dz-message no-pad">
                                                <div class="thumbnail no-margin" id="file_div">
                                                    <label class="btn-bs-file btn btn-primary upload-btn">
                                                        Add image <span class="text-danger">*</span>
                                                        <input type="file" name="files[]" id="files" multiple=""/>
                                                    </label>

                                                    <!--<input type="file" name="files[]" id="files" multiple="" />
                                                   <input type="button" class='btn btn-info1'  onclick="add_file();" value="ADD MORE">-->


                                                </div>
                                            </div>

                                        </div>

                                    </div>

                                    <!-- <div class="col-md-2 no-pl upload-item-img" >
													<div class="dropzone no-pad">
													<div class="dz-message no-pad">
														<div class="thumbnail no-margin">
														   <img src="<?php echo base_url(); ?>img/portfolio5.jpg" alt="Lights" style="width:100%">
														</div>
													</div>	
													<span class="img-close"> x </span>
													</div>
												</div> -->
                                    <!--
                                    <div class="dropzone no-pad col-md-2" style="min-height:120px !important; margin-bottom:10px;display:none;" id="my-awesome-dropzone">
                                        <div class="dz-message no-pad">
                                            <div>
                                                <div class="thumbnail no-margin" style="min-height:125px;">
                                                  <a href="#">
                                                    <!--<img src="img/portfolio5.jpg" alt="Lights" style="width:100%">--
                                                    <p class="text-center" style="line-height:100px;"> Default image </p>
                                                  </a>
                                                </div>

                                                 <label class="btn-bs-file btn btn-primary">
                                                    Add more
                                                    <input type="file" />
                                                </label>
                                            </div>
                                        </div>
                                    </div>-->
                                </div>
                                <span id="error6"></span>

                                <div class="form-buttons-w">
                                    <button class="btn btn-primary" type="button" id="click"> Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>


                </div>

            </div>

        </div>
    </div>
</div>
</div>
<div class="display-type"></div>
</div>

<div aria-hidden="true" aria-labelledby="myLargeModalLabel" class="modal fade product-image" role="dialog"
     tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Product Image</h5>
                <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span
                        aria-hidden="true"> &times;</span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-3 col-lg-3 ">
                        <div class="thumbnail">
                            <a href="<?php echo base_url(); ?>img/portfolio2.jpg">
                                <img src="<?php echo base_url(); ?>img/portfolio2.jpg" alt="Lights" style="width:100%">

                                <div class="caption">
                                    <p class="no-margin">Item Name</p>
                                </div>
                            </a>
                        </div>
                    </div>


                    <div class="col-md-3">
                        <div class="thumbnail">
                            <a href="<?php echo base_url(); ?>img/portfolio3.jpg">
                                <img src="<?php echo base_url(); ?>img/portfolio3.jpg" alt="Lights" style="width:100%">

                                <div class="caption">
                                    <p class="no-margin">Item Name</p>
                                </div>
                            </a>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="thumbnail">
                            <a href="<?php echo base_url(); ?>img/portfolio4.jpg">
                                <img src="<?php echo base_url(); ?>img/portfolio4.jpg" alt="Lights" style="width:100%">

                                <div class="caption">
                                    <p class="no-margin">Item Name</p>
                                </div>
                            </a>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="thumbnail">
                            <!-- <a href="img/portfolio5.jpg">
                              <img src="img/portfolio5.jpg" alt="Lights" style="width:100%"> -->
                            <div class="caption">
                                <p class="no-margin">Item Name</p>
                            </div>
                            </a>
                        </div>
                    </div>


                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal" type="button"> Close</button>
                <button class="btn btn-primary" type="button"> Save changes</button>
            </div>
        </div>
    </div>
</div>
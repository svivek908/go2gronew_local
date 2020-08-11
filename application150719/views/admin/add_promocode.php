<style>
    input[type="file"] {
        display: block;
    }
    .validation_error {
        color:red
    }
</style>
<div class="content-w">
    <!--------------------
    START - Breadcrumbs
    -------------------->
    <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>/Admin">Home</a>
        </li>
        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>/Admin/viewpromocodes">Promocodes</a>
        </li>
        <li class="breadcrumb-item"><span>Add Promocode</span>
        </li>
    </ul>
    <!--------------------
    END - Breadcrumbs
    -------------------->
    <!-- <div class="content-panel-toggler"><i class="os-icon os-icon-grid-squares-22"></i><span>Sidebar</span>
     </div>-->
    <div class="content-i">
        <div class="content-box">
            <div class="row">
                <div class="col-sm-12">
                    <div class="element-wrapper">
                        <h6 class="element-header">Add Promocode</h6>
                        <div class="col-md-12 element-box" style="border:solid 1px #efefef; padding:3% 5%;">
                            <form action="" method="post" id="validation">
                                <div class="form-group">
                                    <label for="">Code *</label>
                                    <input class="form-control" placeholder="" id="code" type="text" value="" required>
                                    <span class="validation_error" id="error_code"></span>
                                </div>

                                <div class="form-group">
                                    <label for="">Code Type *</label>
                                    <select class="form-control" id="ctype">
                                        <option value="fixed">Fixed</option>
                                        <option value="percentage">Percentage</option>
                                    </select>
                                    <span class="validation_error" id="error_ctype"></span>
                                </div>

                                <div class="form-group">
                                    <label for="">Value *</label>
                                    <input class="form-control"  placeholder="" id="cvalue" type="number" value="1" min="0">
                                    <span class="validation_error" id="error_cvalue"></span>
                                </div>

                                <div class="form-group">
                                    <label for="">Max Discount Amount (Only applicable for type - "Percentage")</label>
                                    <input class="form-control" placeholder="" id="max_discount_amount" type="number" min="0">
                                    <span class="validation_error" id="error_max_discount_amount"></span>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="">Start date *</label>
                                            <input class="form-control" placeholder="" type="text" id="start_date">
                                            <span class="validation_error" id="error_start_date"><!-- <input class="single-daterange form-control" placeholder="" type="text" id="start_date">
                                            <span class="validation_error" id="error_start_date"> --></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="">End date *</label>
                                        <div class="input-group">
                                            <input class="form-control" placeholder="" type="text" id="end_date"><!-- <input class="single-daterange11 form-control" placeholder="" type="text" id="end_date"> -->
                                            <span class="validation_error" id="error_end_date"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="">Times code valid for an user *</label>
                                    <input class="form-control" id="per_user_allowed" type="number" value="1" min="0">
                                    <span class="validation_error" id="error_per_user_allowed"></span>
                                </div>
                                <div class="form-group">
                                    <label for="">Minimum order size</label>
                                    <input class="form-control" placeholder="" id="minimum_order_size" type="number" min="0">
                                    <span class="validation_error" id="error_del_charge"></span>
                                </div>

                                 <div class="form-group">
                                    <label for="">Description</label>
                                    <textarea class="form-control" placeholder="" id="description"></textarea>
                                </div>
                        </div>

                        <div class="form-buttons-w">
                            <button class="btn btn-primary" type="button" id="add_promocode_submit"> Submit</button>
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

<div id="upload_logoimage_modal" class="modal" role="dialog">
    <div class="modal-dialog" style="min-width:530px">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Upload & Crop Image</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-8 text-center">
                        <center><div id="logoimage_modal" style="width:350px;"></div></center>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success crop_logoimage">Crop & Upload Image</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div id="upload_bannerimage_modal" class="modal" role="dialog">
    <div class="modal-dialog" style="min-width:1300px">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Upload & Crop Image</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-8 text-center">
                        <center><div id="bannerimage_modal" style="width:1200px;"></div></center>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success crop_bannerimage">Crop & Upload Image</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>



<script>
    $(document).ready(function () {
        //-------------------
        $( "#start_date" ).datepicker().datepicker('setDate',new Date());
        var date2 = $('#start_date').datepicker('getDate', '+1d'); 
        date2.setDate(date2.getDate()+1); 
        $( "#end_date" ).datepicker().datepicker('setDate',date2);
        //-----------------------
        $("#add_promocode_submit").click(function () {
            var code = $("#code").val();
            var ctype = $("#ctype").val();
            var cvalue = $("#cvalue").val();
            var max_discount_amount = $("#max_discount_amount").val();
            var start_date = $("#start_date").val();
            var end_date = $("#end_date").val();
            var per_user_allowed = $("#per_user_allowed").val();
            var minimum_order_size = $("#minimum_order_size").val();
            var description = $("#description").val();

            if (code == "") {
                $("#error_code").html("* Code is a required field.");
                return false;
            }
            if (cvalue == "") {
                $("#error_cvalue").html("* Value is a required field.");
                return false;
            }

            var formdata = new FormData();

            var api_key = "<?= $this->session->get_userdata('user')['user']->user->api_key; ?>";
            formdata.append('Authorization', api_key);
            formdata.append('code', code);
            formdata.append('ctype', ctype);
            formdata.append('cvalue', cvalue);
            formdata.append('max_discount_amount', max_discount_amount);
            formdata.append('start_date', start_date);
            formdata.append('end_date', end_date);
            formdata.append('per_user_allowed', per_user_allowed);
            formdata.append('minimum_order_size', minimum_order_size);
            formdata.append('description', description);

            jQuery("#fullLoader").show();
            $.ajax({
                method: "POST",
                url: "<?php echo base_url();?>Admin/save_promocode",
                data: formdata,
                contentType: false,
                processData: false,
                success: function (data) {
                    jQuery("#fullLoader").hide();
                    console.log(data);
                    if (data.error) {
                        swal("Oops!", data.message, "error");
                    } else {
                        swal({
                                title: "Success",
                                type: 'success',
                                text: "Promocode added successfully!",
                                closeOnConfirm: false,
                                animation: "slide-from-top"
                            },
                            function () {
                                //location.reload();
                                window.location.href = "<?php echo site_url('Admin/viewpromocodes');?>";
                            });
                    }
                }
            });
        });
    });
</script>
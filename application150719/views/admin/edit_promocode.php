<?php
$user = $this->session->get_userdata('go2groadmin_session');
$api_key = $user['go2groadmin_session']['logged_user_api_key'];
?>
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
        <li class="breadcrumb-item"><span>Edit Promocode</span>
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
                        <h6 class="element-header">Edit Promocode</h6>
                        <div class="col-md-12 element-box" style="border:solid 1px #efefef; padding:3% 5%;">
                            <form action="" method="post" id="validation">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label for="">End date *</label>
                                        <div class="input-group">
                                            <input type="hidden" name="p_id" id="p_id" value="<?php echo $id;?>">
                                            <input class="form-control" placeholder="" type="text" id="end_date" value="<?php echo $end_date;?>"><!-- <input class="single-daterange11 form-control" placeholder="" type="text" id="end_date"> -->
                                            <span class="validation_error" id="error_end_date"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="">Times code valid for an user *</label>
                                    <input class="form-control" id="per_user_allowed" type="number"  min="0" value="<?php echo $allowed_per_user;?>">
                                    <span class="validation_error" id="error_per_user_allowed"></span>
                                </div>
                                <div class="form-group">
                                    <label for="">Minimum order size </label>
                                    <input class="form-control" placeholder="" id="minimum_order_size" type="number" min="0" value="<?php echo $min_order_amount;?>">
                                    <span class="validation_error" id="error_del_charge"></span>
                                </div>

                                <!-- <div class="form-group">
                                    <label for="">Max Discount Amount (Only applicable for type - "Percentage")</label>
                                    <input class="form-control" placeholder="" id="max_discount_amount" type="number" min="0" value="<?php //echo $max_discount_amount;?>">
                                    <span class="validation_error" id="error_max_discount_amount"></span>
                                </div> -->
                        </div>

                        <div class="form-buttons-w">
                            <button class="btn btn-primary" type="button" id="edit_promocode_submit"> Submit</button>
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

<script>
    $(document).ready(function () {
        //-------------------
        $( "#end_date" ).datepicker();
        //-----------------------
        $("#edit_promocode_submit").click(function () {
            /*var code = $("#code").val();
            var ctype = $("#ctype").val();
            var cvalue = $("#cvalue").val();
            var start_date = $("#start_date").val();
            var description = $("#description").val();*/
            var p_id = $("#p_id").val();
            var per_user_allowed = $("#per_user_allowed").val();
            var minimum_order_size = $("#minimum_order_size").val();
            //var max_discount_amount = $("#max_discount_amount").val();
            var end_date = $("#end_date").val();

            if (end_date == "") {
                $("#error_code").html("* End date is a required field.");
                return false;
            }

            var formdata = new FormData();

            var api_key = "<?php echo $api_key; ?>";
            formdata.append('Authorization', api_key);
            formdata.append('end_date', end_date);
            formdata.append('p_id', p_id);
            //formdata.append('max_discount_amount', max_discount_amount);
            formdata.append('per_user_allowed', per_user_allowed);
            formdata.append('minimum_order_size', minimum_order_size);
            /*formdata.append('code', code);
            formdata.append('ctype', ctype);
            formdata.append('cvalue', cvalue);
            formdata.append('max_discount_amount', max_discount_amount);
            formdata.append('start_date', start_date);
            formdata.append('per_user_allowed', per_user_allowed);
            formdata.append('minimum_order_size', minimum_order_size);
            formdata.append('description', description);*/
            jQuery("#fullLoader").show();
            $.ajax({
                method: "POST",
                url: "<?php echo base_url('Admin_control/update_promocode');?>",
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
                                text: "Promocode update successfully!",
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
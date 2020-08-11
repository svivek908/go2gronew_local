<?php
$user = $this->session->get_userdata('go2groadmin_session');
$api_key = $user['go2groadmin_session']['logged_user_api_key'];
?>
<style>
    .element-box, .invoice-w, .big-error-w {
        padding: 7% 2px;
        margin-bottom: 1rem;
    }

    div#orderTable_info {
        padding-left: 3%;
    }

    .text-center a {
        color: white;
    }

</style>
<script>
    $(document).ready(function () {
        var api_key = "<?php echo $api_key;?>";
        $("#loader").show();
        jQuery.ajax({
            type: 'GET',
            url: '<?php echo site_url('Admin/promocodes_list');?>',
            data: {'Authorization': api_key},
            success: function (res) {
                $("#loader").hide();
                console.log(res);//all_promocodes
                var promocodelist = res.all_promocodes;
                var jsonlength = promocodelist.length;
                var html = ''; 
                for (var i = 0; i < jsonlength; i++) {
                    var Status ='';
                    var result = promocodelist[i];
                    console.log(result.id);
                    console.log(result.code);
                    var date="1501501639";
                    var dateString = unitimeToTime(result.start_date);
                    var enddateString = unitimeToTime(result.end_date);
                    var alternatOrderStatus;
                   if(result.status == 'active'){
                       Status = '<a href="javascript:void(0);" class="btn btn-success" onclick="changestatuspromocode('+result.id+');"> Active </a>';
                   } else{
                        Status = '<a href="javascript:void(0);" class="btn btn-danger" onclick="changestatuspromocode('+result.id+');"> Inactive </a>';
                   }

                    html += '<tr>\
                                  <td class="text-center">'+ dateString + '</td>\
                                  <td class="text-center">'+ enddateString + '</td>\
                                  <td class="word-wrap">' + result.code + '</td>\
                                  <td class="word-wrap">' + result.min_order_amount + '</td>\
                                  <td class="text-center">' + result.allowed_per_user + '</td>\
                                  <td class="text-center">' + Status + '</td>\
                                  <td class="text-center"><a href="<?php echo base_url('Admin_control/edit_promocode/');?>'+result.id+'" class="btn btn-info"> Edit </a> <a href="javascript:void(0);" class="btn btn-danger" onclick="delete_code('+result.id+');"> Delete </a></td></tr>';
                }
                $('#data-list2').html(html);
                $('#promocodeTable').dataTable({
                    //"order": [[5, "desc"]]
                });
            }
        });
    });

    function changestatuspromocode(id) {
        var api_key = "<?php echo $api_key;?>";
        swal({
            title: "",
            text: "Are you sure? You want to change promocode status",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: 'Yes, I am sure!',
            cancelButtonText: "No, cancel it!",
            closeOnConfirm: false,
            closeOnCancel: false
        },
        function(isConfirm){
           if (isConfirm){
                jQuery.ajax({
                    type: 'POST',
                    url: '<?php echo site_url('Admin/change_promocode_status'); ?>',
                    data: {'Authorization': api_key, 'id': id},
                    success: function (res) {
                        console.log(res);
                        if (res.error) {
                            swal("Error!", "Promocode status not chnage", "error");
                        } else {
                            swal({
                                title: "Success!",
                                text: res.message,
                                type: 'success',
                                closeOnConfirm: true
                            },
                            function () {
                                location.reload();
                            });
                        }
                    }
                });
            } else {
              swal("Cancelled", "", "error");
                 e.preventDefault();
            }
        });
    }

    function delete_code(id) {
        var api_key = "<?php echo $api_key;?>";
        swal({
                title: "",
                text: "Are you sure? You want to delete promocode",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes, I am sure!',
                cancelButtonText: "No, cancel it!",
                closeOnConfirm: false,
                closeOnCancel: false
            },
            function(isConfirm){
               if (isConfirm){
                    jQuery.ajax({
                        type: 'POST',
                        url: '<?php echo site_url('Admin_control/deletepromocode'); ?>',
                        data: {'Authorization': api_key, 'id': id},
                        success: function (res) {
                            console.log(res);
                            if (res.error) {
                                swal("Error!", res.message, "error");
                            } else {
                                swal({
                                    title: "Success!",
                                    text: res.message,
                                    type: 'success',
                                    closeOnConfirm: true
                                },
                                function () {
                                    location.reload();
                                });
                            }
                        }
                    });
                } else {
                  swal("Cancelled", "", "error");
                     e.preventDefault();
                }
            });
    }

</script>
<div class="content-w">
    <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>Admin">Home</a>
        </li>
        <li class="breadcrumb-item">Promocode list
        </li>
    </ul>
    <!--------------------
}
END - Breadcrumbs
-------------------->
   <!-- <div class="content-panel-toggler"><i class="os-icon os-icon-grid-squares-22"></i><span>Sidebar</span>
    </div>-->
    <div class="content-i">
        <div class="content-box">

            <div class="row">
				
			
                <div class="col-sm-12">
                    <div class="element-wrapper">
                        <h6 class="element-header"> 
							<span> Promocodes </span>
						</h6>
					
                        <?php //include('order_menubar.php'); ?>
                        <div class="element-box" style="padding-top:4%; padding-bottom: 100px; position:relative;">
                            <div class="loader" id="loader"></div>
                            <div class="pull-right" id="" style="padding-bottom: 22px;"><a href="<?php echo base_url('Admin_control/add_promocode'); ?>" class="btn btn-info">Add Promocode</a></div>
                            <div class="table-responsive order-table">
                                <table class="table table-lightborder" id="promocodeTable">
                                    <thead>
                                    <tr>
                                        <th>Start Date/Time</th>
                                        <th>Expiry Date/Time</th>
                                        <th>Promocode</th>
                                        <th>Min. order amount</th>
                                        <th>Allowed per user</th>
                                        <th>Change status to</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody id="data-list2">
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
</div>
</div>
</div>

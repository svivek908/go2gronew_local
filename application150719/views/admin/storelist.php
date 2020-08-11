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
            url: '<?php echo site_url('Admin/getallstores');?>',
            data: {'Authorization': api_key},
            success: function (res) {
                $("#loader").hide();
                console.log(res);//all_promocodes
                var all_storelist = res.all_store;
                var jsonlength = all_storelist.length;
                var html = '';
                var Status ='';
                for (var i = 0; i < jsonlength; i++) {
                    var logo =''; var banner ='';
                    var result = all_storelist[i];
                    if(result.logo != ''){
                       logo = '<img src="<?= $this->config->item('api_url_image')?>'+result.logo+'" style="height: 60px;border-radius: 20px;">';
                    }
                    if(result.banner != ''){
                       banner = '<img src="<?= $this->config->item('api_url_image')?>'+result.banner+'" style="height: 60px;border-radius: 20px;">';
                    }


                  if(result.status == 'inactive'){
                       Status = '<a href="javascript:void(0);" class="btn btn-success" onclick="changestatusstore('+result.id+');"> Active </a>';
                   } else{
                        Status = '<a href="javascript:void(0);" class="btn btn-danger" onclick="changestatusstore('+result.id+');"> Inactive </a>';
                   }


                    html += '<tr>\
                            <td class="text-center">'+ result.created_at + '</td>\
                            <td class="word-wrap">' + logo + '</td>\
                            <td class="text-center">' + banner + '</td>\
                            <td class="text-center">' + result.name + '</td>\
                            <td class="text-center">' + result.zipcode + '</td>\
                            <td class="text-center">' + result.delivery_charge + '</td>\
                            <td class="text-center">' + result.free_delivery_amount + '</td>\
                            <td class="text-center">' + Status + '</td>\
                            <td class="text-center"><a href="<?php echo site_url('Admin/editstore/');?>'+ result.id +'" class="edit-item-btn green-bg"> Edit </a></td></tr>';
                }
                $('#data-storelist').html(html);
                $('#storeTable').dataTable({
                    //"order": [[5, "desc"]]
                });
            }
        });
    });

    function changestatusstore(id) {
        var api_key = "<?php echo $api_key;?>";
        swal({
            title: "",
            text: "Are you sure? You want to change store status",
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
                    url: '<?php echo site_url('Admin/change_store_status'); ?>',
                    data: {'Authorization': api_key, 'id': id},
                    success: function (res) {
                        console.log(res);
                        if (res.error) {
                            swal("Error!", "Please add store item and itemlink data", "error");
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
        <li class="breadcrumb-item">Store list
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
							<span> Stores </span>
						</h6>
					
                        <?php //include('order_menubar.php'); ?>
                        <div class="element-box" style="padding-top:4%; padding-bottom: 100px; position:relative;">
                            <div class="loader" id="loader"></div>
                            <div class="pull-right" id="" style="padding-bottom: 22px;"><a href="<?php echo base_url('Admin/addstore'); ?>" class="btn btn-info">Add Store</a></div>
                            <div class="table-responsive order-table">
                                <table class="table table-lightborder" id="storeTable">
                                    <thead>
                                    <tr>
                                        <th>Date/Time</th>
                                        <th>Logo</th>
                                        <th>Banner</th>
                                        <th>Name</th>
                                        <th>zipcode</th>
                                        <th>Delivery_charge</th>
                                        <th>Free delivery amount</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody id="data-storelist">
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

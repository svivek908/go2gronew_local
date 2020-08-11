<?php
$user = $this->session->get_userdata('user');
$api_key = $user['user']->user->api_key;
?>

<!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<link rel="stylesheet" href="http://cdn.datatables.net/1.10.2/css/jquery.dataTables.min.css">
<script type="text/javascript" src="http://cdn.datatables.net/1.10.2/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>-->
<script>
    $(document).ready(function () {
        var api_key = "<?php echo $api_key;?>";

       /* $("#loader").show();
        jQuery.ajax({
            type: 'GET',
            url: '<?php echo base_url();?>Admin/getActiveProduct',
            data: {'Authorization': api_key},
            success: function (res) {
                $("#loader").hide();
                //  console.log(res);
                var itemlist = res.item;
                var jsonlength = itemlist.length;
                var html = '';
                for (var i = 0; i < jsonlength; i++) {
                    var result = itemlist[i];
                    html += '<tr>\
                            <td class="nowrap sorting_1"">' + result.item_id + '</td>\
                            <td>\
                                <div class="cell-image-list"  data-target=".product-image" data-toggle="modal">\
                                   <div class="cell-img" style="background-image: url(<?php echo $this->config->item('api_url_image');?>' + result.item_image + ')"></div>\
                                   </div>\
                            </td>\
                            <td class="word-wrap">' + result.item_name + '</td>\
                            <td class="word-wrap">\
                                <p class="no-margin">' + result.item_sdesc + '</p>\
                            </td>\
                            <td class="word-wrap">' + result.item_fdesc + '</td>\
                            <td class="text-right">' + result.item_price.toFixed(2) + '</td>\
                            <td class="text-center">\
                            <div class="status-pill ' + (result.item_status == 0 ? 'green' : 'red') + '" data-title="Complete" id="data3" data-toggle="tooltip"><input type="hidden" id="status" value="' + result.item_status + '"/></div>\
                            </td>\
                            <td> ' + (result.discount == 0 ? '<a id="btn_'+result.item_id+'" href="javascript:void(0)" class="edit-item-btn green-bg" onclick="addremvbestseller(\''+result.item_id+'\', -1)"> Add </a>' : '<a id="btn_'+result.item_id+'" href="javascript:void(0)"  class="edit-item-btn blue-bg" onclick="addremvbestseller(\''+result.item_id+'\', 0)"> Remove </a>') + '</td>\
                        </tr>';
                }

                $('#data-list').html(html);
                $('#myTable').dataTable();
            }
        });*/
        var dataTable = $('#myTable').DataTable( {
            "processing": true,
            "serverSide": true,
            "pageLength": 50,
            "columnDefs": [
                { className: "nowrap sorting_1", "targets": [ 0 ] },
                { className: "word-wrap", "targets": [ 3,4 ] },
                { className: "text-right", "targets": [ 5 ] },
                { className: "text-center", "targets": [ 6 ] }
            ],
            "columns": [
                { "orderable": false },
                { "orderable": false },
                null,
                { "orderable": false },
                { "orderable": false },
                null,
                { "orderable": false },
                { "orderable": false }
            ],
            "ajax":{
                url :"<?php echo base_url();?>Admin/getActiveProduct", // json datasource
                type: "post",  // method  , by default get
                data: {'Authorization': api_key},
                error: function(){  // error handling
                    $(".myTable-error").html("");
                    $("#myTable").append('<tbody class="myTable-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                    $("#myTable_processing").css("display","none");

                }

            }
        } );

    });

    function addremvbestseller(itemId, status){
        //console.log(event.target);
        var api_key = "<?php echo $api_key;?>";
        var message;
        var successMsg;
        var btn;
        if(status == -1){
            message = "You want to add this product in bestseller";
            successMsg = "Product successfully added in bestseller";
            btn = '<a id="btn_'+itemId+'" href="javascript:void(0)"  class="edit-item-btn blue-bg" onclick="addremvbestseller(\''+itemId+'\', 0)"> Remove </a>';
        }else{
            message ="You want to remove this product from bestseller";
            successMsg = "Product successfully removed from bestseller";
            btn = '<a id="btn_'+itemId+'" href="javascript:void(0)" class="edit-item-btn green-bg" onclick="addremvbestseller(\''+itemId+'\', -1)"> Add </a>';
        }
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
                url: '<?php echo base_url();?>Admin/addBestSeller',
                data: {'Authorization': api_key, 'item_id':itemId, 'status':status},
                success: function (res) {
                    if(!res.error){
                        swal('Success!', successMsg, 'success');
                        jQuery('#btn_'+itemId).replaceWith(btn);
                    }else{
                        swal('Error!', 'Please try again. Product not updated', 'error');
                    }
                }
            });
        });

    }
</script>
<div class="content-w">

    <ul class="breadcrumb">
        <li class="breadcrumb-item">Best Seller
        </li>
    </ul>
    <!--------------------
END - Breadcrumbs
-------------------->
    <!--<div class="content-panel-toggler"><i class="os-icon os-icon-grid-squares-22"></i><span>Sidebar</span>
    </div>-->
    <?php include 'inpage_store_select.php'; ?>
    <div class="content-i">
        <div class="content-box">
            <div class="row">
                <div class="col-sm-12">

                    <div class="element-wrapper">
                        <!-- <div class="element-actions">
                                <form class="form-inline justify-content-sm-end">
                                    <select class="form-control form-control-sm rounded">
                                        <option value="Pending">Today</option>
                                        <option value="Active">Last Week </option>
                                        <option value="Cancelled">Last 30 Days</option>
                                    </select>
                                </form>
                            </div> -->
                        <h6 class="element-header">Best Seller</h6>

                        <div class="element-box" style="padding-bottom: 100px; position:relative;">

                            <!--<div class="loader" id="loader"></div>-->
                            <div class="table-responsive">
                                <table class="table table-lightborder" id="myTable">
                                    <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Product Image</th>
                                        <th style="width:100px;">Products Name</th>
                                        <th>Short Details</th>
                                        <th>Full Details</th>
                                        <th class="text-right">Price</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Best seller</th>

                                    </tr>
                                    </thead>
                                    <!--<tbody id="data-list">

                                    </tbody>-->
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

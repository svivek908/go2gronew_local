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

    function link(orderid) {

        window.location = '<?php echo base_url();?>Admin/checkstatus/' + orderid + '';
    }

    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip(); 
        $("#loader").show();
         neworders();
    });
    
    //----new order
    function neworders(page_num){
        //var search = $('#emp-search').val();
        page_num = page_num?page_num:0;
        jQuery.ajax({
            type: 'POST',
            url: '<?php echo base_url('Admin/neworders');?>'+'/'+page_num,
            data: {page:page_num},
            dataType:"json",
            success: function (res) {
                $("#loader").hide();
                //console.log(res.order);
                if(res.error == false){
                    $("#data-list2").html(res.page);
                }
            }
        }); 
    }

    //----delivered order
    function deleverorder(page_num){
        //var search = $('#emp-search').val();
        page_num = page_num?page_num:0;
        jQuery.ajax({
            type: 'POST',
            url: '<?php echo base_url('Admin/deleverorder');?>'+'/'+page_num,
            data: {page:page_num},
            dataType:"json",
            success: function (res) {
                $("#loader").hide();
                //console.log(res.order);
                if(res.error == false){
                    $("#data-list2").html(res.page);
                }
            }
        }); 
    }

    //----rejectorder order
    function rejectorder(page_num){
        //var search = $('#emp-search').val();
        page_num = page_num?page_num:0;
        jQuery.ajax({
            type: 'POST',
            url: '<?php echo base_url('Admin/rejectorder');?>'+'/'+page_num,
            data: {page:page_num},
            dataType:"json",
            success: function (res) {
                $("#loader").hide();
                //console.log(res.order);
                if(res.error == false){
                    $("#data-list2").html(res.page);
                }
            }
        }); 
    }

    //----cancelorder order
    function cancelorder(page_num){
        //var search = $('#emp-search').val();
        page_num = page_num?page_num:0;
        jQuery.ajax({
            type: 'POST',
            url: '<?php echo base_url('Admin/cancelorder');?>'+'/'+page_num,
            data: {page:page_num},
            dataType:"json",
            success: function (res) {
                $("#loader").hide();
                //console.log(res.order);
                if(res.error == false){
                    $("#data-list2").html(res.page);
                }
            }
        }); 
    }

   
    function selectstatusmain(oldstatus, status, orderid) {
        var status = parseInt(status);
        var statusnew = getTextStatus(status);

        var statusold = getTextStatus(oldstatus);

        var api_key = "<?php //echo $api_key;?>";
        swal({
                title: "",
                text: "Are you sure? You want to change status " + statusold + " to " + statusnew,
                type: "input",
                showCancelButton: true,
                closeOnConfirm: false,
                animation: "slide-from-top",
                inputPlaceholder: "Write something",
                showLoaderOnConfirm: true
            },
            function (inputValue) {
                if (inputValue === false) return false;
                if (status == 5) {
                    if (inputValue === "") {
                        swal.showInputError("Please Enter Reason For Reject Order!");
                        return false
                    }
                }

                var message = inputValue;
                if (message == 0) {
                    var message = "no message";
                }
                $("#selectstatus_" + orderid + " option").each(function () {
                    var $thisOption = $(this);
                    /*var $value1 =$(this).text();
                     console.log($value1);
                     */
                    if ($thisOption.val() == status) {
                        $thisOption.attr("disabled", "disabled");
                        $thisOption.next('option').prop("disabled", false);
                    }
                });

                jQuery.ajax({
                    type: 'POST',
                    url: '<?php echo base_url();?>Admin/checkorderstatus',
                    data: {'Authorization': api_key, 'orderid': orderid, 'status': status, 'message': message},
                    success: function (res) {
                        console.log(res);
                        if (res.error) {
                            swal("Erorr!", "Status not updated successfully", "error");
                        } else {
                            var message;
                            if(res.paymentbyauth){
                                     message = 'Status updated successfully and Order amount captured successfully';
                            }else{
                                     message = 'Status updated successfully';
                            }
                            if(res.isrefund==true){
                                     message = 'please refund your payment by going refund payment page.';
                            }else if(res.isvoid==true){
                                 message = 'your payment voided successfully';
                            }
                            swal({
                                    title: "Success!",
                                    text: message,
                                    type: 'success',
                                    closeOnConfirm: true
                                },
                                function () {

                                    if (res.currentstatus == 4 || res.currentstatus == 5) {
                                        $('#order_id_' + orderid).remove();
                                    }

                                    var changeStatus = getTextStatus(res.currentstatus);
                                    $("#cstatus_" + orderid).html(changeStatus);

                                    $("#selectstatus option").each(function () {
                                        var $thisOption = $(this);
                                        /*var $value1 =$(this).text();
                                         console.log($value1);
                                         */
                                        if ($thisOption.val() == res.currentstatus) {
                                            $thisOption.attr("disabled", "disabled");
                                            $thisOption.next('option').prop("disabled", false);
                                            $thisOption.prop("selected", true);
                                        }
                                    });
                                });

                        }
                    }

                });
            });
    }

</script>
<div class="content-w">
    <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>Admin">Home</a>
        </li>
        <li class="breadcrumb-item">Order List
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
                            <span> Order </span>
                        </h6>
                    
                        <?php $this->load->view('admin/include/order_menubar'); ?>
                        <div class="element-box" style="padding-top:4%; padding-bottom: 100px; position:relative;">
                            <div class="loader" id="loader"></div>
                            <div class="pull-right"><input type="text" name="search" class="form-control" onkeyup="neworders();" id="search"></div>
                            <div class="table-responsive order-table" id="data-list2">
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

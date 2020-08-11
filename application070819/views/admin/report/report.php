<div class="content-w" style="width:100%">
    <!--------------------START - Breadcrumbs ------------------>
    <ul class="breadcrumb">
        <li class="breadcrumb-item"><span>Report</span>
        </li>
    </ul>
    <!----------------END - Breadcrumbs ----------------->
    <!--<div class="content-panel-toggler"><i class="os-icon os-icon-grid-squares-22"></i><span>Sidebar</span>
    </div>-->
    <div class="content-i">
        <div class="content-box">
            <div class="row">
                <div class="col-sm-12">
                    <div class="element-wrapper">
                        <h6 class="element-header">Download Report</h6>

                        <div class="col-md-12 element-box" style="border:solid 1px #efefef; padding:3% 5%;">
                            <form action="" method="post">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for=""> From</label>
                                            <input id="tdate" class="single-daterange form-control" placeholder="From"
                                                   type="text">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="">To</label>
                                            <input id="fdate" class="single-daterange form-control" placeholder="To"
                                                   type="text">
                                        </div>

                                    </div>

                                    <div class="form-group" style="width:100%;">
                                        <div class="row no-margin">
                                            <div class="col-md-2">
                                                <div class="form-check">
                                                    <select name="stores" id="stores" class="form-control">
                                                        <option value="All">All</option>
                                                        <?php if($all_stores){
                                                            foreach($all_stores as $store){?>
                                                                <option value="<?php echo $store['id'];?>"><?php echo $store['name'];?></option>
                                                        <?php } } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        <input checked="checked" class="form-check-input"
                                                               name="orderType" type="radio" value=0>
                                                        New Order
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        <input class="form-check-input" name="orderType" type="radio"
                                                               value=4>
                                                        Delivered Order
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-2 ">
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        <input class="form-check-input" name="orderType" type="radio"
                                                               value=5>
                                                        Rejected Order
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        <input class="form-check-input" name="orderType" type="radio"
                                                               value=6>
                                                        Cancelled Order
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-buttons-w">
                                    <button class="btn btn-primary" type="button" id="getReport"> Submit</button>
                                </div>
                            </form>
                            <div class="table-responsive" id="ordersData">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    jQuery("#getReport").click(function () {
        jQuery("#fullLoader").show();
        var tdate = jQuery("#tdate").val();
        var fdate = jQuery("#fdate").val();
        var orderStatus = jQuery("input[name='orderType']:checked").val();
        var startDate = new Date(tdate);
        startDate.setHours(0, 0, 0, 0);
        var fromUnitime = Math.floor(startDate.getTime() / 1000);
        var endDate = new Date(fdate);
        endDate.setHours(23, 59, 59, 59);
        var toUnitime = Math.floor(endDate.getTime() / 1000);
        var storeid = $('#stores').val();

        jQuery.ajax({
            type: 'GET',
            url: '<?php echo base_url();?>Admin/getReport',
            data: {'fdate': fromUnitime, 'tdate': toUnitime, 'status': orderStatus, 'storeid':storeid},
            dataType:"json",
            success: function (res) {
                jQuery("#fullLoader").hide();
                var html = '';
                var refundStatus;
                if (!res.error) {
                    var orderlist = res.order;
                    $('#ordersData').html(res.page);
                } else {
                    swal("No Record Found");
                }
            }
        });

        return false;
    });
</script>
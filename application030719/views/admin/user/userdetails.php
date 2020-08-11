<div class="content-w" style="width: 100%;">
    <!--------------------START - Breadcrumbs-------------->
    <ul class="breadcrumb">
        <li class="breadcrumb-item"><span>Users Detail</span>
        </li>
    </ul>
    <!-------------END - Breadcrumbs ------------>
    <!--<div class="content-panel-toggler"><i class="os-icon os-icon-grid-squares-22"></i><span>Sidebar</span>
    </div>-->
    <div class="content-i">
        <div class="content-box">
            <div class="row">
                <div class="col-sm-12">
                    <div class="element-wrapper">
                        <h6 class="element-header">Users Detail</h6>

                        <div class="col-md-12 element-box" style="border:solid 1px #efefef; padding:3% 5%;">

                            <div class="form-group" style="width:100%;">
                                <div class="row no-margin">
                                    <div class="col-md-2">
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input checked="checked" class="form-check-input" name="userType"
                                                       type="radio" value='all'>
                                                All Users
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input class="form-check-input" name="userType" type="radio"
                                                       value='datewise'>
                                                Date Wise
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="pull-right"><input type="text" name="search" class="form-control" onkeyup="userlist();" id="search"></div>
                            <form id="dateForm" style="display: none">
                                <div class="row">

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for=""> From</label>
                                            <input id="tdate" class="single-daterange form-control" placeholder="From"
                                                   type="text">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="">To</label>
                                            <input id="fdate" class="single-daterange form-control" placeholder="To"
                                                   type="text">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <button class="btn btn-primary" type="button" id="getUsers" onclick="userlist();" style="margin-top: 29px;">Submit
                                        </button>
                                    </div>
                                </div>
                            </form>
                            
                            <div class="table-responsive" id="usersData">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    jQuery(document).ready(function () {
        var type = jQuery("input[name='userType']:checked").val();
        if (type == 'all') {
           userlist();
        }
    });
    jQuery("input[name='userType']").click(function () {
        var userType = jQuery(this).val();
        if (userType == 'all') {
            jQuery('#dateForm').hide();
            userlist();
        }
        if (userType == 'datewise') {
            jQuery('#dateForm').show();
        }
    });

    function userlist(page_num){
        var search = $('#search').val();
        page_num = page_num?page_num:0;

        var userType = jQuery("input[name='userType']:checked").val();
        if (userType == 'all') {
            obj = {'utype': userType, 'fdate':'', 'tdate': '', search:search};
        }
        else if (userType == 'datewise') {
            var tdate = jQuery("#tdate").val();
            var fdate = jQuery("#fdate").val();
            var startDate = new Date(tdate);
            startDate.setHours(0, 0, 0, 0);
            var fromUnitime = Math.floor(startDate.getTime() / 1000);
            var endDate = new Date(fdate);
            endDate.setHours(23, 59, 59, 59);
            var toUnitime = Math.floor(endDate.getTime() / 1000);
            obj = {'utype': userType, 'fdate': fromUnitime, 'tdate': toUnitime, search:search}
        }

        jQuery.ajax({
            type: 'POST',
            url: '<?php echo base_url('Admin/getUsers');?>'+'/'+page_num,
            data: obj,
            dataType:"json",
            success: function (res) {
                jQuery("#fullLoader").hide();
                var html = '';
                var refundStatus;
                if (!res.error) {
                    $('#usersData').html(res.page);
                } else {
                    swal("No Record Found");
                }
            }
        });
    }
</script>
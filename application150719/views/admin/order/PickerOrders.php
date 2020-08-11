<?php
$user = $this->session->get_userdata('go2groadmin_session');
$api_key = $user['go2groadmin_session']['logged_user_api_key'];
?>


<div class="content-w" style="width: 100%">
    <!--------------------
START - Breadcrumbs
-------------------->
    <ul class="breadcrumb">
        <li class="breadcrumb-item"><span>Picker Order</span>
        </li>
    </ul>
    <!--------------------
END - Breadcrumbs
-------------------->
    <!--<div class="content-panel-toggler"><i class="os-icon os-icon-grid-squares-22"></i><span>Sidebar</span>
    </div>-->
    <div class="content-i">
        <div class="content-box">
            <div class="row">
                <div class="col-sm-12">
                    <div class="element-wrapper">
                        <h6 class="element-header">Picker Order</h6>

                        <div class="col-md-12 element-box" style="border:solid 1px #efefef; padding:3% 5%;">
                            <form action="" method="post">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for=""> Select Picker</label>
                                            <select id="picker_id" class="form-control"><option value="">Select Picker</option></select>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div class="table-responsive" id="pickerOrdersData">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    jQuery(document).ready(function(){
        getActivePicker();
    });

    jQuery("#picker_id").change(function () {
        jQuery("#fullLoader").show();
        var api_key = '<?php echo $api_key; ?>';
        var picker_id = $(this).val();
        jQuery.ajax({
            type: 'GET',
            url: '<?php echo base_url();?>Admin/getPickerOrder',
            data: {'Authorization': api_key, 'picker_id': picker_id},
            success: function (res) {
                jQuery("#fullLoader").hide();
                var html = '';
                if (!res.error) {
                    var orderlist = res.orderlist;
                    var jsonlength = orderlist.length;
                    html += '<table class="table table-lightborder" id="orderTable">\
                    <thead>\
                    <tr>\
                    <th>Order Id</th>\
                <th>Info </th>\
                <th>Address</th>\
                <th>Pincode</th>\
                <th>price</th>\
                <th>Delivery Time</th>\
                <th>Status</th>\
                </tr>\
                </thead>\
                <tbody>';

                    for (var i = 0; i < jsonlength; i++) {

                        var result = orderlist[i];

                        var dateString = unitimeToTime(result.delivery_time);

                        var shipping_address = '';
                        try {
                            var shipping_addr_json = JSON.parse(result.ship_address);
                            shipping_address = shipping_addr_json.street_address;
                            if(shipping_addr_json.apt_no != ''){
                                shipping_address += ', ' + shipping_addr_json.apt_no;
                            }
                            if(shipping_addr_json.complex_name != ''){
                                shipping_address += ', ' + shipping_addr_json.complex_name;
                            }
                        } catch (e) {
                            shipping_address = result.ship_address;
                        }

                        html += '<tr>\
                              <td class="nowrap"><a href="<?php echo base_url();?>Admin/checkstatus/' + result.order_id + '">' + result.order_id + '</a></td>\
                              <td class="word-wrap">' + result.ship_name + ' <p class="no-margin">' + result.ship_email + '</p> ' + result.ship_mobile_number + '</td>\
                              <td class="text-center tooltip111">' + shipping_address.substr(0, 15) + '...<span class="tooltiptext tooltip111-bottom">' + shipping_address +'</span></td>\
                              <td class="text-center" >' + result.ship_pincode + '</td>\
                              <td class="text-center">' + result.finalprice.toFixed(2) + '</td>\
                              <td class="text-center"><span style="display: none;">'+result.delivery_time+'</span>' + dateString + '</td>\
                              <td class="text-center">' + getTextStatus(result.order_status) + '</td>\
                           </tr>';
                    }
                    html += '</tbody></table>';
                    $('#pickerOrdersData').html(html);
                    $('#orderTable').dataTable({
                        "order": [[5, "desc"]]
                    });
                } else {
                    swal("No Order Found");
                }
            }
        });

        return false;
    });

    function getActivePicker(){
        var api_key = '<?php echo $api_key; ?>';
        jQuery.ajax({
            type: 'GET',
            url: '<?php echo base_url();?>Admin/getActivePickerList',
            data: {'Authorization': api_key},
            success: function (res) {
                var pickerlist = res.userlist;

                var html = '';

                for (var i = 0; i < pickerlist.length; i++) {
                    var result = pickerlist[i];
                    html += '<option value="'+result.id+'">'+result.name+' ('+result.email+')</option>';
                }
                jQuery("#picker_id").append(html);
            }
        });
    }
</script>
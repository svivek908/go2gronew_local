<?php include 'header.php' ;
load_css(array('public/assets/stylesheet/pgstyle/account.css?'));
?>
<div class="page-heading" xmlns="http://www.w3.org/1999/html">
</div>

<!-- BEGIN Main Container col2-right -->
<section class="main-container col2-right-layout">
    <div class="main container">
        <div class="row">
            <section class="col-main col-sm-12 col-xs-12 wow bounceInUp animated animated">
                <div class="my-account">

                    <!--page-title-->
                    <!-- BEGIN DASHBOARD-->
                    <div class="dashboard">
                        <div class="welcome-msg">
                            <p class="hello"><strong>Hello, <?php echo $fname;?>  <?php echo $lname;?></strong></p>
                            <p>From your My Account Dashboard you have the ability to view a snapshot of your recent account activity and update your account information. Select a link below to view or edit information.</p>
                        </div>
                        <div class="recent-orders">
                            <div class="title-buttons"> <strong>Recent Orders</strong> <a href="#"></a> </div>
                            <div class="table-responsive">
                                <table class="data-table table-striped" id="my-orders-table">
                                    <colgroup>
                                        <col width="">
                                        <col width="">
                                        <col>
                                        <col width="1">
                                        <col width="1">
                                        <col width="20%">
                                    </colgroup>
                                    <thead>
                                    <tr class="first last">
                                        <th>Order Id </th>
                                        <th>Store name</th>
                                        <th>Date</th>
                                        <th>Address</th>
                                        <th class="td-account"><span class="nobr">Full Name</span></th>
                                        <th class="td-grandtotal"><span class="nobr">Grand Total</span></th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody class="shop_list">

                                    </tbody>
                                </table>
                            </div>
                            <!--table-responsive-->
                        </div>
                        <!--recent-orders-->

                    </div>
            </section>
        </div>
        <!--row-->
    </div>
    <!--main container-->
</section>
<!--main-container col2-left-layout-->


<?php include 'footer.php' ?>
<script>
    OrderHistory();
    function OrderHistory()
    {

        <?php if(empty($apikey) || !trim($apikey)) {?>
        var api_key='not defined';
        <?php }else{?>
        var api_key='<?php echo $apikey;?>';
        <?php }?>
        jQuery.ajax({
            type: 'GET',
            url: '<?php echo base_url(); ?>/OrderHistory',
            data: {
                'Authorization': api_key
            },
            success: function (res) {
                console.log(res);
                var html1 = '';

                if (res.error == true) {

                } else {
                    var catList = res.order;

                    var jsonLength = catList.length;

                    var jsonLength1 = 1;
                    console.log(jsonLength);
                    var html1 = '';


                    for (var i = 0; i < jsonLength; i++) {

                        var result = catList[i];
                        var time=unixtimestamp(result.datetime);
                        var timeDiffrence = res.currentunitime-result.datetime;

                        var orderid=result.order_id;
                        var storeid  = result.store_id;
                        var storename  = result.storename;
                        var shipping_address = '';
//                        console.log(result.shipping_address);
                        try {
                            var shipping_addr_json = JSON.parse(result.shipping_address);
                            shipping_address = shipping_addr_json.street_address;
                            if(shipping_addr_json.apt_no != ''){
                                shipping_address += ', ' + shipping_addr_json.apt_no;
                            }
                            if(shipping_addr_json.complex_name != ''){
                                shipping_address += ', ' + shipping_addr_json.complex_name;
                            }
                        } catch (e) {
                            shipping_address = result.shipping_address;
                        }

                        html1 += '<tr class="first odd">\
        <td><a href="<?php echo base_url();?>/accountDetail/'+orderid+'/'+storeid+'">'+result.order_id+'</a></td>\
        <td>'+result.storename+'</td>\
       <td><span class="nobr">'+time+'</span></td>\
        <td><span class="shipping_account">'+shipping_address+'</span></td>\
    <td>'+result.ship_name+'</td>\
    <td><span class="price">$'+roundoffvalue(result.finalprice)+'</span></td>\
    <td id="status_'+result.order_id+'"><em>'+orderStatus(result.status)+'</em></td>\
    <td class="a-center last"><span class="nobr"> <a href="<?php echo base_url();?>/accountDetail/'+orderid+'/'+storeid+'">View Order</a> '+(result.status==0 && timeDiffrence<=300 ?'<a id="cancel_'+result.order_id+'" href="javascript:void(0)" class="block red" onclick=cancelOrder(\''+result.order_id+'\') >Cancel Order</a>':'')+' '+(result.is_order_edit==1 ?'<a href="<?php echo base_url(); ?>/alternate_product/<?php echo $userId; ?>/'+result.order_id+'" class="block green" >Edit alternate</a>':'')+'</span></td>\
    </tr>';

                    }
                }
                jQuery('.shop_list').append(html1);
            }
        });
    }
    function cancelOrder(orderId){
        var api_key='<?php echo $apikey;?>';
        swal({
                title: "Are you sure?",
                text: "You want to cancel this order",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                closeOnConfirm: false,
                closeOnCancel: true,
                showLoaderOnConfirm:true
            },
            function() {

                jQuery.ajax({
                    type: 'post',
                    url: '<?php echo base_url(); ?>/cancelorder',
                    data: {
                        'Authorization': api_key,
                        'orderid':orderId
                    },
                    success: function (res) {
                        if(res.error){
                            swal('Error!', res.message, 'error');
                        }else{
                            swal('Success!', res.message, 'success');
                            jQuery('#status_'+orderId).text(orderStatus(6));
                            jQuery('#status_'+orderId).append('<em class="red"> (Refund Inprogress)</em>');
                            jQuery('#cancel_'+orderId).remove();
                        }
                    }
                });

            });

    }
</script>
</body>
</html>
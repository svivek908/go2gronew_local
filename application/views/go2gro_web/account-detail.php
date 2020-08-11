<?php include 'header.php';
load_css(array('public/assets/stylesheet/pgstyle/account.css?'));
?>

<div id="loader-repeatorder" class=""></div>
<div class="page-heading">
</div>

<!-- BEGIN Main Container col2-right -->
<section class="main-container col2-right-layout">
    <div class="main container">
        <div class="row">
            <section class="col-main col-sm-12 col-xs-12 wow bounceInUp animated animated">
                <div class="my-account no-padTB">

                    <!--page-title-->
                    <!-- BEGIN DASHBOARD-->
                    <div class="dashboard">
                        <div class="recent-orders">
                            <div class="row order-detail" id="order-detail11">

                            </div>

                            <div class="row">

                                <div class="col-md-12 text-center filter-slider">
                                    <div class="gr-line-circle circle-line1543345">

                                        <div class="message">

                                            <div class="col-md-12 text-center filter-slider">
                                                <div class="gr-bor11">
                                                    <div class="text-right tooltip">
                                                        <span class="gr-circle11"> </span>
                                                        <span class="tooltiptext tooltip-bottom">Pending</span>
                                                    </div>
                                                    <div class="text-right tooltip">
                                                        <span class="gr-circle11"> </span>
                                                        <span class="tooltiptext tooltip-bottom">For Prepare</span>
                                                    </div>
                                                    <div class="text-right tooltip">
                                                        <span class="gr-circle11"> </span>
                                                        <span class="tooltiptext tooltip-bottom">For Packed</span>
                                                    </div>
                                                    <div class="text-right tooltip">
                                                        <span class="gr-circle11"> </span>
                                                        <span class="tooltiptext tooltip-bottom">Go for Delivery</span>
                                                    </div>
                                                    <div class="text-right tooltip">
                                                        <span class="gr-circle11"> </span>
                                                        <span class="tooltiptext tooltip-bottom">Delivered</span>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th width="150"><span class=" gr-main-circle  "> </span></th>
                                            <th width="150"><span class="gr-main-circle"> </span></th>
                                            <th width="150"><span class="gr-main-circle "> </span></th>
                                            <th width="150"><span class="gr-main-circle "> </span></th>
                                            <th width="150"><span class="gr-main-circle "> </span></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr id="ordercontent">

                                        </tr>

                                        </tbody>
                                    </table>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-12 no-pad text-center order-detail-table">
                                    <table class="data-table table-striped">
                                        <colgroup>
                                            <col width="20%">
                                            <col width="20%">
                                            <col width="20%">
                                            <col width="20%">
                                            <col width="20%">
                                        </colgroup>
                                        <thead>
                                        <th> Product Image</th>
                                        <th> Product Name</th>
                                        <th> Product Price</th>
                                        <th> Product Quantity</th>
                                        <th> Total</th>
                                        </thead>
                                        <tbody class="shop_list11">

                                        <tr>
                                            <td colspan="5">
                                                <div id="loaderprofile2">
                                                    <div class="loader">

                                                    </div>
                                                </div>
                                            </td>
                                        </tr>

                                        </tbody>
                                    </table>
                                </div>

                            </div>


                        </div>
                        <!--recent-orders-->
                    </div>
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
    var orderListVAlue;
    orderdetail();
    //order();
    <?php if(empty($apikey) || !trim($apikey)) {?>
    var api_key = 'not defined';
    <?php }else{?>
    var api_key = '<?php echo $apikey;?>';
    <?php }?>

    function orderdetail() {
        var orderiid = '<?php echo $order_id;?>';
        var store_id = '<?php echo $store_id;?>';
        <?php if(empty($apikey) || !trim($apikey)) {?>
        var api_key = 'not defined';
        <?php }else{?>
        var api_key = '<?php echo $apikey;?>';
        <?php }?>
        jQuery.ajax({
            type: 'GET',
            url: '<?php echo base_url(); ?>/OrderDetail',
            data: {

                'Authorization': api_key,
                'order_id': orderiid,
                'store_id': store_id
            },
            success: function (res) {
                jQuery('#loaderprofile2').addClass('hidden');
                var html1 = '';

                if (res.error == true) {
                    swal('Error!', res.message, 'error');
                } else {
                    orderListVAlue = res;

                    var catlist = orderListVAlue.orderdetail;
                    var orderDetail = res.order;

                    console.log(catlist);
                    var jsonLength = catlist.length;
                    var jsonLength1 = 1;
                    console.log(jsonLength);
                    var html1 = '';
                    for (var i = 0; i < jsonLength; i++) {

                        var result = catlist[i];
                        if (result.status == 1 || result.status == 2) {
                            html1 += ' <tr class="first odd" >\
                        <td><img src="<?php echo base_url('public/upload/item/');?>'+ result.item_image + '" class="td-img" onerror="this.src=\'<?php echo base_url('public/upload/item/noprivew.jpg');?>\';"/></td>\
                        <td><span class="nobr">' + result.item_name + '</span></td>\
                    <td><span class="price">$' + parseFloat(result.item_price).toFixed(2) + '</span></td>\
                    <td><em>' + result.item_quty + '</em></td>\
                    <td><em>$' + result.total + '</em></td>\
                    </tr>';
                        }
                    }
                    jQuery('.shop_list11').html(html1);
                    order();
                    rangeslider();
                }
            }
        });
    }

    function order() {


        var catList = orderListVAlue.order;

        var html1 = '';
        var htmlslider = '';

        totalSalesTax = catList.tax;
        totalAmount = catList.finalprice;

        var shipping_address = '';
//                        console.log(result.shipping_address);
        try {
            var shipping_addr_json = JSON.parse(catList.shipping_address);
            shipping_address = shipping_addr_json.street_address;
            if(shipping_addr_json.apt_no != ''){
                shipping_address += ', ' + shipping_addr_json.apt_no;
            }
            if(shipping_addr_json.complex_name != ''){
                shipping_address += ', ' + shipping_addr_json.complex_name;
            }
        } catch (e) {
            shipping_address = catList.shipping_address;
        }

        var address = '';
//                        console.log(result.shipping_address);
        try {
            var addr_json = JSON.parse(catList.address);
            address = addr_json.street_address;
            if(addr_json.apt_no != ''){
                address += ', ' + addr_json.apt_no;
            }
            if(addr_json.complex_name != ''){
                address += ', ' + addr_json.complex_name;
            }
        } catch (e) {
            address = catList.address;
        }

        var discount_label = 'Discount';
        /*
         * 	-1 = referral discount, 0 = no coupon or referral discount, >1 valid promocode id
         */
        if(catList.coupanid == -1 ){
            discount_label = 'Referral Discount';
        } if(catList.coupanid > 1 ){
            discount_label = 'Promocode Discount';
        }
        html1 += '<div class="col-md-4 pad10 borderRight">\
                <div class="row" >\
            <div class="col-md-12 margin-addcol"  id="order-detail"> <h4 class="mar5"> ORDER-DETAILS </h4></div>\
        </div>\
             <div class="row" >\
            <div class="col-md-5"> 	<p> <strong> Order ID  </strong> </p></div>\
        <div class="col-md-7"> 	<p>#' + catList.order_id + '</p></div>\
        </div>\
         <div class="row">\
            <div class="col-md-5"> 	<p> <strong>Order Date  </strong></p></div>\
        <div class="col-md-7"> 	<p> ' + _unixtimestamp(catList.datetime) + ' </p></div>\
        </div>\
        <div class="row">\
            <div class="col-md-5"> 	<p> <strong>Delivery Time  </strong></p></div>\
        <div class="col-md-7"> 	<p>' + formatedDate(catList.dlv_date) + ' ' + catList.slot_name + '  </p></div>\
        </div>\
        <div class="row">\
            <div class="col-md-4"> 	<p> <strong> Delivery Charge </strong></p></div>\
            <div class="col-md-2"> 	<p>$' + parseFloat(catList.dlv_charge).toFixed(2) + ' </p></div>\
            <div class="col-md-4"> 	<p> <strong> Processing Fee </strong></p></div>\
            <div class="col-md-2"> 	<p>$' + parseFloat(catList.processingfee).toFixed(2) + '</p></div>\
        </div>\
        <div class="row">\
            <div class="col-md-4"> 	<p> <strong> Sales Tax </strong></p></div>\
            <div class="col-md-2"> 	<p>$' + parseFloat(totalSalesTax).toFixed(2) + ' </p></div>\
            <div class="col-md-4"> 	<p> <strong> Tip </strong></p></div>\
            <div class="col-md-2"> 	<p>$' + parseFloat(catList.tip_amount).toFixed(2) + '</p></div>\
        </div>\
         <div class="row">\
            <div class="col-md-4"> 	<p> <strong>Total Amount </strong></p></div>\
            <div class="col-md-2"> 	<p>$' + parseFloat(totalAmount).toFixed(2) + ' </p></div>\
            <div class="col-md-4"> 	<p> <strong> '+ discount_label +' </strong></p></div>\
            <div class="col-md-2"> 	<p>$' + parseFloat(catList.discount_amount).toFixed(2) + '</p></div>\
        </div>\
        </div>\
         <div class="col-md-4 pad10 borderRight Right-height">\
            <div class="row">\
            <div class="col-md-12 margin-addcol"> <h4 class="mar5"> ADDRESS </h4></div>\
        </div>\
        <div class="row">\
             <div class="col-md-4"> 	<p> <strong> User Name </strong></p></div>\
        <div class="col-md-8"> 	<p> ' + catList.ship_name + '</p></div>\
        </div>\
        <div class="row">\
         <div class="col-md-4"> 	<p> <strong> Address </strong></p></div>\
        <div class="col-md-8"> 	<p>' + shipping_address + '</p></div>\
        </div>\
        <div class="row">\
            <div class="col-md-4"> 	<p> <strong> Phone </strong></p></div>\
        <div class="col-md-8"> 	<p> ' + catList.ship_mobile + '</p></div>\
        </div>\
        </div>\
          <div class="col-md-4 pad10">\
            <div class="row">\
            <div class="col-md-12 col-accountmr"> <h4 class="mar5"> INFO </h4></div>\
        </div>\
        <div class="row">\
            <div class="col-md-12">\
                <a href="" class="green tool"> <strong><i class="fa fa-user"> </i> User Profile </strong>  \
                    <span id="xyz">\
                        <p> ' + catList.first_name + ' ' + catList.last_name + '</p><p>' + address + '</p>	<p>' + catList.mobile + '</p>	' +
                        '<p>' + catList.email_id + '</p>\
                    </span> \
                </a>\
            </div>\
        <div>\
            <div class="col-md-4"> 	<p> <strong> Current Status </strong></p></div>\
            <div class="col-md-8"> 	<p class="order-laststatus"> ' + orderStatus(catList.status) + '</p></div> \
        </div>';


        if (catList.status == 4) { // Case for delivered
            var onclick_str = "repeat_order('"+catList.order_id+"')";
            html1 += '<div class="col-md-12">\
                <a onclick="'+onclick_str+'" href="javascript:void(0)" class="green tool"> <strong><i class="fa fa-product"> </i> Repeat Order </strong>  \
                </a>\
            <div>';
        }

        html1 += '</div>';

        if (catList.status == 0) {

            htmlslider += '<img src="<?php echo base_url('public/assets/images/111.png');?>"><p>';

        }
        else if (catList.status == 1) {
            htmlslider += '<img src="<?php echo base_url('public/assets/images/222.png');?>" ><p>';
        }
        else if (catList.status == 2) {

            htmlslider += '<img src="<?php echo base_url('public/assets/images/333.png');?>"><p>';

        }
        else if (catList.status == 3) {

            htmlslider += '<img src="<?php echo base_url('public/assets/images/444.png');?>" ><p>';

        }
        else if (catList.status == 4) {

            htmlslider += '<img src="<?php echo base_url('public/assets/images/555.png');?>"><p>';
        }
        else if (catList.status == 5) {

            htmlslider += '<img src="<?php echo base_url('public/assets/images/rejected.png');?>"><p>';

        }
        else if (catList.status == 6) {

            htmlslider += '<img src="<?php echo base_url('public/assets/images/cancelled.png');?>"><p>';

        }
        jQuery('#order-detail11').html(html1);
        jQuery('.gr-line-circle ').html(htmlslider);

    }

    function repeat_order(order_id){
        jQuery("#loader-repeatorder").addClass('loading');
        jQuery.ajax({
            type: 'POST',
            url: '<?php echo base_url(); ?>/RepeatOrder',
            data: {
                'Authorization': api_key,
                'order_id': order_id
            },
            success: function (res) {
                console.log(res);
                var html1 = '';
                if (res.error == false) {
                    swal({title: 'Success!', text: res.message, type: 'success'}, function () {
                        window.location = '<?php echo base_url('ShoppingCart'); ?>';
                    });
                } else {
                    swal({title: 'Error', text: res.message, type: 'error'}, function () {
                        window.location = '<?php echo base_url('ShoppingCart'); ?>';
                    });
                }
            },
            complete: function(){
                jQuery("#loader-repeatorder").removeClass('loading');
            }
        });
    }

    function rangeslider() {
        var catList = orderListVAlue.orderstatus;
        var jsonLength = catList.length;
        console.log("progressbar");
        console.log(catList);
        var jsonLength1 = 1;
        console.log(jsonLength);
        var html1 = '';
        var orderStatus = catList.reverse();
        html1 += '<td><br><span></span></td>';
        for (var i = 0; i < jsonLength; i++) {
            var result = orderStatus[i];
            var time = _unixtimestamp(result.updatetime);
            console.log("progressbar--------------");
            console.log(result.status);
            html1 += '<td>' + result.message + '<br><span>' + time + '</span></td>';

        }

        jQuery('#ordercontent ').html(html1);
    }
</script>
</body>
</html>
<?php

$segs = $this->uri->segment_array();
$tag = end($segs);
$search_array = array();
$user_id = $segs[2];
$order_id = $segs[3];
$alternateApprovalRole = isset($_REQUEST['role'])?$_REQUEST['role']:"";
$store = isset($_REQUEST['store'])?$_REQUEST['store']:"";
//echo ($tag);
include 'header.php';

?>


<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" /    >

<style>
    .aprove-btn a {
        display: inline-block;
        text-align: center;
        margin-top: 5px;
        padding: 3px 12px;
		font-size: 12px;
    }
	.a-center{text-align: center;}
    .aprove-btn a:hover {
        color: #333;
    }
	a{text-decoration: none !important;}

    .disaproveblock {
        background: #ffe9ec !important;
    }

    .disaproveblock a, .disaproveblock, .disaproveblock span, .disaproveblock span .price {
        color: #6f6f6f;

    }
	
	

    .aprove-button {
        background: #7fcb28;
        color: #fff !important;
        border-color: #74b727;
		padding: 3px;
		font-size: 10px;
		margin-bottom: 2px;
		display: inline-block;
		line-height: 15px;
    }
	#alternative_products_all table td{
		padding: 3px 13px;
	}

    .disaprove-button {
        background: #ec1c24;
        border-color: #d21920;
        color: #fff !important;
		padding: 3px;
		font-size: 10px;
    }

    .approve-block {
        background: #edffd8 !important;

    }

    .alternate-title {
        display: inline-block;
        margin: 15px;
    }

    .no-pad {
        padding: 0px;
    }

    .no-margin {
        margin: 0px !important;
    }

    .edit-order td {
        font-size: 14px;
    }

    .proced-checkout {
        font-size: 16px;
        background: #7fcb28;
        color: #fff !important;
        padding: 5px 30px;
        border-color: #6db718;
    }

    .proced-checkout:hover {
        color: #333;
    }

    .overlay_hidden {
        pointer-events: none;
    }

    .overlay_visible {
        pointer-events: auto;
    }
	/*#alternative_products_all form{*/
		/*border-bottom: double 3px;*/
		/*border-color: #7fcb28;*/
		/*margin-bottom: 15px;*/
	/*}*/
	.aprove-btn a:last-child:hover{
		border-color:#d21920;
	}
	#alternative_products_all table tbody td{
		vertical-align: middle;
	}

.cancelorder-btn:hover{
	-webkit-transition: all 0.4s cubic-bezier(0.8, 0, 0, 1) !important;
	-o-transition: all 0.4s cubic-bezier(0.8, 0, 0, 1) !important;
	transition: all 0.4s cubic-bezier(0.8, 0, 0, 1) !important;
	box-shadow: inset 0 -52px 0 0 #ec1c24 !important;
	border: 2px solid #ec1c24 !important;
	color: #fff;
}

	.notselect-item{
		color: #c00;
	}
	.notselect-item input[type=checkbox]{
		position: relative;
		top:3px;
	}
.main-product{
	background: #7fcb28;
	color: #fff;
	    padding: 15px!important;
}

.alernative_inputbg{    margin-left: 2px;
    width: 150px;
    background: #fff;
    border: 1px solid gainsboro;
    padding: 10px;}
	
	.alernative_min{display: inline-block!important;
    width: 40px!important;
    height: 42px!important;
    background: #eee!important;
    text-align: center!important;
    vertical-align: middle;
    border: 0px!important;
    position: relative!important;
    left: 6px!important;
    top: -3px!important;}
	
  .alernative_plus{display: inline-block!important;
    width: 40px!important;
    height: 42px!important;
    background: #eee!important;
    text-align: center!important;
    vertical-align: middle;
    border: 0px!important;
    position: relative!important;
    left: -3px!important;
    top: -3px!important;}

.main-product h2{margin: 0px;}
	.edit-order tbody tr:first-child td{border-top:none !important;}

	.alt-note{
		margin: 0px;
		font-size: 11px;
		padding: 5px;
		color: #fd9292;
	}
.table-product-img{	max-width:70px;}
.table-item-img{	max-width:57px;}
@media screen and (max-width: 990px) and (min-width: 320px) {
	.table-product-img{	max-width:100px;}
	.table-item-img{max-width:85px;}
	.alt-product-width{
	    min-width: 700px;
		overflow-x: scroll;
	}
	.change-qunty span {
		display: inline-block !important;
		width: 30px !important;
		height: 25px !important;
		margin: 2px 4px 2px 0px !important;
	}
	.change-qunty span input{height:25px;}
	

}

@media screen and (max-width: 990px) and (min-width: 320px){
	
	.alernative_min{top: 2px!important;width: 42px!important;}
	.alernative_plus{left: 6px!important;top: 10px!important;width: 42px!important;}
	.table-striped>tbody>tr:nth-child(odd){height: 155px;}
	.order-detail{margin-top: 136px}
	.alernative_inputbg{width: 42px;
    background: #fff;
    border: 1px solid gainsboro;
    padding: 10px;
    height: 40px!important;}
 
}

</style>


<div class="slideshow-container">

	<div class="mySlides fade1">
		<div class="numbertext">1 / 4</div>
		<img src="<?php echo base_url();?>images/Banner_0101new.jpg" style="width:100%">
	</div>
    <div class="mySlides fade1">
        <div class="numbertext">2 / 4</div>
        <img src="<?php echo base_url(); ?>images/Banner_0101.png" style="width:100%">
    </div>

    <div class="mySlides fade1">
        <div class="numbertext">3 / 4</div>
        <img src="<?php echo base_url(); ?>images/slide-img2.png" style="width:100%">
    </div>

    <div class="mySlides fade1">
        <div class="numbertext">4 / 4</div>
        <img src="<?php echo base_url(); ?>images/slide-img1.png" style="width:100%">
    </div>

    <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
    <a class="next" onclick="plusSlides(1)">&#10095;</a>
    <br>

    <div style="text-align:center">
        <span class="dot" onclick="currentSlide(1)"></span>
        <span class="dot" onclick="currentSlide(2)"></span>
        <span class="dot" onclick="currentSlide(3)"></span>
    </div>

</div>
<div id="loaderorder"></div>

<div class="main-container col1-layout wow bounceInUp animated">
    <div class="main">
        <div class="col-main" style="margin-top:165px;">
            <!--  Product -->
            <div class="product-view wow bounceInUp animated" itemscope="" itemtype="http://schema.org/Product"
                 itemid="#product_base">
                <div></div>
                <div class="table-responsive shopping-cart-tbl  container" style=" padding-top:15px; ">

                    <div class="row no-margin">
                        <div class="col-md-12 order-detail clearfix" style="margin-bottom:20px;border:solid 1px #efefef;"
                             id="orderdesc">
                        </div>

                    </div>

                    <div id="alternative_products_all" class="alt-product-width">
                    </div>

					<div class="col-md-12 clearfix" style="margin:15px 0px;padding:0px;">
						<div class="col-md-6 col-xs-6">

							<button type="button" onclick="finalProcessPlaceOrder('cancel')" title="Continue Shopping" class="cancelorder-btn text-center button btn-continue1 ">
						<span>
							<span> Cancel Entire Order </span>
						</span>
							</button>
						</div>

						<div class="col-md-6 col-xs-6 text-right">
							<button type="button" onclick="OrderAmountDetail();" title="submit" class="text-center button btn-continue1 ">
							<span>
								<span> Submit </span>
							</span>
							</button>
						</div>
					</div>
                </div>

                <!-- End Product -->

            </div>

            <!--  Product -->
			<div class="product-view wow bounceInUp animated" itemscope="" id="amountDetailBlock"></div>

<!--payment model open -->
			<div aria-hidden="true" aria-labelledby="exampleModalLabel" class="modal fade" id="paymentModal" role="dialog" tabindex="-1">
				<div  style="display: block;background: rgba(51, 51, 51, 0.78);z-index: 0;width: 100%;height: 100%;position: fixed;"></div>
				<div class="modal-dialog" role="document" style="width:410px;">
					<div class="modal-content" >
						<div class="modal-header text-center"><h4 class="modal-title checkout-title" id="exampleModalLabel">Checkout</h4>
							<button aria-label="Close" class="close reset-edit-form" data-dismiss="modal" type="button" >
								<span aria-hidden="true"> &times;</span></button>
						</div>
						<div class="modal-body">
							<div class="payment_box payment_method_authorizenet" id="ccformContainer" style="padding: 10px 15px;">
								<div style="margin-bottom: 10px; font-size: 14px; text-align:center; "  class="desc">
									<label for="payment_method_authorizenet">
										<ul id="credit-card-type" >
											<li class="VI col-md-3">
												<img src="<?php echo base_url();?>public/assets/images/payment-2.png" width='65'/>
											</li>
											<li class="AE col-md-3"><img src="<?php echo base_url();?>public/assets/images/payment-3.png" width='65'/></li>
											<li class="MC col-md-3"><img src="<?php echo base_url();?>public/assets/images/payment-4.png" width='65'/></li>
											<li class="DI col-md-3"><img src="<?php echo base_url();?>public/assets/images/discover.png" width='65'/></li>
										</ul>
								</div>
								<p class="text-center">All cards are charged by &#169;Authorize.Net &#174;&#8482; servers.</p>
								<form class="creditly-card-form" id="checkout_form">
									<section class="creditly-wrapper creditcard-height">
										<div class="credit-card-wrapper creditcard">
											<div class="first-row form-group">
												<div class="col-sm-12 controls">
													<label class="control-label">Card Number</label>
													<input class="number credit-card-number form-control"
														   type="text" name="card_number" id="authorizenet-card-number"
														   inputmode="numeric" autocomplete="cc-number" autocompletetype="cc-number" x-autocompletetype="cc-number"
														   placeholder="&#149;&#149;&#149;&#149; &#149;&#149;&#149;&#149; &#149;&#149;&#149;&#149; &#149;&#149;&#149;&#149;">
												</div>

											</div>
											<div class="second-row form-group">

												<div class="col-sm-6 controls">
													<label class="control-label">Expiration</label>
													<input id="authorizenet-card-expiry" class="expiration-month-and-year form-control"
														   type="text" name=cardexpiry"
														   placeholder="MM / YY" required>
												</div>
												<div class="col-sm-6 controls">
													<label class="control-label">CVV</label>
													<input id="authorizenet-card-cvc" class="security-code form-control"
														   inputmode="numeric"
														   type="text" name="cvv"
														   placeholder="&#149;&#149;&#149;">
												</div>
											</div>
										</div>
									</section>
							</div>
						</div>
						<div class="modal-footer text-center">
							<button class="btn btn-secondary red-close-btn reset-edit-form" data-dismiss="modal" type="button"> Close</button>
							<button class="btn btn-primary black" type="submit"> Submit</button>
						</div>
						</form>
					</div>
				</div>
			</div>
			<!--payment model close -->
        </div>
		</div>
</div>
        <?php include 'footer.php'; ?>
	<script>
		var notAvailableItemCost = 0;
		var notAvailableItemSalesTax = 0;
		var alternateProductCount = 0;
		var availableItemCount = 0;
		var alternateArray=[];
		var listarray = [];
		var order_detail;
		var finalObject ={};
		var altitemqty={};
		jQuery("#loaderorder").addClass('loading');
		jQuery(document).ready(function () {
			GetAlternateProductDetail();
		});

		function finalProcessPlaceOrder(tag){

			var orderDetail=order_detail.orderdetail;
			var ord_userid = orderDetail.user_id;
			var order_id = orderDetail.order_id;
			var alternateApprovalRole = '<?php echo $alternateApprovalRole;?>';
			finalObject.alternateApprovalRole = alternateApprovalRole;
			finalObject.ord_userid = ord_userid;
			finalObject.order_id = order_id;
			if(tag === 'cancel'){
				jQuery("#loaderorder").addClass('loading');
				finalObject ={"ord_userid":ord_userid,"new_order_status":0,"orderstatus":6 , "order_id":order_id, "ord_tax" : orderDetail.tax, "ord_totalprice" : orderDetail.total_price,
					"ord_finlprice" : orderDetail.finalprice,"item_detail":"{}"};
			}else if(tag === 'confirm'){
				jQuery("#loaderorder").addClass('loading');
			}

			jQuery.ajax({
				type: 'post',
				data: finalObject,
				url: '<?php echo base_url();?>alternatPlaceorder',
				success: function (res) {
					if(!res.error){
						if(tag === 'cancel'){
							swal({
								title:"Cancelled",
								type:"success",
								text:"Your complete order cancelled."
							},function(){
                                // if viewing in webview(i.e in case of picker) dont redirect to home and show a static page
                                if(alternateApprovalRole == "picker"){
                                    window.location = "<?php echo base_url('success.html'); ?>";
                                } else {
                                    window.location = "<?php echo base_url(); ?>";
                                }
							});
						}else if(tag === 'confirm'){
                            swal({
                                title:"Success",
                                type:"success",
                                text:"Your order successfully updated."
                            },function(){
                                jQuery("#loaderorder").removeClass('loading');
                                // if viewing in webview(i.e in case of picker) dont redirect to home and show a static page
                                if(alternateApprovalRole == "picker"){
                                    //console.log('picker');
                                    window.location = "<?php echo base_url('success.html'); ?>";
                                } else {
                                    //console.log('user');
                                    window.location = "<?php echo base_url(); ?>";
                                }
                            });
						}
					}else{
						jQuery("#loaderorder").removeClass('loading');
						swal({
							title:"Error",
							type:"error",
							text:res.message
						});
					}
				},
                error : function (err){

                    // unkowingly success from server is also landing here, need to check json object being returned from server
                    // for now treating it as a success
                    console.log('error calling api :'+ err);
                    jQuery("#loaderorder").removeClass('loading');
                    if(alternateApprovalRole == "picker"){
                        window.location = "<?php echo base_url('success.html'); ?>";
                    } else {
                        window.location.reload();
                    }
                }
			});
		}

		function GetAlternateProductDetail() {

			var order_id = '<?php echo $order_id;?>';
			var user_id = '<?php echo $user_id;?>';
			var role = '<?php echo $alternateApprovalRole;?>';
			var store = '<?php echo $store;?>';
			var paidproducts = '';
			jQuery.ajax({
				type: 'GET',
				data: {'order_id': order_id, 'user_id': user_id,'role': role,'store': store},
				url: '<?php echo base_url();?>GetAlternateProductDetailApi',
				success: function (res) {
					jQuery("#loaderorder").removeClass('loading');
					order_detail = res;
					if (res.error == true) {
						swal({
							title:"Error",
                            text:"Your cart has already been edited. Please check your new order details in order history.",
							type:"error"
						},function(){
							window.location ='<?php echo base_url(); ?>';
						});
					} else {
						var count = 1;
						var allproductlist = res.itemlist;
						var allproductlength = allproductlist.length;
						for (var i = 0; i < allproductlength; i++) {
							var resultlist = allproductlist[i];
							var status = resultlist['status'];
							altitemqty[resultlist.item_id]=resultlist.item_quty;
							console.log(altitemqty);
							if(status == 1){
								availableItemCount++;
							} else if (status == 2) {
								notAvailableItemCost = notAvailableItemCost+resultlist.item_price*resultlist.item_quty;
								notAvailableItemSalesTax = notAvailableItemSalesTax+(resultlist.item_price*resultlist.item_quty*resultlist.Sales_tax)/100;
								paidproducts += '<form action="" method="post">\
							<input name="form_key" type="hidden" value="EPYwQxF6xoWcjLUr">\
							<fieldset style="width:100%;overflow:hidden;" class="clearfix">\
							<table  class="no-margin data-table cart-table table-striped">\
							<colgroup>\
							<col width="1">\
							<col>\
							<col width="1">\
							<col width="1">\
							<col width="1">\
							<col width="1">\
							<col width="1">\
							</colgroup>\
							<thead>\
							</thead>\
							<tbody >\
							<tr> <td colspan="7" class="text-center main-product"> <h2> Original Item (Out of Stock) </h2> </td> </tr>\
							<tr class="first last">\
							<td rowspan="1">Product</td>\
							<td rowspan="1"><span class="nobr">Product Name</span>\
						</td>\
						<td class="a-right" colspan="1"><span class="nobr">Price</span>\
						</td>\
						<td class="a-right" colspan="1"><span class="nobr">Sales Tax</span>\
						</td>\
						<td rowspan="1" class="a-center text-center" style="min-width:170px;">Qty</td>\
							<td class="a-right" colspan="1" style="border-right: solid 1px #efefef;">Subtotal</td>\
							<td class="a-right" colspan="1" style="max-width: 100px;border-right: solid 1px #efefef;width: 100px;" >Action</td>\
						</tr>\
							<tr class="odd" >\
							<td class="image hidden-table table-item-img">\
							<a href="<?php echo base_url();?>productDetail?id=' + resultlist.item_id + '" title="' + resultlist.item_name + '" class="product-image">\
							<img src="<?php echo $this->config->item("api_img_url");?>/' + resultlist.imageurl + '" onerror="this.src=\'<?php echo $this->config->item('api_img_url');?>upload/item/noprivew.jpg\';" width="75" alt="' + resultlist.item_name + '">\
							</a>\
							</td>\
							<td style="width:170px;">\
							<p class="product-name" style="border:none;font-weight:bold;">\
							<a href="<?php echo base_url();?>productDetail?id=' + resultlist.item_id + '">' + resultlist.item_name + '</a>\
						</p>\
						</td>\
						<td class="a-right hidden-table">\
							<span class="cart-price">\
							<span class="price">$' + parseFloat(resultlist.item_price).toFixed(2) + '</span> </span>\
						</td>\
						<td class="a-right hidden-table"> <span class="cart-price">\
							<span class="price">' + resultlist.Sales_tax + '%</span> </span>\
						</td>\
						<td class="a-center movewishlist text-center">\
							<div class="change-qunty">\
							<span> <b> ' + resultlist.item_quty + ' </b> </span>\
						</div>\
						</td>\
						<td class="a-right movewishlist" > <span class="cart-price">   <span class="price5555" >$' + (resultlist.item_price*resultlist.item_quty).toFixed(2) + '</span> </span>\
						</td>\
						<td style="max-width: 70px;border-right: solid 1px #efefef;"> &nbsp;</td>\
						</tr>\
						</tbody>\
								<tfoot>\
							<tr class="first last">\
							<td colspan="50" class="text-center last">\
							<div class="col-md-12" style="padding:25px;">\
							<h2 class="alternate-title"> Alternative Product </h2>\
						<a href="<?php echo base_url();?>" type="button" title="Continue Shopping" class="text-center button btn-continue1 "><span><span><i class="fa fa-arrow-down" aria-hidden="true"></i></span></span>\
						</a>\
						<p class="alt-note"> IF NO ALTERNATIVE ITEM IS SELECTED, THEN THE ORIGINAL ITEM WILL BE REMOVED FROM YOUR CART.</p>\
						</div>\
						</td>\
						</tr>\
						</tfoot>\
						</table>';
							} else if (status == 3) {
								alternateArray.push(resultlist);
								alternateProductCount++;
								paidproducts += '<div class="col-md-12 no-pad " style="margin-bottom:10px;">\
					<div class="table-responsive abc">\
							<form action="" method="post">\
							<input name="form_key" type="hidden" value="EPYwQxF6xoWcjLUr">\
							<fieldset>\
							<table  class="data-table cart-table table-striped abc2">\
							<colgroup>\
							<col width="1">\
							<col>\
							<col width="1">\
							<col width="1">\
							<col width="1">\
							<col width="1">\
							<col width="1">\
						</colgroup>\
							<thead>\
							</thead>\
							<tbody >\
						<tr id="blockapprovalstatus_' + resultlist.item_id+resultlist.alernative_item_id+'">\
							<td class="image hidden-table table-product-img">\
							<a href="<?php echo base_url();?>productDetail?id=' + resultlist.item_id + '" title="' + resultlist.item_name + '" class="product-image">\
							<img src="<?php echo $this->config->item("api_img_url");?>/' + resultlist.imageurl + '" width="75" onerror="this.src=\'<?php echo $this->config->item('api_img_url');?>upload/item/noprivew.jpg\';" alt="' + resultlist.item_name + '">\
							</a>\
							</td>\
							<td style="width:170px;">\
							<p class="product-name" style="border:none;font-weight:bold;">\
							<a href="<?php echo base_url();?>productDetail?id=' + resultlist.item_id + '">' + resultlist.item_name + '</a>\
						</p>\
						</td>\
						<td class="a-right hidden-table" style="width:90px;">\
							<span class="cart-price">\
							<span class="price">$' + parseFloat(resultlist.item_price).toFixed(2) + '</span> </span>\
						</td>\
						<td class="a-right hidden-table " style="width:65px;"> <span class="cart-price">\
							<span class="price">' + resultlist.Sales_tax + '%</span> </span>\
						</td>\
						<td class="a-center movewishlist text-center">\
							<div class="change-qunty">\
							<button onclick="plusMinusQty(\'minus\' , \'' + resultlist.alernative_item_id + '\',\'' + resultlist.item_id + '\', ' + resultlist.item_price + ')" class="reduced items-count items-count-cart alernative_min" type="button"><i class="icon-minus">&nbsp;</i></button> <span><input style="margin-left: 2px;" class="alernative_inputbg" type="text" disabled="" id="altqty_' + resultlist.item_id+resultlist.alernative_item_id+ '" name="qty" maxlength="12" value="' + resultlist.item_quty + '" title="Quantity:" class=""></span>\
						<button onclick="plusMinusQty(\'plus\' , \'' + resultlist.alernative_item_id + '\',\'' + resultlist.item_id + '\', ' + resultlist.item_price + ')" class="increase items-count items-count-cart alernative_plus" type="button"><i class="icon-plus">&nbsp;</i></button>\
						</div>\
						</td>\
						<td class="a-right movewishlist"> <span class="cart-price"> <span class="price5555" id="itemsubtotal_' + resultlist.item_id +resultlist.alernative_item_id+ '">$' + (resultlist.item_price * resultlist.item_quty).toFixed(2) + '</span> </span>\
						</td>\
						<td style="max-width: 70px;text-align: right;"> \
						<div> <input id="myCheckbox_'+resultlist.alernative_item_id+resultlist.item_id+'"  type="checkbox" name="myCheckbox_'+resultlist.alernative_item_id+'" value="'+resultlist.item_id+'" onclick="ApproveDisApproveOrder(this, \'' + resultlist.item_id + '\', 1, \'' + resultlist.alernative_item_id + '\', '+resultlist.item_price+', '+resultlist.Sales_tax+'); unselectAlternat(\''+resultlist.alernative_item_id+'\', \'alternative\',\''+resultlist.item_id+'\' );" /> </div>\
						</td>\
						</tr>\
						</tbody>\
						</table>\
						<div class="col-md-12 ">\
							<div class="table-responsive ">\
						</div>\
						</div>\
						</fieldset>\
							</form>\
							</div>\
						</div></fieldset>\
							</form>\
							</div>\
							</div>\
							</div>';
							}
						}

					}
					jQuery('#alternative_products_all').html(paidproducts);
					OrderDetail();
				}
			})
		}



		function OrderDetail() {
			var order_user_detail = order_detail.orderdetail;
			var userorder = '';

            var shipping_address = '';
            try {
                var shipping_addr_json = JSON.parse(order_user_detail.shipping_address);
                shipping_address = shipping_addr_json.street_address;
                if(shipping_addr_json.apt_no != ''){
                    shipping_address += ', ' + shipping_addr_json.apt_no;
                }
                if(shipping_addr_json.complex_name != ''){
                    shipping_address += ', ' + shipping_addr_json.complex_name;
                }
            } catch (e) {
                shipping_address = order_user_detail.shipping_address;
            }

			userorder += '<div class="col-md-6 col-xs-6 pad20 borderRight">\
			<div class="row">\
			<div class="col-md-12" style="margin:10px 0px;">\
			<h4> ORDER-DETAILS </h4></div>\
		</div>\
		<div class="row">\
			<div class="col-md-4">\
			<p> <strong> Order ID  </strong> </p>\
		</div>\
		<div class="col-md-8">\
			<p>#' + order_user_detail.order_id + '</p>\
		</div>\
		</div>\
		<div class="row">\
			<div class="col-md-4">\
			<p> <strong>Order Date  </strong></p>\
		</div>\
		<div class="col-md-8">\
			<p> ' + unixtimestamp(order_user_detail.datetime) + '</p>\
		</div>\
		</div>\
		<div class="row">\
			<div class="col-md-4">\
			<p> <strong> Amount Paid </strong></p>\
		</div>\
		<div class="col-md-8">\
			<p>$' + order_user_detail.finalprice + ' </p>\
			</div>\
			</div>\
			</div>\
			<div class="col-md-6 col-xs-6 pad20 ">\
			<div class="row">\
			<div class="col-md-12" style="margin:10px 0px;">\
			<h4> ADDRESS </h4></div>\
		</div>\
		<div class="row">\
			<div class="col-md-4">\
			<p> <strong> User Name </strong></p>\
		</div>\
		<div class="col-md-8">\
			<p> ' + order_user_detail.first_name + ' ' + order_user_detail.last_name + '</p>\
		</div>\
		</div>\
		<div class="row">\
			<div class="col-md-4">\
			<p> <strong> Address</strong></p>\
		</div>\
		<div class="col-md-8">\
			<p>' + shipping_address + '</p>\
			</div>\
			</div>\
			<div class="row">\
			<div class="col-md-4">\
			<p> <strong> Phone </strong></p>\
		</div>\
		<div class="col-md-8">\
			<p> ' + order_user_detail.mobile + '</p>\
			</div>\
			</div>\
			</div>';

			jQuery('#orderdesc').html(userorder);
		}


		function OrderAmountDetail() {
			listarray=[];
			console.log(alternateArray);

			alternateArray.forEach(function(element) {
				var myCheckbox = jQuery('input[name="myCheckbox_'+element.alernative_item_id+'"]:checked').val();
				console.log(myCheckbox);
				var altQty = jQuery('#altqty_' + element.item_id+element.alernative_item_id).val();
				var approvelist = {};
					if(element.item_id == myCheckbox){

						approvelist.itemid = element.item_id;
						approvelist.status = 1;
						approvelist.quty = altQty;
						approvelist.alt_itemid = element.alernative_item_id;
						approvelist.price = element.item_price;
						approvelist.salesTax = element.Sales_tax;
					}else{
						approvelist.itemid = element.item_id;
						approvelist.status = 4;
						approvelist.quty = altQty;
						approvelist.alt_itemid = element.alernative_item_id;
						approvelist.price = element.item_price;
						approvelist.salesTax = element.Sales_tax;
					}
				listarray.push(approvelist);
			});
			console.log(listarray);
			var newItemCost=0;
			var newItemSalesTax=0;

			var disapproveStatusCount = 0;
			for(var i=0; i<listarray.length; i++){
				var result = listarray[i];
				if(result.status == 1){
					newItemCost = newItemCost+parseInt(result.quty)*result.price;
					newItemSalesTax = newItemSalesTax+(parseInt(result.quty)*result.price*result.salesTax)/100;
				}else{
					disapproveStatusCount++;
				}
			}
			if(disapproveStatusCount == listarray.length){
				finalObject.new_order_status=1;
			}else if(disapproveStatusCount < listarray.length){
				finalObject.new_order_status=2;
			}else{
				finalObject.new_order_status=0;
			}

			if(availableItemCount > 0 || disapproveStatusCount < listarray.length){
				// if already available item in cart
				finalObject.orderstatus=0;
			}else{
				// if all item disapprove not available item in cart
				finalObject.orderstatus=6;
			}


			var order_amount_detail = order_detail.orderdetail;

			var ordertotalamount = '';
			var amountPaid = order_amount_detail.finalprice;
			var notAvaialableItmTotal = notAvailableItemCost+notAvailableItemSalesTax;
			var newItmTotal = newItemCost+newItemSalesTax;

			var newSalesTaxTotal = order_amount_detail.tax - notAvailableItemSalesTax +newItemSalesTax;
			finalObject.ord_tax = newSalesTaxTotal.toFixed(2);

			var newOrderItemTotal= order_amount_detail.total_price-notAvailableItemCost+newItemCost;
			finalObject.ord_totalprice = newOrderItemTotal.toFixed(2);
			var newFinalprice = newOrderItemTotal+newSalesTaxTotal+order_amount_detail.dlv_charge+order_amount_detail.processingfee+order_amount_detail.tip_amount;
			finalObject.ord_finlprice = newFinalprice.toFixed(2);
			finalObject.item_detail=JSON.stringify({"item_detail":listarray});

			ordertotalamount += '\
				<div class="shopping-cart-tbl container" style="padding: 10px;">\
				<div>\
				<div class="col-sm-12 ">\
				<div class="">\
				<div class="inner">\
				<table class="table shopping-cart-table-total edit-order" id="AmountDetail">\
			<tbody>\
		<tr>\
		<td style="" class="a-left" colspan="1"> Previous order Total  </td>\
	<td style="" class="a-right"> <span class="text">$' + amountPaid.toFixed(2)+ '</span></td>\
	</tr>\
	<tr>\
		<td style="" class="a-left" colspan="1"> Not Available Items Cost</td>\
	<td style="" class="a-right"> <span class="text">$' + notAvailableItemCost.toFixed(2) + '</span></td>\
	</tr>\
	<tr>\
	<td style="" class="a-left" colspan="1"> Not Available Items Sales Tax </td>\
	<td style="" class="a-right"> <span class="price1" >$'+notAvailableItemSalesTax.toFixed(2)+'</span></td>\
	</tr>\
	<tr>\
	<td style="" class="a-left" colspan="1"> New Items Cost </td>\
	<td style="" class="a-right"> <span class="price1" >$'+newItemCost.toFixed(2)+'</span></td>\
	</tr>\
	<tr>\
	<td style="" class="a-left" colspan="1"> New Items Sales Tax </td>\
	<td style="" class="a-right"> <span class="price1" >$'+newItemSalesTax.toFixed(2)+'</span></td>\
	</tr>\
	</tbody>\
	<tfoot>\
	<tr>\
	<td style="" class="a-left" colspan="1"> <strong>Final amount will be</strong> </td>\
	<td style="" class="a-right"> <strong><span class="priceq">$'+newFinalprice.toFixed(2)+'</span></strong> </td>\
	</tr>\
	</tfoot>\
	</table>\
				<ul class="checkout">\
				<li><a type="button" href="javascript:void(0);" onclick="finalProcessPlaceOrder(\'confirm\')" title="Proceed to confirm" class="button proced-checkout"><span>Proceed To Confirm</span></a></li><br>\
			</ul>\
			</div>\
			</div>\
			</div>\
			</div>\
			</div>';
			jQuery('#amountDetailBlock').html(ordertotalamount);

		}



		function ApproveDisApproveOrder(id, itemid, status, alt_itemid, price, salesTax) {

			jQuery("#amountDetailBlock").html('');
			var myCheckbox = document.getElementsByName("myCheckbox_"+alt_itemid);
			if(id.checked == false){
				id.checked = false;
				swal({
					type:"warning",
					title:"Warning",
					text:"If no alternative item is selected, then the original item will be removed from your cart."
				});
				return false;
			}
				Array.prototype.forEach.call(myCheckbox,function(el){
					el.checked = false;
				});
				id.checked = true;

		}

		function plusMinusQty(tag,altitemid, itemid, price) {
			listarray = jQuery.grep(listarray, function (e) {
				return e.itemid != itemid;
			});
			/*jQuery('#blockapprovalstatus_' + itemid+altitemid).removeClass('disaproveblock');
			jQuery('#blockapprovalstatus_' + itemid+altitemid).removeClass('approve-block');*/
			jQuery("#amountDetailBlock").html('');
			var qty = jQuery('#altqty_' + itemid+altitemid).val();
			console.log(qty, price);

			if (qty !== '' && qty >= 1) {
				if (tag === 'plus') {
					if(qty<altitemqty[itemid]){
							qty = parseInt(qty) + 1;
							jQuery('#altqty_' + itemid+altitemid).val(qty);
							jQuery('#itemsubtotal_' + itemid+altitemid).text('$' + (qty * price).toFixed(2));
					}else{
						swal({
							title:'error',
							type:'error',
							text:'You can\'t add more than suggested quantity.'
						});
					}
				}
			}
			if (qty !== '' && qty > 1) {
				if (tag === 'minus') {
					qty = parseInt(qty) - 1;
					jQuery('#altqty_' + itemid+altitemid).val(qty);
					jQuery('#itemsubtotal_' + itemid+altitemid).text('$' + (qty * price).toFixed(2));

				}
			}
		}

		function proceedPayment(){
			var orderDetail = order_detail.orderdetail;
		}



	</script>
</body>
</html>
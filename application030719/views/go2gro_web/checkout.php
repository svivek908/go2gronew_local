<?php
 include 'header.php';
?>
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<style>	
.panel-group .panel+.panel {
	margin-top: -2px!important;
}

.checkboxcontainer input {
	display: none;
}

.checkboxcontainer {
	display: inline-block;
	padding-left: 30px;
	position: relative;
	cursor: pointer;
}

.checkboxcontainer .checkmark {
	display: inline-block;
	width: 30px;
	height: 30px;
	background: white;
	position: absolute;
	left: 0px;
	top: 0px;
	border-radius: 50%;
	border: 1px solid #cac8c8;
}

.checkboxcontainer input:checked+.checkmark {
	background-color: white;
	border: 1px solid #7fcb28;
	color: #fff!important;
}

.checkmark-tik {
	line-height: 30px;
	padding-left: 6px;
	color: #cac8c8;
}

.close-disabled {
	display: inline-block;
	width: 30px;
	height: 30px;
	background: white;
	left: 0px;
	top: 0px;
	border-radius: 50%;
	border: 1px solid #cac8c8;
}

.close-disabled:before {
	content: "\f00d";
	line-height: 28px;
	padding-left: 8px;
	color: #cac8c8;
	font-size: 14px;
}

.checkboxcontainer input:checked+.checkmark:before {
	content: "\f00c";
	font-family: FontAwesome;
	line-height: 30px;
	padding-left: 6px;
	color: #7fcb28;
}

.no-bgbtn {
	background: #ffffff!important;
	padding: 15px 0px!important;
	color: #7fcb28!important;
	width: 100%;
	margin-bottom: 10px;
	border: 1px solid #7fcb28!important;
}

.no-bgbtn:hover {
	color: #fff!important;
}

.visapayment {
	width: 100%;
	float: left;
}

.daytext {
	font-size: 14px;
	color: #b7b5b5;
	margin-bottom: 12px;
	display: block;
}

.nav-tabs>li>a {
	border: 0px!important;
}

.tabs_timedate li {
	margin-right: 29px;
	text-align: center!important;
}

.fa-credit-card {
	font-size: 26px;
	color: #8a8787;
}

.border-none {
	border: 0px!important;
}

.chackout-titel {
	padding: 20px;
	background: #fff!important;
}

.number {
	border: 2px solid #e21837;
	color: #e21837;
}

.number {
	border-radius: 50px;
	display: inline-block;
	font-weight: 700;
	height: 34px;
	line-height: 28px;
	margin: 0 8px 0 0;
	text-align: center;
	text-decoration: none;
	vertical-align: 0;
	width: 34px;
}

.complete-chack {
	font-size: 57px;
	border: 6px solid #7fcb28;
	width: 90px;
	height: 90px;
	padding: 5px;
	text-align: center;
	border-radius: 50%;
	line-height: 70px;
	color: #7fcb28;
	margin-bottom: 15px;
}

.text-Congrats {
	font-size: 24px;
	margin-bottom: 15px;
	color: #7fcb28;
}

.datel-circle {
	position: relative;
	display: block;
	padding: 0px;
	width: 60px;
	height: 60px;
	border-radius: 50%!important;
	text-align: center;
	line-height: 54px!important;
	font-size: 12px;
	border: 2px solid #b7b5b5;
	color: #b7b5b5;
	margin: 0 auto;
}

.tabs_timedate .active span {
	border-color: #7fcb28!important;
	color: #7fcb28!important;
}

.tabs_timedate .active span {
	border-color: #7fcb28!important;
	color: #7fcb28!important;
}

.nav-tabs>li.active>a,
.nav-tabs>li.active>a:hover,
.nav-tabs>li.active>a:focus {
	border: none;
	background: none;
}

.controls {
	margin-bottom: 25px;
}

.savechanges-btn {
	border: solid 1px #7fcb28 !important;
	background: #7fcb28;
	color: #fff;
	border-radius: 2px;
	padding: 6px 15px;
	font-size: 14px;
}

.savechanges-btn:hover {
	border: solid 1px #7fcb28 !important;
	background: #7fcb28;
	color: #fff;
	border-radius: 2px;
	padding: 6px 15px;
	font-size: 14px;
}

.ordercomplte-body {
	margin: 0px 60px;
	padding: 0px 60px;
}

label {
	font-size: 15px;
	margin-bottom: 10px;
}

.checkout-cardinner {
	height: 440px!important;
	border: 1px solid #dadad9!important;
	box-shadow: 0px 1px 3px 1px #8888882e;
}

.review-itemimg {
	padding: 15px;
	text-align: center;
	margin-bottom: 18px;
	box-shadow: 0px 1px 3px 1px #8888882e;
	margin-top: 16px;
}

.btn-pymentsave {
	background: #7fcb28 !important;
	border: solid 1px #7fcb28 !important;
	color: #fff !important;
	border-radius: 2px;
	padding: 6px 15px;
	font-size: 14px;
	outline-color: #7fcb28;
}

.form-control {
	border-radius: 2px;
}

.cancel-color {
	background: #e21837 !important;
	border: solid 1px #e21837 !important;
	color: #fff !important;
	border-radius: 2px;
	padding: 6px 15px;
	font-size: 14px;
	outline-color: #e21837;
}

.cancel-color:hover {
	background: #e21837 !important;
	border: solid 1px #e21837 !important;
	color: #fff !important;
	border-radius: 2px;
	padding: 6px 15px;
	font-size: 14px;
	outline-color: #e21837;
}

.pac-container {
	z-index: 100000 !important;
}

.checkoutBox-payment {
	display: inline-block;
	float: right;
}

.checkoutBox-payment input[type=checkbox]:not(old) {
	width: 2em;
	margin: 0;
	padding: 0;
	font-size: 1em;
	opacity: 0;
}

.checkoutBox-payment input[type=checkbox]:not(old)+label {
	display: inline-block;
	margin-left: -2em;
	line-height: 1.5em;
}

.checkoutBox-payment input[type=checkbox]:not(old)+label>span {
	display: inline-block;
	width: 25px;
	height: 25px;
	margin: 0.25em 0.5em 0.25em 0.25em;
	border: 0.0625em solid rgb(127, 203, 40);
	border-radius: 50px;
	background: rgb(127, 203, 40);
	background-image: -webkit-linear-gradient(rgb(236, 236, 236), rgb(202, 202, 202));
	background-image: -webkit-linear-gradient(rgb(236, 236, 236), rgb(202, 202, 202));
	background-image: -webkit-linear-gradient(rgb(236, 236, 236), rgb(202, 202, 202));
	background-image: -webkit-linear-gradient(rgb(236, 236, 236), rgb(202, 202, 202));
	background-image: -webkit-linear-gradient(rgb(236, 236, 236), rgb(202, 202, 202));
	vertical-align: bottom;
}

.checkoutBox-payment input[type=checkbox]:not(old):checked+label>span,
.checkoutBox-payment input[type=radio]:not(old):checked+label>span {
	background-image: -webkit-linear-gradient(rgb(236, 236, 236), rgb(202, 202, 202));
	background-image: -webkit-linear-gradient(rgb(236, 236, 236), rgb(202, 202, 202));
	background-image: -webkit-linear-gradient(rgb(236, 236, 236), rgb(202, 202, 202));
	background-image: -webkit-linear-gradient(rgb(236, 236, 236), rgb(202, 202, 202));
	background-image: -webkit-linear-gradient(rgb(236, 236, 236), rgb(202, 202, 202));
}

.checkoutBox-payment input[type=checkbox]:not(old):checked+label>span:before {
	content: '\f00c';
	font-family: FontAwesome;
	display: block;
	width: 25px;
	color: rgb(255, 255, 255);
	font-size: 1em;
	line-height: 24px;
	text-align: center;
	text-shadow: 0 0 0.0714em rgb(101, 101, 101);
	font-weight: bold;
	background-image: -webkit-linear-gradient(rgb(127, 203, 40), rgb(106, 169, 33));
	background-image: -webkit-linear-gradient(rgb(127, 203, 40), rgb(106, 169, 33));
	background-image: -webkit-linear-gradient(rgb(127, 203, 40), rgb(106, 169, 33));
	background-image: -webkit-linear-gradient(rgb(127, 203, 40), rgb(106, 169, 33));
	background-image: -webkit-linear-gradient(rgb(127, 203, 40), rgb(106, 169, 33));
	border-radius: 50%;
}

.no-before {
	display: none;
}

.default-add {
	display: inline-block;
	position: relative;
	top: -2px;
}

.pointerNone {
	pointer-events: none;
}

.cancle-icon {
	border: solid 1px #f9a9a9;
	display: inline-block;
	width: 25px;
	height: 25px;
	background: #ffffff;
	border-radius: 50%;
	position: relative;
	top: -9px;
	cursor: pointer;
}

.cancle-icon i {
	display: inline-block;
	color: #f57575;
	text-align: center;
	width: 25px;
	height: 25px;
	position: relative;
	top: 4px;
	left: -1px;
}

.my-error-class {
	color: red;
}

.my-valid-class {
	color: green;
}

ul.checkout li {
	float: left;
	width: 50%;
}

ul.checkout {
	width: 100%;
	display: inline-block;
	list-style: none;
}

.creditbtn {
	background: #7fcb28;
	border: 1px solid #7fcb28;
	padding: 5px 12px;
	border-radius: 2px;
}

.creditbtn:hover {
	background: #7fcb28;
	border: 1px solid #7fcb28;
	padding: 5px 12px;
	border-radius: 2px;
}

#credit-card-type {
	width: 228px;
	list-style: none;
}

#credit-card-type li {
	float: left;
	width: 51px;
	height: 32px;
	/*        background:url('*/
	<?php //echo base_url();?>/*images/payment-2.png') 0 -32px no-repeat;margin:5px 6px 0 0;overflow:hidden;text-indent:-500em;float:left;-webkit-transition:all .2s;-moz-transition:all .2s;-ms-transition:all .2s;-o-transition:all .2s;transition:all .2s;}*/
	/*    background:url('*/
	<?php //echo base_url();?>/*images/payment-3.png') 0 -32px no-repeat;margin:5px 6px 0 0;overflow:hidden;text-indent:-500em;float:left;-webkit-transition:all .2s;-moz-transition:all .2s;-ms-transition:all .2s;-o-transition:all .2s;transition:all .2s;}*/
	/**/
	/*    background:url('*/
	<?php //echo base_url();?>/*images/payment-4.png') 0 -32px no-repeat;margin:5px 6px 0 0;overflow:hidden;text-indent:-500em;float:left;-webkit-transition:all .2s;-moz-transition:all .2s;-ms-transition:all .2s;-o-transition:all .2s;transition:all .2s;}*/
	/**/
	/*    background:url('*/
	<?php //echo base_url();?>/*images/discover.png') 0 -32px no-repeat;margin:5px 6px 0 0;overflow:hidden;text-indent:-500em;float:left;-webkit-transition:all .2s;-moz-transition:all .2s;-ms-transition:all .2s;-o-transition:all .2s;transition:all .2s;}*/
	#credit-card-type .VI {
		background-position: 0px -32px;
	}
	#credit-card-type .MC {
		background-position: -51px -32px;
	}
	#credit-card-type .AE {
		background-position: -102px -32px;
	}
	#credit-card-type .DI {
		background-position: -153px -32px;
	}
	#credit-card-type .VI.active {
		background-position: 0px 0px;
	}
	#credit-card-type .MC.active {
		background-position: -51px 0px;
	}
	#credit-card-type .AE.active {
		background-position: -102px 0px;
	}
	#credit-card-type .DI.active {
		background-position: -153px 0px;
	}
	@media only screen and (max-width: 767px) and (min-width: 480px) {
		.nav-tabs>li {
			border-bottom: 0px;
		}
	}
</style>
 <?php load_css(array('public/assets/src/creditly.css?'));?>
 <?php load_js(array('public/assets/src/creditly.js?'));?>


<div id="loaderorder" class="loading"></div>
<div class="page-heading">
</div>

<!-- BEGIN Main Container -->

<div class="main-container col1-layout wow bounceInUp animated">

    <div class="main">
        <div class="cart wow bounceInUp animated">
            <div class="cart-collaterals container">

                <!-- BEGIN COL2 SEL COL 1 -->
<!---->
                <div id="loaderprofile3">
<!--loading..-->
     </div>

                <div class="col-lg-8 col-md-7 col-sm-12" >
                <!--checkout tabs start-->
                    <form  id="checkout_form" method="post">
                    <div class="panel-group checkoutprocedure" id="checkout-procedure">
                         
                          <div class="panel panel-default">
                            <div class="panel-heading chackout-titel">
                              <h4 class="panel-title">
							  <span class="number">1</span>
                                <a data-toggle="collapse" data-parent="#checkout-procedure" href="#DeliveryTo" id="deliveryToTab" onclick="tabClick('deliveryTo')">
                                Delivery Address
                                    <span class="pull-right"> <i class="fa fa-angle-up" aria-hidden="true"></i> </span>
                                </a>

                              </h4>
                            </div>
                            <div id="DeliveryTo" class="panel-collapse collapse in">
                              <div class="panel-body">
                             
                                    <div class="totals">
                                        <h3>DELIVERY DETAILS</h3>
                                        <div class="inner border-none">

                                            <div class="row">
                                                <div class="form-group col-sm-6">
                                                    <label>First Name*</label>
                                                    <input type="text" name="firstname" required id="fname" class="form-control form_control_01" value="<?php echo $first_name;?>">
                                                </div>
                                                <div class="form-group col-sm-6">
                                                    <label>Last Name*</label>
                                                    <input type="text" name="lastname" required id="lname" class="form-control form_control_01" value="<?php echo $last_name;?>">
                                                </div>
                                                <div class="form-group col-sm-6">
                                                    <label>Contact Number*</label>
                                                    <input type="number" name="mobile" required id="mobile" minlength="10" maxlength="12" class="form-control form_control_01" value="<?php echo $mobile;?>">
                                                </div>
                                                <div class="form-group col-sm-6">
                                                    <label>Email*</label>
                                                    <input type="email" name="email" required id="emailid" class="form-control form_control_01" value="<?php echo $email_id ;?>" disabled>
                                                </div>
                                            </div>
                                            <!-- end row -->

                                            
                                            <!-- end row -->

                                            <div class="row">
                                                <div class="form-group col-sm-6">
                                                    <label>Street Address*</label>
                                                    <input type="text" name="address" required id="address1" class="form-control form_control_01 map_api_autocomplete" value="<?=$street_address?>">
                                                    <input type="hidden" name="latitude" id="latitude" value="<?=$latitude?>">
                                                    <input type="hidden" name="longitude" id="longitude" value="<?=$longitude?>">
                                                </div>
                                                <div class="form-group col-sm-6">
                                                    <label>Unit</label>
                                                    <input type="text" name="apt_no" id="apt_no" class="form-control form_control_01" value="<?=$apt_no?>">
                                                </div>
                                                <div class="form-group col-sm-6">
                                                    <label>Residential Community</label>
                                                    <input type="text" name="complex_name" id="complex_name" class="form-control form_control_01" value="<?=$complex_name?>">
                                                </div>
                                                <!-- <div class="form-group col-sm-6">
                                                    <label>Country</label>

                                                    <select onchange="get_state(this.value);"  name="country"
                                                            class="form-control form_control_01" style="padding: 0px 5px !important;" id="countrygetid">

                                                    </select>
                                                </div> -->
                                                <div class="form-group col-sm-6">
                                                    <label>State*</label>
                                                    <input type="hidden" name="country" id="countrygetid" value="<?=$country_id?>">
                                                    <select onchange="get_city(this.value);" name="state" class="form-control form_control_01" style="padding: 0px 5px !important;" id="stategetid">

                                                    </select>
                                                </div>
                                                <div class="form-group col-sm-6">
                                                    <label>City*</label>
                                                    <select  class="form-control form_control_01" name="city" style="padding: 0px 5px !important;" id="citygetid">

                                                    </select>
                                                </div>
                                                <div class="form-group col-sm-6">
                                                    <label>Zip code*</label>
                                                    <input type="text" class="form-control form_control_01" name="pincode" id="pin_code" value="<?php echo $pin_code;?>">
                                                </div>
                                                <div class="form-group col-sm-12" style="margin-top: 20px;">
                                                    <div>
                                                        <label>
                                                            <span style="color: #7fcb28">*Go2Gro terms and conditions apply to all promotions, credits and offers.</span>
                                                            <input type="hidden" name="agree"> <span class="default-add"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end row -->


                                        </div><!--inner-->
                                    </div><!--totals-->
                                    <div class="col-md-12 text-right ">
                                        <button type="submit" class="btn continuebtn" style="background: #7fcb28 !important;border: solid 1px #7fcb28 !important;color: #fff !important;border-radius: 2px;" >Next</button>
                                    </div>
                              </div>
                            </div>
                          </div>


                          <div class="panel panel-default">
                            <div class="panel-heading chackout-titel">
                              <h4 class="panel-title">
							    <span class="number">2</span>
                                <a data-toggle="collapse" class="pointerNone" data-parent="#checkout-procedure" id="deliveryTab" onclick="getDeliveryTimeSlot(); tabClick('delivery');" href="#Delivery">
                                Delivery Time  <span class="pull-right"> <i class="fa fa-angle-down" aria-hidden="true"></i> </span> </a>
                              </h4>
                            </div>
                            <div id="Delivery" class="panel-collapse collapse">

                              <div class="panel-body" id="timeSlot">

                              </div>
                            </div>
                          </div>


                          <div class="panel panel-default">
                            <div class="panel-heading chackout-titel">
                              <h4 class="panel-title">
							    <span class="number">3</span>
                                <a data-toggle="collapse" class="pointerNone" id="paymentTab" data-parent="#checkout-procedure" onclick="getsavecarddata(); tabClick('payment');" href="#Payment">
                                Payment  <span class="pull-right"> <i class="fa fa-angle-down" aria-hidden="true"></i> </span></a>
                              </h4>
                            </div>
                            <div id="Payment" class="panel-collapse collapse">
                              <div class="panel-body">
                                    <div class="col-md-12 clearfix" id="cardView">
                                    </div>
                                   
                                    <div class="col-md-12 ">
                                        <button type="button" class="btn btn-primary creditbtn" data-toggle="modal" data-target="#creditcard" onclick="resetForm();">  Add Card</button>
                                    </div>

                                   <!--  <div class="col-md-12 ">
                                        <button type="button" disabled id="paymentContinue" class="btn continuebtn pull-right" style="background: #78b92f !important;border: solid 1px #78b92f !important;color: #fff !important;" > Continue</button>
                                    </div> -->
                              </div>
                            </div>
                          </div>

                        <div class="panel panel-default">
                            <div class="panel-heading chackout-titel">
                                <h4 class="panel-title">
								  <span class="number">4</span>
                                    <a data-toggle="collapse" class="pointerNone" id="reviewTab" data-parent="#checkout-procedure" href="#review" onclick="tabClick('review');">
                                        Review <span class="pull-right"> <i class="fa fa-angle-down" aria-hidden="true"></i> </span></a>
                                </h4>
                            </div>
                            <div id="review" class="panel-collapse collapse">
                                <div class="panel-body" id="reviewItem"></div>
								
                            </div>
                        </div>
                      
						 <!-- <div class="panel panel-default ordercomplte">
						  <div class="panel-heading chackout-titel">
							<h4 class="panel-title">
							<span class="number">5</span>
							  <a data-toggle="collapse" href="#collapse1">Order Complete  <span class="pull-right"> <i class="fa fa-angle-down" aria-hidden="true"></i> </span></a>
							</h4>
						  </div>
						  <div id="collapse1" class="panel-collapse collapse">
						     <div class="panel-body">
							 <div class="col-lg-12 text-center">
							 <div class="ordercomplte-body text-center">
							 <!-- <i class="fas fa-check complete-chack"></i>
							 <h4 class="text-Congrats">Congrats! Your Order has been Accepted..</h4>
							 <button type="button" title="Place Order" id="placeOrderfinal" onclick="want_to_tip()" class="button btn-proceed-checkout no-bgbtn" ><span>Place Order</span></button>
							 </div>
							 </div>
							 </div>
						  </div>
						</div>-->

					
                       <!-- <div class="col-md-12 no-pad ordercomplte">
                            <div class="col-md-7 col-xs-6"><p> Order Complete</p> </div>
                            <div class="col-md-5 col-xs-6">
                                <button type="button" title="Place Order" id="placeOrderfinal" onclick="want_to_tip()" class="button btn-proceed-checkout" ><span>Place Order</span></button>
                            </div>
                        </div>-->
                        </div>
                        </form>
                <!--checkout tabs End-->
                

                </div> <!--col-sm-4-->

                <div class="col-lg-4 col-md-5 col-sm-12">
                    <div class="totals">
                        <h3>Shopping Cart Total</h3>
                        <div class="inner checkout-cardinner " style="">

                            <table id="shopping-cart-totals-table" class="table shopping-cart-table-total">
                                <colgroup><col>
                                    <col width="1">
                                </colgroup>
                                <tfoot class="Total_Amount35show">

                                <tr>
                                    <td style="" class="a-left" colspan="1">
                                        Total
                                    </td>
                                    <td style="" class="a-right">
                                        <span class="price" id="subtotalcartprice">0.00</span>
                                    </td>
                                </tr>
                                <tr style="background: #7fcb281a;">
                                    <td style="color: #ec1c24 !important;" class="a-left" colspan="1">
                                        <strong>Grand Total</strong>
                                    </td>
                                    <td style="color: #ec1c24 !important;" class="a-right">
                                        <strong><span class="price" id="finalcartprice">0.00</span></strong>
                                    </td>
                                </tr>

                                </tfoot>
                                <tbody class="Total_Amount34show">
                                <tr>
                                    <td style="" class="a-left" colspan="1">
                                        Subtotal    </td>
                                    <td style="" class="a-right">
                                        <span class="price11" id="cartpriceval11">$0.00</span>  </td>
                                </tr>
                                <tr>
                                    <td style="" class="a-left" colspan="1">
                                        Sales  Tax  </td>
                                    <td style="" class="a-right">
                                        <span class="text" id="taxval" >$0.00</span></td>
                                </tr>
                                <tr>
                                    <td style="" class="a-left" colspan="1">
                                        Processing Fee </td>
                                    <td style="" class="a-right">
                                        <span class="text" id="fee" >$0.00</span>    </td>
                                </tr>
                                <tr>
                                    <td style="" class="a-left" colspan="1">
                                        Delivery Charge    </td>
                                    <td style="" class="a-right">
                                        <span class="price1" id="coupencode1">$0.00</span>    </td>
                                </tr>
<!--                                <tr>-->
<!--                                    <td style="" class="a-left" colspan="1">-->
<!--                                        Tip </td>-->
<!--                                    <td style="" class="a-right" style="border:solid 1px #c00;">-->
<!--                                        <span class="text"  ><input type="number" id="tipamount" step="0.00" value="" class="valid" style="width: 100%;"> </span>-->
<!--                                    </td>-->
<!--                                </tr>-->

                                </tbody>
                            </table>

                        </div><!--inner-->
                     </div><!--totals-->
					 <div class="col-sm-12" style="padding:0px;">
                    <div class="checkout-note">
					<span><i class="fa fa-credit-card" aria-hidden="true"></i></span>
                        <p> Your card will temporary authorized for <b id="authAmount"></b>. Your statement will  reflect total amount after order completion.</p>
                    </div>
					</div>

                </div> <!--col-sm-4-->

            </div>
        </div> <!--cart-collaterals-->



    </div>  <!--cart-->

</div><!--main-container-->
<!--<button type="button" onclick=" RefundMoney();" title="refundmoney" name="refundmoney" id="refundmoney"   class=" button btn-proceed-checkout" ><span>refundmoney</span></button>-->

<section class="section-padding bg-white border-top section-margin">
         <div class="container">
            <div class="row">
               <div class="col-lg-4 col-sm-6 col-xs-12">
                  <div class="feature-box">
                     <i class="mdi mdi-truck-fast"><i class="fas fa-truck-moving"></i></i>
                     <h6>Free &amp; Next Day Delivery</h6>
                     <p>Lorem ipsum dolor sit amet, cons...</p>
                  </div>
               </div>
               <div class="col-lg-4 col-sm-6 col-xs-12">
                  <div class="feature-box">
                     <i class="mdi mdi-basket"><i class="fas fa-shopping-basket"></i></i>
                     <h6>100% Satisfaction Guarantee</h6>
                     <p>Rorem Ipsum Dolor sit amet, cons...</p>
                  </div>
               </div>
               <div class="col-lg-4 col-sm-6 col-xs-12">
                  <div class="feature-box">
                     <i class="mdi mdi-tag-heart"><i class="fas fa-tags"></i></i>
                     <h6>Great Daily Deals Discount</h6>
                     <p>Sorem Ipsum Dolor sit amet, Cons...</p>
                  </div>
               </div>
            </div>
         </div>
      </section>




</div> <!--col1-layout-->

<?php include 'footer.php' ?>
    <!--<script src="https://www.paypalobjects.com/api/checkout.js"></script>-->

<!-- Modal -->

  <div class="modal fade" id="creditcard" role="dialog" style="background: rgba(0,0,0,0.5);position: fixed;">
    <div class="modal-dialog">

        <!-- Modal content -->
        <div class="modal-content" style="margin-top:20%;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3 class="modal-title text-center">Add Card</h3>
            </div>
            <form action="javascript:void(0);" method="post" id="authrize_form" class="creditly-card-form">
                <div class="modal-body clearfix checkout-card">
                    <div class="col-md-12">
                        <div class="form-group marT15 clearfix">
                            <section class="auth-card">
                                <div class="credit-card-wrapper">
                                    <div class="first-row form-group">
                                        <div class="col-sm-12 controls">
                                            <label class="control-label">Card Number*</label>
                                            <input class="credit-card-number form-control"
                                                   type="text" name="card_number" id="authorizenet-card-number"
                                                   inputmode="numeric" autocomplete="cc-number" autocompletetype="cc-number" x-autocompletetype="cc-number"
                                                   placeholder="&#149;&#149;&#149;&#149; &#149;&#149;&#149;&#149; &#149;&#149;&#149;&#149; &#149;&#149;&#149;&#149;" required="">
                                               </div>
                                    </div>
                                    <div class="second-row form-group">
                                        <div class="col-sm-6 controls">
                                            <label class="control-label">Expiration*</label>
                                            <input id="authorizenet-card-expiry" class="expiration-month-and-year form-control" required
                                                   type="text" name=cardexpiry"
                                                   placeholder="MM / YY">
                                        </div>
                                        <div class="col-sm-6 controls">
                                            <label class="control-label">CVV*</label>
                                            <input id="authorizenet-card-cvc" class="security-code form-control"
                                                   inputmode="numeric"
                                                   type="text" name="cvv"
                                                   placeholder="&#149;&#149;&#149;">
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                        <div class="col-sm-12 controls">
                            <label class="control-label">Billing Address*</label>
                            <input type="text" name="billingAddress" class="form-control map_api_autocomplete" id="txtPlaces"  placeholder="Enter a location" />
                        </div>
                        <div class="col-sm-12 controls">
                            <label class="control-label">Zip Code*</label>
                            <input type="text" name="zipcode" id="zipcode" placeholder="Zip Code" class="form-control zipcode-container" style="display: inline-block; margin-right:5px;">
                            <p class="marT15 addressvali"style="display: inline-block;" ></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id='save-card' class="btn btn-default savechanges-btn" >Save</button>
                    <button type="button" class="btn btn-default  cancel-color" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
 </div>


<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB-wq5TQX9liVeO4LoNFs8tF48H0PqKy2o&libraries=places&callback=initAutocomplete" async defer></script>
<script>
    // jQuery("#tipamount").numeric();
    // paypalcheckoutbutton();
    get_country();
    get_state(<?php echo $country_id;?>);
    get_city(<?php echo $state_id;?>);
    cartRewiew(); // changed the calling event of this function as this function now returns tips array and tips array is needed on load
    var cardData = {};
    var chooseCardData ={};
    var finalOrderPlaceObj = {};
    finalOrderPlaceObj.first_name ='<?php echo $first_name; ?>';
    finalOrderPlaceObj.last_name = '<?php echo $last_name; ?>';
    finalOrderPlaceObj.email_id = '<?php echo $email_id; ?>';
    finalOrderPlaceObj.mobile = '<?php echo $mobile; ?>';
    finalOrderPlaceObj.address= '<?php echo $street_address; ?>';
    finalOrderPlaceObj.pincode = '<?php echo $pincode; ?>';
    finalOrderPlaceObj.api_key = '<?php echo $apikey; ?>';
    finalOrderPlaceObj.country_id = '<?php echo $country_id; ?>';
    finalOrderPlaceObj.state_id = '<?php echo $state_id; ?>';
    finalOrderPlaceObj.city_id = '<?php echo $city_id; ?>';
    finalOrderPlaceObj.county_name = '<?php echo $country_name; ?>';
    finalOrderPlaceObj.state_name = '<?php echo $state_name; ?>';
    finalOrderPlaceObj.city_name = '<?php echo $city_name; ?>';

    var billingCity ='';
    var billingState = '';
    var billingCountry = '';


    //  mycartdata();

    jQuery(document).ready(function(){
        //contentdata();
    });


    function continueTab(tag){
        if(tag == "deliveryTo"){
            jQuery("#deliveryTab").click();
            jQuery("#deliveryToTab").find("i").removeClass('fa-angle-up');
            jQuery("#deliveryToTab").find("i").addClass('fa-angle-down');
            jQuery("#deliveryTab").find("i").removeClass('fa-angle-down');
            jQuery("#deliveryTab").find("i").addClass('fa-angle-up');
            jQuery("#deliveryTab").removeClass("pointerNone");
        }else if(tag == "delivery"){
            jQuery("#paymentTab").click();
            jQuery("#deliveryTab").find("i").removeClass('fa-angle-up');
            jQuery("#deliveryTab").find("i").addClass('fa-angle-down');
            jQuery("#paymentTab").find("i").removeClass('fa-angle-down');
            jQuery("#paymentTab").find("i").addClass('fa-angle-up');
            jQuery("#paymentTab").removeClass("pointerNone");
        }else if(tag == "payment"){
            jQuery("#reviewTab").click();
            jQuery("#paymentTab").find("i").removeClass('fa-angle-up');
            jQuery("#paymentTab").find("i").addClass('fa-angle-down');
            jQuery("#reviewTab").find("i").removeClass('fa-angle-down');
            jQuery("#reviewTab").find("i").addClass('fa-angle-up');
            jQuery("#reviewTab").removeClass("pointerNone");
            //jQuery("#placeOrderfinal").prop('disabled', false);

        }
    }
    function resetForm(){
        jQuery('#authrize_form')[0].reset();
        jQuery('.addressvali').text('');
    }

    function tabClick(tag){
        if(tag == 'deliveryTo'){
            var hasClass = jQuery('#deliveryToTab').hasClass('collapsed');
            if(hasClass){
                jQuery("#deliveryToTab").find("i").removeClass('fa-angle-down');
                jQuery("#deliveryToTab").find("i").addClass('fa-angle-up');
            }else{
                jQuery("#deliveryToTab").find("i").removeClass('fa-angle-up');
                jQuery("#deliveryToTab").find("i").addClass('fa-angle-down');
            }
        }else if(tag == 'delivery'){
            var hasClass = jQuery('#deliveryTab').hasClass('collapsed');
            if(hasClass){
                jQuery("#deliveryTab").find("i").removeClass('fa-angle-down');
                jQuery("#deliveryTab").find("i").addClass('fa-angle-up');
            }else{
                jQuery("#deliveryTab").find("i").removeClass('fa-angle-up');
                jQuery("#deliveryTab").find("i").addClass('fa-angle-down');
            }
        }else if(tag == 'payment'){
            var hasClass = jQuery('#paymentTab').hasClass('collapsed');
            if(hasClass){
                jQuery("#paymentTab").find("i").removeClass('fa-angle-down');
                jQuery("#paymentTab").find("i").addClass('fa-angle-up');
            }else{
                jQuery("#paymentTab").find("i").removeClass('fa-angle-up');
                jQuery("#paymentTab").find("i").addClass('fa-angle-down');
            }
        }else if(tag == 'review'){
            var hasClass = jQuery('#reviewTab').hasClass('collapsed');
            if(hasClass){
                jQuery("#reviewTab").find("i").removeClass('fa-angle-down');
                jQuery("#reviewTab").find("i").addClass('fa-angle-up');
            }else{
                jQuery("#reviewTab").find("i").removeClass('fa-angle-up');
                jQuery("#reviewTab").find("i").addClass('fa-angle-down');
            }
        }

    }
    function cartRewiew(){
        var hasClass = jQuery('#review').hasClass('in');
        console.log(hasClass);
        if(!hasClass){
            jQuery("#reviewItem").html('<div class="deliveryloader"></div>');

            getCartData().done(function(res){
                if(!res.error){
                    // set_tip(res.tips_arr);

                    var result=res.item;
                    var reviewHtml = '<div class="col-md-12 no-pad">\
                <h3>Review</h3>\
                </div>';
                    for(var i= 0; i<result.length; i++){
                        var reviewItems = result[i];
                        reviewHtml += '<div class="col-xs-12 review-product">\
                                            <div class="col-xs-2 review-itemimg"><img src="<?php echo $this->config->item('api_img_url'); ?>'+reviewItems.item_image+'" width="50" height="50" onerror="this.src=\'<?php echo $this->config->item('api_img_url');?>upload/item/noprivew.jpg\';"> </div>\
											<div class="col-xs-2 text-center" style="font-size: 14px;margin-top: 36px;">'+reviewItems.item_quty+'</div>\
                                            <div class="col-xs-6" style="font-size: 14px;margin-top: 36px;">'+reviewItems.item_name+' </div>\
                                            <div class="col-xs-2" style="font-size: 14px;margin-top: 36px;" >$'+(reviewItems.item_price*reviewItems.item_quty).toFixed(2)+'</div>\
                                        </div>';
                    }
                    /*reviewHtml += '<div class="col-md-12 ">\
        <button type="button" id="reviewContinue" class="btn continuebtn pull-right" onclick="continueTab(\'review\')" style="background: #78b92f !important;border: solid 1px #78b92f !important;color: #fff !important;" > Continue</button>\
        </div>';*/
                }
				reviewHtml += '<div class="col-lg-12 text-center">\
							 <div class="ordercomplte-body text-center">\
							 <button type="button" title="Place Order" id="placeOrderfinal" onclick="want_to_tip()" class="button btn-proceed-checkout no-bgbtn" ><span>Place Order</span></button></div></div>';
                jQuery("#reviewItem").html(reviewHtml);
            });
        }
    }

    function getDeliveryTimeSlot(){
        var hasClass = jQuery('#Delivery').hasClass('in');
        if(!hasClass){
            //jQuery("#placeOrderfinal").prop('disabled', true);
            jQuery('#timeSlot').html('<div class="deliveryloader"></div>');
            jQuery.ajax({
                type: 'GET',
                url: '<?php echo base_url();?>getDeliveryTimeslot',
                success: function(res) {
                    var timeslothtml='<div class="col-md-12 no-pad">\
                    <h3 id="cdt">Choose Delivery Times</h3>\
                </div>\
                <div class="col-md-12 no-pad choose-time" id="timeSlot">\
                    ';
                    if(!res.error){
                        var slots =res.slots;
                        var timeZone ='<?php echo $this->config->item('time_zone'); ?>';
                        var startTimeDelivry = <?php echo $this->config->item('start'); ?>;
                        var endTimeDelivry = <?php echo $this->config->item('end'); ?>;
                        timeslothtml +='<ul class="nav nav-tabs tabs_timedate" style="width: 100% !important;margin-bottom:0px;padding-bottom: 12px;">';
                        for(var i=0; i<slots.length; i++) {
                            var result = slots[i];
                            var day = moment.unix(result.unitime).tz(timeZone).format("dddd");
                            var daten = moment.unix(result.unitime).tz(timeZone).format("D MMM");
                            var current_year = moment.unix(result.unitime).tz(timeZone).format("MMM YYYY");
                            var dt = moment.unix(result.unitime).tz(timeZone).format("D");
                            timeslothtml += '<li class="' + (i == 0 ? 'active' : '') + '"><a data-toggle="tab" href="#acc-day' + i + '" style="    padding: 0px !important;text-align: center;"><span class="datel-circle">'+ dt + '</span><span class="text-center daytext">'+result.work+'</span></a></li>';
                        }
                        timeslothtml += '</ul>';
                        timeslothtml += '<div class="tab-content" style="padding:0px 0px;">';

                        for(var j=0; j<slots.length; j++) {
                            var slotsDay = slots[j];
                            var perDaySlot = slotsDay.perdayslot;
                            var convertedHour = parseInt(slotsDay.converted);
                            var converTwoHour = convertedHour+2;
                            var convertedDate = parseInt(moment.unix(slotsDay.unitime).tz(timeZone).format("DD"));
                            var currentDate = parseInt(moment().tz(timeZone).format("DD"));
                            console.log("cd"+currentDate);
                            console.log("conver"+convertedDate);

                            timeslothtml += '<div id="acc-day'+j+'" class="tab-pane day-detail fade in '+(j==0?'active':'')+'">\
                        <ul>';
                            for(var k=0; k<perDaySlot.length; k++) {
                                var resultPerDay = perDaySlot[k];
                                var startTime = resultPerDay.start_time;
                                var startHour = parseInt(startTime.split(':')[0]);
                                var btn;
                                var classActive = '';
                                var checkedBox = '';
                                console.log((startHour+'>='+convertedHour));

                                if(!(_.isEmpty(timeSlotObject))){
                                    if(timeSlotObject.convertedate == convertedDate && timeSlotObject.slotid == resultPerDay.time_slot_id){

                                       //classActive = 'choose-time-btnactive';
                                        //checkedBox = 'checked';
										classActive = 'checkmark:before';
                                        checkedBox = 'checked';
                                        }else{
                                        classActive = '';
                                        checkedBox = '';
                                    }
                                }
                                /*if(resultPerDay.avilable == "0" && startHour == 0 && parseInt(slotsDay.converted) > startTimeDelivry && parseInt(slotsDay.converted) < endTimeDelivry){
                                    btn = ' <div class="choose-time-btn pull-right  btn btn-default '+classActive+'"><label> <input name="timecheck" '+checkedBox+'  onclick="chooseTimeslot(this, \''+slotsDay.date+'\', \''+resultPerDay.time_slot_id+'\', '+convertedDate+')" type="checkbox" />Choose</label></div>';
                                }else if(resultPerDay.avilable == "0" && convertedDate> currentDate){
                                    btn = '<div class="choose-time-btn pull-right  btn btn-default '+classActive+'"><label> <input name="timecheck" '+checkedBox+' onclick="chooseTimeslot(this, \''+slotsDay.date+'\', \''+resultPerDay.time_slot_id+'\', '+convertedDate+')" type="checkbox" />Choose</label></div>';
                                }else if(resultPerDay.avilable == "0" && startHour>=converTwoHour){
                                    btn = '<div class="choose-time-btn pull-right  btn btn-default '+classActive+'"><label> <input name="timecheck" '+checkedBox+' onclick="chooseTimeslot(this, \''+slotsDay.date+'\', \''+resultPerDay.time_slot_id+'\', '+convertedDate+')" type="checkbox" />Choose</label></div>';
                                }else{
                                    btn = '<button type="button" class="pull-right btn btn-default unavailable" disabled>Unavailable</button>';
                                }*/
                                if(resultPerDay.avilable == "0" && resultPerDay.isShowTIme == 0){
                                    <!-- btn = ' <div class="choose-time-btn pull-right  btn btn-default '+classActive+'"><label><input name="timecheck" '+checkedBox+'  onclick="chooseTimeslot(this, \''+slotsDay.date+'\', \''+resultPerDay.time_slot_id+'\', '+convertedDate+')" type="checkbox" />Choose</label></div>';-->\
									btn = ' <div class="choose-time-btn pull-right '+classActive+'"><label class="checkboxcontainer"><input type="checkbox" name="timecheck" onclick="chooseTimeslot(this, \''+slotsDay.date+'\', \''+resultPerDay.time_slot_id+'\', '+convertedDate+')" '+checkedBox+'><span class="checkmark '+classActive+'"><i class="fas fa-check checkmark-tik"></i></span></label></div>';
                                    timeslothtml += '<li class="clearfix" style="margin-bottom:25px;">\
                                    <div class="col-md-12 clearfix no-pad">\
                                    <div class="col-md-4 col-xs-7">'+resultPerDay.slot_name+'</div>\
                                    <div class="col-md-8 col-xs-5">\
                                    '+btn+'\
                                    </div>\
                                    </div>\
                                    </li>';

                                    }else{
                                   <!--  btn = '<button type="button" class="pull-right btn btn-default unavailable" disabled>Unavailable</button>';-->\
								      <!--  btn = '<span class="pull-right  unavailable" disabled><i class="fa fa-times close-disabled"></i></span>';-->
                                }
                            }
                            timeslothtml += '</ul></div>';
                        }
                        timeslothtml += '</div></div>\
                        <div class="col-md-12 ">\
                        <!--<button disabled type="button" id="deliveryContinue" class="btn continuebtn pull-right" style="background:#78b92f !important;border: solid 1px #78b92f !important;color: #fff !important;" > Continue</button>-->\
                        </div>';
                        jQuery('#timeSlot').html(timeslothtml);
                        jQuery('#cdt').html(current_year);
                    }
                }
            });
        }
    }

    function chooseTimeslot(id, scheduleDate, slotid, convertedDate){
        continueTab('delivery');
        var myCheckbox = document.getElementsByName("timecheck");
        if(id.checked == false){
            id.checked = true;
            //id.parentElement.parentElement.classList.remove('choose-time-btnactive');
            return false;
        }
        Array.prototype.forEach.call(myCheckbox,function(el){
            el.checked = false;
            el.parentElement.parentElement.classList.remove('choose-time-btnactive');
        });
        id.parentElement.parentElement.classList.add('choose-time-btnactive');
        id.checked = true;
        timeSlotObject.date = scheduleDate;
        timeSlotObject.slotid = slotid;
        timeSlotObject.convertedate = convertedDate;
        console.log(timeSlotObject);
    }



    function get_country() {
        var api_key = '<?php echo $apikey;?>';

        jQuery.ajax({
            type: 'GET',
            url: '<?php echo base_url();?>getCountry',
            data: {
                'Authorization': api_key
            },
            success: function(res) {
                if (res.error = true) {

                }
                console.log(res);
                var countryval = res.country;
                var countrylength = res.country.length;
                html += '<option id="did"  value="">please Select Country</option>';
                for (var i = 0; i < countrylength; i++) {
                    var result = countryval[i];
                    var html = '';
                    //jQuery('#countrygetid').attr(result.name);

                    html += '<option id="did"  value="' + result.id + '">' + result.name + '</option>';
                    jQuery('#countrygetid').append(html);
                }


            }
        });
    }

    function get_state(id) {

        var api_key = '<?php echo $apikey;?>';
        jQuery.ajax({
            type: 'GET',
            url: '<?php echo base_url();?>getState',
            data: {
                'Authorization': api_key,
                'cid': id
            },
            success: function(res) {
                console.log(res);
                var stateval = res.state;
                var statelength = res.state.length;
                console.log(statelength);
                html += '<option id="did"  value="">please Select state</option>';
                for (var i = 0; i < statelength; i++) {
                    var result = stateval[i];
                    var html = '';
                    //                    jQuery('#countrygetid').text(result.name);

                    html += '<option  value="' + result.id + '">' + result.name + '</option>';
                    jQuery('#stategetid').append(html);
                }


            }
        });
    }

    function get_city(id) {
        var api_key = '<?php echo $apikey;?>';
        jQuery.ajax({
            type: 'GET',
            url: '<?php echo base_url();?>getCity',
            data: {
                'Authorization': api_key,
                'city_id': id
            },
            success: function(res) {
                console.log(res);
                var countryval = res.city;
                var countrylength = res.city.length;
                html += '<option id="did"  value="">please Select City</option>';
                for (var i = 0; i < countrylength; i++) {
                    var result = countryval[i];
                    var html = '';
                    //                    jQuery('#countrygetid').text(result.name);

                    html += '<option  value="' + result.id + '">' + titleCase(result.name) + '</option>';
                    jQuery('#citygetid').append(html);
                }


            }
        });
    }

    function ordertimingcheck111() {
        jQuery.ajax({
            type: 'GET',
            url: '<?php echo base_url();?>orderplacedcheck',
            data: {},
            success: function(res) {



         if (res.error == true) {
                    swal('', res.message, 'warning');
             jQuery(".loaderprofile4").text('Place Order');
                } else {
             jQuery(".loaderprofile4").text('Place Order');
            var val=jQuery('input[name="payment_method"]:checked').val();
            // $( "input[name*='man']" ).val( "has man in it!" );

             if(val==1){
                 jQuery("#loaderprofile3").addClass('loading');
                authorizenetdataget();
             }else if(val==2)
             {
             
                 paypalcheckoutbutton();
             }
                }
}
        });
        return false;
    }



    function taxcalculate() {
        var cartpriceval = jQuery('#cartpriceval').text();
        var resstr = cartpriceval.replace("$", " ");
        var taxval = jQuery('#taxval').text();
        var tax = (resstr * taxval) / 100;
        var finalprice = parseFloat(resstr) + parseFloat(taxval);
        var resj = finalprice.toFixed(2);
        return finalprice;

    }

    jQuery(function () {
        jQuery("#checkout_form").validate({

            // Specify the validation rules
            rules: {
                firstname: "required",
                lastname: "required",
                email: {
                    required: true,
                    email: true
                },
                mobile: {
                    required: true,
                    minlength: 10,
                    maxlength: 12
                },
                address: "required",
                country: "required",
                state: "required",
                city: "required",
                pincode: {
                    required: true,
                    minlength: 5
                }
            },
            // Specify the validation error messages
            messages: {
                firstname: "Please enter your first name",
                lastname: "Please enter your last name",
                mobile: {
                    required: "Please provide a valid mobile number",
                    minlength: "Your  mobile number must be 10 digits",
                    maxlength: "Your  mobile number must be 12 digits"
                },
                email: "Please enter a valid email address",
                address: "Please enter your address",
                country: "Please enter your country",
                state: "Please enter your state",
                city: "Please enter your city",
                pincode: {
                    required: "Please provide a pincode",
                    minlength: "Your zipcode must be 5 characters long"
                }
            },
            submitHandler: function (form) {
               // ordertimingcheck111();
                var firstname = jQuery('#fname').val();
                var lastname = jQuery('#lname').val();
                var email = jQuery('#emailid').val();
                var country = jQuery('#countrygetid').val();
                var state = jQuery('#stategetid').val();
                var city = jQuery('#citygetid').val();
                var mobile = jQuery('#mobile').val();
                var pin_code = jQuery('#pin_code').val();
                var street_address = jQuery('#address1').val();
                var apt_no = jQuery('#apt_no').val();
                var complex_name = jQuery('#complex_name').val();
                var latitude = jQuery('#latitude').val();
                var longitude = jQuery('#longitude').val();
                var address ={"street_address":street_address,"apt_no":apt_no, "complex_name":complex_name,"latitude":latitude,"longitude":longitude};
                // console.log(address);
                finalOrderPlaceObj.first_name =firstname;
                finalOrderPlaceObj.last_name = lastname;
                finalOrderPlaceObj.email_id = email;
                finalOrderPlaceObj.mobile = mobile;
                finalOrderPlaceObj.address= address;
                finalOrderPlaceObj.pincode = pin_code;
                finalOrderPlaceObj.country_id = country;
                finalOrderPlaceObj.state_id = state;
                finalOrderPlaceObj.city_id = city;
                continueTab('deliveryTo');
            }
        });
        return false;
    });


    jQuery(function () {
        var creditly = Creditly.initialize(
            '.auth-card .expiration-month-and-year',
            '.auth-card .credit-card-number',
            '.auth-card .security-code',
            '.auth-card .card-type');

        // Setup form validation on the #register-form element
        jQuery("#authrize_form").validate({
            // Specify the validation rules
            rules: {
                card_number: {
                    required: true,
                    //minlength: 19
                },
                billingAddress: "required",
                zipcode: "required",
                cardexpiry: "required",
                cvv: "required"
            },


            // Specify the validation error messages
            messages: {
                card_number: {
                    required: "Please enter valid card number",
                    //minlength: "Enter 16 digit card number"
                },
                billingAddress: "Please enter billing address",
                zipcode: "Please enter valid zipcode",
                cardexpiry: "Please enter valid expiry",
                cvv: "Please enter valid cvv"
            },

            submitHandler: function (form) {
                jQuery("#Payment").addClass('panel-collapse collapse in');
                var output = creditly.validate();
                if (output) {

                    // Your validated credit card output
                    var card_number = output["number"];
                    var cvv = output["security_code"];

                    console.log(output);
                    saveCardData(card_number, cvv);
                }else{
                    swal("Error", "Invalid card", "error");
                }
            }
        });
    return false;
});



    function saveCardData(card_number, card_cvv){

        jQuery("#loaderorder").addClass('loading');
        var price = localStorage.getItem("total_am");
        var totaltax = localStorage.getItem("totaltax");
        var card_expiration =jQuery('#authorizenet-card-expiry').val();
        var card = card_expiration.split('/');
        var cardmonth = card[0].trim()+'/'+card[1].trim();
        console.log(card_expiration);
        var firstname = finalOrderPlaceObj.first_name;
        var lastname = finalOrderPlaceObj.last_name;
        var email = finalOrderPlaceObj.email_id;
        var mobile = finalOrderPlaceObj.mobile;
        var billingaddress = jQuery('#txtPlaces').val();
        var billingaddressdb =billingaddress;
        billingaddress = billingaddress.substring(0, 29);
        console.log("billing---"+billingaddress.length);
        var billingzip = jQuery('#zipcode').val();

        finalOrderPlaceObj.county_name = '<?php echo $country_name ?>';
        finalOrderPlaceObj.state_name = '<?php echo $state_name ?>';
        finalOrderPlaceObj.city_name = '<?php echo $city_name ?>';

        if(billingCity == '') billingCity =finalOrderPlaceObj.city_name;
        if(billingCountry == '') billingCountry=finalOrderPlaceObj.county_name;
        if(billingState == '') billingState =finalOrderPlaceObj.state_name;

        if(_.isEmpty(cardData)){
            jQuery.ajax({
                type: 'POST',
                url: '<?php echo base_url();?>createCustomerProfile',
                data: {
                    'card_number':card_number,
                    'card_expiry':cardmonth,
                    'card_cvc':card_cvv,
                    'firstname':firstname,
                    'lastname':lastname,
                    'email':email,
                    'country':billingCountry,
                    'state':billingState,
                    'city':billingCity,
                    'address':billingaddress,
                    'mobile':mobile,
                    'pin_code':billingzip

                },
                beforeSend: function ()
                {
                    ajaxindicatorstart('Please Wait...');
                },
                success: function (res) {
                    if(res.error==true){
                        swal({
                            title:"Error!" ,
                            text:res.message,
                            type:'error'
                        },function(){
                             ajaxindicatorstop();
                            jQuery("#loaderorder").removeClass('loading');
                        });

                    }else{
                        console.log(res);
                        var customerPaymentProfileId=res.paymentProfilesID;

                        var customerProfileId=res.CustomerProfileId;
                        cardData.customerProfileId=customerProfileId;
                        getCustomerProfile(customerPaymentProfileId, customerProfileId).success(function(rescard){

                            saveCard(customerPaymentProfileId, customerProfileId,billingaddressdb, billingzip, rescard.CardLast4, rescard.Cardtype).success(function(res) {
                                if (res.error) {
                                    ajaxindicatorstop();
                                    swal("Error", res.message, "error");
                                } else {
                                    ajaxindicatorstop();
                                    swal({
                                        title:"Success",
                                        type:"success",
                                        text:"Card profile created successfully"
                                    },function(){
                                        jQuery('#creditcard').css("display","none");
                                        jQuery('#creditcard').removeClass("in");
                                        jQuery("#loaderorder").removeClass('loading');
                                        console.log(rescard);
                                        var cardhtml ='';
                                        var cardImage = getCardImage(rescard.Cardtype);
                                        cardhtml += '<div class="visapayment" id="card_id_'+customerPaymentProfileId+'">\
                                <img src="'+cardImage+'" width="30px" height="20px">\
                                <p style="display:inline;">'+rescard.CardLast4+'</p>\
                            <div class="checkoutBox-payment">\
									  <input type="checkbox" onclick="toggleCheckbox(this, \''+res.card_id+'\', \''+customerProfileId+'\', \''+customerPaymentProfileId+'\')" name="checkbox"><label for="checkbox1"><span></span></label>\
									  <div class="cancle-icon" onclick="removesavecard(\''+customerProfileId+'\', \''+customerPaymentProfileId+'\')" > <i class="fa fa-close"> </i>  </div>\
									  </div>\
                            </div>';
                                        jQuery("#cardView").append(cardhtml);
                                    });

                                }
                            });

                        });
                    }
                }
            });

        }else{
            console.log('save card');
            var user_id='<?php echo $userId; ?>';
            var CustomerProfileId=cardData.customerProfileId;

            jQuery.ajax({
                type: 'POST',
                url: '<?php echo base_url();?>createCustomerPaymentProfile',
                data: {
                    'customerProfileId':CustomerProfileId,
                    'card_number':card_number,
                    'card_expiry':cardmonth,
                    'card_cvc':card_cvv,
                    'firstname':firstname,
                    'lastname':lastname,
                    'country':billingCountry,
                    'state':billingState,
                    'city':billingCity,
                    'address':billingaddress,
                    'mobile':mobile,
                    'pin_code':billingzip

                },
                beforeSend: function ()
                {
                    ajaxindicatorstart('Please Wait...');
                },
                success: function (res) {
                    if(res.error==true){
                        swal({
                            title:"Error!" ,
                            text:res.message,
                            type:'error'
                        },function(){
                            ajaxindicatorstop();
                            jQuery("#loaderorder").removeClass('loading');
                        });
                    }else{
                        var paymentProfileID=res.response;
                        getCustomerProfile(paymentProfileID, CustomerProfileId).success(function(rescard){

                            saveCard(paymentProfileID, CustomerProfileId,billingaddressdb, billingzip, rescard.CardLast4, rescard.Cardtype).success(function(res){
                                if(res.error){
                                     ajaxindicatorstop();
                                }else{
                                     ajaxindicatorstop();
                                    swal({
                                        title:"Success",
                                        type:"success",
                                        text:"Card profile created successfully"
                                    },function(){
                                        jQuery("#loaderorder").removeClass('loading');
                                        jQuery('#creditcard').css("display","none");
                                        jQuery('#creditcard').removeClass("in");
                                        console.log(rescard);
                                        var cardImage = getCardImage(rescard.Cardtype);
                                        var cardhtml ='';
                                        cardhtml += '<div class="visapayment" id="card_id_'+paymentProfileID+'">\
                                <img src="'+cardImage+'" width="30px" height="20px">\
                                <p style="display:inline;">'+rescard.CardLast4+'</p>\
									<div class="checkoutBox-payment">\
									  <input type="checkbox" onclick="toggleCheckbox(this, \''+res.card_id+'\', \''+CustomerProfileId+'\', \''+paymentProfileID+'\')" name="selectCard"><label for="checkbox1"><span></span></label>\
									 <div class="cancle-icon" onclick="removesavecard(\''+CustomerProfileId+'\', \''+paymentProfileID+'\')" > <i class="fa fa-close"> </i>  </div>\
									  </div>\
                            </div>';
                                        jQuery("#cardView").append(cardhtml);
                                    });
                                }

                            });

                        });

                        console.log(res);

                    }

                }


            });
        }
    }

    function saveCard(customerPaymentProfileId,customerProfileId,biladdress_card, bilzipcode_card, cardnumber, cardType){
        var auth = '<?php echo $apikey;?>';

        return jQuery.ajax({
            type: 'POST',
            url: '<?php echo base_url();?>saveCard',
            data: {
                'Authorization':auth,
                'customerPaymentProfileId':customerPaymentProfileId,
                'customerProfileId':customerProfileId,
                'biladdress_card':biladdress_card,
                'bilzipcode_card':bilzipcode_card,
                'cardType':cardType,
                'cardnumber':cardnumber
            }
        });
    }
    function getsavecarddata(){
        var hasClass = jQuery('#Payment').hasClass('in');
        if(!hasClass){
            //jQuery("#placeOrderfinal").prop('disabled', true);
            jQuery('#cardView').html('<div class="deliveryloader"></div>');
            var auth = '<?php echo $apikey;?>';

            jQuery.ajax({
                type: 'GET',
                url: '<?php echo base_url();?>getsavecard',
                data: {
                    'Authorization': auth

                },
                beforeSend: function ()
                {
                    ajaxindicatorstart('Please Wait...');
                },
                success: function (res) {
                    ajaxindicatorstop();
                    console.log(res);
                    if(res.error==true){
                        jQuery('#cardView .deliveryloader').remove();
                    }else{
                        if(res.type == "0"){
                            var user_id=res.user_id;
                            var cardDetail = res.card;
                            var cardhtml = '';
                            for(var i=0; i<cardDetail.length; i++){
                                var resultCard= cardDetail[i];
                                var CustomerProfileId=resultCard.customerProfileId;
                                var paymentProfilesID=resultCard.customerPaymentProfileId;
                                console.log(CustomerProfileId);
                                console.log(paymentProfilesID);
                                var cardChecked = '';
                                var cardBeforeClass = '';
                                if(!(_.isEmpty(chooseCardData))){
                                    if(chooseCardData.paymentprofileid==paymentProfilesID){
                                        cardChecked = 'checked';
                                        cardBeforeClass = '';
                                    }else{
                                        cardChecked = '';
                                        cardBeforeClass = '';
                                    }
                                }

                                cardData.customerProfileId=CustomerProfileId;
                                var cardImage = getCardImage(resultCard.cardtype);
                                jQuery('#cardView').html('');
                                cardhtml += '<div class="visapayment" id="card_id_'+paymentProfilesID+'">\
                                <img src="'+cardImage+'" width="30px" height="20px">\
                                <p style="display: inline;">'+resultCard.card_number+'</p>\
                           <div class="checkoutBox-payment">\
									  <input type="checkbox" '+cardChecked+' onclick="toggleCheckbox(this, \''+resultCard.card_id+'\', \''+CustomerProfileId+'\', \''+paymentProfilesID+'\')" name="selectCard"><label for="checkbox1"><span class="'+cardBeforeClass+'"></span></label>\
									  <div class="cancle-icon" onclick="removesavecard(\''+CustomerProfileId+'\', \''+paymentProfilesID+'\')" > <i class="fa fa-close"> </i>  </div>\
									  </div>\
                            </div>';
                                jQuery("#cardView").append(cardhtml);

                            }
                        }else{
                            jQuery('#cardView .deliveryloader').remove();
                            cardData.customerProfileId= res.customerProfileId;
                        }
                    }

                }
            });
        }

    }

    function getCustomerProfile(paymentProfilesID, CustomerProfileId){
        return jQuery.ajax({
            type: 'POST',
            url: '<?php echo base_url();?>getCustomerPaymentProfile',
            data: {
                'customerProfileId': CustomerProfileId,
                'customerpaymentprofile': paymentProfilesID
            }
        });
    }


    function toggleCheckbox(id, card_id, customerProfileId, paymentprofileid){
        var myCheckbox = document.getElementsByName("selectCard");
        continueTab('payment');
        if(id.checked == false){
            id.checked = true;
            return false;
        }
        Array.prototype.forEach.call(myCheckbox,function(el){
            el.checked = false;
            console.log('ddsd');
            el.nextSibling.childNodes[0].classList.add('no-before');
        });
        id.checked = true;
        id.nextSibling.childNodes[0].classList.remove('no-before');
        cardData.customerProfileId= customerProfileId;
        chooseCardData.paymentprofileid=paymentprofileid;
        chooseCardData.card_id = card_id;
    }

    function want_to_tip(){

        var price = localStorage.getItem("total_am");
        var totaltax = localStorage.getItem("totaltax");
        var firstname = finalOrderPlaceObj.first_name;
        var lastname = finalOrderPlaceObj.last_name;
        var email = finalOrderPlaceObj.email_id;
        var country = finalOrderPlaceObj.country_id;
        var state = finalOrderPlaceObj.state_id;
        var city = finalOrderPlaceObj.city_id;
        var address = finalOrderPlaceObj.address;
        var mobile = finalOrderPlaceObj.mobile;
        var pin_code = finalOrderPlaceObj.pincode;

        if(firstname == '' || lastname == '' || lastname == '' || email == '' || country == '' || state == '' || city == '' || address == '' || mobile == '' || pin_code == ''){
            swal("Error", "Please add complete shipping details" , "error");
            return false;
        }else if(_.isEmpty(timeSlotObject)){
            swal("Error", "Please select delivery time" , "error");
            return false;
        }else if(mobile.length<10 || mobile.length>12){
            swal("Error", "Please enter mobile number min. 10 or max. 12 digit" , "error");
            return false;
        }else if(pin_code.length<5){
            swal("Error", "Please enter mobile number min. 5 digit zipcode" , "error");
            return false;
        }
        else if(_.isEmpty(chooseCardData) || _.isEmpty(cardData)){
            swal("Error", "Please select card for authorize payment" , "error");
            return false;
        }
        /*
        if(jQuery('#tipamount').val() == '' || jQuery('#tipamount').val() == '0'){

            swal({
                    title:"Warning!",
                    text: "Do you wish to enter a tip amount?",
                    type:"warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes",
                    cancelButtonText: "No",
                    closeOnConfirm: false,
                    showLoaderOnConfirm: true
                }, function (isConfirm) {
                    if (isConfirm){
                        swal.close();
                        document.getElementById("tipamount").focus();
                        jQuery('html, body').animate({
                            scrollTop: jQuery("#tipamount").offset().top - 125
                        }, 2000);
                    } else {
                        placeorder();
                    }
                });
        } else { */
            placeorder();
        /* } */
    }

    function placeorder(){

        /*var billingaddresscard =chooseCardData.billingAddress;
         var billingzip =chooseCardData.billingzip;*/
        var price = localStorage.getItem("total_am");
        var totaltax = localStorage.getItem("totaltax");
        var firstname = finalOrderPlaceObj.first_name;
        var lastname = finalOrderPlaceObj.last_name;
        var email = finalOrderPlaceObj.email_id;
        var country = finalOrderPlaceObj.country_id;
        var state = finalOrderPlaceObj.state_id;
        var city = finalOrderPlaceObj.city_id;
        var address = finalOrderPlaceObj.address;
        var mobile = finalOrderPlaceObj.mobile;
        var pin_code = finalOrderPlaceObj.pincode;
        var discount = 0.00;
/*
        if(firstname == '' || lastname == '' || lastname == '' || email == '' || country == '' || state == '' || city == '' || address == '' || mobile == '' || pin_code == ''){
            swal("Error", "Please add complete shipping details" , "error");
            return false;
        }else if(_.isEmpty(timeSlotObject)){
            swal("Error", "Please select delivery time" , "error");
            return false;
        }else if(mobile.length<10 || mobile.length>12){
            swal("Error", "Please enter mobile number min. 10 or max. 12 digit" , "error");
            return false;
        }else if(pin_code.length<5){
            swal("Error", "Please enter mobile number min. 5 digit zipcode" , "error");
            return false;
        }
        else if(_.isEmpty(chooseCardData) || _.isEmpty(cardData)){
            swal("Error", "Please select card for authorize payment" , "error");
            return false;
        }
        */
        var slotid= timeSlotObject.slotid;
        var slotdate = timeSlotObject.date;
        var customerPaymentProfileId = chooseCardData.paymentprofileid;
        var card_id = chooseCardData.card_id;
        var customerProfileId = cardData.customerProfileId;
        var discount_type = localStorage.getItem("discount_type");
        var discount_id = localStorage.getItem("discount_id");
        if(discount_id==''){
            discount_id=0;
        }
        discount = localStorage.getItem("discount_amnt");
        discount = parseFloat(discount).toFixed(2);
        jQuery("#loaderorder").addClass('loading');
        //var authorizeAmount = parseFloat(total_Am0unt)+parseFloat(authorizePercent)*parseFloat(total_Am0unt)/100;
        //authorizeAmount = authorizeAmount.toFixed(2);
        jQuery.ajax({
            type:'POST',
            url:'<?php echo base_url();?>chargeCustomerProfile',
            data:{
                'amount':authorizeAmount,
                'profileid':customerProfileId,
                'paymentprofileid':customerPaymentProfileId
            },
            beforeSend: function ()
            {
                ajaxindicatorstart('Please Wait...');
            },
            success:function(res){ 
                ajaxindicatorstop();
                if(res.error==true){
                    swal({
                        title: "Sorry! Please Try Again",
                        text: res.message,
                        type: "error",
                        showCancelButton: true
                    },
                    function(){
                        jQuery("#loaderorder").removeClass('loading');
                    });
                }else{
                    console.log(res);
                    var pay_email = '';
                    var pay_phone = '';
                    var payerID = '';

                    var cardnumber='NA';
                    var expirydate='NA';
                    var paymentby='1';
                    var payerID = res.AUTHCODE;
                    var paymentID =res.TRANSID;
                    var processing_fee = '<?php echo $this->config->item('processing_fee');?>';
                    var deleviry_charge = localStorage.getItem("delivery_charge");
                    var deleviry_chargeold = '<?php echo $this->config->item('deleviry_chargeold');?>';

                    var auth = '<?php echo $apikey;?>';

                    // var tip_amount = jQuery('#tipamount').val();
                    var tip_amount = jQuery('#tipamount').html();
                    if (tip_amount == '') {
                        var tip_amount1 = 0.00;
                    } else {
                        var tip_amount1 = tip_amount;

                    }
                    if (pay_email == '') {
                        var pay_email1 = "NA";
                    } else {
                        var pay_email1 = pay_email;
                    }
                    if (pay_phone == '') {
                        var pay_phone1 = "NA";
                    } else {
                        var pay_phone1 = pay_phone;
                    }
                    if (payerID == '') {
                        payerID = "NA";
                    } else {
                        payerID = payerID;
                    }
                    if (customerPaymentProfileId == '') {
                        var payerID1 = "NA";
                    } else {
                        var payerID1 = payerID;
                    }
                    if(customerPaymentProfileId==''){
                        var customerPaymentProfileId1='NA';
                    }else{
                        var customerPaymentProfileId1=customerPaymentProfileId;
                    }
                    if(customerProfileId==''){
                        var customerProfileId1='NA';
                    }else{
                        var customerProfileId1=customerProfileId;
                    }

                    jQuery.ajax({
                        type: 'POST',
                        url: '<?php echo base_url();?>placeorder',
                        data: {
                            'Authorization': auth,
                            'bil_firstname': firstname,
                            'bil_lastname': lastname,
                            'bil_contact': mobile,
                            'bil_email': email,
                            'bil_address': address,
                            'bil_countryid': country,
                            'bil_stateid': state,
                            'bil_cityid': city,
                            'bil_pincode': pin_code,
                            'ord_txnid':  paymentID,
                            'ord_totalprice': price,
                            'ord_finlprice': parseFloat(total_Am0unt).toFixed(2),
                            'ord_tax': parseFloat(totaltax).toFixed(2),
                            'ord_processingfee': parseFloat(processing_fee).toFixed(2),
                            'payerID':payerID,
                            'pay_email': pay_email1,
                            'pay_phone': pay_phone1,
                            'ord_deliverycharge': parseFloat(deleviry_charge).toFixed(2),
                            'ord_coupanid': discount_id,
                            'tip_amount': parseFloat(tip_amount1).toFixed(2),
                            'slotid':slotid,
                            'develydate':slotdate,
                            'AuthoAmount':parseFloat(authorizeAmount).toFixed(2),
                            'card_id':card_id,
                            'discount_type':discount_type,
                            'discount_amount':discount
                        },
                        beforeSend: function ()
                        {
                            ajaxindicatorstart('Please Wait...');
                        },
                        success: function(res) {
                            ajaxindicatorstop();
                            console.log(res);
                            if (res.error ==true) {
                                jQuery("#loaderorder").removeClass('loading');
                                swal({
                                        title: "Sorry! Please Try Again",
                                        text: "Continue Shopping",
                                        type: "error",
                                        showCancelButton: true
                                    },
                                    function(){
                                       window.location.href = '<?php echo base_url();?>';
                                });

                            } else {
                                swal({
                                        title: "Success",
                                        text: "Order Placed Successfully",
                                        type: "success"
                                    },
                                function(){
                                    localStorage.clear();
                                    window.location.href = '<?php echo base_url();?>success';
                                });
                            }
                        }

                    });
                }
            }
        });
    }
    /*
    google.maps.event.addDomListener(window, 'load', function () {
        var input = document.getElementById("txtPlaces");
        var places = new google.maps.places.Autocomplete(input);

        google.maps.event.addListener(places, 'place_changed', function () {
            var place = places.getPlace();
            var address = place.formatted_address;
            console.log(place);
            console.log(place.address_components[6]['long_name']);
            var latitude = place.geometry.location.lat();
            var longitude = place.geometry.location.lng();
            var a = place.address_components;
            var city=''
            var state='';
            var postal_cc='';
            var country='';
            for(i = 0; i <  a.length; ++i)
            {
                var t = a[i].types;
                if(compIsType(t, 'administrative_area_level_1'))
                    state = a[i].long_name; //store the state
                else if(compIsType(t, 'locality'))
                    city = a[i].long_name; //store the city
                else if(compIsType(t, 'postal_code'))
                    postal_cc = a[i].long_name; //store the city
                else if(compIsType(t, 'country'))
                    country = a[i].long_name; //store the city
            }
            billingCity =city;
            billingState = state;
            billingCountry = country;
            var mesg = "Address: " + address;
            mesg += "\nLatitude: " + latitude;
            mesg += "\nLongitude: " + longitude;
            var geocoder = new google.maps.Geocoder();
            jQuery('.addressvali').text(address);
            jQuery('#zipcode').val(postal_cc);
        });
    });
    */
    function compIsType(t, s) {
        for(z = 0; z < t.length; ++z)
            if(t[z] == s)
                return true;
        return false;
    }

    function removesavecard(custid, custpaymentid){
        var auth = '<?php echo $apikey;?>';
        console.log(auth);
        swal({
                title:"Warning!",
                text: "Are you sure you want to delete this card?",
                type:"warning",
                showCancelButton: true,
                confirmButtonText: "Yes",
                closeOnConfirm: false,
                showLoaderOnConfirm: true
            },
            function () {
                jQuery.ajax({
                    type: 'POST',
                    url: '<?php echo base_url();?>removeCard',
                    data: {'Authorization': auth, 'profileid': custid, 'paymentprofileid': custpaymentid},
                    success: function (res) {
                        console.log(res);
                        if (res.error == false) {
                            swal({
                                title: 'Success',
                                text: "Card deleted successfully.",
                                type: 'success'
                            }, function () {
                                jQuery('#card_id_'+custpaymentid).remove();
                                if("paymentprofileid" in chooseCardData && chooseCardData.paymentprofileid === custpaymentid){
                                    chooseCardData = {};
                                }
                            });
                        } else {
                            swal(res.message);
                        }
                    }


                });
            });
    }

    function initAutocomplete() {

        var acInputs = document.getElementsByClassName("map_api_autocomplete");

        for (var i = 0; i < acInputs.length; i++) {

            var autocomplete = new google.maps.places.Autocomplete(acInputs[i]);
            autocomplete.inputId = acInputs[i].id;

            google.maps.event.addListener(autocomplete, 'place_changed', function () {
                // document.getElementById("log").innerHTML = 'You used input with id ' + this.inputId;
                if(this.inputId == 'address1') fillInAddress(autocomplete);
                if(this.inputId == 'txtPlaces') add_new_card_billing_info(autocomplete);
            });
        }




//        // Create the autocomplete object, restricting the search to geographical
//        // location types.
//        autocomplete = new google.maps.places.Autocomplete(
//            /** @type {!HTMLInputElement} */(document.getElementById('address1')),
//            {types: ['geocode']});
//
//        // When the user selects an address from the dropdown, populate the address
//        // fields in the form.
//        autocomplete.addListener('place_changed', fillInAddress(autocomplete));
//
//        autocomplete1 = new google.maps.places.Autocomplete(
//            /** @type {!HTMLInputElement} */(document.getElementById('txtPlaces')),
//            {types: ['geocode']});
//
//        // When the user selects an address from the dropdown, populate the address
//        // fields in the form.
//        autocomplete1.addListener('place_changed', add_new_card_billing_info(autocomplete1));
      }

    function fillInAddress(autocomplete) {
        // Get the place details from the autocomplete object.
        var place = autocomplete.getPlace();
        document.getElementById('latitude').value=place.geometry.location.lat();
        document.getElementById('longitude').value= place.geometry.location.lng();
      }

    function add_new_card_billing_info(autocomplete1){
        var place = autocomplete1.getPlace();
        var address = place.formatted_address;
        console.log(place);
        console.log(place.address_components[6]['long_name']);
        var latitude = place.geometry.location.lat();
        var longitude = place.geometry.location.lng();
        var a = place.address_components;
        var city=''
        var state='';
        var postal_cc='';
        var country='';
        for(i = 0; i <  a.length; ++i)
        {
            var t = a[i].types;
            if(compIsType(t, 'administrative_area_level_1'))
                state = a[i].long_name; //store the state
            else if(compIsType(t, 'locality'))
                city = a[i].long_name; //store the city
            else if(compIsType(t, 'postal_code'))
                postal_cc = a[i].long_name; //store the city
            else if(compIsType(t, 'country'))
                country = a[i].long_name; //store the city
        }
        billingCity =city;
        billingState = state;
        billingCountry = country;
        var mesg = "Address: " + address;
        mesg += "\nLatitude: " + latitude;
        mesg += "\nLongitude: " + longitude;
        var geocoder = new google.maps.Geocoder();
        jQuery('.addressvali').text(address);
        jQuery('#zipcode').val(postal_cc);
    }

    /*
    function set_tip(tips_arr){
        var tips = tips_arr.tips;
        for(var i=0; i<tips.length; i++){
            var price = localStorage.getItem("total_am");
            var tip_amount = (price * tips[i] * 0.01).toFixed(2);
            console.log('Tip % - '+tips[i]+' Amount - '+tip_amount);

        }
        // console.log(tips_arr.default);
    }
    */

</script>
</body>
</html>
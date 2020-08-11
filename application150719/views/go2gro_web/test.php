<?php include 'header.php';
$paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
//$paypal_url = 'https://www.paypal.com/cgi-bin/webscr';
// define( 'SSL_URL', 'https://www.paypal.com/cgi-bin/webscr' );

$paypal_id = 'yashparikh-facilitator@go2gro.com';
$business = $paypal_id;
$userId = $user['user']->user->id;
$first_name = $user['user']->user->first_name;
$last_name = $user['user']->user->last_name;
$email_id = $user['user']->user->email_id;
$mobile = $user['user']->user->mobile;
$address= $user['user']->user->address;
$pincode = $user['user']->user->pincode;
$api_key = $user['user']->user->api_key;
$password = $user['user']->user->password;
$country_id = $user['user']->user->country_id;
$state_id = $user['user']->user->state_id;
$city_id = $user['user']->user->city_id;
$county_name = $user['user']->user->county_name;
$state_name = $user['user']->user->state_name;
$city_name = $user['user']->user->city_name;
$rad=rand();
$key=md5($rad);
//$key=bin2hex(mcrypt_create_iv(10, MCRYPT_DEV_URANDOM));
$cart = $this->session->get_userdata("cart");
$cartloop=$cart['cart']->item;
$taxresultadd=0;
$tax1=0.00;
$total_Amount=0.00;
foreach ($cartloop as $row) {

    $cartId = $row->item_id;
    $tax1 += $row->Sales_tax;

    $total_Amount += $row->total;



//print_r($cartloop);
}
$taxresult = ($total_Amount * $tax1) / 100;

$taxresultadd += $taxresult + $total_Amount;
$taxresultadd= round($taxresultadd, 2);

$cancel_return = '<?php echo base_url();?>cancel';
$return = '<?php echo base_url();?>success';
//$aimp=implode(',',$arr);
?>
<style>
    .default-add{
        display: inline-block;
        position: relative;
        top: -2px;
    }
    .my-error-class {
        color:red;
    }
    .my-valid-class {
        color:green;
    }
</style>
<div class="page-heading">
</div>

<!-- BEGIN Main Container -->

<div class="main-container col1-layout wow bounceInUp animated">

    <div class="main">
        <div class="cart wow bounceInUp animated">

            <!-- BEGIN CART COLLATERALS -->
            <form action="<?php echo $paypal_url; ?>" name="paypalform" method="post">
                <input type="hidden" name="business" value="<?php echo $paypal_id; ?>">
                <input type="hidden" name="cmd" value="_xclick">
                <input type="hidden" name="item_name" value="apple">
                <input type="hidden" name="item_number" value="1">
                <input type="hidden" name="amount" value="<?php echo $taxresultadd+4.99; ?>">
                <input type="hidden" name="currency_code" value="USD">
                <input type='hidden' name='cancel_return' value='<?php echo base_url();?>cancel'>
                <input type='hidden' name='return' value='<?php echo base_url();?>success'>
            </form>


            <form class="cart-collaterals container" id="checkout_form" method="post" action="#">
                <!-- BEGIN COL2 SEL COL 1 -->



                <!--                <input type="image" src="https://www.paypal.com/en_AU/i/btn/btn_buynow_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online.">-->
                <!--                <img alt="" border="0" src="https://www.paypal.com/en_AU/i/scr/pixel.gif" width="1" height="1">-->
                <!-- BEGIN TOTALS COL 2 -->

                <div class="col-sm-8">
                    <div class="totals">
                        <h3>BILLING DETAILS</h3>
                        <div class="inner">

                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label>First Name*</label>
                                    <input type="text" name="firstname" id="fname" class="form-control form_control_01" value="<?php echo $first_name;?>">
                                </div>
                                <div class="form-group col-sm-6">
                                    <label>Last Name*</label>
                                    <input type="text" name="lastname" id="lname" class="form-control form_control_01" value="<?php echo $last_name;?>">
                                </div>
                                <div class="form-group col-sm-6">
                                    <label>Contact Number*</label>
                                    <input type="text" name="mobile" id="mobile" class="form-control form_control_01" value="<?php echo $mobile;?>">
                                </div>
                                <div class="form-group col-sm-6">
                                    <label>Email*</label>
                                    <input type="email" name="email" id="emailid" class="form-control form_control_01" value="<?php echo $email_id ;?>" disabled>
                                </div>
                            </div>
                            <!-- end row -->

                            <div class="hero clearfix">
                                <div class="form-group">
                                    <p>Same Delivery is Only Available in Newyork City Only, However we Delivered all the Products Worldwide as soon as Possible with High Quality Team. </p>
                                </div>
                            </div>
                            <!-- end row -->

                            <div class="row">
                                <div class="form-group col-sm-12">
                                    <label>Address*</label>
                                    <input type="text" name="address" id="address1" class="form-control form_control_01" value="<?php echo $address;?>">
                                </div>
                                <div class="form-group col-sm-12">
                                    <input type="text" class="form-control form_control_01" placeholder="">
                                </div>
                                <div class="form-group col-sm-6">
                                    <label>Country</label>

                                    <select onchange="get_state(this.value);"  name="country"
                                            class="form-control form_control_01" style="padding: 0px 5px !important;" id="countrygetid">

                                        <!--                                            <option> d</option>-->
                                        <!--                                            <option> d</option>-->
                                    </select>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label>State*</label>
                                    <select onchange="get_city(this.value);" name="state" class="form-control form_control_01" style="padding: 0px 5px !important;" id="stategetid">
                                        <!--                                            <option> d</option>-->
                                        <!--                                            <option> d</option>-->
                                        <!--                                            <option> d</option>-->

                                    </select>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label>Town / City*</label>
                                    <select  class="form-control form_control_01" name="city" style="padding: 0px 5px !important;" id="citygetid">
                                        <!--                                            <option> d</option>-->
                                        <!--                                            <option> d</option>-->
                                        <!--                                            <option> d</option>-->
                                    </select>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label>Pin code*</label>
                                    <input type="text" class="form-control form_control_01" name="pincode" id="pin_code" value="<?php echo $pin_code;?>">
                                </div>
                                <div class="form-group col-sm-12">
                                    <div>
                                        <label>
                                            <!--                                                Make your default delivery address-->
                                            <input type="hidden" name="agree"> <span class="default-add"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <!-- end row -->


                        </div><!--inner-->
                    </div><!--totals-->
                </div> <!--col-sm-4-->

                <div class="col-sm-4">
                    <div class="totals">
                        <h3>Shopping Cart Total</h3>
                        <div class="inner">

                            <table id="shopping-cart-totals-table" class="table shopping-cart-table-total">
                                <colgroup><col>
                                    <col width="1">
                                </colgroup><tfoot>

                                <tr>
                                    <td style="" class="a-left" colspan="1">
                                        <strong>Grand Total</strong>
                                    </td>
                                    <td style="" class="a-right">
                                        <strong><span class="price" id="finalcartprice"><?php echo $taxresultadd+4.99;?></span></strong>
                                    </td>
                                </tr>

                                </tfoot>
                                <tbody>

                                <tr>
                                    <td style="" class="a-left" colspan="1">
                                        Subtotal    </td>
                                    <td style="" class="a-right">
                                        <span class="price" id="cartpriceval"></span>  </td>
                                </tr>

                                <tr>
                                    <td style="" class="a-left" colspan="1">
                                        tax%    </td>
                                    <td style="" class="a-right">
                                        <span class="text" id="taxval" ><?php echo $tax1;?></span>    </td>
                                </tr>
                                <tr>
                                    <td style="" class="a-left" colspan="1">
                                        Delivery Charges    </td>
                                    <td style="" class="a-right">
                                        <span class="price1" id="coupencode1">$4.99</span>    </td>
                                </tr>

                                </tbody>
                            </table>

                            <ul class="checkout">
                                <li>

                                    <button type="submit" title="Proceed to Checkout" name="checkoutformdata1" id="checkoutformdata" class="button btn-proceed-checkout" ><span>Place Order</span></button>
                                </li><br>
                            </ul>
                        </div><!--inner-->
                    </div><!--totals-->
                </div> <!--col-sm-4-->

            </form>
        </div> <!--cart-collaterals-->



    </div>  <!--cart-->

</div><!--main-container-->

</div> <!--col1-layout-->

<div id="paypal-button-container"></div>

<script>
    paypal.Button.render({

        env: 'sandbox', // sandbox | production

        // PayPal Client IDs - replace with your own
        // Create a PayPal app: https://developer.paypal.com/developer/applications/create
        client: {
            sandbox:    'AZDxjDScFpQtjWTOUtWKbyN_bDt4OgqaF4eYXlewfBP4-8aqX3PiV8e1GWU6liB2CUXlkA59kJXE7M6R',
            production: '<insert production client id>'
        },

        // Show the buyer a 'Pay Now' button in the checkout flow
        commit: true,

        // payment() is called when the button is clicked
        payment: function(data, actions) {

            // Make a call to the REST api to create the payment
            return actions.payment.create({
                payment: {
                    transactions: [
                        {
                            amount: { total: '0.01', currency: 'USD' }
                        }
                    ]
                }
            });
        },

        // onAuthorize() is called when the buyer approves the payment
        onAuthorize: function(data, actions) {

            // Make a call to the REST api to execute the payment
            return actions.payment.execute().then(function() {
                window.alert('Payment Complete!');
            });
        }

    }, '#paypal-button-container');

</script>

<?php include 'footer.php' ?>
<script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>
<script>

    get_country();
    get_state(<?php echo $country_id;?>);
    get_city(<?php echo $state_id;?>);
    mycartdata();
    function mycartdata() {
        var api_key = '<?php echo $apikey;?>';
        jQuery.ajax({
            type: 'GET',
            url: '<?php echo base_url("getCartItem"); ?>',
            data: {
                'Authorization': api_key
            },
            success: function (res) {
                console.log(res);
                if(res.error==false){
                    var catList = res.item;

                    var jsonLength = catList.length;

                    console.log(jsonLength);
                    var html1 = '';
                    var $total=0;
                    var count = 1;
                    for (var i = 0; i < jsonLength; i++) {

                        count = count + 1;
                        var result = catList[i];
                        $total+=result.total;

//                        alert(finalprice);
                        html1 += '<tr class="odd"> <td class="image hidden-table">\
                        <a href="product-detail.html" title="Women&#39;s Georgette Animal Print" class="product-image">\
                        <img src="<?php echo $this->config->item('api_img_url');?>' + result.item_image + '" width="75" alt="Women Georgette Animal Print"></a></td><td>\
                        <h2 class="product-name"><a href="product-detail.html">' + result.item_name + '</a></h2></td>\
                    <td class="a-center hidden-table"><a href="#" onclick="edit_cart(' + result.item_id + ')" class="edit-bnt" title="Edit item parameters"></a></td>\
                     <td class="a-right hidden-table">\
                        <span class="cart-price">\
                        <span class="price">' + result.item_price + '</span>\
                    </span>\
                    </td>\
                    <td class="a-center movewishlist">\
                        <input name="cart[26340][qty]" value="' + result.item_quty + '" size="4" title="Qty" id=qty name="qty" class="input-text qty" maxlength="12">\
                        </td>\
                        <td class="a-right movewishlist">\
                        <span class="cart-price">\
                    <span class="price">' + result.total + '</span>\
                    </span>\
                    </td>\
                    <td class="a-center last"><a href="#" title="Remove item" onclick="delete_cart(' + result.item_id + ')" class="button remove-item"><span><span>Remove item</span></span></a></td></tr>';
                    }
                    jQuery('#cartpriceval').text(""+$total);
                    // alert(taxcalculate());
                    //alert($total);

//                jQuery('#finalcartprice').text("$"+taxcalculate());
                }
                else{
                    html1 +='<tr class="odd"><td style="width:100%;"><h2 class="product-name">' + res.message + '</h2></td><tr>';

                }
                // jQuery('.cartshow').append(html1);

            }

        });
    }

    function get_country(){
        var  api_key = '<?php echo $api_key;?>';

        jQuery.ajax({
            type:'GET',
            url:'<?php echo base_url();?>getCountry',
            data:{'Authorization': api_key},
            success:function(res){
                if(res.error=true)
                {

                }
                console.log(res);
                var countryval=res.country;
                var countrylength=res.country.length;
                html+='<option id="did"  value="">please Select Country</option>';
                for(var i=0; i<countrylength; i++)
                {
                    var result=countryval[i];
                    var html='';
                    //jQuery('#countrygetid').attr(result.name);

                    html+='<option id="did"  value="'+result.id+'">'+result.name+'</option>';
                    jQuery('#countrygetid').append(html);
                }


            }
        });
    }

    function get_state(id){

        var  api_key = '<?php echo $api_key;?>';
        jQuery.ajax({
            type:'GET',
            url:'<?php echo base_url();?>getState',
            data:{'Authorization': api_key, 'cid':id},
            success:function(res){
                console.log(res);
                var stateval=res.state;
                var statelength=res.state.length;
                console.log(statelength);
                html+='<option id="did"  value="">please Select state</option>';
                for(var i=0; i<statelength; i++)
                {
                    var result=stateval[i];
                    var html='';
//                    jQuery('#countrygetid').text(result.name);

                    html+='<option  value="'+result.id+'">'+result.name+'</option>';
                    jQuery('#stategetid').append(html);
                }


            }
        });
    }
    function get_city(id){
        var  api_key = '<?php echo $api_key;?>';
        jQuery.ajax({
            type:'GET',
            url:'<?php echo base_url();?>getCity',
            data:{'Authorization': api_key, 'city_id':id},
            success:function(res){
                console.log(res);
                var countryval=res.city;
                var countrylength=res.city.length;
                html+='<option id="did"  value="">please Select City</option>';
                for(var i=0; i<countrylength; i++)
                {
                    var result=countryval[i];
                    var html='';
//                    jQuery('#countrygetid').text(result.name);

                    html+='<option  value="'+result.id+'">'+result.name+'</option>';
                    jQuery('#citygetid').append(html);
                }


            }
        });
    }



    jQuery(document).ready(function(){

        jQuery(function() {

            // Setup form validation on the #register-form element
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
                        minlength: 10
                    },
                    address: "required",
                    country:"required",
                    state:"required",
                    city:"required",
                    pincode:{
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
                        minlength: "Your  mobile number must be 11 digits"
                    },
                    email: "Please enter a valid email address",
                    address:"Please enter your address",
                    country:"Please enter your country",
                    state:"Please enter your state",
                    city:"Please enter your city",
                    pincode:{
                        required: "Please provide a pincode",
                        minlength: "Your password must be 5 characters long"
                    }
                },

                submitHandler: function(form) {
                    // form.submit();


                    var auth = '<?php echo $api_key;?>';
                    var firstname = jQuery('#fname').val();
                    var lastname = jQuery('#lname').val();
                    var email = jQuery('#emailid').val();
                    var country = jQuery('#countrygetid').val();
                    var state = jQuery('#stategetid').val();
                    var city = jQuery('#citygetid').val();
                    var address = jQuery('#address1').val();
                    var mobile = jQuery('#mobile').val();
                    var pin_code = jQuery('#pin_code').val();
                    var finalcartprice1 = jQuery('#finalcartprice').text();
                    var cartpriceval = jQuery('#cartpriceval').text();
                    var taxval = jQuery('#taxval').text();
                    var coupencode = jQuery('#coupencode').text();
                    var ord_txnid = '<?php echo $key;?>';

                    var finalcartprice2= finalcartprice1+taxcalculate();
                    var finalcartprice21= parseFloat(finalcartprice2+4.99);
                    // alert(finalcartprice2);

//         var ord_txnid = $.md5(strVal);

//console.log("fname"+firstname);
//            console.log("lastname"+lastname);
//            console.log("email"+email);
//            console.log("country"+country);
//            console.log("state"+state);
//            console.log("city"+city);console.log("address"+address);
//            console.log("mobile"+mobile);
//            console.log("pin_code"+pin_code);
//            console.log("finalcartprice"+finalcartprice);
//            console.log("cartpriceval"+cartpriceval);
//            console.log( "taxval"+taxval);
//            console.log("coupencode"+coupencode);
//            console.log("ord_txnid"+ord_txnid);



                    jQuery.ajax({
                        type: 'POST',
                        url: '<?php echo base_url();?>storecheckoutdata',
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
                            'ord_txnid': ord_txnid,
                            'ord_totalprice': cartpriceval,
                            'ord_finlprice': finalcartprice21,
                            'ord_tax':taxval,
                            'ord_coupanid':'NA'
                        },
                        success: function(res) {
//                        Jquery('#checkoutformdata').click(function(e){
//                            e.preventDefault();

                            console.log(res);
                            submitPayuForm();
//                            swal({
//                                    title: "Thank You We Have Recevied Your Order Successfully.",
//                                    text: "Continue Shopping",
//                                    type: "success",
//                                    showCancelButton: true
//                                },
//                                function(){
//                                    submitPayuForm();
//                                });
//                        });
                            //   swal("Good job!", res.message, "success");
//                       window.location.href="<?php //echo base_url();?>//";
                        }
                    });
                    return false;


                }
            });

        });


//        jQuery('#checkoutformdata').click(function() {
//
//
//
//
//
//        });



    });

    function taxcalculate()
    { var cartpriceval = jQuery('#cartpriceval').text();
        var resstr = cartpriceval.replace("$", " ");
        var taxval = jQuery('#taxval').text();
        var tax=(resstr*taxval)/100;
        var finalprice= parseFloat(resstr) + parseFloat(taxval);
        var resj=finalprice.toFixed(2);
        //alert(resj);
        //  sum += Number($(this).val());

        //alert(finalprice);
        //jQuery('#taxval').text();
        return finalprice;

    }



</script>
<script type="text/javascript">
    function submitPayuForm() {
        var paypalform = document.forms.paypalform;
        paypalform.submit();
    }

</script>
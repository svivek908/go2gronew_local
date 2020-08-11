<?php include 'header.php';
load_css(array('public/assets/stylesheet/pgstyle/profile.css?'));
?>
<div id="loaderorder" class="loading"></div>
<div class="page-heading">
</div>
<!-- BEGIN Main Container col2-right -->
<div class="main-container col2-right-layout">
    <div class="main container">
        <div class="row">
            <section class="col-main col-sm-12 wow bounceInUp animated animated cart-collaterals">
                <div id="messages_product_view"></div>
                <div class="totals">
                    <h3>PROFILE</h3>
                    <div class="inner clearfix">
                        <form id="updateprofile" method="post" action="javascript:void(0);">
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label>First Name<em class="required">*</em></label>
                                    <input type="text" name="firstname" id="first_name" class="form-control form_control_01" value="<?php echo $userresult['first_name'];?>">
                                </div>
                                <div class="form-group col-sm-6">
                                    <label>Last Name<em class="required">*</em></label>
                                    <input type="text" name="lastname" id="last_name" class="form-control form_control_01" value="<?php echo $userresult['last_name'];?>">
                                </div>
                                <div class="form-group col-sm-6">
                                    <label>Contact Number<em class="required">*</em></label>
                                    <input type="text" name="mobile" id="mobileid" minlength="10" maxlength="12" class="form-control form_control_01" value="<?php echo $userresult['mobile'] ;?>">
                                </div>
                                <div class="form-group col-sm-6">
                                    <label>Email<em class="required">*</em></label>
                                    <input type="email" disabled name="email" class="form-control form_control_01" id="emailid" value="<?php echo $userresult['email_id'];?>">
                                </div>

                                <div class="form-group col-sm-12">
                                    <label>Street Address<em class="required">*</em></label>
                                    <input type="text" name="address" class="form-control form_control_01 map_api_autocomplete" id="addressid" value="<?=$userresult['street_address']?>">
                                    <input type="hidden" name="" id="latitude" value="<?=$userresult['latitude']?>">
                                    <input type="hidden" name="" id="longitude" value="<?=$userresult['longitude']?>">
                                </div>
                                <div class="form-group col-sm-12">
                                    <label>Unit</label>
                                    <input type="text" name="" class="form-control form_control_01" id="apt_no" value="<?=$userresult['apt_no']?>">
                                </div>
                                <div class="form-group col-sm-12">
                                    <label>Residential community</label>
                                    <input type="text" name="" class="form-control form_control_01" id="complex_name" value="<?=$userresult['complex_name']?>">
                                </div>
                                 <div class="form-group col-sm-6">
                                    <label>State*</label>
                                    <input type="hidden" name="country" id="countryid" value="<?php echo $userresult['country_id'];?>">
                                    <select class="form-control form_control_01" name="state" id="stateid">
                                        <option value="<?php echo $userresult['state_id'];?>"><?php echo $userresult['state_name'];?></option>

                                    </select>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label>City*</label>
                                    <select  class="form-control form_control_01" name="city" id="cityid">
                                        <option value="<?php echo $userresult ['city_id'];?>"><?php echo $userresult['city_name'];?></option>
                                    </select>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label>Zip code*</label>
                                    <input type="text" name="pincode" id="pin_code" onkeypress="pincodecheck11();"  class="form-control form_control_01" value="<?php echo $userresult['pincode'];?>">
                                </div>
                                <div class="buttons-set  col-sm-12">
                                    <button type="submit" title="Submit" class="button submit pull-right"><span><span>Submit</span></span>
                                    </button>
                                </div>
                            </div>
                            <!-- end row -->
                        </form>

                    </div>
                    <!--inner-->
                </div>
                <div class="totals profile-totalmr">
                    <div>
                        <h3>Credit Card Detail  <button type="button" class="pull-right btn btn-primary creditbtn profile-addcartbtn" data-toggle="modal" data-target="#creditcard" onclick="resetFormProfile();">  Add Card</button></h3>
                    </div>

                    <div class="inner clearfix" id="cardViewProfile">

                    </div>
                    <!--inner-->
                </div>
                <div class="totals">
                    <h3>Change Password</h3>
                    <div class="inner clearfix">
                <form id="ChangePassword" method="post" action="javascript:void(0);">
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label>Old Password<em class="required">*</em></label>
                            <input type="password" id="pwd1" name="oldpassword" class="form-control form_control_01" value="">
                        </div>
                        <div class="form-group col-sm-6">
                            <label>New Password<em class="required">*</em></label>
                            <input type="password" id="pwd2" name="newpassword" class="form-control form_control_01" value="">
                        </div>
                        <div class="form-group col-sm-6">
                            <label>Confirm Password<em class="required">*</em></label>
                            <input type="password" id="pwd3" name="confirmpassword" class="form-control form_control_01" value="">
                        </div>

                        <div class="buttons-set  col-sm-12">
                            <button type="submit" title="Submit" class="button submit pull-right"><span><span>Submit</span></span>
                            </button>
                        </div>
                    </div>
                    <!-- end row -->
                </form>
                    </div>
                    <!--inner-->
                </div>
                <!--totals-->
            </section>
            <!--col-right sidebar-->
        </div>
        <!--row-->
    </div>
    <!--main-container-inner-->
</div>
<!--main-container col2-left-layout-->

<div class="modal fade profile-fade" id="creditcard" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content profile-addtop">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3 class="modal-title text-center">Add Card</h3>
            </div>
            <form action="javascript:void(0);" method="post" id="authrize_formProfile" class="creditly-card-form">
                <div class="modal-body clearfix checkout-card">



                    <div class="col-md-12">
                        <div class="marT15 clearfix">
                            <section class="auth-card">
                                <div class="credit-card-wrapper">
                                    <div class="first-row form-group clearfix">
                                        <div class="col-sm-12 controls">
                                            <label class="control-label">Card Number*</label>
                                            <input class="credit-card-number form-control"
                                                   type="text" name="card_number" id="authorizenet-card-number"
                                                   inputmode="numeric" autocomplete="cc-number" autocompletetype="cc-number" x-autocompletetype="cc-number"
                                                   placeholder="&#149;&#149;&#149;&#149; &#149;&#149;&#149;&#149; &#149;&#149;&#149;&#149; &#149;&#149;&#149;&#149;">
                                        </div>

                                    </div>

                                     <div class="form-group marT15 clearfix">
                                    <div class="second-row form-group">
                                        <!--<div class="col-sm-8 controls">
                                            <label class="control-label">Name on Card</label>
                                            <input class="billing-address-name form-control"
                                                   type="text" name="name"
                                                   placeholder="">
                                        </div>-->
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

                                </div>
                            </section>

                        </div>
                        <div class="form-group marT15 clearfix">
                            <div class="col-sm-12 controls">
                                <label class="control-label">Billing Address*</label>
                                <input type="text" name="billingAddress" class="form-control map_api_autocomplete" id="txtPlaces"  placeholder="Enter a location" />
                            </div>
                        </div>

                        <div class="form-group marT15 clearfix">
                            <div class="col-sm-12 controls">
                                <label class="control-label">Zip Code*</label>
                                <input type="text" name="zipcode" id="zipcode" placeholder="Zip Code" class="form-control zipcode-container profile-zipcode">
                                <p class="marT15 d-inline" id="addressvali"></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id='save-card' class="btn btn-default savechanges-btn" >Save</button>
                    <button type="button" class="btn btn-default savechanges-btn cancel-color" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>

    </div>
</div>

<?php include 'footer.php' ?>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB-wq5TQX9liVeO4LoNFs8tF48H0PqKy2o&libraries=places&callback=initAutocomplete" async defer></script>
<script>

   var cardData ={};
   var billingCity ='';
   var billingState = '';
   var billingCountry = '';

    function validateEmail(x)
    {
        var emailID = x;
        atpos = emailID.indexOf("@");
        dotpos = emailID.lastIndexOf(".");

        if (atpos < 1 || ( dotpos - atpos < 2 ))
        {
            return false;
        }
        return  true;
    }
    function Validatenumber(inputtxt)
    {
        if(isNaN(inputtxt)||inputtxt.indexOf(" ")!=-1)
        {
            swal("Enter numeric value");
            //return false;
        }
    }
   function resetFormProfile(){
   
       jQuery('#authrize_formProfile')[0].reset();
       jQuery('#addressvali').text('');
   }

    function getsavecarddata(){
            jQuery("#loaderorder").removeClass('loading');
            jQuery('#cardViewProfile').html('<div class="deliveryloader"></div>');
            var auth = '<?php echo $apikey;?>';

            jQuery.ajax({
                type: 'GET',
                url: '<?php echo base_url();?>getsavecard',
                data: {
                    'Authorization': auth

                },
                success: function (res) {
                    console.log(res);
                    if(res.error==true){
                        jQuery('#cardViewProfile .deliveryloader').remove();
                    }else{
                        //type==1 when custmor profile already exist and type==0 not exist
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

                                cardData.customerProfileId=CustomerProfileId;
                                var cardImage = getCardImage(resultCard.cardtype);
                                jQuery('#cardViewProfile').html('');

                                cardhtml += '<div class="col-xs-12 " id="card_id_'+paymentProfilesID+'"><div class="visapayment" >\
                                    <div class="col-md-6 col-xs-9">\<img src="'+cardImage+'" width="30px" height="20px"><p>'+resultCard.card_number+'</p>\ </div>\
									<div class="col-md-6 col-xs-3">\
                                    <div class="checkoutBox-payment">\
                                    <div class="cancle-icon" onclick="removesavecard(\''+CustomerProfileId+'\', \''+paymentProfilesID+'\')" > <i class="fa fa-close"> </i>\
                                    </div>\
                                    </div>\
                                    </div>\
									</div>\
                                    </div>';
                                jQuery("#cardViewProfile").append(cardhtml);

                            }
                        }else{
                            jQuery('#cardViewProfile .deliveryloader').remove();
                            cardData.customerProfileId= res.customerProfileId;
                        }
                    }

                }
            });

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
                           });
                       } else {
                           swal(res.message);
                       }
                   }


               });
           });
   }

   function saveCardDataProfile(card_number, card_cvv){
       jQuery("#loaderorder").addClass('loading');
       var card_expiration =jQuery('#authorizenet-card-expiry').val();
       var card = card_expiration.split('/');
       var cardmonth = card[0].trim()+'/'+card[1].trim();
       console.log(card_expiration);
       var firstname = '<?php echo $userresult['first_name']; ?>';
       var lastname = '<?php echo $userresult['last_name']; ?>';
       var email = '<?php echo $userresult['email_id']; ?>';
       var mobile = '<?php echo $userresult['mobile']; ?>';
       var billingaddress = jQuery('#txtPlaces').val();
       var billingaddressdb =billingaddress;
       billingaddress = billingaddress.substring(0, 29);
       console.log("billing---"+billingaddress.length);
       var billingzip = jQuery('#zipcode').val();

       if(billingCity == '') billingCity ='<?php echo $userresult['city_name']; ?>';
       if(billingCountry == '') billingCountry='<?php echo $userresult['country_name']; ?>';
       if(billingState == '') billingState ='<?php echo $userresult['state_name']; ?>';

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
                           //jQuery("#loaderorder").removeClass('loading');
                       });

                   }else{
                       console.log(res);
                       var customerPaymentProfileId=res.paymentProfilesID;

                       var customerProfileId=res.CustomerProfileId;
                       cardData.customerProfileId=customerProfileId;
                       getCustomerProfile(customerPaymentProfileId, customerProfileId).success(function(rescard){

                           saveCard(customerPaymentProfileId, customerProfileId,billingaddressdb, billingzip, rescard.CardLast4, rescard.Cardtype).success(function(res) {
                               if (res.error) {
                                   swal("Error", res.message, "error");
                               } else {
                                    ajaxindicatorstop();
                                   swal({
                                       title:"Success",
                                       type:"success",
                                       text:"Card profile created successfully"
                                   },function(){
                                       jQuery('#creditcard').css("display","none");
                                       //jQuery('#creditcard').removeClass("in");
                                       //jQuery("#loaderorder").removeClass('loading');
                                       console.log(rescard);
                                       var cardhtml ='';
                                       var cardImage = getCardImage(rescard.Cardtype);
                                       cardhtml += '<div class="col-xs-12 col-md-6" id="card_id_'+customerPaymentProfileId+'"><div class="visapayment" >\
                                <img src="'+cardImage+'" width="30px" height="20px">\
                                <p>'+rescard.CardLast4+'</p>\
                            <div class="checkoutBox-payment">\
									  \
									  <div class="cancle-icon" onclick="removesavecard(\''+customerProfileId+'\', \''+customerPaymentProfileId+'\')" > <i class="fa fa-close"> </i>  </div>\
									  </div></div>\
                            </div>';
                                       jQuery("#cardViewProfile").append(cardhtml);
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
                    ajaxindicatorstop();
                       swal({
                           title:"Error!" ,
                           text:res.message,
                           type:'error'
                       },function(){
                             ajaxindicatorstop();
                          // jQuery("#loaderorder").removeClass('loading');
                       });
                   }else{
                       var paymentProfileID=res.response;
                       getCustomerProfile(paymentProfileID, CustomerProfileId).success(function(rescard){

                           saveCard(paymentProfileID, CustomerProfileId,billingaddressdb, billingzip, rescard.CardLast4, rescard.Cardtype).success(function(res){
                               if(res.error){

                               }else{
                                    ajaxindicatorstop();
                                   swal({
                                       title:"Success",
                                       type:"success",
                                       text:"Card profile created successfully"
                                   },function(){
                                       //jQuery("#loaderorder").removeClass('loading');
                                       jQuery('#creditcard').css("display","none");
                                       jQuery('#creditcard').removeClass("in");
                                       console.log(rescard);
                                       var cardImage = getCardImage(rescard.Cardtype);
                                       var cardhtml ='';
                                       cardhtml += '<div class="col-xs-12" id="card_id_'+paymentProfileID+'"><div class="visapayment">\
                                        <div class="col-md-6 col-xs-9">\<img src="'+cardImage+'" width="30px" height="20px"><p>'+rescard.CardLast4+'</p>\</div>\
                                        <div class="col-md-6 col-xs-3">\
                                          <div class="checkoutBox-payment">\
                                            <div class="cancle-icon" onclick="removesavecard(\''+CustomerProfileId+'\', \''+paymentProfileID+'\')" > <i class="fa fa-close"> </i>  </div>\
                                          </div></div></div></div>';
                                       jQuery("#cardViewProfile").append(cardhtml);
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

   jQuery(function () {
       var creditly = Creditly.initialize(
           '.auth-card .expiration-month-and-year',
           '.auth-card .credit-card-number',
           '.auth-card .security-code',
           '.auth-card .card-type');

       // Setup form validation on the #register-form element
       jQuery("#authrize_formProfile").validate({

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
               var output = creditly.validate();
               console.log(output);
               if (output) {

                   // Your validated credit card output
                   var card_number = output["number"];
                   var cvv = output["security_code"];

                   console.log(output);
                   saveCardDataProfile(card_number, cvv);
               }
           }
       });
       return false;
   });

    jQuery(document).ready(function(){
        getsavecarddata();
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
                jQuery('#addressvali').text(address);
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

        
            // Setup form validation on the #register-form element
            jQuery("#updateprofile").validate({

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
                        maxlength: 12,
                        minlength:10
                    },
                    address: "required",
                    //country:"required",
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
                        required: "Please enter  Mobile number",
                        minlength: "Please enter 10 digit Mobile number",
                        maxlength:"Please enter 12 digit Mobile number"
                    },
                    email: "Please enter a valid email address",
                    address:"Please enter your address",
                    //country:"Please enter your country",
                    state:"Please enter your state",
                    city:"Please enter your city",
                    pincode:{
                        required: "Please provide a pincode",
                        minlength: "Your pincode must be 5 characters long"
                    }
                },

                submitHandler: function(form) {
                    // form.submit();
                    var auth = '<?php echo $apikey;?>';
                    var firstname = jQuery('#first_name').val();
                    var lastname = jQuery('#last_name').val();
                    var email = jQuery('#emailid').val();
                    var country = jQuery('#countryid').val();
                    var state = jQuery('#stateid').val();
                    var city = jQuery('#cityid').val();
                    // var address = jQuery('#addressid').val();
                    var mobile = jQuery('#mobileid').val();
                    var pin_code = jQuery('#pin_code').val();

                    var street_address = jQuery('#addressid').val();
                    var apt_no = jQuery('#apt_no').val();
                    var complex_name = jQuery('#complex_name').val();
                    var latitude = jQuery('#latitude').val();
                    var longitude = jQuery('#longitude').val();
                    var address ={"street_address":street_address,"apt_no":apt_no, "complex_name":complex_name,"latitude":latitude,"longitude":longitude};
                   //alert(firstname);
                        if(isNaN(mobile)||mobile.indexOf(" ")!=-1)
                        {
                            swal("Enter numeric value");
                            //return false;
                        }else{

                        jQuery.ajax({
                            dataType:'json',
                            type: 'POST',
                            url: '<?php echo base_url();?>UpdateProfile',
                            data: {
                                'Authorization': auth,
                                'user_firstname': firstname,
                                'user_lastname': lastname,
                                'user_address': address,
                                'user_countryid': country,
                                'user_stateid': state,
                                'user_cityid': city,
                                'user_pincode': pin_code,
                                'user_mobile':mobile
                            },
                            beforeSend: function ()
                            {
                                ajaxindicatorstart('Please Wait...');
                            },
                            success: function (res) {
                                ajaxindicatorstop();
                                var userresult = res.user;
                                if (res.error == true) {
                                    swal(res.message);
                                } else {
                                    swal({
                                        title: "Thank You! We Have Successfully Updated Your Profile .",
                                        type: "success",
                                        showCancelButton: true
                                        },function () {
                                            window.location.href = '<?php echo base_url();?>/profile';
                                        });

                                    jQuery('#first_name').val(userresult.first_name);
                                    jQuery('#last_name').val(userresult.last_name);
                                    jQuery('#emailid').val(userresult.email_id);
                                   // jQuery('#countryid').val(userresult.country_id);
                                    jQuery('#stateid').val(userresult.state_id);
                                    jQuery('#cityid').val(userresult.city_id);
                                    //jQuery('#addressid').val(userresult.address);
                                    jQuery('#mobileid').val(userresult.mobile);
                                    jQuery('#pin_code').val(userresult.pincode);
                                   // jQuery('#countryid').text(userresult.county_name);
                                    jQuery('#stateid').text(userresult.state_name);
                                    jQuery('#cityid').text(userresult.city_name);
                                }
                            }
                        });
                        return false;
                    }
                }
            });

        });

    function pincodecheck11(){
        var id=jQuery('#pin_code').val();

        var pid=0;
        jQuery.ajax({
            type: 'GET',
            url: '<?php echo base_url("index.php/main/checkPincode"); ?>',
            data: {'id': id},
            success: function (res) {
                if(res.error==true){
                  swal(res.message);

                }else{
                    swal(res.message);
                }
            }
        });
    }
    //get_country();
    function get_country(){
        var  api_key = '<?php echo $apikey;?>';
        jQuery.ajax({
            type:'GET',
            url:'<?php echo base_url();?>getCountry',
            data:{'Authorization': api_key},
            success:function(res){
                console.log(res);
                var countryval=res.country;
                var countrylength=res.country.length;

                for(var i=0; i<countrylength; i++)
                {
                    var result=countryval[i];
                    var html='';
                    //jQuery('#countrygetid').attr(result.name);
                    html+='<option id="did"  value="">Please Select Country</option>';
                    html+='<option id="did"  value="'+result.id+'">'+result.name+'</option>';
                    jQuery('#countryid').append(html);
                }
            }
        });

    }

    function get_state(id){
        var  api_key = '<?php echo $apikey;?>';
        jQuery.ajax({
            type:'GET',
            url:'<?php echo base_url();?>getState',
            data:{'Authorization': api_key, 'cid':id},
            success:function(res){
                console.log(res);
                var stateval=res.state;
                var statelength=res.state.length;
                console.log(statelength);
                html+='<option id="did"  value="">Please Select State</option>';
                for(var i=0; i<statelength; i++)
                {
                    var result=stateval[i];
                    var html='';
//                    jQuery('#countrygetid').text(result.name);
                    html+='<option id="did"  value="">Please Select State</option>';
                    html+='<option  value="'+result.id+'">'+result.name+'</option>';
                    jQuery('#stateid').append(html);
                }
            }
        });
    }
    function get_city(id){
        var  api_key = '<?php echo $apikey;?>';
        jQuery.ajax({
            type:'GET',
            url:'<?php echo base_url();?>getCity',
            data:{'Authorization': api_key, 'city_id':id},
            success:function(res){
                console.log(res);
                var countryval=res.city;
                var countrylength=res.city.length;
                html+='<option id="did"  value="">Please Select City</option>';
                for(var i=0; i<countrylength; i++)
                {
                    var result=countryval[i];
                    var html='';
                    html+='<option id="did"  value="">Please Select City</option>';
                    html+='<option  value="'+result.id+'">'+result.name+'</option>';
                    jQuery('#cityid').append(html);
                }
            }
        });
    }



    jQuery("#ChangePassword").validate({
        // Specify the validation rules
        rules: {
            oldpassword : {
                required: true
            },
            newpassword : {
                required: true
            },
            confirmpassword : {
                required: true,
                equalTo : "#pwd2"
            }
        },
        // Specify the validation error messages
        messages: {
            oldpassword: {
                required: "Please provide your old password"
            },
            newpassword: {
                required: "Please provide your new password"
            },
            confirmpassword: {
                required: "Please provide your confirm password",
              equalTo : "Your password must be same as new password"
            }
        },

        submitHandler: function(form) {
            // form.submit();
            var  api_key = '<?php echo $apikey;?>';
            var oldpwd=jQuery('#pwd1').val();
            var newpwd=jQuery('#pwd2').val();
            console.log(api_key);
            console.log(oldpwd);
            console.log(newpwd);
            jQuery.ajax({
                type:'POST',
                url:'<?php echo base_url();?>ChangePassword',
                data:{'Authorization': api_key, 'oldpassword':oldpwd,'newpassword':newpwd},
                beforeSend: function ()
                {
                   // ajaxindicatorstart('Please Wait...');
                },
                success:function(res){
                    //return false;
                    ajaxindicatorstop();
                    if(res.error==true){
                        swal("Something! Went Wrong Please Try Again");
                    }else{
                        console.log(res);
                        logoutdata();
                    }
                }
            });
        }
    });

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
        var city='';
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

</script>
</body>
</html>

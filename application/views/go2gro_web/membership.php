<?php include 'header.php';
load_css(array('public/assets/stylesheet/pgstyle/membership.css?'));
?>
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<div class="content-w"> 
    <!-- BEGIN Main Container col2-right -->
    <div id="loaderloginpro" class="loading hidden"></div>
    <div class="main-container col2-right-layout ">
        <div class="main container">
            <div class="row">
                <section class="col-main col-sm-12 wow bounceInUp animated animated cart-collaterals visi-add">
                    <div id="messages_product_view"></div>
                    <div class="totals" id="membership_plan_htmldata">
                        <h3>Select your membership plan</h3>
                        <div class="inner clearfix">
                        </div>
                        <!--inner-->
                    </div>
                </section>
                <!--col-right sidebar-->
          <div class="col-sm-12 col-fulladd"> 
          <section class="col-main col-sm-12 cart-collaterals visi-add" id="creditcardpanel"> 
            <div class="totals totals-marginbt">
                        <div>
                            <h3>Credit Card Detail <button type="button" class="pull-right btn btn-primary creditbtn membership-creditbtn" id="creditbtn" data-toggle="modal" data-target="#creditcard" onclick="resetFormProfile();">  Add Card</button></h3>
                        </div>
                        <div class="inner" id="cardViewProfile">
                        </div>
                        <!--inner-->
              
               
                 <div class="col-md-12 text-right no_pad"> 
                <div class="memsp_btn">  <button class="btn btn-default membership_sbt savechanges-btn" id="create_membership_now"> Pay </button> </div>
                 </div>
                 
                    </div>
          </section>
          </div>
          
            </div>
            <!--row-->
        </div>
        <!--main-container-inner-->
    </div>
    <!--main-container col2-left-layout-->

    <!-- add card model -->
    <div class="modal fade membership-bgadd" id="creditcard" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content membership-contentfull">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="modal-title text-center">Add Card</h3>
                </div>
                <form action="javascript:void(0);" method="post" id="authrize_formProfile" class="creditly-card-form">
                    <div class="modal-body clearfix checkout-card">
                        <div class="col-md-12">
                            <div class="form-group marT15 clearfix">
                                <section class="auth-card">
                                    <div class="credit-card-wrapper">
                                        <div class="first-row form-group">
                                            <div class="col-sm-12 controls">
                                                <label class="control-label membership-modeltitel">Card Number*</label>
                                                <input class="credit-card-number form-control membership-input"
                                                       type="text" name="card_number" id="authorizenet-card-number"
                                                       inputmode="numeric" autocomplete="cc-number" autocompletetype="cc-number" x-autocompletetype="cc-number"
                                                       placeholder="&#149;&#149;&#149;&#149; &#149;&#149;&#149;&#149; &#149;&#149;&#149;&#149; &#149;&#149;&#149;&#149;">
                                            </div>

                                        </div>
                                        <div class="second-row form-group">
                                            <!--<div class="col-sm-8 controls">
                                                <label class="control-label">Name on Card</label>
                                                <input class="billing-address-name form-control"
                                                       type="text" name="name"
                                                       placeholder="">
                                            </div>-->
                                            <div class="col-sm-6 controls">
                                                <label class="control-label membership-modeltitel">Expiration*</label>
                                                <input id="authorizenet-card-expiry" class="expiration-month-and-year form-control" required
                                                       type="text" name=cardexpiry"
                                                       placeholder="MM / YY">
                                            </div>
                                            <div class="col-sm-6 controls">
                                                <label class="control-label membership-modeltitel">CVV*</label>
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
                                <label class="control-label membership-modeltitel">Billing Address*</label>

                                <input type="text" name="billingAddress" class="form-control map_api_autocomplete membership-input" id="txtPlaces"  placeholder="Enter a location" />

                            </div>
                            <div class="col-sm-12 controls">
                                <label class="control-label membership-modeltitel">Zip Code*</label>
                                <input type="text" name="zipcode" class="form-control" id="zipcode" placeholder="Zip Code" class="zipcode-container membership-zepcode ">
                                <p class="marT15 d-inline" id="addressvali"></p>
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
     <!-- add card model end -->
</div>
<?php include 'footer.php' ?>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB-wq5TQX9liVeO4LoNFs8tF48H0PqKy2o&libraries=places&callback=initAutocomplete" async defer></script>
<script>

    var cardData ={};
    var billingCity ='';
    var billingState = '';
    var billingCountry = '';
    jQuery(document).ready(function(){
        getmembership_plan_data();
        getsavecarddata();
    });

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

    function getmembership_plan_data(){
        jQuery('#cardViewProfile').html('<div class="deliveryloader"></div>');
        var auth = '<?php echo $apikey;?>';
        jQuery.ajax({
            type: 'GET',
            url: '<?php echo base_url('membership_plan');?>',
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
                    swal({
                       title:"Error!" ,
                       text:res.message,
                       type:'error'
                   },function(){
                       jQuery("#loaderorder").removeClass('loading');
                       jQuery('#membership_plan_htmldata').html('');
                   });
                    //jQuery('#cardViewProfile .deliveryloader').remove();
                }else{
                        var check =''; var select_html= unselect_html = '';
                        var membership_plan = res.membership_plan;
                        var html_data = '<h3>Select your membership plan</h3><div class="inner clearfix">';
                        if(res.status=="Valid"){
                            jQuery('#create_membership_now').css("display","none");
                            jQuery('#creditbtn').css("display","none");
                            jQuery('#creditcardpanel').css("display","none");

                            for(var i=0; i<membership_plan.length; i++){
                                
                                if(membership_plan[i].id == res.planid){
                                    check = "checked";
                                    select_html+='<div class="col-md-12 membership_block membership_active">\
                                        <label class="membership-width"><div class="row"><div class="col-md-12 active_planinfo"><div> <p> '+ res.message +'</p> </div></div>\
                                        <div class="col-md-12"><div><input id="radio-'+membership_plan[i].id +'" class="radio-custom" name="radio-group" type="radio" '+ check +' value='+membership_plan[i].id+'  disabled><label for="radio-'+membership_plan[i].id +'" class="radio-custom-label col-md-12" ><div class=""><div class="col-md-8 col-sm-8 col-xs-12 mem_detail clearfix"><h4 class="titel-color">'+membership_plan[i].plan_name+'</h4><p>'+ membership_plan[i].description +'</p>\
                                    </div><div class="col-md-3 col-sm-3 col-sm-12 text-right mem_amnt "><h4 class="titel-color"> $'+membership_plan[i].price+' </h4>\
                                    </div></div>\
                                    </label></div> \
                                            </div>\
                                        </div>\
                                    </label>\
                                </div>';
                                }else{
                                     unselect_html+='<div class="col-md-12 membership_block">\
                                        <label class="membership-width"><div class="row"><div class="col-md-12"><div><input id="radio-'+membership_plan[i].id +'" class="radio-custom" name="radio-group" type="radio" value='+membership_plan[i].id+'  disabled><label for="radio-'+membership_plan[i].id +'" class="radio-custom-label col-md-12" ><div class=""><div class="col-md-8 col-sm-8 col-xs-12 mem_detail clearfix"><h4 class="titel-color">'+membership_plan[i].plan_name+'</h4><p>'+ membership_plan[i].description +'</p>\
                                    </div><div class="col-md-3 col-sm-3 col-sm-12 text-right mem_amnt "><h4 class="titel-color"> $'+membership_plan[i].price+' </h4>\
                                    </div></div>\
                                    </label></div> \
                                            </div>\
                                        </div>\
                                    </label>\
                                </div>';
                                }
                            }
                            html_data+=select_html+unselect_html;
                        }
                        else{
                            for(var i=0; i<membership_plan.length; i++){
                                if(membership_plan[i].duration == 12){
                                    check = "checked";
                                }
                                html_data+='<div class="col-md-12 membership_block">\
                                <label class="membership-width"><div class="row">\
                                <div class="col-md-12"><div><input id="radio-'+membership_plan[i].id +'" class="radio-custom" name="radio-group" type="radio" '+ check +' value='+membership_plan[i].id+'><label for="radio-'+membership_plan[i].id +'" class="radio-custom-label col-md-12" ><div class=""><div class="col-md-8 col-sm-8 col-xs-12 mem_detail clearfix"><h4 class="titel-color">'+membership_plan[i].plan_name+'</h4><p>'+ membership_plan[i].description +'</p>\
                                    </div><div class="col-md-3 col-sm-3 col-sm-12 text-right mem_amnt "><h4 class="titel-color"> $'+membership_plan[i].price+' </h4>\
                                    </div></div>\
                                    </label></div> \
                                            </div>\
                                        </div>\
                                    </label>\
                                </div>';
                            }
                        } 
                    jQuery("#membership_plan_htmldata").html(html_data);
                }

            }
        });
    }

    function getsavecarddata(){
        jQuery('#cardViewProfile').html('<div class="deliveryloader"></div>');
        var auth = '<?php echo $apikey;?>';
        jQuery.ajax({
            type: 'GET',
            url: '<?php echo base_url();?>getsavecard',
            data: {
                'Authorization': auth
            },
            beforeSend: function ()
            {
                ajaxindicatorstart('Please wait...');
            },
            success: function (res) {
                ajaxindicatorstop();
                console.log(res);
                if(res.error==true){
                    jQuery('#cardViewProfile .deliveryloader').remove();
                }else{
                    //type==1 when custmor profile already exist and type==0 not exist
                    if(res.type == "0"){
                        var user_id=res.user_id;
                        var cardDetail = res.card;
                        var cardhtml = ''; var cardChecked=''; var cardBeforeClass ='';
                        for(var i=0; i<cardDetail.length; i++){
                            var resultCard= cardDetail[i];
                            var CustomerProfileId=resultCard.customerProfileId;
                            var paymentProfilesID=resultCard.customerPaymentProfileId;
                            console.log(CustomerProfileId);
                            console.log(paymentProfilesID);

                            cardData.customerProfileId=CustomerProfileId;
                            var cardImage = getCardImage(resultCard.cardtype);
                            jQuery('#cardViewProfile').html('');

                            cardhtml += '<div class="col-xs-12 col-lg-12 " id="card_id_'+paymentProfilesID+'"><div class="visapayment membership-flatadd" >\
                                <img src="'+cardImage+'" width="30px" height="20px"><p>'+resultCard.card_number+'</p>\
                                <div class="checkoutBox-payment"><input type="checkbox" class="check" data-profileId="'+CustomerProfileId+'" data-payprofileId="'+paymentProfilesID+'" name="selectCard" value="'+resultCard.card_id+'"><label for="checkbox1"><span class="'+cardBeforeClass+'"></span></label>\
                                <div class="cancle-icon" onclick="removesavecard(\''+CustomerProfileId+'\', \''+paymentProfilesID+'\')" > <i class="fa fa-close"> </i>\
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
                beforeSend: function ()
                {
                    ajaxindicatorstart('Please wait...');
                },
                success: function (res) {
                    ajaxindicatorstop();
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
       var firstname = '<?php echo $fname ?>';
       var lastname = '<?php echo $lname ?>';
       var email = '<?php echo $email_id ?>';
       var mobile = '<?php echo $mobile ?>';
       var billingaddress = jQuery('#txtPlaces').val();
       var billingaddressdb =billingaddress;
       billingaddress = billingaddress.substring(0, 29);
       console.log("billing---"+billingaddress.length);
       var billingzip = jQuery('#zipcode').val();

       if(billingCity == '') billingCity ='<?php echo $county_name ?>';
       if(billingCountry == '') billingCountry='<?php echo $county_name ?>';
       if(billingState == '') billingState ='<?php echo $state_name ?>';

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
                    ajaxindicatorstart('Please wait...');
                },
                success: function (res) {
                    ajaxindicatorstop();
                    if(res.error==true){
                        swal({
                           title:"Error!" ,
                           text:res.message,
                           type:'error'
                        },function(){
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
                                   swal("Error", res.message, "error");
                               } else {
                                   swal({
                                       title:"Success",
                                       type:"success",
                                       text:"Card profile created successfully"
                                   },function(){
                                    jQuery("#loaderorder").removeClass('loading');
                                    jQuery('#creditcard').css("display","none");
                                    jQuery('#creditcard').removeClass("in");
                                        getsavecarddata();
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
                    ajaxindicatorstart('Please wait...');
                },
                success: function (res) {
                ajaxindicatorstop();
                   if(res.error==true){
                       swal({
                           title:"Error!" ,
                           text:res.message,
                           type:'error'
                       },function(){
                           jQuery("#loaderorder").removeClass('loading');
                       });
                   }else{
                       var paymentProfileID=res.response;
                       getCustomerProfile(paymentProfileID, CustomerProfileId).success(function(rescard){

                           saveCard(paymentProfileID, CustomerProfileId,billingaddressdb, billingzip, rescard.CardLast4, rescard.Cardtype).success(function(res){
                               if(res.error){

                               }else{

                                   swal({
                                       title:"Success",
                                       type:"success",
                                       text:"Card profile created successfully"
                                   },function(){
                                        jQuery("#loaderorder").removeClass('loading');
                                        jQuery('#creditcard').css("display","none");
                                        jQuery('#creditcard').removeClass("in");
                                        getsavecarddata();
                                   });
                               }
                           });
                       });
                       //console.log(res);
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

    /*function toggleCheckbox(id, card_id, customerProfileId, paymentprofileid){
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
    }*/

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
               card_number:"required",
               billingAddress: "required",
               zipcode: "required",
               cardexpiry: "required",
               cvv: "required"
           },


           // Specify the validation error messages
           messages: {
               card_number:"Please enter valid card number",
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

    //------------------------------------------------------
    jQuery(document).on('click','.check',function() {
        jQuery('.check').not(this).prop('checked', false);
    });
    //------------------------------------------------------

    jQuery(document).on('click','#create_membership_now',function(){
        var auth = '<?php echo $apikey;?>';
        var profileid = "";
        var paymentpayprofileid = "";
        var cardId = "";
        var checked_check = false;
        if(jQuery("input[name='radio-group']:checked").length > 0){
            var selected_plan = jQuery("input[name='radio-group']:checked").val();
            jQuery('input[name="selectCard"]:checked').each(function() {
                checked_check = true;
                profileid = jQuery(this).data("profileid");
                paymentpayprofileid = jQuery(this).data("payprofileid");
                cardId = jQuery(this).val();
            });

            if(checked_check){
                jQuery('#loaderloginpro').removeClass('hidden');
                jQuery("#loaderlogin").addClass('glyphicon glyphicon-refresh glyphicon-refresh-animate');
                //console.log("Sumit=>"+"profileId_@_"+profileid+"_paymentpayprofileid@_"+paymentpayprofileid+"_id="+cardId+"_selected_plan="+selected_plan);
                jQuery.ajax({
                    type: 'POST',
                    url: '<?php echo base_url();?>membership_payment',
                    data: {
                        'Authorization':auth,
                        'profileid':profileid,
                        'paymentpayprofileid':paymentpayprofileid,
                        'selected_plan':selected_plan,
                        'selected_Cardid':cardId
                    },
                    beforeSend: function ()
                    {
                        ajaxindicatorstart('Please wait...');
                    },
                    success: function (res) {
                        ajaxindicatorstop();
                        jQuery('#loaderloginpro').addClass('hidden');
                        jQuery("#loaderlogin").removeClass('glyphicon glyphicon-refresh glyphicon-refresh-animate');
                        console.log(res);
                       if(res.error==true){
                           swal({
                               title:"Error!" ,
                               text:res.message,
                               type:'error'
                           },function(){
                               jQuery("#loaderorder").removeClass('loading');
                           });
                       }else{
                            swal({
                               title:"success" ,
                               text:res.message,
                               type:'success'
                           },function(){
                               jQuery("#loaderorder").removeClass('loading');
                               getmembership_plan_data();
                           });
                       }
                    }
                })
            }else{
                swal({
                   title:"Error!" ,
                   text:"Please Select/Add a Card",
                   type:'error'
               });
            }
        }
        else{
            swal({
               title:"Error!" ,
               text:"Please select membership plan",
               type:'error'
           });
        }
    })
</script>
</body>
</html>

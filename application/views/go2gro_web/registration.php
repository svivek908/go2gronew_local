<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
    	<title><?php echo $this->config->item('title'); ?></title>

        <link rel="icon" href="<?php echo base_url();?>public/assets/images/fevicon.png" type="image/x-icon">
        <link rel="shortcut icon" href="#" type="image/x-icon">
        <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <!-- CSS Style -->
         <?php load_css(array(
            'public/assets/stylesheet/bootstrap.min.css?',
            'public/assets/stylesheet/font-awesome.css?',
            'public/assets/stylesheet/revslider.css?',
            'public/assets/stylesheet/owl.carousel.css?',
            'public/assets/stylesheet/owl.theme.css?',
            'public/assets/stylesheet/jquery.bxslider.css?',
            'public/assets/stylesheet/jquery.mobile-menu.css?',
            'public/assets/stylesheet/style.css?',
            'public/assets/stylesheet/flexslider.css?',
            'public/assets/stylesheet/sticky/style.css?',
            'public/assets/stylesheet/responsive.css?',

        ));?>
        <link href='https://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,700italic,400,600,700,800' rel='stylesheet' type='text/css'>
        <link href='https://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
        <link href='https://fonts.googleapis.com/css?family=Roboto+Slab:400,700' rel='stylesheet' type='text/css'>
        <?php load_css(array(
            'public/assets/stylesheet/cloud-zoom.css?',
            'public/assets/assets/css/paymentInfo.css?',
        ));?>

        <!-- BEGIN GOOGLE ANALYTICS CODEs -->
        <link rel='stylesheet prefetch' href='https://cdn.rawgit.com/filamentgroup/fixed-sticky/master/fixedsticky.css'>
         <?php load_js(array('public/assets/sweetalertlib/dist/sweetalert.min.js?',
        ));?>
         <?php load_css(array(
            'public/assets/sweetalertlib/dist/sweetalert.css?',
            'public/assets/toaster/demos/css/jquery.toast.css?',
        ));?>
         <?php load_js(array(
            'public/assets/js/jquery.min.js?',
            'public/assets/js/bootstrap.min.js?',
            'public/assets/js/parallax.js?',
            'public/assets/js/revslider.js?',
            'public/assets/js/common.js?',
            'public/assets/js/jquery.bxslider.min.js?',
            'public/assets/js/jquery.flexslider.js?',
            'public/assets/js/owl.carousel.min.js?',
            'public/assets/js/jquery.mobile-menu.min.js?',
        ));?>
        <!-- JavaScript -->
        <!--    <script src="--><?php //echo base_url();?><!--assets/scripts/libs/jquery.js"></script>-->
        <script src='https://cdn.rawgit.com/filamentgroup/fixed-sticky/master/fixedsticky.js'></script>
         <?php load_js(array(
            'public/assets/js/jquery.validate.min.js?',
            'public/assets/toaster/demos/js/jquery.toast.js?',
            'public/assets/js/cloud-zoom.js?',
            'public/assets/js/lodash.min.js?',
        ));?>
        <script type="text/javascript" src="https://cdn.ywxi.net/js/1.js" async></script>
         <?php load_js(array('public/assets/js/jquery-ui.js?',
        ));?>
        <?php load_css(array(
            'public/assets/stylesheet/jquery-ui.css?',
        ));?>
         <?php load_js(array('public/assets/js/moment.min.js?',
            'public/assets/js/moment-timezone-with-data.js?',
            'public/assets/js/index.js?',
            'public/assets/js/jquery.twbsPagination.js?',
            'public/assets/src/creditly.js?',
        ));?>
       <?php load_css(array(
            'public/assets/src/creditly.css?',
            'public/assets/stylesheet/pgstyle/registration.css?',
        ));?>

        <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAUKMuAiuK26GQzmWcPDkTLxGzDZeZzM3Y&libraries=places"></script>
        
        <link href='https://fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css'>
    </head>
    <body class="zipcode_bg">
        <div class="container"> 
            <div class="col-md-5 col-md-offset-7"> 
                <div class="login-container registration-conatiner clearfix"> 
                     <div class="col-md-12 text-center">
                        <a href="<?php echo base_url();?>" title="Go2Gro">
                            <div class="registration-toplogo"><img src="<?php echo base_url('public/assets//images/go2gro_logo.png');?>" alt="Go2Gro"></div>
                        </a>
                    </div>
                    
                    <div class="zipcode login_pform clearfix" id="zipcodevalset">
                        <!--reg form start -->
                        <form action="#" method="POST" id="regForm">
                             <input type="hidden" name="logintype" class="input-text required-entry validate-password" id="logintype" title="Password" value="<?php echo $logintype;?>" placeholder="">
                            <div class="clearfix">
                                <!-- One "tab" for each step in the form: -->
                                <div class="tab">
                                    <div class="col-md-12 ">
                                        <div class="form-group">
                                            <div class="input-box">

                                                <input type="text" name="fname" id="fname"class="input-text required-entry validate-email" title="First name" placeholder="First Name" oninput="this.className = ''" value="<?php echo $name;?>">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="input-box">
                                                <input type="text" name="lname" id="lname"class="input-text required-entry validate-email" title="Last name" placeholder="Last name" value="<?php echo $lname;?>" oninput="this.className = ''">
                                            </div>  
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="input-box">
                                                <input type="text" name="user_email" id="user_email"class="input-text required-entry validate-email" title="Email Address" placeholder="Email Address" oninput="this.className = ''" value="<?php echo $email;?>" >
                                            </div>
                                        </div>
                                    </div>
                                    <?php if($gmail_reg) { ?>
                                        <input type="hidden" name="pass" class="input-text required-entry validate-password" id="pass" title="Password" value="<?php echo $password;?>" placeholder="">
                                    <?php }else{ ?>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div class="input-box">
                                                    <input type="password" name="pass" class="input-text required-entry validate-password" id="pass" title="Password" value="" placeholder="Password" oninput="this.className = ''">
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                           <div class="input-box">
                                                <input type="text" name="mobile" minlength="10" maxlength="12"class="input-text required-entry validate-password" id="mobile" title="Mobile" placeholder="Mobile" oninput="this.className = ''">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="input-box">
                                                <input type="text" name="" class="input-text required-entry validate-password map_api_autocomplete" id="address" title="address" placeholder="address" oninput="this.className = ''">
                                                <input type="hidden" name="" id="latitude" value="">
                                                <input type="hidden" name="" id="longitude" value="">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                           <div class="input-box">
                                                <input type="text" name="" class="input-text" id="apt_no" title="Unit" placeholder="Unit" oninput="this.className = ''">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="input-box">
                                                <input type="text" name="" class="input-text" id="complex_name" title="Residential community" placeholder="Residential community" oninput="this.className = ''">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="input-box">
                                                <input type="text" name="pin_code" class="input-text required-entry validate-password" id="pin_code" title="Zip code" placeholder="Zip code" oninput="this.className = ''">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="input-box">
                                                <input type="text" name="referral_code" class="input-text" id="referral_code" title="Referral code" placeholder="Referral code">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <input type="checkbox" id="terms" name="terms" class="checkbox registration-checkbox">
                                            <p class="inlineB">By signing up, you agree to Go2Gro's 
                                                <a href="<?= base_url() . 'privacy'; ?>"> <strong> Privacy Policy </strong></a> and
                                                <a href="#" class="term_con_space"><strong> Terms & <span class="left_mar_modify">Conditions</span> </strong></a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div >
                              <div class="col-md-12 text-right rs_mar">
                                <button type="button" class="btn btn-default prevbtn-login" id="prevBtn" onclick="nextPrev(-1)">Previous</button>
                                <button type="button" class="btn btn-success next-btnlogin" id="nextBtn" onclick="nextPrev(1)"><span id="loaderlogin" class=""></span>Next</button>
                              </div>
                            </div>

                            <!-- Circles which indicates the steps of the form: -->
                            <div class="registration-step">
                              <span class="step"></span>
                              <span class="step"></span>
                              <!-- <span class="step"></span>
                              <span class="step"></span> -->
                            </div>
                        </form>
                         <!--reg form close -->
                    </div>
                    
                     <div class="login_with">
                        <p class="text-center fs-30 no-margin" > Sign In with </p>
                     
                        <div class="text-center fs-32"> 
                            <!-- <span class="fb_icon"> <i class="fa fa-facebook-official" aria-hidden="true"></i></span> -->
                           <a href="<?php echo base_url('Main/logingoogle');?>"> <span class="gp_icon"> <i class="fa fa-google-plus-square" aria-hidden="true"></i> </span></a>
                        </div>
                    </div>
                    
                    <!-- <div class="alrdy_acc"> <p> Don't have an account? <a href="<?php echo base_url();?>registration"> Sign Up </a> </p></div> -->

                </div>
            </div>
        </div>

        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB-wq5TQX9liVeO4LoNFs8tF48H0PqKy2o&libraries=places&callback=initAutocomplete" async defer></script>
        <script>
            jQuery(document).ready(function () {
                queryinformationshow();   
            });
            //-------------------Step Form----------------------------
            var validate_pass = '<?php echo $gmail_reg;?>'; //when gmail signup password not check
            var currentTab = 0; // Current tab is set to be the first tab (0)
            showTab(currentTab); // Display the current tab

            function showTab(n) {
                // This function will display the specified tab of the form ...
                var x = document.getElementsByClassName("tab");
                x[n].style.display = "block";
                // ... and fix the Previous/Next buttons:
                if (n == 0) {
                    document.getElementById("prevBtn").style.display = "none";
                } else {
                    document.getElementById("prevBtn").style.display = "inline";
                    //document.getElementById("register_btn").id = "nextBtn";
                }
                if (n == (x.length - 1)) {
                	document.getElementById("nextBtn").innerHTML = "Submit";
                    //document.getElementById("nextBtn").id = "register_btn";
                    
                } else {
                    document.getElementById("nextBtn").innerHTML = "Next";
                }
                // ... and run a function that displays the correct step indicator:
                fixStepIndicator(n)
            }

            function nextPrev(n) {
                // This function will figure out which tab to display
                var x = document.getElementsByClassName("tab");
                console.log(n +'@'+ currentTab +'##'+ x.length);
                // Exit the function if any field in the current tab is invalid:
                if (n == 1 && !validateForm()) return false;
                //if you have reached the end of the form... :
                if (currentTab <= x.length) {
                	// Hide the current tab:
                	if(currentTab != n){
                		x[currentTab].style.display = "none";
                	}
                	// Increase or decrease the current tab by 1:
                	currentTab = currentTab + n;
                }
                
                if (currentTab >= x.length && n!= -1){
                	currentTab = n;
                    //...the form gets submitted:
                     registration();
                    return false;
                }
                // Otherwise, display the correct tab:
          		showTab(currentTab);
            }

            function validateForm() {
                // This function deals with validation of the form fields
                var x, y, i, valid = true;
                x = document.getElementsByClassName("tab");
                y = x[currentTab].getElementsByTagName("input");
                // A loop that checks every input field in the current tab:
                for (i = 0; i < y.length; i++) {
                    // If a field is empty...
                    if (y[i].id == "fname" || y[i].id == "lname" || (y[i].id == "pass" && validate_pass==false) || y[i].id == "address" 
                        || y[i].id == "apt_no" || y[i].id == "complex_name" || y[i].id == "pin_code") {
                        if (y[i].value == "") {
                            // add an "invalid" class to the field:
                            y[i].className += " invalid";
                            // and set the current valid status to false:
                            valid = false;
                        }
                    }
                    if (y[i].id == "user_email") {
                        var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                        if (y[i].value == "") {
                            // add an "invalid" class to the field:
                            y[i].className += " invalid";
                            // and set the current valid status to false:
                            valid = false;
                        }else if(!re.test(String(y[i].value).toLowerCase())){
                            y[i].className += " invalid";
                            swal("Enter valid email address");
                            valid = false;
                        }
                    }
                    if (y[i].id == "mobile") {
                        if (y[i].value == "") {
                            // add an "invalid" class to the field:
                            y[i].className += " invalid";
                            // and set the current valid status to false:
                            valid = false;
                        }
                        else if(isNaN(y[i].value) || y[i].value.indexOf(" ") != -1){
                            swal("Enter valid mobile number");
                            valid = false;
                        }
                        else if (y[i].value.length > 12) {
                            swal("Enter 10 digit mobile number");
                            valid = false;
                        }
                        else if (y[i].value.length < 10) {
                            swal("Enter 10 digit mobile number");
                            valid = false;
                        }
                    }
                    if(y[i].id == "terms"){
                        terms = jQuery("#terms").is(':checked');
                        if(!terms){
                            swal("Please agree privacy policy and terms & condition");
                            valid = false;
                        }
                    }
                }
                // If the valid status is true, mark the step as finished and valid:
                if (valid) {
                    document.getElementsByClassName("step")[currentTab].className += " finish";
                }
                return valid; // return the valid status
            }

            function fixStepIndicator(n) {
                // This function removes the "active" class of all steps...
                var i, x = document.getElementsByClassName("step");
                for (i = 0; i < x.length; i++) {
                    x[i].className = x[i].className.replace(" active", "");
                }
                //... and adds the "active" class to the current step:
                x[n].className += " active";
            }
            //-------------------------------------------
            function queryinformationshow() {
                swal({
                    title: "Get your first delivery free!",
                    text: "Please verify your number on the text message to get a free delivery on the first order!\nA link will be sent to your phone number after you create an account.",
                    type: "",
                    confirmButtonColor: "#DD6B55"
                });
            }

            function registration(){
            	var user_email = jQuery('#user_email').val();
                var pass = jQuery('#pass').val();
                var first_name = jQuery('#fname').val();
                var last_name = jQuery('#lname').val();
                var street_address = jQuery('#address').val();
                var apt_no = jQuery('#apt_no').val();
                var complex_name = jQuery('#complex_name').val();
                var latitude = jQuery('#latitude').val();
                var longitude = jQuery('#longitude').val();
                var address ={"street_address":street_address,"apt_no":apt_no, "complex_name":complex_name,"latitude":latitude,"longitude":longitude};
                var mobile = jQuery('#mobile').val();
                var pin_code = jQuery('#pin_code').val();
                var referral_code = jQuery('#referral_code').val();
                var logintype = jQuery('#logintype').val();
                var terms = jQuery("#terms").is(':checked');
            	jQuery.ajax({
                    type: 'POST',
                    url: '<?php echo base_url();?>signupform',
                    data: {
                        'first_name': first_name,
                        'last_name': last_name,
                        'user_email': user_email,
                        'pass': pass,
                        'mobile': mobile,
                        'pin_code': pin_code,
                        'address': address,
                        'referral_code':referral_code,
                        'logintype':logintype,
                    },
                    beforeSend: function ()
                    {
                       ajaxindicatorstart('Please Wait...');
                    },
                    success: function (res) {
                        ajaxindicatorstop();
                        if (res.error == true) {
                            sweetAlert("", res.message, "error");
                        } else {
                            swal({
                                title: "Success!",
                                type: "success",
                                text: "Registration Successful"
                            },
                            function () {
                                window.location.href = '<?php echo base_url();?>';
                            });
                        }
                    }
                });
            }
            
            function pincodesignup() {
                var zipcode = jQuery('#pin_code').val();
                if(zipcode.length >=5){
                    jQuery.ajax({
                        type: 'GET',
                        url: '<?php echo base_url("checkZipcode");?>',
                        data: {'zipcode': zipcode},
                        success: function (res) {
                            if (res.error) {
                                jQuery('#zipcode_chek').html(res.message);
                                jQuery('#pin_code').val('');
                                return false;
                            } else {
                                jQuery('#zipcode_chek').html('');
                                return true;
                            }
                        }
                    });
                }
            }

            function forgetpassreq() {
                jQuery('#loaderloginpro').removeClass('hidden');
                var emailid = jQuery('#email_idval').val();
                if (emailid == '') {
                    swal("Please enter your email address");
                    jQuery('#loaderloginpro').addClass('hidden');
                } else {

                    jQuery.ajax({
                        type: 'POST',
                        url: '<?php echo base_url("EmailVerify"); ?>',
                        data: {'emailid': emailid},
                        success: function (res) {
                            jQuery('#loaderloginpro').addClass('hidden');
                            if (res.error == true) {
                                swal(res.message);

                            } else {
                                swal({
                                        title: res.message,
                                        type: "success",
                                        showCancelButton: true
                                    },
                                    function () {
                                        window.location.href = '<?php echo base_url();?>';
                                    });
                            }
                        }
                    });
                }
                return false;
            }

            function initAutocomplete(){
                autocomplete = new google.maps.places.Autocomplete(
                    /** @type {!HTMLInputElement} */(document.getElementById('address')),
                    {types: ['geocode']});

                // When the user selects an address from the dropdown, populate the address
                // fields in the form.
                autocomplete.addListener('place_changed', fillInAddress(autocomplete));
            }

            function fillInAddress(autocomplete) {
                // Get the place details from the autocomplete object.
                var place = autocomplete.getPlace();
                document.getElementById('latitude').value=place.geometry.location.lat();
                document.getElementById('longitude').value= place.geometry.location.lng();
            }

            //-----------ajax indicator start------
            function ajaxindicatorstart(text)
            {
                if(jQuery('body').find('#resultLoading').attr('id') != 'resultLoading'){
                jQuery('body').append('<div id="resultLoading"><div><img src="<?php echo base_url('public/assets/images/ajax-loader.gif');?>"><div>'+text+'</div></div><div class="bg"></div></div>');
                }
                jQuery('#resultLoading').css({
                    'width':'100%',
                    'height':'100%',
                    'position':'fixed',
                    'z-index':'10000000',
                    'top':'0',
                    'left':'0',
                    'right':'0',
                    'bottom':'0',
                    'margin':'auto'
                });

                jQuery('#resultLoading .bg').css({
                    'background':'#000000',
                    'opacity':'0.7',
                    'width':'100%',
                    'height':'100%',
                    'position':'absolute',
                    'top':'0'
                });

                jQuery('#resultLoading>div:first').css({
                    'width': '250px',
                    'height':'75px',
                    'text-align': 'center',
                    'position': 'fixed',
                    'top':'0',
                    'left':'0',
                    'right':'0',
                    'bottom':'0',
                    'margin':'auto',
                    'font-size':'16px',
                    'z-index':'10',
                    'color':'#ffffff'

                });

                jQuery('#resultLoading .bg').height('100%');
                jQuery('#resultLoading').fadeIn(300);
                jQuery('body').css('cursor', 'wait');
            }
            //-----------ajax stop-----

            function ajaxindicatorstop()
            {
                jQuery('#resultLoading .bg').height('100%');
                jQuery('#resultLoading').fadeOut(300);
                jQuery('body').css('cursor', 'default');
            }
        </script>
    </body>
</html>

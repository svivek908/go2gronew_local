<?php
if($this->session->has_userdata('pincode')) {
    $isPincode = true;
    $pin_code= $this->session->userdata['pincode']['pincode'];
}else{
    $isPincode = false;
    $pin_code='';
}
$cart=$this->session->get_userdata("cart");

if (empty($cart)) {
    if(isset($cart) && isset($cart['cart']->item)){
        $cartloop=$cart['cart']->item;
        foreach($cartloop as $cartdata){
            $cartId = $cartdata->item_id;
            $item_name=$cartdata->item_name;
            $item_qty=$cartdata->item_quty;
            $item_price=$cartdata->item_price;
        }
    }
}else{}

if ($this->session->has_userdata('go2grouser')) {
    $isLogin = true;
    $logged_user = $this->session->userdata('go2grouser');;
    $userId = $logged_user['id'];
    $fname = $logged_user['first_name'];
    $lname = $logged_user['last_name'];
    $apikey=$logged_user['api_key'];
} else {
    $isLogin = false;
}?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $this->config->item('title'); ?></title>

    <link rel="icon" href="<?php echo base_url();?>images/fevicon.png" type="image/x-icon">
    <link rel="shortcut icon" href="#" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- CSS Style -->
    <?php load_css(array('public/assets/stylesheet/bootstrap.min.css?',
    'public/assets/stylesheet/font-awesome.css?',
    'public/assets/stylesheet/revslider.css?',
    'public/assets/stylesheet/owl.carousel.css?',
    'public/assets/stylesheet/owl.theme.css?',
    'public/assets/stylesheet/jquery.bxslider.css?',
    'public/assets/stylesheet/jquery.mobile-menu.css?',
    'public/assets/stylesheet/style.css?',
    'public/assets/stylesheet/sticky/style.css?',
    'public/assets/stylesheet/responsive.css?'));?>
    
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,700italic,400,600,700,800' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Roboto+Slab:400,700' rel='stylesheet' type='text/css'>

    <?php load_css(array('public/assets/stylesheet/cloud-zoom.css?','public/assets/css/paymentInfo.css?'));?>
    

    <!-- BEGIN GOOGLE ANALYTICS CODEs -->
    <link rel='stylesheet prefetch' href='https://cdn.rawgit.com/filamentgroup/fixed-sticky/master/fixedsticky.css'>
     <?php load_js(array('public/assets/sweetalertlib/dist/sweetalert.min.js?',
    
    ));?>
    <script src="<?php echo base_url();?>"></script>
    <?php load_css(array('public/assets/sweetalertlib/dist/sweetalert.css?',
    'public/assets/toaster/demos/css/jquery.toast.css?'));?>
    
    <!-- JavaScript -->
    <?php load_js(array('public/assets/js/jquery.min.js?',
    'public/assets/js/bootstrap.min.js?',
    'public/assets/js/parallax.js?',
    'public/assets/js/revslider.js?',
    'public/assets/js/common.js?',
    'public/assets/js/jquery.bxslider.min.js?',
    'public/assets/js/jquery.flexslider.js?',
    'public/assets/js/owl.carousel.min.js?',
    'public/assets/js/jquery.mobile-menu.min.js?',
    ));?>
    <!--    <script src="--><?php //echo base_url();?><!--assets/scripts/libs/jquery.js"></script>-->
   
    <script src='https://cdn.rawgit.com/filamentgroup/fixed-sticky/master/fixedsticky.js'></script>
    <?php load_js(array('public/assets/js/jquery.validate.min.js?',
    'public/assets/toaster/demos/js/jquery.toast.js?',
    'public/assets/js/cloud-zoom.js?',
    'public/assets/js/lodash.min.js?',
    ));?>
    <script type="text/javascript" src="https://cdn.ywxi.net/js/1.js" async></script>
     <?php load_js(array('public/assets/js/jquery-ui.js?',
    ));?>
     <?php load_css(array('public/assets/stylesheet/jquery-ui.css?',
     ));?>
      <?php load_js(array('public/assets/js/moment.min.js?',
        'public/assets/js/moment-timezone-with-data.js?',
        'public/assets/js/index.js?',
        'public/assets/js/jquery.twbsPagination.js?',
        'public/assets/src/creditly.js?',
    ));?>
   <?php load_css(array('public/assets/src/creditly.css?','public/assets/stylesheet/pgstyle/login.css?'
     ));?>

    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAUKMuAiuK26GQzmWcPDkTLxGzDZeZzM3Y&libraries=places"></script>
      
    <link href='https://fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css'>

    </head>


<body class="zipcode_bg">
    <div class="container"> 
        <div class="col-md-5 col-md-offset-7"> 
            <div class="login-container clearfix"> 
                 <div class="col-md-12 text-center">
                    <a href="<?php echo base_url();?>" title="Go2Gro">
                        <div class="login-toplogo"><img src="<?php echo base_url('public/assets/images/go2gro_logo.png');?>" alt="Go2Gro"></div>
                    </a>
                </div>
                
                <div class="zipcode login_pform clearfix" id="zipcodevalset">
                    <form  action="#" method="POST"  id="login-form">
                        <div class="clearfix">
                           <!--login form start -->

                            <div class="col-md-10 col-md-offset-1">
                                <div class="form-group">
                                   <div class="input-box">
                                        <input type="email" name="email_id" value="<?php echo get_cookie('email'); ?>"id="email_id" class="input-text required-entry validate-email"title="Email Address" placeholder="Enter Email Address">
                                    </div>  
                                </div>
                            </div>

                             <div class="col-md-10 col-md-offset-1">
                                <div class="form-group">
                                   <div class="input-box">
                                         <input type="password" name="pass_word"class="input-text required-entry validate-password" id="pass_word"title="Password" value="<?php echo get_cookie('password'); ?>" placeholder="Enter Password">
                                    </div>  
                                </div>
                            </div>


                        </div>

                        <!--login form close -->

                        <div class="col-md-10 col-md-offset-1 text-center ">
                            <button type="submit" class="button login-btn  " title="Login" name="send" id="send2"><span>Sign In</span><span id="loaderlogin" class="loaderlogin-pad"></span></button>
                        </div>


                    </form>
                </div>
                
                 <div class="login_with">
                     <p class="text-center fs-30 no-margin" > Sign In with </p>
                   <!-- <h2 class="text-center fs-30 no-margin" ><a href="<?php echo base_url();?>login"> Log In Here</span></a></h2>-->
                   <div class="text-center fs-32"> 
                        <!-- <span class="fb_icon"> <i class="fa fa-facebook-official" aria-hidden="true"></i></span> -->
                       <a href="<?php echo base_url('Main/logingoogle');?>"> <span class="gp_icon"> <img src="https://www.go2gro.com/go2gro_beta/application/images/google_icon.png" alt="Go2Gro"></span></a>
                   </div>
                </div>
                
                <div class="alrdy_acc"> <p> Don't have an account? <a href="<?php echo base_url();?>registration"> Sign Up </a> </p></div>

            </div>
        </div>
    </div>

    <!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB-wq5TQX9liVeO4LoNFs8tF48H0PqKy2o&libraries=places&callback=initAutocomplete" async defer></script> -->
    <script>
        function queryinformationshow() {
            swal({
                title: "Attention!",
                text: "Please verify your number on the text message to get a free delivery on the first order!\nA link will be sent to your phone number after you create an account.",
                type: "info",
                confirmButtonColor: "#DD6B55"
            });
        }
        jQuery(document).ready(function () {

            jQuery('#send2').click(function () {
                jQuery('#loaderloginpro').removeClass('hidden');
                jQuery("#loaderlogin").addClass('glyphicon glyphicon-refresh glyphicon-refresh-animate');
                var email = jQuery('#email_id').val();
                var password = jQuery('#pass_word').val();
                var remember_me = jQuery("#remember_me").is(':checked');
                console.log(remember_me);
                if (email == '') {
                    swal("Email is required");
                    jQuery('#loaderloginpro').addClass('hidden');
                    jQuery("#loaderlogin").removeClass('glyphicon glyphicon-refresh glyphicon-refresh-animate');
                } else if (password == '') {
                    swal("Password is required");
                    jQuery('#loaderloginpro').addClass('hidden');
                    jQuery("#loaderlogin").removeClass('glyphicon glyphicon-refresh glyphicon-refresh-animate');
                } else {
                    jQuery.ajax({
                        type: 'POST',
                        url: '<?php echo base_url('logindata');?>',
                        data: {'email': email, 'password': password, 'remember_me': remember_me},
                        success: function (res) {
                            if (res.error == true) {
                                sweetAlert("", res.message, "error");
                                jQuery('#loaderloginpro').addClass('hidden');
                                jQuery("#loaderlogin").removeClass('glyphicon glyphicon-refresh glyphicon-refresh-animate');
                            } else {
                                window.location.href = '<?php echo base_url('home');?>';
                            }
                        }
                    });
                }
                return false;
            });
        });

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
    </script>
</body>
</html>

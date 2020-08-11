<?php
$user = $this->session->get_userdata("user");
$pincode = $this->session->get_userdata("pincode");

if($this->session->userdata('pincode')) {
    $isPincode = true;
    $pin_code=$pincode['pincode'];
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

if (isset($user) && isset($user['user']->user->id)) {
    $isLogin = true;
    $fname = $user['user']->user->first_name;
    $lname = $user['user']->user->last_name;
    $userId = $user['user']->user->id;
    $apikey=$user['user']->user->api_key;

} else {
    $isLogin = false;
}?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $this->config->item('title'); ?></title>

    <link rel="icon" href="<?php echo base_url('public/assets/images/fevicon.png?'.$this->config->item('Web_unique_url'));?>" type="image/x-icon">
    <link rel="shortcut icon" href="#" type="image/x-icon">

    <!-- CSS Style -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('public/assets/stylesheet/bootstrap.min.css?'.$this->config->item('Web_unique_url'));?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('public/assets/stylesheet/font-awesome.css?'.$this->config->item('Web_unique_url'));?>" media="all">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('public/assets/stylesheet/revslider.css?'.$this->config->item('Web_unique_url'));?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('public/assets/stylesheet/owl.carousel.css?'.$this->config->item('Web_unique_url'));?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('public/assets/stylesheet/owl.theme.css?'.$this->config->item('Web_unique_url'));?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('public/assets/stylesheet/jquery.bxslider.css?'.$this->config->item('Web_unique_url'));?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('public/assets/stylesheet/jquery.mobile-menu.css?'.$this->config->item('Web_unique_url'));?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('public/assets/stylesheet/style.css?'.$this->config->item('Web_unique_url'));?>" media="all">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('public/assets/stylesheet/flexslider.css?'.$this->config->item('Web_unique_url'));?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('public/assets/stylesheet/sticky/style.css?'.$this->config->item('Web_unique_url'));?>" media="all">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('public/assets/stylesheet/responsive.css?'.$this->config->item('Web_unique_url'));?>" media="all">
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,700italic,400,600,700,800' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Roboto+Slab:400,700' rel='stylesheet' type='text/css'>

    <link rel="stylesheet" type="text/css" href="<?php echo base_url('public/assets/stylesheet/cloud-zoom.css?'.$this->config->item('Web_unique_url'));?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('public/assets/css/paymentInfo.css?'.$this->config->item('Web_unique_url'));?>">

    <!-- BEGIN GOOGLE ANALYTICS CODEs -->
    <link rel='stylesheet prefetch' href='https://cdn.rawgit.com/filamentgroup/fixed-sticky/master/fixedsticky.css'>
    <script src="<?php echo base_url('public/assets/sweetalertlib/dist/sweetalert.min.js?'.$this->config->item('Web_unique_url'));?>"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('public/assets/sweetalertlib/dist/sweetalert.css?'.$this->config->item('Web_unique_url'));?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('public/assets/toaster/demos/css/jquery.toast.css?'.$this->config->item('Web_unique_url'));?>">

    <!-- JavaScript -->
    <script type="text/javascript" src="<?php echo base_url('public/assets/js/jquery.min.js?'.$this->config->item('Web_unique_url'));?>"></script>
    <script type="text/javascript" src="<?php echo base_url('public/assets/js/bootstrap.min.js?'.$this->config->item('Web_unique_url'));?>"></script>
    <script type="text/javascript" src="<?php echo base_url('public/assets/js/parallax.js?'.$this->config->item('Web_unique_url'));?>"></script>
    <script type="text/javascript" src="<?php echo base_url('public/assets/js/revslider.js?'.$this->config->item('Web_unique_url'));?>"></script>
    <script type="text/javascript" src="<?php echo base_url('public/assets/js/common.js?'.$this->config->item('Web_unique_url'));?>"></script>
    <script type="text/javascript" src="<?php echo base_url('public/assets/js/jquery.bxslider.min.js?'.$this->config->item('Web_unique_url'));?>"></script>
    <script type="text/javascript" src="<?php echo base_url('public/assets/js/jquery.flexslider.js?'.$this->config->item('Web_unique_url'));?>"></script>
    <script type="text/javascript" src="<?php echo base_url('public/assets/js/owl.carousel.min.js?'.$this->config->item('Web_unique_url'));?>"></script>
    <script type="text/javascript" src="<?php echo base_url('public/assets/js/jquery.mobile-menu.min.js?'.$this->config->item('Web_unique_url'));?>"></script>
    <script src='https://cdn.rawgit.com/filamentgroup/fixed-sticky/master/fixedsticky.js'></script>
    <script src="<?php echo base_url('public/assets/js/jquery.validate.min.js?'.$this->config->item('Web_unique_url'));?>"></script>
    <script type="text/javascript" src="<?php echo base_url('public/assets/toaster/demos/js/jquery.toast.js?'.$this->config->item('Web_unique_url'));?>"></script>
    <script type="text/javascript" src="<?php echo base_url('public/assets/js/cloud-zoom.js?'.$this->config->item('Web_unique_url'));?>"></script>
    <script type="text/javascript" src="<?php echo base_url('public/assets/js/lodash.min.js?'.$this->config->item('Web_unique_url'));?>"></script>
    <script type="text/javascript" src="https://cdn.ywxi.net/js/1.js" async></script>

    <script src="<?php echo base_url('public/assets/js/jquery-ui.js?'.$this->config->item('Web_unique_url'));?>"></script>
    <link rel="stylesheet" href="<?php echo base_url('public/assets/stylesheet/jquery-ui.css?'.$this->config->item('Web_unique_url'));?>">
    <script src="<?php echo base_url('public/assets/js/moment.min.js?'.$this->config->item('Web_unique_url'));?>"></script>
    <script src="<?php echo base_url('public/assets/js/moment-timezone-with-data.js?'.$this->config->item('Web_unique_url'));?>"></script>
    <script src="<?php echo base_url('public/assets/js/index.js?'.$this->config->item('Web_unique_url'));?>"></script>

    <script src="<?php echo base_url('public/assets/js/jquery.twbsPagination.js?'.$this->config->item('Web_unique_url'));?>);?>" type="text/javascript"></script>
    <script src="<?php echo base_url('public/assets/src/creditly.js?'.$this->config->item('Web_unique_url'));?>);?>"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('public/assets/src/creditly.css?'.$this->config->item('Web_unique_url'));?>);?>">

    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAUKMuAiuK26GQzmWcPDkTLxGzDZeZzM3Y&libraries=places"></script>
    <style>
      .zipcode_bg{
		  background:url(<?php echo base_url('public/assets/images/zipcode_bg.png');?>) center no-repeat;
		  height:894px;
		  overflow:hidden;
	  }
	  .zipcode-container{
		  background: #fff;
          padding: 0px !important;
          margin-top: 68%;
          border: solid 1px #c1c0c0;
          box-shadow: 1px 3px 7px #6363638f;
        }
	  .cont_btn ,.cont_btn:hover{
		  background:#43b02a;
		  width:100%;
          border: none;

	  }
      .sub_heading{
        background: #efefef;
        margin-top: 23px;
        text-align: center;
        height: 40px;
        line-height: 40px;
        font-size: 14px;
    }
    .alrdy_acc a{
        color:#43b02a;
        text-decoration: underline;
            margin-top: 25px;
    }
   .alrdy_acc {
    margin: 25px auto;
    text-align: center;
    font-size: 14px;
    }
    .alrdy_acc p{margin-bottom: 0px;}
    @media (min-width: 1080px) and (max-width: 1366px) {
         .zipcode-container{
           margin-top: 40%;
        }
    }
	
	
@media only screen and (min-width: 320px) and (max-width: 479px) {
	.zipcode-container{margin-top: 12%;}
	.zipcode_bg {
	background:url('<?php echo base_url('public/assets/images/zipcode_bg.png');?>') no-repeat!important;
    height: 488px!important;
    overflow: hidden!important;
    background-position: center!important;
	}
	
    </style>
<link href='https://fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css'>

	</head>


<body class="zipcode_bg">


	<div class="container"> 
		<div class="col-md-5 col-md-offset-7"> 
			<div class="zipcode-container clearfix"> 
				 <div class="col-md-12 text-center">
                    <a href="<?php echo base_url('public/assets');?>" title="Go2Gro">
                        <div style="margin: 25px auto;"><img src="<?php echo base_url('public/assets/images/go2gro_logo.png?'.$this->config->item('Web_unique_url'));?>" alt="Go2Gro" width="240px"></div>
                    </a>
                </div>
				
				<div class="zipcode clearfix" id="zipcodevalset">
                    <div><?php alert();?></div>
                    <form  action="#" method="POST" id="change">
                        <div class="clearfix">
                            <div class="col-md-10 col-md-offset-1">
                                <div class="form-group">
                                    <input type="text" class="form-control pincode" name="pincode_value" id="pincode_value" placeholder="Enter Your Zip Code" >
                                </div>
                            </div>
                        </div>
                        <div class="col-md-10 col-md-offset-1 text-center ">
                            <button type="submit" class="btn btn-primary continue firststep cont_btn">Continue</button>
                        </div>
                    </form>
                </div>
				
				 <div class="zipcode2">
                    <h2 class="text-center fs-30 no-margin">Currently, we are not available in your area.</h2>
                    <!--<p>Be the first to order! Enter your email to be notified when go2grow is in your area.</p>-->
                    <h2 class="text-center fs-30 no-margin" ><a href="<?php echo base_url('public/assets/login');?>"><span style="color:green;"> Sign In Here</span></a></h2>
                </div>
				
                <!-- <div class="alrdy_acc"> <p> Already have an account? <a href="<?php //echo base_url('public/assets');?>login"> Sign in </a> </p></div>
 -->
                <div class="col-md-12 sub_heading"> 
                    <div> <p>Groceries & More At Your Door </p></div>
                </div>
				
			</div>
		</div>
	</div>

    <script type="text/javascript">
   
    jQuery(document).ready(function(){
        <?php if($isPincode==true || $isLogin==true){?>
        window.location.href="<?php echo base_url('home');?>";
        <?php } else { ?>
        jQuery('.zipcode2').hide();
        jQuery('.firststep').click(function () {
            var zipcode = jQuery('#pincode_value').val();
            if(zipcode=='')
            {
                swal("Please enter your Zip code");
                return false;

            }else if(isNaN(zipcode)||zipcode.indexOf(" ")!=-1)
            {
                swal("Please enter numeric value");
                return false;
            }
            else {
                jQuery.ajax({
                    type: 'GET',
                    url: '<?php echo base_url("checkZipcode"); ?>',
                    data: {'zipcode': zipcode},
                    success: function (res) {
                        console.log(res);
                        
                        if (res.error == true) {
                            jQuery('.zipcode2').show();
                            jQuery('.zipcode').hide();
                        }
                        else {
                            window.location.href= res.redirectto;
                        }

                    }
                });
            }
            return false;

        });

        <?php } ?>
    });
</script>
</body>
</html>

<?php
if($this->session->has_userdata('pincode')) {
    $isPincode = true;
    $pin_code=$this->session->userdata['pincode']['pincode'];
}else{
    $isPincode = false;
    $pin_code='';
}

$selected_store_name = "";
if($this->session->has_userdata('select_store_data')){
  $selected_store_name = $this->session->userdata['select_store_data']['storename'];
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
    $logged_user = $this->session->userdata('go2grouser');
    $userId = $logged_user['id'];
    $fname = $logged_user['first_name'];
    $lname = $logged_user['last_name'];
    $email_id = $logged_user['email_id'];
    $mobile = $logged_user['mobile'];
    $apikey=$logged_user['api_key'];
} else {
    $isLogin = false;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title><?php echo $this->config->item('title'); ?></title>
    <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=no">
    <meta name="title" content="New Grocery Delivery Service in State College, Pennsylvania.">
    <meta name="description" content="A cheapest online grocery ordering and home delivery system for the residents of State College, Pennsylvania. 'Groceries and More at your Door'.">
    <meta name="keywords" content="grocery stores that deliver near me, groceries delivery state college, food stores near me, cheap groceries online, grocery suppliers Pennsylvania, home delivery of groceries, online grocery shopping, food shopping online, grocery shop online">
    <meta name="robots" content="*">
    <meta name="viewport" content="initial-scale=1.0, width=device-width">
    <link rel="icon" href="<?php echo base_url();?>images/fevicon.png" type="image/x-icon">
    <link rel="shortcut icon" href="#" type="image/x-icon">
    <!-- CSS Style -->
    <?php load_css(array('public/assets/stylesheet/bootstrap.min.css?','public/assets/stylesheet/font-awesome.css?'));?>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">

    <!--forget password -->
    <?php load_css(array('public/assets/stylesheet/go2groresponsive.css?','public/assets/stylesheet/go2gro.css?',
        'public/assets/stylesheet/owl.carousel.css?','public/assets/stylesheet/owl.theme.css?',
        'public/assets/stylesheet/style.css?','public/assets/stylesheet/flexslider.css?',
        'public/assets/stylesheet/sticky/style.css?','public/assets/stylesheet/responsive.css?'));?>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">

    <?php load_css(array('public/assets/stylesheet/cloud-zoom.css?',
        'public/assets/css/paymentInfo.css?'));?>

    <!-- BEGIN GOOGLE ANALYTICS CODEs -->
    <link rel='stylesheet prefetch' href='https://cdn.rawgit.com/filamentgroup/fixed-sticky/master/fixedsticky.css'>
    <?php load_css(array('public/assets/sweetalertlib/dist/sweetalert.css?','public/assets/toaster/demos/css/jquery.toast.css?'));?>
    
    <!-- JavaScript -->
</head>

<body>
    <header class="main_header">
   <div class="header-banner">
      <div class="assetBlock">
         <?php if($isLogin==true){?>
         <div class="height20 flowhidden" id="slideshow">
            <p class="d-block">Welcome<span class="padleft5"> <?php echo ucfirst($fname)." ".ucfirst($lname);?> </span></p>
            <p class=""><a href="#">Order timings:<span class="padleft5"> <?php if($this->session->has_userdata('select_store_data'))
               {
                   $opentime = $this->session->userdata['select_store_data']['opening_time'];
                   $closetime = $this->session->userdata['select_store_data']['closing_time'];
                   echo $opentime .' - '.$closetime;
               }?> (Delivery within 2 hours)</span></a></p>
         </div>
         <?php } else{?>
         <p class="d-inlineblock text-white"><a href="<?php echo base_url('Login');?>">Sign up / Sign in Is Required</a></p>
         <!-- <p class="d-inlineblock text-white"><a href="<?php //echo base_url('login');?>">Sign up</a></p> -->
         <?php }?>
      </div>
   </div>
   <div id="header">
      <div class="header-container container-fluid ">
         <div class="row">
            <div class="logo">
               <a href="<?php echo base_url();?>" title="Go2Gro">
                    <img src="<?php echo base_url('public/assets/images/logo.png');?>" alt="Go2Gro" width="130px" />
                  </a>
            </div>
            <div class="fl-nav-menu">
               <nav>
                  <div class="nav-inner">
                     <?php if($this->session->has_userdata('select_store_data')){?>
                     <a href="<?php echo site_url('select_store/'.$pin_code);?>" class="store_icon"> <i  class="fa fa-map-marker-alt" aria-hidden="true"> </i> <?php echo $selected_store_name;?> </a>
                     <?php } ?>
                     <!--search bar start-->
                     <div class="<?php if($this->uri->segment(1) == "select_store"){ echo "header-change "; }else{ echo "header_search";}?>">
                        <div class="input-group">
                           <span class="input-group-btn">
                              <select class="form-control zipcode_show pincode_ival" id="pincode_ival" onchange="pincodecheck(this.value);">
                                 <option value="">Change zip code</option>
                              </select>
                           </span>
                           <?php if($this->uri->segment(1) == "select_store"){?>
                           <span  class="form-control responsive_ad order-delv-in">Delivery in <b> <?php echo $pin_code; ?> </b> </span>
                           <!-- </a> --> 
                           <?php } else{?>
                           <input class="form-control control-responsive searchbar" aria-label="Search" id="searchbar2" type="text" placeholder="Search.." value="<?php if(isset($_GET['q']))echo htmlentities($_GET['q']); ?>"
                              onkeydown="SearchEnter(event, this)">
                           <span class="input-group-btn"><button class="btn btn-secondary ser_btn" type="button" onclick="SearchEnter_byclick(event, 'searchbar2');"><i class="fa fa-search"></i> Search</button></span>
                           <?php } ?>
                        </div>
                     </div>
                     <!--search bar start-->
                  </div>
               </nav>
            </div>
            <!--row-->
            <div class="fl-header-right">
               <div class="fl-cart-contain">
                  <div class="mini-cart" id="main">
                     <div class="basket" onclick="openNav()" >
                        <a href="#">
                        <span id="basket_id" class="basket_id"> 0 </span> <b> My Cart </b></a>
                     </div>
                  </div>
               </div>
               <div class="fl-links bg-white">
                  <div class="no-js">
                     <span class="sing_in_up">
                     <?php if(!$this->session->has_userdata('go2grouser')){ ?>
                     <span class="user-signin"><i class="fas fa-user-circle"></i></span>
                     <a href="<?php echo base_url('Login');?>"> Sign In /</a>
                     <a href="<?php echo base_url('registration');?>"> Sign Up </a>
                     <?php }  ?>
                     </span>
                  </div>
               </div>
            </div>
         </div>
         <!--header2 start-->
         <div class="row">
            <div class="go2gro-menu">
               <div id="navbarsubmenu">
                  <ul class="margin-auto text-center">
                     <li class="nav-item">
                        <a class="nav-link shop" href="<?php echo site_url('select_store/'.$pin_code);?>"> Change Store</a>
                     </li>
                     <li class="nav-item">
                        <a href="<?php echo base_url();?>" class="nav-link">Home</a>
                     </li>
                     <li class="nav-item dropdown">
                        <a href="<?php echo base_url();?>" class="nav-link">Departments</a>
                     </li>
                     <?php if($this->session->has_userdata('go2grouser')){ ?>
                     <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="javascript:void(0)" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        My Account<i class="fas fa-chevron-down padleft5"></i>
                        </a>
                        <div class="dropdown-menu">
                           <a class="dropdown-item" href="<?php echo base_url();?>profile" title="My Profile">  My Profile </a>
                           <a class="dropdown-item"  href="<?php echo base_url();?>account" title="Wishlist"> Order History </a>
                           <a class="dropdown-item"  href="<?php echo base_url();?>ShoppingCart" title="Checkout"> My Cart </a>
                           <a class="dropdown-item" href="javascript:void(0);" title="Checkout" onclick ="invite_friend();"> Invite Friend</a>
                           <a class="dropdown-item"  href="<?php echo site_url('membership');?>" title="Membership Plan"> Membership Plan</a>
                           <a class="dropdown-item" onclick="logoutdata();" href="javascript:void(0);"  title="Log Out">Logout </a>
                        </div>
                     </li>
                     <?php } ?>
                     <li class="nav-item">
                        <a class="nav-link" href="<?php echo base_url();?>contact">Contact Us</a>
                     </li>
                  </ul>
               </div>
            </div>
         </div>
         <!--header 2 close-->
      </div>
   </div>
</header>



<!--responsive header start-->
<nav class="navbar navbar-inverse res_header">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#res_header_g2g">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>                        
      </button>
      
    <div class="text-right d-block fl-right">
        <div class="fl-cart-contain">
          <div class="mini-cart" id="main">
             <div class="basket" onclick="openNav()" >
                <a href="#">
                <span id="basket_id" class="basket_id"> 0 </span> <b> My Cart </b></a>
             </div>
          </div>
        </div>  
        
        
        <div class="fl-links">
          <div class="no-js">
             <span class="sing_in_up nopad-right" >
             <?php if(!$this->session->has_userdata('go2grouser')){ ?>
             <span class="user-signin"><i class="fas fa-user-circle"></i></span>
             <a href="<?php echo base_url('Login');?>"> Sign In /</a>
             <a href="<?php echo base_url('registration');?>"> Sign Up </a>
             <?php }  ?>
             </span>
          </div>
       </div>
    </div>
    
    
      
    <a class="navbar-brand pd-top7" href="<?php echo base_url();?>" title="Go2Gro">
        <div class="logo">
            <img src="<?php echo base_url('public/assets/images/logo.png');?>" alt="Go2Gro" width="110px">
        </div>
      </a>
    </div>
    <div class="collapse navbar-collapse1" id="res_header_g2g">
        <ul class="nav navbar-nav">
            <li class="active">
                <div class="nav-inner <?php if($this->session->has_userdata('select_store_data')){ echo "height100" ;} else{ echo "height50";}?>">
                     <?php if($this->session->has_userdata('select_store_data')){?>
                     <a href="<?php echo site_url('select_store/'.$pin_code);?>" class="store_icon"> <i  class="fa fa-map-marker-alt" aria-hidden="true"> </i> <?php echo $selected_store_name;?> </a>
                     <?php } ?>
                     <!--search bar start-->
                     <div class="<?php if($this->uri->segment(1) == "select_store" && !$this->session->has_userdata('select_store_data')){ echo "header-change_rsmodify"; }else{ echo "header-change header_search";} ?>">
                        <div class="input-group">
                           <span class="input-group-btn">
                              <select class="form-control zipcode_show pincode_ival" id="pincode_ival" onchange="pincodecheck(this.value);">
                                 <option value="">Change zip code</option>
                              </select>
                           </span>
                           <?php if($this->uri->segment(1) == "select_store"){?>
                           <span class="form-control order-delv-in responsive_ad">Delivery in <b> <?php echo $pin_code; ?> </b> </span>
                           <!-- </a> --> 
                           <?php } else{?>
                           <input class="form-control control-responsive searchbar" aria-label="Search" id="searchbar" type="text" placeholder="Search.." value="<?php if(isset($_GET['q']))echo htmlentities($_GET['q']); ?>"
                              onkeydown="SearchEnter(event, this)">
                           <span class="input-group-btn"><button class="btn btn-secondary ser_btn" type="button" onclick="SearchEnter_byclick(event, 'searchbar');"><i class="fa fa-search"></i> </button>  </span>
                           <?php } ?>
                        </div>
                     </div>
                     <!--search bar start-->
                </div>
            </li>
        
            <li class="nav-item">
                <a class="nav-link shop" href="<?php echo site_url('select_store/'.$pin_code);?>"> Change Store</a>
            </li>
            <li class="nav-item">
                <a href="<?php echo base_url();?>" class="nav-link">Home</a>
            </li>
            <li class="nav-item dropdown">
                <a href="<?php echo base_url();?>" class="nav-link">Departments</a>
            </li>
            <?php if($this->session->has_userdata('go2grouser')){ ?>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="javascript:void(0)" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">My Account<i class="fas fa-chevron-down padleft8"></i>
                </a>
                <ul class="dropdown-menu">
                   <li> <a class="dropdown-item" href="<?php echo base_url();?>profile" title="My Profile">  My Profile </a> </li>
                   <li><a class="dropdown-item"  href="<?php echo base_url();?>account" title="Wishlist"> Order History </a></li>
                   <li><a class="dropdown-item"  href="<?php echo base_url();?>ShoppingCart" title="Checkout"> My Cart </a></li>
                   <li><a class="dropdown-item" href="javascript:void(0);" title="Checkout" onclick ="invite_friend();"> Invite Friend</a></li>
                   <li><a class="dropdown-item"  href="<?php echo site_url('membership');?>" title="Membership Plan"> Membership Plan</a></li>
                   <li><a class="dropdown-item" onclick="logoutdata();" href="javascript:void(0);"  title="Log Out">Logout </a></li>
                </ul>
            </li>
            <?php } ?>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo base_url();?>contact">Contact Us</a>
            </li>
        </ul>
    </div>
  </div>
</nav>
<!--responsive header close-->
   <div id="mySidenav" class="sidenav">
   <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
   <div id="minicartview"> </div>
</div>
<div class="modal-overlay"></div>
<!-- Invite friend Modal -->
<div id="invitefrndmodal" class="modal opec_one fade" role="dialog">
   <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content overflowauto">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Invite Friend</h4>
         </div>
         <div class="modal-body">
            <div class="clearfix">
               <div class="col-md-12 invite_bg">
                  <img src="<?php echo base_url();?>/public/assets/images/refel_g2gro_logo.png"  />
                  <h3> Give $5 , Get $5</h3>
                  <p> For every friend who places their first order </p>
               </div>
               <div class="col-md-12 reward-text">
                  <h5 id="title_msg"> You are yet to earn your reward. </h5>
               </div>
               <div class="col-md-12 reward-text">
                  <div class="col-md-8 col-sm-12">
                     <p class="fs-16 bold" id="sharelink_users">Shared your link</p>
                     <a href="<?php echo base_url(); ?>terms" target="_blank"  class="green-text"> VIEW TERMS</a> 
                  </div>
                  <div class="col-md-4 col-sm-12 clearfix">
                     <div class="pull-right">
                        <a href="javascript:void(0);" class="link_copy"  id="link_copy"> <i class="fa fa-clone" aria-hidden="true"></i></a>
                        <span id="copy_msg_show" class="copy_msg_show" >Copied</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-12 share_link">
                  <ul>
                     <a href="" target="_blank" id="fblink" class="clearfix fb_bg"><li class="">  <i class="fab fa-facebook-f"></i></li></a>
                     <a href="" target="_blank" id="twtlink" class="clearfix tw_bg"><li class="">  <i class="fab fa-twitter"></i> </li></a>
                     <a href="" target="_blank" id="lkdnlink" class="clearfix ln_bg"><li class="">  <i class="fab fa-linkedin"></i> </li></a>
                     <a href="" target="_blank" id="whatsapplink" class="clearfix ws_bg"><li class="">  <i class="fab fa-whatsapp"></i> </li></a>
                     <a href="" target="_blank" id="instlink" class="clearfix lnsta_bg"><li class="">  <i class="fab fa-instagram"></i> </li></a>
                     <a href="" target="_blank" id="gpluslink" class="clearfix gp_bg"><li class="">  <i class="fab fa-google-plus-g"></i> </li></a>
                  </ul>
               </div>
            </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
         </div>
      </div>
   </div>
</div>
<?php 
if(isset($_GET['v'])){
	$codeval=$_GET['v'];

$code=substr($codeval,-6);
$user_id= substr($codeval,0,-7);

}
else
{
	 redirect('invalid', 'refresh');
}


?>


<!DOCTYPE>
<html xmlns="https://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<title>Go2Gro</title>
<!-- must have -->
<link href="<?php echo base_url();?>public/forgotpassword/css/bannerscollection_zoominout.css" rel="stylesheet" type="text/css">
<link href='https://fonts.googleapis.com/css?family=Droid+Sans' rel='stylesheet' type='text/css'>
<link href='https://fonts.googleapis.com/css?family=Lato:400,700,900,700italic,900italic' rel='stylesheet' type='text/css'>
<link rel="icon" href="<?php echo base_url();?>public/forgotpassword/images/fevicon.png" type="image/x-icon">
    <link rel="shortcut icon" href="#" type="public/forgotpassword/image/x-icon">
<style type="text/css">
body {
width:100%;
height:100%;
margin:0;
padding:0;
overflow-x:hidden;
}
 .label.error {
            color:red !important;
        }
        .label.valid {
            color:green !important;
        }
       .forget-passcontainer{
        height: auto!important;
       }
</style>
<link rel = "stylesheet" href = "<?php echo base_url();?>public/forgotpassword/css/flexBackground.css"/>
	
<!-- must have -->





	
	
</head>

<body bgcolor="#cccccc">

	<!--<div class = "flexBackground">
		<canvas class = "canvasBackground">
		</canvas>
	</div>-->


            <div id="">
            	<div class="myloader"></div>
				<div class="overlay"> </div>
                <!-- CONTENT -->
                <!--<ul class="bannerscollection_zoominout_list">
               		<li data-initialZoom="0.77" data-finalZoom="1" data-horizontalPosition="center" data-verticalPosition="center"data-bottom-thumb="images/bg-1.jpg" data-text-id="#bannerscollection_zoominout_photoText1" data-autoPlay="10"><img src="images/bg-1.jpg" alt="" width="2500" height="1570" /></li>
                    
                    <li data-initialZoom="0.77" data-finalZoom="1" data-horizontalPosition="left" data-verticalPosition="top" data-bottom-thumb="images/bg-2.jpg" data-text-id="#bannerscollection_zoominout_photoText2" data-link="https://codecanyon.net/user/LambertGroup" data-target="_blank"><img src="images/bg-2.jpg" alt="" width="2500" height="1570" /></li>
                    
                    <li data-horizontalPosition="right" data-verticalPosition="center" data-initialZoom="0.77" data-finalZoom="1" data-bottom-thumb="images/bg-3.jpg"><img src="images/bg-3.jpg" alt="" width="2500" height="1570"  /></li>
                    
                    <li data-horizontalPosition="left" data-verticalPosition="top" data-initialZoom="1" data-finalZoom="0.77" data-bottom-thumb="images/bg-4.jpg" data-text-id="#bannerscollection_zoominout_photoText4"><img src="images/bg-4.jpg" alt="" width="2500" height="1570"  /></li>
                    
                    <li data-horizontalPosition="center" data-verticalPosition="center" data-initialZoom="1" data-finalZoom="0.77" data-duration="15" data-bottom-thumb="images/bg-5.jpg" data-text-id="#bannerscollection_zoominout_photoText5"><img src="images/bg-5.jpg" alt="" width="2500" height="1570" /></li>
                </ul> -->
                
                
				
				<div class="forget-passcontainer" id="bannerscollection_zoominout_photoText1111"> 
					<div class="logo"> 
						<img src="public/forgotpassword/images/logo_01.png" />
					</div>
					<div class="row wow bounceInleft" >
						<div class="col-md-4">
						<p class="heading">Welcome as per your change password request please enter new password for your account</p>
						<div class="cover-signin">
							<p style="font-size:20px;">Forget Password</p>
							<form  name="fgorgetform" id="forget_form" action="javascript:void(0);" method="POST" >
								<div class="form-group">
								  <label for="pwd">Password:</label>
								  <input type="password"  class="form-control" id="pwd11" name="pwd" placeholder="Enter password">
								</div>
								<div class="form-group">
								  <label for="cpwd">Confirm Password:</label>
								  <input type="password"   class="form-control" id="cpwd11" name="cpwd" placeholder="confirm password">
								</div>
								<div class="text-center">
								<button class="submit-btn" type="submit" id="submit11" name="submit">Submit</button>
								 <!-- class="submit-btn" type="submit" id="submit11" name="submit">Submit</button> -->
								</div>
							</form>
						</div>
					</div>
					</div>
				</div> 
           </div>  



</body>
</html>
<script type = "text/javascript" src = "<?php echo base_url();?>public/forgotpassword/js/jquery.min.js"></script>
	<script type = "text/javascript" src="<?php echo base_url();?>public/forgotpassword/js/flexBackground2.js"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
<script src="<?php echo base_url();?>public/forgotpassword/js/jquery.ui.touch-punch.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>public/forgotpassword/js/bannerscollection_zoominout.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>public/forgotpassword/js/jquery.validate.min.js"></script>
  <script>
    var ua = navigator.userAgent.toLowerCase();
    if (ua.indexOf("msie") != -1 || ua.indexOf("opera") != -1) {
      jQuery('body').css('overflow','hidden');
      jQuery('html').css('overflow','hidden');
    }       
    
    jQuery(function() {
      jQuery('#bannerscollection_zoominout_opportune').bannerscollection_zoominout({
        skin: 'opportune',
        responsive:true,
        width: 1920,
        height: 1200,
        width100Proc:true,
        height100Proc:true,
        fadeSlides:1,
        showNavArrows:true,
        showBottomNav:true,
        autoHideBottomNav:true,
        thumbsWrapperMarginTop: -55,
        pauseOnMouseOver:false
      });   
      
    });

    
  </script>
  
  <script type= "text/javascript">
  $(document).ready(function(){
    $(".flexBackground").flexBackground({numberOfPoints:'700',
    radius:'2',
    interval : '50',
    color : 'rgb(256, 256, 256)' //Only In RGB format. don't use #hex or any other color format
    });
})
  </script>
    

  
<script>



	    jQuery("#forget_form").validate({

        // Specify the validation rules
        rules: {

            
            pwd : {
                required: true,
                // minlength : 5,
                // maxlength : 8,

            },

            cpwd : {
                required: true,
                // minlength : 5,
                // maxlength : 8,
                equalTo : "#pwd11"
            }


        },


        // Specify the validation error messages
        messages: {

            pwd: {
                required: "Password must be filled out!",
                // minlength: "Your password must be at least 12 characters long"
            },
            cpwd: {
                required: "Confirm Password must be filled out!",
                // minlength: "Your password must be at least 12 characters long",
                equalTo : "Password and Confirm Password Does Not Match!"
            },

        },

        submitHandler: function(form) {
        var  userid = '<?php echo $user_id;?>';
            var password=jQuery('#pwd11').val();
            
            jQuery.ajax({
                type:'POST',
                url:'<?php echo base_url();?>updatenewpassword',
                data:{'password': password, 'userid':userid},
                success:function(res){
                  if(res.error==true){
                     alert(res.message);
                    }else{
                   console.log(res);
                    window.location.href = '<?php echo base_url();?>confirm';
               }

}
});
            return false;

        }
    });
	    

forgetPassword();
	function forgetPassword(){
		var code='<?php echo $code;?>';
		var user_id='<?php echo $user_id;?>';
        jQuery.ajax({
			method:"POST",
			url:"<?php echo base_url();?>forgetpasswordcheckvalid",
			data:{"code":code,"userid":user_id},
			success:function(res){
				if(res.error==true)
        {
            window.location.href="<?php echo base_url();?>invalid";
        }else{

        }
				}
			});
	}
	  

</script>
<?php include 'header.php';
load_css(array('public/assets/stylesheet/pgstyle/store.css?'));
?>
<!--breadcrumbs-->
<!-- BEGIN Main Container col2-left -->
<section class="main-container col2-left-layout bounceInUp animated top-spacestor">
            <div class="row mart87">
                <div class="col-md-12 text-center">
                  <h1 class="store-head"> Stores Near You</h1>
                </div>
            </div>
			
 <div class="row res_mar_none no-pad no-mar">
  <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
    <!-- Wrapper for slides -->
	<div class="container">
    <div class="carousel-inner" role="listbox">
      <div class="item active">
        <img src="https://www.go2gro.com/go2gro_beta/images/slider1.jpg" alt="" class="lazy img-responsive post-image">
      </div>
	  </div>
    </div>
    <!-- Controls -->
</div>
 </div>
    <div class="man-section">
        <div class="container">
            <div class="row store-blockcliarfix">
                <div class="block-brands clearfix">
                    <?php
                    if(isset($stores) && $stores!=''){
                    foreach ($stores as $key => $store_value) {
                        $workingday = json_decode($store_value['working_daytime'],true);
                        foreach ($workingday as $work_key => $day_time_value) {
                            if(ucfirst($work_key)==date('l') && ($day_time_value['opening_time']=='closed' || $day_time_value['closing_time']=='closed')){
                                //$action_url = 'javascript:void(0)';
                                $action_url = site_url('user_store/'.$store_value['id']);
                                //$class = 'show_msg';
                                $class = '';
                                $working_time = ucfirst($day_time_value['opening_time']);
                                break;
                            }
                            elseif(ucfirst($work_key)==date('l')){
                                $action_url = site_url('user_store/'.$store_value['id']);
                                $class='';
                                $working_time = $day_time_value['opening_time'].'-'.$day_time_value['closing_time'];
                                break;
                            }
                            else{
                                $action_url = 'javascript:void(0)';
                                $class='';
                                $working_time ='';
                            }                                       
                       }
                                
                    ?>
                    <div class="col-xs-12 col-lg-3 col-md-6 col-centered ">
                        <div class="<?php if($selected_store_id == $store_value['id']){ echo "select-shadow";} ?>" style="background:#fff;padding-top:1px;margin-bottom:15px;">
                        <a href="<?php echo $action_url;?>" class="<?php echo $class;?>">
                            <div class="block-in text-center">
                                <div class="block-into">
                                    <img src="<?php echo $this->config->item('api_img_url').$store_value['logo']; ?>">
                                </div>
                                <div class="block-in-btn text-center">
                                    <span><?php echo $store_value['name'];?></span>
                                    <p><?php echo $working_time;?> </p>
                                    <p>Delivery fee - $<?php echo $store_value['delivery_charge'];?></p>
                                </div>
                            </div>
                        </a>
                      </div>
                    </div>
                <?php } }else{
                    echo '<div class="text-center store-font">'.$stores['message'].'</div>';
                } ?>
				
                </div>
            </div>

         </div>
    </div>
	
	<section class="section-padding bg-white border-top section-margin">
         <div class="container">
            <div class="row">
               <div class="col-lg-4 col-sm-6 col-xs-12">
                  <div class="feature-box">
                     <i class="mdi mdi-truck-fast"><i class="fas fa-truck-moving"></i></i>
                     <h6>Quality & Freshness Gaurantee</h6>
                  </div>
               </div>
               <div class="col-lg-4 col-sm-6 col-xs-12">
                  <div class="feature-box">
                     <i class="mdi mdi-basket"><i class="fas fa-shopping-basket"></i></i>
                     <h6>100% Satisfaction </h6>
                  </div>
               </div>
               <div class="col-lg-4 col-sm-6 col-xs-12">
                  <div class="feature-box">
                     <i class="mdi mdi-tag-heart"><i class="fas fa-tags"></i></i>
                     <h6>Great Daily Deals Discount</h6>
                  </div>
               </div>
            </div>
         </div>
      </section>

</section>
<!--main-container col2-left-layout-->


<link href='https://fonts.googleapis.com/css?family=Trocchi' rel='stylesheet'>
<?php
load_js(array('public/assets/js/home_js/masonry.pkgd.min.js?'));
?>

<?php include 'footer.php' ?>
</body>
</html>

<!-- For version 1,2,3,4,6 -->
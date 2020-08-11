<?php include 'header.php'; 
if($this->session->has_userdata('select_store_data')){
    $banner_slider_img = $this->config->item('api_img_url').$this->session->userdata['select_store_data']['banner'];
    //$banner_slider_img = 'https://www.go2gro.com/go2gro_beta/go2growApi/upload/stores/weis_bg.png';
}else{
    $banner_slider_img = base_url('images/weis_bg.png');
}
load_css(array('public/assets/stylesheet/pgstyle/home.css?'));
?>


<div class="slideshow-container">
    <div class="mySlides fade1 res-mar">
        <img src="<?php echo $banner_slider_img; ?>">
    </div>
    <div class="text-center">
        <span class="dot" onclick="currentSlide(1)"></span>
    </div>
</div>
<script>
    var slideIndex = 1;
    showSlides(slideIndex);

    function plusSlides(n) {
        showSlides(slideIndex += n);
    }

    function currentSlide(n) {
        showSlides(slideIndex = n);
    }

    function showSlides(n) {
        var i;
        var slides = document.getElementsByClassName("mySlides");
        var dots = document.getElementsByClassName("dot");
        if (n > slides.length) {
            slideIndex = 1
        }
        if (n < 1) {
            slideIndex = slides.length
        }
        for (i = 0; i < slides.length; i++) {
            slides[i].style.display = "none";
        }
        for (i = 0; i < dots.length; i++) {
            dots[i].className = dots[i].className.replace(" active", "");
        }
        slides[slideIndex - 1].style.display = "block";
        dots[slideIndex - 1].className += " active";
    }
    var myIndex = 0;
    carousel();

    function carousel() {
        var i;
        var x = document.getElementsByClassName("mySlides");
        for (i = 0; i < x.length; i++) {
            x[i].style.display = "none";
        }
        myIndex++;
        if (myIndex > x.length) {myIndex = 1}
        x[myIndex-1].style.display = "block";
        setTimeout(carousel, 5000); // Change image every 2 seconds
    }
</script>

<!--breadcrumbs-->

<!-- BEGIN Main Container col2-left -->
<section class="main-container col2-left-layout bounceInUp animated">
    <!-- For version 1, 2, 3, 8 -->
    <!-- For version 1, 2, 3 -->
    <div class="container">
        <div class="row">
            <div class="col-main col-sm-12 product-grid home-spacetop">
                <div class="pro-coloumn them-bg">
                    <article class="col-main home-colman">
                    <?php if(count($category) > 0) { ?>
                        <div id="page-contents">
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-12">
                                        <!-- Media========================================== -->
                                        <div class="media">

                                            <div class="row js-masonry" data-masonry='{ "itemSelector": ".grid-item", "columnWidth": ".grid-sizer","percentPosition": true,  "transitionDuration": "0.3s" }'>
                                                <div class="grid-sizer col-md-2 col-sm-4 col-xs-6"></div>
                                            
                                                <?php foreach($category as $cat) {
                                                        //----------21-jan-19-----
                                                        if($cat['disclaimer']!=""){
                                                            $href = "javascript:void(0);";
                                                            $onclick = "DisclaimerPopup(event,'".ucfirst($cat['disclaimer'])."','".$cat['id']."')";
                                                        }else{
                                                            $href = base_url('getlistitem?id='.$cat['id']);
                                                            $onclick ="";
                                                        }
                                                    ?>
                                                <div class="grid-item col-md-2 col-sm-4 col-xs-4">
                                                    <div class="media-grid">
                                                        <div class="img-wrapper">
                                                            <div class="item-img">
                                                                <div class="col-md-12">
                                                                    <a href="<?php echo $href;?>" title="<?= ucfirst($cat['cat_name']) ?>" class="" onclick="<?php echo $onclick;?>">
                                                                            <img src="<?php echo $this->config->item('api_img_url').$cat['image'];?>" alt="" class="lazy img-responsive post-image" />
                                                                    </a>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 no-pad">
                                                                <a class="pro-name" href="<?php echo $href;?>" onclick="<?php echo $onclick;?>"><?= ucfirst($cat['cat_name']); ?></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>  
                                                <?php } ?>     
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
<?php } ?>

                    </article>
                </div>
                <!--	///*///======    End article  ========= //*/// -->
            </div>

            <!--col-right sidebar-->
        </div>
        <!--row-->
    </div>
    <!--container-->
</section>
<!--main-container col2-left-layout-->


<link href='https://fonts.googleapis.com/css?family=Trocchi' rel='stylesheet'>

<div id="page-contents">
    <div class="container">
        <div class="row">
            <div class="col-md-12" >

                <div class="media" id="cat_product_grid1">
				</div>
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
<?php include 'footer.php' ?>
</body>
</html>
<!-- For version 1,2,3,4,6 -->


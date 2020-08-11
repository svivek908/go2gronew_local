<?php
include 'header.php'
$subid=$_GET['id'];
?>
    <div class="slideshow-container">

        <div class="mySlides fade1">
            <div class="numbertext">1 / 3</div>
            <img src="<?php echo base_url();?>images/slide-img2.png" style="width:100%">
        </div>

        <div class="mySlides fade1">
            <div class="numbertext">2 / 3</div>
            <img src="<?php echo base_url();?>images/slide-img1.png" style="width:100%">
        </div>

        <div class="mySlides fade1">
            <div class="numbertext">3 / 3</div>
            <img src="<?php echo base_url('public/assets/images/slide-img1.png');?>" style="width:100%">
        </div>

        <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
        <a class="next" onclick="plusSlides(1)">&#10095;</a>
        <br>

        <div style="text-align:center">
            <span class="dot" onclick="currentSlide(1)"></span>
            <span class="dot" onclick="currentSlide(2)"></span>
            <span class="dot" onclick="currentSlide(3)"></span>
        </div>

    </div>
    <!--breadcrumbs-->

    <!-- BEGIN Main Container col2-left -->
    <section class="main-container col2-left-layout bounceInUp animated">
        <!-- For version 1, 2, 3, 8 -->
        <!-- For version 1, 2, 3 -->
        <div class="container">
            <div class="row">
                <div class="col-main col-sm-9 product-grid">
                    <div class="pro-coloumn">
                        <div class="block">
                            <div class="block-title"> Sub DepartMent </div>
                        </div>
                        <article class="col-main">
                            <div class="category-products" id="cat_products">
                                <ul class="products-grid" id="cat_product_grid1">

                                </ul>
                            </div>
                        </article>
                    </div>
                    <!--	///*///======    End article  ========= //*/// -->
                </div>
                <?php include('right_sidebar.php');?>
                <!--col-right sidebar-->
            </div>
            <!--row-->
        </div>
        <!--container-->
    </section>
    <!--main-container col2-left-layout-->
    <section class=" wow bounceInUp animated">
        <div class="best-pro slider-items-products container">
            <div class="new_title">
                <h2>Best Seller</h2>
            </div>
            <div id="best-seller" class="product-flexslider hidden-buttons">
            </div>
        </div>
    </section>

<?php include 'footer.php' ?>
<script>
    getSubcategory1()

    function getSubcategory1() {

var subId=<?php echo $subid;?>;

        var api_key='<?php echo $apikey;?>';

        jQuery.ajax({
            type: 'GET',
            url: '<?php echo base_url("index.php/main/getSubcategory1"); ?>',
            data: {'subId': subId,
                'Authorization':api_key },
            success: function (res) {
                console.log(res);
if(res.error==true){
    sweetAlert("", res.message, "warning");

}else{
    var catList = res.subcategory;
    var jsonLength = catList.length;
    console.log(jsonLength);
    var html = '';
    for (var i = 0; i < jsonLength; i++) {
        var result = catList[i];
        html += '<li class="item col-lg-3 col-md-3 col-sm-4 col-xs-6">\
                             <div class="item-inner">\
                            <div class="item-img">\
                            <div class="item-img-info">\
                            <a href="<?php echo base_url();?>getlistitem?id='+result.sub_id+'" title="Retis lapen casen" class="product-image"><img src="<?php echo $this->config->item('api_img_url');?>' + result.sub_image + '" alt="Retis lapen casen">\
                            </a>\
                            </div>\
                            </div>\
                            <div class="item-info">\
                            <div class="info-inner">\
                            <div class="item-title"><a href="<?php echo base_url();?>getlistitem?id='+result.sub_id+'" title="Retis lapen casen">' + result.sub_name + '</a> </div>\
                            </div>\
                            </div>\
                            </div>\
                            </li>';
    }
    jQuery('#cat_product_grid1').append(html);

}
//                    var jsonData = JSON.parse(data);



            },

            error: function (textStatus, errorThrown) {
               //doesnt goes here
               window.location.href="<?php echo base_url();?>login"
            }

        });

    }

    getbestSeller();
</script>
</body>
</html>

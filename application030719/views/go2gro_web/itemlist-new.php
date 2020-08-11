<?php include 'header.php';
$id=$_GET['id'];
?>

<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.1.1/assets/owl.carousel.min.css">
       <link rel="stylesheet" href="http://themes.audemedia.com/html/goodgrowth/css/owl.theme.default.min.css">
	   <script src='https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.1.1/owl.carousel.min.js'></script> -->
<?php load_css(array('public/assets/stylesheet/slick.min.css?'));
?>

<div class="pagetop">
</div>
<!--breadcrumbs-->

<!-- BEGIN Main Container col2-left -->
<section class="main-container col2-left-layout bounceInUp animated">
    <!-- For version 1, 2, 3, 8 -->
    <!-- For version 1, 2, 3 -->
	<section style="margin-top: 140px;background: #fff;padding: 15px;"> 
		<div class="container">
		
		
       <div class="row" style=""> 
       <!-- TESTIMONIALS -->
    <section class="testimonials">
	<div class="container">
      <div class="row">
        <div class="col-sm-12">
          <div id="customers-testimonials" class="owl-carousel">
            <!--TESTIMONIAL 1 -->
            <!--<div class="item">
              <div class="shadow-effect">
                <img class="img-responsive" src="https://image.freepik.com/free-photo/spaghetti-with-carbonara-sauce_1216-324.jpg" alt="">
              
              </div>
            </div>-->
			<?php foreach($categories as $cat){
				if($cat['disclaimer']!=""){
					$href = "javascript:void(0);";
					$onclick = "DisclaimerPopup(event,'".ucfirst($cat['disclaimer'])."','".$cat['id']."')";
				}else{
					$href = base_url('getlistitem?id='.$cat['id']);
					$onclick ="";
				} ?>
				<div class="item">
					<div class="shadow-effect product_slider">
						<a href="<?php echo $href;?>" title="<?= ucfirst($cat['cat_name']) ?>" onclick="<?php echo $onclick;?>">
							<span><img src="<?php echo $this->config->item('api_img_url').$cat['image'];?>" alt="" class="img-responsive post-image" /></span>
						</a>
						<p class="fs-16"><a class="pro-name" href="<?php echo $href;?>" onclick="<?php echo $onclick;?>"><?= ucfirst($cat['cat_name']) ?></a></p>
						<!-- <p class="fs-13">105 Item</p> -->
					</div>
				</div>
                <?php } ?>
            <!--END OF TESTIMONIAL 1 -->
            <!--TESTIMONIAL 2 -->
			<?php /*?>
            <div class="item">
              <div class="shadow-effect">
                <img class="img-responsive" src="https://image.freepik.com/free-photo/dishes-with-healthy-waffles_1220-367.jpg" alt="">
                
              </div>
            </div>
            <!--END OF TESTIMONIAL 2 -->
            <!--TESTIMONIAL 3 -->
            <div class="item">
              <div class="shadow-effect">
                <img class="img-responsive" src="https://image.freepik.com/free-photo/top-view-of-tasty-noodles-with-prawns_1203-1769.jpg" alt="">
               
              </div>
            </div>
            <!--END OF TESTIMONIAL 3 -->
            <!--TESTIMONIAL 4 -->
            <div class="item">
              <div class="shadow-effect">
                <img class="img-responsive" src="https://image.freepik.com/free-photo/burguer-with-garnish_1088-72.jpg" alt="">
               
              </div>
            </div>
            <!--END OF TESTIMONIAL 4 -->
            <!--TESTIMONIAL 5 -->
            <div class="item">
              <div class="shadow-effect">
                <img class="img-responsive" src="https://image.freepik.com/free-photo/delicious-pastry-with-chicken_1203-1616.jpg" alt="">
              
              </div>
            </div> <?php */ ?>
            <!--END OF TESTIMONIAL 5 -->
          </div>
        </div>
      </div>
      </div>
    </section>
  
    <!-- END OF TESTIMONIALS -->

      </div>
		</div>
	</section>
    <div class="container">
        <div class="row">
            <div class="col-main col-sm-12 product-grid" style="margin-top:50px;">
                <div id="sticky-anchor"></div>
                <div class="category-navigation res_modify" style="height:52px;background:#ececec; color:#333; line-height: 40px; border: solid 1px #e0e0e0;" id="sticky">
                    <div class="col-md-12 heroSlider-fixed">
                        <div class="overlay"></div>
                        <!-- Slider -->
                        <div class="slider responsive cat-navigation" id="category_id">
                        </div>
                        <!-- control arrows -->
                        <div class="prev">
                            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> 
                        </div>
                        <div class="next">
                            <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                        </div>
                    </div>
                </div>

                <div class="pro-coloumn">
                    <article class="col-main" style="margin:0px;">
                        <div class="category-products no-pad">
                            <div >
                                <div id="products_grid_item" >
                                </div>
                            </div>
                        </div>
                        <!--div class="loader hidden" id="loaderprofile1"></div-->
                    </article>
                </div>
            </div>
        </div>
        <!--row-->
    </div>
    <!--container-->
</section>
<!--main-container col2-left-layout-->
<?php include 'footer.php'; ?>
<script>
    var headersubid = 0; //for change header
    var items;
    var activeSubid = 0;
    var processing;    // for scroller stop
    var pagenoincr = 0;
    jQuery(document).ready(function(){
        departmentid(pagenoincr);
        jQuery(document).scroll(function(e){
            sticky_relocate();
            if (processing){
                //console.log(processing);
                return false;
            }

            if (jQuery(window).scrollTop() >= (jQuery(document).height() - jQuery(window).height())*0.85){
                processing = true;
                pagenoincr = pagenoincr+1;
                if(activeSubid ==0){
                    getlistalldepartment(0, pagenoincr);
                }else{
                    getlistbydeptid(activeSubid, pagenoincr, '', false);
                }

            }
        });
    });
    function sticky_relocate() {
        var window_top = jQuery(window).scrollTop();
        var div_top = jQuery('#sticky-anchor').offset().top;
        //console.log(window_top+'>'+div_top);
        if (window_top > div_top) {
            jQuery('#sticky').addClass('stick');
            jQuery('#sticky').addClass('stik_head_wid');
            jQuery('#sticky-anchor').height(jQuery('#sticky').outerHeight());
        } else {
            jQuery('#sticky').removeClass('stik_head_wid');
            jQuery('#sticky').removeClass('stick');
            jQuery('#sticky-anchor').height(0);
        }
    }

    function sliderslic(){
        jQuery('.slick').slick("unslick");
        jQuery('.responsive').slick({
            dots: false,
            prevArrow: jQuery('.prev'),
            nextArrow: jQuery('.next'),
            infinite: false,
            speed: 800,
            slidesToShow:2,
            slidesToScroll:1,
            variableWidth: true,
            responsive: [
                {
                    breakpoint: 624,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 3,
                        infinite: true,
                        dots: false
                    }
                },
                {
                    breakpoint: 400,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 2
                    }
                },
                {
                    breakpoint: 380,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }

                // You can unslick at a given breakpoint now by adding:
                // settings: "unslick"
                // instead of a settings object
            ]
        });
    }

    function departmentid(pageno){
        var listId=<?php echo $id;?>;
        var subcatid = 0;  //cat_id=0 for all;
        jQuery('#loaderprofile1').removeClass('hidden');
        var api_key='sdfsdfdfsdf';
        pagenoincr =pageno;
        var pageno = pageno;
        var unitime = 0;
        jQuery.ajax({
            type: 'GET',
            url: '<?php echo base_url(); ?>departmentitemid_new',
            data: {"listId": listId , "catid":subcatid, "pageno":pageno, "unitime":unitime},
            beforeSend: function ()
            {
                ajaxindicatorstart('Loading...');
            },
            success: function (res) {
              ajaxindicatorstop();
                var subcatid = res.subid;
                if(res.error){

                }else{
                    jQuery('#loaderprofile1').addClass('hidden');
                    items = res.items;
                    var subCatList = res.subcategory;
                    var jsonLength = subCatList.length;
                    console.log(jsonLength);
                    var html = '';
                    var count = 1;
                    for (var i = 0; i < jsonLength; i++) {
                        count = count + 1;
                        var result = subCatList[i];
                        var catname = result.sub_name;
                        var classActive = '';
                        var cat_id = result.sub_id;
                        var taboption;
                        if(i == 0){
                            console.log(i);
                            cat_id = 0;
                            catname = 'All';
                            classActive = 'slick-activate';
                            taboption = '<a href="javascript:void(0)" onclick="getlistalldepartment('+cat_id+', 0, true)" > ' + catname + '</a>';
                        }else{
                            classActive = '';
                            taboption = '<a href="javascript:void(0)" onclick="getlistbydeptid('+cat_id+', 0, \''+catname+'\', true)" > ' + catname + '</a>';
                        }
                        html += '<div class="'+classActive+'" id="subid_'+cat_id+'">\
                            '+taboption+'\
                            </div>';
                    }
                    jQuery('#category_id').html(html);
                    function newW()
                    {
                        jQuery(".cover").fadeOut(200);
                    }
                    setTimeout(newW, 500);
        
                    sliderslic();
                    departmentwiseData();

                }
            }
        });
    }

    function departmentwiseData(){
        var count = 1;
        var html2='';
        for (var i = 0; i < items.length; i++) {
            count = count + 1;
            var itemlistcatwise = items[i];
                if(itemlistcatwise.subcat_id != headersubid){
                    headersubid = itemlistcatwise.subcat_id;
                   var html = '<div class="item_subheader" ><p>'+itemlistcatwise.sub_name+'</p></div>\
            <ul id="headersubid_'+itemlistcatwise.subcat_id+'" class="products-grid clearfix"  style="z-index: 1;margin: 0px;"></ul>';
                    jQuery('#products_grid_item').append(html);
                }
            var itembycate = itemlistcatwise.items;
            for(var j = 0; j < itembycate.length; j++){
                var result11 = itembycate[j];
                var qtyID = 1;
                var cartData = localStorage.getItem('cartData');
                var hiddenClassQty = '';
                var hiddenClassBtn = '';
                if(cartData != null){
                    cartData = JSON.parse(cartData);
                    var index = cartData.findIndex(cartData => cartData.item_id==result11.item_id);
                    console.log(index);
                    if(index !== -1){
                        qtyID = cartData[index].item_quty;
                        hiddenClassQty = '';
                        hiddenClassBtn = 'hidden';
                    }else{
                        hiddenClassQty = 'hidden';
                        hiddenClassBtn = '';
                    }
                }else{
                    hiddenClassQty = 'hidden';
                    hiddenClassBtn = '';
                }

                html2 += '<li class="item col-lg-4 col-md-3 col-sm-4 col-xs-6">\
                    <a href="<?php echo base_url();?>productDetail?id=' + result11.item_id + '">\
            <div class="post cover">\
              <div class="avatar"></div>\
              <div class="line"></div>\
              <div class="price"></div>\
              <div class="cart"></div>\
              </div>\
                        <div class="item-inner">\
            <div class="item-img ">\
                        <div class="item-img-info getlistitem item-img center-image1"><a href="<?php echo base_url();?>productDetail?id=' + result11.item_id + '" title="' + result11.item_name + '" class="product-image"><img style="height:150px;" data-src="http://placehold.it/150x100?text=Loading..."  src="<?php echo $this->config->item('api_img_url');?>' + result11.item_image + '" onerror="this.src=\'<?php echo $this->config->item('api_img_url');?>upload/item/noprivew.jpg\';" alt="'+result11.item_name+'"></a>\
                        </div></div>\
                        <div class="item-info">\
                        <div class="info-inner">\
                       <div class="item-title"><a href="<?php echo base_url();?>productDetail?id=' + result11.item_id + '" title="' + result11.item_name + '">'+titleCase(result11.item_name)+'</a></div>\
                   <div class="item-content text-left">\
                    <div class="item-price">\
                        <div class="price-box"><span class="regular-price" id="product-price-1"><span class="price">$' + parseFloat(result11.item_price).toFixed(2) + '</span> </span> </div>\
                    </div>\
                     <div class="add_cart">\
                    <div  class="change-qunty '+hiddenClassQty+'" data-id="qty_'+result11.item_id+'" style="border: solid 1px #eee;border-radius: 50px;box-shadow: 2px 2px 2px #eee;">\
  <button class="reduced items-count" onclick="minusqtyheader(\'' + result11.item_id + '\');" type="button" style="background:#fff;border:0px;color:#96cb50;border-radius: 50px 0px 0px 50px;text-align: center;padding: 0;"><i class="icon-minus">&nbsp;</i></button>\
    <span><input data-id="qtyval_'+result11.item_id+'" type="text" disabled name="result.item_id" id="qtyitem_' + result11.item_id + '" maxlength="12" value="' + qtyID + '" title="Quantity:" type="text" title="Quantity:" class="input-text qty" placeholder="0" style="border: none;width: 40px;color: #8cc63f;padding: 3px;margin-bottom: 0px;text-align: center;"></span>\
    <button  onclick="plusqtyheader(\'' + result11.item_id + '\');" class="increase items-count" type="button" style="background:#fff; border:0px;color:#96cb50;border-radius:0px 50px 50px 0px;text-align:center;padding: 0;    outline: whitesmoke;"><i class="icon-plus">&nbsp;</i></button>\
</div>\
  <button class="button btn-cart '+hiddenClassBtn+'" data-id="btn_'+result11.item_id+'" type="button" onclick="Addcartitemlist(\'' + result11.item_id + '\',' + result11.item_price + ',' + result11.Sales_tax + ');"><span>Add to Cart</span></button>\
                 </div>\
                    </div>\
                    </div>\
                    </div>\
                    </div> </a> </li>';
            }
            jQuery('#headersubid_'+itemlistcatwise.subcat_id).append(html2);
        }
    }

    function getlistalldepartment(categoryId, page, tag=false){
        console.log(categoryId);
        if(tag && categoryId==activeSubid){
            return false;
        }
        if(tag){
            headersubid =0;
            jQuery('html, body').animate({scrollTop: '0px'}, 300);
            jQuery("#subid_"+categoryId).addClass('slick-activate');
            jQuery("#subid_"+activeSubid).removeClass('slick-activate');
            activeSubid = categoryId;
            jQuery('#products_grid_item').html('');
        }

        var deptId=<?php echo $id;?>;
        var subcatid = categoryId;  //cat_id=0 for all;
        jQuery('#loaderprofile1').removeClass('hidden');
        var api_key='sdfsdfdfsdf';
        pagenoincr = page;
        var pageno = page;
        var unitime = 0;

        jQuery.ajax({
            type: 'GET',
            url: '<?php echo base_url(); ?>departmentitemid_new',
            data: {"listId": deptId , "catid":subcatid, "pageno":pageno, "unitime":unitime},
            beforeSend: function ()
            {
                ajaxindicatorstart('Loading...');
            },
            success: function (res) {
                ajaxindicatorstop();
                if(res.updationstatus === "0"){
                    processing = true;
                    jQuery('#loaderprofile1').addClass('hidden');
                    return false;
                }else{
                   jQuery('#loaderprofile1').addClass('hidden');
                    processing = false;
                }

                    var itemlist = res.items;
                    var count = 1;

                    for (var i = 0; i < itemlist.length; i++) {
                        count = count + 1;
                        var result11 = itemlist[i];
                        console.log(result11.subcat_id+' ='+headersubid);
                        if(result11.subcat_id != headersubid){
                            headersubid = result11.subcat_id;
                            var html = '<div class="item_subheader" ><p>'+result11.sub_name+'</p></div>\
            <ul id="headersubid_'+result11.subcat_id+'" class="products-grid clearfix"  style="z-index: 1;margin: 0px;"></ul>';
                            jQuery('#products_grid_item').append(html);
                        }
                        var itembycate1 = result11.items;
                        var html2='';
                        for(var j = 0; j < itembycate1.length; j++) {
                            var resultlist = itembycate1[j];

                            var qtyID = 1;
                            var cartData = localStorage.getItem('cartData');
                            var hiddenClassQty = '';
                            var hiddenClassBtn = '';
                            if(cartData != null){
                                cartData = JSON.parse(cartData);
                                var index = cartData.findIndex(cartData => cartData.item_id==resultlist.item_id);
                                console.log(index);
                                if(index !== -1){
                                    qtyID = cartData[index].item_quty;
                                    hiddenClassQty = '';
                                    hiddenClassBtn = 'hidden';
                                }else{
                                    hiddenClassQty = 'hidden';
                                    hiddenClassBtn = '';
                                }
                            }else{
                                hiddenClassQty = 'hidden';
                                hiddenClassBtn = '';
                            }

                            html2 += '<li class="item col-lg-4 col-md-3 col-sm-4 col-xs-6">\
                            <a href="<?php echo base_url();?>productDetail?id=' + result11.item_id + '">\
              <div class="post cover">\
              <div class="avatar"></div>\
              <div class="line"></div>\
              <div class="price"></div>\
              <div class="cart"></div>\
              </div>\
                        <div class="item-inner">\
                        <div class="item-img ">\
                        <div class="item-img-info getlistitem item-img center-image1"><a href="<?php echo base_url();?>productDetail?id=' + resultlist.item_id + '" title="' + resultlist.item_name + '" class="product-image"><img style="height:150px;" data-src="http://placehold.it/150x100?text=Loading..."  src="<?php echo $this->config->item('api_img_url');?>' + resultlist.item_image + '" onerror="this.src=\'<?php echo $this->config->item('api_img_url');?>upload/item/noprivew.jpg\';" alt="' + resultlist.item_name + '"></a>\
                        </div></div>\
                        <div class="item-info">\
                        <div class="info-inner">\
                       <div class="item-title"><a href="<?php echo base_url();?>productDetail?id=' + resultlist.item_id + '" title="' + resultlist.item_name + '">' + titleCase(resultlist.item_name) + '</a></div>\
                   <div class="item-content text-left">\
                    <div class="item-price">\
                        <div class="price-box"><span class="regular-price" id="product-price-1"><span class="price">$' + parseFloat(resultlist.item_price).toFixed(2) + '</span> </span> </div>\
                    </div>\
                    <div class="add_cart">\
                    <div class="change-qunty '+hiddenClassQty+'" data-id="qty_'+resultlist.item_id+'" style="border: solid 1px #eee;border-radius: 50px;box-shadow: 2px 2px 2px #eee;">\
                    <button onclick="minusqtyheader(\'' + resultlist.item_id + '\');" class="reduced items-count" type="button" style="background:#fff;border:0px;color:#96cb50;border-radius: 50px 0px 0px 50px;text-align: center;padding: 0;"><i class="icon-minus">&nbsp;</i></button> ' +
                                ' <span><input data-id="qtyval_'+resultlist.item_id+'" type="text" disabled name="result.item_id" id="qtyitem_' + resultlist.item_id + '" maxlength="12" value="' + qtyID + '" title="Quantity:" class="input-text qty" style="border: none;width: 40px;color: #8cc63f;padding: 3px;margin-bottom: 0px;text-align: center;">\</span>' +
                                '<button onclick="plusqtyheader(\'' + resultlist.item_id + '\');" class="increase items-count" type="button" style="background:#fff; border:0px;color:#96cb50;border-radius:0px 50px 50px 0px;text-align:center;padding: 0;"><i class="icon-plus">&nbsp;</i></button> </div>\
                                <button data-id="btn_'+resultlist.item_id+'" class="button btn-cart '+hiddenClassBtn+'" type="button" onclick="Addcartitemlist(\'' + resultlist.item_id + '\',' + resultlist.item_price + ',' + resultlist.Sales_tax + ');"><span>Add to Cart</span></button>\
                    </div>\
                    </div>\
                    </div>\
                    </div>\
                    </div> </a> </li>';

                        }
                        jQuery('#headersubid_'+result11.subcat_id).append(html2);
              function newW()
              {
                jQuery(".cover").fadeOut(200);
              }
              setTimeout(newW, 500);

                    }
            }
        });
    }

    function getlistbydeptid(categoryId, page, subname, tag=false){

        var deptId=<?php echo $id;?>;
        var subcatid = categoryId;  //cat_id=0 for all;
        jQuery('#loaderprofile1').removeClass('hidden');
        var api_key='sdfsdfdfsdf';
        pagenoincr =page;
        var pageno = page;
        var unitime = 0;
        if(categoryId != activeSubid){
            //console.log('sdsddf');
            var html = '<div style="display:none" class="item_subheader" ><p>'+subname+'</p></div>\
            <ul id="headersubid_'+categoryId+'" class="products-grid clearfix"  style="z-index: 1;margin: 0px;"></ul>';
            jQuery('#products_grid_item').html(html);
        }

        if(tag){
            jQuery('html, body').animate({scrollTop: '0px'}, 300);
            jQuery("#subid_"+categoryId).addClass('slick-activate');
            jQuery("#subid_"+activeSubid).removeClass('slick-activate');
            activeSubid = categoryId;
        }

        jQuery.ajax({
            type: 'GET',
            url: '<?php echo base_url(); ?>departmentitemid_new',
            data: {"listId": deptId, "catid": subcatid, "pageno": pageno, "unitime": unitime},
            beforeSend: function ()
            {
                ajaxindicatorstart('Loading...');
            },
            success: function (res) {
                ajaxindicatorstop();
                if (res.updationstatus === "0") {
                    processing = true;
                    jQuery('#loaderprofile1').addClass('hidden');
                    return false;
                } else {
                    jQuery('#loaderprofile1').addClass('hidden');
                    processing = false;
                }


            var itembycate1 = res.item;
                var html2 ='';
        for(var j = 0; j < itembycate1.length; j++) {
            var resultlist = itembycate1[j];

            var qtyID = 1;
            var cartData = localStorage.getItem('cartData');
            var hiddenClassQty = '';
            var hiddenClassBtn = '';
            if(cartData != null){
                cartData = JSON.parse(cartData);
                var index = cartData.findIndex(cartData => cartData.item_id==resultlist.item_id);
                console.log(index);
                if(index !== -1){
                    qtyID = cartData[index].item_quty;
                    hiddenClassQty = '';
                    hiddenClassBtn = 'hidden';
                }else{
                    hiddenClassQty = 'hidden';
                    hiddenClassBtn = '';
                }
            }else{
                hiddenClassQty = 'hidden';
                hiddenClassBtn = '';
            }

            html2 += '<li class="item col-lg-4 col-md-3 col-sm-4 col-xs-6">\
            <a href="<?php echo base_url();?>productDetail?id=' + resultlist.item_id + '">\
      <div class="post cover">\
              <div class="avatar"></div>\
              <div class="line"></div>\
              <div class="price"></div>\
              <div class="cart"></div>\
              </div>\
                        <div class="item-inner" >\
                        <div class="item-img ">\
                        <div class="item-img-info getlistitem item-img center-image1"><a href="<?php echo base_url();?>productDetail?id=' + resultlist.item_id + '" title="' + resultlist.item_name + '" class="product-image"><img style="height:150px;" data-src="http://placehold.it/150x100?text=Loading..."  src="<?php echo $this->config->item('api_img_url');?>' + resultlist.item_image + '" onerror="this.src=\'<?php echo $this->config->item('api_img_url');?>upload/item/noprivew.jpg\';" alt="' + resultlist.item_name + '"></a>\
                        </div></div>\
                        <div class="item-info">\
                        <div class="info-inner">\
                       <div class="item-title"><a href="<?php echo base_url();?>productDetail?id=' + resultlist.item_id + '" title="' + resultlist.item_name + '">' + titleCase(resultlist.item_name) + '</a></div>\
                   <div class="item-content text-left">\
                    <div class="item-price" style="padding-right: 35px;">\
                        <div class="price-box"><span class="regular-price" id="product-price-1"><span class="price">$' + parseFloat(resultlist.item_price).toFixed(2) + '</span> </span> </div>\
                    </div>\
                    <div class="add_cart">\
                    <div class="change-qunty '+hiddenClassQty+'" data-id="qty_'+resultlist.item_id+'" style="border: solid 1px #eee;border-radius: 50px;box-shadow: 2px 2px 2px #eee;">\
                    <button onclick="minusqtyheader(\'' + resultlist.item_id + '\');" class="reduced items-count" type="button" style="background:#fff;border:0px;color:#96cb50;border-radius: 50px 0px 0px 50px;text-align: center;padding: 0;"><i class="icon-minus">&nbsp;</i></button> ' +
                ' <span><input data-id="qtyval_'+resultlist.item_id+'" type="text" disabled name="result.item_id" id="qtyitem_' + resultlist.item_id + '" maxlength="12" value="' + qtyID + '" title="Quantity:" class="input-text qty" style="border: none;width: 40px;color: #8cc63f;padding: 3px;margin-bottom: 0px;text-align: center;">\</span>' +
                '<button onclick="plusqtyheader(\'' + resultlist.item_id + '\');" class="increase items-count" type="button" style="background:#fff; border:0px;color:#96cb50;border-radius:0px 50px 50px 0px;text-align:center;padding: 0;"><i class="icon-plus">&nbsp;</i></button> </div>\
                <button data-id="btn_'+resultlist.item_id+'" class="button btn-cart '+hiddenClassBtn+'" type="button" onclick="Addcartitemlist(\'' + resultlist.item_id + '\',' + resultlist.item_price + ',' + resultlist.Sales_tax + ');"><span>Add to Cart</span></button>\
                    </div>\
                    </div>\
                    </div>\
                    </div>\
                    </div> </a> </li>';

        }
               jQuery('#headersubid_'+resultlist.subcat_id).append(html2);
                //jQuery('#headersubid_'+resultlist.subcat_id).html(html2);
                    function newW()
                    {
                        jQuery(".cover").fadeOut(200);
                    }
                    setTimeout(newW, 500);

            }
        });
    }

</script>
  <script>
  jQuery(document).ready(function($) {
"use strict";
$('#customers-testimonials').owlCarousel( {
    loop: true,
    center: true,
    items: 5,
    margin: 30,
    autoplay: true,
    dots:true,
    nav:true,
   autoWidth:true,
    autoplayTimeout: 8500,
    smartSpeed: 450,
    navText: ['<i class="fa fa-angle-left"></i>','<i class="fa fa-angle-right"></i>'],
    responsive: {
      0: {
        items: 1
      },
      768: {
        items: 2
      },
      1000: {
        items: 3
      }
    }
  });
});
    </script>


<script> 
jQuery(window).on('load', function() {
    jQuery(".cover").fadeOut(200);
});

//stackoverflow does not fire the window onload properly, substituted with fake load

function newW()
{
    jQuery(window).load();
}
setTimeout(newW, 500);
</script>

<script> 
  jQuery(document).ready(function () {
    var itemsMainDiv = ('.MultiCarousel');
    var itemsDiv = ('.MultiCarousel-inner');
    var itemWidth = "";

    jQuery('.leftLst, .rightLst').click(function () {
        var condition = jQuery(this).hasClass("leftLst");
        if (condition)
            click(0, this);
        else
            click(1, this)
    });

    ResCarouselSize();

    jQuery(window).resize(function () {
        ResCarouselSize();
    });

    //this function define the size of the items
    function ResCarouselSize() {
        var incno = 0;
        var dataItems = ("data-items");
        var itemClass = ('.item');
        var id = 0;
        var btnParentSb = '';
        var itemsSplit = '';
        var sampwidth = jQuery(itemsMainDiv).width();
        var bodyWidth = jQuery('body').width();
        jQuery(itemsDiv).each(function () {
            id = id + 1;
            var itemNumbers = jQuery(this).find(itemClass).length;
            btnParentSb = jQuery(this).parent().attr(dataItems);
            itemsSplit = btnParentSb.split(',');
            jQuery(this).parent().attr("id", "MultiCarousel" + id);


            if (bodyWidth >= 1200) {
                incno = itemsSplit[3];
                itemWidth = sampwidth / incno;
            }
            else if (bodyWidth >= 992) {
                incno = itemsSplit[2];
                itemWidth = sampwidth / incno;
            }
            else if (bodyWidth >= 768) {
                incno = itemsSplit[1];
                itemWidth = sampwidth / incno;
            }
            else {
                incno = itemsSplit[0];
                itemWidth = sampwidth / incno;
            }
            jQuery(this).css({ 'transform': 'translateX(0px)', 'width': itemWidth * itemNumbers });
            jQuery(this).find(itemClass).each(function () {
                jQuery(this).outerWidth(itemWidth);
            });

            jQuery(".leftLst").addClass("over");
            jQuery(".rightLst").removeClass("over");

        });
    }


    //this function used to move the items
    function ResCarousel(e, el, s) {
        var leftBtn = ('.leftLst');
        var rightBtn = ('.rightLst');
        var translateXval = '';
        var divStyle = jQuery(el + ' ' + itemsDiv).css('transform');
        var values = divStyle.match(/-?[\d\.]+/g);
        var xds = Math.abs(values[4]);
        if (e == 0) {
            translateXval = parseInt(xds) - parseInt(itemWidth * s);
            jQuery(el + ' ' + rightBtn).removeClass("over");

            if (translateXval <= itemWidth / 2) {
                translateXval = 0;
                jQuery(el + ' ' + leftBtn).addClass("over");
            }
        }
        else if (e == 1) {
            var itemsCondition = jQuery(el).find(itemsDiv).width() - jQuery(el).width();
            translateXval = parseInt(xds) + parseInt(itemWidth * s);
            jQuery(el + ' ' + leftBtn).removeClass("over");

            if (translateXval >= itemsCondition - itemWidth / 2) {
                translateXval = itemsCondition;
                jQuery(el + ' ' + rightBtn).addClass("over");
            }
        }
        jQuery(el + ' ' + itemsDiv).css('transform', 'translateX(' + -translateXval + 'px)');
    }

    //It is used to get some elements from btn
    function click(ell, ee) {
        var Parent = "#" + jQuery(ee).parent().attr("id");
        var slide = jQuery(Parent).attr("data-slide");
        ResCarousel(ell, Parent, slide);
    }

});
</script>
</body>
</html>
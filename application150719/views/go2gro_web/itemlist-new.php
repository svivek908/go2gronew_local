<?php
include 'header.php';
$id=$_GET['id'];
load_css(array('public/assets/stylesheet/slick.min.css?'));
?>
<style type="text/css">
    
.owl-carousel .owl-next, .owl-carousel .owl-prev {
  width: 30px;
  height: 30px;
  line-height: 30px;
  border-radius: 50%;
  position: absolute;
  top: 30%;
  background: #fff !important;
  color: #8cc63f;
  border: solid 1px #8cc63f;
  box-shadow: 2px 2px 2px #d5d5d5;
  text-align: center;
}
.owl-carousel .owl-prev {
  left: -32px;
    color: #8cc63f !important;
    line-height: 20px !important;
    font-size: 20px !important;
}
.owl-carousel .owl-next {
  right: -32px;
  color:#8cc63f !important;
  line-height: 20px !important;
  font-size: 20px !important;
}
</style>
<div class="pagetop">
</div>
<!--breadcrumbs-->

<!-- BEGIN Main Container col2-left -->
<section class="main-container col2-left-layout bounceInUp animated">
    <section class="sec-adjst"> 
        <div class="container">     
       <div class="row"> 
       <!-- TESTIMONIALS -->
    <section class="testimonials">
    <div class="container">
      <div class="row">
        <div class="col-sm-12">
            
             <div id="customers-testimonials" class="owl-carousel">
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
                            <span><img src="<?php echo base_url('public/'.$cat['image']);?>" alt="" class="img-responsive post-image" /></span>
                        </a>
                        <p class="fs-16"><a class="pro-name" href="<?php echo $href;?>" onclick="<?php echo $onclick;?>"><?= ucfirst($cat['cat_name']) ?></a></p>
                        <!-- <p class="fs-13">105 Item</p> -->
                    </div>
                </div>
            <?php } ?>
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
            <div class="col-main col-sm-12 product-grid">
                <div id="sticky-anchor"></div>
                <div class="category-navigation res_modify sticky_contn" id="sticky">
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
                    <article class="col-main no-mar">
                        <div class="category-products no-pad">
                            <div >
                                <div id="products_grid_item" >
                                </div>
                            </div>
                        </div>
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
            success: function (res) {
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
            <ul id="headersubid_'+itemlistcatwise.subcat_id+'" class="products-grid clearfix no-mar index1"></ul>';
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
                        <div class="item-img-info getlistitem item-img center-image1"><a href="<?php echo base_url();?>productDetail?id=' + result11.item_id + '" title="' + result11.item_name + '" class="product-image"><img class="height150" data-src="http://placehold.it/150x100?text=Loading..."  src="<?php echo $this->config->item('api_img_url');?>' + result11.item_image + '" onerror="this.src=\'<?php echo $this->config->item('api_img_url');?>upload/item/noprivew.jpg\';" alt="'+result11.item_name+'"></a>\
                        </div></div>\
                        <div class="item-info">\
                        <div class="info-inner">\
                       <div class="item-title"><a href="<?php echo base_url();?>productDetail?id=' + result11.item_id + '" title="' + result11.item_name + '">'+titleCase(result11.item_name)+'</a></div>\
                   <div class="item-content text-left">\
                    <div class="item-price">\
                        <div class="price-box"><span class="regular-price" id="product-price-1"><span class="price">$' + parseFloat(result11.item_price).toFixed(2) + '</span> </span> </div>\
                    </div>\
                     <div class="add_cart">\
                    <div  class="change-qunty qty-manage '+hiddenClassQty+'" data-id="qty_'+result11.item_id+'" >\
    <button class="reduced lessbtn items-count" onclick="minusqtyheader(\'' + result11.item_id + '\');" type="button"><i class="icon-minus">&nbsp;</i></button>\
        <span><input data-id="qtyval_'+result11.item_id+'" type="text" disabled name="result.item_id" id="qtyitem_' + result11.item_id + '" maxlength="12" value="' + qtyID + '" title="Quantity:" type="text" title="Quantity:" class="input-text qty qty-text" placeholder="0" ></span>\
        <button  onclick="plusqtyheader(\'' + result11.item_id + '\');" class="increase additem-qty items-count" type="button"><i class="icon-plus">&nbsp;</i></button>\
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
            <ul id="headersubid_'+result11.subcat_id+'" class="products-grid clearfix no-mar index1"></ul>';
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
                        <div class="item-img-info getlistitem item-img center-image1"><a href="<?php echo base_url();?>productDetail?id=' + resultlist.item_id + '" title="' + resultlist.item_name + '" class="product-image"><img class="height150" data-src="http://placehold.it/150x100?text=Loading..."  src="<?php echo $this->config->item('api_img_url');?>' + resultlist.item_image + '" onerror="this.src=\'<?php echo $this->config->item('api_img_url');?>upload/item/noprivew.jpg\';" alt="' + resultlist.item_name + '"></a>\
                        </div></div>\
                        <div class="item-info">\
                        <div class="info-inner">\
                       <div class="item-title"><a href="<?php echo base_url();?>productDetail?id=' + resultlist.item_id + '" title="' + resultlist.item_name + '">' + titleCase(resultlist.item_name) + '</a></div>\
                   <div class="item-content text-left">\
                    <div class="item-price">\
                        <div class="price-box"><span class="regular-price" id="product-price-1"><span class="price">$' + parseFloat(resultlist.item_price).toFixed(2) + '</span> </span> </div>\
                    </div>\
                    <div class="add_cart">\
                    <div class="change-qunty qty-manage '+hiddenClassQty+'" data-id="qty_'+resultlist.item_id+'">\
                    <button onclick="minusqtyheader(\'' + resultlist.item_id + '\');" class="reduced lessbtn items-count" type="button" ><i class="icon-minus">&nbsp;</i></button> ' +
                                ' <span><input data-id="qtyval_'+resultlist.item_id+'" type="text" disabled name="result.item_id" id="qtyitem_' + resultlist.item_id + '" maxlength="12" value="' + qtyID + '" title="Quantity:" class="input-text qty qty-text" >\</span>' +
                                '<button onclick="plusqtyheader(\'' + resultlist.item_id + '\');" class="increase plusqtyheader items-count" type="button" ><i class="icon-plus">&nbsp;</i></button> </div>\
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
            var html = '<div class="item_subheader d-none" ><p>'+subname+'</p></div>\
            <ul id="headersubid_'+categoryId+'" class="products-grid clearfix no-mar index100"></ul>';
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
                        <div class="item-img-info getlistitem item-img center-image1"><a href="<?php echo base_url();?>productDetail?id=' + resultlist.item_id + '" title="' + resultlist.item_name + '" class="product-image"><img class="height150" data-src="http://placehold.it/150x100?text=Loading..."  src="<?php echo $this->config->item('api_img_url');?>' + resultlist.item_image + '" onerror="this.src=\'<?php echo $this->config->item('api_img_url');?>upload/item/noprivew.jpg\';" alt="' + resultlist.item_name + '"></a>\
                        </div></div>\
                        <div class="item-info">\
                        <div class="info-inner">\
                       <div class="item-title"><a href="<?php echo base_url();?>productDetail?id=' + resultlist.item_id + '" title="' + resultlist.item_name + '">' + titleCase(resultlist.item_name) + '</a></div>\
                   <div class="item-content text-left">\
                    <div class="item-price padright35">\
                        <div class="price-box"><span class="regular-price" id="product-price-1"><span class="price">$' + parseFloat(resultlist.item_price).toFixed(2) + '</span> </span> </div>\
                    </div>\
                    <div class="add_cart">\
                    <div class="change-qunty qty-manage '+hiddenClassQty+'" data-id="qty_'+resultlist.item_id+'" >\
                    <button onclick="minusqtyheader(\'' + resultlist.item_id + '\');" class="reduced lessbtn items-count" type="button"><i class="icon-minus">&nbsp;</i></button> ' +
                ' <span><input data-id="qtyval_'+resultlist.item_id+'" type="text" disabled name="result.item_id" id="qtyitem_' + resultlist.item_id + '" maxlength="12" value="' + qtyID + '" title="Quantity:" class="input-text qty qty-text">\</span>' +
                '<button onclick="plusqtyheader(\'' + resultlist.item_id + '\');" class="increase additem-qty items-count" type="button"><i class="icon-plus">&nbsp;</i></button> </div>\
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
        items: 7,
        margin: 30,
        dots:true,
    nav:true,
     autoWidth:true,
     autoPlay : 5000,
        stopOnHover : false,
        smartSpeed: 450,
        navigation:true,
        navigationText: ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"],
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

</body>
</html>
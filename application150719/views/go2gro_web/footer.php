
<?php $pageName = $this->uri->segment(1);
load_css(array('public/assets/stylesheet/pgstyle/footerstyle.css?'));
?>

<footer>
   <!-- Modal -->
    <div id="myModal" class="modal fade" role="dialog">
      <div class="modal-backdrop fade in"></div>
      <div class="modal-dialog modal-sm">
         <!-- Modal content-->
         <div class="modal-content">
            <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal">&times;</button>
               <h4 class="modal-title">Add Tip</h4>
            </div>
            <div class="modal-body">
               <div class="form-group">
                  <label for="email">Enter Tip Amount</label>
                  <input type="number" class="form-control" id="custom_tip" placeholder="Enter Tip Amount">
               </div>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-default btn-modelsame">OK</button>
               <button type="button" class="btn btn-default btn-modelsame"  data-dismiss="modal">Close</button>
            </div>
         </div>
      </div>
   </div>
   <!-- BEGIN INFORMATIVE FOOTER -->
   <div>
      <div class="container">
         <div class="row">
            <div class="col-sm-4 col-xs-12 col-lg-4">
               <div class="co-info">
                  <div class="text-left">
                     <a href="<?php echo base_url();?>"><img src="<?php echo base_url('public/assets/images/logo_footer.png');?>" alt="footer logo"></a>
                  </div>
                  <address>
                     <div><em class="icon-mobile-phone"></em><span> +1 (833) 346-2476 / <br>+1 (833) 3GO-2GRO</span>
                     </div>
                     <div><em class="icon-envelope"></em><span>customersupport@go2gro.com</span></div>
                     <div><i class="fas fa-globe-asia"></i><span>www.go2gro.com</span></div>
                  </address>
               </div>
            </div>
            <div class="col-sm-6 col-xs-12 col-lg-5">
               <div class="footer-column">
                  <h4>Quick Links</h4>
                  <ul class="links">
                     <li class="first"><a title="Home" href="<?php echo base_url(); ?>">Home</a>
                     </li>
                     <li><a title="Privacy Policy" href="<?php echo base_url(); ?>/privacy">Privacy Policy</a>
                     </li>
                     <li><a title="Return Policy" href="<?php echo base_url(); ?>/return_policy">Return
                        Policy</a>
                     </li>
                     <li><a title="Terms & Condition" href="<?php echo base_url(); ?>/terms">Terms &
                        Conditions</a>
                     </li>
                  </ul>
               </div>
               <div class="footer-column column-pad">
                  <h4>Information</h4>
                  <ul class="links">
                     <li><a title="About Us" href="<?php echo base_url(); ?>/about">About Us</a>
                     </li>
                     <li><a title="Contact Us" href="<?php echo base_url(); ?>/contact">Contact Us</a>
                     </li>
                     <li><a title="Order History?" href="<?php echo base_url(); ?>/account">Order History</a>
                     </li>
                     <li class="last"><a title="FAQ" href="<?php echo base_url(); ?>faq">FAQ</a>
                     </li>
                  </ul>
               </div>
            </div>
            <div >
               <div class="col-sm-2 col-xs-12 col-lg-3">
                  <div class="footer download-blockpad">
                     <h4>Download App</h4>
                     <div class="logo-space">
                        <a href="<?php echo base_url();?>"><img src="<?php echo base_url('public/assets/images/logo_footer.png');?>" alt="footer logo">
                        </a>
                     </div>
                     <div class="app">
                        <a target="_blank" href="https://play.google.com/store/apps/details?id=com.go2gro_user.go2gro"><img class="and_img" src="<?php echo base_url('public/assets/images/play-store.png');?>" alt="Android">
                        </a>
                        <a target="_blank" href="https://itunes.apple.com/us/app/go2gro/id1317699292?ls=1&mt=8"><img class="and_img" src="<?php echo base_url('public/assets/images/apple.png');?>" alt="Apple">
                        </a>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <!--row-->
      </div>
      <!--container-->
   </div>
   <!--footer-inner-->
   <div class="footer-middle">
      <div class="container">
         <div class="row">
            <div class="row"></div>
         </div>
         <!--row-->
      </div>
      <!--container-->
   </div>
   <!--footer-middle-->
   <div class="footer-bottom ft-bottom">
      <div class="container">
         <div class="row">
            <div class="col-sm-4 col-xs-12 text-left coppyright"> <span> © 2018 SNPC Global, LLC All Rights Reserved. </span> </div>
            <div class="col-sm-8 col-xs-12 text-right authorizefooter-img">
               <div class="col-md-6 authorize">
                  <a href="javascript:void(0)"> Payment Options:</a>
               </div>
               <div class="col-md-6">
                  <div class="authorizenetSeal">
                     <script type="text/javascript" language="javascript">var ANS_customer_id="eae89c1c-7862-4291-8406-8b85dc5c0593";</script>
                     <script type="text/javascript" language="javascript" src="//verify.authorize.net/anetseal/seal.js" ></script>
                     <span>
                        <img src="<?php echo base_url('public/assets/images/discover.png');?>" >
                        <img src="<?php echo base_url('public/assets/images/payment-2.png');?>" >
                        <img src="<?php echo base_url('public/assets/images/payment-3.png');?>" >
                        <img src="<?php echo base_url('public/assets/images/payment-4.png');?>" >
                        <img src="<?php echo base_url('public/assets/images/dinnerclub.png');?>" >
                        <img src="<?php echo base_url('public/assets/images/jcb.png');?>" >
                     </span>
                  </div>
               </div>
            </div>
         </div>
         <!--row-->
      </div>
      <!--container-->
   </div>
   <!--footer-bottom-->
   <!-- BEGIN SIMPLE FOOTER -->
</footer>

<!-- End For version 1,2,3,4,6 -->
<!-- jsScript -->
    <?php load_js(array('public/assets/sweetalertlib/dist/sweetalert.min.js?','public/assets/js/jquery.min.js?','public/assets/js/bootstrap.min.js?','public/assets/js/parallax.js?','public/assets/js/revslider.js?',
    'public/assets/js/common.js?','public/assets/js/jquery.bxslider.min.js?','public/assets/js/jquery.flexslider.js?',
    'public/assets/js/owl.carousel.min.js?','public/assets/js/jquery.mobile-menu.min.js?',
    'public/assets/js/home_js/masonry.pkgd.min.js?','public/assets/js/home_js/script.js?','public/assets/js/slick.min.js?'));?>

    <script src='https://cdn.rawgit.com/filamentgroup/fixed-sticky/master/fixedsticky.js'></script>

    <?php load_js(array('public/assets/js/jquery.validate.min.js?','public/assets/toaster/demos/js/jquery.toast.js?',
    'public/assets/js/cloud-zoom.js?','public/assets/js/lodash.min.js?'));?>

    <script type="text/javascript" src="https://cdn.ywxi.net/js/1.js" async></script>

    <?php load_js(array('public/assets/js/jquery-ui.js?','public/assets/js/moment.min.js?','public/assets/js/moment-timezone-with-data.js?','public/assets/js/index.js?','public/assets/js/jquery.twbsPagination.js?',
    'public/assets/src/creditly.js?'));?>
    <!-- jsDeliver -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.lazy/1.7.10/jquery.lazy.js"></script>
</div>
<script>
    jQuery('.modal-overlay').hide();
    jQuery(document).ready(function () {
        jQuery('.lazy').lazy();

        jQuery(window).scroll(function(){
            if (jQuery(window).scrollTop() >=60) {
                jQuery('nav').addClass('fixed-header');

            }
            else {
                jQuery('nav').removeClass('fixed-header');
            }
        });

        jQuery.ajax({
            type: 'GET',
            url: '<?php echo base_url('allzipcodes'); ?>',
            data: {},
            success: function (res) {
                console.log(res);
                if (res.error == true) {

                }
                else {
                    var selected_zipcode = '<?php echo $pin_code?>';
                    var opt_html = '<option>Select zip code</option>';
                    for(var p=0; p < res.zipcodes.length; p++){
                        var select = '';
                        if(selected_zipcode == res.zipcodes[p]['pincode']){
                            select = 'selected';
                        }
                        opt_html+='<option value="'+res.zipcodes[p]['pincode']+'" '+select+'>'+res.zipcodes[p]['pincode']+'</option>';
                    }
                    jQuery(".pincode_ival").html(opt_html);
                }
            }
        });
    });

    function openNav() {
        <?php if ($isLogin==true) { ?>
        jQuery('.modal-overlay').show();
        jQuery("#fixed-checkout").show("slide", { direction: "left" }, 1000);
        //jQuery('#fixed-checkout').show();
        //document.getElementById("mySidenav").style.width = "560px";
        document.getElementById("mySidenav").className += " otherclass";
        document.getElementById("main").style.marginLeft = "0px";
        document.body.style.backgroundColor = "white";
        <?php } else {?>
        window.location = "<?php echo base_url('Login'); ?>";
        <?php } ?>
    }

    function closeNav() {
        jQuery('.modal-overlay').hide();
        jQuery('#fixed-checkout').hide();
        //document.getElementById("mySidenav").style.width = "0";
        document.getElementById("mySidenav").classList.remove("otherclass");
        document.getElementById("main").style.marginLeft= "0";
        document.body.style.backgroundColor = "white";
    }

    function invite_friend(){
        <?php if ($isLogin==true) { ?>
            var api_key = '<?php echo $apikey;?>';
            jQuery.ajax({
                type: 'POST',
                url: '<?php echo site_url("refer_to_friend"); ?>',
                data: {
                    'Authorization': api_key
                },
                success: function (res) {
                    console.log(res);
                    if (res.error == true) {
                        sweetAlert("Oops...", res.message, "error");
                    }
                    else {
                    console.log('title:'+ res.title_message );
                    jQuery("#title_msg").text(res.title_message);
                    jQuery("#sharelink_users").text(res.share_message);
        
                    jQuery("#fblink").attr("href",'http://www.facebook.com/sharer.php?u=https://www.go2gro.com/');
                    jQuery("#twtlink").attr("href",'http://twitter.com/share?text='+res.title_message+'&url=https://www.go2gro.com/');
                    jQuery("#lkdnlink").attr("href",'https://www.linkedin.com/shareArticle?mini=true&title='+res.title_message+'&url=https://www.go2gro.com/');
                    jQuery("#whatsapplink").attr("href",'https://api.whatsapp.com/send?text='+res.share_message);
                    jQuery("#instlink").attr("href",'https://www.instagram.com/?url=https://www.go2gro.com/');
                    jQuery("#gpluslink").attr("href",'https://plus.google.com/share?url=https://www.go2gro.com&title='+res.title_message);
                    jQuery('#invitefrndmodal').modal('show');

                    }
                }
            });
        <?php } ?>
    }

    jQuery("#link_copy").click(function(){
        var $temp = jQuery("<input>");
        jQuery("body").append($temp);
        $temp.val(jQuery("#sharelink_users").text()).select();
        if(document.execCommand("copy")){
           // var p = jQuery("#copy_msg_show").css({"background-color": "black", "color": "#fff"});
            var p = jQuery("#copy_msg_show").css({"background-color": "none", "color": "#94d256"});
            p.css('display','block');
            p.show(1500);
            setTimeout(function () {
              p.hide(1500);
            }, 2000);
        }
        $temp.remove();
    });

    jQuery('.searchbar').autocomplete({
        source: function (request, response) {
            var searchval = request.term;
            searchval = searchval.trim();
            var status = 0;
            jQuery.ajax({
                type: 'GET',
                url: '<?php echo base_url(); ?>/searchList',
                data: {"searchStr": searchval, "status":status,"page":0 },
                success: function (res) {
                    if (res.error == true) {
                    }
                    else {
                        response(jQuery.map(res.item, function (items) {
                        console.log(items);
                            return {
                                label: items.item_name,
                                value: items.item_name,
                                itemid: items.item_id
                            }
                        }));
                    }
                }
            });
        },
        select: function (event, response) {
            var searchText = fixedEncodeURIComponent(response.item.value);
            window.location = "<?php echo base_url(); ?>searchresult?q="+searchText;
        },
        minLength: 0
    });

    function fixedEncodeURIComponent(str) {
        return encodeURIComponent(str).replace(/[!'()*]/g, function(c) {
            return '%' + c.charCodeAt(0).toString(16);
        });
    }

    function SearchEnter(event, ele) {

        if (event.keyCode == 13) {
            var searchstr = encodeURIComponent(ele.value);
            if(searchstr == ""){
                ele.focus();
                return false;
            }
            window.location = "<?php echo base_url(); ?>searchresult?q="+searchstr;
        }
    }
        
    function SearchEnter_byclick(event, id) {
        var search = document.getElementById(id).value;
        var searchstr = encodeURIComponent(search);
        if(searchstr == ""){
            ele.focus();
            return false;
        }
        window.location = "<?php echo base_url(); ?>searchresult?q="+searchstr;
        
    }

    function progressNotify(message, type) {
        jQuery.toast({
            heading: 'Loading..',
            text: message,
            icon: type,
            position: 'bottom-center',
            stack: false
        });
    }
    function successNotify(message, type) {
        jQuery.toast({
            heading: 'Success',
            text: message,
            icon: type,
            position: 'bottom-center',
            stack: false,
            loader: false

        });
    }
    function errorNotify(message, type) {
        jQuery.toast({
            heading: 'Error',
            text: message,
            icon: type,
            position: 'bottom-center',
            stack: false

        });
    }

    /*jQuery(".carousel").swipe({
      swipe: function(event, direction, distance, duration, fingerCount, fingerData) {
        if (direction == 'left') $(this).carousel('next');
        if (direction == 'right') $(this).carousel('prev');
      },
      allowPageScroll:"vertical"
    });*/
</script>
<script>
    var myCartData;
    var categoryData;
    var authorizeAmount = 0;
    var timeSlotObject = {};
    var total_Am0unt = localStorage.getItem("total_Am0unt");
    total_Am0unt = parseFloat(total_Am0unt);

    jQuery(document).ready(function () {
        <?php if(!$isLogin){ ?>
            localStorage.clear();
        <?php } 
        if($this->session->has_userdata('select_store_data'))
        {
        ?>
        //getcategory();
        getbestSeller();
        mycartdata1();
        <?php } ?>
        jQuery(document).on('click', '.show_msg', function () {
            swal({title: 'Error', text: titleCase("Oops Store closed!"), type: 'error'}, function () {
                
            });
        })
    });

    function getCartData() {
        var cartData = {};
        <?php if(empty($apikey) || !trim($apikey)) {?>
        var api_key = 'not defined';
        <?php }else{?>
        var api_key = '<?php echo $apikey;?>';
        <?php }?>
        var jqXhr = jQuery.ajax({
            type: 'GET',
            url: '<?php echo base_url("getCartItem"); ?>',
            data: {
                'Authorization': api_key
            }
        });
        return jqXhr;
    }

    function mycartdata1() {
        var pagename = '<?php echo $pageName; ?>';
        if (pagename == 'checkout') {
            jQuery("#loaderorder").addClass('loading');
        }
        <?php if(empty($apikey) || !trim($apikey)) {?>
        var api_key = 'not defined';
        <?php }else{?>
        var api_key = '<?php echo $apikey;?>';
        <?php } ?>
        if(api_key !='not defined' && api_key!=''){
            jQuery.ajax({
            type: 'GET',
            url: '<?php echo base_url("getCartItem"); ?>',
            data: {
                'Authorization': api_key
            },
            success: function (res) {
                myCartData = res;
                var html1 = '';
                if (res.error == true) {
                    //------------------Remove cart data form local storage
                    localStorage.removeItem('cartData');
                    localStorage.removeItem("discount_amnt");
                    localStorage.removeItem("discount_label");
                    localStorage.removeItem("discount_type");
                    localStorage.removeItem("discount_id");
                    //html1 = html1;
                    html1 = '<div class="empty-cardbar"><img src="https://www.go2gro.com/go2gro_beta/images/empty-cart.jpg">' +
                    '<a class="btn-checkout cardbar-checkoutbtn" title="Continue Shopping" href="javascript:void()"  onclick= "closeNav()"><span class="continu-btn">Continue Shopping</span></a>'+
                    '</div>';
                    jQuery('.basket_id').text(0);
                    if (pagename == 'ShoppingCart') {
                        swal({title: 'Error', text: titleCase(res.message), type: 'error'}, function () {
                            window.location = '<?php echo base_url(); ?>';
                        });
                    }
                    if (pagename == 'checkout') {
                        swal({title: 'Error', text: titleCase(res.message), type: 'error'}, function () {
                            window.location = '<?php echo base_url(); ?>';
                        });
                    }
                } else {
                    if (api_key != 'not defined') {
                        var discount_amnt=0.00;
                        var delivery_charges_label = res.delivery_charges_label;
                        //if (localStorage.getItem("discount_amnt") === null) {
                        if (!localStorage.getItem("discount_amnt")) {
                            console.log('first step');
                            discount_amnt = parseFloat(res.discount).toFixed(2);
                            localStorage.setItem("discount_amnt", discount_amnt);
                            localStorage.setItem("discount_label",res.discount_label);
                            localStorage.setItem("discount_type",res.discount_type);
                            localStorage.setItem("discount_id",res.discount_id);
                        }else{
                            console.log('Second step');
                            discount_amnt = localStorage.getItem("discount_amnt");
                            discount_amnt = parseFloat(discount_amnt).toFixed(2);
                        }
                        localStorage.setItem('cartData', JSON.stringify( res.item));
                        catList = res.item;
                        var jsonLength = catList.length;
                        var jsonLength1;
                         jsonLength1 = jsonLength;
                        jQuery('.basket_id').text(jsonLength);
                        var $total = 0.00;

                        html1 += '<div class="fl-mini-cart-content">\
                        <div class="block-subtitle"><div class="top-subtotal">' + jsonLength + '&nbsp;items </div></div>';
                        for (var i = 0; i < jsonLength1; i++) {
                            var result = catList[i];
                            $total = parseFloat($total) + parseFloat(result.total);
                        }
                        var totalAmount = Math.round($total * 100) / 100;
                        html1 +='<div class="subtotal-block subtotal-setblock">'+'<p> Sub-Total <span class="fright"> $'+parseFloat(totalAmount).toFixed(2)+' </span> </p>'+'</div>';

                        html1 += '<div class="cart-items-container">';
                        var count = 1;
                        for (var i = 0; i < jsonLength1; i++) {
                            count = count + 1;
                            var result = catList[i];
                              html1 += '<ul class="mini-products-list" id="cart-sidebar">\
                                <li class="item first">\
                                <div class="item-inner">\
                                <a class="product-image" title="timi &amp; leslie Sophia Diaper Bag, Lemon Yellow/Shadow White" href="<?php echo base_url();?>productDetail?id=' + result.item_id + '">\
                                <img alt="' + result.item_image + '" src="<?php echo $this->config->item('api_img_url');?>' + result.item_image + '" onerror="this.src=\'<?php echo $this->config->item('api_img_url');?>upload/item/noprivew.jpg\';" >\
                                </a>\
                                <div class="product-details">\
                                <div class="access d-none">\
                                <a onclick="delete_cart1(\'' + result.item_id + '\')" class="btn-remove1" title="Remove This Item" href="javascript:void(0);">Remove</a>\
                                </div>\
                                <p class="product-name">\
                                <a href="<?php echo base_url();?>productDetail?id=' + result.item_id + '">' + titleCase(result.item_name) + '</a>\
                                </p><strong>\
                                <div class="change-qunty d-none">\
                                <button onclick="minusqtyheader(\'' + result.item_id + '\');" class="reduced items-count pad-colcount" type="button"><i class="icon-minus">&nbsp;</i></button>\
                                <span><input data-id="qtyval_' +result.item_id + '" type="text" disabled  name="qtyheader" id="qtyheader_' + result.item_id + '" maxlength="12" value="' + result.item_quty + '" title="Quantity:" class="input-text input-text-01 qty"></span>\
                                <button onclick="plusqtyheader(\'' + result.item_id + '\');" class="increase items-count pad-colcount" type="button"><i class="icon-plus">&nbsp;</i></button>\
                                </div>\
                                <div><span class="mycart_change_qnt"> ' + result.item_quty + ' <input type="hidden" data-id="qtyval_' +result.item_id + '" type="text" disabled  name="qtyheader" id="qtyheader_' + result.item_id + '" maxlength="12" value="' + result.item_quty + '" title="Quantity:" class="input-text input-text-01 qty"> </span> <span class="qnt-blockfind"> \
                                <button class="btn btn_down_arrow" onclick="plusqtyheader(\'' + result.item_id + '\');"><i class="icon-plus color-them"></i> </button> <button class="btn btn_down_arrow" onclick="minusqtyheader(\'' + result.item_id + '\');"><i class="icon-minus color-them"></i> </button></span> <div class="display-add">\
                                  <div><p class="pg-deatil"> $'+ parseFloat(result.total).toFixed(2) +'</p></div><div> <p class="pg-deatil" onclick="delete_cart1(\'' + result.item_id + '\')"> <i class="icon-trash deleting-icon"></i>Delete</p></div>\
                                </div> </div>\
                                </strong> <!-- <span class="price22323">$' + parseFloat(result.item_price).toFixed(2) + '</span>-->\
                                </div>\
                                </div>\
                                </li>\
                                </ul>';
                        }
                        html1 += '</div>';
                        html1 += '<div class=""><div class="">\
                            <p> <span class="price11" id="headertotalamount_' + result.item_id + '"></span></p> \
                            </div></div>\
                            <div class="actions">\
                            <a class="btn-checkout checkout-actionsset" title="Checkout" href="<?php echo base_url();?>ShoppingCart"><span class="subtoal-amt"> Sub Total : $'+parseFloat(totalAmount).toFixed(2)+' </span><span class="checkout-span"> Checkout</span></a>\
                            </div>\
                           </div>';

                        if (res.isfirstorder == true) {
                            deleviry_charge =<?php echo $this->config->item('free_deleviry_charge');?>;
                        } else {
                            //---------------change 04/12/2018 storewise-----
                            //deleviry_charge =<?php //echo $this->config->item('deleviry_charge');?>;
                            deleviry_charge =res.delivery_charges;
                        }
                        localStorage.setItem("delivery_charge", deleviry_charge);
                        localStorage.setItem("delivery_charges_label", delivery_charges_label);
                    }
                    else {
                        html1 += '<div class="fl-mini-cart-content d-block">\
                            <div class="block-subtitle"><div class="top-subtotal"> items </div></div>' +
                            '<ul class="mini-products-list" id="cart-sidebar">\
                                    <li class="item first">\
                                 <p class="product-name"><a href="<?php echo base_url();?>Login">Please <span> Login </span> To View Your Cart Detail</a>\
                            </p>\
                           </li>\
                           </ul>';
                    }
                }
                jQuery('#minicartview').html(html1);
                mycartdatarightsidebar();
                shoppingcartdata();
                contentdata(res.tips_arr);
            }
        });
        }
    }

    jQuery(".item").last().addClass("last");

    function delete_cart1(id) {
        progressNotify('Item deleting', 'info');

        <?php if(empty($apikey) || !trim($apikey)) {?>
        var api_key = 'not defined';
        <?php }else{?>
        var api_key = '<?php echo $apikey;?>';
        <?php }?>
        <?php if(empty($userId) || !trim($userId)) {?>
        var user_id = 'not defined';
        <?php }else{?>
        var user_id = '<?php echo $userId;?>';
        <?php }?>
        jQuery.ajax({
            type: 'POST',
            url: '<?php echo base_url('deleteitemcart');?>',
            data: {"id": id, "user_id": user_id, "Authorization": api_key},
            success: function (res) {
                if (res.error == false) {
                    successNotify("Item removed from your cart.", "success");
                    jQuery('[data-id=qty_' +id + ']').addClass('hidden');
                    jQuery('[data-id=btn_' +id + ']').removeClass('hidden');
                    jQuery('[data-id=qtyval_' +id + ']').val(1);
                    mycartdata1();
                   // closeNav();
                } else {
                    errorNotify("Please Try Again", "error");
                }
            }
        });
    }

    function Addcartdata1(id, price, tax) {

        <?php if($isLogin==true){?>

        <?php if(empty($apikey) || !trim($apikey)) {?>
        var api_key = 'not defined';

        <?php }else{?>
        var api_key = '<?php echo $apikey;?>';
        progressNotify('Item adding to cart', 'info');
        <?php }?>
        var qty = jQuery("#qty_" + id).val();
        jQuery.ajax({
            type: 'POST',
            url: '<?php echo base_url("addcartitem"); ?>',
            data: {
                'cart_id': id,
                'qty': qty,
                'price': price,
                'tax': tax,
                'Authorization': api_key
            },
            success: function (res) {
                var catList = res.item;
                var errmsg = res.error;

                if (errmsg == true) {
                    UpdateCart(id);
                }
                else {
                    successNotify("Item added to cart", "success");
                    jQuery('[data-id=qty_' +id + ']').removeClass('hidden');
                    jQuery('[data-id=btn_' +id + ']').addClass('hidden');
                    mycartdata1();
                    if (res.isfirstorder == true) {
                        deleviry_charge =<?php echo $this->config->item('free_deleviry_charge');?>;


                    } else {
                        deleviry_charge =<?php echo $this->config->item('deleviry_charge');?>;

                    }
                    localStorage.setItem("delivery_charge", deleviry_charge);
                }
            }
        });
        return false;
        <?php  }else { ?>

        window.location.href = "<?php echo base_url();?>Login";
        <?php }?>
    }

    function UpdateCart(id) {
        progressNotify('Item quantity updating', 'info');
        <?php if(empty($apikey) || !trim($apikey)) {?>
        var api_key = 'not defined';
        <?php }else{?>
        var api_key = '<?php echo $apikey;?>';
        <?php }?>

        var qty = jQuery("#qty_" + id).val();
        <?php if(empty($apikey) || !trim($apikey)) {?>
        var api_key = 'not defined';
        <?php }else{?>
        var api_key = '<?php echo $apikey;?>';
        <?php }?>

        jQuery.ajax({
            type: 'POST',
            url: '<?php echo base_url("updateitemcart"); ?>',
            data: {
                'cart_id': id,
                'qty': qty,
                'Authorization': api_key
            },
            success: function (res) {
                if (res.error == true) {

                } else {
                    successNotify("Item quantity updated", "success");
                    mycartdata1();
                }
            }
        });
    }

    function logoutdata() {
        jQuery.ajax({
            type: 'POST',
            url: '<?php echo base_url();?>logout',
            data: '',
            success: function (res) {
                if (res.error == true) {
                    //sweetAlert("Oops...", res.message, "error");
                } else {
                    localStorage.clear();
                    window.location.href = '<?php  echo base_url();?>';
                }
            }
        });
    }

   function getbestSeller() {
        var url, data;
        <?php if($isLogin){?>
        var user_id = '<?php echo $userId;?>';
        data = {"user_id": user_id};
        <?php } else { ?>
        data = {"user_id": 0};
        <?php } ?>
        jQuery.ajax({
            type: 'GET',
            url: '<?php echo base_url("getBestSeller"); ?>',
            data: data,
            success: function (res) {
                console.log(res);   
                if(res.error == true){

                }else{
                var catList = res.bestseller;
                var jsonLength = catList.length;
                var cartData = localStorage.getItem('cartData');
                var hiddenClassQty = '';
                var hiddenClassBtn = '';
                var qtyID =1;
                if(cartData != null){
                    cartData = JSON.parse(cartData);
                    var index = cartData.findIndex(cartData => cartData.item_id==catList.item_id);
                    if(index != -1){
                        console.log(index);
                        qtyID = cartData[index].item_quty;
                        hiddenClassQty = '';
                        hiddenClassBtn = 'hidden';
                    }else{
                        hiddenClassQty = 'hidden';
                        hiddenClassBtn = '';
                    }
                }else{
                    hiddenClassQty='hidden';
                    hiddenClassBtn = '';
                }
                var html3 = '';
                html3 += '<div class="slider-items slider-width-col4 products-grid " >';

                for (var i = 0; i < jsonLength; i++) {
                    var result = catList[i];

                    html3 += '<div class="item">\
                            <div class="item-inner">\
                            <div class="item-img item-innerbg">\
                            <div class="item-label11 new-top-left11"></div>\
                            <div class="item-img-info">\
                            <a href="<?php echo base_url();?>productDetail?id=' + result.item_id + '" title="' + result.item_name + '" class="product-image"><img src="<?php echo $this->config->item('api_img_url');?>' + result.item_image + '" onerror="this.src=\'<?php echo $this->config->item('api_img_url');?>upload/item/noprivew.jpg\';"  alt="' + result.item_name + '"></a>\
                            </div>\
                            </div>\
                            <div class="item-info">\
                            <div class="info-inner">\
                            <div class="item-title">\
                            <a href="<?php echo base_url();?>productDetail?id=' + result.item_id + '" class="text-change11" title="' + result.item_name + '">' + titleCase(result.item_name) + '</a> \
                            </div>\
                            <div class="item-content text-left">\
                            <div class="item-price">\
                            <div class="price-box"><span class="regular-price" ><span class="price">$' + parseFloat(result.item_price).toFixed(2) + '</span></span></div>\
                            </div>\
                            <div class="add_cart">\
                            <div class="change-qunty change_qunty_bs slideritems-qty '+hiddenClassQty+'" data-id="qty_'+result.item_id+'" >\
                            <button onClick="minusqtyheader(\'' + result.item_id + '\');" class="qty-item" type="button"><i class="icon-minus">&nbsp;</i></button>\
                            \
                            <span><input type="text" data-id="qtyval_'+result.item_id+'" disabled  name="result.item_id" id="qty_' + result.item_id + '" maxlength="12" value="'+qtyID+'" title="Quantity:" class="result-qtn"></span>\
                            \
                            <button onClick="plusqtyheader(\'' + result.item_id + '\');" class="qty-item" type="button" ><i class="icon-plus">&nbsp;</i></button>\
                            \
                            </div>\
                            <button data-id="btn_'+result.item_id+'" class="button btn-cart '+hiddenClassBtn+'"  type="button" id="cartchange1" onclick=Addcartitemlist(\'' + result.item_id + '\',' + result.item_price + ',' + result.Sales_tax + ');><span>Add to Cart</span></button>\
                            <button class="button btn-cart d-none" disabled="disabled" id="cartchange" type="button" onclick=Addcartitemlist(\'' + result.item_id + '\',' + result.item_price + ',' + result.Sales_tax + ')><span>Adding</span></button>\
                            </div>\
                            </div>\
                            </div>\
                            </div>\
                            </div>\
                            </div>';
                }
                html3 += '</div>';
                jQuery('#best-seller').html(html3);
                jQuery("#best-seller .slider-items").owlCarousel({
                    items: 6,
                    itemsDesktop: [1024, 4],
                    itemsDesktopSmall: [900, 3],
                    itemsTablet: [600, 2],
                    itemsMobile: [320, 1],
                    navigation: !0,
                    navigationText: ['<a class="flex-prev"></a>', '<a class="flex-next"></a>'],
                    slideSpeed: 500,
                    pagination: !1
                })
                }
            }
        });
    }
    function orderStatus(status) {

        if (status == 0) {
            status = 'Pending';
        } else if (status == 1) {
            status = 'For Prepare';
        } else if (status == 2) {
            status = 'For Packed';
        }
        else if (status == 3) {
            status = 'Go for Delivery';
        }
        else if (status == 4) {
            status = 'Delivered';
        }
        else if (status == 5) {
            status = 'Reject';
        } else if (status == 6) {
            status = 'Cancelled';
        }
        return status;
    }

    function unixtimestamp(unix_timestamp) {
        var timeZone = '<?php echo $this->config->item('time_zone'); ?>';
        return moment.unix(unix_timestamp).tz(timeZone).format("MM/DD/YYYY  hh:mm A");
    }

    function _unixtimestamp(unix_timestamp) {
        var timeZone = '<?php echo $this->config->item('time_zone'); ?>';
        return moment.unix(unix_timestamp).tz(timeZone).format("DD-MM-YYYY  hh:mm A");
    } 
    function roundoffvalue(value) {
        var totalAmount = Math.round(value * 100) / 100;

        return totalAmount;
    }
    function updatecartvalue() { //localStorage.clear();
        jQuery(".loaderbutton5").text("loading..");
        jQuery(".loaderbutton5").attr("disabled", true);
        <?php if(empty($apikey) || !trim($apikey)) {?>
        var api_key = 'not defined';
        <?php }else{?>
        var api_key = '<?php echo $apikey;?>';
        <?php }?>
        // var item_id='<?php //echo $itemIds;?>';
        var itemArray = localStorage.getItem("totalitemarray");
        var item_id = itemArray;

        var price = localStorage.getItem("total_am");
        var tac = localStorage.getItem("totaltax");
        var deleviry_charge;

        if (price < 10) {
            jQuery(".loaderbutton5").text("Proceed to Checkout");
            jQuery(".loaderbutton5").removeAttr("disabled");
            sweetAlert("", "Please add more items to your cart to reach order minimum ($10)", "warning");
            return false;
        }
        jQuery.ajax({
            type: 'POST',
            url: '<?php echo base_url("updatecartvalue");?>',
            data: {
                'item_id': item_id,
                'Authorization': api_key
            },
            success: function (res) {

                jQuery(".loaderbutton5").text("Proceed to Checkout");
                jQuery(".loaderbutton5").removeAttr("disabled");
                if (res.error == true) {
                    sweetAlert("", "Something went wrong Please Try Again", "warning");
                } else {
                    if (res.isfirstorder == true) {
                        deleviry_charge =<?php echo $this->config->item('free_deleviry_charge');?>;
                    } else {
                        deleviry_charge =<?php echo $this->config->item('deleviry_charge');?>;
                    }
                    localStorage.setItem("delivery_charge", deleviry_charge);
                    window.location.href = '<?php echo base_url();?>checkout';
                }
            }
        });
    }

    function shoppingcartdata() {
        var shoppingcartData = myCartData;
        jQuery('#loaderprofile1').addClass('hidden');
        var html1 = '';
        var html2 = '';
        var html3 = '';
        if (shoppingcartData.error == false) {
            var shopcatList = shoppingcartData.item;
            var jsonLength = shopcatList.length;
            var $total = 0;
            var taxcal = 0;
            var processing_fee =<?php echo $this->config->item('processing_fee');?>;
            var deleviry_charge = localStorage.getItem("delivery_charge");
            deleviry_charge = parseFloat(deleviry_charge);
            var delivery_charges_label = localStorage.getItem("delivery_charges_label");
            var discount_amnt = localStorage.getItem("discount_amnt");
            discount_amnt = parseFloat(discount_amnt).toFixed(2);
            var discount_label = localStorage.getItem("discount_label");
            var deleviry_chargeold =<?php echo $this->config->item('deleviry_chargeold');?>;
            var totalfinalamountvalue = 0.00;
            var count = 1;
            var salestax = 0;
            var arr = [];
            for (var i = 0; i < jsonLength; i++) {
                count = count + 1;
                var result = shopcatList[i];
                arr.push(result.item_id);
                if (result.Sales_tax > salestax) {
                    salestax = result.Sales_tax;
                }
                $total =  parseFloat($total) + parseFloat(result.total);
                var totalAmount = Math.round($total * 100) / 100;
                console.log(totalAmount);
                localStorage.setItem("total_am", totalAmount);
                localStorage.setItem("itemID", result.item_id);
                html1 += '<tr class="odd">\
                      <td class="image hidden-table odd-table">\
                      <a href="<?php echo base_url();?>productDetail?id=' + result.item_id + '" title="Women&#39;s Georgette Animal Print" class="product-image">\
                      <img src="<?php echo $this->config->item('api_img_url');?>' + result.item_image + '" width="75" onerror="this.src=\'<?php echo $this->config->item('api_img_url');?>upload/item/noprivew.jpg\';" alt="Women Georgette Animal Print">\
                      </a>\
                      </td>\
                      <td class="set-tdwidth">\
                      <h2 class="product-name">\
                      <a href="<?php echo base_url();?>productDetail?id=' + result.item_id + '">' + result.item_name + '</a>\
                      </h2>\
                      </td>\
                      <td class="a-right hidden-table">\
                      <span class="cart-price">\
                      <span class="price price-green">$' +parseFloat(result.item_price).toFixed(2) + '</span>\
                      </span>\
                      </td>\
                      <td class="a-right hidden-table">\
                      <span class="cart-price">\
                      <span class="price">' + result.Sales_tax + '%</span>\
                      </span>\
                      </td>\
                      <td class="a-center movewishlist">\
                      <div class="change-qunty text-center">\
                      <button onclick="minusqtyheader(\'' + result.item_id + '\');" class="items-count items-count-cart shoppingcard-min" type="button"><i class="icon-minus">&nbsp;</i></button>\
                      <span><input data-id="qtyval_'+result.item_id+'" class="set-inputmargin card-count" type="text" disabled name="qty" id="cartupdateqty_' + result.item_id + '" maxlength="12" value="' + result.item_quty + '" title="Quantity:" class="card-count"></span>\
                      <button onclick="plusqtyheader(\'' + result.item_id + '\');" class="items-count items-count-cart  shoppingcard-plus" type="button"><i class="icon-plus">&nbsp;</i></button>\
                      </div>\
                      </td>\
                      <td class="movewishlist">\
                      <span class="cart-price">\
                      <span class="price5555 price-green" id="totalprice_' + result.item_id + '">$' + parseFloat(result.total).toFixed(2) + '</span>\
                      </span>\
                      </td>\
                      <td class="a-center last"><a href="javascript:void(0)" title="Remove item" onclick="delete_cart1(\'' + result.item_id + '\')" class="button remove-item"><span><span>Remove item</span></span></a>\
                    </td></tr>';
                taxcal = taxcal + (((result.item_price * result.item_quty) * result.Sales_tax) / 100);
            }
            localStorage.setItem("totalitemarray", arr);
            taxcal = roundoffvalue(taxcal + ((deleviry_charge * salestax) / 100));
            localStorage.setItem("totaltax", taxcal);
            totalfinalamountvalue = roundoffvalue($total + taxcal + processing_fee + deleviry_charge);
            var subfinalamount = totalfinalamountvalue;
            totalfinalamountvalue = (subfinalamount -discount_amnt);
            localStorage.setItem("sub_total_Am0unt", subfinalamount);
            localStorage.setItem("total_Am0unt", totalfinalamountvalue);
            html3 += '<tr>\
                    <td class="a-left" colspan="1">Total</td>\
                    <td class="a-right">\
                    <span class="priceq" id="">$' + subfinalamount.toFixed(2) + '</span>\
                    </td>\
                    </tr>\
                    <tr>\
                    <td class="a-left" colspan="1">\
                    '+ discount_label +'</td>\
                    <td class="a-right">\
                    <span class="priceq praice-setblock" id="">- $' + discount_amnt + '</span></strong>\
                    </td>\
                    </tr>\
                    <tr class="comon-bgtr">\
                    <td class="a-left comon-colortd" colspan="1">\
                    <strong>Grand Total</strong>\
                    </td>\
                    <td class="a-right comon-colortd">\
                    <strong><span class="priceq" id="total_amount77443">$' + parseFloat(totalfinalamountvalue).toFixed(2) + '</span></strong>\
                    </td>\
                    </tr>';
            html2 += '<tr>\
                    <td class="a-left" colspan="1">Subtotal</td>\
                    <td class="a-right"><span class="price" id="total_amount">$' + parseFloat(totalAmount).toFixed(2) + '</span></td>\
                    </tr>\
                    <tr>\
                    <td class="a-left" colspan="1">Sales  Tax</td>\
                    <td class="a-right"><span class="text" id="taxval" >$' + parseFloat(taxcal).toFixed(2) + '</span></td>\
                    </tr>\
                    <tr>\
                    <td  class="a-left" colspan="1">Processing Fee    </td>\
                    <td  class="a-right"><span class="price445" id="fee"></span>$' + processing_fee + ' </td>\
                    </tr>\
                    <tr>\
                    <td  class="a-left" colspan="1">'+delivery_charges_label+' <!--<span class="pull-right">Promo: Stay Warm</span>--></td>\
                    <td  class="a-right"><span class="price1" id="coupencode">&nbsp;&nbsp;$' + parseFloat(deleviry_charge).toFixed(2) + '</span></td>\
                    </tr>';
        }else {
            html1 += '<tr class="odd"><td class="td-full"><h2 class="product-name">' + shoppingcartData.message + '</h2></td><tr>';
        }
        jQuery('#showsessiondata').html(html2);
        jQuery('#showsessiondata1').html(html3);
        jQuery('.cartshow').html(html1);
    }

    function getcategory() {
        jQuery.ajax({
            type: 'GET',
            url: '<?php echo base_url("index.php/main/getCategory"); ?>',
            data: '',
            success: function (res) {
                jQuery('#loaderprofile').addClass('hidden');
                categoryData = res;
                var catList = res.category;
                var jsonLength = catList.length;
                var html = '';
                for (var i = 0; i < jsonLength; i++) {
                    var result = catList[i];
                    var desclaimer = result.disclaimer.replace(/'/g, "\\'");
                    html += '<li class="item col-lg-3 col-md-3 col-sm-3 col-xs-6">\
                             <div class="item-inner">\
                            <div class="item-img  common-bordernone">\
                            <div class="item-img-info center-image1 " >\
                            <a onclick="DisclaimerPopup(event, \''+desclaimer+'\', '+result.id+')" href="javascript:void(0);" title="' + result.cat_name + '" class="product-image"><img src="<?php echo $this->config->item('api_img_url');?>' + result.image + '" alt="' + result.cat_name + '" >\
                            </a>\
                            </div>\
                            </div>\
                            <div class="item-info">\
                            <div class="info-inner">\
                            <div class="item-title"><a onclick="DisclaimerPopup(event, \''+desclaimer+'\', '+result.id+')" href="javascript:void(0);" title="' + result.cat_name + '">' + titleCase(result.cat_name) + '</a> </div>\
                            </div>\
                            </div>\
                            </div>\
                            </li>';
                }
                jQuery('#cat_product_grid').html(html);
                navigationcategorymenu();
            }
        });
    }

    function navigationcategorymenu() {
        var catList = categoryData.category;
        var jsonLength = catList.length;
        var html = '';
        for (var i = 0; i < jsonLength; i++) {
            var result = catList[i];
            var desclaimer = result.disclaimer.replace(/'/g, "\\'");
            html += '<div class="col-lg-2 col-md-3 col-sm-4 col-xs-4">\
                <div class="push_img center-image1 imgcenter">\
                <a onclick="DisclaimerPopup(event, \''+desclaimer+'\', '+result.id+')" href="javascript:void(0);"><img src="<?php echo $this->config->item('api_img_url');?>' + result.image + '" alt="menu item 4" width="100%; height:120px;">\
                <div class="nav_cate">' + titleCase(result.cat_name) + '</div>\
                </a>\
                </div>\
                </div>';
        }
        jQuery('#catdepartmnet').html(html);
    }


    function pincodecheck(zip) {
        var id = zip;
        jQuery.ajax({
            type: 'GET',
            url: '<?php echo base_url("checkzipcode"); ?>',
            data: {'zipcode': zip},
            beforeSend: function ()
            {
                ajaxindicatorstart('Change zip code...');
            },
            success: function (res) {
                ajaxindicatorstop();
                if (res.error == true) {
                    swal("Zip code is not avaliable");
                    location.reload();
                } else {
                    window.location.href = res.redirectto;
                }
            }
        });
        return false;
    }

    function mycartdatarightsidebar() {
        <?php if(empty($apikey) || !trim($apikey)) {?>
        var api_key = 'not defined';
        <?php }else{?>
        var api_key = '<?php echo $apikey;?>';
        <?php }?>
        var html1 = '';
        var grandTotal = 0;
        if (myCartData.error == true) {
            jQuery('#carttotalitem').text(0);
            <?php if ($isLogin==true) { ?>
            html1 += '<div class="summary">\
            <p class="amount">' + myCartData.message + '</p>\
            </div>';
            <?php }else {?>
            html1 += '<div class="summary">\
                    <p class="amount">Please Login to view your cart</p>\
              </div>';
            <?php } ?>
        } else {
            if (api_key != 'not defined') {
                var catList = myCartData.item;
                var jsonLength = catList.length;
                var jsonLength1 = 1;
                jQuery('#carttotalitem').text(jsonLength);
                var count = 1;
                for (var i = 0; i < jsonLength; i++) {
                    count = count + 1;
                    var result = catList[i];
                    grandTotal = roundoffvalue(parseFloat(grandTotal) + parseFloat(result.total));
                    var url = '<?php echo base_url();?>';
                    html1 += '<li class="item">\
                                <div class="item-inner">\
                                <a href="<?php echo base_url();?>productDetail?id=' + result.item_id + '" class="product-image">\
                                <img src="<?php echo $this->config->item('api_img_url');?>' + result.item_image + '" width="80" onerror="this.src=\'<?php echo $this->config->item('api_img_url');?>upload/item/noprivew.jpg\';" alt="product">\
                                </a>\
                                <div class="product-details">\
                                <div class="access"> <a href="javascript:void(0);" onclick="delete_cart1(\'' + result.item_id + '\')" class="btn-remove1">Remove</a>\
                                </div>\
                                <p class="product-name"><a href="<?php echo base_url();?>productDetail?id=' + result.item_id + '" >' + titleCase(result.item_name) + '</a>\
                            </p>\
                            <strong>\
                            <div class="change-qunty">\
                            <button  onClick="minusqtyheader(\'' + result.item_id + '\');" class="reduced items-count pad-colcount" type="button"><i class="icon-minus">&nbsp;</i></button>\
                              <span><input data-id="qtyval_'+result.item_id+'" type="text" disabled  name="qty211" id="qty1_' + result.item_id + '" maxlength="12" value="' + result.item_quty + '" title="Quantity:" class="input-text input-text-01 qty"></span>\
                               <button  onClick="plusqtyheader(\'' + result.item_id + '\');" class="increase items-count pad-colcount" type="button" ><i class="icon-plus">&nbsp;</i></button>\
                            </div>\
                            </strong> x <span class="price">$' + parseFloat(result.item_price).toFixed(2) + '</span>\
                            <p class="subtotal"> <span class="label">Subtotal:</span> <span class="price" id="totalprice' + result.item_id + '">$' + parseFloat(result.total).toFixed(2) + '</span> </p>\
                            </div>\
                           </div>\
                            </li>';
                }
            } else {
                html1 = '<div class="summary">\
                    <p class="amount">You Are Not Login User <a href="<?php echo base_url();?>Login"><span>Please Login To View</span></a> your cart.</p>\
              </div>';
            }
        }
        jQuery('#grandTotal').text(grandTotal);
        jQuery('#cart_item_list').html(html1);
    }

    function plusqty1(id) {
        progressNotify('Item quantity updating', 'info');
        <?php if(empty($apikey) || !trim($apikey)) {?>
        var api_key = 'not defined';
        <?php }else{?>
        var api_key = '<?php echo $apikey;?>';
        <?php }?>
        var result = document.getElementById("qty1_" + id);
        var qty11 = parseInt(result.value);
        if (!isNaN(qty11)) {
            var qty_valid = qty11 + 1;
            jQuery.ajax({
                type: 'POST',
                url: '<?php echo base_url("updateitemcart"); ?>',
                data: {
                    'cart_id': id,
                    'qty': qty_valid,
                    'Authorization': api_key
                },
                success: function (res) {
                    if (res.error == true) {

                    } else {
                        jQuery("#qty1_" + id).val(qty_valid);
                        jQuery('[data-id=qtyval_' +id + ']').val(qty_valid);
                        jQuery("#totalprice" + id).text(parseFloat(res.total).toFixed(2));
                        successNotify("Item quantity updated", "success");
                        mycartdata1();
                    }
                }
            });
        }
        return false;
    }

    function minusqty1(id) {

        <?php if(empty($apikey) || !trim($apikey)) {?>
        var api_key = 'not defined';
        <?php }else{?>
        var api_key = '<?php echo $apikey;?>';
        <?php }?>
        var result = document.getElementById('qty1_' + id);
        var qty12 = parseInt(result.value);
        if (qty12 > 1) {
            progressNotify('Item quantity updating', 'info');
            var qtyvaminusl = qty12 - 1;
            jQuery.ajax({
                type: 'POST',
                url: '<?php echo base_url("updateitemcart"); ?>',
                data: {
                    'cart_id': id,
                    'qty': qtyvaminusl,
                    'Authorization': api_key
                },
                success: function (res) {
                    if (res.error == true) {

                    } else {
                        successNotify("Item quantity updated", "success");
                        mycartdata1();
                        jQuery('[data-id=qtyval_' +id + ']').val(qtyvaminusl);
                        jQuery("#qty1_" + id).val(qtyvaminusl);
                        jQuery("#totalprice" + id).text(parseFloat(res.total).toFixed(2));
                    }
                }
            });
        }
        return false;
    }
    function minusqtyheader(id) {
        <?php if(empty($apikey) || !trim($apikey)) {?>
        var api_key = 'not defined';
        <?php }else{?>
        var api_key = '<?php echo $apikey;?>';
        <?php }?>
       // var result = document.getElementById('qtyheader_' + id);
        var quantity = jQuery('[data-id=qtyval_' +id + ']').val();
        var qty12 = parseInt(quantity);
        if (qty12 > 1) {
            progressNotify('Item quantity updating', 'info');
            var qtyvaminusl = qty12 - 1;
            jQuery.ajax({
                type: 'POST',
                url: '<?php echo base_url("updateitemcart"); ?>',
                data: {
                    'cart_id': id,
                    'qty': qtyvaminusl,
                    'Authorization': api_key
                },
                success: function (res) {
                    if (res.error == true) {

                    } else {
                        successNotify("Item quantity updated", "success");
                        if(qtyvaminusl == 0){
                            jQuery('[data-id=qty_' +id + ']').addClass('hidden');
                            jQuery('[data-id=btn_' +id + ']').removeClass('hidden');
                        }
                        mycartdata1();
                        jQuery('[data-id=qtyval_' +id + ']').val(qtyvaminusl);
                        //jQuery("#headertotalamount_" + id).text(res.total.toFixed(2));
                        jQuery("#totalprice_"+id).text(parseFloat(res.total).toFixed(2));
                        jQuery("#qtyheader_" + id).val(qtyvaminusl);
                    }
                }
            });
        }else{
            delete_cart1(id);
        }
        //return false;
    }

    function plusqtyheader(id) {
        progressNotify('Item quantity updating', 'info');
        <?php if(empty($apikey) || !trim($apikey)) {?>
        var api_key = 'not defined';
        <?php }else{?>
        var api_key = '<?php echo $apikey;?>';
        <?php }?>
        //var result = document.getElementById("qtyheader_" + id);
        var quantity = jQuery('[data-id=qtyval_' +id + ']').val();
        var qty11 = parseInt(quantity);
        if (!isNaN(qty11)) {
            var qty_valid = qty11 + 1;

            jQuery.ajax({
                type: 'POST',
                url: '<?php echo base_url("updateitemcart"); ?>',
                data: {
                    'cart_id': id,
                    'qty': qty_valid,
                    'Authorization': api_key
                },
                success: function (res) {
                    if (res.error == true) {
                        errorNotify(res.message,"error");
                        UpdateCartitemlist(id);
                    } else {
                        successNotify('Item quantity updated', 'success');
                        mycartdata1();
                        jQuery('[data-id=qtyval_' +id + ']').val(qty_valid);
                        jQuery("#qtyheader_" + id).val(qty_valid);
                        //jQuery("#headertotalamount_" + id).text(res.total);
                        // for shopping cart
                        jQuery("#totalprice_"+id).text(parseFloat(res.total).toFixed(2));
                        // location.reload();
                    }
                }
            });
        }
        return false;
    }

    function plusqty(id) {
        var result = document.getElementById("qty_" + id);
        var qty = result.value;
        if (!isNaN(qty))
            result.value++;
        return false;
    }

    function minusqty(id) {
        var result = document.getElementById('qty_' + id);
        var qty = result.value;
        if (result.value > 1) {
            result.value--;
            return false;
            //$rooms.val(b);
        }
        else {
            //  $("#subs").prop("disabled", true);
        }
    }

    function plusqtyitemlist(id) {
        var result = document.getElementById("qtyitem_" + id);
        var qty = result.value;
        if (!isNaN(qty))
            result.value++;
        return false;
    }

    function minusqtyitemlist(id) {
        var result = document.getElementById('qtyitem_' + id);
        var qty = result.value;
        if (result.value > 1) {
//        if( !isNaN( qty ))
            result.value--;
        }
        return false;
    }

    function Addcartitemlist(id, price, tax) {

        <?php if($isLogin==true){?>
        <?php if(empty($apikey) || !trim($apikey)) {?>
        var api_key = 'not defined';
        <?php }else{?>
        var api_key = '<?php echo $apikey;?>';
        progressNotify('Item adding to cart', 'info');
        <?php }?>
        //var qty = jQuery("#qtyitem_" + id).val();
        var qty = jQuery('[data-id=qtyval_' +id + ']').val();
        jQuery.ajax({
            type: 'POST',
            url: '<?php echo base_url("addcartitem"); ?>',
            data: {
                'cart_id': id,
                'qty': qty,
                'price': price,
                'tax': tax,
                'Authorization': api_key
            },
            success: function (res) {
                var catList = res.item;
                var errmsg = res.error;
                if (errmsg == true) {
                    errorNotify(res.message,"error");
                    UpdateCartitemlist(id);
                }else {
                    successNotify("Item added to cart", "success");
                    jQuery('[data-id=qty_' +id + ']').removeClass('hidden');
                    jQuery('[data-id=btn_' +id + ']').addClass('hidden');

                    mycartdata1();
                    if (res.isfirstorder == true) {
                        deleviry_charge =<?php echo $this->config->item('free_deleviry_charge');?>;
                    } else {
                        deleviry_charge =<?php echo $this->config->item('deleviry_charge');?>;
                    }
                    localStorage.setItem("delivery_charge", deleviry_charge);
                }
            }
        });
        return false;
        <?php  }else { ?>

        window.location.href = "<?php echo base_url();?>Login";
        <?php }?>
    }

    function UpdateCartitemlist(id) {

        <?php if(empty($apikey) || !trim($apikey)) {?>
        var api_key = 'not defined';
        <?php }else{?>
        var api_key = '<?php echo $apikey;?>';
        <?php }?>

        var qty = jQuery("#qtyitem_" + id).val();
        <?php if(empty($apikey) || !trim($apikey)) {?>
        var api_key = 'not defined';
        <?php }else{?>
        var api_key = '<?php echo $apikey;?>';
        <?php }?>

        jQuery.ajax({
            type: 'POST',
            url: '<?php echo base_url("updateitemcart"); ?>',
            data: {
                'cart_id': id,
                'qty': qty,
                'Authorization': api_key
            },
            success: function (res) {
                if (res.error) {

                } else {
                    successNotify("Item quantity updated", "success");
                    mycartdata1();
                }
            }
        });
    }


    function contentdata(tips_arr) {
        var price = localStorage.getItem("total_am");
        price = parseFloat(price);

        jQuery("#loaderorder").removeClass('loading');
        var discount_amnt = localStorage.getItem("discount_amnt");
        discount_amnt = parseFloat(discount_amnt).toFixed(2);
        var sub_total_Am0unt = localStorage.getItem("sub_total_Am0unt");
        sub_total_Am0unt = parseFloat(sub_total_Am0unt);
        total_Am0unt = localStorage.getItem("total_Am0unt");
        total_Am0unt = parseFloat(total_Am0unt);
        var authorizePercent = '<?php echo $this->config->item('authorizeAmountpercent');?>';
        authorizeAmount = calculateAuthorizeAmt(total_Am0unt);
        jQuery("#authAmount").text('$' + authorizeAmount);
        var html2 = '';
        var html3 = '';
        var totaltax = localStorage.getItem("totaltax");
        totaltax = parseFloat(totaltax);
        console.log(totaltax);
        var processing_fee = <?php echo $this->config->item('processing_fee');?>;
        var deleviry_charge = localStorage.getItem("delivery_charge");
        deleviry_charge = parseFloat(deleviry_charge);
        var delivery_charges_label = localStorage.getItem("delivery_charges_label");
        var deleviry_chargeold = <?php echo $this->config->item('deleviry_chargeold');?>;
        var new_tips_section = set_tip(tips_arr);
        var tipamount = localStorage.getItem("tipamount");
        sub_total_Am0unt = sub_total_Am0unt+parseFloat(tipamount);
        total_Am0unt = sub_total_Am0unt - discount_amnt;
        localStorage.setItem("total_Am0unt",total_Am0unt);
        var discount_label = localStorage.getItem("discount_label");
        html2 += '<tr>\
                        <td  class="a-left" colspan="1">\
                        Subtotal    </td>\
                        <td class="a-right">\
                        <span class="price11" id="cartpriceval11">$' + parseFloat(price).toFixed(2) + '</span>  </td>\
                    </tr>\
                     <tr>\
                    <td class="a-left" colspan="1">\
                        Sales  Tax  </td>\
                    <td  class="a-right">\
                        <span class="text" id="taxval" >$' + parseFloat(totaltax).toFixed(2) + '</span></td>\
                    </tr>\
                    <tr>\
                    <td  class="a-left" colspan="1">\
                        Processing Fee </td>\
                    <td  class="a-right">\
                        <span class="text" id="fee" >$' + processing_fee + '</span>    </td>\
                    </tr>\
                    <tr>\
                    <td class="a-left" colspan="1">\
                        '+delivery_charges_label+'  <!--<span class="pull-right">Promo: Stay Warm</span> -->  </td>\
                    <td class="a-right">\
                        <span class="price1" id="coupencode14">&nbsp;&nbsp;$' + parseFloat(deleviry_charge).toFixed(2) + '</span>    </td>\
                    </tr>\
                    <tr class="tr-bgadd">'+new_tips_section+'</tr>';
                    
        html3 += '<tr>\
                    <td class="a-left" colspan="1">\
                        Total  <!--<span class="pull-right">Promo: Stay Warm</span> -->  </td>\
                    <td  class="a-right">\
                        <span class="price1" id="subtotalcartprice">&nbsp;&nbsp;$' + parseFloat(sub_total_Am0unt).toFixed(2) + '</span>    </td>\
                    </tr>\
                    <tr>\
                    <td  class="a-left" colspan="1">\
                        '+ discount_label +'  <!--<span class="pull-right">Promo: Stay Warm</span> -->  </td>\
                    <td class="a-right">\
                        <span class="price1" id="discounamount">&nbsp;&nbsp;-$' + parseFloat(discount_amnt).toFixed(2)+ '</span>    </td>\
                    </tr>\
                    <tr>\
                        <td class="a-left" colspan="1">\
                        <strong>Grand Total</strong>\
                    </td>\
                    <td class="a-right">\
                        <strong><span class="price" id="finalcartprice">$' + parseFloat(total_Am0unt).toFixed(2) + '</span></strong>\
                    </td>\
                    </tr>';
        jQuery('.Total_Amount34show').html(html2);
        jQuery('.Total_Amount35show').html(html3);
    }

    function set_tip(tips_arr){
        var tips = tips_arr.tips;
        var price = localStorage.getItem("total_am");

        var default_tip = parseFloat(price * tips_arr.default * 0.01).toFixed(2);

        add_tip_to_gtotal(default_tip);


        var new_tips_section = '<td colspan="2">\
                                    <div> \
                                        <p class="d-inlineblock"> $ Select a tip amount </p>\
                                        <span class="pull-right" id="tip_percent_nd_amount">'+tips_arr.default+'% <b> | </b> $<span id="tipamount">'+default_tip+'</span></span>\
                                    </div>\
                                    <div class="col-md-12 no-pad">\
                                        <div class="col-md-8 no-pad">';
        for(var i=0; i<tips.length; i++) {
            var active = '';
            var tip_amount = parseFloat(price * tips[i] * 0.01).toFixed(2);
            var onclick_str = "add_tip_to_gtotal("+tip_amount+","+tips[i]+")";
            if(tips[i] == tips_arr.default){
                active = 'tip_atm_active';
            }
            new_tips_section += '<div id="tip_'+tips[i]+'" class="tip_amt '+active+'" onclick="'+onclick_str+'"> \
                                    <p> '+tips[i]+'% </p>\
                                    <p> $'+tip_amount+' </p> \
                                </div>';
        }
            new_tips_section += '</div> \
                                    <div class="col-md-4 text-right no-pad"> \
                                        <button class="button btn custm_btn" onclick="input_custom_tip()"> Custom</button>\
                                    </div>\
                                </div>\
                            </td>';
                                
        return new_tips_section;
    }

    function input_custom_tip(){
        swal({
                title: "Tip!",
                text: "Enter your tip amount",
                type: "input",
                input: "number",
                showCancelButton: true,
                closeOnConfirm: true,
                animation: "slide-from-top"
            },
            function(tip){
                if (tip === false) return false;

                if (tip === "") {
                    swal.showInputError("Please enter a tip amount");
                    return false
                }

                if (tip < 0) {
                    tip = 0;
                }

                jQuery(".tip_atm_active").removeClass("tip_atm_active");
                add_tip_to_gtotal(tip, 'na');
            });
    }

    function add_tip_to_gtotal(tip, tip_percent){
        //var actualamount = localStorage.getItem("total_Am0unt");
        var actualamount = localStorage.getItem("sub_total_Am0unt");
        var discount_amnt = localStorage.getItem("discount_amnt");
        var discount_amnt = parseFloat(discount_amnt);
        var total = parseFloat(actualamount);
        var tip = parseFloat(tip);
        //---------------05_12_18---------------
        localStorage.setItem("tipamount",parseFloat(tip).toFixed(2));
        //---------------05_12_18---------------
        var total_w_tip = tip + total;
        total_Am0unt = total_w_tip - discount_amnt;
        authorizeAmount = calculateAuthorizeAmt(parseFloat(total_Am0unt).toFixed(2));
        jQuery('#subtotalcartprice').text("$" + parseFloat(total_w_tip).toFixed(2));
        jQuery('#discounamount').text("-$" + parseFloat(discount_amnt).toFixed(2));
        jQuery('#finalcartprice').text("$" + parseFloat(total_Am0unt).toFixed(2));
        jQuery('#authAmount').text("$" + authorizeAmount);
        var tip_str = '';
        if(tip_percent == 'na'){
            tip_str = '$<span id="tipamount">'+tip+'</span>';
        } else {
            tip_str = tip_percent+'% <b> | </b> $<span id="tipamount">'+tip+'</span>';
            jQuery(".tip_atm_active").removeClass("tip_atm_active");
            jQuery('#tip_'+tip_percent).addClass('tip_atm_active');
        }
        jQuery('#tip_percent_nd_amount').html(tip_str);
        return true;
    }
    
    function calculateAuthorizeAmt(totalAmount) {
        var authorizePercent = '<?php echo $this->config->item('authorizeAmountpercent');?>';
        var authorizeAmt = parseFloat(totalAmount) + parseFloat(authorizePercent) * parseFloat(totalAmount) / 100;
        authorizeAmt = 5 * (Math.ceil(authorizeAmt / 5));
        authorizeAmt = authorizeAmt.toFixed(2);
        return authorizeAmt;
    }

    function formatedDate(date1) {
        var timeZone = '<?php echo $this->config->item('time_zone'); ?>';
        return moment.tz(date1, timeZone).format("DD-MM-YYYY"); 
    }

    function titleCase(str) {
        var splitStr = str.toLowerCase().split(' ');
        for (var i = 0; i < splitStr.length; i++) {
            splitStr[i] = splitStr[i].charAt(0).toUpperCase() + splitStr[i].substring(1);
        }
        return splitStr.join(' ');
    }

    function getCardImage(Type) {
        if (Type == 'Discover')
            return "<?php echo base_url('public/assets/images/discover.png'); ?>";
        else if (Type == 'Visa')
            return "<?php echo base_url('public/assets/images/payment-2.png'); ?>";
        else if (Type == 'AmericanExpress')
            return "<?php echo base_url('public/assets/images/payment-3.png'); ?>";
        else if (Type == 'MasterCard')
            return "<?php echo base_url('public/assets/images/payment-4.png'); ?>";
        else if (Type == 'DinersClub')
            return "<?php echo base_url('public/assets/images/dinnerclub.png'); ?>";
        else if (Type == 'JCB')
            return "<?php echo base_url('public/assets/images/jcb.png'); ?>";
        else
            return "<?php echo base_url('public/assets/images/default_card.png'); ?>";
    }

    function DisclaimerPopup(e, desclaimer, catId){

        if(desclaimer !== ""){
            swal({
                title: "Disclaimer",
                text: desclaimer,
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                closeOnConfirm: true,
                closeOnCancel: true
            },function(isConfirm){
                if(isConfirm) window.location.href = "<?php echo base_url();?>getlistitem?id=" + catId; else return false;
            });
        }else{
            window.location.href = "<?php echo base_url();?>getlistitem?id=" + catId;
        }
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

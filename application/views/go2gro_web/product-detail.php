<?php
include 'header.php' ;
$id=$_GET['id'];
?>


<div class="page-heading d-none">
</div>
<!-- BEGIN Main Container -->
<div class="main-container col1-layout wow bounceInUp animated product-detail-sec">
  <div class="main mart140">
    <div class="col-main">
      <!-- Endif Next Previous Product -->
      <div class="product-view wow bounceInUp animated" itemscope="" itemtype="http://schema.org/Product" itemid="#product_base">
        <div id="messages_product_view"></div>
        <!--product-next-prev-->
        <div class="product-essential container them-bg">
          <div class="row" id="productDescinfo">

          </div>
        </div>

        <!--product-essential-->
        <div class="product-collateral container">
          <ul id="product-detail-tab" class="nav nav-tabs product-tabs">
            <li class="active"> <a href="#disclaimer" data-toggle="tab">Disclaimer</a> </li>
            <li > <a href="#product_tabs_description" data-toggle="tab"> Product Ingredients </a> </li>
          </ul>
          <div id="productTabContent" class="tab-content">
            <div class="tab-pane fade in active description-pg" id="disclaimer">
              <div class="std">
                <p>Product images, ingredients, nutrition facts and other packaging details, in some cases, may not be current or complete. We recommend our customers to always check the product's information physically before using or consuming. Go2Gro aims to present absolutely correct and accurate product information but cannot ensure the accuracy of any product information presented on this website. For absolute correct information, please refer to the manufacturer of the products. </p>
                <p>Go2Gro has a strict policy of selling Cigarettes and Tobacco items to 18+ customers only. Upon delivery you will be asked to present an identification card for age verification purpose. Any customer found being under the age of 18 or presenting fraudulent identity card or document will not receive the order and refund will not be issued in such case. The law enforcement authorities will also be notified.</p>
              </div>
            </div>
            <div class="tab-pane fade description-pg" id="product_tabs_description">
              <div class="std">
                <p></p>
              </div>
            </div>
          </div>
        </div>
        <!--product-collateral-->
          <div class="box-additional">
              <!-- BEGIN RELATED PRODUCTS -->
              <div class="related-pro container">
                  <div class="slider-items-products">
                      <div class="new_title center">
                          <h2>Best Seller</h2>
                      </div>
                      <div id="best-seller" class="product-flexslider hidden-buttons">
                      </div>
                  </div>
              </div>
              <!-- end related product -->

          </div>
        <!-- end related product -->
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
      </div>
      <!--box-additional-->
      <!--product-view-->
    </div>
  </div>
  <!--col-main-->
</div>
<!--main-container-->
<div class="modal fade" id="myModal" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Modal Header</h4>
      </div>
      <div class="modal-body">
        <p>Some text in the modal.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<?php include 'footer.php' ;?>

<script !src="">
    getListDescription();

    function getListDescription() {
        var itemId = '<?php echo $id;?>';
        jQuery.ajax({
            type: 'GET',
            url: '<?php echo base_url("getItemDescription"); ?>',
            data: {
                'itemId': itemId
            },
            success: function(res) {
                var catList = res.item;

                console.log(catList);
                var cartData = localStorage.getItem('cartData');
                var hiddenClassQty = '';
                var hiddenClassBtn = '';
                var qtyID =1;
                if(cartData != null && cartData!='undefined'){
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

                var html = '';
                var count = 1;
              var imagesgallry=res.item.images;
              console.log(imagesgallry);
              var imagegallrylength=imagesgallry.length;
              for(var j=0; j<imagegallrylength;j++)
              {
                var result=imagesgallry[j];
                var thumbnail="<?php echo $this->config->item('api_img_url');?>"+result.imageurl;
              }

html +='<form action="" method="post" id="product_addtocart_form">\
    <div class="product-img-box  col-sm-6 col-xs-12">\
	                     <div class="product-left">\
                        <div class="new-label new-top-left"> New </div>\
                        <div class="product-image">\
                        <div class="large-image"> <a href="<?php echo $this->config->item('api_img_url');?>'+catList.item_image+'" class="cloud-zoom" id="zoom1" rel="useWrapper: false, zoomWidth:\'143\', zoomHeight:\'143\', adjustY:8, adjustX:2"> <img src="<?php echo $this->config->item('api_img_url');?>'+catList.item_image+'" onerror="this.src=\'<?php echo $this->config->item('api_img_url');?>upload/item/noprivew.jpg\';"> </a> </div>\
                        <div class="flexslider flexslider-thumb">\
                        <ul class="previews-list slides">\
                        <li><a href="'+thumbnail+'" class="cloud-zoom-gallery" rel="useZoom: \'zoom1\', smallImage: \''+thumbnail+'\'"><img src="'+thumbnail+'" onerror="this.src=\'<?php echo $this->config->item('api_img_url');?>upload/item/noprivew.jpg\';" alt = "Thumbnail 1"/></a></li>\
                           </ul>\
                        </div>\
                        </div>\
						 </div>\
                       </div>\
                        <div class="product-shop col-sm-6 col-xs-12">\
						 <div class="product-right">\
                         <div class="product-name">\
                        <h1 itemprop="name">'+catList.item_name+'</h1>\
                    </div>\
                  <div class="price-block">\
                        <div class="price-box"> <span class="regular-price" id="product-price-123"> <span class="price">$'+parseFloat(catList.item_price).toFixed(2)+'</span> </span> </div>\
                    </div>\
					<div class="add-to-box">\
                        <div class="add-to-cart">\
                     <div class="change-qunty '+hiddenClassQty+'" data-id="qty_'+catList.item_id+'">\
                          <button onClick="minusqtyheader(\''+catList.item_id+'\')" class="reduced items-count items-min min-postion" type="button"><i class="icon-minus">&nbsp;</i></button>\
       <span><input data-id="qtyval_'+catList.item_id+'" class="top-count" type="text"  name="qty" id="qtyitem_'+catList.item_id+'" maxlength="12" value="'+qtyID+'" title="Quantity" disabled></span>\
    <button onClick="plusqtyheader(\''+catList.item_id+'\')" class="increase items-count items-plus" type="button"><i class="icon-plus">&nbsp;</i></button>\
 </div>\
<button data-id="btn_'+catList.item_id+'" type="button" title="Add to Cart" class="button btn-cart btn-cartadd '+hiddenClassBtn+'" onClick="Addcartitemlist(\''+catList.item_id+'\','+catList.item_price+','+catList.Sales_tax+');"><span><i class="fas fa-shopping-cart"></i>Add to Cart</span></button>\
                    </div>\
                   </div>\
                   <div class="short-description">\
				    <p class="availability in-stock">\
                        <link itemprop="availability" href="http://schema.org/InStock">\
                        <span>In stock</span></p>\
                        <h2>Quick Overview</h2>\
                    <p class="description-pg">'+catList.item_sdesc+' </p>\
                    </div>\
					<div class="share-link">\
					<p class="text-center fs-30 no-margin"> share </p>\
					</div>\
                  <div class="social col-md-12 text-center">\
                        <ul class="link">\
                        <li class="fb"> <a href="https://www.facebook.com/go2gro/" rel="nofollow" target="_blank"> </a> </li>\
                        <li class="linkedin"> <a href="http://www.linkedin.com/" rel="nofollow" target="_blank"> </a> </li>\
                        <li class="tw"> <a href="https://twitter.com/go2gro" rel="nofollow" target="_blank"> </a> </li>\
                        <li class="instagram"> <a href="https://www.instagram.com/go2gro/" rel="nofollow" target="_blank"> </a> </li>\
                         <!--<li class="googleplus"> <a href="https://plus.google.com/" rel="nofollow" target="_blank"> </a> </li>-->\
                        </ul>\
                        </div>\
                         <ul class="checkout"><li>\
    <a type="button" href="<?php echo base_url();?>ShoppingCart"  title="Proceed to Checkout" class="button btn-proceed-checkout"><span>Proceed to Checkout</span></a>\
              </li><br>\
              </ul>\
			     </div>\
                    </div>\
                   </form>';

             jQuery('#productDescinfo').append(html);
                jQuery('#product_tabs_description').find('p').html(catList.item_fdesc);
             jQuery('.cloud-zoom, .cloud-zoom-gallery').CloudZoom();
            }
        });
    }
    getbestSeller();

    function Product_DetailAdd(id) {


      <?php if(empty($apikey) || !trim($apikey)) {?>
      var api_key='not defined';
      <?php }else{?>
      var api_key='<?php echo $apikey;?>';
      <?php }?>


      var result = document.getElementById("qtyitem_"+id);

      var qty11 = parseInt(result.value);
      if (!isNaN(qty11)) {
        var qty_valid = qty11+1;

        jQuery.ajax({
          type: 'POST',
          url: '<?php echo base_url("updateitemcart"); ?>',
          data: {
            'cart_id':id,
            'qty':qty_valid,
            'Authorization':api_key },
          success: function (res) {
            console.log(res);
            if(res.error==true)
            {

            }else {
              //  mycartdatarightsidebar();

              jQuery("#pd_"+id).val(qty_valid);

             // jQuery("#totalprice"+id).text(res.total);
            }

          }

        });
        return false;
      }
    }

    function Product_Detailsub(id) {
      <?php if(empty($apikey) || !trim($apikey)) {?>
      var api_key='not defined';
      <?php }else{?>
      var api_key='<?php echo $apikey;?>';
      <?php }?>
      var result = document.getElementById('qtyitem_'+id);
      var qty12 = parseInt(result.value);
      if( !isNaN( qty12 )) {
        var qtyvaminusl =qty12-1;
        jQuery.ajax({
          type: 'POST',
          url: '<?php echo base_url("updateitemcart"); ?>',
          data: {
            'cart_id': id,
            'qty': qtyvaminusl,
            'Authorization': api_key
          },
          success: function (res) {
            console.log(res);
            if (res.error == true) {

            } else {

              jQuery("#pd_"+id).val(qtyvaminusl);

             // jQuery("#totalprice"+id).text(res.total);
            }

          }

        });
      }

      return false;
      //return false;

    }
</script>

</body>
</html>

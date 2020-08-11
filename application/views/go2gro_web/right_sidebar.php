<?php
   ?>
<aside class="col-left fixedsticky sidebar col-sm-3 col-xs-12 wow bounceInUp animated">
   <div>
      <div class="block block-list block-cart maxhig400"  >
         <div class="block-title"> My Cart </div>
         <?php if( $isLogin==true){?>
         <div class="block-content" >
            <div class="summary">
               <p class="amount block-subtitle">Items: <a href="javascript:void(0);" id="carttotalitem">0</a>&nbsp;&nbsp;&nbsp; Total:&nbsp;&nbsp;<span class="bold">$</span><a href="javascript:void(0);" id="grandTotal">0</a></p>
            </div>
            <div class="ajax-checkout">
            </div>
            <p class="block-subtitle">Recently added item(s)</p>
            <?php }?>
            <ul  class="mini-products-list" id="cart_item_list" ></ul>
            <div class="actions">
               <a class="btn-checkout" title="Checkout" href="<?php echo base_url();?>ShoppingCart"><span>Checkout</span></a>
            </div>
         </div>
      </div>
   </div>
   <!--block block-list block-compare-->
</aside>
<!---->
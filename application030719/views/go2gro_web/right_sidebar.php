<?php
/**
 * Created by PhpStorm.
 * User: Rajendra
 * Date: 7/25/2017
 * Time: 3:13 PM
 *
 */

?>
<style type="text/css">
    input.input-text, select, textarea {
        background-color: #fff;
        border: none;
        padding: 10px;
        outline: none;
        color: #333;
        border: 1px #ddd solid;
        width: 40px;
    }
    .custom button.items-count {
        background-color: #fff;
        border: 1px #ddd solid;
        transition: color 300ms ease-in-out 0s, background-color 300ms ease-in-out 0s, background-position 300ms ease-in-out 0s;
        color: #444;
        font-size: 10px;
        line-height: normal;
        padding: 13px 15px 12px 15px;
        line-height: normal;
    }

    input, button, select, textarea {
        font-family: inherit;
        font-size: inherit;
        line-height: inherit;
    }
	.sidenav a:hover ,btn-checkout:hover{
		color: #f1f1f1;
		height: auto !important;
		}
</style>
<aside class="col-left fixedsticky sidebar col-sm-3 col-xs-12 wow bounceInUp animated">
    <div>
        <div class="block block-list block-cart" style="max-height: 400px;">
            <div class="block-title"> My Cart </div>
            <?php if( $isLogin==true){?>
            <div class="block-content" >
                <div class="summary">
                    <p class="amount block-subtitle" >Items: <a href="javascript:void(0);" id="carttotalitem">0</a>&nbsp;&nbsp;&nbsp; Total:&nbsp;&nbsp;<span style="font-weight: 400;">$</span><a href="javascript:void(0);" id="grandTotal">0</a></p>
                </div>
                <div class="ajax-checkout">
                    <!--                    <button type="button" title="Checkout" class="button button-checkout" onClick="--><?php //echo base_url();?>
                    <!--                    Shoppingcart"> -->
                    <!--                    <span>Checkout</span> </button>-->
                </div>
                <p class="block-subtitle">Recently added item(s)</p>
                <?php }?>
                <ul  class="mini-products-list" id="cart_item_list" >


                    <!--                    <li class="item">-->
                    <!--                        <div class="item-inner">-->
                    <!--                            <a href="#" class="product-image"><img src="products-images/p1.jpg" width="80" alt="product">-->
                    <!--                            </a>-->
                    <!--                            <div class="product-details">-->
                    <!--                                <div class="access"> <a href="#" class="btn-remove1">Remove</a>-->
                    <!--                                </div>-->
                    <!--                                <!--access-->
                    <!--                                <p class="product-name"><a href="#">RETIS LAPEN CASEN</a>-->
                    <!--                                </p>-->
                    <!--                                <strong>-->
                    <!--                                    <div class="custom">-->
                    <!--                                        <button onclick="var result = document.getElementById('qty'); var qty = result.value; if( !isNaN( qty )) result.value++;return false;" class="increase items-count" type="button" style="padding:5px;"><i class="icon-plus">&nbsp;</i></button>-->
                    <!--                                        <input type="text" style="width:42px;" name="qty" id="qty" maxlength="12" value="0" title="Quantity:" class="input-text input-text-01 qty">-->
                    <!--                                        <button onclick="var result = document.getElementById('qty'); var qty = result.value; if( !isNaN( qty ) &amp;&amp; qty > 0 ) result.value--;return false;" class="reduced items-count" type="button" style="padding:5px;"><i class="icon-minus">&nbsp;</i></button>-->
                    <!--                                    </div>-->
                    <!--                                </strong> x <span class="price">$299.00</span>-->
                    <!--                            </div>-->
                    <!--                            <!--product-details-bottoms-->
                    <!--                        </div>-->
                    <!--                    </li>-->
                    <!--                    <li class="item  last1">-->
                    <!--                        <div class="item-inner">-->
                    <!--                            <a href="#" class="product-image"><img src="products-images/p2.jpg" width="80" alt="product">-->
                    <!--                            </a>-->
                    <!--                            <div class="product-details">-->
                    <!--                                <div class="access"> <a href="#" class="btn-remove1">Remove</a>-->
                    <!--                                </div>-->
                    <!--                                <!--access-->
                    <!--                                <p class="product-name"><a href="#">RETIS LAPEN CASEN</a>-->
                    <!--                                </p>-->
                    <!--                                <strong>-->
                    <!--                                    <div class="custom">-->
                    <!--                                        <button onclick="var result = document.getElementById('qty'); var qty = result.value; if( !isNaN( qty )) result.value++;return false;" class="increase items-count" type="button" style="padding:5px;"><i class="icon-plus">&nbsp;</i></button>-->
                    <!--                                        <input type="text" style="width:42px;" name="qty" id="qty" maxlength="12" value="0" title="Quantity:" class="input-text input-text-01 qty">-->
                    <!--                                        <button onclick="var result = document.getElementById('qty'); var qty = result.value; if( !isNaN( qty ) &amp;&amp; qty > 0 ) result.value--;return false;" class="reduced items-count" type="button" style="padding:5px;"><i class="icon-minus">&nbsp;</i></button>-->
                    <!--                                    </div>-->
                    <!--                                </strong> x <span class="price">$299.00</span>-->
                    <!---->
                    <!--                            </div>-->
                    <!--                            <!--product-details-bottoms-->
                    <!--                        </div>-->
                    <!--                    </li>-->
                </ul>
              <div class="actions">
                            <a class="btn-checkout" title="Checkout" href="<?php echo base_url();?>ShoppingCart"><span>Checkout</span></a>
                            </div>
            </div>
        </div>
    </div>
    <!--block block-list block-compare-->
</aside>
<!---->
<script>



    //    function plusqty(id) {
    //        var result = document.getElementById("qty_"+id);
    //        var qty = result.value;
    //        if (!isNaN(qty))
    //            result.value++;
    //        return false;
    //    }
    //
    //    function minusqty(id) {
    //
    //        var result = document.getElementById('qty_'+id);
    //        var qty = result.value;
    //        if( !isNaN( qty ))
    //            result.value--;
    //        return false;
    //
    //    }
    //    function edit_cart(id) {
    //        <?php //if(empty($apikey) || !trim($apikey)) {?>
    //        var api_key='not defined';
    //        <?php //}else{?>
    //        var api_key='<?php //echo $apikey;?>//';
    //        <?php //}?>
    //
    //        var qty = jQuery("#qty_"+id).val();
    //
    //        jQuery.ajax({
    //            type: 'POST',
    //            url: '<?php //echo base_url("updateitemcart"); ?>//',
    //            data: {
    //                'cart_id': id,
    //                'qty': qty,
    //                'Authorization': api_key
    //            },
    //            success: function (res) {
    //                location.reload();
    //            }
    //
    //
    //    });
    //        return false;
    //    }


</script>
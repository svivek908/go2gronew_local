<?php
   include ('header.php'); 
   
   $itemIds = '';
   if (isset($cart)) {
       $aimp="";
       $cartId=0;
       $taxresultadd = 0;
       $tax1 = 0.00;
       $total_Amount = 0.00;
       $proccessing_fee=0.00;
       $delivery_chrges=0.00;
       if (!empty($cart)) {
           if(empty( $cart['cart']->item)){
               $aimp="";
           }else{
               $cartloop = $cart['cart']->item;
   
               $arrItemId = array();
               $txcalc=0;
               foreach ($cartloop as $row) {
                   $cartId = $row->item_id;
                   $arrItemId[] = $cartId;
                    $item_price= $row->item_price;
   
                   $tax1 = $row->Sales_tax;
                   $txcalc +=round(($item_price*$tax1)/100, 2);
                 $total_Amount += $row->total;
               }
               $itemIds = implode(',', $arrItemId);
               $proccessing_fee=0.49;
               $delivery_chrges=4.99;
               $taxresultadd = round($txcalc + $total_Amount +$proccessing_fee+$delivery_chrges, 2);
           }
       } else {
           $cartloop ="";
           $aimp = "";
       }
   }
   //$id=$_GET['id'];
?>

<!-- BEGIN Main Container -->
<div class="main-container col1-layout wow bounceInUp animated">
   <div class="main">
      <div class="cart wow bounceInUp animated">
         <div class="table-responsive shopping-cart-tbl  container">
            <form action="" method="post">
               <input name="form_key" type="hidden" value="EPYwQxF6xoWcjLUr">
               <fieldset>
                  <table id="shopping-cart-table" class="data-table cart-table table-striped">
                     <colgroup>
                        <col width="1">
                        <col>
                        <col width="1">
                        <col width="1">
                        <col width="1">
                        <col width="1">
                        <col width="1">
                     </colgroup>
                     <thead>
                        <tr class="first last">
                           <th rowspan="1">Product</th>
                           <th rowspan="1"><span class="nobr">Product Name</span></th>
                           <th class="a-right" colspan="1"><span class="nobr">Unit Price</span></th>
                           <th class="a-right" colspan="1"><span class="nobr">Sales Tax</span></th>
                           <th rowspan="1" class="a-center text-center minw170">Qty</th>
                           <th class="a-right" colspan="1">Subtotal</th>
                           <th rowspan="1" class="a-center">Action</th>
                        </tr>
                     </thead>
                     <tfoot>
                        <tr class="first last">
                           <td colspan="50" class="a-right last">
                              <div id="loaderprofile1">
                                 <div class="loader"></div>
                              </div>
                              <a href="<?php echo base_url();?>" type="button" title="Continue Shopping" id="continueshoppingclick1" class="button btn-continue1 " ><span>Continue Shopping</span>
                              </a>
                           </td>
                        </tr>
                     </tfoot>
                     <tbody class="cartshow"> </tbody>
                  </table>
               </fieldset>
            </form>
         </div>
         <!-- BEGIN CART COLLATERALS -->
         <div class="cart-collaterals container">
            <!-- BEGIN COL2 SEL COL 1 -->
            <!-- BEGIN TOTALS COL 2 -->
            <div class="col-sm-6 no-pl" >
               <div class="discount bg-white">
                  <h3 >Discount Codes</h3>
                  <form id="discount-coupon-form" action="" method="post">
                     <label for="coupon_code" class="fs-14">Enter your coupon code if you have one.</label>
                     <input type="hidden" name="remove" id="remove-coupone" value="0">
                     <input class="input-text fullwidth" type="text" id="coupon_code" name="coupon_code" value="">
                     <button type="button" title="Apply Coupon" class="button coupon " onClick="check_promocode()" value="Apply Coupon"><span>Apply</span>
                     </button>
                  </form>
               </div>
               <!--discount-->
            </div>
            <!--col-sm-4-->
            <div class="col-sm-6">
               <div class="totals">
                  <h3>Shopping Cart Total</h3>
                  <div class="inner">
                     <table id="shopping-cart-totals-table" class="table shopping-cart-table-total">
                        <colgroup>
                           <col>
                           <col width="1">
                        </colgroup>
                        <tfoot id="showsessiondata1">
                        </tfoot>
                        <tbody id="showsessiondata">
                        </tbody>
                     </table>
                     <ul class="checkout check_height">
                        <li>
                           <button type="button" onclick="updatecartvalue();" title="Proceed to Checkout" class="button btn-proceed-checkout loaderbutton5" onClick=""><span>Proceed to Checkout</span></button>
                        </li>
                        <br>
                     </ul>
                  </div>
                  <!--inner-->
               </div>
               <!--totals-->
            </div>
            <!--col-sm-4-->
         </div>
         <!--cart-collaterals-->
      </div>
      <!--cart-->
   </div>
   <!--main-container-->
</div>
<?php include 'footer.php' ?>
<script>
   jQuery( ".odd" ).last().addClass( "last" );
   jQuery( ".odd" ).first().addClass( "first last" );
   
   function check_promocode() {
       var api_key='<?php echo $apikey;?>';
       var pro_code = jQuery("#coupon_code").val();
       if(pro_code !=''){
               jQuery.ajax({
               type: 'POST',
               url: '<?php echo site_url("check_promocode"); ?>',
               data: {
                   'Authorization':api_key,
                   'coupon_code':pro_code
               },
               success: function (res) {
                   console.log(res);
                   if(res.error==true){
                       swal({title: 'Error', text: titleCase(res.message), type: 'error'});
                       localStorage.removeItem("discount_amnt");
                       localStorage.removeItem("discount_type");
                       localStorage.removeItem("discount_id");
                   }else{
                       var html1 = '';
                       var html2 = '';
                       var html3 = '';
                       var total = 0.00;
                       var taxcal = 0;
                       var totalfinalamountvalue = 0.00;
                       var count = 1;
                       var salestax = 0;
                       var arr = [];
                       var shopcatList = res.item;
                       var deleviry_charge = parseFloat(res.delivery_charges);
                      var delivery_charges_label = res.delivery_charges_label;
                       localStorage.setItem("delivery_charge",deleviry_charge);
                       localStorage.setItem("delivery_charges_label",delivery_charges_label);
                       var deleviry_chargeold =<?php echo $this->config->item('deleviry_chargeold');?>;
                       var processing_fee = parseFloat(res.processing_fee);
                       var discount_amnt = parseFloat(res.discount);
                       var totalfinalamountvalue = 0.00;
                       var count = 1;
                       localStorage.setItem("discount_amnt", discount_amnt);
                       localStorage.setItem("discount_type",res.discount_type);
                       localStorage.setItem("discount_id",res.discount_id);
                       localStorage.setItem("discount_label",res.discount_label);
                       var jsonLength = shopcatList.length;
                       
                       for (var i = 0; i < jsonLength; i++) {
                           count = count + 1;
                           var result = shopcatList[i];
                           arr.push(result.item_id);
                           if (result.Sales_tax > salestax) {
                               salestax = result.Sales_tax;
                           }
                           total =  total + parseFloat(result.total);
                           var totalAmount = Math.round(total * 100) / 100;
                           localStorage.setItem("total_am", totalAmount);
                           localStorage.setItem("itemID", result.item_id);
                           html1 += '<tr class="odd">\
                           <td class="image hidden-table">\
                           <a href="<?php echo base_url();?>productDetail?id=' + result.item_id + '" title="Women&#39;s Georgette Animal Print" class="product-image">\
                                 <img src="<?php echo $this->config->item('api_img_url');?>' + result.item_image + '" width="75" onerror="this.src=\'<?php echo $this->config->item('api_img_url');?>upload/item/noprivew.jpg\';" alt="Women Georgette Animal Print">\
                           </a>\
                           </td>\
                           <td>\
                           <h2 class="product-name">\
                           <a href="<?php echo base_url();?>productDetail?id=' + result.item_id + '">' + result.item_name + '</a>\
                           </h2>\
                           </td>\
                           <td class="a-right hidden-table">\
                           <span class="cart-price">\
                           <span class="price">$' + parseFloat(result.item_price).toFixed(2) + '</span>\
                           </span>\
                           </td>\
                           <td class="a-right hidden-table">\
                           <span class="cart-price">\
                           <span class="price">' + result.Sales_tax + '%</span>\
                           </span>\
                           </td>\
                           <td class="a-center movewishlist">\
                           <div class="change-qunty">\
                           <button onclick="minusqtyheader(\'' + result.item_id + '\');" class="reduced items-count items-count-cart" type="button"><i class="icon-minus">&nbsp;</i></button>\
                           <span><input data-id="qtyval_'+result.item_id+'" type="text" disabled name="qty" id="cartupdateqty_' + result.item_id + '" maxlength="12" value="' + result.item_quty + '" title="Quantity:" class="marl2 "></span>\
                           <button onclick="plusqtyheader(\'' + result.item_id + '\');" class="increase items-count items-count-cart" type="button"><i class="icon-plus">&nbsp;</i></button>\
                           </div>\
                           </td>\
                           <td class="a-right movewishlist">\
                           <span class="cart-price">\
                           <span class="price5555" id="totalprice_' + result.item_id + '">$' + parseFloat(result.total).toFixed(2) + '</span>\
                           </span>\
                           </td>\
                           <td class="a-center last"><a href="javascript:void(0)" title="Remove item" onclick="delete_cart1(\'' + result.item_id + '\')" class="button remove-item"><span><span>Remove item</span></span></a>\
                           </td></tr>';
                           taxcal = taxcal + (((result.item_price * result.item_quty) * result.Sales_tax) / 100);
                       }
                       localStorage.setItem("totalitemarray", arr);
                       taxcal = roundoffvalue(taxcal + ((deleviry_charge * salestax) / 100));
                       localStorage.setItem("totaltax", taxcal);
                       totalfinalamountvalue = roundoffvalue(total + taxcal + processing_fee + deleviry_charge);
                       console.log('aaaaa = ' +totalfinalamountvalue);
                       var subfinalamount = totalfinalamountvalue;
                       totalfinalamountvalue = (subfinalamount - discount_amnt);
                       localStorage.setItem("sub_total_Am0unt", subfinalamount);
                       localStorage.setItem("total_Am0unt", totalfinalamountvalue);
                       html3 += '<tr>\
                       <td  class="a-left" colspan="1">Total</td>\
                       <td  class="a-right">\
                       <span class="priceq" id="">$' + subfinalamount.toFixed(2) + '</span>\
                       </td>\
                       </tr>\
                       <tr>\
                       <td  class="a-left" colspan="1">\
                       '+ res.discount_label +'</span></td>\
                       <td   class="a-right">\
                       <span class="priceq d-inline minw120">- $' + discount_amnt + '</span></strong>\
                       </td>\
                       </tr>\
                       <tr>\
                       <td class="a-left" colspan="1">\
                       <strong>Grand Total</strong>\
                       </td>\
                       <td class="a-right">\
                       <strong><span class="priceq" id="total_amount77443">$' + parseFloat(totalfinalamountvalue).toFixed(2) + '</span></strong>\
                       </td>\
                       </tr>';
                       html2 += '<tr>\
                       <td   class="a-left" colspan="1">Subtotal</td>\
                       <td   class="a-right"><span class="price" id="total_amount">$' + parseFloat(totalAmount).toFixed(2) + '</span></td>\
                       </tr>\
                       <tr>\
                       <td   class="a-left" colspan="1">Sales  Tax</td>\
                       <td   class="a-right"><span class="text" id="taxval" >$' + taxcal.toFixed(2) + '</span></td>\
                       </tr>\
                       <tr>\
                       <td   class="a-left" colspan="1">Processing Fee    </td>\
                       <td   class="a-right"><span class="price445" id="fee"></span>$' + parseFloat(processing_fee).toFixed(2) + ' </td>\
                       </tr>\
                       <tr>\
                       <td   class="a-left" colspan="1">'+delivery_charges_label+' <!--<span class="pull-right">Promo: Stay Warm</span>--></td>\
                       <td   class="a-right"><span class="price1" id="coupencode">&nbsp;&nbsp;$' + parseFloat(deleviry_charge).toFixed(2) + '</span></td>\
                       </tr>';
                       jQuery('#showsessiondata').html(html2);
                       jQuery('#showsessiondata1').html(html3);
                       jQuery('.cartshow').html(html1);    
                       swal({title: 'Success', text: titleCase('Promocode Apply'), type: 'success'});
                   }
               }
           });
           return false;
       } else {
           swal('Warning!','Please enter a coupon code first','warning');
       }
   }
</script>
</body>
</html>
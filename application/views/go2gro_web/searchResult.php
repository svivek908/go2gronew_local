<?php
include 'header.php' 
?>
<div class="pagetop">
</div>
<!--breadcrumbs-->

<!-- BEGIN Main Container col2-left -->
<section class="main-container col2-left-layout bounceInUp animated">
    <!-- For version 1, 2, 3, 8 -->
    <!-- For version 1, 2, 3 -->
    <div class="container">
        <div class="row">
            <div class="col-main col-sm-12 product-grid">
                <div class="pro-coloumn">
                    <article class="col-main">
                        <div class="toolbar padTB15">
                            <div class="col-sm-6 col-xs-6 col-lg-8">
                                <input type="text" id="searchStr" placeholder="Search" class="serchbyfilter filterser"
                                       onkeyup="filter();" >
                            </div>

                            <div id="sort-by" class="sortbyfilter col-sm-6 col-xs-6 col-lg-4 text-right">
                                <select id="sortbyType" onchange="filter();" class="pull-right shotby_sel">
                                    <option value="" selected>Sort By</option>
                                    <option value="name">Name</option>
                                    <option value="low">Price--Low to High</option>
                                    <option value="high">Price--High to Low</option>
                                </select>
                            </div>
                        </div>
                        <div class="category-products">
                            <ul class="products-grid" id="products_grid_item">
                            </ul>
                        </div>
                        <div class="pagination pagestyle" id="pagination"></div>
                    </article>
                </div>
            </div>
            <?php //include('right_sidebar.php'); ?>

            <!--block block-list block-compare-->
            <!--  </aside> -->
            <!--col-right sidebar-->
        </div>
        <!--row-->
    </div>
    <!--container-->
</section>
<!--main-container col2-left-layout-->
<section class="wow bounceInUp animated">
    <div class="best-pro slider-items-products container">
        <div class="new_title">
            <h2>Best Seller</h2>
        </div>
        <div id="best-seller" class="product-flexslider hidden-buttons">

        </div>
    </div>
</section>
<!-- For version 1,2,3,4,6 -->
<?php include 'footer.php'; ?>
<script>

    var filterArray = [];
    var actualArray = [];
    var totalPages = 0;
    jQuery(document).ready(function () {
        searchItemData(0);
        init();
    });
    function init() {
        if (totalPages > 0) {
            jQuery('#pagination').twbsPagination({
                totalPages: totalPages,
                visiblePages: 5,
                nextClass: 'next-page',
                prevClass: 'prev-page',
                hideOnlyOnePage: true,
                onPageClick: function (event, page) {
                    searchItemData(page - 1);
                }
            });
        }
    }

    function searchItemData(pageno) {
        var scrollPos = jQuery("body").offset().top;
        jQuery(window).scrollTop(scrollPos);
        jQuery("#products_grid_item").html('<div class="loader" id="loadersearch"></div>');
        jQuery('#searchStr').val('');
        var searchkey = "<?php echo $searchStr; ?>";
        searchkey = searchkey.trim();
        filterArray = [];
        actualArray = [];
        var status = 1;
        var api_key = 'sdfsdfdfsdf';

        jQuery.ajax({
            type: 'GET',
            url: '<?php echo base_url(); ?>/searchList',
            data: {"searchStr": searchkey, "status": status, "page": pageno},
            beforeSend: function ()
            {
                ajaxindicatorstart('Please wait...');
            },
            success: function (res) {
                ajaxindicatorstop();
                var html2 = '';
                var totalCount = res.totalcount;
                totalPages = Math.ceil(totalCount / 20);
                if (!res.error) {
                    var searchItemList = res.item;
                    console.log(searchItemList.length);
                    for (var j = 0; j < searchItemList.length; j++) {
                        var result11 = searchItemList[j];

                        filterArray.push(result11);
                        actualArray.push(result11);
                        var qtyID = 1;
                        var cartData = localStorage.getItem('cartData');
                        var hiddenClassQty = '';
                        var hiddenClassBtn = '';
                        if (cartData != null) {
                            cartData = JSON.parse(cartData);
                            var index = cartData.findIndex(function(obj) { return obj.item_id === result11.item_id; });
                            console.log(index);
                            if (index != -1) {
                                console.log(index);
                                qtyID = cartData[index].item_quty;
                                hiddenClassQty = '';
                                hiddenClassBtn = 'hidden';
                            } else {
                                hiddenClassQty = 'hidden';
                                hiddenClassBtn = '';
                            }
                        } else {
                            hiddenClassQty = 'hidden';
                            hiddenClassBtn = '';
                        }

                        html2 += '<li class="item col-lg-4 col-md-3 col-sm-4 col-xs-6">\
                        <div class="item-inner">\
                        <div class="item-img ">\
                        <div class="item-img-info getlistitem item-img center-image1">\
                        <a href="<?php echo base_url();?>productDetail?id=' + result11.item_id + '" title="' + result11.item_name + '" class="product-image"><img src="<?php echo base_url('public/upload/item/');?>' + result11.item_image + '" alt="Retis lapen casen"></a>\
                        <div class="item-box-hover"></div></div></div>\
                        <div class="item-info">\
                        <div class="info-inner">\
                       <div class="item-title"><a href="<?php echo base_url();?>productDetail?id=' + result11.item_id + '" title="' + result11.item_name + '">' + result11.item_name + '</a></div>\
                   <div class="item-content">\
                    <div class="item-price">\
                        <div class="price-box"><span class="regular-price" id="product-price-1"><span class="price">$' + parseFloat(result11.item_price).toFixed(2) + '</span> </span> </div>\
                    </div>\
                    <div class="add_cart mart0">\
                    <div class="change-qunty ' + hiddenClassQty + '" data-id="qty_' + result11.item_id + '">\
                    <button onClick="minusqtyheader(\'' + result11.item_id + '\');" class="reduced items-count" type="button"><i class="icon-minus">&nbsp;</i></button>\
                    <span><input data-id="qtyval_' + result11.item_id + '" type="text" disabled name="result.item_id" id="qtyitem_' + result11.item_id + '" maxlength="12" value="' + qtyID + '" title="Quantity:" class="input-text qty">\</span>\
                    <button onClick="plusqtyheader(\'' + result11.item_id + '\');" class="increase items-count" type="button"><i class="icon-plus">&nbsp;</i></button> </div>\
                    <button data-id="btn_' + result11.item_id + '" class="button btn-cart ' + hiddenClassBtn + '" type="button" onclick="Addcartitemlist(\'' + result11.item_id + '\', ' + parseFloat(result11.item_price).toFixed(2) + ', ' + parseFloat(result11.Sales_tax).toFixed(2) + ');"><span>Add to Cart</span></button>\
                    </div>\
                    </div>\
                    </div>\
                    </div>\
                    </div></li>';
                    }
                } else {
                    html2 += 'No Record Found';
                }
                jQuery("#loadersearch").addClass('hidden');
                jQuery('#products_grid_item').html(html2);
                init();
                filter();
            }
        });
    }

    function filter() {
        var searchStr = jQuery("#searchStr").val();
        searchStr = searchStr.toLowerCase();
        var sortbytype = jQuery("#sortbyType").val();

        filterArray = _.uniq(filterArray, function (list) {
            return list.item_id;
        });
        actualArray = _.uniq(actualArray, function (list) {
            return list.item_id;
        });
        if (sortbytype == 'name') {
            filterArray = _.sortBy(filterArray, function (itemlist) {
                //console.log(item);
                return itemlist.item_name;
            });
        } else if (sortbytype == 'low') {
            filterArray = _.sortBy(filterArray, function (itemlist) {
                //console.log(item);
                return itemlist.item_price;
            });
        } else if (sortbytype == 'high') {
            filterArray = _.sortBy(filterArray, function (itemlist) {
                //console.log(item);
                return itemlist.item_price;
            });
            filterArray = filterArray.reverse();
        } else {
            filterArray = _.uniq(actualArray, function (list) {
                return list.item_id;
            });
        }
        console.log(filterArray);
        //filterArray
        var sortHtml = '';
        for (var i = 0; i < filterArray.length; i++) {
            var patt = new RegExp(searchStr);
            var result11 = filterArray[i];
            if (patt.test(result11.item_name.toLowerCase())) {
                var qtyID = 1;
                var cartData = localStorage.getItem('cartData');
                var hiddenClassQty = '';
                var hiddenClassBtn = '';
                if (cartData != null) {
                    cartData = JSON.parse(cartData);
                    var index = cartData.findIndex(function(obj) { return obj.item_id === result11.item_id; });
                    if (index != -1) {
                        console.log(index);
                        qtyID = cartData[index].item_quty;
                        hiddenClassQty = '';
                        hiddenClassBtn = 'hidden';
                    } else {
                        hiddenClassQty = 'hidden';
                        hiddenClassBtn = '';
                    }
                } else {
                    hiddenClassQty = 'hidden';
                    hiddenClassBtn = '';
                }
                sortHtml += '<li class="item serchproductimg col-lg-4 col-md-3 col-sm-4 col-xs-6">\
                        <div class="item-inner">\
                        <div class="item-img ">\
                        <div class="item-img-info getlistitem item-img center-image1"><a href="<?php echo base_url();?>productDetail?id=' + result11.item_id + '" title="' + result11.item_name + '" class="product-image"><img  src="<?php echo $this->config->item('api_img_url');?>' + result11.item_image + '" alt="Retis lapen casen"></a>\
                       </div></div>\
                        <div class="item-info">\
                        <div class="info-inner">\
                        <div class="item-title"><a href="<?php echo base_url();?>productDetail?id=' + result11.item_id + '" title="' + result11.item_name + '">' + result11.item_name + '</a></div>\
                        <div class="item-content">\
                        <div class="item-price">\
                        <div class="price-box"><span class="regular-price" id="product-price-1"><span class="price">$' + parseFloat(result11.item_price).toFixed(2) + '</span> </span> </div>\
                        </div>\
                        <div class="add_cart mart0">\
                        <div class="change-qunty qty-manage ' + hiddenClassQty + '" data-id="qty_' + result11.item_id + '" >\
                        <button onClick="minusqtyheader(\'' + result11.item_id + '\');" class="reduced lessbtn items-count" type="button"><i class="icon-minus">&nbsp;</i></button>\
                        <span><input data-id="qtyval_' + result11.item_id + '" type="text" disabled id="qtyitem_' + result11.item_id + '" maxlength="12" value="' + qtyID + '" title="Quantity:" class="input-text qty qty-text">\</span>\
                        <button onClick="plusqtyheader(\'' + result11.item_id + '\');" class="increase additem-qty items-count" type="button"><i class="icon-plus">&nbsp;</i></button> </div>\
                        <button data-id="btn_' + result11.item_id + '" class="button btn-cart ' + hiddenClassBtn + '" type="button" onclick="Addcartitemlist(\'' + result11.item_id + '\', ' + parseFloat(result11.item_price).toFixed(2) + ', ' + parseFloat(result11.Sales_tax).toFixed(2) + ');"><span>Add to Cart</span></button>\
                        </div>\
                        </div>\
                        </div>\
                        </div>\
                        </div></li>';
            }
        }
        jQuery('#products_grid_item').html(sortHtml);
    }

</script>
</body>
</html>

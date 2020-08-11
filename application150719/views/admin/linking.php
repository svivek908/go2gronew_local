<script>
    <?php
$user = $this->session->get_userdata('go2groadmin_session');
$api_key = $user['go2groadmin_session']['logged_user_api_key'];
$item_id=$itemid['item_id'];
?>

    
    //main script this page
    $(document).ready(function () {
        var api_key = "<?php echo $api_key;?>";
        var item_id = "<?php echo $item_id;?>";
        //alert('hello');
        getProductLink(); // get all product link
        getdepartment();  //  get all department link
        viewlinking();   //  view link product api
    });
    function savedata() // save linking product
    {
        if ($("#category").val() == 0) {
            sweetAlert("Oops...", "Select Department!", "error");
            return false;
        }
        if ($("#subcategory").val() == 0) {
            sweetAlert("Oops...", "Select category!", "error");
            return false;
        }
        //this function used for save link product

        var api_key = "<?php echo $api_key;?>";
        var item_id = "<?php echo $item_id;?>";

        var subid1 = $("#subcategory").val();
        jQuery("#fullLoader").show();
        $.ajax({
            type: 'POST',
            url: '<?php echo base_url();?>Admin/save_link_product',
            data: {'Authorization': api_key, 'item_id': item_id, 'subcate_id': subid1},
            success: function (res) {
                jQuery("#fullLoader").hide();
                if (res.error) {
                    swal("Oops!", "Your product linking error !", "error");
                }
                else {
                    swal("Success!", "Your product linking successfully !", "success");
                    window.location = '<?php echo base_url();?>Admin/viewproduct';
                }
            }
        });
    }
    function closelink(subid, itemid) //delete product linking
    {
        var api_key = "<?php echo $api_key;?>";
        $('#subcat_' + subid).css('display', 'none');
        $.ajax({
            type: 'POST',
            url: '<?php echo base_url();?>/Admin/closelink',
            data: {'Authorization': api_key, 'item_id': itemid, 'subcate_id': subid},
            success: function (res) {

                if (res.error) {
                    swal("Error!", "Product not delete successfully !", "error");
                    $('#subcat_' + subid).css('display', 'block');
                }
            }
        });
    }
    function viewlinking() {
        var api_key = "<?php echo $api_key;?>";
        var item_id = "<?php echo $item_id;?>";
        $.ajax({
            type: 'GET',
            url: '<?php echo base_url();?>Admin/viewlinking',
            data: {'Authorization': api_key, 'item_id': item_id},
            success: function (res) {
                // console.log(res.subcategory);
                var itemlist = res.subcategory;
                var jsonlength = itemlist.length;
                var html = '';
                for (var i = 0; i < jsonlength; i++) {
                    var result = itemlist[i];
                    //console.log(result.sub_id);
                    html += '<li id="subcat_' + result.sub_id + '"> <span class="close"  onclick=closelink(' + result.sub_id + ',\'' + res.item.item_id + '\')  value="' + result.sub_id + '"> x </span> <span>' + result.sub_name + '</span></li>';
                }
                $(".select-category ul").append(html);
            }
        });
    }
    function getProductLink() {
        var api_key = "<?php echo $api_key;?>";
        var item_id = "<?php echo $item_id;?>";
        $.ajax({
            type: 'GET',
            url: '<?php echo base_url();?>Admin/getlinkproduct',
            data: {'Authorization': api_key, 'item_id': item_id},
            success: function (res) {
                //console.log(res.item);
                getProductLinkhtml = "";
                var itemlist = res.item;
                getProductLinkhtml += '<tr>\
                                    <td>\
                                    <div class="cell-image-list"  data-target=".product-image" data-toggle="modal">\
                                    <div class="cell-img" style="background-image: url(<?php echo $this->config->item('api_url_image');?>' + itemlist.item_image + ')"></div>\
                                    </div>\
                                    </td>\
                                    <td class="nowrap">' + itemlist.item_name+' '+itemlist.item_size+ '</td>\
                                    <td class="word-wrap">\
                                    ' + itemlist.item_sdesc + '\
                                    </td>\
                                    <td class="word-wrap">' + itemlist.item_fdesc + '</td>\
                                    <td class="text-right">$' + itemlist.item_price.toFixed(2) + '</td>\
                            </tr>';
                $("#data").html(getProductLinkhtml);
            }
        });
    }
    function getdepartment() {
        var api_key = "<?php echo $api_key;?>";
        // console.log(api_key);
        $.ajax({
            type: 'GET',
            url: '<?php echo base_url();?>Admin/getdepartment',
            data: {'Authorization': api_key},
            success: function (res) {
                var itemlist = res.category;
                var jsonlength = itemlist.length;
                var getdepartmenthtml = '';
                for (var i = 0; i < jsonlength; i++) {
                    var result = itemlist[i];
                    getdepartmenthtml += '<option value="' + result.id + '">' + result.cat_name + '</option>';
                }
                $("#category").append(getdepartmenthtml);
            }
        });
    }
    function getsubcategory() {
        var api_key = "<?php echo $api_key;?>";
        var item_id = "<?php echo $item_id;?>";
        var cid = $("#category").val();
        //console.log(cid);
        if (cid.length == 0) {
            $("#subcategory").html('');
            return false;
        }
        $.ajax({
            type: 'GET',
            url: '<?php echo base_url();?>Admin/getsubcategory',
            data: {'Authorization': api_key, 'item_id': item_id, 'category_id': cid},
            success: function (res) {
                //console.log(res.subcategory);
                var itemlist = res.subcategory;
                /*console.log(itemlist);
                 */
                var jsonlength = itemlist.length;
                var getsubcategoryhtml = '';
                for (var i = 0; i < jsonlength; i++) {
                    var result = itemlist[i];
                    //console.log(result.cat_name);
                    getsubcategoryhtml += '<option value="' + result.sub_id + '">' + result.sub_name + '</option>';
                }
                $("#subcategory").html(getsubcategoryhtml);
            }
        });
    }
</script>
<div class="content-w">
    <!--------------------
START - Breadcrumbs
-------------------->
    <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>Admin">Home</a>
        </li>
        <li class="breadcrumb-item">Products Linking
        </li>
    </ul>
    <!--------------------
END - Breadcrumbs
-------------------->
    <!--<div class="content-panel-toggler"><i class="os-icon os-icon-grid-squares-22"></i><span>Sidebar</span>
    </div>-->
    <div class="content-i">
        <div class="content-box">
            <div class="row">
                <div class="col-sm-12">
                    <div class="element-box">
                        <div class="table-responsive">
                            <table class="table table-lightborder">
                                <tbody id="data">
                                </tbody>
                            </table>
                        </div>
                        <!--selected category start-->
                        <div class="controls-above-table categy-block">
                            <span class="head-title"> Selected Category</span>

                            <div class="row mrT20">
                                <div class="col-sm-12 select-category">
                                    <ul>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!--selected category close-->
                        <div class="controls-above-table">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-4 col-sm-5" for=""> Select
                                            Department</label>

                                        <div class="col-sm-8 no-pl">
                                            <select class="form-control select2" id="category"
                                                    onchange="getsubcategory()" multiple="true">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group row ">
                                        <label class="col-form-label col-md-4 col-sm-5 text-right" for=""> Select
                                            Category</label>

                                        <div class="col-sm-8 no-pl">
                                            <select class="form-control select2" multiple="true" id="subcategory">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <button class="btn btn-primary pull-right" id="save" onclick="savedata()">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<div class="display-type"></div>
</div>

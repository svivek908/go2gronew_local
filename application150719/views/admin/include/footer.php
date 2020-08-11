
    <div id="select-store-popup" class="row" style="background: #fff;margin: 0px 0px 25px 0px; padding:15px 0px;"></div>
    <script src="<?php echo base_url('admin_assets/bower_components/chart/dist/Chart.min.js');?>"></script>
    <script src="<?php echo base_url('admin_assets/bower_components/select2/dist/js/select2.full.min.js');?>"></script>
    <script src="<?php echo base_url('admin_assets/bower_components/ckeditor/ckeditor.js');?>"></script>
    <script src="<?php echo base_url('admin_assets/bower_components/bootstrap-validator/dist/validator.min.js');?>"></script>
    <script src="<?php echo base_url('admin_assets/bower_components/bootstrap-daterangepicker/daterangepicker.js');?>"></script>
    <script src="<?php echo base_url('admin_assets/bower_components/editable-table/mindmup-editabletable.js');?>"></script>
   <script src="<?php echo base_url('admin_assets/bower_components/fullcalendar/dist/fullcalendar.min.js');?>"></script>
    <script src="<?php echo base_url('admin_assets/bower_components/perfect-scrollbar/js/perfect-scrollbar.jquery.min.js');?>"></script>
    <script src="<?php echo base_url('admin_assets/bower_components/tether/dist/js/tether.min.js');?>"></script>
    <script src="<?php echo base_url('admin_assets/bower_components/bootstrap/js/dist/util.js');?>"></script>
    <script src="<?php echo base_url('admin_assets/bower_components/bootstrap/js/dist/alert.js');?>"></script>
    <script src="<?php echo base_url('admin_assets/bower_components/bootstrap/js/dist/button.js');?>"></script>
    <script src="<?php echo base_url('admin_assets/bower_components/bootstrap/js/dist/carousel.js');?>"></script>
    <script src="<?php echo base_url('admin_assets/bower_components/bootstrap/js/dist/collapse.js');?>"></script>
    <script src="<?php echo base_url('admin_assets/bower_components/bootstrap/js/dist/dropdown.js');?>"></script>
    <script src="<?php echo base_url('admin_assets/bower_components/bootstrap/js/dist/modal.js');?>"></script>
    <script src="<?php echo base_url('admin_assets/bower_components/bootstrap/js/dist/tab.js');?>"></script>
    <script src="<?php echo base_url('admin_assets/bower_components/bootstrap/js/dist/tooltip.js');?>"></script>
    <script src="<?php echo base_url('admin_assets/bower_components/bootstrap/js/dist/popover.js');?>"></script>
    <script src="<?php echo base_url('admin_assets/js/mainc599.js?version=3.3');?>"></script>

    <script type="text/javascript" src="<?php echo base_url('admin_assets/datatable/js/jquery.dataTables.min.js');?>"></script>
    <script type="text/javascript" src="<?php echo base_url('admin_assets/datatable/js/dataTables.buttons.min.js');?>"></script>
    <script type="text/javascript" src="<?php echo base_url('admin_assets/datatable/js/jszip.min.js');?>"></script>
    <script type="text/javascript" src="<?php echo base_url('admin_assets/datatable/js/pdfmake.min.js');?>"></script>
    <script type="text/javascript" src="<?php echo base_url('admin_assets/datatable/js/vfs_fonts.js');?>"></script>
    <script type="text/javascript" src="<?php echo base_url('admin_assets/datatable/js/buttons.html5.min.js');?>"></script>
    <script src="<?php echo base_url('admin_assets/js/moment-timezone-with-data.js');?>"></script>

    <script src="<?= base_url('admin_assets/js/croppie.js')?>"></script>

    <style>
        .block-in {
            height: 250px;
        }

        .block-brands a:hover {
            text-decoration: none;
        }

        .brand-img {
            width: 100%;
            height: 100%;
            box-shadow: 0px 0px 0px 1px rgba(0, 0, 0, 0.15);
            border-radius: 50%;
            background-color: #fff;
        }

        .block-into {
            width: 128px;
            height: 128px;
            position: relative;
            margin: 30px auto;
        }

        .block-in-btn {
            color: #606060;
            border-radius: 0px 0px 5px 5px;
        }

        .block-in-btn span {
            font-size: 14px;
            font-weight: bold;
        }

        .block-in-btn p {
            margin-top: 8px;
            margin-bottom: 0px;
        }

        .block-into img {
            width: 100%;
            height: 100%;
            box-shadow: 0px 0px 0px 1px rgba(0, 0, 0, 0.15);
            border-radius: 50%;
            background-color: #fff;
        }

        .active_store{
            border-radius: 50%;
            width: 8px !important;
            height: 8px !important;
            display: inline-block;
            line-height: 0px;
            background: #20a226;           
            margin-right:5px; 
        }

    .inactive_store{
            border-radius: 50%;
            width: 8px !important;
            height: 8px !important;
            display: inline-block;
            line-height: 0px;
            background: #c00;
            margin-right:5px;           
        }
    </style>
    <script>
    /*function getTextStatus(statusid){
        var status;
        switch(statusid)
        {
            case 0:
                status="PENDING";
                break;
            case 1:
                status="PREPARE";
                break;
            case 2:
                status="PACKED";
                break;
            case 3:
                status="OUT FOR DELIVERY";
                break;
            case 4:
                status="DELIVERED";
                break;
            case 6:
                status="CANCELLED";
                break;
            default:
                status="REJECT";
        }
        return status;
    }*/

    function unitimeToTime(unitime){
        var timeZone ='<?php echo $this->config->item('time_zone'); ?>';
        return moment.unix(unitime).tz(timeZone).format("MM/DD/YYYY  hh:mm A");
    }

    function formatedDate(date1){
        var timeZone ='<?php echo $this->config->item('time_zone'); ?>';
        return moment(date1).tz(timeZone).format("DD-MMM-YYYY");
    }

    function save_store_to_session1(store_id, redirect_url){
        $("#fullLoader").show();
        jQuery.ajax({
            type: 'POST',
            url: '<?php echo base_url('Admin/save_store_to_session');?>',
            data: {
                'store_id':store_id
            },
            success: function (res) {
                $("#fullLoader").hide();
                console.log(res);
                if(res.status == 'success'){
                    swal(
                        {
                            title: "Success!",
                            text: "Store Selected Successfully!",
                            type: "success",
                            timer: 250,
                            showConfirmButton: false
                        },
                        function () {
                            if(redirect_url != '') {
                                window.location = redirect_url;
                            } else {
                                window.location.reload();
                            }
                        }
                    );
                } else {
                    swal(
                        {
                            title: "Error",
                            text: "Error while selecting store. Please try again!",
                            type: "error"
                        }
                    );
                }
            }
        });
    }

    function select_store(redirect_url) {
        <?= $store_id ="";
        $store_data = $this->session->get_userdata('user');
        if(array_key_exists('store',$store_data)){
          $store_id =   $store_data['store'][0]['id'];
        } ?>;
        
        var store = "<?= $store_id; ?>";
        if (store != "" && redirect_url != "") {
            window.location = redirect_url;
        } else {
            $("#fullLoader").show();
            jQuery.ajax({
                type: 'GET',
                url: '<?php echo base_url('Admin/get_stores');?>',
                dataType:"json",
                success: function (res) {
                    $("#fullLoader").hide();
                    if (!res.error) {
                        //console.log(res.stores);
                        var stores_html = '<div class="row">';
                        res.stores.forEach(function (store) {
                            var change_class = 'inactive_store';
                            if(store.status =='active'){
                                change_class = 'active_store';
                            }
                            var onclick_str = "save_store_to_session1(" + store.id + ",'" + redirect_url + "')";
                           stores_html += '<div class="col-md-4" >\
                                                <div  style="border:solid 1px #f5f5f5;">\
                                                    <a href="#" onclick="' + onclick_str + '">\
                                                        <div class="block-in text-center">\
                                                            <div class="block-into">\
                                                                <img src="<?= base_url('public/')?>' + store.logo + '">\
                                                            </div>\
                                                            <div class="block-in-btn text-center">\
                                                                <span class ="'+change_class +'"></span><span>'+ store.name + '</span>\
                                                                <p></p>\
                                                            </div>\
                                                        </div>\
                                                    </a>\
                                                </div>\
                                            </div>';
                        });
                        stores_html += '</div>';
                        swal({
                            html: true,
                            title: 'Select a Store',
                            type: 'info',
                            text: stores_html,
                            showCloseButton: true,
                            showCancelButton: true,
                            showConfirmButton: false,
                            focusConfirm: false
                        })
                    } else {
                        swal(
                            {
                                title: "Error",
                                text: "Couldn't fetch stores. Setting Store 1 as selected store",
                                type: "error"
                            },
                            function () {
                                save_store_to_session1(store, redirect_url);
                            }
                        );
                    }
                }
            });
        }
    }
    </script>
    </body>
</html>
<?php
$user = $this->session->get_userdata('go2groadmin_session');
$api_key = $user['go2groadmin_session']['logged_user_api_key'];
?>

<style>
    table.dataTable tr th.select-checkbox.selected::after {
        content: "✔";
        margin-top: -11px;
        margin-left: -4px;
        text-align: center;
        text-shadow: rgb(176, 190, 217) 1px 1px, rgb(176, 190, 217) -1px -1px, rgb(176, 190, 217) 1px -1px, rgb(176, 190, 217) -1px 1px;
    }
</style>

<div class="content-w" style="width:100%;" xmlns="http://www.w3.org/1999/html">
    <!--------------------
START - Breadcrumbs
-------------------->
    <ul class="breadcrumb">
        <li class="breadcrumb-item"><span>Notifications</span>
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
                    <div class="element-wrapper" style="padding-bottom: 1%;">
                        <h6 class="element-header">Notifications</h6>

                        <div class="col-md-12 element-box" style="border:solid 1px #efefef; padding:3% 5%;">

										<form>
											<div class="form-group">
												<label for=""> Notification Title : </label>
												<input class="form-control" placeholder="Item Name" type="text"  id="title">
											</div>
											
											<div class="form-group">
												<label> Notification Message : </label>
												<textarea class="form-control" rows="3" id="message"></textarea>
											</div>
											
											<div class="form-buttons-w">
												<button class="btn btn-primary" type="button"  onclick="send_notifications()" value="Send Notification"> Submit</button>
											</div>
										</form>
						
						
						
                           <!-- <div class="form-group" style="width:100%;">
                                <div class="row no-margin">
                                    <div class="col-md-12">
                                        <div class="form-check">
                                            <label></label><br>
                                            <input type="text" class="" name="" id="title" placeholder="">
                                        </div>
                                    </div>
                                </div>
                                <div class="row no-margin">
                                    <div class="col-md-12">
                                        <div class="form-check">
                                            <label> </label><br>
                                            <textarea class="" name="" id="message"> </textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row no-margin">
                                    <div class="col-md-12">
                                        <div class="form-check">
                                            <input type="submit" id="btn-submit" onclick="send_notifications()" value="Send Notification">
                                        </div>
                                    </div>
                                </div>
                            </div>
							
							-->

                        </div>
                    </div>
					
					 <div class="element-wrapper"> 
						<div class="col-md-12 element-box"> 
                            <div class="form-group" style="width:100%;">
                                <div class="row no-margin">
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input checked="checked" class="form-check-input" name="userType"
                                                       type="radio" value='all' onclick="userlist()">
                                                All Users
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input class="form-check-input" name="userType"
                                                       type="radio" value='items_in_cart' onclick="userlist()">
                                                Items in cart
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input class="form-check-input" name="userType"
                                                       type="radio" value='recent_active_users' onclick="userlist()">
                                                Recently Active Users
                                            </label>
                                        </div>
                                    </div>


                                </div>
                            </div>
                            <div class="table-responsive" id="usersData">
                            </div>
						</div>
					 </div>
					 
					 
					 
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var all_users = [];
    var dt_table;
    jQuery(document).ready(function () {
        
        var type = jQuery("input[name='userType']:checked").val();
        if (type == 'all') {
            userlist();
        }
        /*
        jQuery("input[name='userType']").click(function () {
            var userType = jQuery(this).val();
            if (userType == 'all') {
                // jQuery('#dateForm').hide();
                userlist();
            }
            if (userType == 'datewise') {
                jQuery('#dateForm').show();
            }
        })

        jQuery("#btn-submit").on('click', function(e){
            e.preventDefault();

            var user_ids = [];
            var checkboxes = dt_table.jQuery('.usr_check');
            for (var i = 0; i < checkboxes.length; i++) {
                if (checkboxes[i].type == 'checkbox' && checkboxes[i].checked == true) {
                    user_ids.push(checkboxes[i].value);
                }
            }
            console.log(user_ids);


            //console.log(dt_table.jQuery('.usr_check').serialize());

//            $.ajax({
//                url: "/path/to/your/script.php",
//                data: table.$('input[type="checkbox"]').serialize()
//            }).done(function(data){
//                console.log("Response", data);
//            });

        })
        */

        $('body').on('click', '.usr_check', function () {
            var usr_id = $(this).val();
//            console.log();
            if ($(this).prop('checked')) {
//                console.log(usr_id+' checked');
                if(jQuery.inArray(usr_id, all_users) == -1) {
                    all_users.push(usr_id);
                }
            } else {
//                console.log(usr_id+' unchecked');
                var index = all_users.indexOf(usr_id);
                if (index > -1) {
                    all_users.splice(index, 1);
                }
            }
        })

    });

    function a(){
        dt_table = $('#userTable').dataTable({
            stateSave: true,
            destroy: true,
//            columnDefs: [{
//                orderable: false,
//                className: 'select-checkbox',
//                targets: 0
//            }],
//            select: {
//                style: 'os',
//                selector: 'td:first-child'
//            },
            "order": [[3, "desc"]],
            dom: 'Bfrtip',
            buttons: [
                // 'excelHtml5'
                // 'csvHtml5',
                //'pdfHtml5'
            ]
        })
        var allPages = dt_table.fnGetNodes();
        $('body').on('click', '#selectAll', function () {

            if ($(this).hasClass('allChecked')) {
                all_users = [];
                $('.usr_check', allPages).prop('checked', false);
            } else {
                jQuery(allPages).each(function () {
                    var usr_id = jQuery(this).find('.usr_check').val();
                    if(jQuery.inArray(usr_id, all_users) == -1) {
                        all_users.push(usr_id);
                    }
                });
                $('.usr_check', allPages).prop('checked', true);
            }
            $(this).toggleClass('allChecked');
        })
        console.log(allPages);
    }


    /*
    function checkAll(ele) {
        var checkboxes = document.getElementsByClassName('usr_check');
        if (ele.checked) {
            for (var i = 0; i < checkboxes.length; i++) {
                if (checkboxes[i].type == 'checkbox') {
                    checkboxes[i].checked = true;
                }
            }
        } else {
            for (var i = 0; i < checkboxes.length; i++) {
                console.log(i)
                if (checkboxes[i].type == 'checkbox') {
                    checkboxes[i].checked = false;
                }
            }
        }
    }
    */

    function send_notifications() {
        /*var selectuser_ids = [];
        jQuery("input:checkbox[class=usr_check]:checked").each(function(){
            selectuser_ids.push(jQuery(this).val());
        });*/
        var title = jQuery('#title').val();
        var message = jQuery('#message').val();
        if(typeof all_users !== 'undefined' &&  all_users.length > 0){
            selectuser_ids = all_users;
        }else{
            var allPages = dt_table.fnGetNodes();
            selectuser_ids = [];
            jQuery(allPages).each(function () {
                var usr_id = jQuery(this).find('.usr_check').val();
                if(jQuery.inArray(usr_id, all_users) == -1) {
                    selectuser_ids.push(usr_id);
                }
            });
        }
        if(title == '' && message == ''){
            swal("Oops!","Please Enter title and message","warning");
            return false;
        }
        else if (selectuser_ids.length > 0){
            var title = jQuery('#title').val();
            var message = jQuery('#message').val();

            var api_key = '<?php echo $api_key; ?>';
            var url = '<?php echo base_url();?>Admin/send_notification_to_users';
            var obj = {'Authorization': api_key, 'title': title, 'message': message, 'users': JSON.stringify(selectuser_ids)};
            jQuery("#fullLoader").show();
            jQuery.ajax({
                type: 'POST',
                url: url,
                data: obj,
                success: function (res) {
                    jQuery("#fullLoader").hide();
                    console.log(res);
                    if (!res.error) {
                        swal("Success","Notifications sent successfully","success");
                    } else {
                        swal("Oops!","An Error occured while sending notifications","warning");
                    }
                }
            });

            /*
             var user_ids = [];
             var checkboxes = dt_table.jQuery('.usr_check');
             for (var i = 0; i < checkboxes.length; i++) {
             if (checkboxes[i].type == 'checkbox' && checkboxes[i].checked == true) {
             user_ids.push(checkboxes[i].value);
             }
             }
             console.log(user_ids);
             */
        } else {
            swal("Oops!","Please select a user to send notification","warning");
            return false;
        }
    }

    function userlist() {
        var api_key = '<?php echo $api_key; ?>';
        var userType = jQuery("input[name='userType']:checked").val();

        jQuery("#fullLoader").show();
        var url;
        var obj;
        // if (userType == 'all') {
            url = '<?php echo base_url();?>Admin_control/getUsers';
            obj = {'Authorization': api_key, 'status': userType};
        // }
        /*
        if (userType == 'datewise') {
            var tdate = jQuery("#tdate").val();
            var fdate = jQuery("#fdate").val();
            var startDate = new Date(tdate);
            startDate.setHours(0, 0, 0, 0);
            var fromUnitime = Math.floor(startDate.getTime() / 1000);
            var endDate = new Date(fdate);
            endDate.setHours(23, 59, 59, 59);
            var toUnitime = Math.floor(endDate.getTime() / 1000);

            url = '<?php //echo base_url();?>Admin/getUsers';
            obj = {'Authorization': api_key, 'fdate': fromUnitime, 'tdate': toUnitime, 'status': userType};
        }
        */

        jQuery.ajax({
            type: 'GET',
            url: url,
            data: obj,
            success: function (res) {
                console.log(res);
                jQuery("#fullLoader").hide();
                var html = '';
                if (!res.error) {

                    if (userType == 'all'){

                        var userslist = res.userslist;
                        var jsonlength = userslist.length;
                        html += '<table class="table table-lightborder" id="userTable">\
                                    <thead>\
                                        <tr>\
                                        <th><input type="checkbox" id="selectAll" name="chk[]"></th>\
                                        <th>Name</th>\
                                        <th>Email</th>\
                                        <th>Mobile</th>\
                                        <th>Address</th>\
                                        <th>Pin Code</th>\
                                        <th>City</th>\
                                        <th>State</th>\
                                        <th>Country</th>\
                                        <th>Date/Time</th>\
                                        </tr>\
                                    </thead>\
                                    <tbody>';

                        for (var i = 0; i < jsonlength; i++) {

                            var result = userslist[i];

                            var dateString = unitimeToTime(result.unitime);

                            var shipping_address = '';
                            try {
                                var shipping_addr_json = JSON.parse(result.address);
                                shipping_address = shipping_addr_json.street_address;
                                if (shipping_addr_json.apt_no != '') {
                                    shipping_address += ', ' + shipping_addr_json.apt_no;
                                }
                                if (shipping_addr_json.complex_name != '') {
                                    shipping_address += ', ' + shipping_addr_json.complex_name;
                                }
                            } catch (e) {
                                shipping_address = result.address;
                            }

                            html += '<tr>\
                                  <td class="nowrap"><input class="usr_check" type="checkbox" value="' + result.id + '"></td>\
                                  <td class="nowrap">' + result.first_name + ' ' + result.last_name + '</td>\
                                  <td class="text-center">' + result.email_id + '</td>\
                                  <td class="text-center">' + result.mobile + '</td>\
                                  <td class="text-center tooltip111">' + shipping_address.substr(0, 15) + '...<span class="tooltiptext tooltip111-bottom">' + shipping_address +'</span></td>\
                                  <td class="text-center" >' + result.pincode + '</td>\
                                  <td class="text-center" >' + result.city + '</td>\
                                  <td class="text-center" >' + result.state + '</td>\
                                  <td class="text-center">' + result.country + '</td>\
                                  <td class="text-center"><span style="display: none;">' + result.unitime + '</span>' + dateString + '</td>\
                               </tr>';
                        }
                        html += '</tbody></table>';
                    } else if(userType == 'items_in_cart'){
                        var userslist = res.userslist;
                        html += '<table class="table table-lightborder" id="userTable">\
                                    <thead>\
                                        <tr>\
                                            <th><input type="checkbox" id="selectAll" name="chk[]"></th>\
                                            <th>User</th>\
                                            <th>Store</th>\
                                            <th>Cart</th>\
                                            <th>Subtotal</th>\
                                        </tr>\
                                    </thead>\
                                    <tbody>';

                        for (var i = 0; i < userslist.length; i++) {
                            var result = userslist[i];
                            var user = result.user;
                            var store = result.store;
                            var items = result.items;

                            var item_names = '';
                            for (var j = 0; j < items.length; j++) {
                                var itm = items[j];
                                item_names += itm.item_name;
                                if(j != items.length-1){
                                    item_names += ', ';
                                }
                            }

                            html += '<tr>\
                                  <td class="nowrap"><input class="usr_check" type="checkbox" value="' + user.id + '"></td>\
                                  <td class="nowrap">' + user.name  + '<br>' + user.email_id + '<br>'+ user.mobile + '</td>\
                                  <td class="text-center" >' + store.name + '</td>\
                                  <td class="text-center" >' + item_names + '</td>\
                                  <td class="text-center" >' + result.subtotal + '</td>';
                            html += '</tr>';
                        }
                        html += '</tbody></table>';
                    }
                    else if(userType == 'recent_active_users'){
                        var userslist = res.userslist;
                        html += '<table class="table table-lightborder" id="userTable">\
                                    <thead>\
                                        <tr>\
                                            <th><input type="checkbox" id="selectAll" name="chk[]"></th>\
                                            <th>User</th>\
                                            <th>Store</th>\
                                            <th>Order</th>\
                                            <th>Status</th>\
                                        </tr>\
                                    </thead>\
                                    <tbody>';

                        for (var i = 0; i < userslist.length; i++) {
                            var result = userslist[i];
                            var status = 'NA';
                            if(result.status == 4){
                                status = 'Delivered';
                            } else if(result.status == 5){
                                status = 'Rejected';
                            } else if(result.status == 6){
                                status = 'Cancelled';
                            }



                            html += '<tr>\
                                  <td class="nowrap"><input class="usr_check" type="checkbox" value="' + result.user_id + '"></td>\
                                  <td class="nowrap">' + result.first_name  + ' ' + result.last_name + '<br>' + result.email_id + '<br>'+ result.mobile + '</td>\
                                  <td class="text-center" >' + result.store_name + '</td>\
                                  <td class="text-center" ><a target="_blank" href="<?= base_url("Admin/checkstatus/")?>' + result.order_id + '/'+result.store_id+'">' + result.order_id + '</a><br>'+timeConverter(result.datetime)+'</td>\
                                  <td class="text-center" >' + status + '</td>';
                            html += '</tr>';
                        }
                        html += '</tbody></table>';
                    }
                    
                    //dt_table.fnClearTable();
                    //dt_table.fnDraw();
                    //dt_table.fnDestroy();
                    //  dt_table = $('#userTable').dataTable();
                    // if ($.fn.dataTable.isDataTable("#userTable"))
                    // {
                    //     dt_table.fnClearTable();
                    //     dt_table.fnDraw();
                    //     dt_table.fnDestroy();
                    // }
                    $('#usersData').html(html);
                    a();

                } else {
                    swal("No Record Found");
                }

            }
        });

        return false;
    }
    function timeConverter(unix_timestamp) {
        var a = new Date(unix_timestamp * 1000);
        var today = new Date();
        var yesterday = new Date(Date.now() - 86400000);
        var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        var year = a.getFullYear();
        var month = months[a.getMonth()];
        var date = ("0"+a.getDate()).substr(-2);
        var hour = ("0"+a.getHours()).substr(-2);
        var min = ("0"+a.getMinutes()).substr(-2);

//        var date = a.getDate();
//        var hour = a.getHours();
//        var min = a.getMinutes();

        if (a.setHours(0,0,0,0) == today.setHours(0,0,0,0))
            return 'today, ' + hour + ':' + min;
        else if (a.setHours(0,0,0,0) == yesterday.setHours(0,0,0,0))
            return 'yesterday, ' + hour + ':' + min;
        else if (year == today.getFullYear())
            return date + ' ' + month + ', ' + hour + ':' + min;
        else
            return date + ' ' + month + ' ' + year + ', ' + hour + ':' + min;
    }
</script>
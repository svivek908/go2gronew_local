<?php
$user = $this->session->get_userdata('go2groadmin_session');
$api_key = $user['go2groadmin_session']['logged_user_api_key'];

?>
<style>
	.user-pro-img img{
		border:solid 1px #eee; display:inline-block; width:50px; height:50px;border-radius: 50%;
	}
</style>

<div class="content-w" style="width: 100%;">
    <!--------------------
START - Breadcrumbs
-------------------->
    <ul class="breadcrumb">
        <li class="breadcrumb-item"><span>Picker Users Detail</span>
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
                    <div class="element-wrapper">
                        <h6 class="element-header">Picker Users Detail</h6>

                        <div class="col-md-12 element-box" style="border:solid 1px #efefef; padding:3% 5%;">

                            <div class="table-responsive" id="pickeruserData">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    jQuery(document).ready(function () {
        pickeruserlist();
    });
    function pickeruserlist() {
        var api_key = '<?php echo $api_key; ?>';
        jQuery("#fullLoader").show();
        var url;
        var obj = {'Authorization': api_key};
        jQuery.ajax({
            type: 'GET',
            url: "<?php echo base_url().'Admin/getpickerDeliveryUserList'; ?>",
            data: obj,
            success: function (res) {
                jQuery("#fullLoader").hide();
                var html = '';
                if (!res.error) {
                    var userslist = res.userlist;
                    var jsonlength = userslist.length;
                    html += '<table class="table table-lightborder" id="pickeruserTable">\
                    <thead>\
                    <tr>\
                    <th>Image</th>\
                    <th>Name</th>\
                <th>Email</th>\
                <th>Mobile</th>\
                <th>Address</th>\
                <th>Zip Code</th>\
                <th title="Social Security Number">SSN</th>\
                <th>Action</th>\
                <th title="Social Security Number policy">SSN Policy</th>\
                <th title="Shoper\'s Shopping Applicant Policy">SSA Policy</th>\
                <th>Contract Agreement</th>\
                </tr>\
                </thead>\
                <tbody>';

                    for (var i = 0; i < jsonlength; i++) {

                        var result = userslist[i];
                        html += '<tr>\
                              <td class="nowrap"><span class="user-pro-img"> <img src="<?php echo $this->config->item("api_url_image"); ?>' + result.image + '" /></span></td>\
                              <td >' + result.name + '</td>\
                              <td title="' + result.email + '" class="word-wrap">' + result.email + '</td>\
                              <td >' + result.mobile_no + '</td>\
                              <td title="' + result.Address + '" class="word-wrap">' + result.Address + '</td>\
                              <td >' + result.zip_code + '</td>\
                              <td >' + result.SSN + '</td>\
                              <td >' + (result.is_active==0 && result.ic_agreement==1 && result.sa_policy ==1 && result.taxpayer_i_c_policy==1?'<button id="picker_'+result.id+'" class="btn btn-primary" onclick="approvePicker(\''+result.id+'\', 1 )"> Approve </button>':result.is_active==1?'Approved':'<button id="picker_'+result.id+'" class="btn btn-primary" onclick="approvePicker(\''+result.id+'\', 1 )"> Approve </button>') + '</td>\
                              <td >' + (result.ic_agreement==0?"N":"Y") + '</td>\
                              <td >' + (result.sa_policy ==0?"N":"Y") + '</td>\
                              <td >' + (result.taxpayer_i_c_policy==0?"N":"Y") + '</td>\
                           </tr>';
                    }
                    html += '</tbody></table>';
                    $('#pickeruserData').html(html);
                    $('#pickeruserTable').dataTable();
                } else {
                    swal("No Record Found");
                }
            }
        });

        return false;
    }

    function approvePicker(pickerId, status){
        var api_key = "<?php echo $api_key;?>";
        var message;
        if(status == 1){
            message = "You want to approve this user";
        }else{
            message ="You want to disapprove this user";
        }
        swal({
            title:"Are you sure?",
            text: message,
            showCancelButton: true,
            closeOnConfirm: false,
            animation: "slide-from-top",
            showLoaderOnConfirm: true
        },function(){
            jQuery.ajax({
                type: 'POST',
                url: '<?php echo base_url();?>Admin/approvalPickerDelivery',
                data: {'Authorization': api_key, 'picker_id':pickerId, 'status':status},
                success: function (res) {
                    if(!res.error){
                        swal('Success!', res.message, 'success');
                        jQuery("#picker_"+pickerId).replaceWith('Approved');
                    }else{
                        swal('Error!', res.message, 'error');
                    }
                }
            });
        });

    }
</script>
<?php 

/**
 * get logged admin data
 * @param string $apikey
 * @param string $name
 */
if ( ! function_exists('alert')) {  
    function alert($msg='', $type='success_msg') {
        $CI =& get_instance();?>
        <?php if (empty($msg)): ?>
            <?php if ($CI->session->flashdata('success_msg')): ?>
              <?php echo success_alert($CI->session->flashdata('success_msg')); ?>
            <?php endif ?>
            <?php if ($CI->session->flashdata('error_msg')): ?>
              <?php echo error_alert($CI->session->flashdata('error_msg')); ?>
            <?php endif ?>
            <?php if ($CI->session->flashdata('info_msg')): ?>
              <?php echo info_alert($CI->session->flashdata('info_msg')); ?>
            <?php endif ?>
        <?php else: ?>
            <?php if ($type == 'success_msg'): ?>
              <?php echo success_alert($msg); ?>
            <?php endif ?>
            <?php if ($type == 'error_msg'): ?>
              <?php echo error_alert($msg); ?>
            <?php endif ?>
            <?php if ($type == 'info_msg'): ?>
              <?php echo info_alert($msg); ?>
            <?php endif ?>
        <?php endif; ?>
    <?php }
}
/**
* Success alert
*/
if ( ! function_exists('success_alert')) {  
    function success_alert($msg = '') {?>
        <div class="alert alert-success">
            <button data-dismiss="alert" class="close" type="button">×</button>
            <strong>Success!</strong> <?php echo $msg ?>
        </div>
    <?php 
    }
}
 
/**
* Error alert
*/
if ( ! function_exists('error_alert')) {  
    function error_alert($msg = '') {?>
        <div class="alert alert-danger">
            <button data-dismiss="alert" class="close" type="button">×</button>
            <strong>Error!</strong> <?php echo $msg ?>
        </div>
    <?php 
    }
}
 
/**
* info alert
*/
if ( ! function_exists('info_alert')) { 
    function info_alert($msg = '') {?>
        <div class="alert alert-info">
            <button data-dismiss="alert" class="close" type="button">×</button>
            <strong>Info: </strong> <?php echo $msg ?>
        </div>
    <?php 
    }
}

if (!function_exists('logged_admin_record')) {
    function logged_admin_record() {
        $ci = get_instance();
        if($ci->session->has_userdata('go2groadmin_session')){
            return array('name' => $ci->session->userdata['go2groadmin_session']['logged_username'],
            'id' => $ci->session->userdata['go2groadmin_session']['logged_userid'],
            'apikey' => $ci->session->userdata['go2groadmin_session']['logged_user_api_key']);
        }
    }
}

/**
 * link the css files 
 * 
 * @param array $array
 * @return print css links
 */
if (!function_exists('load_css')) {
    function load_css(array $array) {
        $ci = get_instance();
        foreach ($array as $uri) {
            echo "<link rel='stylesheet' type='text/css' href='" . base_url($uri) .$ci->config->item('Web_unique_url'). "' /> \n";
        }
    }
}

/**
 * link the javascript files 
 * 
 * @param array $array
 * @return print js links
 */
if (!function_exists('load_js')) {

    function load_js(array $array) {
        $ci = get_instance();
        foreach ($array as $uri) {
            echo "<script type='text/javascript'  src='" . base_url($uri) .$ci->config->item('Web_unique_url')."'></script>\n";
        }
    }
}

if (!function_exists('get_selected_storeid')) {
    function get_selected_storeid(){
        $ci = get_instance();
        $storeid = false;
        if($ci->session->has_userdata('select_store_data')){
            $storeid = $ci->session->userdata['select_store_data']['storeid'];
            return $storeid;
        }
        return $storeid;
    }
}

if (!function_exists('getuserid')) {
    function getuserid($table,$auth_key){
        $ci = get_instance();
        $uid = false;
        $getuserid = $ci->Model->get_selected_data('id',$table,array('api_key' => $auth_key));
        if(count($getuserid) > 0){
            $uid = $getuserid[0]['id'];
        }
        return $uid;
    }
}

/**
 * create a encoded id for sequrity pupose 
 * 
 * @param string $id
 * @param string $salt
 * @return endoded value
 */
if (!function_exists('encode_str')) {
    function encode_str($id, $salt) {
        $ci = get_instance();
        $id = $ci->encrypt->encode($id . $salt);
        $id = str_replace("=", "~", $id);
        $id = str_replace("+", "_", $id);
        $id = str_replace("/", "-", $id);
        return $id;
    }
}

/**
 * decode the id which made by encode_id()
 * 
 * @param string $id
 * @param string $salt
 * @return decoded value
 */
if (!function_exists('decode_str')) {
    function decode_str($id, $salt) {
        $ci = get_instance();
        $id = str_replace("_", "+", $id);
        $id = str_replace("~", "=", $id);
        $id = str_replace("-", "/", $id);
        $id = $ci->encrypt->decode($id);
        if ($id && strpos($id, $salt) !== false) {
            return str_replace($salt, "", $id);
        }
    }
}

//------file upload----
if (!function_exists('do_file_upload')) {
    function do_file_upload($filename,$uploadpath,$filetype,$size='',$width='',$height='') {
        $file = [];
        $ci = & get_instance();
        $config['upload_path']          = $uploadpath;
        $config['allowed_types']        = $filetype;
        $config['max_size']             = $size;
        $config['max_width']            = $width;
        $config['max_height']           = $height;
        $config['encrypt_name']           = true;
        $ci->load->library('upload', $config);
        if (!$ci->upload->do_upload($filename))
        {
            $file = array('success' => false,'error'=>$ci->upload->display_errors());
        }
        else
        {
            $file = array('success' => true,'done'=>$ci->upload->data());
        }
        return $file;
    }
}
//-------------Ajax Pagination------------

if(! function_exists('ajaxpagination'))
{ 
    function ajaxpagination($url, $rowscount, $per_page,$fun='',$uri_segment)
    {
        $call = 'all_pagination';
        if($fun){
            $call = $fun;
        }
        $ci = & get_instance();
        $ci->load->library('Ajax_pagination');
        $config = array();
        $config['base_url'] = site_url($url);
        $config["uri_segment"] = $uri_segment;
        $config["total_rows"] = $rowscount;
        $config["per_page"] = $per_page;
        $config['link_func']  = $call;
        $config['full_tag_open'] = '<nav><ul class="pagination">';
        $config['full_tag_close'] = '</ul></nav>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a class="page-link">';
        $config['cur_tag_close'] = '</a></li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['first_link'] = 'First';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_link'] = 'Last';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        //$ci->pagination->initialize($config);
        $ci->ajax_pagination->initialize($config);
        //return $ci->ajax_pagination->create_links();
    }
}

//-------------Normal Pagination------------
if(! function_exists('cipagination'))
{
    function cipagination($url, $rowscount, $per_page) {
        $ci = & get_instance();
        $ci->load->library('pagination');
        $config = array();
        $config["base_url"] = base_url($url);
        $config["total_rows"] = $rowscount;
        $config["per_page"] = $per_page;
        $config["uri_segment"] = 3;
        $config['full_tag_open'] = '<nav><ul class="pagination">';
        $config['full_tag_close'] = '</ul></nav>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a>';
        $config['cur_tag_close'] = '</a></li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['first_link'] = 'First';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_link'] = 'Last';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $ci->pagination->initialize($config);
        return $ci->pagination->create_links();
    }
}

if(! function_exists('responseJSON'))
{
    function responseJSON($data)
    {
        if (gettype($data) == 'array') {
            $data = json_encode($data);
        } else if (gettype($data) == 'object') {
            $data = json_encode($data);
        }
        header('Content-Type: application/json; charset=utf-8');
        echo $data;
        return;
    }
}

if(! function_exists('getTextStatus'))
{
    function getTextStatus($statusid)
    {
        switch($statusid)
        {
            case 0:
                $status="PENDING";
                break;
            case 1:
                $status="PREPARE";
                break;
            case 2:
                $status="PACKED";
                break;
            case 3:
                $status="OUT FOR DELIVERY";
                break;
            case 4:
                $status="DELIVERED";
                break;
            case 6:
                $status="CANCELLED";
                break;
            default:
                $status="REJECT";
        }
        return $status;
    }
}

if(! function_exists('verifyRequiredParams')){
    function verifyRequiredParams($required_fields)
    {
        $error = false;
        $error_fields = "";
        $request_params = array();
        $request_params = $_REQUEST;
        // Handling PUT request params
        if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
            $ci = & get_instance();
            parse_str($ci->request()->getBody(), $request_params);
        }
        foreach ($required_fields as $field) {
            if (!isset($request_params[$field]) || strlen(trim($request_params[$field])) <= 0) {
                $error = true;
                $error_fields .= $field . ', ';
            }
        }

        if ($error) {
            // Required field(s) are missing or empty
            // echo error json and stop the app
            $response = array();
            $ci = & get_instance();
            $response["error"] = true;
            $response["message"] = 'Required field(s) ' . substr($error_fields, 0, -2) . ' is missing or empty';
            return $response;
        }
    }
}

if(! function_exists('generateRandomString')){
    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}

//==================generateApiKey==============
if(! function_exists('generateApiKey')){
    function generateApiKey()
    {
        return md5(uniqid(rand(), true));
    }
}

//==================get_client_ip==============
if(! function_exists('get_client_ip')){
    function get_client_ip()
    {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress; 
    }
}
//=====================get_the_browser==============
if(! function_exists('get_the_browser')){
    function get_the_browser()
    {
        if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)
            return 'Internet explorer';
        elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== false)
            return 'Internet explorer';
        elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox') !== false)
            return 'Mozilla Firefox';
        elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== false)
            return 'Google Chrome';
        elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mini') !== false)
            return "Opera Mini";
        elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Opera') !== false)
            return "Opera";
        elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Safari') !== false)
            return "Safari";
        else
            return 'Other';
    }
}
//=================getpinExist===================
if(! function_exists('getpinExist')){
    function getpinExist($zipcode){
        $ci = & get_instance();
        $result = false;
        $zipcode = $ci->Model->get_row('avl_pincode',array('pincode' => $zipcode,'status'=>'0'));
        if($zipcode){
            $result = $zipcode;
        }
        return $result;
    }
}

//================is_item_under_purchase_limit==============
if(! function_exists('is_item_under_purchase_limit')){
    function is_item_under_purchase_limit($item_id, $item_qty, $user_id,$action,$store_id){
        $ci = & get_instance();
        $upper_limit = ITEM_LIMIT_EXCEED; // 192 ounces for beer and wine combined
        $ounces = 0;
        $items_in_cart = [];
        $cart_items = $ci->Cart_model->getcartitem($user_id,$store_id); // Get cart items of the logged in user
        if(count($cart_items) > 0){
            foreach ($cart_items as $value) {
              array_push($items_in_cart, $value['item_id']);
            }
        }

        $item_details = $ci->Cart_model->is_item_under_purchase_limit($items_in_cart,$store_id,$user_id); // executes only when cart not empty
        if(count($item_details) > 0){
            foreach ($item_details as $item_detail) {
                if($item_detail['fluid_ounce'] != NULL && $item_detail['item_id'] != $item_id ){ // exclude current item to avoid calculation mistake in case of qty update
                    $ounces += ($item_detail['fluid_ounce'] * $item_detail['item_quty']);
                }
            }
        }

        if($action == 'additem'){
            $current_item_details = $ci->Cart_model->is_item_under_purchase_limit(array($item_id),$store_id); // executes for the current item and cart is empty (for the first time)
        } elseif($action == 'updateitem'){
            $current_item_details = $ci->Cart_model->is_item_under_purchase_limit(array($item_id),$store_id, $user_id); // executes for the current item and cart is empty (for the first time)
        }

        if(count($current_item_details) > 0){
            foreach ($current_item_details as  $current_item_detail) {
                if($current_item_detail['fluid_ounce'] != NULL ){
                    $ounces += ($current_item_detail['fluid_ounce'] * $item_qty );
                }
            }
        }
        return ($ounces > $upper_limit) ? false : true;
    }
}

//===================is_item_beers_under_purchase_limit===============
if(! function_exists('is_item_beers_under_purchase_limit')){
    function is_item_beers_under_purchase_limit($item_id, $item_qty, $user_id, $action,$store_id){
        $ci = & get_instance();
        $upper_limit_qyt = Item_Beers_exceed_limit; // 12 qty for beer 
        $purchase_qty = 0;
        $items_in_cart = [];

        $cart_items = $ci->Cart_model->getcartitem($user_id,$store_id); // Get cart items of the logged in user
        if(count($cart_items) > 0){
            foreach ($cart_items as $value) {
              array_push($items_in_cart, $value['item_id']);
            }
        }

        $item_details = $ci->Cart_model->is_item_under_purchase_limit($items_in_cart,$store_id,$user_id); // executes only when cart not empty
        if(count($item_details) > 0){
            foreach ($item_details as $item_detail) {
                // exclude current item to avoid calculation mistake in case of qty update
                if($item_detail['item_type'] == Test_item_type && $item_detail['item_id'] != $item_id){ 
                    $purchase_qty += ($item_detail['item_size'] * $item_detail['item_quty']);
                }
            }
        }
       
        if($action == 'additem'){
            $current_item_details = $ci->Cart_model->is_item_under_purchase_limit(array($item_id),$store_id); // executes for the current item and cart is empty (for the first time)
        } elseif($action == 'updateitem'){
            $current_item_details = $ci->Cart_model->is_item_under_purchase_limit(array($item_id),$store_id, $user_id); // executes for the current item and cart is empty (for the first time)
        }

        if(count($current_item_details) > 0){
            foreach ($current_item_details as  $current_item_detail) {
                if($current_item_detail['item_type'] == Test_item_type){
                   $purchase_qty += ($current_item_detail['item_size'] *  $item_qty);
                }
            }
        }
        return ($purchase_qty > $upper_limit_qyt) ? false : true;
    }
}

//==================
if(! function_exists('is_membership_applicable')){
    function is_membership_applicable($userid, $subtotal,$free_delivery_amount){
        $ci = & get_instance();
        $membership_applicable = false;
        $user_details = $ci->Model->get_selected_data(array('membership_plan_id','membership_date'),'users',array('id' => $userid));
        if(isset($user_details[0])){
            $user_details = $user_details[0];
            $usr_membership_plan_id = $user_details['membership_plan_id'];
            $usr_membership_date = $user_details['membership_date'];
            if($usr_membership_plan_id > 0){
                $check_expire = json_decode($usr_membership_date);
                $current_time = date("Y-m-d H:i:s",time()); // Getting Current Date & Time
                $current_timestamp = strtotime($current_time); 
                if($check_expire->expire >= $current_timestamp){
                    if($subtotal >= $free_delivery_amount){
                        $membership_applicable = true;
                    }
                }
            }
        }
        return $membership_applicable;
    }
}

//=====================return shipping address============
if ( ! function_exists('get_compatible_address')) { 
    function get_compatible_address($address){
        $address_arr = json_decode($address);
        if(isset($address_arr->street_address)){
            $address_json = $address;
        } else {
            $address_json['street_address'] = $address;
            $adress_json['apt_no'] = $address_json['complex_name'] = $address_json['latitude'] = $address_json['longitude'] = '';
            $address_json = json_encode($address_json);
        }
        return $address_json;
    }
}

//==================check user membership===========
if ( ! function_exists('check_user_membership')) { 
    function check_user_membership($userid){
        $ci = & get_instance();
        $user_details = $ci->Model->get_selected_data(array('membership_plan_id','membership_date'),'users',array('id' => $userid));
        if(isset($user_details[0])){
            $user_details = $user_details[0];
            $usr_membership_plan_id = $user_details['membership_plan_id'];
            $usr_membership_date = $user_details['membership_date'];
            $ismembership = false;
            if($usr_membership_plan_id > 0){
                $check_expire = json_decode($usr_membership_date);
                $current_time = date("Y-m-d H:i:s",time()); // Getting Current Date & Time
                $current_timestamp = strtotime($current_time); 
                if($check_expire->expire >= $current_timestamp){
                    $ismembership = true;
                }
            }
            return $ismembership;
        }
    }
}

//==================get_discount_for_user===========
/*if ( ! function_exists('get_discount_for_user')) { 
    function get_discount_for_user($userid){
        $udiscount = 0;
        $ci = & get_instance();
        $referral_discount = $ci->Model->get_selected_data(array('referred_by','referred_to','redeemed_referral_count','max_referrals_allowed'),"users",array('id' => $userid));
        if(count($referral_discount) > 0){
            $referral_discount = $referral_discount[0];
            if(isset($referral_discount['referred_by']) && $referral_discount['referred_by']!=''){
                $referred_by = json_decode($referral_discount['referred_by']);
                $referred_by_redeemed = $referred_by->is_redeemed;
                if(isset($referred_by_redeemed) && $referred_by_redeemed == false){
                    $udiscount = REFERRAL_DISCOUNT;
                } else {
                    $referred_to = json_decode($referral_discount['referred_to']);

                    $max_referrals_allowed = $referral_discountreferral_discount['max_referrals_allowed'];
                    $redeemed_referral_count = $referral_discount['redeemed_referral_count'];
                    if($redeemed_referral_count < $max_referrals_allowed){
                        if(count($referred_to) >= $redeemed_referral_count+1) {
                            $udiscount = REFERRAL_DISCOUNT;
                        }
                    }
                }
            }
            return $ismembership;
        }
    }
}*/
?>
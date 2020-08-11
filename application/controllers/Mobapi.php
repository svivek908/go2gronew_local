<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once 'vendor/autoload.php';
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;

class Mobapi extends CI_Controller {
	private $userid;
    function __construct(){
        parent::__construct();
        $api_key  = $this->input->get_request_header('Authorization');
        if(isset($api_key) && $api_key!=''){
        	if(!$this->isValidApiKey($api_key)){
        		responseJSON(array("error" =>true, "message" => "Access Denied. Invalid Api key"));
        		return false;
        	}
        }
        $this->load->helper('mail');
        $this->load->helper('sms');
        $this->load->library('payment');
        $this->load->model('Cart_item','Cart_model');
        $this->load->model('Mobileapi','Mobile');
         header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, authKey");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
    }

    private function isValidApiKey($api_key){
    	$this->userid = getuserid('users',$api_key);
    	return $this->userid;
    }

    //---------------------check_app_version
    public function check_app_version(){
        $response = array();
        $this->form_validation->set_rules('app_type', 'App type', 'required');
        if($this->form_validation->run()== true){
            $app_type = $this->input->post('app_type');
            if($app_type == 'android'){
                $info = array('id', 'a_v_name', 'a_v_code', 'title', 'message', 'is_compulsory', 'app_type', 'app_url', 'create_at', 'update_at');
            }elseif ($app_type == 'ios') {
                $info = array('id', 'i_app_version', 'i_build_version', 'title', 'message', 'is_compulsory', 'app_type', 'app_url', 'create_at', 'update_at');
            }
            $latest_app = $this->Model->get_selected_data($info,'app_version',array('app_type' => $app_type),'id','DESC','1');
            if (count($latest_app) > 0) {
                $response["error"] = false;
                $response["res"] = $latest_app;
            } else {
                $response["error"] = true;
                $response["message"] = "No Data found";
            }
        }else{
            $response["error"] = true;
            $response['message'] = strip_tags(validation_errors());
        }
        responseJSON($response);
    }
	//-----------pincode---------
	public function pincode($zipcode)
    {
    	$response = array();
        if(getpinExist($zipcode)){
            $response["error"] = false;
            $response['stores'] = $this->Model->get_stores_by_zipcode($zipcode);
            $response['current_time'] = time();
        }else{
            $response["error"] = true;
            $response["message"] = "Invalid zipcode";
        }
        responseJSON($response);
    }

    //------------Registration------------
    public function registration(){
        $social_media_register = $this->input->post('social_media_register');
        $this->form_validation->set_rules('first_name', 'Frist name', 'required');
        $this->form_validation->set_rules('last_name', 'Last name', 'required');
        $this->form_validation->set_rules('pincode', 'Pin code', 'required');
        $this->form_validation->set_rules('divicetype', 'Device Type', 'required');
        $this->form_validation->set_rules('deviceid', 'Device id', 'required');
        $this->form_validation->set_rules('emailid', 'Email', 'required|valid_email|is_unique[users.email_id]',
        array(
                'required'      => 'You have not provided %s.',
                'is_unique'     => 'This %s already exists.'
        ));
        $this->form_validation->set_rules('mobile', 'Mobile', 'required|is_unique[users.mobile]',
        array(
                'required'      => 'You have not provided %s.',
                'is_unique'     => 'This %s already exists.'
        ));
        $response = array();
        if($this->form_validation->run()== true){
            $first_name = html_escape($this->input->post('first_name'));
            $last_name = html_escape($this->input->post('last_name'));
            $email = $this->input->post('emailid');
            $mobile = $this->input->post('mobile');
		    $address = $this->input->post('address');
		    $pincode = $this->input->post('pincode');
		    $password = $this->input->post('password');
		    $devicetype = $this->input->post('divicetype');
		    $deviceid = $this->input->post('deviceid');
		    $referral_code = $this->input->post('referral_code');
		    $referral_code = empty($referral_code) ? null : $referral_code;
		    $social_media_register = $app->request->post('social_media_register');
		    $regtype = NULL;
  			if($social_media_register=="true"){
	            $password = "socialmedialogin";
	            $regtype = "gmail";
	        }
            $uuid = $this->db->set('id', 'UUID()', FALSE);
            $data = array(
                "first_name" => $first_name,
                "last_name" => $last_name, 
                "email_id" => $email,
                "password" => getHashedPassword($password),
                "mobile" => $mobile,
                "address" => json_encode($this->input->post('address')),
                "pincode" => $pincode,
                'logintype'=>$regtype);
            if($this->Model->get_record('avl_pincode',array('pincode' =>$pincode , 'status' => '0'))){
                $rec = $this->Model->get_city_state_country($pincode);
                if(count($rec) > 0){
                    $data['country_id'] = $rec[0]['countryid'];
                    $data['state_id'] = $rec[0]['stateid'];
                    $data['city_id']= $rec[0]['cityid'];
                }
                $referral_code_valid = true; $referred_by_user_id = null;
                if($referral_code != null){
                    $referred_by_user_id = $this->Model->get_selected_data('id','users',$where=array('referral_code' => $referral_code),$order=FALSE,$type=FALSE,$limit=FALSE,$start=FALSE);
                    if(!$referred_by_user_id){
                        $referral_code_valid = false;
                    }
                }
                if($referral_code_valid){
                    $time = time();
                    $data['referral_code'] = $this->getUniqueReferralCode($first_name);
                    $data['unitime'] = $time;
                    $data['api_key'] = generateApiKey();
                    $data['max_referrals_allowed'] = MAX_REFERRALS_ALLOWED;
                    //-------------Insert user record --------------
                    $create_user =  $this->Model->create_user('users',$data);
                    if($create_user){
                        $create_user_id = $this->Model->get_selected_data('id','users',array('email_id' => $email));
                        $create_user_id = $create_user_id[0]['id'];
                        if (!($referral_code == null && $referred_by_user_id == null)) {
                            $this->addReferralFieldsToUsers($referred_by_user_id[0]['id'], $create_user_id);
                        }
                        //------------Send mail to user-------------
                        $username =  $first_name .' '. $last_name;
                        $udata['username'] =  $username;
                        $subject = 'Welcome,' . $username . ' to the Go2Gro family';
                        $mail_msg = $this->load->view('go2gro_web/template/registration',$udata,true);
                        $issendmail = $this->general->send_mail($email,$subject,$mail_msg);
                        if ($issendmail) {
                            $response["ismailsend"] = "Mail send sucessfully";
                        } else {
                            $response["ismailsend"] = "Mail Not send sucessfully";
                        }
                        //----------------
                        $msg = "Welcome," . $username . " to the Go2Gro family! \n
                        Go2Gro is happy to see you sign up. \n
                        Please click on the given link to verify your mobile no and get your first order at $0 delivery Charges.\n
                        www.Go2Gro.com/" . verifymobile . "/mobile.php?v=" .$create_user_id;
                        $message = urlencode($msg);
                        $issendsms = $this->general->sendsms($mobile, $message);
                        if ($issendsms) {
                            $response["issmssend"] = "SMS send sucessfully";
                        } else {
                            $response["issmssend"] = "SMS Not send sucessfully";
                        }
                        $welmsg = "Welcome, " . $username . "  to the Go2Gro family! \n
                        Go2Gro is happy to see you sign up – we endeavor to never disappoint! We are here to save your run to the grocery store, ensuring
                        fresh groceries in your kitchen. \n Please verify your number by clicking on the verification link sent to your phone number to
                        get a free delivery on your first order. \n A link will be sent to your phone number after you create an account";

                        $welmessage = urlencode($welmsg);
                        $issendwelcomesms = $this->general->sendsms($mobile, $welmessage);
                        if ($issendwelcomesms) {
                            $response["issendwelcomesms"] = "SMS send sucessfully";
                        } else {
                            $response["issendwelcomesms"] = "SMS Not send sucessfully";
                        }
                        $response["error"] = false;
                        $response["message"] = "You are successfully registered";
                        $response["user"] = $create_user_id;
                    }
                    else {
                        $response["error"] = true;
                        $response['message']= USER_CREATE_FAILED;
                    }
                }else{
                    $response["error"] = true;
                    $response['message']= INVALID_REF_CODE;
                }
            }else{
                $response["error"] = true;
                $response["message"] = "Pincode Not valid";
            }
        }else{
            $response["error"] = true;
            $response['message'] = strip_tags(validation_errors());
        }
        responseJSON($response);
    }

    //-----------login----------
    public  function login(){
    	$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('password', 'password', 'required');
        $this->form_validation->set_rules('divicetype', 'Device type', 'required');
        $this->form_validation->set_rules('deviceid', 'Device id', 'required');
        $response = array();
        if($this->form_validation->run()== true){
	        $this->load->Model('Login_Model');

	        $email=$this->input->post('email');
	        $password=$this->input->post('password');
	        $divicetype=$this->input->post('divicetype');
	        $deviceid=$this->input->post('deviceid');

	        $res = $this->Login_Model->auth_user($email,$password);
	        if ($res != NULL) {
	        	$userid = $res[0]->id;
	        	//---------update deviceid----
	        	if ($devicetype == "android") {
		           	$checkgcmid = $this->Model->get_record('tbl_usergcm',array('divicetype' => $divicetype,'user_id' => $userid,'gcm_id' => $deviceid));
		           	if(empty($checkgcmid)){
		           		$this->Model->add('tbl_usergcm',array('divicetype' => $divicetype,'user_id' => $userid,'gcm_id' => $deviceid));
		           	}
		        } else if ($devicetype == "ios") {
		            $checkgcmid = $this->Model->get_record('tbl_usergcm',array('divicetype' => $divicetype,'user_id' => $userid,'gcm_id' => $deviceid));
		           	if(empty($checkgcmid)){
		           		$this->Model->add('tbl_usergcm',array('divicetype' => $divicetype,'user_id' => $userid,'gcm_id' => $deviceid));
		           	}
		        }
		        //--------update forget passsword code---
		        $isinforgertuserid = $this->Model->get_selected_data('user_id','forgetpasswordcode',array('user_id' => $userid));
		        if(count($isinforgertuserid) > 0){
		        	$this->Model->update('forgetpasswordcode',array('status' => 1),array('user_id' => $userid));
		        }
	            $response["error"] = false;
            	$response['user'] = $res;
            	$response['message'] = "Welcome To Go2Grow";
	        } else {
	            $response['error'] = true;
	            $response['message'] = "An error occurred. Please try again";
	        }
        } else{
            $response["error"] = true;
            $response['message'] = strip_tags(validation_errors());
        }
        responseJSON($response);
    }

    //------------category--------------
    public function category($storeid){
    	$response = array();
        if($storeid != ''){
        	$check_store =$this->Model->get_record('stores',array('id' => $storeid,'status' => 'active'));
        	if(count($check_store) > 0) {
        		$category = $this->Model->get_all_record('category',$order='position',$type="ASC",$limit='',$start="",$where=array('store_id' => $storeid,'status'=>0));
        		if(count($category) > 0){
		            $response["error"] = false;
		            $response["category"] = $category;
		            $response['store'] = $check_store[0];
		            $response['current_time'] = time();
		            $response["message"] = "Category list avaliable";
		        }else {
		            $response["error"] = true;
		            $response["message"] = "No Categories Available";
		        }
        	}else {
		        $response["error"] = true;
		        $response["message"] = "Invalid Store ID!";
		    }
        }else{
            $response["error"] = true;
            $response['message'] = 'Store id required';
        }
        responseJSON($response);
    }

    //---------get BestSeller------------
    public function bestseller()
    {
    	$response = array();
    	$this->form_validation->set_rules('store_id', 'Store id', 'required');
        if($this->form_validation->run()== true){
	        $storeid = $this->input->get('store_id');
	        $userid = $this->input->get('user_id');
	        $check_store =$this->Model->get_record('stores',array('id' => $storeid,'status' => 'active'));
	        if(count($check_store) > 0) {
	            // fetching all user tasks
	            if(isset($userid) && $userid==0){
	                $where = array('p.item_status' => '0','p.discount!=' =>0);
	            }
	            else{
	                $item_ids = ""; $where="";
	                $rec = $this->Model->get_distinct_data('item_id','cart_item',array('user_id' => $userid,'status' => 0, 'store_id' =>$storeid));
	                foreach ($rec as $value) {
	                    $item_ids.="'".$value."',";
	                }
	                if(isset($item_ids) && !empty($item_ids)){
	                    $where = "p.item_id not in(".rtrim($item_ids,',').") AND ";
	                }
	                $where .= "p.item_status = '0' and p.`discount`!='0'";
	            }
	            $result = $this->Model->getBestseller($userid,$storeid,$where,'p.`item_id`','rand()',15,0);
	            if (count($result) > 0) {
	                $response["bestseller"] = array();
	                foreach ($result as $bestseller) {
	                    array_push($response["bestseller"], $bestseller);
	                }
	                $response["error"] = false;
	                $response["message"] = "Bestseller list avaliable";
	            }else {
	                $response["error"] = true;
	                $response["message"] = "No Record Found";
	            }
	        }else{
	            $response["error"] = true;
	            $response["message"] = "Invalid Store ID!";
	        }
	    }
        else{
            $response["error"] = true;
            $response['message'] = strip_tags(validation_errors());
        }
        responseJSON($response);
    }

    //----------password change---------
    public function passwordchange(){
    	$response = array();
    	$this->form_validation->set_rules('opassword', 'Old password', 'required');
    	$this->form_validation->set_rules('npassword', 'New password', 'required');
        if($this->form_validation->run()== true){
        	$api_key  = $this->input->get_request_header('Authorization');
        	if($api_key != ''){
        		$oldpassword = $this->input->post('opassword');
    			$newpassword = $this->input->post('npassword');
        		$isUserExistsbyuserid = $this->Model->get_selected_data('id','users',array('id' => $this->userid));
        		if($isUserExistsbyuserid){
        			$newpassword_hash =  getHashedPassword($newpassword);
        			$useroldpass = $this->Model->get_selected_data('password','users',array('id' => $this->userid));
        			if(verifyHashedPassword($oldpassword, $useroldpass[0]->password)){
			            $last_id = $this->Model->update('users',array('password' => $newpassword_hash),array('id' => $this->userid));
			            if($last_id){
			            	$response["error"] = false;
            				$response["message"] = "Your password change successfully";
			            }else{
			            	$response["error"] = true;
            				$response["message"] = "Oops! An error occurred while password chenaged";
			            }
			        }else{
			        	$response["error"] = true;
            			$response["message"] = "Sorry, your old password does not match";
			        }
        		}else{
        			$response["error"] = true;
            		$response["message"] = "Sorry, you are not valid User";
        		}
        	}else{
        		$response["error"] = true;
            	$response['message'] = 'Api key is misssing';
        	}
        }
        else{
            $response["error"] = true;
            $response['message'] = strip_tags(validation_errors());
        }
        responseJSON($response);
    }

    //-----------forgetpassreq------
    public function forgetpassreq(){
        $response = array();
        $this->form_validation->set_rules('emailid', 'emailid', 'required|valid_email');
        if($this->form_validation->run()== true){
            $emailid = $this->input->post('emailid');
            $user = $this->Model->get_record('users',array('email_id' => $emailid));
            if(count($user) > 0){
                $genratedcode = generateRandomString();
                $userid = $user[0]['id'];
                $username = $user[0]['first_name'] . " " . $user[0]['last_name'];
                $urlandfix = $userid . "-" . $genratedcode;

                $link = passwordurl . "forgetpassword?v=" . $urlandfix;
                $data = array('username' => $username,'link' => $link);
                $html = $this->load->view('go2gro_web/template/forget_password',$data,true);

                $checkcodeinforget = $this->Model->get_record('forgetpasswordcode',array('user_id' => $userid,'status' => 0));
                if(count($checkcodeinforget) > 0){
                    $this->Model->update('forgetpasswordcode', array('code' => $genratedcode), array('user_id' => $userid));
                    $issendmail = $this->general->send_mail($emailid,'Password Reset Link',$html);
                    if ($issendmail) {
                        $response["ismailsend"] = "Mail send sucessfully";
                    } else {
                        $response["ismailsend"] = "Mail Not send sucessfully";
                    }
                    $response["error"] = false;
                    $response["message"] = $username . " Don’t Worry, we got you! Let’s get you a new password";
                }else{
                    /*$response["error"] = true;
                    $response["message"] = "Oops! An error occurred while password chenaged";*/
                    $last_insert = $this->Model->add('forgetpasswordcode',array('user_id' => $userid,'code' => $genratedcode));
                    if($last_insert){
                        $issendmail = $this->general->send_mail($emailid,'Password Reset Link',$html);
                        if ($issendmail) {
                            $response["ismailsend"] = "Mail send sucessfully";
                        } else {
                            $response["ismailsend"] = "Mail Not send sucessfully";
                        }
                        $response["error"] = false;
                        $response["message"] = $username . " Don’t Worry, we got you! Let’s get you a new password";
                    }
                }
            }
        }else{
            $response["error"] = true;
            $response['message'] = strip_tags(validation_errors());
        }
        responseJSON($response);
    }

    //----------updatenewpassword--------
    public function updatenewpassword(){
        $response = array();
        $this->form_validation->set_rules('password', 'Password', 'required');
        $this->form_validation->set_rules('userid', 'User id', 'required');
        if($this->form_validation->run()== true){
            $userid = $this->input->post('userid');
            $password = $this->input->post('password');
            $checkcodeinforget = $this->Model->get_record('forgetpasswordcode',array('user_id' => $userid,'status' => 0));
            if(count($checkcodeinforget) > 0){
                $newpassword_hash =  getHashedPassword($password);
                $last_id = $this->Model->update('users',array('password' => $newpassword_hash),array('id' => $this->userid));
                if($last_id){
                    $this->Model->update('forgetpasswordcode', array('status' => 1), array('user_id' => $userid));
                    $response["error"] = false;
                    $response["message"] = "password change sucessfully";
                }else{
                    $response["error"] = true;
                    $response["message"] = "Oops! An error occurred while password chenaged";
                }
            }else{
                $response["error"] = true;
                $response["message"] = "No Request pending for change password";
            }
        }else{
            $response["error"] = true;
            $response['message'] = strip_tags(validation_errors());
        }
        responseJSON($response);
    }

    //-------------relateditem--------------
    public function relateditem($id,$storeid)
    {
        $response = array();
        $userid=$this->input->get('user_id');
        $check_store =$this->Model->get_record('stores',array('id' => $storeid,'status' => 'active'));
        if(count($check_store) > 0) {
            $relateditem = $this->Mobile->mob_getRelateditem($id,$store_id);
            if(count($result) > 0){
                $response["reateditem"] = array();
                foreach ($relateditem as $key => $value) {
                   array_push($response["reateditem"], $value);
                }
                $response["error"] = false;
                $response["message"] = "reated item list avaliable";
            }else{
                $result = $this->Mobile->mob_getBestseller($user_id,$store_id);
                if (count($result) > 0) {
                    $response["reateditem"] = array();
                    foreach ($relateditem as $key => $value) {
                       array_push($response["reateditem"], $value);
                    }
                    $response["error"] = false;
                    $response["message"] = "reated item list avaliable";
                }else {
                    $response["error"] = true;
                    $response["message"] = "No Record Found";
                }
            }
        }else {
            $response["error"] = true;
            $response["message"] = "Invalid Store ID!";
        }
        responseJSON($response);
    }

    //--------------getItemDescription------------
    public function getItemDescription($itemid,$storeid)
    {
        $response = array();
        $getstore = $this->Model->get_record('stores',array('id' => $storeid,'status' => 'active'));
        if (count($getstore) > 0) {
            $re = $this->Model->isitemExists($itemId,$storeid);
            if ($re) {
                $result = $this->Model->getItem($itemId,$storeid);
                if ($result != NULL) {
                    if (count($result) > 0) {
                        foreach ($result as $row ) {
                           $itm = array_map('utf8_encode', $row);
                        }
                        $reslink = $this->Model->admin_getitemimages($itemId,$storeid);
                        if (count($reslink) > 0) {
                            $imagesaaray = $reslink;
                        }
                        $response["error"] = false;
                        $response["item"] = $itm;
                        $response["item"]["images"] = $imagesaaray;
                        $response["message"] = "list get sucessfuly";
                    }else {
                        $response["error"] = true;
                        $response["message"] = "The requested resource doesn't exists";
                    }
                }else {
                    $response["error"] = true;
                    $response["message"] = "The requested resource doesn't exists";
                }
            } else {
                $response["error"] = true;
                $response["message"] = "The Item doesn't exists";
            }
            responseJSON($response);
        }else{
            $response["error"] = true;
            $response["message"] = "Invalid Store ID!";
            responseJSON($response);
        }
    }

    //-------------profile update-------
    public function update_profile(){
        $response = array();
        $this->form_validation->set_rules('first_name', 'First name', 'required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required');
        $this->form_validation->set_rules('country', 'Country', 'required');
        $this->form_validation->set_rules('state', 'State', 'required');
        $this->form_validation->set_rules('city', 'City', 'required');
        $this->form_validation->set_rules('pincode', 'Pincode', 'required');
        $this->form_validation->set_rules('mobile', 'mobile', 'required');
        if($this->form_validation->run()== true){
            $api_key  = $this->input->get_request_header('Authorization');
            if($api_key != ''){
                $userid = getuserid('users',$api_key);
                $data = array('first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'address' => $this->input->post('address'),
                'mobile' => $this->input->post('mobile'),
                'country_id' => $this->input->post('country'),
                'state_id' => $this->input->post('state'),
                'city_id' => $this->input->post('city'),
                'pincode' => $this->input->post('pincode'));

                if(getpinExist($pincode)){
                    $isUserMobileExists = $this->Model->get_record('users',array('mobile' => $mobile, 'id' => $userid));
                    if(count($isUserMobileExists) > 0){
                        $profileupdate = $this->Model->update('users',$data,array('id' => $userid));
                        if($profileupdate){
                            $resp = $this->Model->get_record('users',array('id' => $userid));;
                            $response["error"] = false;
                            $response["message"] = "Profile Update Successfully";
                            $response["user"] = $resp;
                        }else{
                            $response["error"] = true;
                            $response["message"] = "Oops! An error occurred while profile update";
                        }
                    }else{
                        $isMobileExists = $this->Model->get_record('users',array('mobile' => $mobile));
                        if(count($isMobileExists) > 0){
                            $response["error"] = true;
                            $response["message"] = "Sorry, this phone number is already registered with another email address. Please contact customer support at customersupport@go2gro.com for further assistance.";
                        }
                    }
                }else{
                    $response["error"] = true;
                    $response["message"] = "Invalid zipcode";
                }
            }else{
                $response["error"] = true;
                $response['message'] = 'Api key is misssing';
            }
        }
        else{
            $response["error"] = true;
            $response['message'] = strip_tags(validation_errors());
        }
        responseJSON($response);
    }

    //----------itembydept------------
    public function itembydept($deptid, $storeid){
        $response = array();
        $getstore = $this->Model->get_record('stores',array('id' => $storeid,'status' => 'active'));
        if (count($getstore) > 0) {
            $result = $this->Model->get_selected_data(array('sub_id','sub_name','sub_image'),'subcategory',array('cat_id' => $deptid,'sab_status' =>'0'),'feature_product_status','desc');
            if(count($result) > 0 ){
                $item = array();
                foreach ($result as $key => $value) {
                    $subid = $value['sub_id'];
                    $res = $this->Mobile->getitembysubcat($subid,$store_id);
                    if (count($res) > 0 ) {
                        $items = array();
                        foreach ($res as  $subcatitem) {
                            $subcatitem['sub_id'] = $subid;
                            $items[] = $subcatitem;
                        }
                        $value['items'] = $items;
                    }
                    $item[] = $subcat;
                }
                $response["error"] = false;
                $response["subcategory"] = $item;
                $response["message"] = "list get sucessfuly";
            }else{
                $response["error"] = true;
                $response["message"] = "The requested resource doesn't exists";
            }
        }
        else{
            $response["error"] = true;
            $response["message"] = "Invalid Store ID!";
        }
        responseJSON($response);
    }

    //------------------newitemsbydepts_all-------
    public function newitemsbydepts_all($deptid, $subid, $pageno, $unitime, $storeid){
        $response = array();
        $getstore = $this->Model->get_record('stores',array('id' => $storeid,'status' => 'active'));
        $ress = false;
        if(count($getstore) > 0){
            $pageno = $pageno*15;
            if ($subid == "0") {
                $ress = true;
            } 
            else {
                $checkdptdublink = $this->Model->get_record('subcategory',array('cat_id' => $deptid,'sub_id' => $subid,'sab_status' =>0));
                if(count($checkdptdublink) > 0){
                    $ress = true;
                }
            }

            if($ress){
                $result = $this->Model->get_selected_data(array('sub_id','sub_name','sub_image'),'subcategory',array('cat_id' => $deptid,'sab_status' =>'0'),'feature_product_status','desc');
                if(count($result) > 0 ){
                    $item = array();
                    if ($subid == 0) {
                        foreach($result as $value) {
                            $item[] = $value;
                        }

                        $getitembysubcat = $this->Model->newgetitembysubcat(array("p.id","p.item_id","CONCAT(p.`item_name`, ' ', p.`item_size`) as item_name","p.`item_sdesc`","p.`item_fdesc`","p.`item_price`","p.`item_status`","p.`Sales_tax`","inl.subcat_id","sub.sub_name","ct.id as cat_id", "IFNULL( ROUND( AVG( pr.rating ) ) , 0 ) AS rating_average","itmg.imageurl as item_image"),$storeid,$subid,array('ct.id' => $deptid,'p.item_status' =>0,'p.unitime >' => $unitime, 'inl.status' =>0),'p.`item_id`','sub.sub_id','ASC','15', $pageno);
                        $list = array();
                        if(count($getitembysubcat) > 0){
                            foreach ($getitembysubcat as $row) {
                                $row = array_map('utf8_encode', $row);
                                $isAlreadyExists = false;
                                foreach ($list as $x => $x_value) {
                                    if ($row['subcat_id'] == $x_value['subcat_id']) {
                                        $isAlreadyExists = true;
                                        $itemlist['id'] = $row['id'];
                                        $itemlist['item_id'] = $row['item_id'];
                                        $itemlist['item_name'] = $row['item_name'];
                                        $itemlist['item_sdesc'] = $row['item_sdesc'];
                                        $itemlist['item_fdesc'] = $row['item_fdesc'];
                                        $itemlist['item_price'] = $row['item_price'];
                                        $itemlist['item_status'] = $row['item_status'];
                                        $itemlist['Sales_tax'] = $row['Sales_tax'];
                                        //  $itemlist['cart_qrt'] = $row['cart_qrt'];
                                        $itemlist['rating_average'] = $row['rating_average'];
                                        $itemlist['item_image'] = $row['item_image'];
                                        array_push($list[$x]['items'], $itemlist);
                                        break;
                                    }
                                }
                                if ($isAlreadyExists == false) {
                                    $itemlistarray = array();
                                    $itemlist['id'] = $row['id'];
                                    $itemlist['item_id'] = $row['item_id'];
                                    $itemlist['item_name'] = $row['item_name'];
                                    $itemlist['item_sdesc'] = $row['item_sdesc'];
                                    $itemlist['item_fdesc'] = $row['item_fdesc'];
                                    $itemlist['item_price'] = $row['item_price'];
                                    $itemlist['item_status'] = $row['item_status'];
                                    $itemlist['Sales_tax'] = $row['Sales_tax'];
                                    //$itemlist['cart_qrt'] = $row['cart_qrt'];
                                    $itemlist['rating_average'] = $row['rating_average'];
                                    $itemlist['item_image'] = $row['item_image'];
                                    array_push($itemlistarray, $itemlist);

                                    $tendes['items'] = $itemlistarray;
                                    $tendes['subcat_id'] = $row['subcat_id'];
                                    $tendes['sub_name'] = $row['sub_name'];

                                    $xyz = array_push($list, $tendes);
                                }
                            }
                            if ($unitime == "0") {
                                $response['updationstatus'] = "1";
                                $response['message'] = "items get successfully";
                            } else {
                                $response['updationstatus'] = "3";
                                $response['message'] = "updation requried";
                            }
                            $response ['items'] = $list;
                            $response["subcategory"] = $item;
                            $response["error"] = false;
                            $response["unitime"] = $time;
                            $response["subid"] = $subid;
                        }else{
                            if ($unitime == "0") {
                                $response['updationstatus'] = "0";
                                $response['message'] = "No data avaliable";
                            } else {
                                $response['updationstatus'] = "2";
                                $response['message'] = "updation not requried";
                            }
                            $response["subcategory"] = $item;
                            $response["error"] = false;
                            $response["unitime"] = $time;
                            $response["subid"] = $subid;
                        }
                    }
                    else{
                        foreach ($result as  $value) {
                            $item[] = $value;
                        }

                        $getitembysubcat = $this->Model->newgetitembysubcat(array("p.id","p.item_id","CONCAT(p.`item_name`, ' ', p.`item_size`) as item_name","p.`item_sdesc`","p.`item_fdesc`","p.`item_price`","p.`item_status`","p.`Sales_tax`","inl.subcat_id","sub.sub_name","IFNULL( ROUND( AVG( pr.rating ) ) , 0 ) AS rating_average","itmg.imageurl as item_image"),$storeid,$subid,array('inl.subcat_id' => $subid,'p.item_status' =>0,'p.unitime >' => $unitime, 'inl.status' =>0),'p.`item_id`','sub.sub_id','ASC','15', $pageno);
                        $items = array();
                        if (count($getitembysubcat) > 0) {

                            foreach ($getitembysubcat as $values) {
                                $items[] = array_map('utf8_encode', $values);
                            }

                            if ($unitime == "0") {
                                $response['updationstatus'] = "1";
                                $response['message'] = "items get successfully";
                            } else {
                                $response['updationstatus'] = "3";
                                $response['message'] = "updation requried";
                            } 
                        }
                        else {
                            if ($unitime == "0") {
                                $response['updationstatus'] = "0";
                                $response['message'] = "No data avaliable";
                            } else {
                                $response['updationstatus'] = "2";
                                $response['message'] = "updation not requried";
                            }
                        }
                        $response["item"] = $items;
                        $response["subcategory"] = $item;
                        $response["error"] = false;
                        $response["unitime"] = $time;
                        $response["subid"] = $subid;
                    }
                }
                else {
                    $response["error"] = true;
                    $response["message"] = "The requested resource doesn't exists";
                }
            }else{
                $response["error"] = true;
                $response["message"] = "The requested resource doesn't exists";
            }
        }
        else{
            $response["error"] = true;
            $response["message"] = "Invalid Store";
        }
        responseJSON($response);
    }

    //----------------orders-------------------
    public function orders($storeid){
        $response = array();
        $api_key  = $this->input->get_request_header('Authorization');
        if($api_key != ''){
            $userid = getuserid('users',$api_key);
            $order = $this->Mobile->getmyneworder($userid,$storeid);
            if(count($order) > 0){
                $response["error"] = false;
                $response["order"] = array();
                $response["currentunitime"] = time();
                $response["message"] = "order list get sucessfuly";
                foreach ($order as $key => $value) {
                    if ($value['is_order_edit'] == "2") {
                        $abc["trans_history"] = array();
                        $order_transhistory = $this->Model->get_record('order_trans_history',array('orderid' => $value['order_id']));
                        if (count($order_transhistory) > 0) {
                            foreach ($order_transhistory as $ot){
                                array_push($abc["trans_history"], $ot);
                            }
                            $value['transhistory'] = $abc["trans_history"];
                        }
                    }
                    array_push($response["order"], $value);
                }
            }else {
                $response["error"] = true;
                $response["message"] = "No Order Found";
            }
        }else{
            $response["error"] = true;
            $response['message'] = 'Api key is misssing';
        }
        responseJSON($response);
    }

    //---------------orderdetail---------------
    public function orderdetail($orderid,$storeid){
        $response = array();
        $api_key  = $this->input->get_request_header('Authorization');
        if($api_key != ''){
            $userid = getuserid('users',$api_key);
            $check_ord = $this->Model->getorderbyid($orderid, $userid,$storeid);
            if (count($check_ord) > 0) {
                $order = $check_ord[0];
                $response["orderdetail"] = array();
                $response["orderstatus"] = array();
                $resp = $this->Model->getcartitembyorderid($orderid,$order['store_id']);
                $ordstat = $this->Model->get_selected_data(array(`order_id`, `updateby_id`, `status`, `message`, `updatetime`),'order_status_info',array('order_id'=>$orderid),'updatetime','DESC');
                if ($order['is_order_edit'] == "2") {
                    $response["trans_history"] = array();
                    $reds = $this->Model->get_record('order_trans_history',array('orderid'=>$orderid));
                    if (count($reds) > 0) {
                        foreach ($reds as $subcatl ) {
                           array_push($response["trans_history"], $subcatl);
                        }
                    }
                }

                $order['discount_label'] = 'Discount';
                if($order['coupanid'] == -1){
                    $order['discount_label'] = 'Referral Discount';
                } else if($order['coupanid'] >= 1){
                    $order['discount_label'] = 'Promocode Discount';
                }

                if (count($resp) > 0) {
                    foreach ($resp as $item ) {
                        $item = array_map('utf8_encode', $item);
                        array_push($response["orderdetail"], $item);
                    }
                }
                if (count($ordstat) > 0) {
                    foreach ($ordstat as $ords ) {
                        array_push($response["orderstatus"], $ords);
                    }
                }


                $response["error"] = false;
                $order['shipping_address'] = get_compatible_address($order['shipping_address']);
                $response["order"] = $order;
                $response["message"] = "order list get sucessfuly";
                foreach ($check_ord as $subcat ) {
                    array_push($response["order"], $subcat);
                }
            } 
            else {
                $response["error"] = true;
                $response["message"] = "No Order Found";
            }
        }
        else{
            $response["error"] = true;
            $response['message'] = 'Api key is misssing';
        }
        responseJSON($response);
    }

    //---------------getsavecard---------------
    public function getsavecard(){
        $response = array();
        $api_key  = $this->input->get_request_header('Authorization');
        if($api_key != ''){
            $userid = getuserid('users',$api_key);
            $result = $this->Model->get_record('user_save_card',array('user_id' => $userid,'status' => 0));
            $result1 = $this->Model->get_record('user_save_card',array('user_id' => $userid,'status' => 1));
            if(count($result) > 0){
                $response['card'] = array();
                foreach ($result as $paycard) {
                    array_push($response["card"], $paycard);
                }
                $response["error"] = false;
                $response["type"] = "0";
                $response["message"] = "crad get sucessfuly";
            }
            elseif(count($result1) > 0){
                $response["error"] = false;
                $response["type"] = "1";
                $response["message"] = "previous customer profile id";
                $response["customerProfileId"] = $result1[0]['customerProfileId'];
            }else{
                $response["error"] = true;
                $response["message"] = "No saved card or info found!";
            }
        }else{
            $response["error"] = true;
            $response['message'] = 'Api key is misssing';
        }
        responseJSON($response);
    }

    //-------------usersavecard--------------
    public function usersavecard(){
        $response = array();
        $api_key  = $this->input->get_request_header('Authorization');
        if($api_key != ''){
            $this->form_validation->set_rules('customerPaymentProfileId','customerPaymentProfileId','required');
            $this->form_validation->set_rules('customerProfileId','customerProfileId','required');
            $this->form_validation->set_rules('biladdress_card','biladdress_card','required');
            $this->form_validation->set_rules('bilzipcode_card','bilzipcode_card','required');
            $this->form_validation->set_rules('cardnumber','cardnumber','required');
            $this->form_validation->set_rules('cardtype','cardType','required');
            if($this->form_validation->run()== true){
                $userid = getuserid('users',$api_key);
                $customerPaymentProfileId=$this->input->post('customerPaymentProfileId');
                $customerProfileId=$this->input->post('customerProfileId');
                $biladdress_card=$this->input->post('biladdress_card');
                $bilzipcode_card=$this->input->post('bilzipcode_card');
                $cardnumber =$this->input->post('cardnumber');
                $cardType = $this->input->post('cardtype');
                if ($customerPaymentProfileId != "NA" && $customerProfileId != "NA") {
                    $chk = $this->Model->get_record('user_save_card',array('customerPaymentProfileId' => $customerPaymentProfileId, 'customerProfileId' =>$customerProfileId, 'user_id' => $userid));
                    if (count($chk) > 0) {
                        $response["error"] = true;
                        $response["message"] = "card already added";
                    } 
                    else {
                        $uuid = $this->db->set('card_id', 'UUID()', FALSE);
                        $data = array('user_id' => $userid,'customerProfileId' => $customerProfileId,
                            'customerPaymentProfileId' => $customerPaymentProfileId,
                            'bill_address_card' => $biladdress_card,'bil_zipcode_card' =>$bilzipcode_card ,
                            'card_number' => $cardnumber,'cardtype' => $cardType);
                        $lastid = $this->Model->add('user_save_card',$data);
                        if ($lastid) {
                            $result = $this->Model->get_selected_data('card_id','user_save_card',array('id' => $lastid));
                            $response["error"] = false;
                            $response["card_id"] = $result[0]['card_id'];
                            $response["message"] = "card save successfully";
                        } else {
                            $response["error"] = true;
                            $response["message"] = "something went wrong please try again";
                        }
                    }
                } else {
                    $response["error"] = true;
                    $response["message"] = "Information Not Valid";
                }
            }
            else{
                $response['error'] = true;
                $response['message'] = strip_tags(validation_errors());
            }    
        }else{
            $response["error"] = true;
            $response['message'] = 'Api key is misssing';
        }
        responseJSON($response);
    }

    //-------------placeorder_mobile--------
    public function placeorder_mobile(){
        $response = array();
        $api_key  = $this->input->get_request_header('Authorization');
        if($api_key != ''){
            $this->form_validation->set_rules('ord_processingfee','processing fee','required');
            $this->form_validation->set_rules('ord_txnid','ord_txnid','required');
            $this->form_validation->set_rules('ord_totalprice','Order totla price','required');
            $this->form_validation->set_rules('ord_tax','order tax','required');
            $this->form_validation->set_rules('ord_coupanid','order coupanid','required');
            $this->form_validation->set_rules('ord_finlprice','order finlprice','required');
            $this->form_validation->set_rules('ord_processingfee','order processingfee','required');
            $this->form_validation->set_rules('ord_deliverycharge','order deliverycharge','required');
            $this->form_validation->set_rules('bil_firstname','Firstname','required');
            $this->form_validation->set_rules('bil_lastname','Lastname ','required');
            //$this->form_validation->set_rules('bil_address','Address','required');
            $this->form_validation->set_rules('bil_email','Email','required');
            $this->form_validation->set_rules('bil_contact','Contact','required');
            $this->form_validation->set_rules('bil_countryid','Country','required');
            $this->form_validation->set_rules('bil_stateid','State','required');
            $this->form_validation->set_rules('bil_cityid','City','required');
            $this->form_validation->set_rules('bil_pincode','Zipcode','required');
            $this->form_validation->set_rules('pay_email','pay_email','required');
            $this->form_validation->set_rules('payerID','payerID','required');
            $this->form_validation->set_rules('tip_amount','tip_amount','required');
            $this->form_validation->set_rules('slotid','slotid','required');
            $this->form_validation->set_rules('develydate','develydate','required');
            $this->form_validation->set_rules('AuthoAmount','AuthoAmount','required');
            $this->form_validation->set_rules('card_id','card_id','required');
            $this->form_validation->set_rules('store_id','Store id','required');
            
            if($this->form_validation->run() == true){
                $storeid = $this->input->post('store_id');
                $userid = getuserid('users',$api_key);
                $bil_firstname=$this->input->post('bil_firstname');
                $bil_contact=$this->input->post('bil_contact');
                $bil_email=$this->input->post('bil_email');
                $bil_lastname=$this->input->post('bil_lastname');
                $bil_address= json_encode($this->input->post('bil_address')); // json_encode as the address is updated now to be an array
                $bil_countryid=$this->input->post('bil_countryid');
                $bil_stateid=$this->input->post('bil_stateid');
                $bil_cityid=$this->input->post('bil_cityid');
                $bil_pincode=$this->input->post('bil_pincode');
                $ord_txnid=$this->input->post('ord_txnid');
                $ord_totalprice=$this->input->post('ord_totalprice');
                $ord_finlprice=$this->input->post('ord_finlprice');
                $ord_tax=$this->input->post('ord_tax');
                $processing_fee=$this->input->post('ord_processingfee');
                $ord_deliverycharge=$this->input->post('ord_deliverycharge');
                $payerID=$this->input->post('payerID');
                $pay_email=$this->input->post('pay_email');
                $pay_phone=$this->input->post('pay_phone');
                $tip_amount=$this->input->post('tip_amount');
                $card_id = $this->input->post('card_id');
                $slotid =$this->input->post('slotid');
                $develydate =$this->input->post('develydate');
                $authAmount =$this->input->post('AuthoAmount');
                $ord_coupanid=$this->input->post('ord_coupanid');
                $discount_type=$this->input->post('discount_type');
                $discount_id=$this->input->post('discount_id');
                $discount_amount=$this->input->post('discount_amount');
                $check_store =$this->Model->get_record('stores',array('id' => $storeid,'status' => 'active'));
                if(count($check_store) > 0){
                    if((int)$tip_amount >= 0 ) {
                        $timedate = time();
                        $resp = $this->Cart_model->getCartItem($userid,$storeid);
                        if(count($resp) > 0){
                            $table_name_prefix = STORE_PREFIX.$storeid;
                            $tbl_time_slot = $table_name_prefix.'_'.TIME_SLOT;
                            $getStarttime = $this->Model->get_record($tbl_time_slot,array('time_slot_id' => $slotid));
                            if(count($getStarttime) > 0){
                                $getStarttime = $getStarttime[0];
                                if ($getStarttime['slot_name'] == "Within 2 hours") {
                                    $deliveryUnitime = $timedate + 2 * 60 * 60;
                                } else {
                                    $deliveryUnitime = strtotime($develydate . " " . $getStarttime['end_time']);
                                }
                                //-------cartsubtotal------
                                $resp1 = $this->Model->get_selected_data('round (sum(`price`*`item_quty`),2) as subtotal','cart_item',array('user_id' =>$userid, 'status'=>'0','store_id'=>$storeid));
                                if(count($resp1) > 0){
                                    $cartsubtotal =  $resp1[0]['subtotal'] + $ord_deliverycharge + $processing_fee + $ord_tax + $tip_amount;
                                    $cartsubtotal = (string)$cartsubtotal;
                                    $orderid = "ORD" . time() . rand(1000, 9999);
                                    $item_count = $this->Model->get_selected_data('COUNT(id) as item_count','cart_item',array('user_id' =>$userid, 'status'=>'0','store_id'=>$storeid));
                                    $item_count = $item_count[0]['item_count'];
                                    $varity_count = $this->Model->getVarityCount($userid,$storeid);
                                    $varity_count = count($varity_count);
                                    $ordered_from = 'WEB';
                                    $orderdata = array('order_id' => $orderid,'user_id' => $userid,'txn_id' => $ord_txnid,
                                        'total_price' => $ord_totalprice,'tax' => $ord_tax,'coupanid' => $ord_coupanid, 
                                        'discount_amount' => $discount_amount, 'finalprice' => $ord_finlprice,
                                        'datetime' => $timedate,'processingfee' => $processing_fee,'dlv_charge' => $ord_deliverycharge,
                                        'slot_id' => $slotid,'dlv_date' => $develydate,'pay_email' => $pay_email,
                                        'pay_phone' => $pay_phone,'payerID' => $payerID,'tip_amount' => $tip_amount,
                                        'auth_amount' => $authAmount,'card_id' => $card_id,'delivery_time' => $deliveryUnitime,
                                        'item_count' => $item_count, 'varity_count' => $varity_count,
                                        'ordered_from' => $ordered_from,'store_id' => $storeid,
                                    );
                                    $lastid = $this->Model->add('order_table',$orderdata);
                                    if($lastid){
                                        $ordupdate = false;
                                        //----------updateorderitem---------
                                        $cartitemlist = $this->Model->get_record('cart_item',array('user_id' => $userid ,'status' => '0' ,'store_id' => $storeid));
                                        $item_data = [];
                                        foreach($cartitemlist as $itemcrt){
                                            $item_data[] = array(
                                                'item_id'   => $itemcrt['item_id'],
                                                'item_quty'  => $itemcrt['item_quty'],
                                                'price'     =>   $itemcrt['price'],
                                                'tax'  => $itemcrt['tax'],
                                                'user_id'   => $itemcrt['user_id'],
                                                'order_id'  => $orderid,
                                                'status'    => '1', // Status is changed to 1 = ordered item
                                                'created_at' => date('Y-m-d H:i:s')
                                            );
                                        }
                                        $add_item = $this->Model->batch_rec('ordered_item',$item_data);
                                        if($add_item){
                                            $ordupdate = true;
                                            foreach($cartitemlist as $itemrmv){
                                                $this->Model->delete('cart_item',array('item_id' => $itemrmv['item_id'],'user_id'=>$userid,'status' => '0','store_id' =>$storeid));
                                            }
                                        }
                                        //----------Shipping address---------
                                        $shipping_data = array('order_id' =>$orderid ,'user_id' =>$userid,
                                            'first_name' => $bil_firstname,'last_name' => $bil_lastname,
                                            'address' =>$bil_address,'ship_mobile_number'=> $bil_contact, 
                                            'email_id'=>$bil_email, 'country_id' => $bil_countryid, 
                                            'state_id' => $bil_stateid,'city_id' => $bil_cityid,
                                            'pincode' =>$bil_pincode);
                                        $insert_shipping_add = $this->Model->add('shipping_address',$shipping_data);
                                        if(!empty($discount_type)){
                                            if($discount_type == 'referral'){
                                                $referral_discount = $this->Model->get_selected_data(array('referred_by','referred_to','redeemed_referral_count','max_referrals_allowed'),"users",array('id' => $userid));
                                                if(count($referral_discount) > 0){
                                                    $referral_discount = $referral_discount[0];
                                                    if(isset($referral_discount['referred_by']) && $referral_discount['referred_by']!=''){
                                                        $referred_by = json_decode($referral_discount['referred_by']);
                                                        $referred_by_redeemed = $referred_by->is_redeemed;
                                                        if(isset($referred_by_redeemed) && $referred_by_redeemed == false){
                                                            // update is_redeemed to true and exit
                                                            $referral_info_new_user['user_id'] = $referred_by->user_id;
                                                            $referral_info_new_user['unitime'] = time();
                                                            $referral_info_new_user['is_redeemed'] = true;
                                                            $referral_info_new_user['order_id'] = $orderid;
                                                            $new_referral_json_new_user = json_encode($referral_info_new_user);
                                                            $this->Model->update('users',array('referred_by' => $new_referral_json_new_user),array('id' => $userid));
                                                        }else {
                                                            $max_referrals_allowed = $referral_discount['max_referrals_allowed'];
                                                            $redeemed_referral_count = $referral_discount['redeemed_referral_count'];
                                                            if($redeemed_referral_count < $max_referrals_allowed){
                                                                $referred_to = json_decode($referral_discount['referred_to']);
                                                                if(count($referred_to) >= $redeemed_referral_count+1) {
                                                                    foreach ($referred_to as $refrr) {
                                                                        if ($refrr->order_id == null) {
                                                                            $refrr->order_id = $order_id;
                                                                            $refrr->unitime = time();
                                                                            break;
                                                                        } else {
                                                                            continue;
                                                                        }
                                                                    }
                                                                    $referral_json_user = json_encode($referred_to);

                                                                    $this->Model->update('users',array('referred_to' => $referral_json_user,'redeemed_referral_count' =>'redeemed_referral_count'+1),array('id' => $userid));
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            } else if($discount_type == 'promotional'){
                                                if(!empty($coupanid)){
                                                    //-----------update_promotional_info----------
                                                    $get_promocode_rec = $this->Model->get_selected_data('id','promocode', array('id' => $coupanid));
                                                    if(count($get_promocode_rec) > 0){
                                                        $this->Model->add('user_promocode',array('promocode_id' => $coupanid,'user_id' =>$userid, 'order_id' => $orderid,
                                                            'created_at' => date('Y-m-d H:i:s')));
                                                    }
                                                }
                                            } else if($discount_type == 'membership'){
                                                // yet to be developed
                                            }
                                        }
                                        //-----------change json address to string----------
                                        $new_address = '';
                                        $json_address = json_decode($bil_address,true);
                                        if($json_address['apt_no'] != ''){
                                            $new_address.=  $json_address['apt_no'];
                                        }
                                        if($json_address['complex_name'] != ''){
                                            $new_address.= ', ' .$json_address['complex_name'];
                                        }
                                        $new_address.= ', '.$json_address['street_address'];
                                        $storename = $this->Model->get_selected_data('name','stores',array('id' => $storeid));
                                        $table_name_prefix = STORE_PREFIX.$storeid;
                                        $time_sloat = $table_name_prefix.'_'.TIME_SLOT;
                                        $slottime = $this->Model->get_selected_data('slot_name',$time_sloat,array('time_slot_id' => $slotid));
                                        $mail_info = array('storeid'=>$storeid,'storename' => $storename[0]['name'],
                                            'slottime' => $slottime[0]['slot_name'],'orderid' => $orderid,
                                            'datetime' =>  date('d-m-Y h:i:s A', $timedate),
                                            'fullname' => $bil_firstname.' '.$bil_lastname,'txnid' => $ord_txnid,
                                            'tip_amount' => $tip_amount,'bi_contact' => $bil_contact,
                                            'bi_email' => $bil_email,'bi_address' => $new_address,
                                            'subtotal' => $ord_totalprice,'totaltax' => $ord_tax,
                                            'processingfees' => $processing_fee,'deliverycharge' => $ord_deliverycharge,
                                            'finalprice' => $ord_finlprice,'develydate' => $develydate);
                                        //---------Send mail------
                                        $mail_temp = $this->load->view('go2gro_web/template/order_template',$mail_info,true);
                                        $ismailsend = $this->general->send_mail($bil_email,"Your Go2Gro order (".$orderid.") has been placed !",$mail_temp);
                                        //---------Send sms------
                                        $msg = "Dear " . $bil_firstname . " " . $bil_lastname . ", \n
                                            Thank you for using Go2Gro!\n
                                            Your Order id is " . $orderid . " \n
                                            Our dedicated team prepares your order as soon as you hit checkout, and bring it to your
                                            doorstep with a smile on our face. Your order will be with you shortly. We will contact you,
                                            if there are any changes in your order.";
                                        $sendsms = $this->general->sendsms($bil_contact,$msg);
                                        //-----------Send notification-----------
                                        $this->general->sent_notifiction($userid,$orderid,ORDER_PLACED,date('Y-m-d H:i:s'),$timedate,ORDER_TAG,"NA", "NA");
                                        //----------------------
                                        $response["error"] = false;
                                        $response["itemupdate"] = $ordupdate;
                                        $response["shipadressinser"] = $insert_shipping_add;
                                        $response["ismailsend"] = $ismailsend;
                                        $response["issmssend"] = $sendsms;
                                        $response["orderid"] = $orderid;
                                        $response["message"] = "order placed sucessfuly";
                                    }else{
                                        $response['error'] = true;
                                        $response['message'] = "An error occurred. Please try again";
                                    }
                                }else{
                                    $response['error'] = true;
                                    $response['message'] = "Subtotal error";
                                }
                            }
                        }else{
                            $response['error'] = true;
                            $response['message'] = "Cart empty";
                        }
                    }else{
                        $response['error'] = true;
                        $response['message'] = "Tip amount cannot be less than zero";
                    }
                }else{
                    $response['error'] = true;
                    $response['message'] = "Invalid Store";
                }
            }else{
                $response['error'] = true;
                $response['message'] = strip_tags(validation_errors());
            }
        }
        else{
            $response["error"] = true;
            $response['message'] = 'Api key is misssing';
        }
        responseJSON($response);
    }

    //-------------itemsearch-------------
    public function itemsearch(){
        $this->form_validation->set_rules('str','Search str','required');
        $this->form_validation->set_rules('status','status','required');
        $this->form_validation->set_rules('pageno','pageno','required');
        $this->form_validation->set_rules('store_id','store_id','required');
        $response = array();
        if($this->form_validation->run() == true){
            $deviceType = $this->input->post('deviceType');
            $str = $this->input->post('str');
            $status = $this->input->post('status');
            $pageno = $this->input->post('pageno');
            $storeid = $this->input->post('store_id');
            $str = addslashes($str);
            if (strlen($str) > 4) {
                $strwithrmlast = substr($str, 0, -1);
                $strwithrmlast2 = substr($str, 0, -2);
            } else {
                $strwithrmlast = $str;
                $strwithrmlast2 = $str;
            }
            $getstore = $this->Model->get_record('stores',array('id' => $storeid,'status' => 'active'));
            if(count($getstore) > 0) {
                $api_key  = $this->input->get_request_header('Authorization');
                if($api_key != ''){
                    $userid = getuserid('users',$api_key);
                    if(strlen($str) >= 4) {
                        $user_suggestions = $this->Model->get_selected_data('searched_keywords','users',array('id' => $userid));
                        if(! $user_suggestions) $user_suggestions = []; // Handle empty field from DB
                        if(! in_array($str, $user_suggestions)) { // Don't insert duplicate keywords
                            if(count($user_suggestions) >= 10 ){
                                array_shift($user_suggestions); // Push to top of array
                            }
                            array_push($user_suggestions, ucwords($str));
                            $this->Model->update('users',array('searched_keywords' => $user_suggestions),array('id' => $userid));
                        }
                    }
                }
                else{
                    if ($status == 0) {
                        $resultcat = $this->Model->getitemsuggetionbycat($str,$storeid,$deviceType);
                        if (count($resultcat) > 0) {
                            $response["error"] = false;
                            $response["item"] = $resultcat;
                            $response["message"] = "list get sucessfuly";
                        } 
                        else{
                            $result = $this->Model->getitemsuggetionbystr($str,$storeid,$deviceType);
                            if (count($result) > 0) {
                                $response["error"] = false;
                                $response["item"] = $result;
                                $response["message"] = "list get sucessfuly";
                            } 
                            else 
                            {
                                $result1 = $this->Model->getitemsuggetion($str, $strwithrmlast, $strwithrmlast2,$storeid,$deviceType);
                                if (count($result1) > 0) {
                                    $response["error"] = false;
                                    $response["item"] = $result1;
                                    $response["message"] = "list get sucessfuly";
                                } else {
                                    $response["error"] = true;
                                    $response["message"] = "The requested resource doesn't exists";
                                }
                            }
                        }
                    }
                    elseif ($status == 1) {
                        $resultcatitem =$this->Model->getsuggetionitembycart($str, $pageno, $storeid,$deviceType);
                        if (count($resultcatitem)>0) {
                            $re = $this->Model->getsuggetionitembycartcount($str,$storeid,$deviceType);
                            $response["totalcount"] = count($re);
                            $response["error"] = false;
                            $response["item"] = $resultcatitem;
                            $response["message"] = "list get sucessfuly";
                        }else{
                            $result2 = $this->Model->getsuggetionitemsbystr($str, $pageno,$storeid,$deviceType);
                            if (count($result2) > 0) {
                                $re = $this->Model->getsuggetionitemscountbystr($str,$storeid,$deviceType);
                                $response["totalcount"] = count($re);
                                $response["error"] = false;
                                $response["item"] = $result2;
                                $response["message"] = "list get sucessfuly";
                            } 
                            else{
                                $result3 = $this->Model->getsuggetionitems($str, $pageno, $strwithrmlast, $strwithrmlast2,$storeid,$deviceType);
                                if (count($result3) > 0) {
                                    $re = $this->Model->getsuggetionitemscount($str, $strwithrmlast, $strwithrmlast2,$storeid,$deviceType);
                                    $response["totalcount"] = count($re);
                                    $response["error"] = false;
                                    $response["item"] = $result3;
                                    $response["message"] = "list get sucessfuly";
                                }else{
                                    $response["error"] = true;
                                    $response["message"] = "The requested resource doesn't exists 1";
                                }
                            }
                        }
                    } 
                    else 
                    {
                        $response["error"] = true;
                        $response["message"] = "something went wrong please try again";
                    }
                }
            }else{
                $response["error"] = true;
                $response["message"] = "Invalid Store ID!";
            }
        }else{
            $response['error'] = true;
            $response['message'] = strip_tags(validation_errors());
        }
        responseJSON($response);
    }

    //-------------getcartitems---------
    public function getcartitems(){
        $response = array();
        $api_key  = $this->input->get_request_header('Authorization');
        if($api_key != ''){
            $this->form_validation->set_rules('store_id','store id','required');
            if($this->form_validation->run() == true){
                $storeid = $this->input->get('store_id');
                $userid = getuserid('users',$api_key);
                $check_store =$this->Model->get_record('stores',array('id' => $storeid,'status' => 'active'));
                if(count($check_store) > 0){
                    //--------empty_cart_if_item_from_another_store-------
                    $items_exits = $this->Model->get_record('cart_item', array('user_id' =>$userid, 'store_id<>' => $storeid));
                    if(count($items_exits) > 0){
                        $this->Model->delete('cart_item',array('user_id' => $userid,'status' =>0));
                    }

                    $resp = $this->Cart_model->getcartitem($userid,$storeid);
                    if(count($resp) > 0){
                        $discount = 0; $processing_fee = 0.00;
                        $response["error"] = false;
                        $response["item"] = array();
                        $charges = $this->Model->get_selected_data(array('delivery_charges','processing_fee'),"charges_rule");
                        $referral_discount = $this->Model->get_selected_data(array('referred_by','referred_to','redeemed_referral_count','max_referrals_allowed'),"users",array('id' => $userid));
                        if(count($referral_discount) > 0){
                            $referral_discount = $referral_discount[0];
                            if(isset($referral_discount['referred_by']) && $referral_discount['referred_by']!=''){
                                $referred_by = json_decode($referral_discount['referred_by']);
                                $referred_by_redeemed = $referred_by->is_redeemed;
                                if(isset($referred_by_redeemed) && $referred_by_redeemed == false){
                                    $discount = REFERRAL_DISCOUNT;
                                }else {
                                    $referred_to = json_decode($referral_discount['referred_to']);
                                    $max_referrals_allowed = $referral_discount['max_referrals_allowed'];
                                    $redeemed_referral_count = $referral_discount['redeemed_referral_count'];
                                    if($redeemed_referral_count < $max_referrals_allowed){
                                        if(count($referred_to) >= $redeemed_referral_count+1) {
                                            $discount = REFERRAL_DISCOUNT;
                                        }
                                    }
                                }
                                if($discount > 0){
                                    $response["discount_label"] = "Referral Discount";
                                    $response["discount_type"] = "referral";
                                    $response["discount_id"] = -1;
                                }
                            }
                        }else{
                            $response["discount_label"] = "Discount";
                            $response["discount_type"] = "";
                            $response["discount_id"] = 0;
                        }
                        if(isset($charges) && count($charges) >0){
                            $processing_fee = $charges[0]["processing_fee"];
                        }
                        //delivery charges store wise
                        $response["delivery_charges_label"] = "Delivery charge";
                        $response["delivery_charges"] = $store_data['delivery_charge'];
                        $response["processing_fee"] = $processing_fee;
                        $response["discount"] = $discount;
                        $response["message"] = "list get sucessfuly";
                        $subtotal = 0;

                        foreach ($resp as $item) {
                            $item = array_map('utf8_encode', $item);
                            array_push($response["item"], $item);
                            $subtotal += $item['total']; 
                        }
                        //------------check membership plan------
                        $is_membership = is_membership_applicable($userid,$subtotal,MEMBERSHIP_APPLICABLE_SUBTOTAL);
                        if($subtotal >= $store_data['free_delivery_amount']){
                            $response["delivery_charges_label"] = "Free Delivery";
                            $response["delivery_charges"] = 0;
                        }
                        elseif($is_membership){
                            $response["delivery_charges_label"] = "Delivery charge (Membership Plan)";
                            $response["delivery_charges"] = MEMBERSHIP_DELIVERY_CHARGE; //membership apply if subtotal > $40
                        }
                        $chk = $this->Cart_model->ischeckfirsetorder($userid);
                        $response["isfirstorder"] = $chk;
                        $response['tips_arr'] = unserialize(TIPS_ARR);
                    }else{
                        $response["error"] = true;
                        $response["message"] = "your cart is empty";
                    }
                }else{
                    $response["error"] = true;
                    $response["message"] = "Invalid Store";
                }
            }else{
                $response['error'] = true;
                $response['message'] = strip_tags(validation_errors());
            }
        }else{
            $response["error"] = true;
            $response['message'] = 'Api key is misssing';
        }
        responseJSON($response);
    }

    //-------------additemcart-----------

    public function additemcart(){
        $response = array();
        $api_key  = $this->input->get_request_header('Authorization');
        if($api_key != ''){
            $this->form_validation->set_rules('item_id','Item id','required');
            $this->form_validation->set_rules('item_quty','Item quty','required');
            $this->form_validation->set_rules('item_price','Item price','required');
            $this->form_validation->set_rules('item_tax','Item tax','required');
            $this->form_validation->set_rules('store_id','store','required');
            if($this->form_validation->run() == true){
                $item_id = $this->input->post('item_id');
                $item_quty = $this->input->post('item_quty');
                $item_price = $this->input->post('item_price');
                $item_tax = $this->input->post('item_tax');
                $storeid = $this->input->post('store_id');
                $userid = getuserid('users',$api_key);
                $check_store =$this->Model->get_record('stores',array('id' => $storeid,'status' => 'active'));
                if(count($check_store) > 0){
                    //-----------check item in store-------- 
                    $check_item_in_store = $this->Cart_model->checkitemisexistinstore($item_id,$storeid);
                    if($check_item_in_store){
                        //-----------check item in cart-------- 
                        $checkitemisexist =  $this->Model->get_record('cart_item',array('item_id' => $item_id,'user_id' => $userid,'status'=> 0,'store_id' => $storeid));
                        if(count($checkitemisexist) > 0 ){
                            $response['error'] = true;
                            $response['message'] = 'Item already added in cart.';
                        }else{
                            $condition = "`cat_name` In('Beers','Wines','Wine') AND `store_id`=1";
                            $categories_with_limit = $this->Model->get_selected_data('id','category',$condition);

                            $flag = true;
                            $itemlink_table =  STORE_PREFIX.$storeid.'_'.ITEMLINK_TABLE;
                            $cat_id = $this->Model->gettwodata('s.cat_id',"`".$itemlink_table."` as il","subcategory as s",'il.subcat_id = s.sub_id',$where=array('il.item_id' => $item_id));

                            if(isset($categories_with_limit) && count($categories_with_limit) > 0 ){
                                $category_array = array();
                                for($i = 0; $i < count($categories_with_limit); $i++){
                                    array_push($category_array,$categories_with_limit[$i]['id']);
                                }
                                if(in_array($cat_id[0]['cat_id'], $category_array)) { // check if this item exixts in one of the restricted purchase category (beer,wine) & if item is allowed as per the limit
                                    if (!is_item_under_purchase_limit($item_id, $item_quty, $userid, 'additem',$storeid)) {
                                        $flag = false;
                                    }
                                    if (!is_item_beers_under_purchase_limit($item_id, $item_quty, $userid, 'additem',$storeid)) {
                                        $flag = false;
                                    }
                                }
                            }
                            if($flag) 
                            {
                                //----------insertitemincart-------------------
                                $user = $this->Model->add('cart_item',array('item_id' => $item_id,'item_quty' => $item_quty,'user_id' =>$userid ,'price'=>$item_price,'tax' => $item_tax,'store_id' => $storeid));
                                if ($user) {
                                    $resp = $this->Cart_model->getcartitem($userid,$storeid);
                                    if (count($resp) > 0) {
                                        $response["error"] = false;
                                        $response["item"] = array();
                                        $response["message"] = "Item added successfully in cart.";
                                        foreach ($resp as $item) {
                                            $item = array_map('utf8_encode', $item);
                                            array_push($response["item"], $item);
                                        }
                                        
                                        $chk = $this->Cart_model->ischeckfirsetorder($userid);
                                        $response["isfirstorder"] = $chk;
                                    } 
                                    else {
                                        $response["error"] = true;
                                        $response["message"] = "The requested resource doesn't exists";
                                    }
                                } 
                                else {
                                    $response['error'] = true;
                                    $response['message'] = "An error occurred. Please try again";
                                }
                            }
                            else {
                                $response['error'] = true;
                                $response['message'] = 'You have exceeded the allowed limit for this category of items.';
                            }
                        }
                    }else{
                        $response['error'] = true;
                        $response['message'] = 'invalid item';
                    }
                }else{
                    $response["error"] = true;
                    $response["message"] = "Invalid Store";
                }
            }else{
                $response['error'] = true;
                $response['message'] = strip_tags(validation_errors());
            }
        }else{
            $response["error"] = true;
            $response['message'] = 'Api key is misssing';
        }
        responseJSON($response);
    }

    //-------------itemdeletecart---------
    public function itemdeletecart(){
        $response = array();
        $api_key  = $this->input->get_request_header('Authorization');
        if($api_key != ''){
            $this->form_validation->set_rules('item_id','Item id','required');
            $this->form_validation->set_rules('store_id','store','required');
            if($this->form_validation->run() == true){
                $userid = getuserid('users',$api_key);
                $item_id = $this->input->post('item_id');
                $storeid = $this->input->post('store_id');
                $check_store =$this->Model->get_record('stores',array('id' => $storeid,'status' => 'active'));
                if(count($check_store) > 0){
                    //-----------check item in store-------- 
                    $check_item_in_store = $this->Cart_model->checkitemisexistinstore($item_id,$storeid);
                    if($check_item_in_store){
                        //-----------check item in cart-------- 
                        $checkitemisexist =  $this->Model->get_record('cart_item',array('item_id' => $item_id,'user_id' => $userid,'status'=> 0,'store_id' => $storeid));
                        if(count($checkitemisexist) > 0 ){
                            $deleteitemincart = $this->Model->delete('cart_item',array('item_id' => $item_id,'user_id' => $userid,'status'=> 0,'store_id' => $storeid));
                            if($deleteitemincart){
                                $resp = $this->Cart_model->getcartitem($userid,$storeid);
                                if (count($resp) > 0) {
                                    $response["error"] = false;
                                    $response["item"] = array();
                                    $response["message"] = "Item added successfully in cart.";
                                    foreach ($resp as $item) {
                                        $item = array_map('utf8_encode', $item);
                                        array_push($response["item"], $item);
                                    }
                                }else{
                                    $response["error"] = false;
                                    $response["message"] = "your cart is empty";
                                }
                            }else{
                                $response['error'] = true;
                                $response['message'] = "An error occurred. Please try again";
                            }
                        }else{
                            $response['error'] = true;
                            $response['message'] = 'Item is not avaliable in cart';
                        }
                    }
                    else{
                        $response['error'] = true;
                        $response['message'] = 'invalid item';
                    }
                }else{
                    $response["error"] = true;
                    $response["message"] = "Invalid Store";
                }
            }else{
                $response['error'] = true;
                $response['message'] = strip_tags(validation_errors());
            }
        }else{
            $response["error"] = true;
            $response['message'] = 'Api key is misssing';
        }
        responseJSON($response);
    }

    //-----------updateitemcart----------
    public function cartitemupdate(){  
        $response = array();
        $api_key  = $this->input->get_request_header('Authorization');
        if($api_key != ''){
            $this->form_validation->set_rules('item_id','Item id','required');
            $this->form_validation->set_rules('item_quty','Item quainty','required');
            $this->form_validation->set_rules('store_id','store','required');
            if($this->form_validation->run() == true){
                $userid = getuserid('users',$api_key);
                $item_id = $this->input->post('item_id');
                $item_quty = $this->input->post('item_quty');
                $storeid = $this->input->post('store_id');
                $check_store =$this->Model->get_record('stores',array('id' => $storeid,'status' => 'active'));
                if(count($check_store) > 0){
                    //-----------check item in store-------- 
                    $check_item_in_store = $this->Cart_model->checkitemisexistinstore($item_id,$storeid);
                    if($check_item_in_store){
                        //-----------check item in cart-------- 
                        $checkitemisexist =  $this->Model->get_record('cart_item',array('item_id' => $item_id,'user_id' => $userid,'status'=> 0,'store_id' => $storeid));
                        if(count($checkitemisexist) > 0 ){
                            $condition = "`cat_name` In('Beers','Wines','Wine') AND `store_id`='".$storeid."'";
                            $categories_with_limit = $this->Model->get_selected_data('id','category',$condition);
                            $flag = true;
                            $itemlink_table =  STORE_PREFIX.$storeid.'_'.ITEMLINK_TABLE;
                            $cat_id = $this->Model->gettwodata('s.cat_id',"`".$itemlink_table."` as il","subcategory as s",'il.subcat_id = s.sub_id',$where=array('il.item_id' => $item_id));

                            if(isset($categories_with_limit) && count($categories_with_limit) > 0 ){
                                $category_array = array();
                                for($i = 0; $i < count($categories_with_limit); $i++){
                                    array_push($category_array,$categories_with_limit[$i]['id']);
                                }

                                if (in_array($cat_id[0]['cat_id'], $category_array)) { // check if this item exixts in one of the restricted purchase category (beer,wine) & if item is allowed as per the limit
                                    if (!is_item_under_purchase_limit($item_id, $item_quty, $userid, 'updateitem',$storeid)) {
                                        $flag = false;
                                    }
                                    if (!is_item_beers_under_purchase_limit($item_id, $item_quty, $userid, 'updateitem',$storeid)) {
                                        $flag = false;
                                    }
                                }
                            }
                            if($flag) 
                            {
                                //----------insertitemincart-------------------
                                $user = $this->Model->update('cart_item',array('item_quty' => $item_quty),array('item_id' => $item_id,'user_id' =>$userid ,'status'=> 0,'store_id' => $storeid));
                                if ($user) {
                                    $resp = $this->Cart_model->getcartitem($userid,$storeid,$item_id);
                                    if (count($resp) > 0) {
                                        foreach ($resp as $item) {
                                            $item = array_map('utf8_encode', $item);
                                        }
                                        $response["error"] = false;
                                        $response["item_price"] = $item['item_price'];
                                        $response["item_quty"] = $item['item_quty'];
                                        $response["total"] = $item['total'];
                                        $response["message"] = "Item update successfully in cart.";
                                    } 
                                    else {
                                        $response["error"] = true;
                                        $response["message"] = "your cart is empty";
                                    }
                                } 
                                else {
                                    $response['error'] = true;
                                    $response['message'] = "An error occurred. Please try again";
                                }
                            }
                            else {
                                $response['error'] = true;
                                $response['message'] = 'You have exceeded the allowed limit for this category of items.';
                            }
                        }else{
                            $response['error'] = true;
                            $response['message'] = 'Item is not avaliable in cart';
                        }
                    }else{
                        $response['error'] = true;
                        $response['message'] = 'invalid item';
                    }
                }else{
                    $response["error"] = true;
                    $response["message"] = "Invalid Store";
                }
            }else{
                $response['error'] = true;
                $response['message'] = strip_tags(validation_errors());
            }
        }else{
            $response["error"] = true;
            $response['message'] = 'Api key is misssing';
        }
        responseJSON($response);
    }

    //-----------getslot----------------
    public function getslot($storeid){
        $response = array();
        if($storeid != ''){
            $check_store =$this->Model->get_record('stores',array('id' => $storeid,'status' => 'active'));
            if(count($check_store) > 0) {
                $stores = $check_store[0];
                $workingday = json_decode($stores['working_daytime'],true);
                foreach ($workingday as $work_key => $day_time_value){
                    $starttime = substr(date('H', strtotime($day_time_value['opening_time'])),0,2);
                    $endtime = substr(date('H', strtotime($day_time_value['closing_time'])),0,2);

                    for ($i = 0; $i <= 6; $i++) {
                        $end_date = strtotime("+$i day", $start_date);
                        $date = date('Y-m-d', $end_date);
                        $timestamp = strtotime("+$i day", $time);
                        $daybydate = strtotime($date);
                        $storeday = date('D', $daybydate);
                        $day = date('l', $daybydate);
                        if($day == ucfirst($work_key) && ($day_time_value['opening_time']!='closed' && $day_time_value['closing_time']!='closed')){
                            
                            if(ucfirst($work_key) == date('l')){
                                $work_key ="Today";
                            }
                            $store_close_time = date('H:i', strtotime($day_time_value['closing_time']));
                            $store_close_time = substr($store_close_time,0,2) ;

                            $result = $this->Model->getslot($storeid,$date,$storeday,$currentdate, $converttime,$starttime,$endtime);
                            $new_arr = $result;
                            //----------------last time get-------------------------
                            $lst_index = count($result) -1;
                            //----------------last time get-------------------------
                            $perDayslot = array();
                            if (count($result) > 0) {
                                foreach ($result as $key => $slots) {
                                    $slots["isShowTIme"] = 1;
                                    //Amit Code Start
                                    $currentdate = date('Y-m-d', $time);
                                    if ($date == $currentdate) {
                                        $slot_start_time = substr($slots["start_time"], 0, 2);
                                        $slot_time = substr($slots["end_time"], 0, 2);
                                        //------------within 2 hrs delaivery time hide
                                        if($slots["slot_name"]=="Within 2 hours"){
                                            $start_two_hours_time = $slot_start_time-1;
                                            $slots["start_two_hours_time"] = $start_two_hours_time;
                                            $slots["converttime"] =$converttime;
                                            if($converttime >=$start_two_hours_time &&  $converttime < $slot_time){
                                                $slots["isShowTIme"] = 0;
                                            }else{
                                                $slots["isShowTIme"] = 1;
                                            }
                                        }//------------within 2 hrs delaivery time hide end
                                        else if($key==$lst_index){
                                            //store closeing time manage last timeslot 
                                            $last_slot_name = $result[$lst_index]['slot_name'];
                                            if($store_close_time >= $slot_time && $converttime < $slot_time) { 
                                                if($slots["slot_name"]==$last_slot_name){
                                                    $slots["isShowTIme"] = 0;
                                                }
                                            }
                                            //$slot_time = substr($slots["end_time"], 0, 2);
                                        }
                                        else{
                                            $slot_time = substr($slots["end_time"], 0, 2);
                                            $timehours2 = date('H:i', $time);
                                            $converttime2 = substr($timehours2, 0, 2);
                                            $converttime2 = $converttime2 + 2;
                                            if ($converttime2 <= $slot_time) {
                                                $slots["isShowTIme"] = 0;
                                            }
                                            else {
                                                $slots["isShowTIme"] = 1;
                                            }
                                        }  
                                    } 
                                    else {
                                        $slots["isShowTIme"] = 0;
                                        if($slots["slot_name"]=="Within 2 hours"){
                                            $slots["isShowTIme"] = 1;
                                        }
                                    }
                                    //Amit Code Ends
                                    array_push($perDayslot, $slots);
                                }
                            }
                            $abcd[]= array("date" => $date, "perdayslot" =>$perDayslot, "unitime" => $timestamp, "converted" => $converttime,"day" => $day,"work" => ucfirst($work_key));
                            array_push($response['slots'], $abcd);
                        }
                    }
                    //-----------sorting array by date------
                    $new_arr = array();
                    if(count($abcd) > 0){
                        $new_arr = array();
                        foreach ($abcd as $key => $row)
                        {
                            $new_arr[$key]['date'] = $row['date'];
                            $new_arr[$key]['perdayslot'] = $row['perdayslot'];
                            $new_arr[$key]['unitime'] = $row['unitime'];
                            $new_arr[$key]['converted'] = $row['converted'];
                            $new_arr[$key]['day'] = $row['day'];
                            $new_arr[$key]['work'] = $row['work'];
                        }
                        array_multisort($new_arr, SORT_ASC, $abcd);
                    }
                    $response["error"] = false;
                    $response['slots'] = $new_arr;
                    $response["message"] = "all slot avaliable";
                }
            }else{
                $response["error"] = true;
                $response["message"] = "Invalid Store";
            }
        }else{
            $response["error"] = true;
            $response['message'] = 'Store id missing';
        }
        responseJSON($response);
    }

    //-------------getalternativeitem------
    public function getalternativeitem($userid, $orderid, $storeid){
        $check_store = $this->Model->get_record('stores',array('id' => $storeid,'status' => 'active'));
        if(count($check_store) > 0) {
            $isUserExistsbyuserid = $this->Model->get_selected_data('id','users',array('id' => $userid));
            if($isUserExistsbyuserid){
                $checkordereditmode = $this->Model->get_selected_data('order_id','order_table',array('order_id' => $orderid,'is_order_edit' => '1'));
                if($checkordereditmode){
                    $checkorderbyuser = $this->Model->get_record('order_table',array('order_id' => $storeid,'user_id' => $userid));
                    if($checkorderbyuser){
                        $result = $this->Model->getalernativeitems($userid, $orderid, $storeid);
                        if(count($result) > 0){
                            $chk = $this->Model->getorderbyid($orderid, $userid,$storeid);
                            if(count($chk) > 0){
                                $order = $chk[0];
                                $response["orderdetail"] = $order;
                                $response["error"] = false;
                                $response["itemlist"] = array();
                                foreach ($result as $value) {
                                    $value= array_map('utf8_encode', $value);
                                    array_push($response["itemlist"], $value);
                                }
                                $response["message"] = "item get sucessfuly";
                            }
                        }else{
                            $response["error"] = true;
                            $response["message"] = "The requested resource doesn't exists";
                        }
                    }else{
                        $response["error"] = true;
                        $response["message"] = "order not belongs to this user";
                    }
                }else{
                    $response["error"] = true;
                    $response["message"] = "order not in edit mode";
                }
            }else{
                $response["error"] = true;
                $response["message"] = "User Not Valid";
            }
        }else{
            $response["error"] = true;
            $response["message"] = "Invalid Store";
        }
        responseJSON($response);
    }

    //-------------alternatplaceorder---------
    public function alternatplaceorder(){
        $this->form_validation->set_rules('ord_userid','User Id','required');
        $this->form_validation->set_rules('new_order_status','New Order Status','required');
        $this->form_validation->set_rules('orderstatus','Order status','required');
        $this->form_validation->set_rules('order_id','order id','required');
        $this->form_validation->set_rules('ord_tax','Order Tax','required');
        $this->form_validation->set_rules('ord_totalprice','Order Total price','required');
        $this->form_validation->set_rules('ord_finlprice','Order Final price','required');
        $this->form_validation->set_rules('item_detail','Item Details','required');
        if($this->form_validation->run() == true){
            $userid         = $this->input->post('ord_userid');
            $neworderstatus = $this->input->post('new_order_status');
            $orderid        = $this->input->post('order_id');
            $ordtax         = $this->input->post('ord_tax');
            $totalprice     = $this->input->post('ord_totalprice');
            $finalprice     = $this->input->post('ord_finlprice');
            $rp_amount      = $this->input->post('rp_amount');
            $rp_status      = $this->input->post('rp_status');
            $rp_tranxid     = $this->input->post('rp_tranxid');
            $item_detail    = $this->input->post('item_detail');
            $orderstatus    = $this->input->post('orderstatus');
            $rp_compeletestatus    = $this->input->post('rp_compeletestatus');
            $timedate = time();
            $isUserExistsbyuserid = $this->Model->get_selected_data('id','users',array('id' => $userid));
            if($orderstatus == 0){
                $checkOrderPicked = $this->Model->get_selected_data('id','pd_order',array('order_id' => $orderid));
                if($checkOrderPicked){
                    $orderstatus = 1;
                }
            }
            if($isUserExistsbyuserid){
                $checkordereditmode = $this->Model->get_selected_data('order_id','order_table',array('order_id' => $orderid,'is_order_edit' => '1'));
                if($checkordereditmode){
                    $checkorderbyuser = $this->Model->get_record('order_table',array('order_id' => $storeid,'user_id' => $userid));
                    if($checkorderbyuser){
                        if($neworderstatus == "0"){
                            $iseditmode = "2";
                            $resp = $this->Model->update('order_table',array('status' => $orderstatus,'is_order_edit' => $iseditmode,'alternate_approval_role' => ""),array('order_id' => $orderid));
                            $response["error"] = false;
                            $response["mainorderupdate"] = $resp;
                            $response["message"] = "order cancel successfully";
                        }
                        elseif ($neworderstatus == "1") {
                            $res = $this->Model->update('order_table',array('total_price' => $totalprice,'tax' => $ordtax,'finalprice' => $finalprice),array('order_id' => $orderid));
                            if($res){
                                $counter = 0;
                                $data = json_decode($item_detail, true);
                                foreach ($data as $key => $arrays) {

                                    foreach ($arrays as $array) {
                                        //$str= implode(',' ,$array);
                                        $alt_item = $array['alt_itemid'];
                                        $status = $array['status'];
                                        $itemid = $array['itemid'];
                                        $quty = $array['quty'];
                                        $resp = $this->Cart_model->updateitemincart($itemid, $status, $alt_item, $orderid, $quty);
                                        if ($resp) {
                                            $counter++;
                                        }
                                    }
                                }
                                $iseditmode = "2";
                                $resp = $this->Model->update('order_table',array('status' => $orderstatus,'is_order_edit' => $iseditmode,'alternate_approval_role' => $role),array('order_id' => $orderid));
                                $response["error"] = false;
                                $response["mainorderupdate"] = $resp;
                                $response["updatecounter"] = $counter;
                                $response["message"] = "order Update successfully";
                            }else{
                                $response["error"] = true;
                                $response["errorstatus"] = "2";
                                $response["message"] = "order not update, something went wrong";
                            }
                        }
                        elseif ($neworderstatus == "2") {
                            $res = $this->Model->update('order_table',array('total_price' => $totalprice,'tax' => $ordtax,'finalprice' => $finalprice),array('order_id' => $orderid));
                            if ($res) {
                                $counter = 0;
                                $data = json_decode($item_detail, true);
                                foreach ($data as $key => $arrays) {

                                    foreach ($arrays as $array) {
                                        //$str= implode(',' ,$array);
                                        $alt_item = $array['alt_itemid'];
                                        $status = $array['status'];
                                        $itemid = $array['itemid'];
                                        $quty = $array['quty'];

                                        $resp = $db->updateitemincart($itemid, $status, $alt_item, $orderid, $quty);
                                        if ($resp) {
                                            $counter++;
                                        }
                                    }
                                }
                                $iseditmode = "2";
                                $resp = $this->Model->update('order_table',array('status' => $orderstatus,'is_order_edit' => $iseditmode,'alternate_approval_role' =>""),array('order_id' => $orderid));
                                $response["error"] = false;
                                $response["mainorderupdate"] = $resp;
                                $response["updatecounter"] = $counter;
                                $response["message"] = "order update successfully";
                            }else {
                                $response["error"] = true;
                                $response["errorstatus"] = "3";
                                $response["message"] = "order not update, something went wrong";
                                echoRespnse(200, $response);
                            }
                        }else{
                            $response["error"] = true;
                            $response["message"] = "New order Status Not Valid";
                        }
                    }else{
                        $response["error"] = true;
                        $response["message"] = "order not belongs to this user";
                    }
                }else{
                    $response["error"] = true;
                    $response["message"] = "order not in edit mode";
                }
            }else{
                $response["error"] = true;
                $response["message"] = "User Not Valid";
            }
        }
        else{
            $response['error'] = true;
            $response['message'] = strip_tags(validation_errors());
        }
        responseJSON($response);
    }

    //--------------updatecheckoutcart----------
    public function updatecheckoutcart(){
        $response = array();
        $api_key  = $this->input->get_request_header('Authorization');
        if($api_key != ''){
            $this->form_validation->set_rules('item_id','Item id','required');
            $this->form_validation->set_rules('store_id','store','required');
            if($this->form_validation->run() == true){
                $userid = getuserid('users',$api_key);
                $itemids = $this->input->post('item_id');
                $storeid = $this->input->post('store_id');
                $cartitems = explode(",", $itemids);
                $count = count($cartitems);
                $valcount = 0;
                $countitem = 0;
                foreach ($cartitems as $itemid) {
                    $itemid = trim($itemid);
                    $item_table = STORE_PREFIX.$storeid.'_'.ITEMS_TABLE;
                    $res = $this->Model->get_record('cart_item',array('item_id' => $itemid,'user_id' => $userid,'status' => 0,'store_id' => $storeid));
                    if(count($res) > 0){
                        $countitem++;
                        $item = $this->Model->get_record($item_table,array('item_id' => $itemid));
                        if($this->Model->update('cart_item',array('price'=>$item[0]['item_price'],'tax'=> $item[0]['Sales_tax']),array('item_id' =>$itemid,'user_id'=>$userid,'status' =>0,'store_id' => $storeid))){
                            $valcount++;
                        }
                    }
                }
                $chk = $this->Cart_model->ischeckfirsetorder($userid);
                $response["isfirstorder"] = $chk;
                if ($countitem != 0) {
                    if ($valcount == $countitem) {
                        $response['messsage'] = "cart update sucessfully";
                        $response['error'] = false;
                        $response['countval'] = $valcount;
                    } else {
                        $response['messsage'] = "An error occurred. Please try again";
                        $response['error'] = true;
                        $response['countval'] = $valcount;
                    }
                } else {
                    $response['messsage'] = "No item found in your cart";
                    $response['error'] = true;
                    $response['countval'] = $valcount;
                }
            }else{
                $response['error'] = true;
                $response['message'] = strip_tags(validation_errors());
            }
        }else{
            $response["error"] = true;
            $response['message'] = 'Api key is misssing';
        }
        responseJSON($response);
    }

    //-------------userreemovecard-----------
    public function userreemovecard(){
        $response_result = array();
        $api_key  = $this->input->get_request_header('Authorization');
        if($api_key != ''){
            $this->form_validation->set_rules('customerPaymentProfileId','customerPaymentProfileId','required');
            $this->form_validation->set_rules('customerProfileId','customerProfileId','required');
            if($this->form_validation->run() == true){
                $userid = getuserid('users',$api_key);
                if($userid){
                    if ($customerPaymentProfileId != "NA" && $customerProfileId != "NA") {
                        $getsavecardbyid = $this->Model->get_record('user_save_card',array('customerProfileId' => $customerProfileId,'customerPaymentProfileId' => $customerPaymentProfileId, 'user_id' => $userid));
                        if(count($getsavecardbyid) > 0){
                            $isspayment = $this->payment->deleteCustomerPaymentProfile($customerProfileId,$customerPaymentProfileId);
                            if($isspayment){
                                $last_id_update = $this->Model->update('user_save_card',array('status' => '1'),array('user_id' =>$userid,'customerProfileId' => $customerProfileId,'customerPaymentProfileId' => $customerPaymentProfileId));
                                if($last_id_update){
                                    $response_result["iscardremove"] = true;
                                    $response_result["error"] = false;
                                    $response_result["message"] = "card deleted sucessfully";
                                }
                            }else {
                                $response_result["error"] = true;
                                $response_result["message"] = "card not deleted";
                            }
                        }else{
                            $response_result["error"] = true;
                            $response_result["message"] = "something went wrong please try again";
                        }
                    }else{
                        $response_result["error"] = true;
                        $response_result["message"] = "Information Not Valid";
                    }
                }else{
                    $response_result['error'] = true;
                    $response_result['message'] = 'invalid auth key';
                }
            }
            else{
                $response_result['error'] = true;
                $response_result['message'] = strip_tags(validation_errors());
            }
        }else{
            $response_result["error"] = true;
            $response_result['message'] = 'Api key is misssing';
        }
        responseJSON($response_result);
    }

    //-------------cancleorder------------
    public function cancleorder(){
        $response = array();
        $api_key  = $this->input->get_request_header('Authorization');
        if($api_key != ''){
            $this->form_validation->set_rules('orderid','orderid','required');
            $this->form_validation->set_rules('store_id','store_id','required');
            if($this->form_validation->run() == true){
                $orderid = $this->input->post('orderid');
                $storeid = $this->input->post('store_id');
                $userid = getuserid('users',$api_key);
                if($userid){
                    $resp = $this->Model->getorderbyid($orderid, $userid,$storeid);
                    if (count($resp) > 0) {
                        $info = $resp[0];
                        $bi_name = $info['ship_name'];
                        $bi_address = $info['shipping_address'];
                        $bi_contact = $info['ship_mobile'];
                        $ship_pincode = $info['ship_pincode'];
                        $bi_email = $info['email_id'];
                        $txnid = $info['txn_id'];
                        $subtotal = $info['total_price'];
                        $totaltax = $info['tax'];
                        $tip_amount = $info['tip_amount'];
                        $processingfees = $info['dlv_charge'];
                        $deliverycharge = $info['processingfee'];
                        $finalprice = $info['finalprice'];
                        $timedate = $info['datetime'];
                        $currenttime = time();
                        $difference = $currenttime - $timedate;
                        $minutes = 5; // order can be cancelled only within 5 mins of placing
                        //if ($difference > ($minutes*60) ) {
                        if ($difference > 600){
                            $response['error'] = true;
                            $response['message'] = "Cancellation Policy: Sorry! Orders can only be cancelled within 10 minutes of ordering. If you have any questions regarding your order you can also contact our Toll Free Number: +1 (833) 346-2476 ";
                        } 
                        else {
                            $isvoid = $this->payment->voidTransaction($txnid);
                            if ($isvoid == "true") {
                                // when user is cancelling the order move items from ordered_item table to finished_item table
                                $cart_items = $this->Model->get_record('ordered_item', array('order_id' => $orderid));
                                $item_data = [];
                                foreach($cart_items as $itemcrt){
                                    $item_data[] = array(
                                        'item_id'   => $itemcrt['item_id'],
                                        'item_quty'  => $itemcrt['item_quty'],
                                        'price'=> $itemcrt['price'],
                                        'tax'  => $itemcrt['tax'],
                                        'user_id'   => $itemcrt['user_id'],
                                        'order_id'  => $itemcrt['order_id'],
                                        'alernative_item_id'  => $itemcrt['alernative_item_id'],
                                        'status'    => $itemcrt['status'],
                                    );
                                }
                                $add_item = $this->Model->batch_rec('finished_item',$item_data);
                                if($add_item){
                                    $ordupdate = true;
                                    foreach($cart_items as $oid){
                                        $this->Model->delete('ordered_item',array('order_id' => $oid['order_id']));
                                    }
                                }
                                $user = $this->Model->update('order_table',array('status'=>6),array('order_id'=>$orderid,'status'=>0));
                                if($user){
                                    //-----------change json address to string 
                                    $new_address = '';
                                    $json_address = json_decode($bi_address,true);
                                        
                                    if($json_address['apt_no'] != ''){
                                        $new_address.=  $json_address['apt_no'];
                                    }
                                    if($json_address['complex_name'] != ''){
                                        $new_address.= ', ' .$json_address['complex_name'];
                                    }
                                    $new_address.= ', '.$json_address['street_address'];
                                    //-----------change json address to string end
                                    $mail_info = array('orderid' => $orderid,'datetime' =>date('d-m-Y h:i:s A', $timedate),
                                        'fullname' => $bi_name,'txnid' => $txnid,'tip_amount' => $tip_amount,
                                        'bi_contact' => $bi_contact,'bi_email' => $bi_email,'bi_address' => $new_address,
                                        'subtotal' => $subtotal,'totaltax' => $totaltax,'processingfees' => $processingfees,
                                        'deliverycharge' => $deliverycharge,'finalprice' => $finalprice,'store_id' =>$storeid);
                                    //---------Send Order cancle mail------
                                    $mail_temp = $this->load->view('go2gro_web/template/ordercancel',$mail_info,true);
                                    $issendmail = $this->general->send_mail($bi_email,"Your Go2Gro order (".$orderid.") has been placed !",$mail_temp);
                                    
                                    $response["error"] = false;
                                    $response["orderid"] = $orderid;
                                    $response["isvoid"] = $isvoid;
                                    $response["message"] = "Your Order Cancel sucessfully";
                                }else {
                                    // unknown error occurred
                                    $response['error'] = true;
                                    $response['message'] = "An error occurred. Please try again";
                                }
                            } else {
                                $response['error'] = true;
                                $response['message'] = "Something went wrong in void unsetteld";
                            }
                        }
                    } else {
                        $response['error'] = true;
                        $response['message'] = 'invalid Request By User';
                    }
                }else{
                    $response_result['error'] = true;
                    $response_result['message'] = 'invalid auth key';
                }
            }else{
                $response['error'] = true;
                $response['message'] = strip_tags(validation_errors());
            }
        }else{
            $response["error"] = true;
            $response['message'] = 'Api key is misssing';
        }
        responseJSON($response);
    }

    //-----------notification------------
    public function notification(){
        $response = array("error" => true, "message" => "No Notification Found");
        $api_key  = $this->input->get_request_header('Authorization');
        if($api_key != ''){
            $userid = getuserid('users',$api_key);
            if($userid){
                $chk = $this->Mobile->getNotification($userid);
                $sent_notification_list = $this->Model->get_selected_data(array('id', 'user_id', 'title','message','created_at'),'sent_notifications',array('user_id' => $userid),$order='created_at',$type='DESC',$limit='10');
                if(count($chk) > 0){
                    $response["error"] = false;
                    foreach ($chk as $value) {
                        $notifyImage = DEFULTIMAGE;
                        $notificationid = $value['id'];
                        $orderid = $value['order_id'];
                        $userid = $value['userid'];
                        $notify_userid = $value['notificationuserid'];
                        $notificationaction = $value['action'];
                        $datetime = $value['unitime'];
                        $status = $value['status'];
                        $title = $value['title'];
                        $message = $value['message'];
                        $tag = $value['tag'];
                        $storename = $value['storename'];
                        $store_id = $value['store_id'];

                        if ($tag == "ORDER") {
                            $message = "click here to see order detail";
                            $title = "";
                            if ($notificationaction == ORDER_PLACED) {
                                $title = "#" . $orderid . " " . ORDER_PLACED_MSG;
                            } elseif ($notificationaction == ORDER_PREPARE) {
                                $title = "#" . $orderid . " " . ORDER_PREPARE_MSG;
                            } elseif ($notificationaction == ORDER_PACKED) {
                                $title = "#" . $orderid . " " . ORDER_PACKED_MSG;
                            } elseif ($notificationaction == ORDER_SHIPPED) {
                                $title = "#" . $orderid . " " . ORDER_SHIPPED_MSG;
                            } elseif ($notificationaction == ORDER_OUTFORDELIVERY) {
                                $title = "#" . $orderid . " " . ORDER_OUTFORDELIVERY_MSG;
                            } elseif ($notificationaction == ORDER_DELIVERED) {
                                $title = "#" . $orderid . " " . ORDER_DELIVERED_MSG;
                            } elseif ($notificationaction == ORDER_REJECT) {
                                $title = "#" . $orderid . " " . ORDER_REJECT_MSG;
                            } elseif ($notificationaction == ORDER_CANCLE) {
                                $title = "#" . $orderid . " " . ORDER_CANCLE_MSG;
                            } elseif ($notificationaction == ORDER_SEND_ALTERNATIVE) {
                                $title = "#" . $orderid . " " . ORDER_SEND_ALTERNATIVE_MSG;
                            }
                        }
                        $data = array("id" => $notificationid, "order_id" => $orderid, "notificationuserid" => $notify_userid, "action" => $notificationaction, "unitime" => $datetime, "title" => $title, "message" => $message, "tag" => $tag, "image" => $notifyImage, "status" => $status, "storename" => $storename, "store_id" => $store_id);
                        array_push($response["notification"], $data);
                    }
                    $response["message"] = "Notification list get sucessfuly";
                }
                if($sent_notification_list){
                    foreach ($sent_notification_list as $sent_notification) {
                        $notifyImage = DEFULTIMAGE;
                        $title = $sent_notification['title'];
                        $message = $sent_notification['message'];
                        $datetime = strtotime($sent_notification['created_at']);
                        $data = array("id" => "", "order_id" => "", "notificationuserid" => "", "action" => "", "unitime" => $datetime, "title" => $title, "message" => $message, "tag" => "", "image" => $notifyImage, "status" => "", "storename" => "", "store_id" => "");
                        array_push($response["notification"], $data);
                    }
                    $response["error"] = false;
                    $response["message"] = "Notification list get sucessfuly";
                }
            }else{
                $response_result['error'] = true;
                $response_result['message'] = 'invalid auth key';
            }
        }else{
            $response['message'] = 'Api key is misssing';
        }
        responseJSON($response);
    }

    //------------RepeatOrder-----------
    public function RepeatOrder($orderid,$store_id){
        $response = array("error" => true);
        $api_key  = $this->input->get_request_header('Authorization');
        if($api_key != ''){
            $userid = getuserid('users',$api_key);
            if($userid){
                $check_store =$this->Model->get_record('stores',array('id' => $storeid,'status' => 'active'));
                if(count($check_store) > 0){
                    $chk = $this->Model->getorderbyid($orderid, $userid,$storeid);
                    if(count($chk) > 0){
                        $order = $chk[0];
                        $cart_items = array();
                        if($order['status'] == 4){ // Only allowed for delivered orders
                            $resp = $this->Model->getcartitembyorderid($orderid,$storeid);
                            if(count($resp) > 0){
                                foreach ($resp as  $item) {
                                    $item= array_map('utf8_encode', $item);
                                    //Take only items with status 1 i.e exclude alternate or cancelled items
                                    if($item['status'] == 1) { 
                                        $c_items['item_id'] = $item['item_id'];
                                        $c_items['item_qty'] = $item['item_quty'];
                                        // Get latest/updated price and tax
                                        $item_details = $this->Model->getItem($item['item_id'],$storeid);
                                        if(count($item_details) > 0){
                                            $item_details_val = $item_details[0];
                                            $c_items['item_name'] = $item_details_val['item_name'];
                                            $c_items['item_price'] = $item_details_val['item_price'];
                                            $c_items['item_tax'] = $item_details_val['Sales_tax'];
                                            
                                        }
                                        array_push($cart_items, $c_items);
                                    }
                                }
                            }
                            if(count($cart_items) > 0){
                                $result = $this->add_items_to_cart($cart_items, $userid,$storeid);
                                if($result['status'] == 'success'){
                                    $response["error"] = false;
                                    $response["message"] = "Order repeat successful";
                                }else {
                                    $response["error"] = true;
                                    $response["message"] = $result['message'];
                                }
                            }else{
                                $response["error"] = true;
                                $response["message"] = 'No items to add in the cart';
                            }
                        }else {
                            $response["error"] = true;
                            $response["message"] = 'Only delivered orders can be repeated';
                        }
                    }else{
                        $response["message"] = "No Order Found";
                    }
                }else{
                    $response["error"] = true;
                    $response["message"] = "Invalid Store";
                }
            }else{
                $response_result['message'] = 'invalid auth key';
            }
        }else{
            $response['message'] = 'Api key is misssing';
        }
        responseJSON($response);
    }

    //-----------add_items_to_cart-----------------
    private function add_items_to_cart($cart_items, $userid, $storeid){
        $invalid_items = $out_of_limit_items = $db_error_items = [];
        $result = ['status'=>'failure','message'=>'No cart items to be added'];

        $condition = "`cat_name` In('Beers','Wines','Wine') AND `store_id`='".$storeid."'";
        $categories_with_limit = $this->Model->get_selected_data('id','category',$condition);
        $flag = true;
        $itemlink_table =  STORE_PREFIX.$storeid.'_'.ITEMLINK_TABLE;
        $cat_id = $this->Model->gettwodata('s.cat_id',"`".$itemlink_table."` as il","subcategory as s",'il.subcat_id = s.sub_id',$where=array('il.item_id' => $item_id));
        $category_array = array();
        if(isset($categories_with_limit) && count($categories_with_limit) > 0 ){
            for($i = 0; $i < count($categories_with_limit); $i++){
                array_push($category_array,$categories_with_limit[$i]['id']);
            }
        }

        foreach($cart_items as $item) {
            //-----------check item in store-------- 
            $check_item_in_store = $this->Cart_model->checkitemisexistinstore($item['item_id'],$storeid);
            if($check_item_in_store){
                //----------get_item_category----------------
                $itemlink_table =  STORE_PREFIX.$storeid.'_'.ITEMLINK_TABLE;
                $cat_id = $this->Model->gettwodata('s.cat_id',"`".$itemlink_table."` as il","subcategory as s",'il.subcat_id = s.sub_id',$where=array('il.item_id' => $item['item_id']));
                $cat_id = $cat_id[0]['cat_id'];
                $flag = true;
                $checkitemisexist =  $this->Model->get_record('cart_item',array('item_id' =>$item['item_id'],'user_id' => $userid,'status'=> 0,'store_id' => $storeid));
                if(count($checkitemisexist) > 0 ){
                    $cart_item = $this->Model->getcartitembyid($userid, $item['item_id'],$storeid);
                    $cart_item_ = $cart_item[0];
                    $qty_already_in_cart = $cart_item_['item_quty'];
                    $qty_to_update = $qty_already_in_cart + $item['item_qty'];
                }
                if(in_array($cat_id, $categories_with_limit)) {
                    if($item_already_in_cart) {
                        $action = 'updateitem';
                        $qty = $qty_to_update;
                    } else {
                        $action = 'additem';
                        $qty = $item['item_qty'];
                    }

                    if (!is_item_under_purchase_limit($item['item_id'], $qty, $userid, $action,$storeid)) {
                        $flag = false;
                    }
                    if (!is_item_beers_under_purchase_limit($item['item_id'], $qty, $userid, $action,$storeid)) {
                       $flag = false;
                    }
                }
                if ($flag) {
                    if($item_already_in_cart){
                        $item_updated = $this->Model->update('cart_item', array('item_quty'=> $qty_to_update),array('item_id'=>$item['item_id'],'user_id' =>$userid,'status' =>0 ,'store_id' => $storeid));
                        if(!$item_updated){
                            array_push($db_error_items, $item['item_name']);
                        }
                    }else {
                        $item_inserted = $this->Model->add('cart_item',array('item_id'=>$item['item_id'],'item_quty'=>$item['item_qty'],'user_id'=>$userid,'price'=>$item['item_price'],'tax'=>$item['item_tax'],'store_id'=>$store_id));
                        if (!$item_inserted) {
                            array_push($db_error_items, $item['item_name']);
                        }
                    }
                } else {
                    array_push($out_of_limit_items, $item['item_name']);
                }
            }else{
                array_push($invalid_items, $item['item_name']);
            }
        }

        if(count($db_error_items)>0 || count($out_of_limit_items)>0 || count($invalid_items)>0){
            $result = ['status'=>'failure','message'=>'Certain Items couldn\'t be added to cart. Reason : '];
            if(count($db_error_items)>0){
                $result['message'] .= implode(', ',$db_error_items).' caused error while adding.';
            }
            if(count($out_of_limit_items)>0){
                $result['message'] .= implode(', ',$out_of_limit_items).' exceeded the purchase limit.';
            }
            if(count($invalid_items)>0){
                $result['message'] .= implode(', ',$invalid_items).' are invalid items.';
            }
        } else {
            $result['status'] = 'success';
        }
        return $result;
    }

    //------------check_promocode_applicable--------
    /*public function check_promocode_applicable(){
        $response = array("error" => true);
        $api_key  = $this->input->get_request_header('Authorization');
        if($api_key != ''){
            $this->form_validation->set_rules('orderid','orderid','required');
            $this->form_validation->set_rules('store_id','store_id','required');
            if($this->form_validation->run() == true){
                $userid = getuserid('users',$api_key);
                if($userid){
                    $check_store =$this->Model->get_record('stores',array('id' => $storeid,'status' => 'active'));
                    if(count($check_store) > 0) {

                    }else{
                        $response['error'] = true;
                        $response['message'] = strip_tags(validation_errors());
                    }
                }
            }else{
                $response['error'] = true;
                $response['message'] = strip_tags(validation_errors());
            }
        }else{
        
        }
    }*/

    //-------------------get_membership_plan---------------
    public function get_membership_plan(){
        $response = array("error" => true);
        $api_key  = $this->input->get_request_header('Authorization');
        if($api_key != ''){
            $userid = getuserid('users',$api_key);
            $response["membership_plan"] = array();
            $user_detail = $this->Model->get_record('users',array('id' => $userid ));
            $memshipplan_data= $this->Model->get_selected_data(array(`id`,`plan_name`,`price`,`duration`,`description`,`create_at`),'membership_plan',array('status'=>'active','del_status'=>0));
            $response["error"] = false;
            if (count($memshipplan_data) > 0) {
                foreach ($memshipplan_data as $task) {
                    array_push($response["membership_plan"], $task);
                }
            }else{
                $response["error"] = true;
                $response['message'] = "Membership Plan not avaliable";
            }
            if($user_detail[0]['membership_plan_id'] > 0 ){
                $check_expire = json_decode($user_detail[0]['membership_date']);
                $current_time = date("Y-m-d H:i:s",time()); // Getting Current Date & Time
                $current_timestamp = strtotime($current_time); 
                if($check_expire->expire >= $current_timestamp){
                    $response["planid"] = $user_detail[0]['membership_plan_id'];
                    $response["status"] = "Valid";
                    $response['message'] = "Currently Active membership plan";
                }else{
                     $response["plan_id"] = 0;
                    $response["status"] = "Invalid";
                    $response['message'] = "Your membership plan is expire";
                }
            }else{
                $response["plan_id"] = 0;
                $response["status"] = "Available";
            }
        }else{
            $response['message'] = 'Api key is misssing';
        }
        responseJSON($response);
    }

     //---------refer to friend api-------------------//
    public function refer_to_friend()
    {
        $response = array();
        $api_key  = $this->input->get_request_header('Authorization');
        if($api_key != ''){
            $userid = getuserid('users',$api_key);
            $user_details = $this->Model->get_record('users',array('id' => $this->userid));
            $user_details = $user_details[0];
            $full_name = ucwords(trim($user_details['first_name'])).' '.ucwords(trim($user_details['last_name']));
            $referral_code = $user_details['referral_code'];
            $referral_discount = REFERRAL_DISCOUNT;
            $offers_redeemed =$this->Model->check_referrals_earned($user_id);
            $response["error"] = false;
            if($offers_redeemed > 0){
                $response['title_message'] = "You have earned $offers_redeemed rewards.";
            } else {
                $response['title_message'] = "You are yet to earn your reward.";
            }
            $response["share_message"] = "$full_name has sent you the link to Go2Gro.\nUse this Referral Code $referral_code to get $$referral_discount off on your first order.\nPlaystore - https://bit.ly/2Lh3fjG\nAppstore - https://apple.co/2EtU7b5 \nWeb - https://www.go2gro.com";
        }else{
            $response['message'] = 'Api key is misssing';
        }
        responseJSON($response);
    }
    //---------------Membership payment----------
    public function insertmembership_paymentinfo(){
        $response_result = array("Error code"=> "","Error message"=>"");
        $api_key  = $this->input->get_request_header('Authorization');
        if($api_key != ''){
            $this->form_validation->set_rules('authCode','Auth key','required');
            $this->form_validation->set_rules('transactionId','paymentpayprofileid','required');
            $this->form_validation->set_rules('description','profileid','required');
            $this->form_validation->set_rules('selected_planId','selected plan','required');
            if($this->form_validation->run() == TRUE){
                $authCode=$this->input->post('authCode');
                $transactionId=$this->input->post('transactionId');
                $paymentResponse=$this->input->post('description');
                $responseCode=$this->input->post('responseCode');
                $selected_planid = $this->input->post('selected_planId');
                $responseText = array("1"=>"Approved", "2"=>"Declined", "3"=>"Error", "4"=>"Held for Review");
                $paymentStatus = "";
                $auto_renew_membership_status = "NA";
                $auto_renew_membership_datetime = "0000-00-00 00:00:00";
                if(isset($responseText[$responseCode])){
                    $paymentStatus = $responseText[$responseCode];
                }
                $userid = getuserid('users',$auth);
                if($userid){
                    try{
                        $this->Model->transstart();
                        $user_detail  = $this->Model->get_record('users',array('id' => $id));
                        $user_detail = $user_detail[0];
                        //----------getmembershipplanbyid
                        $plan_details = $this->Model->get_selected_data(array('id','plan_name','price','duration','description','status','create_at'),'membership_plan',array('id' => $selected_planid));
                        if(count($plan_details) > 0){
                            $plan_details = $plan_details[0];
                            $amount =  $plan_details['price'];
                            $duration =  $plan_details['duration'];
                            $plan_name =  $plan_details['plan_name'];

                            $plan_info = array('planid' => $selected_planid,'amount' => $amount, 'duration' =>$duration,'plan_name'=>$plan_name);
                            $plan_info_json = json_encode($plan_info);
                            //-------------Check user exits membership or not
                            if($user_detail['membership_plan_id'] > 0 ){ 
                                $check_expire = json_decode($user_detail['membership_date']);
                                $current_time = date("Y-m-d H:i:s",time()); // Getting Current Date & Time
                                $current_timestamp = strtotime($current_time); 
                                if($check_expire->expire >= $current_timestamp){
                                    $response["error"] = true;
                                    $response["message"] = "You have already take a membership";
                                }else{
                                    if($responseCode == "1"){
                                        //----------get membership expire date----------
                                        $current_time = date("Y-m-d H:i:s",time()); // Getting Current Date & Time
                                        $current_timestamp = strtotime($current_time); 
                                        $expire_timestamp = strtotime("+".(int)$duration." month");  // Getting timestamp of 1 month from now
                                        $final_expire = date("Y-m-d H:i:s",+$expire_timestamp);
                                        $final_expiretimestamp = strtotime($final_expire);
                                        //{"creation":1545650514,"expire":1548328914};
                                        $user_update_data = array("creation" =>$current_timestamp, "expire" =>$final_expiretimestamp);
                                        $user_update_jsondata = json_encode($user_update_data);
                                        $authorizenet_payment_data = array('user_id' => $userid,'plan_info' => $plan_info_json,
                                            'transaction_id' => $transactionId,'auth_code'=> $authCode,'response_code' => $responseCode,
                                            'amount' => $amount,'payment_status' => $paymentStatus,
                                            'payment_response' => $paymentResponse,'create_at' => date('Y-m-d H:i:s'));
                                        
                                        $authorizenet_payment_add = $this->Model->add('tbl_authorizenet_payment',$authorizenet_payment_data);
                                        if($authorizenet_payment_add){
                                            $user_rec_update = $this->Model->update('users',array('membership_plan_id' =>$selected_planid,
                                            'membership_date'=> $user_update_jsondata,'auto_renew_membership_status' => "NA",
                                            'auto_renew_membership_datetime'=> "0000-00-00 00:00:00"),array('id' => $userid));
                                            if($user_rec_update){
                                                $plan_take_user = "Monthly";
                                                if($duration == 12){
                                                    $plan_take_user ="Yearly";
                                                }
                                                $uemail = $user_detail['email_id'];
                                                $umobile = $user_detail['mobile'];
                                                $uname = $user_detail['first_name'].' '.$user_detail[0]['last_name'];
                                                
                                                $data['message'] = "Dear Go2Gro Costomer,<br/> Your ".$plan_take_user." Membership plan has been successfully created.<br/><br/>Thank you for choosing Go2Gro as your delivery service. We deeply value your business.<br/><br/>Regards,<br/> Go2Gro Customer Support";
                                                $sms_message = "Dear Go2Gro Costomer,\n Your ".$plan_take_user." Membership plan has been successfully created.\nThank you for choosing Go2Gro as your delivery service. We deeply value your business.\nRegards,\n Go2Gro Customer Support";
                                                $sms_message = urlencode($sms_message);

                                                $mail_msg = $this->load->view('go2gro_web/template/membership',$data,true);
                                                $issendmail = $this->general->send_mail($uemail,$uname . ' membership information',$mail_msg);
                                                $issendsms = $this->general->sendsms($umobile, $sms_message);
                                                $response["ismailsend"] = "Mail Not send";
                                                $response["issmssend"] = "SMS Not send";
                                                if ($issendmail) {
                                                    $response["ismailsend"] = "Mail send sucessfully";
                                                }
                                                if ($issendsms) {
                                                    $response["issmssend"] = "SMS send sucessfully";
                                                }
                                                $response["error"] = false;
                                                $response["message"] = "You have join membership successfully\n".$paymentResponse;
                                            }
                                        }else{
                                            $response["error"] = true;
                                            $response["message"] = "Something wrong when data update";
                                        }
                                    }else{
                                        $user_update_jsondata = "";
                                        $authorizenet_payment_data = array('user_id' => $userid,'plan_info' => $plan_info_json,
                                            'transaction_id' => $transactionId,'auth_code'=> $authCode,'response_code' => $responseCode,
                                            'amount' => $amount,'payment_status' => $paymentStatus,
                                            'payment_response' => $paymentResponse,'create_at' => date('Y-m-d H:i:s'));
                                        $authorizenet_payment_add = $this->Model->add('tbl_authorizenet_payment',$authorizenet_payment_data);
                                        if($authorizenet_payment_add){
                                            $user_rec_update = $this->Model->update('users',array('membership_plan_id' =>$selected_planid,'membership_date'=> $user_update_jsondata,'auto_renew_membership_status' => "NA",'auto_renew_membership_datetime'=> "0000-00-00 00:00:00"),array('id' => $userid));
                                            $response["error"] = true;
                                            $response["message"] = "Credit Card ERROR :  Invalid respons";
                                        }else{
                                            $response["error"] = true;
                                            $response["message"] = "Something wrong";
                                        }
                                    }
                                }
                            }else{ //---------------First time membership create-----
                                if($responseCode == "1"){
                                    //----------get membership expire date----------
                                    $current_time = date("Y-m-d H:i:s",time()); // Getting Current Date & Time
                                    $current_timestamp = strtotime($current_time); 
                                    $expire_timestamp = strtotime("+".(int)$duration." month");  // Getting timestamp of 1 month from now
                                    $final_expire = date("Y-m-d H:i:s",+$expire_timestamp);
                                    $final_expiretimestamp = strtotime($final_expire);
                                    //{"creation":1545650514,"expire":1548328914};
                                    $user_update_data = array("creation" =>$current_timestamp, "expire" =>$final_expiretimestamp);
                                    $user_update_jsondata = json_encode($user_update_data);
                                    $authorizenet_payment_data = array('user_id' => $userid,'plan_info' => $plan_info_json,
                                        'transaction_id' => $transactionId,'auth_code'=> $authCode,'response_code' => $responseCode,
                                        'amount' => $amount,'payment_status' => $paymentStatus,
                                        'payment_response' => $paymentResponse,'create_at' => date('Y-m-d H:i:s'));
                                    
                                    $authorizenet_payment_add = $this->Model->add('tbl_authorizenet_payment',$authorizenet_payment_data);
                                    if($authorizenet_payment_add){
                                        $user_rec_update = $this->Model->update('users',array('membership_plan_id' =>$selected_planid,
                                        'membership_date'=> $user_update_jsondata,'auto_renew_membership_status' => "NA",
                                        'auto_renew_membership_datetime'=> "0000-00-00 00:00:00"),array('id' => $userid));
                                        if($user_rec_update){
                                            $plan_take_user = "Monthly";
                                            if($duration == 12){
                                                $plan_take_user ="Yearly";
                                            }
                                            $uemail = $user_detail['email_id'];
                                            $umobile = $user_detail['mobile'];
                                            $uname = $user_detail['first_name'].' '.$user_detail[0]['last_name'];
                                            
                                            $data['message'] = "Dear Go2Gro Costomer,<br/> Your ".$plan_take_user." Membership plan has been successfully created.<br/><br/>Thank you for choosing Go2Gro as your delivery service. We deeply value your business.<br/><br/>Regards,<br/> Go2Gro Customer Support";
                                            $sms_message = "Dear Go2Gro Costomer,\n Your ".$plan_take_user." Membership plan has been successfully created.\nThank you for choosing Go2Gro as your delivery service. We deeply value your business.\nRegards,\n Go2Gro Customer Support";
                                            $sms_message = urlencode($sms_message);

                                            $mail_msg = $this->load->view('go2gro_web/template/membership',$data,true);
                                            $issendmail = $this->general->send_mail($uemail,$uname . ' membership information',$mail_msg);
                                            $issendsms = $this->general->sendsms($umobile, $sms_message);
                                            $response_result["ismailsend"] = "Mail Not send";
                                            $response_result["issmssend"] = "SMS Not send";
                                            if ($issendmail) {
                                                $response_result["ismailsend"] = "Mail send sucessfully";
                                            }
                                            if ($issendsms) {
                                                $response_result["issmssend"] = "SMS send sucessfully";
                                            }
                                            $response_result["error"] = false;
                                            $response_result["message"] = "You have join membership successfully\n".$paymentResponse;
                                        }
                                    }else{
                                        $response["error"] = true;
                                        $response["message"] = "Something wrong when data update";
                                    }
                                }else{
                                    $user_update_jsondata = "";
                                    $authorizenet_payment_data = array('user_id' => $userid,'plan_info' => $plan_info_json,
                                        'transaction_id' => $transactionId,'auth_code'=> $authCode,'response_code' => $responseCode,
                                        'amount' => $amount,'payment_status' => $paymentStatus,
                                        'payment_response' => $paymentResponse,'create_at' => date('Y-m-d H:i:s'));
                                    $authorizenet_payment_add = $this->Model->add('tbl_authorizenet_payment',$authorizenet_payment_data);
                                    if($authorizenet_payment_add){
                                        $user_rec_update = $this->Model->update('users',array('membership_plan_id' =>$selected_planid,'membership_date'=> $user_update_jsondata,'auto_renew_membership_status' => "NA",'auto_renew_membership_datetime'=> "0000-00-00 00:00:00"),array('id' => $userid));
                                        $response["error"] = true;
                                        $response["message"] = "Credit Card ERROR :  Invalid respons";
                                    }else{
                                        $response["error"] = true;
                                        $response["message"] = "Something wrong";
                                    }
                                }
                            }
                        }
                        $this->Model->transcomplete();
                    }catch (customException $e){
                        $this->Model->transrollback();
                        $response["error"] = true;
                        $response["message"] = $e->errorMessage();
                    }
                }else{
                    $response['error'] = true;
                    $response['message'] = 'invalid auth key';
                }
            }else{
                $response["error"] = true;
                $response["message"] = strip_tags(validation_errors());
            }
        }else{
            $response['message'] = 'Api key is misssing';
        }
        responseJSON($response);
    }

    public function test()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, authKey");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        $result=$this->Model->getlist();
        $results=$this->Model->getlist_itemName();
        $fileresults=$this->Model->file_name();
        $response = array();
        if ($result) {
            $response['error']= false;
            $response['data']= $result;
            $response['item_name']= $results;
            $response['fileresults']= $fileresults;
            $response['message']= 'Data get successfully';
        }else{
             $response['error']= true;
             $response['message'] = 'Data Don’t get';
        }
        
        responseJSON($response);
    }
    public function gettbldata()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, authKey");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        $id=$this->input->get('id');
        $data=array('id'=>$id);
        //print_r($data);die();
        $result=$this->Model->get_record('tbljson',$data);
        //print_r($result);die;
        $response = array();
        if ($result) {
            $response['error']= false;
            $response['datatbl']= $result;
           
            $response['message']= 'Data get successfully';
        }else{
             $response['error']= true;
             $response['message'] = 'Data Don’t get';
        }
        
        responseJSON($response);
    }
    public function testinput()
    {
       $name= $this->input->post('inputValue');
        
       $lname= $this->input->post('data');
       $key= $this->input->post('key');
        
       //print_r($lname);die;
        $response = array();
        $tbldata= array('filename'=>$name,'datajtbl'=>$lname,'key'=>$key);
        $this->Model->addjsondata('tbljson',$tbldata);
       $data=array('Item_Master_Name'=>$name);
       $data_itemname=array('item_name'=>$lname);

      $result= $this->Model->getallitem('test',$data);
      // if ($result=='') {
      //    $insertallitem =$this->Model->insertallitem($data);
      // }
      //print_r($insertallitem);die;
      $resultitemname= $this->Model->getitem_name('item_name',$data_itemname);
     
      if ($result==true) {
        $response['error']=true;
        //$response['message']= "Item Master Name duplicate please check !";
        
      }elseif ($resultitemname==true) {

          $response['error']=true;
       // $response['message']= "Item Name duplicate please check !";
      }else{
             //$insertdataitems= $this->Model->insertitems($data);
             //$insertdataitem_name= $this->Model->insertitemname($data_itemname);
           
                $response['error']= false;
            $response['message']= 'Data insert successfully';
           /* }else{
                $response['error']=true;
                $response['message']= "Data don't insert successfully";
            }  */ 
      }
     // print_r($result);die;
     // $newArray = array();
     //  foreach ($result as $key => $valuek) {
     // array_push($newArray, $valuek);

     //   }
     //  // print_r($newArray);die;
     //   $aTmp2 = array();
     //  foreach ($name as $key => $value) {
     //    array_push($aTmp2, $value);
     //     //   $aTmp2[] = $value;
     //   }
     //  // print_r($aTmp2);
     //  // print_r($newArray);die;
     //  // $results=array_diff($aTmp2,$newArray);
     //   //var_dump($results);
     //   //print_r($results);die;
     //   foreach ($results as $key => $data) {
     //    $dataresult= array('name' =>$data,'last_name'=>'');
     //       $this->Model->insertresult($dataresult);
     //   }
        
        /*if ($dataresult) {
            $response['error']= false;
            $response['message']= 'Data get successfully';
        }else{
             $response['error']= true;
             $response['message'] = 'Data Don’t get';
        }*/
        
        responseJSON($response);
    }

    public function autocompleteinsert(){
        $Item_Master_Name=$this->input->post('Item_Master_Name');
        $item_name=$this->input->post('item_name');
        $datamaster_name= array('Item_Master_Name'=>$Item_Master_Name);
        $dataitem_name= array('item_name'=>$item_name);
        $save= 'true';
        $result= $this->Model->getallitem('test',$datamaster_name);
          if ($result=='') {
             $insertallitem =$this->Model->insertallitem($datamaster_name);
          }
        $resultitemname= $this->Model->getitem_name('item_name',$dataitem_name);  
        if ($resultitemname=='') {
             $insertallitemame =$this->Model->insertallitemname($dataitem_name);
          }
          $response = array();
        if ($save=='true' ) {
            $response['error']= false;
            $response['message']= 'Data save successfully';
        }else{
             $response['error']= true;
             $response['message'] = 'Data Don’t save get';
        }  
         responseJSON($response);
    }
    public function exceldata(){
         $result=$this->Model->getlist();
         $results=$this->Model->getlist_itemName();
         //print_r($result);

      //   print_r($results);die;
         if ($result) {

            $filename = "Membership Users list.xls";
            header("Content-Disposition: attachment; filename=\"$filename\"");
            header("Content-Type: application/vnd.ms-excel");
        
            echo '<table class="table" border="1">
                        <thead">
                            <tr>
                            <th>Item Master Id </th>
                                <th>Item Master Name</th>
                                <th>Description</th>
                                <th>Item Master Category</th>
                                <th>Item Master Sub Category</th>
                                <th>Is Active</th>
                                <th>Is Safe Recommended</th>
                                <th>Best Seller</th>
                                <th>Today Special</th>
                                <th>Food Type(Veg,Non-Veg,Egg)</th>
                                <th>Property Name</th>
                                <th>Kitchen Name</th>
                                <th>Split Bill Item Category</th>
                                <th>HSN Code</th>
                                <th>Tax On Item ID</th>
                                <th>Item Id</th>
                                <th>Item Name</th>
                                <th>Item Price</th>
                                <th>Item Code</th>
                                <th>Is Active</th>
                                <th>Split</th>
                                <th>Tag</th>
                                <th>Description</th>
                                <th>Alternative Name</th>
                                <th>Property Name</th>
                                <th>Quantum Value</th>
                                <th>Quantum Unit</th>
                                <th>Nutrition Value</th>
                                <th>is Home Delivery</th>
                                <th>Is Take Away</th>
                                <th>Is Dinning</th>
                                <th>Unit</th>
                                <th>Tax On Total Applicable</th>
                                <th>Is Discount</th>
                                <th>Discount Type</th>
                                <th>Discount Rate</th>
                            </tr>
                        </thead>
                        <tbody>';
                        $i = 0;

                        foreach ($result as $value  ) { 
                          
                       
                            echo '<tr>
                                <td></td>
                                <td>'.$value['Item_Master_Name'].'</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>'.$results[$i]['item_name'].'</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                
                            </tr>';
                              ++$i;
                        }
                echo '</tbody>';
                echo '</table>';
             # code...
         }else{
            header("Content-type: text/plain");
            header("Content-Disposition: attachment; filename=savethis.txt");
            // do your Db stuff here to get the content into $content
            print "Oops Something worng...\n";
        }
    }
}
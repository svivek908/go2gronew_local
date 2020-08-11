<?php

defined('BASEPATH') OR exit('No direct script access allowed');

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
        $this->load->model('Mobileapi','Mobile');
    }

    private function isValidApiKey($api_key){
    	$this->userid = getuserid('users',$api_key);
    	return $this->userid;
    }

	//-----------pincode---------
	public function pincode($zipcode)
    {
    	$response = array();
        if(getpinExist($zipcode)){
            $response["error"] = false;
            $response['stores'] = $this->Model->get_stores_by_zipcode($zipcode)
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
                        $issendmail = send_mail($email,$subject,$mail_msg);
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
                        $issendsms = sendsms($mobile, $message);
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
                        $issendwelcomesms = sendsms($mobile, $welmessage);
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
		        } else if ($devicetype == "ios") 
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
	    }else{
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
        			$useroldpass = $this->Model->get_selected_data('password','users',array('id' => $this->userid))
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
                    $issendmail = send_mail($emailid,'Password Reset Link',$html);
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
                        $issendmail = send_mail($emailid,'Password Reset Link',$html);
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
                $auth = $this->input->post('Authorization');
                $userid = $this->session->userdata["go2grouser"]['id'];
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
            
            if($this->form_validation->run() == true){
                $storeid = get_selected_storeid();
                $userid = $this->session->userdata["go2grouser"]['id'];
                $auth1=$this->input->post('Authorization');
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
                                                    }
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
                                        $ismailsend = send_mail($bil_email,"Your Go2Gro order (".$orderid.") has been placed !",$mail_temp);
                                        //---------Send sms------
                                        $msg = "Dear " . $bil_firstname . " " . $bil_lastname . ", \n
                                            Thank you for using Go2Gro!\n
                                            Your Order id is " . $orderid . " \n
                                            Our dedicated team prepares your order as soon as you hit checkout, and bring it to your
                                            doorstep with a smile on our face. Your order will be with you shortly. We will contact you,
                                            if there are any changes in your order.";
                                        $sendsms = sendsms($bil_contact,$msg);
                                        //-----------Send notification-----------
                                        notification($userid,$orderid,ORDER_PLACED,date('Y-m-d H:i:s'),$timedate,ORDER_TAG,"NA", "NA");
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
}
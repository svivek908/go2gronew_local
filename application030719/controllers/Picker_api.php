<?php  
defined('BASEPATH') OR exit('No direct script access allowed');
class Picker_api extends CI_Controller {
    private $pickerid;
    public function __construct()
    {
        parent::__construct();
        $api_key  = $this->input->get_request_header('Authorization');
        if(isset($api_key) && $api_key!=''){
          if(!$this->isValidApiKey($api_key)){
            responseJSON(array("error" =>true, "message" => "Access Denied. Invalid Api key"));
            return false;
          }
        }
        $this->load->model('Picker_model');
        $this->load->helper('sms');
    }

    private function isValidApiKey($api_key){
      $this->pickerid = getuserid('pd_person',$api_key);
      return $this->pickerid;
    } 

    //-----------Registration------------
    public function registration()
    {
        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[pd_person.email]',
        array(
                'required'      => 'You have not provided %s.',
                'is_unique'     => 'This %s already exists.'
        ));
        $this->form_validation->set_rules('mobileno', 'Mobile', 'required|is_unique[pd_person.mobile_no]',
        array(
                'required'      => 'You have not provided %s.',
                'is_unique'     => 'This %s already exists.'
        ));
         if($this->form_validation->run()== true){
          $uuid = $this->db->set('id', 'UUID()', false);
		    $name = $this->input->post('name');
		    $email = $this->input->post('email');
		    $password = $this->input->post('password');
       	$mobileno = $this->input->post('mobileno');
       	$image = $_FILES['image']['name'];
		    $role = "picker_delivery";
		    $uploaddir = "./public/upload/pickeruserimage/";
        $imagename = "Picker_" . time() . 'image';
        $uploadpath = $uploaddir . $imagename;
        //var_dump($imagename);die();
		    $allowed_types = 'gif|jpg|png';
        $otp = rand(100000, 999999);
        $message = $otp . " is your one time password for verification.";
        $userdata =array('name'=>$name,'email'=>$email,'password'=>getHashedPassword($password),'mobile_no'=>$mobileno,'image'=>$uploadpath);
        
        if ($userdata) {
          //$userdata =array('id'=>$uuid,'name'=>$name,'email'=>$email,'password'=>getHashedPassword($password),'mobile_no'=>$mobileno);
         // var_dump($userdata);die();
          $imageresult = do_file_upload('image',$uploaddir,$allowed_types);
          if ($imageresult) {
            if ($this->Model->create_user('pd_person',$userdata)) {
                    if (sendsms($mobileno, $message)) {
                    $userDetail = $this->Model->get_record('pd_person',array('mobile_no'=>$mobileno));
                    $response["error"] = false;
                    $response["message"] = "User Successfully Registered";
                    $response["userDetail"] = $userDetail;
                    $response["otp"] = $otp;
                    $response["hellosign_agreement"] = "https://portal.helloworks.com/link/LpjxuIHBEU4sKzB8";
                    $response["picker_policy"] = BASEURL."driver_policy/".$userDetail[0]['id'];
                    $response["agreement_url"] = BASEURL."driver_contract/".$userDetail[0]['id'];
                    } else {
                        $response["error"] = true;
                        $response["message"] = "OTP not send successfully";
                    }
            } else {
                    $response["error"] = true;
                    $response["message"] = "Processing Error. Please Try Again";
            }
            # code...
          }
        } else {
          # code...
        }
         }else{
            $response["error"] = true;
            $response['message'] = strip_tags(validation_errors());
         }
         responseJSON($response);
    }

    //----------login----------
    public function login()
    {
    	 $username = $this->input->post('username');
        $password = getHashedPassword($this->input->post('password'));
        $response = array();
        if ($this->Picker_model->isuservalid($username,$password)) {
        $getuserdata = $this->Model->get_record('pd_person',array('mobile_no'=>$username));

       if ($getuserdata['ic_agreement'] == 0) {
          $response["error"] = true;
          $response["message"] = "ic_agreement !";
       } elseif ($getuserdata['sa_policy'] == 0) {
           $response["error"] = true;
           $response["message"] = "sa_policy !";
       } elseif ($getuserdata['taxpayer_i_c_policy'] == 0) {
            $response["error"] = true;
            $response["message"] = "taxpayer_i_c_policy !";
       } elseif ($getuserdata['is_active'] == deactivate) {
           $response["error"] = true;
           $response["message"] = "is_active !";
       }else{
       $response["error"] = false;
       $response["message"] = "Login  Successfully !";
       $response["data"] = $getuserdata;
       }
        }else{
           $response["error"] = true;
           $response["message"] = "Login don't Successfully !";
        }
         responseJSON( $response);

    }

    /**************updatePolicyWorkStatus**********************/
    public function updatePolicyWorkStatus()
    {
      $this->form_validation->set_rules('tag', 'Tag', 'required');
        $this->form_validation->set_rules('status', 'Status', 'required');
        $response = array();
         if($this->form_validation->run()== true){
          $userid = $this->input->get('userid');
          $status = $this->input->post('status');
          $tag = $this->input->post('tag');
          var_dump($status);exit;
          if ($tag == 'ic_agreement') {
            if ($this->Model->update('pd_person',array('ic_agreement' => $status),array('id' => $userid))) {
              $userDetail = $this->Model->get_record('pd_person',array('id' => $userid));
              $userDetail['profile_url']= IMAGE_SHOWURL.$userDetail[0]['image'];
              $response["error"] = false;
              $response["message"] = "Independent Contractor Policy Updated Successfully";
              $response["userDetail"] = $userDetail;
            } else {
                $response["error"] = true;
                $response["message"] = "Status not updated";
            }
            } else if ($tag == 'sa_policy') {

                if ($this->Model->update('pd_person',array('sa_policy' => $status),array('id' => $userid))) {
                    $userDetail = $this->Model->get_record('pd_person',array('id' => $userid));
                    $response["error"] = false;
                    $response["message"] = "Shoppers Application Policy Updated Successfully";
                    $response["userDetail"] = $userDetail;
                } else {
                    $response["error"] = true;
                    $response["message"] = "Status not updated";
                }
                }else if ($tag == 'at_work') {
                    if ($this->Model->update('pd_person',array('at_work' => $status),array('id' => $userid))) {
                        $userDetail = $this->Model->get_record('pd_person',array('id' => $userid));
                        $response["error"] = false;
                        $response["message"] = "Work Status Updated Successfully";
                        $response["userDetail"] = $userDetail;
                    } else {
                        $response["error"] = true;
                        $response["message"] = "Status not updated";
                    }
          } else {
              $response["error"] = true;
              $response["message"] = "Tag is invalid";
          }
          }else{
            $response["error"] = true;
            $response['message'] = strip_tags(validation_errors());
         }
         responseJSON($response);
    }
    /*******getUserDetail****************/
    public function getUserDetail()
    {
      $response = array();
      $userid = $this->input->get('userid');
      $device_type = $this->input->get('device_type');
      $device_token = $this->input->get('device_token');
      $userDetail = $this->Model->get_record('pd_person',array('id' => $userid));
      $userDetail['profile_url']= IMAGE_SHOWURL.$userDetail[0]['image'];
      $this->Picker_model->insert_gcm_data_for_picker($device_token,$device_type,$userid,time())
      $available_orders = 0;
      if ($userDetail) {
        $orders = $this->Model->get_selected_data('order_id','order_table',array('status' => 0,`dlv_date`= CURDATE()));
        if ($orders) { 
            if (count($orders) > 0) {
                for ($i = 0; $i < $orders->num_rows; $i++) {
                    $result = $orders;
                    $ordertime = $result[0]['datetime'];
                    $currenttime = time();
                    
                    $minutes = 10;
                    if (($currenttime - $ordertime) > ($minutes * 60)) {
                        $available_orders++;
                    }
                }
            }
        }
        $response["error"] = false;
        $response["message"] = "User detail get successfully";
        $response["Availbale_order"] = $available_orders;
        $response["userDatail"] = $userDetail;
      } else {
          $response["error"] = true;
          $response["message"] = "User detail not found";
      }
      echoRespnse(200, $response);
    }
}
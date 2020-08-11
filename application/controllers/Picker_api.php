<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class Picker_api extends CI_Controller
{
    private $picker_id;
    public function __construct() 
    {
        parent::__construct();
		$this->load->library('payment');
        $api_key = $this->input->get_request_header('Authorization');
        if (isset($api_key) && $api_key != '') {
            if (!$this->isValidApiKey($api_key)) {
                responseJSON(array(
                    "error" => true,
                    "message" => "Access Denied. Invalid Api key"
                ));
                return false;
            }
        }
        $this->load->model('Picker_model');
        $this->load->helper('sms');
    }
    
    private function isValidApiKey($api_key)
    {
        $this->pickerid = getuserid('pd_person', $api_key);
        return $this->pickerid;
    }
    
    function getPickerStatusMsg($pickerStatus)
    {
        if ($pickerStatus == 1) {
            $pickerStatusMsg = "You have already selected order. Please complete your order.";
        } else if ($pickerStatus == 2) {
            $pickerStatusMsg = "You have started shopping.";
        } else if ($pickerStatus == 3) {
            $pickerStatusMsg = "You have completed shopping. Please deliver order.";
        } else if ($pickerStatus == 0) {
            $pickerStatusMsg = "No order selected";
        } else {
            $pickerStatusMsg = "No status found";
        }
        return $pickerStatusMsg;
    }
    //-----------Registration------------
    public function registration()
    {
        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[pd_person.email]', array(
            'required' => 'You have not provided %s.',
            'is_unique' => 'This %s already exists.'
        ));
        $this->form_validation->set_rules('mobileno', 'Mobile', 'required|is_unique[pd_person.mobile_no]', array(
            'required' => 'You have not provided %s.',
            'is_unique' => 'This %s already exists.'
        ));
        if ($this->form_validation->run() == true) {
            $uuid          = $this->db->set('id', 'UUID()', false);
            $name          = $this->input->post('name');
            $email         = $this->input->post('email');
            $password      = $this->input->post('password');
            $mobileno      = $this->input->post('mobileno');
            $image         = $_FILES['image']['name'];
            $role          = "picker_delivery";
            $uploaddir     = "./public/upload/pickeruserimage/";
            $imagename     = "Picker_" . time() . 'image';
            $uploadpath    = $uploaddir . $imagename;
            //var_dump($imagename);die();
            $allowed_types = 'gif|jpg|png';
            $otp           = rand(100000, 999999);
            $message       = $otp . " is your one time password for verification.";
            $userdata      = array(
                'name' => $name,
                'email' => $email,
                'password' => getHashedPassword($password),
                'mobile_no' => $mobileno,
                'image' => $uploadpath
            );
            
            if ($userdata) {
                //$userdata =array('id'=>$uuid,'name'=>$name,'email'=>$email,'password'=>getHashedPassword($password),'mobile_no'=>$mobileno);
                // var_dump($userdata);die();
                $imageresult = do_file_upload('image', $uploaddir, $allowed_types);
                if ($imageresult) {
                    if ($this->Model->create_user('pd_person', $userdata)) {
                        if (sendsms($mobileno, $message)) {
                            $userDetail                      = $this->Model->get_record('pd_person', array(
                                'mobile_no' => $mobileno
                            ));
                            $response["error"]               = false;
                            $response["message"]             = "User Successfully Registered";
                            $response["userDetail"]          = $userDetail;
                            $response["otp"]                 = $otp;
                            $response["hellosign_agreement"] = "https://portal.helloworks.com/link/LpjxuIHBEU4sKzB8";
                            $response["picker_policy"]       = BASEURL . "driver_policy/" . $userDetail[0]['id'];
                            $response["agreement_url"]       = BASEURL . "driver_contract/" . $userDetail[0]['id'];
                        } else {
                            $response["error"]   = true;
                            $response["message"] = "OTP not send successfully";
                        }
                    } else {
                        $response["error"]   = true;
                        $response["message"] = "Processing Error. Please Try Again";
                    }
                    # code...
                }
            } else {
                # code...
            }
        } else {
            $response["error"]   = true;
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
        $r=$this->Picker_model->isuservalid($username, $password);
        var_dump($r);exit;
        if ($this->Picker_model->isuservalid($username, $password)) {
            $getuserdata = $this->Model->get_record('pd_person', array(
                'mobile_no' => $username
            ));
            
            if ($getuserdata['ic_agreement'] == 0) {
                $response["error"]   = true;
                $response["message"] = "ic_agreement !";
            } elseif ($getuserdata['sa_policy'] == 0) {
                $response["error"]   = true;
                $response["message"] = "sa_policy !";
            } elseif ($getuserdata['taxpayer_i_c_policy'] == 0) {
                $response["error"]   = true;
                $response["message"] = "taxpayer_i_c_policy !";
            } elseif ($getuserdata['is_active'] == deactivate) {
                $response["error"]   = true;
                $response["message"] = "is_active !";
            } else {
                $response["error"]   = false;
                $response["message"] = "Login  Successfully !";
                $response["data"]    = $getuserdata;
            }
        } else {
            $response["error"]   = true;
            $response["message"] = "Login don't Successfully !";
        }
        responseJSON($response);
        
    }
    /*************************************************updateProfile****************************************************** */
    public function updateProfile()
    {
        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('picker_id', 'picker_id', 'required');
        $this->form_validation->set_rules('address', 'address',  'required');
        $this->form_validation->set_rules('zip_code', 'zip_code',  'required');
        if ($this->form_validation->run() == true) {
            $time = time();
            // reading post params
            $picker_id = $this->input->post('picker_id');
            $name = $this->input->post('name');
            $address = $this->input->post('address');
            $zipCode = $this->input->post('zip_code');
            $image         = $_FILES['image']['name'];
            $response = array();
            $uploaddir     = "./public/upload/pickeruserimage/";
            $imagename     = "Picker_" . time() . 'image';
            $uploadpath    = $uploaddir . $imagename;
            //var_dump($imagename);die();
            $allowed_types = 'gif|jpg|png';
            $userdata      = array(
                'name' => $name,
                'picker_id' => $picker_id,
                'address' => $address,
                'zip_code' => $zip_code,
                'image' => $uploadpath
            );
            if($this->Model->get_record('pd_person', array('id' => $picker_id))){
                $imageresult = do_file_upload('image', $uploaddir, $allowed_types);
                if ($imageresult) {
                    if ($this->Model->update('pd_person', array('name' =>$name,'Address'=>$address,'zip_code'=>$zipCode,'image'=>$uploadpath ), array('id' => $picker_id ))) {
                        $userDetail = $this->Model->get_record('pd_person', array(
                            'id' => $picker_id
                        ));
                        $response["error"] = false;
                        $response["message"] = "Profile Successfully Updated";
                        $response["userDetail"] = $userDetail;
                    } else {
                        $response["error"] = true;
                        $response["message"] = "Profile Detail Not Updated";
                    }
                } else {
                    $response["error"] = true;
                    $response["message"] = "Image Uploading Error";
                }
            } else {
                if ($this->Model->update('pd_person', array('name' =>$name,'Address'=>$address,'zip_code'=>$zipCode ), array('id' => $picker_id ))) {
                    $userDetail = $userDetail = $this->Model->get_record('pd_person', array(
                        'id' => $picker_id
                    ));
                    $response["error"] = false;
                    $response["message"] = "Profile Successfully Updated";
                    $response["userDetail"] = $userDetail;
                } else {
                    $response["error"] = true;
                    $response["message"] = "Profile Detail Not Updated";
                }
            }
            }
        } else {
            $response["error"]   = true;
            $response['message'] = strip_tags(validation_errors());
        }
        responseJSON($response);
    }
    /**************updatePolicyWorkStatus**********************/
    public function updatePolicyWorkStatus()
    {
         
        $this->form_validation->set_rules('tag', 'Tag', 'required');
        $this->form_validation->set_rules('status', 'Status', 'required');
        $response = array();
        if ($this->form_validation->run() == true) {
            $userid = $this->pickerid;
            
            $status = $this->input->post('status');
            $tag    = $this->input->post('tag');
            
            if ($tag == 'ic_agreement') {
                if ($this->Model->update('pd_person', array(
                    'ic_agreement' => $status
                ), array(
                    'id' => $userid
                ))) {
                    $userDetail                = $this->Model->get_record('pd_person', array(
                        'id' => $userid
                    ));
                    $userDetail['profile_url'] = IMAGE_SHOWURL . $userDetail[0]['image'];
                    $response["error"]         = false;
                    $response["message"]       = "Independent Contractor Policy Updated Successfully";
                    $response["userDetail"]    = $userDetail;
                } else {
                    $response["error"]   = true;
                    $response["message"] = "Status not updated";
                }
            } else if ($tag == 'sa_policy') {
                
                if ($this->Model->update('pd_person', array(
                    'sa_policy' => $status
                ), array(
                    'id' => $userid
                ))) {
                    $userDetail             = $this->Model->get_record('pd_person', array(
                        'id' => $userid
                    ));
                    $response["error"]      = false;
                    $response["message"]    = "Shoppers Application Policy Updated Successfully";
                    $response["userDetail"] = $userDetail;
                } else {
                    $response["error"]   = true;
                    $response["message"] = "Status not updated";
                }
            } else if ($tag == 'at_work') {
                if ($this->Model->update('pd_person', array(
                    'at_work' => $status
                ), array(
                    'id' => $userid
                ))) {
                    $userDetail             = $this->Model->get_record('pd_person', array(
                        'id' => $userid
                    ));
                    $response["error"]      = false;
                    $response["message"]    = "Work Status Updated Successfully";
                    $response["userDetail"] = $userDetail;
                } else {
                    $response["error"]   = true;
                    $response["message"] = "Status not updated";
                }
            } else {
                $response["error"]   = true;
                $response["message"] = "Tag is invalid";
            }
        } else {
            $response["error"]   = true;
            $response['message'] = strip_tags(validation_errors());
        }
        responseJSON($response);
    }
    /*******getUserDetail****************/
    public function getUserDetail()
    {
        $response                  = array();
        $userid                    = $this->pickerid;
        $device_type               = $this->input->get('device_type');
        $device_token              = $this->input->get('device_token');
        $userDetail                = $this->Model->get_record('pd_person', array(
            'id' => $userid
        ));
        $userDetail['profile_url'] = IMAGE_SHOWURL . $userDetail[0]['image'];
        $this->Picker_model->insert_gcm_data_for_picker($device_token, $device_type, $userid, time());
        $available_orders = 0;
        if ($userDetail) {
            $orders = $this->Model->get_selected_data('order_id', 'order_table', array(
                'status' => 0,
                `dlv_date` => CURDATE()
            ));
            if (count($orders) > 0) {
                for ($i = 0; $i < $orders->num_rows; $i++) {
                    $result      = $orders;
                    $ordertime   = $result[0]['datetime'];
                    $currenttime = time();
                    
                    $minutes = 10;
                    if (($currenttime - $ordertime) > ($minutes * 60)) {
                        $available_orders++;
                    }
                }
            }
            $response["error"]           = false;
            $response["message"]         = "User detail get successfully";
            $response["Availbale_order"] = $available_orders;
            $response["userDatail"]      = $userDetail;
        } else {
            $response["error"]   = true;
            $response["message"] = "User detail not found";
        }
        responseJSON($response);
    }
    /************addPickerOrder************/
    public function addPickerOrder()
    {
        $this->form_validation->set_rules('order_detail', 'Order Detail', 'required');
        $response = array();
        if ($this->form_validation->run() == true) {
            $picker_id       = $this->pickerid;
            $order_detail    = $this->input->post('order_detail');
            $data            = json_decode($order_detail, true);
            $orderids        = "";
            $insertArray     = array();
            $store_not_match = false;
            foreach ($data as $arrays) {
                array_push($insertArray, "('" . $picker_id . "', '" . $arrays["order_id"] . "', '" . $arrays["dispatch_time"] . "')");
                $orderids .= "'" . $arrays["order_id"] . "',";
            }
            if ($store_not_match == true) {
                $response["error"]   = true;
                $response["message"] = "Please select same store orders";
            } else {
                $orderids        = trim($orderids, ",");
                $pickerStatus    = $this->Model->get_selected_data('picker_order_status', 'pd_person', array(
                    'id' => $picker_id
                ));
                $pickerStatusMsg = $this->getPickerStatusMsg($pickerStatus);
                if ($pickerStatus == 0) {
                    
                    if (!$this->Model->get_selected_data('id', 'pd_order', array(
                        'id' => $orderids
                    ))) {
                        if ($this->Model->add('pd_order', $insertArray)) {
                            //update order status to prepare mode
                            $orderStatus = 1;
                            foreach ($data as $arrays) {
                                $orderInfo = $this->Picker_moedel->getOrderInfoByOrderId($arrays["order_id"]);
                                if (count($orderInfo) > 0) {
                                    $orderInfoResult = $orderInfo[0];
                                    addorderstatus($orderInfoResult, $picker_id, $orderStatus, "Order picked!");
                                }
                            }
                            $this->Model->update('order_table', array(
                                'status' => $orderStatus
                            ), array(
                                'order_id' => $order_id
                            ));
                            $this->Model->update('pd_person', array(
                                'picker_order_status' => $status
                            ), array(
                                'id' => pickerid
                            ));
                            $response["error"]   = false;
                            $response["message"] = "Order Selected Successfully.";
                        } else {
                            $response["error"]   = true;
                            $response["message"] = "Order Not Selected. Please Try Again";
                        }
                    } else {
                        $response["error"]   = true;
                        $response["message"] = $pickerStatusMsg;
                    }
                } else if ($pickerStatus == 2) {
                    $response["error"]   = true;
                    $response["message"] = $pickerStatusMsg;
                } else if ($pickerStatus == 3) {
                    $response["error"]   = true;
                    $response["message"] = $pickerStatusMsg;
                } else {
                    $response["error"]   = true;
                    $response["message"] = "Order Not Selected. Please Try Again";
                }
            }
        } else {
            $response["error"]   = true;
            $response['message'] = strip_tags(validation_errors());
        }
        responseJSON($response);
    }
    
    /**********************************************getPickerOrder*****************************/
    public function getPickerOrder()
    {
        $picker_id   = $this->pickerid;
        $response = array();
        $ordersDetail    = array();
        $pickerStatus    = $this->Model->get_selected_data('picker_order_status', 'pd_person', array(
            'id' => $picker_id
        ));
        $pickerStatusMsg = $this->getPickerStatusMsg($pickerStatus);
        
        $pickerOrders = $this->Picker_moedel->getOrderByPickerId($picker_id);
        if ($pickerOrders) {
            if (count($pickerOrders) > 0) {
                foreach ($pickerOrders as $row) {
                    $resslot                  = $this->Picker_moedel->getstlottime($row['slot_id'], $row['store_id']);
                    $delivery_slot            = $resslot[0];
                    $row['delivery_slot']     = $delivery_slot['slot_name'];
                    /*
                     * if current time - Order time > 11 mins then only add this order to list
                     */
                    $row['dispatch_time_str'] = date('h:i A', $row['dispatch_time']);
                    $row['delivery_time_str'] = date('h:i A', $row['delivery_time']);
                    $row['order_datetime']    = date('d-m-Y h:i A', $row['datetime']);
                    $row['ship_address']      = get_compatible_address($row['ship_address']); // changes address to json if in string format
                    
                    $itemInfo = $this->Model->getItemdetailByOrderId($row['order_id'], $row['store_id']);
                    if ($itemInfo) {
                        if (count($itemInfo) > 0) {
                            $ordersItemDetail = array();
                            foreach ($itemInfo as $result) {
                                $result                     = array_map('utf8_encode', $result);
                                $result["complete_img_url"] = IMAGE_SHOWURL . $result[0]["imageurl"];
                                array_push($ordersItemDetail, $result);
                            }
                            $row['orderItemInfo'] = $ordersItemDetail;
                        }
                    }
                    array_push($ordersDetail, $row);
                }
                
                $response["error"]           = false;
                $response["picker_status"]   = $pickerStatus;
                $response["pickerStatusMsg"] = $pickerStatusMsg;
                $response["message"]         = "Order List Get Successfully";
                $response["StoreDetail"]     = array(
                    "store_address" => "69, electronic complex vijay nagar Indore Madhya Pradesh",
                    "lat" => 22.719568,
                    "lng" => 75.857727
                );
                $response["Orders"]          = $ordersDetail;
            } else {
                $response["error"]           = false;
                $response["picker_status"]   = $pickerStatus;
                $response["pickerStatusMsg"] = $pickerStatusMsg;
                $response["message"]         = "No Order Found";
            }
        } else {
            $response["error"]   = true;
            $response["message"] = "Processing error. Please try again";
        }
        responseJSON($response);
    }
    /***************************getPickeOrderItem*********************************/
    public function getPickeOrderItem()
    {
        $response            = array();
        $picker_id            = $this->pickerid;
        $ordersDetail        = array();
        $finalArray          = array();
        $suggestedAltShowBtn = false; //if false btn not show on in review page and true then show
        $pickerOrders        = $this->Picker_moedel->getOrderByPickerId($picker_id);
        
        /*
         *  Check if last order cancelled by picker,
         *  return status that picker is ready to pick orders again
         */
        
        $picker_non_delievered_orders     = $this->Model->get_selected_data('order_id', 'pd_order', array(
            'picker_id' => $picker_id,
            'status' => 0
        ));
        $picker_non_delievered_orders_num = count($picker_non_delievered_orders);
        
        if ($picker_non_delievered_orders_num > 0) {
            
            $count_of_cancelled_orders = 0;
            foreach ($picker_non_delievered_orders_num as $order_id) {
                $order_details = $this->Model->get_record('order_table', array(
                    'order_id' => $order_id['order_id'],
                    'status' => 'active'
                ));
                if (count($order_details) > 0) {
                    $order_detail = $order_details[0];
                    if ($order_detail['status'] == 6) {
                        $count_of_cancelled_orders++;
                    }
                }
            }
            if ($count_of_cancelled_orders == $picker_non_delievered_orders_num) {
                // Update the status of picker to Initial Stage Able to Select New Orders(Status=0)
                $this->Model->update('pd_person', array(
                    'picker_order_status' => 0
                ), array(
                    'id' => $userid
                ));
            }
        }
        
        $pickerStatus = $this->Model->get_selected_data('picker_order_status', 'pd_person', array(
            'id' => $picker_id
        ));
        
        $pickerStatusMsg = getPickerStatusMsg($pickerStatus);
        
        if ($pickerOrders) {
            if ($pickerOrders->num_rows > 0) {
                //update picker status = 2  for shopping start
                $this->Model->update('pd_person', array(
                    'picker_order_status' => 2
                ), array(
                    'id' => $userid
                ));
                
                $pickerStatus = $this->Model->get_selected_data('picker_order_status', 'pd_person', array(
                    'id' => $picker_id
                ));
                
                $pickerStatusMsg = getPickerStatusMsg($pickerStatus);
                
                $orderIds_arr = array();
                $store_id     = null;
                foreach ($pickerOrders as $row) {
                    
                    $resslot              = $this->Model->getstlottime($row['slot_id'], $row['store_id']);
                    $delivery_slot        = $resslot[0];
                    $row['delivery_slot'] = $delivery_slot['slot_name'];
                    
                    $store_id = $row['store_id'];
                    
                    $row["dispatch_time"] = date("d-M-Y h:i A", $row["dispatch_time"]);
                    $row["delivery_time"] = date("d-M-Y h:i A", $row["delivery_time"]);
                    $alterurl             = "";
                    
                    // If Status is 1 for order edit we set a timer of 5 mins after that picker is also allowed to approve alternate item
                    if ($row["is_order_edit"] == 1) {
                        $difference_seconds = time() - (int) $row["pickerAltAprovaltime"];
                        
                        $minutes = 5; // changed from 10 mins to 5 mins on clients demand by burhan on 27-3-18
                        
                        if ($difference_seconds >= ($minutes * 60)) {
                            $row["is_available_for_picker_to_edit"] = true;
                            $alterurl                               = alternataivelink . $row['order_user_id'] . "/" . $row['order_id'] . "?role=picker&store=" . $store_id;
                        } else {
                            $row["is_available_for_picker_to_edit"] = false;
                        }
                    } else {
                        $row["is_available_for_picker_to_edit"] = false;
                    }
                    
                    if ($row["is_order_edit"] == 3) {
                        $suggestedAltShowBtn = true;
                    }
                    
                    /*
                     * If Order is new(status=0) or item is suggested alternate without mail shoot to user(status=3), then only show the suggest alternate button
                     */
                    if ($row["is_order_edit"] == 0 || $row["is_order_edit"] == 3) {
                        $row["show_suggest_alternate_button"] = true;
                    } else {
                        $row["show_suggest_alternate_button"] = false;
                    }
                    
                    // Append a PHP View URL for picker with the help of which picker can approve/disapprove order / item
                    $row['alternate_approval_url'] = $alterurl;
                    $row['ship_address']           = get_compatible_address($row['ship_address']); // changes address to json if in string format
                    
                    array_push($ordersDetail, $row);
                    array_push($orderIds_arr, $row['order_id']);
                }
                
                $results = array();
                if (count($orderIds_arr) > 0) {
                    foreach ($orderIds_arr as $order_id) {
                        $itemInfo = $this->Picker_model->getItemdetailByOrderIdWithMotherCategory($order_id, $store_id);
                        if ($itemInfo) {
                            if (count($itemInfo) > 0) {
                                foreach ($itemInfo as $result) {
                                    $result = array_map('utf8_encode', $result);
                                    array_push($results, $result);
                                }
                            }
                        }
                    }
                }
                //sorted output using mother category rank
                //$itemInfo = $db->getItemdetailByOrderIdWithMotherCategory($orderIds,$store_id);
                if ($itemInfo->num_rows > 0) {
                    foreach ($results as $result) {
                        $alternateItemArray             = $this->Picker_model->getAlternativeItem($result['order_id'], $result['item_id'], $store_id);
                        $result["alternativeItemArray"] = $alternateItemArray;
                        // get key of order which user order item using order_id
                        $result['sub_name']             = $this->Picker_model->getSubCategoryNameForItem($result['item_id'], $store_id);
                        $result["complete_img_url"]     = IMAGE_SHOWURL . $result["imageurl"];
                        $key                            = array_search($result['order_id'], array_column($ordersDetail, 'order_id'));
                        $ordersArray                    = array_merge(array(
                            "orderDetail" => $ordersDetail[$key]
                        ), array(
                            "ordersItemDetail" => $result
                        ));
                        array_push($finalArray, $ordersArray);
                    }
                    
                    $response["error"]               = false;
                    $response["suggestedAltShowBtn"] = $suggestedAltShowBtn;
                    $response["message"]             = "Order Item List Get Successfully";
                    $response["picker_status"]       = $pickerStatus;
                    $response["pickerStatusMsg"]     = $pickerStatusMsg;
                    $response["Orders"]              = $finalArray;
                } else {
                    $response["error"]           = false;
                    $response["message"]         = "No Order Item Found";
                    $response["picker_status"]   = $pickerStatus;
                    $response["pickerStatusMsg"] = $pickerStatusMsg;
                }
            } else {
                $response["error"]   = true;
                $response["message"] = "Processing error. Please try again";
            }
            responseJSON($response);
        }
    }
    /*****************************************shoppingComplete*********************************/
    public function shoppingComplete()
    {
        $this->form_validation->set_rules('item_count', 'Item Count', 'required');
        $response = array();
        if ($this->form_validation->run() == true) {
            $picker_id      = $this->pickerid;
            $item_count     = $this->input->post('item_count');
            $response       = array();
            $totalItemCount = 0;
            $pickerOrders   = $this->Picker_moedel->getOrderByPickerId($picker_id);
            if ($pickerOrders) {
                
                $orderIdArray = array();
                foreach ($pickerOrders as $row) {
                    $resslot              = $this->Model->getstlottime($row['slot_id'], $row['store_id']);
                    $delivery_slot        = $resslot[0];
                    $row['delivery_slot'] = $delivery_slot['slot_name'];
                    
                    array_push($orderIdArray, "'" . $row['order_id'] . "'");
                    $singleOrderItemCount = $this->Model->get_record('ordered_item', array(
                        'order_id' => $mobileno,
                        'status' => 1
                    ));
                    
                    $totalItemCount = $totalItemCount + count($singleOrderItemCount);
                }
                $orderIds = implode(",", $orderIdArray);
                
                if ($totalItemCount == $item_count) {
                    //update picker status = 3  for shopping start
                    $isUpdatePickerStatus = $this->Model->update('pd_person', array(
                        'picker_order_status' => 3
                    ), array(
                        'id' => $picker_id
                    ));
                    $pickerStatus         = $this->Model->get_selected_data('picker_order_status', 'pd_person', array(
                        'id' => $picker_id
                    ));
                    
                    $pickerStatusMsg = $this->getPickerStatusMsg($pickerStatus);
                    
                    $status              = 2;
                    //update order status to packed
                    $isUpdateOrderStatus = $this->Model->update('order_table', array(
                        'status' => $status
                    ), array(
                        'order_id' => $orderIds
                    ));
                    
                    foreach ($orderIdArray as $orderId) {
                        $orderInfo = $this->Picker_model->getOrderInfoByOrderId($orderId);
                        
                        if (Count($orderInfo) > 0) {
                            $orderInfoResult = $orderInfo[0];
                            addorderstatus($orderInfoResult, $picker_id, $status, "Order packed!");
                        }
                        
                    }
                    
                    if ($isUpdatePickerStatus == true && $isUpdateOrderStatus == true) {
                        $response["error"]           = false;
                        $response["picker_status"]   = $pickerStatus;
                        $response["pickerStatusMsg"] = $pickerStatusMsg;
                        $response["message"]         = "Status update to packed.";
                    } else {
                        $response["error"]   = true;
                        $response["message"] = "Status not update";
                    }
                } else {
                    $response["error"]            = true;
                    $response["total_item_count"] = $totalItemCount;
                    $response["message"]          = "You have not complete your shopping";
                }
                
                
            } else {
                $response["error"]   = true;
                $response["message"] = "Processing error. Please try again";
            }
        } else {
            $response["error"]   = true;
            $response['message'] = strip_tags(validation_errors());
        }
        responseJSON($response);
    }
    
    /***************************************getDeliveryOrder****************************************/
	public function getDeliveryOrder()
	{
		$pickerid      = $this->pickerid;
		$response = array();
		$ordersDetail =array();
		$pickerStatus = $this->Model->get_selected_data('picker_order_status', 'pd_person', array('id' => $pickerid));
		$pickerStatusMsg = $this->getPickerStatusMsg($pickerStatus);
		$deliveryOrder = $this->Picker_model->getOrderForDeliverByPickerId($pickerid, 0);
		$numRows = count($deliveryOrder);
		$deliveredOrderCount = 0;
		
		if($numRows > 0){
			foreach( $deliveryOrder as $row ){
				$resslot = $this->Model->getstlottime($row['slot_id'], $row['store_id']);
				$delivery_slot = $resslot[0];
				$row['delivery_slot'] = $delivery_slot['slot_name'];
				if ($row['order_status'] == 4) {
					$deliveredOrderCount = $deliveredOrderCount + 1;
				}

				$row['dispatch_time_str'] = date('h:i A', $row['dispatch_time']);
				$row['delivery_time_str'] = date('h:i A', $row['delivery_time']);
				$row['order_datetime'] = date('d-M-Y h:i A', $row['datetime']);
				$row['ship_address'] = get_compatible_address($row['ship_address']); // changes address to json if in string format

				$itemInfo = $this->Model->getItemdetailByOrderId($row['order_id'],$row['store_id']);
				if( count($itemInfo) > 0 ){
					$ordersItemDetail =array();
					foreach($itemInfo as $result){
						$result = array_map('utf8_encode', $result);
						$result["complete_img_url"] = IMAGE_SHOWURL.$result["imageurl"];
						array_push($ordersItemDetail, $result);
					}
					$row['orderItemInfo'] = $ordersItemDetail;
				}
				array_push($ordersDetail, $row);
			}

			$allOrderDeliveryStatus = false;
			if ($numRows == $deliveredOrderCount) {
				$allOrderDeliveryStatus = true;
			}

			$response["error"] = false;
			$response["picker_status"]= $pickerStatus;
			$response["pickerStatusMsg"] = $pickerStatusMsg;
			$response["allOrderDeliveryStatus"] = $allOrderDeliveryStatus;
			$response["message"] = "Order List Get Successfully";
			$response["Orders"] = $ordersDetail;
		}else{
			$response["error"] = false;
			$response["picker_status"]= $pickerStatus;
			$response["allOrderDeliveryStatus"] = true;
			$response["pickerStatusMsg"] = $pickerStatusMsg;
			$response["message"] = "No Order Found";
		}
		responseJSON($response);
	}
	/**********************************getPickerOrderHistory*************************************/
    public function getPickerOrderHistory()
	{
		$pickerid      = $this->pickerid;
		$response = array();

		$ordersDetail =array();
		$pickerStatus = $this->Model->get_selected_data('picker_order_status', 'pd_person', array('id' => $pickerid));
		$pickerStatusMsg = $this->getPickerStatusMsg($pickerStatus);

		$deliveryOrder = $this->Picker_model->getOrderForDeliverByPickerId($pickerid, 1);
		$numRows = $deliveryOrder;
		$deliveredOrderCount = 0;
		if($deliveryOrder){
			if($numRows > 0){
				foreach( $deliveryOrder as $row ){
					$resslot = $this->Model->getstlottime($row['slot_id'], $row['store_id']);
					$delivery_slot = $resslot[0];
					$row['delivery_slot'] = $delivery_slot['slot_name'];

					if ($row['order_status'] == 4) {
						$deliveredOrderCount = $deliveredOrderCount + 1;
					}

					$row['dispatch_time_str'] = date('d-M-Y h:i A', $row['dispatch_time']);
					$row['delivery_time_str'] = date('d-M-Y h:i A', $row['delivery_time']);
					$row['order_datetime'] = date('d-M-Y h:i A', $row['datetime']);
					$row['ship_address'] = get_compatible_address($row['ship_address']); // changes address to json if in string format

					$itemInfo = $this->Model->getItemdetailByOrderId($row['order_id'], $row['store_id']);
					
						if(Count($itemInfo) > 0){
							$ordersItemDetail =array();
							foreach( $itemInfo as $result ){
								$result = array_map('utf8_encode', $result);
								$result["complete_img_url"] = IMAGE_SHOWURL.$result["imageurl"];
								array_push($ordersItemDetail, $result);
							}
							$row['orderItemInfo'] = $ordersItemDetail;
						}
					

					array_push($ordersDetail, $row);
				}

				$response["error"] = false;
				$response["picker_status"]= $pickerStatus;
				$response["pickerStatusMsg"] = $pickerStatusMsg;
				$response["message"] = "Order List Get Successfully";
				$response["Orders"] = $ordersDetail;
			}else{
				$response["error"] = false;
				$response["picker_status"]= $pickerStatus;
				$response["pickerStatusMsg"] = $pickerStatusMsg;
				$response["message"] = "No Order Found";
			}
		}else{
			$response["error"] = true;
			$response["message"] = "Processing error. Please try again";
		}
		responseJSON($response);
	}

	/*************************************************outForDelivery*************************************************************/
	public function outForDelivery()
	{
		$response = array();

		// reading post params
		$picker_id      = $this->pickerid;
		$order_id = $this->input->post('order_id');
		$ordersDetail = array();

		if($this->Model->get_record('pd_order', array('picker_id' =>$picker_id,'order_id'=>$order_id))){
			$orderInfo = $this->Picker_model->getOrderInfoByOrderId($order_id);
			if(Count($orderInfo) > 0){

				$orderInfoResult = $orderInfo[0];
				$store_id = $orderInfoResult['store_id'];
				$trnsionid = $orderInfoResult['txn_id'];
				$amount = $orderInfoResult['finalprice'];
				$currentOrderStatus = $orderInfoResult['order_status'];
				$order_status = 3; //for out for delivery
				if($currentOrderStatus != $order_status){
					$isspayment = $this->payment->capturePreviouslyAuthorizedAmount($trnsionid, $amount);
					if ($isspayment) {

						$bi_email = $orderInfoResult['email_id'];
						$userid = $orderInfoResult['user_id'];
						$bi_firstname = $orderInfoResult['first_name'];
						$bi_lastname = $orderInfoResult['last_name'];
						$bi_address = $orderInfoResult['address'];
						$bi_contact = $orderInfoResult['mobile'];
						$txnid = $orderInfoResult['txn_id'];
						$finalprice = $orderInfoResult['finalprice'];
						$totaltax = $orderInfoResult['tax'];
						$time = time();
						$processingfees = $orderInfoResult['processingfee'];
						$deliverycharge = $orderInfoResult['dlv_charge'];
						$slotid = $orderInfoResult['slot_id'];
						$subtotal = $orderInfoResult['total_price'];
						$develydate = $orderInfoResult['dlv_date'];
						$tip_amount = $orderInfoResult['tip_amount'];



                        addorderstatus($orderInfoResult, $picker_id, $order_status, "Order out for delivery!");
                        $mail_info = array('orderid' => $order_id,'userid'=>$userid,'bi_firstname'=>$bi_firstname,'bi_lastname'=>$bi_lastname,'bi_address'=>$bi_address,'bi_contact'=>$bi_contact,'txnid'=>$txnid,'finalprice'=>$finalprice,'totaltax'=>$totaltax,'time'=>$time,'processingfees'=>$processingfees,'deliverycharge'=>$deliverycharge,'slotid'=>$slotid,'develydate'=>$develydate,'subtotal'=>$subtotal,'tip_amount'=>$tip_amount,'store_id'=>$store_id);
                        $mail_temp = $this->load->view('go2gro_web/template/picker_alternteorderplaced',$mail_info,true);
                        $issendmail = $this->general->send_mail($bi_email,"Your Go2Gro order (".$orderid.") has been placed !",$mail_temp);

                        if($order_status == 3){
                            $update_rec = array('is_payment_done' => 1);
                            $condition_arr = array('status' =>$order_status , 'order_id' => $order_id);
                        }else{
                            $update_rec = array('status' => $order_status);
                            $condition_arr = array('order_id' => $order_id);
                        }
						$this->Model->update('order_table',$update_rec, $condition_arr);


						$deliveryOrder = $this->Picker_model->getOrderForDeliverByPickerId($pickerid, 0);
						if(count($deliveryOrder) > 0){
							foreach( $deliveryOrder as $row){
								$resslot = $this->Model->getstlottime($row['slot_id'], $row['store_id']);
								$delivery_slot = $resslot[0];
								$row['delivery_slot'] = $delivery_slot['slot_name'];
								$row['dispatch_time_str'] = date('h:i A', $row['dispatch_time']);
								$row['delivery_time_str'] = date('h:i A', $row['delivery_time']);
								$row['order_datetime'] = date('d-M-Y h:i A', $row['datetime']);
								$row['ship_address'] = get_compatible_address($row['ship_address']); // changes address to json if in string format

								$itemInfo = $this->Model->getItemdetailByOrderId($row['order_id'], $row['store_id']);
								
									if(count($itemInfo) > 0){
										$ordersItemDetail =array();
										foreach( $itemInfo as  $result){
											$result = array_map('utf8_encode', $result);
											$result["complete_img_url"] = IMAGE_SHOWURL.$result["imageurl"];
											array_push($ordersItemDetail, $result);
										}
										$row['orderItemInfo'] = $ordersItemDetail;
									}
								
								array_push($ordersDetail, $row);
							}
						}


						$response['error'] = false;
						$response['message'] = "Order Status Changed to Out For Delivery";
						$response["Orders"] = $ordersDetail;
					}else{
						$response['error'] = true;
						$response['message'] = "Payment not fatch and staus not update";
					}
				}else{
					$response['error'] = true;
					$response['message'] = "Already update this order status";
				}

			}else{
				$response["error"] = true;
				$response["message"] = "Order not found";
			}
			
		}else{
			$response["error"] = true;
			$response["message"] = "You have not selected this order.";
		}
		responseJSON($response);
    }
    /**********************************changeStatusToDelivered**************************** */
    public function changeStatusToDelivered()
    {
        $response = array();
        $picker_id      = $this->pickerid;
        $order_id = $this->input->post('order_id'); 
        $ordersDetail = array();
        if($this->Model->get_record('pd_order', array('picker_id' =>$picker_id,'order_id'=>$order_id))){
            $orderInfo = $this->Picker_model->getOrderInfoByOrderId($order_id);
            if(count($orderInfo) > 0){
                $orderInfoResult = $orderInfo[0];
                $status = 4;   //for delivered order
                $currentOrderStatus = $orderInfoResult['order_status'];
                if($currentOrderStatus != $status) {
                    addorderstatus($orderInfoResult, $picker_id, $status, "Order delivered!");
                    if($order_status == 3){
                        $update_rec = array('is_payment_done' => 1);
                        $condition_arr = array('status' =>$order_status , 'order_id' => $order_id);
                    }else{
                        $update_rec = array('status' => $order_status);
                        $condition_arr = array('order_id' => $order_id);
                    }
                    $this->Model->update('order_table',$update_rec, $condition_arr);
                    //$this->Picker_model->updateSingleOrderStatus($order_id, $status);

                    $deliveryOrder = $this->Picker_model->getOrderForDeliverByPickerId($picker_id, 0);
                    $numRows = count($deliveryOrder);
                    $deliveredOrderCount = 0;
                    if ($numRows > 0) {
                        foreach (  $deliveryOrder as $row) {

                            $resslot = $this->Model->getstlottime($row['slot_id'], $row['store_id']);
                            $delivery_slot = $resslot[0];
                            $row['delivery_slot'] = $delivery_slot['slot_name'];
                            
                            if ($row['order_status'] == $status) {
                                $deliveredOrderCount = $deliveredOrderCount + 1;
                            }
                            $itemInfo = $this->Model->getItemdetailByOrderId($row['order_id'], $row['store_id']);
                            
                                if (count($itemInfo) > 0) {
                                    $ordersItemDetail = array();
                                   foreach (  $itemInfo as $result) {
                                        $result = array_map('utf8_encode', $result);
                                        $result["complete_img_url"] = IMAGE_SHOWURL . $result["imageurl"];
                                        array_push($ordersItemDetail, $result);
                                    }
                                    $row['orderItemInfo'] = $ordersItemDetail;
                                }
                            
                            array_push($ordersDetail, $row);
                        }
                    }

                    $allOrderDeliveryStatus = false;
                    if ($numRows == $deliveredOrderCount) {
                        //update picker status = 0  for initial stat(no order selected)
                        $isUpdatePickerStatus = $this->Model->update('pd_person', array('picker_order_status' => 0), array('id' => $picker_id));
                        $this->Model->update('pd_order', array('status' => 1), array('picker_id' => $picker_id,'status'=>0));
                        $allOrderDeliveryStatus = true;
                    }
                    $picker_status = $this->Model->get_selected_data('picker_order_status', 'pd_person', array('id' => $picker_id));
                    $response['error'] = false;
                    $response['message'] = "Status update successfully";
                    $response["allOrderDeliveryStatus"] = $allOrderDeliveryStatus;
                    $response["picker_status"] = $picker_status;
                    $response["Orders"] = $ordersDetail;
                }else{
                    $response['error'] = true;
                    $response['message'] = "Already updated this order status";
                }
            }else{
                $response["error"] = true;
                $response["message"] = "Order not found";
            }
        }else{
            $response["error"] = true;
            $response["message"] = "You have not selected this order.";
        }
        responseJSON($response);
    }
    /****************************************************itemfound ****************** */
    public function itemfound()
    {
        $response = array();
        // reading post params
        $picker_id = $this->pickerid;
        $order_id = $this->input->post('order_id');
        $item_id = $this->input->post('item_id');
        $status = $this->input->post('status');

        /**
         * $status = 0 item in todolist
        * $status = 1 item in reviewlist(suggested alternate)
        * $status = 2 item in done list
        */
        if($this->Model->get_record('pd_order', array('picker_id' =>$picker_id,'order_id'=>$order_id))){
            if($status == 2){
                $itemFoundStatus = $status;
                
                if($this->Model->update('ordered_item', array('item_found_status' => $status), array('order_id' => $order_id,'item_id'=>item_id))){
                    $response["error"] = false;
                    $response["message"] = "Item Found Status update.";
                }else{
                    $response["error"] = true;
                    $response["message"] = "Item Found Status not update.";
                }
            }else if($status == 0){
                $itemFoundStatus = $status;
                if($this->Model->update('ordered_item', array('item_found_status' => $status), array('order_id' => $order_id,'item_id'=>item_id))){
                    $response["error"] = false;
                    $response["message"] = "Item Found Status update. Item remove from done list";
                }else{
                    $response["error"] = true;
                    $response["message"] = "Item Found Status not update.";
                }
            }else{
                $response["error"] = true;
                $response["message"] = "Item Found Status not update. Send wrong status.";
            }
        }else{
            $response["error"] = true;
            $response["message"] = "You have not selected this order.";
        }
        responseJSON($response);
    }
    /*******************************getitemonedit****************************/
    public function getitemonedit()
    {
        $response = array();
        $picker_id = $this->pickerid;
        $order_id = $this->input->post('order_id');
        // For getting store_id
        $store_id = -1;
        if($orderInfo = $this->Picker_model->getOrderInfoByOrderId($order_id)){
            if(count($orderInfo) >0){
                $store_id = $orderInfo[0];
            }
        }

        $result1 = $this->Picker_model->getsublink($itemid,$store_id);
        $rowa = $result1[0];
        $subcat = $rowa['sub_id'];
        // fetch task
        $result = $this->Picker_model->getitembysubcat($subcat, $itemid,$store_id);
            
        if (count($result) > 0) {
            $response["error"] = false;
            $response["item"] = array();
            $response["message"] = "list get sucessfuly";
            foreach ( $result as $subcat ) {
                $subcat = array_map('utf8_encode', $subcat);
                array_push($response["item"], $subcat);
            }       
        } else {
            $response["error"] = true;
            $response["message"] = "The requested resource doesn't exists";
        }
        responseJSON($response);
    }
    /********************************* */
    public function itemsearchonedit()
    {
        $this->form_validation->set_rules('str', 'str', 'required');
        $this->form_validation->set_rules('item_id', 'Item Id', 'required');
        $this->form_validation->set_rules('pageno', 'Page No', 'required');
        $this->form_validation->set_rules('order_id', 'Order Id', 'required');
        $response = array();
        if ($this->form_validation->run() == true) {
            $order_id = $this->input->post('order_id');
            $str = $this->input->post('str');
            $itemid = $this->input->post('item_id');
            $pageno = $this->input->post('pageno');

            $store_id = -1;
            if($orderInfo = $this->Picker_model->getOrderInfoByOrderId($order_id)){
                if(count($orderInfo) > 0){
                    $store_id = $orderInfo[0]['store_id'];
                }
            }

            $str = addslashes($str);
            $result = $this->Picker_model->getsuggetionitems($str, $pageno, $itemid,$store_id);
            if (count($result) > 0) {
                $item = array();
                foreach ( $result as $itm  ) {

                    $item[] = $itm;
                }

                $re = $this->Picker_model->adm_getsuggetionitemscount($str, $itemid,$store_id);
                $response["totalcount"] =count($re);
                $response["error"] = false;
                $response["item"] = $item;
                $response["message"] = "list get sucessfuly";  
            } else {
                $response["error"] = true;
                // $response["message"] = "The requested resource doesn't exists 1";
                $response["message"] = "No items found!";
            }
                
        } else {
            $response["error"]   = true;
            $response['message'] = strip_tags(validation_errors());
        }
        responseJSON($response);
    }
    /******************************************suggestAlternate*********************************************/
    public function suggestAlternate()
    {
        $this->form_validation->set_rules('order_user_id', 'order_user_id', 'required');
        $this->form_validation->set_rules('orderid', ' orderid', 'required');
        $this->form_validation->set_rules('alt_item_id', 'alt_item_id', 'required');
        $this->form_validation->set_rules('item_detail', 'item_detail', 'required');
        $response = array();
        if ($this->form_validation->run() == true) {
            $response = array();
            $order_user_id = $this->input->post('order_user_id');
            $picker_id = $this->input->post('picker_id');
            $orderid = $this->input->post('orderid');
            $item_detail = $this->input->post('item_detail');
            $alt_item_id = $this->input->post('alt_item_id');
        //    $store_id = $app->request->post('store_id');
            $item_detail_array = json_decode($item_detail);
            $suggestedAltShowBtn =false;
            $ordersDetail = array();
            $finalArray = array();
            $altItemMaxPrice = array();
            $temparray = array();
            
            $store_id = -1;
            if($orderInfo = $this->Picker_model->getOrderInfoByOrderId($orderid)){
                if(count($orderInfo) > 0){
                    $store_id = $orderInfo[0]['store_id'];
                }
            }
            
            if($this->Model->get_record('pd_person', array('picker_id' => $picker_id,'order_id'=>$orderid))){
            if($this->Model->get_record('ordered_item', array('order_id' => $orderid,'item_id'=>$alt_item_id,'status'=>2))){
                $response["error"] = true;
                $response["message"] = "You already suggest this item alternate";
            }else{
                
                if($this->Model->get_record('ordered_item', array('order_id' => $orderid,'item_id'=>$alt_item_id,'status'=>3))){
                    $response["error"] = true;
                    $response["message"] = "You can not give alternative of this item because item is give another item alternative";
                }else{
                    //$orderInfo = 
                    
                    $cartitem = $this->Model->get_record('ordered_item', array('order_id' => $orderid));
                    if(count($cartitem) > 0){
                        $replaceItemTotal = 0;
                        foreach( $cartitem as $cartItemInfo ){
                            $status = $cartItemInfo["status"];
                            $totalItemPrice = $cartItemInfo["item_quty"]*$cartItemInfo["price"];
                            $totalItemSaleTax =$totalItemPrice*$cartItemInfo["tax"]/100;
                            if($status == 2){
                                $replaceItemTotal=$replaceItemTotal+$totalItemPrice+$totalItemSaleTax;
                            }

                            if($cartItemInfo["item_id"] == $alt_item_id){
                                $altItemPrice = $cartItemInfo["item_quty"]*$cartItemInfo["price"];
                                $altItemSaleTax =$altItemPrice*$cartItemInfo["tax"]/100;
                                $replaceItemTotal = $replaceItemTotal+$altItemPrice+$altItemSaleTax;
                            }

                            if($status == 5){
                                $altid = $cartItemInfo["alernative_item_id"];
                                $subtotal = $totalItemPrice+$totalItemSaleTax;
                                if(array_key_exists($altid,$altItemMaxPrice)){
                                    array_push($temparray, $subtotal );
                                    $altItemMaxPrice[$altid]=$temparray;
                                }else{
                                    $temparray = [];
                                    array_push($temparray, $subtotal );
                                    $altItemMaxPrice[$altid]=$temparray;
                                }
                            }
                        }

                        $sumalternateMaxPrice = 0;
                        foreach($altItemMaxPrice as $itemPrice){
                            $sumalternateMaxPrice = $sumalternateMaxPrice+max($itemPrice);
                        }

                        $actual = "";
                        $incomingAltItem = array();
                        foreach ($item_detail_array as $array) {
                            $incomingAltItemTotal = $array->quty*$array->price+$array->quty*$array->price*$array->sale_tax/100;
                            array_push($incomingAltItem, $incomingAltItemTotal);
                            $actual .= "(" . "'" . $array->itemid . "','" . $array->quty . "','" . $array->userid . "','" . $array->price . "','" . $array->sale_tax . "','" . $array->orderid . "','" . $array->alt_item_id . "','" . $array->status . "'" . "),";
                        }

                        $orderInfo = $this->Picker_model->getOrderInfoByOrderId($order_id);
                        $result = $orderInfo[0];
                        $newOrderTotal = $result["finalprice"]-$replaceItemTotal+$sumalternateMaxPrice + max($incomingAltItem);

                        $commacolumforquery = trim($actual, ',');

                        if($newOrderTotal > $result["auth_amount"]){
                            $response["error"] = true;
                            $response["message"] = "Order Total is greater than captured amount.";
                        }else{
                            $res = $this->Model->add('ordered_item',$commacolumforquery);

                            if($res) {
                                $respon = $this->Picker_model->updateorderncart($orderid, $order_user_id, $alt_item_id);
                                if($respon){

                                    $pickerOrders = $this->Picker_moedel->getOrderByPickerId($picker_id);
        //                            $orderIds = "";
                                    $orderIds_arr = array();
                                    foreach( $pickerOrders as $row){
                                        $resslot = $this->Picker_moedel->getstlottime($row['slot_id'], $row['store_id']);
                                        $delivery_slot = $resslot[0];

                                        $row['delivery_slot'] = $delivery_slot['slot_name'];
                                        $row["dispatch_time"]= date("d-M-Y h:i A", $row["dispatch_time"]);
                                        $row["delivery_time"]= date("d-M-Y h:i A", $row["delivery_time"]);
                                        $row["is_available_for_picker_to_edit"] = false;
                                        $row["alternate_approval_url"] = "";

                                        /*
                                        * If Order is new(status=0) or item is suggested alternate without mail shoot to user(status=3), then only show the suggest alternate button
                                        */
                                        if($row["is_order_edit"] == 0 || $row["is_order_edit"] == 3){
                                            $row["show_suggest_alternate_button"] = true;
                                        } else {
                                            $row["show_suggest_alternate_button"] = false;
                                        }

                                        array_push($ordersDetail, $row);
        //                                $orderIds .= "'".$row['order_id']."'".",";
                                        array_push($orderIds_arr, $row['order_id']);

                                        if($row["is_order_edit"] == 3){
                                            $suggestedAltShowBtn = true;
                                        }
                                    }

                                    //            $orderIds = trim($orderIds, ',');
                                    $results = array();
                                    if(count($orderIds_arr) > 0){
                                        foreach($orderIds_arr as $order_id){
                                            $itemInfo = $this->Picker_moedel->getItemdetailByOrderIdWithMotherCategory($order_id,$store_id);
                                            
                                                if (count($itemInfo) > 0) {
                                                    foreach ($itemInfo as $result) {
                                                        $result = array_map('utf8_encode', $result);
                                                        array_push($results,$result);
                                                    }
                                                }
                                            
                                        }
                                    }
                                   
                                    foreach($results as $result){
                                        $alternateItemArray = $this->Picker_moedel->getAlternativeItem($result['order_id'], $result['item_id'], $store_id);
                                        $result["alternativeItemArray"] = $alternateItemArray;
                                        // get key of order which user order item using order_id
                                        $result['sub_name'] = $this->Picker_moedel->getSubCategoryNameForItem($result['item_id'], $store_id);
                                        $result["complete_img_url"] = IMAGE_SHOWURL.$result["imageurl"];
                                        $key = array_search($result['order_id'], array_column($ordersDetail, 'order_id'));
                                        $ordersArray = array_merge(array("orderDetail" => $ordersDetail[$key]), array("ordersItemDetail"=>$result));
                                        array_push($finalArray, $ordersArray);
                                    }
    
                                    $response["error"] = false;
                                    $response["suggestedAltShowBtn"] = $suggestedAltShowBtn;
                                    $response["message"] = "Alternate added successfully";
                                    $response["order_itemlist"] = $finalArray;
                                }else{
                                    $response["error"] = true;
                                    $response["message"] = "Cart Detail not updated successfully";
                                }

                            }else{
                                $response["error"] = true;
                                $response["message"] = "Alternate not inserted";
                            }
                    }
                    }else{
                        $response["error"] = true;
                        $response["message"] = "order is empty";
                    }
                }
            }
            }else{
                $response["error"] = true;
                $response["message"] = "You have not selected this order";
            }
            
        } else {
            $response["error"]   = true;
            $response['message'] = strip_tags(validation_errors());
        }
        responseJSON($response);
    }
    /**************************************completeOrderAlternateSuggest*********************************** */
    public function completeOrderAlternateSuggest()
    {
        $this->form_validation->set_rules('picker_id', 'picker_id', 'required');
        $this->form_validation->set_rules('orderids', 'orderids', 'required');
        $response = array();
        if ($this->form_validation->run() == true) 
        {
            $picker_id = $this->input->post('picker_id');
            $orderids = $this->input->post('orderids');
            $orderidsArry = json_decode($orderids);
            $suggestedAltShowBtn = false;
            $ordersDetail = array();
            $finalArray = array();
            $currenttime = time();
            $this->Model->transcomplete();
            $proceed_flag = true;
            $store_id = -1;
            foreach($orderidsArry as $orderid) {

                if($orderInfo = $this->Picker_moedel->getOrderInfoByOrderId($orderid)){
                    if(count($orderInfo) == 1){
                        $store_id = $orderInfo[0]['store_id'];
                    }
                }
        
                $updatecartItemStatus = $this->Model->update('ordered_item', array('status' => 3), array('order_id' => $orderid,'status'=>5));
                if($updatecartItemStatus) {
                    $is_edit_order = 1;  // alternate suggested to user
                    $updateOrderEditStatusAlternateTime = $this->Model->update('order_table', array('is_order_edit' => $is_edit_order,'pickerAltAprovaltime'=>$currenttime), array('order_id' => $orderid));
                    if(!$updateOrderEditStatusAlternateTime){
                        $this->Model->transrollback();
                        $proceed_flag = false;
                        break;
                    }
                } else {
                    $this->Model->transrollback();
                    $proceed_flag = false;
                    break;
                }
            }
            $this->Model->transcommit();
            $this->Model->transcomplete();
            if($proceed_flag) {
                foreach ($orderidsArry as $orderid) {
                    $chr1 = $this->Model->get_record('shipping_address',array('order_id' =>$orderid )) ;
                    $chr = $chr1[0];
                    $user_id = $chr['user_id'];
                    $emailid = $chr['email_id'];
                    $mobilenumber = $chr['ship_mobile_number'];
                    $firstname = $chr['first_name'];
                    $lastname = $chr['last_name'];

                    $link = alternataivelink . $user_id . "/" . $orderid;

                    $subject = 'Welcome,' . $username . ' to the Go2Gro family';
                    $msglink = "Dear Customer, <br>Thank you for your order! <br><br> Unfortunately due to the unavailability of some product(s), your order needs to be reviewed for alternative options. <br><br><strong> Please click on the following link to review more options for the unavailable product(s).<strong> <br><br> " . $link . " <br><br> We apologize for this inconvenience, however endeavour to cater for your every need.";
                    $udata = array('orderid'=>$orderid,'user_id'=>$user_id,'msglink'=>$msglink,'emailid'=>$emailid,'firstname'=>$firstname,'lastname'=>$lastname);
                    $mail_msg = $this->load->view('go2gro_web/template/Picker_orderalternetlink',$udata,true);
                    $issendmail = $this->general->send_mail($email,$subject,$msglink);
                    $ordmessagelink = urlencode($msglink);
                    $sendsms = $this->general->sendsms($mobilenumber, $ordmessagelink);

                    $notifyuseridarray=array();
                    $resp=$this->Picker_moedel->getgcmdata($user_id);
                    if (count($resp) > 0) {
                        $timedate = time();
                        $date=date("Y-m-d h:i:s");
                        array_push($notifyuseridarray,$user_id);
                        $this->general->manage_notification("0",$notifyuseridarray,$orderid,ORDER_SEND_ALTERNATIVE,$date,$timedate,ORDER_TAG,"NA","NA");
                    }
                }
        
                $pickerOrders = $this->Picker_moedel->getOrderByPickerId($picker_id);
                //   $orderIds = "";
                $orderIds_arr = array();
                while ( $pickerOrders as $row) {
                    $resslot = $this->Picker_moedel->getstlottime($row['slot_id'], $row['store_id']);
                    $delivery_slot = $resslot[0];
                    $row['delivery_slot'] = $delivery_slot['slot_name'];
                    $row["dispatch_time"] = date("d-M-Y h:i A", $row["dispatch_time"]);
                    $row["delivery_time"] = date("d-M-Y h:i A", $row["delivery_time"]);
        
                    $row["is_available_for_picker_to_edit"] = false;
                    $row["alternate_approval_url"] = "";
        
                    //$orderIds .= "'" . $row['order_id'] . "'" . ",";
        
                    if ($row["is_order_edit"] == 3) {
                        $suggestedAltShowBtn = true;
                    }
        
                    /*
                     * If Order is new(status=0) or item is suggested alternate without mail shoot to user(status=3), then only show the suggest alternate button
                     */
                    if($row["is_order_edit"] == 0 || $row["is_order_edit"] == 3){
                        $row["show_suggest_alternate_button"] = true;
                    } else {
                        $row["show_suggest_alternate_button"] = false;
                    }
        
                    array_push($ordersDetail, $row);
                    array_push($orderIds_arr, $row['order_id']);
                }
        
                //            $orderIds = trim($orderIds, ',');
                $results = array();
                if(count($orderIds_arr) > 0){
                    foreach($orderIds_arr as $order_id){
                        $itemInfo = $this->Picker_moedel->getItemdetailByOrderIdWithMotherCategory($order_id,$store_id);
                        if($itemInfo) {
                            if (count($itemInfo) > 0) {
                               foreach ( $itemInfo as $result) {
                                    $result = array_map('utf8_encode', $result);
                                    array_push($results,$result);
                                }
                            }
                        }
                    }
                }
                
                foreach($results as $result){
                    $alternateItemArray = $this->Picker_model->getAlternativeItem($result['order_id'], $result['item_id'], $store_id);
                    $result["alternativeItemArray"] = $alternateItemArray[0];
                    // get key of order which user order item using order_id
                    $result['sub_name'] = $this->Picker_moedel->getSubCategoryNameForItem($result['item_id'], $store_id);
                    $result["complete_img_url"] = IMAGE_SHOWURL.$result[0]["imageurl"];
                    $key = array_search($result['order_id'], array_column($ordersDetail, 'order_id'));
                    $ordersArray = array_merge(array("orderDetail" => $ordersDetail[$key]), array("ordersItemDetail"=>$result));
                    array_push($finalArray, $ordersArray);
                }
        
                $response["error"] = false;
                $response["suggestedAltShowBtn"] = $suggestedAltShowBtn;
                $response["order_itemlist"] = $finalArray;
                $response["message"] = "Alternative item send successfully";
            }else{
                $response["error"] = true;
                $response["message"] = "Unable to update please try again";
            }
           
        } else {
            $response["error"]   = true;
            $response['message'] = strip_tags(validation_errors());
        }
        responseJSON($response);
    }
    /********************************addItemFlag******************************** */
    public function addItemFlag()
    {
        $this->form_validation->set_rules('picker_id', 'picker_id', 'required');
        $this->form_validation->set_rules('order_id', 'order_id', 'required');
        $this->form_validation->set_rules('item_id', 'item_id', 'required');
        $this->form_validation->set_rules('message', 'message', 'required');
        $response = array();
        if ($this->form_validation->run() == true) 
        {   
            $picker_id = $this->input->post('picker_id');
            $order_id = $this->input->post('order_id');
            $item_id = $this->input->post('item_id');
            $message = $this->input->post('message');
            $store_id = -1;
            if($orderInfo = $this->Picker_moedel->getOrderInfoByOrderId($order_id);
                if($orderInfo->num_rows == 1){
                    $store_id = $orderInfo[0]['store_id'];
                }
            }
           
            if($this->Model->get_record('pd_order', array('picker_id' => $picker_id,'order_id' => $order_id))){
                $itemflag_table = STORE_PREFIX.$store_id.'_'.ITEMFLAG_TABLE;
                $data = array('picker_id'=>$picker_id,'order_id'=>$order_id,'item_id'=>$item_id,'message'=>$message);
                if($this->Model->add('"'.$itemflag_table.'"',$data)){
                    $response["error"] = false;
                    $response["message"] = "Flag Successfully Added";
                }else{
                    $response["error"] = true;
                    $response["message"] = "Please try again. Flag not added";
                }
            }else{
                $response["error"] = true;
                $response["message"] = "You have not selected this order.";
            }
        } else {
            $response["error"]   = true;
            $response['message'] = strip_tags(validation_errors());
        }
        responseJSON($response);
    }
    /*******************************addorderstatus**************************** */
    public function addorderstatus()
    {
        $order_user_id = $orderInfoResult["user_id"];
        $order_id = $orderInfoResult['order_id'];
        $username = $orderInfoResult['ship_name'];
        $useremail = $orderInfoResult['ship_email'];
        $usermobile = $orderInfoResult['ship_mobile_number'];
        $user = $this->Picker_moedel->insertorderstatus($order_id, $updateby, $status, $message);
        if($user){
            if ($status == 4 || $status == 3) {

                if ($status == '3') {
                    $msg = "Dear $username ,\n Your Order ($orderid) is Packed and is out for delivery.";
                } elseif ($status == '4') {
                    $msg = "Dear $username,\n Your Go2Gro order ($order_id) has been delivered.\n We hope that everything turned out fantastic Thank You for using Go2Gro!";
                }

                $issmssend = sendsms($usermobile, $msg);
            }

            if($status==1){
                $tag=ORDER_PREPARE;
            }elseif($status==2){
                $tag=ORDER_PACKED;
            } elseif($status==3){
                $tag=ORDER_OUTFORDELIVERY;
            }elseif($status==4){
                $tag=ORDER_DELIVERED;
            }elseif($status==5){
                $tag=ORDER_REJECT; 
            }

            $notifyuseridarray=array();
            $respx=$this->Picker_moedel->getgcmdata($order_user_id);
            if (count($respx) > 0) {

                date_default_timezone_set(TIME_ZONE);
                $timedate = time();
                $date=date("Y-m-d h:i:s");
                array_push($notifyuseridarray,$order_user_id);
                $this->general->manage_notification("0",$notifyuseridarray,$order_id,$tag,$date,$timedate,ORDER_TAG,"NA","NA");
                //-----------Send notification-----------
                //$this->general->sent_notifiction($userid,$orderid,ORDER_PLACED,date('Y-m-d H:i:s'),$timedate,ORDER_TAG,"NA", "NA");
            }
            return true;
        }else{
            return false;
        }
    }
}

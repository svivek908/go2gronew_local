<?php
defined('BASEPATH') or exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET,POST,OPTIONS");

class Web_api extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('mail');
        $this->load->helper('sms');
        $this->load->model('Cart_item', 'Cart_model');
        $this->load->library('payment');
        //require_once APPPATH.'third_party/PassHash.php';
    }

    //-----------get all zipcode------------
    public function allzipcodes()
    {
        $allzipcodes = $this->Model->get_selected_data('pincode', 'avl_pincode', array('status' => 0));
        if (count($allzipcodes) > 0) {
            $response["error"]    = false;
            $response["zipcodes"] = $allzipcodes;
            $response["status"]   = 'success';
        } else {
            $response["error"]  = true;
            $response["status"] = 'error';
        }
        responseJSON($response);
    }

    //-------------check zipcode exits---------------
    public function checkzipcode()
    {
        $zipcode = $this->input->get('zipcode');
        if (getpinExist($zipcode)) {
            $this->session->set_userdata('pincode', array('pincode' => $zipcode));
            $response["error"]      = false;
            $response["redirectto"] = base_url('select_store/' . $zipcode);
            $response["message"]    = "Zip Code  avaliable";
        } else {
            $this->session->unset_userdata('pincode');
            $response["error"]   = true;
            $response["message"] = "Invalid zipcode";
        }
        responseJSON($response);
    }

    //--------------get category-----------------
    public function getCategory()
    {
        $storeid  = get_selected_storeid();
        $response = array();
        $category = $this->Model->get_all_record('category', $order = 'position', $type = "ASC", $limit = '', $start = "", $where = array('store_id' => $storeid, 'status' => 0));
        if (count($category) > 0) {
            $response["error"]    = false;
            $response["category"] = $category;
            $response["message"]  = "Category list avaliable";
        } else {
            $response["error"]   = true;
            $response["message"] = "No Record Found";
        }
        responseJSON($response);
    }

    //------------login user------------
    public function logindata()
    {
        $this->load->Model('Login_Model');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('password', 'password', 'required');
        if ($this->form_validation->run() == true) {
            $email       = $this->input->post('email');
            $password    = $this->input->post('password');
            $remember_me = $this->input->post('remember_me');
            if ($remember_me == 'true') {
                set_cookie('remember_me', $remember_me, '3600');
                set_cookie('email', $email, '3600');
                set_cookie('password', $password, '3600');
            } else {
                delete_cookie('remember_me');
                delete_cookie('email');
                delete_cookie('password');
            }
            $res = $this->Login_Model->auth_user($email, $password);
            if ($res != null) {
                foreach ($res as $value) {
                    $sess_arr = array('id' => $value->id,
                        'first_name'           => $value->first_name,
                        'last_name'            => $value->last_name,
                        'email_id'             => $value->email_id,
                        'mobile'               => $value->mobile,
                        'api_key'              => $value->api_key,
                        'address'              => $value->address,
                        'zipcode'              => $value->pincode,
                        'membership_plan_id'   => $value->membership_plan_id,
                        'membership_date'      => $value->membership_date);
                }
                $this->session->set_userdata('go2grouser', $sess_arr);
                $logged_user         = $this->session->userdata('go2grouser');
                $response["error"]   = false;
                $response['user']    = $logged_user['id'];
                $response['message'] = "Welcome To Go2Grow";
            } else {
                $response['error']   = true;
                $response['message'] = "An error occurred. Please try again";
            }
        } else {
            $response["error"]   = true;
            $response['message'] = strip_tags(validation_errors());
        }
        responseJSON($response);
    }

    //------------Signup user------------
    public function signupform()
    {
        $logintype = $this->input->post('logintype');
        $this->form_validation->set_rules('first_name', 'Frist name', 'required');
        $this->form_validation->set_rules('last_name', 'Last name', 'required');
        $this->form_validation->set_rules('pin_code', 'Pin code', 'required');
        $this->form_validation->set_rules('user_email', 'Email', 'required|valid_email|is_unique[users.email_id]',
            array(
                'required'  => 'You have not provided %s.',
                'is_unique' => 'This %s already exists.',
            ));
        $this->form_validation->set_rules('mobile', 'Mobile', 'required|is_unique[users.mobile]',
            array(
                'required'  => 'You have not provided %s.',
                'is_unique' => 'This %s already exists.',
            ));

        if ($this->form_validation->run() == true) {
            //-------html_escape function added by sumit 21-jan-2019
            $first_name    = html_escape($this->input->post('first_name'));
            $last_name     = html_escape($this->input->post('last_name'));
            $pincode       = $this->input->post('pin_code');
            $referral_code = $this->input->post('referral_code');
            $password      = $this->input->post('pass');
            $email         = $this->input->post('user_email');
            $mobile        = $this->input->post('mobile');
            $uuid          = $this->db->set('id', 'UUID()', false);
            $data          = array(
                "first_name" => $first_name,
                "last_name"  => $last_name,
                "email_id"   => $email,
                "password"   => $this->general->getHashedPassword($password),
                "mobile"     => $mobile,
                "address"    => json_encode($this->input->post('address')),
                "pincode"    => $pincode,
                'logintype'  => $logintype);

            if ($this->Model->get_record('avl_pincode', array('pincode' => $pincode, 'status' => '0'))) {
                $rec = $this->Model->get_city_state_country($pincode);
                if (count($rec) > 0) {
                    $data['country_id'] = $rec[0]['countryid'];
                    $data['state_id']   = $rec[0]['stateid'];
                    $data['city_id']    = $rec[0]['cityid'];
                }
                $referral_code_valid = true;
                $referred_by_user_id = null;
                if ($referral_code != null) {
                    $referred_by_user_id = $this->Model->get_selected_data('id', 'users', $where = array('referral_code' => $referral_code), $order = false, $type = false, $limit = false, $start = false);
                    if (!$referred_by_user_id) {
                        $referral_code_valid = false;
                    }
                }
                if ($referral_code_valid) {
                    $time                          = time();
                    $data['referral_code']         = $this->getUniqueReferralCode($first_name);
                    $data['unitime']               = $time;
                    $data['api_key']               = generateApiKey();
                    $data['max_referrals_allowed'] = MAX_REFERRALS_ALLOWED;
                    //-------------Insert user record --------------
                    $create_user = $this->Model->create_user('users', $data);
                    if ($create_user) {
                        $create_user_id = $this->Model->get_selected_data('id', 'users', array('email_id' => $email));
                        $create_user_id = $create_user_id[0]['id'];
                        if (!($referral_code == null && $referred_by_user_id == null)) {
                            $this->addReferralFieldsToUsers($referred_by_user_id[0]['id'], $create_user_id);
                        }
                        //------------Send mail to user-------------
                        $username          = $first_name . ' ' . $last_name;
                        $udata['username'] = $username;
                        $subject           = 'Welcome,' . $username . ' to the Go2Gro family';
                        $mail_msg          = $this->load->view('go2gro_web/template/registration', $udata, true);
                        $issendmail        = $this->general->send_mail($email, $subject, $mail_msg);
                        if ($issendmail) {
                            $response["ismailsend"] = "Mail send sucessfully";
                        } else {
                            $response["ismailsend"] = "Mail Not send sucessfully";
                        }
                        //----------------
                        $msg = "Welcome," . $username . " to the Go2Gro family! \n
                        Go2Gro is happy to see you sign up. \n
                        Please click on the given link to verify your mobile no and get your first order at $0 delivery Charges.\n
                        www.Go2Gro.com/" . verifymobile . "/mobile.php?v=" . $create_user_id;
                        $message   = urlencode($msg);
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

                        $welmessage       = urlencode($welmsg);
                        $issendwelcomesms = $this->general->sendsms($mobile, $welmessage);
                        if ($issendwelcomesms) {
                            $response["issendwelcomesms"] = "SMS send sucessfully";
                        } else {
                            $response["issendwelcomesms"] = "SMS Not send sucessfully";
                        }
                        $response["error"]   = false;
                        $response["message"] = "You are successfully registered";
                        $response["user"]    = $create_user_id;
                    } else {
                        $response["error"]   = true;
                        $response['message'] = USER_CREATE_FAILED;
                    }
                } else {
                    $response["error"]   = true;
                    $response['message'] = INVALID_REF_CODE;
                }
            } else {
                $response["error"]   = true;
                $response["message"] = "Pincode Not valid";
            }
        } else {
            $response["error"]   = true;
            $response['message'] = strip_tags(validation_errors());
        }
        responseJSON($response);
    }

    private function addReferralFieldsToUsers($referred_by_user_id, $new_user_id)
    {
        $referred_to_rec = $this->Model->get_selected_data('referred_to', 'users', array('id' => $referred_by_user_id));
        if ($referred_to_rec) {
            $referred_to       = json_decode($referred_to_rec[0]['referred_to'], true);
            $new_referral_json = null;

            if (count($referred_to) > 0) {
                // some referrals already there, append this one to them and update
                $referral_info['user_id']  = $new_user_id;
                $referral_info['unitime']  = time();
                $referral_info['order_id'] = null;

                array_push($referred_to, $referral_info);
                $new_referral_json = json_encode($referred_to);
            } else {
                $new_arr = array();
                // first referral, create new json and update
                $referral_info['user_id']  = $new_user_id;
                $referral_info['unitime']  = time();
                $referral_info['order_id'] = null;
                array_push($new_arr, $referral_info);
                $new_referral_json = json_encode($new_arr);
            }
            $referred_to_update = $this->Model->update('users', array('referred_to' => $new_referral_json), array('id' => $referred_by_user_id));
            //-------reffred by json------------
            $referral_info_new_user['user_id']     = $referred_by_user_id;
            $referral_info_new_user['unitime']     = time();
            $referral_info_new_user['is_redeemed'] = false;
            $referral_info_new_user['order_id']    = null;
            $new_referral_json_new_user            = json_encode($referral_info_new_user);

            $referred_by_update = $this->Model->update('users', array('referred_by' => $new_referral_json_new_user), array('id' => $new_user_id));
            if ($referred_to_update && $referred_by_update) {
                return true;
            } else {
                return false;
            }
        }
    }

    //--------generate reffreal code-------
    private function getUniqueReferralCode($firstname)
    {
        $referral_unique = false;
        while (!$referral_unique) {
            $referral_code = strtoupper(preg_replace('/\s/', '', $firstname)) . rand(1000, 9999);

            $ref = $this->Model->get_selected_data('referral_code', 'users', array('referral_code' => $referral_code));
            if (!$ref) {
                $referral_unique = true;
            }
        }
        return $referral_code;
    }

    //---------get department------------
    public function departmentitemid_new()
    {
        $response    = array();
        $storeid     = get_selected_storeid();
        $deptid      = $this->input->get('listId'); //department_id
        $subid       = $this->input->get('catid'); //category_id
        $pageno      = $this->input->get('pageno'); //page_no. 0 for initial
        $unitime     = $this->input->get('unitime'); //unitime 0 for initial
        $time        = time();
        $pageno      = $pageno * 15;
        $check_store = $this->Model->get_record('stores', array('id' => $storeid, 'status' => 'active'));
        $ress        = false;
        if (count($check_store) > 0) {
            if ($subid == "0") {
                $ress = true;
            } else {
                $checkdptdublink = $this->Model->get_record('subcategory', array('cat_id' => $deptid, 'sub_id' => $subid, 'sab_status' => 0));
                if (count($checkdptdublink) > 0) {
                    $ress = true;
                }
            }

            if ($ress) {
                $result = $this->Model->get_selected_data(array('sub_id', 'sub_name', 'sub_image'), 'subcategory', array('cat_id' => $deptid, 'sab_status' => '0'), 'feature_product_status', 'desc');
                if (count($result) > 0) {
                    $item = array();
                    if ($subid == 0) {
                        foreach ($result as $value) {
                            $item[] = $value;
                        }

                        $getitembysubcat = $this->Model->newgetitembysubcat(array("p.id", "p.item_id", "CONCAT(p.`item_name`, ' ', p.`item_size`) as item_name", "p.`item_sdesc`", "p.`item_fdesc`", "p.`item_price`", "p.`item_status`", "p.`Sales_tax`", "inl.subcat_id", "sub.sub_name", "ct.id as cat_id", "IFNULL( ROUND( AVG( pr.rating ) ) , 0 ) AS rating_average", "itmg.imageurl as item_image"), $storeid, $subid, array('ct.id' => $deptid, 'p.item_status' => 0, 'p.unitime >' => $unitime, 'inl.status' => 0), 'p.`item_id`', 'sub.sub_id', 'ASC', '15', $pageno);
                        $list            = array();
                        if (count($getitembysubcat) > 0) {
                            foreach ($getitembysubcat as $row) {
                                $row             = array_map('utf8_encode', $row);
                                $isAlreadyExists = false;
                                foreach ($list as $x => $x_value) {
                                    if ($row['subcat_id'] == $x_value['subcat_id']) {
                                        $isAlreadyExists         = true;
                                        $itemlist['id']          = $row['id'];
                                        $itemlist['item_id']     = $row['item_id'];
                                        $itemlist['item_name']   = $row['item_name'];
                                        $itemlist['item_sdesc']  = $row['item_sdesc'];
                                        $itemlist['item_fdesc']  = $row['item_fdesc'];
                                        $itemlist['item_price']  = $row['item_price'];
                                        $itemlist['item_status'] = $row['item_status'];
                                        $itemlist['Sales_tax']   = $row['Sales_tax'];
                                        //  $itemlist['cart_qrt'] = $row['cart_qrt'];
                                        $itemlist['rating_average'] = $row['rating_average'];
                                        $itemlist['item_image']     = $row['item_image'];
                                        array_push($list[$x]['items'], $itemlist);
                                        break;
                                    }
                                }
                                if ($isAlreadyExists == false) {
                                    $itemlistarray           = array();
                                    $itemlist['id']          = $row['id'];
                                    $itemlist['item_id']     = $row['item_id'];
                                    $itemlist['item_name']   = $row['item_name'];
                                    $itemlist['item_sdesc']  = $row['item_sdesc'];
                                    $itemlist['item_fdesc']  = $row['item_fdesc'];
                                    $itemlist['item_price']  = $row['item_price'];
                                    $itemlist['item_status'] = $row['item_status'];
                                    $itemlist['Sales_tax']   = $row['Sales_tax'];
                                    //$itemlist['cart_qrt'] = $row['cart_qrt'];
                                    $itemlist['rating_average'] = $row['rating_average'];
                                    $itemlist['item_image']     = $row['item_image'];
                                    array_push($itemlistarray, $itemlist);

                                    $tendes['items']     = $itemlistarray;
                                    $tendes['subcat_id'] = $row['subcat_id'];
                                    $tendes['sub_name']  = $row['sub_name'];

                                    $xyz = array_push($list, $tendes);
                                }
                            }
                            if ($unitime == "0") {
                                $response['updationstatus'] = "1";
                                $response['message']        = "items get successfully";
                            } else {
                                $response['updationstatus'] = "3";
                                $response['message']        = "updation requried";
                            }
                            $response['items']       = $list;
                            $response["subcategory"] = $item;
                            $response["error"]       = false;
                            $response["unitime"]     = $time;
                            $response["subid"]       = $subid;
                        } else {
                            if ($unitime == "0") {
                                $response['updationstatus'] = "0";
                                $response['message']        = "No data avaliable";
                            } else {
                                $response['updationstatus'] = "2";
                                $response['message']        = "updation not requried";
                            }
                            $response["subcategory"] = $item;
                            $response["error"]       = false;
                            $response["unitime"]     = $time;
                            $response["subid"]       = $subid;
                        }
                    } else {
                        foreach ($result as $value) {
                            $item[] = $value;
                        }

                        $getitembysubcat = $this->Model->newgetitembysubcat(array("p.id", "p.item_id", "CONCAT(p.`item_name`, ' ', p.`item_size`) as item_name", "p.`item_sdesc`", "p.`item_fdesc`", "p.`item_price`", "p.`item_status`", "p.`Sales_tax`", "inl.subcat_id", "sub.sub_name", "IFNULL( ROUND( AVG( pr.rating ) ) , 0 ) AS rating_average", "itmg.imageurl as item_image"), $storeid, $subid, array('inl.subcat_id' => $subid, 'p.item_status' => 0, 'p.unitime >' => $unitime, 'inl.status' => 0), 'p.`item_id`', 'sub.sub_id', 'ASC', '15', $pageno);
                        $items           = array();
                        if (count($getitembysubcat) > 0) {

                            foreach ($getitembysubcat as $values) {
                                $items[] = array_map('utf8_encode', $values);
                            }

                            if ($unitime == "0") {
                                $response['updationstatus'] = "1";
                                $response['message']        = "items get successfully";
                            } else {
                                $response['updationstatus'] = "3";
                                $response['message']        = "updation requried";
                            }
                        } else {
                            if ($unitime == "0") {
                                $response['updationstatus'] = "0";
                                $response['message']        = "No data avaliable";
                            } else {
                                $response['updationstatus'] = "2";
                                $response['message']        = "updation not requried";
                            }
                        }
                        $response["item"]        = $items;
                        $response["subcategory"] = $item;
                        $response["error"]       = false;
                        $response["unitime"]     = $time;
                        $response["subid"]       = $subid;
                    }
                } else {
                    $response["error"]   = true;
                    $response["message"] = "The requested resource doesn't exists";
                }
            } else {
                $response["error"]   = true;
                $response["message"] = "The requested resource doesn't exists";
            }
        } else {
            $response["error"]   = true;
            $response["message"] = "Invalid Store";
        }
        responseJSON($response);
    }

    //---------get BestSeller------------
    public function getBestSeller()
    {
        $storeid     = get_selected_storeid();
        $userid      = $this->input->get('user_id');
        $check_store = $this->Model->get_record('stores', array('id' => $storeid, 'status' => 'active'));
        if (count($check_store) > 0) {
            // fetching all user tasks
            if (isset($userid) && $userid == 0) {
                $where = array('p.item_status' => '0', 'p.discount!=' => 0);
            } else {
                $item_ids = "";
                $where    = "";
                $rec      = $this->Model->get_distinct_data('item_id', 'cart_item', array('user_id' => $userid, 'status' => 0, 'store_id' => $storeid));
                foreach ($rec as $value) {
                    $item_ids .= "'" . $value['item_id'] . "',";
                }
                if (isset($item_ids) && !empty($item_ids)) {
                    $where = "p.item_id not in(" . rtrim($item_ids, ',') . ") AND ";
                }
                $where .= "p.item_status = '0' and p.`discount`!='0'";
            }
            $result = $this->Model->getBestseller($userid, $storeid, $where, 'p.`item_id`', 'rand()', 15, 0);
            if (count($result) > 0) {
                $response["bestseller"] = array();

                foreach ($result as $bestseller) {
                    array_push($response["bestseller"], $bestseller);
                }
                $response["error"]   = false;
                $response["message"] = "Bestseller list avaliable";
            } else {
                $response["error"]   = true;
                $response["message"] = "No Record Found";
            }
        } else {
            $response["error"]   = true;
            $response["message"] = "Invalid Store ID!";
        }
        responseJSON($response);
    }

    //---------Update profile
    public function UpdateProfile()
    {
        $user_id = $this->session->userdata("go2grouser")['id'];
        $data    = array("first_name" => html_escape($this->input->post('user_firstname')),
            "last_name"                   => html_escape($this->input->post('user_lastname')),
            "mobile"                      => $this->input->post('user_mobile'),
            "address"                     => json_encode($this->input->post('user_address')),
            "pincode"                     => $this->input->post('user_pincode'),
            "country_id"                  => $this->input->post('user_countryid'),
            "state_id"                    => $this->input->post('user_stateid'),
            "city_id"                     => $this->input->post('user_cityid'));
        if ($this->Model->get_record('avl_pincode', array('pincode' => $this->input->post('user_pincode'), 'status' => '0'))) {
            $result = $this->Model->update('users', $data, array('id' => $user_id));
            if ($result) {
                $resp                = $this->Model->get_record('users', array('id' => $user_id));
                $response["error"]   = false;
                $response["message"] = "You are successfully registered";
                $response["user"]    = $resp;
            } else {
                $response["error"]   = true;
                $response["message"] = "Oops! An error occurred while profile update";
            }
        } else {
            $response["error"]   = true;
            $response["message"] = "Pincode Not valid";
        }
        responseJSON($response);
    }
    //-------change paasword---------
    public function ChangePassword()
    {
        $user_id = $this->session->userdata("go2grouser")['id'];
        $this->form_validation->set_rules('oldpassword', 'Oldpassword', 'required');
        $this->form_validation->set_rules('newpassword', 'Newpassword', 'required');
        if ($this->form_validation->run() == true) {
            $oldpassword = $this->input->post('oldpassword');
            $newpassword = $this->general->getHashedPassword($this->input->post('newpassword'));
            $data        = array('password' => $newpassword);
            $res         = $this->Model->get_record('users', array('id' => $user_id));
            if ($this->Model->get_record('users', array('id' => $user_id))) {
                $user_password = $res[0]['password'];
                if ($this->general->verifyHashedPassword($oldpassword, $user_password)) {
                    $result = $this->Model->update('users', $data, array('id' => $user_id));
                    if ($result) {
                        $response["error"]   = false;
                        $response["message"] = "Your password change successfully";
                    } else {
                        $response["error"]   = true;
                        $response["message"] = "Your  password don't change successfully";
                    }
                } else {
                    $response["error"]   = true;
                    $response["message"] = "Your  oldpassword don't match successfully";
                }
            }
        } else {
            $response["error"]   = true;
            $response['message'] = strip_tags(validation_errors());
        }
    }

    //===================Addcartitem=============
    public function addcartitem()
    {
        $response = array();
        $this->form_validation->set_rules('Authorization', 'Auth key', 'required');
        if ($this->form_validation->run() == true) {
            $storeid     = get_selected_storeid();
            $check_store = $this->Model->get_record('stores', array('id' => $storeid, 'status' => 'active'));
            if (count($check_store) > 0) {
                $auth1  = $this->input->post('Authorization');
                $userid = getuserid('users', $auth1);
                if ($userid) {
                    $item_quty  = $this->input->post('qty');
                    $item_id    = $this->input->post('cart_id');
                    $item_price = $this->input->post('price');
                    $item_tax   = $this->input->post('tax');
                    //-----------check item in store--------
                    $check_item_in_store = $this->Cart_model->checkitemisexistinstore($item_id, $storeid);
                    if ($check_item_in_store) {
                        //-----------check item in cart--------
                        $checkitemisexist = $this->Model->get_record('cart_item', array('item_id' => $item_id, 'user_id' => $userid, 'status' => 0, 'store_id' => $storeid));
                        if (count($checkitemisexist) > 0) {
                            $response['error']   = true;
                            $response['message'] = 'Item already added in cart.';
                        } else {
                            $condition             = "`cat_name` In('Beers','Wines','Wine') AND `store_id`=1";
                            $categories_with_limit = $this->Model->get_selected_data('id', 'category', $condition);

                            $flag           = true;
                            $itemlink_table = STORE_PREFIX . $storeid . '_' . ITEMLINK_TABLE;
                            $cat_id         = $this->Model->gettwodata('s.cat_id', "`" . $itemlink_table . "` as il", "subcategory as s", 'il.subcat_id = s.sub_id', $where = array('il.item_id' => $item_id));

                            if (isset($categories_with_limit) && count($categories_with_limit) > 0) {
                                $category_array = array();
                                for ($i = 0; $i < count($categories_with_limit); $i++) {
                                    array_push($category_array, $categories_with_limit[$i]['id']);
                                }
                                if (in_array($cat_id[0]['cat_id'], $category_array)) {
                                    // check if this item exixts in one of the restricted purchase category (beer,wine) & if item is allowed as per the limit
                                    if (!is_item_under_purchase_limit($item_id, $item_quty, $userid, 'additem', $storeid)) {
                                        $flag = false;
                                    }
                                    if (!is_item_beers_under_purchase_limit($item_id, $item_quty, $userid, 'additem', $storeid)) {
                                        $flag = false;
                                    }
                                }
                            }
                            if ($flag) {
                                //----------insertitemincart-------------------
                                $user = $this->Model->add('cart_item', array('item_id' => $item_id, 'item_quty' => $item_quty, 'user_id' => $userid, 'price' => $item_price, 'tax' => $item_tax, 'store_id' => $storeid));
                                if ($user) {
                                    $resp = $this->Cart_model->getcartitem($userid, $storeid);
                                    if (count($resp) > 0) {
                                        $response["error"]   = false;
                                        $response["item"]    = array();
                                        $response["message"] = "Item added successfully in cart.";
                                        foreach ($resp as $item) {
                                            $item = array_map('utf8_encode', $item);
                                            array_push($response["item"], $item);
                                        }

                                        $chk                      = $this->Cart_model->ischeckfirsetorder($userid);
                                        $response["isfirstorder"] = $chk;
                                    } else {
                                        $response["error"]   = true;
                                        $response["message"] = "The requested resource doesn't exists";
                                    }
                                } else {
                                    $response['error']   = true;
                                    $response['message'] = "An error occurred. Please try again";
                                }
                            } else {
                                $response['error']   = true;
                                $response['message'] = 'You have exceeded the allowed limit for this category of items.';
                            }
                        }
                    } else {
                        $response['error']   = true;
                        $response['message'] = 'invalid item';
                    }
                } else {
                    $response['error']   = true;
                    $response['message'] = 'invalid auth key';
                }
            } else {
                $response["error"]   = true;
                $response["message"] = "Invalid Store";
            }
        } else {
            $response["error"]   = true;
            $response["message"] = strip_tags(validation_errors());
        }
        responseJSON($response);
    }

    //===================UpdatecartItem========

    public function updateitemcart()
    {
        $response = array();
        $this->form_validation->set_rules('Authorization', 'Auth key', 'required');
        if ($this->form_validation->run() == true) {
            $storeid     = get_selected_storeid();
            $check_store = $this->Model->get_record('stores', array('id' => $storeid, 'status' => 'active'));
            if (count($check_store) > 0) {
                $auth      = $this->input->post('Authorization');
                $item_quty = $this->input->post('qty');
                $item_id   = $this->input->post('cart_id');
                $userid    = getuserid('users', $auth);
                if ($userid) {
                    //-----------check item in store--------
                    $check_item_in_store = $this->Cart_model->checkitemisexistinstore($item_id, $storeid);
                    if ($check_item_in_store) {
                        //-----------check item in cart--------
                        $checkitemisexist = $this->Model->get_record('cart_item', array('item_id' => $item_id, 'user_id' => $userid, 'status' => 0, 'store_id' => $storeid));
                        if (count($checkitemisexist) > 0) {
                            $condition             = "`cat_name` In('Beers','Wines','Wine') AND `store_id`=1";
                            $categories_with_limit = $this->Model->get_selected_data('id', 'category', $condition);
                            $flag                  = true;
                            $itemlink_table        = STORE_PREFIX . $storeid . '_' . ITEMLINK_TABLE;
                            $cat_id                = $this->Model->gettwodata('s.cat_id', "`" . $itemlink_table . "` as il", "subcategory as s", 'il.subcat_id = s.sub_id', $where = array('il.item_id' => $item_id));

                            if (isset($categories_with_limit) && count($categories_with_limit) > 0) {
                                $category_array = array();
                                for ($i = 0; $i < count($categories_with_limit); $i++) {
                                    array_push($category_array, $categories_with_limit[$i]['id']);
                                }

                                if (in_array($cat_id[0]['cat_id'], $category_array)) {
                                    // check if this item exixts in one of the restricted purchase category (beer,wine) & if item is allowed as per the limit
                                    if (!is_item_under_purchase_limit($item_id, $item_quty, $userid, 'updateitem', $storeid)) {
                                        $flag = false;
                                    }
                                    if (!is_item_beers_under_purchase_limit($item_id, $item_quty, $userid, 'updateitem', $storeid)) {
                                        $flag = false;
                                    }
                                }
                            }
                            if ($flag) {
                                //----------insertitemincart-------------------
                                $user = $this->Model->update('cart_item', array('item_quty' => $item_quty), array('item_id' => $item_id, 'user_id' => $userid, 'status' => 0, 'store_id' => $storeid));
                                if ($user) {
                                    $resp = $this->Cart_model->getcartitem($userid, $storeid, $item_id);
                                    if (count($resp) > 0) {
                                        foreach ($resp as $item) {
                                            $item = array_map('utf8_encode', $item);
                                        }
                                        $response["error"]      = false;
                                        $response["item_price"] = $item['item_price'];
                                        $response["item_quty"]  = $item['item_quty'];
                                        $response["total"]      = $item['total'];
                                        $response["message"]    = "Item update successfully in cart.";
                                    } else {
                                        $response["error"]   = true;
                                        $response["message"] = "your cart is empty";
                                    }
                                } else {
                                    $response['error']   = true;
                                    $response['message'] = "An error occurred. Please try again";
                                }
                            } else {
                                $response['error']   = true;
                                $response['message'] = 'You have exceeded the allowed limit for this category of items.';
                            }
                        } else {
                            $response['error']   = true;
                            $response['message'] = 'Item is not avaliable in cart';
                        }
                    } else {
                        $response['error']   = true;
                        $response['message'] = 'invalid item';
                    }
                } else {
                    $response['error']   = true;
                    $response['message'] = 'invalid auth key';
                }
            } else {
                $response["error"]   = true;
                $response["message"] = "Invalid Store";
            }
        } else {
            $response["error"]   = true;
            $response["message"] = strip_tags(validation_errors());
        }
        responseJSON($response);
    }

    //==================getcartItem===========
    public function getCartItem()
    {
        $response = array();
        $auth     = $this->input->get('Authorization');
        if (isset($auth) && $auth != '') {
            $storeid     = get_selected_storeid();
            $check_store = $this->Model->get_record('stores', array('id' => $storeid, 'status' => 'active'));
            if (count($check_store) > 0) {
                $store_data = $check_store[0];
                $userid     = getuserid('users', $auth);
                if ($userid) {
                    $resp = $this->Cart_model->getcartitem($userid, $storeid);
                    if (count($resp) > 0) {
                        $discount          = 0;
                        $processing_fee    = 0.00;
                        $response["error"] = false;
                        $response["item"]  = array();
                        $charges           = $this->Model->get_selected_data(array('delivery_charges', 'processing_fee'), "charges_rule");

                        $referral_discount = $this->Model->get_selected_data(array('referred_by', 'referred_to', 'redeemed_referral_count', 'max_referrals_allowed'), "users", array('id' => $userid));
                        if (count($referral_discount) > 0) {
                            $referral_discount = $referral_discount[0];
                            if (isset($referral_discount['referred_by']) && $referral_discount['referred_by'] != '') {
                                $referred_by          = json_decode($referral_discount['referred_by']);
                                $referred_by_redeemed = $referred_by->is_redeemed;
                                if (isset($referred_by_redeemed) && $referred_by_redeemed == false) {
                                    $discount = REFERRAL_DISCOUNT;
                                }
                                else {
                                    $referred_to             = json_decode($referral_discount['referred_to']);
                                    $max_referrals_allowed   = $referral_discount['max_referrals_allowed'];
                                    $redeemed_referral_count = $referral_discount['redeemed_referral_count'];
                                    if ($redeemed_referral_count < $max_referrals_allowed) {
                                        if (count($referred_to) >= $redeemed_referral_count + 1) {
                                            $discount = REFERRAL_DISCOUNT;
                                        }
                                    }
                                }
                            }
                            if($discount > 0){
                                $response["discount_label"] = "Referral Discount";
                                $response["discount_type"]  = "referral";
                                $response["discount_id"]    = -1;
                            }else {
                                $response["discount_label"] = "Discount";
                                $response["discount_type"]  = "";
                                $response["discount_id"]    = 0;
                            }
                        } 
                        if (isset($charges) && count($charges) > 0) {
                            $processing_fee = $charges[0]["processing_fee"];
                        }
                        //delivery charges store wise
                        $response["delivery_charges_label"] = "Delivery charge";
                        $response["delivery_charges"]       = $store_data['delivery_charge'];
                        $response["processing_fee"]         = $processing_fee;
                        $response["discount"]               = $discount;
                        $response["message"]                = "list get sucessfuly";
                        $subtotal                           = 0;

                        foreach ($resp as $item) {
                            $item = array_map('utf8_encode', $item);
                            array_push($response["item"], $item);
                            $subtotal += $item['total'];
                        }
                        //------------check membership plan------
                        $is_membership = is_membership_applicable($userid, $subtotal, MEMBERSHIP_APPLICABLE_SUBTOTAL);
                        if ($subtotal >= $store_data['free_delivery_amount']) {
                            $response["delivery_charges_label"] = "Free Delivery";
                            $response["delivery_charges"]       = 0;
                        } elseif ($is_membership) {
                            $response["delivery_charges_label"] = "Delivery charge (Membership Plan)";
                            $response["delivery_charges"]       = MEMBERSHIP_DELIVERY_CHARGE; //membership apply if subtotal > $40
                        }
                        $chk                      = $this->Cart_model->ischeckfirsetorder($userid);
                        $response["isfirstorder"] = $chk;
                        $response['tips_arr']     = unserialize(TIPS_ARR);
                    } else {
                        $response["error"]   = true;
                        $response["message"] = "your cart is empty";
                    }
                } else {
                    $response['error']   = true;
                    $response['message'] = 'invalid auth key';
                }
            } else {
                $response["error"]   = true;
                $response["message"] = "Invalid Store";
            }
        } else {
            $response["error"]   = true;
            $response["message"] = 'Authorization key required';
        }
        responseJSON($response);
    }

    //=================Delete item from cart===========
    public function deleteitemcart()
    {
        $response = array();
        $this->form_validation->set_rules('Authorization', 'Auth key', 'required');
        $this->form_validation->set_rules('id', 'Item Id', 'required');
        if ($this->form_validation->run() == true) {
            $storeid     = get_selected_storeid();
            $check_store = $this->Model->get_record('stores', array('id' => $storeid, 'status' => 'active'));
            if (count($check_store) > 0) {
                $auth    = $this->input->post('Authorization');
                $item_id = $this->input->post('id');
                $userid  = getuserid('users', $auth);
                if ($userid) {
                    //-----------check item in store--------
                    $check_item_in_store = $this->Cart_model->checkitemisexistinstore($item_id, $storeid);
                    if ($check_item_in_store) {
                        //-----------check item in cart--------
                        $checkitemisexist = $this->Model->get_record('cart_item', array('item_id' => $item_id, 'user_id' => $userid, 'status' => 0, 'store_id' => $storeid));
                        if (count($checkitemisexist) > 0) {
                            $deleteitemincart = $this->Model->delete('cart_item', array('item_id' => $item_id, 'user_id' => $userid, 'status' => 0, 'store_id' => $storeid));
                            if ($deleteitemincart) {
                                $resp = $this->Cart_model->getcartitem($userid, $storeid);
                                if (count($resp) > 0) {
                                    $response["error"]   = false;
                                    $response["item"]    = array();
                                    $response["message"] = "Item added successfully in cart.";
                                    foreach ($resp as $item) {
                                        $item = array_map('utf8_encode', $item);
                                        array_push($response["item"], $item);
                                    }
                                } else {
                                    $response["error"]   = false;
                                    $response["message"] = "your cart is empty";
                                }
                            } else {
                                $response['error']   = true;
                                $response['message'] = "An error occurred. Please try again";
                            }
                        } else {
                            $response['error']   = true;
                            $response['message'] = 'Item is not avaliable in cart';
                        }
                    } else {
                        $response['error']   = true;
                        $response['message'] = 'invalid item';
                    }
                } else {
                    $response['error']   = true;
                    $response['message'] = 'invalid auth key';
                }
            } else {
                $response["error"]   = true;
                $response["message"] = "Invalid Store";
            }
        } else {
            $response["error"]   = true;
            $response["message"] = strip_tags(validation_errors());
        }
        responseJSON($response);
    }

    //============check_promocode================
    public function check_promocode()
    {
        if ($this->session->has_userdata('go2grouser')) {
            $response = array();
            $this->form_validation->set_rules('coupon_code', 'Promocode', 'required');
            if ($this->form_validation->run() == true) {
                $storeid     = get_selected_storeid();
                $userid      = $this->session->userdata["go2grouser"]['id'];
                $check_store = $this->Model->get_record('stores', array('id' => $storeid, 'status' => 'active'));
                if (count($check_store) > 0) {
                    $auth      = $this->input->post('Authorization');
                    $promocode = $this->input->post('coupon_code');
                    //------is_promocode_valid_and_applicable
                    $is_promocode_valid = $this->Model->get_record('promocode', array('status' => 'active', 'end_date>=' => time(), 'start_date <' => time()));
                    if (count($is_promocode_valid) > 0) {
                        $promocode_rec = $is_promocode_valid[0];
                        $discount_id   = $promocode_rec['id'];
                        $discount      = 0;
                        $status_flag   = false;
                        /*
                         * If discount through referral code is being availed, cant use promocode
                         */
                        $referral_discount = $this->Model->get_selected_data(array('referred_by', 'referred_to', 'redeemed_referral_count', 'max_referrals_allowed'), "users", array('id' => $userid));
                        if (count($referral_discount) > 0) {
                            $referral_discount = $referral_discount[0];
                            if (isset($referral_discount['referred_by']) && $referral_discount['referred_by'] != '') {
                                $referred_by          = json_decode($referral_discount['referred_by']);
                                $referred_by_redeemed = $referred_by->is_redeemed;
                                if (isset($referred_by_redeemed) && $referred_by_redeemed == false) {
                                    $discount = REFERRAL_DISCOUNT;
                                }
                                else {
                                    $referred_to             = json_decode($referral_discount['referred_to']);
                                    $max_referrals_allowed   = $referral_discount['max_referrals_allowed'];
                                    $redeemed_referral_count = $referral_discount['redeemed_referral_count'];
                                    if ($redeemed_referral_count < $max_referrals_allowed) {
                                        if (count($referred_to) >= $redeemed_referral_count + 1) {
                                            $discount = REFERRAL_DISCOUNT;
                                        }
                                    }
                                }
                            }
                        }

                        if (!$discount) {
                            $status_flag = true;
                        } else {
                            $error_msg = "Promocode cannot be applied if referral discount applicable ". $discount;
                        }
                        /*
                         * check if user has applied this promocode times allowed per user
                         */
                        if ($status_flag) {
                            $count_of_promocode_applicable_per_user = $promocode_rec['allowed_per_user'];

                            //--------------promocode_applied_by_user-----------
                            $count_of_promocode_applied_by_user = $this->Model->gettwodata(array('up.id'), 'user_promocode as up', 'promocode as p', 'p.id=up.promocode_id', array('p.code' => $promocode, 'up.user_id' => $userid));
                            if (count($count_of_promocode_applied_by_user) < $count_of_promocode_applicable_per_user) {
                                $status_flag = true;
                            } else {
                                $status_flag = false;
                            }

                        }

                        if ($status_flag) {
                            $subtotal   = 0;
                            $cart_items = array();
                            $resp       = $this->Cart_model->getcartitem($userid, $storeid);
                            if (count($resp) > 0) {

                                foreach ($resp as $item) {
                                    array_push($cart_items, $item); // can be used in below final response if flag is true
                                    $subtotal += $item['item_price'] * $item['item_quty'];
                                }
                            } else {
                                $error_msg = "your cart is empty";
                            }
                            /*
                             * Check for minimum order size for this promocode to be applied
                             */
                            if ($subtotal) {
                                if (!empty($promocode_rec['min_order_amount'])) {
                                    if ($subtotal < $promocode_rec['min_order_amount']) {
                                        $status_flag = false;
                                        $error_msg   = 'Minimum order amount for this promocode is $' . $promocode_rec['min_order_amount'];
                                    } else {
                                        $status_flag = true;
                                    }
                                }

                                /*
                                 * If type = percentage, check for max_discount_amount
                                 */
                                if ($status_flag) {
                                    if ($promocode_rec['ctype'] == 'percentage') {
                                        $percentage_of_amount = $promocode_rec['cvalue'] * 0.01;
                                        $final_discount       = $discount_amount       = round($subtotal * $percentage_of_amount, 2);

                                        if (!empty($promocode_rec['max_discount_amount'])) {
                                            if ($discount_amount > $promocode_rec['max_discount_amount']) {
                                                $final_discount = $promocode_rec['max_discount_amount'];
                                            }
                                        }
                                    } else {
                                        $final_discount = $promocode_rec['cvalue'];
                                    }
                                }
                            }
                        }
                    }
                    /*
                     * All checks passed return the same object as checkout with updated dicount amount
                     */
                    if ($status_flag) {
                        $store                              = $check_store[0];
                        $response["error"]                  = false;
                        $response["delivery_charges_label"] = "Delivery charge";
                        $response["delivery_charges"]       = $store['delivery_charge'];

                        $is_membership = is_membership_applicable($userid, $subtotal, MEMBERSHIP_APPLICABLE_SUBTOTAL);
                        //if order amount limit exceed to store free delivery amount
                        if ($subtotal >= $store['free_delivery_amount']) {
                            $response["delivery_charges_label"] = "Free Delivery";
                            $response["delivery_charges"]       = 0;
                        } elseif ($is_membership) //if order amount exceed to membership order amount
                        {
                            $response["delivery_charges_label"] = "Delivery charge (Membership Plan)";
                            $response["delivery_charges"]       = MEMBERSHIP_DELIVERY_CHARGE;
                        }
                        $charges                    = $this->Model->get_selected_data(array('delivery_charges', 'processing_fee'), "charges_rule");
                        $chk                        = $this->Cart_model->ischeckfirsetorder($userid);
                        $response["processing_fee"] = $charges[0]['processing_fee'];
                        $response["discount"]       = $final_discount;
                        $response["discount_id"]    = $discount_id;
                        $response["discount_type"]  = "promotional";
                        $response["discount_label"] = "Promotional Code Discount";
                        $response["message"]        = "Cart details fetched successfully!";
                        $response["item"]           = $cart_items;
                        $response["isfirstorder"]   = $chk;
                        $response['tips_arr']       = unserialize(TIPS_ARR);
                    } else {
                        $response["error"]   = true;
                        $response["message"] = $error_msg;
                    }
                } else {
                    $response["error"]   = true;
                    $response["message"] = "Invalid Store";
                }
            } else {
                $response["error"]   = true;
                $response["message"] = strip_tags(validation_errors());
            }
            responseJSON($response);
        } else {
            redirect('login');
        }
    }

    //================updatecartvalue=========
    public function updatecartvalue()
    {
        if ($this->session->has_userdata('go2grouser')) {
            $response = array();
            $this->form_validation->set_rules('item_id', 'item id', 'required');
            if ($this->form_validation->run() == true) {
                $auth      = $this->input->post('Authorization');
                $storeid   = get_selected_storeid();
                $userid    = $this->session->userdata["go2grouser"]['id'];
                $itemids   = $this->input->post('item_id');
                $cartitems = explode(",", $itemids);
                $count     = count($cartitems);
                $valcount  = 0;
                $countitem = 0;
                $resval    = array();

                foreach ($cartitems as $itemid) {
                    $itemid = trim($itemid);
                    //--------getitemfromcart----------
                    $res = $this->Model->get_record('cart_item', array('item_id' => $itemid, 'user_id' => $userid, 'store_id' => $storeid));
                    if ($res) {
                        $countitem++;
                        //--------getpricetaxitem--------
                        $item_table = STORE_PREFIX . $storeid . '_' . ITEMS_TABLE;
                        $item_data  = $this->Model->get_selected_data(array('item_price', 'Sales_tax'), $item_table, array('item_id' => $itemid));
                        $item_price = $item_data[0]['item_price'];
                        $item_tex   = $item_data[0]['Sales_tax'];

                        $update_cart = $this->Model->update('cart_item', array('price' => $item_price, 'tax' => $item_tex), array('item_id' => $itemid, 'user_id' => $userid, 'store_id' => $storeid, 'status' => 0));
                        if ($update_cart) {
                            $valcount++;
                        }
                        $chk                      = $this->Cart_model->ischeckfirsetorder($userid);
                        $response["isfirstorder"] = $chk;
                        if ($countitem != 0) {
                            if ($valcount == $countitem) {
                                $response['messsage'] = "cart update sucessfully";
                                $response['error']    = false;
                                $response['countval'] = $valcount;
                            } else {
                                $response['messsage'] = "An error occurred. Please try again";
                                $response['error']    = true;
                                $response['countval'] = $valcount;
                            }

                        } else {
                            $response['messsage'] = "No item found in your cart";
                            $response['error']    = true;
                            $response['countval'] = $valcount;
                        }
                    }
                }
            } else {
                $response["error"]   = true;
                $response["message"] = strip_tags(validation_errors());
            }
            responseJSON($response);
        } else {
            redirect('Login');
        }
    }
    //----------ContactUs------------------
    public function ContactUs()
    {
        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('emailid', 'Email', 'required');
        $this->form_validation->set_rules('comment', 'Comment', 'required');
        if ($this->form_validation->run() == true) {
            $time  = time();
            $name  = $this->input->post('name');
            $email = $this->input->post('emailid');
            $data  = array("username" => $name,
                "emailid"                 => $email,
                "mobile"                  => $this->input->post('mobile'),
                "comment"                 => $this->input->post('comment'),
                "unitime"                 => $time);
            $chk = $this->Model->add('contactus', $data);
            if (count($chk) > 0) {
                $username               = $name;
                $udata['username']      = $data;
                $subject                = 'Welcome,' . $name . ' to the Go2Gro family';
                $mail_msg               = $this->load->view('go2gro_web/template/contactus', $udata, true);
                $ismailsend             = $this->general->send_mail($email, "Contact us", $mail_msg);
                $response["error"]      = false;
                $response["ismailsend"] = $ismailsend;
                $response["message"]    = "contact request send sucessfuly";
            } else {
                $response["error"]   = true;
                $response["message"] = "something went wrong please try again";
            }
        } else {
            $response["error"]   = true;
            $response["message"] = strip_tags(validation_errors());
        }
        responseJSON($response);
    }

    //------------------get_membership_plan--------------
    public function get_membership_plan()
    {
        $user_id                     = $this->session->userdata("go2grouser")['id'];
        $auth                        = $this->input->post('Authorization');
        $response["membership_plan"] = array();
        $user                        = $this->Model->get_record('users', array('id' => $user_id));
        //var_dump($user);exit;
        $memshipplan_data  = $this->Model->get_selected_data(array(`id`, `plan_name`, `price`, `duration`, `description`, `create_at`), 'membership_plan', array('status' => 'active', 'del_status' => 0));
        $response["error"] = false;
        if (count($memshipplan_data) > 0) {
            foreach ($memshipplan_data as $task) {
                array_push($response["membership_plan"], $task);
            }
        } else {
            $response["error"]   = true;
            $response['message'] = "Membership Plan not avaliable";
        }
        if ($user[0]['membership_plan_id'] > 0) {
            $check_expire      = json_decode($user[0]['membership_date']);
            $current_time      = date("Y-m-d H:i:s", time()); // Getting Current Date & Time
            $current_timestamp = strtotime($current_time);
            if ($check_expire->expire >= $current_timestamp) {
                $response["planid"]  = $user[0]['membership_plan_id'];
                $response["status"]  = "Valid";
                $response['message'] = "Currently Active membership plan";
            } else {
                $response["status"]  = "Invalid";
                $response['message'] = "Your membership plan is expire";
            }
        } else {
            $response["status"] = "Available";
        }
        responseJSON($response);
    }

    //---------------reer to friend---------
    public function refer_to_friend()
    {
        $user_id              = $this->session->userdata("go2grouser")['id'];
        $auth                 = $this->input->post('Authorization');
        $user                 = $this->Model->get_record('users', array('id' => $user_id));
        $offers_redeemed      = 0;
        $full_name            = ucwords(trim($user[0]['first_name'])) . ' ' . ucwords(trim($user[0]['last_name']));
        $referral_code        = $user[0]['referral_code'];
        $referral_discount    = REFERRAL_DISCOUNT;
        $response["error"]    = false;
        $result               = $this->Model->get_selected_data(array('referred_by', 'redeemed_referral_count'), 'users', array('id' => $user_id));
        $referred_by          = json_decode($result[0]['referred_by'], true);
        $offers_redeemed      = $offers_redeemed + $result[0]['redeemed_referral_count'];
        $referred_by_redeemed = $referred_by['is_redeemed'];
        if ($referred_by_redeemed) {
            $offers_redeemed++;
        }
        if ($offers_redeemed > 0) {
            $response['title_message'] = "You have earned $offers_redeemed rewards.";
        } else {
            $response['title_message'] = "You are yet to earn your reward.";
        }
        $response["share_message"]    = "$full_name has sent you the link to Go2Gro.\nUse this Referral Code " . $referral_code . " to get $$referral_discount off on your first order.\nPlaystore - https://bit.ly/2Lh3fjG\nAppstore - https://apple.co/2EtU7b5 \nWeb - https://www.go2gro.com";
        $response["store_share_link"] = "https://play.google.com/store/apps/details?id=com.go2gro_user.go2gro&hl=en_IN";
        $response["web_share_link"]   = "https://www.go2gro.com/";
        responseJSON($response);
    }
    //------------getCountry---------
    public function getCountry()
    {
        if ($this->session->has_userdata('go2grouser')) {
            $auth                = $this->input->get('Authorization');
            $country             = $this->Model->get_all_record('countries');
            $response["country"] = array();
            if (count($country) > 0) {
                foreach ($country as $key => $value) {
                    array_push($response["country"], $value);
                }
                $response["error"]   = false;
                $response["message"] = "country list avaliable";
            } else {
                $response["error"]   = true;
                $response["message"] = "No Record Found";
            }
            responseJSON($response);
        } else {
            redirect('Login');
        }
    }
    //------------getState---------
    public function getState()
    {
        if ($this->session->has_userdata('go2grouser')) {
            $cid               = $this->input->get('cid');
            $auth              = $this->input->get('Authorization');
            $states            = $this->Model->get_record('states', array('country_id' => $cid));
            $response["state"] = array();
            if (count($states) > 0) {
                foreach ($states as $key => $value) {
                    array_push($response["state"], $value);
                }
                $response["error"]   = false;
                $response["message"] = "states get sucessfuly";
            } else {
                $response["error"]   = true;
                $response["message"] = "The requested resource doesn't exists";
            }
            responseJSON($response);
        } else {
            redirect('Login');
        }
    }

    //------------getCity---------
    public function getCity()
    {
        if ($this->session->has_userdata('go2grouser')) {
            $state_id         = $this->input->get('city_id');
            $auth             = $this->input->get('Authorization');
            $cities           = $this->Model->get_record('cities', array('state_id' => $state_id));
            $response["city"] = array();
            if (count($cities) > 0) {
                foreach ($cities as $key => $value) {
                    array_push($response["city"], $value);
                }
                $response["error"]   = false;
                $response["message"] = "city get sucessfuly";
            } else {
                $response["error"]   = true;
                $response["message"] = "The requested resource doesn't exists";
            }
            responseJSON($response);
        } else {
            redirect('Login');
        }
    }

    //------------Time Solt---------
    public function getDeliverySlots()
    {
        if ($this->session->has_userdata('go2grouser')) {
            $this->session->userdata('go2grouser');
            $storeid     = get_selected_storeid();
            $userid      = $this->session->userdata["go2grouser"]['id'];
            $time        = time();
            $timehours   = date('H:i', $time);
            $currentdate = date('Y-m-d', $time);
            $converttime = substr($timehours, 0, 2);
            $start_date  = strtotime($currentdate);
            $response    = array('slots' => array());
            $abcd        = array();
            $check_store = $this->Model->get_record('stores', array('id' => $storeid, 'status' => 'active'));
            if (count($check_store) > 0) {
                $stores     = $check_store[0];
                $workingday = json_decode($stores['working_daytime'], true);
                foreach ($workingday as $work_key => $day_time_value) {
                    $starttime = substr(date('H', strtotime($day_time_value['opening_time'])), 0, 2);
                    $endtime   = substr(date('H', strtotime($day_time_value['closing_time'])), 0, 2);

                    for ($i = 0; $i <= 6; $i++) {
                        $end_date  = strtotime("+$i day", $start_date);
                        $date      = date('Y-m-d', $end_date);
                        $timestamp = strtotime("+$i day", $time);
                        $daybydate = strtotime($date);
                        $storeday  = date('D', $daybydate);
                        $day       = date('l', $daybydate);
                        if ($day == ucfirst($work_key) && ($day_time_value['opening_time'] != 'closed' && $day_time_value['closing_time'] != 'closed')) {

                            if (ucfirst($work_key) == date('l')) {
                                $work_key = "Today";
                            }
                            $store_close_time = date('H:i', strtotime($day_time_value['closing_time']));
                            $store_close_time = substr($store_close_time, 0, 2);

                            $result  = $this->Model->getslot($storeid, $date, $storeday, $currentdate, $converttime, $starttime, $endtime);
                            $new_arr = $result;
                            //----------------last time get-------------------------
                            $lst_index = count($result) - 1;
                            //----------------last time get-------------------------
                            $perDayslot = array();
                            if (count($result) > 0) {
                                foreach ($result as $key => $slots) {
                                    $slots["isShowTIme"] = 1;
                                    //Amit Code Start
                                    $currentdate = date('Y-m-d', $time);
                                    if ($date == $currentdate) {
                                        $slot_start_time = substr($slots["start_time"], 0, 2);
                                        $slot_time       = substr($slots["end_time"], 0, 2);
                                        //------------within 2 hrs delaivery time hide
                                        if ($slots["slot_name"] == "Within 2 hours") {
                                            $start_two_hours_time          = $slot_start_time - 1;
                                            $slots["start_two_hours_time"] = $start_two_hours_time;
                                            $slots["converttime"]          = $converttime;
                                            if ($converttime >= $start_two_hours_time && $converttime < $slot_time) {
                                                $slots["isShowTIme"] = 0;
                                            } else {
                                                $slots["isShowTIme"] = 1;
                                            }
                                        } //------------within 2 hrs delaivery time hide end
                                        else if ($key == $lst_index) {
                                            //store closeing time manage last timeslot
                                            $last_slot_name = $result[$lst_index]['slot_name'];
                                            if ($store_close_time >= $slot_time && $converttime < $slot_time) {
                                                if ($slots["slot_name"] == $last_slot_name) {
                                                    $slots["isShowTIme"] = 0;
                                                }
                                            }
                                            //$slot_time = substr($slots["end_time"], 0, 2);
                                        } else {
                                            $slot_time    = substr($slots["end_time"], 0, 2);
                                            $timehours2   = date('H:i', $time);
                                            $converttime2 = substr($timehours2, 0, 2);
                                            $converttime2 = $converttime2 + 2;
                                            if ($converttime2 <= $slot_time) {
                                                $slots["isShowTIme"] = 0;
                                            } else {
                                                $slots["isShowTIme"] = 1;
                                            }
                                        }
                                    } else {
                                        $slots["isShowTIme"] = 0;
                                        if ($slots["slot_name"] == "Within 2 hours") {
                                            $slots["isShowTIme"] = 1;
                                        }
                                    }
                                    //Amit Code Ends
                                    array_push($perDayslot, $slots);
                                }
                            }
                            $abcd[] = array("date" => $date, "perdayslot" => $perDayslot, "unitime" => $timestamp, "converted" => $converttime, "day" => $day, "work" => ucfirst($work_key));
                            array_push($response['slots'], $abcd);
                        }
                    }
                    //-----------sorting array by date------
                    $new_arr = array();
                    if (count($abcd) > 0) {
                        $new_arr = array();
                        foreach ($abcd as $key => $row) {
                            $new_arr[$key]['date']       = $row['date'];
                            $new_arr[$key]['perdayslot'] = $row['perdayslot'];
                            $new_arr[$key]['unitime']    = $row['unitime'];
                            $new_arr[$key]['converted']  = $row['converted'];
                            $new_arr[$key]['day']        = $row['day'];
                            $new_arr[$key]['work']       = $row['work'];
                        }
                        array_multisort($new_arr, SORT_ASC, $abcd);
                    }
                    $response["error"]   = false;
                    $response['slots']   = $new_arr;
                    $response["message"] = "all slot avaliable";
                }
            } else {
                $response["error"]   = true;
                $response["message"] = "Invalid Store";
            }
            responseJSON($response);
        } else {
            redirect('Login');
        }
    }

    //------------payment get save card------
    public function getsavecard()
    {
        if ($this->session->has_userdata('go2grouser')) {
            $userid  = $this->session->userdata["go2grouser"]['id'];
            $result  = $this->Model->get_record('user_save_card', array('user_id' => $userid, 'status' => 0));
            $result1 = $this->Model->get_record('user_save_card', array('user_id' => $userid, 'status' => 1));
            if (count($result) > 0) {
                $response['card'] = array();
                foreach ($result as $paycard) {
                    array_push($response["card"], $paycard);
                }
                $response["error"]   = false;
                $response["type"]    = "0";
                $response["message"] = "crad get sucessfuly";
            } elseif (count($result1) > 0) {
                $response["error"]             = false;
                $response["type"]              = "1";
                $response["message"]           = "previous customer profile id";
                $response["customerProfileId"] = $result1[0]['customerProfileId'];
            } else {
                $response["error"]   = true;
                $response["message"] = "The requested resource doesn't exists";
            }
            responseJSON($response);
        } else {
            redirect('Login');
        }
    }

    //-----------payment card save-----
    public function saveCard()
    {
        if ($this->session->has_userdata('go2grouser')) {
            $response = array();
            $this->form_validation->set_rules('customerPaymentProfileId', 'customerPaymentProfileId', 'required');
            $this->form_validation->set_rules('customerProfileId', 'customerProfileId', 'required');
            $this->form_validation->set_rules('biladdress_card', 'biladdress_card', 'required');
            $this->form_validation->set_rules('bilzipcode_card', 'bilzipcode_card', 'required');
            $this->form_validation->set_rules('cardnumber', 'cardnumber', 'required');
            $this->form_validation->set_rules('cardType', 'cardType', 'required');
            if ($this->form_validation->run() == true) {
                $auth                     = $this->input->post('Authorization');
                $userid                   = $this->session->userdata["go2grouser"]['id'];
                $customerPaymentProfileId = $this->input->post('customerPaymentProfileId');
                $customerProfileId        = $this->input->post('customerProfileId');
                $biladdress_card          = $this->input->post('biladdress_card');
                $bilzipcode_card          = $this->input->post('bilzipcode_card');
                $cardnumber               = $this->input->post('cardnumber');
                $cardType                 = $this->input->post('cardType');
                if ($customerPaymentProfileId != "NA" && $customerProfileId != "NA") {
                    $chk = $this->Model->get_record('user_save_card', array('customerPaymentProfileId' => $customerPaymentProfileId, 'customerProfileId' => $customerProfileId, 'user_id' => $userid));
                    if (count($chk) > 0) {
                        $response["error"]   = true;
                        $response["message"] = "card already added";
                    } else {
                        $uuid = $this->db->set('card_id', 'UUID()', false);
                        $data = array('user_id'    => $userid, 'customerProfileId'         => $customerProfileId,
                            'customerPaymentProfileId' => $customerPaymentProfileId,
                            'bill_address_card'        => $biladdress_card, 'bil_zipcode_card' => $bilzipcode_card,
                            'card_number'              => $cardnumber, 'cardtype'              => $cardType);
                        $lastid = $this->Model->add('user_save_card', $data);
                        if ($lastid) {
                            $result              = $this->Model->get_selected_data('card_id', 'user_save_card', array('id' => $lastid));
                            $response["error"]   = false;
                            $response["card_id"] = $result[0]['card_id'];
                            $response["message"] = "card save successfully";
                        } else {
                            $response["error"]   = true;
                            $response["message"] = "something went wrong please try again";
                        }
                    }
                } else {
                    $response["error"]   = true;
                    $response["message"] = "Information Not Valid";
                }
            } else {
                $response['error']   = true;
                $response['message'] = strip_tags(validation_errors());
            }
            responseJSON($response);
        } else {
            redirect('Login');
        }
    }

    //----------placeorder---------
    public function placeorder()
    {
        if ($this->session->has_userdata('go2grouser')) {
            $this->form_validation->set_rules('ord_processingfee', 'processing fee', 'required');
            $this->form_validation->set_rules('ord_txnid', 'ord_txnid', 'required');
            $this->form_validation->set_rules('ord_totalprice', 'Order totla price', 'required');
            $this->form_validation->set_rules('ord_tax', 'order tax', 'required');
            $this->form_validation->set_rules('ord_coupanid', 'order coupanid', 'required');
            $this->form_validation->set_rules('ord_finlprice', 'order finlprice', 'required');
            $this->form_validation->set_rules('ord_processingfee', 'order processingfee', 'required');
            $this->form_validation->set_rules('ord_deliverycharge', 'order deliverycharge', 'required');
            $this->form_validation->set_rules('bil_firstname', 'Firstname', 'required');
            $this->form_validation->set_rules('bil_lastname', 'Lastname ', 'required');
            //$this->form_validation->set_rules('bil_address','Address','required');
            $this->form_validation->set_rules('bil_email', 'Email', 'required');
            $this->form_validation->set_rules('bil_contact', 'Contact', 'required');
            $this->form_validation->set_rules('bil_countryid', 'Country', 'required');
            $this->form_validation->set_rules('bil_stateid', 'State', 'required');
            $this->form_validation->set_rules('bil_cityid', 'City', 'required');
            $this->form_validation->set_rules('bil_pincode', 'Zipcode', 'required');
            $this->form_validation->set_rules('pay_email', 'pay_email', 'required');
            $this->form_validation->set_rules('payerID', 'payerID', 'required');
            $this->form_validation->set_rules('tip_amount', 'tip_amount', 'required');
            $this->form_validation->set_rules('slotid', 'slotid', 'required');
            $this->form_validation->set_rules('develydate', 'develydate', 'required');
            $this->form_validation->set_rules('AuthoAmount', 'AuthoAmount', 'required');
            $this->form_validation->set_rules('card_id', 'card_id', 'required');

            if ($this->form_validation->run() == true) {
                $storeid            = get_selected_storeid();
                $userid             = $this->session->userdata["go2grouser"]['id'];
                $auth1              = $this->input->post('Authorization');
                $bil_firstname      = $this->input->post('bil_firstname');
                $bil_contact        = $this->input->post('bil_contact');
                $bil_email          = $this->input->post('bil_email');
                $bil_lastname       = $this->input->post('bil_lastname');
                $bil_address        = json_encode($this->input->post('bil_address')); // json_encode as the address is updated now to be an array
                $bil_countryid      = $this->input->post('bil_countryid');
                $bil_stateid        = $this->input->post('bil_stateid');
                $bil_cityid         = $this->input->post('bil_cityid');
                $bil_pincode        = $this->input->post('bil_pincode');
                $ord_txnid          = $this->input->post('ord_txnid');
                $ord_totalprice     = $this->input->post('ord_totalprice');
                $ord_finlprice      = $this->input->post('ord_finlprice');
                $ord_tax            = $this->input->post('ord_tax');
                $processing_fee     = $this->input->post('ord_processingfee');
                $ord_deliverycharge = $this->input->post('ord_deliverycharge');
                $payerID            = $this->input->post('payerID');
                $pay_email          = $this->input->post('pay_email');
                $pay_phone          = $this->input->post('pay_phone');
                $tip_amount         = $this->input->post('tip_amount');
                $card_id            = $this->input->post('card_id');
                $slotid             = $this->input->post('slotid');
                $develydate         = $this->input->post('develydate');
                $authAmount         = $this->input->post('AuthoAmount');
                $ord_coupanid       = $this->input->post('ord_coupanid');
                $discount_type      = $this->input->post('discount_type');
                $discount_id        = $this->input->post('discount_id');
                $discount_amount    = $this->input->post('discount_amount');

                $check_store = $this->Model->get_record('stores', array('id' => $storeid, 'status' => 'active'));
                if (count($check_store) > 0) {
                    if ((int) $tip_amount >= 0) {
                        $timedate = time();
                        $resp     = $this->Cart_model->getCartItem($userid, $storeid);
                        if (count($resp) > 0) {
                            $table_name_prefix = STORE_PREFIX . $storeid;
                            $tbl_time_slot     = $table_name_prefix . '_' . TIME_SLOT;
                            $getStarttime      = $this->Model->get_record($tbl_time_slot, array('time_slot_id' => $slotid));
                            if (count($getStarttime) > 0) {
                                $getStarttime = $getStarttime[0];
                                if ($getStarttime['slot_name'] == "Within 2 hours") {
                                    $deliveryUnitime = $timedate + 2 * 60 * 60;
                                } else {
                                    $deliveryUnitime = strtotime($develydate . " " . $getStarttime['end_time']);
                                }
                                //-------cartsubtotal------
                                $resp1 = $this->Model->get_selected_data('round (sum(`price`*`item_quty`),2) as subtotal', 'cart_item', array('user_id' => $userid, 'status' => '0', 'store_id' => $storeid));
                                if (count($resp1) > 0) {
                                    $cartsubtotal = $resp1[0]['subtotal'] + $ord_deliverycharge + $processing_fee + $ord_tax + $tip_amount;
                                    $cartsubtotal = (string) $cartsubtotal;
                                    $orderid      = "ORD" . time() . rand(1000, 9999);
                                    $item_count   = $this->Model->get_selected_data('COUNT(id) as item_count', 'cart_item', array('user_id' => $userid, 'status' => '0', 'store_id' => $storeid));
                                    $item_count   = $item_count[0]['item_count'];
                                    $varity_count = $this->Model->getVarityCount($userid, $storeid);
                                    $varity_count = count($varity_count);
                                    $ordered_from = 'WEB';
                                    $orderdata    = array('order_id' => $orderid, 'user_id'            => $userid, 'txn_id'             => $ord_txnid,
                                        'total_price'                    => $ord_totalprice, 'tax'         => $ord_tax, 'coupanid'          => $ord_coupanid,
                                        'discount_amount'                => $discount_amount, 'finalprice' => $ord_finlprice,
                                        'datetime'                       => $timedate, 'processingfee'     => $processing_fee, 'dlv_charge' => $ord_deliverycharge,
                                        'slot_id'                        => $slotid, 'dlv_date'            => $develydate, 'pay_email'      => $pay_email,
                                        'pay_phone'                      => $pay_phone, 'payerID'          => $payerID, 'tip_amount'        => $tip_amount,
                                        'auth_amount'                    => $authAmount, 'card_id'         => $card_id, 'delivery_time'     => $deliveryUnitime,
                                        'item_count'                     => $item_count, 'varity_count'    => $varity_count,
                                        'ordered_from'                   => $ordered_from, 'store_id'      => $storeid,
                                    );
                                    $lastid = $this->Model->add('order_table', $orderdata);
                                    if ($lastid) {
                                        $ordupdate = false;
                                        //----------updateorderitem---------
                                        $cartitemlist = $this->Model->get_record('cart_item', array('user_id' => $userid, 'status' => '0', 'store_id' => $storeid));
                                        $item_data    = [];
                                        foreach ($cartitemlist as $itemcrt) {
                                            $item_data[] = array(
                                                'item_id'    => $itemcrt['item_id'],
                                                'item_quty'  => $itemcrt['item_quty'],
                                                'price'      => $itemcrt['price'],
                                                'tax'        => $itemcrt['tax'],
                                                'user_id'    => $itemcrt['user_id'],
                                                'order_id'   => $orderid,
                                                'status'     => '1', // Status is changed to 1 = ordered item
                                                'created_at' => date('Y-m-d H:i:s'),
                                            );
                                        }
                                        $add_item = $this->Model->batch_rec('ordered_item', $item_data);
                                        if ($add_item) {
                                            $ordupdate = true;
                                            foreach ($cartitemlist as $itemrmv) {
                                                $this->Model->delete('cart_item', array('item_id' => $itemrmv['item_id'], 'user_id' => $userid, 'status' => '0', 'store_id' => $storeid));
                                            }
                                        }
                                        //----------Shipping address---------
                                        $shipping_data = array('order_id' => $orderid, 'user_id'                => $userid,
                                            'first_name'                      => $bil_firstname, 'last_name'        => $bil_lastname,
                                            'address'                         => $bil_address, 'ship_mobile_number' => $bil_contact,
                                            'email_id'                        => $bil_email, 'country_id'           => $bil_countryid,
                                            'state_id'                        => $bil_stateid, 'city_id'            => $bil_cityid,
                                            'pincode'                         => $bil_pincode);
                                        $insert_shipping_add = $this->Model->add('shipping_address', $shipping_data);
                                        if (!empty($discount_type)) {
                                            if ($discount_type == 'referral') {
                                                $referral_discount = $this->Model->get_selected_data(array('referred_by', 'referred_to', 'redeemed_referral_count', 'max_referrals_allowed'), "users", array('id' => $userid));
                                                if (count($referral_discount) > 0) {
                                                    $referral_discount = $referral_discount[0];
                                                    if (isset($referral_discount['referred_by']) && $referral_discount['referred_by'] != '') {
                                                        $referred_by          = json_decode($referral_discount['referred_by']);
                                                        $referred_by_redeemed = $referred_by->is_redeemed;
                                                        if (isset($referred_by_redeemed) && $referred_by_redeemed == false) {
                                                            // update is_redeemed to true and exit
                                                            $referral_info_new_user['user_id']     = $referred_by->user_id;
                                                            $referral_info_new_user['unitime']     = time();
                                                            $referral_info_new_user['is_redeemed'] = true;
                                                            $referral_info_new_user['order_id']    = $orderid;
                                                            $new_referral_json_new_user            = json_encode($referral_info_new_user);
                                                            $this->Model->update('users', array('referred_by' => $new_referral_json_new_user), array('id' => $userid));
                                                        } else {
                                                            $max_referrals_allowed   = $referral_discount['max_referrals_allowed'];
                                                            $redeemed_referral_count = $referral_discount['redeemed_referral_count'];
                                                            if ($redeemed_referral_count < $max_referrals_allowed) {
                                                                $referred_to = json_decode($referral_discount['referred_to']);
                                                                if (count($referred_to) >= $redeemed_referral_count + 1) {
                                                                    foreach ($referred_to as $refrr) {
                                                                        if ($refrr->order_id == null) {
                                                                            $refrr->order_id = $order_id;
                                                                            $refrr->unitime  = time();
                                                                            break;
                                                                        } else {
                                                                            continue;
                                                                        }
                                                                    }
                                                                    $referral_json_user = json_encode($referred_to);
                                                                    $this->Model->update('users', array('referred_to' => $referral_json_user, 'redeemed_referral_count' => 'redeemed_referral_count'+1), array('id' => $userid));
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            } else if ($discount_type == 'promotional') {
                                                if (!empty($coupanid)) {
                                                    //-----------update_promotional_info----------
                                                    $get_promocode_rec = $this->Model->get_selected_data('id', 'promocode', array('id' => $coupanid));
                                                    if (count($get_promocode_rec) > 0) {
                                                        $this->Model->add('user_promocode', array('promocode_id' => $coupanid, 'user_id' => $userid, 'order_id' => $orderid,
                                                            'created_at'                                             => date('Y-m-d H:i:s')));
                                                    }
                                                }
                                            } else if ($discount_type == 'membership') {
                                                // yet to be developed
                                            }
                                        }
                                        //-----------change json address to string----------
                                        $new_address  = '';
                                        $json_address = json_decode($bil_address, true);
                                        if ($json_address['apt_no'] != '') {
                                            $new_address .= $json_address['apt_no'];
                                        }
                                        if ($json_address['complex_name'] != '') {
                                            $new_address .= ', ' . $json_address['complex_name'];
                                        }
                                        $new_address .= ', ' . $json_address['street_address'];
                                        $storename         = $this->Model->get_selected_data('name', 'stores', array('id' => $storeid));
                                        $table_name_prefix = STORE_PREFIX . $storeid;
                                        $time_sloat        = $table_name_prefix . '_' . TIME_SLOT;
                                        $slottime          = $this->Model->get_selected_data('slot_name', $time_sloat, array('time_slot_id' => $slotid));
                                        $mail_info         = array('storeid' => $storeid, 'storename'                         => $storename[0]['name'],
                                            'slottime'                           => $slottime[0]['slot_name'], 'orderid'          => $orderid,
                                            'datetime'                           => date('d-m-Y h:i:s A', $timedate),
                                            'fullname'                           => $bil_firstname . ' ' . $bil_lastname, 'txnid' => $ord_txnid,
                                            'tip_amount'                         => $tip_amount, 'bi_contact'                     => $bil_contact,
                                            'bi_email'                           => $bil_email, 'bi_address'                      => $new_address,
                                            'subtotal'                           => $ord_totalprice, 'totaltax'                   => $ord_tax,
                                            'processingfees'                     => $processing_fee, 'deliverycharge'             => $ord_deliverycharge,
                                            'finalprice'                         => $ord_finlprice, 'develydate'                  => $develydate);
                                        //---------Send mail------
                                        $mail_temp  = $this->load->view('go2gro_web/template/order_template', $mail_info, true);
                                        $ismailsend = $this->general->send_mail($bil_email, "Your Go2Gro order (" . $orderid . ") has been placed !", $mail_temp);
                                        //---------Send sms------
                                        $msg = "Dear " . $bil_firstname . " " . $bil_lastname . ", \n
                                            Thank you for using Go2Gro!\n
                                            Your Order id is " . $orderid . " \n
                                            Our dedicated team prepares your order as soon as you hit checkout, and bring it to your
                                            doorstep with a smile on our face. Your order will be with you shortly. We will contact you,
                                            if there are any changes in your order.";
                                        $sendsms                     = $this->general->sendsms($bil_contact, $msg);
                                        $response["error"]           = false;
                                        $response["itemupdate"]      = $ordupdate;
                                        $response["shipadressinser"] = $insert_shipping_add;
                                        $response["ismailsend"]      = $ismailsend;
                                        $response["issmssend"]       = $sendsms;
                                        $response["orderid"]         = $orderid;
                                        $response["message"]         = "order placed sucessfuly";
                                    } else {
                                        $response['error']   = true;
                                        $response['message'] = "An error occurred. Please try again";
                                    }
                                } else {
                                    $response['error']   = true;
                                    $response['message'] = "Subtotal error";
                                }
                            }
                        } else {
                            $response['error']   = true;
                            $response['message'] = "Cart empty";
                        }
                    } else {
                        $response['error']   = true;
                        $response['message'] = "Tip amount cannot be less than zero";
                    }
                } else {
                    $response['error']   = true;
                    $response['message'] = "Invalid Store";
                }
            } else {
                $response['error']   = true;
                $response['message'] = strip_tags(validation_errors());
            }
            responseJSON($response);
        } else {
            redirect('Login');
        }
    }

    //--------------getItemDescription------------
    public function getItemDescription()
    {
        $storeid  = get_selected_storeid();
        $itemId   = $this->input->get('itemId');
        $getstore = $this->Model->get_record('stores', array('id' => $storeid, 'status' => 'active'));
        if (count($getstore) > 0) {
            $re = $this->Model->isitemExists($itemId, $storeid);
            if ($re) {
                $result = $this->Model->getItem($itemId, $storeid);
                if ($result != null) {
                    if (count($result) > 0) {
                        foreach ($result as $row) {
                            $itm = array_map('utf8_encode', $row);
                        }
                        $reslink = $this->Model->admin_getitemimages($itemId, $storeid);
                        if (count($reslink) > 0) {
                            $imagesaaray = $reslink;
                        }
                        $response["error"]          = false;
                        $response["item"]           = $itm;
                        $response["item"]["images"] = $imagesaaray;
                        $response["message"]        = "list get sucessfuly";
                    } else {
                        $response["error"]   = true;
                        $response["message"] = "The requested resource doesn't exists";
                    }
                } else {
                    $response["error"]   = true;
                    $response["message"] = "The requested resource doesn't exists";
                }
            } else {
                $response["error"]   = true;
                $response["message"] = "The Item doesn't exists";
            }
            responseJSON($response);
        } else {
            $response["error"]   = true;
            $response["message"] = "Invalid Store ID!";
            responseJSON($response);
        }
    }

    //------------OrderHistory----------
    public function OrderHistory()
    {
        $userid   = $this->session->userdata("go2grouser")['id'];
        $storeid  = get_selected_storeid();
        $auth     = $this->input->get('Authorization');
        $response = array();
        $getstore = $this->Model->get_record('stores', array('id' => $storeid, 'status' => 'active'));
        if (count($getstore) > 0) {
            $chk = $this->Cart_model->getmyneworder($userid);
            if ($chk != null) {
                if (count($chk) > 0) {
                    $response["error"]          = false;
                    $response["order"]          = array();
                    $abc["trans_history"]       = array();
                    $response["currentunitime"] = time();
                    $response["message"]        = "order list get sucessfuly";
                    foreach ($chk as $subcat) {
                        if ($subcat['is_order_edit'] == "2") {
                            $subcatO = $subcat['order_id'];
                            $reds    = $this->Model->get_record('order_trans_history', array('orderid' => $subcatO));
                            if (count($reds) > 0) {
                                foreach ($reds as $subcatl) {
                                    array_push($abc["trans_history"], $subcatl);
                                }
                                $subcat['transhistory'] = $abc["trans_history"];
                            }

                        }
                        array_push($response["order"], $subcat);
                    }
                    responseJSON($response);
                } else {
                    $response["error"]   = true;
                    $response["message"] = "No Order Found";
                    responseJSON($response);
                }
            } else {
                $response["error"]   = true;
                $response["message"] = "The requested resource doesn't exists";
                responseJSON($response);
            }
        } else {
            $response["error"]   = true;
            $response["message"] = "Invalid Store ID!";
            responseJSON($response);
        }
    }

    //---------------Searchlist------
    public function searchList()
    {
        $storeid  = get_selected_storeid();
        $status   = $this->input->get('status');
        $pageno   = $this->input->get('page');
        $str      = $this->input->get('searchStr');
        $response = array();
        $str      = addslashes($str);
        if (strlen($str) > 4) {
            $strwithrmlast  = substr($str, 0, -1) . "<br>";
            $strwithrmlast2 = substr($str, 0, -2);
        } else {
            $strwithrmlast  = $str;
            $strwithrmlast2 = $str;
        }
        $getstore = $this->Model->get_record('stores', array('id' => $storeid, 'status' => 'active'));
        if (count($getstore) > 0) {
            if ($status == 0) {
                $resultcat = $this->Model->getitemsuggetionbycat($str, $storeid);
                if (count($resultcat) > 0) {
                    $response["error"]   = false;
                    $response["item"]    = $resultcat;
                    $response["message"] = "list get sucessfuly";
                } else {
                    $result = $this->Model->getitemsuggetionbystr($str, $storeid);
                    if (count($result) > 0) {
                        $response["error"]   = false;
                        $response["item"]    = $result;
                        $response["message"] = "list get sucessfuly";
                    } else {
                        $result1 = $this->Model->getitemsuggetion($str, $strwithrmlast, $strwithrmlast2, $storeid);
                        if (count($result1) > 0) {
                            $response["error"]   = false;
                            $response["item"]    = $result1;
                            $response["message"] = "list get sucessfuly";
                        } else {
                            $response["error"]   = true;
                            $response["message"] = "The requested resource doesn't exists";
                        }
                    }
                }
            } elseif ($status == 1) {
                $resultcatitem = $this->Model->getsuggetionitembycart($str, $pageno, $storeid);
                if (count($resultcatitem) > 0) {
                    $re                     = $this->Model->getsuggetionitembycartcount($str, $storeid);
                    $response["totalcount"] = count($re);
                    $response["error"]      = false;
                    $response["item"]       = $resultcatitem;
                    $response["message"]    = "list get sucessfuly";
                } else {
                    $result2 = $this->Model->getsuggetionitemsbystr($str, $pageno, $storeid);
                    if (count($result2) > 0) {
                        $re                     = $this->Model->getsuggetionitemscountbystr($str, $storeid);
                        $response["totalcount"] = count($re);
                        $response["error"]      = false;
                        $response["item"]       = $result2;
                        $response["message"]    = "list get sucessfuly";
                    } else {
                        $result3 = $this->Model->getsuggetionitems($str, $pageno, $strwithrmlast, $strwithrmlast2, $storeid);
                        if (count($result3) > 0) {
                            $re                     = $this->Model->getsuggetionitemscount($str, $strwithrmlast, $strwithrmlast2, $storeid);
                            $response["totalcount"] = count($re);
                            $response["error"]      = false;
                            $response["item"]       = $result3;
                            $response["message"]    = "list get sucessfuly";
                        } else {
                            $response["error"]   = true;
                            $response["message"] = "The requested resource doesn't exists 1";
                        }
                    }
                }
            } else {
                $response["error"]   = true;
                $response["message"] = "something went wrong please try again";
            }
        } else {
            $response["error"]   = true;
            $response["message"] = "Invalid Store ID!";
        }
        responseJSON($response);
    }

    //---------OrderDetail--------
    public function OrderDetail()
    {
        if ($this->session->has_userdata('go2grouser')) {
            $orderid           = $this->input->get('order_id');
            $store_id          = $this->input->get('store_id');
            $localselcet_store = get_selected_storeid();
            if ($store_id == $localselcet_store) {
                $userid   = $this->session->userdata("go2grouser")['id'];
                $response = array();
                $chk      = $this->Model->getorderbyid($orderid, $userid, $store_id);
                if (count($chk) > 0) {
                    $order                   = $chk[0];
                    $response["orderdetail"] = array();
                    $response["orderstatus"] = array();
                    $resp                    = $this->Model->getcartitembyorderid($orderid, $order['store_id']);
                    $ordstat                 = $this->Model->get_selected_data(array(`order_id`, `updateby_id`, `status`, `message`, `updatetime`), 'order_status_info', array('order_id' => $orderid), 'updatetime', 'DESC');
                    if ($order['is_order_edit'] == "2") {
                        $response["trans_history"] = array();
                        $reds                      = $this->Model->get_record('order_trans_history', array('orderid' => $orderid));
                        if (count($reds) > 0) {
                            foreach ($reds as $subcatl) {
                                array_push($response["trans_history"], $subcatl);
                            }
                        }
                    }
                    if (count($resp) > 0) {
                        foreach ($resp as $item) {
                            $item = array_map('utf8_encode', $item);
                            array_push($response["orderdetail"], $item);
                        }
                    }
                    if (count($ordstat) > 0) {
                        foreach ($ordstat as $ords) {
                            array_push($response["orderstatus"], $ords);
                        }
                    }

                    $response["error"]   = false;
                    $response["order"]   = $order;
                    $response["message"] = "order list get sucessfuly";
                    foreach ($chk as $subcat) {
                        array_push($response["order"], $subcat);
                    }
                } else {
                    $response["error"]   = true;
                    $response["message"] = "No Order Found";
                }
            } else {
                $response  = array("error" => true, "message" => "No stores available");
                $storedata = $this->Model->get_record('stores', array('id' => $orderid));
                $storename = $storedata[0]['name'];
                $response  = array('error' => true, 'message' => 'This order is placed in ' . $storename . ' store, please change your current store to ' . $storename . ' to view the order.');
            }
            responseJSON($response);
        } else {
            redirect('Login');
        }
    }

    //-----------Cancle order------
    public function cancelOrder()
    {
        if ($this->session->has_userdata('go2grouser')) {
            $auth1   = $this->input->post('Authorization');
            $orderid = $this->input->post('orderid');
            $storeid = get_selected_storeid();
            $userid  = $this->session->userdata("go2grouser")['id'];
            $resp    = $this->Model->getorderbyid($orderid, $userid, $storeid);
            if (count($resp) > 0) {
                $info           = $resp[0];
                $bi_name        = $info['ship_name'];
                $bi_address     = $info['shipping_address'];
                $bi_contact     = $info['ship_mobile'];
                $ship_pincode   = $info['ship_pincode'];
                $bi_email       = $info['email_id'];
                $txnid          = $info['txn_id'];
                $subtotal       = $info['total_price'];
                $totaltax       = $info['tax'];
                $tip_amount     = $info['tip_amount'];
                $processingfees = $info['dlv_charge'];
                $deliverycharge = $info['processingfee'];
                $finalprice     = $info['finalprice'];
                $timedate       = $info['datetime'];
                $currenttime    = time();
                $difference     = $currenttime - $timedate;
                $minutes        = 5; // order can be cancelled only within 5 mins of placing
                if ($difference > ($minutes * 60)) {
                    $response['error']   = true;
                    $response['message'] = "Cancellation Policy: Sorry! Orders can only be cancelled within 10 minutes of ordering. If you have any questions regarding your order you can also contact our Toll Free Number: +1 (833) 346-2476 ";
                } else {
                    $isvoid = $this->payment->voidTransaction($txnid);
                    if ($isvoid == "true") {
                        // when user is cancelling the order move items from ordered_item table to finished_item table
                        $cart_items = $this->Model->get_record('ordered_item', array('order_id' => $orderid));
                        $item_data  = [];
                        foreach ($cart_items as $itemcrt) {
                            $item_data[] = array(
                                'item_id'            => $itemcrt['item_id'],
                                'item_quty'          => $itemcrt['item_quty'],
                                'price'              => $itemcrt['price'],
                                'tax'                => $itemcrt['tax'],
                                'user_id'            => $itemcrt['user_id'],
                                'order_id'           => $itemcrt['order_id'],
                                'alernative_item_id' => $itemcrt['alernative_item_id'],
                                'status'             => $itemcrt['status'],
                            );
                        }
                        $add_item = $this->Model->batch_rec('finished_item', $item_data);
                        if ($add_item) {
                            $ordupdate = true;
                            foreach ($cart_items as $oid) {
                                $this->Model->delete('ordered_item', array('order_id' => $oid['order_id']));
                            }
                        }
                        $user = $this->Model->update('order_table', array('status' => 6), array('order_id' => $orderid, 'status' => 0));
                        if ($user) {
                            //-----------change json address to string
                            $new_address  = '';
                            $json_address = json_decode($bi_address, true);

                            if ($json_address['apt_no'] != '') {
                                $new_address .= $json_address['apt_no'];
                            }
                            if ($json_address['complex_name'] != '') {
                                $new_address .= ', ' . $json_address['complex_name'];
                            }
                            $new_address .= ', ' . $json_address['street_address'];
                            //-----------change json address to string end
                            $mail_info = array('orderid' => $orderid, 'datetime'          => date('d-m-Y h:i:s A', $timedate),
                                'fullname'                   => $bi_name, 'txnid'             => $txnid, 'tip_amount'        => $tip_amount,
                                'bi_contact'                 => $bi_contact, 'bi_email'       => $bi_email, 'bi_address'     => $new_address,
                                'subtotal'                   => $subtotal, 'totaltax'         => $totaltax, 'processingfees' => $processingfees,
                                'deliverycharge'             => $deliverycharge, 'finalprice' => $finalprice, 'store_id'     => $storeid);
                            //---------Send mail------
                            $mail_temp  = $this->load->view('go2gro_web/template/ordercancel', $mail_info, true);
                            $issendmail = $this->general->send_mail($bi_email, "Your Go2Gro order (" . $orderid . ") has been placed !", $mail_temp);

                            $response["error"]   = false;
                            $response["orderid"] = $orderid;
                            $response["isvoid"]  = $isvoid;
                            $response["message"] = "Your Order Cancel sucessfully";
                        } else {
                            // unknown error occurred
                            $response['error']   = true;
                            $response['message'] = "An error occurred. Please try again";
                        }
                    } else {
                        $response['error']   = true;
                        $response['message'] = "Something went wrong in void unsetteld";
                    }
                }
            } else {
                $response['error']   = true;
                $response['message'] = 'invalid Request By User';
            }
            responseJSON($response);
        } else {
            redirect('Login');
        }
    }

    //--------------------GetAlternateProductDetailApi------------
    public function GetAlternateProductDetailApi()
    {
        $orderid = $this->input->get('order_id');
        $userid  = $this->input->get('user_id');
        $role    = $this->input->get('role');
        if (isset($role) && $role == 'picker') {
            $storeid = $this->input->get('store');
        } else {
            $order_info = $this->Model->get_selected_data('store_id', 'order_table', array('order_id' => $orderid));
            if (count($order_info) > 0) {
                $storeid = $order_info[0]['store_id'];
            }
        }
        $check_store = $this->Model->get_record('stores', array('id' => $storeid, 'status' => 'active'));
        if (count($check_store) > 0) {
            $isUserExistsbyuserid = $this->Model->get_selected_data('id', 'users', array('id' => $userid));
            if ($isUserExistsbyuserid) {
                $checkordereditmode = $this->Model->get_selected_data('order_id', 'order_table', array('order_id' => $orderid, 'is_order_edit' => '1'));
                if ($checkordereditmode) {
                    $checkorderbyuser = $this->Model->get_record('order_table', array('order_id' => $storeid, 'user_id' => $userid));
                    if ($checkorderbyuser) {
                        $result = $this->Model->getalernativeitems($userid, $orderid, $storeid);
                        if (count($result) > 0) {
                            $chk = $this->Model->getorderbyid($orderid, $userid, $storeid);
                            if (count($chk) > 0) {
                                $order                   = $chk[0];
                                $response["orderdetail"] = $order;
                                $response["error"]       = false;
                                $response["itemlist"]    = array();
                                foreach ($result as $value) {
                                    $value = array_map('utf8_encode', $value);
                                    array_push($response["itemlist"], $value);
                                }
                                $response["message"] = "item get sucessfuly";
                            }
                        } else {
                            $response["error"]   = true;
                            $response["message"] = "The requested resource doesn't exists";
                        }
                    } else {
                        $response["error"]   = true;
                        $response["message"] = "order not belongs to this user";
                    }
                } else {
                    $response["error"]   = true;
                    $response["message"] = "order not in edit mode";
                }
            } else {
                $response["error"]   = true;
                $response["message"] = "User Not Valid";
            }
        } else {
            $response["error"]   = true;
            $response["message"] = "Invalid Store";
        }
        responseJSON($response);
    }

    /****************alerternated place order*/
    public function alternatPlaceorder()
    {
        if ($this->session->has_userdata('go2grouser')) {
            $response = array();
            $this->form_validation->set_rules('new_order_status', 'New Order Status', 'required');
            $this->form_validation->set_rules('orderstatus', 'Orderstatus', 'required');
            $this->form_validation->set_rules('order_id', 'orderid', 'required');
            $this->form_validation->set_rules('ord_tax', 'Order Tax', 'required');
            $this->form_validation->set_rules('ord_totalprice', 'Order Total price', 'required');
            $this->form_validation->set_rules('ord_finlprice', 'Order Final price', 'required');
            $this->form_validation->set_rules('item_detail', 'Item Details', 'required');
            if ($this->form_validation->run() == true) {
                $userid               = $this->session->userdata("go2grouser")['id'];
                $neworderstatus       = $this->input->post('new_order_status');
                $orderid              = $this->input->post('order_id');
                $ordtax               = $this->input->post('ord_tax');
                $totalprice           = $this->input->post('ord_totalprice');
                $finalprice           = $this->input->post('ord_finlprice');
                $rp_amount            = $this->input->post('rp_amount');
                $rp_status            = $this->input->post('rp_status');
                $rp_tranxid           = $this->input->post('rp_tranxid');
                $item_detail          = $this->input->post('item_detail');
                $orderstatus          = $this->input->post('orderstatus');
                $role                 = $this->input->post('role');
                $rp_compeletestatus   = $this->input->post('rp_compeletestatus');
                $timedate             = time();
                $status               = "1";
                $isUserExistsbyuserid = $this->Model->get_selected_data('id', 'users', array('id' => $userid));
                if ($orderstatus == 0) {
                    $checkOrderPicked = $this->Model->get_selected_data('id', 'pd_order', array('order_id' => $orderid));
                    if ($checkOrderPicked) {
                        $orderstatus = 1;
                    } else {
                        $orderstatus = 0;
                    }
                }
                if ($isUserExistsbyuserid) {
                    $resultmode = $this->Model->get_selected_data('order_id', 'order_table', array('order_id' => $orderid, 'is_order_edit' => $status));
                    if ($resultmode) {
                        $resultord = $this->Model->get_record('order_table', array('order_id' => $orderid, 'user_id' => $userid));
                        if ($resultord) {
                            if ($neworderstatus == "0") {
                                $iseditmode = "2";
                                $resp       = $this->Model->update('order_table', array('status' => $orderstatus, 'is_order_edit' => $iseditmode, 'alternate_approval_role' => $role), array('order_id' => $orderid));
                                if (!(isset($role) && $role == 'picker')) {
                                    notify_picker_for_order_update($orderid);
                                }
                                $orderitemlist = $this->Model->get_record('ordered_item', array('order_id' => $orderid));
                                $item_data     = [];
                                foreach ($orderitemlist as $itemcrt) {
                                    $item_data[] = array(
                                        'item_id'    => $itemcrt['item_id'],
                                        'item_quty'  => $itemcrt['item_quty'],
                                        'price'      => $itemcrt['price'],
                                        'tax'        => $itemcrt['tax'],
                                        'user_id'    => $itemcrt['user_id'],
                                        'order_id'   => $orderid,
                                        'status'     => '1', // Status is changed to 1 = ordered item
                                        'created_at' => date('Y-m-d H:i:s'),
                                    );
                                }
                                $add_item = $this->Model->batch_rec('finished_item', $item_data);
                                if ($add_item) {
                                    $ordupdate = true;
                                    foreach ($orderitemlist as $itemrmv) {
                                        $this->Model->delete('ordered_item', array('order_id' => $orderid));
                                    }
                                }
                                $response["error"]           = false;
                                $response["mainorderupdate"] = $resp;
                                $response["message"]         = "order cancel successfully";
                            } elseif ($neworderstatus == "1") {
                                $res = $this->Model->update('order_table', array('total_price' => $totalprice, 'tax' => $ordtax, 'finalprice' => $finalprice), array('order_id' => $orderid));
                                if ($res) {
                                    $counter = 0;
                                    $data    = json_decode($item_detail, true);
                                    foreach ($data as $key => $arrays) {
                                        foreach ($arrays as $array) {
                                            //$str= implode(',' ,$array);
                                            $alt_item = $array['alt_itemid'];
                                            $status   = $array['status'];
                                            $itemid   = $array['itemid'];
                                            $quty     = $array['quty'];
                                            $resp     = $this->Cart_model->updateitemincart($itemid, $status, $alt_item, $orderid, $quty);
                                            if ($resp) {
                                                $counter++;
                                            }
                                        }
                                    }
                                    $iseditmode = "2";
                                    $resp       = $this->Model->update('order_table', array('status' => $orderstatus, 'is_order_edit' => $iseditmode, 'alternate_approval_role' => $role), array('order_id' => $orderid));

                                    if (!(isset($role) && $role == 'picker')) {
                                        $this->notify_picker_for_order_update($orderid);
                                    }

                                    // Todo : if all items are in alternate and none is selected then its a case of order cancel and we'll have to move entries from ordered_item -> finished_item

                                    $response["error"]           = false;
                                    $response["mainorderupdate"] = $resp;
                                    $response["updatecounter"]   = $counter;
                                    $response["message"]         = "order Update successfully";
                                } else {
                                    $response["error"]       = true;
                                    $response["errorstatus"] = "2";
                                    $response["message"]     = "order not update, something went wrong";
                                }
                            } elseif ($neworderstatus == "2") {
                                $res = $this->Model->update('order_table', array('total_price' => $totalprice, 'tax' => $ordtax, 'finalprice' => $finalprice), array('order_id' => $orderid)); //
                                if ($res) {
                                    $counter = 0;
                                    $data    = json_decode($item_detail, true);
                                    foreach ($data as $key => $arrays) {
                                        foreach ($arrays as $array) {
                                            //$str= implode(',' ,$array);
                                            $alt_item = $array['alt_itemid'];
                                            $status   = $array['status'];
                                            $itemid   = $array['itemid'];
                                            $quty     = $array['quty'];

                                            $resp = $this->Model->updateitemincart($itemid, $status, $alt_item, $orderid, $quty);
                                            if ($resp) {
                                                $counter++;
                                            }
                                        }
                                    }
                                    $iseditmode = "2";
                                    $resp       = $this->Model->update('order_table', array('status' => $orderstatus, 'is_order_edit' => $iseditmode, 'alternate_approval_role' => $role), array('order_id' => $orderid));

                                    if (!(isset($role) && $role == 'picker')) {
                                        $this->notify_picker_for_order_update($orderid);
                                    }
                                    $response["error"]           = false;
                                    $response["mainorderupdate"] = $resp;
                                    $response["updatecounter"]   = $counter;
                                    $response["message"]         = "order update successfully";
                                } else {
                                    $response["error"]       = true;
                                    $response["errorstatus"] = "3";
                                    $response["message"]     = "order not update, something went wrong";
                                }
                            } else {
                                $response["error"]   = true;
                                $response["message"] = "New order Status Not Valid";
                            }
                        } else {
                            $response["error"]   = true;
                            $response["message"] = "order not belongs to this user";
                        }
                    } else {
                        $response["error"]   = true;
                        $response["message"] = "order not in edit mode";
                    }
                } else {
                    $response["error"]   = true;
                    $response["message"] = "User Not Valid";
                }
            } else {
                $response['error']   = true;
                $response['message'] = strip_tags(validation_errors());
            }
            responseJSON($response);
        } else {
            redirect('Login');
        }
    }

    public function notify_picker_for_order_update($order_id)
    {
        $picker_id = $this->Model->get_selected_data('picker_id', 'pd_order', array('order_id' => $orderid));
        if (count($picker_id) > 0) {
            $picker_id_arr = $picker_id[0];

            $device_tokens = $this->Model->get_record('picker_gcm', array('picker_id' => $picker_id_arr['picker_id']));
            if (count($device_tokens) > 0) {
                $item              = $device_tokens[0];
                $device_tokens_arr = $item;
                picker_notify_ios($order_id, $device_tokens_arr);
                picker_notify_android($order_id, $device_tokens_arr);
            }
        }
    }
    //------------logout-------
    public function logout()
    {
        $this->session->unset_userdata('pincode');
        $this->session->unset_userdata('go2grouser');
        $this->session->unset_userdata('select_store_data');
        $this->session->sess_destroy();
        if (!$this->session->has_userdata('go2grouser')) {
            $res = array("error" => false, "message" => "logout sucessfully");
            responseJSON($res);
        } else {
            $res = array("error" => true, "message" => "somthing went wrong");
            responseJSON($res);
        }
    }
}

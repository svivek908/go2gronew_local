<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET,POST,OPTIONS");


class Main extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        require_once APPPATH.'third_party/src/Google_Client.php';
        require_once APPPATH.'third_party/src/contrib/Google_Oauth2Service.php';
        $zipcode = '';
        if($this->session->has_userdata('pincode')){
            $this->zipcode = $this->session->userdata['pincode']['pincode'];
        }
    }

	public function index()
	{
        //------------get ip information-----------
        /*$ip_details = get_client_ip();
        $browser = get_the_browser();
        $geopluginURL='http://www.geoplugin.net/php.gp?ip='.$ip_details;
        $addrDetailsArr = unserialize(file_get_contents($geopluginURL));
        $city = (!isset($addrDetailsArr['geoplugin_city'])) ? 'Not Define' : $addrDetailsArr['geoplugin_city'];
        $country = (!isset($addrDetailsArr['geoplugin_countryName'])) ? 'Not Define' : $addrDetailsArr['geoplugin_countryName'];
        $timezone = (!isset($addrDetailsArr['geoplugin_timezone'])) ? 'Not Define' : $addrDetailsArr['geoplugin_timezone'];
        $continentName = (!isset($addrDetailsArr['geoplugin_continentName'])) ? 'Not Define' : $addrDetailsArr['geoplugin_continentName'];
        $currencyCode = (!isset($addrDetailsArr['geoplugin_currencyCode'])) ? 'Not Define' : $addrDetailsArr['geoplugin_currencyCode'];
        $latitude = (!isset($addrDetailsArr['geoplugin_latitude'])) ? 'Not Define' : $addrDetailsArr['geoplugin_latitude'];
        $longitude = (!isset($addrDetailsArr['geoplugin_longitude'])) ? 'Not Define' : $addrDetailsArr['geoplugin_longitude'];
        $region = (!isset($addrDetailsArr['geoplugin_region'])) ? 'Not Define' : $addrDetailsArr['geoplugin_region'];*/
        //------------get ip information-----------
        $logged_user = $this->session->userdata("go2grouser");
        $pincode = $this->session->userdata('pincode');
        $storeid ='';
        if($this->session->has_userdata('select_store_data'))
        {
            $storeid = $this->session->userdata['select_store_data']['storeid'];
        }
        if( $pincode || (isset($logged_user) && isset($logged_user['id']))){
            $userid ='';
            if(isset($logged_user) && isset($logged_user['id'])){
                $userid = $logged_user['id'];
                $this->zipcode = $logged_user['zipcode'];
                if(!$pincode){
                    $this->session->set_userdata('pincode',array('pincode'=>$this->zipcode));
                }
            }
            //$ip_info_arr = array('method'=>"ip_information",'user' => $userid,'pincode' => $pincode['pincode'],'ip_details' =>$ip_details,'browser_name' => $browser,'user_aget_details' => $_SERVER['HTTP_USER_AGENT'],'current_time' => date('Y-m-d H:i:s'),'city' => $city, 'country' =>$country,'timezone' =>$timezone,'continentName' =>$continentName,'latitude' =>$latitude,'longitude' =>$longitude,'region' =>$region,'currencyCode'=>$currencyCode);
            //--------------useragent details--------
            if(isset($storeid) && $storeid!=''){
                redirect('home');
            }
            redirect('select_store/'.$this->zipcode);
        } else {
             //$ip_info_arr = array('method'=>"ip_information", 'user' =>'','pincode' => '','ip_details' =>$ip_details,'browser_name' => $browser,'user_aget_details' => $_SERVER['HTTP_USER_AGENT'],'current_time' => date('Y-m-d H:i:s'),'city' => $city, 'country' =>$country,'timezone' =>$timezone,'continentName' =>$continentName,'latitude' =>$latitude,'longitude' =>$longitude,'region' =>$region,'currencyCode'=>$currencyCode);
            //$this->common->curlpostRequest1($ip_info_arr);
            $this->load->view('go2gro_web/index');
        }
	}

    public function select_store($zipcode = '') {
        if( $zipcode == ''){
            $this->load->view('go2gro_web/index');
        }
        $data = array('selected_store_id'=>'');
        if($zipcode != ""){
            if(getpinExist($zipcode)){
                $data['stores'] = $this->Model->get_stores_by_zipcode($zipcode);
                if(count($data['stores']) > 0){
                    if($this->session->has_userdata('select_store_data')){
                        $storeid = $this->session->userdata['select_store_data']['storeid'];
                        $data['selected_store_id'] = $storeid;
                    }
                    $this->load->view('go2gro_web/store_select',$data);
                }else{
                    $this->session->set_flashdata('error_msg','Store not available');
                    redirect('');
                }
            }else{
                 $this->session->unset_userdata(array('pincode'=>$zipcode));
                 $this->session->set_flashdata('error_msg','No zipcode available');
                redirect('');
            }
        }else{
            $this->session->set_flashdata('error_msg','Invalid zip code');
            redirect('');
        }
    }

	public function select_store_id($storeid){
        if(!$this->session->has_userdata('pincode')){
            redirect('');
        }
		if($storeid!=''){
            $storedata =$this->Model->get_record('stores',array('id' => $storeid,'status' => 'active'));
            if(count($storedata) > 0){
                foreach ($storedata as $storeval){
                    $workingday = json_decode($storeval['working_daytime'],true);
                    foreach ($workingday as $work_key => $day_time_value){
                        if(ucfirst($work_key)==date('l')){
                            if($this->session->has_userdata('select_store_data'))
                            {
                                if($storeid == $this->session->userdata['select_store_data']['storeid']){
                                    redirect('home');
                                }
                                elseif($storeid != $this->session->userdata['select_store_data']['storeid'] ){
                                    //--------------update session data
                                    $storedata = array('storeid'=>$storeval['id'],
                                    'storename'=>$storeval['name'],
                                    'logo'=>$storeval['logo'],
                                    'banner'=>$storeval['banner'],
                                    'zipcode'=>$storeval['zipcode'],
                                    'delivery_charge'=>$storeval['delivery_charge'],
                                    'work_day'=>ucfirst($work_key),
                                    'opening_time'=>$day_time_value['opening_time'],
                                    'closing_time'=> $day_time_value['closing_time'],
                                    'workingday' =>$workingday
                                    );
                                    $this->session->set_userdata('select_store_data',$storedata);
                                    //--------------empty cart items
                                    if($this->session->has_userdata("go2grouser")){
                                        $logged_user = $this->session->userdata("go2grouser");
                                        $userid= $logged_user['id'];
                                        //---------empty_cart_if_item_from_another_store---------------
                                        $cart_item_exits = $this->Model->get_record('cart_item',array('user_id' => $userid,'store_id' =>$storeid));
                                        if(count($cart_item_exits) > 0){
                                            if($this->Model->delete('cart_item',array('user_id' => $userid,'status' =>0))) {
                                                echo "<script type='text/javascript'>";
                                                echo "if (localStorage){";
                                                echo "localStorage.removeItem('cartData');
                                                    }";
                                                echo "</script>";
                                            }
                                        }
                                    }
                                    redirect('home');
                                }
                            }else{
                                $storedata = array('storeid'=>$storeval['id'],
                                'storename'=>$storeval['name'],
                                'logo'=>$storeval['logo'],
                                'banner'=>$storeval['banner'],
                                'zipcode'=>$storeval['zipcode'],
                                'delivery_charge'=>$storeval['delivery_charge'],
                                'work_day'=>ucfirst($work_key),
                                'opening_time'=>$day_time_value['opening_time'],
                                'closing_time'=> $day_time_value['closing_time'],
                                'workingday'=> $workingday,
                                );
                                $this->session->set_userdata('select_store_data',$storedata);
                                redirect('home');
                            }
                        }
                    }
                }
            }else{
                redirect('home');
            }
		}else{
			$this->load->view('go2gro_web/index');
		}
    } 

    public function home(){
        if(!$this->session->has_userdata("pincode")){
            redirect('');
        }
        if($this->session->has_userdata("go2grouser")){
            $logged_user = $this->session->userdata("go2grouser");
            $userid = $logged_user['id'];
        }
        /*if($this->session->has_userdata("pincode")){
            $pincode = $this->session->userdata['pincode']['pincode'];
            $ip_info_arr = array('method'=>"ip_information",'user' => $userid,'pincode' => $pincode,'ip_details' =>$ip_details,'browser_name' => $browser,'user_aget_details' => $_SERVER['HTTP_USER_AGENT'],'current_time' => date('Y-m-d H:i:s'));
            $this->common->curlpostRequest1($ip_info_arr);
        }*/
        //------------get ip information-----------
        $storeid =get_selected_storeid();
        if($storeid)
        {
            $data['category']= $this->Model->get_all_record('category',$order='position',$type="ASC",$limit='',$start="",$where=array('store_id' => $storeid,'status'=>0));
            $this->load->view('go2gro_web/home',$data);
        }else{
            redirect('select_store/'.$this->zipcode);
        }
    }

    public function getBestSeller()
    {
        $storeid =get_selected_storeid();
        $data = $this->Model->get_record('stores',array('id' => $storeid, 'status' => 'active'));
        responseJSON($data);
    }
    
    public function login()
    {
        $this->load->view('go2gro_web/login');
    }
    public function registration()
    {
        $reg_data = array('gmail_reg' => false,'email' =>'',
            'name' =>'','lname' =>'','image'=>'','password' =>'','logintype'=>"");
        $this->load->view('go2gro_web/registration',$reg_data);
    }
    public function privacy()
    {
        $this->load->view('go2gro_web/privacy-policy');
    }
    public function success()
    {
        if($this->session->has_userdata('go2grouser')){
            $this->load->view('go2gro_web/success');
        }else{
            redirect('Login');
        }
    }
    public function cancel()
    {
        if($this->session->has_userdata('go2grouser')){
            $this->load->view('go2gro_web/cancel');
        }else{
            redirect('Login');
        }
    }
    public function about()
    {
        $this->load->view('go2gro_web/about-us');
    }
    public function disclaimer()
    {
        $this->load->view('go2gro_web/disclaimer');
    }
    public function faq()
    {
        $this->load->view('go2gro_web/faq');
    }
    public function return_policy()
    {
        $this->load->view('go2gro_web/return-policy');
    }

    public function contact()
    {
        $this->load->view('go2gro_web/contact-us');
    }

    public function terms()
    {
        $this->load->view('go2gro_web/terms-condition');
    }
    public function alternateProduct()
    {
        if($this->session->has_userdata('go2grouser')){
            $this->load->view('go2gro_web/alternateproduct');
        }else{
            redirect('Login');
        }
    }

    //----------------------------google login ---------------------------

    /*public function logingoogle($value='')
    {
        $clientId = '554613440241-94326cuv7khcjldn536t6csbdgm2qoas.apps.googleusercontent.com'; //Google client ID
        $clientSecret = 'n_9NLUOTyhz3L23eCzymciOZ'; //Google client secret
        $redirectURL = 'https://www.go2gro.com/go2gro_beta/social_media_login';
        $gClient = new Google_Client();
        $gClient->setApplicationName('Login');
        $gClient->setClientId($clientId);
        $gClient->setClientSecret($clientSecret);
        $gClient->setRedirectUri($redirectURL);
        $google_oauthV2 = new Google_Oauth2Service($gClient);
        if(isset($_GET['code']))
        {
            $gClient->authenticate($_GET['code']);
            $_SESSION['token'] = $gClient->getAccessToken();
           // header('Location: ' . filter_var($redirectURL, FILTER_SANITIZE_URL));
        }


        if (isset($_SESSION['token'])) 
         {
            //$gClient->setAccessToken($_SESSION['token']);
         }
        //var_dump($_SESSION['token']);exit;
        if ($gClient->getAccessToken()) {
            $userProfile = $google_oauthV2->userinfo->get();
            $user_email=$userProfile['email'];
            $userdata= array('email' => $user_email,'method'=>"logingoogle" );
            $result = $this->common->curlpostRequest1($userdata);

            $res1 = json_decode($result); 

            if ($res1->error==false) {
                $this->session->set_userdata(array('user'=>$res1));
                redirect('Main/home');
            }
            else{    
            	//----------password set default email id g --
                $reg_data = array('gmail_reg' => true,
                    'email' =>$userProfile['email'] ,
                    'name' =>$userProfile['given_name'],
                    'image'=>$userProfile['picture'],'lname' =>$userProfile['family_name'],'password'=>$user_email.'g','logintype'=>"google");
                $this->load->view('go2gro_web/registration',$reg_data);
            }        
        } 
        else 
        {
            echo $url = $gClient->createAuthUrl();
            redirect($url);
           //exit;
        }
    }*/

    public function getSubcategory()
    {
        $this->load->view('go2gro_web/subcategory');
    }
    
    public  function getlistitemnew()
    {
        $storeid =get_selected_storeid();
        $categories=$this->Model->get_record('stores',array('id'=>$storeid));
        if (count($categories) > 0) {
            $result = $this->Model->get_all_record('category',$order='position',$type="ASC",$limit='',$start="",$where=array('store_id' => $storeid));
            $this->load->view('go2gro_web/itemlist-new',array('categories'=>$result));
        }
    }
    public  function checkout()
    {
        if($this->session->has_userdata('go2grouser')){
            $User_data = $this->session->userdata['go2grouser'];
            $get_city_state_country = $this->Model->get_city_state_country($User_data['zipcode']);
            $uinfo = $get_city_state_country[0];
            $data = array('first_name' =>$User_data['first_name'],'last_name' =>$User_data['last_name'],'email_id' =>$User_data['email_id'],'mobile' =>$User_data['mobile'],'country_id' => $uinfo['countryid'],'country_name' => $uinfo['country_name'],'city_name' =>$uinfo['city_name'] ,'city_id' =>$uinfo['cityid'] ,'state_id' =>$uinfo['stateid'],'state_name' =>$uinfo['state_name'],'pincode' => $User_data['zipcode']); 
            $whole_address = json_decode($User_data['address']);
            if(isset($whole_address->street_address)){
                $data['street_address'] = $whole_address->street_address;
                $data['latitude'] = $whole_address->latitude;
                $data['longitude'] = $whole_address->longitude;
                $data['apt_no'] = $whole_address->apt_no;
                $data['complex_name'] = $whole_address->complex_name;
            }else {
                $data['street_address'] = ''; $data['latitude'] = '';
                $data['longitude'] = ''; $data['apt_no'] = '';
                $data['complex_name'] = '';
            }
            $this->load->view('go2gro_web/checkout',$data);
        }else{
            redirect('Login');
        }   
    }
    public  function account()
    {
        if($this->session->has_userdata('go2grouser')){
            $this->load->view('go2gro_web/account');
        }else{
            redirect('Login');
        }
    }
    
    public  function profile()
    {
        if($this->session->has_userdata("go2grouser")){
            $logged_user = $this->session->userdata("go2grouser");
            $userid = $logged_user['id'];
            $user_data=$this->Model->get_record('users',array('id'=>$userid));
            $data['cities'] = $this->Model->get_all_record('cities');
            $data['states'] = $this->Model->get_all_record('states');
            //$data['countries'] = $this->Model->get_all_record('countries');
            $city_name = $country_name = $state_name = '';
            $city_state_counttry = $this->Model->get_city_state_country($user_data[0]['pincode']);
            if(count($city_state_counttry) > 0){
                $city_name = $city_state_counttry[0]['city_name'];
                $state_name= $city_state_counttry[0]['state_name'];
                $country_name = $city_state_counttry[0]['country_name'];
            }
            $full_address = json_decode($user_data[0]['address'],true);
            $data['userresult'] = array('first_name'=>$user_data[0]['first_name'],'last_name'=>$user_data[0]['last_name'],
                'mobile'=>$user_data[0]['mobile'],'email_id'=>$user_data[0]['email_id'],'street_address'=> $full_address['street_address'],
                'apt_no'=> $full_address['apt_no'], 'complex_name'=> $full_address['complex_name'],'latitude'=> $full_address['latitude'],
                'longitude'=> $full_address['longitude'],'country_id'=>$user_data[0]['country_id'],'state_id'=>$user_data[0]['state_id'],
                'city_id'=>$user_data[0]['city_id'],'pincode'=>$user_data[0]['pincode'],'city_name' => $city_name,'country_name' => $country_name,'state_name' =>$state_name);
            $this->load->view('go2gro_web/profile',$data);
        }else{
            redirect('Login');
        }
    }

    public  function productDetail()
    {
        $this->load->view('go2gro_web/product-detail');
    }
    public  function ShoppingCart()
    {
        if($this->session->has_userdata('go2grouser')){
            $this->load->view('go2gro_web/shopping-cart');
        }else{
            redirect('Login');
        }
    }
    
    public function accountDetail($orderid,$storeid)
    {
        if($this->session->has_userdata("go2grouser")){
            $data = array('order_id'=>$orderid,'store_id'=>$storeid);
            $this->load->view('go2gro_web/account-detail',$data);
        }else{
            redirect('Login');
        }
    }

    public function membership() {
        if($this->session->has_userdata("go2grouser")){
            $pincode =$this->session->userdata("go2grouser")['zipcode'];
            $result=$this->Model->get_city_state_country($pincode);
            $data = array('county_name' =>$result[0]['country_name'],
                'state_name' =>$result[0]['state_name'],
                'city_name' =>$result[0]['city_name']);
            $this->load->view('go2gro_web/membership',$data);
        }else{
            redirect('home');
        }
    }

    public function searchListView(){
        $searchStr = $this->input->get('q');
        $data['searchStr'] = htmlentities(urldecode($searchStr));
        $this->load->view('go2gro_web/searchResult',$data);
    }
}
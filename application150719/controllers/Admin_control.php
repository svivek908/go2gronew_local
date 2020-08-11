<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_control extends CI_Controller {
    private $userid;
    function __construct() {
        parent::__construct();
        $api_key  = $this->input->get_request_header('Authorization');
       
        // if(!$this->session->has_userdata('go2groadmin_session')){
        //     redirect('Go2gro_adminlogin');
        // }
        
        $this->load->model('Datatables_model',"tablemodels");
        $logged_user = logged_admin_record();
        
        /*$this->loggeduserId = $logged_user['id'];
        $this->loggeduserName = $logged_user['name'];*/
    }
    private function isValidApiKey($api_key){
        $this->userid = getuserid('admin',$api_key);
    	return $this->userid;
    }
    public function index()
    {
        $this->load->view('admin/include/header');
        $this->load->view('admin/include/sidebar');
        $this->load->view('admin/dashboard');
        $this->load->view('admin/include/footer');
    }

    //------------Dashboard record-----------
    public function salesanalyticDatailDonut(){
        $fromTime = $this->input->get('fromTime');
        $toTime = $this->input->get('toTime');
        $where="datetime BETWEEN '".$fromTime."' AND '".$toTime."'";
        $salesanalyticData = $this->Model->salesanalyticDatail($where);
        echo json_encode($salesanalyticData);
    }

    public function saleDatailByOrderStatus(){
        $fromTime = $this->input->get('fromTime');
        $toTime = $this->input->get('toTime');
        $status = $this->input->get('status');
        
        if($status == 0){
            $where="datetime BETWEEN '".$fromTime."' AND '".$toTime."' and `status` in(0,1,2,3)";
        }else{
            $where = array('status' => $status);
        }
        $group_by = array("MONTH(FROM_UNIXTIME(`datetime`))", "YEAR(FROM_UNIXTIME(`datetime`))");
            
        $saleDatailByOrderStatus = $this->Model->adm_getbarsaleanalysis(array('MONTHNAME(FROM_UNIXTIME(`datetime`)) as month_name' , 'YEAR(FROM_UNIXTIME(`datetime`)) as year_name' , 'COUNT(*) order_count', 'sum(`finalprice`) as total'),'order_table',$where,$group_by);
        if(count($saleDatailByOrderStatus) > 0){
            $response = array('error' => false, 'detail' => $saleDatailByOrderStatus);
        }else{
            $response = array('error' => true, 'detail' =>'');
        }
        echo json_encode($response);
    }

    //-------------Orders--------------------
    public function all_orders()
    {
        $this->load->view('admin/include/header');
        $this->load->view('admin/include/sidebar');
        $this->load->view('admin/order/index');
        $this->load->view('admin/include/footer');
    }
   
   //------------new orders
    public function neworders()
    {
        $response = array('error' => true);
        //----------get orders from picker
        $picker_orders_ids = array(); $orders = array();
        $picker_orders = $this->Model->get_selected_data('order_id','pd_order',$where=array('status'=>0),$order='',$type='',$limit='',$start='');
        if(count($picker_orders) > 0){
            foreach ($picker_orders as $key => $po) {
                array_push($picker_orders_ids, $po['order_id']);
            }
        }
        $per_page = 5;
        $page = $this->input->post('page');
        $offset = 0; 
        if($page){
            $offset = $page;
        }
        $uri_segment =3;
        //-----------get new orders--------------
        $column_search = array('ord.order_id');
        $column_select = array('ord.order_id', 'ord.finalprice', 'ord.datetime', 'ord.status','ord.tax','ord.dlv_charge','ord.is_order_edit', 'usr.email_id', 'usr.mobile', 'usr.address', 'usr.pincode',
            "CONCAT(shpadd.first_name, '" ."', shpadd.last_name) as name", 'shpadd.address as shipping_address', 'shpadd.ship_mobile_number as ship_mobile', 'shpadd.pincode as ship_pincode','st.name as store_name');
        
        $relation1 = 'ord.user_id = usr.id';
        $relation2 = 'ord.order_id = shpadd.order_id';
        $relation3 = 'ord.store_id=st.id';
        $where = "ord.status NOT IN ( 4, 5,6 )";

        $new_orders_count = $this->Model->getfourtabledata($column_select,'order_table as ord','users as usr','shipping_address as shpadd','stores as st',$relation1,$relation2,$relation3,$where,$column_search,$orderby=array('ord.datetime' => 'DESC'),$group_by="",$limit="",$start="");

        $new_orders = $this->Model->getfourtabledata($column_select,'order_table as ord','users as usr','shipping_address as shpadd','stores as st',$relation1,$relation2,$relation3,$where,$column_search,$orderby=array('ord.datetime' => 'DESC'),$group_by="",$limit=$per_page,$start=$offset);

        if(count($new_orders) > 0){
            foreach ($new_orders as $key => $order_val) {
                $abc["trans_history"] = array();
                // --------check order id in picker ids array
                $new_orders[$key]['picked_by']= NULL;
                if(in_array($order_val['order_id'],$picker_orders_ids)){
                     // Get the name of the Picker
                    $order_with_picker = $this->Model->gettwodata(array('pd_order.picker_id','pd_person.name as picked_by'),'pd_order','pd_person','pd_order.picker_id=pd_person.id',array('pd_order.order_id'=>$order_val['order_id']),$order="",$type="",$limit="");
                    if(count($order_with_picker) > 0){
                        $new_orders[$key]['picked_by'] = $order_with_picker[0]['picked_by'];
                    }
                }

                //-------------------
                if($order_val['is_order_edit'] == "2"){
                    $ordertransionhistory =  $this->Model->get_selected_data('*','order_trans_history',$where=array('orderid'=>$order_val['order_id']),$order='',$type='',$limit='',$start='');
                    if(count($ordertransionhistory) > 0){
                        foreach ($ordertransionhistory as $key => $oth) {
                            array_push($abc["trans_history"], $oth);
                        }
                        $new_orders[$key]["transhistory"] = $abc["trans_history"];
                    }
                }
                //array_push($orders,$new_orders);
                $response['error'] = false;
            } //-------loop close
            $url = site_url('Admin/neworders');
            $rowscount = count($new_orders_count);
            ajaxpagination($url, $rowscount, $per_page,$fun='neworders',$uri_segment);
            $data['order'] = $new_orders;
            $html = $this->load->view('admin/order/list', $data,true);
            $response["page"] =$html;
        }
        echo json_encode($response);
    }

    //----delivered order
    public function deleverorder()
    {
        $response = array('error' => true);
        //----------get orders from picker
        $picker_orders_ids = array(); $orders = array();
        $picker_orders = $this->Model->get_selected_data('order_id','pd_order',$where=array('status'=>1),$order='',$type='',$limit='',$start='');
        if(count($picker_orders) > 0){
            foreach ($picker_orders as $key => $po) {
                array_push($picker_orders_ids, $po['order_id']);
            }
        }
        $per_page = 5;
        $page = $this->input->post('page');
        $offset = 0; 
        if($page){
            $offset = $page;
        }
        $uri_segment =3;
        //-----------get new orders--------------
        $column_search = array('ord.order_id');
        $column_select = array('ord.order_id', 'ord.finalprice', 'ord.datetime', 'ord.status','ord.tax','ord.dlv_charge','ord.is_order_edit', 'usr.email_id', 'usr.mobile', 'usr.address', 'usr.pincode',
            "CONCAT(shpadd.first_name, '" ."', shpadd.last_name) as name", 'shpadd.address as shipping_address', 'shpadd.ship_mobile_number as ship_mobile', 'shpadd.pincode as ship_pincode','st.name as store_name');
        
        $relation1 = 'ord.user_id = usr.id';
        $relation2 = 'ord.order_id = shpadd.order_id';
        $relation3 = 'ord.store_id=st.id';
        $where = array('ord.status' => 4);

        $deleverorder_count = $this->Model->getfourtabledata($column_select,'order_table as ord','users as usr','shipping_address as shpadd','stores as st',$relation1,$relation2,$relation3,$where,$column_search,$orderby=array('ord.datetime' => 'DESC'),$group_by="",$limit="",$start="");

        $deleverorder = $this->Model->getfourtabledata($column_select,'order_table as ord','users as usr','shipping_address as shpadd','stores as st',$relation1,$relation2,$relation3,$where,$column_search,$orderby=array('ord.datetime' => 'DESC'),$group_by="",$limit=$per_page,$start=$offset);

        if(count($deleverorder) > 0){
            foreach ($deleverorder as $key => $order_val) {
                $abc["trans_history"] = array();
                // --------check order id in picker ids array
                $deleverorder[$key]['picked_by']= NULL;
                if(in_array($order_val['order_id'],$picker_orders_ids)){
                     // Get the name of the Picker
                    $order_with_picker = $this->Model->gettwodata(array('pd_order.picker_id','pd_person.name as picked_by'),'pd_order','pd_person','pd_order.picker_id=pd_person.id',array('pd_order.order_id'=>$order_val['order_id']),$order="",$type="",$limit="");
                    if(count($order_with_picker) > 0){
                        $deleverorder[$key]['picked_by'] = $order_with_picker[0]['picked_by'];
                    }
                }

                //-------------------
                if($order_val['is_order_edit'] == "2"){
                    $ordertransionhistory =  $this->Model->get_selected_data('*','order_trans_history',$where=array('orderid'=>$order_val['order_id']),$order='',$type='',$limit='',$start='');
                    if(count($ordertransionhistory) > 0){
                        foreach ($ordertransionhistory as $key => $oth) {
                            array_push($abc["trans_history"], $oth);
                        }
                        $deleverorder[$key]["transhistory"] = $abc["trans_history"];
                    }
                }
                //array_push($orders,$new_orders);
                $response['error'] = false;
            } //-------loop close
            $url = site_url('Admin/deleverorder');
            $rowscount = count($deleverorder_count);
            ajaxpagination($url, $rowscount, $per_page,$fun='deleverorder',$uri_segment);
            $data['order'] = $deleverorder;
            $html = $this->load->view('admin/order/list', $data,true);
            $response["page"] =$html;
        }
        echo json_encode($response);
    }
    
     //----rejectorder order
    public function rejectorder()
    {
        $response = array('error' => true);
        //----------get orders from picker
        $orders = array();
        $per_page = 5;
        $page = $this->input->post('page');
        $offset = 0; 
        if($page){
            $offset = $page;
        }
        $uri_segment =3;
        //-----------get new orders--------------
        $column_search = array('ord.order_id');
        $column_select = array('ord.order_id', 'ord.finalprice', 'ord.datetime', 'ord.status','ord.tax','ord.dlv_charge','ord.is_order_edit', 'usr.email_id', 'usr.mobile', 'usr.address', 'usr.pincode',
            "CONCAT(shpadd.first_name, '" ."', shpadd.last_name) as name", 'shpadd.address as shipping_address', 'shpadd.ship_mobile_number as ship_mobile', 'shpadd.pincode as ship_pincode','st.name as store_name');
        
        $relation1 = 'ord.user_id = usr.id';
        $relation2 = 'ord.order_id = shpadd.order_id';
        $relation3 = 'ord.store_id=st.id';
        $where = array('ord.status' => 5);

        $rejectorder_count = $this->Model->getfourtabledata($column_select,'order_table as ord','users as usr','shipping_address as shpadd','stores as st',$relation1,$relation2,$relation3,$where,$column_search,$orderby=array('ord.datetime' => 'DESC'),$group_by="",$limit="",$start="");

        $rejectorder = $this->Model->getfourtabledata($column_select,'order_table as ord','users as usr','shipping_address as shpadd','stores as st',$relation1,$relation2,$relation3,$where,$column_search,$orderby=array('ord.datetime' => 'DESC'),$group_by="",$limit=$per_page,$start=$offset);

        if(count($rejectorder) > 0){
            foreach ($rejectorder as $key => $order_val) {
                $abc["trans_history"] = array();
                // --------check order id in picker ids array
                $rejectorder[$key]['picked_by']= NULL;
                //-------------------
                if($order_val['is_order_edit'] == "2"){
                    $ordertransionhistory =  $this->Model->get_selected_data('*','order_trans_history',$where=array('orderid'=>$order_val['order_id']),$order='',$type='',$limit='',$start='');
                    if(count($ordertransionhistory) > 0){
                        foreach ($ordertransionhistory as $key => $oth) {
                            array_push($abc["trans_history"], $oth);
                        }
                        $rejectorder[$key]["transhistory"] = $abc["trans_history"];
                    }
                }
                //array_push($orders,$new_orders);
                $response['error'] = false;
            } //-------loop close
            $url = site_url('Admin/rejectorder');
            $rowscount = count($rejectorder_count);
            ajaxpagination($url, $rowscount, $per_page,$fun='rejectorder',$uri_segment);
            $data['order'] = $rejectorder;
            $html = $this->load->view('admin/order/list', $data,true);
            $response["page"] =$html;
        }
        echo json_encode($response);
    }

    //----cancelorder
    public function cancelorder()
    {
        $response = array('error' => true);
        //----------get orders from picker
        $orders = array();
        $per_page = 5;
        $page = $this->input->post('page');
        $offset = 0; 
        if($page){
            $offset = $page;
        }
        $uri_segment =3;
        //-----------get new orders--------------
        $column_search = array('ord.order_id');
        $column_select = array('ord.order_id', 'ord.finalprice', 'ord.datetime', 'ord.status','ord.tax','ord.dlv_charge','ord.is_order_edit', 'usr.email_id', 'usr.mobile', 'usr.address', 'usr.pincode',
            "CONCAT(shpadd.first_name, '" ."', shpadd.last_name) as name", 'shpadd.address as shipping_address', 'shpadd.ship_mobile_number as ship_mobile', 'shpadd.pincode as ship_pincode','st.name as store_name');
        
        $relation1 = 'ord.user_id = usr.id';
        $relation2 = 'ord.order_id = shpadd.order_id';
        $relation3 = 'ord.store_id=st.id';
        $where = array('ord.status' => 6);

        $cancelorder_count = $this->Model->getfourtabledata($column_select,'order_table as ord','users as usr','shipping_address as shpadd','stores as st',$relation1,$relation2,$relation3,$where,$column_search,$orderby=array('ord.datetime' => 'DESC'),$group_by="",$limit="",$start="");

        $cancelorder = $this->Model->getfourtabledata($column_select,'order_table as ord','users as usr','shipping_address as shpadd','stores as st',$relation1,$relation2,$relation3,$where,$column_search,$orderby=array('ord.datetime' => 'DESC'),$group_by="",$limit=$per_page,$start=$offset);

        if(count($cancelorder) > 0){
            foreach ($cancelorder as $key => $order_val) {
                $abc["trans_history"] = array();
                // --------check order id in picker ids array
                $cancelorder[$key]['picked_by']= NULL;
                //-------------------
                if($order_val['is_order_edit'] == "2"){
                    $ordertransionhistory =  $this->Model->get_selected_data('*','order_trans_history',$where=array('orderid'=>$order_val['order_id']),$order='',$type='',$limit='',$start='');
                    if(count($ordertransionhistory) > 0){
                        foreach ($ordertransionhistory as $key => $oth) {
                            array_push($abc["trans_history"], $oth);
                        }
                        $cancelorder[$key]["transhistory"] = $abc["trans_history"];
                    }
                }
                //array_push($orders,$new_orders);
                $response['error'] = false;
            } //-------loop close
            $url = site_url('Admin/cancelorder');
            $rowscount = count($cancelorder_count);
            ajaxpagination($url, $rowscount, $per_page,$fun='cancelorder',$uri_segment);
            $data['order'] = $cancelorder;
            $html = $this->load->view('admin/order/list', $data,true);
            $response["page"] =$html;
        }
        echo json_encode($response);
    }

    //-----------All order count -------------
    public function allordercount()
    {
        $orders_count = $this->Model->alltypes_order_count();
        $array = array('newcount' => '-', 'delivercount' => '-','rejectcount' => '-', 'canclecount'=>'-');
        if(count( $orders_count) > 0){
            $array = array('newcount' => $orders_count[0]['newcount'], 'delivercount' => $orders_count[0]['delivercount'],'rejectcount' => $orders_count[0]['rejectcount'], 'canclecount'=> $orders_count[0]['canclecount']);
        }
        echo json_encode($array);
    }

    //----------Report-----------------------

    public function report()
    {
        $this->load->view('admin/include/header');
        $this->load->view('admin/include/sidebar');
        $data['all_stores'] = $this->Model->get_selected_data(array('id','name'),'stores');
        $this->load->view('admin/report/report',$data);
        $this->load->view('admin/include/footer');
    }

    //-----------get report--------
    public function getReport(){
        $fdate        =      $this->input->get('fdate');
        $tdate        =      $this->input->get('tdate');
        $status       =      $this->input->get('status');
        $storeid       =      $this->input->get('storeid');
        $per_page     = 20;
        $page = $this->input->post('page');
        $offset = 0; 
        if($page){
            $offset = $page;
        }
        $uri_segment =3;
        //-----------get new orders--------------
        $column_search = array('ord.order_id');
        $column_select = array('ord.order_id','ord.datetime','ord.refund_status','ord.refund_txnid','ord.status','ord.tax','ord.dlv_charge','ord.finalprice','ord.processingfee','ord.tip_amount','ord.total_price','usr.email_id', 'usr.mobile', 'usr.address', 'usr.pincode',"CONCAT(shpadd.first_name, '" ."', shpadd.last_name) as name", 'shpadd.address as shipping_address', 'shpadd.ship_mobile_number as ship_mobile', 'shpadd.pincode as ship_pincode','st.name as store_name');
        
        $relation1 = 'ord.user_id = usr.id';
        $relation2 = 'ord.order_id = shpadd.order_id';
        $relation3 = 'ord.store_id=st.id';

        $where="ord.datetime BETWEEN '".$fdate."' AND '".$tdate."' AND `ord.status`= '".$status."'";

        if($storeid != 'All'){
             $where.=" AND ord.store_id = '".$storeid."'";
        }

        $orders_count = $this->Model->getfourtabledata($column_select,'order_table as ord','users as usr','shipping_address as shpadd','stores as st',$relation1,$relation2,$relation3,$where,$column_search,$orderby=array('ord.datetime' => 'DESC'),$group_by="",$limit="",$start="");

        $orders = $this->Model->getfourtabledata($column_select,'order_table as ord','users as usr','shipping_address as shpadd','stores as st',$relation1,$relation2,$relation3,$where,$column_search,$orderby=array('ord.datetime' => 'DESC'),$group_by="",$limit=$per_page,$start=$offset);

        $url = site_url('Admin/getReport');
        $rowscount = count($orders_count);
        ajaxpagination($url, $rowscount, $per_page,$fun='neworders',$uri_segment);
        $data['order'] = $orders;
        $html = $this->load->view('admin/report/list', $data,true);
        $response["page"] =$html;
        echo json_encode(array('error' => false, 'page' => $html));
    }

    public function userDetails(){
        $this->load->view('admin/include/header');
        $this->load->view('admin/include/sidebar');
        $this->load->view('admin/user/userdetails');
        $this->load->view('admin/include/footer');
    }

    public function getUsers(){
        $fdate        =      $this->input->get('fdate');
        $tdate        =      $this->input->get('tdate');
        $utype        =      $this->input->get('utype');

        $per_page     = 20;
        $page = $this->input->post('page');
        $offset = 0; 
        if($page){
            $offset = $page;
        }
        $uri_segment =3;
        //-----------get new orders--------------
        $column_search = array('u.first_name','u.last_name','u.email_id','u.mobile');
        $column_select = array('u.id','u.first_name','u.last_name','u.email_id','u.mobile','u.address','u.pincode','u.country_id','u.state_id','u.city_id','u.ismobile_verify', 'u.unitime', 'ci.name as city','c.name as country','s.name as state');
        
        $relation1 = 'u.country_id=c.id';
        $relation2 = 's.id=u.state_id';
        $relation3 = 'ci.id=u.city_id';

        $where ="";
        if($fdate != '' && $tdate !=''){
           $where="u.unitime BETWEEN '".$fdate."' AND '".$tdate."'";
        }

        $users_count = $this->Model->getfourtabledata($column_select,'users as u','countries as c','states as s','cities as ci',$relation1,$relation2,$relation3,$where,$column_search,$orderby=array('u.`id`' => 'DESC'),$group_by="",$limit="",$start="");

        $users = $this->Model->getfourtabledata($column_select,'users as u','countries as c','states as s','cities as ci',$relation1,$relation2,$relation3,$where,$column_search,$orderby=array('u.`id`' => 'DESC'),$group_by="",$limit=$per_page,$start=$offset);

        $url = site_url('Admin/getUsers');
        $rowscount = count($users_count);
        ajaxpagination($url, $rowscount, $per_page,$fun='userlist',$uri_segment);
        $data['users'] = $users;
        $html = $this->load->view('admin/user/list', $data,true);
        $response["page"] =$html;
        echo json_encode(array('error' => false, 'page' => $html));
    }

    //--------------get store list
    public function get_stores(){
        $response = array("error"=> true, "message" => "");
        $store = $this->Model->get_selected_data('*','stores');
        if(count($store) > 0){
            $response['error'] = false;
            $response['stores'] = $store;
        }else{
            $response["error"] = true;
            $response["message"] = "No stores available";
        }
        echo json_encode($response);
    }

    public function select_store(){
        /*$stores = [];
        $api_key = $this->session->get_userdata('user')['user']->user->api_key;
        $result = $this->common->curlpostRequest3('get_stores_for_zipcode', $api_key);
        $result = json_decode($result);

        if(!$result->error){
            $stores = $result->stores;
        }
        $this->load->view('admin/header');
        $this->load->view('admin/store_select',['stores'=>$stores]);
        $this->load->view('admin/footer');*/
    }

    public function save_store_to_session()
    {
        $store = false;
        $store_id = $this->input->post('store_id');

        $store = $this->Model->get_selected_data('*','stores',array('id' => $store_id));
        if(count($store) > 0){
            $this->session->set_userdata('store',$store);
            $response = array('status'=>'success');
        }else{
            $response = array('status'=>'fail');
        }
        
        responseJSON($response);
    }
    function pickerDeliveryUser()
    {
        $this->load->view('admin/include/header');
        $this->load->view('admin/include/sidebar');
        $this->load->view('admin/user/picker_delivery_user');
        $this->load->view('admin/include/footer');
    }
    function getpickerDeliveryUserList()
    {
        $auth = $this->input->get('Authorization');
        $response = array();
        $userlist = $this->Model->get_all_record('pd_person');
        $userArray = array();
        if (count($userlist) > 0) {
            foreach ( $userlist as $userDetail) {
                array_push($userArray, $userDetail);
            }
            $result = $userArray;
        } else {
            $result = false;
        }
        if($userArray){
            $response["error"] = false;
            $response["message"] = "User list get successfully";
            $response["userlist"] = $userArray;
        }else{
            $response["error"] = true;
            $response["message"] = "No user Found";
        }
        responseJSON($response);
    }
    function pickerOrders()
    {
        $this->load->view('admin/include/header');
        $this->load->view('admin/include/sidebar');
        $this->load->view('admin/order/PickerOrders');
        $this->load->view('admin/include/footer');
    }
    function getActivePickerList()
    {
        $auth = $this->input->get('Authorization');
        $userlist = $this->Model->get_all_record('pd_person');
        $userArray = array();
        if (count($userlist) > 0) {
            foreach ( $userlist as $userDetail) {
                array_push($userArray, $userDetail);
            }
            $result = $userArray;
        } else {
            $result = false;
        }
        if($userArray){
            $response["error"] = false;
            $response["message"] = "User list get successfully";
            $response["userlist"] = $userArray;
        }else{
            $response["error"] = true;
            $response["message"] = "No user Found";
        }
        responseJSON($response);
        
    }
    function getPickerOrder()
    {
    	// Disable store_id check
        $auth = $this->input->get('Authorization');
        $picker_id = $this->input->get('picker_id');
       
//        $segmants=$picker_id.'/'.$this->session->userdata('store')->id;
        $store_id = "";
        
        if($this->session->userdata('store')){
        	$store_id = $this->session->userdata('store');
        }
        $segmants=$picker_id.'/'.$store_id;
        $r=$this->tablemodels->adm_getOrderByPickerId($picker_id,$store_id);
        var_dump($store_id);exit;
        $res = $this->common->curlpostRequest3('getOrderByPicker', $auth, $segmants);
        $this->common->responseJSON($res);
    }
    function viewstores()
    {
        $this->load->view('admin/include/header');
        $this->load->view('admin/include/sidebar');
        $this->load->view('admin/storelist');
        $this->load->view('admin/include/footer');
    }
    function getallstores(){
        $auth = $this->input->get('Authorization');
        $response = array();
        $all_store = array();
        $api_key  = $auth ;
        if($api_key != ''){
                $stores = $this->tablemodels->get_all_stores();
                    if (count($stores) > 0) {
                        foreach ( $stores as $list) {
                            array_push($all_store, $list);
                        }
                        $response["error"] = false;
                        $response["all_store"] = $all_store;
                        $response["message"] = "Store available";
                    }else{
                        $response["error"] = true;
                        $response["message"] = "Store not available";
                    }
                
            }else{
        		$response["error"] = true;
            	$response['message'] = 'Api key is misssing';
            }
            responseJSON($response);
        
    }
    function notifications()
    {
        $this->load->view('admin/include/header');
        $this->load->view('admin/include/sidebar');
        $this->load->view('admin/notifications');
        $this->load->view('admin/include/footer');
    }
    function gtUsers(){
        $auth = $this->input->get('Authorization');
        $status = $this->input->get('status');
        $res = [];
        if($status == 'all'){
            $res = $this->common->curlpostRequest3('users', $auth);
        }
        if($status == 'items_in_cart'){
            $res = $this->common->curlpostRequest3('get_users_with_items_in_cart', $auth);
        }
        if($status == 'recent_active_users'){
            $res = $this->common->curlpostRequest3('get_users_with_recent_orders', $auth);
        }
        if($status == 'datewise'){
        	$fdate   =    $this->input->get('fdate');
        	$tdate   =    $this->input->get('tdate');
        	$querySegments = $fdate.'/'.$tdate;
            $res = $this->common->curlpostRequest3('users', $auth, $querySegments);
        }
        $res1 = json_decode($res);
        $this->common->responseJSON($res1);

    }
    function viewpromocodes()
    {
        $this->load->view('admin/include/header');
        $this->load->view('admin/include/sidebar');
        $this->load->view('admin/view_promocode');
        $this->load->view('admin/include/footer');
    }
    function promocodes_list()
    {
        $auth = $this->input->get('Authorization');
        $response = array();
        $all_promocodes = array();
        
        $promocodes = $this->tablemodels->get_all_record('pd_person');
            if ( count($promocodes) > 0) {
                foreach ( $promocodes as $promocode) {
                    array_push($all_promocodes, $promocode);
                }
                $response["error"] = false;
                $response["all_promocodes"] = $all_promocodes;
                $response["message"] = "Promocode available";
            }else{
                $response["error"] = true;
                $response["message"] = "Promocode not available";
            }
        
            responseJSON($response);
    }
    function activeproduct()
    {
        $this->load->view('admin/include/header');
        $this->load->view('admin/include/sidebar');
        $this->load->view('admin/product/activeproduct');
        $this->load->view('admin/include/footer');
    }
    function createdepartment()
    {

        $this->load->view('admin/include/header');
        $this->load->view('admin/include/sidebar');
        $this->load->view('admin/create-department');
        $this->load->view('admin/include/footer');
    }
    function createcategory()
    {
        $this->load->view('admin/include/header');
        $this->load->view('admin/include/sidebar');
        $this->load->view('admin/create-category');
        $this->load->view('admin/include/footer');
    }

    public function getsingleorder()
    {  
       
        // Disable store_id check
        $auth = $this->input->get('Authorization');
        // If Store_id is passed from view use it otherwise use the one in session
        // $store_id = $this->input->get('store_id') ? $this->input->get('store_id') : $this->session->userdata('store')->id;
        $store_id = $this->input->get('store_id') ? $this->input->get('store_id') : -1;
        
        $query_segment = $this->input->get('orderid');
        $response = array();
        date_default_timezone_set('America/New_York');

        $res = $this->common->curlpostRequest3('orderdetail', $auth, $query_segment);
        $res1 = json_decode($res);

        /*
         * Edit order view requires store_id for alternate suggestion and etc, therefore passing current orders store_id in session
         */
        $store = false;
        $result = $this->common->curlpostRequest3('get_store', $auth, $res1->order->store_id);
        $result = json_decode($result);

        if(!$result->error){
            $store = $result->store;
        }

        if($store){
            $this->session->set_userdata('store',$store);
        }

        $this->common->responseJSON($res1);
    }
    function edit_promocode($id)
    {
        $api_key = '5264c97f0fb14fd6d02bc98c9b30fee2';
        $response = array();
        $promocode = $this->Model->get_record('promocode',array('id'=>$id));
        if(count($promocode)>0) {
            $response["error"] = false;
            $response["message"] = "promocode details available";
            $response["promocode"] = $promocode[0];
        } else {
            $response["error"] = true;
            $response["message"] = "Invalid promocode";
        }
        $result=$response;
        
        if($result['error']==false){
           
            $data =array();
            $data['id'] = $result["promocode"]['id'];
            $data['end_date'] = date('m/d/Y',$result["promocode"]["end_date"]);
            $data['allowed_per_user'] = $result["promocode"]["allowed_per_user"];
            //$data['max_discount_amount'] = $result->promocode->max_discount_amount;
            $data['min_order_amount'] = $result["promocode"]["min_order_amount"];
           
        }
        $this->load->view('admin/include/header');
        $this->load->view('admin/include/sidebar');
        $this->load->view('admin/edit_promocode',$data);
        $this->load->view('admin/include/footer');
    }
    function update_promocode()
    {
        $auth = $this->input->post('Authorization');
        $p_id =$this->input->post('p_id');
        $data = array(
            // 'code' => $this->input->post('code'),
            // 'ctype' => $this->input->post('ctype'),
            // 'cvalue' => $this->input->post('cvalue'),
            'p_id' => $this->input->post('p_id'),
            'max_discount_amount' => $this->input->post('max_discount_amount'),
            'end_date' => $this->input->post('end_date'),
            'per_user_allowed' => $this->input->post('per_user_allowed'),
            'minimum_order_size' => $this->input->post('minimum_order_size'),
            // 'description' => $this->input->post('description')
        );
       // var_dump($data);exit;
        $this->Model->update('promocode',array('max_discount_amount'=>$this->input->post('max_discount_amount'),'end_date' => $this->input->post('end_date'),'allowed_per_user' => $this->input->post('per_user_allowed'),'min_order_amount' => $this->input->post('minimum_order_size')),array('id'=>$p_id));die;
        $res = $this->common->curlpostRequest1('update_promocode', $data, $auth);
        $res1 = json_decode($res);
        $this->common->responseJSON($res1);
    }
    function deletepromocode()
    {
        $auth = $this->input->post('Authorization');
        $id = $this->input->post('id');
        $data = array('id' =>$id);
        $result = $this->tablemodels->admin_delete_promocode($id);
        if ($result=="not delete") {
            $response["error"] = true;
            $response["result"] = $result;
            $response["message"] = "You cannot delete this promocode as it is being used by someone, therefore changing status to inactive";
        }
        elseif ($result=="done") {
            $response["error"] = false;
            $response["result"] = $result;
            $response["message"] = "Promocode delete successfully";
        }
        else {
            $response["error"] = true;
            $response["message"] = "Promocode not delete";
        }
        responseJSON($response);;
    }
    function add_promocode()
    {
        $this->load->view('admin/include/header');
        $this->load->view('admin/include/sidebar');
        $this->load->view('admin/add_promocode');
        $this->load->view('admin/include/footer');
    }
    function linking($id)
    {
        $data['itemid'] = array('item_id' => $id);
        $this->load->view('admin/include/header');
        $this->load->view('admin/include/sidebar');
        $this->load->view('admin/linking', $data);
        $this->load->view('admin/include/footer');

    }
}

?>
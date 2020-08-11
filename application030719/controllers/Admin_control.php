<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_control extends CI_Controller {
    function __construct() {
        parent::__construct();
        if(!$this->session->has_userdata('go2groadmin_session')){
            redirect('Go2gro_adminlogin');
        }
        $this->load->model('Datatables_model',"tablemodels");
        $logged_user = logged_admin_record();
        /*$this->loggeduserId = $logged_user['id'];
        $this->loggeduserName = $logged_user['name'];*/
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
}

?>
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_agent_details extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->database();
		$this->load->model('Model','m');
	}

	public function index()
	{
		$this->load->view('user_agent_data/index');
	}

	public function get_data(){
		$data = $row = array();
		// Set orderable column fields
        $column_order = array(null, 'users.first_name','users.last_name','client_ip_info.pincode','client_ip_info.ip_address','client_ip_info.created_at');
        // Set searchable column fields
        $column_search = array('users.first_name','users.last_name','client_ip_info.pincode','client_ip_info.ip_address','client_ip_info.created_at');
        // Set default order
        $order = array('client_ip_info.created_at' => 'desc');
        $info = array('users.first_name','users.last_name','client_ip_info.*');
		$memData = $this->m->getRows($info,$_POST,'client_ip_info','users','users.id=client_ip_info.userid',$column_order,$column_search,$order);


		if(isset($_POST['start'])){
			$i=$_POST['start'];
		}else{
			$i=0;
		}
		foreach($memData as $value){
            $i++;
            $created = date('d-m-Y H:i:s', strtotime($value->created_at));
            //$status = ($value->status == 1)?'Active':'Inactive';
            $data[] = array($i, 
            	$created,$value->first_name.' '.$value->last_name,
            	$value->pincode ,
            	$value->country , 
            	$value->ip_address , 
            	$value->city, 
            	$value->region, 
            	$value->latitude, 
            	$value->longitude, 
            	$value->continentName, 
            	$value->browser_name, 
            	$value->user_aget_details);
        }
        
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->m->countAll('client_ip_info'),
            "recordsFiltered" => $this->m->countFiltered($info,$_POST,'client_ip_info','users','users.id=client_ip_info.userid',$column_order,$column_search,$order),
            "data" => $data,
        );
        
        // Output to JSON format
        echo json_encode($output);
	}
}
?>
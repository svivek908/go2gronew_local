<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Datatables_model extends CI_Model { 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	//----------For one Table------
	private function _get_datatables_query($table,$condition,$column_search,$column_order,$order)
	{
		$this->db->from($table);
		if(isset($condition)){
			$this->db->where($condition);
		}
		$i = 0;
	
		foreach ($column_search as $item) // loop column 
		{
			if($_POST['search']['value']) // if datatable send POST for search
			{
				
				if($i===0) // first loop
				{
					$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
					$this->db->like($item, $_POST['search']['value']);
				}
				else
				{
					$this->db->or_like($item, $_POST['search']['value']);
				}

				if(count($column_search) - 1 == $i) //last loop
					$this->db->group_end(); //close bracket
			}
			$i++;
		}
		
		if(isset($_POST['order'])) // here order processing
		{
			$this->db->order_by($column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} 
		else if(isset($this->order))
		{
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}
	
	function get_datatables($table,$column_select,$condition,$column_search,$column_order,$order)
	{
		$this->db->select($column_select);
		$this->_get_datatables_query($table,$condition,$column_search,$column_order,$order);
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result_array();
	}
	//----------For one Table------
	function count_filtered($table,$column_select,$condition,$column_search,$column_order,$order)
	{
		$this->_get_datatables_query($table,$condition,$column_search,$column_order,$order);
		$query = $this->db->get();
		return $query->num_rows();
	}

	//-----------get data with two tables----------------------------------------
	
	private function _get_datatwotables_query($table1,$table2,$relation,$condition,$column_search,$order)
	{
		$this->db->from($table1);
		$this->db->join($table2,$relation,"left");
		if(isset($condition)){
			$this->db->where($condition);
		}
		$i = 0;
		if(isset($_POST['search']) && $_POST['search']!='')
		{
			foreach ($column_search as $item) // loop column 
			{
				if($_POST['search']) // if datatable send POST for search
				{
					
					if($i===0) // first loop
					{
						$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
						$this->db->like($item, $_POST['search']);
					}
					else
					{
						$this->db->or_like($item, $_POST['search']);
					}

					if(count($column_search) - 1 == $i) //last loop
						$this->db->group_end(); //close bracket
				}
				$i++;
			}
		}
		if(isset($order))
		{
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	function get_datatwotables($table1,$table2,$column_select,$relation,$condition,$column_search,$order,$limit,$start)
	{
		$this->db->select($column_select);
		$this->_get_datatwotables_query($table1,$table2,$relation,$condition,$column_search,$order);
		$this->db->limit($limit, $start);
		$query = $this->db->get();
		//echo $this->db->last_query();
		return $query->result_array();
	}

	public function count_twotablefiltered($table1,$table2,$column_select,$relation,$condition,$column_search,$order)
	{
		$this->_get_datatwotables_query($table1,$table2,$relation,$condition,$column_search,$order);
		$query = $this->db->get();
		return $query->num_rows();
	}

	//-----------get data with three tables----------------------------------------
	
	private function _get_data_threetables_query($table1,$table2,$table3,$relation1,$relation2,$join_type1,$join_type2,$condition,$column_search,$column_order,$order)
	{
		$this->db->from($table1);
		$this->db->join($table2,$relation1,$join_type1);
		$this->db->join($table3,$relation2,$join_type2);
		if(isset($condition)){
			$this->db->where($condition);
		}
		$i = 0;
		if(isset($_POST['search']) && $_POST['search']!='')
		{
			foreach ($column_search as $item) // loop column 
			{
				if($_POST['search']['value']) // if datatable send POST for search
				{
					
					if($i===0) // first loop
					{
						$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
						$this->db->like($item, $_POST['search']['value']);
					}
					else
					{
						$this->db->or_like($item, $_POST['search']['value']);
					}

					if(count($column_search) - 1 == $i) //last loop
						$this->db->group_end(); //close bracket
				}
				$i++;
			}
		}
		if(isset($_POST['order'])) // here order processing
		{
			$this->db->order_by($column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		}
		else if(isset($this->order))
		{
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	function get_data_threetables($table1,$table2,$table3,$column_select,$relation1,$relation2,$join_type1,$join_type2,
	$condition,$column_search,$column_order,$order,$limit,$start,$group_by = "")
	{
		$this->db->select($column_select);
		$this->_get_data_threetables_query($table1,$table2,$table3,$relation1,$relation2,$join_type1,$join_type2,$condition,$column_search,$column_order,$order);
		$this->db->limit($limit, $start);
		if($group_by!=""){
			$this->db->group_by($group_by);
		}
		$query = $this->db->get();
		//echo $this->db->last_query();
		return $query->result_array();
	}

	public function count_threetablefiltered($table1,$table2,$table3,$column_select,$relation1,$relation2,$join_type1,$join_type2,$condition,$column_search,$column_order,$order)
	{
		$this->_get_data_threetables_query($table1,$table2,$table3,$relation1,$relation2,$join_type1,$join_type2,$condition,$column_search,$column_order,$order);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all($table,$condition)
	{
		$this->db->from($table);
		if(isset($condition)){
			$this->db->where($condition);
		}
		return $this->db->count_all_results();
	}
	public function adm_getOrderByPickerId($pickerId, $store_id=1)
    {
        $this->db->select(" po.`picker_id`, po.`order_id`, po.`dispatch_time`, CONCAT( sa.`first_name` , ' ', sa.`last_name` ) AS ship_name, sa.address as ship_address, sa.email_id as ship_email, sa.ship_mobile_number, sa.pincode as ship_pincode, ot.delivery_time, ot.user_id as order_user_id, ot.`total_price`, ot.`tax`, ot.`finalprice`, ot.`dlv_charge`, ot.`tip_amount`, ot.`processingfee`, ot.`datetime`, ot.`dlv_date`, ot.`auth_amount`, ot.`is_order_edit`, ot.`status` as order_status, tts.slot_name as delivery_slot
		FROM `pd_order` as po
		INNER JOIN `order_table` as ot ON po.order_id=ot.order_id
		INNER JOIN shipping_address as sa ON po.order_id=sa.order_id
		INNER JOIN tbl_time_slot as tts ON tts.time_slot_id = ot.slot_id
		WHERE po.`picker_id`='".$pickerId."'
		AND ot.store_id='".$store_id."'");
        $query = $this->db->get();
        $result = $query->result_array();
        $orderArray = array();
        if ($result->num_rows > 0) {
            foreach ( $result as $orderDetail) {
                array_push($orderArray, $orderDetail);
            }
            $result = $orderArray;
        } else {
            $result = false;
        }
        $stmt->close();
        return $result;
	}
	public function get_all_stores(){
		$result = null;
		$this->db->from('stores');
		$this->db->order_by("created_at", "DESC");
		$query = $this->db->get(); 
		return $query->result();
        
	}
	public function get_all_record(){
		$result = null;
		$this->db->from('promocode');
		$this->db->order_by("id", "DESC");
		$query = $this->db->get(); 
		return $query->result();
        
	}
	public function admin_delete_promocode($id)
    {
        /*
        * Check promocode used by user if exits then update code status inactive
        */
        $this->db->select(" `promocode_id` FROM `user_promocode` WHERE `promocode_id`='".$id."'");
		$query = $this->db->get();
        $res = $query->result();
        $responce = false;
        if (count($res) > 0) {
			$updatestatus ="inactive";
			$data= array('status'=>$updatestatus);
			$this->db->where('id',$id);
		 $this->db->update('promocode',$data);
		 return 'not delete';
        }
        else{
			$this->db->where('id', $id);
			 $this->db->delete('promocode');
			 return 'done';
            
        }
        return $responce;
    }

}

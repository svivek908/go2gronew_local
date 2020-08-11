<?php
Class Cart_item extends CI_Model{

	//--------------checkitemisexistinstore-----------------
	public function checkitemisexistinstore($itemid,$store_id){
        $bol = false;
        $item_table = STORE_PREFIX.$store_id.'_'.ITEMS_TABLE;
        $this->db->select('*');
        $this->db->from("`".$item_table."`");
        $this->db->where(array('item_id' => $itemid,'item_status' => 0));
        $query = $this->db->get();
        if ($query->num_rows() > 0) 
        {
            $bol = true;
        }
        return $bol;
    }
    //-------------getcartitem--------------
	public function getcartitem($userid, $store_id,$itemid=false){
		$condition = array('ci.user_id' => $userid,'ci.store_id' => $store_id,'ci.status' => '0');
		if($itemid){
			$condition['ci.item_id'] = $itemid;
		}
        $item_table = STORE_PREFIX.$store_id.'_'.ITEMS_TABLE;
        $itemimages_table = STORE_PREFIX.$store_id.'_'.ITEMIMAGES_TABLE;
        $this->db->select("p.`id` ,ci.id as arrangeid, p.`item_id` , CONCAT(p.`item_name`, ' ', p.`item_size`) as item_name , p.`item_sdesc` , p.`item_fdesc` , p.`item_price` , p.`item_status` , p.`Sales_tax` , ci.`item_quty` , (ci.`item_quty` * p.item_price) AS total, itmg.imageurl AS item_image");
        $this->db->from("cart_item` as ci");
        $this->db->join("`".$item_table."` as p",'ci.`item_id` = p.item_id');
        $this->db->join("`".$itemimages_table."` as itmg",'itmg.image_id = p.item_image');
        $this->db->where($condition);
	    $this->db->order_by("ci.id",'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) 
        {
            return $query->result_array();
        }
        else
        {
            return array();
        }
	}

    //-------------is_item_under_purchase_limit----------
    public function is_item_under_purchase_limit($item_ids, $store_id, $user_id = null){
    	$item_ids_string = "'" . implode ( "', '", $item_ids ) . "'";
    	$item_table = STORE_PREFIX.$store_id.'_'.ITEMS_TABLE;
        $itemlink_table =   STORE_PREFIX.$store_id.'_'.ITEMLINK_TABLE;
        $where = "i.item_id IN (".$item_ids_string.")";
    	if($user_id == null){
    		$select_arr = array('i.fluid_ounce','i.item_type','item_size','i.item_id', 's.cat_id');
    		$this->db->from("`".$item_table."` as i")
    		->join("`".$itemlink_table."` as il",'i.item_id = il.item_id')
    		->join('subcategory as s','il.subcat_id = s.sub_id')
    		->where($where);
    	}else{
    		$select_arr = array('i.fluid_ounce','i.item_type','item_size', 'i.item_id','ct.item_quty', 's.cat_id');
    		$this->db->from("`".$item_table."` as i")
    		->join("`".$itemlink_table."` as il",'i.item_id = il.item_id')
    		->join('subcategory as s','il.subcat_id = s.sub_id')
    		->join('cart_item as ct','i.item_id = ct.item_id')
    		->where($where)
    		->where(array('ct.status' => '0', 'ct.user_id' => $user_id, 'ct.store_id' => $store_id));
    	}
    	$this->db->select($select_arr);
    	$query = $this->db->get();
        if ($query->num_rows() > 0) 
        {
            return $query->result_array();
        }
        else
        {
            return array();
        }
    }
    //----------------ischeckfirsetorder------------
    public function ischeckfirsetorder($userid)
    {
        $chk = $this->getmyneworder($userid);
        if (count($chk) > 0) {
        	return false;
        }
        else{
            $chr = $this->Model->get_selected_data('ismobile_verify','users',array('id' =>$userid));
            if ($chr[0]['ismobile_verify'] == 1) {
                return true;
            } else {
                return false;
            }
        }
    }
    //----------getmyneworder------------
    public function getmyneworder($userid){
   		$this->db->select("ord.order_id, ord.finalprice, ord.datetime,ord.refund_status,ord.is_order_edit, ord.status, str.`name` AS storename,str.`id` AS store_id, usr.`first_name`,usr.`last_name`,usr.email_id, usr.mobile, usr.address, usr.pincode, CONCAT( shpadd.`first_name` , ' ', shpadd.`last_name` ) AS ship_name, shpadd.`address` AS shipping_address, shpadd.`ship_mobile_number` AS ship_mobile, shpadd.`pincode` AS ship_pincode");
        $this->db->from("order_table` as ord");
        $this->db->join("users as usr",'ord.`user_id` = usr.id');
        $this->db->join("stores as str",'str.`id`= ord.`store_id`');
        $this->db->join("shipping_address as shpadd",'ord.`order_id` = shpadd.`order_id`');
        $this->db->where('ord.`user_id`',$userid);
        $this->db->order_by("ord.datetime",'DESC');
        $query = $this->db->get();
        if ($query->num_rows() > 0) 
        {
            return $query->result_array();
        }
        else
        {
            return array();
        }
    }

    //-------------updateitemincart------------
    public function updateitemincart($itemid, $status, $alt_item, $orderid, $quty)
    {
        $res = $this->Model->update('ordered_item',array('status' => '4'),array('item_id'=> $itemid,'order_id' => $orderid));
        if($res){
            $sql = "UPDATE ordered_item SET `status` = CASE `item_id` WHEN ".$itemid." THEN ".$status." END ,`item_quty` = CASE `item_id` WHEN ".$itemid."  THEN ".$quty." END WHERE  `order_id`= ".$orderid." AND (`alernative_item_id` = ".$alt_item." AND `item_id` = ".$itemid." )";
            $query = $this->db->query($sql);
            if ($query->affected_rows() > 0) 
            {
                return true;
            }else{
                $res = $this->Model->update('ordered_item',array('status' => '2'),array('item_id'=> $itemid,'order_id' => $orderid));
                return false;
            }
        }else{
            return false;
        }
    }
}
?>
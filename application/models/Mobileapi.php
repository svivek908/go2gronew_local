<?php
Class Mobileapi extends CI_Model{

	public function mob_getRelateditem($itemid, $store_id){
        $item_table = STORE_PREFIX.$store_id.'_'.ITEMS_TABLE;
        $itemimages_table = STORE_PREFIX.$store_id.'_'.ITEMIMAGES_TABLE;
        $itemlink_table =   STORE_PREFIX.$store_id.'_'.ITEMLINK_TABLE;
        $itemrating_table = STORE_PREFIX.$store_id.'_'.ITEMRATING_TABLE;
        $result =  $this->getsubitem($itemlink_table,$itemid,$store_id);
        if(count($result) > 0){
            $subid = $result[0]['subid'];
            if ($subid == null) {
                $this->db->select("p.`id`,p.`item_id`,CONCAT(p.`item_name`, ' ', p.item_size) as item_name,p.`item_sdesc`,p.`item_fdesc`,p.`item_price`,p.`item_status`,p.`Sales_tax` , IFNULL( ROUND( AVG( pr.rating ) ) , 0 ) AS rating_average,itmg.imageurl as item_image FROM ".$itemrating_table." pr
                    RIGHT JOIN `".$item_table."` p ON pr.`item_id` = p.`item_id`
                    join `".$itemimages_table."` itmg on itmg.image_id=p.`item_image` and p.item_status = '0' and p.item_id !=".$itemid." GROUP BY p.`item_id` ORDER BY RAND() LIMIT 0 , 15");
            } else {
                $this->db->select("p.`id`,p.`item_id`,CONCAT(p.`item_name`, ' ', p.item_size) as item_name,p.`item_sdesc`,p.`item_fdesc`,p.`item_price`,p.`item_status`,p.`Sales_tax` , IFNULL( ROUND( AVG( pr.rating ) ) , 0 ) AS rating_average,itmg.imageurl as item_image
                    FROM ".$itemrating_table." pr RIGHT JOIN `".$item_table."` p ON pr.`item_id` = p.`item_id` join `".$itemimages_table."` itmg on itmg.image_id=p.`item_image` AND p.`item_id` in (SELECT `item_id` FROM `".$itemlink_table."` WHERE `subcat_id`in (".$subid.")) and p.item_status = '0'
                    GROUP BY p.`item_id` ORDER BY RAND() LIMIT 0 , 15");
            }
        }else{
            $this->db->select("p.`id`,p.`item_id`,CONCAT(p.`item_name`, ' ', p.item_size) as item_name,p.`item_sdesc`,p.`item_fdesc`,p.`item_price`,p.`item_status`,p.`Sales_tax` , IFNULL( ROUND( AVG( pr.rating ) ) , 0 ) AS rating_average,itmg.imageurl as item_image FROM ".$itemrating_table." pr
                RIGHT JOIN `".$item_table."` p ON pr.`item_id` = p.`item_id`
                join `".$itemimages_table."` itmg on itmg.image_id=p.`item_image` and p.item_status = '0' and p.item_id !=?
                GROUP BY p.`item_id`
                ORDER BY RAND() LIMIT 0 , 15");
        }
        $query = $this->db->get();
        if ($query->num_rows() > 0) 
        {
            return $query->result_array();
        }else {
            return NULL;
        }
    }	

    //-------------------mob_getBestseller------------
    public function mob_getBestseller($userid, $store_id)
    {
        $item_table = STORE_PREFIX.$store_id.'_'.ITEMS_TABLE;
        $itemimages_table = STORE_PREFIX.$store_id.'_'.ITEMIMAGES_TABLE;
        $itemrating_table = STORE_PREFIX.$store_id.'_'.ITEMRATING_TABLE;
        if ($userid == "0") {
        	$query = $this->db->query("p.`id`,p.`item_id`,CONCAT(p.`item_name`, ' ', p.item_size) as item_name,p.`item_sdesc`,p.`item_fdesc`,p.`item_price`,p.`item_status`,p.`Sales_tax` ,p.`discount`, IFNULL( ROUND( AVG( pr.rating ) ) , 0 ) AS rating_average,itmg.imageurl as item_image FROM $itemrating_table pr RIGHT JOIN $item_table p ON pr.`item_id` = p.`item_id` join $itemimages_table itmg on itmg.image_id=p.`item_image` AND p.item_status = '0' and p.`discount`!='0' GROUP BY p.`item_id` order by rand() LIMIT 0 , 15"); 
        }else{
           	$query = $this->db->query("p.`id`,p.`item_id`,CONCAT(p.`item_name`, ' ', p.item_size) as item_name,p.`item_sdesc`,p.`item_fdesc`,p.`item_price`,p.`item_status`,p.`Sales_tax` ,p.`discount`, IFNULL( ROUND( AVG( pr.rating ) ) , 0 ) AS rating_average,itmg.imageurl as item_image FROM $itemrating_table pr RIGHT JOIN $item_table p ON pr.`item_id` = p.`item_id` join $itemimages_table itmg on itmg.image_id=p.`item_image` and p.item_id not in (SELECT DISTINCT `item_id` FROM `cart_item` WHERE `user_id`=? and `status`='0' AND store_id=?) AND p.item_status = '0' and p.`discount`!='0' GROUP BY p.`item_id` order by rand() LIMIT 0 , 15");
        }
        if ($query->num_rows() > 0) 
        {
            return $query->result_array();
        }else {
            return NULL;
        }
    }

    //------------------getitembysubcat----------
    public function getitembysubcat($subcat, $store_id){
    	$query = $this->db->query("SELECT p.`id`,p.`item_id`,CONCAT(p.`item_name`, ' ', p.`item_size`) as item_name,p.`item_sdesc`,p.`item_fdesc`,p.`item_price`,p.`item_status`,p.`Sales_tax` , IFNULL( ROUND( AVG( pr.rating ) ) , 0 ) AS rating_average,itmg.imageurl as item_image FROM ".$itemrating_table." pr
			RIGHT JOIN `".$item_table."` p ON pr.`item_id` = p.`item_id`
			JOIN ".$itemlink_table." inl ON inl.item_id = p.item_id
			join `".$itemimages_table."` itmg on itmg.image_id=p.`item_image`
			AND inl.subcat_id =?
			AND p.item_status =  '0'
			GROUP BY p.`item_id` ");
    	if ($query->num_rows() > 0) 
        {
            return $query->result_array();
        }else {
            return NULL;
        }
    }

    public function getmyneworder($userid,$store_id)
    {
        $this->db->select("ord.order_id, ord.finalprice, ord.datetime,ord.refund_status,ord.is_order_edit, ord.status, str.`name` AS storename,str.`id` AS store_id, usr.`first_name`,usr.`last_name`,usr.email_id, usr.mobile, usr.address, usr.pincode, CONCAT( shpadd.`first_name` , ' ', shpadd.`last_name` ) AS ship_name, shpadd.`address` AS shipping_address, shpadd.`ship_mobile_number` AS ship_mobile, shpadd.`pincode` AS ship_pincode")
        ->from('order_table as ord')
        ->join('users usr','ord.user_id = usr.id')
        ->join('stores str', 'ord.store_id = stores.id')
        ->join('shipping_address as shpadd', 'ord.order_id = shpadd.order_id')
        ->where(array('ord.user_id' => $userid,'ord.order_id' => $orderid))
        ->order_by('ord.datetime','DESC');
        $query = $this->db->get();
        if ($query->num_rows() > 0) 
        {
            return $query->result_array();
        }else {
            return NULL;
        }
    }

    private function getsubitem($table,$itemid, $store_id){
        $this->db->select("GROUP_CONCAT(`subcat_id`) as subid")
        ->from($table)
        ->where(array('item_id' => $itemid, 'status' => '0'));
        $query = $this->db->get();
        if ($query->num_rows() > 0) 
        {
            return $query->result_array();
        }else {
            return NULL;
        }
    }

    public function getNotification($userid){
        $this->db->select("tn.`id`,tn.`order_id`, tn.`userid`, tn.`notificationuserid`, tn.`action`, tn.`date`, tn.`unitime`, tn.`status`, tn.`title`, tn.`message`, tn.`tag`, stores.`name` as storename,stores.`id` as store_id")
        ->from('tbl_notification as tn')
        ->join('order_table as ot','tn.order_id=ot.order_id')
        ->join('stores','ot.`store_id`= stores.`id`','left')
        ->where("tn.`notificationuserid`",$userid)
        ->order_by(tn.`date`,"DESC");
        $query = $this->db->get();
        if ($query->num_rows() > 0) 
        {
            return $query->result_array();
        }else {
            return NULL;
        }
    }
}
?>
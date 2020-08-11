<?php
Class Picker_model extends CI_Model 
{
    public function isuservalid($username, $password)
    {
        $this->db->select('
		            pd_person.mobile_no,
		            pd_person.email,
		            pd_person.password')->from('pd_person')->where("(pd_person.email = '" . $username . "' OR pd_person.mobile_no = '" . $username . "')")->where('password', $password);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    
    public function insert_gcm_data_for_picker($device_token, $device_type, $userid, $time)
    {
        if ($devicetype == "android") {
            $this->db->select('*');
            $this->db->where('device_type', $device_type);
            $this->db->where('picker_id', $userid);
            $this->db->where('gcm_id', $device_token);
        } else if ($devicetype == "ios") {
            $this->db->select('*');
            $this->db->where('device_type', $device_type);
            $this->db->where('picker_id', $userid);
            $this->db->where('uid', $device_token);
        } else {
            return null;
        }
        $query = $this->db->get("picker_gcm");
        if ($query->num_rows() > 0) {
            return true;
        } else {
            if ($devicetype == 'android') {
                $data = array(
                    'picker_id' => $userid,
                    'gcm_id' => $device_token,
                    'device_type' => $device_type,
                    'unitime' => $time
                );
                $this->db->insert('picker_gcm', $data);
                $insert_id = $this->db->insert_id();
                return $insert_id;
            } else if ($devicetype == 'ios') {
                $data = array(
                    'picker_id' => $userid,
                    'uid' => $device_token,
                    'device_type' => $device_type,
                    'unitime' => $time
                );
                $this->db->insert('picker_gcm', $data);
                $insert_id = $this->db->insert_id();
                return $insert_id;
            }
            if ($insert_id) {
                return true;
            } else {
                return null;
            }
            
        }
    }
    
    public function getOrderInfoByOrderId($order_id)
    {
        $this->db->select(" users.`id` as user_id, users.`first_name`, users.`last_name`, users.`email_id`, users.`mobile`, users.`address`, users.`pincode`,
		ot.`order_id`, ot.`txn_id`, ot.`total_price`, ot.`tax`, ot.`finalprice`, ot.`dlv_charge`, ot.`tip_amount`, ot.`processingfee`, ot.`datetime`,ot.slot_id, ot.`dlv_date`, ot.`auth_amount`, ot.`refund_status`, ot.`refund_txnid`, ot.`is_payment_done`, ot.`void_status`, ot.`is_order_edit`, ot.`status` as order_status, ot.`delivery_time`, ot.store_id,
		CONCAT(sa.`first_name`, sa.`last_name`) ship_name, sa.`address` as ship_address, sa.`ship_mobile_number`, sa.`email_id` as ship_email, sa.`pincode` as ship_pincode
		FROM `order_table` as ot
		INNER JOIN `shipping_address` as sa on ot.`order_id`=sa.`order_id`
		INNER JOIN users on ot.user_id =users.id
		WHERE ot.`order_id`='" . $order_id . "'");
        $query = $this->db->get();
        //echo $this->db->last_query();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return array();
        }
    }
    
    public function getOrderByPickerId($picker_id)
    {
        $this->db->select(" po.`picker_id`, po.`order_id`, po.`dispatch_time`,
		CONCAT( sa.`first_name` , ' ', sa.`last_name` ) AS ship_name, sa.address as ship_address, sa.email_id as ship_email, sa.ship_mobile_number, sa.pincode as ship_pincode,
		ot.varity_count, ot.item_count, ot.delivery_time, ot.user_id as order_user_id, ot.pickerAltAprovaltime, ot.is_order_edit,ot.store_id,ot.slot_id
		FROM `pd_order` as po
		INNER JOIN `order_table` as ot ON po.order_id=ot.order_id
		INNER JOIN shipping_address as sa ON po.order_id=sa.order_id
		WHERE po.`picker_id`='" . $picker_id . "' AND ot.status=1");
        $query = $this->db->get();
        //echo $this->db->last_query();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return array();
        }
    }
    
    public function getstlottime($slotid, $store_id)
    {
        $table_name_prefix = STORE_PREFIX . $store_id;
        $time_sloat        = $table_name_prefix . '_' . TIME_SLOT;
        $this->db->select('*');
        $this->db->from("`" . $time_sloat . "`");
        $this->db->where('time_slot_id', $slotid);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return array();
        }
    }
    public function getItemdetailByOrderId($order_id, $store_id = 1)
    {
        // cart_item -> ordered_item -> finished_item
        $result = false;
        
        $item_table       = STORE_PREFIX . $store_id . '_' . ITEMS_TABLE;
        $itemimages_table = STORE_PREFIX . $store_id . '_' . ITEMIMAGES_TABLE;
        $itemlink_table   = STORE_PREFIX . $store_id . '_' . ITEMLINK_TABLE;
        
        /*
         * Author:Burhan on 6 Dec 18
         * Changed structure & query.
         * When an item whos subcat_id was 0 in itemlink table item was not being returned
         * Changed to LEFT OUTER JOIN from INNER JOIN
         * And passed generic category name
         */
        $sql = " cit.`item_id`, cit.`item_quty`, cit.`user_id`, cit.`price`, cit.`tax`, cit.`order_id`,
        CONCAT(item.`item_name`, ' ', item.item_size) as item_name ,iimg.imageurl ,
        IFNULL(scat.sub_name, 'Uncategorised') as sub_name
        FROM ordered_item as cit
        INNER JOIN $item_table as item ON cit.item_id= item.item_id
        INNER JOIN $itemlink_table as ilnk ON cit.item_id=ilnk.item_id
        INNER JOIN $itemimages_table as iimg on iimg.image_id = item.item_image
        LEFT OUTER JOIN subcategory as scat ON ilnk.subcat_id = scat.sub_id
        WHERE cit.order_id = '" . $order_id . "' AND cit.status=1
        GROUP BY cit.item_id";
        $this->db->select($sql);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $ordered_item_table_res = $result = $query->result_array();
            /*
             * if the items are not present in ordered_item they might have been transferred to finished_item
             */
            if (count($ordered_item_table_res->num_rows) == 0) {
                $sql = "SELECT cit.`item_id`, cit.`item_quty`, cit.`user_id`, cit.`price`, cit.`tax`, cit.`order_id`,
        CONCAT(item.`item_name`, ' ', item.item_size) as item_name ,iimg.imageurl ,
        IFNULL(scat.sub_name, 'Uncategorised') as sub_name
        FROM finished_item as cit
        INNER JOIN $item_table as item ON cit.item_id= item.item_id
        INNER JOIN $itemlink_table as ilnk ON cit.item_id=ilnk.item_id
        INNER JOIN $itemimages_table as iimg on iimg.image_id = item.item_image
        LEFT OUTER JOIN subcategory as scat ON ilnk.subcat_id = scat.sub_id
        WHERE cit.order_id = '" . $order_id . "' AND cit.status=1
        GROUP BY cit.item_id";
                $this->db->select($sql);
                $query = $this->db->get();
                if ($query->num_rows() > 0) {
                    $result = $query->result_array();
                    
                }
            }
        }
        return $result;
    }
    public function getItemdetailByOrderIdWithMotherCategory($order_id, $store_id = 1)
    {
        $item_table       = STORE_PREFIX . $store_id . '_' . ITEMS_TABLE;
        $itemimages_table = STORE_PREFIX . $store_id . '_' . ITEMIMAGES_TABLE;
        
        // cart_item -> ordered_item
        $this->db->select(" cit.`item_id`, cit.`item_quty`, cit.`user_id`, cit.`price`, cit.`tax`, cit.`order_id`,cit.item_found_status, cit.status as item_status,
		CONCAT(item.`item_name`, ' ', item.item_size) as item_name,
		iimg.imageurl,
		mcat.category_name as mother_cat_name, mcat.rank
		FROM `ordered_item` as cit
		INNER JOIN `" . $item_table . "` as item ON cit.item_id= item.item_id
		LEFT JOIN mothers_category as mcat ON item.mother_cat_id=mcat.id
		INNER JOIN `" . $itemimages_table . "` as iimg on iimg.image_id = item.item_image
		WHERE cit.order_id = '" . $order_id . "' AND (cit.status=1 or cit.status=2)
		ORDER BY mcat.rank ASC");
        
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return array();
        }
    }
    public function getAlternativeItem($orderid, $alt_item_id, $store_id = 1)
    {
        $item_table       = STORE_PREFIX . $store_id . '_' . ITEMS_TABLE;
        $itemimages_table = STORE_PREFIX . $store_id . '_' . ITEMIMAGES_TABLE;
        $itemlink_table   = STORE_PREFIX . $store_id . '_' . ITEMLINK_TABLE;
        $response         = false;
        $alternativeArray = array();
        // cart_item -> ordered_item
        $this->db->select(" cit.`item_id`, cit.`item_quty`, cit.`user_id`, cit.`price`, cit.`tax`, cit.`order_id`,cit.item_found_status, cit.status as item_status, CONCAT(item.`item_name`, ' ', item.item_size) as item_name, iimg.imageurl, scat.sub_name, mcat.category_name as mother_cat_name, mcat.rank FROM `ordered_item` as cit INNER JOIN `" . $item_table . "` as item ON cit.item_id= item.item_id INNER JOIN " . $itemlink_table . " as ilnk ON cit.item_id=ilnk.item_id INNER JOIN subcategory as scat ON ilnk.subcat_id = scat.sub_id LEFT JOIN mothers_category as mcat ON item.mother_cat_id=mcat.id INNER JOIN `" . $itemimages_table . "` as iimg on iimg.image_id = item.item_image WHERE cit.`order_id`='" . $orderid . "' AND cit.`alernative_item_id`='" . $alt_item_id . "' AND (cit.status =5 OR cit.status=3) GROUP BY cit.order_id, cit.item_id ORDER BY mcat.rank ASC");
        
        $query  = $this->db->get();
        $result = $query->result_array();
        if (count($result) > 0) {
            foreach ($result as $row) {
                $row = array_map('utf8_encode', $row);
                array_push($alternativeArray, $row);
                
            }
            $response = $alternativeArray;
        } else {
            $response = $alternativeArray;
        }
        
        return $response;
    }
    public function getSubCategoryNameForItem($item_id, $store_id = 1)
    {
        $sub_name = 'Uncategorised';
        
        $itemlink_table = STORE_PREFIX . $store_id . '_' . ITEMLINK_TABLE;
        
        $this->db->select(" scat.sub_name
						FROM $itemlink_table as ilnk
						INNER JOIN subcategory as scat ON ilnk.subcat_id = scat.sub_id
						WHERE ilnk.item_id = '" . $item_id . "' LIMIT 1");
        
        $query = $this->db->get();
        
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            if (coun($result) > 0) {
                $sub_name = $result[0]['sub_name'];
            }
        }
        
        return $sub_name;
    }
	public function getOrderForDeliverByPickerId($picker_id, $picker_deliver_status)
	{
		$this->db->select(" po.`picker_id`, po.`order_id`, po.`dispatch_time`,
		CONCAT( sa.`first_name` , ' ', sa.`last_name` ) AS ship_name, sa.address as ship_address, sa.email_id as ship_email, sa.ship_mobile_number, sa.pincode as ship_pincode,
		ot.varity_count, ot.item_count, ot.delivery_time, ot.`total_price`, ot.`tax`, ot.`dlv_charge`, ot.`processingfee`, ot.`datetime`,ot.tip_amount, ot.`finalprice`, ot.status as order_status, ot.user_id as order_user_id, ot.dlv_date,ot.slot_id,st.id as store_id, st.name as store_name, st.logo as store_logo
		FROM `pd_order` as po
		INNER JOIN `order_table` as ot ON po.order_id=ot.order_id
		INNER JOIN shipping_address as sa ON po.order_id=sa.order_id
		INNER JOIN stores as st ON st.id = ot.store_id
		WHERE po.`picker_id`='".$picker_id."' AND (ot.status=2 OR ot.status=3 OR ot.status=4) AND po.status='".$picker_deliver_status."'
		ORDER BY ot.`datetime` DESC");
		$query = $this->db->get();
        
        if ($query->num_rows() > 0) {
            $result = $query->result_array(); 
            
        }else {
			$return = false;
              }
			return $return;
        
        
    }
    // public function updateSingleOrderStatus($orderId, $status){
    //     if($status == 3){
    //         $stmt = $this->conn->prepare("UPDATE `order_table` SET `is_payment_done`= 1, `status`=? WHERE `order_id` =?");
    //         $stmt->bind_param("is", $status, $orderId);
    //     }else{
    //         $stmt = $this->conn->prepare("UPDATE `order_table` SET `status`=? WHERE `order_id` =?");
    //         $stmt->bind_param("is", $status, $orderId);
    //     }
    //     if($stmt->execute()){
    //         if($status == 4) { // when order status changes to delivered (4 = delivered)
    //             // Now remove the entries from ordered_item table and shift to finished_item table
    //             $this->move_from_ordereditem_to_finisheditem($orderId);
    //         }
    //         $return = true;
    //     }else{
    //         $return = false;
    //     }
    //     $stmt->close();
    //     return $return;
    // }
    public function getsublink($itemid,$store_id=1)
    {
        $itemlink_table = STORE_PREFIX.$store_id.'_'.ITEMLINK_TABLE;
        $this->db->select(" scat.sub_id, scat.sub_name FROM  `".$itemlink_table."` iln JOIN subcategory scat ON iln.`subcat_id` = scat.sub_id AND iln.`item_id` = '".$itemid."' AND iln.`status` =  '0'");
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            
        }else {
			$return = false;
              }
			return $return;
    }
    public function getitembysubcat($subcat, $itemid, $store_id=1)
    {
        $item_table = STORE_PREFIX.$store_id.'_'.ITEMS_TABLE;
        $itemimages_table = STORE_PREFIX.$store_id.'_'.ITEMIMAGES_TABLE;
        $itemlink_table =   STORE_PREFIX.$store_id.'_'.ITEMLINK_TABLE;
        $itemrating_table = STORE_PREFIX.$store_id.'_'.ITEMRATING_TABLE;
        // $stmt = $this->conn->prepare("SELECT it. * FROM  `".$itemlink_table."` itln JOIN `".$item_table."` it ON itln.item_id = it.item_id AND itln.subcat_id =? AND itln.status =0");

        $this->db->select(" p.`id`,p.`item_id`,CONCAT(p.`item_name`, ' ', p.item_size) as item_name,p.`item_sdesc`,p.`item_fdesc`,p.`item_price`,p.`item_status`,p.`Sales_tax` , IFNULL( ROUND( AVG( pr.rating ) ) , 0 ) AS rating_average,itmg.imageurl as item_image
        FROM ".$itemrating_table." pr
        RIGHT JOIN `".$item_table."` p ON pr.`item_id` = p.`item_id`
        JOIN ".$itemlink_table." inl ON inl.item_id = p.item_id
        join `".$itemimages_table."` itmg on itmg.image_id=p.`item_image` WHERE inl.subcat_id ='".$subcat."'
        AND p.item_status =  '0' AND p.item_id != '".$itemid."'
        GROUP BY p.`item_id` ");
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            
        }else {
			$return = false;
              }
			return $return;
    }
    public function getsuggetionitems($str, $pagenum, $itemid, $store_id=1)
    {
        $item_table = STORE_PREFIX.$store_id.'_'.ITEMS_TABLE;
        $itemimages_table = STORE_PREFIX.$store_id.'_'.ITEMIMAGES_TABLE;
        $itemlink_table =   STORE_PREFIX.$store_id.'_'.ITEMLINK_TABLE;
        $itemrating_table = STORE_PREFIX.$store_id.'_'.ITEMRATING_TABLE;
        // $stmt = $this->conn->prepare("SELECT it. * FROM  `".$itemlink_table."` itln JOIN `".$item_table."` it ON itln.item_id = it.item_id AND itln.subcat_id =? AND itln.status =0");
        $responcepageno = $pagenum * 20;
        // $stmt = $this->conn->prepare("(SELECT p.`id`,p.`item_id`,p.`item_name`,p.`item_sdesc`,p.`item_fdesc`,p.`item_price`,p.`item_status`,p.`Sales_tax` , IFNULL( ROUND( AVG( pr.rating ) ) , 0 ) AS rating_average,itmg.imageurl as item_image FROM ".$itemrating_table." pr RIGHT JOIN `".$item_table."` p ON pr.`item_id` = p.`item_id` JOIN ".$itemlink_table." inl ON inl.item_id = p.item_id join `".$itemimages_table."` itmg on itmg.image_id=p.`item_image` AND p.item_name like '$str%' AND p.item_status = '0' GROUP BY p.`item_id` limit 0,5 ) UNION (SELECT p.`id`,p.`item_id`,p.`item_name`,p.`item_sdesc`,p.`item_fdesc`,p.`item_price`,p.`item_status`,p.`Sales_tax` , IFNULL( ROUND( AVG( pr.rating ) ) , 0 ) AS rating_average,itmg.imageurl as item_image FROM ".$itemrating_table." pr RIGHT JOIN `".$item_table."` p ON pr.`item_id` = p.`item_id` JOIN ".$itemlink_table." inl ON inl.item_id = p.item_id join `".$itemimages_table."` itmg on itmg.image_id=p.`item_image` AND p.item_name like '%$str%' AND p.item_status = '0' GROUP BY p.`item_id` LIMIT $responcepageno,30)");
        $this->db->select("a.item_id,a.item_name,a.`item_sdesc`,a.`item_fdesc`,a .`item_price`,a.`item_status`,a.`Sales_tax`,a.rating_average,a.item_image,il.subcat_id,c.id from ((SELECT
        p.`id`,p.`item_id`,CONCAT(p.`item_name`, ' ', p.item_size) as item_name,p.`item_sdesc`,p.`item_fdesc`,p
        .`item_price`,p.`item_status`,p.`Sales_tax` , IFNULL( ROUND(
        AVG( pr.rating ) ) , 0 ) AS rating_average,itmg.imageurl as
        item_image FROM ".$itemrating_table." pr RIGHT JOIN `".$item_table."` p ON pr.`item_id`
        = p.`item_id` join `".$itemimages_table."` itmg on
        itmg.image_id=p.`item_image` AND CONCAT(p.`item_name`, ' ', p.item_size) like '$str%' AND
        p.item_status = '0' GROUP BY p.`item_id`  ) UNION (SELECT
        p.`id`,p.`item_id`,CONCAT(p.`item_name`, ' ', p.item_size) as item_name,p.`item_sdesc`,p.`item_fdesc`,p
        .`item_price`,p.`item_status`,p.`Sales_tax` , IFNULL( ROUND(
        AVG( pr.rating ) ) , 0 ) AS rating_average,itmg.imageurl as
        item_image FROM ".$itemrating_table." pr RIGHT JOIN `".$item_table."` p ON pr.`item_id`
        = p.`item_id`  join `".$itemimages_table."` itmg on
        itmg.image_id=p.`item_image` AND CONCAT(p.`item_name`, ' ', p.item_size) like '%$str%' AND
        p.item_status = '0' GROUP BY p.`item_id`))
        AS a
        join ".$itemlink_table." il on a.item_id=il.item_id join subcategory sub on
        sub.sub_id=il.subcat_id join category c on c.id =sub.cat_id WHERE a.item_id != '".$itemid."'
        GROUP by a.item_id
        order by c.id asc
        limit $responcepageno,20");
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            
        }else {
			$return = false;
              }
		return $return;
    }
    public function adm_getsuggetionitemscount($str, $itemid, $store_id=1)
    {
        $item_table = STORE_PREFIX.$store_id.'_'.ITEMS_TABLE;
        $itemimages_table = STORE_PREFIX.$store_id.'_'.ITEMIMAGES_TABLE;
        $itemlink_table =   STORE_PREFIX.$store_id.'_'.ITEMLINK_TABLE;
        $itemrating_table = STORE_PREFIX.$store_id.'_'.ITEMRATING_TABLE;
        // $stmt = $this->conn->prepare("SELECT it. * FROM  `".$itemlink_table."` itln JOIN `".$item_table."` it ON itln.item_id = it.item_id AND itln.subcat_id =? AND itln.status =0");
        //  $stmt = $this->conn->prepare("(SELECT p.`id`,p.`item_id`,p.`item_name`,p.`item_sdesc`,p.`item_fdesc`,p.`item_price`,p.`item_status`,p.`Sales_tax` , IFNULL( ROUND( AVG( pr.rating ) ) , 0 ) AS rating_average,itmg.imageurl as item_image FROM ".$itemrating_table." pr RIGHT JOIN `".$item_table."` p ON pr.`item_id` = p.`item_id` JOIN ".$itemlink_table." inl ON inl.item_id = p.item_id join `".$itemimages_table."` itmg on itmg.image_id=p.`item_image` AND p.item_name like '$str%' AND p.item_status = '0' GROUP BY p.`item_id` limit 0,5 ) UNION (SELECT p.`id`,p.`item_id`,p.`item_name`,p.`item_sdesc`,p.`item_fdesc`,p.`item_price`,p.`item_status`,p.`Sales_tax` , IFNULL( ROUND( AVG( pr.rating ) ) , 0 ) AS rating_average,itmg.imageurl as item_image FROM ".$itemrating_table." pr RIGHT JOIN `".$item_table."` p ON pr.`item_id` = p.`item_id` JOIN ".$itemlink_table." inl ON inl.item_id = p.item_id join `".$itemimages_table."` itmg on itmg.image_id=p.`item_image` AND p.item_name like '%$str%' AND p.item_status = '0' GROUP BY p.`item_id`)");

        $this->db->select(" a.item_id,a.item_name,a.`item_sdesc`,a.`item_fdesc`,a .`item_price`,a.`item_status`,a.`Sales_tax`,a.rating_average,a.item_image,il.subcat_id,c.id from ((SELECT
        p.`id`,p.`item_id`,CONCAT(p.`item_name`, ' ', p.item_size) as item_name,p.`item_sdesc`,p.`item_fdesc`,p
        .`item_price`,p.`item_status`,p.`Sales_tax` , IFNULL( ROUND(
        AVG( pr.rating ) ) , 0 ) AS rating_average,itmg.imageurl as
        item_image FROM ".$itemrating_table." pr RIGHT JOIN `".$item_table."` p ON pr.`item_id`
        = p.`item_id` join `".$itemimages_table."` itmg on
        itmg.image_id=p.`item_image` AND CONCAT(p.`item_name`, ' ', p.item_size) like '$str%' AND
        p.item_status = '0' GROUP BY p.`item_id`  ) UNION (SELECT
        p.`id`,p.`item_id`,CONCAT(p.`item_name`, ' ', p.item_size) as item_name,p.`item_sdesc`,p.`item_fdesc`,p
        .`item_price`,p.`item_status`,p.`Sales_tax` , IFNULL( ROUND(
        AVG( pr.rating ) ) , 0 ) AS rating_average,itmg.imageurl as
        item_image FROM ".$itemrating_table." pr RIGHT JOIN `".$item_table."` p ON pr.`item_id`
        = p.`item_id`  join `".$itemimages_table."` itmg on
        itmg.image_id=p.`item_image` AND CONCAT(p.`item_name`, ' ', p.item_size) like '%$str%' AND
        p.item_status = '0' GROUP BY p.`item_id`))
        AS a
        join ".$itemlink_table." il on a.item_id=il.item_id join subcategory sub on
        sub.sub_id=il.subcat_id join category c on c.id =sub.cat_id WHERE a.item_id != '".$itemid."'
        GROUP by a.item_id
        order by c.id asc");
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            
        }else {
			$return = NULL;
              }
		return $return;
    }
    public function updateorderncart($orderid, $user_id, $altenativeitemsid)
    {
        $response = false;
        //echo "UPDATE cart_item crt, order_table ordh SET ordh.is_order_edit=1, crt.status=2 WHERE crt.user_id=? and crt.order_id=? and crt.item_id in ($altenativeitemsid) AND ordh.order_id = ?";
        // cart_item -> ordered_item
        $this->db->UPDATE(" ordered_item crt, order_table ordh SET ordh.is_order_edit=3, crt.status=2, crt.item_found_status=1 WHERE crt.user_id='".$user_id."' and crt.order_id='".$orderid."' and crt.item_id ='".$altenativeitemsid."' AND ordh.order_id = '".$orderid."' ");
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $response = true;
            
        }else {
			$response = false;
              }
        return $response;
    }
    public function getgcmdata($userid)
    {
        $ios = 'ios';
        $android = 'android';
        $gcmDataArray = [];
        $this->db->select(" * FROM `tbl_usergcm` WHERE `user_id`='".$userid."' and `divicetype`='".$ios."' order by unitime,id DESC LIMIT 5");
        $query = $this->db->get();
        $iosData = $query->result_array();
        if (count($iosData) > 0) {
            foreach (  $iosData as $item) {
                array_push($gcmDataArray, $item);
            }
        }
      
        $this->db->select("* FROM `tbl_usergcm` WHERE `user_id`='".$userid."' and `divicetype`='".$ios."' order by unitime,id DESC LIMIT 5");
        $query = $this->db->get();
        $androidData = $query->result_array();
        if ($androidData->num_rows > 0) {
            foreach ( $androidData as $row) {
                array_push($gcmDataArray, $row);
            }
        }
        
        return $gcmDataArray;

    }
    
    public function insertorderstatus($orderid, $updateby, $status, $message)
    {
        $time = time();
        $this->db->select(" * from order_status_info WHERE order_id = '".$orderid."' and status='".$status."'");
        $query = $this->db->get();
        $num_rows = $query->result_array();
        
        if ($num_rows > 0) {
            return true;
        } else {
            $data = array('order_id'=>$orderid,'updateby_id'=>$updateby,'status'=>$status,'message'=>$message,'updatetime'=>$time);
            $this->db->insert('order_status_info', $data);
            $insert_id = $this->db->insert_id();
            return  $insert_id;
        }
    }

    
}
?>

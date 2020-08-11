<?php 
Class Model extends MY_Model{

   //---------All Order ccount----
    public function alltypes_order_count()
    {
        //------Subquery----------
        $this->db->select(array('(SELECT COUNT(*) from order_table where status=0) as newcount',
            '(SELECT COUNT(*) from order_table where status=4) as delivercount',
            '(SELECT COUNT(*) from order_table where status=5) as rejectcount',
            '(SELECT COUNT(*) from order_table where status=6) as canclecount'))
        ->from('order_table')
        ->where_in('status', array('0', '4', '5', '6'))
        ->group_by("status")
        ->limit('1');
        $query = $this->db->get();
        //echo $this->db->last_query();
        if ($query->num_rows() > 0) 
        {
            return $query->result_array();
        }
        else 
        {
            return array();
        }
    }

    public function salesanalyticDatail($where){
        $this->db->select("(case `status`
        WHEN 0 THEN 'new order'
        WHEN 1 THEN 'prepare '
        WHEN 2 THEN 'packed'
        WHEN 3 THEN 'out for delivry'
        WHEN 4 THEN 'deliverd'
        WHEN 5 THEN 'reject'
        WHEN 6 THEN 'cancle'
        ELSE 'new' END ) as hfh,
        `status`, COUNT(*) as order_count, sum(`finalprice`) as total")
        ->from('order_table')
        ->where($where)
        ->group_by("status");

        $query = $this->db->get();
        //echo $this->db->last_query();
        if ($query->num_rows() > 0) 
        {
            return $query->result_array();
        }
        else 
        {
            return array();
        }
    }

    public function adm_getbarsaleanalysis($info,$table,$where,$group_by){
        $this->db->select($info)
        ->from($table)
        ->where($where)
        ->group_by($group_by);

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

    public function newgetitembysubcat($info,$store_id,$subcat,$where,$groupby,$orderby,$ordertype,$limit, $pageno){
        $item_table = STORE_PREFIX.$store_id.'_'.ITEMS_TABLE;
        $itemimages_table = STORE_PREFIX.$store_id.'_'.ITEMIMAGES_TABLE;
        $itemlink_table =   STORE_PREFIX.$store_id.'_'.ITEMLINK_TABLE;
        $itemrating_table = STORE_PREFIX.$store_id.'_'.ITEMRATING_TABLE;

        $this->db->select($info);
        $this->db->from("`".$itemrating_table."` as pr");
        $this->db->join("`".$item_table."` as p",'pr.`item_id` = p.`item_id`','right');
        $this->db->join("`".$itemlink_table."` as inl",'inl.item_id = p.item_id');
        $this->db->join("`".$itemimages_table."` as itmg",'itmg.image_id=p.`item_image`');
        $this->db->join('subcategory as sub', 'sub.sub_id =inl.subcat_id');
        if ($subcat == 0) {
            $this->db->join('category ct','sub.cat_id=ct.id');
        }
        $this->db->where($where);
        $this->db->group_by($groupby);
        $this->db->order_by($orderby,$ordertype);
        $this->db->limit($limit,$pageno);
        $query = $this->db->get();
        //echo $this->db->last_query();
        if ($query->num_rows() > 0) 
        {
            return $query->result_array();
        }
        else
        {
            return array();
        }
    }

    //------------getBestseller--------------------
    public function getBestseller($userid,$store_id,$where,$groupby,$orderby,$limit,$start){
        $item_table = STORE_PREFIX.$store_id.'_'.ITEMS_TABLE;
        $itemimages_table = STORE_PREFIX.$store_id.'_'.ITEMIMAGES_TABLE;
        $itemrating_table = STORE_PREFIX.$store_id.'_'.ITEMRATING_TABLE;
        $this->db->select("p.`id`,p.`item_id`,CONCAT(p.`item_name`, ' ', p.`item_size`) as item_name,p.`item_sdesc`,p.`item_fdesc`,p.`item_price`,p.`item_status`,p.`Sales_tax` ,p.`discount`, IFNULL( ROUND( AVG( pr.rating ) ) , 0 ) AS rating_average,itmg.imageurl as item_image");
        $this->db->from("`".$itemrating_table."` as pr");
        $this->db->join("`".$item_table."` as p",'pr.`item_id` = p.`item_id`','right');
        $this->db->join("`".$itemimages_table."` as itmg",'itmg.image_id=p.`item_image`');
        $this->db->where($where);
        $this->db->group_by($groupby);
        $this->db->order_by($orderby);
        $this->db->limit($limit,$start);
        $query = $this->db->get();
        //echo $this->db->last_query();
        if ($query->num_rows() > 0) 
        {
            return $query->result_array();
        }
        else
        {
            return array();
        }
    }

    //----------get slot-----------
    public function getslot($storeid,$date,$storeday,$currentdate, $converttime,$starttime,$endtime){
        $table_name_prefix = STORE_PREFIX.$storeid;
        $tbl_time_slot = $table_name_prefix.'_'.TIME_SLOT;
        if ($currentdate == $date) {
            if ($converttime >= $starttime && $converttime < $endtime) {
                $sql = "SELECT all_slots.*,orders.countt, 
                (case  WHEN orders.countt<10 THEN '0' WHEN orders.countt IS NULL THEN '0' WHEN orders.countt>=10 THEN '1' END) AS 'avilable' FROM ".$tbl_time_slot." 
                AS all_slots LEFT JOIN ( select slot_id,COUNT(order_id) as countt from order_table where dlv_date=".$date." and status not in (4,5,6) group by slot_id ) AS orders ON orders.`slot_id` = all_slots.`time_slot_id` WHERE all_slots.`store_day`='".$storeday."' AND all_slots.`status`=0";
            }else{
                $sql = "SELECT all_slots.*,orders.countt, 
                (case  WHEN orders.countt<10 THEN '0' WHEN orders.countt IS NULL THEN '0' WHEN orders.countt>=10 THEN '1' END) AS 'avilable' FROM ".$tbl_time_slot." 
                AS all_slots LEFT JOIN ( select slot_id,COUNT(order_id) as countt from order_table where dlv_date=".$date." and status not in (4,5,6) group by slot_id ) AS orders ON orders.`slot_id` = all_slots.`time_slot_id` WHERE all_slots.`store_day`='".$storeday."' AND all_slots.`status`=0";
            }
        }else{
            $sql = "SELECT all_slots.*,orders.countt, 
                (case  WHEN orders.countt<10 THEN '0' WHEN orders.countt IS NULL THEN '0' WHEN orders.countt>=10 THEN '1' END) AS 'avilable' FROM ".$tbl_time_slot." 
                AS all_slots LEFT JOIN ( select slot_id,COUNT(order_id) as countt from order_table where dlv_date=".$date." and status not in (4,5,6) group by slot_id ) AS orders ON orders.`slot_id` = all_slots.`time_slot_id` WHERE all_slots.`store_day`='".$storeday."' AND all_slots.`status`=0";
        }
        $query = $this->db->query($sql);
        //echo $this->db->last_query();
        if ($query->num_rows() > 0) 
        {
            return $query->result_array();
        }
        else 
        {
            return array();
        }
    }

    public function isitemExists($itemid, $store_id)
    {
        $item_table = STORE_PREFIX.$store_id.'_'.ITEMS_TABLE;
        $bol = false;
        $this->db->select();
        $this->db->from($item_table);
        $this->db->where('item_id',$itemid);
       $query = $this->db->get();
        //echo $this->db->last_query();
        if ($query->num_rows() > 0) 
        {
            return $query->result_array();
        }
        else
        {
            return array();
        }
    }

    public function admin_getitemimages($itemid, $store_id)
    {
        $itemimages_table = STORE_PREFIX.$store_id.'_'.ITEMIMAGES_TABLE;
        $this->db->select('image_id,imageurl');
        $this->db->from($itemimages_table);
        $this->db->where('item_id',$itemid);
        $query = $this->db->get();
        //echo $this->db->last_query();
        if ($query->num_rows() > 0) 
        {
            return $query->result_array();
        }
        else
        {
            return array();
        }
    }

    public function getItem($item_id, $store_id)
    {
        $item_table = STORE_PREFIX.$store_id.'_'.ITEMS_TABLE;
        $itemimages_table = STORE_PREFIX.$store_id.'_'.ITEMIMAGES_TABLE;
        $itemrating_table = STORE_PREFIX.$store_id.'_'.ITEMRATING_TABLE;

        $sql = "SELECT p.`id`,p.`item_id`,CONCAT(p.`item_name`, ' ', p.`item_size`) as item_name,p.`item_sdesc`,p.`item_fdesc`,p.`item_price`,p.`item_status`,p.`Sales_tax`, IFNULL( ROUND( AVG( pr.rating ) ) , 0 ) AS rating_average ,itmg.imageurl as item_image
            FROM ".$itemrating_table." pr
            RIGHT JOIN `".$item_table."` p ON pr.`item_id` = p.`item_id`
            join `".$itemimages_table."` itmg on itmg.image_id=p.`item_image` AND p.`item_id` ='".$item_id."'and p.item_status='0'";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) 
        {
            return $query->result_array();
        }else {
            return NULL;
        }
    }

    //------------getVarityCount---------------
    public function getVarityCount($userid,$store_id){
        $itemlink_table =   STORE_PREFIX.$store_id.'_'.ITEMLINK_TABLE;
        $this->db->select('sc.cat_id');
        $this->db->from('cart_item as ci');
        $this->db->join("`".$itemlink_table."` as ili",'ci.item_id = ili.item_id`');
        $this->db->join('subcategory as sc', 'ili.subcat_id=sc.sub_id');
        $this->db->where(array('ci.user_id' => $userid,'ci.status'=>'0','ci.store_id' =>$store_id));
        $this->db->group_by('sc.cat_id');
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

    public function getcartitemformail($orderid, $store_id){
        $item_table = STORE_PREFIX.$store_id.'_'.ITEMS_TABLE;
        $itemimages_table = STORE_PREFIX.$store_id.'_'.ITEMIMAGES_TABLE;
        $this->db->select('crt.`item_id`,crt.`item_quty`,crt.`price`,crt.`tax`,CONCAT(itm.`item_name`," ",itm.`item_size`) as item_name,itmg.imageurl');
        $this->db->from('ordered_item as crt');
        $this->db->join("`".$item_table."` as itm",'crt.item_id=itm.item_id');
        $this->db->join("`".$itemimages_table."` as itmg",'itm.item_image=itmg.image_id');
        $this->db->where(array('crt.order_id' => $orderid,'crt.status'=>'1'));
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

    public function getorderbyid($orderid, $userid,$store_id)
    {
        $table_name_prefix = STORE_PREFIX.$store_id;
        $time_sloat = $table_name_prefix.'_'.TIME_SLOT;
        $this->db->select(" ord.*,tmsl.slot_name, usr.`first_name`,usr.`last_name`,usr.email_id, usr.mobile, usr.address, usr.pincode,stores.name As storename, CONCAT( shpadd.`first_name` ,  ' ', shpadd.`last_name` ) AS ship_name, shpadd.`address` AS shipping_address, shpadd.`ship_mobile_number` AS ship_mobile, shpadd.`pincode` AS ship_pincode")
        ->from('order_table as ord')
        ->join('users usr','ord.user_id = usr.id')
        ->join('stores', 'ord.store_id = stores.id')
        ->join('shipping_address as shpadd', 'ord.order_id = shpadd.order_id')
        ->join("`".$time_sloat."` as tmsl", 'time_slot_id=ord.slot_id')
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
    public function getcartitembyorderid($orderid, $store_id)
    {
        $result = false;
        $item_table = STORE_PREFIX.$store_id.'_'.ITEMS_TABLE;
        $itemimages_table = STORE_PREFIX.$store_id.'_'.ITEMIMAGES_TABLE;
        // #1 SubQueries no.1 -------------------------------------------

        $this->db->select('ci.item_id,ci.price as item_price,ci.tax as Sales_tax,ci.alernative_item_id,ci.item_quty, 
            (ci.item_quty * ci.price) AS total,ci.alernative_item_id as sequence,ci.status')
        ->from('ordered_item ci')
        ->where(array('ci.status!=' =>'0','ci.order_id' => $orderid,'ci.alernative_item_id !=' => 'NA'));
        $subQuery1 = $this->db->get_compiled_select();
         
        // #2 SubQueries no.2 -------------------------------------------

        $this->db->select('ci.`item_id`,ci.price as item_price,ci.tax as Sales_tax,ci.alernative_item_id,ci.`item_quty` , ( ci.`item_quty` * ci.price ) AS total,ci.`item_id` as sequence ,ci.status')
        ->from('ordered_item ci')
        ->where(array('ci.status!=' =>'0','ci.order_id' => $orderid,'ci.alernative_item_id=' => 'NA'));
        $subQuery2 = $this->db->get_compiled_select();
        
        // #3 Main query--------------------------

        $this->db->select('alt.*, CONCAT(itm.item_name," ", itm.item_size) as item_name,itm.item_sdesc,itm.item_fdesc,itm.discount,itmg.imageurl as item_image,itm.item_status')

        // #3 Union with queris ------------
        ->from("(".$subQuery1." UNION ".$subQuery2.") as alt")
        ->join("`".$item_table."` as itm",'alt.item_id =itm.item_id')
        ->join("`".$itemimages_table."` as itmg",'itm.item_image=itmg.image_id')
        ->order_by('sequence','ASC')
        ->order_by('alernative_item_id','DESC');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
           $result = $query->result_array();
        }else{
            // if the items are not present in ordered_item they might have been transferred to finished_item so check there then
            // #1 SubQueries no.1 -------------------------------------------

            $this->db->select('ci.item_id,ci.price as item_price,ci.tax as Sales_tax,ci.alernative_item_id,ci.item_quty, 
                (ci.item_quty * ci.price) AS total,ci.alernative_item_id as sequence,ci.status')
            ->from('finished_item ci')
            ->where(array('ci.status!=' =>'0','ci.order_id' => $orderid,'ci.alernative_item_id !=' => 'NA'));
            $subQuery1 = $this->db->get_compiled_select();
             
            // #2 SubQueries no.2 -------------------------------------------

            $this->db->select('ci.`item_id`,ci.price as item_price,ci.tax as Sales_tax,ci.alernative_item_id,ci.`item_quty` , ( ci.`item_quty` * ci.price ) AS total,ci.`item_id` as sequence ,ci.status')
            ->from('finished_item ci')
            ->where(array('ci.status!=' =>'0','ci.order_id' => $orderid,'ci.alernative_item_id=' => 'NA'));
            $subQuery2 = $this->db->get_compiled_select();
            
            // #3 Main query--------------------------

            $this->db->select('alt.*, CONCAT(itm.item_name," ", itm.item_size) as item_name,itm.item_sdesc,itm.item_fdesc,itm.discount,itmg.imageurl as item_image,itm.item_status')

            // #3 Union with queris ------------
            ->from("(".$subQuery1." UNION ".$subQuery2.") as alt")
            ->join("`".$item_table."` as itm",'alt.item_id =itm.item_id')
            ->join("`".$itemimages_table."` as itmg",'itm.item_image=itmg.image_id')
            ->order_by('sequence','ASC')
            ->order_by('alernative_item_id','DESC');
            $query = $this->db->get();
            if ($query->num_rows() > 0) {
               $result = $query->result_array();
            }
        }
        return $result;
    }
    
    //-----------------------getitemsuggetionbycat-------------------
    public function getitemsuggetionbycat($str, $store_id , $deviceType = NULL)
    {
        $idsArray = unserialize(IOS_RESTRICT_CATEGORY);
        $ids = implode(', ', $idsArray);
        $item_table = STORE_PREFIX.$store_id.'_'.ITEMS_TABLE;
        $itemlink_table =   STORE_PREFIX.$store_id.'_'.ITEMLINK_TABLE;
        if ($deviceType == "ios") {
            
          $sql = "SELECT il.subcat_id,a.item_id,a.item_name,c.id from (SELECT item_id,CONCAT(`item_name`, ' ', `item_size`) as item_name FROM `".$item_table."` WHERE `item_id` in (SELECT `item_id` FROM `".$itemlink_table."` WHERE `subcat_id` in (SELECT `sub_id` FROM `subcategory` WHERE `sub_name` ='".$str."')) and `item_status`='0') AS a join ".$itemlink_table." il on a.item_id=il.item_id join subcategory sub on sub.sub_id=il.subcat_id join category c on c.id =sub.cat_id AND c.id NOT IN($ids) AND il.status=0 GROUP by a.item_id order by c.id asc LIMIT 0,10";
        } else {
            
            $sql= "SELECT il.subcat_id,a.item_id,a.item_name,c.id from (SELECT item_id,CONCAT(`item_name`, ' ', `item_size`) as item_name FROM `".$item_table."` WHERE `item_id` in (SELECT `item_id` FROM `".$itemlink_table."` WHERE `subcat_id` in (SELECT `sub_id` FROM `subcategory` WHERE `sub_name` ='".$str."')) and `item_status`='0') AS a join ".$itemlink_table." il on a.item_id=il.item_id join subcategory sub on sub.sub_id=il.subcat_id join category c on c.id =sub.cat_id AND il.status=0 GROUP by a.item_id order by c.id asc LIMIT 0,10";
        }
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) 
        {
            return $query->result_array();
        }
        else 
        {
            return NULL;
        }
    }
    //-----------------------getitemsuggetionbystr-------------------
    public function getitemsuggetionbystr($str, $store_id, $deviceType = NULL){
        $idsArray = unserialize(IOS_RESTRICT_CATEGORY);
        $ids = implode(', ', $idsArray);
        $item_table = STORE_PREFIX.$store_id.'_'.ITEMS_TABLE;
        $itemlink_table =   STORE_PREFIX.$store_id.'_'.ITEMLINK_TABLE;
        if ($deviceType == "ios") {
            $sql= "SELECTil.subcat_id,a.item_id,a.item_name,c.id from ((SELECT item_id,CONCAT(`item_name`, ' ', `item_size`) as item_name FROM `".$item_table."` WHERE `item_name` like '$str%' and `item_status`='0') UNION (SELECT item_id,CONCAT(`item_name`, ' ', `item_size`) as item_name FROM `".$item_table."` WHERE CONCAT(`item_name`, ' ', `item_size`) like '%$str%' and `item_status`='0') ) AS a join ".$itemlink_table." il on a.item_id=il.item_id join subcategory sub on sub.sub_id=il.subcat_id join category c on c.id =sub.cat_id AND c.id NOT IN($ids) AND il.status=0 GROUP by a.item_id order by c.id asc LIMIT 0,10";
        } else {
            $sql= "SELECT il.subcat_id,a.item_id,a.item_name,c.id from ((SELECT item_id,CONCAT(`item_name`, ' ', `item_size`) as item_name FROM `".$item_table."` WHERE CONCAT(`item_name`, ' ', `item_size`) like '$str%' and `item_status`='0') UNION (SELECT item_id,CONCAT(`item_name`, ' ', `item_size`) as item_name FROM `".$item_table."` WHERE `item_name` like '%$str%' and `item_status`='0') ) AS a join ".$itemlink_table." il on a.item_id=il.item_id join subcategory sub on sub.sub_id=il.subcat_id join category c on c.id =sub.cat_id AND il.status=0 GROUP by a.item_id order by c.id asc LIMIT 0,10";
        }
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) 
        {
            return $query->result_array();
        }
        else
        {
            return NULL;
        }
    }

    public function getsuggetionitembycart($str, $pagenum, $store_id, $deviceType = NULL)
    {
        $item_table = STORE_PREFIX.$store_id.'_'.ITEMS_TABLE;
        $itemimages_table = STORE_PREFIX.$store_id.'_'.ITEMIMAGES_TABLE;
        $itemlink_table =   STORE_PREFIX.$store_id.'_'.ITEMLINK_TABLE;
        $itemrating_table = STORE_PREFIX.$store_id.'_'.ITEMRATING_TABLE;
        $responcepageno = $pagenum * 20;
        $idsArray = unserialize(IOS_RESTRICT_CATEGORY);
        $ids = implode(', ', $idsArray);
        if ($deviceType == "ios") {
            $sql = "SELECT a.item_id,a.item_name,a.`item_sdesc`,a.`item_fdesc`,a .`item_price`,a.`item_status`,a.`Sales_tax`,a.rating_average,a.item_image,il.subcat_id,c.id from ((SELECT p.`id`,p.`item_id`,CONCAT(p.`item_name`, ' ', p.`item_size`) as item_name,p.`item_sdesc`,p.`item_fdesc`,p .`item_price`,p.`item_status`,p.`Sales_tax` , IFNULL( ROUND( AVG( pr.rating ) ) , 0 ) AS rating_average,itmg.imageurl as item_image FROM ".$itemrating_table." pr RIGHT JOIN `".$item_table."` p ON pr.`item_id` = p.`item_id` join `".$itemimages_table."` itmg on itmg.image_id=p.`item_image` AND p.`item_id` in (SELECT `item_id` FROM `".$itemlink_table."` WHERE `subcat_id` in (SELECT `sub_id` FROM `subcategory` WHERE `sub_name` = '".$str."')) AND p.item_status = '0' GROUP BY p.`item_id` )) AS a join ".$itemlink_table." il on a.item_id=il.item_id join subcategory sub on sub.sub_id=il.subcat_id join category c on c.id =sub.cat_id AND c.id NOT IN($ids) AND il.status=0 GROUP by a.item_id order by c.id asc limit $responcepageno,20";
        } else {
           $sql = "SELECT a.item_id,a.item_name,a.`item_sdesc`,a.`item_fdesc`,a .`item_price`,a.`item_status`,a.`Sales_tax`,a.rating_average,a.item_image,il.subcat_id,c.id from ((SELECT p.`id`,p.`item_id`,CONCAT(p.`item_name`, ' ', p.`item_size`) as item_name,p.`item_sdesc`,p.`item_fdesc`,p .`item_price`,p.`item_status`,p.`Sales_tax` , IFNULL( ROUND( AVG( pr.rating ) ) , 0 ) AS rating_average,itmg.imageurl as item_image FROM ".$itemrating_table." pr RIGHT JOIN `".$item_table."` p ON pr.`item_id` = p.`item_id` join `".$itemimages_table."` itmg on itmg.image_id=p.`item_image` AND p.`item_id` in (SELECT `item_id` FROM `".$itemlink_table."` WHERE `subcat_id` in (SELECT `sub_id` FROM `subcategory` WHERE `sub_name` = '".$str."')) AND p.item_status = '0' GROUP BY p.`item_id` )) AS a join ".$itemlink_table." il on a.item_id=il.item_id join subcategory sub on sub.sub_id=il.subcat_id join category c on c.id =sub.cat_id AND il.status=0 GROUP by a.item_id order by c.id asc limit $responcepageno,20";
        }
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) 
        {
            return $query->result_array();
        }
        else
        {
           return NULL;
        }
    }

    //--------------------------------getitemsuggetion
    public function getitemsuggetion($str, $firststr, $seconstr, $store_id, $deviceType = NULL)
    {
        $idsArray = unserialize(IOS_RESTRICT_CATEGORY);
        $ids = implode(', ', $idsArray);
        $item_table = STORE_PREFIX.$store_id.'_'.ITEMS_TABLE;
        $itemlink_table =   STORE_PREFIX.$store_id.'_'.ITEMLINK_TABLE;

        if ($deviceType == "ios") {
            $sql = "SELECT il.subcat_id,a.item_id,a.item_name,c.id from ((SELECT item_id,CONCAT(`item_name`, ' ', `item_size`) as item_name FROM `".$item_table."` WHERE (CONCAT(`item_name`, ' ', `item_size`) like '$str%' or CONCAT(`item_name`, ' ', `item_size`) like '$firststr%' or CONCAT(`item_name`, ' ', `item_size`) like '$seconstr%') and `item_status`='0') UNION (SELECT item_id,CONCAT(`item_name`, ' ', `item_size`) as item_name FROM `".$item_table."` WHERE (CONCAT(`item_name`, ' ', `item_size`) like '%$str%' or CONCAT(`item_name`, ' ', `item_size`) like '%$firststr%' or CONCAT(`item_name`, ' ', `item_size`) like '%$seconstr%' ) and `item_status`='0'))
                AS a join ".$itemlink_table." il on a.item_id=il.item_id join subcategory sub on sub.sub_id=il.subcat_id join category c on c.id=sub.cat_id AND c.id NOT IN($ids) AND il.status=0 GROUP by a.item_id order by c.id asc LIMIT 0,10";
        }else{
            $sql = "SELECT il.subcat_id,a.item_id,a.item_name,c.id from ((SELECT
                item_id,CONCAT(`item_name`, ' ', `item_size`) as item_name FROM `".$item_table."` WHERE (CONCAT(`item_name`, ' ', `item_size`) like '$str%' or CONCAT(`item_name`, ' ', `item_size`) like '$firststr%' or CONCAT(`item_name`, ' ', `item_size`) like '$seconstr%')
                and `item_status`='0') UNION (SELECT item_id,CONCAT(`item_name`, ' ', `item_size`) as item_name FROM
                `".$item_table."` WHERE (CONCAT(`item_name`, ' ', `item_size`) like '%$str%' or CONCAT(`item_name`, ' ', `item_size`) like '%$firststr%' or CONCAT(`item_name`, ' ', `item_size`) like '%$seconstr%' ) and `item_status`='0') )
                AS a join ".$itemlink_table." il on a.item_id=il.item_id join subcategory
                sub on sub.sub_id=il.subcat_id join category c on c.id
                =sub.cat_id AND il.status=0 GROUP by a.item_id order by c.id asc LIMIT 0,10";
        }
        $query = $this->db->query($sql);
        //$str= $this->db->last_query();
        if ($query->num_rows() > 0) 
        {
            return $query->result_array();
        }
        else
        {
           return NULL;
        }
    }
    
    //--------------------------------getsuggetionitembycartcount
    public function getsuggetionitembycartcount($str, $store_id, $deviceType = NULL)
    {
        $item_table = STORE_PREFIX.$store_id.'_'.ITEMS_TABLE;
        $itemimages_table = STORE_PREFIX.$store_id.'_'.ITEMIMAGES_TABLE;
        $itemlink_table =   STORE_PREFIX.$store_id.'_'.ITEMLINK_TABLE;
        $itemrating_table = STORE_PREFIX.$store_id.'_'.ITEMRATING_TABLE;
        $idsArray = unserialize(IOS_RESTRICT_CATEGORY);
        $ids = implode(', ', $idsArray);
        if ($deviceType == "ios") {
            $sql = "SELECT a.item_id,a.item_name,a.`item_sdesc`,a.`item_fdesc`,a .`item_price`,a.`item_status`,a.`Sales_tax`,a.rating_average,a.item_image,il.subcat_id,c.id from ((SELECT p.`id`,p.`item_id`,CONCAT(p.`item_name`, ' ', p.`item_size`) as item_name,p.`item_sdesc`,p.`item_fdesc`,p .`item_price`,p.`item_status`,p.`Sales_tax` , IFNULL( ROUND( AVG( pr.rating ) ) , 0 ) AS rating_average,itmg.imageurl as item_image FROM ".$itemrating_table." pr RIGHT JOIN `".$item_table."` p ON pr.`item_id` = p.`item_id` join `".$itemimages_table."` itmg on itmg.image_id=p.`item_image` AND p.`item_id` in (SELECT `item_id` FROM `".$itemlink_table."` WHERE `subcat_id` in (SELECT `sub_id` FROM `subcategory` WHERE `sub_name` = '".$str."')) AND p.item_status = '0' GROUP BY p.`item_id` )) AS a join ".$itemlink_table." il on a.item_id=il.item_id join subcategory sub on sub.sub_id=il.subcat_id join category c on c.id =sub.cat_id AND c.id NOT IN($ids) AND il.status=0 GROUP by a.item_id order by c.id asc";
        } else {
            $sql = "SELECT a.item_id,a.item_name,a.`item_sdesc`,a.`item_fdesc`,a .`item_price`,a.`item_status`,a.`Sales_tax`,a.rating_average,a.item_image,il.subcat_id,c.id from ((SELECT p.`id`,p.`item_id`,CONCAT(p.`item_name`, ' ', p.`item_size`) as item_name,p.`item_sdesc`,p.`item_fdesc`,p .`item_price`,p.`item_status`,p.`Sales_tax` , IFNULL( ROUND( AVG( pr.rating ) ) , 0 ) AS rating_average,itmg.imageurl as item_image FROM ".$itemrating_table." pr RIGHT JOIN `".$item_table."` p ON pr.`item_id` = p.`item_id` join `".$itemimages_table."` itmg on itmg.image_id=p.`item_image` AND p.`item_id` in (SELECT `item_id` FROM `".$itemlink_table."` WHERE `subcat_id` in (SELECT `sub_id` FROM `subcategory` WHERE `sub_name` = '".$str."')) AND p.item_status = '0' GROUP BY p.`item_id` )) AS a join ".$itemlink_table." il on a.item_id=il.item_id join subcategory sub on sub.sub_id=il.subcat_id join category c on c.id =sub.cat_id AND il.status=0 GROUP by a.item_id order by c.id asc";
        }

        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) 
        {
            return $query->result_array();
        }
        else
        {
           return NULL;
        }
    }

    public function getsuggetionitemsbystr($str, $pagenum, $store_id, $deviceType = NULL)
    {
        $item_table = STORE_PREFIX.$store_id.'_'.ITEMS_TABLE;
        $itemimages_table = STORE_PREFIX.$store_id.'_'.ITEMIMAGES_TABLE;
        $itemlink_table =   STORE_PREFIX.$store_id.'_'.ITEMLINK_TABLE;
        $itemrating_table = STORE_PREFIX.$store_id.'_'.ITEMRATING_TABLE;
        $responcepageno = $pagenum * 20;
        $idsArray = unserialize(IOS_RESTRICT_CATEGORY);
        $ids = implode(', ', $idsArray);

        $searchTerms = explode(' ', $str);
        $searchTermBits = array();
        foreach ($searchTerms as $term) {
            $term = trim($term);
            if (!empty($term)) {
                $searchTermBits[] = "CONCAT(p.`item_name`, ' ', p.`item_size`) LIKE '%$term%'";
            }
        }
        $searchstring = implode(' AND ', $searchTermBits);

        if ($deviceType == "ios") {
            $sql = "SELECT a.item_id,a.item_name,a.`item_sdesc`,a.`item_fdesc`,a .`item_price`,a.`item_status`,a.`Sales_tax`,a.rating_average,a.item_image,il.subcat_id,c.id from ((SELECT
                p.`id`,p.`item_id`,CONCAT(p.`item_name`, ' ', p.`item_size`) as item_name,p.`item_sdesc`,p.`item_fdesc`,p
                .`item_price`,p.`item_status`,p.`Sales_tax` , IFNULL( ROUND(
                AVG( pr.rating ) ) , 0 ) AS rating_average,itmg.imageurl as
                item_image FROM ".$itemrating_table." pr RIGHT JOIN `".$item_table."` p ON pr.`item_id`
                = p.`item_id` join `".$itemimages_table."` itmg on
                itmg.image_id=p.`item_image` AND ".$searchstring." AND
                p.item_status = '0' GROUP BY p.`item_id`  )
                UNION (
                SELECT
                p.`id`,p.`item_id`,CONCAT(p.`item_name`, ' ', p.`item_size`) as item_name,p.`item_sdesc`,p.`item_fdesc`,p
                .`item_price`,p.`item_status`,p.`Sales_tax` , IFNULL( ROUND(
                AVG( pr.rating ) ) , 0 ) AS rating_average,itmg.imageurl as
                item_image FROM ".$itemrating_table." pr RIGHT JOIN `".$item_table."` p ON pr.`item_id`
                = p.`item_id`  join `".$itemimages_table."` itmg on
                itmg.image_id=p.`item_image` AND ".$searchstring." AND
                p.item_status = '0' GROUP BY p.`item_id`))
                AS a
                join ".$itemlink_table." il on a.item_id=il.item_id join subcategory sub on
                sub.sub_id=il.subcat_id join category c on c.id =sub.cat_id AND c.id NOT IN($ids) AND il.status=0
                GROUP by a.item_id
                order by c.id asc
                limit $responcepageno,20";
        }else{
                $sql = "SELECT a.item_id,a.item_name,a.`item_sdesc`,a.`item_fdesc`,a .`item_price`,a.`item_status`,a.`Sales_tax`,a.rating_average,a.item_image,il.subcat_id,c.id from ((SELECT
                p.`id`,p.`item_id`,CONCAT(p.`item_name`, ' ', p.`item_size`) as item_name,p.`item_sdesc`,p.`item_fdesc`,p
                .`item_price`,p.`item_status`,p.`Sales_tax` , IFNULL( ROUND(
                AVG( pr.rating ) ) , 0 ) AS rating_average,itmg.imageurl as
                item_image FROM ".$itemrating_table." pr RIGHT JOIN `".$item_table."` p ON pr.`item_id`
                = p.`item_id` join `".$itemimages_table."` itmg on
                itmg.image_id=p.`item_image` AND ".$searchstring." AND
                p.item_status = '0' GROUP BY p.`item_id`  ) UNION (SELECT
                p.`id`,p.`item_id`,CONCAT(p.`item_name`, ' ', p.`item_size`) as item_name,p.`item_sdesc`,p.`item_fdesc`,p
                .`item_price`,p.`item_status`,p.`Sales_tax` , IFNULL( ROUND(
                AVG( pr.rating ) ) , 0 ) AS rating_average,itmg.imageurl as
                item_image FROM ".$itemrating_table." pr RIGHT JOIN `".$item_table."` p ON pr.`item_id`
                = p.`item_id`  join `".$itemimages_table."` itmg on
                itmg.image_id=p.`item_image` AND ".$searchstring." AND
                p.item_status = '0' GROUP BY p.`item_id`))
                AS a
                join ".$itemlink_table." il on a.item_id=il.item_id join subcategory sub on
                sub.sub_id=il.subcat_id join category c on c.id =sub.cat_id AND il.status=0
                GROUP by a.item_id
                order by c.id asc
                limit $responcepageno,20";
            }

            $query = $this->db->query($sql);
            if ($query->num_rows() > 0) 
            {
                return $query->result_array();
            }else {
                return NULL;
            }
    }

    public function getsuggetionitemscountbystr($str, $store_id, $deviceType = NULL)
    {
        $item_table = STORE_PREFIX.$store_id.'_'.ITEMS_TABLE;
        $itemimages_table = STORE_PREFIX.$store_id.'_'.ITEMIMAGES_TABLE;
        $itemlink_table =   STORE_PREFIX.$store_id.'_'.ITEMLINK_TABLE;
        $itemrating_table = STORE_PREFIX.$store_id.'_'.ITEMRATING_TABLE;
        $idsArray = unserialize(IOS_RESTRICT_CATEGORY);
        $ids = implode(', ', $idsArray);

        $searchTerms = explode(' ', $str);
        $searchTermBits = array();
        foreach ($searchTerms as $term) {
            $term = trim($term);
            if (!empty($term)) {
                $searchTermBits[] = "CONCAT(p.`item_name`, ' ', p.`item_size`) LIKE '%$term%'";
            }
        }
        $searchstring = implode(' AND ', $searchTermBits);

        if ($deviceType == "ios") {
            $sql = "SELECT a.item_id,a.item_name,a.`item_sdesc`,a.`item_fdesc`,a .`item_price`,a.`item_status`,a.`Sales_tax`,a.rating_average,a.item_image,il.subcat_id,c.id
        from (
        (SELECT p.`id`,p.`item_id`,CONCAT(p.`item_name`, ' ', p.`item_size`) as item_name,p.`item_sdesc`,p.`item_fdesc`,p.`item_price`,p.`item_status`,p.`Sales_tax` , IFNULL( ROUND(
        AVG( pr.rating ) ) , 0 ) AS rating_average,itmg.imageurl as item_image
        FROM ".$itemrating_table." pr
        RIGHT JOIN `".$item_table."` p ON pr.`item_id`= p.`item_id`
        join `".$itemimages_table."` itmg on itmg.image_id=p.`item_image` AND ".$searchstring." AND p.item_status = '0'
        GROUP BY p.`item_id`)
        UNION
        (SELECT p.`id`,p.`item_id`,CONCAT(p.`item_name`, ' ', p.`item_size`) as item_name,p.`item_sdesc`,p.`item_fdesc`,p.`item_price`,p.`item_status`,p.`Sales_tax` , IFNULL( ROUND(
        AVG( pr.rating ) ) , 0 ) AS rating_average,itmg.imageurl as item_image
        FROM ".$itemrating_table." pr
        RIGHT JOIN `".$item_table."` p ON pr.`item_id`= p.`item_id`
        join `".$itemimages_table."` itmg on itmg.image_id=p.`item_image` AND ".$searchstring." AND p.item_status = '0' GROUP BY p.`item_id`)
        ) AS a
        join ".$itemlink_table." il on a.item_id=il.item_id
        join subcategory sub on sub.sub_id=il.subcat_id
        join category c on c.id =sub.cat_id AND c.id NOT IN($ids) AND il.status=0
        GROUP by a.item_id
        order by c.id asc";
        } else {
            $sql = "SELECT a.item_id,a.item_name,a.`item_sdesc`,a.`item_fdesc`,a .`item_price`,a.`item_status`,a.`Sales_tax`,a.rating_average,a.item_image,il.subcat_id,c.id
        from (
        (SELECT p.`id`,p.`item_id`,CONCAT(p.`item_name`, ' ', p.`item_size`) as item_name,p.`item_sdesc`,p.`item_fdesc`,p.`item_price`,p.`item_status`,p.`Sales_tax` , IFNULL( ROUND(
        AVG( pr.rating ) ) , 0 ) AS rating_average,itmg.imageurl as item_image
        FROM ".$itemrating_table." pr
        RIGHT JOIN `".$item_table."` p ON pr.`item_id`= p.`item_id`
        join `".$itemimages_table."` itmg on itmg.image_id=p.`item_image` AND ".$searchstring." AND p.item_status = '0'
        GROUP BY p.`item_id`
        ) UNION (SELECT p.`id`,p.`item_id`,CONCAT(p.`item_name`, ' ', p.`item_size`) as item_name,p.`item_sdesc`,p.`item_fdesc`,p.`item_price`,p.`item_status`,p.`Sales_tax` , IFNULL( ROUND(AVG( pr.rating ) ) , 0 ) AS rating_average,itmg.imageurl as item_image
        FROM ".$itemrating_table." pr
        RIGHT JOIN `".$item_table."` p ON pr.`item_id`= p.`item_id`
        join `".$itemimages_table."` itmg on itmg.image_id=p.`item_image` AND ".$searchstring." AND p.item_status = '0'
        GROUP BY p.`item_id`)
        ) AS a
        join ".$itemlink_table." il on a.item_id=il.item_id
        join subcategory sub on sub.sub_id=il.subcat_id
        join category c on c.id =sub.cat_id AND il.status=0
        GROUP by a.item_id
        order by c.id asc";
        }

        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) 
        {
            return $query->result_array();
        }else {
            return NULL;
        }
    }

    //---------------------getalernativeitems--------------------
    public function getalernativeitems($userid, $orderid, $store_id){
        $item_table = STORE_PREFIX.$store_id.'_'.ITEMS_TABLE;
        $itemimages_table = STORE_PREFIX.$store_id.'_'.ITEMIMAGES_TABLE;

        $sql = "SELECT alt.*,CONCAT(itm.item_name, ' ', itm.item_size) as item_name,itm.item_sdesc,itm.item_fdesc,itm.discount,itmg.imageurl FROM (select ci.`item_id`,ci.price as item_price,ci.tax as Sales_tax,ci.alernative_item_id,ci.item_quty,ci.alernative_item_id as sequence ,ci.status FROM ordered_item ci
        where ci.`status` in (1,2,3) and ci.user_id= ? and ci.order_id= ? and ci.alernative_item_id !='NA'
        UNION SELECT ci.`item_id`,ci.price as item_price,ci.tax as Sales_tax,ci.alernative_item_id,ci.item_quty,ci.`item_id` as sequence ,ci.status FROM ordered_item ci where ci.`status` in (1,2,3) and ci.user_id= ? and ci.order_id= ? and ci.alernative_item_id ='NA') as alt,`".$item_table."` itm,`".$itemimages_table."` itmg
        where alt.item_id =itm.item_id and itm.item_image=itmg.image_id
        ORDER BY sequence ASC,alernative_item_id DESC";

        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) 
        {
            return $query->result_array();
        }else {
            return NULL;
        }
    }

    //------------------get_renew_membership_userslist-------

    public function get_renew_membership_userslist($status){
        $membership_plan_id = 0; $membership_plan_del_status = 0;
        $this->db->select('mp.id,mp.plan_name,mp.price, mp.duration,u.id as userid,u.api_key,u.first_name,u.last_name,u.email_id,u.mobile,u.pincode,u.membership_plan_id,u.membership_date,u.renew_membership_status')
        ->from('membership_plan as mp')
        ->join('users as u','u.membership_plan_id = mp.id')
        ->where(array('u.membership_plan_id!=' => $membership_plan_id,'u.renew_membership_status' => $status,'mp.del_status' =>$membership_plan_del_status));
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

    //==================getcartitembyid================
    public function getcartitembyid($userid, $item_id, $store_id){
        $item_table = STORE_PREFIX.$store_id.'_'.ITEMS_TABLE;
        $itemimages_table = STORE_PREFIX.$store_id.'_'.ITEMIMAGES_TABLE;
        $this->db->select("p.id,p.item_id,CONCAT(p.item_name, ' ', p.item_size) as item_name,p.`item_sdesc`,p.`item_fdesc`,p.`item_price`,p.`item_status`,p.`Sales_tax`,ci.`item_quty`,(ci.`item_quty`*p.item_price) as total,itmg.imageurl as item_image")
        ->from('cart_item as ci')
        ->join("`".$item_table."` as p",'ci.`item_id` =p.item_id')
        ->join("`".$itemimages_table."` as itmg",'p.item_image=itmg.image_id')
        ->where(array('ci.status' => 0,'ci.user_id' =>$userid ,'ci.item_id' => $item_id,'ci.store_id' => $store_id))
        ->order_by(tn.`date`,"DESC");
        $query = $this->db->get();
        if ($query->num_rows() > 0) 
        {
            return $query->result_array();
        }else {
            return NULL;
        }
    }

    //=============check_referrals_earned==================
    public function check_referrals_earned($user_id){
        $this->db->select("referred_by,redeemed_referral_count")
        ->from('users')
        ->where('id',$userid);
        $query = $this->db->get();
        $res = $query->result_array();
        $offers_redeemed = 0;
        $result = $res[0];
        $referred_by = json_decode($result['referred_by']);
        $offers_redeemed = $offers_redeemed + $result['redeemed_referral_count'];
        $referred_by_redeemed = $referred_by->is_redeemed;
        if($referred_by_redeemed){
            $offers_redeemed++;
        }
        return $offers_redeemed;
    }
    public function checkforgetcode($userid, $code)
    {

        $this->db->select(" fco.code,urs.first_name,urs.last_name from forgetpasswordcode fco join users urs on fco.user_id=urs.id and fco.user_id = '".$userid."' and fco.code='".$code."' and fco.status='0'");
        $query = $this->db->get();
        if ($query) {
            $resultSet = $query->result_array();
            $result = $resultSet;
            return $result;
        } else {

            return NULL;
        }
    }
}
?>
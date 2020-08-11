<?php
Class My_model extends CI_Model
{
    /*********Insert data in to table*********/
    function add($table,$data) 
    {
        $this->db->insert($table, $data);
        //echo $this->db->last_query(); die();
        $insert_id = $this->db->insert_id();
        return  $insert_id;
    }
    //==========batch rec insert
    function batch_rec($table,$data)
    {
        $this->db->insert_batch($table, $data);
        $insert_id = $this->db->insert_id();
        return  $insert_id;
    }

    function create_user($table,$data)
    {
        return $this->db->insert($table, $data);
    }

    /*********Update data in to table*********/
    function update($table,$data,$where)
    {
        $this->db->where($where);
        return $this->db->update($table,$data);
    }
    

    //Update record with existing record in codeigniter
    public function exiting_update($id,$table,$field,$value){
      $this->db->where('id',$id);
      $this->db->where("NOT FIND_IN_SET($value,$field)!=",0);
      $this->db->set($field, 'CONCAT('.$field.',\',\',\''.$value.'\')', FALSE);
      $this->db->update($table);
      echo $this->db->last_query(); die();
    }


    public function exiting_replace($id,$table,$field,$value)
    {
        $this->db->where('id',$id);
        $this->db->where("NOT FIND_IN_SET($value,$field)!=",0);
        $this->db->set($field, 'REPLACE('.$field.',\',\',\''.$value.'\',"")');
        $this->db->update($table);
        echo $this->db->last_query(); die();
        //$this->db->query("UPDATE products_table SET images=replace(images, 'img3.jpg,',''),images=replace(images, ',img3.jpg','') WHERE product_name='Lamps'");
    }
    /*********get all Data from table*********/
    function get_all_record($table,$order='',$type="",$limit='',$start="",$where="")
    {
        if($limit!='' && $start!='' )
        {
            $this->db->limit($limit,$start);
        }
        else if($limit!='' && $start=='')
        {
            $this->db->limit($limit);
        }
        if($where!='')
        {
          $this->db->where($where);
        }
        if($order!='' && $type!='')
        {
          $this->db->order_by($order,$type);
        }
        $query = $this->db->get($table);
        //echo $this->db->last_query(); die();
        if ($query->num_rows() > 0) 
        {
            return $query->result_array();
        } 
        else 
        {
            return array();
        }
    }
    
    /*********get single record with condition**********/
    function get_record($table,$where)
    {
        $query = $this->db->get_where($table,$where);
        if ($query->num_rows() > 0) 
        {
            return $query->result_array();
        }
        else 
        {
            return array();
        }
    }

    /*************get row***************/
    public function get_row($table_name='', $where=''){
        if($where!=''){    
            $this->db->where($where);
        }
        $query=$this->db->get($table_name);
        if($query->num_rows()>0)
            return $query->row_array();
        else
            return FALSE;
    }
    
    public function get_row_record($table,$where)
    {
        $query = $this->db->get_where($table,$where);
        if ($query->num_rows() > 0) 
        {
            return $query->result();
        } 
        else 
        {
            return array();
        }
    }

    public function get_stores_by_zipcode($zipcode){
        $this->db->where("FIND_IN_SET($zipcode,`zipcode`)!=",0);
        $this->db->where("status",'active');
        $query=$this->db->get("stores");
        if($query->num_rows()>0){
            return $query->result_array();
        }
        else{    
             return FALSE;
        }
    }
    
    /*********Delete Data form table*********/
    function delete($table,$where)
    {
        if( $this->db->delete($table,$where) )
        {
            return "deleted";
        }
        else
        {
            return false;
        }
    }

    /***********get selected data************/
    function get_selected_data($info,$table,$where='',$order='',$type='',$limit='',$start='')
    {
        $this->db->select($info);
        $this->db->from($table);
        if($where!='')
        {
            $this->db->where($where);
        }
        if($order!='' && $type!='')
        {
          $this->db->order_by($order,$type);
        }
        if($limit!='' && $start!='')
        {
          $this->db->limit($limit,$start);
        }
        if($limit!='')
        {
          $this->db->limit($limit);
        }
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

    /***********count data************/
    public function record_count($table,$where='',$group='')
    {
        /*if($group!='')
        {
            $this->db->distinct();
            $this->db->group_by($group);
        }*/
        if($where!=''){
            $this->db->where($where);
        }
        $this->db->from($table);
        //echo $this->db->last_query();
        return $this->db->count_all_results();
    }
    
    //-------------get two tables Data----------
    public function gettwodata($data,$table,$table1,$on,$where="",$order="",$type="",$limit="")
    {
        $this->db->select($data);
        $this->db->from($table);
        $this->db->join($table1,$on,"left");
        if($where!="")
        {
            $this->db->where($where);
        }
        if($order!="" && $type!="")
        {
            $this->db->order_by($order,$type);
        }
        if($limit!="")
        {
            $this->db->limit($limit);
        }
        $query = $this->db->get();
        //echo $this->db->last_query(); die();
        if($query->num_rows() > 0){
            return $query->result_array();
        }else{
            return array();
        }
    }

    //-------------get three tables Data----------
    public function getthreedata($data,$table,$table1,$table2,$on,$on1,$where="",$order="",$type="",$limit="",$start="")
    {
        $this->db->select($data);
        $this->db->from($table);
        $this->db->join($table1,$on);
        $this->db->join($table2,$on1);
        if($where!="")
        {
            $this->db->where($where);
        }
        if($order!="" && $type!="")
        {
            $this->db->order_by($order,$type);
        }
        if($limit!='' && $start!='')
        {
          $this->db->limit($limit,$start);
        }
        if($limit!='')
        {
          $this->db->limit($limit);
        }
        $query = $this->db->get();
        //echo $this->db->last_query(); die();
        if($query->num_rows() > 0){
            return $query->result_array();
        }else{
            return array();
        }
    }

    //------------------Get Data form 4 tables----
    public function getfourtabledata($data,$table1,$table2,$table3,$table4,$on1,$on2,$on3,$where="",$column_search,$orderby,$group_by="",$limit="",$start="")
    {
        $this->db->select($data,FALSE);
        $this->db->from($table1);
        $this->db->join($table2,$on1,'left');
        $this->db->join($table3,$on2,'left');
        $this->db->join($table4,$on3,'left');
        if($where!="")
        {
            $this->db->where($where);
        }
        //==================Search filter check================================
        if(isset($_POST['search']) && $_POST['search']!='')
        {
            $i = 0;
            foreach ($column_search as $item) // loop column 
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
                $i++;
            }
        }
        //============group by record=========
        if($group_by!='')
        {
            $this->db->group_by($group_by);
        }
        if(isset($orderby)){
            foreach ($orderby as $key => $value) {
                $this->db->order_by($key,$value);
            }
        }
        if($limit!='' && $start!='')
        {
          $this->db->limit($limit,$start);
        }
        if($limit!='')
        {
          $this->db->limit($limit);
        }
        $query = $this->db->get();
        //echo $this->db->last_query();
        if($query->num_rows() > 0){
            return $query->result_array();
        }else{
            return array();
        }
    }

    //============get email=======
    function get_email($table,$data)
    {
        $this->db->select('email');
        $query = $this->db->get_where($table,$data);
        if ($query->num_rows() > 0) 
        {
            return $query->result_array();
        } else 
        {
            return array();
        }
    }
    
    public function get_city_state_country($pincode){
        $this->db->select(array('ci.id as cityid','ci.name as city_name','st.id as stateid','st.name as state_name','cou.id as countryid','cou.name as country_name'));
        $this->db->from('avl_pincode as pi');
        $this->db->join('cities as ci','pi.city_id=ci.id');
        $this->db->join('states as st','st.id=ci.state_id');
        $this->db->join('countries as cou','st.country_id=cou.id');
        $this->db->where('pi.pincode',$pincode);
        $query = $this->db->get();
        if($query->num_rows() > 0){
            return $query->result_array();
        }else{
            return array();
        }
    }

    public function get_distinct_data($select,$table,$where){
        $this->db->distinct();
        $this->db->select($select);
        $this->db->from($table);
        $this->db->where($where);
        $query = $this->db->get();
        if($query->num_rows() > 0){
            return $query->result_array();
        }else{
            return array();
        }
    }

    //--------------Running Transactions------
    public function transstart(){
        $this->db->trans_start();
    }

    public function transcomplete(){
        $this->db->trans_complete();
    }

    public function transrollback(){
        $this->db->trans_rollback();
    }
    public function transcommit(){
        $this->db->trans_commit();
    }


    public function custom_qry($sql){
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    public function getlist()
    {
        $this->db->select('*');
        $this->db->from('test');
        $query = $this->db->get();
        if($query->num_rows() > 0){
            return $query->result_array();
        }else{
            return array();
        }
    }
    public function getlist_itemName()
    {
        $this->db->select('*');
        $this->db->from('item_name');
        $query = $this->db->get();
        if($query->num_rows() > 0){
            return $query->result_array();
        }else{
            return array();
        }
    }
    public function getallitem($tbl,$data){
        $this->db->order_by("id", "asc");
        $query = $this->db->get_where($tbl,$data);
        if ($query->num_rows() > 0) 
        {
            return true;
        } 
        else 
        {
            return false;
        }

        /*$this->db->select('name,last_name');
        $this->db->from('test');
        $query = $this->db->get();
        if($query->num_rows() > 0){
            return $query->result_array();
        }else{
            return array();
        }*/
    }
    public function getitem_name($tbl,$data){
        $this->db->order_by("id", "asc");
        $query = $this->db->get_where($tbl,$data);
        if ($query->num_rows() > 0) 
        {
            return true;
        } 
        else 
        {
            return false;
        }}
        public function file_name(){
       $this->db->select('filename,id');
        $this->db->from('tbljson');
        $query = $this->db->get();
        if($query->num_rows() > 0){
            return $query->result_array();
        }else{
            return array();
        }}
   public function insertitems($data)
    {
        $this->db->insert('test', $data);
        //echo $this->db->last_query(); die();
        $insert_id = $this->db->insert_id();
        if($this->db->affected_rows() > 0)
        {
            // Code here after successful insert
            return true; // to the controller
        }else{
            return false;
        }
    }
    public function insertitemname($data)
    {
        $this->db->insert('item_name', $data);
        //echo $this->db->last_query(); die();
        $insert_id = $this->db->insert_id();
        if($this->db->affected_rows() > 0)
        {
            // Code here after successful insert
            return true; // to the controller
        }else{
            return false;
        }
    }
    public function insertallitem($data)
    {
        $this->db->insert('test', $data);
        //echo $this->db->last_query(); die();
        $insert_id = $this->db->insert_id();
        if($this->db->affected_rows() > 0)
        {
            // Code here after successful insert
            return true; // to the controller
        }else{
            return false;
        }
    }
    public function insertallitemname($data)
    {
        $this->db->insert('item_name', $data);
        //echo $this->db->last_query(); die();
        $insert_id = $this->db->insert_id();
        if($this->db->affected_rows() > 0)
        {
            // Code here after successful insert
            return true; // to the controller
        }else{
            return false;
        }
    }
    function addjsondata($table,$data) 
    {   
        //print_r($data);die;
        if ($data['key']=='insert') {
            unset($data['key']);
             $this->db->insert($table, $data);
            //echo $this->db->last_query(); die();
            $insert_id = $this->db->insert_id();
            return  $insert_id;
        }
        if ($data['key']=='update') {
            unset($data['key']);
            $this->db->where('id',$data['filename']);
            unset($data['filename']);
           // print_r($data);
            return $this->db->update($table,$data);
        }
        
    }
}

?>
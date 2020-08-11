<?php 
Class Picker_model extends CI_Model{

		
	public function isuservalid($username,$password)
		{
			$this->db->select('
		            pd_person.mobile_no,
		            pd_person.email,
		            pd_person.password')
		          ->from('pd_person')
		          ->where("(pd_person.email = '".$username."' OR pd_person.mobile_no = '".$username."')")
		          ->where('password', $password);
		          $query = $this->db->get();
			    if($query->num_rows() > 0 ){
			      return true;
			    }else{
			      return false;
			    }
		}

	public function insert_gcm_data_for_picker($device_token,$device_type,$userid,$time)
	{
		if ($devicetype == "android") {
			$this->db->select('*');
			$this->db->where('device_type',$device_type);
			$this->db->where('picker_id',$userid);
			$this->db->where('gcm_id',$device_token);
        } else if ($devicetype == "ios") {
        	$this->db->select('*');
			$this->db->where('device_type',$device_type);
			$this->db->where('picker_id',$userid);
			$this->db->where('uid',$device_token);
        } else {
            return null;
        }
        $query=$this->db->get("picker_gcm");
        if($query->num_rows() > 0 ){
	      return true;
	    }else{
	    	if ($devicetype == 'android') {
	    		$data= array('picker_id'=>$userid,'gcm_id'=>$device_token,'device_type'=>$device_type,'unitime'=>$time,);
	    		$this->db->insert('picker_gcm', $data);
		        $insert_id = $this->db->insert_id();
		        return  $insert_id;
            } else if ($devicetype == 'ios') {
            	$data= array('picker_id'=>$userid,'uid'=>$device_token,'device_type'=>$device_type,'unitime'=>$time,);
            	$this->db->insert('picker_gcm', $data);
		        $insert_id = $this->db->insert_id();
		        return  $insert_id;
            }
             if ($insert_id) {
            	return true;
            }
             else {
                return null;
            }
	      
	    }
	}	
		
    }
?>
			
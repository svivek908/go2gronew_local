<?php
Class Login_model extends CI_Model
{
  private $table = null;

    function __construct() {
        parent::__construct($this->table);
    }
    
  //=====Admin login======
   function auth($username, $password)
   {
      $username=$this->db->escape_str($username);
      $password=$this->db->escape_str($password);
      $this -> db -> select('*')
                 -> from('admin')
                -> where('admin_email', $username)
                -> where('admin_password', md5($password))
                -> limit(1);
      $query = $this -> db -> get();
      //echo $this->db->last_query(); die();
      if($query -> num_rows() == 1)
      {
        return $query->result();
      }
      else
      {
        return false;
      }
   }

   //====user login====
   function auth_user($username, $password)
   {
      $username=$this->db->escape_str($username);
      $this -> db -> select('*')
                 -> from('users')
                -> where('email_id', $username)
                -> limit(1);
      $query = $this -> db -> get();
      if($query -> num_rows() == 1)
      {
        $user_rec = $query->result();
        if($this->general->verifyHashedPassword($password, $user_rec[0]->password)){
            return $user_rec;
        } else {
            return array();
        }
      }
      else
      {
        return false;
      }
   }

   
}
?>
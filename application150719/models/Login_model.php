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
    $this -> db -> select('*')
               -> from('admin')
              -> where('admin_email', $username)
              -> limit(1);
    $query = $this -> db -> get();
    if($query->num_rows() == 1)
    {
      $user_rec = $query->result();
     // var_dump($user_rec[0]->admin_password);exit;
      if(verifyHashedPassword($password, $user_rec[0]->admin_password)){
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
        if(verifyHashedPassword($password, $user_rec[0]->password)){
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
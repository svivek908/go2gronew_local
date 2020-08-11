<?php
/*

Project name 	:	 go2grow admin
Company 	 	: 	 jsm infosoft
Developer	 	: 	 sachin sahu
Description	 	:	 this is panel used for admin login admin this controller and  after login redirect user Admin panel

*/

defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET,POST,OPTIONS");

class Demo extends CI_Controller{
    function __construct() {
        parent::__construct();
        if($this->session->has_userdata('supermomadmin_session')){
            redirect('Admin_dashboard');
        }
        $this->load->model('login_model');
    }
    //------------Login------

    public function index(){
        $this->form_validation->set_rules('username','Username','trim|required');
        $this->form_validation->set_rules('password', 'Password', 'required');
        if($this->form_validation->run()==TRUE)
        {
            $username = $this->input->post('username');
            $password = $this->input->post('password');
            $remember = $this->input->post('remeber');
            $username = $this->security->xss_clean($username);
            $rec = $this->login_model->Auth($username,$password);
            if($rec)
            {
                foreach ($rec as $value)
                {
                    /*if($remember=='1'){
                        setcookie("email", $username, time() + 86400 * 30);
                        setcookie("pass", $username, time() + 86400 * 30);
                        setcookie("rem", $remember, time() + 86400 * 30);
                    }else{
                        unset($_COOCKIE[""]);
                        unset($_COOCKIE[""]);
                        unset($_COOCKIE[""]);
                        setcookie("", $username, time() -10);
                        setcookie("", $username, time() -100);
                        setcookie("", $remember, time() -100);
                    }*/
                    $sess_arr = array('logged_userid' => $value->admin_id,
                        'logged_username' => $value->admin_name,
                        'logged_useremail' => $value->admin_email,
                        'logged_user_api_key' => $value->api_key,
                        'logged_user_status' => $value->admin_status);
                }
                $this->session->set_userdata('go2groadmin_session',$sess_arr);
                redirect('Admin_dashboard');
            }else{
            $this->session->set_flashdata('error','Invalid Username And Password');
            $this->load->view('admin/login');
            }
        }else{
            $this->load->view('admin/include/header');
            $this->load->view('admin/login');
        }
    }

    public function logout(){
        $this->session->unset_userdata('go2groadmin_session');
        $this->session->sess_destroy();
        redirect('Go2gro_adminlogin');
    }
}
?>
<?php

if ( ! function_exists('send_mail')) {  
    function send_mail($email,$subject,$mail_msg) {
        $CI =& get_instance();
        //Load email library
        $CI->load->library('email');

        //SMTP & mail configuration
        $config = array(
            'protocol'  => 'smtp',
            'smtp_host' => 'smtp.gmail.com',
            'smtp_port' => 587,
            'smtp_user' => 'no-reply@go2gro.com',
            'smtp_pass' => 'hellodear',
            'smtp_crypto' => 'tls', //can be 'ssl' or 'tls' for example
            'mailtype'  => 'html',
            'charset'   => 'utf-8'
        );
        $CI->email->initialize($config);
        $CI->email->set_mailtype("html");
        $CI->email->set_newline("\r\n");

        //Email content
        $htmlContent = $mail_msg;
        $CI->email->to($email);
        //$CI->email->BCC('yashparikh@go2gro.com','Go2Gro');
        $CI->email->BCC('amit.gupta@jsminfosoft.com');
        $CI->email->from('no-reply@go2gro.com','go2gro');
        $CI->email->subject($subject);
        $CI->email->message($mail_msg);
        //Send email
        if ($CI->email->send()) {
            return true;
        } else {
            //return $CI->email->print_debugger(); // check error;
            return false;
        }
    }
}



?>
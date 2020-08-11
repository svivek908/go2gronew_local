<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 
 */
class General
{
	
	function __construct()
	{
		# code...
	}
	/**-----------------Password Generate--------------
	 * This function used to generate the hashed password
	 * @param {string} $plainPassword : This is plain text password
	 */
	function getHashedPassword($plainPassword)
    {
        return password_hash($plainPassword, PASSWORD_DEFAULT);
    }

    /**
	 * This function used to generate the hashed password
	 * @param {string} $plainPassword : This is plain text password
	 * @param {string} $hashedPassword : This is hashed password
	 */
    function verifyHashedPassword($plainPassword, $hashedPassword)
    {
        return password_verify($plainPassword, $hashedPassword) ? true : false;
    }

	//-------------Mail function-----------
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

    //-----------------Send SMS----------------------

    function sendsms($mobile, $message)
    {   
        $CI =& get_instance();
        //$db = new DbHandler();
        $ch = curl_init();
        $responce = false;
        $username = usernamesms;
        $apikeysms = apikeysms;
        $url = "https://api-mapper.clicksend.com/http/v2/send.php?method=http&username=" . $username . "&key=" . $apikeysms . "&to=" . $mobile . "&message=".$message."";
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,
            true);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_HTTPGET, 1);
        //curl_setopt($ch,CURLOPT_DNS_USE_GLOBAL_CACHE, FALSE); //----uncomment on live 
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, FALSE); //----comment on live 
        curl_setopt($ch,CURLOPT_DNS_CACHE_TIMEOUT, 2);

        $output = curl_exec($ch);

        if (curl_errno($ch)) {
            //  echo 'error:' . curl_error($ch);
            $responce = false;
        } else {
            $responce = true;
        }
        curl_close($ch);
        return $responce;
    }

    //------------------Notification----------------

    function sent_notifiction($userid,$orderid,$action,$date,$timedate,$notitag,$title,$message){
        $CI =& get_instance();
        $android_arr = $CI->Model->get_selected_data('*','tbl_usergcm',array('divicetype' => 'android','gcm_id!='=>'','gcm_id!='=>'NA'),$order='id',$type='desc',$limit='5');
        $ios_arr = $CI->Model->get_selected_data('*','tbl_usergcm',array('divicetype' => 'ios','uid!='=>'','uid!='=>'NA'),$order='id',$type='desc',$limit='5');
        $android_gcm_ids = array(); $ios_gcm_ids = array();
        $array = array();
        foreach ($android_arr as $key => $value) {
            $array[] = array('order_id' => $orderid,
                'notificationuserid' => $userid,
                'action' => $action,
                'date' => $date,
                'unitime' => $timedate,
                'status' =>0,
                'title' => $title,
                'message' => $message,
                'tag' =>$notitag);
            $android_gcm_ids[] = $value['gcm_id'];
        }
        //-----------------IOS--------
        foreach ($ios_arr as $key => $val) {
            $array[] = array('order_id' => $orderid,
                'notificationuserid' => $userid,
                'action' => $action,
                'date' => $date,
                'unitime' => $timedate,
                'status' =>0,
                'title' => $title,
                'message' => $message,
                'tag' =>$notitag);
            $ios_gcm_ids[] = $val['uid'];
        }
        $CI->Model->batch_rec('tbl_notification',$array);
        $message_arr = array("title" => "#".$orderid."", "orderid" => $orderid,"senderid" => $userid, "reciverid" => $userid, "status" => $statusonpost, "notificationtype" => $statusonpost,"notificationtime" => $date, "notificationid"=>"","notificationunitime" => $timedate, "notificationdate" => $date,"vibrate" => 1,"sound" => 1,"largeIcon" => DEFULTIMAGE, "smallIcon" => DEFULTIMAGE,"subtitle" => '', "tickerText" => '');
        $message_arr["message"] = ord_msg($action);
        sendNotification($android_gcm_ids,$message_arr,"android");
        sendNotification($ios_gcm_ids,$message_arr,"ios");
    }
}

//===================order message============

function ord_msg($statusonpost){
    if($statusonpost == ORDER_PLACED){
        $message=ORDER_PLACED_MSG." \n click here to see order detail";
    }elseif($statusonpost == ORDER_PREPARE){
        $message=ORDER_PREPARE_MSG." \n click here to see order detail";
    }elseif($statusonpost == ORDER_PACKED){
        $message=ORDER_PACKED_MSG." \n click here to see order detail";
    }elseif($statusonpost == ORDER_SHIPPED){
        $message=ORDER_SHIPPED_MSG." \n click here to see order detail";
    }elseif($statusonpost == ORDER_OUTFORDELIVERY){
        $message=ORDER_OUTFORDELIVERY_MSG." \n click here to see order detail";
    }elseif($statusonpost == ORDER_DELIVERED){
        $message=ORDER_DELIVERED_MSG." \n click here to see order detail";
    }elseif($statusonpost == ORDER_REJECT){
        $message=ORDER_REJECT_MSG." \n click here to see order detail";
    }elseif($statusonpost == ORDER_CANCLE){
        $message=ORDER_CANCLE_MSG." \n click here to see order detail";
    }elseif($statusonpost==ORDER_SEND_ALTERNATIVE){
        $message=ORDER_SEND_ALTERNATIVE_MSG." \n click here to see order detail";
    }else{
        $message = '';
    }
    return $message;
}
//===================sendNotification===============
	function sendNotification($registatoin_ids,$message,$device_type)
    {
        $fields = array('registration_ids' => $registatoin_ids);

        if($device_type == 'ios'){
            $fields['notification'] = $message;
        }else{
            $fields['data'] = $message;
        }

        $headers = array(
            'Authorization: key='.GOOGLE_API_KEY,
            'Content-Type: application/json'
        );
        // Open connection
        $ch = curl_init();

        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, GOOGLE_FCM_URL);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        // Execute post
        $result = curl_exec($ch);

        if ($result === FALSE) {
            die('FCM Send Error: ' . curl_error($ch));
        }
        // Close connection
        curl_close($ch);
        return $result;
    }
//===================send Ios Notification===============
    function sendIosNotification($registatoin_ids,$message)
    {
        $fields = array(
            'registration_ids' =>$registatoin_ids,
            'notification' => $message,
        );
        $json = json_encode($fields);
        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: key='. GOOGLE_API_KEY;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, GOOGLE_IOS_FCM_URL);
        //setting the method as post
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"POST");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        //adding headers 
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //disabling ssl support
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        //Send the request
        $response = curl_exec($ch);
        //Close request
        if ($response === FALSE) {
            die('FCM Send Error: ' . curl_error($ch));
        }
        //echo $response;
        curl_close($ch);
        return $response;
    }

    

/*
*
* End class
*/
?>
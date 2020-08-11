<?php 
if(! function_exists('notification')) {
    function notifiction($userid,$orderid,$action,$date,$timedate,$notitag,$title,$message){
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
                'title' => $title
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
                'title' => $title
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

if(! function_exists('ord_msg')){
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
        return $message);
    }
}
//===================sendNotification===============
if(! function_exists('sendNotification')) {
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
}
//===================send Ios Notification===============
/*if(! function_exists('sendIosNotification')) {
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
}*/
?>
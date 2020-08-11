<?php 
if(! function_exists('notification')) {
    function notifiction($userid,$orderid,$action,$date,$timedate,$notitag,$title,$message){
        $CI =& get_instance();
        $android_arr = $CI->Model->get_selected_data('*','tbl_usergcm',array('divicetype' => 'android'),$order='id',$type='desc',$limit='5');
        $ios_arr = $CI->Model->get_selected_data('*','tbl_usergcm',array('divicetype' => 'ios'),$order='id',$type='desc',$limit='5');
        $android_gcm_ids = array(); $ios_gcm_ids = array();

        foreach ($android_arr as $key => $value) {
            $array = array('order_id' => $orderid,
                'notificationuserid' => $userid,
                'action' => $action,
                'date' => $date,
                'unitime' => $timedate,
                'status' =>0,
                'tag' =>$notitag);
            $CI->Model->add('tbl_notification',$array);
        }
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
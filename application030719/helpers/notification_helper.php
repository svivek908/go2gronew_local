<?php 
//===================sendNotification===============
if(! function_exists('sendNotification')) {
    function sendNotification($deviceid,$title,$message,$type)
    {
        define( 'API_ACCESS_KEY', 'AIzaSyCATsK70Wfy5v6JIkKA97ud3Od3dG47s3w' );
        //$registrationIds = '$registerId'; // device id
        $msg = array
             (
                'title'    => $title,
                'auto_message' =>$message,
                'type'=>$type,
                //'auto_icon'    => 'https://www.musclesandwellness.com/frenchise/images/logo/logo-white-new.png',/*Default Icon*/
                             //'sound' => 'mySound'/*Default sound*/
             );
            $fields = array
            (
            'to'    => $deviceid,
            'data'    => $msg
            );
        $headers = array
        (
            'Authorization: key=' . API_ACCESS_KEY,
            'Content-Type: application/json'
        );
        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
        $result = curl_exec($ch );
        curl_close( $ch );
        return $result;
    }
}
?>
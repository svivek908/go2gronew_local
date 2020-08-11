<?php

if ( ! function_exists('send_sms')) {  
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
}



?>
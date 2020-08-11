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
    if(! function_exists('sendstatussms'))
    {
        function sendstatussms($mobile, $username1, $status, $orderid)
        {
            $CI =& get_instance();
            /*if ($status == '2') {
                $msg = "Dear $username1, Your Order ($orderid) is Ready/Packed, and will dispatched soon.";
            } else*/
            if ($status == '3') {
                $msg = "Dear $username1 ,\n
        Your Order ($orderid) is Packed and is out for delivery.";
            } elseif ($status == '4') {
                $msg = "Dear $username1,\n Your Go2Gro order ($orderid) has been delivered.
        We hope that everything turned out fantastic
        Thank You for using Go2Gro!";
            }
        
            $message = urlencode($msg);
            $ch = curl_init();
            $responce = false;
        
            $username = usernamesms;
            $apikeysms = apikeysms;
        
            $url = "https://api-mapper.clicksend.com/http/v2/send.php?method=http&username=" . $username . "&key=" . $apikeysms . "&to=" . $mobile . "&message=$message";
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,
                true);
            curl_setopt($ch, CURLOPT_HEADER, 1);
            curl_setopt($ch, CURLOPT_HTTPGET, 1);
            curl_setopt($ch,
                CURLOPT_DNS_USE_GLOBAL_CACHE, false);
            curl_setopt($ch,
                CURLOPT_DNS_CACHE_TIMEOUT, 2);
        
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
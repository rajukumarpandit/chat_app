<?php

$ch = curl_init("https://api-ap2.pusher.com");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CAINFO, "D:/Server/php/extras/ssl/cacert.pem");
$output = curl_exec($ch);

if(curl_errno($ch)){
    echo 'Error:' . curl_error($ch);
} else {
    echo "cURL works!";
}
curl_close($ch);
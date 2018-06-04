<?php

$accessKey = '93f258dace70208b4b5d38de7266570dc48a7572';
$secretKey = 'a0ZkBetAFbDwW89HXF260jF2Iut6iMpph95v';

$payload = array();

$base_url = 'http://dev.forge.ly';
$api_uri = '/api/v1';
$uri = '/users';

$url = $base_url . $api_uri . $uri;

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_USERPWD, $accessKey . ':' . $secretKey);

//curl_setopt($ch, CURLOPT_COOKIE, 'XDEBUG_SESSION=forgelyapitest');

if (count($payload)) {
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload, '', '&'));
}

$httpInfo = curl_getinfo($ch);

$output = curl_exec($ch);

$decoded = json_decode($output, true);


curl_close($ch);

print '<pre>' . print_r($decoded, true) . '</pre>';

//
//
//$data = array(
//  "authorizedKey" => "abbad35c5c01-xxxx-xxx",
//  "senderEmail" => "myemail@yahoo.com",
//  "recipientEmail" => "jaketalledo86@yahoo.com",
//  "comment" => "Invitation",
//  "forceDebitCard" => "false"
//);
//
//$url_send ="http://dev.forge.ly/online-hiring/ziprecruiter-apply-response";
//$str_data = json_encode($data);
//
//function sendPostData($url, $post, $debug = false){
//  $ch = curl_init($url);
//  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
//  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//  curl_setopt($ch, CURLOPT_POSTFIELDS,$post);
//  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
//  if($debug) {
//    curl_setopt($ch, CURLOPT_COOKIE, 'XDEBUG_SESSION=forgelyapitest');
//  }
//  $result = curl_exec($ch);
//  curl_close($ch);  // Seems like good practice
//  return $result;
//}
//
//echo " " . sendPostData($url_send, $str_data, true);
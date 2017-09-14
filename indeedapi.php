<?php


$payload = array();

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

$httpInfo = curl_getinfo($ch);

$output = curl_exec($ch);

$decoded = json_decode($output, true);


curl_close($ch);

print '<pre>' . print_r($decoded, true) . '</pre>';
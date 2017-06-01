<?php

$accessKey = '0d19666afb824d151fed5f0c190abd93c47d0760';
$secretKey = 'pERmxdV7ReCDzYiEKreWITE0K3VN5JmFpWSU';

$payload = array();

$base_url = 'http://dev.forge.ly';
$api_uri = '/api/v1';
$uri = '/company';

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
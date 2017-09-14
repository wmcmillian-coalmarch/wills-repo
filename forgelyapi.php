<?php
$json = $_POST['json'];
$data = json_decode($json, true);
file_put_contents(__DIR__ . '/postdata.json', json_encode($data, JSON_PRETTY_PRINT));


$accessKey = '4011b08f7218bc650a3e6270f52394a6d9ae4cfc';
$secretKey = 'SMpd6pFYYWsLSOmItT2zglICChPNdteJ0Cly';

$payload = array();

$base_url = 'http://dev.forge.ly';
$api_uri = '/api/v1';
$uri = '/jobs/sprowt';

$url = $base_url . $api_uri . $uri;

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_USERPWD, $accessKey . ':' . $secretKey);

curl_setopt($ch, CURLOPT_COOKIE, 'XDEBUG_SESSION=forgelyapitest');

if (count($payload)) {
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload, '', '&'));
}

$httpInfo = curl_getinfo($ch);

$output = curl_exec($ch);

$decoded = json_decode($output, true);


curl_close($ch);

print '<pre>' . print_r($decoded, true) . '</pre>';
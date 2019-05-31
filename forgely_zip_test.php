<?php

$dir = __DIR__;
$json = file_get_contents($dir . '/zipPost.json');

$headers = [
    "User-Agent: libwww-perl/6.13",
    "Content-Length: " . strlen($json),
    "Content-Type: application/json"
];

$url = 'http://dev.forge.ly/online-hiring/ziprecruiter-apply-response';

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

curl_setopt($ch, CURLOPT_COOKIE, 'XDEBUG_SESSION=forgelyapitest');

$result = curl_exec($ch);
curl_close($ch);  // Seems like good practice

echo "<pre>" . print_r($result, true) ."</pre>";
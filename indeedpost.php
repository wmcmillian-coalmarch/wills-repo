<?php


//$json = file_get_contents('/home/will/Sites/sandbox/2017-09-27--18_02_08.json');
$json = file_get_contents(__DIR__ . '/indeedData.json');
$contents = json_decode($json, true);

$url = $contents["questions"]["url"] . '/submission';
if(strpos($url, 'https://forge.ly') !== false){
    $url = str_replace('https://forge.ly', 'http://dev.forge.ly', $url);
}

$headersArray = [
    'Total-Route-Time' => '0',
    'Content-Length' => '24772',
    'Cf-Visitor' => '{"scheme":"https"}',
    'X-Indeed-Signature' => '49ke9TGBXJ5JQOnKTzw67wrVOA8=',
    'X-Request-Id' => 'ca20ef3b-ff1b-4c4d-a48f-435a2d1eeaa9',
    'Accept-Encoding' => 'gzip',
    'Cf-Connecting-Ip' => '198.58.75.9',
    'Host' => 'requestb.in',
    'Cf-Ipcountry' => 'US',
    'Content-Type' => 'application/json; charset=UTF-8',
    'User-Agent' => 'IndeedApply/d6950746bf8aecfe6f597525453c3b54c3d92e14 (+http://www.indeed.com/hire/indeed-apply)',
    'Connect-Time' => '1',
    'Via' => '1.1 vegur',
    'Connection' => 'close',
    'Cf-Ray' => '3a4f61c27b4a58c1-DFW',
];

$headersArray['Content-Length'] = strlen($json);

$headers = [];
foreach($headersArray as $h => $v) {
    $headers[] = "$h: $v";
}

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_COOKIE, 'XDEBUG_SESSION=PHPSTORM');

$result = curl_exec($ch);

echo '<pre>' . print_r($result, true) . '</pre>';
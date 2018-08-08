<?php

function curl($url, $payload = [], $method = 'GET', $headers = []) {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    if(!empty($headers)) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }

    if(DEBUG) {
        curl_setopt($ch, CURLOPT_COOKIE, 'XDEBUG_SESSION=forgelyapitest');
    }

    if(!empty(ACCESS_KEY) && !empty(SECRET_KEY)) {
        curl_setopt($ch, CURLOPT_USERPWD, ACCESS_KEY . ':' . SECRET_KEY);
    }

    if (count($payload)) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload, '', '&'));
    }

    $httpInfo = curl_getinfo($ch);

    $output = curl_exec($ch);

    curl_close($ch);

    return [
        'info' => $httpInfo,
        'output' => $output
    ];
};

function authenticate()
{
    $base_url = 'http://dev.forge.ly';
    $tokenfile = __DIR__ . '/forgelyToken.json';
    $res = post($base_url . '/api/login_check', [
        '_username' => 'wmcmillian@coalmarch.com',
        '_password' => 'C04lm4rch'
    ]);

    $tokenRes = json_decode($res['output'], true);

    if (!empty($tokenRes['token'])) {
        file_put_contents($tokenfile, json_encode($tokenRes, JSON_PRETTY_PRINT));
    }
}

function get($url, $payload = [], $headers = []) {
    return curl($url, $payload, 'GET', $headers);
}

function post($url, $payload = [], $headers = []) {
    return curl($url, $payload, 'POST', $headers);
}
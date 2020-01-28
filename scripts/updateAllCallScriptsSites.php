#!/usr/bin/env php
<?php

date_default_timezone_set('America/New_York');

exec("which terminus", $terminus);
$terminus = array_shift($terminus);
define('TERMINUS', $terminus);

function terminusCommand($cmd) {
    $terminus = TERMINUS;
    $cmd = "$terminus $cmd --format=json";
    $result = exec($cmd, $json);
    $json = implode("", $json);
    return json_decode($json, true);
}


$sites = terminusCommand('org:site:list 08b99cd5-81a3-4b67-acc1-d5dba50390f8 --upstream=ffa5972a-65d3-4ba0-b8f1-82d5aa3c3de7');

foreach($sites as $site) {
    if($site['plan_name'] == 'Sandbox') {
        $env = 'dev';
    }
    else {
        $env = 'live';
    }
    
    $cmd = __DIR__ . '/updateCallScriptsSite.sh ' . $site['name'] . ' ' . $env;
    system($cmd);
}
#! /usr/bin/env php
<?php

date_default_timezone_set('America/New_York');

$terminus = $_SERVER['HOME'] . '/terminus/bin/terminus';

$site_list = exec("$terminus org:sites 08b99cd5-81a3-4b67-acc1-d5dba50390f8 --format=json", $json);
$site_list = implode("", $json);
$site_list = json_decode($site_list, true);


foreach($site_list as $site) {
    if(empty($site['frozen']) && $site['service_level'] == 'free') {
        exec("$terminus backup:create {$site['name']}.dev");
        echo date('Y-m-d H:i:s') . " - {$site['name']} backed up.\n";
    }
}
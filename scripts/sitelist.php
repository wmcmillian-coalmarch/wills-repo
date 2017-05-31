#! /usr/bin/env php
<?php

date_default_timezone_set('America/New_York');

$terminus = $_SERVER['HOME'] . '/terminus/bin/terminus';

$site_list = exec("$terminus organizations sites list --org=08b99cd5-81a3-4b67-acc1-d5dba50390f8 --format=json", $json);
$site_list = implode("", $json);
$site_list = json_decode($site_list, true);

$csv = array();
foreach($site_list as $site) {
    $json = array();
    $info = exec("$terminus site info --site={$site['name']} --format=json", $json);
    $info = implode("", $json);
    $info = json_decode($info, true);
    if(is_array($info['upstream'])) {
        foreach ($info['upstream'] as $k => $v) {
            $info['upstream_' . $k] = $v;
        }
        unset($info['upstream']);
    }
    else {
        $info['upstream_url'] = 'none';
    }

    if(count($csv) == 0) {
        $csv[] = array_keys($info);
    }
    $csv[] = array_values($info);
}

$fp = fopen('sites.csv', 'w');

foreach ($csv as $line) {
    fputcsv($fp, $line);
}

fclose($fp);

echo "Site list created: sites.csv";
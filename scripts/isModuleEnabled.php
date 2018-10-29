#! /usr/bin/env php
<?php

date_default_timezone_set('America/New_York');

exec("which terminus", $terminus);
$terminus = array_shift($terminus);
define('TERMINUS', $terminus);

if(empty($argv) || empty($argv[1])) {
    echo 'module argument needed';
    die(1);
}

$module = $argv[1];

$env = 'live';
if(!empty($argv) && !empty($argv[2])) {
    $env = $argv[2];
}

define('TERMINUS_SITE_ENV', $env);
define('MODULE_TEST', $module);

//$site_list = [
//    [
//        'service_level' => 'basic',
//        'framework' => 'drupal',
//        'name' => 'carypestcompany'
//    ],
//    [
//        'service_level' => 'basic',
//        'framework' => 'drupal',
//        'name' => 'tpc-2017'
//    ]
//];

$site_list = exec("$terminus org:sites 08b99cd5-81a3-4b67-acc1-d5dba50390f8 --format=json", $json);
$site_list = implode("", $json);
$site_list = json_decode($site_list, true);


$output = array();
foreach($site_list as &$site) {
    $something = 'something';
    if($site['service_level'] !== 'free' && $site['framework'] == 'drupal') {
        $name = $site['name'];
        if(isModuleEnabled($name)) {
            $output[] = $name;
        }
    }
}


function isModuleEnabled($site) {
    echo "checking site: $site ...\n";
    $terminus = TERMINUS;
    $env = TERMINUS_SITE_ENV;
    $module = MODULE_TEST;
    //$cmd = 'pm-list --pipe --type=module --status=enabled --no-core --format=json';
    $cmd = 'sql-query \'SELECT status FROM system WHERE name = "'.$module.'"\'';
    exec("$terminus drush $site.$env -- $cmd 2>&1", $out);
    array_pop($out);
    $status = array_pop($out);
    if(strlen($status) > 1) {
        return false;
    }
    return !empty($status);
}

if(empty($output)) {
    echo 'No site has that module enabled' . "\n";
}
else {
    $output = implode("\n", $output);
    file_put_contents('./hasModule.txt', $output);
    echo "\nresult:\n";
    echo "============================\n\n";
    echo $output;
    echo "\n\n============================\n\n";
    echo 'Result saved to ./hasModule.txt';
}
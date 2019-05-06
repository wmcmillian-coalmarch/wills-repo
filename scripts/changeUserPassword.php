#! /usr/bin/env php
<?php
/**
 * Created by PhpStorm.
 * User: willmcmillian
 * Date: 10/29/18
 * Time: 1:27 PM
 */

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

function drushCommand($siteEnv, $cmd, $jsonParse = true) {
    $terminus = TERMINUS;
    if($jsonParse) {
        $cmd = "$terminus drush $siteEnv -- $cmd --format=json";
        $result = exec($cmd, $json);
        $json = implode("", $json);
        return json_decode($json, TRUE);
    }
    
    $cmd = "$terminus drush $siteEnv -- $cmd";
    $result = exec($cmd, $json);
    return $json;
}

$longopts = [
    'user:',
    'password:',
    'help'
];



$options = getopt('u:hp:', $longopts);
if(empty($options)) {
    $options = ['h' => true];
}

$file = __FILE__;

if(isset($options['h']) || isset($options['help'])) {
    echo <<<EOT
This script resets a password for a user for every drupal site in Coalmarch's organization.
$file -u/--user=username/id/email -p/--password="newPassword"

Usage:

$file -u1 --password="newPassword"
$file -u="user@coalmarch.com" -p="newPassword"
$file --user=username --password="newPassword"

EOT;

    exit;
}

if(empty($options['u']) && empty($options['user'])) {
    echo 'user argument missing.';
    die(1);
}


if(empty($options['p']) && empty($options['password'])) {
    echo 'password argument missing.';
    die(1);
}

$user = empty($options['u']) ? $options['user'] : $options['u'];
$password = empty($options['p']) ? $options['password'] : $options['p'];
define('USER', $user);
define('PASSWORD', $password);


function updateUser($siteName) {
    $envs = [
        'dev',
        'test',
        'live'
    ];
    
    $environments = terminusCommand("env:list $siteName");
    foreach($environments as $environment) {
        if(in_array($environment['id'], $envs) && $environment['initialized'] == 'true') {
            $siteEnv = "$siteName." . $environment['id'];
            $userInfoRaw = drushCommand($siteEnv, 'uinf ' . USER, false);
            $userInfo = [];
            foreach ($userInfoRaw as $row) {
                if(strpos($row, 'User') !== false) {
                    $rowParts = explode(':', $row);
                    $userInfo[trim($rowParts[0])] = trim($rowParts[1]);
                }
            }
            if(!empty($userInfo['User name'])) {
                $response = drushCommand($siteEnv, 'upwd "' . $userInfo['User name'] . '" --password="' . PASSWORD . '"', FALSE);
                echo array_pop($response) . "\n";
            }
            else {
                echo 'User ' . USER . ' not found for site: ' . $siteName . ' in environment ' . $environment['id'];
            }
        }
    }
    
}

$site_list = terminusCommand('org:sites 08b99cd5-81a3-4b67-acc1-d5dba50390f8');

foreach($site_list as $site) {
    if($site['framework'] == 'drupal' && $site['frozen'] != 'true') {
        updateUser($site['name']);
    }
}
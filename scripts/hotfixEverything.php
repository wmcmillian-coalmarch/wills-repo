#! /usr/bin/env php
<?php

date_default_timezone_set('America/New_York');

exec("which terminus", $terminus);
$terminus = array_shift($terminus);
define('TERMINUS', $terminus);

exec("which drush", $drush);
$drush = array_shift($drush);
define('DRUSH', $drush);
define('HOME', getenv('HOME'));

exec('echo $OSTYPE', $os);
$os = array_shift($os);
define('OS', $os);

define('CWD', getcwd());

function terminusCommand($cmd) {
    $terminus = TERMINUS;
    $cmd = "$terminus $cmd --format=json";
    $result = exec($cmd, $json);
    $json = implode("", $json);
    return json_decode($json, true);
}

function drushCommand($site, $cmd, $jsonParse = true) {
    chdir(HOME . '/Sites/' .$site);
    $drush = DRUSH;
    if($jsonParse) {
        $cmd = "$drush $cmd --format=json";
        $result = exec($cmd, $json);
        $json = implode("", $json);
        return json_decode($json, TRUE);
    }
    
    $cmd = "$drush $cmd";
    $result = exec($cmd, $json);
    return $json;
}

function tDrushCommand($siteEnv, $cmd, $jsonParse = true) {
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

$site_list = terminusCommand('org:sites 08b99cd5-81a3-4b67-acc1-d5dba50390f8');

foreach($site_list as $site) {
    if($site['framework'] == 'drupal' && $site['service_level'] != 'free') {
        hotfixSite($site['name']);
    }
}

function hotfixSite($site) {
    $siteDir = HOME . "/Sites/$site";
    if(!is_dir($siteDir)) {
        exec('tclone ' . $site);
        chdir($siteDir);
    }
    else {
        chdir($siteDir);
        exec('git fetch --tags');
    }
    if(strpos(OS, 'darwin') !== false) {
        $sort = 'gsort';
    }
    else {
        $sort = 'sort';
    }
    
    exec("git tag | grep live_ | $sort -V", $tags);
    $latestLiveTag = array_pop($tags);
    if(strpos($latestLiveTag, 'pantheon_live_') === false) {
        echo "$site doesn't contain live Pantheon tag!";
        return false;
    }
    exec('git checkout ' .  $latestLiveTag);
    
    $status = drushCommand($site, 'status');
    if(!empty($status) && !empty($status['drupal-version']) && strpos($status['drupal-version'], '7') === 0 && $status['drupal-version'] != '7.63') {
        $latestLive_number = str_replace('pantheon_live_', '', $latestLiveTag);
        $newNum = ++$latestLive_number;
        exec('git pull https://github.com/pantheon-systems/drops-7.git --commit -q', $return);
        $lastLine = array_pop($return);
        if(strpos($lastLine, 'failed') !== false) {
            $failed = CWD . '/failed-hotfixes.txt';
            if(!is_file($failed)) {
                exec("touch $failed");
            }
            exec('echo "'.$site.'\n" >> ' . $failed);
            echo "$site  failed hotfix!\n\n\n";
            return false;
        }
        exec('git tag -a pantheon_live_' . $newNum . ' -m "update drupal hotfix"');
        $tReturn = terminusCommand("backup:create {$site}.live -n");
        exec('git push --tags');
        $tdReturn = tDrushCommand("$site.live", 'cc all');
        $tdReturn = tDrushCommand("$site.live", 'updatedb -y');
        echo "$site hotfixed!\n\n\n";
        exec('git checkout master -q');
        return true;
    }
    
    if(!empty($status) && !empty($status['drupal-version']) && strpos($status['drupal-version'], '7') === 0 && $status['drupal-version'] == '7.63') {
        return true;
    }
    
    $failed = CWD . '/status-failed-hotfixes.txt';
    if(!is_file($failed)) {
        exec("touch $failed");
    }
    exec('echo "'.$site.'\n" >> ' . $failed);
    echo "$site  failed hotfix!\n\n\n";
    
    return false;
}
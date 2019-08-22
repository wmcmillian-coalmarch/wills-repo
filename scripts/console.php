#!/usr/bin/env bash
<?php

$args = $argv;
exec('!!', $output);

function getDirectory($sitesRoot) {
    $cwd = getcwd();
    if(preg_match('#(.*)/'.$sitesRoot.'/([^/]+)#', $cwd, $matches)) {
        return (empty($matches[1])) ? null : $matches[1];
    }
    
    return null;
}

$dir = getDirectory('Projects');
if(empty($dir)) {
    $dir = getDirectory('Sites');
}

if(empty($dir)) {
    throw new Exception('Project directory not detected!');
}

$tests = [
    'bin/appc' => 'COALMARCH_SYMPHONY',
    'bin/console' => 'SYMPHONY_4',
    'app/console' => 'SYMPHONY',
    'artisan' => 'LARAVEL',
    'core/lib/Drupal.php' => 'DRUPAL_8',
    'modules/system/system.module' => 'DRUPAL_7',
    'wp-config.php' => 'WORDPRESS'
];
$cmd = '';
foreach($tests as $filepath => $type) {
    $file = $dir . '/' . $filepath;
    if(file_exists($file)) {
        switch($type) {
            case 'COALMARCH_SYMPHONY':
                $cmd = $filepath;
                break;
            case 'SYMPHONY_4':
            case 'SYMPHONY':
            case 'LARAVEL':
                $cmd = '/usr/bin/env php ' . $filepath;
                break;
            case 'DRUPAL_8':
                $cmd = 'drupal';
                break;
            case 'DRUPAL_7':
                $cmd = 'drush';
                break;
        }
        break;
    }
}
#!/usr/bin/env php
<?php
$first = $argv[1];
$comp = $argv[2];
$second = $argv[3];

if(empty($first) || empty($comp) || empty($second)) {
    echo '3 arguments are required!';
    exit(1);
}


date_default_timezone_set('UTC');

$firstDate = new DateTime($first);
$secondDate = new DateTime($second);

switch($comp) {
    case '>':
        $ret = $firstDate > $secondDate;
        break;
    case '<':
        $ret = $firstDate < $secondDate;
        break;
    case '<=':
        $ret = $firstDate <= $secondDate;
        break;
    case '>=':
        $ret = $firstDate >= $secondDate;
        break;
    case '==':
        $ret = $firstDate == $secondDate;
        break;
    case '!=':
        $ret = $firstDate != $secondDate;
        break;
}

echo $ret ? 1 : 0;
#!/usr/bin/env php
<?php

global $argv;

$count = $argv[1];
$email = $argv[2];

if(empty($count)) {
    print "Must have a number of customers";
    exit(1);
}

if(empty($email)) {
    $email = 'wmcmillian@coalmarch.com';
}


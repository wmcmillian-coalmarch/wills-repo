<?php

$users = array(
    'asentner',
    'awelles',
    'cnein',
    'jcorson',
    'jfekete',
    'jstanley',
    'lhenderson',
    'lkennedy',
    'mludlam',
    'mmayorga',
    'rbetterbid',
    'rkirkpatrick',
    'rpratt',
    'rscotton',
    'tmeans',
    'wmcmillian',
);

$return = array();

foreach($users as $username) {
    $user = array(
        'name' => $username,
        'mail' => "$username@coalmarch.com",
        'pass' => 'c04lm4rch',
        'status' => 1,
        'roles' => array(
            2 => 'authenticated user',
            3 => 'administrator'
        ),
    );
    $account = user_save(null, $user);
    $return[$account->uid] = $account->name;
}

dsm($return);
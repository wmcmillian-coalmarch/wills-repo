<?php
define('DEBUG', false);
define('ACCESS_KEY', null);
define('SECRET_KEY', null);
//define('ACCESS_KEY','8bf06dc0f01aa8b1ef801378645b131641c6d6a2');
//define('SECRET_KEY', 'SQB48JgfmrTWnAFlkLvF6Mf60aNiUEpviArF');

require_once 'forgelycurlfunctions.php';



$tokenfile = __DIR__ . '/forgelyToken.json';
$tokenJson = file_get_contents($tokenfile);
if(empty($tokenJson)) {
    authenticate();
    $tokenJson = file_get_contents($tokenfile);
}

$token = json_decode($tokenJson, true);
$token = $token['token'];

$authHeaders = [
    'Authorization: Bearer ' . $token
];

//$authHeaders = [];

// $payload = array();

$base_url = 'http://dev.forge.ly';
$api_uri = '/api/v2';
$uri = '/company';

$url = $base_url . $api_uri . $uri;

$res = get($url, [], $authHeaders);


print '<pre>' . print_r($res, true) . '</pre>';
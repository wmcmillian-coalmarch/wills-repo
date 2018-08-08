<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Pest Pac API</title>
    <link href="./public/css/app.css" rel="stylesheet">
</head>
<body>

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 6/20/18
 * Time: 4:40 PM
 */

require_once('app/PestPac.php');
require_once('app/PestPacOauth.php');
use PestPac\PestPac;
use PestPac\PestPacOauth;


$wwid_user = 'josh.baker@trianglepest.com';
$wwid_pass = 'Bugz0202@';
$id = 'WBbx7tbTgYl3j3B1HcgSNcXNDTka';
$secret = 'rfIbfQpJ4B5m7EnsrzFoZZn6v7sa';
$tokenUrl = "https://is.workwave.com/oauth2/token?scope=openid";
$url = "https://is.workwave.com/oauth2/token?scope=openid";
$access_token = "eabe3068-bca3-345f-bcf3-c5b08e81540e";
$grantType = 'password';
$apiKey = "fXGpNGCKII3m8UhVxkRNt34k7z7P9sGt";

$ppc = new PestPacOauth($wwid_user,$wwid_pass,$id,$secret,'password');
$ppc->authenticate($url);
$pp = new PestPac($apiKey);
print_r($pp->testCall());


?>

<div class="container">
    <div class="card">
        <div class="card-content">
            <form class="control" action="/index.php">
                <button type="submit" class="button is-block is-info is-large">Submit</button>
            </form>
        </div>
    </div>

</div>

</body>
</html>

<?php
function sendMail() {
    $credentials = "api:key-d94da75c8c7955e514b7af06362ae929";
    $data = array(
        'from'    => 'devel@coalmarch.com',
        'to'      => 'wmcmillian@coalmarch.com',
        'subject' => 'ssl test',
        'text'    => 'test test test'
    );
    $ch = curl_init('https://api.mailgun.net/v3/coalmarch.com/messages');
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Basic " . base64_encode($credentials)));
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($ch);
}

function checkDomain($domain) {
    $opts = array(
        'ssl' => array(
            'capture_peer_cert' => TRUE
        )
    );
    
    $context = stream_context_create($opts);
    $client = stream_socket_client("ssl://$domain:443", $errno, $errstr, 30, STREAM_CLIENT_CONNECT, $context);
    $cont = stream_context_get_params($client);
    if(!empty($cont['options']['ssl']['peer_certificate'])) {
        $cert = openssl_x509_parse($cont['options']['ssl']['peer_certificate']);
        $expiration = new DateTime(date('c',$cert['validTo_time_t']));
        $now = new DateTime();
        $diff = $expiration->sub($now);
        if($now >= $expiration || ($expiration > $now && $diff->$w <= 1)) {
            return false;
        }
        else {
            return true;
        }
    }
}
exec("terminus organizations sites list --format=json", $out);
$sites = json_decode($out[0], true);
$hostnames = array();

foreach($sites as $site) {
    if($site['service_level'] == 'pro') {
        $cmd = "terminus site hostnames list --env=live --site={$site['name']} --format=json";
        $out = array();
        exec($cmd, $out);
        $site_hosts = json_decode($out[0], true);
        $hosts = array();
        foreach($site_hosts as $h) {
            if($h['type'] == 'custom') {
                $hosts[] = $h['domain'];
            }
        }
        $hostnames[$site['name']] = array(
            'site_info' => $site,
            'hosts' => $hosts
        );
    }
}


foreach($hostnames as $sslsite) {
    
}
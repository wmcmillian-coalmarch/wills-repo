<?php



function curlUrl($uri, $post = array(), $headers = array(), $debug = false){
    
    $base_url = 'https://coalmarch.rocket.chat';
    $url = $base_url . $uri;
    $ch = curl_init($url);
    if(!empty($post)) {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    }
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    if(!empty($post)) {
        $postFields = http_build_query($post, null, '&');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
    }
    
    if(!empty($headers)) {
        $headerTexts = array();
        foreach($headers as $key => $value) {
            $headerTexts[] = "$key: $value";
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headerTexts);
    }
    
    
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    if($debug) {
        curl_setopt($ch, CURLOPT_COOKIE, 'XDEBUG_SESSION=forgelyapitest');
    }
    $result = curl_exec($ch);
    curl_close($ch);  // Seems like good practice
    return $result;
}

$auth = curlUrl('/api/v1/login', array(
    'user' => 'wmcmillian@coalmarch.com',
    'password' => '5reasonswhyIlovelamps'
));

$auth = json_decode($auth, true);
if(!empty($auth['status']) && $auth['status'] == 'success') {
    $creds = $auth['data'];
    
    $headers = array(
        'X-Auth-Token' => $creds['authToken'],
        'X-User-Id' => $creds['userId']
    );
    
    $commands = curlUrl('/api/v1/commands.list', array(), $headers);
    
    $output = json_decode($commands, true);
    
}

if(!empty($output)) {
    print '<pre>' . print_r($output, true) . '</pre>';
}


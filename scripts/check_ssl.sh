#!/bin/bash

set -e
set -x

function sendMail(){
    to=$1
    subject=$2
    body=$3

    curl -s --user 'api:key-d94da75c8c7955e514b7af06362ae929' \
        https://api.mailgun.net/v3/coalmarch.com/messages \
        -F from='Development <devel@coalmarch.com>' \
        -F to="$to" \
        -F subject="$subject" \
        -F text="$body"
}


function checkSSL() {
    domain=$1

    r=$(openssl s_client -connect $domain:443 2>/dev/null | openssl x509 -noout -dates)
    return $r
}

domain=$1

dates=$(checkSSL $domain)

if [[ $dates == "*unable to load*" ]]; then
    echo 'No SSL cert'
    exit 0
fi

echo $dates

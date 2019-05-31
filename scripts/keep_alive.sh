#!/usr/bin/env bash

set -e;

if [ -z "$1" ]
then
        ROOT=$(drush ev "print DRUPAL_ROOT");
        SITE=${ROOT##*/}
else
        SITE=$1
fi;



if [ -z "$2" ]
then
    ENV="dev"
else
    ENV=$2
fi;

get_date() {
    date --utc --date="$1" +"%Y-%m-%d %H:%M:%S"
}

timestamp=($(terminus env:code-log $SITE.$ENV --field=Timestamp))
timestamp=$(echo $timestamp);
is_old=$(timeStampCompare.php "$timestamp" "<" "7 days ago");

if [[ $is_old = "1" ]]
then
    terminus connection:set $SITE.$ENV sftp
    echo 'file_put_contents("keepAlive.txt", time());' | terminus drush $SITE.$ENV -- php
    terminus env:commit $SITE.$ENV --message="Keep Alive script"
    terminus connection:set $SITE.$ENV git
else
    echo "not old enough"
fi

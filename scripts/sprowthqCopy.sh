#!/usr/bin/env bash
set -e

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null 2>&1 && pwd )";
cd $DIR/..;
DIR=$(pwd);

DIRS="/var/lib/mysql
/var/www
/etc/letsencrypt
/etc/php
/etc/nginx
"

echo '#######################################'
echo '         copying directories...'
echo '#######################################'
echo

for dir in $DIRS;
do
  echo "Copying $dir to ${DIR}${dir}";
  mkdir -p ${DIR}${dir};
  rsync -vr $dir ${DIR}${dir}/../
done

echo
echo '#######################################'
echo '               DONE!!!'
echo '#######################################'
echo
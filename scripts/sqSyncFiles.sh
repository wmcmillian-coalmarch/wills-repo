#!/usr/bin/env bash

set -e

source ${HOME}/bin/getSiteEnv.sh;

SITEDIR=~/$PROJECTDIR/$SITE;

cd $SITEDIR;

if [ -f ~/Sites/$SITE/core/lib/Drupal.php ]
then
    DRUPALV=8
else
    DRUPALV=7
fi;

if [ -z "${MYSQL_USER}" ]; then
    MYSQL_USER="root"
fi
if [ -z "${MYSQL_PASSWORD}" ]; then
    MYSQL_PASSWORD="root"
fi
if [ -z "${MYSQL_HOST}" ]; then
    MYSQL_HOST="localhost"
fi

chmod 775 $SITEDIR/sites/default;

cd $SITEDIR/sites/default;

mkdir -p files;

chmod -R 777 files;

rsync --rsync-path='sudo -u www rsync' -rvlhiW --info=progress2 --copy-unsafe-links --size-only --ipv4 --progress sprowthq:/var/www/${SITE}-${ENV}/sites/default/files/ files

chmod -R 777 files;
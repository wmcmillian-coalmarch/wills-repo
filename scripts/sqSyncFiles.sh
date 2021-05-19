#!/usr/bin/env bash
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null 2>&1 && pwd )";
set -e

source ${HOME}/bin/getSiteEnv.sh;
source $DIR/isSprowt3.sh;

SITEDIR=~/$PROJECTDIR/$SITE;

if [ $ISSPROWT3 = "1" ]; then
  SITEDIR=~/$PROJECTDIR/sprowt3-core;
  FILEPARENTDIR=sites/$SITE;
  SERVERFILEDIR=/var/www/sprowt3-core/sites/$SITE/files;
else
  FILEPARENTDIR=sites/default;
  SERVERFILEDIR=/var/www/$SITE-$ENV/sites/default/files;
fi


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

chmod 755 $SITEDIR/$FILEPARENTDIR;

cd $SITEDIR/$FILEPARENTDIR;

mkdir -p files;

chmod -R 777 files;

rsync --rsync-path='sudo -u www rsync' -rvlhiW --info=progress2 --copy-unsafe-links --size-only --ipv4 --progress sprowthq:$SERVERFILEDIR/ files

chmod -R 777 files;
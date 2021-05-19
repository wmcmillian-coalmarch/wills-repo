#!/usr/bin/env bash
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null 2>&1 && pwd )";
set -e

source ${HOME}/bin/getSiteEnv.sh;
source $DIR/isSprowt3.sh;

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

local_settings=~/Sites/${SITE}/sites/default/settings.local.php;
if [ ! -f $local_settings ]; then
    chmod 775 ~/Sites/$SITE/sites/default
    createLocalDrupalSettings.sh $SITE;
fi
chmod 775 ~/Sites/$SITE/sites/default

DRUSH="/home/www/bin/drush";

if [ $DRUPALV = "7" ]; then
    CLEARCACHECMD="cd /var/www/${SITE}-${ENV} && $DRUSH cc all"
else
    CLEARCACHECMD="cd /var/www/${SITE}-${ENV} && $DRUSH cr"
fi
FILENAME="${SITE}-${ENV}-backup.sql"
SERVERFILE="/tmp/${FILENAME}";
BACKUPCMD="backupLocal.sh $SITE $ENV"

echo "Exporting remote database..."

ssh sprowthq "sudo -u www -- sh -c '${CLEARCACHECMD}'"
ssh sprowthq "sudo -u www -- sh -c '${BACKUPCMD}'"
ssh sprowthq "sudo chmod 777 ${SERVERFILE}.gz"
scp sprowthq:${SERVERFILE}.gz ./${FILENAME}

$LOCALDRUSH sql-create -y;

pv ./${FILENAME} | gunzip | $($LOCALDRUSH sql-connect);
if [ $DRUPALV = "7" ]; then
    $LOCALDRUSH vset cache 0;drush vset preprocess_css 0;drush vset preprocess_js 0;
fi
if [ $DRUPALV = "7" ]; then
    $LOCALDRUSH cc all
else
    $LOCALDRUSH cr
fi
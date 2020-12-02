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

if [ $DRUPALV = "8" ]
then
    local_settings=~/Sites/${SITE}/sites/default/settings.local.php;
    if [ ! -f $local_settings ]; then
        chmod 775 ~/Sites/$SITE/sites/default
        createLocalDrupalSettings.sh $SITE;
    fi
    chmod 775 ~/Sites/$SITE/sites/default
else
    if [ ! -f ~/Sites/$SITE/sites/default/settings.php ]
    then
        DB=${SITE//-/_}
        echo "No settings.php. creating..."
        drush si minimal --db-url=mysql://$MYSQL_USER:$MYSQL_PASSWORD@$MYSQL_HOST/$DB -y > /dev/null 2>&1 & spinner $! || true
        chmod 775 ~/Sites/$SITE/sites/default
    fi;
fi;

if [ $DRUPALV = "7" ]; then
    CLEARCACHECMD="cd /var/www/${SITE}-${ENV} && drush cc all"
else
    CLEARCACHECMD="cd /var/www/${SITE}-${ENV} && drush cr"
fi
FILENAME="${SITE}-${ENV}-backup.sql"
SERVERFILE="/tmp/${FILENAME}";
BACKUPCMD="cd /var/www/${SITE}-${ENV} && drush sql:dump --result-file=${SERVERFILE} --gzip"

echo "Exporting remote database..."

ssh sprowthq "sudo -u www -- sh -c '${CLEARCACHECMD}'"
ssh sprowthq "sudo -u www -- sh -c '${BACKUPCMD}'"
ssh sprowthq "sudo chmod 777 ${SERVERFILE}.gz"
scp sprowthq:${SERVERFILE}.gz ./${FILENAME}

drush sql-create -y;

pv ./${FILENAME} | gunzip | $(drush sql-connect);
if [ $DRUPALV = "7" ]; then
    drush vset cache 0;drush vset preprocess_css 0;drush vset preprocess_js 0;
fi
if [ $DRUPALV = "7" ]; then
    drush cc all
else
    drush cr
fi
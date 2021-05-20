#!/usr/bin/env bash
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null 2>&1 && pwd )";
set -e

source ${HOME}/bin/getSiteEnv.sh;
source $DIR/isSprowt3.sh;

if [ $ISSPROWT3 = "1" ]; then
  SITEDIR=~/$PROJECTDIR/sprowt3-core;
else
  SITEDIR=~/$PROJECTDIR/$SITE;
fi

if [ $ISSPROWT3 = "1" ]; then
  SITESETTINGSDIR=${SITEDIR}/sites/${SITE};
else
  SITESETTINGSDIR=${SITEDIR}/sites/default;
fi

cd $SITEDIR;

if [ -f ${SITEDIR}/core/lib/Drupal.php ]
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

local_settings=${SITESETTINGSDIR}/settings.local.php;
if [ ! -f $local_settings ]; then
    chmod 755 ${SITESETTINGSDIR}
    createLocalDrupalSettings.sh $SITE;
fi
chmod 755 ${SITESETTINGSDIR}

DRUSH="/home/www/bin/drush";

if [ $DRUPALV = "7" ]; then
    CLEARCACHECMD="cd /var/www/${SITE}-${ENV} && $DRUSH cc all"
else
  if [ $ISSPROWT3 = "1" ]; then
    CLEARCACHECMD="cd /var/www/sprowt3-core && $DRUSH @${SITE}-${ENV} cr"
  else
    CLEARCACHECMD="cd /var/www/${SITE}-${ENV} && $DRUSH cr"
  fi
fi
FILENAME="${SITE}-${ENV}-backup.sql"
SERVERFILE="/tmp/${FILENAME}";
BACKUPCMD="/home/www/bin/backupLocal.sh $SITE $ENV"

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
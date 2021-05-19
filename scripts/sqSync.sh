#!/usr/bin/env bash
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null 2>&1 && pwd )";
set -e

source ${HOME}/bin/getSiteEnv.sh;
source $DIR/isSprowt3.sh;

$DIR/sqSyncCode.sh $SITE $ENV;
$DIR/sqSyncDB.sh $SITE $ENV;
$DIR/sqSyncFiles.sh $SITE $ENV;

if [ $ISSPROWT3 = "1" ]; then
  cd ~/${PROJECTDIR}/sprowt3-core;
  echo "Site synced"
  echo "To use drush run 'drush @$SITE'"
else
  cd ~/${PROJECTDIR}/${SITE};
  echo "Site synced"
fi
#!/usr/bin/env bash

set -e

source ${HOME}/bin/getSiteEnv.sh;

DRUSH="/home/www/bin/drush";
DRUSHARGS="";
STARTDRUSH=false;
for arg in "$@"; do
  if [ "$arg" = '--' ]; then
    STARTDRUSH=true;
  elif [ $STARTDRUSH ]; then
      DRUSHARGS=" $arg";
  fi
done

DRUSHCMD="cd /var/www/${SITE}-${ENV} && $DRUSH $DRUSHARGS"
ssh sprowthq "sudo -u www -- sh -c '${DRUSHCMD}'"
#!/usr/bin/env bash

set -e

source ${HOME}/bin/getSiteEnv.sh;

DRUSH="/home/www/bin/drush";
DRUSHARGS="";
STARTDRUSH="false";
for arg in "$@"; do
  if [ "$arg" = '--' ]; then
    STARTDRUSH="true";
  elif [ $STARTDRUSH = "true" ]; then
    pattern='\s'
    if [[ $arg =~ $pattern ]]; then
      arg="\"$arg\"";
    fi
      DRUSHARGS="$DRUSHARGS $arg";
  fi
done

DRUSHCMD="cd /var/www/${SITE}-${ENV} && $DRUSH $DRUSHARGS"
#echo $DRUSHCMD;
ssh sprowthq "sudo -u www -- sh -c '${DRUSHCMD}'"
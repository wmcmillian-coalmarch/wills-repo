#!/usr/bin/env bash
source ${HOME}/bin/getSiteEnv.sh;

CMD="/home/www/bin/isSprowt3.sh $SITE $ENV";

ISSPROWT3=$(ssh sprowthq "sudo -u www -- sh -c '${CMD}'")
LOCALDRUSH=$(which drush);
if [ $ISSPROWT3 = "1" ]; then
  LOCALDRUSH="$LOCALDRUSH @$SITE";
fi
#!/usr/bin/env bash
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null 2>&1 && pwd )";
source ${HOME}/bin/getSiteEnv.sh;

ISSPROWT3=$($DIR/isSprowt3.sh $SITE $ENV);
if [ $ISSPROWT3 = "1" ]; then
  echo "$SITE $ENV is sprowt3";
else
  echo "$SITE $ENV is not sprowt3";
fi

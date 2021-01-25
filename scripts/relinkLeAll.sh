#!/usr/bin/env bash
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null 2>&1 && pwd )";
SITES=$(find /etc/letsencrypt/archive/* -type d -printf '%p\n');
for site in $SITES;
do
  $DIR/relinkLe.sh ${site##*/};
done

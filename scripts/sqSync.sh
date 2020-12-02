#!/usr/bin/env bash

set -e

source ${HOME}/bin/getSiteEnv.sh;

sqSyncCode.sh $SITE $ENV;
sqSyncDB.sh $SITE $ENV;
sqSyncFiles.sh $SITE $ENV;

cd ~/${PROJECTDIR}/${SITE};

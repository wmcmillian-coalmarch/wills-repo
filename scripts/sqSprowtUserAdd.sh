#!/usr/bin/env bash

set -e
CONFIG=$HOME/.sprowtuser;
if [ ! -f $CONFIG ]; then
  echo 'No config file found! Please enter your username and password';
  echo 'Username:';
  read USERNAME;
  echo 'Password:';
  read PASSWORD;
  echo "#Coalmarch sprowtuser config" > $CONFIG;
  echo "" >> $CONFIG;
  echo "USERNAME=\"$USERNAME\"" >> $CONFIG;
  echo "PASSWORD=\"$PASSWORD\"" >> $CONFIG;
else
  source $CONFIG;
fi

source ${HOME}/bin/getSiteEnv.sh;

${HOME}/bin/sqDrush.sh $SITE $ENV -- cm-add --name=$USERNAME --password="$PASSWORD"
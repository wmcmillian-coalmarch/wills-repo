#!/usr/bin/env bash

set -e

source ${HOME}/bin/getSiteEnv.sh;

SITEDIR=$PROJECTDIR/$SITE;

if [ ! -d $SITEDIR ]; then
  git clone git@github.com:coalmarch-development/${SITE}.git
else
  cd $SITEDIR && git pull
fi
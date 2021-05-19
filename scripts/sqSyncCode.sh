#!/usr/bin/env bash
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null 2>&1 && pwd )";
set -e

source ${HOME}/bin/getSiteEnv.sh;
source $DIR/isSprowt3.sh;

if [ $ISSPROWT3 = "1" ]; then
  COREDIR=~/$PROJECTDIR/sprowt-core;
  SITEDIR=~/$PROJECTDIR/sprowt-core/sites/${SITE};
  SITELN=~/$PROJECTDIR/sprowt-core/sites/${SITE}.test;
  echo "Syncing sprowt3 core code";
  if [ ! -d $COREDIR ]; then
    git clone git@github.com:coalmarch-development/sprowt3.git $COREDIR;
  else
    cd $COREDIR && git pull;
  fi
  echo "Syncing site code";
  if [ ! -d $SITEDIR ]; then
    git clone git@github.com:coalmarch-development/${SITE}.git $SITEDIR
  else
    cd $SITEDIR && git pull;
  fi
  if [ ! -e $SITELN ]; then
    echo "Linking site alias"
    ln -s $SITEDIR $SITELN;
  fi
  $DIR/addSprowt3Aliases --site=$SITE;
else
  SITEDIR=~/$PROJECTDIR/$SITE;

  echo "Syncing code";

  if [ ! -d $SITEDIR ]; then
    git clone git@github.com:coalmarch-development/${SITE}.git $SITEDIR
  else
    cd $SITEDIR && git pull
  fi
fi
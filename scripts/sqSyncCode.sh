#!/usr/bin/env bash
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null 2>&1 && pwd )";
set -e

source ${HOME}/bin/getSiteEnv.sh;
source $DIR/isSprowt3.sh;

if [ $ISSPROWT3 = "1" ]; then
  COREDIR=~/$PROJECTDIR/sprowt3-core;
  SITEDIR=~/$PROJECTDIR/sprowt3-core/sites/${SITE};
  SITELN=~/$PROJECTDIR/sprowt3-core/sites/${SITE}.test;
  echo "Syncing sprowt3 core code";
  if [ ! -d $COREDIR ]; then
    git clone git@github.com:coalmarch-development/sprowt3.git $COREDIR;
    echo "Running composer install. Have to run twice because of a patching error..."
    cd $COREDIR && composer install || echo 'Running composer again' && git checkout . && git clean -df && composer install
  else
    cd $COREDIR && git pull && composer install;
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
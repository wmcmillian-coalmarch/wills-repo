#!/usr/bin/env bash

source ${HOME}/bin/getSiteEnv.sh;

terminus backup:create $SITE.$ENV;

if [ ! -d ~/Sites/$SITE ]; then
    siteget $SITE $ENV;
else
    prefresh $SITE $ENV;
fi

cd ~/Sites/$SITE;


if [ ! $ENV = 'dev' ]; then
    terminus backup:create $SITE.dev;
fi

terminus upstream:updates:apply $SITE.dev --updatedb

drush cex -y;
needsPush=0;
if [[ `git status --porcelain` ]]; then
    needsPush=1;
    git add .
    git commit -m "Config export"
fi

git pull --no-edit

drush updatedb -y;

if [ $needsPush = "1" ]; then
    git push;
fi

pdrush cim --partial -y;
pdrush cr;

if [ $ENV = 'test' ] || [ $ENV = 'live' ]; then
    terminus env:deploy "$SITE.test" --note="Upstream Updates" --cc --updatedb;
    pdrush test cim --partial -y;
    pdrush test cr
fi

if [ $ENV = 'live' ]; then
    terminus env:deploy "$SITE.live" --note="Upstream Updates" --cc --updatedb;
    pdrush live cim --partial -y;
    pdrush live cr
fi
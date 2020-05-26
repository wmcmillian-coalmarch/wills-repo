#! /usr/bin/env bash

set -e

source ${HOME}/bin/getSiteEnv.sh;

echo "Pulling down live site, $SITE..."

cd ~/.drush

notfound="Not found:.*"
if [[ $(drush sa @pantheon.$SITE.live 2>&1) =~ $notfound ]]
then
    echo "Alias @pantheon.$SITE.live not found!"
    echo "adding you to the site in pantheon"
    email=$(terminus auth:whoami);
    terminus site:team:add $SITE $email &&
    echo "refreshing site aliases..."
    terminus aliases
fi;

if [ -d ~/Sites/$SITE ]; then
    echo "Directory for $SITE already exists. Pulling"
    cd ~/Sites/$SITE
    chmod 775 ~/Sites/$SITE/sites/default
    git pull
else
    echo "Cloning $SITE" &&
    tclone $SITE &&
    cd ~/Sites/$SITE
    chmod 775 ~/Sites/$SITE/sites/default
fi
rewritebase $SITE
DRUPALV=8
terminus-ssp $SITE dev &&
drushfiles $SITE dev &&
cd ~/Sites/$SITE

echo "Backing up site on Pantheon..."
terminus backup:create $SITE.dev

echo "Pulling in upstream..."

git pull git@bitbucket.org:coalmarch/call-scripts-upstream.git --no-edit
drush cr;

echo "Running config import locally..."

drush cim -y;


echo "Running database update locally..."

drush updatedb -y

echo "Pushing up and running updates on Pantheon"

git push &&
sleep 10 &&
pdrush cr &&
pdrush dev cim --y &&
pdrush dev updatedb -y &&
pdrush dev cr

echo "Done updating site!"
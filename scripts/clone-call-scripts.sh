#!/usr/bin/env bash

set -e

spinner()
{
    local pid=$1
    local delay=0.75
    local spinstr='|/-\'
    while [ "$(ps a | awk '{print $1}' | grep $pid)" ]; do
        local temp=${spinstr#?}
        printf " [%c]  " "$spinstr"
        local spinstr=$temp${spinstr%"$temp"}
        sleep $delay
        printf "\b\b\b\b\b\b"
    done
    printf "    \b\b\b\b"
}

SITE="$1";

echo "Creating site...";
terror=".*error.*"
if [[ $(terminus site:info $SITE --field=id 2>&1) =~ $terror ]]; then
    terminus site:create $SITE $SITE 8a129104-9d37-4082-aaf8-e6f31154644e --org=08b99cd5-81a3-4b67-acc1-d5dba50390f8 -y;
else
    echo "Site exists already on pantheon!"
fi

terminus tag:add $SITE coalmarch "Call Scripts Beta"

CALLSCRIPTS=~/Sites/call-scripts

echo "Fetching site...";
CLONE="tclone $SITE"

echo "refreshing site aliases..."
terminus aliases > /dev/null 2>&1 & spinner $!

notfound="Not found:.*"
if [[ $(drush sa @pantheon.$SITE.dev 2>&1) =~ $notfound ]]; then
    echo "Alias @pantheon.$SITE.dev not found!"
    echo "adding you to the site in pantheon"
    email=$(terminus auth:whoami);
    terminus site:team:add $SITE $email &&
    echo "refreshing site aliases..."
    terminus aliases > /dev/null 2>&1 & spinner $!
fi;
echo "refreshing site list..."
terminus site:list > /dev/null 2>&1 & spinner $!
if [ -d ~/Sites/$SITE ]; then
    echo "Directory for $SITE already exists. Pulling"
    cd ~/Sites/$SITE
    chmod 775 ~/Sites/$SITE/sites/default
    git pull
else
    eval $CLONE &&
    cd ~/Sites/$SITE
    chmod 775 ~/Sites/$SITE/sites/default
fi

rewritebase $SITE

cd $CALLSCRIPTS;
git pull;
cd ~/Sites/$SITE;

echo "Backing up site dev environment..."
terminus backup:create $SITE.dev

#copy code
echo "Switching site mode..."
terminus connection:set $SITE.dev git;

echo "Copying code...";
git pull;
#cp -r $CALLSCRIPTS/modules ~/Sites/$SITE/
#cp -r $CALLSCRIPTS/themes ~/Sites/$SITE/
#cp -r $CALLSCRIPTS/config ~/Sites/$SITE/

rsync -av --progress $CALLSCRIPTS/modules ~/Sites/$SITE --exclude node_modules;
rsync -av --progress $CALLSCRIPTS/themes ~/Sites/$SITE --exclude node_modules;
rsync -av --progress $CALLSCRIPTS/config ~/Sites/$SITE --exclude node_modules;

git add .
git commit -am "Copying code from call-scripts"
git push;

#wipe site
echo "Wiping site dev environment database and files...";
terminus env:wipe $SITE.dev -y;

#import db
echo "Importing database...";
MYSQL=$(terminus connection:info $SITE.dev --field="MySQL Command");
$MYSQL < $CALLSCRIPTS/backup.sql;

#upload files
echo "Copying files";
cp -r $CALLSCRIPTS/sites/default/files ~/Sites/$SITE/sites/default/;

UUID=$(terminus site:info $SITE --field=id);
cd ~/Sites/$SITE/sites/default;
while [ 1 ]
do
rsync --partial -rlvz --size-only --ipv4 --progress -e 'ssh -p 2222' ./files/* --temp-dir=../tmp/ dev.$UUID@appserver.dev.$UUID.drush.in:files/
if [ "$?" = "0" ] ; then
echo "rsync completed normally"
else
echo "Rsync failure. Backing off and retrying..."
sleep 180
fi
done

#clear cache
echo "Clearing cache..."
pdrush cr;

echo "Clone done!"

open "http://dev-$SITE.pantheonsite.io";


#!/usr/bin/env bash

set -e;

SITES="wedocreepy";

for site in $SITES;
do
    echo "Updating $site..."
    dir="$HOME/Sites/$site";
    if [ -d "$dir" ]; then
        cd $dir && git checkout master && git pull --no-edit;
    else
        tclone $site
    fi
    cd $dir &&
    prefresh $site live &&
    git pull --no-edit git@bitbucket.org:coalmarch/sprowt.git;
    drush updatedb -y;
    echo "Finished updating $site";
done
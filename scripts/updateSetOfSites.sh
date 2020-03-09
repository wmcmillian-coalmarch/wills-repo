#! /usr/bin/env bash

SITES="simplythebestpestcontrol
safespraypestcontrol
gogreenlawnservices2020
deltapest
ransfordpc
extermatrim
clancybrospestcontrol
";

for site in $SITES;
do
    echo "Updating $site ..." &&
    terminus backup:create $site.dev &&
    terminus upstream:updates:apply $site.dev --updatedb &&
    terminus drush $site.dev -- cc all
done
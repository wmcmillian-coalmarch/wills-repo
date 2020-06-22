#! /usr/bin/env bash

livesites="miller-pest-call-scripts
bel-o-call-scripts
invader-call-scripts
simply-the-best-call-scripts
preventive-pest-call-scripts
extermatrim-call-scripts"

devsites=""

for site in $devsites;
do
    ~/Sites/sandbox/scripts/callScriptsUpdate_dev_only.sh $site;
done

for site in $livesites;
do
    ~/Sites/sandbox/scripts/callScriptsUpdate.sh $site;
done
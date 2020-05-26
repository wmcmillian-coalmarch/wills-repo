#! /usr/bin/env bash

livesites="adams-pest-control-call-scripts
bel-o-call-scripts
envirocon-call-scripts
extermatrim-call-scripts
invader-call-scripts
preventive-pest-call-scripts
simply-the-best-call-scripts"

devsites="coalmarch-demo-call-scripts
coalmarch-demo-call-scripts-integration
dauv-call-scripts
dauv-call-scripts-integration"

for site in $devsites;
do
    ~/Sites/sandbox/scripts/callScriptsUpdate_dev_only.sh $site;
done

for site in $livesites;
do
    ~/Sites/sandbox/scripts/callScriptsUpdate.sh $site;
done
#! /usr/bin/env bash

set -e;

PROJECTDIR="Sites"
if [[ "${PWD}" =~ ^${HOME}/Projects ]]; then
    PROJECTDIR="Projects"
fi

source ${HOME}/bin/getSiteEnv.sh;

pbackups $SITE;
terminus upstream:updates:apply $SITE.dev --updatedb &&
terminus env:deploy "$SITE.test" --note="upstream updates" --cc --updateb;
terminus env:deploy "$SITE.live" --note="upstream updates" --cc --updateb;
#!/bin/bash

set -e

if [ -z "$1" ]
then
    if [[ ! "${PWD}" =~ ^${HOME}/$PROJECTDIR ]]; then
        echo "Not in a Project directory like ~/Sites or ~/Projects"
        exit 1;
    fi

    SITE=${PWD##*/$PROJECTDIR/}
    SITE=${SITE%%/*}
else
    SITE=$1
fi;

if [ -z "$2" ]
then
        ENV="dev"
else
        ENV=$2
fi;

if [ "$SITE" = "live" ] || [ "$SITE" = "dev" ] || [ "$SITE" = "test" ]
then
    ENV=$SITE
    if [[ ! "${PWD}" =~ ^${HOME}/$PROJECTDIR ]]; then
        echo "Not in a Project directory like ~/Sites or ~/Projects"
        exit 1;
    fi

    SITE=${PWD##*/$PROJECTDIR/}
    SITE=${SITE%%/*}
fi;


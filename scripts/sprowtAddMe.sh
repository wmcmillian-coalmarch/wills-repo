#!/usr/bin/env bash
source ${HOME}/bin/getSiteEnv.sh;

sprowt-user-add $SITE live &&
sprowt-user-add $SITE dev &&
sprowt-user-add $SITE local
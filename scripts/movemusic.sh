#!/usr/bin/env bash

set -e

if [ -z "$1" ]
then
        ROOT=${PWD}
else
        ROOT=$1
fi;


function loopthrumv {
    for f in $1/*; do
        if [ -d "$f" ]; then
            loopthrumv "$f";
        elif [ -f "$f" ]; then
            NAME=${f##*/}
            DIR=${f%/$NAME}
            DIRNAME=${DIR##*/}
            if [ -f "$DIR"/../"$DIRNAME"\ -\ "$NAME" ]; then
                mv "$f" "$DIR"/../"$DIRNAME"\ -\ "$NAME".1
            else
                mv "$f" "$DIR"/../"$DIRNAME"\ -\ "$NAME"
            fi
        fi
    done
}

function loopthrurm {
    for f in $1/*; do
        if [ -d "$f" ]; then
            if [ `find $f -prune -empty` ]; then
                rmdir "$f";
            else
                loopthrurm "$f";
            fi
        fi
    done
}

loopthrumv $ROOT;
loopthrurm $ROOT;
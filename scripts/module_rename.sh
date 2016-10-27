#! /bin/bash

set -e

if [ -z "$1" ]; then
    echo "ERROR! No module named!";
    exit 1
fi

if [ -z "$2" ]; then
    echo "ERROR! No new name!";
    exit 1
fi

MODULE=$1
NEWNAME=$2

if [ ! -d $MODULE ]; then
    echo "You must be in the directory with a module.";
    exit 1
fi

if [ ! -f "$MODULE/$MODULE.module" ]; then
    echo "$MODULE not a drupal module";
    exit 1
fi

cd $MODULE;

echo "renaming files";
find . -name "$MODULE*" -print | xargs rename "s/$MODULE/$NEWNAME/";

echo "string replace $MODULE with $NEWNAME";
find . -type f -print | xargs sed -i "s/$MODULE/$NEWNAME/";

echo "renaming directory";
cd ..
mv $MODULE $NEWNAME;

echo "DONE!"
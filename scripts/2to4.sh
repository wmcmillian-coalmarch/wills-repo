#!/usr/bin/env bash
NOFILE=false;
if [ -z "$1" ]; then
  NOFILE=true;
fi
if [ ! -f $1 ]; then
  NOFILE=true;
fi
if [ $NOFILE = true ]; then
  echo "No file specified!";
  exit 1
fi

sed -i 's/^\( *\)\(.\)/\1\1\2/g' $1;

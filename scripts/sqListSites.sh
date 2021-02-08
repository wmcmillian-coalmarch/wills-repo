#!/usr/bin/env bash

set -e

FINDLIVECMD="find ~/Sites/*-live -maxdepth 0 -printf '%f##'"
FINDDEVCMD="find ~/Sites/*-dev -maxdepth 0 -printf '%f##'"

SITES=();
LIVESITES=$(ssh sprowthq "sudo -u www -- sh -c '${FINDLIVECMD}'" | sed 's/##/\n/g');
DEVSITES=$(ssh sprowthq "sudo -u www -- sh -c '${FINDDEVCMD}'" | sed 's/##/\n/g')
for site in $LIVESITES; do
  newsite=$(echo $site | sed 's/-live$//');
  SITES+=("$newsite");
done
for site in $DEVSITES; do
  newsite=$(echo $site | sed 's/-dev$//');
  pattern="^$newsite$"
  if printf '%s\n' "${SITES[@]}" | grep -q -P "$pattern"; then
    true
  else
    SITES+=("$newsite");
  fi
done
IFS=$'\n' sorted=($(sort <<<"${SITES[*]}"))
unset IFS
printf "%s\n" "${sorted[@]}"
#!/bin/bash
set -e

if [ -z "$1" ]
then
    location="."
else
    location="$1"
fi;

menus=(
    "main-menu:main-menu_link_export.json"
    "menu-mobile-menu:menu-mobile-menu_link_export.json"
    "menu-mobile-callout:menu-mobile-callout_link_export.json"
    "menu-utility-menu:menu-utility-menu_link_export.json"
    "menu-mobile-footer:menu-mobile-footer_link_export.json"
    "menu-mobile-utility:menu-mobile-utility_link_export.json"
)


for m in "${menus[@]}" ; do
    name="${m%%:*}"
    file="${m##*:}"
    drush sme $name --file-name=$file --location=$location
done
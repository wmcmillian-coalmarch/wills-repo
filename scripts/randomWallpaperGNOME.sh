#! /usr/bin/env  bash
set -e
DIR=/home/willmcmillian/Pictures/Wallpapers;
WP=$(find $DIR/ -type f | shuf -n 1);

gsettings set org.gnome.desktop.background picture-uri file://$WP &&
gsettings set org.gnome.desktop.screensaver picture-uri file://$WP
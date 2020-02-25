#! /usr/bin/env  bash
set -e
user=$(id --real --user);
PID=$(pgrep -u $user gnome-session)
PID=$(echo $PID | cut -d" " -f1)
export DBUS_SESSION_BUS_ADDRESS=$(grep -z DBUS_SESSION_BUS_ADDRESS /proc/$PID/environ|cut -d= -f2-);

DIR=/home/willmcmillian/Pictures/Wallpapers;
WP=$(find $DIR/ -type f | shuf -n 1);

gsettings set org.gnome.desktop.background picture-uri file://$WP &&
gsettings set org.gnome.desktop.screensaver picture-uri file://$WP
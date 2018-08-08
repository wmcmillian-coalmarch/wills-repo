#! /bin/bash

BG_DIR=/usr/share/slick-backgrounds
mkdir -p $BG_DIR;

CONFIG_FILE=/etc/lightdm/slick-greeter.conf
FILE=$(find /home/will/.config/variety/Downloaded/Unsplash/* -type f | shuf -n 1);
cp $FILE $BG_DIR/;
find $BG_DIR -mtime +10 -type f -delete;
FILENAME=${FILE##*/};

TARGET_KEY="background"
REPLACEMENT_VALUE="$BG_DIR/$FILENAME";

chmod 775 $REPLACEMENT_VALUE;

cp $CONFIG_FILE $CONFIG_FILE-bak;
sed -i "s|\($TARGET_KEY *= *\).*|\1$REPLACEMENT_VALUE|" $CONFIG_FILE
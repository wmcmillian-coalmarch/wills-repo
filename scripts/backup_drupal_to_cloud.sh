#!/usr/bin/env bash

SITE=$1;

if [ -z $SITE ]; then
    echo 'Site must be set!';
    exit 1;
fi

if [ -d "/var/www/$SITE" ]; then
    SITEDIR="/var/www/$SITE";
elif [ -d "$HOME/Sites/$SITE" ]; then
    SITEDIR="$HOME/Sites/$SITE";
else
    SITEDIR="$HOME/Projects/$SITE";
fi

# Specify the destination folder.
TARGET=$HOME/exports/$SITE;
# Specify the source folder.
SOURCE="$SITEDIR"

if [ -d $SOURCE ]; then
    cd $SOURCE || exit 1;
else
    echo "Site doesn't exist!"
    exit 1;
fi

mkdir -p $TARGET;
touch $TARGET/$SITE-db.sql.gz;
touch $TARGET/$SITE-code.tar.gz
touch $TARGET/$SITE-files.tar.gz
rm $TARGET/$SITE*;


if [ -f $SOURCE/core/lib/Drupal.php ]
then
    DRUPALV=8
else
    DRUPALV=7
fi;

echo -n "clearing cache..."
if [ $DRUPALV = "8" ]; then
    drush cr all > /dev/null 2>&1;
else
    drush cc all > /dev/null 2>&1;
fi
echo "cache cleared";

CREDSTR=$(drush sql-connect);
CREDSTR=${CREDSTR#mysql};
CREDSTR=${CREDSTR/--database=/};
CREDS="";

for OPT in $CREDSTR; do
    if [[ $OPT =~ --.* ]]
    then
        CREDS="$CREDS $OPT";
    else
        DB=$OPT;
    fi
done

CREDS="$CREDS $DB";

echo -n "exporting database..."
mysqldump $CREDS | gzip > $TARGET/"$SITE-db.sql.gz" &&
echo "exported database to $TARGET/$SITE-db.sql.gz";

echo -n "exporting code..."
tar -czf $TARGET/$SITE-code.tar.gz --exclude=sites/default/files* --exclude=.git* --exclude=.idea* . &&
echo "exported code to $TARGET/$SITE-code.tar.gz";

echo -n "exporting files..."
cd $SOURCE/sites/default/files
tar -czf $TARGET/$SITE-files.tar.gz . &&
echo "exported files to $TARGET/$SITE-files.tar.gz";

time=`date +'%F %T %Z'`
bucket="coalmarch_nearline_backups"
location="$bucket/$SITE"
array=( "$SITE-db.sql.gz" "$SITE-code.tar.gz" "$SITE-files.tar.gz" );

for file in "${array[@]}"; do
    gsutil -h "x-goog-meta-created-time:$time" cp $TARGET/$file gs://$location/latest/$file;
    # Save Daily
    if [ $(date +"%k") -eq 0 ]; then
        gsutil -h "x-goog-meta-created-time:$time" cp $TARGET/$file gs://$location/daily/$(date +"%u")-$file;
    fi

    # Save Weekly
    if [ $(date +"%u") -eq 1 ]; then
        gsutil -h "x-goog-meta-created-time:$time" cp $TARGET/$file gs://$location/weekly/$(date +"%U")-$file;
    fi

    # Save Monthly
    if [ $(date +"%e") -eq 1 ]; then
        gsutil -h "x-goog-meta-created-time:$time" cp $TARGET/$file gs://$location/monthly/$(date +"%m")-$file;
    fi
done



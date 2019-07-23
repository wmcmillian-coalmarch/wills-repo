#!/bin/bash

if [ -z "$1" ]; then
    echo "Error! Must supply database name" 1>&2
    exit 1;
fi

if [ -z "$DATABASE_HOST" ]; then
    echo "Error! Must export database host into variable DATABASE_HOST" 1>&2
    exit 1;
fi

if [ -z "$DATABASE_USER" ]; then
    echo "Error! Must export database host into variable DATABASE_USER" 1>&2
    exit 1;
fi

if [ -z "$DATABASE_PASSWORD" ]; then
    echo "Error! Must export database host into variable DATABASE_PASSWORD" 1>&2
    exit 1;
fi

################################################################
##
##   MySQL Database Backup Script
##   Written By: Rahul Kumar
##   URL: https://tecadmin.net/bash-script-mysql-database-backup/
##   Last Update: Jan 05, 2019
##
################################################################

export PATH=/bin:/usr/bin:/usr/local/bin
TODAY=`date +"%d%b%Y"`

################################################################
################## Update below values  ########################

DATABASE_NAME=$1
DB_BACKUP_PATH="$HOME/backup/$DATABASE_NAME"
mkdir -p $DB_BACKUP_PATH;
MYSQL_HOST=$DATABASE_HOST
MYSQL_PORT='3306'
MYSQL_USER=$DATABASE_USER
MYSQL_PASSWORD=$DATABASE_PASSWORD

BACKUP_RETAIN_DAYS=30   ## Number of days to keep local backup copy

#################################################################

mkdir -p ${DB_BACKUP_PATH}/${TODAY}
echo "Backup started for database - ${DATABASE_NAME}"


mysqldump -h ${MYSQL_HOST} \
   -P ${MYSQL_PORT} \
   -u ${MYSQL_USER} \
   -p${MYSQL_PASSWORD} \
   ${DATABASE_NAME} | gzip > ${DB_BACKUP_PATH}/${TODAY}/${DATABASE_NAME}-${TODAY}.sql.gz

if [ $? -eq 0 ]; then
  echo "Database backup successfully completed"
else
  echo "Error found during backup"
  exit 1
fi


##### Remove backups older than {BACKUP_RETAIN_DAYS} days  #####

DBDELDATE=`date +"%d%b%Y" --date="${BACKUP_RETAIN_DAYS} days ago"`

if [ ! -z ${DB_BACKUP_PATH} ]; then
      cd ${DB_BACKUP_PATH}
      if [ ! -z ${DBDELDATE} ] && [ -d ${DBDELDATE} ]; then
            rm -rf ${DBDELDATE}
      fi
fi

### End of script ####
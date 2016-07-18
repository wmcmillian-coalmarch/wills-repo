#!/bin/bash

ssh intelligrated-staging "./int_staging_sync.sh" &&
scp intelligrated-staging:staging_dump.sql.gz . &&
drush sql-create -y;
pv staging_dump.sql.gz | gunzip | mysql -uroot -proot intelligrated
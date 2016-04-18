#!/bin/bash

ssh intelligrated "./live_db_get.sh" &&
scp intelligrated:live_dump.sql.gz . &&
drush sql-create -y;
pv live_dump.sql.gz | gunzip | mysql -uroot -proot intelligrated
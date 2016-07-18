#!/bin/bash

cd sites/default/files;

rsync -razv intelligrated-staging:/var/www/staging2.intelligrated.com/sites/default/files/ ./

chmod -R 777 .;

cd -;
#!/bin/bash

cd sites/default/files;

rsync -razv intelligrated:/var/www/intelligrated2.com/sites/default/files/ ./

chmod -R 777 .;

cd -;
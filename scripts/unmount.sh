#! /usr/bin/env bash

files="/etc/nginx/nginx.conf
/etc/php/5.6/cli/php.ini
/etc/php/5.6/fpm/php.ini
/etc/php/5.6/fpm/php-fpm.conf
/etc/php/5.6/fpm/pool.d/www.conf
/etc/php/7.3/cli/php.ini
/etc/php/7.3/fpm/php.ini
/etc/php/7.3/fpm/php-fpm.conf
/etc/php/7.3/fpm/pool.d/www.conf
"
for file in $files;
do
  backup="${file}-bak";
  if [ -h $file ]; then
    rm $file;
    if [ -f $backup ]; then
      mv $backup $file;
    fi
  fi
done

dirs="/etc/nginx/conf.d
/etc/letsencrypt
/var/www
/var/lib/mysql
";

for dir in $dirs;
do
  backup="${dir}-bak";
  umount $dir;
  rmdir $dir;
  if [ -d $backup ]; then
    mv $backup $dir;
  fi
done

umount /mnt/sprowt-data01
#! /usr/bin/env bash
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null 2>&1 && pwd )";
sprowtData="$DIR/..";

dirs="/etc/nginx/conf.d
/etc/letsencrypt
/var/www
/var/lib/mysql
";

for dir in $dirs;
do
  backup="${dir}-bak";
  if [ -d $dir ] && [ "$(ls -A $dir)" ]; then
    mv $dir $backup;
  fi

  if [ ! -d $dir ]; then
    mkdir -p $dir;
  fi

  mount --bind ${sprowtData}${dir} $dir;
done

$DIR/relinkLeAll.sh &&
chown -R mysql:mysql /var/lib/mysql &&
chown -R www:www /var/www

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
  if [ -f $file ]; then
    mv $file "${file}-bak";
  elif [ -h $file ]; then
    rm $file;
  fi

  ln -s ${sprowtData}${file} $file;
done
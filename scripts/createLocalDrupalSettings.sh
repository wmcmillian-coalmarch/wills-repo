#! /usr/bin/env bash
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null 2>&1 && pwd )";
source $DIR/isSprowt3.sh;

if [ -z "${MYSQL_USER}" ]; then
    MYSQL_USER="root"
fi
if [ -z "${MYSQL_PASSWORD}" ]; then
    MYSQL_PASSWORD="root"
fi
if [ -z "${MYSQL_HOST}" ]; then
    MYSQL_HOST="localhost"
fi

spinner()
{
    local pid=$1
    local delay=0.75
    local spinstr='|/-\'
    tput civis
    while [ "$(ps a | awk '{print $1}' | grep $pid)" ]; do
        local temp=${spinstr#?}
        printf " [%c]  " "$spinstr"
        local spinstr=$temp${spinstr%"$temp"}
        sleep $delay
        printf "\b\b\b\b\b\b"
    done
    tput cnorm
    printf "    \b\b\b\b"
}

trap 'tput cnorm' EXIT;

PROJECTDIR="Sites"
if [[ "${PWD}" =~ ^${HOME}/Projects ]]; then
    PROJECTDIR="Projects"
fi

if [ $ISSPROWT3 = "1" ]; then
  SITEDIR=~/$PROJECTDIR/sprowt3-core;
else
  SITEDIR=~/$PROJECTDIR/$SITE;
fi

if [ -z "$1" ]
then
    if [[ ! "${PWD}" =~ ^${HOME}/$PROJECTDIR ]]; then
        echo "Not in a Project directory like ~/Sites or ~/Projects"
        exit 1;
    fi

    SITE=${PWD##*/$PROJECTDIR/}
    SITE=${SITE%%/*}
else
    SITE=$1
fi;

if [ -f ${SITEDIR}/core/lib/Drupal.php ]
then
    DRUPALV=8
else
    if [ -f ${SITEDIR}/modules/system/system.module ]; then
        DRUPALV=7
    else
        echo 'A drupal site was not found.'
        exit 1;
    fi;
fi

if [ $DRUPALV = "7" ]; then
    DB=${SITE//-/_}
    default_settings_file=~/${PROJECTDIR}/${SITE}/sites/default/default.settings.php;
    settings_file=~/${PROJECTDIR}/${SITE}/sites/default/settings.php;
    local_settings=~/${PROJECTDIR}/${SITE}/sites/default/settings.local.php;
    settings_file_tmp=~/${PROJECTDIR}/${SITE}/sites/default/settings.php-tmp;
    install_log=~/${PROJECTDIR}/${SITE}/install.log;
    if [ -f $settings_file ]; then
      mv $settings_file $settings_file_tmp;
    fi
    drush si minimal --db-url=mysql://$MYSQL_USER:$MYSQL_PASSWORD@$MYSQL_HOST/$DB -y > $install_log 2>&1 & spinner $! || true;
    if [ -f $settings_file ]; then
      rm $install_log;
      chmod 775 ~/$PROJECTDIR/$SITE/sites/default
      chmod 755 $settings_file;
      if [ -f $local_settings ]; then
        rm $local_settings;
      fi
      touch $local_settings;
      chmod 755 $local_settings;
      echo '<?php' >> $local_settings;
      echo '' >> $local_settings;
            echo "" >> $local_settings;
      echo "" >> $local_settings;
      echo "/**=====================================" >> $local_settings;
      echo "*            local install" >> $local_settings;
      echo "*=====================================**/" >> $local_settings;
      echo "" >> $local_settings;
      echo "" >> $local_settings;
      diff "${default_settings_file}" "${settings_file}" | grep ">" | sed 's/^>//g' | sed 's/^\( *\)\(.\)/\1\1\2/g' >> "${local_settings}";
      if [ -f $settings_file_tmp ]; then
        mv $settings_file_tmp $settings_file;
      fi
    fi

    exit 0;
fi

if [ $ISSPROWT3 = "1" ]; then
  SITESETTINGSDIR=${SITEDIR}/sites/${SITE};
else
  SITESETTINGSDIR=${SITEDIR}/sites/default;
fi

DB=${SITE//-/_}
settings_file=${SITESETTINGSDIR}/settings.php;
default_settings_file=${SITEDIR}/sites/default/default.settings.php;
settings_file_tmp=${SITESETTINGSDIR}/settings.php-tmp;
local_settings=${SITESETTINGSDIR}/settings.local.php;
local_settings_template=${SITEDIR}/sites/example.settings.local.php;
install_log=${SITEDIR}/${SITE}-install.log;

chmod 755 ${SITESETTINGSDIR};
echo "${SITESETTINGSDIR}/settings.local.php not found. Setting up local settings file... This might take a bit...";
mv $settings_file $settings_file_tmp;
$LOCALDRUSH si minimal --db-url=mysql://$MYSQL_USER:$MYSQL_PASSWORD@$MYSQL_HOST/$DB -y > $install_log 2>&1 & spinner $! || true;
chmod 755 $install_log;
if [ -f $settings_file ]; then
    rm $install_log;
    chmod 755 ${SITESETTINGSDIR}
    chmod 755 $settings_file;
    cp -f $local_settings_template $local_settings;
    chmod 755 $local_settings;
    echo "" >> $local_settings;
    echo "" >> $local_settings;
    echo "/**=====================================" >> $local_settings;
    echo "*            local install" >> $local_settings;
    echo "*=====================================**/" >> $local_settings;
    echo "" >> $local_settings;
    echo "" >> $local_settings;
    diff "${default_settings_file}" "${settings_file}" | grep ">" | sed 's/^>//g' | sed 's/^\( *\)\(.\)/\1\1\2/g' >> "${local_settings}";
    rm -f $settings_file;
    mv -f $settings_file_tmp $settings_file;
else
    echo "Something went wrong...";
    cat $install_log;
    exit 1;
fi
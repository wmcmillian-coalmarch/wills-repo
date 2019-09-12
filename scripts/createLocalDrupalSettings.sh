#! /usr/bin/env bash
spinner()
{
    local pid=$1
    local delay=0.75
    local spinstr='|/-\'
    while [ "$(ps a | awk '{print $1}' | grep $pid)" ]; do
        local temp=${spinstr#?}
        printf " [%c]  " "$spinstr"
        local spinstr=$temp${spinstr%"$temp"}
        sleep $delay
        printf "\b\b\b\b\b\b"
    done
    printf "    \b\b\b\b"
}

if [ -z "$1" ]
then
    PROJECTDIR="Sites"
    if [[ "${PWD}" =~ ^${HOME}/Projects ]]; then
        PROJECTDIR="Projects"
    fi

    if [[ ! "${PWD}" =~ ^${HOME}/$PROJECTDIR ]]; then
        echo "Not in a Project directory like ~/Sites or ~/Projects"
        exit 1;
    fi

    SITE=${PWD##*/$PROJECTDIR/}
    SITE=${SITE%%/*}
else
    SITE=$1
fi;

if [ -f ~/${PROJECTDIR}/${SITE}/core/lib/Drupal.php ]
then
    DRUPALV=8
else
    if [ -f ~/${PROJECTDIR}/${SITE}/modules/system/system.module ]; then
        DRUPALV=7
    else
        echo 'A drupal site was not found.'
        exit 1;
    fi;
fi

if [ ! $DRUPALV = "8" ]; then
    echo 'This script not compatible with Drupal 7 yet.'
    exit 1;
fi

DB=${SITE//-/_}
settings_file=~/${PROJECTDIR}/${SITE}/sites/default/settings.php;
default_settings_file=~/${PROJECTDIR}/${SITE}/sites/default/default.settings.php;
settings_file_tmp=~/${PROJECTDIR}/${SITE}/sites/default/settings.php-tmp;
local_settings=~/${PROJECTDIR}/${SITE}/sites/default/settings.local.php;
local_settings_template=~/${PROJECTDIR}/${SITE}/sites/example.settings.local.php;
install_log=~/${PROJECTDIR}/${SITE}/install.log;

chmod 775 ~/${PROJECTDIR}/${SITE}/sites/default
echo "${SITE}/sites/default/settings.local.php not found. Setting up local settings file... This might take a bit...";
mv $settings_file $settings_file_tmp;
drush si --db-url=mysql://root:root@localhost/$DB -y > $install_log 2>&1 & spinner $! || true;
if [ -f $settings_file ]; then
    rm $install_log;
    chmod 775 ~/$PROJECTDIR/$SITE/sites/default
    chmod 777 $settings_file;
    cp -f $local_settings_template $local_settings;
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
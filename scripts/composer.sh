#!/usr/bin/env bash
moduleDir=$(php-config --extension-dir)
options=$(find $moduleDir -maxdepth 1 -name "*.so"| \

    grep --invert-match xdebug| \

    # remove problematic extensions
    egrep --invert-match 'enchant|mysql|wddx|pgsql|opcache|redis'| \

    sed --expression 's/\(.*\)/ --define extension=\1/'| \

    # join everything together back in one big line
    tr --delete '\n'
)
# build the final command line
php --no-php-ini $options -d memory_limit=-1 -d extension=pdo_mysql -d extension=pdo_pgsql ~/bin/composer.phar "${@}"

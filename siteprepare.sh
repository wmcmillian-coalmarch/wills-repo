#!/bin/bash
set -e

drush vset file_public_path "sites/default/files";
drush vset file_private_path "sites/default/files/private";
drush vset file_temporary_path "/tmp";

drush eval "db_update('users')->fields(array('name' => 'coalmarch', 'mail' => 'devel@coalmarch.com', 'status' => 1))->condition('uid', 1)->execute();";
drush upwd coalmarch --password="c04lm4rch";
echo "$USER updated to be coalmarch";

USERS="wmcmillian
lroger
dwhite";
for user in $USERS; do
    drush ucrt $user --mail="$user@coalmarch.com" --password="c04lm4rch";
    drush urol "administrator" $user;
done

drush en pantheon_api pantheon_login admin_menu admin_menu_toolbar module_filter -y;
drush dis overlay toolbar -y;
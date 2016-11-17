#!/usr/bin/env bash

SITES="duke-dhvi
duke-dhvi-shared-resources
duke-iqa
duke-chavi-id
";

for site in $SITES; do
    drush @pantheon.$site.dev ucrt lroger --mail="lroger@coalmarch.com" --password="c04lm4rch";
    drush @pantheon.$site.dev ucrt dwhite --mail="dwhite@coalmarch.com" --password="c04lm4rch";
    drush @pantheon.$site.dev urol "administrator" lroger;
    drush @pantheon.$site.dev urol "administrator" dwhite;
done;
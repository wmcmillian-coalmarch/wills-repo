<?php

require_once 'module_rename.php';

function module_rename_drush_command(){
    $items = array();

    $items['module-rename'] = array(
        'description' => 'Rename a module',
        'aliases' => array('mr'),
        'arguments' => array(
            'old' => 'Old module machine name',
            'new' => 'New module machine name'
        ),
        'options' => array(
            'replace' => 'Comma seperated list of words to replace with the words in the --replace-with option in the module. These must match placement in the list.',
            'replace-with' => 'Comma seperated list of words replacing the words in the --replace option. These must match placement in the list.'
        ),
    );

    $items['module-string-replace'] = array(
        'description' => 'Rename a module',
        'aliases' => array('msr'),
        'arguments' => array(
            'module' => 'Module machine name',
        ),
        'options' => array(
            'replace' => 'Comma seperated list of words to replace with the words in the --replace-with option in the module. These must match placement in the list.',
            'replace-with' => 'Comma seperated list of words replacing the words in the --replace option. These must match placement in the list.'
        ),
    );

    $items['module-copy'] = array(
        'description' => 'Copy a module somewhere else',
        'aliases' => array('mc'),
        'arguments' => array(
            'module' => 'Module machine name',
        ),
        'options' => array(
            'destination' => 'New module destination'
        ),
    );

    $items['module-copy-rename'] = array(
        'description' => 'Copy and rename a module',
        'aliases' => array('mcr'),
        'arguments' => array(
            'old' => 'Old module machine name',
            'new' => 'New module machine name'
        ),
        'options' => array(
            'destination' => 'New module destination',
            'replace' => 'Comma seperated list of words to replace with the words in the --replace-with option in the module. These must match placement in the list.',
            'replace-with' => 'Comma seperated list of words replacing the words in the --replace option. These must match placement in the list.'
        ),
    );



    return $items;
}

function drush_module_rename_module_rename($old, $new) {
    $MCR = new ModuleCopyRename();
    $modulepath = $MCR->rename($old, $new);

    drush_log("Module renamed to $modulepath", 'success');

    $replace = drush_get_option_list('replace');
    $replace_with = drush_get_option_list('replace-with');

    foreach($replace as $k => $r) {
        if(!empty($replace_with[$k])) {
            $MCR->stringReplace($modulepath, $r, $replace_with[$k]);
            drush_log("Replaced \"$r\" with \"{$replace_with[$k]}\"", 'success');
        }
        else {
            drush_log("No match found for \"$r\"!", 'warning');
        }
    }

}

function drush_module_rename_module_string_replace($module) {
    $MCR = new ModuleCopyRename();
    $modulepath = $MCR->getModulePath($module);
    $replace = drush_get_option_list('replace');
    $replace_with = drush_get_option_list('replace-with');

    foreach($replace as $k => $r) {
        if(!empty($replace_with[$k])) {
            $MCR->stringReplace($modulepath, $r, $replace_with[$k]);
            drush_log("Replaced \"$r\" with \"{$replace_with[$k]}\"", 'success');
        }
        else {
            drush_log("No match found for \"$r\"!", 'warning');
        }
    }
}

function drush_module_rename_module_copy($module) {
    $MCR = new ModuleCopyRename();

    $destination = drush_get_option('destination');

    if(empty($destination)) {
        $destination = drush_prompt('Where do you want the module?', '.');
    }

    if(!is_dir($destination)) {
        drush_set_error("Destination $destination does not exist!");
    }

    $dest = $destination . '/' . $module;

    $MCR->copy($module, $dest);

    drush_log("$module copied to $dest", 'success');
}

function drush_module_rename_module_copy_rename($old, $new) {
    $MCR = new ModuleCopyRename();

    $destination = drush_get_option('destination');

    if(empty($destination)) {
        $destination = drush_prompt('Where do you want the module?', '.');
    }

    if(!is_dir($destination)) {
        drush_set_error("Destination $destination does not exist!");
    }

    $modulepath = $MCR->copyRename($old, $new, $destination);


    drush_log("Module copied and renamed to $modulepath", 'success');

    $replace = drush_get_option_list('replace');
    $replace_with = drush_get_option_list('replace-with');

    foreach($replace as $k => $r) {
        if(!empty($replace_with[$k])) {
            $MCR->stringReplace($modulepath, $r, $replace_with[$k]);
            drush_log("Replaced \"$r\" with \"{$replace_with[$k]}\"", 'success');
        }
        else {
            drush_log("No match found for \"$r\"!", 'warning');
        }
    }
}

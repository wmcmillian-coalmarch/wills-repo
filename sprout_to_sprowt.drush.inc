<?php

function sprout_to_sprowt_drush_command(){
    $items = array();

    $items['sprout-to-sprowt'] = array(
        'description' => 'Convert a site from a Sprout site to a Sprowt site',
        'aliases' => array('s2s'),
    );

    return $items;
}

function drush_sprout_to_sprowt(){
    if(!function_exists('drush_registry_rebuild')) {
        drush_set_error('Registry Rebuild must exist to continue');
        drush_die();
    }

    $prompt = "
    *************************************************************************
    It is highly recommended to back up your Database before running this.
    If it screws up you have no choice but to re-import a backed up database.
    *************************************************************************
";
    drush_print($prompt);
    if(!drush_confirm('Have you backed up your database?')){
        drush_die('Exiting...');
    }

    drush_print('Setting up variables...');
    //rename variables
    variable_set('install_profile', 'sprowt');
    $vars = db_query("
        SELECT name
        FROM variable
        WHERE name LIKE 'leadbuilder_%'
    ")->fetchCol();
    foreach($vars as $var) {
        $val = variable_get($var);
        $new_var = str_replace('leadbuilder', 'sprowt', $var);
        variable_set($new_var, $val);
    }

    drush_print('Setting up modules...');
    $prompt = "
    You should have copied the features from the previous site to sites/all/modules/features.
    ";
    drush_print($prompt);
    if(!drush_confirm('did you copy the features?')){
        drush_die('Exiting...');
    }

    //rename modules
    $l_modules = db_query("
        SELECT *
        FROM system
        WHERE name LIKE 'leadbuilder_%'
        AND type = 'module'
        AND filename NOT LIKE '%modules/features%'
    ")->fetchAll(PDO::FETCH_ASSOC);

    $updates = array();
    foreach($l_modules as $row) {
        $new_row = $row;
        $new_row['name'] = str_replace('leadbuilder', 'sprowt', $row['name']);
        $new_row['filename'] = str_replace('leadbuilder', 'sprowt', $row['filename']);
        $new_row['filename'] = str_replace('sprout', 'sprowt', $new_row['filename']);
        $updates[] = db_update('system')
            ->fields($new_row)
            ->condition('name', $row['name']);
    }

    foreach($updates as $update) {
        $update->execute();
    }

    $sprout = db_query("SELECT * FROM system WHERE name = 'sprout'")->fetchAssoc();
    $sprout['name'] = 'sprowt';
    $sprout['filename'] = str_replace('sprout', 'sprowt', $sprout['filename']);
    db_update('system')
        ->fields($sprout)
        ->condition('name', 'sprout')
        ->execute();

    drush_registry_rebuild();

    drush_print('Setting up blocks...');
    //update blocks
    $blocks = db_query("
        SELECT *
        FROM block
        WHERE module LIKE 'leadbuilder_%'
    ")->fetchAll(PDO::FETCH_ASSOC);

    foreach($blocks as $block) {
        $new_block = $block;
        $new_block['module'] = str_replace('leadbuilder', 'sprowt', $block['module']);
        $new_block['delta'] = str_replace('leadbuilder', 'sprowt', $block['delta']);
        $class = 'block--' . str_replace('_','-',$block['module']) . '-' . str_replace('_','-',$block['delta']);
        $classes = !empty($block['css_class']) ? explode(' ', $block['css_class']) : array();
        $classes[] = $class;
        $new_block['css_class'] = implode(' ', $classes);
        db_update('block')
            ->fields($new_block)
            ->condition('bid', $block['bid'])
            ->execute();
    }

    drush_print('Finishing up...');
    //add sprowt_setup table
    if(db_table_exists('sprowt_setup')){
        db_drop_table('sprowt_setup');
    }

    $sprowt_setup_schema = array(
        'description' => 'The initial setup for this sprowt site',
        'fields' => array(
            'setup_id' => array('type' => 'serial', 'unsigned' => true, 'not null' => true),
            'form_name' => array('type'=> 'varchar', 'length' => 255, 'not null' => true, 'default' => ''),
            'form_field' => array('type'=> 'varchar', 'length' => 255, 'not null' => true, 'default' => ''),
            'field_value' => array('type'=> 'text'),
        ),
        'indexes' => array(
            'form' => array('form_name')
        ),
        'primary key' => array('setup_id')
    );

    db_create_table('sprowt_setup', $sprowt_setup_schema);

    $setup_rows = db_query("
        SELECT *
        FROM sprout_setup
    ")->fetchAll(PDO::FETCH_ASSOC);

    foreach($setup_rows as $row) {
        db_insert('sprowt_setup')->fields($row)->execute();
    }

    drush_cache_clear_all();

    drush_print('Done!');
}
<?php


function addwill_drush_command(){
  $items = array();
  
  $items['addwill'] = array(
    'description' => 'Adds will to a drupal site',
    'aliases' => array(),
    'options' => array(
      'password' => 'Add a password'
    ),
  );
  
  return $items;
}


function drush_addwill(){
  $password = drush_get_option('password');
  
  if(empty($password)) {
    $password = drush_prompt(dt('Password?'));
  }
  
  drush_set_option('mail', 'wmcmillian@coalmarch.com');
  
  drush_invoke('ucrt', array(
    'wmcmillian'
  ));
  
  drush_unset_option('mail');
  drush_unset_option('password');
  
  
  $role = db_query("
    SELECT name
    FROM role
    WHERE rid = 3
  ")->fetchField();
  
  drush_invoke('urol', array(
    $role,
    'wmcmillian'
  ));
  
}
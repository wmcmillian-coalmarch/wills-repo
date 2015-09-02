<?php

die(); // We don't want this to be executed should it end up on a server somehow.

// Bootstrap Drupal (Not necessary)
define('DRUPAL_ROOT', $_SERVER['DOCUMENT_ROOT']);
require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

// BEGIN REDIRECT SCRIPT

// Define redirect array
// the 'path-to-redirect' should never have duplicates
$redirs = array(
  array('path-to-redirect', 'node/1'),
  array('path-to-redirect', 'node/1'),
  array('path-to-redirect', 'node/1'),
  array('path-to-redirect', 'node/1'),
);

$return = array();

// Put redirects into the system
foreach($redirs as $red) {
  $redirect = new stdClass();

  $exists = (drupal_lookup_path('source',$red[0]));
  
  
  if(!$exists) {
    module_invoke(
      'redirect', 
      'object_prepare',
      $redirect, 
      array(
        'source' => $red[0], 
        'source_options' => array(), 
        'redirect' => $red[1], 
        'redirect_options' => array(), 
        'language' => LANGUAGE_NONE, 
      )
    );
  
    module_invoke('redirect', 'save', $redirect);
    
    $return[] = $redirect;
  }
}

dsm($return);


?>

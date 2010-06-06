<?php
 /**
  * My hackish solution to the issue where php does not report errors as 
  * expected. This file should be included in index.php of an index.php on
  * problematic Drupal installs. It sets itself as the error_handler and 
  * logs things to a file here in the ~/Projects/debug directory.  There is 
  * also a dd() like function: ars_dd() for sending debugging bits to.
  */

// Please report all php errors
error_reporting(-1);

set_error_handler('ars_error_handler', E_ALL | E_STRICT & ~E_DEPRECATED);
/**
 * Custom error handler. Hopefully this hijacks things before the drupal 
 * handler can get to it and supress things.
 */
function ars_error_handler( $errno/*int*/  ,  $errstr /*string*/ , $errfile /*string*/,  $errline /*int*/,   $errcontext/*array*/  ) {
  print "Error: $errno: $errstr. Check debug.log.";
ars_dd($errcontext, "Error $errno: $errstr in $errfile on line $errline");
  return FALSE;
}  // end ars_error_handler


  /**
   * Shamelessly copied from drupal_debug in the devel module 
   * and bent to my will.
   */
function ars_dd($data, $label = NULL) {
  ob_start();
  
  print_r($data);
  $string = ob_get_clean();
  $out = date('j F Y h:i:s A ');
  if ($label) {
    $out .= $label .': '. $string;
  }
  else {
    $out .= $string;
  }
  $out .= "\n";
  
  $file = '/Users/arenesoper/Projects/debug/debug.log';
  $handle = fopen($file, "a");
  if (fwrite($handle, $out) === FALSE) {
    print('The debug file could not be written!');
  } 
  fclose($handle);
} // end ars_dd

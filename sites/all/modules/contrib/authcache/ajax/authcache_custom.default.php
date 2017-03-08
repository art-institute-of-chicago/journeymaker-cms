<?php

/**
 * @file authcache_custom.default.php
 * 
 * Your custom Ajax functions.
 *
 * Copy this file to authcache_custom.php and place in the same directory as
 * your settings.php file (or the Authcache ajax directory).
 *
 * For documentation & support, visit:
 * @link http://drupal.org/project/authcache
 */


/**
 * Example custom function.
 *
 * This function will be called if the Authcache:ajax JSON object contains
 * { 'funcname' : 'var' } Use hook_authcache_ajax() in your Drupal module
 * to modify the Authcache:ajax JSON object for the cached page.
 *
 * @see modulename
 */
function _authcache_funcname($vars) {
  global $user; // current user

  // For core or contributed modules, it's best to try to include
  // the module file and call the required function. Example:
  include_once dirname(drupal_get_filename('module', 'module_name')) . '/module_name.module';
  return module_name_display_info();

  // You can return an array or a single value. This will be converted into
  // JSON and sent back to the browser. Create a JavaScript function called
  // _authcache_funcname(var) to handle the return value.  See authcache_example.module/.js

  return array(2, 3, 5, 7 => array(11, 17, 19));
}

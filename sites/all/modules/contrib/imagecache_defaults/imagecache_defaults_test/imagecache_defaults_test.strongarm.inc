<?php
/**
 * @file
 * imagecache_defaults_test.strongarm.inc
 */

/**
 * Implements hook_strongarm().
 */
function imagecache_defaults_test_strongarm() {
  $export = array();

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'menu_options_imagecache_defaults_test';
  $strongarm->value = array(
    0 => 'main-menu',
  );
  $export['menu_options_imagecache_defaults_test'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'menu_parent_imagecache_defaults_test';
  $strongarm->value = 'main-menu:0';
  $export['menu_parent_imagecache_defaults_test'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'node_options_imagecache_defaults_test';
  $strongarm->value = array(
    0 => 'status',
    1 => 'promote',
  );
  $export['node_options_imagecache_defaults_test'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'node_preview_imagecache_defaults_test';
  $strongarm->value = '0';
  $export['node_preview_imagecache_defaults_test'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'node_submitted_imagecache_defaults_test';
  $strongarm->value = 1;
  $export['node_submitted_imagecache_defaults_test'] = $strongarm;

  return $export;
}

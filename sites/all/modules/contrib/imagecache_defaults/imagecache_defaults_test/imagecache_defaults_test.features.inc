<?php
/**
 * @file
 * imagecache_defaults_test.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function imagecache_defaults_test_ctools_plugin_api() {
  list($module, $api) = func_get_args();
  if ($module == "strongarm" && $api == "strongarm") {
    return array("version" => "1");
  }
}

/**
 * Implements hook_node_info().
 */
function imagecache_defaults_test_node_info() {
  $items = array(
    'imagecache_defaults_test' => array(
      'name' => t('Testing: ImageCache Defaults'),
      'base' => 'node_content',
      'description' => t('Content type provided for ImageCache Defaults tests.'),
      'has_title' => '1',
      'title_label' => t('Title'),
      'help' => '',
    ),
  );
  return $items;
}

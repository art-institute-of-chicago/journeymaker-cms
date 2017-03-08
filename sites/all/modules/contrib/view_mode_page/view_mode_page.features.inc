<?php
/**
 * @file
 * Integrate View Mode Page with the Features module
 */

/**
 * Implements hook_features_export_options().
 */
function view_mode_page_pattern_features_export_options() {
  $options = array();

  $patterns = module_invoke('view_mode_page', 'get_entity_patterns');
  foreach ($patterns as $pattern) {
    $name = $pattern->entity_type . ':' . $pattern->content_type . ':' . $pattern->view_mode;
    $options[$name] = $pattern->entity_type . ':' . $pattern->content_type . ': ' . $pattern->view_mode;
  }

  return $options;
}

/**
 * Implements hook_features_export().
 */
function view_mode_page_pattern_features_export($data, &$export, $module_name) {
  $pipe = array();
  $export['dependencies']['view_mode_page'] = 'view_mode_page';
  foreach ($data as $component) {
    $export['features']['view_mode_page_pattern'][$component] = $component;
    if (!isset($pipe['pattern'])) {
      $pipe['pattern'] = array();
    }
    $pipe['pattern'][] = $component;
  }
  return $pipe;
}

/**
 * Implements hook_features_export_render().
 */
function view_mode_page_pattern_features_export_render($module_name, $data, $export = NULL) {
  $code = array();
  $code[] = '  $vmp_config = array();';
  $code[] = '';
  foreach ($data as $sys_name) {
    $sys_name_pieces = explode(':', $sys_name);
    switch (count($sys_name_pieces)) {
      case 2:
        $entity_type = 'node';
        $content_type = $sys_name_pieces[0];
        $view_mode = $sys_name_pieces[1];
        break;

      case 3:
        $entity_type = $sys_name_pieces[0];
        $content_type = $sys_name_pieces[1];
        $view_mode = $sys_name_pieces[2];
        break;
    }
    $item = module_invoke('view_mode_page', 'get_entity_patterns', $entity_type, $content_type, $view_mode);
    ksort($item);
    $code[] = '  $vmp_config[] = ' . features_var_export($item, '  ') . ';';
  }
  $code[] = '  return $vmp_config;';
  $code = implode("\n", $code);
  return array('view_mode_page_pattern_default' => $code);
}

/**
 * Implements hook_features_export_rebuild().
 */
function view_mode_page_pattern_features_rebuild($module) {
  $items = module_invoke($module, 'view_mode_page_pattern_default');
  foreach ($items as $item) {
    db_merge('view_mode_page')
      ->key(array(
        'entity_type' => $item[0]['entity_type'],
        'content_type' => $item[0]['content_type'],
        'view_mode' => $item[0]['view_mode'],
        'url_pattern' => $item[0]['url_pattern'],
      ))
      ->fields(array(
        'entity_type' => $item[0]['entity_type'],
        'content_type' => $item[0]['content_type'],
        'view_mode' => $item[0]['view_mode'],
        'url_pattern' => $item[0]['url_pattern'],
        'title' => $item[0]['title'],
        'show_title' => $item[0]['show_title'],
      ))
      ->execute();
  }
  menu_rebuild();
}

/**
 * Implements hook_features_revert().
 */
function view_mode_page_pattern_features_revert($module) {
  view_mode_page_pattern_features_rebuild($module);
  return TRUE;
}

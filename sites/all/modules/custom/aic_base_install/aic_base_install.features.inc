<?php
/**
 * @file
 * aic_base_install.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function aic_base_install_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "field_group" && $api == "field_group") {
    return array("version" => "1");
  }
}

/**
 * Implements hook_image_default_styles().
 */
function aic_base_install_image_default_styles() {
  $styles = array();

  // Exported image style: aic_background.
  $styles['aic_background'] = array(
    'label' => 'aic_background',
    'effects' => array(
      1 => array(
        'name' => 'image_scale_and_crop',
        'data' => array(
          'width' => 1920,
          'height' => 1080,
        ),
        'weight' => 1,
      ),
    ),
  );

  // Exported image style: aic_large.
  $styles['aic_large'] = array(
    'label' => 'aic_large',
    'effects' => array(
      2 => array(
        'name' => 'image_scale',
        'data' => array(
          'width' => 1600,
          'height' => 1600,
          'upscale' => 0,
        ),
        'weight' => 1,
      ),
    ),
  );

  // Exported image style: aic_medium.
  $styles['aic_medium'] = array(
    'label' => 'aic_medium',
    'effects' => array(
      3 => array(
        'name' => 'image_scale',
        'data' => array(
          'width' => 800,
          'height' => 800,
          'upscale' => 0,
        ),
        'weight' => 1,
      ),
    ),
  );

  // Exported image style: aic_small.
  $styles['aic_small'] = array(
    'label' => 'aic_small',
    'effects' => array(
      4 => array(
        'name' => 'image_scale_and_crop',
        'data' => array(
          'width' => 375,
          'height' => 315,
        ),
        'weight' => 1,
      ),
    ),
  );

  return $styles;
}

/**
 * Implements hook_node_info().
 */
function aic_base_install_node_info() {
  $items = array(
    'activity_template' => array(
      'name' => t('Activity Template'),
      'base' => 'node_content',
      'description' => '',
      'has_title' => '1',
      'title_label' => t('Title'),
      'help' => '',
    ),
    'aic_theme' => array(
      'name' => t('Theme'),
      'base' => 'node_content',
      'description' => '',
      'has_title' => '1',
      'title_label' => t('Title'),
      'help' => '',
    ),
    'artwork' => array(
      'name' => t('Artwork'),
      'base' => 'node_content',
      'description' => '',
      'has_title' => '1',
      'title_label' => t('Title'),
      'help' => '',
    ),
  );
  drupal_alter('node_info', $items);
  return $items;
}

<?php
/**
 * @file
 * Administrative page callbacks for the ImageCache Defaults module.
 */

/**
 * ImageCache Defaults global settings page.
 *
 * @return array
 *   A Drupal system settings form to be rendered by drupal_get_form().
 */
function imagecache_defaults_settings() {
  $form = array();

  $form['imagecache_defaults_global'] = array(
    '#type' => 'fieldset',
    '#collapsible' => FALSE,
    '#title' => t('Site-wide default image settings'),
  );

  $form['imagecache_defaults_global']['imagecache_defaults_broken_path_handling'] = array(
    '#type' => 'radios',
    '#title' => t('Default image replacement behaviour'),
    '#description' => t('How should ImageCache Defaults handle broken and corrupt image file paths?'),
    '#options' => array(
      IMAGECACHE_DEFAULTS_DISABLED => t("Don't remove or modify any image paths."),
      IMAGECACHE_DEFAULTS_REMOVE_ALL => t("<em>Remove</em> everything sent to Image that doesn't have a valid image path."),
      IMAGECACHE_DEFAULTS_REPLACE_ALL => t("<em>Replace</em> everything sent to Image that doesn't have a valid image path."),
      IMAGECACHE_DEFAULTS_REPLACE_VALID => t("<em>Replace valid but broken</em> paths. Remove images with blank or corrupt paths. (Recommended)"),
    ),
    '#default_value' => variable_get('imagecache_defaults_broken_path_handling', IMAGECACHE_DEFAULTS_REPLACE_VALID),
  );

  $form['imagecache_defaults_global']['imagecache_defaults_default_image_style'] = array(
    '#type' => 'select',
    '#title' => t('Default image preprocess style'),
    '#description' => t("The default image will be modified by this style before the replaced image's styles are applied."),
    '#options' => image_style_options(FALSE),
    '#default_value' => variable_get('imagecache_defaults_default_image_style', 'imagecache_defaults_prepare_default_image'),
  );

  $form['imagecache_defaults_global']['imagecache_defaults_bypass_cache'] = array(
    '#type' => 'checkbox',
    '#title' => t('Disable the ImageCache Defaults cache (Not recommended).'),
    '#description' => t('<strong>Warning:</strong> This will force ImageCache Defaults to re-validate and lookup the dimensions of every image on every page load. This is a performance hit that may be negligible or very expensive, depending on your server setup.'),
    '#default_value' => variable_get('imagecache_defaults_bypass_cache', FALSE),
  );

  $form['imagecache_defaults_reporting'] = array(
    '#type' => 'fieldset',
    '#collapsible' => FALSE,
    '#title' => t('Broken image reports'),
  );

  $form['imagecache_defaults_reporting']['imagecache_defaults_watchdog_enabled'] = array(
    '#type' => 'checkbox',
    '#title' => t('Log a watchdog message whenever ImageCache Defaults attempts to repair an image path.'),
    '#description' => t('<strong>Warning:</strong> This can easily result in many log entries for sites that have been recently migrated with an incomplete or missing files directory.'),
    '#default_value' => variable_get('imagecache_defaults_watchdog_enabled', FALSE),
  );

  // This is rendered by the page callback function.
  return system_settings_form($form);
}

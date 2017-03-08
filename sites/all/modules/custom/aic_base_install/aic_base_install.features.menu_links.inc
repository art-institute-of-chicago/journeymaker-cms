<?php
/**
 * @file
 * aic_base_install.features.menu_links.inc
 */

/**
 * Implements hook_menu_default_menu_links().
 */
function aic_base_install_menu_default_menu_links() {
  $menu_links = array();

  // Exported menu link: management_publish-data:admin/publish-data.
  $menu_links['management_publish-data:admin/publish-data'] = array(
    'menu_name' => 'management',
    'link_path' => 'admin/publish-data',
    'router_path' => 'admin/publish-data',
    'link_title' => 'Publish Data',
    'options' => array(
      'identifier' => 'management_publish-data:admin/publish-data',
    ),
    'module' => 'system',
    'hidden' => 0,
    'external' => 0,
    'has_children' => 0,
    'expanded' => 0,
    'weight' => 0,
    'customized' => 0,
    'parent_identifier' => 'management_administration:admin',
  );
  // Exported menu link: navigation_activity-template:node/add/activity-template.
  $menu_links['navigation_activity-template:node/add/activity-template'] = array(
    'menu_name' => 'navigation',
    'link_path' => 'node/add/activity-template',
    'router_path' => 'node/add/activity-template',
    'link_title' => 'Activity Template',
    'options' => array(
      'identifier' => 'navigation_activity-template:node/add/activity-template',
    ),
    'module' => 'system',
    'hidden' => 0,
    'external' => 0,
    'has_children' => 0,
    'expanded' => 0,
    'weight' => 0,
    'customized' => 0,
    'parent_identifier' => 'navigation_add-content:node/add',
  );
  // Exported menu link: navigation_artwork--theme-listing:artwork-list.
  $menu_links['navigation_artwork--theme-listing:artwork-list'] = array(
    'menu_name' => 'navigation',
    'link_path' => 'artwork-list',
    'router_path' => 'artwork-list',
    'link_title' => 'Artwork / Theme listing',
    'options' => array(
      'identifier' => 'navigation_artwork--theme-listing:artwork-list',
    ),
    'module' => 'system',
    'hidden' => 0,
    'external' => 0,
    'has_children' => 0,
    'expanded' => 0,
    'weight' => 0,
    'customized' => 0,
  );
  // Exported menu link: navigation_artwork:node/add/artwork.
  $menu_links['navigation_artwork:node/add/artwork'] = array(
    'menu_name' => 'navigation',
    'link_path' => 'node/add/artwork',
    'router_path' => 'node/add/artwork',
    'link_title' => 'Artwork',
    'options' => array(
      'identifier' => 'navigation_artwork:node/add/artwork',
    ),
    'module' => 'system',
    'hidden' => 0,
    'external' => 0,
    'has_children' => 0,
    'expanded' => 0,
    'weight' => 0,
    'customized' => 0,
    'parent_identifier' => 'navigation_add-content:node/add',
  );
  // Exported menu link: navigation_csv-import:admin/content/csv-import/processForm.
  $menu_links['navigation_csv-import:admin/content/csv-import/processForm'] = array(
    'menu_name' => 'navigation',
    'link_path' => 'admin/content/csv-import/processForm',
    'router_path' => 'admin/content/csv-import/processForm',
    'link_title' => 'CSV Import',
    'options' => array(
      'attributes' => array(
        'title' => '',
      ),
      'identifier' => 'navigation_csv-import:admin/content/csv-import/processForm',
    ),
    'module' => 'system',
    'hidden' => 1,
    'external' => 0,
    'has_children' => 0,
    'expanded' => 0,
    'weight' => 0,
    'customized' => 1,
  );
  // Exported menu link: navigation_theme--artwork-listing:artwork-list-theme.
  $menu_links['navigation_theme--artwork-listing:artwork-list-theme'] = array(
    'menu_name' => 'navigation',
    'link_path' => 'artwork-list-theme',
    'router_path' => 'artwork-list-theme',
    'link_title' => 'Theme / Artwork listing',
    'options' => array(
      'identifier' => 'navigation_theme--artwork-listing:artwork-list-theme',
    ),
    'module' => 'system',
    'hidden' => 0,
    'external' => 0,
    'has_children' => 0,
    'expanded' => 0,
    'weight' => 0,
    'customized' => 0,
  );
  // Exported menu link: navigation_theme:node/add/aic-theme.
  $menu_links['navigation_theme:node/add/aic-theme'] = array(
    'menu_name' => 'navigation',
    'link_path' => 'node/add/aic-theme',
    'router_path' => 'node/add/aic-theme',
    'link_title' => 'Theme',
    'options' => array(
      'identifier' => 'navigation_theme:node/add/aic-theme',
    ),
    'module' => 'system',
    'hidden' => 0,
    'external' => 0,
    'has_children' => 0,
    'expanded' => 0,
    'weight' => 0,
    'customized' => 0,
    'parent_identifier' => 'navigation_add-content:node/add',
  );

  // Translatables
  // Included for use with string extractors like potx.
  t('Activity Template');
  t('Artwork');
  t('Artwork / Theme listing');
  t('CSV Import');
  t('Publish Data');
  t('Theme');
  t('Theme / Artwork listing');

  return $menu_links;
}

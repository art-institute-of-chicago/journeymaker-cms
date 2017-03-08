<?php
/**
 * @file
 * aic_base_install.field_group.inc
 */

/**
 * Implements hook_field_group_info().
 */
function aic_base_install_field_group_info() {
  $field_groups = array();

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_artwork|field_collection_item|field_prompt|default';
  $field_group->group_name = 'group_artwork';
  $field_group->entity_type = 'field_collection_item';
  $field_group->bundle = 'field_prompt';
  $field_group->mode = 'default';
  $field_group->parent_name = '';
  $field_group->data = array(
    'label' => 'Artwork',
    'weight' => '5',
    'children' => array(
      0 => 'field_artwork',
    ),
    'format_type' => 'fieldset',
    'format_settings' => array(
      'label' => 'Artwork',
      'instance_settings' => array(
        'id' => '',
        'classes' => 'group-artwork field-group-fieldset',
        'description' => '',
      ),
      'formatter' => 'collapsible',
    ),
  );
  $field_groups['group_artwork|field_collection_item|field_prompt|default'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_prompts|node|aic_theme|default';
  $field_group->group_name = 'group_prompts';
  $field_group->entity_type = 'node';
  $field_group->bundle = 'aic_theme';
  $field_group->mode = 'default';
  $field_group->parent_name = '';
  $field_group->data = array(
    'label' => 'Prompts',
    'weight' => '8',
    'children' => array(
      0 => 'field_prompt',
    ),
    'format_type' => 'fieldset',
    'format_settings' => array(
      'formatter' => 'collapsible',
      'instance_settings' => array(
        'description' => '',
        'classes' => 'group-prompts field-group-fieldset',
        'id' => '',
      ),
    ),
  );
  $field_groups['group_prompts|node|aic_theme|default'] = $field_group;

  // Translatables
  // Included for use with string extractors like potx.
  t('Artwork');
  t('Prompts');

  return $field_groups;
}

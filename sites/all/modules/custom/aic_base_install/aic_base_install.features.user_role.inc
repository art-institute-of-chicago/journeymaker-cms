<?php
/**
 * @file
 * aic_base_install.features.user_role.inc
 */

/**
 * Implements hook_user_default_roles().
 */
function aic_base_install_user_default_roles() {
  $roles = array();

  // Exported role: Editor.
  $roles['Editor'] = array(
    'name' => 'Editor',
    'weight' => 4,
  );

  // Exported role: Publisher.
  $roles['Publisher'] = array(
    'name' => 'Publisher',
    'weight' => 5,
  );

  // Exported role: Standard User.
  $roles['Standard User'] = array(
    'name' => 'Standard User',
    'weight' => 3,
  );

  return $roles;
}

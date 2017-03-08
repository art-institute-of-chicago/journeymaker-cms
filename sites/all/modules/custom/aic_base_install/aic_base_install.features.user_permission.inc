<?php
/**
 * @file
 * aic_base_install.features.user_permission.inc
 */

/**
 * Implements hook_user_default_permissions().
 */
function aic_base_install_user_default_permissions() {
  $permissions = array();

  // Exported permission: 'create activity_template content'.
  $permissions['create activity_template content'] = array(
    'name' => 'create activity_template content',
    'roles' => array(
      'authenticated user' => 'authenticated user',
    ),
    'module' => 'node',
  );

  // Exported permission: 'create aic_theme content'.
  $permissions['create aic_theme content'] = array(
    'name' => 'create aic_theme content',
    'roles' => array(
      'authenticated user' => 'authenticated user',
    ),
    'module' => 'node',
  );

  // Exported permission: 'create artwork content'.
  $permissions['create artwork content'] = array(
    'name' => 'create artwork content',
    'roles' => array(
      'authenticated user' => 'authenticated user',
    ),
    'module' => 'node',
  );

  // Exported permission: 'delete any activity_template content'.
  $permissions['delete any activity_template content'] = array(
    'name' => 'delete any activity_template content',
    'roles' => array(
      'authenticated user' => 'authenticated user',
    ),
    'module' => 'node',
  );

  // Exported permission: 'delete any aic_theme content'.
  $permissions['delete any aic_theme content'] = array(
    'name' => 'delete any aic_theme content',
    'roles' => array(
      'authenticated user' => 'authenticated user',
    ),
    'module' => 'node',
  );

  // Exported permission: 'delete any artwork content'.
  $permissions['delete any artwork content'] = array(
    'name' => 'delete any artwork content',
    'roles' => array(
      'authenticated user' => 'authenticated user',
    ),
    'module' => 'node',
  );

  // Exported permission: 'delete own activity_template content'.
  $permissions['delete own activity_template content'] = array(
    'name' => 'delete own activity_template content',
    'roles' => array(
      'authenticated user' => 'authenticated user',
    ),
    'module' => 'node',
  );

  // Exported permission: 'delete own aic_theme content'.
  $permissions['delete own aic_theme content'] = array(
    'name' => 'delete own aic_theme content',
    'roles' => array(
      'authenticated user' => 'authenticated user',
    ),
    'module' => 'node',
  );

  // Exported permission: 'delete own artwork content'.
  $permissions['delete own artwork content'] = array(
    'name' => 'delete own artwork content',
    'roles' => array(
      'authenticated user' => 'authenticated user',
    ),
    'module' => 'node',
  );

  // Exported permission: 'edit any activity_template content'.
  $permissions['edit any activity_template content'] = array(
    'name' => 'edit any activity_template content',
    'roles' => array(
      'authenticated user' => 'authenticated user',
    ),
    'module' => 'node',
  );

  // Exported permission: 'edit any aic_theme content'.
  $permissions['edit any aic_theme content'] = array(
    'name' => 'edit any aic_theme content',
    'roles' => array(
      'authenticated user' => 'authenticated user',
    ),
    'module' => 'node',
  );

  // Exported permission: 'edit any artwork content'.
  $permissions['edit any artwork content'] = array(
    'name' => 'edit any artwork content',
    'roles' => array(
      'authenticated user' => 'authenticated user',
    ),
    'module' => 'node',
  );

  // Exported permission: 'edit own activity_template content'.
  $permissions['edit own activity_template content'] = array(
    'name' => 'edit own activity_template content',
    'roles' => array(
      'authenticated user' => 'authenticated user',
    ),
    'module' => 'node',
  );

  // Exported permission: 'edit own aic_theme content'.
  $permissions['edit own aic_theme content'] = array(
    'name' => 'edit own aic_theme content',
    'roles' => array(
      'authenticated user' => 'authenticated user',
    ),
    'module' => 'node',
  );

  // Exported permission: 'edit own artwork content'.
  $permissions['edit own artwork content'] = array(
    'name' => 'edit own artwork content',
    'roles' => array(
      'authenticated user' => 'authenticated user',
    ),
    'module' => 'node',
  );

  return $permissions;
}

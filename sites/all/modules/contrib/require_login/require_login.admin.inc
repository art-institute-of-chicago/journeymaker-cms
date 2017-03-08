<?php
/**
 * @file
 * require_login.admin.inc
 */

/**
 * Page callback for require login configurations form.
 */
function require_login_admin_settings() {
  $form = array();
  $form['require_login_deny_message'] = array(
    '#type' => 'textarea',
    '#title' => t('Deny Message'),
    '#description' => t('Message shown to non-authenticated visitors after login page redirect.'),
    '#default_value' => variable_get('require_login_deny_message', REQUIRE_LOGIN_DEFAULT_MESSAGE),
  );
  $form['require_login_excluded_paths'] = array(
    '#type' => 'textarea',
    '#title' => t('Exclude Paths'),
    '#description' => t('Define paths which will NOT require user authentication. Use &lt;front&gt; for front page.'),
    '#default_value' => variable_get('require_login_excluded_paths', ''),
  );
  $form['#submit'][] = 'require_login_admin_settings_submit';
  return system_settings_form($form);
}

/**
 * Submit handler for require login configurations form.
 */
function require_login_admin_settings_submit($form, &$form_state) {

  // Trim extra newlines/whitespace from excluded paths.
  $form_state['values']['require_login_excluded_paths'] = trim($form_state['values']['require_login_excluded_paths']);
  variable_set('require_login_excluded_paths', $form_state['values']['require_login_excluded_paths']);
}

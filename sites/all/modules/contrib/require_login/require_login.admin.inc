<?php

/**
 * @file
 * require_login.admin.inc
 */

/**
 * Require login configuration form callback.
 */
function require_login_admin_settings() {
  $form['require_login_deny_message'] = array(
    '#type' => 'textarea',
    '#title' => t('Access Denied Message'),
    '#description' => t('Shown to anonymous users prior to user authentication (login).'),
    '#default_value' => variable_get('require_login_deny_message', REQUIRE_LOGIN_DEFAULT_MESSAGE),
  );
  $items = array(
    t('Use &lt;front&gt; to exclude the front page.'),
    t('Use relative path to exclude content and other internal Drupal pages. <em>Example: /about/contact</em>'),
    t('Use absolute path to exclude Drupal bootstrap enabled PHP scripts. <em>Example: /path/to/drupal/script/filename.php</em>'),
  );
  $items = theme('item_list', array('items' => $items));
  $form['require_login_excluded_paths'] = array(
    '#type' => 'textarea',
    '#title' => t('Exclude Paths'),
    '#description' => t('Disable user authentication (login) requirement on specific paths. Enter one exclusion per line using the following formats:') . $items,
    '#default_value' => variable_get('require_login_excluded_paths', ''),
  );
  $form['require_login_auth_path'] = array(
    '#type' => 'textfield',
    '#title' => t('User Authentication (Login) Path'),
    '#description' => t('Override the user authentication (login) path. May include URL queries and fragments.'),
    '#default_value' => variable_get('require_login_auth_path', 'user/login'),
  );
  return system_settings_form($form);
}

/**
 * Require login configuration form validation handler.
 */
function require_login_admin_settings_validate($form, &$form_state) {
  $absolutes = array();

  // Add leading slash to paths (except for <front>). Trims extra whitespace
  // and prepares exclusions for saving.
  $exclude_paths = explode(PHP_EOL, $form_state['values']['require_login_excluded_paths']);
  foreach ($exclude_paths as $key => $exclude_path) {
    $exclude_path = trim($exclude_path);
    if (empty($exclude_path) || $exclude_path == '<front>') {
      continue;
    }
    $url = parse_url($exclude_path);

    // Detect invalid absolute domain in path.
    if (isset($url['scheme']) || isset($url['host']) || preg_match('/^www./i', $url['path'])) {
      $absolutes[] = trim($exclude_path);
    }

    // Confirm leading forward slash presence.
    elseif (substr($exclude_path, 0, 1) != '/') {
      $exclude_paths[$key] = '/' . $exclude_path;
    }

    // Trim unnecessary whitespace from ends.
    else {
      $exclude_paths[$key] = $exclude_path;
    }
  }
  $form_state['values']['require_login_excluded_paths'] = implode(PHP_EOL, $exclude_paths);

  // Throw error if absolute paths were detected.
  if ($absolutes) {
    form_set_error('require_login_excluded_paths', t('Exclude paths cannot include protocols or domain names. Invalid paths:<br />!paths', array(
      '!paths' => implode('<br />', $absolutes),
    )));
  }

  // Add leading slash to user login path. Trims extra whitespace and prepares
  // user login path for saving.
  if (!empty($form_state['values']['require_login_auth_path'])) {
    $auth_path = trim($form_state['values']['require_login_auth_path']);
    $url = parse_url($auth_path);

    // Detect invalid absolute domain in path.
    if (isset($url['scheme']) || isset($url['host']) || preg_match('/^www./i', $url['path'])) {
      form_set_error('require_login_auth_path', t('User login path must be relative.'));
    }

    // Confirm leading forward slash presence.
    elseif (substr($auth_path, 0, 1) != '/') {
      $form_state['values']['require_login_auth_path'] = '/' . $auth_path;
    }

    // Trim unnecessary whitespace from ends.
    else {
      $form_state['values']['require_login_auth_path'] = $auth_path;
    }
  }
}

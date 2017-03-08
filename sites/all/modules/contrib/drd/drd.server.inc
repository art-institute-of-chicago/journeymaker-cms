<?php

/**
 * Build the form to edit Drupal core properties.
 *
 * @param array $form
 * @param array $form_state
 * @param int $sid
 * @return array
 */
function drd_server_edit($form, &$form_state, $sid = 0) {
  if ($sid) {
    $server = (array) drd_server_load($sid);
    if (is_string($server['settings'])) {
      $server['settings'] = unserialize($server['settings']);
    }
  }
  else {
    $server = array(
      'sid' => 0,
      'name' => '',
      'url' => '',
      'usessl' => 0,
      'status' => 1,
      'remoteinstall' => 0,
      'weight' => 0,
      'aes_key' => '',
      'aes_cipher' => '',
      'aes_iv' => '',
      'aes_impl' => '',
      'drupalversion' => '',
      'settings' => array(),
    );
  }
  if (empty($server['aes_iv'])) {
    $aes_iv_org = variable_get("aes_encryption_iv", '');
    aes_make_iv(TRUE);
    $server['aes_iv'] = variable_get("aes_encryption_iv", '');
    variable_set("aes_encryption_iv", $aes_iv_org);
  }
  $server['settings'] = drupal_array_merge_deep(array(
    'ssh2' => array(
      'enable' => FALSE,
      'host' => '',
      'port' => 22,
      'drush' => FALSE,
      'auth' => array(
        'mode' => 1,
        'username' => '',
        'password' => '',
        'file_public_key' => '',
        'file_private_key' => '',
        'key_secret' => '',
      ),
    ),
  ), $server['settings']);

  $form['#server'] = $server;

  $form['name'] = array(
    '#type' => 'textfield',
    '#title' => t('Name'),
    '#default_value' => $server['name'],
    '#max_length' => 255,
  );
  $form['url'] = array(
    '#type' => 'textfield',
    '#title' => t('URL'),
    '#default_value' => $server['url'],
    '#max_length' => 255,
    '#disabled' => ($server['sid'] > 0),
    '#description' => t('The domain (and optionally path) to the root of your Drupal core installation, e.g. www.example.com or www.example.com/drupal. If you have a multi-site setup, enter one of the domains.'),
  );

  _drd_tags_build_form('server', $server['sid'], $form);

  $form['usessl'] = array(
    '#type' => 'checkbox',
    '#title' => t('Use SSL'),
    '#default_value' => $server['usessl'],
  );
  $form['status'] = array(
    '#type' => 'checkbox',
    '#title' => t('Enabled'),
    '#default_value' => $server['status'],
  );
  $form['remoteinstall'] = array(
    '#type' => 'checkbox',
    '#title' => t('Support remote installation'),
    '#default_value' => $server['remoteinstall'],
  );
  $form['remoteinstallinfo'] = array(
    '#type' => 'container',
    '#states' => array(
      'invisible' => array(
        'input[name="remoteinstall"]' => array('checked' => FALSE),
      ),
    ),
  );
  $form['remoteinstallinfo']['info'] = array(
    '#markup' => t('To support remote installation, go to your remote Drupal core, move the file drd_server.install.php.txt to the Drupal core root directory and rename it to drd_server.install.php.'),
    '#prefix' => '<div class="messages warning">',
    '#suffix' => '</div>',
  );
  $form += aes_config();
  unset($form['aes']['aes_convert']);
  unset($form['aes']['view_method']);
  unset($form['aes']['aes_key_storage_method']);
  unset($form['aes']['aes_key_path']);
  unset($form['aes']['submit']);
  $form['aes']['aes_implementation']['#default_value'] = $server['aes_impl'];
  $form['aes']['aes_cipher']['#default_value'] = $server['aes_cipher'];
  $form['aes']['aes_iv'] = array(
    '#type' => 'textfield',
    '#title' => 'AES IV',
    '#default_value' => $server['aes_iv'],
    '#disabled' => TRUE,
  );

  $form['ssh2'] = array(
    '#type' => 'fieldset',
    '#title' => t('SSH2 Settings'),
    '#access' => function_exists('ssh2_connect'),
  );
  $form['ssh2']['ssh2_enable'] = array(
    '#type' => 'checkbox',
    '#title' => t('Enable SSH2 support'),
    '#default_value' => $server['settings']['ssh2']['enable'],
  );
  $form['ssh2']['settings'] = array(
    '#type' => 'container',
    '#states' => array(
      'invisible' => array(
        'input[name="ssh2_enable"]' => array('checked' => FALSE),
      ),
    ),
  );
  $form['ssh2']['settings']['ssh2_host'] = array(
    '#type' => 'textfield',
    '#title' => 'Hostname or IP',
    '#default_value' => $server['settings']['ssh2']['host'],
  );
  $form['ssh2']['settings']['ssh2_auth_mode'] = array(
    '#type' => 'select',
    '#title' => t('Auth mode'),
    '#options' => array(
      '1' => t('Username and password'),
      '2' => t('Public key'),
    ),
    '#default_value' => $server['settings']['ssh2']['auth']['mode'],
  );
  $form['ssh2']['settings']['ssh2_port'] = array(
    '#type' => 'textfield',
    '#title' => 'Port',
    '#default_value' => $server['settings']['ssh2']['port'],
  );
  $form['ssh2']['settings']['ssh2_auth_username'] = array(
    '#type' => 'textfield',
    '#title' => 'User name',
    '#default_value' => $server['settings']['ssh2']['auth']['username'],
  );
  $form['ssh2']['settings']['ssh2_auth_mode_1'] = array(
    '#type' => 'container',
    '#states' => array(
      'visible' => array(
        ':input[name="ssh2_auth_mode"]' => array('value' => '1'),
      ),
    ),
  );
  $form['ssh2']['settings']['ssh2_auth_mode_1']['ssh2_auth_password'] = array(
    '#type' => 'password',
    '#title' => 'Password',
    '#default_value' => $server['settings']['ssh2']['auth']['password'],
  );
  $form['ssh2']['settings']['ssh2_auth_mode_2'] = array(
    '#type' => 'container',
    '#states' => array(
      'visible' => array(
        ':input[name="ssh2_auth_mode"]' => array('value' => '2'),
      ),
    ),
  );
  $form['ssh2']['settings']['ssh2_auth_mode_2']['ssh2_auth_file_public_key'] = array(
    '#type' => 'textfield',
    '#title' => 'Public key filename',
    '#default_value' => $server['settings']['ssh2']['auth']['file_public_key'],
  );
  $form['ssh2']['settings']['ssh2_auth_mode_2']['ssh2_auth_file_private_key'] = array(
    '#type' => 'textfield',
    '#title' => 'Private key filename',
    '#default_value' => $server['settings']['ssh2']['auth']['file_private_key'],
  );
  $form['ssh2']['settings']['ssh2_auth_mode_2']['ssh2_auth_key_secret'] = array(
    '#type' => 'password',
    '#title' => 'Passphrase for encrypted private key',
    '#default_value' => $server['settings']['ssh2']['auth']['key_secret'],
  );
  $form['ssh2']['settings']['drush_enable'] = array(
    '#type' => 'checkbox',
    '#title' => t('Enable Drush support'),
    '#default_value' => $server['settings']['ssh2']['drush'],
  );

  if ($server['sid']) {
    $sub_form = drd_action_remote('drd.config.server.read', 'server', $server['sid']);
    if (!empty($sub_form) && is_array($sub_form)) {
      $form = array_merge($form, $sub_form);
    }
  }

  $form['actions'] = array('#type' => 'actions');
  $form['actions']['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Save'),
  );
  return $form;
}

/**
 * Validates the edited Drupal core properties before submitting them.
 *
 * @param array $form
 * @param array $form_state
 */
function drd_server_edit_validate($form, &$form_state) {
  // Try to validate the URL
  $url = $form_state['values']['url'];
  $usessl = $form_state['values']['usessl'];
  $valid = TRUE;
  if (strpos($url, '://') !== FALSE) {
    form_set_error('url', t('The URL can not contain a the protocol, i.e. do not input http:// at the beginning of the URL.'));
    $valid = FALSE;
  }
  if (strpos($url, ' ') !== FALSE) {
    form_set_error('url', t('The URL can not contain space characters.'));
    $valid = FALSE;
  }
  if (strpos($url, '/') === 0) {
    form_set_error('url', t('The URL should not contain leading slashes.'));
    $valid = FALSE;
  }
  if (!empty($url) && substr($url, -1) == '/') {
    form_set_error('url', t('The URL should not contain trailing slashes.'));
    $valid = FALSE;
  }
  if (empty($url)) {
    form_set_error('url', t('The URL can not be empty.'));
    $valid = FALSE;
  }
  if ($valid) {
    $url = ($usessl ? 'https://' : 'http://') . $url;
    try {
      $options = array('timeout' => 300, 'max_redirects' => 0);
      $output = _drd_xmlrpc($url . '/xmlrpc.php', 'system.getCapabilities', array(), $options);
      if (empty($output['xmlrpc'])) {
        $error = xmlrpc_error();
        if ($error->code == 302) {
          $new_url = str_replace('/xmlrpc.php', '', $error->message);
          form_set_error('url', t('Your URL %url redirects to %target, please adjust your settings accordingly.', array('%url' => $url, '%target' => $new_url)));
          $url = $new_url;
        }
        else {
          watchdog('DRD', 'XMLRPC error code @code: @message', array('@code' => $error->code, '@message' => $error->message,), WATCHDOG_ALERT);
          $valid = FALSE;
        }
      }
    }
    catch (Exception $e) {
      $valid = FALSE;
    }
    if (!$valid) {
      form_set_error('url', t('Failed to connect to the URL %url.', array('%url' => $url)));
    }
    else {
      // Check the remote installation support
      if ($form_state['values']['remoteinstall']) {
        $valid = TRUE;
        $url .= '/drd_server.install.php';
        try {
          $response = drupal_http_request($url, array('max_redirects' => 0));
          if ($response->code != 200) {
            $valid = FALSE;
          }
        }
        catch (Exception $e) {
          $valid = FALSE;
        }
        if (!$valid) {
          form_set_error('remoteinstall', t('Remote installation file drd_server.install.php not available on this domain.'));
        }
      }
    }
  }

  if (!empty($form_state['values']['aes_key'])) {
    if ($form_state['values']['aes_key'] !== $form_state['values']['aes_key_c']) {
      form_set_error('aes_key', t("The encryption keys didn't match."));
    }
  }
}

/**
 * Submits the edited Drupal core properties and installs the Drupal core if required.
 *
 * @param array $form
 * @param array $form_state
 */
function drd_server_edit_submit($form, &$form_state) {
  $changed = FALSE;
  $values = $form_state['values'];
  $server = $form['#server'];
  $server['name'] = $values['name'];
  $server['url'] = $values['url'];
  $server['usessl'] = $values['usessl'];
  $server['status'] = $values['status'];
  $server['remoteinstall'] = $values['remoteinstall'];
  $server['aes_impl'] = $values['aes_implementation'];
  $server['aes_cipher'] = $values['aes_cipher'];
  $server['aes_iv'] = $values['aes_iv'];

  if (empty($values['ssh2_auth_password'])) {
    $values['ssh2_auth_password'] = $server['settings']['ssh2']['auth']['password'];
  }
  if (empty($values['ssh2_auth_key_secret'])) {
    $values['ssh2_auth_key_secret'] = $server['settings']['ssh2']['auth']['key_secret'];
  }
  $server['settings'] = array(
    'ssh2' => array(
      'enable' => $values['ssh2_enable'],
      'host' => $values['ssh2_host'],
      'port' => $values['ssh2_port'],
      'drush' => $values['drush_enable'],
      'auth' => array(
        'mode' => $values['ssh2_auth_mode'],
        'username' => $values['ssh2_auth_username'],
        'password' => $values['ssh2_auth_password'],
        'file_public_key' => $values['ssh2_auth_file_public_key'],
        'file_private_key' => $values['ssh2_auth_file_private_key'],
        'key_secret' => $values['ssh2_auth_key_secret'],
      ),
    ),
  );

  if (!empty($form_state['values']['aes_key'])) {
    $changed = ($server['aes_key'] !== $values['aes_key']);
    $server['aes_key'] = $values['aes_key'];
  }
  elseif (empty($server['aes_key'])) {
    $server['aes_key'] = user_password(15);
    $changed = TRUE;
  }
  $new = ($server['sid'] == 0);
  if (!$new) {
    // db_update requires the settings field to be serialized where as
    // drupal_write_record serializes the field itself.
    $server['settings'] = serialize($server['settings']);
    db_update('drd_server')
      ->fields($server)
      ->condition('sid', $server['sid'])
      ->execute();
  }
  else {
    drupal_write_record('drd_server', $server);
    $changed = FALSE;
    if ($server['remoteinstall']) {
      $result = drd_install_drd_server('install server', 'server', $server['sid'], 0, FALSE);
    }
    else {
      $result = t('This Drupal core does not support remote installation of the drd_server module. If you want to support remote installation, go to your remote Drupal core, move the file drd_server.install.php.txt to the Drupal core root directory and rename it to drd_server.install.php! After that, change the setting here for this Drupal core and turn remote installation on.');
    }
    if (!$result) {
      drd_set_message($server, 'Drupal core installation failed, please check manually.', 'error');
    }
    else if ($result == 'Either module DRD_SERVER or AES can not be enabled.') {
      drd_set_message($server, $result, 'error');
    }
    else {
      drd_set_message((object)$server, $result);
      $query = drupal_get_destination();
      $query['goto'] = '1';
      $query['destination'] = 'admin/drd';
      $query['drd-extras'] = '1';
      $link = l('here', 'admin/drd/server/' . $server['sid'] . '/pushaes', array('query' => $query));
      drd_set_message((object)$server, 'After that, ...');
      drd_set_message((object)$server, '... either click ' . $link . ' to push the AES keys to the Drupal core.');
      drd_set_message((object)$server, '... or edit the remote AES keys manually: Implementation = "' . $server['aes_impl'] . '", Cipher = "' . $server['aes_cipher'] . '", Key = "' . $server['aes_key'] . '", IV = "' . $server['aes_iv'] . '"');
    }
    $goto = 'admin/drd';
  }

  if (isset($values['tags'])) {
    _drd_tags_save('server', $server['sid'], $values['tags']);
  }

  if ($changed) {
    drd_update_aes($server['sid']);
  }
  if (!$new) {
    drd_action_remote('drd.config.server.save', 'server', $server['sid'], $values);
  }

  if (!empty($goto)) {
    drupal_goto($goto);
  }
}

/**
 * @param int $sid
 * @return array
 */
function drd_server_view($sid) {
  $build['info'] = drd_server_info($sid);

  $url = drd_url_by_id('server', $sid);
  $output = '';
  drd_info('server', $sid, $url, $output);
  $form = drupal_get_form('drd_action_form', 'server', $sid);
  $build['actions_form'] = array(
    '#markup' => drupal_render($form),
  );
  $build['detail'] = array(
    '#markup' => $output,
  );

  return $build;
}

/**
 * @param int $sid
 * @return array
 */
function drd_server_view_heartbeat($sid) {
  include_once('drd.monitor.inc');
  $build['info'] = drd_server_info($sid);

  $url = drd_url_by_id('server', $sid);
  $output = '';
  drd_charts('server', $sid, $url, $output);
  $build['detail'] = array(
    '#markup' => $output,
  );

  return $build;
}

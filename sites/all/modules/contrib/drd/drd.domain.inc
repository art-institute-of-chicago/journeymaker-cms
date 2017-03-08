<?php

/**
 * Build the form to edit domain properties.
 *
 * @param array $form
 * @param array $form_state
 * @param int $did
 * @return array
 */
function drd_domain_edit($form, &$form_state, $did) {
  $domain = drd_domain_load($did);

  $form['#did'] = $did;
  $form['#did_settings'] = $domain->settings;

  $form['serverinfo'] = drd_server_info($domain->sid);

  $form['domain'] = array(
    '#type' => 'container',
  );
  $form['domain']['name'] = array(
    '#type' => 'textfield',
    '#title' => t('Name'),
    '#default_value' => $domain->name,
    '#disabled' => TRUE,
  );
  $form['domain']['url'] = array(
    '#type' => 'textfield',
    '#title' => t('URL'),
    '#default_value' => $domain->url,
    '#disabled' => TRUE,
  );

  $form['domain']['usessl'] = array(
    '#type' => 'checkbox',
    '#title' => t('Use SSL'),
    '#default_value' => $domain->usessl,
  );
  $form['domain']['status'] = array(
    '#type' => 'checkbox',
    '#title' => t('Enabled'),
    '#default_value' => $domain->status,
  );

  _drd_tags_build_form('domain', $did, $form);

  $sub_form = drd_action_remote('drd.config.domain.read', 'domain', $did);
  if (!empty($sub_form) && is_array($sub_form)) {
    $form = array_merge($form, $sub_form);
  }

  drd_requirements_build_form($form, $domain);

  $form['actions'] = array('#type' => 'actions');
  $form['actions']['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Save'),
  );
  return $form;
}

/**
 * Store the edited domain properties.
 *
 * @param array $form
 * @param array $form_state
 */
function drd_domain_edit_submit($form, &$form_state) {
  $did = $form['#did'];
  $values = $form_state['values'];
  $settings = $form['#did_settings'];
  $changed = (serialize($settings['ignore']) != serialize($values['drd_requirements_ignore']));
  $settings['ignore'] = $values['drd_requirements_ignore'];
  $update = array(
    'usessl' => $values['usessl'],
    'status' => $values['status'],
    'settings' => serialize($settings),
  );
  if ($changed) {
    $requirements = drd_domain_load_requirements($did);
    $update += _drd_update_domain_requirements($requirements, $settings['ignore']);
  }
  db_update('drd_domain')
    ->fields($update)
    ->condition('did', $did)
    ->execute();

  _drd_tags_save('domain', $form['#did'], $values['tags']);

  drd_action_remote('drd.config.domain.save', 'domain', $form['#did'], $values);
}

/**
 * Callback to display a page with all domain details.
 *
 * @param int $did
 * @param string $mode
 * @return array
 */
function drd_domain_view($did, $mode = 'status') {
  $build['info'] = drd_domain_info($did);
  $build['status'] = drd_domain_status($did);

  drd_action_build_form($form, 'domain');
  $url = drd_url_by_id('domain', $did);
  $output = '';
  drd_info('domain', $did, $url, $output, $mode);
  $form = drupal_get_form('drd_action_form', 'domain', $did);
  $build['actions_form'] = array(
    '#markup' => drupal_render($form),
  );
  $build['detail'] = array(
    '#markup' => $output,
  );

  return $build;
}

/**
 * Callback to display heartbeat data for a specific domain.
 *
 * @param int $did
 * @return array
 */
function drd_domain_view_heartbeat($did) {
  include_once('drd.monitor.inc');
  $build['info'] = drd_domain_info($did);

  $url = drd_url_by_id('domain', $did);
  $output = '';
  drd_charts('domain', $did, $url, $output);
  $build['detail'] = array(
    '#markup' => $output,
  );

  return $build;
}

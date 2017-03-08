<?php

/**
 * Settings form for the DRD module.
 *
 * @param array $form
 * @param array $form_state
 * @return array
 */
function drd_admin_settings($form, $form_state) {
  drupal_set_title(t('Drupal Remote Dashboard - Settings'));

  $form['drd_debug'] = array(
    '#type' => 'checkbox',
    '#title' => t('Debug'),
    '#default_value' => variable_get('drd_debug', FALSE),
  );
  $form['drd_debug_export'] = array(
    '#type' => 'submit',
    '#value' => t('Export Debug Log'),
    '#states' => array(
      'visible' => array(
        'input#edit-drd-debug' => array('checked' => TRUE),
      ),
    ),
  );
  $form['drd_cluster_mode'] = array(
    '#type' => 'checkbox',
    '#title' => t('Operate on a cluster behind load balancer'),
    '#default_value' => variable_get('drd_cluster_mode', FALSE),
  );
  $form['cluster_mode_details'] = array(
    '#type' => 'container',
    '#states' => array(
      'visible' => array(
        'input#edit-drd-cluster-mode' => array('checked' => TRUE),
      ),
    ),
  );
  $form['cluster_mode_details']['drd_cluster_mode_token'] = array(
    '#type' => 'textfield',
    '#title' => t('Cluster mode token'),
    '#default_value' => variable_get('drd_cluster_mode_token', user_password(20)),
    '#disabled' => TRUE,
  );
  $form['drd_items_in_lists'] = array(
    '#type' => 'textfield',
    '#title' => t('Number of items in lists'),
    '#default_value' => variable_get('drd_items_in_lists', 25),
  );

  // Defaults to show only active or installed sites in domain list. Override with filter.
  $form['drd_domain_list_hide_inactive'] = array(
    '#type' => 'checkbox',
    '#title' => t('Hide inactive domains'),
    '#default_value' => variable_get('drd_domain_list_hide_inactive', FALSE)
  );
  $form['drd_domain_list_hide_uninstalled'] = array(
    '#type' => 'checkbox',
    '#title' => t('Hide uninstalled domains'),
    '#default_value' => variable_get('drd_domain_list_hide_uninstalled', FALSE)
  );

  // Display regular tasks
  $tasks = variable_get('drd_tasks', array());
  if (!empty($tasks)) {
    $actions = module_invoke_all('drd_actions');
    $form['tasks'] = array(
      '#type' => 'fieldset',
      '#title' => t('Tasks'),
    );
    foreach ($tasks as $key => $task) {
      $def_key = 'def_' . $key;
      $form['tasks'][$def_key] = array(
        '#type' => 'container',
      );
      $form['tasks'][$def_key]['button_' . $def_key] = array(
        '#type' => 'submit',
        '#value' => t('Delete task #!n', array('!n' => $key)),
      );
      $form['tasks'][$def_key]['info'] = array(
        '#markup' => t('@label: execute every !period, next execution scheduled for !time', array(
          '@label' => $actions[$task['action']]['label'],
          '!period' => format_interval($task['period']),
          '!time' => format_date($task['next']),
        )),
      );
    }
  }

  // Globally ignore requirements
  drd_requirements_build_form($form);

  $form['#submit'][] = 'drd_admin_settings_submit';

  return system_settings_form($form);
}

/**
 * Submit handler for the settings form.
 *
 * @param array $form
 * @param array $form_state
 */
function drd_admin_settings_submit($form, $form_state) {
  if ($form_state['values']['op'] == $form_state['values']['drd_debug_export']) {
    _drd_export_debug_log();
    drupal_goto('admin/drd/settings');
  }
  $parts = explode('#', $_POST['op']);
  if (count($parts) > 1) {
    $key = (int)$parts[1];
    $tasks = variable_get('drd_tasks', array());
    unset($tasks[$key]);
    variable_set('drd_tasks', $tasks);
  }

  // Handle the requirements ignore settings
  $ignore = $form_state['values']['drd_requirements_ignore'];
  $changed = (serialize(variable_get('drd_requirements_ignore', array())) != serialize($ignore));
  if ($changed) {
    variable_set('drd_requirements_ignore', $ignore);
    $ids = drd_domain_load_all('settings');
    foreach ($ids as $id => $settings) {
      if (!empty($settings)) {
        $settings = unserialize($settings);
        if (!isset($settings['ignore'])) {
          $settings['ignore'] = array();
        }
      }
      else {
        $settings = array('ignore' => array());
      }
      $requirements = drd_domain_load_requirements($id);
      $update = _drd_update_domain_requirements($requirements, $settings['ignore']);
      db_update('drd_domain')
        ->fields($update)
        ->condition('did', $id)
        ->execute();
    }
  }
}

/**
 * Form to add a new task for either server or domain which will be executed
 * by cron on a regular basis.
 *
 * @param array $form
 * @param array $form_state
 * @param string $mode
 * @return array
 */
function drd_admin_add_task($form, $form_state, $mode) {
  drupal_set_title(t('Add new regular task'));
  $form['period'] = array(
    '#type' => 'textfield',
    '#title' => t('Period in seconds between tasks'),
  );
  drd_action_build_form($form, $mode, TRUE);
  $form['options']['submit']['#value'] = t('Save');
  return $form;
}

/**
 * Submit handler to add a new regular task to cron.
 *
 * @param array $form
 * @param array $form_state
 */
function drd_admin_add_task_submit($form, $form_state) {
  $values = $form_state['values'];
  $tasks = variable_get('drd_tasks', array());
  $tasks[] = array(
    'mode' => $form['#drd-mode'],
    'next' => REQUEST_TIME + $values['period'],
    'period' => $values['period'],
    'action' => $values['action'],
    'param' => $values,
  );
  variable_set('drd_tasks', $tasks);
  drupal_goto('admin/drd/settings');
}

/**
 * Menu callback to display a confirmation form before purging all update
 * status data from remote cores and domains.
 *
 * @param array $form
 * @param array $form_state
 * @return array
 */
function drd_admin_reset_projects($form, $form_state) {
  $form['info'] = array(
    '#markup' => t('Are you sure? This will delete all usage data about modules and themes and you will have to re-collect them from your remote domains.'),
  );
  $form['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Yes, purge all data!'),
  );
  return $form;
}

/**
 * Submit handler to finally purge all update status data from remote cores
 * and domains.
 *
 * @param array $form
 * @param array $form_state
 */
function drd_admin_reset_projects_submit($form, $form_state) {
  db_truncate('drd_project_release_domain')->execute();
  db_truncate('drd_project_release')->execute();
  db_truncate('drd_project')->execute();
  drupal_set_message('Usage data has been purged.');
  drupal_goto('admin/drd/settings');
}

/**
 * Exports all current debug log entries from watchdog to an HTML file.
 */
function _drd_export_debug_log() {
  if (!module_exists('dblog')) {
    drupal_set_message('Module dblog not enabled, nothing to export.', 'error');
    return;
  }
  $entries = db_select('watchdog', 'w')
    ->fields('w', array('message', 'variables', 'timestamp'))
    ->condition('w.type', 'DRD')
    ->orderBy('w.wid')
    ->execute()
    ->fetchAll();
  if (empty($entries)) {
    drupal_set_message('No debug entries available.', 'warning');
    return;
  }
  $filename = 'drd_debug_' . format_date(REQUEST_TIME, 'custom', 'Ymd_hi') . '.html';
  $n = 0;
  $html = '<html><body><h1>DRD Debug Log</h1>';
  foreach ($entries as $entry) {
    $n++;
    $html .= '<p><span>' . $entry->timestamp . '</span> ' . t($entry->message, unserialize($entry->variables)) . '</p>';
  }
  $html .= '</body></html>';
  file_put_contents(file_directory_temp() . '/' . $filename, $html);
  drupal_set_message(t('DRD debug exported with !n records. !link', array('!n' => $n, '!link' => l('Download', 'system/temporary/' . $filename))));
}

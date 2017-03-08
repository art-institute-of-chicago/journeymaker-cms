<?php

/**
 * Builds the output for the Drupal core list in the DRD dashboard including
 * filters, actions and the list of Drupal cores with their details.
 *
 * @return array
 */
function drd_server() {
  if (isset($_POST['op'])) {
    if ($_POST['op'] == t('Reset')) {
      $_SESSION['drd']['filter']['server'] = array();
      drupal_goto('admin/drd');
    }
    if ($_POST['op'] == t('Execute')) {
      if (empty($_POST['action'])) {
        drupal_set_message('No action selected.', 'warning');
      }
      else if (empty($_POST['server'])) {
        drupal_set_message('No Drupal core selected.', 'warning');
      }
      else {
        drd_action('server', $_POST['server'], $_POST['action']);
      }
    }
  }
  drupal_set_title(t('Drupal Remote Dashboard - Cores'));
  $build['server_filter_form'] = drupal_get_form('drd_server_filter_form');
  $build['server_list'] = drupal_get_form('drd_server_list');

  return $build;
}

/**
 * Builds the Drupal core list according to the current filter settings.
 *
 * @param array $form
 * @param array $form_state
 * @return string
 */
function drd_server_list($form, &$form_state) {
  $header = array(
    'status' => array('data' => t('Status'), 'field' => 's.status'),
    'name'   => array('data' => t('Name'),   'field' => 's.name', 'sort' => 'asc'),
    'usessl' => array('data' => t('SSL'),    'field' => 's.usessl'),
    //'svn'    => array('data' => t('SVN'),    'field' => 's.svnenable'),
    'drupal' => array('data' => t('Drupal'), 'field' => 's.drupalversion'),
    'analysis'=> array('data' => t('Analysis')),
    'domains'=> array('data' => t('Domains')),
  );

  $result = drd_get_servers($_SESSION['drd']['filter']['server'], TRUE, $header);

  if ($result->rowCount() > 0) {
    drd_action_build_form($form, 'server');
  }

  $server = array();
  foreach ($result as $s) {
    $server[$s->sid] = drd_server_build_row($s);
  }

  $form['server'] = array(
    '#type' => 'tableselect',
    '#header' => $header,
    '#options' => $server,
    '#empty' => t('No Drupal core available.'),
  );
  $form['server_list_pager'] = array('#theme' => 'pager');

  drupal_add_js(array('drd' => array('urlAjax' => url('drd/ajax/server/'))), 'setting');

  return $form;
}

/**
 * @param stdClass $s
 * @return array
 */
function drd_server_build_row($s) {
  static $destination;
  static $charts;
  if (!isset($destination)) {
    $destination = drupal_get_destination();
  }
  if (!isset($charts)) {
    $charts = module_exists('chart');
  }

  $name = (strlen($s->name) > 35) ? substr($s->name, 0, 30) . '...' : $s->name;
  $count = drd_server_domains_count($s->sid);
  $warning_or = db_or()
    ->condition('d.status_w_health', 1)
    ->condition('d.status_w_secure', 1)
    ->condition('d.status_w_tuning', 1)
    ->condition('d.status_w_seo', 1)
    ->condition('d.status_w_other', 1);
  $error_or = db_or()
    ->condition('d.status_e_health', 1)
    ->condition('d.status_e_secure', 1)
    ->condition('d.status_e_tuning', 1)
    ->condition('d.status_e_seo', 1)
    ->condition('d.status_e_other', 1);
  $count_warning = db_select('drd_domain', 'd')->fields('d', array('did'))->condition('d.sid', $s->sid)->condition($warning_or)->countQuery()->execute()->FetchField();
  $count_error = db_select('drd_domain', 'd')->fields('d', array('did'))->condition('d.sid', $s->sid)->condition($error_or)->countQuery()->execute()->FetchField();
  $warning = ($count_warning) ? drd_icon('warning') : '';
  $error   = ($count_error) ? drd_icon('error') : '';
  $edit = drd_icon('edit', 'pencil');
  $link = drd_icon('open', 'extlink');
  $heartbeat = ($charts) ? l(drd_icon('heartbeat', 'heart'), 'admin/drd/server/' . $s->sid . '/heartbeat', array('html' => TRUE)) : '';
  $status = ($s->status) ? t('Active') : t('Disabled');

  $result = array(
    '#attributes' => array(
      'id' => 'drd-server-' . $s->sid,
      'class' => array('drd-data'),
    ),
    'status' => $status,
    'name'   => array(
      'data' => '<span title="' . $s->url . '">' . l($name, 'admin/drd/server/' . $s->sid) . '</span>' .
        l($edit, 'admin/drd/server/' . $s->sid . '/edit', array('html' => TRUE, 'query' => $destination, 'attributes' => array('title' => $s->name))) .
        $heartbeat .
        l($link, drd_url($s), array('html' => TRUE, 'attributes' => array('target' => '_blank'))),
      'class' => array('name'),
    ),
    'usessl' => ($s->usessl) ? t('Yes') : t('No'),
    //'svn'    => ($s->usessl) ? t('Yes') : t('No'),
    'drupal' => $s->drupalversion,
    'analysis' => $warning . $error,
    'domains'=> l($count, 'admin/drd/domains/' . $s->sid),
  );
  return $result;
}

/**
 * Builds the filter form for the Drupal core list.
 *
 * @param array $form
 * @param array $form_state
 * @param bool $module_status
 * @return array
 */
function drd_server_filter_form($form, &$form_state, $module_status = FALSE) {
  if (!isset($_SESSION['drd']['filter']['server']['status'])) {
    $_SESSION['drd']['filter']['server']['status'] = -1;
  }
  if (!isset($_SESSION['drd']['filter']['server']['ssl'])) {
    $_SESSION['drd']['filter']['server']['ssl'] = -1;
  }
  if (!isset($_SESSION['drd']['filter']['server']['tags'])) {
    $_SESSION['drd']['filter']['server']['tags'] = array();
  }
  if (!isset($_SESSION['drd']['filter']['server']['drupalversions'])) {
    $_SESSION['drd']['filter']['server']['drupalversions'] = array();
  }
  if (!isset($_SESSION['drd']['filter']['server']['module_status'])) {
    $_SESSION['drd']['filter']['server']['module_status'] = FALSE;
  }
  $form['filter_wrapper'] = array(
    '#type' => 'fieldset',
    '#title' => t('Filter'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['filter_wrapper']['filter_status'] = array(
    '#type' => 'select',
    '#title' => t('Status'),
    '#options' => array(
      '-1' => t('- any -'),
      '1' => t('Active'),
      '0' => t('Disabled'),
    ),
    '#default_value' => $_SESSION['drd']['filter']['server']['status'],
  );
  $form['filter_wrapper']['filter_ssl'] = array(
    '#type' => 'select',
    '#title' => t('SSL'),
    '#options' => array(
      '-1' => t('- any -'),
      '1' => t('Yes'),
      '0' => t('No'),
    ),
    '#default_value' => $_SESSION['drd']['filter']['server']['ssl'],
  );
  $form['filter_wrapper']['filter_drupalversions'] = array(
    '#type' => 'select',
    '#title' => t('Drupal Versions'),
    '#multiple' => TRUE,
    '#options' => drd_drupalversion_load_all(),
    '#default_value' => $_SESSION['drd']['filter']['server']['drupalversions'],
  );
  $form['filter_wrapper']['filter_module_status'] = array(
    '#type' => 'checkbox',
    '#title' => t('Include disabled modules'),
    '#default_value' => $_SESSION['drd']['filter']['server']['module_status'],
    '#access' => $module_status,
  );

  _drd_tags_build_filter_form('server', $form);

  $form['filter_wrapper']['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Apply'),
  );
  $form['filter_wrapper']['reset'] = array(
    '#type' => 'submit',
    '#value' => t('Reset'),
  );
  return $form;
}

/**
 * @param array $form
 * @param array $form_state
 */
function drd_server_filter_form_submit($form, $form_state) {
  $values = $form_state['values'];
  if ($_POST['op'] == t('Apply')) {
    $_SESSION['drd']['filter']['server']['status'] = $values['filter_status'];
    $_SESSION['drd']['filter']['server']['ssl'] = $values['filter_ssl'];
    $_SESSION['drd']['filter']['server']['tags'] = $values['filter_tags'];
    $_SESSION['drd']['filter']['server']['drupalversions'] = $values['filter_drupalversions'];
    $_SESSION['drd']['filter']['server']['module_status'] = $values['filter_module_status'];
  }
}

/**
 * Builds the filter query for the Drupal core list.
 *
 * @param SelectQuery $query
 * @param array $filter
 */
function drd_server_build_filter_query(&$query, $filter) {
  if (!empty($filter['sids'])) {
    $query->condition('d.sid', $filter['sids']);
  }
  if ($filter['status'] >= 0) {
    $query->condition('s.status', $filter['status']);
  }
  if ($filter['ssl'] >= 0) {
    $query->condition('s.usessl', $filter['ssl']);
  }
  if (!empty($filter['drupalversions'])) {
    $query->condition('s.drupalversion', $filter['drupalversions']);
  }
  if (!empty($filter['tags'])) {
    $query->join('drd_term', 't', 's.sid = t.oid');
    $query->condition('t.mode', 'server')
        ->condition('t.tid', $filter['tags']);
  }
}

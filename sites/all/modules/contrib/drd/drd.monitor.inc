<?php

define('DRD_CHART_NORMAL', 0);
define('DRD_CHART_INCREMENTS', 1);

function _drd_monitor_prev_day($time) {
  $start = mktime(0, 0, 0, date('n', $time), date('j', $time)-1, date('Y', $time));
  $end = mktime(23, 59, 59, date('n', $time), date('j', $time)-1, date('Y', $time));
  return array($start, $end);
}

function _drd_monitor_prev_week($time) {
  $day_one = $time - (date('w', $time) * 24 * 60 * 60);
  list($start, $end) = _drd_monitor_prev_day($day_one);
  $start = $end - (7 * 24 * 60 * 60) + 1;
  return array($start, $end);
}

function _drd_monitor_prev_month($time) {
  $start = mktime(0, 0, 0, date('n', $time)-1, 1, date('Y', $time));
  $end = mktime(23, 59, 59, date('n', $time), 0, date('Y', $time));
  return array($start, $end);
}

function _drd_monitor_prev_quarter($time) {
  $month = date('n', $time);
  if ($month < 4) {
    $end_month = 1;
  }
  else if ($month < 7) {
    $end_month = 4;
  }
  else if ($month < 10) {
    $end_month = 7;
  }
  else {
    $end_month = 10;
  }
  $start = mktime(0, 0, 0, $end_month-3, 1, date('Y', $time));
  $end = mktime(23, 59, 59, $end_month, 0, date('Y', $time));
  return array($start, $end);
}

function _drd_monitor_prev_year($time) {
  $start = mktime(0, 0, 0, 1, 1, date('Y', $time)-1);
  $end = mktime(23, 59, 59, 12, 31, date('Y', $time)-1);
  return array($start, $end);
}

function drd_cleanup_monitor_values() {
  $period_callbacks = array(
    'days' => '_drd_monitor_prev_day',
    'weeks' => '_drd_monitor_prev_week',
    'months' => '_drd_monitor_prev_month',
    'quarters' => '_drd_monitor_prev_quarter',
    'years' => '_drd_monitor_prev_year',
  );
  $type = 0;
  foreach ($period_callbacks as $callback) {
    $type++;
    $current = REQUEST_TIME;
    while (TRUE) {
      list($start, $end) = $callback($current);
      if (db_select('drd_monitor_values', 'mv')
          ->fields('mv', array('did'))
          ->condition('mv.type', $type)
          ->condition('mv.timestamp', $end)
          ->countQuery()
          ->execute()
          ->fetchField() > 0) {
        // We have processed that period already and can cancel
        break;
      }
      $dids = db_select('drd_monitor_values', 'mv')
          ->fields('mv', array('did'))
          ->condition('mv.type', 0)
          ->condition('mv.timestamp', $end, '<=')
          ->distinct()
          ->execute()
          ->fetchAllKeyed(0, 0);
      if (empty($dids)) {
        // Period too early, no values available, cancel the loop
        break;
      }
      foreach ($dids as $did) {
        $query = db_select('drd_monitor_values', 'mv')
            ->condition('mv.did', $did)
            ->condition('mv.type', 0)
            ->condition('mv.timestamp', $start, '>=')
            ->condition('mv.timestamp', $end, '<=');
        $query->addExpression('MAX(user_accounts)', 'user_accounts');
        $query->addExpression('MAX(user_sessions)', 'user_sessions');
        $query->addExpression('MAX(user_authenticated)', 'user_authenticated');
        $query->addExpression('MAX(node_count)', 'node_count');
        $query->addExpression('MAX(comment_count)', 'comment_count');
        $query->addExpression('MAX(files_temp_count)', 'files_temp_count');
        $query->addExpression('MAX(files_temp_size)', 'files_temp_size');
        $query->addExpression('MAX(files_perm_count)', 'files_perm_count');
        $query->addExpression('MAX(files_perm_size)', 'files_perm_size');
        $query->addExpression('SUM(watchdog_emergency)', 'watchdog_emergency');
        $query->addExpression('SUM(watchdog_alert)', 'watchdog_alert');
        $query->addExpression('SUM(watchdog_critical)', 'watchdog_critical');
        $query->addExpression('SUM(watchdog_error)', 'watchdog_error');
        $query->addExpression('SUM(watchdog_warning)', 'watchdog_warning');
        $query->addExpression('SUM(watchdog_notice)', 'watchdog_notice');
        $query->addExpression('SUM(watchdog_info)', 'watchdog_info');
        $query->addExpression('SUM(watchdog_debug)', 'watchdog_debug');
        $record = $query->execute()->fetchObject();
        $record->did = $did;
        $record->timestamp = $end;
        $record->type = $type;
        drupal_write_record('drd_monitor_values', $record);
      }
      $current = $start;
    }
  }
}

/**
 *
 * @param string $mode
 * @param int $id
 * @param string $url
 * @param string $output
 */
function drd_charts($mode, $id, $url, &$output) {
  if ($mode == 'server') {
    $dids = array_keys(db_select('drd_domain', 'd')
        ->fields('d', array('did'))
        ->condition('d.sid', $id)
        ->execute()
        ->fetchAllKeyed(0, 0));
  }
  else {
    $dids = array($id);
  }
  $def = _drd_monitor_periods();
  $parts = explode('/', $_GET['q']);
  $period = array_pop($parts);
  if (empty($def[$period])) {
    $period = '24h';
  }
  $start = $def[$period]['start'];
  $end = $def[$period]['end'];
  $type = $def[$period]['type'];
  $query = db_select('drd_monitor_values', 'mv')
      ->fields('mv')
      ->condition('mv.did', $dids)
      ->condition('mv.type', $type)
      ->condition('mv.timestamp', $start, '>=')
      ->condition('mv.timestamp', $end, '<=')
      ->orderBy('mv.timestamp', 'ASC')
      ->execute();

  $charts = array(
    'user' => array(
      '#theme' => 'chart',
      '#chart_id' => 'chart_user_' . $id,
      '#title' => chart_title(t('Visitors'), 'cc0000', 15),
      '#type' => CHART_TYPE_LINE,
      '#size' => chart_size(800, 200),
      '#adjust_resolution' => TRUE,
      '#legends' => array(
        t('Sessions'),
        t('Authenticated users'),
      ),
      '#data_colors' => array('00ff00', '0000ff', ),
    ),
    'count' => array(
      '#theme' => 'chart',
      '#chart_id' => 'chart_counts_' . $id,
      '#title' => chart_title(t('New Entities'), 'cc0000', 15),
      '#type' => CHART_TYPE_BAR_V_GROUPED,
      '#size' => chart_size(800, 200),
      '#bar_size' => chart_bar_size(10, 3),
      '#adjust_resolution' => TRUE,
      '#legends' => array(
        t('User Accounts'),
        t('Nodes'),
        t('Comments'),
        t('Permanent files'),
        t('Temporary files'),
      ),
      '#data_colors' => array('00ff00', '0000ff', '00ffff', 'ff0000', 'ffff00'),
    ),
    'watchdog' => array(
      '#theme' => 'chart',
      '#chart_id' => 'chart_watchdog_' . $id,
      '#title' => chart_title(t('Watchdog'), 'cc0000', 15),
      '#type' => CHART_TYPE_LINE,
      '#size' => chart_size(800, 200),
      '#adjust_resolution' => TRUE,
      '#legends' => array(
        t('Emergency'),
        t('Alert'),
        t('Critical'),
        t('Error'),
        t('Warning'),
      ),
      '#data_colors' => array('ff0000', 'ff9900', 'ffff00', '00ff00', '0000ff'),
    ),
  );

  $values = array(
    'user' => array(
      'mode' => DRD_CHART_NORMAL,
      'user_sessions' => array(),
      'user_authenticated' => array(),
    ),
    'count' => array(
      'mode' => DRD_CHART_INCREMENTS,
      'user_accounts' => array(),
      'node_count' => array(),
      'comment_count' => array(),
      'files_perm_count' => array(),
      'files_temp_count' => array(),
    ),
    'watchdog' => array(
      'mode' => DRD_CHART_NORMAL,
      'watchdog_emergency' => array(),
      'watchdog_alert' => array(),
      'watchdog_critical' => array(),
      'watchdog_error' => array(),
      'watchdog_warning' => array(),
    ),
  );
  while ($record = $query->fetchObject()) {
    foreach ($values as $key => $items) {
      foreach ($items as $ikey => $v) {
        if (isset($record->$ikey)) {
          if (!isset($values[$key][$ikey][$record->timestamp])) {
            $values[$key][$ikey][$record->timestamp] = 0;
          }
          $values[$key][$ikey][$record->timestamp] += $record->$ikey;
        }
      }
    }
  }
  foreach ($values as $chart => $lists) {
    $mode = $values[$chart]['mode'];
    unset($lists['mode']);
    $ckeys = array();
    $cvalues = array();
    foreach ($lists as $key => $list) {
      $charts[$chart]['#data'][$key] = _drd_charts_optimize($list, $mode);
      $ckeys = array_merge($ckeys, array_keys($charts[$chart]['#data'][$key]));
      $cvalues = array_merge($cvalues, array_values($charts[$chart]['#data'][$key]));
    }
    $minY = ($mode == DRD_CHART_INCREMENTS) ? 0 : min($cvalues);
    $maxY = max($cvalues);
    $minX = min($ckeys);
    $maxX = max($ckeys);
    if ($maxX - $minX > (60*60*24*7)) {
      $dateformat = 'd.m.Y';
    }
    else if ($maxX - $minX <= (60*60*24)) {
      $dateformat = 'H:i';
    }
    else {
      $dateformat = 'D';
    }
    $charts[$chart]['#mixed_axis_labels'][CHART_AXIS_Y_LEFT][0][] = chart_mixed_axis_range_label($minY, $maxY);
    $charts[$chart]['#mixed_axis_labels'][CHART_AXIS_X_BOTTOM][1][] = chart_mixed_axis_label(format_date(intval($minX), 'custom', $dateformat));
    $charts[$chart]['#mixed_axis_labels'][CHART_AXIS_X_BOTTOM][1][] = chart_mixed_axis_label(format_date(intval($minX + (($maxX - $minX) / 2)), 'custom', $dateformat));
    $charts[$chart]['#mixed_axis_labels'][CHART_AXIS_X_BOTTOM][1][] = chart_mixed_axis_label(format_date(intval($maxX), 'custom', $dateformat));
  }

  $output = drupal_render($charts);
}

function _drd_charts_optimize($values, $mode) {
  ksort($values);
  $result = array();
  $current = FALSE;
  $prev = FALSE;
  foreach ($values as $key => $value) {
    if ($current === FALSE && $mode == DRD_CHART_INCREMENTS) {
      $current = $value;
      $prev = $key;
    }
    if ($current !== $value) {
      if ($prev !== FALSE && $mode != DRD_CHART_INCREMENTS) {
        $result[$prev] = $current;
      }
      switch ($mode) {
        case DRD_CHART_NORMAL:
          $set = $value;
          break;
        case DRD_CHART_INCREMENTS:
          $set = ($value - $current);
      }
      $result[$key] = $set;
      $current = $value;
    }
    $prev = $key;
  }
  $count = count($result);
  if ($count == 1 && $mode != DRD_CHART_INCREMENTS) {
    $result[$key] = $value;
  }
  if (empty($result)) {
    $result[REQUEST_TIME] = 0;
  }
  if ($count < 100) {
    return $result;
  }

  // Shorten the list, otherwise the URL to call the chart from Google-API will be too long
  $combine = floor($count / 50);
  $sresult = array();
  $rkeys = array_keys($result);
  $rvalues = array_values($result);
  while (!empty($rkeys)) {
    $group = array();
    for ($k = 0; $k < $combine; $k++) {
      if (!empty($rkeys)) {
        $key = array_shift($rkeys);
        $value = array_shift($rvalues);
        $group[$key] = $value;
      }
    }
    $keys = array_keys($group);
    $key = floor((min($keys) + max($keys)) / 2);
    $value = max(array_values($group));
    $sresult[$key] = $value;
  }
  return $sresult;
}

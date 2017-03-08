<?php

/**
 * @param string $mode
 * @param int $sid
 * @param int $did
 * @return array
 */
function drd_modules($mode = 'all', $sid = 0, $did = 0) {
  drupal_set_title(t('Drupal Remote Dashboard - Modules'));

  $query = db_select('drd_project', 'p');
  $query->innerJoin('drd_project_release', 'pr', 'p.pid = pr.pid');
  $query->innerJoin('drd_project_release_server', 'prs', 'pr.rid = prs.rid');
  $query->innerJoin('drd_server', 's', 'prs.sid = s.sid');
  $query->orderBy('p.type');
  $query->orderBy('p.name');
  $query->orderBy('pr.version');
  $query->orderBy('s.sid');
  $query->fields('p')
    ->fields('pr', array('rid', 'version'))
    ->fields('s', array('sid'));
  $query->addField('pr', 'link', 'pr_link');
  $query->addField('pr', 'timestamp', 'pr_timestamp');
  $query->addField('pr', 'details', 'pr_details');
  $query->addField('s', 'name', 'server_name');
  if ($sid) {
    $query->condition('s.sid', $sid);
    $build['info'] = drd_server_info($sid);
  }
  elseif ($did) {
    $query->innerJoin('drd_project_release_domain', 'prd', 'pr.rid = prd.rid');
    $query->condition('prd.did', $did);
    $build['info'] = drd_domain_info($did);
  }
  else {
    include_once 'drd.server.pages.inc';
    $build['info'] = drupal_get_form('drd_server_filter_form', TRUE);
    drd_server_build_filter_query($query, $_SESSION['drd']['filter']['server']);
    if (empty($_SESSION['drd']['filter']['server']['module_status'])) {
      $query->innerJoin('drd_project_release_domain', 'prd', 'pr.rid = prd.rid');
    }
  }
  $modules = $query->execute();
  $servers = array();
  $data = array();
  while ($module = $modules->fetch()) {
    $details = unserialize($module->details);
    $info = _drd_modules_status($details[$module->version]['status']);
    if ($mode == 'updates' && $info['type'] == 'ok') {
      // Ignore this.
      continue;
    }
    if ($mode == 'security' && $info['type'] != 'security') {
      // Ignore this.
      continue;
    }

    if (empty($data[$module->type][$module->pid])) {
      $data[$module->type][$module->pid] = array(
        'title' => $module->title,
        'link' => $module->link,
        'details' => $details,
        'versions' => array(),
      );
    }
    if (empty($servers[$module->sid])) {
      $servers[$module->sid] = $module;
    }
    if (empty($data[$module->type][$module->pid]['versions'][$module->version])) {
      $data[$module->type][$module->pid]['versions'][$module->version] = array(
        'rid' => $module->rid,
        'version' => $module->version,
        'details' => unserialize($module->pr_details),
        'servers' => array(),
      );
    }
    $data[$module->type][$module->pid]['versions'][$module->version]['servers'][$module->sid] = TRUE;
  }

  $header = array(
    'project' => array('data' => ''),
    'version' => array('data' => ''),
    'update' => array('data' => ''),
  );
  foreach ($servers as $server) {
    $header['s_' . $server->sid] = array('data' => '<span title="' . $server->server_name . '">' . substr($server->server_name, 0, 4) . '...</span>', 'class' => 'server');
  }

  $rows = array();
  $c = 3 + count($servers);
  $link = drd_icon('open', 'extlink');
  foreach ($data as $type => $installs) {
    $rows[$type] = array(
      'data' => array(
        'project' => array(
          'data' => '<h2>' . $type . '</h2>',
          'colspan' => $c,
        ),
      ),
      'class' => array('drd-type'),
    );

    foreach ($installs as $pid => $install) {
      $link_text = empty($install['link']) ? '' : l($link, $install['link'], array('html' => TRUE, 'external' => TRUE, 'attributes' => array('target' => '_blank')));
      $rows['p_' . $pid] = array(
        'data' => array(
          'project' => array(
            'data' => $install['title'] . $link_text,
            'colspan' => $c,
          ),
        ),
        'class' => array('drd-project'),
      );

      foreach ($install['versions'] as $v => $version) {
        $info = _drd_modules_status($install['details'][$version['version']]['status']);
        $extra = empty($info['title']) ? '' : '<span class="description">' . $info['title'] . '</span>';
        $update = '';
        if (empty($info['ok'])) {
          $r = $install['details'][$version['version']]['recommended'];
          if (empty($install['versions'][$r])) {
            $data[$type][$pid]['versions'][$r]['details'] = unserialize(
              db_select('drd_project_release', 'pr')
                ->fields('pr', array('details'))
                ->condition('pr.pid', $pid)
                ->condition('pr.version', $r)
                ->execute()
                ->fetchField()
            );
            $install['versions'][$r] = $data[$type][$pid]['versions'][$r];
          }
          if (empty($install['versions'][$r])) {
            $update = 'error';
          }
          else {
            $update_details = $install['versions'][$r]['details'];
            $label = empty($update_details['version']) ? 'unknown' : $update_details['version'];
            $update_extras = '<span class="drd-display">' . drd_icon('show', 'info');
            $update_extras .= '<div class="detail">';
            $update_extras .= '<p>' . t('Notes') . l($link, $update_details['release_link'], array('html' => TRUE, 'external' => TRUE, 'attributes' => array('target' => '_blank'))) . '</p>';
            $update_extras .= '<p>' . t('Date') . ': <strong>' . format_date($update_details['date']) . '</strong></p>';
            $update_extras .= '<p>' . t('File size') . ': <strong>' . format_size($update_details['filesize']) . '</strong></p>';
            $update_extras .= '<p>' . t('MD hash') . ': <strong>' . $update_details['mdhash'] . '</strong></p>';
            if (!empty($update_details['terms']['Release type'])) {
              $update_extras .= '<p>' . t('Release types') . ': <strong>' . implode(', ', $update_details['terms']['Release type']) . '</strong></p>';
            }
            $update_extras .= '</div>';
            $update_extras .= '</span>';
            $update = l($label, $update_details['download_link']) . $update_extras;
          }
        }

        $rows['r_' . $version['rid']] = array(
          'data' => array(
            'project' => array(
              'data' => '',
              'class' => array('empty'),
            ),
            'version' => array(
              'data' => $version['version'] . $extra,
              'class' => array('version'),
            ),
            'update' => array(
              'data' => $update,
              'class' => array('update'),
            ),
          ),
          'class' => array('drd-version', $info['type']),
        );

        $domains_query = db_select('drd_domain', 'd');
        $domains_query->innerJoin('drd_project_release_domain', 'prd', 'd.did = prd.did');
        $domains = $domains_query
          ->fields('d', array('sid', 'did', 'name', 'url'))
          ->condition('prd.rid', $version['rid'])
          ->condition('d.sid', array_keys($servers))
          ->orderBy('d.sid')
          ->orderBy('d.name')
          ->execute()
          ->fetchAll();
        $server_domains = array();
        foreach ($domains as $domain) {
          $server_domains[$domain->sid][] = $domain;
        }
        foreach ($servers as $sid => $server) {
          if (empty($version['servers'][$sid])) {
            $count = '';
          }
          else if (empty($server_domains[$sid])) {
            $count = '0';
          }
          else {
            $count = count($server_domains[$sid]);
            $count .= '<span class="drd-display">' . drd_icon('show', 'info');
            $count .= '<div class="detail">';
            foreach ($server_domains[$sid] as $domain) {
              $count .= '<p>' . l($domain->name . ' (' .  $domain->url . ')', 'admin/drd/domain/' . $domain->did) . '</p>';
            }
            $count .= '</div>';
            $count .= '</span>';
          }
          $rows['r_' . $version['rid']]['data']['s_' . $sid] = $count;
        }
      }
    }
  }
  $build['table'] = array(
    '#markup' => theme('table', array(
      'header' => $header,
      'rows' => $rows,
      'attributes' => array(
        'id' => 'drd-modules',
      ),
      'sticky' => TRUE,
      'empty' => t('No data available.'),
    ))
  );
  return $build;
}

/**
 * @param string $status
 * @return string
 */
function _drd_modules_status_type($status) {
  $info = _drd_modules_status($status);
  return $info['type'];
}

/**
 * @param string $status
 * @return array
 */
function _drd_modules_status($status) {
  $info = array();
  switch ($status) {
    case UPDATE_NOT_SECURE:
    case UPDATE_REVOKED:
      $info['title'] = ' ' . t('(Security update)');
      $info['type'] = 'security';
      break;

    case UPDATE_NOT_SUPPORTED:
      $info['title'] = ' ' . t('(Unsupported)');
      $info['type'] = 'unsupported';
      break;

    case UPDATE_UNKNOWN:
    case UPDATE_NOT_FETCHED:
    case UPDATE_NOT_CHECKED:
      $info['type'] = 'unknown';
      break;

    case UPDATE_NOT_CURRENT:
      $info['type'] = 'recommended';
      break;

    default:
      $info['type'] = 'ok';
      $info['ok'] = TRUE;
  }
  return $info;
}

/**
 * @param array $projects
 * @param stdClass $domain
 * @param bool $reset_all
 */
function _drd_update_projects($projects, $domain, $reset_all = FALSE) {
  if ($reset_all) {
    $dids = array_values(drd_server_domains($domain->sid));
  }
  else {
    $dids = array($domain->did);
  }
  if (!empty($dids)) {
    db_delete('drd_project_release_domain')
      ->condition('did', $dids)
      ->execute();
  }

  // Grab installation status for each project on all domains.
  foreach ($dids as $did) {
    $projects['#drd_status'][$did] = drd_action_remote('drd.status', 'domain', $did);
  }

  foreach ($projects as $name => $project) {
    if ($name == '#drd_status') {
      continue;
    }
    $existing = array_shift(explode('+', $project['existing_version']));

    // Make sure, only one process at a time is creating new records for projects.
    if (!lock_acquire('project_create')) {
      while (lock_wait('project_create')) {
        // Wait until we get the lock
      }
      lock_acquire('project_create');
    }

    $stored_project = db_select('drd_project', 'p')
      ->fields('p')
      ->condition('p.name', $name)
      ->execute()
      ->fetchAssoc();
    if (empty($stored_project)) {
      $stored_project = array(
        'name' => $name,
        'type' => array_shift(explode('-', $project['project_type'])),
        'title' => isset($project['title']) ? $project['title'] : $name,
        'link' => isset($project['link']) ? $project['link'] : '',
        'details' => array(),
      );
      drupal_write_record('drd_project', $stored_project);
    }
    else {
      $stored_project['details'] = unserialize($stored_project['details']);
    }

    lock_release('project_create');

    if (empty($stored_project['details'][$existing]) ||
      !isset($project['status']) ||
      _drd_modules_status_type($project['status']) != 'unknown') {
      /*
       * Only update project details of the existing version if either there
       * is no detail information available yet or if we received valid data
       * from the remote core. This avoids the scenario where the release status
       * was set to unknown if fetching information wasn't possible for what ever
       * reason and we then overwrote valid status information from a previous
       * fetch which then got lost.
       */
      $stored_project['details'][$existing] = array(
        'existing_major' => isset($project['existing_major']) ? $project['existing_major'] : 1,
        'install_type' => isset($project['install_type']) ? $project['install_type'] : '',
        'latest_version' => isset($project['latest_version']) ? $project['latest_version'] : '',
        'recommended' => isset($project['recommended']) ? $project['recommended'] : '',
        'status' => isset($project['status']) ? $project['status'] : '',
      );
      drupal_write_record('drd_project', $stored_project, 'pid');
    }
    $pid = $stored_project['pid'];
    if (empty($project['releases'])) {
      $project['releases'] = array(
        $existing => array(
          'download_link' => '',
          'date' => 0,
        ),
      );
    }
    if (empty($project['releases'][$existing])) {
      $project['releases'][$existing] = array(
        'download_link' => '',
        'date' => 0,
      );
    }
    foreach ($project['releases'] as $version => $release) {
      $stored_release = db_select('drd_project_release', 'r')
        ->fields('r')
        ->condition('r.pid', $pid)
        ->condition('r.version', $version)
        ->execute()
        ->fetchAssoc();
      if (empty($stored_release)) {
        $stored_release = array(
          'pid' => $pid,
          'version' => $version,
          'link' => isset($release['download_link']) ? $release['download_link'] : '',
          'timestamp' => isset($release['date']) ? $release['date'] : 0,
          'details' => $release,
        );
        drupal_write_record('drd_project_release', $stored_release);
      }
    }
    $rid = db_select('drd_project_release', 'r')
      ->fields('r', array('rid'))
      ->condition('r.pid', $pid)
      ->condition('r.version', $existing)
      ->execute()
      ->fetchField();
    foreach ($dids as $did) {
      if ($stored_project['type'] == 'core' || isset($projects['#drd_status'][$did]['projects_enabled'][$name])) {
        $values = array('rid' => $rid, 'did' => $did);
        drupal_write_record('drd_project_release_domain', $values);
      }
    }

    // Store the release id which is currently installed on the server.
    $query = db_select('drd_project', 'p');
    $query->innerJoin('drd_project_release', 'pr', 'p.pid = pr.pid');
    $query->innerJoin('drd_project_release_server', 'prs', 'pr.rid = prs.rid');
    $srids = $query
      ->fields('prs', array('rid'))
      ->condition('p.pid', $pid)
      ->condition('prs.sid', $domain->sid)
      ->execute()
      ->fetchCol();

    if (sizeof($srids) != 1 || $srids[0] != $rid) {
      if (!empty($srids)) {
        db_delete('drd_project_release_server')
          ->condition('rid', array_values($srids))
          ->condition('sid', $domain->sid)
          ->execute();
      }
      $values = array('rid' => $rid, 'sid' => $domain->sid);
      drupal_write_record('drd_project_release_server', $values);
    }
  }
}

<?php

/**
 * @param string $cat
 * @return string
 */
function _drd_requirements_category_label($cat) {
  static $categories;
  if (!isset($categories)) {
    $categories = array(
      'health' => t('Health'),
      'secure' => t('Security'),
      'tuning' => t('Tuning'),
      'seo' => t('SEO'),
      'other' => t('Others'),
    );
  }
  return $categories[$cat];
}

/**
 * @param bool $cat
 * @return array
 */
function _drd_requirements_categories($cat = FALSE) {
  static $categories;
  if (!isset($categories)) {
    $categories = array(
      'health' => array(
        'cron',
        'update_core',
        'update_contrib',
        'update',
      ),
      'secure' => array(
        'php_register_globals',
        'settings.php',
        'update access',
        'update_core',
        'update_contrib',
        'unicode',
        'drd_server.ssl',
        'drd_server.securepages',
        'drd_server.user1',
        'drd_server.admincount',
        'drd_server.hidden.warnings',
        'drd_server.removed.txtfiles',
        'drd_server.module.php',
        'drd_server.module.mollom',
      ),
      'other' => array(
      ),
      'tuning' => array(
        'drd_server.module.devel',
        'drd_server.compress.css',
        'drd_server.compress.js',
        'drd_server.compress.page',
        'drd_server.enable.cache',
        'drd_server.theme.registry',
        'drd_server.trim.watchdog',
        'drd_server.defined.403',
        'drd_server.defined.404',
        'css_gzip',
        'php_memory_limit',
        'ctools_css_cache',
        'file system',
      ),
      'seo' => array(
        'drd_server.module.xmlsitemap',
        'drd_server.robots.txt',
        'drd_server.enable.cleanurl',
        'drd_server.module.pathauto',
        'drd_server.favicon',
        'drd_server.module.analytics',
        'drd_server.module.metatag',
        'drd_server.module.redirect',
        'drd_server.module.globalredirect',
        'drd_server.module.pagetitle',
      ),
    );
    foreach ($categories as $category => $items) {
      if ($category != 'other') {
        foreach ($items as $item) {
          $categories['other'][] = $item;
        }
      }
    }

  }
  if ($cat) {
    return $categories[$cat];
  }
  return array_keys($categories);
}

/**
 * @param array $requirements
 * @param string $cat
 * @param bool $omit_ignored
 * @return array
 */
function _drd_requirements_subset($requirements, $cat, $omit_ignored = FALSE) {
  $categories = _drd_requirements_categories($cat);
  $result = ($cat == 'other') ? $requirements : array();
  foreach ($categories as $category) {
    if (isset($requirements[$category])) {
      if ($cat == 'other') {
        unset($result[$category]);
      }
      else {
        if (!$omit_ignored || empty($requirements[$category]['ignore'])) {
          $result[$category] = $requirements[$category];
        }
      }
    }
  }
  if ($cat == 'other') {
    foreach ($result as $key => $req) {
      if ($omit_ignored && !empty($req['ignore'])) {
        unset($result[$key]);
      }
    }

  }
  return $result;
}

/**
 * @param array $form
 * @param stdClass $scope
 */
function drd_requirements_build_form(&$form, $scope = NULL) {
  $ignore_global = variable_get('drd_requirements_ignore', array());
  if (isset($scope)) {
    if (is_object($scope)) {
      $requirements = drd_domain_load_requirements($scope->did);
      $settings = $scope->settings['ignore'];
    }
    else {
      // Maybe later support a list of objects here.
      return;
    }
  }
  else {
    $ids = drd_domain_load_all();
    $requirements = array();
    foreach ($ids as $id => $name) {
      $requirements += drd_domain_load_requirements($id, TRUE);
    }
    $settings = $ignore_global;
  }

  $ignore = $settings;
  foreach ($ignore as $key => $def) {
    if (empty($requirements[$key])) {
      unset($ignore[$key]);
    }
  }
  foreach ($requirements as $key => $requirement) {
    if (!is_numeric($key) && isset($requirement['severity']) && $requirement['severity'] > 0) {
      // Maybe ignore it
      $ignore[$key] = $requirement['title'];
    }
  }
  foreach ($ignore as $key => $def) {
    if (empty($def)) {
      unset($ignore[$key]);
    }
    else if (isset($scope)) {
      if (!empty($ignore_global[$key])) {
        $ignore[$key] .= ' (' . t('ignored globally') . ')';
      }
    }
  }
  $form['drd_requirements_ignore'] = array(
    '#type' => 'checkboxes',
    '#title' => t('Error and warnings to be ignored'),
    '#options' => $ignore,
    '#default_value' => $settings,
    '#access' => !empty($ignore),
  );
}

/**
 * @param int $did
 * @param bool $filter_for_severity
 * @return array
 */
function drd_domain_load_requirements($did, $filter_for_severity = FALSE) {
  $cid = _drd_cache_id('domain', $did, 'info');
  $cache = cache_get($cid, 'cache_drd');
  $result = empty($cache) ? array() : $cache->data['arguments']['variables']['requirements'];
  if ($filter_for_severity) {
    foreach ($result as $key => $requirement) {
      if (is_numeric($key) || empty($requirement['severity'])) {
        unset($result[$key]);
      }
    }
  }
  return $result;
}

/**
 * @param array $requirements
 * @param array $ignore
 * @param bool $extras
 * @param bool $cache_store
 * @return array
 */
function _drd_update_domain_requirements($requirements, $ignore, $extras = FALSE, $cache_store = FALSE) {
  $ignore_global = variable_get('drd_requirements_ignore', array());
  $server_values = array();
  $extra = array();
  if ($extras) {
    $server_values = array(
      'drd_server.api',
      'drupal',
      'file_progress',
      'image_gd',
      'mailhandler_php_imap',
      'php',
      'php_extensions',
      'php_memory_limit',
      'php_register_globals',
      'unicode',
      'update_core',
      'webserver',
    );
    $extra = array();
  }
  include_once DRUPAL_ROOT . '/includes/install.inc';
  foreach ($requirements as $key => $requirement) {
    if ($extras) {
      if (in_array($key, $server_values)) {
        $extra[$key] = $requirement;
      }
    }
    if (isset($requirement['severity']) && $requirement['severity'] > REQUIREMENT_OK) {
      if (!empty($ignore[$key]) || !empty($ignore_global[$key])) {
        $requirements[$key]['ignore'] = TRUE;
      }
    }
  }
  $result = array();
  $sections = _drd_requirements_categories();
  $i = 0;
  foreach ($sections as $section) {
    $subset = _drd_requirements_subset($requirements, $section, TRUE);
    $result['status_w_' . $section] = (int) (drupal_requirements_severity($subset) == REQUIREMENT_WARNING);
    $result['status_e_' . $section] = (int) (drupal_requirements_severity($subset) == REQUIREMENT_ERROR);
    if ($cache_store) {
      $subset = _drd_requirements_subset($requirements, $section);
      $cache = array(
        'callback' => '_drd_requirements_block',
        'arguments' => array($section, $subset),
      );
      $i++;
      cache_set($cache_store . $i . ':' . $section, $cache, 'cache_drd');
    }
  }
  if ($extras) {
    $result[] = $extra;
  }
  return $result;
}

/**
 * @param string $section
 * @param array $requirements
 * @return string
 */
function _drd_requirements_block($section, $requirements) {
  if ($GLOBALS['drd']['mode'] == 'server') {
    $object = drd_server_load($GLOBALS['drd']['id']);
    $icon = '';
  }
  else {
    $object = drd_domain_load($GLOBALS['drd']['id']);
    $w = 'status_w_' . $section;
    $e = 'status_e_' . $section;
    $icon = drd_icon_status($object->$w, $object->$e);
  }
  $output = '<fieldset class="drd-box-small ' . $section . '">';
  $output .= '<div class="content">';
  $output .= theme('drd_requirements', array(
    'object' => $object,
    'title' => $icon . ' ' . _drd_requirements_category_label($section),
    'requirements' => $requirements,
  ));
  $output .= '</div>';
  $output .= '</fieldset>';
  return $output;
}

/**
 * @param array $variables
 * @return string
 */
function theme_drd_requirements($variables) {
  $requirements = $variables['requirements'];
  $title = $variables['title'];
  $object = $variables['object'];
  $ignore_global = variable_get('drd_requirements_ignore', array());
  $id = isset($object->did) ? 'domain:' . $object->did : 'server:' . $object->sid;
  $severities = array(
    REQUIREMENT_INFO => array(
      'title' => t('Info'),
      'class' => 'info',
    ),
    REQUIREMENT_OK => array(
      'title' => t('OK'),
      'class' => 'ok',
    ),
    REQUIREMENT_WARNING => array(
      'title' => t('Warning'),
      'class' => 'warning',
    ),
    REQUIREMENT_ERROR => array(
      'title' => t('Error'),
      'class' => 'error',
    ),
  );
  $output = '<table class="system-status-report">';
  $output .= '<thead><tr><th colspan="6">' . $title . '</th></tr></thead>';

  foreach ($requirements as $key => $requirement) {
    if (empty($requirement['#type'])) {
      $level = isset($requirement['severity']) ? (int) $requirement['severity'] : 0;
      $severity = $severities[$level];
      $severity['icon'] = '<div title="' . $severity['title'] . '"><span class="element-invisible">' . $severity['title'] . '</span></div>';
      $class = $severity['class'];
      $fix = '';
      $ignore = '';
      if (!empty($ignore_global[$key])) {
        $class .= ' ignored';
      }
      else if (!empty($object->settings['ignore'][$key])) {
        $class .= ' ignored';
        $ignore = '<span class="drd-function" id="' . $id . '" remote="0" function="unignore" key="' . $key . '">unignore</span>';
      };
      if ($level > 0) {
        if (empty($ignore_global[$key]) && empty($object->settings['ignore'][$key])) {
          $ignore = '<span class="drd-function" id="' . $id . '" remote="0" function="ignore" key="' . $key . '">ignore</span>';
        }
        if (isset($requirement['drd.autofix'])) {
          $fix = '<span class="drd-function" id="' . $id . '" remote="1" function="' . $requirement['drd.autofix'] . '" key="' . $key . '">FIX</span>';
        }
      }

      // Output table row(s)
      $title = isset($requirement['title']) ? $requirement['title'] : '';
      $value = isset($requirement['value']) ? $requirement['value'] : '';
      $info = empty($requirement['description']) ? '' : '<span class="drd-display">' . drd_icon('show', 'info') . '<div class="detail">' . $requirement['description'] . '</div></span>';
      $output .= '<tr class="' . $class . '">' .
        '<td class="status-icon">' . $severity['icon'] . '</td>' .
        '<td class="status-title">' . $title . '</td>' .
        '<td class="status-value">' . $value . '</td>' .
        '<td class="status-info">' . $info . '</td>' .
        '<td class="status-fix">' . $fix . '</td>' .
        '<td class="status-ignore">' . $ignore . '</td>' .
        '</tr>';
    }
  }

  $output .= '</table>';
  return $output;
}

<?php

/**
 * @file
 * Authcache Ajax Callback (authcache.php)
 *
 * The Authcache Ajax phase, included by ../authcache.inc during the drupal
 * bootstrap stage DRUPAL_BOOTSTRAP_PAGE_CACHE.
 *
 * Calls functions as defined in GET request: _authcache_{key} => value(s)
 * (Uses Authcache:ajax JSON from authcache.js)
 * Outputs JSON object of values returned by functions, if any.
 *
 * DO NOT MODIFY THIS FILE!
 * Place custom functions into sites/yoursite/authcache_custom.php. Additionally
 * you may place functions into authcache_custom.php in the same directory as
 * this file.
 *************************************************************/

// Attempt to prevent "cross-site request forgery" by requiring a custom header.
if (!isset($_SERVER['HTTP_AUTHCACHE'])) {
  header($err = 'HTTP/1.1 400 Bad Request (No Authcache Header)');
  die($err);
}

// GET is faster than POST, but has a character limit and less secure (easier to log)
$SOURCE = ($_SERVER['REQUEST_METHOD'] == 'POST') ? $_POST : $_GET;

// Set current page for bootstrap
if (isset($_POST['q'])) {
  $_GET['q'] = $_POST['q'];
}

// Continue Drupal bootstrap. Establish database connection and validate session.
drupal_bootstrap(DRUPAL_BOOTSTRAP_SESSION, TRUE);

// If user session is invalid/expired, delete Authcache-defined cookies.
global $user;
if (!$user->uid && isset($_COOKIE['authcache'])) {
  setcookie('drupal_user', "", REQUEST_TIME - 86400, ini_get('session.cookie_path'), ini_get('session.cookie_domain'), ini_get('session.cookie_secure') == '1');
  setcookie('drupal_uid', "", REQUEST_TIME - 86400, ini_get('session.cookie_path'), ini_get('session.cookie_domain'), ini_get('session.cookie_secure') == '1');
  setcookie('authcache', "", REQUEST_TIME - 86400, ini_get('session.cookie_path'), ini_get('session.cookie_domain'), ini_get('session.cookie_secure') == '1');
  setcookie('nocache', "", REQUEST_TIME - 86400, ini_get('session.cookie_path'), ini_get('session.cookie_domain'), ini_get('session.cookie_secure') == '1');
}

global $_authcache_is_ajax;
$_authcache_is_ajax = true;

// Add your own custom functions to authcache_custom.php and place in your settings.php directory.
if (file_exists($authcache_custom_inc = conf_path() . '/authcache_custom.php')) {
  include $authcache_custom_inc;
}
elseif (file_exists($authcache_custom_inc = dirname(__FILE__) . '/authcache_custom.php')) {
  include $authcache_custom_inc;
}

$response = NULL; // Ajax response

// Loop through GET or POST key/value pairs, call functions, and return results.
if (is_array($SOURCE)) { // GET or POST
  foreach ($SOURCE as $key => $value) {
    $func_name = "_authcache_$key";
    if (function_exists($func_name)) {
      $r = $func_name($value);
      if ($r !== NULL) {
        $response[$key] = $r;
      }
    }
  }
}


// Calculate database benchmarks, if enabled.
if (variable_get('dev_query', FALSE)) {
  $response['db_queries'] = _authcache_dev_query();
}

// Should browser cache this response? (See authcache_example for possible usage).
// This must be placed after bootstrap since drupal_page_header()
// will define header to make pages not cache
if (isset($SOURCE['max_age']) && is_numeric($SOURCE['max_age'])) {
  // Tell browser to cache response for 'max_age' seconds
  header("Cache-Control: max-age={$SOURCE['max_age']}, must-revalidate");
  header('Expires: ' . gmdate('D, d M Y H:i:s', REQUEST_TIME+24*60*60) . ' GMT'); // 1 day
}

header("Content-type: text/javascript");

// Extracted from drupal_json_encode in common.inc
if (version_compare(PHP_VERSION, '5.3.0', '>=')) {
  // Encode <, >, ', &, and " using the json_encode() options parameter.
  print json_encode($response, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);
}
else {
  // json_encode() escapes <, >, ', &, and " using its options parameter, but
  // does not support this parameter prior to PHP 5.3.0.  Use a helper instead.
  include_once DRUPAL_ROOT . '/includes/json-encode.inc';
  print drupal_json_encode_helper($response);
}


//
// Drupal Core functions
//

/**
 * Form tokens (prevents CSRF)
 *
 * form_token_id is a hidden field added by authcache.module's hook_form_alter()
 * @see form.inc
 */
function _authcache_form_token_id($vars) {
  include_once './includes/common.inc';
  foreach ($vars as $form_token_id) {
    $tokens[$form_token_id] = drupal_get_token($form_token_id);
  }
  return $tokens;
}


/**
 * Node history
 * @see node.module
 */
function _authcache_node_history($nid) {
  global $user;

  include_once './modules/node/node.module';

  // Update the 'last viewed' timestamp of the specified node for current user.
  // We do not want to use node_tag_view here because it requires a node_load
  // which seems impossible when drupal is not fully bootstraped. The following
  // code is directly copied from node_tag_view.
  if ($user->uid) {
    db_merge('history')
      ->key(array(
      'uid' => $user->uid,
      'nid' => $nid,
    ))
      ->fields(array('timestamp' => REQUEST_TIME))
      ->execute();
  }

  // Retrieves the timestamp at which the current user last viewed the specified node
  return node_last_viewed($nid);
}

/**
 * Display number of new comments on node-teaser
 */
function _authcache_comment_num_new($nids) {
  include_once './modules/node/node.module';
  include_once './modules/comment/comment.module';

  $counts = array_map('comment_num_new', $nids);

  return array_combine($nids, $counts);
}

/**
 * Node counter and access log statistics
 * @see statistics.module
 */
function _authcache_statistics($vars) {
  include_once './modules/statistics/statistics.module';
  statistics_exit();
}

/**
 * Number of new forum topics for user
 * @see forum.module
 */
function _authcache_forum_topic_new($vars) {
  global $user;
  $new = array();

  drupal_bootstrap(DRUPAL_BOOTSTRAP_PATH);
  include_once './includes/common.inc';
  include_once './includes/path.inc';
  include_once './modules/field/field.module';
  include_once './modules/node/node.module';  // Need NODE_NEW_LIMIT definition
  include_once './modules/forum/forum.module';
  include_once './modules/filter/filter.module'; // XSS filter for l()

  foreach ($vars as $tid) {
    $new_topics = (int) _forum_topics_unread($tid, $user->uid);
    if ($new_topics) {
      $new[$tid] = l(format_plural($new_topics, '1 new', '@count new'), "forum/$tid", array('fragment' => 'new'));
    }
  }
  return $new;
}

/**
 * Number of new topic replies for user or topic is unread
 * @see forum.module
 */
function _authcache_forum_topic_info($vars) {
  global $user;
  $info = array();

  drupal_bootstrap(DRUPAL_BOOTSTRAP_PATH);
  include_once './includes/common.inc';
  include_once './includes/path.inc';
  include_once './modules/field/field.module';
  include_once './modules/node/node.module';  // Need NODE_NEW_LIMIT definition
  include_once './modules/forum/forum.module';
  include_once './modules/comment/comment.module';

  foreach ($vars as $nid => $timestamp) {
    $history = _forum_user_last_visit($nid);
    $new_topics = (int)comment_num_new($nid, $history);
    if ($new_topics) {
      $info[$nid] = format_plural($new_topics, '1 new', '@count new');
    }
    elseif ($timestamp > $history) { // unread
      $info[$nid] = 1;
    }
  }

  return $info;
}


/**
 * Return default form values for site contact form
 * @see contact.module
 */
function _authcache_contact($vars) {
  global $user;
  return array('name' => $user->name, 'mail' => $user->mail);
}

/**
 * Get poll results/form for user
 * Response will be cached.
 * @see poll.module
 */
function _authcache_poll($vars) {
  // FULL bootstrap required in case custom theming is used
  drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
  $node = node_load($vars['nid']);
  $build = node_view($node);

  if (isset($build['poll_view_voting'])) {
    $output = render($build['poll_view_voting']);
  }
  elseif (isset($build['poll_view_results'])) {
    $output = render($build['poll_view_results']);
  }

  return array(
    'nid' => $vars['nid'],
    'html' => $output,
  );
}

/**
 * Render primary & secondary tabs.
 * Response will be cached.
 * @see menu.inc
 */
function _authcache_menu_local_tasks($vars) {
  drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

  return array(
    'tabs' => render(menu_local_tabs()),
    'action_links' => render(menu_local_actions()),
  );
}

/**
 * Render blocks. Grab from cache if available.
 * @param <array> $blocks
 *   [block id] => [block cache id]
 * @see block.module
 */
function _authcache_blocks($blocks) {
  global $user, $theme_key;
  $return = array();

  // Full bootstrap required for correct theming.
  drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

  foreach ($blocks as $block_id => $block_cid) {
    // If block cache is per user, then specify current user id.
    $block_cid = preg_replace('/:u.[0-9]+/', ":u.$user->uid", $block_cid);

    // Validate user roles with block visibility roles.
    // (In case someone is trying to hack into viewing certain blocks.)
    if (strpos($block_cid, ':r.') !== FALSE) {
      $matches = array();
      preg_match('/:r.([0-9,]+)/', $block_cid, $matches);
      if (isset($matches[1])) {
        // Cache id is built using exact user roles, so a direct comparison works. @see _block_get_cache_id().
        if ($matches[1] != implode(',', array_keys($user->roles))) {
          continue;
        }
      }
    }

    // Check cache_block bin first
    if ($block_cached = cache_get($block_cid, 'cache_block')) {
      $return[$block_id] = array(
        'subject' => check_plain($block_cached->data['subject']),
        'content' => render($block_cached->data['content']),
      );
    }
    else {
      $id = explode('-', $block_id, 2);

      $block = block_load($id[0], $id[1]);
      $build = reset(_block_get_renderable_array(_block_render_blocks(array($block_id => $block))));
      if (!empty($build['#theme_wrappers'])) {
        $build['#theme_wrappers'] = array_diff($build['#theme_wrappers'], array('block'));
      }

      $return[$block_id] = array(
        'subject' => check_plain($build['#block']->subject),
        'content' => render($build),
      );
    }
  }

  return $return;
}

//
// Authcache reserved/internal functions
//

function _authcache_q($vars) { }        // query string
function _authcache_max_age($vars) { }  // cache time (seconds)
function _authcache_time($vars) { }     // cache invalidation

/**
 * Database benchmarks for Authcache Ajax phase
 */
function _authcache_dev_query() {
  global $queries;
  if (!$queries) return;

  $time_query = 0;
  foreach ($queries as $q) {
    $time_query += $q[1];
  }
  $time_query = round($time_query * 1000, 2); // Convert seconds to milliseconds
  $percent_query = round(($time_query / timer_read('page')) * 100);

  return count($queries) . " queries @ {$time_query} ms";
}

//
// Contributed Module functions
//


/**
 * Example of customized block info being returned
 * @see authcache_example.module
 */
function _authcache_authcache_example($vars) {
  include_once './includes/common.inc';
  drupal_bootstrap(DRUPAL_BOOTSTRAP_PATH); // Use FULL if needed for additional functions

  include_once dirname(drupal_get_filename('module', 'authcache_example')) . '/authcache_example.module';
  return authcache_example_display_block_0();
}


/**
 * @todo Add support for additional contributed modules!
 ********************************************************/

<?php

/**
 * DRD Server Action to retrieve the SVN status (not yet implemented.
 *
 * @param string $action_name
 * @param string $mode
 * @param int $sid
 * @param array $options
 */
function drd_vcs_svn_status($action_name, $mode, $sid, $options) {
  _drd_vcs_execute($sid, $options, 'status', '-u');
}

/**
 * DRD Server Action to perform a SVN update (not yet implemented.
 *
 * @param string $action_name
 * @param string $mode
 * @param int $sid
 * @param array $options
 */
function drd_vcs_svn_update($action_name, $mode, $sid, $options) {
  _drd_vcs_execute($sid, $options, 'update', '--accept theirs-full --force --non-interactive');
}

/**
 * @param int $sid
 * @param array $options
 * @param string $cmd
 * @param string $param
 */
function _drd_vcs_execute($sid, $options, $cmd, $param) {
  $server = drd_server_load($sid);

  $def = drd_action_remote('drd.config.svn.read', 'server', $sid, $options);
  if (empty($def) || !is_array($def)) {
    drd_set_message($server, t('Can not read SVN settings from remote domain.'), 'error');
    return;
  }
  if (!$def['credentials']['enabled']) {
    drd_set_message($server, t('Skipping domain, SVN is disabled.'), 'error');
    return;
  }

  include_once 'drd.ssh2.inc';
  $connection = drd_ssh2_connect($server);
  if ($connection) {
    foreach ($def['directories'] as $directory) {
      foreach ($directory['paths'] as $path) {
        $command = 'svn ' . $cmd . ' ' . $param . ' ' . $path . ' --username ' . $def['credentials']['username'] . ' --password ' . $def['credentials']['password'];
        $output = drd_ssh2_command($server, $connection, $command);
        $key = 'svn:' . $cmd . ':' . $directory['label'];
        $cid = _drd_cache_id('server', $server->sid, $key);
        cache_set($cid, array(
          'title' => 'SVN ' . $cmd . ' for ' . $directory['label'] . ' (' . $path . ')',
          'callback' => 'drupal_render',
          'arguments' => array(
            '#markup' => '<pre>' . $output . '</pre>'
          )
        ), 'cache_drd');
      }
    }
  }
}

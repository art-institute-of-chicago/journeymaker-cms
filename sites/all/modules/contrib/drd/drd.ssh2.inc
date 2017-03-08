<?php

/**
 *
 * Install PHP SSH2 extension:
 * $ sudo apt-get install libssh2-1 libssh2-1-dev
 * $ sudo pecl install ssh2 channel://pecl.php.net/ssh2-0.12
 * # add "extension=ssh2.so" to php.ini
 */


/**
 * @param stdClass $server
 * @return bool|resource
 */
function drd_ssh2_connect($server) {
  if (!function_exists('ssh2_connect')) {
    drupal_set_message('SSH2 library for PHP is not available.', 'error');
    return FALSE;
  }

  static $connections = array();

  if (!isset($connections[$server->sid])) {
    $connections[$server->sid] = FALSE;
    if (!$server->settings['ssh2']['enable']) {
      drd_set_message($server, 'Skipping domain, SSH2 is disabled.', 'warning');
    }
    else {
      if (!empty($server->settings['ssh2']['host'])) {
        $host = $server->settings['ssh2']['host'];
      }
      else {
        $host = $server->url;
        if (strpos($host, ':') !== FALSE) {
          $host = substr($host, 0, strpos($host, ':'));
        }
        if (strpos($host, '/') !== FALSE) {
          $host = substr($host, 0, strpos($host, '/'));
        }
      }
      $connection = @ssh2_connect($host, $server->settings['ssh2']['port']);
      if ($connection) {
        switch ($server->settings['ssh2']['auth']['mode']) {
          case 1:
            $success = @ssh2_auth_password(
              $connection,
              $server->settings['ssh2']['auth']['username'],
              $server->settings['ssh2']['auth']['password']
            );
            break;

          case 2:
            $success = @ssh2_auth_pubkey_file(
              $connection,
              $server->settings['ssh2']['auth']['username'],
              $server->settings['ssh2']['auth']['file_public_key'],
              $server->settings['ssh2']['auth']['file_private_key'],
              $server->settings['ssh2']['auth']['key_secret']
            );
            break;

          default:
            drd_set_message($server, 'Unknown auth mode.', 'error');

        }
        if (!empty($success)) {
          $connections[$server->sid] = $connection;
        }
        else {
          drd_set_message($server, 'SSH2 authentication failed.', 'error');
        }
      }
      else {
        drd_set_message($server, 'SSH2 connection not possible.', 'error');
      }
    }
  }
  return $connections[$server->sid];
}

/**
 * @param stdClass $server
 * @param resource $connection
 * @param string $cmd
 * @param bool $json
 * @return string|array
 */
function drd_ssh2_command($server, $connection, $cmd, $json = FALSE) {
  $stream = ssh2_exec($connection, $cmd);
  stream_set_blocking($stream, true);
  $std_out = stream_get_contents($stream);
  $std_err = stream_get_contents(ssh2_fetch_stream($stream, SSH2_STREAM_STDERR));
  if (!empty($std_err)) {
    drd_set_message($server, $std_err);
  }
  if ($json) {
    try {
      return drupal_json_decode($std_out);
    }
    catch (Exception $e) {
      return FALSE;
    }
  }
  return $std_out;
}

/**
 * @param string $action_name
 * @param string $mode
 * @param int $sid
 */
function drd_action_ssh2_test($action_name, $mode, $sid) {
  $server = drd_server_load($sid);
  include_once 'drd.ssh2.inc';
  $connection = drd_ssh2_connect($server);
  if ($connection) {
    drd_set_message($server, 'SSH connection successfully established.');
    $home_dir = drd_ssh2_command($server, $connection, 'pwd');
    drd_set_message($server, t('Home directory: !path', array('!path' => $home_dir)));
  }
}

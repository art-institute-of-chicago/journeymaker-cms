<?php

/**
 * @file
 * Drush integration for Cron Key module.
 */

/**
 * Implements hook_drush_command().
 */
function cron_key_drush_command() {
  $items['cron-key'] = array(
    'description' => 'Display the system cron key.',
    'callback' => 'drush_cron_key_display',
  );
  $items['cron-key-reset'] = array(
    'description' => 'Reset the system cron key.',
    'callback' => 'drush_cron_key_reset',
    'drupal dependencies' => array('cron_key'),
  );
  return $items;
}

/**
 * Drush callback; display the system cron key.
 */
function drush_cron_key_display() {
  drush_print(dt('Cron key: ' . variable_get('cron_key')));
}

/**
 * Drush callback; reset the system cron key.
 */
function drush_cron_key_reset() {
  cron_key_reset();
  drush_print(dt('Cron key has been reset successfully.'));
  drush_cron_key_display();
}
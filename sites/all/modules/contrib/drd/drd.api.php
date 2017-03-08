<?php

/**
 * @defgroup drd_hooks DRD hook functions
 *
 * Core hooks for the DRD module suite.
 */

/**
 * @file
 * API documentation file.
 *
 * @ingroup drd_hooks
 */

/**
 * Defines actions for your DRD dashboard.
 *
 * All the actions will be visible in the actions drop down above
 * the core list or the domain list in your DRD dashboard or on the page
 * with details of a domain or core.
 *
 * This hook allows to define any number of additional actions in various
 * categories. Best practice for the callbacks of each action is to return
 * the result from the callback @see drd_server_result(). This will then
 * also contain messages that were triggered by drupal_set_message() to be
 * displayed in your central DRD dashboard.
 *
 * @return array
 *   Keyed array with action definitions as arrays with the following possible keys:
 *   - category: (optional) All the actions are grouped in the drop down in your DRD by categories
 *        and here you can define your own category. It defaults to "- Main actions -".
 *   - label: (required) A label for this action that's been used in the drop down list.
 *   - mode: (optional) Either "server" or "domain" (default). Determines whether this action
 *        is displayed on the core list or the domain list in your DRD.
 *   - callback: (required) The name of a valid function that will be executed when this action
 *        gets selected and executed.
 *   - file: (optional) If the function given in callback sits in a different file which won't be loaded
 *        by Drupal's bootstrap then you should provide the filename here so that the file gets loaded
 *        before the callback will be executed.
 *   - remote: (optional) TRUE or FALSE (default) to determine if the action should be executed
 *        locally at your DRD or remotely at the selected core(s) or domain(s).
 *   - fields: (optional) An array to define any number of form fields (@see FAPI) that will be displayed
 *        next to the actions drop down list in your DRD and the field values will be forwarded to the
 *        function being given as the callback.
 *   - follower: (optional) An array of other DRD actions that will be executed subsequently after this
 *        action, if execution finished successfully.
 *   - queue: (optional) TRUE (default) if this action should be executed directly and the calling
 *        browser instance should be waiting for the result or FALSE to put the action into the queue
 *        to be executed one after the other without holding up the interface.
 *   - refresh: (optional) TRUE if the selected core or domain should be updated in their list after
 *        successful execution of this action or FALSE (default) otherwise.
 *
 * @ingroup drd_hooks
 */
function hook_drd_actions() {
  $actions = array(
    'my_key' => array(
      'category' => t('My Category'),
      'label' => t('My label for the action'),
      'mode' => 'server',
      'remote' => TRUE,
      'fields' => array(
        'reset' => array(
          '#type' => 'checkbox',
          '#title' => t('Reset'),
          '#default_value' => FALSE,
        ),
      ),
    ),
  );
  return $actions;
}

/**
 * Like hook_drd_actions() this hook allows other modules to define extra
 * actions on remote cores and domains. The syntax of the return value is
 * the same as in hook_drd_actions(),
 *
 * @return array
 *
 * @ingroup drd_hooks
 */
function hook_drd_server_actions() {}

/**
 * @param string $mode
 * @param array $ids
 * @param string $action_name
 * @param array $action
 * @param array $values
 *
 * @ingroup drd_hooks
 */
function hook_drd_action_preprocess($mode, $ids, $action_name, $action, &$values) {}

/** ======================================================================
 *
 * Hooks for remote cores and domains, supported by drd_server.
 *
 */

/**
 * @param string $mode
 * @param array $drd_result
 * @param array $args
 * @return array
 */
function hook_drd_server_result($mode, $drd_result, $args) {}

/**
 * @param array $drd_result
 */
function hook_drd_server_result_alter(&$drd_result) {}

/**
 *
 */
function hook_drd_config_server() {}

/**
 *
 */
function hook_drd_config_domain() {}

/**
 *
 */
function hook_drd_server_update_translation() {}

/**
 *
 */
function hook_drd_svn_module() {}

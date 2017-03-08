<?php

/**
 * @file
 * Hooks provided by the Eloqua API module.
 */

/**
 * @addtogroup hooks
 * @{
 */

/**
 * Allow modules to alter data before it's POSTed to Eloqua.
 *
 * @param array $data
 *   An associative array of data, keyed by field name, that will be posted to
 *   Eloqua. You may update, add, or remove items from this array as you deem
 *   fit.
 *
 * @see eloqua_api_post()
 */
function hook_eloqua_api_post_alter(&$data) {
  // For instance, you could globally add your own custom field.
  $data['custom_global_field'] = 'example';
}

/**
 * @} End of "addtogroup hooks".
 */

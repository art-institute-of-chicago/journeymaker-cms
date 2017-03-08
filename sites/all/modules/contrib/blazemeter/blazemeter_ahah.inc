<?php

/**
 * Submit handler for the "add-one-more" button.
 *
 * Increments the max counter and causes a rebuild.
 */
function blazemeter_ahah_add_more_anon($form, &$form_state) {
  $form_state['blazemeter_ahah_anon_page_count']++;
  $form_state['rebuild'] = TRUE;
}

function blazemeter_ahah_add_more_auth($form, &$form_state) {
  $form_state['blazemeter_ahah_auth_page_count']++;
  $form_state['rebuild'] = TRUE;
}

/**
 * Callback for both ajax-enabled buttons.
 *
 * Returns the anon page textfields
 */
function blazemeter_ahah_add_more_anon_callback($form, $form_state) {
  return $form['anonymous']['anon_page'];
}

/**
 * Callback for both ajax-enabled buttons.
 *
 * Returns the auth page textfields
 */
function blazemeter_ahah_add_more_auth_callback($form, $form_state) {
  return $form['authenticated']['auth_page'];
}

<?php

/**
 * @file
 * Admin callbacks for the Eloqua API module.
 */


/**
 * Defines the admin form for the Eloqua API module.
 */
function eloqua_api_admin_settings($form_state) {
  $form = array();
  $settings = _eloqua_api_get_settings();

  // General settings fieldset and fields.
  $form['eloqua_api_settings'] = array(
    '#type' => 'fieldset',
    '#title' => t('Eloqua API Configuration'),
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
  );

  $form['eloqua_api_settings']['eloqua_api_post_url'] = array(
    '#type' => 'textfield',
    '#title' => t('Post URL'),
    '#description' => t("The URL to which Eloqua data should be POST'ed."),
    '#default_value' => $settings['post_url'],
    '#required' => TRUE,
  );

  $form['eloqua_api_settings']['eloqua_api_site_id'] = array(
    '#type' => 'textfield',
    '#title' => t('Site ID'),
    '#description' => t("The site ID associated with this website."),
    '#default_value' => $settings['site_id'],
    '#required' => TRUE,
  );

  $form['eloqua_api_settings']['eloqua_api_default_form'] = array(
    '#type' => 'textfield',
    '#title' => t('Global default form name'),
    '#description' => t('A global, catch-all form name, passed to Eloqua as the value of the elqFormName attribute if none is provided elsewhere.'),
    '#default_value' => $settings['default_form'],
  );

  $form['eloqua_api_settings']['eloqua_api_timeout'] = array(
    '#type' => 'textfield',
    '#title' => t('Timeout'),
    '#description' => t("Number of seconds before the connection to Eloqua times out."),
    '#default_value' => $settings['timeout'],
    '#required' => TRUE,
  );

  $form['eloqua_api_settings']['eloqua_api_explicit_values'] = array(
    '#type' => 'checkbox',
    '#title' => t('Manual field mapping.'),
    '#description' => t('When selected only values in the custom $form_state[\'eloqua_values\'] will be sent to Eloqua. If unchecked, everything in the default $form_state[\'values\'] will be sent to Eloqua.'),
    '#default_value' => $settings['explicit_values'],
  );

  $form['eloqua_api_settings']['eloqua_api_log'] = array(
    '#type' => 'checkbox',
    '#title' => t('Log Eloqua posts.'),
    '#description' => t('Select this to log all posts made to Eloqua.'),
    '#default_value' => $settings['log'],
  );

  $form['eloqua_api_settings']['eloqua_api_tracking_enabled'] = array(
    '#type' => 'checkbox',
    '#title' => t('Enable Eloqua tracking.'),
    '#description' => t('Select this to enable sitewide Eloqua tracking via scripts.'),
    '#default_value' => $settings['tracking_enabled'],
  );

  $form['eloqua_api_settings']['eloqua_api_send_customer_guid'] = array(
    '#type' => 'checkbox',
    '#title' => t('Send Eloqua customer GUID.'),
    '#description' => t('Select this to attempt to send the customer GUID with the POST.'),
    '#default_value' => $settings['send_customer_guid'],
  );

  // Specific forms fieldset.
  // @TODO: Add additional core-provided forms. Contact? Node add? Etc.
  $form['eloqua_api_forms'] = array(
    '#type' => 'fieldset',
    '#title' => t('Eloqua API Enabled Forms'),
    '#description' => t('Forms checked here will always post data to Eloqua.'),
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
  );

  $form['eloqua_api_forms']['eloqua_api_enabled_forms'] = array(
    '#type' => 'checkboxes',
    '#options' => array(
      // Custom forms can be added here via form alter.
      'user_register_form' => t('User Registration'),
    ),
    '#default_value' => $settings['enabled_forms'],
  );

  return system_settings_form($form);
}

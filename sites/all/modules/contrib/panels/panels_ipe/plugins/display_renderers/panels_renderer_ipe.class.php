<?php

/**
 * Renderer class for all In-Place Editor (IPE) behavior.
 */
class panels_renderer_ipe extends panels_renderer_editor {
  // The IPE operates in normal render mode, not admin mode.
  var $admin = FALSE;

  function render() {
    $output = parent::render();
    return "<div id='panels-ipe-display-{$this->clean_key}' class='panels-ipe-display-container'>$output</div>";
  }

  function add_meta() {
    ctools_include('display-edit', 'panels');
    ctools_include('content');

    if (empty($this->display->cache_key)) {
      $this->cache = panels_edit_cache_get_default($this->display);
    }
    // @todo we may need an else to load the cache, but I am not sure we
    // actually need to load it if we already have our cache key, and doing
    // so is a waste of resources.

    ctools_include('cleanstring');
    $this->clean_key = ctools_cleanstring($this->display->cache_key);
    $button = theme('panels_ipe_edit_button', array('class' => 'panels-ipe-startedit', 'text' => t('Customize this page')));
    panels_ipe_toolbar_add_button($this->clean_key, 'panels-ipe-startedit', $button);

//    panels_ipe_get_cache_key($this->clean_key);

    ctools_include('ajax');
    ctools_include('modal');
    ctools_modal_add_js();

    ctools_add_css('panels_dnd', 'panels');
    ctools_add_css('panels_admin', 'panels');
    ctools_add_js('panels_ipe', 'panels_ipe');
    ctools_add_css('panels_ipe', 'panels_ipe');

    $settings = array(
      'formPath' => url($this->get_url('save-form')),
    );
    drupal_add_js(array('PanelsIPECacheKeys' => array($this->clean_key)), 'setting');
    drupal_add_js(array('PanelsIPESettings' => array($this->clean_key => $settings)), 'setting');

    drupal_add_library('system', 'ui.draggable');
    drupal_add_library('system', 'ui.droppable');
    drupal_add_library('system', 'ui.sortable');
//    drupal_add_js('misc/ui/jquery.ui.draggable.min.js');
//    drupal_add_js('misc/ui/jquery.ui.droppable.min.js');
//    drupal_add_js('misc/ui/jquery.ui.sortable.min.js');
//    jquery_ui_add(array('ui.draggable', 'ui.droppable', 'ui.sortable'));
    parent::add_meta();
  }

  /**
   * Override & call the parent, then pass output through to the dnd wrapper
   * theme function.
   *
   * @param $pane
   */
  function render_pane(&$pane) {
    $output = parent::render_pane($pane);
    if (empty($output)) {
      return;
    }

    // If there are region locks, add them.
    if (!empty($pane->locks['type']) && $pane->locks['type'] == 'regions') {
      static $key = NULL;
      $javascript = &drupal_static('drupal_add_js', array());

      // drupal_add_js breaks as we add these, but we can't just lump them
      // together because panes can be rendered independently. So game the system:
      if (empty($key)) {
        $settings['Panels']['RegionLock'][$pane->pid] = $pane->locks['regions'];
        drupal_add_js($settings, 'setting');

        // These are just added via [] so we have to grab the last one
        // and reference it.
        $keys = array_keys($javascript['settings']['data']);
        $key = end($keys);
      }
      else {
        $javascript['settings']['data'][$key]['Panels']['RegionLock'][$pane->pid] = $pane->locks['regions'];
      }

    }

    if (empty($pane->IPE_empty)) {
      // Add an inner layer wrapper to the pane content before placing it into
      // draggable portlet
      $output = "<div class=\"panels-ipe-portlet-content\">$output</div>";
    }
    else {
      $output = "<div class=\"panels-ipe-portlet-content panels-ipe-empty-pane\">$output</div>";
    }
    // Hand it off to the plugin/theme for placing draggers/buttons
    $output = theme('panels_ipe_pane_wrapper', array('output' => $output, 'pane' => $pane, 'display' => $this->display, 'renderer' => $this));

    if (!empty($pane->locks['type']) && $pane->locks['type'] == 'immovable') {
      return "<div id=\"panels-ipe-paneid-{$pane->pid}\" class=\"panels-ipe-nodrag panels-ipe-portlet-wrapper panels-ipe-portlet-marker\">" . $output . "</div>";
    }

    return "<div id=\"panels-ipe-paneid-{$pane->pid}\" class=\"panels-ipe-portlet-wrapper panels-ipe-portlet-marker\">" . $output . "</div>";
  }

  function render_pane_content(&$pane) {
    $content = parent::render_pane_content($pane);
    // Ensure that empty panes have some content.
    if (empty($content->content)) {
      // Get the administrative title.
      $content_type = ctools_get_content_type($pane->type);
      $title = ctools_content_admin_title($content_type, $pane->subtype, $pane->configuration, $this->display->context);

      $content->content = t('Placeholder for empty "@title"', array('@title' => $title));
      // Add these to prevent notices.
      $content->type = 'panels_ipe';
      $content->subtype = 'panels_ipe';
      $pane->IPE_empty = TRUE;
    }

    return $content;
  }

  /**
   * Add an 'empty' pane placeholder above all the normal panes.
   *
   * @param $region_id
   * @param $panes
   */
  function render_region($region_id, $panes) {
    // Generate this region's 'empty' placeholder pane from the IPE plugin.
    $empty_ph = theme('panels_ipe_placeholder_pane', array('region_id' => $region_id, 'region_title' => $this->plugins['layout']['regions'][$region_id]));

    // Wrap the placeholder in some guaranteed markup.
    $panes['empty_placeholder'] = '<div class="panels-ipe-placeholder panels-ipe-on panels-ipe-portlet-marker panels-ipe-portlet-static">' . $empty_ph . "</div>";

    // Generate this region's add new pane button. FIXME waaaaay too hardcoded
    $panes['add_button'] = theme('panels_ipe_add_pane_button', array('region_id' => $region_id, 'display' => $this->display, 'renderer' => $this));

    $output = parent::render_region($region_id, $panes);
    $output = theme('panels_ipe_region_wrapper', array('output' => $output, 'region_id' => $region_id, 'display' => $this->display, 'renderer' => $this));
    $classes = 'panels-ipe-region';

    ctools_include('cleanstring');
    $region_id = ctools_cleanstring($region_id);
    return "<div id='panels-ipe-regionid-$region_id' class='panels-ipe-region'>$output</div>";
  }

  /**
   * AJAX entry point to create the controller form for an IPE.
   */
  function ajax_save_form($break = NULL) {
    if (!empty($this->cache->locked)) {
      if ($break != 'break') {
        $account  = user_load($this->cache->locked->uid);
        $name     = theme('username', array('account' => $account));
        $lock_age = format_interval(time() - $this->cache->locked->updated);

        $message = t("This panel is being edited by user !user, and is therefore locked from editing by others. This lock is !age old.\n\nClick OK to break this lock and discard any changes made by !user.", array('!user' => $name, '!age' => $lock_age));

        $this->commands[] = array(
          'command' => 'unlockIPE',
          'message' => $message,
          'break_path' => url($this->get_url('save-form', 'break'))
        );
        return;
      }

      // Break the lock.
      panels_edit_cache_break_lock($this->cache);
    }

    $form_state = array(
      'display' => &$this->display,
      'content_types' => $this->cache->content_types,
      'rerender' => FALSE,
      'no_redirect' => TRUE,
      // Panels needs this to make sure that the layout gets callbacks
      'layout' => $this->plugins['layout'],
    );

    $output = drupal_build_form('panels_ipe_edit_control_form', $form_state);
    if (empty($form_state['executed'])) {
      // At this point, we want to save the cache to ensure that we have a lock.
      panels_edit_cache_set($this->cache);
      $this->commands[] = array(
        'command' => 'initIPE',
        'key' => $this->clean_key,
        'data' => drupal_render($output),
      );
      return;
    }

    // Otherwise it was submitted.
    if (!empty($form_state['clicked_button']['#save-display'])) {
      // Saved. Save the cache.
      panels_edit_cache_save($this->cache);
    }
    else {
      // Cancelled. Clear the cache.
      panels_edit_cache_clear($this->cache);
    }

    $this->commands[] = array(
      'command' => 'endIPE',
      'key' => $this->clean_key,
      'data' => $output,
    );
  }

  /**
   * Create a command array to redraw a pane.
   */
  function command_update_pane($pid) {
    if (is_object($pid)) {
      $pane = $pid;
    }
    else {
      $pane = $this->display->content[$pid];
    }

    $this->commands[] = ajax_command_replace("#panels-ipe-paneid-$pane->pid", $this->render_pane($pane));
    $this->commands[] = ajax_command_changed("#panels-ipe-display-{$this->clean_key}");
  }

  /**
   * Create a command array to add a new pane.
   */
  function command_add_pane($pid) {
    if (is_object($pid)) {
      $pane = $pid;
    }
    else {
      $pane = $this->display->content[$pid];
    }

    ctools_include('cleanstring');
    $region_id = ctools_cleanstring($pane->panel);
    $this->commands[] = ajax_command_append("#panels-ipe-regionid-$region_id div.panels-ipe-sort-container", $this->render_pane($pane));
    $this->commands[] = ajax_command_changed("#panels-ipe-display-{$this->clean_key}");
  }
}

/**
 * FAPI callback to create the Save/Cancel form for the IPE.
 */
function panels_ipe_edit_control_form($form, &$form_state) {
  $display = &$form_state['display'];
  // @todo -- this should be unnecessary as we ensure cache_key is set in add_meta()
//  $display->cache_key = isset($display->cache_key) ? $display->cache_key : $display->did;

  // Annoyingly, theme doesn't have access to form_state so we have to do this.
  $form['#display'] = $display;

  $layout = panels_get_layout($display->layout);
  $layout_panels = panels_get_regions($layout, $display);

  $form['panel'] = array('#tree' => TRUE);
  $form['panel']['pane'] = array('#tree' => TRUE);

  foreach ($layout_panels as $panel_id => $title) {
    // Make sure we at least have an empty array for all possible locations.
    if (!isset($display->panels[$panel_id])) {
      $display->panels[$panel_id] = array();
    }

    $form['panel']['pane'][$panel_id] = array(
      // Use 'hidden' instead of 'value' so the js can access it.
      '#type' => 'hidden',
      '#default_value' => implode(',', (array) $display->panels[$panel_id]),
    );
  }

  $form['buttons']['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Save'),
    '#id' => 'panels-ipe-save',
    '#submit' => array('panels_edit_display_form_submit'),
    '#save-display' => TRUE,
  );
  $form['buttons']['cancel'] = array(
    '#type' => 'submit',
    '#id' => 'panels-ipe-cancel',
    '#value' => t('Cancel'),
  );
  return $form;
}

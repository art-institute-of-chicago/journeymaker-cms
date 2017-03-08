(function ($) {

/**
 * Provide the summary information for the block settings vertical tabs.
 */
Drupal.behaviors.authcacheAjaxSettingsSummary = {
  attach: function (context) {
    $('fieldset#edit-authcache-settings', context).drupalSetSummary(function (context) {
      if ($('input[name="authcache"]:checked', context).length) {
        var lt = parseInt($('input[name="authcache_lifetime"]', context).val());
        if (lt > 0) {
          return Drupal.t('Load block with Ajax and cache the response in Browser for %seconds seconds', {'%seconds': lt});
        }
        else {
          return Drupal.t('Load block with Ajax');
        }
      }
      else {
        return Drupal.t('No settings');
      }
    });
  }
};

})(jQuery);

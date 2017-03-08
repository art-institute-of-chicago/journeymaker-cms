// Callback function from Ajax request
function _authcache_authcache_example(vars) {
  jQuery("#block-authcache_example-0 .content").html("<tt><b>[cached by browser]</b></tt><br>" + vars);
}

(function($){
    
  function authcacheExampleInit() {
    ajaxJson = {
      // The name of the function to call, both for ajax_authcache.php and
      // this file (see function above). The cookie value will change if
      // the user updates their block (used for max_age cache invalidation)
      'authcache_example' : $.cookie('authcache_example'),
  
      // Makes browser cache the Ajax response to help reduce server resources
      'max_age' : 3600
    }
    
    // Perform independent Authcache ajax request
    Authcache.ajaxRequest(ajaxJson);
  }
  
  authcacheExampleInit();
   
})(jQuery);



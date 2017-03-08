<?php

/**
 * @file
 * Attempt to deliver a cached version of a page depending on the users role.
 *
 * This file gets included by _drupal_bootstrap_page_cache in bootstrap.inc
 * during the drupal bootstrap stage DRUPAL_BOOTSTRAP_PAGE_CACHE. This script
 * serves two purposes:
 * - Delegate the request to the underlying cache handler and deliver a cached
 *   version of a page for this request. Then exit.
 * - Execute Ajax callbacks necessary for customizing a page delivered before
 *   and then exit.
 *
 * In the event of a cache-miss or if a page is not cachable, execution is
 * passed back to _drupal_bootstrap_page_cache and the cache will be served
 * using a full bootstrap.
 *
 * @see _drupal_bootstrap_page_cache in boostrap.inc
 */

// Check if in Ajax phase and return JSON.
// This is a custom HTTP header defined by the authcache.js XHR
if (isset($_SERVER['HTTP_AUTHCACHE'])) {
  require_once dirname(__FILE__) .'/ajax/authcache.php';
  exit;
}

// Attempt to deliver the page from cache
$delivered = authcache_retrieve_cache_page();
if ($delivered) {
  exit;
}

/**
 * Send cached page to browser, if found.
 *
 * @return boolean TRUE if page was delivered, FALSE otherwise
 */
function authcache_retrieve_cache_page(){
	global $base_root;

	// User is logged in but their role should not receive any cached pages
	// (i.e., cached anonymous pages, since they have no authcache key)
	/* simg: this logic doesn't work, because a visitor can have the drupal_user cookie set whilst not logged in */
	/*if (isset($_COOKIE['drupal_user']) && !isset($_COOKIE['authcache'])) {
		return FALSE;
	}*/

	// Caching for browser session was temporarily disabled (most likely from drupal_set_message()/drupal_goto() redirect)
	if (isset($_COOKIE['nocache_temp'])) {
		setcookie('nocache', '', REQUEST_TIME - 36000, ini_get('session.cookie_path'), ini_get('session.cookie_domain'), ini_get('session.cookie_secure') == '1');
		setcookie('nocache_temp', '', REQUEST_TIME - 36000, ini_get('session.cookie_path'), ini_get('session.cookie_domain'), ini_get('session.cookie_secure') == '1');
		return FALSE;
	}

	// Caching disabled for browser session
	if (isset($_COOKIE['nocache'])) {
		return FALSE;
	}

	// Attempt to retrieve page from cache
	if (empty($_POST)) {
		
		// Connect to database and initialzie configuration if not
		// disabled from within settings.php
		if (!variable_get('page_cache_without_database')) {
			drupal_bootstrap(DRUPAL_BOOTSTRAP_VARIABLES, FALSE);
		}

		// Authenticated cache role(s) key
		if ($authcache_keygen = variable_get('authcache_key_generator', FALSE)) 
			$key = (isset($_COOKIE['authcache']) && $_COOKIE['authcache']) ? $_COOKIE['authcache'] : $authcache_keygen();
		else
			$key = (isset($_COOKIE['authcache']) && $_COOKIE['authcache']) ? $_COOKIE['authcache'] : '';
		$cache_key = $key . $base_root . request_uri();
		
		$cache = cache_get($cache_key, 'cache_page');
		
		if (!empty($cache)) {
			// Cached page found
			header('X-Drupal-Cache: HIT');
			
			// render cache benchmark
			if (isset($_COOKIE['authcache_debug'])) {
				setcookie('cache_render', timer_read('page'));
			}
			drupal_serve_page_from_cache($cache);
				
			return TRUE;
			
		} else {
			//cache miss
			header('X-Drupal-Cache: MISS');
		}
	} else {
		//form post detected so allow normal page processing
	}
}

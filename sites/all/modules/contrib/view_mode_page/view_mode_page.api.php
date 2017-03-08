<?php

/**
 * Hook called when getting the list of entity types this is enabled for
 *
 * @param array $types
 *   Array of entity types
 *
 * @return array
 *   Array of entity types
 */
function hook_view_mode_page_get_entity_types($types) {
  return $types;
}

/**
 * Hook called when the results of the get_patterns call is made.
 *
 * This could be used to inject/change results before they are returned.
 *
 * @param array $results
 *   DB results of the view_mode_page_get_entity_patterns()
 * @param string $entity_type
 *   Entity type passed to view_mode_page_get_entity_patterns()
 * @param string $content_type
 *   Content type passed to view_mode_page_get_entity_patterns()
 * @param string $view_mode
 *   View mode passed to view_mode_page_get_entity_patterns()
 *
 * @return array
 *   Results array
 * @see view_mode_page_get_entity_patterns
 */
function hook_view_mode_page_get_entity_patterns($results, $entity_type, $content_type, $view_mode) {
  return $results;
}

/**
 * DEPRECATED: Hook called when the results of the get_patterns call is made.
 *
 * This could be used to inject/change results before they are returned.
 *
 * @param array $results
 *   DB results of the view_mode_page_get_patterns()
 * @param string $content_type
 *   Content type passed to view_mode_page_get_patterns()
 * @param string $view_mode
 *   View mode passed to view_mode_page_get_patterns()
 *
 * @return array
 *   Results array
 * @see view_mode_page_get_patterns
 *
 * @deprecated
 * @see hook_view_mode_page_get_entity_patterns
 */
function hook_view_mode_page_get_patterns($results, $content_type, $view_mode) {
  return $results;
}

/**
 * Hook called just before an entity is used to render a view mode
 *
 * @param object $entity
 *   The entity being rendered
 * @param string $entity_type
 *   The entity type
 * @param string $content_type
 *   The content type/bundle
 * @param string $view_mode
 *   The view mode being used for this display
 * @param string $pattern
 *   The pattern being matched
 *
 * @return object
 *   The entity
 */
function hook_view_mode_page_pre_entity_view($entity, $entity_type, $content_type, $view_mode, $pattern) {
  return $entity;
}

/**
 * DEPRECATED: Hook called just before a node is used to render a view mode
 *
 * @todo references node
 *
 * @param object $node
 *   The node that will be used for the view
 * @param string $content_type
 *   The content type used by view_mode_page
 * @param string $view_mode
 *   The view mode triggered for view_mode_page
 * @param string $pattern
 *   The URL pattern that was triggered
 *
 * @return object
 *   The node
 *
 * @deprecated
 * @see hook_view_mode_page_pre_entity_view
 */
function hook_view_mode_page_pre_view($node, $content_type, $view_mode, $pattern) {
  return $node;
}

/**
 * Hook called just before an entity is used to render a view mode
 *
 * @param object $entity
 *   The entity being rendered
 * @param array $view
 *   The view that was returned from entity_view
 * @param string $entity_type
 *   The entity type
 * @param string $content_type
 *   The content type/bundle
 * @param string $view_mode
 *   The view mode being used for this display
 * @param string $pattern
 *   The pattern being matched
 *
 * @return object
 *   The entity
 */
function hook_view_mode_page_post_entity_view($entity, $view, $entity_type, $content_type, $view_mode, $pattern) {
  return $view;
}

/**
 * DEPRECATED: Hook called with the resulting view from node_view
 *
 * @param object $node
 *   The node that was used for node_view
 * @param array  $view
 *   The view that was returned from node_view
 * @param string $content_type
 *   The content type used by view_mode_page
 * @param string $view_mode
 *   The view mode triggered by view_mode_page
 * @param string $pattern
 *   The URL pattern that was triggered
 *
 * @return array
 *   The view
 *
 * @deprecated
 * @see hook_view_mode_page_post_entity_view
 */
function hook_view_mode_page_post_view($node, $view, $content_type, $view_mode, $pattern) {
  return $view;
}

/**
 * DEPRECATED: Hook called with a pattern is added via view_mode_page
 *
 * @param string $content_type
 *   The content type for the pattern
 * @param string $view_mode
 *   The view mode for the pattern
 * @param string $pattern
 *   The URL pattern
 * @param string $result
 *   The DB result for the pattern addition
 *
 * @see view_mode_page_add_pattern
 *
 * @deprecated
 * @see hook_view_mode_page_entity_pattern_added
 */
function hook_view_mode_page_pattern_added($content_type, $view_mode, $pattern, $result) {
}

/**
 * Hook called with a pattern is added via view_mode_page
 *
 * @param string $entity_type
 *   The entity type the pattern was added for
 * @param string $content_type
 *   The content type for the pattern
 * @param string $view_mode
 *   The view mode for the pattern
 * @param string $pattern
 *   The URL pattern
 * @param string $result
 *   The DB result for the pattern addition
 *
 * @see view_mode_page_add_entity_pattern
 */
function hook_view_mode_page_entity_pattern_added($entity_type, $content_type, $view_mode, $pattern, $result) {
}

/**
 * Hook called when patterns are deleted
 *
 * @param string $entity_type
 *   The entity type passed to the delete pattern function
 * @param string $content_type
 *   The content type passed to the delete pattern function
 * @param string $view_mode
 *   The view mode passed to the delete pattern function
 * @param string $pattern
 *   The URL pattern passed to the delete pattern function
 *
 * @see view_mode_page_delete_patterns
 */
function hook_view_mode_page_entity_patterns_deleted($entity_type = NULL, $content_type = NULL, $view_mode = NULL, $pattern = NULL) {
}

/**
 * DEPRECATED: Hook called when patterns are deleted
 *
 * @param string $content_type
 *   The content type passed to the delete pattern function
 * @param string $view_mode
 *   The view mode passed to the delete pattern function
 * @param string $pattern
 *   The URL pattern passed to the delete pattern function
 *
 * @see view_mode_page_delete_patterns
 *
 * @deprecated
 * @see hook_view_mode_page_entity_patterns_deleted
 */
function hook_view_mode_page_patterns_deleted($content_type = NULL, $view_mode = NULL, $pattern = NULL) {
}

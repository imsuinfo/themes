<?php

/**
 * @file
 * McNeese - event_workflow Theme.
 */

/**
 * @defgroup mcneese McNeese - event_workflow Theme
 * @ingroup mcneese
 * @{
 * Provides the thebe.mcneese.edu/event_workflow mcneese theme.
 */


/**
 * Implements hook_preprocess_page().
 */
function mcneese_event_workflow_preprocess_page(&$vars) {
  $cf = & drupal_static('cf_theme_get_variables', array());
}


/**
 * Render all data for: page.
 */
function mcneese_event_workflow_render_page() {
  $cf = & drupal_static('cf_theme_get_variables', array());

}

/**
 * Implements hook_preprocess_toolbar().
 */
function mcneese_event_workflow_preprocess_toolbar(&$vars) {
  $cf = & drupal_static('cf_theme_get_variables', array());

  $events_tree = menu_build_tree('navigation', array(
    'conditions' => array('ml.link_path' => 'events'),
    'min_depth' => 1,
    'max_depth' => 1,
  ));

  if (!function_exists('toolbar_menu_navigation_links')) {
    return;
  }

  if (!function_exists('mcneese_event_workflow_menu')) {
    return;
  }

  $toolbar_tree = toolbar_get_menu_tree();
  $tree = array_merge($events_tree, $toolbar_tree);

  $links = toolbar_menu_navigation_links($tree);
  $vars['toolbar']['toolbar_menu']['#links'] = $links;

}

/**
 * @} End of '@defgroup mcneese McNeese - event_workflow Theme'.
 */

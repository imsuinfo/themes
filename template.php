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
  $cf = &drupal_static('cf_theme_get_variables', array());

  if (empty($cf)) {
    mcneese_initialize_variables($vars);
  }
}


/**
 * Render all data for: page.
 */
function mcneese_event_workflow_render_page() {
  $cf = &drupal_static('cf_theme_get_variables', array());

  // build & render precrumb
  if (!isset($cf['show']['page']['precrumb'])) {
    $cf['data']['page']['precrumb'] = '';
    $cf['show']['page']['precrumb'] = FALSE;
  }

  if (isset($cf['user']['object']) && is_object($cf['user']['object']) && $cf['user']['object']->uid > 0) {
    $path_parts = explode('/', $cf['at']['path']);
    $count_parts = count($path_parts);

    if ($count_parts > 1 && $path_parts[0] == 'events') {
      if ($count_parts > 2) {
        if ($path_parts[1] == 'view-0' && cf_is_integer($path_parts[2])) {
          $cf['data']['page']['precrumb'] = '<div class="crumb-event_id">' . "Request " . $path_parts[2] . '</div>';
          $cf['show']['page']['precrumb'] = TRUE;
        }
        elseif ($path_parts[1] == 'edit-0' && cf_is_integer($path_parts[2])) {
          $cf['data']['page']['precrumb'] = '<div class="crumb-event_id">' . "Request " . $path_parts[2] . '</div>';
          $cf['show']['page']['precrumb'] = TRUE;
        }
      }

      if ($path_parts[1] == 'create-0') {
        $cf['data']['page']['precrumb'] = '<div class="crumb-event_id">' . "New Request" . '</div>';
        $cf['show']['page']['precrumb'] = TRUE;
      }
    }
  }
}

/**
 * Implements hook_preprocess_toolbar().
 */
function mcneese_event_workflow_preprocess_toolbar(&$vars) {
  $cf = &drupal_static('cf_theme_get_variables', array());

  if (empty($cf)) {
    mcneese_initialize_variables($vars);
  }

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

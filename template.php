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
  global $base_url;
  $cf = & drupal_static('cf_theme_get_variables', array());
}

/**
 * @} End of '@defgroup mcneese McNeese - event_workflow Theme'.
 */

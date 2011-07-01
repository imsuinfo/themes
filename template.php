<?php

/**
 * Override or insert variables into the maintenance page template.
 */
function mcneese_drupal_preprocess_maintenance_page(&$vars) {
  // While markup for normal pages is split into page.tpl.php and html.tpl.php,
  // the markup for the maintenance page is all in the single
  // maintenance-page.tpl.php template. So, to have what's done in
  // mcneese_drupal_preprocess_html() also happen on the maintenance page, it has to be
  // called here.
  mcneese_drupal_preprocess_html($vars);
  mcneese_drupal_preprocess_page($vars);
}

/**
 * Override or insert variables into the html template.
 */
function mcneese_drupal_preprocess_html(&$vars) {
  drupal_add_css(path_to_theme() . '/css/ie.css', array('group' => CSS_THEME, 'browsers' => array('IE' => 'lte IE 8', '!IE' => FALSE), 'preprocess' => FALSE, 'weight' => 2));
  drupal_add_css(path_to_theme() . '/css/ie6.css', array('group' => CSS_THEME, 'browsers' => array('IE' => 'lt IE 7', '!IE' => FALSE), 'preprocess' => FALSE, 'weight' => 2));

  if (!is_array($vars)){
    $vars = array();
  }

  $vars['theme_base_path'] = base_path() . path_to_theme();
  $vars['site_name']       = variable_get('site_name');
  $vars['in_overlay']      = '';
  $vars['in_overlay_css']  = '';

  if (module_exists('overlay')){
    $overlay_mode = overlay_get_mode();

    if ($overlay_mode == 'child'){
      $vars['in_overlay'] = 'child';
      $vars['in_overlay_css'] = ' mcneese_drupal-in_overlay';
    }
  }
}

/**
 * Override or insert variables into the page template.
 */
function mcneese_drupal_preprocess_page(&$vars) {
  if (!is_array($vars)){
    $vars = array();
  }

  $vars['primary_local_tasks']   = menu_primary_local_tasks();
  $vars['secondary_local_tasks'] = menu_secondary_local_tasks();
  $vars['theme_base_path']       = base_path() . path_to_theme();
  $vars['site_name']             = variable_get('site_name');
  $vars['in_overlay']            = '';

  if (module_exists('overlay')){
    $overlay_mode = overlay_get_mode();

    if ($overlay_mode == 'child'){
      $vars['in_overlay'] = 'child';
    }
  }
}

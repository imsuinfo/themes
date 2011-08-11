<?php

/**
 * Override or insert variables into the maintenance page template.
 */
function mcneese_drupal_preprocess_maintenance_page(&$vars) {
  if (!is_array($vars)){
    $vars = array();
  }

  if (empty($vars['msu'])){
    $vars['msu'] = msu_generic_theme_get_variables($vars);
  }
}

/**
 * Override or insert variables into the html template.
 */
function mcneese_drupal_preprocess_html(&$vars) {
  if (!is_array($vars)){
    $vars = array();
  }

  if (empty($vars['msu'])){
    $vars['msu'] = msu_generic_theme_get_variables($vars);
  }
}

/**
 * Override or insert variables into the page template.
 */
function mcneese_drupal_preprocess_page(&$vars) {
  if (!is_array($vars)){
    $vars = array();
  }

  if (empty($vars['msu'])){
    $vars['msu'] = msu_generic_theme_get_variables($vars);
  }

  $vars['primary_local_tasks']   = menu_primary_local_tasks();
  $vars['secondary_local_tasks'] = menu_secondary_local_tasks();

  $keys_to_render = array('logo', 'messages', 'title_prefix', 'title_suffix', 'side_links', 'primary_local_tasks', 'secondary_local_tasks', 'action_links');
  msu_generic_theme_render_variables($vars, $keys_to_render);

  $keys_to_render = array('header', 'sub_header', 'help', 'subtitle', 'sidebar_left', 'sidebar_right', 'content', 'footer');
  msu_generic_theme_render_variables($vars, $keys_to_render, 'page');

  // always show the following fields
  $vars['msu']['show']['title'] = TRUE;
  $vars['msu']['show']['breadcrumb'] = TRUE;
  $vars['msu']['show']['page']['content'] = TRUE;
  $vars['msu']['show']['page']['footer'] = TRUE;
}

/**
 * Implements hook_msu_generic_theme_get_variables_alter().
 */
function mcneese_drupal_msu_generic_theme_get_variables_alter(&$msu, $variables){
  $msu['theme']['path'] = path_to_theme();
  $msu['theme']['machine_name'] = 'mcneese_drupal';
  $msu['theme']['human_name'] = t("McNeese Drupal");

  if ($msu['is']['unsupported']){
    $msu['is_data']['unsupported']['message'] = t("You are using an unsupported version of :name. Please upgrade your webbrowser or <a href='@alternate_browser_url'>download an alternative browser</a>.", array(':name' => $msu['agent']['machine_name'], '@alternate_browser_url' => "/supported_browsers"));
  }

  switch($msu['agent']['machine_name']){
    case 'firefox':
      break;
    case 'mozilla':
      $matches = array();

      $result = preg_match('/rv:(\d*)\.(\d*)/i', $msu['agent']['raw'], $matches);
      if ($result > 0){
        if (isset($matches[1]) && isset($matches[2])) {
          if ($matches[1] <= 1 && $matches[2] <= 7){
            $custom_css = array();
            $custom_css['data'] = $msu['theme']['path'] . '/css/moz_old.css';
            $custom_css['options'] = array('group' => CSS_THEME, 'every_page' => TRUE, 'weight' => 2);

            //$msu['css'][] = $custom_css;
            drupal_add_css($custom_css['data'], (!empty($custom_css['options']) ? $custom_css['options'] : NULL));
          }
        }
      }

      break;
    case 'ie':
      $custom_css = array();
      $custom_css['data'] = $msu['theme']['path'] . '/css/ie8.css';
      $custom_css['options'] = array('group' => CSS_THEME, 'browsers' => array('IE' => 'lte IE 8', '!IE' => FALSE), 'every_page' => TRUE, 'weight' => 2);

      //$msu['css'][] = $custom_css;
      drupal_add_css($custom_css['data'], (!empty($custom_css['options']) ? $custom_css['options'] : NULL));

      if ($msu['agent']['major_version'] < 8){
        $custom_css = array();
        $custom_css['data'] = $msu['theme']['path'] . '/css/ie_old.css';
        $custom_css['options'] = array('group' => CSS_THEME, 'browsers' => array('IE' => 'lte IE 7', '!IE' => FALSE), 'every_page' => TRUE, 'weight' => 3);

        //$msu['css'][] = $custom_css;
        drupal_add_css($custom_css['data'], (!empty($custom_css['options']) ? $custom_css['options'] : NULL));
      }

      break;
    case 'chrome':
    case 'safari':
    case 'midori':
      $custom_css = array();
      $custom_css['data'] = $msu['theme']['path'] . '/css/webkit.css';
      $custom_css['options'] = array('group' => CSS_THEME, 'every_page' => TRUE, 'weight' => 2);

      //$msu['css'][] = $custom_css;
      drupal_add_css($custom_css['data'], (!empty($custom_css['options']) ? $custom_css['options'] : NULL));

      break;
    default:
      switch($msu['agent']['engine']){
        case 'webkit':

          $custom_css = array();
          $custom_css['data'] = $msu['theme']['path'] . '/css/webkit.css';
          $custom_css['options'] = array('group' => CSS_THEME, 'every_page' => TRUE, 'weight' => 2);

          //$msu['css'][] = $custom_css;
          drupal_add_css($custom_css['data'], (!empty($custom_css['options']) ? $custom_css['options'] : NULL));
          break;
        case 'trident':
          $custom_css = array();
          $custom_css['data'] = $msu['theme']['path'] . '/css/ie8.css';
          $custom_css['options'] = array('group' => CSS_THEME, 'browsers' => array('IE' => 'lte IE 8', '!IE' => FALSE), 'every_page' => TRUE, 'weight' => 2);

          //$msu['css'][] = $custom_css;
          drupal_add_css($custom_css['data'], (!empty($custom_css['options']) ? $custom_css['options'] : NULL));
          break;
      }
      break;
  }
}

/**
 * Implements hook_form_FORM_ID_alter() for search_block_form.
 */
function mcneese_drupal_form_search_block_form_alter(&$form, &$form_state, $form_id){
  // This themes css uses a background image instead of the #value text, therefore the input buttons search text must be removed.
  // If this is not removed then a number of browser, some webkit-based, some mozilla-based will have presentation problems.
  $form['actions']['submit'] = array('#type' => 'submit', '#value' => '', '#attributes' => array('title' => t('Search')));
}

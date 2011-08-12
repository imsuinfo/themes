<?php

/**
 * Override or insert variables into the maintenance page template.
 */
function mcneese_drupal_preprocess_maintenance_page(&$vars) {
  if (!is_array($vars)){
    $vars = array();
  }

  if (empty($vars['cf'])){
    $vars['cf'] = cf_theme_get_variables($vars);
  }

  // while is considered not accessible, it should be done on the maintainance page to help ensure accessibility
  // this is because the maintenance page means the site is not accessible
  // with this enabled on the maintenance page, it should help the user gain access to the website as soon as it is up.
  // TODO: add support for specifying an approximate refresh time when the site is put into maintenance mode.
  // default to a 30-minute page expiration/refresh.
  $cf['meta']['name']['refresh'] = '1800';

  $date_value = strtotime('+1800 seconds', $cf['request']);
  $cf['meta']['name']['expires'] = gmdate('D, d M Y H:i:s T', $date_value);
  $cf['meta']['http-equiv']['expires'] = gmdate('D, d M Y H:i:s T', $date_value);
}

/**
 * Override or insert variables into the html template.
 */
function mcneese_drupal_preprocess_html(&$vars) {
  if (!is_array($vars)){
    $vars = array();
  }

  if (empty($vars['cf'])){
    $vars['cf'] = cf_theme_get_variables($vars);
  }

  // refresh is considered not accessible
  $cf['meta']['name']['refresh'] = '';
}

/**
 * Override or insert variables into the page template.
 */
function mcneese_drupal_preprocess_page(&$vars) {
  if (!is_array($vars)){
    $vars = array();
  }

  if (empty($vars['cf'])){
    $vars['cf'] = cf_theme_get_variables($vars);
  }

  $vars['primary_local_tasks']   = menu_primary_local_tasks();
  $vars['secondary_local_tasks'] = menu_secondary_local_tasks();

  $keys_to_render = array('logo', 'messages', 'title_prefix', 'title_suffix', 'side_links', 'primary_local_tasks', 'secondary_local_tasks', 'action_links');
  cf_theme_render_variables($vars, $keys_to_render);

  $keys_to_render = array('header', 'sub_header', 'help', 'subtitle', 'sidebar_left', 'sidebar_right', 'content', 'footer');
  cf_theme_render_variables($vars, $keys_to_render, 'page');

  // always show the following fields
  $vars['cf']['show']['title'] = TRUE;
  $vars['cf']['show']['breadcrumb'] = TRUE;
  $vars['cf']['show']['page']['content'] = TRUE;
  $vars['cf']['show']['page']['footer'] = TRUE;
}

/**
 * Implements hook_cf_theme_get_variables_alter().
 */
function mcneese_drupal_cf_theme_get_variables_alter(&$cf, $variables){
  $cf['theme']['path'] = path_to_theme();
  $cf['theme']['machine_name'] = 'mcneese_drupal';
  $cf['theme']['human_name'] = t("McNeese Drupal");

  if ($cf['is']['unsupported']){
    $cf['is_data']['unsupported']['message'] = t("You are using an unsupported version of :name. Please upgrade your webbrowser or <a href='@alternate_browser_url'>download an alternative browser</a>.", array(':name' => $cf['agent']['machine_name'], '@alternate_browser_url' => "/supported_browsers"));
  }

  switch($cf['agent']['machine_name']){
    case 'firefox':
      break;
    case 'mozilla':
      $matches = array();

      $result = preg_match('/rv:(\d*)\.(\d*)/i', $cf['agent']['raw'], $matches);
      if ($result > 0){
        if (isset($matches[1]) && isset($matches[2])) {
          if ($matches[1] <= 1 && $matches[2] <= 7){
            $custom_css = array();
            $custom_css['data'] = $cf['theme']['path'] . '/css/moz_old.css';
            $custom_css['options'] = array('group' => CSS_THEME, 'every_page' => TRUE, 'weight' => 2);

            //$cf['css'][] = $custom_css;
            drupal_add_css($custom_css['data'], (!empty($custom_css['options']) ? $custom_css['options'] : NULL));
          }
        }
      }

      break;
    case 'ie':
      $custom_css = array();
      $custom_css['data'] = $cf['theme']['path'] . '/css/ie8.css';
      $custom_css['options'] = array('group' => CSS_THEME, 'browsers' => array('IE' => 'lte IE 8', '!IE' => FALSE), 'every_page' => TRUE, 'weight' => 2);

      //$cf['css'][] = $custom_css;
      drupal_add_css($custom_css['data'], (!empty($custom_css['options']) ? $custom_css['options'] : NULL));

      if ($cf['agent']['major_version'] < 8){
        $custom_css = array();
        $custom_css['data'] = $cf['theme']['path'] . '/css/ie_old.css';
        $custom_css['options'] = array('group' => CSS_THEME, 'browsers' => array('IE' => 'lte IE 7', '!IE' => FALSE), 'every_page' => TRUE, 'weight' => 3);

        //$cf['css'][] = $custom_css;
        drupal_add_css($custom_css['data'], (!empty($custom_css['options']) ? $custom_css['options'] : NULL));
      }

      break;
    case 'chrome':
    case 'safari':
    case 'midori':
      $custom_css = array();
      $custom_css['data'] = $cf['theme']['path'] . '/css/webkit.css';
      $custom_css['options'] = array('group' => CSS_THEME, 'every_page' => TRUE, 'weight' => 2);

      //$cf['css'][] = $custom_css;
      drupal_add_css($custom_css['data'], (!empty($custom_css['options']) ? $custom_css['options'] : NULL));

      break;
    default:
      switch($cf['agent']['engine']){
        case 'webkit':

          $custom_css = array();
          $custom_css['data'] = $cf['theme']['path'] . '/css/webkit.css';
          $custom_css['options'] = array('group' => CSS_THEME, 'every_page' => TRUE, 'weight' => 2);

          //$cf['css'][] = $custom_css;
          drupal_add_css($custom_css['data'], (!empty($custom_css['options']) ? $custom_css['options'] : NULL));
          break;
        case 'trident':
          $custom_css = array();
          $custom_css['data'] = $cf['theme']['path'] . '/css/ie8.css';
          $custom_css['options'] = array('group' => CSS_THEME, 'browsers' => array('IE' => 'lte IE 8', '!IE' => FALSE), 'every_page' => TRUE, 'weight' => 2);

          //$cf['css'][] = $custom_css;
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

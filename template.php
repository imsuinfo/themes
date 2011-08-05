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
  $agent_settings = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';

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

  if (function_exists('get_browser') && isset($_SERVER['HTTP_USER_AGENT'])){
    $browser_details = get_browser(null, true);

    if (!empty($browser_details['browser'])){
      $browser = strtolower($browser_details['browser']);
    }

    if (empty($browser_details['majorver'])){
      // do not process if no version number can be found
      $browser = '';
    } else {
      $majorver = $browser_details['majorver'];
    }

    switch ($browser){
      case 'firefox':
        if ($majorver < 3){
          $vars['unsupported'] = t("You are using an unsupported version of Mozilla Firefox. To properly view this website, please upgrade your webbrowser or <a href='@alternate_browser_url'>download an alternative browser</a>.", array('@alternate_browser_url' => "/supported_browsers"));
        }
        break;
      case 'mozilla':
        // get the gecko api version and report unsupported for old mozilla apis
        if (!empty($agent_settings)){
          $matches = array();
          $result = preg_match('/rv:(\d*)\.(\d*)/i', $agent_settings, $matches);
          if ($result > 0){
            if (isset($matches[1]) && isset($matches[2])) {
              if ($matches[1] <= 1 && $matches[2] <= 7){
                drupal_add_css(path_to_theme() . '/css/moz_old.css', array('group' => CSS_THEME, 'preprocess' => FALSE, 'weight' => 3));
                $vars['unsupported'] = t("You are using an unsupported version of Mozilla. To properly view this website, please upgrade your webbrowser or <a href='@alternate_browser_url'>download an alternative browser</a>.", array('@alternate_browser_url' => "/supported_browsers"));
              }
            }
          }
        }
        break;
      case 'ie':
        drupal_add_css(path_to_theme() . '/css/ie8.css', array('group' => CSS_THEME, 'browsers' => array('IE' => 'lte IE 8', '!IE' => FALSE), 'preprocess' => FALSE, 'weight' => 2));

        if ($majorver < 8){
          drupal_add_css(path_to_theme() . '/css/ie_old.css', array('group' => CSS_THEME, 'browsers' => array('IE' => 'lte IE 7', '!IE' => FALSE), 'preprocess' => FALSE, 'weight' => 3));

          // check for gecko in case some firefox browsers are set to report as ie6 or ie7
          if (!empty($agent_settings) && preg_match('/ Gecko/i', $agent_settings) == 0){
            $vars['unsupported'] = t("You are using an unsupported version of Internet Explorer. To properly view this website, please upgrade your webbrowser or <a href='@alternate_browser_url'>download an alternative browser</a>.", array('@alternate_browser_url' => "/supported_browsers"));
          }
        }
        break;
      case 'chrome':
        break;
      case 'opera':
        break;
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

/**
 * Implements hook_form_FORM_ID_alter() for search_block_form.
 */
function mcneese_drupal_form_search_block_form_alter(&$form, &$form_state, $form_id){
  // This themes css uses a background image instead of the #value text, therefore the input buttons search text must be removed.
  // If this is not removed then a number of browser, some webkit-based, some mozilla-based will have presentation problems.
  $form['actions']['submit'] = array('#type' => 'submit', '#value' => '', '#attributes' => array('title' => t('Search')));
}

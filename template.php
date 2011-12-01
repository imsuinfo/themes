<?php

/**
 * Override or insert variables into the maintenance page template.
 */
function mcneese_drupal_preprocess_maintenance_page(&$vars) {
  if (!is_array($vars)){
    $vars = array();
  }

  // store sidebar_first and sidebar_second in left and right sidebars
  if (!empty($vars['sidebar_first'])){
    $vars['sidebar_left'] .= $vars['sidebar_first'];
    unset($vars['sidebar_first']);
  }

  if (!empty($vars['sidebar_second'])){
    $vars['sidebar_right'] .= $vars['sidebar_second'];
    unset($vars['sidebar_second']);
  }

  if (!function_exists('cf_theme_get_variables')){
    return;
  }

  if (empty($vars['cf'])){
    $vars['cf'] = cf_theme_get_variables($vars);
  }

  $keys_to_render = array('logo', 'title_prefix', 'title_suffix', 'side_links', 'primary_local_tasks', 'secondary_local_tasks', 'action_links');
  cf_theme_render_variables($vars, $keys_to_render);

  $keys_to_render = array('header', 'sub_header', 'help', 'sidenote', 'sidebar_left', 'sidebar_right', 'content', 'footer');
  cf_theme_render_variables($vars, $keys_to_render);

  // always show the following fields
  $vars['cf']['show']['title'] = TRUE;
  $vars['cf']['show']['breadcrumb'] = TRUE;
  $vars['cf']['show']['messages'] = TRUE;
  $vars['cf']['show']['content'] = TRUE;
  $vars['cf']['show']['footer'] = TRUE;

  // while is considered not accessible, it should be done on the maintainance page to help ensure accessibility
  // this is because the maintenance page means the site is not available
  // with this enabled on the maintenance page, it should help the user gain access to the website as soon as it is up.
  // TODO: add support for specifying an approximate refresh time when the site is put into maintenance mode.
  // default to a 30-minute page expiration/refresh.
  $vars['cf']['meta']['name']['refresh'] = '1800';

  $date_value = strtotime('+1800 seconds', $vars['cf']['request']);
  $vars['cf']['meta']['name']['expires'] = gmdate('D, d M Y H:i:s T', $date_value);
  $vars['cf']['meta']['http-equiv']['expires'] = gmdate('D, d M Y H:i:s T', $date_value);

  // register that this is a maintenance page
  $vars['cf']['is']['maintenance'] = TRUE;
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
  $vars['cf']['meta']['name']['refresh'] = '';
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

  $keys_to_render = array('logo', 'title_prefix', 'title_suffix', 'side_links', 'primary_local_tasks', 'secondary_local_tasks', 'action_links');
  cf_theme_render_variables($vars, $keys_to_render);

  $keys_to_render = array('header', 'messages', 'sub_header', 'help', 'sidenote', 'sidebar_left', 'sidebar_right', 'content', 'footer');
  cf_theme_render_variables($vars, $keys_to_render, 'page');

  // always show the following fields
  $vars['cf']['show']['title'] = TRUE;
  $vars['cf']['show']['breadcrumb'] = TRUE;
  $vars['cf']['show']['messages'] = TRUE;
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

  $cf['date']['enabled'] = TRUE;

  $msu['meta']['name']['copyright'] = '2011© McNeese State University';
  $msu['meta']['name']['description'] = 'McNeese State University Website';
  $msu['meta']['name']['distribution'] = 'web';

  foreach (array('in_ie_compatibility_mode') as $key){
    $cf['is'][$key] = FALSE;
    $cf['is_data'][$key] = array();
  }

  if (!$cf['is']['logged_in']){
    if ($cf['is']['front']){
      $date_value = strtotime('+1 hour', $cf['request']);
    }
    else {
      $date_value = strtotime('+3 hours', $cf['request']);
    }

    $cf['meta']['name']['expires'] = gmdate('D, d M Y H:i:s T', $date_value);
    $cf['meta']['http-equiv']['expires'] = gmdate('D, d M Y H:i:s T', $date_value);
  }
  else {
    $cf['meta']['http-equiv']['cache-control'] = 'no-cache';
  }

  // html 5 doctype
  $cf['agent']['doctype'] = '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML+RDFa 1.1//EN">';

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
            $cf['is']['unsupported'] = TRUE;

            //$cf['css'][] = $custom_css;
            drupal_add_css($custom_css['data'], (!empty($custom_css['options']) ? $custom_css['options'] : NULL));
          }
        }
      }

      break;
    case 'ie':
      // IE ignores non-ancient css unless the following doctype is used
      $cf['agent']['doctype'] = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">';

      // enforce ie8 compatibility mode
      $cf['meta']['http-equiv']['X-UA-Compatible'] = 'IE=8';

      $custom_css = array();
      $custom_css['data'] = $cf['theme']['path'] . '/css/ie8.css';
      $custom_css['options'] = array('group' => CSS_THEME, 'every_page' => TRUE, 'weight' => 2);

      //$cf['css'][] = $custom_css;
      drupal_add_css($custom_css['data'], (!empty($custom_css['options']) ? $custom_css['options'] : NULL));

      if ($cf['agent']['major_version'] < 8){
        $custom_css = array();
        $custom_css['options'] = array('group' => CSS_THEME, 'every_page' => TRUE, 'weight' => 3);
        $custom_css['data'] = $cf['theme']['path'] . '/css/ie_old.css';
        $cf['is']['unsupported'] = TRUE;

        //$cf['css'][] = $custom_css;
        drupal_add_css($custom_css['data'], (!empty($custom_css['options']) ? $custom_css['options'] : NULL));

        if ($cf['agent']['major_version'] == 7){
          if (preg_match("@; Trident/@", $cf['agent']['raw']) > 0){
            $cf['is']['in_ie_compatibility_mode'] = TRUE;
          }
        }
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

  // FIXME: this should be moved to cf_www
  // If the page is part of a group content type, then display the group_image view.
  if ($cf['is']['node']) {
    if ($cf['is']['logged_in'] && is_object($cf['is_data']['node']['object'])){
      $cf['show']['sidenote'] = TRUE;
      $cf['data']['sidenote']['content'] = '<span class="sidenote-node_id-label">' . t("Node") . '</span> <span class="sidenote-node_id-number">' . check_plain($cf['is_data']['node']['object']->nid) . '</span>';
    }
  }

  if ($cf['is']['unsupported']){
    if ($cf['is']['in_ie_compatibility_mode']){
      $cf['is_data']['unsupported']['message'] = t("You are running Internet Explorer in compatibility mode. To improve your experience using this website, please <a href='@alternate_browser_url'>turn off compatibility mode</a>.", array('@alternate_browser_url' => "http://www.sevenforums.com/tutorials/1196-internet-explorer-compatibility-view-turn-off.html#post_message_10408"));
    }
    else {
      $cf['is_data']['unsupported']['message'] = t("You are using an unsupported version of :name. Please upgrade your webbrowser or <a href='@alternate_browser_url'>download an alternative browser</a>.", array(':name' => $cf['agent']['machine_name'], '@alternate_browser_url' => "/supported_browsers"));
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

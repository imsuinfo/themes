<?php

/**
 * Custom initialization function designed to set defaults.
 *
 * This is provided to handle the case where the cf functions are not available, for whatever reason.
 * This provides a manual setup of the default array keys used in the appropriate *.tpl.php theme templates.
 * The initialized variables is the bare minimumi and defaults most conditionals to FALSE.
 */
function mcneese_drupal_initialize_cf_array(&$vars) {
  $vars['cf'] = array();
  $cf = &$vars['cf'];
  $page = &$vars['page'];


  $cf['agent'] = array();
  $cf['agent']['doctype'] = '';

  $cf['at'] = array();
  $cf['is'] = array();
  $cf['show'] = array();
  $cf['show']['page'] = array();

  foreach (array('front', 'path', 'alias') as $item) {
    $cf['at'][$item] = '';
  }

  foreach (array('overlay', 'unsupported', 'emergency') as $item) {
    $cf['is'][$item] = FALSE;
  }

  foreach (array('help', 'sidebar_left', 'sidebar_right', ) as $item) {
    $cf['show']['page'][$item] = FALSE;
  }

  foreach (array('header', 'sub_header', 'messages', 'content', 'footer') as $item) {
    $cf['data']['page'][$item] = drupal_render($page[$item]);
    $cf['show']['page'][$item] = TRUE;
  }

  foreach (array('logo', 'title_prefix', 'title_suffix', 'sidenote', 'side_links', 'breadcrumb') as $item) {
    $cf['show'][$item] = FALSE;
  }

  foreach (array('primary_local_tasks', 'secondary_local_tasks', 'action_links') as $item) {
    $cf['show'][$item] = FALSE;
  }

  foreach (array('messages', 'title', 'messages') as $item) {
    $cf['show'][$item] = TRUE;
  }

  // some defaults we can guess
  $cf['is']['front'] = drupal_is_front_page();

  $cf['markup_css'] = array();
  $cf['markup_css']['body'] = array();
  $cf['markup_css']['body']['class'] = '';
  $cf['markup_css']['container'] = array();
  $cf['markup_css']['container']['class'] = '';
  $cf['markup_css']['content'] = array();
  $cf['markup_css']['content']['class'] = '';

  return $cf;
}

/**
 * Override or insert variables into the maintenance page template.
 */
function mcneese_drupal_preprocess_maintenance_page(&$vars) {
  global $base_path;

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

  // convert drupal core theme structure to cf theme structure
  $keys_to_render = array('logo', 'title_prefix', 'title_suffix', 'side_links', 'primary_local_tasks', 'secondary_local_tasks', 'action_links');
  foreach ($keys_to_render as $key) {
    if (isset($vars[$key])) {
      $cf['data'][$key] = & $vars[$key];
    }
  }

  if (!function_exists('cf_theme_get_variables')){
    mcneese_drupal_initialize_cf_array($vars);
    return;
  }

  if (empty($vars['cf'])){
    $vars['cf'] = cf_theme_get_variables($vars);
  }

  $keys_to_render = array('logo', 'title_prefix', 'title_suffix', 'side_links', 'primary_local_tasks', 'secondary_local_tasks', 'action_links');
  cf_theme_render_variables($vars, $keys_to_render);

  $keys_to_render = array('header', 'messages', 'sub_header', 'help', 'sidebar_left', 'sidebar_right', 'content', 'footer');
  cf_theme_render_variables($vars, $keys_to_render, 'page');

  // always show the following fields
  $vars['cf']['show']['title'] = TRUE;
  $vars['cf']['show']['breadcrumb'] = TRUE;
  $vars['cf']['show']['page']['messages'] = TRUE;
  $vars['cf']['show']['page']['content'] = TRUE;
  $vars['cf']['show']['page']['footer'] = TRUE;
  $vars['cf']['show']['page']['sidebar_left'] = TRUE;

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

  // process logo
  $vars['cf']['data']['logo']['title'] = $vars['cf']['at']['human_name'];
  $vars['cf']['data']['logo']['alt'] = $vars['cf']['at']['human_name'];
  $vars['cf']['data']['logo']['src'] = $base_path . path_to_theme() . '/images/web_logo.png';

  if ($vars['cf']['at']['machine_name'] == 'sandbox.mcneese.edu' || $vars['cf']['at']['machine_name'] == 'sandbox') {
    $vars['cf']['data']['logo']['title'] = 'Sandbox of ' . $vars['cf']['at']['human_name'];
    $vars['cf']['data']['logo']['alt'] = 'Sandbox of ' . $vars['cf']['at']['human_name'];
    $vars['cf']['data']['logo']['src'] = $base_path . path_to_theme() . '/images/sandbox.png';
  }
}

/**
 * Override or insert variables into the html template.
 */
function mcneese_drupal_preprocess_html(&$vars) {
  if (!is_array($vars)){
    $vars = array();
  }

  // convert drupal core theme structure to cf theme structure
  $keys_to_render = array('logo', 'title_prefix', 'title_suffix', 'side_links', 'primary_local_tasks', 'secondary_local_tasks', 'action_links');
  foreach ($keys_to_render as $key) {
    if (isset($vars[$key])) {
      $cf['data'][$key] = & $vars[$key];
    }
  }

  if (!function_exists('cf_theme_get_variables')){
    mcneese_drupal_initialize_cf_array($vars);
    return;
  }

  if (empty($vars['cf'])){
    $vars['cf'] = cf_theme_get_variables($vars);
  }

  // refresh is considered not accessible
  $vars['cf']['meta']['name']['refresh'] = '';


  if ($vars['cf']['at']['machine_name'] == 'sandbox.mcneese.edu' || $vars['cf']['at']['machine_name'] == 'sandbox') {
    $vars['head_title'] = "Sandbox of (" . $vars['head_title'] . ")";
  } else if ($vars['cf']['at']['machine_name'] == 'wwwdev.mcneese.edu' || $vars['cf']['at']['machine_name'] == 'wwwdev') {
    $vars['head_title'] = $vars['head_title'] . " | Development";
  } else if ($vars['cf']['at']['machine_name'] == 'wwwdev2.mcneese.edu' || $vars['cf']['at']['machine_name'] == 'wwwdev2') {
    $vars['head_title'] = $vars['head_title'] . " | Development";
  } else if ($vars['cf']['at']['machine_name'] == 'wwwdev3.mcneese.edu' || $vars['cf']['at']['machine_name'] == 'wwwdev3') {
    $vars['head_title'] = $vars['head_title'] . " | Development";
  }
}

/**
 * Override or insert variables into the page template.
 */
function mcneese_drupal_preprocess_page(&$vars) {
  global $base_path;

  if (!is_array($vars)){
    $vars = array();
  }

  if (!function_exists('cf_theme_get_variables')){
    mcneese_drupal_initialize_cf_array($vars);
    return;
  }

  if (empty($vars['cf'])){
    $vars['cf'] = cf_theme_get_variables($vars);
  }

  $vars['primary_local_tasks']   = menu_primary_local_tasks();
  $vars['secondary_local_tasks'] = menu_secondary_local_tasks();

  $keys_to_render = array('logo', 'title_prefix', 'title_suffix', 'side_links', 'primary_local_tasks', 'secondary_local_tasks', 'action_links');
  cf_theme_render_variables($vars, $keys_to_render);

  $keys_to_render = array('header', 'messages', 'sub_header', 'help', 'sidebar_left', 'sidebar_right', 'content', 'footer');
  cf_theme_render_variables($vars, $keys_to_render, 'page');

  // always show the following fields
  $vars['cf']['show']['title'] = TRUE;
  $vars['cf']['show']['breadcrumb'] = TRUE;
  $vars['cf']['show']['messages'] = TRUE;
  $vars['cf']['show']['page']['content'] = TRUE;
  $vars['cf']['show']['page']['footer'] = TRUE;

  // process logo
  $vars['cf']['data']['logo']['title'] = $vars['cf']['at']['human_name'];
  $vars['cf']['data']['logo']['alt'] = $vars['cf']['at']['human_name'];
  $vars['cf']['data']['logo']['src'] = $base_path . path_to_theme() . '/images/web_logo.png';

  if ($vars['cf']['at']['machine_name'] == 'sandbox.mcneese.edu' || $vars['cf']['at']['machine_name'] == 'sandbox') {
    $vars['cf']['data']['logo']['title'] = 'Sandbox of ' . $vars['cf']['at']['human_name'];
    $vars['cf']['data']['logo']['alt'] = 'Sandbox of ' . $vars['cf']['at']['human_name'];
    $vars['cf']['data']['logo']['src'] = $base_path . path_to_theme() . '/images/sandbox.png';
  }
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

  $cf['show']['sidenote'] = FALSE;
  $cf['data']['sidenote'] = array();

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

  $cf['show']['logo'] = TRUE;
  $cf['data']['logo'] = array();

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
            $custom_css['options'] = array('group' => CSS_THEME, 'every_page' => TRUE, 'weight' => 2, 'media' => 'all');
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
      $cf['meta']['http-equiv']['X-UA-Compatible'] = 'IE=Edge; IE=9; IE=8';

      if ($cf['agent']['major_version'] <= 8){
        $custom_css = array();
        $custom_css['data'] = $cf['theme']['path'] . '/css/ie8.css';
        $custom_css['options'] = array('group' => CSS_THEME, 'every_page' => TRUE, 'weight' => 2, 'media' => 'all');

        //$cf['css'][] = $custom_css;
        drupal_add_css($custom_css['data'], (!empty($custom_css['options']) ? $custom_css['options'] : NULL));
      }

      if ($cf['agent']['major_version'] < 8){
        $custom_css = array();
        $custom_css['options'] = array('group' => CSS_THEME, 'every_page' => TRUE, 'weight' => 3, 'media' => 'all');
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
      $custom_css['options'] = array('group' => CSS_THEME, 'every_page' => TRUE, 'weight' => 2, 'media' => 'all');

      //$cf['css'][] = $custom_css;
      drupal_add_css($custom_css['data'], (!empty($custom_css['options']) ? $custom_css['options'] : NULL));

      break;
    default:
      switch($cf['agent']['engine']){
        case 'webkit':

          $custom_css = array();
          $custom_css['data'] = $cf['theme']['path'] . '/css/webkit.css';
          $custom_css['options'] = array('group' => CSS_THEME, 'every_page' => TRUE, 'weight' => 2, 'media' => 'all');

          //$cf['css'][] = $custom_css;
          drupal_add_css($custom_css['data'], (!empty($custom_css['options']) ? $custom_css['options'] : NULL));
          break;
        case 'trident':
          $custom_css = array();
          $custom_css['data'] = $cf['theme']['path'] . '/css/ie8.css';
          $custom_css['options'] = array('group' => CSS_THEME, 'browsers' => array('IE' => 'lte IE 8', '!IE' => FALSE), 'every_page' => TRUE, 'weight' => 2, 'media' => 'all');

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

/**
 * Implements hook_breadcrumb().
 */
function mcneese_drupal_breadcrumb($variables) {
  $breadcrumb = $variables['breadcrumb'];

  $output = '<h2 class="element-invisible">' . t("Breadcrumbs") . '</h2>';
  $output .= '<!--REPLACE_CUSTOM_DATA-->';
  $output .= '<div class="breadcrumb">' . implode(' » ', $breadcrumb) . '</div>';

  return $output;
}

<?php

/**
 * @file
 * McNeese - www Theme.
 */

/**
 * @defgroup mcneese McNeese - www Theme
 * @ingroup mcneese
 * @{
 * Provides the www.mcneese.edu mcneese theme.
 */

/**
 * Implements hook_mcneese_get_variables_alter().
 */
function mcneese_www_mcneese_get_variables_alter(&$cf, $vars) {
  $cf['subtheme']['path'] = base_path() . drupal_get_path('theme', 'mcneese_www');
  $cf['subtheme']['machine_name'] = 'mcneese_www';
  $cf['subtheme']['human_name'] = t("McNeese WWW");

  if (function_exists('node_load') && $cf['is']['maintenance']) {
    $loaded_node = node_load(914);

    if (is_object($loaded_node)) {
      if (property_exists($loaded_node, 'status') && $loaded_node->status == NODE_PUBLISHED) {
        $date_value = strtotime('+900 seconds', $cf['request']);
        $cf['meta']['name']['expires'] = gmdate('D, d M Y H:i:s T', $date_value);
        $cf['meta']['http-equiv']['expires'] = gmdate('D, d M Y H:i:s T', $date_value);
        $cf['meta']['http-equiv']['cache-control'] = 'no-cache';


        $cf['is']['emergency'] = TRUE;
        $cf['is_data']['emergency'] = array();
        $cf['is_data']['emergency']['title'] = $loaded_node->title;
        $cf['is_data']['emergency']['body'] = $loaded_node->body['und']['0']['value'];

        $cf['is_data']['emergency']['message'] = 'This website is operating in <span class="emergency_mode-notice-emergency_mode">Emergency Mode</span>.<br>' . "\n";
        $cf['is_data']['emergency']['message'] .= 'To exit <span class="emergency_mode-notice-emergency_mode">Emergency Mode</span>, a privileged user must unpublish the <a href="/emergency_page">Emergency Page</a>.' . "\n";
      }
    }
  }
}


/**
 * Implements hook_preprocess_maintenance_page().
 */
function mcneese_www_preprocess_maintenance_page(&$vars) {
  mcneese_www_preprocess_html($vars);
}

/**
 * Implements hook_preprocess_html().
 */
function mcneese_www_preprocess_html(&$vars) {
  $cf = & drupal_static('cf_theme_get_variables', array());


  if ($cf['at']['machine_name'] == 'sandbox.mcneese.edu' || $cf['at']['machine_name'] == 'sandbox') {
    $vars['head_title'] = "Sandbox of (" . $vars['head_title'] . ")";
  } else if ($cf['at']['machine_name'] == 'wwwdev.mcneese.edu' || $cf['at']['machine_name'] == 'wwwdev') {
    $vars['head_title'] = $vars['head_title'] . " | Development";
  } else if ($cf['at']['machine_name'] == 'wwwdev2.mcneese.edu' || $cf['at']['machine_name'] == 'wwwdev2') {
    $vars['head_title'] = $vars['head_title'] . " | Development";
  }


  // show google verification on front page and then only on www.mcneese.edu.
  if ($cf['is']['front']) {
    if ($cf['at']['machine_name'] == 'www.mcneese.edu' || $cf['at']['machine_name'] == 'www' || $cf['at']['machine_name'] == 'mcneese.edu') {
      $cf['meta']['name']['google-site-verification'] = 'zvxqEbtWmsaA-WXhhueU_iVFT0I9HJRH-QO0ecOL1XI';
    }
  }

  $attributes = array();
  $attributes['id'] = 'mcneese-www-footer';
  $attributes['class'] = array();
  $attributes['role'] = 'navigation';

  $cf['html']['tags']['mcneese_www_html_footer_open'] = array('name' => 'aside', 'type' => 'semantic', 'attributes' => $attributes, 'html5' => $cf['is']['html5']);
  $cf['html']['tags']['mcneese_www_html_footer_close'] = array('name' => 'aside', 'type' => 'semantic', 'open' => FALSE, 'html5' => $cf['is']['html5']);

  $attributes = array();
  $attributes['class'] = array();
  $attributes['class'][] = 'element-invisible';

  $cf['html']['tags']['mcneese_www_html_footer_heading_open'] = array('name' => 'header', 'type' => 'semantic', 'attributes' => $attributes, 'html5' => $cf['is']['html5']);
  $cf['html']['tags']['mcneese_www_html_footer_heading_close'] = array('name' => 'header', 'type' => 'semantic', 'open' => FALSE, 'html5' => $cf['is']['html5']);

  $attributes = array();
  $attributes['class'] = array();
  $attributes['class'][] = 'column-heading';

  $cf['html']['tags']['mcneese_www_html_footer_column_heading_open'] = array('name' => 'header', 'type' => 'semantic', 'attributes' => $attributes, 'html5' => $cf['is']['html5']);
  $cf['html']['tags']['mcneese_www_html_footer_column_heading_close'] = array('name' => 'header', 'type' => 'semantic', 'open' => FALSE, 'html5' => $cf['is']['html5']);

  if ($cf['is']['emergency'] && !$cf['is']['logged_in']) {
    $vars['head_title'] = $cf['is_data']['emergency']['title'] . ' | McNeese State University';
  }
}

/**
 * Implements hook_preprocess_page().
 */
function mcneese_www_preprocess_page(&$vars) {
  $cf = & drupal_static('cf_theme_get_variables', array());

  $cf['show']['page']['group_image'] = FALSE;
  $cf['data']['page']['group_image'] = array();

  if ($cf['at']['machine_name'] == 'sandbox.mcneese.edu' || $cf['at']['machine_name'] == 'sandbox') {
    $cf['data']['page']['logo']['title'] = 'Sandbox of ' . $cf['data']['page']['logo']['title'];
    $cf['data']['page']['logo']['alt'] = 'Sandbox of ' . $cf['data']['page']['logo']['alt'];
    $cf['data']['page']['logo']['href'] = base_path();
    $cf['show']['page']['logo'] = TRUE;
  }

  if ($cf['at']['machine_name'] == 'wwwdev.mcneese.edu' || $cf['at']['machine_name'] == 'wwwdev' || $cf['at']['machine_name'] == 'wwwdev2.mcneese.edu' || $cf['at']['machine_name'] == 'wwwdev2') {
    $cf['data']['page']['logo']['title'] =  $cf['data']['page']['logo']['title'] . ' Development';
    $cf['data']['page']['logo']['alt'] = $cf['data']['page']['logo']['alt'] . ' Development';
    $cf['data']['page']['logo']['href'] = base_path();
    $cf['show']['page']['logo'] = TRUE;
  }

  if ($cf['is']['unsupported']) {
    if (isset($cf['is']['in_ie_compatibility_mode']) && $cf['is']['in_ie_compatibility_mode']) {
      $cf['is_data']['unsupported']['message'] = t("You are running Internet Explorer in compatibility mode. To improve your experience using this website, please <a href='@alternate_browser_url'>turn off compatibility mode</a>.", array('@alternate_browser_url' => "http://www.sevenforums.com/tutorials/1196-internet-explorer-compatibility-view-turn-off.html#post_message_10408"));
    }
    else {
      $cf['is_data']['unsupported']['message'] = t("You are using an unsupported version of :name. Please upgrade your webbrowser or <a href='@alternate_browser_url'>download an alternative browser</a>.", array(':name' => $cf['agent']['human_name'], '@alternate_browser_url' => "/supported_browsers"));
    }
  }
}

/**
 * Render all data for: page.
 */
function mcneese_www_render_page() {
  global $base_url;
  $cf = & drupal_static('cf_theme_get_variables', array());


  // setup default front page title
  if ($cf['is']['front']) {
    $cf['show']['page']['title'] = TRUE;
    $cf['data']['page']['title'] = t("McNeese State University");
  }


  // node-specific content
  if ($cf['is']['node'] && !($cf['is']['maintenance'] && !$cf['is_data']['maintenance']['access'])) {
    // group image
    if (property_exists($cf['is_data']['node']['object'], 'field_group_image_show') && !empty($cf['is_data']['node']['object']->field_group_image_show)) {
      if (property_exists($cf['is_data']['node']['object'], 'field_group_image_custom') && !empty($cf['is_data']['node']['object']->field_group_image_custom)) {
        $cf['data']['page']['group_image']['class'] = 'noscript group_image ';
        $cf['data']['page']['group_image']['height'] = '200px';

        $url = file_create_url($cf['is_data']['node']['object']->field_group_image_custom['und'][0]['uri']);

        if ($cf['show']['page']['menus'] || $cf['show']['page']['asides']) {
          $cf['data']['page']['group_image']['class'] .= 'group_image-small';
          $cf['data']['page']['group_image']['width'] = '755px';

          $cf['data']['page']['group_image']['src'] = image_style_url('group_image', $cf['is_data']['node']['object']->field_group_image_custom['und'][0]['uri']);
          $cf['data']['page']['group_image']['other'] = image_style_url('group_image_large', $cf['is_data']['node']['object']->field_group_image_custom['und'][0]['uri']);
        }
        else {
          $cf['data']['page']['group_image']['class'] .= 'group_image-large';
          $cf['data']['page']['group_image']['width'] = '960px';

          $cf['data']['page']['group_image']['src'] = image_style_url('group_image_large', $cf['is_data']['node']['object']->field_group_image_custom['und'][0]['uri']);
          $cf['data']['page']['group_image']['other'] = image_style_url('group_image', $cf['is_data']['node']['object']->field_group_image_custom['und'][0]['uri']);
        }

        $cf['data']['page']['group_image']['original'] = preg_replace('`^' . $base_url . '`i', '', $url);
        $cf['data']['page']['group_image']['title'] = $cf['is_data']['node']['object']->field_group_image_custom['und'][0]['title'];
        $cf['data']['page']['group_image']['alt'] = $cf['is_data']['node']['object']->field_group_image_custom['und'][0]['alt'];
        $cf['show']['page']['group_image'] = TRUE;
      }
    }
  }
}

/**
 * @} End of '@defgroup mcneese McNeese - www Theme'.
 */

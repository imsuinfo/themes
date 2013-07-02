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

  // default to fixed width for anonymous users.
  if (!$cf['is']['logged_in']) {
    $cf['is']['fixed_width'] = TRUE;
    $cf['is']['flex_width'] = FALSE;
  }

  // node-specific content
  if ($cf['is']['node'] && !($cf['is']['maintenance'] && !$cf['is_data']['maintenance']['access'])) {
    $node = &$cf['is_data']['node']['object'];

    // web form
    if ($node->type == 'webform') {
      if ((isset($cf['is']['node-view']) && $cf['is']['node-view']) || (isset($cf['is']['node-draft']) && $cf['is']['node-draft']) || (isset($cf['is']['node-view-revision']) && $cf['is']['node-view-revision'])) {
        $cf['is']['webform_type-default'] = TRUE;

        if (property_exists($node, 'field_webform_theme') && !empty($node->field_webform_theme['und'][0]['tid'])) {
          $type = &$node->field_webform_theme['und'][0]['tid']; 
          $cf['is']['webform_type-default'] = FALSE;
          $cf['is']['webform_type-'. $type] = TRUE;

          if (!$cf['is']['logged_in']) {
            if ($type == 592) {
              $cf['is']['fixed_width'] = FALSE;
              $cf['is']['flex_width'] = TRUE;
            }
            else if ($type == 594) {
              $cf['is']['fixed_width'] = FALSE;
              $cf['is']['flex_width'] = TRUE;
            }
            else if ($type == 617) {
              $cf['is']['fixed_width'] = TRUE;
              $cf['is']['flex_width'] = FALSE;
            }
          }
        }
      }
    }

    // web document
    if ($node->type == 'document') {
      if ((isset($cf['is']['node-view']) && $cf['is']['node-view']) || (isset($cf['is']['node-draft']) && $cf['is']['node-draft']) || (isset($cf['is']['node-view-revision']) && $cf['is']['node-view-revision'])) {
        $cf['is']['document_type-default'] = TRUE;

        if (property_exists($node, 'field_document_theme') && !empty($node->field_document_theme['und'][0]['tid'])) {
          $type = &$node->field_document_theme['und'][0]['tid'];
          $cf['is']['document_type-default'] = FALSE;
          $cf['is']['document_type-'. $type] = TRUE;
        }
      }
    }
  }
}

/**
 * Implements hook_preprocess_maintenance_page().
 */
function mcneese_www_preprocess_maintenance_page(&$vars) {
  mcneese_www_preprocess_html($vars);

  // always show header for maintenance mode pages.
  $cf['show']['page']['header'] = TRUE;
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


  foreach (array('group_image', 'document_header', 'document_outline', 'document_footer') as $key) {
    $cf['show']['page'][$key] = FALSE;
    $cf['data']['page'][$key] = array();
  }

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

  if (isset($cf['is']['unsupported']) && $cf['is']['unsupported']) {
    if (isset($cf['is']['in_ie_compatibility_mode']) && $cf['is']['in_ie_compatibility_mode']) {
      $cf['is_data']['unsupported']['message'] = t("You are running Internet Explorer in compatibility mode. To improve your experience using this website, please <a href='@alternate_browser_url'>turn off compatibility mode</a>.", array('@alternate_browser_url' => "http://www.sevenforums.com/tutorials/1196-internet-explorer-compatibility-view-turn-off.html#post_message_10408"));
    }
    else {
      $cf['is_data']['unsupported']['message'] = t("You are using an unsupported version of :name. Please upgrade your webbrowser or <a href='@alternate_browser_url'>download an alternative browser</a>.", array(':name' => $cf['agent']['human_name'], '@alternate_browser_url' => "/supported_browsers"));
    }
  }

  // tweak features based on user-agent
  if ($cf['agent']['machine_name'] == 'ie') {
    $custom_css = array();
    $custom_css['options'] = array('type' => 'file', 'group' => CSS_THEME, 'every_page' => TRUE, 'weight' => 5, 'media' => 'all', 'preprocess' => FALSE);
    $custom_css['data'] = $cf['subtheme']['path'] . '/css/workaround/www-ie.css';
    drupal_add_css($custom_css['data'], $custom_css['options']);
  }


  // node-specific content
  if ($cf['is']['node'] && !($cf['is']['maintenance'] && !$cf['is_data']['maintenance']['access'])) {
    $node = &$cf['is_data']['node']['object'];

    // web documents
    if ($node->type == 'document') {
      // only provide styles during node view, but the only way to determine if this is a node view is to guess based on the absolute paths.
      if ($cf['is']['node-view'] || $cf['is']['node-draft'] || $cf['is']['node-view-revision']) {
        mcneese_www_force_floating_regions($cf, array('messages' => 'region', 'help' => 'region', 'information' => 'region', 'menu_tabs' => 'navigation', 'action_links' => 'navigation', 'side' => 'region', 'breadcrumb' => 'navigation'));
      }


      // document header field
      $attributes = array();
      $attributes['id'] = 'mcneese-page-document-header';
      $attributes['class'] = array();
      $attributes['class'][] = 'relative';
      $attributes['class'][] = 'expanded';

      $cf['page']['tags']['mcneese_www_document_header_open'] = array('name' => 'header', 'type' => 'semantic', 'attributes' => $attributes, 'html5' => $cf['is']['html5']);
      $cf['page']['tags']['mcneese_www_document_header_close'] = array('name' => 'header', 'type' => 'semantic', 'open' => FALSE, 'html5' => $cf['is']['html5']);


      // document navigation field
      $attributes = array();
      $attributes['id'] = 'mcneese-page-document-outline';
      $attributes['class'] = array();
      $attributes['class'][] = 'noscript';
      $attributes['class'][] = 'fixed';
      $attributes['class'][] = 'collapsed';
      $attributes['role'] = 'navigation';
      $attributes['tabindex'] = 1;

      $cf['page']['tags']['mcneese_www_document_outline_open'] = array('name' => 'nav', 'type' => 'semantic', 'attributes' => $attributes, 'html5' => $cf['is']['html5']);
      $cf['page']['tags']['mcneese_www_document_outline_close'] = array('name' => 'nav', 'type' => 'semantic', 'open' => FALSE, 'html5' => $cf['is']['html5']);


      // document header field
      $attributes = array();
      $attributes['id'] = 'mcneese-page-document-footer';
      $attributes['class'] = array();
      $attributes['class'][] = 'relative';
      $attributes['class'][] = 'expanded';

      $cf['page']['tags']['mcneese_www_document_footer_open'] = array('name' => 'footer', 'type' => 'semantic', 'attributes' => $attributes, 'html5' => $cf['is']['html5']);
      $cf['page']['tags']['mcneese_www_document_footer_close'] = array('name' => 'footer', 'type' => 'semantic', 'open' => FALSE, 'html5' => $cf['is']['html5']);


      if (isset($node->field_header['und'][0]['safe_value'])) {
        $cf['show']['page']['document_header'] = TRUE;
        $cf['data']['page']['document_header']['markup'] = $node->field_header['und'][0]['safe_value'];
      }

      if (isset($node->field_outline['und'][0]['safe_value'])) {
        $cf['show']['page']['document_outline'] = TRUE;
        $cf['data']['page']['document_outline']['markup'] = $node->field_outline['und'][0]['safe_value'];
      }

      if (isset($node->field_footer['und'][0]['safe_value'])) {
        $cf['show']['page']['document_footer'] = TRUE;
        $cf['data']['page']['document_footer']['markup'] = $node->field_footer['und'][0]['safe_value'];
      }

      // work area menu is not supported by the web document format.
      $cf['show']['page']['work_area_menu'] = FALSE;
    }


    // web form
    if ($node->type == 'webform') {
      // only provide styles during node view, but the only way to determine if this is a node view is to guess based on the absolute paths.
      if ((isset($cf['is']['node-view']) && $cf['is']['node-view']) || (isset($cf['is']['node-draft']) && $cf['is']['node-draft']) || (isset($cf['is']['node-view-revision']) && $cf['is']['node-view-revision'])) {
        if (property_exists($node, 'field_webform_theme') && !empty($node->field_webform_theme['und'][0]['tid'])) {
          if ($node->field_webform_theme['und'][0]['tid'] == 592) {
            mcneese_www_force_floating_regions($cf, array('help' => 'region', 'information' => 'region', 'menu_tabs' => 'navigation', 'action_links' => 'navigation', 'side' => 'region', 'breadcrumb' => 'navigation'));
          }
          else if ($node->field_webform_theme['und'][0]['tid'] == 594) {
            mcneese_www_force_floating_regions($cf, array('messages' => 'region', 'help' => 'region', 'information' => 'region', 'menu_tabs' => 'navigation', 'action_links' => 'navigation', 'side' => 'region', 'breadcrumb' => 'navigation'));
          }
          else if ($node->field_webform_theme['und'][0]['tid'] == 617) {
            mcneese_www_force_floating_regions($cf, array('help' => 'region', 'information' => 'region', 'menu_tabs' => 'navigation', 'action_links' => 'navigation', 'side' => 'region', 'breadcrumb' => 'navigation'));
          }
        }
      }
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
    $node = &$cf['is_data']['node']['object'];

    // group image
    if (property_exists($node, 'field_group_image_show') && is_array($node->field_group_image_show) && isset($node->field_group_image_show['und'][0]['value']) && $node->field_group_image_show['und'][0]['value']) {
      if (property_exists($node, 'field_group_image_custom') && !empty($node->field_group_image_custom)) {
        $cf['data']['page']['group_image']['class'] = 'noscript group_image ';
        $cf['data']['page']['group_image']['height'] = '200';

        $url = file_create_url($node->field_group_image_custom['und'][0]['uri']);

        if ($cf['show']['page']['menus'] || $cf['show']['page']['asides']) {
          $cf['data']['page']['group_image']['class'] .= 'group_image-small';
          $cf['data']['page']['group_image']['width'] = '755';

          $cf['data']['page']['group_image']['src'] = image_style_url('group_image', $node->field_group_image_custom['und'][0]['uri']);
          $cf['data']['page']['group_image']['other'] = image_style_url('group_image_large', $node->field_group_image_custom['und'][0]['uri']);
        }
        else {
          $cf['data']['page']['group_image']['class'] .= 'group_image-large';
          $cf['data']['page']['group_image']['width'] = '960';

          $cf['data']['page']['group_image']['src'] = image_style_url('group_image_large', $node->field_group_image_custom['und'][0]['uri']);
          $cf['data']['page']['group_image']['other'] = image_style_url('group_image', $node->field_group_image_custom['und'][0]['uri']);
        }

        $cf['data']['page']['group_image']['original'] = preg_replace('`^' . $base_url . '`i', '', $url);
        $cf['data']['page']['group_image']['title'] = $node->field_group_image_custom['und'][0]['title'];
        $cf['data']['page']['group_image']['alt'] = $node->field_group_image_custom['und'][0]['alt'];
        $cf['show']['page']['group_image'] = TRUE;
      }
    }


    // web documents
    if ($node->type == 'document') {
      // only provide styles during node view, but the only way to determine if this is a node view is to guess based on the absolute paths.
      if ($cf['is']['node-view'] || $cf['is']['node-draft'] || $cf['is']['node-view-revision']) {
        $cf['show']['page']['title'] = FALSE;
      }
    }
  }
}

/**
 * Render all data for: node.
 */
function mcneese_www_render_node() {
  $cf = & drupal_static('cf_theme_get_variables', array());


  // node-specific content
  if ($cf['is']['node'] && !($cf['is']['maintenance'] && !$cf['is_data']['maintenance']['access'])) {
    $node = &$cf['is_data']['node']['object'];


    // web form custom themes
    if (property_exists($node, 'field_webform_theme') && $node->field_webform_theme['und'][0]['tid'] == 617) {
      if ($cf['is']['node-view'] || $cf['is']['node-draft'] || $cf['is']['node-view-revision']) {
        $cf['show']['node']['header'] = FALSE;
      }
    }
  }
}

/**
 * Force an array of regions to be floating, overriding user settings.
 *
 * @param array $cf
 *   The common functionality array.
 * @param array $array
 *   An array of regions to force as floating.
 */
function mcneese_www_force_floating_regions(&$cf, $regions) {
  foreach ((array) $regions as $key => $location) {
    if (isset($cf['user']['object']->data['mcneese_settings'][$location][$key]['sticky'])) {
      $sticky = & $cf['user']['object']->data['mcneese_settings'][$location][$key]['sticky'];

      if ($sticky == 'always') {
        continue;
      }
    }

    if (isset($cf['page']['tags']['mcneese_page_' . $key . '_open']['attributes'])) {
      $cf['page']['tags']['mcneese_page_' . $key . '_open']['attributes']['tabindex'] = '2';
    }

    if (isset($cf['page']['tags']['mcneese_page_' . $key . '_open']['attributes']['class'])) {
      $class = & $cf['page']['tags']['mcneese_page_' . $key . '_open']['attributes']['class'];

      if (!in_array('fixed', $class)) {
        $class[] = 'fixed';
      }

      if (!in_array('collapsed', $class)) {
        $class[] = 'collapsed';
      }

      $found_key = FALSE;
      $found_key = array_search('relative', $class);

      if ($found_key !== FALSE) {
        unset($class[$found_key]);
      }

      $found_key = FALSE;
      $found_key = array_search('expanded', $class);

      if ($found_key !== FALSE) {
        unset($class[$found_key]);
      }

      // 'side' is a special case, handle appropriately.
      if ($key == 'side') {
        $found_key = FALSE;
        $found_key = array_search('column-1', $class);

        if ($found_key !== FALSE) {
          unset($class[$found_key]);
        }

        continue;
      }
    }
  }
}

/**
 * Implements hook_preprocess_menu_link().
 */
function mcneese_www_preprocess_menu_link(&$vars) {
  if (!isset($vars['element']['#attributes']['class'])) {
    $vars['element']['#attributes']['class'] = array();
  }

  $vars['element']['#attributes']['class'][] = 'menu_link-' . strtolower(drupal_clean_css_identifier($vars['element']['#title'], array(' ' => '-', '_' => '_', '/' => '-', '[' => '-', ']' => '')));
}

/**
 * @} End of '@defgroup mcneese McNeese - www Theme'.
 */

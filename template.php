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

    // web form
    if ($cf['is_data']['node']['object']->type == 'webform') {
      if ((isset($cf['is']['node-view']) && $cf['is']['node-view']) || (isset($cf['is']['node-draft']) && $cf['is']['node-draft']) || (isset($cf['is']['node-view-revision']) && $cf['is']['node-view-revision'])) {
        $cf['is']['webform_type-default'] = TRUE;

        if (property_exists($cf['is_data']['node']['object'], 'field_webform_theme') && !empty($cf['is_data']['node']['object']->field_webform_theme['und'][0]['tid'])) {
          $cf['is']['webform_type-default'] = FALSE;
          $cf['is']['webform_type-'. $cf['is_data']['node']['object']->field_webform_theme['und'][0]['tid']] = TRUE;
        }

        if (!$cf['is']['logged_in']) {
          if ($cf['is_data']['node']['object']->field_webform_theme['und'][0]['tid'] == 592) {
            $cf['is']['fixed_width'] = FALSE;
            $cf['is']['flex_width'] = TRUE;
          }
          else if ($cf['is_data']['node']['object']->field_webform_theme['und'][0]['tid'] == 594) {
            $cf['is']['fixed_width'] = FALSE;
            $cf['is']['flex_width'] = TRUE;
          }
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

  if ($cf['is']['unsupported']) {
    if (isset($cf['is']['in_ie_compatibility_mode']) && $cf['is']['in_ie_compatibility_mode']) {
      $cf['is_data']['unsupported']['message'] = t("You are running Internet Explorer in compatibility mode. To improve your experience using this website, please <a href='@alternate_browser_url'>turn off compatibility mode</a>.", array('@alternate_browser_url' => "http://www.sevenforums.com/tutorials/1196-internet-explorer-compatibility-view-turn-off.html#post_message_10408"));
    }
    else {
      $cf['is_data']['unsupported']['message'] = t("You are using an unsupported version of :name. Please upgrade your webbrowser or <a href='@alternate_browser_url'>download an alternative browser</a>.", array(':name' => $cf['agent']['human_name'], '@alternate_browser_url' => "/supported_browsers"));
    }
  }


  // node-specific content
  if ($cf['is']['node'] && !($cf['is']['maintenance'] && !$cf['is_data']['maintenance']['access'])) {

    // web documents
    if ($cf['is_data']['node']['object']->type == 'document') {
      // only provide styles during node view, but the only way to determine if this is a node view is to guess based on the absolute paths.
      if ($cf['is']['node-view'] || $cf['is']['node-draft'] || $cf['is']['node-view-revision']) {
        mcneese_www_force_floating_regions($cf, array('messages', 'help', 'information', 'tabs', 'action_links', 'side', 'breadcrumb'));
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


      if (isset($cf['is_data']['node']['object']->field_header['und'][0]['safe_value'])) {
        $cf['show']['page']['document_header'] = TRUE;
        $cf['data']['page']['document_header']['markup'] = $cf['is_data']['node']['object']->field_header['und'][0]['safe_value'];
      }

      if (isset($cf['is_data']['node']['object']->field_outline['und'][0]['safe_value'])) {
        $cf['show']['page']['document_outline'] = TRUE;
        $cf['data']['page']['document_outline']['markup'] = $cf['is_data']['node']['object']->field_outline['und'][0]['safe_value'];
      }

      if (isset($cf['is_data']['node']['object']->field_footer['und'][0]['safe_value'])) {
        $cf['show']['page']['document_footer'] = TRUE;
        $cf['data']['page']['document_footer']['markup'] = $cf['is_data']['node']['object']->field_footer['und'][0]['safe_value'];
      }
    }


    // web form
    if ($cf['is_data']['node']['object']->type == 'webform') {
      // only provide styles during node view, but the only way to determine if this is a node view is to guess based on the absolute paths.
      if ((isset($cf['is']['node-view']) && $cf['is']['node-view']) || (isset($cf['is']['node-draft']) && $cf['is']['node-draft']) || (isset($cf['is']['node-view-revision']) && $cf['is']['node-view-revision'])) {
        if (property_exists($cf['is_data']['node']['object'], 'field_webform_theme') && !empty($cf['is_data']['node']['object']->field_webform_theme['und'][0]['tid'])) {
          if ($cf['is_data']['node']['object']->field_webform_theme['und'][0]['tid'] == 592) {
            mcneese_www_force_floating_regions($cf, array('messages', 'help', 'information', 'tabs', 'action_links', 'side', 'breadcrumb'));
          }
          else if ($cf['is_data']['node']['object']->field_webform_theme['und'][0]['tid'] == 594) {
            mcneese_www_force_floating_regions($cf, array('messages', 'help', 'information', 'tabs', 'action_links', 'side', 'breadcrumb'));
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
    // group image
    if (property_exists($cf['is_data']['node']['object'], 'field_group_image_show') && !empty($cf['is_data']['node']['object']->field_group_image_show)) {
      if (property_exists($cf['is_data']['node']['object'], 'field_group_image_custom') && !empty($cf['is_data']['node']['object']->field_group_image_custom)) {
        $cf['data']['page']['group_image']['class'] = 'noscript group_image ';
        $cf['data']['page']['group_image']['height'] = '200';

        $url = file_create_url($cf['is_data']['node']['object']->field_group_image_custom['und'][0]['uri']);

        if ($cf['show']['page']['menus'] || $cf['show']['page']['asides']) {
          $cf['data']['page']['group_image']['class'] .= 'group_image-small';
          $cf['data']['page']['group_image']['width'] = '755';

          $cf['data']['page']['group_image']['src'] = image_style_url('group_image', $cf['is_data']['node']['object']->field_group_image_custom['und'][0]['uri']);
          $cf['data']['page']['group_image']['other'] = image_style_url('group_image_large', $cf['is_data']['node']['object']->field_group_image_custom['und'][0]['uri']);
        }
        else {
          $cf['data']['page']['group_image']['class'] .= 'group_image-large';
          $cf['data']['page']['group_image']['width'] = '960';

          $cf['data']['page']['group_image']['src'] = image_style_url('group_image_large', $cf['is_data']['node']['object']->field_group_image_custom['und'][0]['uri']);
          $cf['data']['page']['group_image']['other'] = image_style_url('group_image', $cf['is_data']['node']['object']->field_group_image_custom['und'][0]['uri']);
        }

        $cf['data']['page']['group_image']['original'] = preg_replace('`^' . $base_url . '`i', '', $url);
        $cf['data']['page']['group_image']['title'] = $cf['is_data']['node']['object']->field_group_image_custom['und'][0]['title'];
        $cf['data']['page']['group_image']['alt'] = $cf['is_data']['node']['object']->field_group_image_custom['und'][0]['alt'];
        $cf['show']['page']['group_image'] = TRUE;
      }
    }


    // web documents
    if ($cf['is_data']['node']['object']->type == 'document') {
      // only provide styles during node view, but the only way to determine if this is a node view is to guess based on the absolute paths.
      if ($cf['is']['node-view'] || $cf['is']['node-draft'] || $cf['is']['node-view-revision']) {
        $cf['show']['page']['title'] = FALSE;
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
  foreach ((array) $regions as $key) {
    $cf['page']['tags']['mcneese_page_' . $key . '_open']['attributes']['tabindex'] = '2';

    if (isset($cf['page']['tags']['mcneese_page_' . $key . '_open']['attributes']['class'])) {
      if (!in_array('fixed', $cf['page']['tags']['mcneese_page_' . $key . '_open']['attributes']['class'])) {
        $cf['page']['tags']['mcneese_page_' . $key . '_open']['attributes']['class'][] = 'fixed';
      }
    }

    if (isset($cf['page']['tags']['mcneese_page_' . $key . '_open']['attributes']['class'])) {
      if (!in_array('collapsed', $cf['page']['tags']['mcneese_page_' . $key . '_open']['attributes']['class'])) {
        $cf['page']['tags']['mcneese_page_' . $key . '_open']['attributes']['class'][] = 'collapsed';
      }
    }

    $found_key = FALSE;

    if (isset($cf['page']['tags']['mcneese_page_' . $key . '_open']['attributes']['class'])) {
      $found_key = array_search('relative', $cf['page']['tags']['mcneese_page_' . $key . '_open']['attributes']['class']);
    }

    if ($found_key !== FALSE) {
      unset($cf['page']['tags']['mcneese_page_' . $key . '_open']['attributes']['class'][$found_key]);
    }

    $found_key = FALSE;

    if (isset($cf['page']['tags']['mcneese_page_' . $key . '_open']['attributes']['class'])) {
      $found_key = array_search('expanded', $cf['page']['tags']['mcneese_page_' . $key . '_open']['attributes']['class']);
    }

    if ($found_key !== FALSE) {
      unset($cf['page']['tags']['mcneese_page_' . $key . '_open']['attributes']['class'][$found_key]);
    }

    // 'side' is a special case, handle appropriately.
    if ($key == 'side') {
      $found_key = FALSE;

      if (isset($cf['page']['tags']['mcneese_page_side_open']['attributes']['class'])) {
        $found_key = array_search('column-1', $cf['page']['tags']['mcneese_page_side_open']['attributes']['class']);
      }

      if ($found_key !== FALSE) {
        unset($cf['page']['tags']['mcneese_page_side_open']['attributes']['class'][$found_key]);
      }

      continue;
    }
  }
}

/**
 * @} End of '@defgroup mcneese McNeese - www Theme'.
 */

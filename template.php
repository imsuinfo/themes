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
  global $base_path;
  global $conf;

  $cf['subtheme']['path'] = base_path() . drupal_get_path('theme', 'mcneese_www');
  $cf['subtheme']['machine_name'] = 'mcneese_www';
  $cf['subtheme']['human_name'] = t("McNeese WWW");

  // default to fixed width for anonymous users.
  if (!$cf['is']['logged_in']) {
    $cf['is']['fixed_width'] = TRUE;
    $cf['is']['flex_width'] = FALSE;
  }

  $rss_feeds = array();

  $rss_feed_groups_blacklist = array();
  if (isset($conf['feed_groups_blacklist']['groups']) && is_array($conf['feed_groups_blacklist']['groups'])) {
    $rss_feed_groups_blacklist = $conf['feed_groups_blacklist']['groups'];
  }

  // node-specific content
  if ($cf['is']['node'] && !($cf['is']['maintenance'] && !$cf['is_data']['maintenance']['access'])) {
    $node = &$cf['is_data']['node']['object'];

    if (isset($node->field_group['und']) && is_array($node->field_group['und'])) {
      $group_ids = array();
      foreach ($node->field_group['und'] as $field_group) {
        if (!cf_is_integer($field_group['tid']) || $field_group['tid'] < 1) {
          continue;
        }

        if (array_key_exists($field_group['tid'], $rss_feed_groups_blacklist)) {
          continue;
        }

        $group_ids[$field_group['tid']] = $field_group['tid'];
      }

      if (!empty($group_ids)) {
        $rss_feeds[] = array(
          'rel' => 'alternate',
          'href' => $base_path . 'rss/feed/group/' . implode(',', $group_ids),
          'type' => 'application/rss+xml',
        );
      }
    }

    // web form
    if ($node->type == 'webform') {
      if (property_exists($node, 'field_webform_theme') && !empty($node->field_webform_theme['und'][0]['tid'])) {
        $type = &$node->field_webform_theme['und'][0]['tid'];

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
          else if ($type == 677) {
            $cf['is']['fixed_width'] = TRUE;
            $cf['is']['flex_width'] = FALSE;
          }
        }
      }
    }

    // document
    if ($node->type == 'document') {
      if (property_exists($node, 'field_document_theme') && !empty($node->field_document_theme['und'][0]['tid'])) {
        $type = &$node->field_document_theme['und'][0]['tid'];

        if (!$cf['is']['logged_in']) {
          if ($type == 669 || $type == 671) {
            $cf['is']['fixed_width'] = TRUE;
            $cf['is']['flex_width'] = FALSE;
          }
          else {
            $cf['is']['fixed_width'] = FALSE;
            $cf['is']['flex_width'] = TRUE;
          }
        }
      }
    }
  }



  // Provide RSS feed links for front page.
  if ($cf['is']['front']) {
    $rss_feeds = array();
    $rss_feeds[] = array(
      'rel' => 'alternate',
      'href' => $base_path . 'rss/feed/featured',
      'type' => 'application/rss+xml',
      'title' => "Follow McNeese Featured",
    );
    $rss_feeds[] = array(
      'rel' => 'alternate',
      'href' => $base_path . 'rss/feed/news',
      'type' => 'application/rss+xml',
      'title' => "Follow McNeese News & Events",
    );
    $rss_feeds[] = array(
      'rel' => 'alternate',
      'href' => $base_path . 'rss/feed/spotlight',
      'type' => 'application/rss+xml',
      'title' => "Follow McNeese Spotlight",
    );
    $rss_feeds[] = array(
      'rel' => 'alternate',
      'href' => $base_path . 'rss/feed/lagniappe',
      'type' => 'application/rss+xml',
      'title' => "Follow McNeese Lagniappe",
    );
  }

  // Add the RSS Feed.
  if (!empty($rss_feeds)) {
    foreach ($rss_feeds as $rss_feed) {
      $cf['link'][] = $rss_feed;
    }
  }
}

/**
 * Implements hook_preprocess_maintenance_page().
 */
function mcneese_www_preprocess_maintenance_page(&$vars) {
  mcneese_www_preprocess_html($vars);

  $cf = & drupal_static('cf_theme_get_variables', array());

  if (empty($cf)) {
    mcneese_initialize_variables($vars);
  }

  $cf['markup_css']['body']['class'] .= ' is-html5 is-fixed_width';

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

  if (!isset($cf['is']['html5'])) {
    $cf['is']['html5'] = TRUE;
  }

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


  // define the bulletin default settings.
  $vars['mcneese_bulletin_mode'] = 2; // mode 1 was used from original design as a sub-theme, so mode 2 represents operating as a hard-coded value.


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


  // process blocks.
  //mcneese_www_process_blocks($cf);


  // additional javascript functions.
  mcneese_www_process_javascript($cf);


  // additional functions for eliminating side panel blocks.
  $side_panel_markup = mcneese_www_process_side_panel($cf);
  if (is_string($side_panel_markup)) {
    $cf['page']['asides']['mcneese_www_process_side_panel'] = array(
      '#type' => 'markup',
      '#markup' => $side_panel_markup,
    );
    $cf['show']['page']['asides'] = TRUE;
  }


  // node-specific content
  if ($cf['is']['node'] && !($cf['is']['maintenance'] && !$cf['is_data']['maintenance']['access'])) {
    $node = &$cf['is_data']['node']['object'];

    // web documents
    if ($node->type == 'document') {
      // disable bulletin.
      $vars['mcneese_bulletin_mode'] = NULL;

      // only provide styles during node view, but the only way to determine if this is a node view is to guess based on the absolute paths.
      if ($cf['is']['node-view'] || $cf['is']['node-draft'] || $cf['is']['node-view-revision']) {
        if (property_exists($node, 'field_document_theme') && !empty($node->field_document_theme['und'][0]['tid'])) {
          if ($node->field_document_theme['und'][0]['tid'] == 592) {
            mcneese_www_force_floating_regions($cf, array('help' => 'region', 'menu_tabs' => 'navigation', 'action_links' => 'navigation', 'side' => 'region', 'breadcrumb' => 'navigation'));
          }
          else {
            mcneese_www_force_floating_regions($cf, array('messages' => 'region', 'help' => 'region', 'menu_tabs' => 'navigation', 'action_links' => 'navigation', 'side' => 'region', 'breadcrumb' => 'navigation'));
          }
        }
        else {
          mcneese_www_force_floating_regions($cf, array('messages' => 'region', 'help' => 'region', 'menu_tabs' => 'navigation', 'action_links' => 'navigation', 'side' => 'region', 'breadcrumb' => 'navigation'));
        }
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
    }


    // web form
    if ($node->type == 'webform') {
      // only provide styles during node view, but the only way to determine if this is a node view is to guess based on the absolute paths.
      if ((isset($cf['is']['node-view']) && $cf['is']['node-view']) || (isset($cf['is']['node-draft']) && $cf['is']['node-draft']) || (isset($cf['is']['node-view-revision']) && $cf['is']['node-view-revision'])) {
        if (property_exists($node, 'field_webform_theme') && !empty($node->field_webform_theme['und'][0]['tid'])) {
          if ($node->field_webform_theme['und'][0]['tid'] == 592) {
            mcneese_www_force_floating_regions($cf, array('help' => 'region', 'menu_tabs' => 'navigation', 'action_links' => 'navigation', 'side' => 'region', 'breadcrumb' => 'navigation'));
          }
          else if ($node->field_webform_theme['und'][0]['tid'] == 594) {
            mcneese_www_force_floating_regions($cf, array('messages' => 'region', 'help' => 'region', 'menu_tabs' => 'navigation', 'action_links' => 'navigation', 'side' => 'region', 'breadcrumb' => 'navigation'));
          }
          else if ($node->field_webform_theme['und'][0]['tid'] == 617) {
            mcneese_www_force_floating_regions($cf, array('help' => 'region', 'menu_tabs' => 'navigation', 'action_links' => 'navigation', 'side' => 'region', 'breadcrumb' => 'navigation'));
          }
          else if ($node->field_webform_theme['und'][0]['tid'] == 677) {
            mcneese_www_force_floating_regions($cf, array('help' => 'region', 'menu_tabs' => 'navigation', 'action_links' => 'navigation', 'side' => 'region', 'breadcrumb' => 'navigation'));
          }
        }
      }
    }


    // page
    if ($node->type == 'page') {
      $type = NULL;
      if (property_exists($node, 'field_page_theme') && !empty($node->field_page_theme['und'][0]['tid'])) {
        $type = &$node->field_page_theme['und'][0]['tid'];
      }

      if ($type == 731) {
        $vars['mcneese_bulletin_mode'] = 1;

        if (!empty($node->field_bulletin['und'][0]['safe_value'])) {
          $cf['page']['bulletin'] = $node->field_bulletin['und'][0]['safe_value'];
        }
      }

      if ($type == 744) {
        $cf['page']['menus'] = array();
        $cf['page']['asides'] = array();

        $cf['show']['page']['menus'] = FALSE;
        $cf['show']['page']['asides'] = FALSE;
      }

      // only provide styles during node view, but the only way to determine if this is a node view is to guess based on the absolute paths.
      if ((isset($cf['is']['node-view']) && $cf['is']['node-view']) || (isset($cf['is']['node-draft']) && $cf['is']['node-draft']) || (isset($cf['is']['node-view-revision']) && $cf['is']['node-view-revision'])) {
        if ($type == 718) {
          mcneese_www_force_floating_regions($cf, array('help' => 'region', 'menu_tabs' => 'navigation', 'action_links' => 'navigation', 'breadcrumb' => 'navigation'));
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
  if (isset($cf['is']['node']) && $cf['is']['node'] && !($cf['is']['maintenance'] && !$cf['is_data']['maintenance']['access'])) {
    $node = &$cf['is_data']['node']['object'];


    // base font-size override
    if (property_exists($node, 'field_base_font_size') && is_array($node->field_base_font_size) && !empty($node->field_base_font_size['und'][0]['value'])) {
      $font_size = (int) $node->field_base_font_size['und'][0]['value'];
      $line_height = $font_size + 4;

      $custom_css = '.mcneese.is-node.is-node-view .mcneese-content-main,' . "\n";
      $custom_css .= '.mcneese.is-node.is-node-view #mcneese-content-main,' . "\n";
      $custom_css .= '.mcneese.is-node.is-node-draft .mcneese-content-main,' . "\n";
      $custom_css .= '.mcneese.is-node.is-node-draft #mcneese-content-main,' . "\n";
      $custom_css .= '.mcneese.is-node.is-node-view-revision .mcneese-content-main,' . "\n";
      $custom_css .= '.mcneese.is-node.is-node-view-revision #mcneese-content-main {' . "\n";
      $custom_css .= '  font-size: ' . $font_size . "px;\n";
      $custom_css .= '  line-height: ' . $line_height . "px;\n";
      $custom_css .= '}' . "\n";

      drupal_add_css($custom_css, array('type' => 'inline', 'group' => CSS_THEME, 'weight' => 100, 'preprocess' => FALSE, 'media' => 'all'));
    }


    // print font-size override
    if (property_exists($node, 'field_print_font_size') && is_array($node->field_print_font_size) && !empty($node->field_print_font_size['und'][0]['value'])) {
      $font_size = (int) $node->field_print_font_size['und'][0]['value'];
      $line_height = $font_size + 2;

      // font-sizes in gecko print differently than in webkit.
      if ($cf['agent']['engine'] == 'gecko') {
        if ($font_size > 1) $font_size--;
        if ($line_height > 1) $line_height--;
      }

      $custom_css = '.mcneese.is-node.is-node-view .mcneese-content-main,' . "\n";
      $custom_css .= '.mcneese.is-node.is-node-view #mcneese-content-main,' . "\n";
      $custom_css .= '.mcneese.is-node.is-node-draft .mcneese-content-main,' . "\n";
      $custom_css .= '.mcneese.is-node.is-node-draft #mcneese-content-main,' . "\n";
      $custom_css .= '.mcneese.is-node.is-node-view-revision .mcneese-content-main,' . "\n";
      $custom_css .= '.mcneese.is-node.is-node-view-revision #mcneese-content-main {' . "\n";
      $custom_css .= '  font-size: ' . $font_size . "px;\n";
      $custom_css .= '  line-height: ' . $line_height . "px;\n";
      $custom_css .= '}' . "\n";

      drupal_add_css($custom_css, array('type' => 'inline', 'group' => CSS_THEME, 'weight' => 100, 'preprocess' => FALSE, 'media' => 'print'));
    }


    // group image
    if (property_exists($node, 'field_group_image_show') && is_array($node->field_group_image_show) && isset($node->field_group_image_show['und'][0]['value']) && $node->field_group_image_show['und'][0]['value']) {
      // assign default 'simple background image' from the dbu.
      $image_url = 'dbu://c/043e94d1/simple_background.png';
      $image_alt = '';
      $image_tooltip = '';

      if (property_exists($node, 'field_group_image_custom') && !empty($node->field_group_image_custom)) {
        $image_url = $node->field_group_image_custom['und'][0]['uri'];
        $image_alt = $node->field_group_image_custom['und'][0]['alt'];
        $image_tooltip = $node->field_group_image_custom['und'][0]['title'];
      }

      $url = file_create_url($image_url);

      $cf['data']['page']['group_image']['class'] = 'noscript group_image ';

      if ($cf['show']['page']['menus'] || $cf['show']['page']['asides']) {
        $cf['data']['page']['group_image']['class'] .= 'group_image-small';

        $cf['data']['page']['group_image']['src'] = image_style_url('group_image', $image_url);
        $cf['data']['page']['group_image']['other'] = image_style_url('group_image_large', $image_url);
      }
      else {
        $cf['data']['page']['group_image']['class'] .= 'group_image-large';

        $cf['data']['page']['group_image']['src'] = image_style_url('group_image_large', $image_url);
        $cf['data']['page']['group_image']['other'] = image_style_url('group_image', $image_url);
      }

      $cf['data']['page']['group_image']['original'] = preg_replace('`^' . $base_url . '`i', '', $url);
      $cf['data']['page']['group_image']['alt'] = $image_alt;
      $cf['data']['page']['group_image']['title'] = $image_tooltip;
      $cf['show']['page']['group_image'] = TRUE;

      // add JQ Maphilight support
      $cf['data']['page']['group_image']['class'] .= ' jq_maphilight';
    }


    // web documents
    if ($node->type == 'document') {
      // only provide styles during node view, but the only way to determine if this is a node view is to guess based on the absolute paths.
      if ($cf['is']['node-view'] || $cf['is']['node-draft'] || $cf['is']['node-view-revision']) {
        $cf['show']['page']['title'] = FALSE;
      }
    }
  }

  // ensure that the primary and secondary navigations work properly by manually overriding the header menus.
  // this allows for the menus to be presented when in maintenance mode or when the database is unavailable.
  // be sure to edit the www-screen-common.css to simulate the active menu item for when the path matches.
  $markup = '<nav class="menu html_tag-nav">';
  $markup .= '  <ul class="navigation_list html_tag-list">';
  $markup .= '    <li class="first leaf menu_link-apply-now menu_link-apply_now menu-link-name-menu-primary-navigation menu-link-mlid-4682 id-menu-link-menu-primary-navigation-4682"><a title="" href="/node/5683">Apply Now</a></li>';
  $markup .= '    <li class="leaf menu_link-future_students menu-link-name-menu-primary-navigation menu-link-mlid-799 id-menu-link-menu-primary-navigation-799"><a title="" href="/future-students">Future Students</a></li>';
  $markup .= '    <li class="leaf menu_link-current_students menu-link-name-menu-primary-navigation menu-link-mlid-898 id-menu-link-menu-primary-navigation-898"><a title="" href="/current-students">Students</a></li>';
  $markup .= '    <li class="leaf menu_link-online_learning menu-link-name-menu-primary-navigation menu-link-mlid-6060 id-menu-link-menu-primary-navigation-6060"><a title="" href="/alearn">Online Learning</a></li>';
  $markup .= '    <li class="leaf menu_link-faculty_staff menu-link-name-menu-primary-navigation menu-link-mlid-385 id-menu-link-menu-primary-navigation-385"><a href="/faculty-staff">Faculty &amp; Staff</a></li>';
  $markup .= '    <li class="leaf menu_link-alumni_friends menu-link-name-menu-primary-navigation menu-link-mlid-1273 id-menu-link-menu-primary-navigation-1273"><a title="" href="/alumni-friends">Alumni &amp; Donors</a></li>';
  $markup .= '    <li class="last leaf menu_link-my_mcneese menu-link-name-menu-primary-navigation menu-link-mlid-388 id-menu-link-menu-primary-navigation-388"><a title="" href="https://mymcneese.mcneese.edu/">MyMcNeese</a></li>';
  $markup .= '  </ul>';
  $markup .= '</nav>';

  $cf['data']['page']['header_menu_1'] = $markup;
  $cf['show']['page']['header_menu_1'] = TRUE;
  $cf['show']['page']['header'] = TRUE;

  $markup = '<nav class="menu html_tag-nav">';
  $markup .= '  <ul class="navigation_list html_tag-list">';
  $markup .= '    <li class="first leaf menu_link-academics menu-link-name-menu-secondary-navigation menu-link-mlid-849 id-menu-link-menu-secondary-navigation-849"><a href="/academics">Academics</a></li>';
  $markup .= '    <li class="leaf menu_link-athletics menu-link-name-menu-secondary-navigation menu-link-mlid-850 id-menu-link-menu-secondary-navigation-850"><a href="/athletics">Athletics</a></li>';
  $markup .= '    <li class="leaf menu_link-bookstore menu-link-name-menu-secondary-navigation menu-link-mlid-851 id-menu-link-menu-secondary-navigation-851"><a href="/bookstore">Bookstore</a></li>';
  $markup .= '    <li class="leaf menu_link-calendar menu-link-name-menu-secondary-navigation menu-link-mlid-2210 id-menu-link-menu-secondary-navigation-2210"><a title="" href="/calendar">Calendar</a></li>';
  $markup .= '    <li class="leaf menu_link-campus-map menu-link-name-menu-secondary-navigation menu-link-mlid-852 id-menu-link-menu-secondary-navigation-852"><a title="" href="/campusmaps">Campus Map</a></li>';
  $markup .= '    <li class="leaf menu_link-catalog menu-link-name-menu-secondary-navigation menu-link-mlid-6058 id-menu-link-menu-secondary-navigation-6058"><a title="" href="/catalog">Catalog</a></li>';
  $markup .= '    <li class="leaf menu_link-employment menu-link-name-menu-secondary-navigation menu-link-mlid-853 id-menu-link-menu-secondary-navigation-853"><a href="/hr/employment">Employment</a></li>';
  $markup .= '    <li class="leaf menu_link-library menu-link-name-menu-secondary-navigation menu-link-mlid-854 id-menu-link-menu-secondary-navigation-854"><a href="/library">Library</a></li>';
  $markup .= '    <li class="leaf menu_link-research menu-link-name-menu-secondary-navigation menu-link-mlid-858 id-menu-link-menu-secondary-navigation-858"><a href="/research">Research</a></li>';
  $markup .= '    <li class="last leaf menu_link-presidents-message menu-link-name-menu-secondary-navigation menu-link-mlid-1564 id-menu-link-menu-secondary-navigation-1564"><a title="" href="/president">President\'s Message</a></li>';
  $markup .= '  </ul>';
  $markup .= '</nav>';

  $cf['data']['page']['header_menu_2'] = $markup;
  $cf['show']['page']['header_menu_2'] = TRUE;
  $cf['show']['page']['header'] = TRUE;


  // jira: ucs-1484: disable 75th links and references, but leave code just in case it needs to be brought back.
  // build the 75th anniversary and prepend it to the 'top' region.
  #$markup = '<div class="noscript no-print" id="the_75th_anniversary_banner">';
  #$markup .= '  <div class="top_padding"></div>';
  #$markup .= '<a class="learn_more" href="http://75th.mcneese.edu/">Learn More</a></div>';
  #
  #if (isset($cf['data']['page']['top'])) {
  #  $cf['data']['page']['top'] = $markup . $cf['data']['page']['top'];
  #}
  #else {
  #  $cf['data']['page']['top'] = $markup;
  #}
  #
  #$cf['show']['page']['top'] = TRUE;


  // build the search box and append it to the 'header' region'.
  if (function_exists('drupal_get_form')) {
    $sbf = (array) drupal_get_form('search_block_form');
    $markup = '  <div id="mcneese-search-box">';
    $markup .= '    <div class="search_box-links">';
    $markup .= '      <ul class="navigation_list">';
    $markup .= '        <li class="search_form-top_box_links-ada"><a href="/ada">ADA</a></li>';
    $markup .= '        <div class="search_form-top_box_links-bar">|</div>';
    $markup .= '        <li class="search_form-top_box_links-staff"><a href="/search/people">Faculty &amp; Staff Search</a></li>';
    $markup .= '        <div class="search_form-top_box_links-bar">|</div>';
    $markup .= '        <li class="search_form-top_box_links-index"><a href="/index">A-Z Index</a></li>';
    $markup .= '      </ul>';
    $markup .= '    </div>';
    $markup .= '    <div class="search_box-box">';
    $markup .= '      ' . drupal_render($sbf);
    $markup .= '    </div>';
    $markup .= '  </div>';
  }

  unset($sbf);

  if (isset($cf['data']['page']['header'])) {
    $cf['data']['page']['header'] .= $markup;
  }
  else {
    $cf['data']['page']['header'] = $markup;
  }

  unset($markup);
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
 * Build the side panel.
 *
 * This is also added by the global conf 'error_document_functions' option.
 * Example:
 *  $conf['error_document_functions'] = array(
 *    'mcneese_www_process_side_panel',
 *  );
 *
 * @param array|null $cf
 *   The cf array that is available for modification.
 *   The error document pages must set this to NULL because the cf data is not available during the static error pages.
 *   Make sure to check if this is an array before operating on inside this function.
 *
 * @return string|null
 *   NULL should be returned when there is no data to present.
 *   Otherwise, a string containing the renderred panel data should be returned.
 */
function mcneese_www_process_side_panel(&$cf) {
  global $base_path;
  global $conf;


  // Do nothing when under maintenance.
  if (is_array($cf) && $cf['is']['maintenance'] && !$cf['is_data']['maintenance']['access']) {
    return NULL;
  }

  $markup = NULL;

  $uri = request_uri();
  $uri_parts = explode('/', preg_replace('/^' . preg_quote($base_path, '/') . '/i', '', $uri));
  $uri_parts_total = count($uri_parts);
  if (isset($uri_parts[($uri_parts_total - 1)])) {
    $uri_parts[($uri_parts_total - 1)] = preg_replace('/([^\?]*)\?.*$/i', '$1', $uri_parts[($uri_parts_total - 1)]);
  }


  // process nodes, whose url paths may be /node/X but the published path are the desired aliases (such as /my/alias).
  $sources = array();
  if (isset($uri_parts[0]) && $uri_parts[0] == 'node' && isset($uri_parts[1]) && is_numeric($uri_parts[1])) {
    $sources = mcneese_www_process_build_node_source_parts($uri_parts[1]);
  }


  // NTAS Widget Block
  if ((isset($uri_parts[0]) && $uri_parts[0] == 'police') || isset($sources[0]['police'])) {
    if (function_exists('mcneese_functions_embed_ntas_widget')) {
      $markup .= '<div id="national_terrorism_advisory_system">';
      $markup .= mcneese_functions_embed_ntas_widget(NULL, '+1 minutes', TRUE);
      $markup .= '</div>';
    }
  }


  // IS Block
  if ((isset($uri_parts[0]) && $uri_parts[0] == 'is') || isset($sources[0]['is'])) {
    // was block 27.
    if ($uri_parts_total == 1) {
      $markup .= '<div class="block block-id-1 block-name-block-block-27 odd html_tag-div">';
      $markup .= '  <div class="align_center padding-top-12 padding-bottom-4">';
      $markup .= '    <a class="twitter-timeline" data-aria-polite="assertive" data-chrome="nofooter noborders transparent noscrollbar" data-widget-id="397859780779196416" href="https://twitter.com/McNeeseInfoSec" rel="noreferrer">Follow @McNeeseInfoSec</a>';
      $markup .= '  </div>';
      $markup .= '</div>';
    }

    // was block 26.
    $markup .= '<div class="block block-id-2 block-name-block-block-26 even html_tag-div">';
    $markup .= '  <div class="align_center margin-top-10 margin-bottom-10">';
    $markup .= '    <div class="download-is-box margin-bottom-10">';
    $markup .= '      <h3>Featured Downloads</h3>';
    $markup .= '      <a href="https://www.eff.org/https-everywhere" target="_blank"><img src="' . $base_path . 'f/f/12497" height="38" width="185" alt="HTTPS Everywhere" title="Encrypt the Web"></a><br>';
    $markup .= '      <a href="https://pack.resetthenet.org/" target="_blank" rel="noreferrer"><img alt="Reset The Net" title="Privacy Pack" height="38" width="185" src="' . $base_path . 'f/f/12498"></a>';
    $markup .= '    </div>';

    // apparently browsers give whitespace space (when they otherwise should not) causing images to wrap/overflow due to invisible/unprinted space.
    $markup .= '<div class="margin-top-20 margin-bottom-10">';
    $markup .= '<a href="https://twitter.com/McNeeseInfoSec" target="_blank" rel="noreferrer"><img alt="@McNeeseInfoSec" title="@McNeeseInfoSec" height="47" width="47" style="width: 47px; height: 47px;" src="' . $base_path . 'f/f/12499"></a>';
    $markup .= '<a href="https://www.staysafeonline.org/ncsam/champions/all-champions/" target="_blank" rel="noreferrer"><img alt="National Cyber Security Awareness Month" title="Free Security Check Ups" height="47" width="47" src="' . $base_path . 'f/f/12500" style="width: 47px; height: 47px;"></a>';
    $markup .= '<a href="https://www.staysafeonline.org/data-privacy-day/check-your-privacy-settings/" target="_blank" rel="noreferrer"><img alt="Data Privacy Day" title="Check Your Privacy Settings" height="47" width="47" src="' . $base_path . 'f/f/12501" style="width: 47px; height: 47px;"></a>';
    $markup .= '<a href="http://stopthinkconnect.org/tips-and-advice/overview/" target="_blank" rel="noreferrer"><img alt="Stop | Think | Connect" title="Tips &amp; Advice" height="47" width="47" src="' . $base_path . 'f/f/12502" style="width: 47px; height: 47px;"></a>';
    $markup .= '</div>';

    $markup .= '  </div>';
    $markup .= '</div>';
  }


  // add custom blocks here.

  return $markup;
}

/**
 * Loads additional javascript.
 *
 * @param array $cf
 *   The cf array that is available for modification.
 */
function mcneese_www_process_javascript(&$cf) {
  global $base_path;
  global $theme_path;

  $uri = request_uri();
  $uri_parts = explode('/', preg_replace('/^' . preg_quote($base_path, '/') . '/i', '', $uri));
  $uri_parts_total = count($uri_parts);
  if (isset($uri_parts[($uri_parts_total - 1)])) {
    $uri_parts[($uri_parts_total - 1)] = preg_replace('/([^\?]*)\?.*$/i', '$1', $uri_parts[($uri_parts_total - 1)]);
  }
  $uri_fixed = implode('/', $uri_parts);


  // process nodes, whose url paths may be /node/X but the published path are the desired aliases (such as /my/alias).
  $sources = array();
  if (isset($uri_parts[0]) && $uri_parts[0] == 'node' && isset($uri_parts[1]) && is_numeric($uri_parts[1])) {
    $sources = mcneese_www_process_build_node_source_parts($uri_parts[1]);
  }


  // NS Dorms javascript
  if ($uri_fixed == 'ns/dorms' || (isset($sources[1]['ns/dorms']) && !isset($sources[2]))) {
    drupal_add_js($base_path . $theme_path . '/js/ns-dorms.js', array('type' => 'file', 'group' => JS_DEFAULT, 'preprocess' => FALSE));
  }


  // NS Helpdesk javascript
  if ($uri_fixed == 'ns/helpdesk' || (isset($sources[1]['ns/helpdesk']) && !isset($sources[2]))) {
    drupal_add_js($base_path . $theme_path . '/js/ns-helpdesk.js', array('type' => 'file', 'group' => JS_DEFAULT, 'preprocess' => FALSE));
  }


  // IS Twitter javascript
  if ($uri_fixed == 'is' || (isset($sources[0]['is']) && !isset($sources[1]))) {
    drupal_add_js($base_path . $theme_path . '/js/is-twitter.js', array('type' => 'file', 'group' => JS_DEFAULT, 'preprocess' => FALSE));
  }


  // Bookstore javascript
  if ($uri_fixed == 'bookstore' || (isset($sources[0]['bookstore']) && !isset($sources[1]))) {
    if (function_exists('mcneese_third_party_add_facebook_connect_legacy_js')) {
      mcneese_third_party_add_facebook_connect_legacy_js();

      if (function_exists('mcneese_third_party_do_facebook_connect_js')) {
        mcneese_third_party_do_facebook_connect_js('71065ce9bc688916c2ab0496f7a6db0f');
      }
    }
  }


  // "Remarketing" javascript (jira tickets: www-1062, www-914)
  {
    $remarketing = FALSE;

    if ($uri_fixed == 'nursing' || (isset($sources[0]['nursing']) && !isset($sources[1]))) {
      $remarketing = TRUE;
    }
    elseif ($uri_fixed == 'nursing/family_nurse_practitioner_curriculum' || (isset($sources[1]['nursing/family_nurse_practitioner_curriculum']) && !isset($sources[2]))) {
      $remarketing = TRUE;
    }
    elseif ($uri_fixed == 'nursing/family_pychiatric/mental_health_nurse_practition' || (isset($sources[2]['nursing/family_pychiatric/mental_health_nurse_practition']) && !isset($sources[3]))) {
      $remarketing = TRUE;
    }
    elseif ($uri_fixed == 'nursing/nurse_executive_curriculum' || (isset($sources[1]['nursing/nurse_executive_curriculum']) && !isset($sources[2]))) {
      $remarketing = TRUE;
    }
    elseif ($uri_fixed == 'nursing/graduate' || (isset($sources[1]['nursing/graduate']) && !isset($sources[2]))) {
      $remarketing = TRUE;
    }
    elseif ($uri_fixed == 'nursing/rn-to-bsn_online' || (isset($sources[1]['nursing/rn-to-bsn_online']) && !isset($sources[2]))) {
      $remarketing = TRUE;
    }
    elseif ($uri_fixed == 'dore/exed/nurse-cm' || (isset($sources[2]['dore/exed/nurse-cm']) && !isset($sources[3]))) {
      $remarketing = TRUE;
    }
    elseif ($uri_fixed == 'alearn' || (isset($sources[0]['alearn']) && !isset($sources[1]))) {
      $remarketing = TRUE;
    }
    elseif ($uri_fixed == 'alearn/cjus' || (isset($sources[1]['alearn/cjus']) && !isset($sources[2]))) {
      $remarketing = TRUE;
    }
    elseif ($uri_fixed == 'alearn/fcst' || (isset($sources[1]['alearn/fcst']) && !isset($sources[2]))) {
      $remarketing = TRUE;
    }
    elseif ($uri_fixed == 'alearn/mgmo' || (isset($sources[1]['alearn/mgmo']) && !isset($sources[2]))) {
      $remarketing = TRUE;
    }
    elseif ($uri_fixed == 'alearn/psco' || (isset($sources[1]['alearn/psco']) && !isset($sources[2]))) {
      $remarketing = TRUE;
    }
    elseif ($uri_fixed == 'alearn/sociology' || (isset($sources[1]['alearn/sociology']) && !isset($sources[2]))) {
      $remarketing = TRUE;
    }
    elseif ($uri_fixed == 'socsi/mscj' || (isset($sources[1]['socsi/mscj']) && !isset($sources[2]))) {
      $remarketing = TRUE;
    }

    if ($remarketing) {
      // using JS_THEME to make sure these get processed after all other js.
      // using defer to ensure that the javascript is only processed after the entire page is loaded.
      // cache is set to false to prevent unintended cache load.
      drupal_add_js($base_path . $theme_path . '/js/google-remarketing.js', array('type' => 'file', 'group' => JS_THEME, 'preprocess' => FALSE, 'defer' => TRUE, 'cache' => FALSE));

      // leave the external js files as external so that client can choose how they want to react to these.
      drupal_add_js('//www.googleadservices.com/pagead/conversion.js', array('type' => 'external', 'group' => JS_THEME, 'preprocess' => FALSE, 'defer' => TRUE, 'cache' => FALSE));

      // double-click clickware is apparently needed by googleadservices.
      $double_click = '<noscript>';
      $double_click .= '<div class="element-invisible">';
      $double_click .= '<img class="element-invisible" height="1" width="1" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/980647790/?value=0&label=8OQlCIqCrwgQ7v7N0wM&guid=ON&script=0">';
      $double_click .= '</div>';
      $double_click .= '</noscript>';

      $cf['show']['page']['bottom'] = TRUE;
      if (!isset($cf['page']['bottom'])) {
        $cf['page']['bottom'] = array();
      }
      $cf['page']['bottom']['remarketing']['#markup'] = $double_click;
      unset($double_click);
    }

    unset($remarketing);
  }
}

/**
 * Processes blocks when the block module is disabled.
 *
 * Known modules designed for block presentation should still be provided when block module is disabled.
 * This provides a fallback for when the block module does not exist.
 *
 * Currently disabled.
 *
 * @param array $cf
 *   The cf array that is available for modification.
 *
 * return bool
 *   TRUE on success (when block is disabled).
 *   FALSE otherwise.
 */
/*
function mcneese_www_process_blocks(&$cf) {
  if (function_exists('block_page_build')) {
    return FALSE;
  }

  global $user;

  if (function_exists('workbench_block_view')) {
    // only call workbench block information for logged in users.
    if ($user->uid > 0) {
      $block = workbench_block_view();

      if (is_array($block)) {
        $cf['show']['page']['information'] = TRUE;
        if (!isset($cf['page']['information'])) {
          $cf['page']['information'] = array();
        }
        $cf['page']['information']['workbench_block_view'] = array(
          '#type' => 'container',
          '#attributes' => array(
            'class' => array(
              'block',
              'block-id-1',
              'block-name-block-workbench-block',
              'html_tag-div',
            ),
          ),
        );
        $cf['page']['information']['workbench_block_view']['block'] = $block;
      }
      unset($block);
    }
  }

  return TRUE;
}
*/

/**
 * Given a source url, loads and identifies all distinct parts.
 *
 * @param string $source
 *   The source url string to search for.
 *
 * @return array
 *   An array of possible values.
 */
function mcneese_www_process_build_node_source_parts($node_id) {
  if (!is_numeric($node_id) || $node_id < 1) {
    return array();
  }

  $sources = &drupal_static(__function__);
  if (isset($sources[$node_id])) {
    return $sources[$node_id];
  }

  $sources[$node_id] = array(
  );

  try {
    $query = db_select('url_alias', 'ua');
    $query->addField('ua', 'pid', 'pid');
    $query->addField('ua', 'alias', 'alias');
    $query->condition('ua.source', 'node/' . $node_id);

    $results = $query->execute()->fetchAll();

    foreach ($results as $result) {
      $result_parts = explode('/', $result->alias);

      $count = 0;
      $total_parts = count($result_parts);
      $previous = NULL;
      for (; $count < $total_parts; $count++) {
        if (is_null($previous)) {
          $previous = $result_parts[$count];
        }
        else {
          $previous .= '/' . $result_parts[$count];
        }

        $sources[$node_id][$count][$previous] = $result->pid;
      }
    }
  }
  catch (Exception $ex) {
  }

  return $sources[$node_id];
}

/**
 * @} End of '@defgroup mcneese McNeese - www Theme'.
 */

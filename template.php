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
  $vars['mcneese_bulletin_mode'] = NULL;


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
      $image_url = 'dbu://c/043e94d1/group_image.png';
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
  $markup = '<nav class="menu html_tag-nav">' . "\n";
  $markup .= '  <ul class="navigation_list html_tag-list">' . "\n";
  $markup .= '    <li class="first leaf menu_link-apply-now menu_link-apply_now menu-link-name-menu-primary-navigation menu-link-mlid-4682 id-menu-link-menu-primary-navigation-4682"><a title="" href="/node/5683">Apply Now</a></li>' . "\n";
  $markup .= '    <li class="leaf menu_link-future_students menu-link-name-menu-primary-navigation menu-link-mlid-799 id-menu-link-menu-primary-navigation-799"><a title="" href="/future-students">Future Students</a></li>' . "\n";
  $markup .= '    <li class="leaf menu_link-current_students menu-link-name-menu-primary-navigation menu-link-mlid-898 id-menu-link-menu-primary-navigation-898"><a title="" href="/current-students">Students</a></li>' . "\n";
  $markup .= '    <li class="leaf menu_link-online_learning menu-link-name-menu-primary-navigation menu-link-mlid-6060 id-menu-link-menu-primary-navigation-6060"><a title="" href="/alearn">Online Learning</a></li>' . "\n";
  $markup .= '    <li class="leaf menu_link-faculty_staff menu-link-name-menu-primary-navigation menu-link-mlid-385 id-menu-link-menu-primary-navigation-385"><a href="/faculty-staff">Faculty &amp; Staff</a></li>' . "\n";
  $markup .= '    <li class="leaf menu_link-alumni_friends menu-link-name-menu-primary-navigation menu-link-mlid-1273 id-menu-link-menu-primary-navigation-1273"><a title="" href="/alumni-friends">Alumni &amp; Donors</a></li>' . "\n";
  $markup .= '    <li class="last leaf menu_link-my_mcneese menu-link-name-menu-primary-navigation menu-link-mlid-388 id-menu-link-menu-primary-navigation-388"><a title="" href="https://mymcneese.mcneese.edu/">MyMcNeese</a></li>' . "\n";
  $markup .= '  </ul>' . "\n";
  $markup .= '</nav>' . "\n";

  $cf['data']['page']['header_menu_1'] = $markup;
  $cf['show']['page']['header_menu_1'] = TRUE;
  $cf['show']['page']['header'] = TRUE;

  $markup = '<nav class="menu html_tag-nav">' . "\n";
  $markup .= '  <ul class="navigation_list html_tag-list">' . "\n";
  $markup .= '    <li class="first leaf menu_link-academics menu-link-name-menu-secondary-navigation menu-link-mlid-849 id-menu-link-menu-secondary-navigation-849"><a href="/academics">Academics</a></li>' . "\n";
  $markup .= '    <li class="leaf menu_link-athletics menu-link-name-menu-secondary-navigation menu-link-mlid-850 id-menu-link-menu-secondary-navigation-850"><a href="/athletics">Athletics</a></li>' . "\n";
  $markup .= '    <li class="leaf menu_link-bookstore menu-link-name-menu-secondary-navigation menu-link-mlid-851 id-menu-link-menu-secondary-navigation-851"><a href="/bookstore">Bookstore</a></li>' . "\n";
  $markup .= '    <li class="leaf menu_link-calendar menu-link-name-menu-secondary-navigation menu-link-mlid-2210 id-menu-link-menu-secondary-navigation-2210"><a title="" href="/calendar">Calendar</a></li>' . "\n";
  $markup .= '    <li class="leaf menu_link-campus-map menu-link-name-menu-secondary-navigation menu-link-mlid-852 id-menu-link-menu-secondary-navigation-852"><a title="" href="/campusmaps">Campus Map</a></li>' . "\n";
  $markup .= '    <li class="leaf menu_link-catalog menu-link-name-menu-secondary-navigation menu-link-mlid-6058 id-menu-link-menu-secondary-navigation-6058"><a title="" href="/catalog">Catalog</a></li>' . "\n";
  $markup .= '    <li class="leaf menu_link-employment menu-link-name-menu-secondary-navigation menu-link-mlid-853 id-menu-link-menu-secondary-navigation-853"><a href="/hr/employment">Employment</a></li>' . "\n";
  $markup .= '    <li class="leaf menu_link-library menu-link-name-menu-secondary-navigation menu-link-mlid-854 id-menu-link-menu-secondary-navigation-854"><a href="/library">Library</a></li>' . "\n";
  $markup .= '    <li class="leaf menu_link-research menu-link-name-menu-secondary-navigation menu-link-mlid-858 id-menu-link-menu-secondary-navigation-858"><a href="/research">Research</a></li>' . "\n";
  $markup .= '    <li class="last leaf menu_link-presidents-message menu-link-name-menu-secondary-navigation menu-link-mlid-1564 id-menu-link-menu-secondary-navigation-1564"><a title="" href="/president">President\'s Message</a></li>' . "\n";
  $markup .= '  </ul>' . "\n";
  $markup .= '</nav>' . "\n";

  $cf['data']['page']['header_menu_2'] = $markup;
  $cf['show']['page']['header_menu_2'] = TRUE;
  $cf['show']['page']['header'] = TRUE;


  // jira: ucs-1484: disable 75th links and references, but leave code just in case it needs to be brought back.
  // build the 75th anniversary and prepend it to the 'top' region.
  #$markup = '<div class="noscript no-print" id="the_75th_anniversary_banner">' . "\n";
  #$markup .= '  <div class="top_padding"></div>' . "\n";
  #$markup .= '<a class="learn_more" href="http://75th.mcneese.edu/">Learn More</a></div>' . "\n";
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
    $markup = '  <div id="mcneese-search-box">' . "\n";
    $markup .= '    <div class="search_box-links">' . "\n";
    $markup .= '      <ul class="navigation_list">' . "\n";
    $markup .= '        <li class="search_form-top_box_links-ada"><a href="/ada">ADA</a></li>' . "\n";
    $markup .= '        <div class="search_form-top_box_links-bar">|</div>' . "\n";
    $markup .= '        <li class="search_form-top_box_links-staff"><a href="/search/people">Faculty &amp; Staff Search</a></li>' . "\n";
    $markup .= '        <div class="search_form-top_box_links-bar">|</div>' . "\n";
    $markup .= '        <li class="search_form-top_box_links-index"><a href="/index">A-Z Index</a></li>' . "\n";
    $markup .= '      </ul>' . "\n";
    $markup .= '    </div>' . "\n";
    $markup .= '    <div class="search_box-box">' . "\n";
    $markup .= '      ' . drupal_render($sbf) . "\n";
    $markup .= '    </div>' . "\n";
    $markup .= '  </div>' . "\n";
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
 * @} End of '@defgroup mcneese McNeese - www Theme'.
 */

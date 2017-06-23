<?php

/**
 * @file
 * McNeese - Base Theme.
 */

/**
 * @defgroup mcneese McNeese - Base Theme
 * @ingroup mcneese
 * @{
 * Provides the base mcneese theme.
 */

/**
 * Implements hook_preprocess().
 */
function mcneese_preprocess(&$vars, $hook) {
  if (!is_array($vars)){
    $vars = array();
  }

  $cf = & drupal_static('cf_theme_get_variables', array());

  if (empty($cf)) {
    mcneese_initialize_variables($vars);
  }
}

/**
 * Implements hook_preprocess_maintenance_page().
 */
function mcneese_preprocess_maintenance_page(&$vars) {
  $cf = & drupal_static('cf_theme_get_variables', array());

  if (empty($cf)) {
    mcneese_initialize_variables($vars);
  }

  mcneese_preprocess_html($vars);
  mcneese_preprocess_page($vars);

  // there are certain cases where maintenance mode is not detectable but in use.
  // the end result is that some of the variables are not defined, but should be.
  // this will attempt to fix the situation.
  if (!isset($cf['is']['maintenance']) || !$cf['is']['maintenance']) {
    $cf['is']['maintenance'] = TRUE;
    $cf['is_data']['maintenance']['mode'] = FALSE;
    $cf['is_data']['maintenance']['type'] = 'unknown';

    mcneese_prepare_maintenance_mode_variables($cf, $vars);

    // manually generate data that is normally auto-generated.
    $cf['markup_css']['body']['class'] .= ' is-maintenance';
    mcneese_remove_toolbar_css($cf);
  }

  // while is considered not accessible, it should be done on the maintainance page to help ensure accessibility
  // this is because the maintenance page means the site is not available
  // with this enabled on the maintenance page, it should help the user gain access to the website as soon as it is up.
  // TODO: add support for specifying an approximate refresh time when the site is put into maintenance mode.
  // default to a 30-minute page expiration/refresh.
  $cf['meta']['name']['refresh'] = '1800';

  if ($cf['is']['emergency']) {
    // during an emergency, default to 15 minute refreshes.
    $cf['meta']['name']['refresh'] = '900';
  }


  // avoid bugs with core assuming the existence of things
  $vars['page'] = array();
  $vars['page']['#show_messages'] = TRUE;

  if (isset($vars['content'])) {
    $vars['page']['content'] = $vars['content'];
  }


  // always show header for maintenance mode pages.
  $cf['show']['page']['header'] = TRUE;
}

/**
 * Implements hook_preprocess_html().
 */
function mcneese_preprocess_html(&$vars) {
  $cf = & drupal_static('cf_theme_get_variables', array());

  if (empty($cf)) {
    mcneese_initialize_variables($vars);
  }

  if ($cf['is']['emergency'] && !$cf['is']['logged_in']) {
    $vars['head_title'] = $cf['is_data']['emergency']['title'] . ' | McNeese State University!?';
  }
}

/**
 * Implements hook_preprocess_toolbar().
 */
function mcneese_preprocess_toolbar(&$vars) {
  $cf = & drupal_static('cf_theme_get_variables', array());

  if (empty($cf)) {
    mcneese_initialize_variables($vars);
  }

  // replace the original toolbar elements with our own custom generated ones.
  $vars['mcneese_toolbar'] = array();
  $vars['mcneese_toolbar']['menu'] = array('items' => array());
  $vars['mcneese_toolbar']['shortcuts'] = array('items' => array());

  $tab_index = 1;

  // identify the collapse/expand state of the shortcuts bar.
  if (function_exists('_toolbar_is_collapsed') && _toolbar_is_collapsed()) {
    $toggle_state = 'collapsed';
    $cf['is']['toolbar-shortcuts-expanded'] = FALSE;
    $cf['is']['toolbar-shortcuts-collapsed'] = TRUE;
  }
  else {
    $toggle_state = 'expanded';
    $cf['is']['toolbar-shortcuts-expanded'] = TRUE;
    $cf['is']['toolbar-shortcuts-collapsed'] = FALSE;
  }

  $toolbar_autohide = 'autoshow';
  $toolbar_expanded = 'expanded';
  $toolbar_sticky = 'fixed';
  $cf['is']['toolbar-expanded'] = TRUE;
  $cf['is']['toolbar-collapsed'] = FALSE;

  if (isset($cf['user']['object']->data['mcneese_settings']['navigation']['toolbar']['autohide'])) {
    if ($cf['user']['object']->data['mcneese_settings']['navigation']['toolbar']['autohide']) {
      $toolbar_autohide = 'autohide';
      $toolbar_expanded = 'collapsed';
      $cf['is']['toolbar-expanded'] = FALSE;
      $cf['is']['toolbar-collapsed'] = TRUE;
    }
    else {
      $toolbar_expanded = 'expanded';
      $cf['is']['toolbar-expanded'] = TRUE;
      $cf['is']['toolbar-collapsed'] = FALSE;
    }
  }

  if (isset($cf['user']['object']->data['mcneese_settings']['navigation']['toolbar']['sticky'])) {
    if ($cf['user']['object']->data['mcneese_settings']['navigation']['toolbar']['sticky']) {
      $toolbar_sticky = 'relative';
    }
    else {
      $toolbar_sticky = 'fixed';
    }
  }


  // add the custom home button
  if (isset($vars['toolbar']['custom_home_link']) && $vars['toolbar']['custom_home_link'] !== FALSE) {
    if (is_array($vars['toolbar']['custom_home_link'])) {
      $vars['mcneese_toolbar']['menu']['items'][] = $vars['toolbar']['custom_home_link'];
    }
  }
  elseif (!isset($vars['toolbar']['custom_home_link']) || $vars['toolbar']['custom_home_link'] === TRUE) {
    $item = array();
    $item['attributes'] = array();
    $item['attributes']['class'] = array();
    $item['attributes']['class'][] = 'mcneese-toolbar-home';
    $item['attributes']['class'][] = 'item';
    $item['attributes']['title'] = t("Home");
    $item['markup'] = '<a href="' . base_path() . '" class="link noscript" tabindex="' . $tab_index . '">Home Page</a>';
    $vars['mcneese_toolbar']['menu']['items'][] = $item;
  }


  // populate the toolbar menu
  foreach ($vars['toolbar']['toolbar_menu']['#links'] as $link) {
    $item = array();
    $item['attributes'] = array();
    $item['attributes']['class'] = array();
    $item['attributes']['class'][] = 'item';
    $item['attributes']['title'] = '';

    if (!empty($link['attributes']['title'])) {
      $item['attributes']['title'] = $link['attributes']['title'];
    }

    if (!empty($link['attributes']['id'])) {
      $item['attributes']['id'] = 'mcneese-' . $link['attributes']['id'];
    }

    if (isset($link['attributes']['class']) && is_array($link['attributes']['class'])) {
      $item['attributes']['class'] = array_merge($item['attributes']['class'], $link['attributes']['class']);
    }

    $item['markup'] = '<a href="' . base_path() . $link['href'] . '" class="link" tabindex="' . $tab_index . '">' . $link['title'] . '</a>';
    $vars['mcneese_toolbar']['menu']['items'][] = $item;
  }


  // add the custom toggle button
  $destination = drupal_get_destination();

  if (!empty($destination['destination'])) {
    $destination = '?destination=' . $destination['destination'];
  }
  else {
    $destination = '';
  }

  $item = array();
  $item['attributes'] = array();
  $item['attributes']['class'] = array();
  $item['attributes']['class'][] = 'mcneese-toolbar-toggle';
  $item['attributes']['class'][] = 'item';
  $item['attributes']['title'] = t("Toggle Shortcut Bar");
  $item['markup'] = '<a href="' . base_path() . 'toolbar/toggle' . $destination . '" class="link noscript" tabindex="' . $tab_index . '">Toggle Shortcut Bar</a>';

  $item['attributes']['class'][] = $toggle_state;
  $vars['mcneese_toolbar']['menu']['items'][] = $item;

  unset($destination);


  // populate the toolbar menu (with user links)
  // reverse the array so that when the user settings are floated, they are in the correct order.
  $toolbar_user = array_reverse($vars['toolbar']['toolbar_user']['#links']);

  foreach ($toolbar_user as $link) {
    $item = array();
    $item['attributes'] = array();
    $item['attributes']['class'] = array();
    $item['attributes']['class'][] = 'mcneese-toolbar-user';
    $item['attributes']['class'][] = 'item';
    $item['attributes']['title'] = '';

    if (!empty($link['attributes']['title'])) {
      $item['attributes']['title'] = $link['attributes']['title'];
    }

    if (!empty($link['attributes']['id'])) {
      $item['attributes']['id'] = 'mcneese-' . $link['attributes']['id'];
    }

    if (isset($link['attributes']['class']) && is_array($link['attributes']['class'])) {
      $item['attributes']['class'] = array_merge($item['attributes']['class'], $link['attributes']['class']);
    }

    if (isset($link['attributes']['class']) && is_array($link['attributes']['class'])) {
      $item['attributes']['class'] = array_merge($item['attributes']['class'], $link['attributes']['class']);
    }

    $item['markup'] = '<a href="' . base_path() . $link['href'] . '" class="link noscript" tabindex="' . $tab_index . '">' . $link['title'] . '</a>';
    $vars['mcneese_toolbar']['menu']['items'][] = $item;
  }

  // populate the shortcut menu
  if (!empty($vars['toolbar']['toolbar_drawer'][0]['shortcuts'])) {
    foreach ($vars['toolbar']['toolbar_drawer'][0]['shortcuts'] as $link_id => &$link) {
      if (!is_numeric($link_id)) continue;

      $item = array();
      $item['attributes'] = array();
      $item['attributes']['class'] = array();
      $item['attributes']['class'][] = 'mcneese-toolbar-shortcuts';
      $item['attributes']['class'][] = 'item';
      $item['attributes']['title'] = '';

      if (!empty($link['#attributes']['title'])) {
        $item['attributes']['title'] = $link['#attributes']['title'];
      }

      if (!empty($link['#attributes']['id'])) {
        $item['attributes']['id'] = 'mcneese-' . $link['#attributes']['id'];
      }

      if (isset($link['#attributes']['class']) && is_array($link['#attributes']['class'])) {
        $item['attributes']['class'] = array_merge($item['attributes']['class'], $link['#attributes']['class']);
      }

      $item['markup'] = '<a href="' . base_path() . $link['#href'] . '" class="link noscript" tabindex="' . $tab_index . '">' . $link['#title'] . '</a>';
      $vars['mcneese_toolbar']['shortcuts']['items'][] = $item;
    }
  }

  // add custom sticky button
  $item = array();
  $item['attributes'] = array();
  $item['attributes']['class'] = array();
  $item['attributes']['class'][] = 'mcneese-toolbar-sticky';
  $item['attributes']['class'][] = 'item';
  $item['attributes']['title'] = t("Toggle Menu Stickiness");
  $item['markup'] = '<div class="link noscript" tabindex="' . $tab_index . '"></div>';

  $vars['mcneese_toolbar']['shortcuts']['items'][] = $item;


  unset($vars['toolbar']);


  // build the html tags
  $cf['toolbar']['tags'] = array();

  $attributes = array();
  $attributes['id'] = 'mcneese-toolbar';
  $attributes['title'] = t("Toolbar");
  $attributes['class'] = array();
  $attributes['class'][] = 'noscript';
  $attributes['class'][] = 'mcneese-toolbar';
  $attributes['class'][] = $toolbar_sticky;
  $attributes['class'][] = $toolbar_expanded;
  $attributes['role'] = 'navigation';

  if (!empty($toolbar_autohide)) {
    $attributes['class'][] = $toolbar_autohide;
  }

  $cf['toolbar']['tags']['mcneese_toolbar_open'] = array('name' => 'aside', 'type' => 'semantic', 'attributes' => $attributes, 'html5' => $cf['is']['html5']);
  $cf['toolbar']['tags']['mcneese_toolbar_close'] = array('name' => 'aside', 'type' => 'semantic', 'open' => FALSE, 'html5' => $cf['is']['html5']);

  $cf['toolbar']['tags']['mcneese_toolbar_nav_close'] = array('name' => 'nav', 'type' => 'semantic', 'open' => FALSE, 'html5' => $cf['is']['html5']);

  $attributes = array();
  $attributes['class'] = array();
  $attributes['class'][] = 'mcneese-toolbar-menu';
  $attributes['class'][] = 'noscript';
  $attributes['class'][] = 'shortcuts-' . $toggle_state;

  $cf['toolbar']['tags']['mcneese_toolbar_menu'] = array('name' => 'nav', 'type' => 'semantic', 'attributes' => $attributes, 'html5' => $cf['is']['html5']);

  $attributes = array();
  $attributes['class'] = array();
  $attributes['class'][] = 'mcneese-toolbar-shortcuts';
  $attributes['class'][] = 'noscript';
  $attributes['class'][] = $toggle_state;

  $cf['toolbar']['tags']['mcneese_toolbar_shortcuts'] = array('name' => 'nav', 'type' => 'semantic', 'attributes' => $attributes, 'html5' => $cf['is']['html5']);

  if ($cf['is']['html5']) {
    $vars['header'] = '<header class="element-invisible">';
    $vars['header'] .= '<h2 class="html_tag-heading">';
    $vars['header'] .= t("Toolbar");
    $vars['header'] .= '</h2>';
    $vars['header'] .= '</header>';
  }
  else {
    $vars['header'] = '<h2 class="element-invisible html_tag-heading">';
    $vars['header'] .= t("Toolbar");
    $vars['header'] .= '</h2>';
  }
}

/**
 * Implements hook_preprocess_page().
 */
function mcneese_preprocess_page(&$vars) {
  $cf = & drupal_static('cf_theme_get_variables', array());

  if (empty($cf)) {
    mcneese_initialize_variables($vars);
  }

  // define the bulletin default settings.
  $vars['mcneese_bulletin_mode'] = 2; // mode 1 was used from original design as a sub-theme, so mode 2 represents operating as a hard-coded value.

  $cf['page']['tags'] = array();

  if (!isset($cf['data']['page'])) {
    $cf['data']['page'] = array();
  }


  // load top so that they can be stored in the 'top' region.
  $cf['page']['top'] = '';
  $cf['show']['page']['top'] = FALSE;
  if (!empty($vars['page']['top'])) {
    $cf['page']['top'] = $vars['page']['top'];
    $cf['show']['page']['top'] = TRUE;
    unset($vars['page']['top']);
  }

  $attributes = array();
  $attributes['id'] = 'mcneese-top';
  $attributes['class'] = array();
  $attributes['class'][] = 'expanded';
  $attributes['class'][] = 'noscript';

  $cf['page']['tags']['mcneese_top_open'] = array('name' => 'aside', 'type' => 'semantic', 'attributes' => $attributes, 'html5' => $cf['is']['html5']);
  $cf['page']['tags']['mcneese_top_close'] = array('name' => 'aside', 'type' => 'semantic', 'open' => FALSE, 'html5' => $cf['is']['html5']);


  // load bottom so that they can be stored in the 'top' region.
  $cf['page']['bottom'] = '';
  $cf['show']['page']['bottom'] = FALSE;
  if (!empty($vars['page']['bottom'])) {
    $cf['page']['bottom'] = $vars['page']['bottom'];
    $cf['show']['page']['bottom'] = TRUE;
    unset($vars['page']['bottom']);
  }

  $attributes = array();
  $attributes['id'] = 'mcneese-bottom';
  $attributes['class'] = array();
  $attributes['class'][] = 'expanded';
  $attributes['class'][] = 'noscript';

  $cf['page']['tags']['mcneese_bottom_open'] = array('name' => 'aside', 'type' => 'semantic', 'attributes' => $attributes, 'html5' => $cf['is']['html5']);
  $cf['page']['tags']['mcneese_bottom_close'] = array('name' => 'aside', 'type' => 'semantic', 'open' => FALSE, 'html5' => $cf['is']['html5']);


  // setup page logo
  $cf['data']['page']['logo']['title'] = $cf['at']['human_name'];
  $cf['data']['page']['logo']['alt'] = $cf['at']['human_name'];
  $cf['data']['page']['logo']['href'] = base_path();
  $cf['show']['page']['logo'] = TRUE;


  // load page header
  if (empty($vars['page']['header'])) {
    $cf['page']['header'] = array();
  }
  else {
    $cf['page']['header'] = $vars['page']['header'];
    unset($vars['page']['header']);
  }

  if (empty($vars['page']['header_menu_1'])) {
    $cf['page']['header_menu_1'] = array();
  }
  else {
    $cf['page']['header_menu_1'] = $vars['page']['header_menu_1'];
    unset($vars['page']['header_menu_1']);
  }

  if (empty($vars['page']['header_menu_2'])) {
    $cf['page']['header_menu_2'] = array();
  }
  else {
    $cf['page']['header_menu_2'] = $vars['page']['header_menu_2'];
    unset($vars['page']['header_menu_2']);
  }

  $attributes = array();
  $attributes['id'] = 'mcneese-header';
  $attributes['class'] = array();
  $attributes['class'][] = 'noscript';
  $attributes['class'][] = 'relative';
  $attributes['class'][] = 'expanded';
  $attributes['role'] = 'banner';

  $cf['page']['tags']['mcneese_page_header_open'] = array('name' => 'aside', 'type' => 'semantic', 'attributes' => $attributes, 'html5' => $cf['is']['html5']);
  $cf['page']['tags']['mcneese_page_header_close'] = array('name' => 'aside', 'type' => 'semantic', 'open' => FALSE, 'html5' => $cf['is']['html5']);


  // load all messages so that they can be stored in the 'messages' region.
  mcneese_preprocess_page_prepare_messages($cf, $vars);


  // load all help so that they can be stored in the 'help' region.
  $help_sticky = 'relative';

  if (isset($cf['user']['object']->data['mcneese_settings']['region']['help']['sticky'])) {
    $sticky = & $cf['user']['object']->data['mcneese_settings']['region']['help']['sticky'];
    if ($sticky) {
      $help_sticky = 'relative';

      if ($sticky == 'on' || $sticky == 'always') {
        $help_sticky = 'relative';
      }
      else if ($sticky == 'off' || $sticky == 'never') {
        $help_sticky = 'fixed';
      }
    }
    else {
      $help_sticky = 'fixed';
    }
  }

  if (!empty($vars['help'])) {
    $cf['page']['help'] = array_merge($vars['cf']['page']['help'], $vars['help']);
    unset($vars['help']);
  }

  if (empty($vars['page']['help'])) {
    $cf['page']['help'] = array();
  }
  else {
    $cf['page']['help'] = $vars['page']['help'];
    unset($vars['page']['help']);
  }

  $attributes = array();
  $attributes['id'] = 'mcneese-help';
  $attributes['class'] = array();
  $attributes['class'][] = 'noscript';
  $attributes['title'] = t("Help");

  if ($cf['is']['html5']) {
    $attributes['class'][] = $help_sticky;

    if ($help_sticky == 'fixed') {
      $attributes['class'][] = 'collapsed';
      $attributes['tabindex'] = '2';
    }
    else {
      $attributes['class'][] = 'expanded';
    }
  }
  else {
    $attributes['class'][] = 'relative';
    $attributes['class'][] = 'expanded';
  }

  $cf['page']['tags']['mcneese_page_help_open'] = array('name' => 'aside', 'type' => 'semantic', 'attributes' => $attributes, 'html5' => $cf['is']['html5']);
  $cf['page']['tags']['mcneese_page_help_close'] = array('name' => 'aside', 'type' => 'semantic', 'open' => FALSE, 'html5' => $cf['is']['html5']);

  $attributes = array();
  $attributes['class'] = array();

  $cf['page']['tags']['mcneese_page_help_header_open'] = array('name' => 'header', 'type' => 'semantic', 'attributes' => $attributes, 'html5' => $cf['is']['html5']);
  $cf['page']['tags']['mcneese_page_help_header_close'] = array('name' => 'header', 'type' => 'semantic', 'open' => FALSE, 'html5' => $cf['is']['html5']);

  $attributes = array();
  $attributes['class'] = array();
  $attributes['class'][] = 'help-wrapper';

  $cf['page']['tags']['mcneese_page_help_wrapper_open'] = array('name' => 'div', 'type' => 'semantic', 'attributes' => $attributes, 'html5' => $cf['is']['html5']);
  $cf['page']['tags']['mcneese_page_help_wrapper_close'] = array('name' => 'div', 'type' => 'semantic', 'open' => FALSE, 'html5' => $cf['is']['html5']);


  // load all bulletin so that they can be stored in the 'bulletin' region.
  $bulletin_sticky = 'relative';

  if (empty($vars['page']['bulletin'])) {
    $cf['page']['bulletin'] = array();
  }
  else {
    $cf['page']['bulletin'] = $vars['page']['bulletin'];
    unset($vars['page']['bulletin']);
  }

  $attributes = array();
  $attributes['id'] = 'mcneese-bulletin';
  $attributes['class'] = array();
  $attributes['class'][] = 'noscript';
  $attributes['title'] = t("Bulletin");

  if ($cf['is']['html5']) {
    $attributes['class'][] = $bulletin_sticky;

    if ($bulletin_sticky == 'fixed') {
      $attributes['class'][] = 'collapsed';
      $attributes['tabindex'] = '2';
    }
    else {
      $attributes['class'][] = 'expanded';
    }
  }
  else {
    $attributes['class'][] = 'relative';
    $attributes['class'][] = 'expanded';
  }

  $cf['page']['tags']['mcneese_page_bulletin_open'] = array('name' => 'aside', 'type' => 'semantic', 'attributes' => $attributes, 'html5' => $cf['is']['html5']);
  $cf['page']['tags']['mcneese_page_bulletin_close'] = array('name' => 'aside', 'type' => 'semantic', 'open' => FALSE, 'html5' => $cf['is']['html5']);

  $attributes = array();
  $attributes['class'] = array();

  $cf['page']['tags']['mcneese_page_bulletin_header_open'] = array('name' => 'header', 'type' => 'semantic', 'attributes' => $attributes, 'html5' => $cf['is']['html5']);
  $cf['page']['tags']['mcneese_page_bulletin_header_close'] = array('name' => 'header', 'type' => 'semantic', 'open' => FALSE, 'html5' => $cf['is']['html5']);

  $attributes = array();
  $attributes['class'] = array();
  $attributes['class'][] = 'bulletin-wrapper';

  $cf['page']['tags']['mcneese_page_bulletin_wrapper_open'] = array('name' => 'div', 'type' => 'semantic', 'attributes' => $attributes, 'html5' => $cf['is']['html5']);
  $cf['page']['tags']['mcneese_page_bulletin_wrapper_close'] = array('name' => 'div', 'type' => 'semantic', 'open' => FALSE, 'html5' => $cf['is']['html5']);


  // load all information so that they can be stored in the 'information' region.
  $information_sticky = 'relative';

  if (isset($cf['user']['object']->data['mcneese_settings']['region']['information']['sticky'])) {
    $sticky = & $cf['user']['object']->data['mcneese_settings']['region']['information']['sticky'];
    if ($sticky) {
      $information_sticky = 'relative';

      if ($sticky == 'on' || $sticky == 'always') {
        $information_sticky = 'relative';
      }
      else if ($sticky == 'off' || $sticky == 'never') {
        $information_sticky = 'fixed';
      }
    }
    else {
      $information_sticky = 'fixed';
    }
  }

  if (empty($vars['page']['information'])) {
    $cf['page']['information'] = array();
  }
  else {
    $cf['page']['information'] = $vars['page']['information'];
    unset($vars['page']['information']);
  }

  $attributes = array();
  $attributes['id'] = 'mcneese-information';
  $attributes['class'] = array();
  $attributes['class'][] = 'noscript';
  $attributes['title'] = t("Information");

  if ($cf['is']['html5']) {
    $attributes['class'][] = $information_sticky;

    if ($information_sticky == 'fixed') {
      $attributes['class'][] = 'collapsed';
      $attributes['tabindex'] = '2';
    }
    else {
      $attributes['class'][] = 'expanded';
    }
  }
  else {
    $attributes['class'][] = 'relative';
    $attributes['class'][] = 'expanded';
  }

  $cf['page']['tags']['mcneese_page_information_open'] = array('name' => 'aside', 'type' => 'semantic', 'attributes' => $attributes, 'html5' => $cf['is']['html5']);
  $cf['page']['tags']['mcneese_page_information_close'] = array('name' => 'aside', 'type' => 'semantic', 'open' => FALSE, 'html5' => $cf['is']['html5']);

  $attributes = array();
  $attributes['class'] = array();

  $cf['page']['tags']['mcneese_page_information_header_open'] = array('name' => 'header', 'type' => 'semantic', 'attributes' => $attributes, 'html5' => $cf['is']['html5']);
  $cf['page']['tags']['mcneese_page_information_header_close'] = array('name' => 'header', 'type' => 'semantic', 'open' => FALSE, 'html5' => $cf['is']['html5']);

  $attributes = array();
  $attributes['class'] = array();
  $attributes['class'][] = 'information-wrapper';

  $cf['page']['tags']['mcneese_page_information_wrapper_open'] = array('name' => 'div', 'type' => 'semantic', 'attributes' => $attributes, 'html5' => $cf['is']['html5']);
  $cf['page']['tags']['mcneese_page_information_wrapper_close'] = array('name' => 'div', 'type' => 'semantic', 'open' => FALSE, 'html5' => $cf['is']['html5']);


  // load all tasks
  if (function_exists('menu_local_tasks')) {
    $cf['page']['menu_tabs'] = menu_local_tasks(0);
    $cf['page']['sub_tabs'] = menu_local_tasks(1);
  }
  else {
    $cf['page']['menu_tabs'] = '';
    $cf['page']['sub_tabs'] = '';
  }

  $tabs_sticky = 'relative';

  if (isset($cf['user']['object']->data['mcneese_settings']['navigation']['menu_tabs']['sticky'])) {
    $sticky = & $cf['user']['object']->data['mcneese_settings']['navigation']['menu_tabs']['sticky'];
    if ($sticky) {
      $tabs_sticky = 'relative';

      if ($sticky == 'on' || $sticky == 'always') {
        $tabs_sticky = 'relative';
      }
      else if ($sticky == 'off' || $sticky == 'never') {
        $tabs_sticky = 'fixed';
      }
    }
    else {
      $tabs_sticky = 'fixed';
    }
  }

  $attributes = array();
  $attributes['id'] = 'mcneese-tabs';
  $attributes['class'] = array();
  $attributes['class'][] = 'noscript';
  $attributes['tabindex'] = '2';
  $attributes['title'] = t("Menu Tabs");

  if ($cf['is']['html5']) {
    $attributes['class'][] = $tabs_sticky;

    if ($tabs_sticky == 'fixed') {
      $attributes['class'][] = 'collapsed';
    }
    else {
      $attributes['class'][] = 'expanded';
    }
  }
  else {
    $attributes['class'][] = 'relative';
    $attributes['class'][] = 'expanded';
  }

  $cf['page']['tags']['mcneese_page_menu_tabs_open'] = array('name' => 'nav', 'type' => 'semantic', 'attributes' => $attributes, 'html5' => $cf['is']['html5']);
  $cf['page']['tags']['mcneese_page_menu_tabs_close'] = array('name' => 'nav', 'type' => 'semantic', 'open' => FALSE, 'html5' => $cf['is']['html5']);


  // load all action_links
  $action_links_sticky = 'relative';

  if (isset($cf['user']['object']->data['mcneese_settings']['navigation']['action_links']['sticky'])) {
    $sticky = & $cf['user']['object']->data['mcneese_settings']['navigation']['action_links']['sticky'];
    if ($sticky) {
      $action_links_sticky = 'relative';

      if ($sticky == 'on' || $sticky == 'always') {
        $action_links_sticky = 'relative';
      }
      else if ($sticky == 'off' || $sticky == 'never') {
        $action_links_sticky = 'fixed';
      }
    }
    else {
      $action_links_sticky = 'fixed';
    }
  }

  if (empty($vars['action_links'])) {
    $cf['page']['action_links'] = array();
  }
  else {
    $cf['page']['action_links'] = $vars['action_links'];
    unset($vars['action_links']);
  }

  $attributes = array();
  $attributes['id'] = 'mcneese-action_links';
  $attributes['class'] = array();
  $attributes['class'][] = 'noscript';
  $attributes['tabindex'] = '2';
  $attributes['title'] = t("Action Links");

  if ($cf['is']['html5']) {
    $attributes['class'][] = $action_links_sticky;

    if ($action_links_sticky == 'fixed') {
      $attributes['class'][] = 'collapsed';
    }
    else {
      $attributes['class'][] = 'expanded';
    }
  }
  else {
    $attributes['class'][] = 'relative';
    $attributes['class'][] = 'expanded';
  }

  $cf['page']['tags']['mcneese_page_action_links_open'] = array('name' => 'nav', 'type' => 'semantic', 'attributes' => $attributes, 'html5' => $cf['is']['html5']);
  $cf['page']['tags']['mcneese_page_action_links_close'] = array('name' => 'nav', 'type' => 'semantic', 'open' => FALSE, 'html5' => $cf['is']['html5']);


  // load page side
  $side_sticky = 'relative';

  if (isset($cf['user']['object']->data['mcneese_settings']['region']['side']['sticky'])) {
    $sticky = & $cf['user']['object']->data['mcneese_settings']['region']['side']['sticky'];
    if ($sticky) {
      $side_sticky = 'relative';

      if ($sticky == 'on' || $sticky == 'always') {
        $side_sticky = 'relative';
      }
      else if ($sticky == 'off' || $sticky == 'never') {
        $side_sticky = 'fixed';
      }
    }
    else {
      $side_sticky = 'fixed';
    }
  }

  $attributes = array();
  $attributes['id'] = 'mcneese-page-side';
  $attributes['class'] = array();
  $attributes['class'][] = 'noscript';
  $attributes['class'][] = 'column-1';

  if ($cf['is']['html5']) {
    $attributes['class'][] = $side_sticky;

    if ($side_sticky == 'fixed') {
      $attributes['class'][] = 'collapsed';
    }
    else {
      $attributes['class'][] = 'expanded';
    }
  }
  else {
    $attributes['class'][] = 'relative';
    $attributes['class'][] = 'expanded';
  }

  $cf['page']['tags']['mcneese_page_side_open'] = array('name' => 'div', 'type' => 'semantic', 'attributes' => $attributes, 'html5' => $cf['is']['html5']);
  $cf['page']['tags']['mcneese_page_side_close'] = array('name' => 'div', 'type' => 'semantic', 'open' => FALSE, 'html5' => $cf['is']['html5']);


  // load title
  $cf['page']['title'] = drupal_get_title();

  if (empty($vars['title_prefix'])) {
    $cf['page']['title_prefix'] = array();
  }
  else {
    $cf['page']['title_prefix'] = $vars['title_prefix'];
    unset($vars['title_prefix']);
  }

  if (empty($vars['title_suffix'])) {
    $cf['page']['title_suffix'] = array();
  }
  else {
    $cf['page']['title_suffix'] = $vars['title_suffix'];
    unset($vars['title_suffix']);
  }

  $attributes = array();
  $attributes['class'] = array();
  $attributes['class'][] = 'page-title';

  $cf['page']['tags']['mcneese_page_title_open'] = array('name' => 'header', 'type' => 'semantic', 'attributes' => $attributes, 'html5' => $cf['is']['html5']);
  $cf['page']['tags']['mcneese_page_title_close'] = array('name' => 'header', 'type' => 'semantic', 'open' => FALSE, 'html5' => $cf['is']['html5']);


  // load all breadcrumb and sidecrumb data
  if (empty($vars['breadcrumb'])) {
    if (function_exists('menu_get_active_breadcrumb')) {
      $cf['page']['breadcrumb'] = drupal_get_breadcrumb();
    }
    else {
      $cf['page']['breadcrumb'] = '';
    }
  }
  else {
    $cf['page']['breadcrumb'] = $vars['breadcrumb'];
    unset($vars['breadcrumb']);
  }

  $cf['page']['precrumb'] = '';

  if (isset($cf['user']['object']) && is_object($cf['user']['object']) && $cf['user']['object']->uid > 0) {
    if ($cf['is']['node']) {
      $cf['page']['precrumb'] .= '<div class="crumb-node_id">' . t("Node " . $cf['is_data']['node']['object']->nid) . '</div>';
    }

    if ($cf['is']['profile']) {
      $cf['page']['precrumb'] .= '<div class="crumb-user_id">' . t("User " . $cf['is_data']['profile']['object']->uid) . '</div>';
    }
  }

  $breadcrumb_sticky = 'relative';

  if (isset($cf['user']['object']->data['mcneese_settings']['navigation']['breadcrumb']['sticky'])) {
    $sticky = & $cf['user']['object']->data['mcneese_settings']['navigation']['breadcrumb']['sticky'];
    if ($sticky) {
      $breadcrumb_sticky = 'relative';

      if ($sticky == 'on' || $sticky == 'always') {
        $breadcrumb_sticky = 'relative';
      }
      else if ($sticky == 'off' || $sticky == 'never') {
        $breadcrumb_sticky = 'fixed';
      }
    }
    else {
      $breadcrumb_sticky = 'fixed';
    }
  }

  $attributes = array();
  $attributes['id'] = 'mcneese-breadcrumb';
  $attributes['class'] = array();
  $attributes['class'][] = 'noscript';
  $attributes['role'] = 'navigation';

  if ($cf['is']['html5']) {
    $attributes['class'][] = $breadcrumb_sticky;

    if ($breadcrumb_sticky == 'fixed') {
      $attributes['class'][] = 'collapsed';
    }
    else {
      $attributes['class'][] = 'expanded';
    }
  }
  else {
    $attributes['class'][] = 'relative';
    $attributes['class'][] = 'expanded';
  }

  $cf['page']['tags']['mcneese_page_breadcrumb_open'] = array('name' => 'nav', 'type' => 'semantic', 'attributes' => $attributes, 'html5' => $cf['is']['html5']);
  $cf['page']['tags']['mcneese_page_breadcrumb_close'] = array('name' => 'nav', 'type' => 'semantic', 'open' => FALSE, 'html5' => $cf['is']['html5']);


  // Load side content
  if (empty($vars['page']['menus'])) {
    $cf['page']['menus'] = array();
  }
  else {
    $cf['page']['menus'] = $vars['page']['menus'];
    unset($vars['page']['menus']);
  }

  if (empty($vars['page']['asides'])) {
    $cf['page']['asides'] = array();
  }
  else {
    $cf['page']['asides'] = $vars['page']['asides'];
    unset($vars['page']['asides']);
  }


  // load all content
  if (empty($vars['page']['content'])) {
    if (empty($vars['content'])) {
      $cf['page']['content'] = array();
    }
    else {
      $cf['page']['content'] = $vars['content'];
    }
  }
  else {
    $cf['page']['content'] = $vars['page']['content'];
  }


  // load footer so that they can be stored in the 'footer' region.
  if (!empty($vars['page']['footer'])) {
    $cf['page']['footer'] = $vars['page']['footer'];
    unset($vars['page']['footer']);
  }

  $attributes = array();
  $attributes['id'] = 'mcneese-footer';
  $attributes['class'] = array();
  $attributes['class'][] = 'expanded';
  $attributes['class'][] = 'noscript';

  $cf['page']['tags']['mcneese_page_footer_open'] = array('name' => 'aside', 'type' => 'semantic', 'attributes' => $attributes, 'html5' => $cf['is']['html5']);
  $cf['page']['tags']['mcneese_page_footer_close'] = array('name' => 'aside', 'type' => 'semantic', 'open' => FALSE, 'html5' => $cf['is']['html5']);


  // add any watermarks
  $cf['page']['watermarks-pre'] = '';
  $cf['page']['watermarks-post'] = '';


  if (isset($cf['is']['user_settings-watermarks']) && $cf['is']['user_settings-watermarks'] && $cf['is']['node'] && isset($cf['user']['object']->uid) && $cf['user']['object']->uid > 0) {
    if (isset($cf['is']['workbench-moderated']) && $cf['is']['workbench-moderated']) {
      if (empty($cf['is']['workbench-moderated-published'])) {
        $cf['page']['watermarks-pre'] .= '<div class="watermark-unpublished">Unpublished</div>';
      }

      if (!empty($cf['is']['workbench-moderated-draft'])) {
        if (!empty($cf['is']['workbench-moderated-state-needs_work'])) {
          $cf['page']['watermarks-post'] .= '<div class="watermark-draft">Needs Work</div>';
        }
        elseif (!empty($cf['is']['workbench-moderated-state-needs_review'])) {
          $cf['page']['watermarks-post'] .= '<div class="watermark-draft">Needs Review</div>';
        }
        else {
          $cf['page']['watermarks-post'] .= '<div class="watermark-draft">Draft</div>';
        }
      }
    }
    else if (empty($vars['node']->status)) {
      $cf['page']['watermarks-pre'] .= '<div class="watermark-unpublished">Unpublished</div>';
    }
  }


  // work area
  $attributes = array();
  $attributes['id'] = 'mcneese-work_area_menu';
  $attributes['class'] = array();
  $attributes['class'][] = 'fixed';
  $attributes['class'][] = 'noscript';

  $cf['page']['tags']['mcneese_page_work_area_menu_open'] = array('name' => 'menu', 'type' => 'semantic', 'attributes' => $attributes, 'html5' => $cf['is']['html5']);
  $cf['page']['tags']['mcneese_page_work_area_menu_close'] = array('name' => 'menu', 'type' => 'semantic', 'open' => FALSE, 'html5' => $cf['is']['html5']);

  $cf['show']['page']['work_area_menu'] = $cf['is']['logged_in'];
  $cf['data']['page']['work_area_menu'] = array();
  $cf['data']['page']['work_area_menu']['page_width'] = 'work_area-state-on';
  $cf['data']['page']['work_area_menu']['page_width-toggle'] = TRUE;

  if (isset($cf['user']['object']->data['mcneese_settings']['style']['work_area']['page_width'])) {
    if ($cf['user']['object']->data['mcneese_settings']['style']['work_area']['page_width']) {
      $cf['data']['page']['work_area_menu']['page_width'] = 'work_area-state-on';
    }
    else {
      $cf['data']['page']['work_area_menu']['page_width'] = 'work_area-state-off';
    }
  }

  if (isset($cf['user']['object']->data['mcneese_settings']['style']['work_area']['page_width-toggle'])) {
    if ($cf['user']['object']->data['mcneese_settings']['style']['work_area']['page_width-toggle']) {
      $cf['data']['page']['work_area_menu']['page_width-toggle'] = TRUE;
    }
    else {
      $cf['data']['page']['work_area_menu']['page_width-toggle'] = FALSE;
    }
  }


  // present the emergency/maintenance message if applicable
  if ($cf['is']['emergency']) {
    if ($cf['is_data']['maintenance']['access']) {
      drupal_set_message($cf['is_data']['emergency']['message'], 'warning', FALSE);
    }
  }
  else if ($cf['is']['maintenance'] && $cf['is_data']['maintenance']['mode']) {
    if ($cf['is_data']['maintenance']['access']) {
      drupal_set_message($cf['is_data']['maintenance']['message'], 'status', FALSE);
    }
  }


  // load any messages that might have appeared during the template preprocess operation
  $messages = drupal_get_messages();

  if (!empty($messages)) {
    if (isset($cf['data']['page']['messages']['raw']) && is_array($cf['data']['page']['messages']['raw'])) {
      $cf['data']['page']['messages']['raw'] = array_merge($cf['data']['page']['messages']['raw'], $messages);
    }
    else {
      $cf['data']['page']['messages']['raw'] = $messages;
    }

    $cf['show']['page']['messages'] = TRUE;
  }


  // assign custom body css tags
  $alias_paths = explode('/', $cf['at']['alias']);

  if (function_exists('cf_theme_safe_css_string_part')) {
    foreach ($alias_paths as $c => &$ap) {
      $p = cf_theme_safe_css_string_part($ap);

      if (!empty($p)) {
        $cf['markup_css']['body']['class'] .= ' alias-part-' . $c . '-' . $p;
      }
    }
  }

  if (isset($vars['node']) && is_object($vars['node'])) {
    $node_type = check_plain($vars['node']->type);

    $custom_tag = $node_type . '_type-default';
    $custom_property = 'field_' . $node_type . '_theme';

    if (property_exists($vars['node'], $custom_property)) {
      $custom_prop = & $vars['node']->$custom_property;
      if (!empty($custom_prop['und'][0]['tid'])) {
        $custom_tag = 'node-theme-' . $node_type . '-' . $custom_prop['und'][0]['tid'];
      }
    }

    $custom_tag = check_plain($custom_tag);
    $cf['markup_css']['body']['class'] .= ' ' . $custom_tag;
  }
}

/**
 * Implements hook_preprocess_media_dialog_page().
 */
function mcneese_preprocess_media_dialog_page(&$vars) {
  mcneese_preprocess_page($vars);

  $cf = & drupal_static('cf_theme_get_variables', array());

  $cf['markup_css']['body']['class'] .= ' is-media_browser_page';

  mcneese_remove_toolbar_css($cf);

  $cf['is']['fixed_width'] = FALSE;
  $cf['is']['flex_width'] = TRUE;
  $cf['is']['media_dialog_page'] = TRUE;

  $cf['page']['breadcrumb'] = NULL;
  $cf['show']['page']['work_area_menu'] = FALSE;
}

/**
 * Implements hook_preprocess_node().
 */
function mcneese_preprocess_node(&$vars) {
  $cf = & drupal_static('cf_theme_get_variables', array());

  if (empty($cf)) {
    mcneese_initialize_variables($vars);
  }

  $cf['node'] = array();
  $cf['node']['tags'] = array();


  // load header
  $cf['node']['header'] = &$vars['title'];
  $cf['node']['title_prefix'] = &$vars['title_prefix'];
  $cf['node']['title_suffix'] = &$vars['title_suffix'];
  unset($vars['title']);
  unset($vars['title_prefix']);
  unset($vars['title_suffix']);

  $attributes = array();
  $attributes['class'] = array('node-header', 'element-invisible');

  $cf['node']['tags']['mcneese_node_header_open'] = array('name' => 'header', 'type' => 'semantic', 'attributes' => $attributes, 'html5' => $cf['is']['html5']);
  $cf['node']['tags']['mcneese_node_header_close'] = array('name' => 'header', 'type' => 'semantic', 'open' => FALSE, 'html5' => $cf['is']['html5']);


  // load content
  $cf['node']['content'] = &$vars['content'];
  unset($vars['content']);

  $node_type = check_plain($vars['node']->type);

  $attributes = array();
  $attributes['class'] = array();
  $attributes['class'][] = 'node';
  $attributes['class'][] = 'node-id-' . check_plain($vars['node']->nid);
  $attributes['class'][] = 'node-revision-' . check_plain($vars['node']->vid);
  $attributes['class'][] = 'node-type-' . $node_type;

  if ($vars['node']->status) {
    $custom_tag = 'node-published';
    $attributes['class'][] = $custom_tag;
  }
  else {
    $custom_tag = 'node-unpublished';
    $attributes['class'][] = $custom_tag;
  }

  // only display uid if the user is logged in
  if (isset($cf['user']['object']->uid) && $cf['user']['object']->uid > 0) {
    $attributes['class'][] = 'node-owner_id-' . check_plain($vars['node']->uid);
  }


  // add custom subthemes if they exist.
  $custom_tag = $node_type . '_type-default';
  $custom_property = 'field_' . $node_type . '_theme';

  if (property_exists($vars['node'], $custom_property)) {
    $custom_prop = & $vars['node']->$custom_property;
    if (!empty($custom_prop['und'][0]['tid'])) {
      $custom_tag = 'node-theme-' . $node_type . '-' . $custom_prop['und'][0]['tid'];
    }
  }

  $custom_tag = check_plain($custom_tag);
  $attributes['class'][] = $custom_tag;

  $cf['node']['tags']['mcneese_node_open'] = array('name' => 'section', 'type' => 'semantic', 'attributes' => $attributes, 'html5' => $cf['is']['html5']);
  $cf['node']['tags']['mcneese_node_close'] = array('name' => 'section', 'type' => 'semantic', 'open' => FALSE, 'html5' => $cf['is']['html5']);
}

/**
 * Implements hook_preprocess_block().
 */
function mcneese_preprocess_block(&$vars) {
  $cf = & drupal_static('cf_theme_get_variables', array());

  if (empty($cf)) {
    mcneese_initialize_variables($vars);
  }

  $cf['block'] = array();
  $cf['block']['tags'] = array();


  // load semantic
  if (!property_exists($vars['block'], 'semantic') || empty($vars['block']->semantic)) {
    $cf['block']['semantic'] = 'ignore';
  }
  else {
    $cf['block']['semantic'] = $vars['block']->semantic;
  }


  // load heading
  $cf['block']['header'] = '';
  $cf['block']['heading'] = '';

  if (!empty($vars['block']->subject)) {
    if (!property_exists($vars['block'], 'heading') || empty($vars['block']->heading)) {
      if ($vars['block']->subject != '<none>') {
        $cf['block']['header'] = $vars['block']->subject;
        $cf['block']['heading'] = 2;
      }
    }
    else if (cf_is_integer($vars['block']->heading) && $vars['block']->heading > 0) {
      $cf['block']['header'] = $vars['block']->subject;
      $cf['block']['heading'] = $vars['block']->heading;
    }
    else {
      $cf['block']['header'] = '';
    }
  }

  $attributes = array();
  $attributes['class'] = array('block-header');

  $cf['block']['tags']['mcneese_block_header_open'] = array('name' => 'header', 'type' => 'semantic', 'attributes' => $attributes, 'html5' => $cf['is']['html5']);
  $cf['block']['tags']['mcneese_block_header_close'] = array('name' => 'header', 'type' => 'semantic', 'open' => FALSE, 'html5' => $cf['is']['html5']);


  // load content
  $cf['block']['content'] = &$vars['content'];
  unset($vars['content']);

  if ($cf['block']['semantic'] != 'none') {
    $attributes = array();
    $attributes['class'] = array();
    $attributes['class'][] = 'block';
    $attributes['class'][] = 'block-id-' . check_plain($vars['block_id']);
    $attributes['class'][] = 'block-name-' . check_plain($vars['block_html_id']);
    $attributes['class'][] = check_plain($vars['block_zebra']);

    if ($cf['block']['semantic'] == 'ignore') {
      $cf['block']['tags']['mcneese_block_open'] = array('name' => 'div', 'type' => 'semantic', 'attributes' => $attributes, 'html5' => $cf['is']['html5']);
      $cf['block']['tags']['mcneese_block_close'] = array('name' => 'div', 'type' => 'semantic', 'open' => FALSE, 'html5' => $cf['is']['html5']);
    }
    else {
      $cf['block']['tags']['mcneese_block_open'] = array('name' => $cf['block']['semantic'], 'type' => 'semantic', 'attributes' => $attributes, 'html5' => $cf['is']['html5']);
      $cf['block']['tags']['mcneese_block_close'] = array('name' => $cf['block']['semantic'], 'type' => 'semantic', 'open' => FALSE, 'html5' => $cf['is']['html5']);
    }
  }
}

/**
 * Implements hook_preprocess_field().
 */
function mcneese_preprocess_field(&$vars) {
  $cf = & drupal_static('cf_theme_get_variables', array());

  if (empty($cf)) {
    mcneese_initialize_variables($vars);
  }

  $cf['field'] = array();
  $cf['field']['tags'] = array();


  // process specific fields: body
  if ($vars['element']['#field_name'] == 'body') {
    $attributes = isset($vars['item_attributes_array']) ? $vars['item_attributes_array'] : array();
    $attributes['class'] = isset($vars['classes_array']) ? $vars['classes_array'] : array();
    $attributes['class'][] = 'field-item';

    $cf['field']['tags']['mcneese_field__body_open'] = array('name' => 'section', 'type' => 'semantic', 'attributes' => $attributes, 'html5' => $cf['is']['html5']);
    $cf['field']['tags']['mcneese_field__body_close'] = array('name' => 'section', 'type' => 'semantic', 'open' => FALSE, 'html5' => $cf['is']['html5']);
  }
}

/**
 * Implements hook_preprocess_user_profile().
 */
function mcneese_preprocess_user_profile(&$vars) {
  $cf = & drupal_static('cf_theme_get_variables', array());

  if (empty($cf)) {
    mcneese_initialize_variables($vars);
  }

  $cf['user_profile'] = array();
  $cf['user_profile']['tags'] = array();


  // profile header
  $attributes = array();
  $attributes['class'] = array('user_profile-header');

  $cf['user_profile']['tags']['mcneese_user_profile_header_open'] = array('name' => 'header', 'type' => 'semantic', 'attributes' => $attributes, 'html5' => $cf['is']['html5']);
  $cf['user_profile']['tags']['mcneese_user_profile_header_close'] = array('name' => 'header', 'type' => 'semantic', 'open' => FALSE, 'html5' => $cf['is']['html5']);


  // profile content
  $attributes = array();

  $cf['user_profile']['tags']['mcneese_user_profile_open'] = array('name' => 'section', 'type' => 'semantic', 'attributes' => $attributes, 'html5' => $cf['is']['html5']);
  $cf['user_profile']['tags']['mcneese_user_profile_close'] = array('name' => 'section', 'type' => 'semantic', 'open' => FALSE, 'html5' => $cf['is']['html5']);
}

/**
 * Implements hook_preprocess_user_profile_category().
 */
function mcneese_preprocess_user_profile_category(&$vars) {
  $cf = & drupal_static('cf_theme_get_variables', array());

  if (empty($cf)) {
    mcneese_initialize_variables($vars);
  }

  $cf['user_profile_category'] = array();
  $cf['user_profile_category']['tags'] = array();


  // profile header
  $attributes = array();
  $attributes['class'] = array('user_profile_category-header');

  $cf['user_profile_category']['tags']['mcneese_user_profile_category_header_open'] = array('name' => 'header', 'type' => 'semantic', 'attributes' => $attributes, 'html5' => $cf['is']['html5']);
  $cf['user_profile_category']['tags']['mcneese_user_profile_category_header_close'] = array('name' => 'header', 'type' => 'semantic', 'open' => FALSE, 'html5' => $cf['is']['html5']);


  // profile content
  $attributes = array();

  $cf['user_profile_category']['tags']['mcneese_user_profile_category_open'] = array('name' => 'section', 'type' => 'semantic', 'attributes' => $attributes, 'html5' => $cf['is']['html5']);
  $cf['user_profile_category']['tags']['mcneese_user_profile_category_close'] = array('name' => 'section', 'type' => 'semantic', 'open' => FALSE, 'html5' => $cf['is']['html5']);
}

/**
 * Implements hook_preprocess_user_picture().
 */
function mcneese_preprocess_user_picture(&$vars) {
  $cf = & drupal_static('cf_theme_get_variables', array());

  if (empty($cf)) {
    mcneese_initialize_variables($vars);
  }

  $cf['user_picture']['tags'] = array();

  $vars['user_picture'] = '';
  $vars['user_name'] = '';

  // username is not always defined, such as when adding new users.
  if (is_object($vars['account']) && property_exists($vars['account'], 'name')) {
    $vars['user_name'] = $vars['account']->name;
  }

  if (variable_get('user_pictures', 0)) {
    if (!empty($vars['account']->picture)) {
      if (is_numeric($vars['account']->picture)) {
        $vars['account']->picture = file_load($vars['account']->picture);
      }
      if (!empty($vars['account']->picture->uri)) {
        $filepath = $vars['account']->picture->uri;
      }
    }
    elseif (variable_get('user_picture_default', '')) {
      $filepath = variable_get('user_picture_default', '');
    }

    if (isset($filepath)) {
      if ($cf['is']['html5']) {
        $alt = "";
        $title = t("@user's Picture", array('@user' => format_username($vars['account'])));
      }
      else {
        $alt = t("@user's Picture", array('@user' => format_username($vars['account'])));
        $title = $alt;
      }

      // If the image does not have a valid Drupal scheme (for eg. HTTP), don't load image styles.
      if (module_exists('image') && file_valid_uri($filepath) && $style = variable_get('user_picture_style', '')) {
        $vars['user_picture'] = theme('image_style', array('style_name' => $style, 'path' => $filepath, 'alt' => $alt, 'title' => $title));
      }
      else {
        $vars['user_picture'] = theme('image', array('path' => $filepath, 'alt' => $alt, 'title' => $title));
      }

      if (!empty($vars['account']->uid) && user_access('access user profiles')) {
        $attributes = array(
          'attributes' => array('title' => t("View user profile.")),
          'html' => TRUE,
        );

        $vars['user_picture'] = l($vars['user_picture'], 'user/' . $vars['account']->uid, $attributes);
      }
    }
  }


  $attributes = array();
  $attributes['class'] = array('file', 'image', 'user-picture');

  $cf['user_picture']['tags']['mcneese_user_picture_open'] = array('name' => 'figure', 'type' => 'semantic', 'attributes' => $attributes, 'html5' => $cf['is']['html5']);
  $cf['user_picture']['tags']['mcneese_user_picture_close'] = array('name' => 'figure', 'type' => 'semantic', 'open' => FALSE, 'html5' => $cf['is']['html5']);
}

/**
 * Implements hook_preprocess_search_block_form().
 */
function mcneese_preprocess_search_block_form(&$vars) {
  $cf = & drupal_static('cf_theme_get_variables', array());

  if (empty($cf)) {
    mcneese_initialize_variables($vars);
  }

  $cf['search_block_form'] = array();
  $cf['search_block_form']['tags'] = array();

  $attributes = array();
  $attributes['class'] = array('search_block_form-section');
  $attributes['role'] = 'search';

  $cf['search_block_form']['tags']['mcneese_search_block_form_section_open'] = array('name' => 'section', 'type' => 'semantic', 'attributes' => $attributes, 'html5' => $cf['is']['html5']);
  $cf['search_block_form']['tags']['mcneese_search_block_form_section_close'] = array('name' => 'section', 'type' => 'semantic', 'open' => FALSE, 'html5' => $cf['is']['html5']);

  $attributes = array();
  $attributes['class'] = array('search_block_form-header');

  $cf['search_block_form']['tags']['mcneese_search_block_form_header_open'] = array('name' => 'header', 'type' => 'semantic', 'attributes' => $attributes, 'html5' => $cf['is']['html5']);
  $cf['search_block_form']['tags']['mcneese_search_block_form_header_close'] = array('name' => 'header', 'type' => 'semantic', 'open' => FALSE, 'html5' => $cf['is']['html5']);
}

/**
 * Implements hook_preprocess_search_results().
 */
function mcneese_preprocess_search_results(&$vars) {
  $cf = & drupal_static('cf_theme_get_variables', array());

  if (empty($cf)) {
    mcneese_initialize_variables($vars);
  }

  $cf['search_results'] = array();
  $cf['search_results']['tags'] = array();

  $attributes = array();
  $attributes['class'] = array();
  $attributes['class'][] = 'search_results-section';

  $cf['search_results']['tags']['mcneese_search_results_section_open'] = array('name' => 'section', 'type' => 'semantic', 'attributes' => $attributes, 'html5' => $cf['is']['html5']);
  $cf['search_results']['tags']['mcneese_search_results_section_close'] = array('name' => 'section', 'type' => 'semantic', 'open' => FALSE, 'html5' => $cf['is']['html5']);

  $attributes = array();
  $attributes['class'] = array('search_results-header');

  $cf['search_results']['tags']['mcneese_search_results_header_open'] = array('name' => 'header', 'type' => 'semantic', 'attributes' => $attributes, 'html5' => $cf['is']['html5']);
  $cf['search_results']['tags']['mcneese_search_results_header_close'] = array('name' => 'header', 'type' => 'semantic', 'open' => FALSE, 'html5' => $cf['is']['html5']);
}

/**
 * Implements hook_preprocess_search_result().
 */
function mcneese_preprocess_search_result(&$vars) {
  $cf = & drupal_static('cf_theme_get_variables', array());

  if (empty($cf)) {
    mcneese_initialize_variables($vars);
  }

  $cf['search_result'] = array();
  $cf['search_result']['tags'] = array();

  $attributes = array();
  $attributes['class'] = array();
  $attributes['class'][] = 'search_result-section';

  $cf['search_result']['tags']['mcneese_search_result_section_open'] = array('name' => 'section', 'type' => 'semantic', 'attributes' => $attributes, 'html5' => $cf['is']['html5']);
  $cf['search_result']['tags']['mcneese_search_result_section_close'] = array('name' => 'section', 'type' => 'semantic', 'open' => FALSE, 'html5' => $cf['is']['html5']);

  $attributes = array();
  $attributes['class'] = array('search_result-header');

  $cf['search_result']['tags']['mcneese_search_result_header_open'] = array('name' => 'header', 'type' => 'semantic', 'attributes' => $attributes, 'html5' => $cf['is']['html5']);
  $cf['search_result']['tags']['mcneese_search_result_header_close'] = array('name' => 'header', 'type' => 'semantic', 'open' => FALSE, 'html5' => $cf['is']['html5']);
}

/**
 * Implements hook_preprocess_advanced_help_popup().
 */
function mcneese_preprocess_advanced_help_popup(&$vars) {
  $cf = & drupal_static('cf_theme_get_variables', array());

  if (empty($cf)) {
    mcneese_initialize_variables($vars);
  }

  $cf['page'] = array();
  $cf['page']['tags'] = array();


  // always use flex width for advanced help.
  $cf['is']['fixed_width'] = FALSE;
  $cf['is']['flex_width'] = TRUE;

  $cf['markup_css']['body']['class'] = preg_replace('/\bis-fixed_width\b/', 'is-flex_width', $cf['markup_css']['body']['class']);


  // page title
  if (!empty($vars['title'])) {
    $cf['page']['title'] = $vars['title'];
  }

  $attributes = array();
  $attributes['class'] = array();
  $attributes['class'][] = 'page-title';

  $cf['page']['tags']['mcneese_page_title_open'] = array('name' => 'header', 'type' => 'semantic', 'attributes' => $attributes, 'html5' => $cf['is']['html5']);
  $cf['page']['tags']['mcneese_page_title_close'] = array('name' => 'header', 'type' => 'semantic', 'open' => FALSE, 'html5' => $cf['is']['html5']);


  // messages
  mcneese_preprocess_page_prepare_messages($cf, $vars);


  // load any messages that might have appeared during the template preprocess operation
  $messages = drupal_get_messages();

  if (!empty($messages)) {
    if (isset($cf['data']['page']['messages']['raw']) && is_array($cf['data']['page']['messages']['raw'])) {
      $cf['data']['page']['messages']['raw'] = array_merge($cf['data']['page']['messages']['raw'], $messages);
    }
    else {
      $cf['data']['page']['messages']['raw'] = $messages;
    }

    $cf['show']['page']['messages'] = TRUE;
  }
}

/**
 * Implements hook_preprocess_workbench_menu_list().
 */
function mcneese_preprocess_workbench_menu_list(&$vars) {
  $cf = & drupal_static('cf_theme_get_variables', array());

  if (empty($cf)) {
    mcneese_initialize_variables($vars);
  }

  $cf['workbench_menu_list'] = array();
  $cf['workbench_menu_list']['tags'] = array();
  $cf['workbench_menu_list']['settings'] = array();
  $cf['workbench_menu_list']['settings']['print_header'] = FALSE;

  $attributes = $vars['list']['attributes'];

  if (!isset($attributes['class']) || !is_array($attributes['class'])) {
    $attributes['class'] = array();
  }

  $attributes['role'] = 'navigation';

  $cf['workbench_menu_list']['tags']['menu_list_open'] = array('name' => 'nav', 'type' => 'semantic', 'attributes' => $attributes, 'html5' => $cf['is']['html5']);
  $cf['workbench_menu_list']['tags']['menu_list_close'] = array('name' => 'nav', 'type' => 'semantic', 'open' => FALSE, 'html5' => $cf['is']['html5']);
}

/**
 * Implements hook_cf_theme_get_variables_alter().
 */
function mcneese_cf_theme_get_variables_alter(&$cf, $vars) {
  $cf['theme']['path'] = base_path() . drupal_get_path('theme', 'mcneese');
  $cf['theme']['machine_name'] = 'mcneese';
  $cf['theme']['human_name'] = t("McNeese");

  $cf['subtheme'] = array();

  $cf['meta']['name']['copyright'] = "2016© McNeese State University";
  $cf['meta']['name']['description'] = "McNeese State University Website";
  $cf['meta']['name']['distribution'] = "web";

  $cf['headers'] = '';
  $cf['tags'] = array();
  $cf['agent']['doctype'] = '<!DOCTYPE html>';
  $cf['date']['enabled'] = TRUE;

  $cf['link']['shortcut_icon'] = array();
  $cf['link']['shortcut_icon']['href'] = $cf['theme']['path'] . '/images/icon.gif';
  $cf['link']['shortcut_icon']['rel'] = 'shortcut icon';

  if (!$cf['is']['logged_in']) {
    if ($cf['is']['front']) {
      $date_value = strtotime('+1 day', $cf['request']);
    }
    else {
      $date_value = strtotime('+1 week', $cf['request']);
    }

    $cf['meta']['name']['expires'] = gmdate('D, d M Y H:i:s T', $date_value);
    $cf['meta']['http-equiv']['expires'] = gmdate('D, d M Y H:i:s T', $date_value);
  }
  else {
    $cf['meta']['http-equiv']['cache-control'] = 'no-cache, must-revalidate';

    $cf['is']['fixed_width'] = FALSE;
    $cf['is']['flex_width'] = TRUE;
  }

  foreach (array('html5', 'legacy', 'unsupported', 'in_ie_compatibility_mode', 'in_ie_normal_mode') as $key) {
    $cf['is'][$key] = FALSE;
    $cf['is_data'][$key] = array();
  }

  $cf['is']['html5'] = TRUE;

  if (!isset($cf['show']['messages'])) {
    $cf['data']['page']['messages'] = array('raw' => array(), 'renderable' => array(), 'blocks' => array());
    $cf['show']['page']['messages'] = FALSE;
  }

  if (!isset($cf['show']['skipnav'])) {
    $cf['data']['skipnav'] = array();
    $cf['show']['skipnav'] = TRUE;
  }


  // tweak features based on user-agent
  if ($cf['agent']['machine_name'] == 'ie') {
    $cf['is']['in_ie_normal_mode'] = TRUE;

    if ($cf['is']['html5']) {
      drupal_add_http_header('X-UA-Compatible', 'IE=Edge,chrome=1');

      $cf['meta']['http-equiv']['X-UA-Compatible'] = 'IE=Edge,chrome=1';
    }
    else {
      drupal_add_http_header('X-UA-Compatible', 'IE=Edge');

      $cf['meta']['http-equiv']['X-UA-Compatible'] = 'IE=Edge';
    }

    $custom_css = array();
    $custom_css['options'] = array('type' => 'file', 'group' => CSS_THEME, 'every_page' => TRUE, 'weight' => 5, 'media' => 'all', 'preprocess' => FALSE);
    $custom_css['data'] = $cf['theme']['path'] . '/css/workaround/ie.css';
    drupal_add_css($custom_css['data'], $custom_css['options']);

    if ($cf['agent']['major_version'] != 'unknown' && $cf['agent']['major_version'] <= 8) {
      // add javascript that auto-creates html5 tag names to workaround design flaw in older IE that does not process CSS for unknown tags.
      drupal_add_js(drupal_get_path('theme', 'mcneese') . '/js/ie_html5.js', array('group' => JS_THEME, 'browsers' => array('IE' => 'lte IE 8', '!IE' => FALSE), 'weight' => 10, 'preprocess' => TRUE));
      drupal_add_js(drupal_get_path('theme', 'mcneese') . '/js/ie_html5-print.js', array('group' => JS_THEME, 'browsers' => array('IE' => 'lte IE 8', '!IE' => FALSE), 'weight' => 10, 'preprocess' => TRUE));

      $cf['is']['in_ie_compatibility_mode'] = FALSE;
      if (isset($cf['agent']['ie_compatibility']) && $cf['agent']['ie_compatibility']) {
        $cf['is']['in_ie_compatibility_mode'] = TRUE;
        $cf['is']['in_ie_normal_mode'] = FALSE;
      }
      else {
        $custom_css = array();
        $custom_css['options'] = array('type' => 'file', 'group' => CSS_THEME, 'every_page' => TRUE, 'weight' => 5, 'media' => 'all', 'preprocess' => FALSE);
        $custom_css['data'] = $cf['theme']['path'] . '/css/workaround/ie-legacy.css';
        drupal_add_css($custom_css['data'], $custom_css['options']);
      }

      if ($cf['agent']['major_version'] <= 7 && !$cf['is']['in_ie_compatibility_mode']) {
        $cf['is']['unsupported'] = TRUE;
        $cf['is']['html5'] = FALSE;
        $cf['is']['legacy'] = TRUE;
      }
    }
  }

  if (!$cf['is']['html5']) {
    $cf['show']['skipnav'] = TRUE;
  }


  // toolbar support (only show for logged in accounts)
  $process_toolbar = FALSE;
  if (function_exists('toolbar_view')) {
    foreach (array('toolbar', 'toolbar-autoshow', 'toolbar-autohide', 'toolbar-fixed', 'toolbar-relative', 'toolbar-expanded', 'toolbar-collapsed', 'toolbar-shortcuts-expanded', 'toolbar-shortcuts-collapsed') as $key) {
      $cf['is'][$key] = FALSE;
      $cf['is_data'][$key] = array();
    }
    $process_toolbar = TRUE;
  }

  if ($cf['is']['maintenance']) {
    mcneese_prepare_maintenance_mode_variables($cf, $vars);

    if ($cf['is_data']['maintenance']['type'] == 'normal') {
      if (!$cf['is_data']['maintenance']['access']) {
        $process_toolbar = FALSE;
      }
    }
    else {
      $process_toolbar = FALSE;
    }

    $cf['is_data']['maintenance']['title'] = t("Site Under Maintenance.");
    $cf['is_data']['maintenance']['body'] = variable_get('maintenance_mode_message', "This website is under maintenance.");
    $cf['is_data']['maintenance']['message'] = 'This website is operating in <span class="maintenance_mode-notice-maintenance_mode">Maintenance Mode</span>.<br>';

    if (user_access('administer site configuration')) {
      $cf['is_data']['maintenance']['message'] .= 'To exit <span class="maintenance_mode-notice-maintenance_mode">Maintenance Mode</span>, <a href="' . url('admin/config/development/maintenance') . '">go online</a>.';
    }

    if (function_exists('mcneese_management_get_emergency_mode')) {
      $emergency_mode = mcneese_management_get_emergency_mode();

      if ($emergency_mode == 3) {
        $emergency_node = mcneese_management_get_emergency_node($emergency_mode);

        if ($emergency_node > 0) {
          $loaded_node = node_load($emergency_node);

          if (is_object($loaded_node)) {
            $date_value = strtotime('+900 seconds', $cf['request']);
            $cf['meta']['name']['expires'] = gmdate('D, d M Y H:i:s T', $date_value);
            $cf['meta']['http-equiv']['expires'] = gmdate('D, d M Y H:i:s T', $date_value);
            $cf['meta']['http-equiv']['cache-control'] = 'no-cache, must-revalidate';

            $cf['is']['emergency'] = TRUE;
            $cf['is_data']['emergency'] = array();
            $cf['is_data']['emergency']['title'] = $loaded_node->title;
            $cf['is_data']['emergency']['body'] = $loaded_node->body['und']['0']['value'];

            $cf['is_data']['emergency']['message'] = 'This website is operating in <span class="emergency_mode-notice-emergency_mode">Emergency Mode</span>.<br>';
            $cf['is_data']['emergency']['message'] .= t('To exit <span class="emergency_mode-notice-emergency_mode">Emergency Mode</span>, a privileged user must <a href="@emergency_manage_path">disable the emergency mode</a>.', array('@emergency_manage_path' => base_path() . 'admin/content/management/emergency'));
          }

          if (!$cf['is']['logged_in']) {
            $vars['head_title'] = $cf['is_data']['emergency']['title'] . ' | McNeese State University';
          }
        }
      }
    }
  }

  if ($process_toolbar) {
    $cf['is']['toolbar'] = user_access('access toolbar');

    if ($cf['is']['toolbar'] && isset($cf['user']['object']->uid) && $cf['user']['object']->uid > 0) {
      $cf['is']['toolbar-expanded'] = TRUE;
      $cf['is']['toolbar-collapsed'] = FALSE;
      $cf['is']['toolbar-sticky'] = FALSE;
      $cf['is']['toolbar-fixed'] = TRUE;
      $cf['is']['toolbar-relative'] = FALSE;
      $cf['is']['toolbar-autoshow'] = TRUE;
      $cf['is']['toolbar-autohide'] = FALSE;

      if (isset($cf['user']['object']->data['mcneese_settings']['navigation']['toolbar'])) {
        $toolbar_settings = $cf['user']['object']->data['mcneese_settings']['navigation']['toolbar'];

        if (isset($toolbar_settings['autohide']) && $toolbar_settings['autohide']) {
          $cf['is']['toolbar-expanded'] = FALSE;
          $cf['is']['toolbar-collapsed'] = TRUE;
          $cf['is']['toolbar-autoshow'] = FALSE;
          $cf['is']['toolbar-autohide'] = TRUE;
        }

        if (isset($toolbar_settings['sticky']) && $toolbar_settings['sticky']) {
          $cf['is']['toolbar-fixed'] = FALSE;
          $cf['is']['toolbar-relative'] = TRUE;
        }
      }

      if (function_exists('_toolbar_is_collapsed') && _toolbar_is_collapsed()) {
        $cf['is']['toolbar-shortcuts-expanded'] = FALSE;
        $cf['is']['toolbar-shortcuts-collapsed'] = TRUE;
      }
      else {
        $cf['is']['toolbar-shortcuts-expanded'] = TRUE;
        $cf['is']['toolbar-shortcuts-collapsed'] = FALSE;
      }
    }
  }


  // workbench moderation support (only show for logged in accounts)
  if ($cf['is']['node'] && isset($cf['user']['object']->uid)  && $cf['user']['object']->uid > 0) {
    if (property_exists($cf['is_data']['node']['object'], 'workbench_moderation')) {
      $cf['is']['workbench-moderated'] = TRUE;
      $cf['is']['workbench-moderated-published'] = FALSE;
      $cf['is']['workbench-moderated-live'] = FALSE;
      $cf['is']['workbench-moderated-draft'] = FALSE;

      if (empty($cf['is_data']['node']['object']->workbench_moderation['published'])) {
        $cf['is']['workbench-moderated-live'] = TRUE;
        $cf['is']['workbench-moderated-draft'] = TRUE;
      }
      else {
        $vid_current = & $cf['is_data']['node']['object']->workbench_moderation['current']->vid;
        $vid_published = & $cf['is_data']['node']['object']->workbench_moderation['published']->vid;
        $cf['is']['workbench-moderated-published'] = TRUE;
        $matched = preg_match('~node/' . $cf['is_data']['node']['object']->nid . '/(draft|edit|revisions/\d+/view)($|/)~i', $cf['at']['path']);

        if ($matched > 0) {
          if ($vid_current == $vid_published) {
            $cf['is']['workbench-moderated-live'] = TRUE;
            $cf['is']['workbench-moderated-draft'] = TRUE;
            $cf['is']['workbench-moderated-state-published'] = TRUE;
          }
          else {
            $cf['is']['workbench-moderated-draft'] = TRUE;

            $wm_state = cf_theme_safe_css_string_part($cf['is_data']['node']['object']->workbench_moderation['current']->state);
            $cf['is']['workbench-moderated-state-' . $wm_state] = TRUE;
            unset($wm_state);
          }
        }

        unset($vid_current);
        unset($vid_published);
      }
    }
    else {
      $cf['is']['workbench-unmoderated'] = TRUE;
    }
  }


  // use current path to define known things about a node path
  if ($cf['is']['node']) {
    $cf['is']['node-view'] = FALSE;
    $cf['is']['node-view-revision'] = FALSE;
    $cf['is']['node-draft'] = FALSE;
    $cf['is']['node-unknown'] = FALSE;

    $current_path = current_path();
    $matched = preg_match('@^node/(\d+)(/$|$|\?.*|#.*)@', $current_path);

    if ($matched > 0) {
      $cf['is']['node-view'] = TRUE;
    }

    if ($matched == 0) {
      $matched = preg_match('@^node/(\d+)/revisions/(\d+)/view(/$|$|\?.*|#.*)@', $current_path);

      if ($matched > 0) {
        $cf['is']['node-view-revision'] = TRUE;
      }
    }

    if ($matched == 0) {
      $matches = array();
      $matched = preg_match('@^node/(\d+)/(edit|draft|webform|webform-\w+\b|accessibility|moderation|revisions|delete|undelete|devel)(/.*$|$|\?.*|#.*)@', $current_path, $matches);

      if ($matched > 0) {
        if (empty($matches[2])) {
          $matched = 0;
        }
        else {
          $cf['is']['node-' . check_plain($matches[2])] = TRUE;

          // tag all special displays that are not some form of 'view' as node_management.
          if ($matches[2] != 'draft') {
            $cf['is']['node_management'] = TRUE;
          }
        }
      }
    }

    if ($matched == 0) {
      $cf['is']['node-unknown'] = TRUE;
    }
  }


  if ($cf['is']['logged_in']) {
    // default to enabled for logged in users
    $cf['is_data']['user_settings-background_colors'] = array();
    $cf['is_data']['user_settings-watermarks'] = array();

    if (isset($cf['user']['object']->data['mcneese_settings']['style']['subtle_information']['background_colors'])) {
      if ($cf['user']['object']->data['mcneese_settings']['style']['subtle_information']['background_colors']) {
        $cf['is']['user_settings-background_colors'] = TRUE;
      }
      else {
        $cf['is']['user_settings-background_colors'] = FALSE;
      }
    }
    else {
      $cf['is']['user_settings-background_colors'] = TRUE;
    }

    if (isset($cf['user']['object']->data['mcneese_settings']['style']['subtle_information']['watermarks'])) {
      if ($cf['user']['object']->data['mcneese_settings']['style']['subtle_information']['watermarks']) {
        $cf['is']['user_settings-watermarks'] = TRUE;
      }
      else {
        $cf['is']['user_settings-watermarks'] = FALSE;
      }
    }
    else {
      $cf['is']['user_settings-watermarks'] = TRUE;
    }

    if (isset($cf['user']['object']->data['mcneese_settings']['style']['work_area']['page_width'])) {
      if ($cf['user']['object']->data['mcneese_settings']['style']['work_area']['page_width']) {
        $cf['is']['fixed_width'] = FALSE;
        $cf['is']['flex_width'] = TRUE;
      }
      else {
        $cf['is']['fixed_width'] = TRUE;
        $cf['is']['flex_width'] = FALSE;
      }
    }
    else {
      $cf['is']['fixed_width'] = FALSE;
      $cf['is']['flex_width'] = TRUE;
    }
  }


  // allow subthemes alter functions to be processed.
  drupal_alter('mcneese_get_variables', $cf, $vars);
}

/**
 * Perform initialization of variables array.
 *
 * @param array $vars
 *   The variables array.
 */
function mcneese_initialize_variables(&$vars) {
  $cf = & drupal_static('cf_theme_get_variables', array());

  if (!empty($cf)) return;

  if (function_exists('cf_theme_get_variables')) {
    $cf = cf_theme_get_variables($vars);
  }
  else {
    $cf['agent'] = array();
    $cf['link'] = array();

    $cf['at'] = array();
    $cf['is'] = array();
    $cf['show'] = array();
    $cf['show']['page'] = array();
    $cf['show']['html'] = array();
    $cf['data'] = array();
    $cf['data']['page'] = array();
    $cf['data']['html'] = array();
    $cf['theme'] = array();

    $cf['show']['page']['breadcrumb'] = FALSE;
    $cf['data']['page']['breadcrumb'] = array();
    $cf['show']['page']['content'] = FALSE;
    $cf['data']['page']['content'] = array();

    $cf['markup_css'] = array();
    $cf['markup_css']['body'] = array();
    $cf['markup_css']['body']['class'] = '';
    $cf['markup_css']['container'] = array();
    $cf['markup_css']['container']['class'] = '';
    $cf['markup_css']['content'] = array();
    $cf['markup_css']['content']['class'] = '';


    // some defaults we can guess
    $cf['at']['machine_name'] = '';
    $cf['at']['human_name'] = '';

    $cf['is']['front'] = drupal_is_front_page();
    $cf['is']['html5'] = TRUE;
    $cf['is']['maintenance'] = FALSE;
    $cf['is']['emergency'] = FALSE;
    $cf['is']['logged_in'] = function_exists('user_is_logged_in') ? user_is_logged_in() : FALSE;

    $cf['request'] = time();

    $cf['agent']['doctype'] = '<!DOCTYPE html>';

    $cf['meta'] = array();
    $cf['meta']['name'] = array();

    if (function_exists('current_path')) {
      $cf['at']['path'] = current_path();
    }
    elseif (isset($_GET['q'])) {
      $cf['at']['path'] = $_GET['q'];
    }

    if (function_exists('request_path')) {
      $cf['at']['alias'] = request_path();
    }
    elseif (isset($_GET['q'])) {
      $cf['at']['alias'] = $_GET['q'];
    }
  }


  // refresh is considered not accessible
  $cf['meta']['name']['refresh'] = '';


  // initialize rdf namespaces
  $cf['show']['html']['rdf_namespaces'] = FALSE;
  $cf['data']['html']['rdf_namespaces'] = drupal_get_rdf_namespaces();

  mcneese_initialize_generic_tags($cf);
}

/**
 * Perform initialization of generic tags.
 *
 * @param array $cf
 *   The cf variables array.
 */
function mcneese_initialize_generic_tags(&$cf) {
  if (isset($cf['generic']['tags'])) {
    return;
  }

  $cf['generic'] = array();
  $cf['generic']['tags'] = array();


  // provide a generic header
  $attributes = array();
  $attributes['class'] = array();
  $attributes['role'] = '';

  $cf['generic']['tags']['mcneese_header_open'] = array('name' => 'header', 'type' => 'semantic', 'attributes' => $attributes, 'html5' => $cf['is']['html5']);
  $cf['generic']['tags']['mcneese_header_close'] = array('name' => 'header', 'type' => 'semantic', 'open' => FALSE, 'html5' => $cf['is']['html5']);


  // provide a generic hgroup
  $attributes = array();
  $attributes['class'] = array();
  $attributes['role'] = '';

  $cf['generic']['tags']['mcneese_hgroup_open'] = array('name' => 'hgroup', 'type' => 'semantic', 'attributes' => $attributes, 'html5' => $cf['is']['html5']);
  $cf['generic']['tags']['mcneese_hgroup_close'] = array('name' => 'hgroup', 'type' => 'semantic', 'open' => FALSE, 'html5' => $cf['is']['html5']);
}

/**
 * Render all data for: page.
 */
function mcneese_render_page() {
  $cf = & drupal_static('cf_theme_get_variables', array());


  // standard render
  if (function_exists('cf_theme_render_cf')) {
    $keys = array('top', 'header', 'header_menu_1', 'header_menu_2', 'action_links', 'title', 'title_prefix', 'title_suffix', 'help', 'bulletin', 'information', 'menus', 'asides', 'precrumb', 'postcrumb', 'help', 'footer', 'bottom', 'hidden', 'watermarks-pre', 'watermarks-post');
    cf_theme_render_cf($cf, $keys, 'page');
  }


  // always show header content if any of its child regions are visible
  if (isset($cf['show']['page']['header_menu_1']) && $cf['show']['page']['header_menu_1']) {
    $cf['show']['page']['header'] = TRUE;
  }
  elseif (isset($cf['show']['page']['header_menu_2']) && $cf['show']['page']['header_menu_2']) {
    $cf['show']['page']['header'] = TRUE;
  }

  if ($cf['is']['maintenance']) {
    $cf['show']['page']['header'] = TRUE;
  }


  // render breadcrumb
  if (!empty($cf['page']['breadcrumb'])) {
    $cf['data']['page']['breadcrumb'] = theme('breadcrumb', array('breadcrumb' => $cf['page']['breadcrumb']));
    $cf['show']['page']['breadcrumb'] = TRUE;
  }
  else {
    $cf['data']['page']['breadcrumb'] = '';
    $cf['show']['page']['breadcrumb'] = FALSE;
  }


  // render content
  if (empty($cf['page']['content'])) {
    $cf['show']['page']['content'] = FALSE;
  }
  else {
    if (is_array($cf['page']['content'])) {
      $cf['data']['page']['content'] = drupal_render_children($cf['page']['content']);
    }
    else {
      $cf['data']['page']['content'] = render($cf['page']['content']);
    }

    if (empty($cf['data']['page']['content'])) {
      $cf['show']['page']['content'] = FALSE;
    }
    else {
      $cf['show']['page']['content'] = TRUE;
    }
  }


  // render the message array pieces
  if (isset($cf['show']['page']) && array_key_exists('messages', $cf['show']['page']) && $cf['show']['page']['messages']) {
    $raw = array();
    if (isset($cf['data']['page']['messages']['raw'])) {
      $raw = $cf['data']['page']['messages']['raw'];
    }

    $blocks = '';
    if (isset($cf['data']['page']['messages']['blocks'])) {
      $blocks = $cf['data']['page']['messages']['blocks'];
    }

    $cf['data']['page']['messages']['renderred'] = theme('status_messages', array('messages' => $raw, 'other' => render($blocks)));
  }


  // build the primary and secondary tabs
  if ($cf['is']['logged_in']) {
    mcneese_render_page_tabs();
  }
  else {
    $cf['show']['page']['menu_tabs'] = FALSE;
  }


  // show user login path title as Login
  if ($cf['at']['path'] == 'user/login') {
    $cf['show']['page']['title'] = TRUE;

    $cf['data']['page']['title'] = t("Login");
  }


  // handle maintenance mode as a special case
  if ($cf['is']['maintenance']) {
    if (!$cf['is_data']['maintenance']['access'] || $cf['is_data']['maintenance']['type'] != 'normal') {
      $cf['show']['page']['header'] = TRUE;
      $cf['show']['page']['title'] = FALSE;

      if ($cf['is_data']['maintenance']['type'] == 'update') {
        $cf['show']['page']['title'] = TRUE;

        if (!$cf['show']['page']['breadcrumb']) {
          $cf['data']['page']['breadcrumb'] = theme('breadcrumb', array('breadcrumb' => array()));
          $cf['show']['page']['breadcrumb'] = TRUE;
        }
      }
      else if ($cf['is_data']['maintenance']['type'] == 'unknown') {
        $cf['show']['page']['title'] = TRUE;
      }
      else {
        $cf['show']['page']['breadcrumb'] = FALSE;
        $cf['show']['page']['precrumb'] = FALSE;
        $cf['show']['page']['postcrumb'] = FALSE;
      }
    }

    if ($cf['is']['emergency']) {
      if (!$cf['is_data']['maintenance']['access']) {
        $cf['show']['page']['title'] = TRUE;

        if ($cf['at']['path'] != 'user/login') {
          $cf['data']['page']['title'] = render($cf['is_data']['emergency']['title']);

          $cf['show']['page']['content'] = TRUE;
          $cf['data']['page']['content'] = render($cf['is_data']['emergency']['body']);
        }

        if (!$cf['show']['page']['breadcrumb']) {
          $cf['data']['page']['breadcrumb'] = '';
          $cf['show']['page']['breadcrumb'] = TRUE;
        }
      }
    }
  }
}

/**
 * Render all data for: node.
 */
function mcneese_render_node() {
  $cf = & drupal_static('cf_theme_get_variables', array());


  if (function_exists('cf_theme_render_cf')) {
    $keys = array('title_prefix', 'title_suffix', 'header', 'content');
    cf_theme_render_cf($cf, $keys, 'node');

    if (empty($cf['data']['node']['content'])) {
      $cf['show']['node']['content'] = FALSE;
    }
  }
}


/**
 * Render all data for: block.
 */
function mcneese_render_block() {
  $cf = & drupal_static('cf_theme_get_variables', array());

  if (function_exists('cf_theme_render_cf')) {
    $keys = array('title_prefix', 'title_suffix', 'header', 'content');
    cf_theme_render_cf($cf, $keys, 'block');

    if ($cf['block']['heading'] == 0) {
      $cf['show']['block']['header'] = FALSE;
    }
    else {
      $cf['show']['block']['header'] = TRUE;
    }
  }
}

/**
 * Custom implementation of theme_status_messages().
 *
 * This overrides the core theme.
 * This adds an additional arguments:
 *   messages (optional):
 *   - a pre-generated message array instead of having this theme function
 *     directly call drupal_get_messages().
 *   other (optional):
 *   - additional markup to append after the standard message.
 *   - this is usually a custom block.
 *   - is assumed to be already renderred markup.
 *
 * @see theme_status_messages()
 * @see mcneese_themes_theme_registry_alter()
 */
function mcneese_status_messages($vars) {
  $cf = & drupal_static('cf_theme_get_variables', array());

  if (!empty($vars['messages']) && is_array($vars['messages'])) {
    $all_messages = $vars['messages'];
  }
  else {
    $display = $vars['display'];
    $all_messages = drupal_get_messages($display);
  }

  $output = '';

  if (empty($all_messages)) {
    return $output;
  }

  $status_heading = array(
    'status' => t("Status message"),
    'error' => t("Error message"),
    'warning' => t("Warning message"),
  );

  $header_class = 'html_tag-header messages-header';

  if (!isset($cf['is']['html5']) || $cf['is']['html5']) {
    $output .= '<header class="' . $header_class . '"><h2 class="html_tag-heading">' . t("Messages") . '</h2>' . '</header>';
  }
  else {
    $output .= '<div class=' . $header_class . '"><h2 class="html_tag-heading">' . t("Messages") . '</h2>' . '</div>';
  }

  $output .= '<div class="messages-wrapper">';

  foreach ($all_messages as $type => $messages) {
    $output .= '<div class="messages ' . $type . '" role="alert">';

    if (!empty($status_heading[$type])) {
      $output .= '<h3 class="element-invisible html_tag-heading">' . $status_heading[$type] . '</h3>';
    }

    if (count($messages) > 1) {
      $output .= ' <ul>';

      foreach ($messages as $message) {
        $output .= '  <li>' . $message . '</li>';
      }

      $output .= ' </ul>';
    }
    else {
      $output .= $messages[0];
    }

    $output .= '</div>';
  }

  if (!empty($vars['other']) && is_string($vars['other'])) {
    $output .= '<div class="messages-other">';
    $output .= $vars['other'];
    $output .= '</div>';
  }

  $output .= '</div>';

  return $output;
}

/**
 * Custom implementation of theme_breadcrumb().
 *
 * This removes the breadcrumb wrapper, requiring the theme to specify the
 * wrapper tag.
 *
 * @see theme_breadcrumb()
 */
function mcneese_breadcrumb($vars) {
  $breadcrumb = (array) $vars['breadcrumb'];
  $output = '';

  $breadcrumb[0] = '<a href="' . base_path() . '" class="link-home" title="Home">' . t("Home") . '</a>';

  $count = 0;
  $total = count($breadcrumb);

  foreach ($breadcrumb as $key => &$crumb) {
    $output .= '<li class="crumb">' . $crumb . '</li>';

    $count++;
    if ($count < $total) {
      $output .= '<div class="crumb-trail">»</div>';
    }
  }

  return $output;
}

/**
 * Custom implementation of hook_menu_tree().
 *
 * @see theme_menu_tree()
 */
function mcneese_menu_tree($vars) {
  $cf = & drupal_static('cf_theme_get_variables', array());

  $attributes = array();
  $attributes['class'] = array('menu');

  $menu_open = array('name' => 'nav', 'type' => 'semantic', 'attributes' => $attributes, 'html5' => $cf['is']['html5']);
  $menu_close = array('name' => 'nav', 'type' => 'semantic', 'open' => FALSE, 'html5' => $cf['is']['html5']);

  return theme('mcneese_tag', $menu_open) . '<ul class="navigation_list html_tag-list">' . $vars['tree'] . '</ul>' . theme('mcneese_tag', $menu_close);
}

/**
 * An internal function for rendering primary & secondary tabs.
 */
function mcneese_render_page_tabs() {
  $cf = & drupal_static('cf_theme_get_variables', array());
  $cf['show']['page']['menu_tabs'] = FALSE;

  if (!empty($cf['page']['menu_tabs']['tabs']['output'])) {
    $tabs = & $cf['page']['menu_tabs']['tabs'];

    if (isset($tabs['count']) && $tabs['count'] > 0) {
      $cf['show']['page']['menu_tabs'] = TRUE;
      $cf['data']['page']['menu_tabs'] = '';
      $count = 0;
      $even_odd = 'even';

      // prepend the menu_tabs-command-1 link
      $menu_tabs_text = (in_array('fixed', $cf['page']['tags']['mcneese_page_menu_tabs_open']['attributes']['class']) ? t("Menu Tabs") : "");
      $attributes = array();
      $attributes['class'] = array('tab', 'tab-command', 'tab-command-1');
      $cf['data']['page']['menu_tabs'] .= theme('list_item', array('markup' => '<a title="Collapse Menu Tabs" class="">' . $menu_tabs_text . '</a>', 'attributes' => $attributes));

      while ($count < $tabs['count']) {
        if (empty($tabs['output'][$count]['#link'])) {
          $count++;
          continue;
        }

        $link = & $tabs['output'][$count]['#link'];
        $attributes = array();
        $attributes['title'] = $link['title'];
        $attributes['class'] = array('tab', 'tab-' . $count, $even_odd);
        $markup = $link['title'];
        $even_odd = ($even_odd == 'even' ? 'odd' : 'even');
        $active = FALSE;

        if ($count == 0) {
          $attributes['class'][] = 'tab-first';
        }
        elseif ($count + 1 == $tabs['count']) {
          $attributes['class'][] = 'tab-last';
        }

        if (!empty($tabs['output'][$count]['#active'])) {
          // Add text to indicate active tab for non-visual users.
          $active = ' <span class="element-invisible">' . t("(active tab)") . '</span>';
          $attributes['class'][] = 'active';

          // If the link does not contain HTML already, check_plain() it now.
          // After we set 'html'=TRUE the link will not be sanitized by l().
          if (empty($link['localized_options']['html'])) {
            $link['title'] = check_plain($link['title']);
          }

          $link['localized_options']['html'] = TRUE;
          $markup = t("!local-tab-title!active", array('!local-tab-title' => $link['title'], '!active' => $active));
          $active = TRUE;
        }

        // add all secondary tabs immediately next to the primary tab
        if ($active) {
          $sub_tabs_markup = '';

          if (empty($cf['page']['sub_tabs']['tabs']['output'])) {
            $attributes['class'][] = 'leaf';
          }
          else {
            $sub_tabs = & $cf['page']['sub_tabs']['tabs'];

            if (isset($sub_tabs['count']) && $sub_tabs['count'] == 1) {
              if (isset($cf['is']['node-moderation']) && $cf['is']['node-moderation']) {
                $sub_tabs['count'] = 0;
              }
            }

            if (isset($sub_tabs['count']) && $sub_tabs['count'] > 0) {
              $attributes['class'][] = 'expanded';

              $sub_tabs_attributes = array('class' => array());
              $sub_tabs_attributes['class'][] = 'sub_tabs';
              $sub_tabs_markup = theme('mcneese_tag', array('name' => 'nav', 'type' => 'semantic', 'attributes' => $sub_tabs_attributes, 'html5' => $cf['is']['html5']));
              $sub_tabs_markup .= '<ul class="navigation_list html_tag-list">';

              if (isset($sub_tabs['count']) && $sub_tabs['count'] > 0) {
                $sub_count = 0;
                $sub_even_odd = 'even';

                while ($sub_count < $sub_tabs['count']) {
                  if (empty($sub_tabs['output'][$sub_count]['#link'])) {
                      $sub_count++;
                    continue;
                  }

                  $sub_link = & $sub_tabs['output'][$sub_count]['#link'];
                  $sub_attributes = array();
                  $sub_attributes['title'] = $sub_link['title'];
                  $sub_attributes['class'] = array('tab', 'tab-' . $sub_count, $sub_even_odd);
                  $sub_markup = $sub_link['title'];
                  $sub_even_odd = ($sub_even_odd == 'even' ? 'odd' : 'even');

                  if ($sub_count == 0) {
                    $sub_attributes['class'][] = 'tab-first';
                  }
                  elseif ($sub_count + 1 == $sub_tabs['count']) {
                    $sub_attributes['class'][] = 'tab-last';
                  }

                  if (!empty($sub_tabs['output'][$sub_count]['#active'])) {
                    // Add text to indicate active tab for non-visual users.
                    $active = ' <span class="element-invisible">' . t("(active tab)") . '</span>';
                    $sub_attributes['class'][] = 'active';

                    // If the link does not contain HTML already, check_plain() it now.
                    // After we set 'html'=TRUE the link will not be sanitized by l().
                    if (empty($sub_link['localized_options']['html'])) {
                      $sub_link['title'] = check_plain($sub_link['title']);
                    }

                    $sub_link['localized_options']['html'] = TRUE;
                    $sub_markup = t("!local-tab-title!active", array('!local-tab-title' => $sub_link['title'], '!active' => $active));
                  }

                  $sub_tabs_markup .= theme('list_item', array('markup' => l($sub_markup, $sub_link['href'], $sub_link['localized_options']), 'attributes' => $sub_attributes));

                  $sub_count++;
                }
              }

              $sub_tabs_markup .= '</ul>';
              $sub_tabs_markup .= theme('mcneese_tag', array('name' => 'nav', 'type' => 'semantic' , 'open' => FALSE, 'html5' => $cf['is']['html5']));
            }
          }

          $cf['data']['page']['menu_tabs'] .= theme('list_item', array('markup' => l($markup, $link['href'], $link['localized_options']) . $sub_tabs_markup, 'attributes' => $attributes));
        }
        else {
          $cf['data']['page']['menu_tabs'] .= theme('list_item', array('markup' => l($markup, $link['href'], $link['localized_options']), 'attributes' => $attributes));
        }

        $count++;
      }
    }
  }
}

/**
 * This updates the messages array for page content preprocessing.
 */
function mcneese_preprocess_page_prepare_messages(&$cf, &$vars) {
  $messages_sticky = 'relative';

  if (isset($cf['user']['object']->data['mcneese_settings']['region']['messages']['sticky'])) {
    $sticky = & $cf['user']['object']->data['mcneese_settings']['region']['messages']['sticky'];
    if ($sticky) {
      $messages_sticky = 'relative';

      if ($sticky == 'on' || $sticky == 'always') {
        $messages_sticky = 'relative';
      }
      else if ($sticky == 'off' || $sticky == 'never') {
        $messages_sticky = 'fixed';
      }
    }
    else {
      $messages_sticky = 'fixed';
    }
  }

  if (empty($vars['page']['messages'])) {
    $cf['data']['page']['messages'] = array();
  }
  else {
    $cf['data']['page']['messages']['blocks'] = $vars['page']['messages'];
    $cf['show']['page']['messages'] = TRUE;
    unset($vars['page']['messages']);
  }

  if (!empty($vars['messages'])) {
    if (empty($cf['page']['data']['messages']['raw'])) {
      $cf['data']['page']['messages']['raw'] = $vars['messages'];
    }
    else {
      $cf['data']['page']['messages']['raw'] = array_merge($cf['page']['data']['messages']['raw'], $vars['messages']);
    }

    $cf['show']['page']['messages'] = TRUE;
    unset($vars['messages']);
  }

  $attributes = array();
  $attributes['id'] = 'mcneese-messages';
  $attributes['class'] = array();
  $attributes['class'][] = 'noscript';
  $attributes['title'] = t("Messages");

  if ($cf['is']['html5']) {
    $attributes['class'][] = $messages_sticky;

    if ($messages_sticky == 'fixed') {
      $attributes['class'][] = 'collapsed';
      $attributes['tabindex'] = '2';
    }
    else {
      $attributes['class'][] = 'expanded';
    }
  }
  else {
    $attributes['class'][] = 'relative';
    $attributes['class'][] = 'expanded';
  }

  $cf['page']['tags']['mcneese_page_messages_open'] = array('name' => 'aside', 'type' => 'semantic', 'attributes' => $attributes, 'html5' => $cf['is']['html5']);
  $cf['page']['tags']['mcneese_page_messages_close'] = array('name' => 'aside', 'type' => 'semantic', 'open' => FALSE, 'html5' => $cf['is']['html5']);
}

/**
 * A semi-generic print function for writing cleaner code.
 *
 * Some of the content that is conditionally floating will appear in multiple
 * places in the code. This function is provided as a way to write that code
 * only once.
 *
 * This only prints known targets.
 *
 * @param array $cf
 *   The global common functionality data array.
 *   Is expected/assumed to be initialized and populated already.
 * @param array $target
 *   The machine-name of the html to print.
 * @param bool $fixed
 *   Designate whether or not the object is fixed.
 * @param bool $float_right
 *   Provided as a workaround for ie<8 lack of standards compliance.
 *   This tells the function that this is to increment and apply the float_right counter for ie8.
 */
function mcneese_do_print(&$cf, $target, $fixed = TRUE, $float_right = FALSE) {
  // workaround ie < 8 inability to follow css standards that are in use on the float-right block
  if ($fixed && $float_right && $cf['agent']['machine_name'] == 'ie' && $cf['agent']['major_version'] <= 8) {
    if (!isset($cf['ie8'])) {
      $cf['ie8'] = array();
    }

    if (!isset($cf['ie8']['float_right-counter'])) {
      $cf['ie8']['float_right-counter'] = 0;
    }
  }

  if ($target == 'messages') {
    if ($fixed === in_array('fixed', $cf['page']['tags']['mcneese_page_messages_open']['attributes']['class']) && $cf['show']['page']['messages']) {
      if ($fixed && $float_right && $cf['agent']['machine_name'] == 'ie' && $cf['agent']['major_version'] <= 8) {
        $cf['page']['tags']['mcneese_page_messages_open']['attributes']['class'][] = 'float_right-' . $cf['ie8']['float_right-counter'];
        $cf['ie8']['float_right-counter']++;
      }

      // do not display title for fixed regions.
      if ($fixed === FALSE) {
        unset($cf['page']['tags']['mcneese_page_messages_open']['attributes']['title']);
      }

      print(theme('mcneese_tag', $cf['page']['tags']['mcneese_page_messages_open']));
      print('<!--(begin-page-messages)-->');
      if (isset($cf['data']['page'][$target]['renderred'])) {
        print($cf['data']['page'][$target]['renderred']);
      }
      if (!empty($cf['data']['page'][$target]['blocks'])) {
        print(render($cf['data']['page'][$target]['blocks']));
      }
      print('<!--(end-page-messages)-->');
      print(theme('mcneese_tag', $cf['page']['tags']['mcneese_page_messages_close']));
    }
  }
  else if ($target == 'bulletin') {
    if ($fixed === in_array('fixed', $cf['page']['tags']['mcneese_page_' . $target . '_open']['attributes']['class']) && $cf['show']['page'][$target]) {
      if ($fixed && $float_right && $cf['agent']['machine_name'] == 'ie' && $cf['agent']['major_version'] <= 8) {
        $cf['page']['tags']['mcneese_page_' . $target . '_open']['attributes']['class'][] = 'float_right-' . $cf['ie8']['float_right-counter'];
        $cf['ie8']['float_right-counter']++;
      }

      // do not display title for fixed regions.
      if ($fixed === FALSE) {
        unset($cf['page']['tags']['mcneese_page_' . $target . '_open']['attributes']['title']);
      }

      print(theme('mcneese_tag', $cf['page']['tags']['mcneese_page_' . $target . '_open']));

      print(theme('mcneese_tag', $cf['page']['tags']['mcneese_page_' . $target . '_header_open']));
      print(theme('mcneese_tag', $cf['generic']['tags']['mcneese_hgroup_open']));
      print('<h2 class="html_tag-heading element-invisible">Bulletin</h2>');
      print(theme('mcneese_tag', $cf['generic']['tags']['mcneese_hgroup_close']));
      print(theme('mcneese_tag', $cf['page']['tags']['mcneese_page_' . $target . '_header_close']));

      print(theme('mcneese_tag', $cf['page']['tags']['mcneese_page_' . $target . '_wrapper_open']));
      print('<!--(begin-page-' . $target . ')-->');
      if (isset($cf['data']['page'][$target])) {
        print($cf['data']['page'][$target]);
      }
      print('<!--(end-page-' . $target . ')-->');
      print(theme('mcneese_tag', $cf['page']['tags']['mcneese_page_' . $target . '_wrapper_close']));

      print(theme('mcneese_tag', $cf['page']['tags']['mcneese_page_' . $target . '_close']));
    }
  }
  else if ($target == 'help' || $target == 'information') {
    if ($target == 'help') {
      $friendly = 'Help';
    }
    else if ($target == 'information') {
      $friendly = 'Information';
    }

    // do not display title for fixed regions.
    if ($fixed === FALSE) {
      unset($cf['page']['tags']['mcneese_page_' . $target . '_open']['attributes']['title']);
    }

    if ($fixed === in_array('fixed', $cf['page']['tags']['mcneese_page_' . $target . '_open']['attributes']['class']) && $cf['show']['page'][$target]) {
      if ($fixed && $float_right && $cf['agent']['machine_name'] == 'ie' && $cf['agent']['major_version'] <= 8) {
        $cf['page']['tags']['mcneese_page_' . $target . '_open']['attributes']['class'][] = 'float_right-' . $cf['ie8']['float_right-counter'];
        $cf['ie8']['float_right-counter']++;
      }

      print(theme('mcneese_tag', $cf['page']['tags']['mcneese_page_' . $target . '_open']));

      print(theme('mcneese_tag', $cf['page']['tags']['mcneese_page_' . $target . '_header_open']));
      print(theme('mcneese_tag', $cf['generic']['tags']['mcneese_hgroup_open']));
      print('<h2 class="html_tag-heading">' . $friendly . '</h2>');
      print(theme('mcneese_tag', $cf['generic']['tags']['mcneese_hgroup_close']));
      print(theme('mcneese_tag', $cf['page']['tags']['mcneese_page_' . $target . '_header_close']));

      print(theme('mcneese_tag', $cf['page']['tags']['mcneese_page_' . $target . '_wrapper_open']));
      print('<!--(begin-page-' . $target . ')-->');
      if (isset($cf['data']['page'][$target])) {
        print($cf['data']['page'][$target]);
      }
      print('<!--(end-page-' . $target . ')-->');
      print(theme('mcneese_tag', $cf['page']['tags']['mcneese_page_' . $target . '_wrapper_close']));

      print(theme('mcneese_tag', $cf['page']['tags']['mcneese_page_' . $target . '_close']));
    }
  }
  else if ($target == 'work_area_menu') {
    if ($fixed === in_array('fixed', $cf['page']['tags']['mcneese_page_work_area_menu_open']['attributes']['class']) && $cf['show']['page']['work_area_menu']) {
      if ($fixed && $float_right && $cf['agent']['machine_name'] == 'ie' && $cf['agent']['major_version'] <= 8) {
        $cf['page']['tags']['mcneese_page_work_area_menu_open']['attributes']['class'][] = 'float_right-' . $cf['ie8']['float_right-counter'];
        $cf['ie8']['float_right-counter']++;
      }

      // do not display title for fixed regions.
      if ($fixed === FALSE) {
        unset($cf['page']['tags']['mcneese_page_' . $target . '_open']['attributes']['title']);
      }

      print(theme('mcneese_tag', $cf['page']['tags']['mcneese_page_work_area_menu_open']));

      print(theme('mcneese_tag', $cf['generic']['tags']['mcneese_header_open']));
      print(theme('mcneese_tag', $cf['generic']['tags']['mcneese_hgroup_open']));
      print('<h2 class="html_tag-heading">Work Area Menu</h2>');
      print(theme('mcneese_tag', $cf['generic']['tags']['mcneese_hgroup_close']));
      print(theme('mcneese_tag', $cf['generic']['tags']['mcneese_header_close']));

      print('<ul class="navigation_list html_tag-list">');
      print('  <!--(begin-page-work_area_menu)-->');

      if ($cf['data']['page']['work_area_menu']['page_width-toggle']) {
        print('  <li class="html_tag-list_item"><a id="mcneese-work_area_menu-page_width" class="' . $cf['data']['page']['work_area_menu']['page_width'] . '" title="Toggle Page Width">Toggle Page Width</a></li>');
      }

      print('  <!--(end-page-work_area_menu)-->');
      print('</ul>');

      print(theme('mcneese_tag', $cf['page']['tags']['mcneese_page_work_area_menu_close']));
    }
  }
  else if ($target == 'menu_tabs' || $target == 'action_links') {
    if ($target == 'menu_tabs') {
      $friendly = 'Menu Tabs';
    }
    else if ($target == 'action_links') {
      $friendly = 'Action Links';
    }

    if ($fixed === in_array('fixed', $cf['page']['tags']['mcneese_page_' . $target . '_open']['attributes']['class']) && $cf['show']['page'][$target]) {
      if ($fixed && $float_right && $cf['agent']['machine_name'] == 'ie' && $cf['agent']['major_version'] <= 8) {
        $cf['page']['tags']['mcneese_page_' . $target . '_open']['attributes']['class'][] = 'float_right-' . $cf['ie8']['float_right-counter'];
        $cf['ie8']['float_right-counter']++;
      }

      // do not display title for fixed regions.
      if ($fixed === FALSE) {
        unset($cf['page']['tags']['mcneese_page_' . $target . '_open']['attributes']['title']);
      }

      print(theme('mcneese_tag', $cf['page']['tags']['mcneese_page_' . $target . '_open']));

      print(theme('mcneese_tag', $cf['generic']['tags']['mcneese_header_open']));
      print(theme('mcneese_tag', $cf['generic']['tags']['mcneese_hgroup_open']));
      print('<h2 class="html_tag-heading">' . $friendly . '</h2>');
      print(theme('mcneese_tag', $cf['generic']['tags']['mcneese_hgroup_close']));
      print(theme('mcneese_tag', $cf['generic']['tags']['mcneese_header_close']));

      print('<!--(begin-page-' . $target . ')-->');
      print('  <ul class="navigation_list html_tag-list">');
      if (isset($cf['data']['page'][$target])) {
        print($cf['data']['page'][$target]);
      }
      print('  </ul>');
      print('<!--(end-page-' . $target . ')-->');

      print(theme('mcneese_tag', $cf['page']['tags']['mcneese_page_' . $target . '_close']));
    }
  }
  else if ($target == 'breadcrumb') {
    if ($fixed === in_array('fixed', $cf['page']['tags']['mcneese_page_' . $target . '_open']['attributes']['class'])) {
      if ($cf['show']['page']['breadcrumb'] || $cf['show']['page']['precrumb'] || $cf['show']['page']['postcrumb']) {
        if ($fixed && $float_right && $cf['agent']['machine_name'] == 'ie' && $cf['agent']['major_version'] <= 8) {
          $cf['page']['tags']['mcneese_page_' . $target . '_open']['attributes']['class'][] = 'float_right-' . $cf['ie8']['float_right-counter'];
          $cf['ie8']['float_right-counter']++;
        }

        // do not display title for fixed regions.
        if ($fixed === FALSE) {
          unset($cf['page']['tags']['mcneese_page_' . $target . '_open']['attributes']['title']);
        }

        print(theme('mcneese_tag', $cf['page']['tags']['mcneese_page_' . $target . '_open']));

        print(theme('mcneese_tag', $cf['generic']['tags']['mcneese_header_open']));
        print(theme('mcneese_tag', $cf['generic']['tags']['mcneese_hgroup_open']));
        print('<h2 class="html_tag-heading">Breadcrumb</h2>');
        print(theme('mcneese_tag', $cf['generic']['tags']['mcneese_hgroup_close']));
        print(theme('mcneese_tag', $cf['generic']['tags']['mcneese_header_close']));

        print('<!--(begin-page-' . $target . ')-->');
        print('  <ul class="navigation_list html_tag-list">');

        if ($cf['show']['page']['precrumb']) {
          print('<!--(begin-page-precrumb)-->');
          print($cf['data']['page']['precrumb']);
          print('<!--(end-page-precrumb)-->');
        }

        if ($cf['show']['page']['breadcrumb']) {
          print('<!--(begin-page-breadcrumb)-->');
          print($cf['data']['page']['breadcrumb']);
          print('<!--(end-page-breadcrumb)-->');
        }

        if ($cf['show']['page']['postcrumb']) {
          print('<!--(begin-page-postcrumb)-->');
          print($cf['data']['page']['postcrumb']);
          print('<!--(end-page-postcrumb)-->');
        }

        print('  </ul>');
        print('<!--(end-page-' . $target . ')-->');

        print(theme('mcneese_tag', $cf['page']['tags']['mcneese_page_' . $target . '_close']));
      }
    }
  }
  else if ($target == 'side') {
    if ($fixed === in_array('fixed', $cf['page']['tags']['mcneese_page_' . $target . '_open']['attributes']['class'])) {
      if ($fixed && $float_right && $cf['agent']['machine_name'] == 'ie' && $cf['agent']['major_version'] <= 8) {
        $cf['page']['tags']['mcneese_page_' . $target . '_open']['attributes']['class'][] = 'float_right-' . $cf['ie8']['float_right-counter'];
        $cf['ie8']['float_right-counter']++;
      }

      if ($cf['show']['page']['menus'] || $cf['show']['page']['asides']) {
        // do not display title for fixed regions.
        if ($fixed === FALSE) {
          unset($cf['page']['tags']['mcneese_page_' . $target . '_open']['attributes']['title']);
        }

        print(theme('mcneese_tag', $cf['page']['tags']['mcneese_page_' . $target . '_open']));
        print('<div class="side_panel-simulated_heading">Side Panel</div>');

        print('<!--(begin-page-' . $target . ')-->');

        if ($cf['show']['page']['menus']) {
          print($cf['data']['page']['menus']);
        }

        if ($cf['show']['page']['asides']) {
          print($cf['data']['page']['asides']);
        }

        print('<!--(end-page-' . $target . ')-->');
        print(theme('mcneese_tag', $cf['page']['tags']['mcneese_page_' . $target . '_close']));
      }
    }
  }
  else if ($target == 'page_header') {
    if ($cf['show']['page']['header']) {
      print(theme('mcneese_tag', $cf['page']['tags']['mcneese_page_header_open']));
      print('<!--(begin-page-header)-->');
      print('<div class="header-section header-top">');

      if ($cf['show']['page']['logo']) {
        print('<div id="mcneese-site-logo"><a href="' .  $cf['data']['page']['logo']['href'] . '" class="site-logo" title="' . $cf['data']['page']['logo']['alt'] . '" role="img">' . $cf['data']['page']['logo']['alt'] . '</a></div>');
      }

      print($cf['data']['page']['header']);

      if ($cf['show']['page']['header_menu_1']) {
        print('<div class="header-menu header-menu-1" role="navigation">');
        print($cf['data']['page']['header_menu_1']);
        print('</div>');
      }

      print('</div>');
      print('<div class="header-separator"></div>');
      print('<div class="header-section header-bottom">');

      if ($cf['show']['page']['header_menu_2']) {
        print('<div class="header-menu header-menu-2" role="navigation">');
        print($cf['data']['page']['header_menu_2']);
        print('</div>');
      }

      print('</div>');
      print('<!--(end-page-header)-->');
      print(theme('mcneese_tag', $cf['page']['tags']['mcneese_page_header_close']));
    }
  }
  else if ($target == 'page_title') {
    if ($cf['show']['page']['title']) {
      print(theme('mcneese_tag', $cf['page']['tags']['mcneese_page_title_open']));
      print(theme('mcneese_tag', $cf['generic']['tags']['mcneese_hgroup_open']));
      print('<!--(begin-page-title)-->');

      if ($cf['show']['page']['title_prefix']) {
        print($cf['data']['page']['title_prefix']);
      }

      print('<h1 class="page-title html_tag-heading">' . $cf['data']['page']['title'] . '</h1>');

      if ($cf['show']['page']['title_suffix']) {
        print($cf['data']['page']['title_suffix']);
      }

      print('<!--(end-page-title)-->');
      print(theme('mcneese_tag', $cf['generic']['tags']['mcneese_hgroup_close']));
      print(theme('mcneese_tag', $cf['page']['tags']['mcneese_page_title_close']));
    }
  }
  else if ($target == 'watermarks-pre' || $target == 'watermarks-post') {
    if ($cf['show']['page'][$target]) {
      print('<div id="mcneese-page-' . $target . '">');
      if (isset($cf['data']['page'][$target])) {
        print($cf['data']['page'][$target]);
      }
      print('</div>');
    }
  }
  else if ($target == 'page_footer') {
    if ($cf['show']['page']['footer']) {
      print(theme('mcneese_tag', $cf['page']['tags']['mcneese_page_footer_open']));
      print('<!--(begin-page-footer)-->');

      if (isset($cf['data']['page']['footer'])) {
        print($cf['data']['page']['footer']);
      }

      print('<!--(end-page-footer)-->');
      print(theme('mcneese_tag', $cf['page']['tags']['mcneese_page_footer_close']));
    }
  }
  else if ($target == 'top') {
    if ($cf['show']['page']['top']) {
      if (isset($cf['data']['page']['top'])) {
        print($cf['data']['page']['top']);
      }
    }
  }
  else if ($target == 'bottom') {
    if ($cf['show']['page']['bottom']) {
      if (isset($cf['data']['page']['bottom'])) {
        print($cf['data']['page']['bottom']);
      }
    }
  }
}

/**
 * Build the maintenance mode array data.
 *
 * This is intended to be called from within a preprocess functions scope.
 *
 * @param array $cf
 *   The global common functionality data array.
 *   Is expected/assumed to be initialized and populated already.
 * @param array $vars
 *   The variables array from the preprocess functions.
 */
function mcneese_prepare_maintenance_mode_variables(&$cf, &$vars) {
  if (function_exists('user_access')) {
    $cf['is_data']['maintenance']['access'] = user_access('access site in maintenance mode');
  }
  else {
    $cf['is_data']['maintenance']['access'] = FALSE;
  }

  $date_value = strtotime('+1800 seconds', $cf['request']);
  $cf['meta']['name']['expires'] = gmdate('D, d M Y H:i:s T', $date_value);
  $cf['meta']['http-equiv']['expires'] = gmdate('D, d M Y H:i:s T', $date_value);
  $cf['meta']['http-equiv']['cache-control'] = 'no-cache, must-revalidate';


  // register that this is a maintenance page
  $cf['is_data']['maintenance']['vars'] = &$vars;
}

/**
 * Removes the generated toolbar css styles.
 *
 * @param array $cf
 *   The global common functionality data array.
 *   Is expected/assumed to be initialized and populated already.
 */
function mcneese_remove_toolbar_css(&$cf) {
  $cf['markup_css']['body']['class'] = preg_replace('/ is-toolbar(-(\w|-|_)+|\b)/i', '', $cf['markup_css']['body']['class']);

  $cf['is']['toolbar'] = FALSE;
  $cf['is']['toolbar-expanded'] = FALSE;
  $cf['is']['toolbar-collapsed'] = FALSE;
  $cf['is']['toolbar-sticky'] = FALSE;
  $cf['is']['toolbar-fixed'] = FALSE;
  $cf['is']['toolbar-relative'] = FALSE;
  $cf['is']['toolbar-autoshow'] = FALSE;
  $cf['is']['toolbar-autohide'] = FALSE;
  $cf['is']['toolbar-shortcuts-expanded'] = FALSE;
  $cf['is']['toolbar-shortcuts-collapsed'] = FALSE;
}


/**
 * @} End of '@defgroup mcneese McNeese - Base Theme'.
 */

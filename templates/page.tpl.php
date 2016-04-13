<?php
/**
 * @file
 * Page theme implementation.
 */
  $cf = & drupal_static('cf_theme_get_variables', array());

  mcneese_render_page();
  mfcs_render_page();

  if (!isset($mcneese_bulletin_mode)) {
    $mcneese_bulletin_mode = NULL;
  }

  if (isset($cf['show']['page']['title']) && !$cf['show']['page']['title']) {
    $mcneese_bulletin_mode = NULL;
  }

  $bulletin_css = '';
  if (!is_null($mcneese_bulletin_mode)) {
    $bulletin_css = ' no_additional';
    if (!empty($cf['data']['page']['bulletin'])) {
      $bulletin_css = ' additional';
    }
  }

  $float_side = in_array('fixed', $cf['page']['tags']['mcneese_page_side_open']['attributes']['class']);
  $split_page = !$float_side && ($cf['show']['page']['menus'] || $cf['show']['page']['asides']);


  // page header
  mcneese_do_print($cf, 'page_header');


  // bulletin
  if (!is_null($mcneese_bulletin_mode)) {
    print('<div id="mcneese-bulletin-wrapper-outer" class="');
    print($bulletin_css);
    print('">');
    print('<div id="mcneese-bulletin-wrapper" class="');
    print($bulletin_css);
    print('">');
    print('<div id="mcneese-bulletin-wrapper-inner" class="');
    print($bulletin_css);
    print('">');
    print('<div id="mcneese-bulletin-page_title" class="');
    print($bulletin_css);
    print('">');
    mcneese_do_print($cf, 'page_title');
    print('</div>');
    print('<div id="mcneese-bulletin-content" class="');
    print($bulletin_css);
    print('">');
    mcneese_do_print($cf, 'bulletin', FALSE);
    print('</div>');
    print('</div>');
    print('</div>');
    print('</div>');
  }


  // float regions - right
  print('<div id="mcneese-float-right" class="expanded fixed">');
  mcneese_do_print($cf, 'messages', TRUE, TRUE);
  mcneese_do_print($cf, 'help', TRUE, TRUE);
  mcneese_do_print($cf, 'information', TRUE, TRUE);
  mcneese_do_print($cf, 'work_area_menu', TRUE, TRUE);
  print('</div>');


  // information and control regions
  mcneese_do_print($cf, 'help', FALSE);
  mcneese_do_print($cf, 'information', FALSE);
  mcneese_do_print($cf, 'menu_tabs', FALSE);


  // main content
  if ($split_page) {
    print('<div id="mcneese-page-content" class="mcneese-content split" role="main">');
    mcneese_do_print($cf, 'breadcrumb', FALSE);
    mcneese_do_print($cf, 'messages', FALSE);
    mcneese_do_print($cf, 'side', FALSE);

    print('<div class="column-2">');
  } else {
    print('<div id="mcneese-page-content" class="mcneese-content full" role="main">');
    mcneese_do_print($cf, 'breadcrumb', FALSE);
    mcneese_do_print($cf, 'messages', FALSE);
  }

  if (is_null($mcneese_bulletin_mode)) {
    mcneese_do_print($cf, 'page_title');
  }

  // float regions - left
  print('<div id="mcneese-float-left" class="expanded fixed">');
  mcneese_do_print($cf, 'menu_tabs');
  mcneese_do_print($cf, 'action_links');
  mcneese_do_print($cf, 'breadcrumb');
  mcneese_do_print($cf, 'side');
  print('</div>');

  // other regions - first
  mcneese_do_print($cf, 'action_links', FALSE);
  mcneese_do_print($cf, 'watermarks-pre');

  // main content - inner
  print('<div id="mcneese-content-main" class="mcneese-content-main" role="main">');
  print('<!--(begin-page-main)-->');
  if ($cf['show']['page']['content']) {
    print($cf['data']['page']['content']);
  }
  print('<!--(end-page-main)-->');
  print('</div>');

  // other regions - last
  mcneese_do_print($cf, 'watermarks-post');

  if ($split_page) {
    print('</div>');
    print('</div>');
  } else {
    print('</div>');
  }

  mcneese_do_print($cf, 'page_footer');

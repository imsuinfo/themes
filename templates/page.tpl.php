<?php
/**
 * @file
 * Page theme implementation.
 */
  $cf = & drupal_static('cf_theme_get_variables', array());

  mcneese_render_page();
  mcneese_www_render_page();

  $float_side = in_array('fixed', $cf['page']['tags']['mcneese_page_side_open']['attributes']['class']);
  $split_page = !$float_side && ($cf['show']['page']['menus'] || $cf['show']['page']['asides']);

  $group_image_width = '';
  if (!empty($cf['data']['page']['group_image']['width'])) {
    $group_image_width = ' width="' . $cf['data']['page']['group_image']['width'] . '"';
  }
  $group_image_height = '';
  if (!empty($cf['data']['page']['group_image']['height'])) {
    $group_image_height = ' height="' . $cf['data']['page']['group_image']['height'] . '"';
  }

  if ($cf['is']['unsupported']) {
    print('<div id="mcneese-unsupported-message">');
    print($cf['is_data']['unsupported']['message']);
    print('</div>');
  }

  mcneese_do_print($cf, 'page_header');
  mcneese_do_print($cf, 'messages', FALSE);
  mcneese_do_print($cf, 'help', FALSE);
  mcneese_do_print($cf, 'information', FALSE);

  print('<div id="mcneese-float-right" class="expanded fixed">');
  mcneese_do_print($cf, 'messages', TRUE, TRUE);
  mcneese_do_print($cf, 'help', TRUE, TRUE);
  mcneese_do_print($cf, 'information', TRUE, TRUE);
  mcneese_do_print($cf, 'work_area_menu', TRUE, TRUE);
  print('</div>');

  if ($mcneese_bulletin_mode == 1) {
    mcneese_do_print($cf, 'menu_tabs', FALSE);

    print('<div id="mcneese-bulletin-wrapper-outer">');
    print('<div id="mcneese-bulletin-wrapper">');
    print('<div id="mcneese-bulletin-wrapper-inner">');
    print('<div id="mcneese-bulletin-page_title">');
    mcneese_do_print($cf, 'page_title');
    print('</div>');

    print('<div id="mcneese-bulletin-content">');
    mcneese_do_print($cf, 'bulletin', FALSE);
    print('</div>');
    print('</div>');
    print('</div>');
    print('</div>');
  } else {
    mcneese_do_print($cf, 'bulletin', FALSE);
  }

  if ($split_page) {
    print('<div id="mcneese-page-content" class="mcneese-content split" role="main">');
    mcneese_do_print($cf, 'side', FALSE);

    print('<div class="column-2">');
  } else {
    print('<div id="mcneese-page-content" class="mcneese-content full" role="main">');
  }

  if ($cf['show']['page']['group_image']) {
    print('<!--(begin-page-group_image)-->');
    print('<img usemap="#group_image_map" class="');
    print($cf['data']['page']['group_image']['class']);
    print('" title="');
    print($cf['data']['page']['group_image']['title']);
    print('" alt="');
    print($cf['data']['page']['group_image']['alt']);
    print('" src="');
    print($cf['data']['page']['group_image']['src']);
    print('"');
    print($group_image_width . $group_image_height);
    print(' other="');
    print($cf['data']['page']['group_image']['other']);
    print('">');

    print('<!--(end-page-group_image)-->');
  }

  if (is_null($mcneese_bulletin_mode)) {
    mcneese_do_print($cf, 'page_title');
  }

  print('<div id="mcneese-float-left" class="expanded fixed">');
  mcneese_do_print($cf, 'menu_tabs');
  mcneese_do_print($cf, 'action_links');

  if (!$cf['is']['front']) {
    mcneese_do_print($cf, 'breadcrumb');
  }

  mcneese_do_print($cf, 'side');

  if ($cf['show']['page']['document_outline']) {
    print(theme('mcneese_tag', $cf['page']['tags']['mcneese_www_document_outline_open']));
    print('<!--(begin-page-document-outline)-->');
    print(theme('mcneese_tag', $cf['generic']['tags']['mcneese_header_open']));
    print(theme('mcneese_tag', $cf['generic']['tags']['mcneese_hgroup_open']));
    print('<h2 class="html_tag-heading">Outline</h2>');
    print(theme('mcneese_tag', $cf['generic']['tags']['mcneese_hgroup_close']));
    print(theme('mcneese_tag', $cf['generic']['tags']['mcneese_header_close']));

    print('<ul class="navigation_list html_tag-list">');
    print($cf['data']['page']['document_outline']['markup']);
    print('</ul>');
    print('<!--(end-page-document-outline)-->');
    print(theme('mcneese_tag', $cf['page']['tags']['mcneese_www_document_outline_close']));
  }
  print('</div>');

  if (is_null($mcneese_bulletin_mode)) {
    mcneese_do_print($cf, 'menu_tabs', FALSE);
  }

  if (!$cf['is']['front']) {
    mcneese_do_print($cf, 'breadcrumb', FALSE);
  }

  mcneese_do_print($cf, 'action_links', FALSE);
  mcneese_do_print($cf, 'watermarks-pre');

  print('<div id="mcneese-content-main" class="mcneese-content-main" role="main">');
  print('<!--(begin-page-main)-->');
  if ($cf['show']['page']['content']) {
    if ($cf['show']['page']['document_header']) {
      print(theme('mcneese_tag', $cf['page']['tags']['mcneese_www_document_header_open']));
      print(theme('mcneese_tag', $cf['generic']['tags']['mcneese_hgroup_open']));
      print('<!--(begin-page-document-header)-->');
      print($cf['data']['page']['document_header']['markup']);
      print('<!--(end-page-document-header)-->');
      print(theme('mcneese_tag', $cf['generic']['tags']['mcneese_hgroup_close']));
      print(theme('mcneese_tag', $cf['page']['tags']['mcneese_www_document_header_close']));
    }

    print($cf['data']['page']['content']);

    if ($cf['show']['page']['document_footer']) {
      print(theme('mcneese_tag', $cf['page']['tags']['mcneese_www_document_footer_open']));
      print('<!--(begin-page-document-footer)-->');
      print($cf['data']['page']['document_footer']['markup']);
      print('<!--(end-page-document-footer)-->');
      print(theme('mcneese_tag', $cf['page']['tags']['mcneese_www_document_footer_close']));
    }
  }
  print('<!--(end-page-main)-->');
  print('</div>');

  mcneese_do_print($cf, 'watermarks-post');

  if ($split_page) {
    print('</div>');
    print('</div>');
  } else {
    print('</div>');
  }

  mcneese_do_print($cf, 'page_footer');

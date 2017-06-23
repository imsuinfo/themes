<?php
/**
 * @file
 * Advanced Help Popup theme implementation.
 */
  $cf = & drupal_static('cf_theme_get_variables', array());

  if (function_exists('cf_theme_generate_headers')) {
    $cf['headers'] = cf_theme_generate_headers($cf);
  }

  mcneese_render_page();

  // define these variables for mcneese_do_print().
  $cf['data']['page']['title'] = $title;

  print($cf['agent']['doctype']);

  print('<html lang="');
  print($language->language);
  print('" dir="');
  print $language->dir;
  print('"');
  if ($cf['show']['html']['rdf_namespaces']) print($cf['data']['html']['rdf_namespaces']);
  print('>');
  print('<head>');
  print('<!--(begin-head)-->');
  print($head);
  print($cf['headers']);
  print('<title>');
  print($head_title);
  print('</title>');
  print($styles);
  print($scripts);
  print('<!--(end-head)-->');
  print('</head>');

  print('<body class="mcneese mcneese-advanced_help_popup ');
  print($cf['markup_css']['body']['class']);
  print('" ');
  print($attributes);
  print('>');
  print('<!--(begin-body)-->');
  if (!$cf['is']['overlay'] && $cf['show']['skipnav']){
    print('<!--(begin-skipnav)-->');
    print('<span id="mcneese-skip_nav-container" class="mcneese-skip_nav-container" role="navigation">');
    print('<a id="mcneese-skip_nav" class="mcneese-skip_nav" href="#mcneese-content-main">');
    print("Skip to main content");
    print('</a></span>');
    print('<!--(end-skipnav)-->');
  }

  print('<div id="mcneese-page" class="mcneese-page">');
  print('<!--(begin-page)-->');
  print('<div id="mcneese-page-content" class="mcneese-content full" role="main">');
  print(theme('mcneese_tag', $cf['page']['tags']['mcneese_page_title_open']));
  print('<!--(begin-advanced_help_popup-header)-->');
  mcneese_do_print($cf, 'page_title', FALSE);
  print('<!--(end-advanced_help_popup-header)-->');
  print(theme('mcneese_tag', $cf['page']['tags']['mcneese_page_title_close']));

  mcneese_do_print($cf, 'messages', FALSE);

  print('<div id="mcneese-content-main" class="mcneese-content-main" role="main">');
  print('<!--(begin-advanced_help_popup-content)-->');
  print($content);
  print('<!--(end-advanced_help_popup-content)-->');
  print('</div>');

  print($closure);
  print('</div>');
  print('<!--(end-page)-->');
  print('</div>');
  print('<!--(end-body)-->');
  print('</body>');
  print('</html>');

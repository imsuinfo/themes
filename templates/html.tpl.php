<?php
/**<?php
 * @file
 * Html theme implementation.
 */
  $cf = & drupal_static('cf_theme_get_variables', array());
  global $base_root;
  $uri = request_uri();

  if (function_exists('cf_theme_generate_headers')) {
    $cf['headers'] = cf_theme_generate_headers($cf);
  }

  print($cf['agent']['doctype'] . "\n");

  print('<html lang="');
  print($language->language);
  print('" dir="');
  print($language->dir);
  print('"');
  if ($cf['show']['html']['rdf_namespaces']) {
    print($cf['data']['html']['rdf_namespaces']);
  }
  print('>');

  print('<head><!--(begin-head)-->');
  print($head);
  print($cf['headers']);

  print('<title>');
  print($head_title);
  print('</title>');

  print('<base href="' . $base_root . $uri . '">');
  print('<meta name="viewport" content="width=device-width, initial-scale=1">');

  print($styles);

  print('<script type="text/javascript">');
  print('  // This script detects whether or not javascript is enabled and if it does, removes the no-script from the body class.' . "\n");
  print('  // This allows for CSS code to react to whether or not javascript is enabled.' . "\n");
  print('  function mcneese_html_body_javascript_detection() {' . "\n");
  print('    document.body.removeAttribute(\'onLoad\');' . "\n");
  print('    document.body.className = document.body.className.replace(/\bno-script\b/i, \'script\');' . "\n");
  print('  }' . "\n");
  print('</script>');

  print($scripts);
  print('<!--(end-head)-->');
  print('</head>');

  print('<body id="mcneese-body" class="mcneese no-script ');
  print($cf['markup_css']['body']['class']);
  print('" ');
  print($attributes);
  print(' onload="mcneese_html_body_javascript_detection();">');

  if (!$cf['is']['overlay'] && $cf['show']['skipnav']) {
    print('<!--(begin-skipnav)-->');
    print('<span id="mcneese-skip_nav-container" class="mcneese-skip_nav-container" role="navigation">');
    print('<a id="mcneese-skip_nav" class="mcneese-skip_nav" href="#mcneese-content-main">');
    print("Skip to main content");
    print('</a></span>');
    print('<!--(end-skipnav)-->');
  }

  print('<!--(begin-body)-->');
  print('<div id="mcneese-top" class="mcneese-top">');
  print('<!--(begin-page_top)-->');
  if (isset($page_top)) print($page_top);
  mcneese_do_print($cf, 'top');
  print('<!--(end-page_top)-->');
  print('</div>');

  print('<div id="mcneese-page" class="mcneese-page">');
  print('<!--(begin-page)-->');
  if (isset($page)) print($page);
  print('<!--(end-page)-->');
  print('</div>');

  print('<div id="mcneese-bottom" class="mcneese-bottom">');
  print('<!--(begin-page_bottom)-->');
  mcneese_do_print($cf, 'bottom');
  if (isset($page_bottom)) print($page_bottom);
  print('<!--(end-page_bottom)-->');
  print('</div>');

  print('<!--(end-body)-->');
  print('</body></html>');

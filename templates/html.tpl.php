<?php
/**
 * @file
 * Html theme implementation.
 */
  $cf = & drupal_static('cf_theme_get_variables', array());
  global $base_url;

  if (!isset($cf['generic']['tags'])) {
    mcneese_initialize_generic_tags($cf);
  }

  if (function_exists('cf_theme_generate_headers')) {
    $cf['headers'] = cf_theme_generate_headers($cf);
  }

  $stp = base_path() . drupal_get_path('theme', 'mcneese_www');

  print($cf['agent']['doctype'] . "\n");

  print('<html lang="');
  print($language->language);
  print('" dir="');
  print $language->dir;
  print('"');
  if ($cf['show']['html']['rdf_namespaces']) print($cf['data']['html']['rdf_namespaces']);
  print('>');
  print('<head>');
  print('<!--(begin-head)-->');
  print('<base href="' . $base_url . '">');
  print($head . "\n");
  print($cf['headers'] . "\n");
  print('<title>');
  print($head_title);
  print('</title>');
  print('<meta name="viewport" content="width=device-width, initial-scale=1">');
  print($styles . "\n");
  print('<script type="text/javascript">');
  print('// This script detects whether or not javascript is enabled and if it does, removes the no-script from the body class.' . "\n");
  print('// This allows for CSS code to react to whether or not javascript is enabled.' . "\n");
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
  if (!$cf['is']['overlay'] && $cf['show']['skipnav']){
    print('<!--(begin-skipnav)-->');
    print('<a id="mcneese-skip_nav" class="mcneese-skip_nav" href="#mcneese-content-main" role="navigation">');
    print("Skip to main content");
    print('</a>');
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

  print(theme('mcneese_tag', $cf['html']['tags']['mcneese_www_html_footer_open']));
  print('<!--(begin-www-footer)-->');
  print(theme('mcneese_tag', $cf['html']['tags']['mcneese_www_html_footer_heading_open']));
  print('<h2>Website Footer</h2>');
  print(theme('mcneese_tag', $cf['html']['tags']['mcneese_www_html_footer_heading_close']));

  print('<div class="columns columns-right">');
  print('<div class="column column-1">');
  print('<img src="');
  print($stp);
  print('/images/footer-columns-right.png" alt="" width="3" height="169">');
  print('</div>');

  print('<div class="column column-2">');
  print(theme('mcneese_tag', $cf['html']['tags']['mcneese_www_html_footer_column_heading_open']));
  print('<h3 class="column-header">Contact Information</h3>');
  print(theme('mcneese_tag', $cf['html']['tags']['mcneese_www_html_footer_column_heading_close']));
  print('<ul>');
  print('<li>Campus: 4205 Ryan Street</li>');
  print('<li>Lake Charles, LA</li>');
  print('<li>Tel: 337-475-5000,</li>');
  print('<li>or 800.622.3352</li>');
  print('</ul>');
  print('</div>');

  print('<div class="column column-3">');
  print(theme('mcneese_tag', $cf['html']['tags']['mcneese_www_html_footer_column_heading_open']));
  print('<h3 class="column-header">Map &amp; Directions</h3>');
  print(theme('mcneese_tag', $cf['html']['tags']['mcneese_www_html_footer_column_heading_close']));
  print('<ul>');
  print('<li>');
  print('<a href="http://maps.google.com/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=&amp;q=McNeese+State+University,+Ryan+Street,+Lake+Charles,+LA&amp;aq=0&amp;sll=37.0625,-95.677068&amp;sspn=52.107327,76.992187&amp;ie=UTF8&amp;hq=McNeese+State+University,+Ryan+Street,+Lake+Charles,+LA&amp;z=15&amp;iwloc=A&amp;ved=0CDQQpQY&amp;sa=X&amp;ei=uX8kTpXcK5OSsAOqs6nZAw" title="Google Map of McNeese State University">');
  print('<img alt="Snippet of Google Map for Campus" src="' . $stp . '/images/footer_map.png">');
  print('</a>');
  print('</li>');
  print('</ul>');
  print('</div>');
  print('</div>');

  print('<div class="columns columns-left">');
  print('<div class="column column-1">');
  print(theme('mcneese_tag', $cf['html']['tags']['mcneese_www_html_footer_column_heading_open']));
  print('<h3 class="column-header"><a href="/node/5683" title="Application">Apply Now</a></h3>');
  print(theme('mcneese_tag', $cf['html']['tags']['mcneese_www_html_footer_column_heading_close']));
  print('</div>');

  print('<div class="column column-2">');
  print(theme('mcneese_tag', $cf['html']['tags']['mcneese_www_html_footer_column_heading_open']));
  print('<h3 class="column-header">Courses</h3>');
  print(theme('mcneese_tag', $cf['html']['tags']['mcneese_www_html_footer_column_heading_close']));
  print('<ul>');
  print('<li><a href="/node/1117">Catalog</a></li>');
  print('<li><a href="/node/412">Schedule</a></li>');
  print('</ul>');
  print('</div>');

  print('<div class="column column-3">');
  print(theme('mcneese_tag', $cf['html']['tags']['mcneese_www_html_footer_column_heading_open']));
  print('<h3 class="column-header">Explore</h3>');
  print(theme('mcneese_tag', $cf['html']['tags']['mcneese_www_html_footer_column_heading_close']));
  print('<ul>');
  print('<li><a href="/node/250">History</a></li>');
  print('<li><a href="/node/3287">Quick Facts</a></li>');
  print('<li><a href="/node/5530">Campus Maps</a></li>');
  print('<li><a href="/node/251">Mission</a></li>');
  print('<li><a href="/node/5675">A-Z Index</a></li>');
  print('<li><a href="/node/8713">Consumer Information</a></li>');
  print('</ul>');
  print('</div>');

  print('<div class="column column-4">');
  print(theme('mcneese_tag', $cf['html']['tags']['mcneese_www_html_footer_column_heading_open']));
  print('<h3 class="column-header">Social Connection</h3>');
  print(theme('mcneese_tag', $cf['html']['tags']['mcneese_www_html_footer_column_heading_close']));
  print('<ul>');
  print('<li class="social facebook"><a href="http://www.facebook.com/McNeeseStateU"><img alt="Facebook Icon" src="');
  print($stp);
  print('/images/facebook_icon.png" title="Facebook"></a></li>');
  print('<li class="social twitter"><a href="http://twitter.com/#!/McNeese"><img alt="Twitter Icon" src="');
  print($stp);
  print('/images/twitter_icon.png" title="Twitter"></a></li>');
  print('<li class="social linked_ln"><a href="http://www.linkedin.com/edu/mcneese-state-university-18431"><img alt="LinkedIn Icon" src="');
  print($stp);
  print('/images/linkedin_icon.png" title="LinkedIn"></a></li>');
  print('<li class="social google_plus"><a href="https://plus.google.com/101409453884998941600" rel="noreferrer"><img alt="Google+ Icon" src="');
  print($stp);
  print('/images/google_plus.png" title="Google+"></a></li>');
  print('</ul>');
  print('</div>');
  print('</div>');

  print('<div class="copyright">');
  print('<img class="copyright-logo" alt="McNeese Footer Logo" src="');
  print($stp);
  print('/images/footer-logo.png" title="McNeese State University">');
  print('<div class="copyright-menus">');
  print('<ul class="copyright-menu copyright-menu-1">');
  print('<li><a href="/node/269">EOE/AA/ADA</a> |</li>');
  print('<li><a href="http://www.ulsystem.net/" title="University of Louisiana System">a member of the University of Louisiana System</a> |</li>');
  print('<li><a href="/node/524">Web Disclaimer</a></li>');
  print('</ul>');

  print('<ul class="copyright-menu copyright-menu-2">');
  print('<li><a href="/policy" title="Policy Statements">Policy Statements</a> |</li>');
  print('<li><a href="/node/1064">University Status &amp; Emergency Preparedness</a></li>');
  print('</ul>');
  print('</div>');
  print('</div>');
  print('<!--(end-www-footer)-->');
  print(theme('mcneese_tag', $cf['html']['tags']['mcneese_www_html_footer_close']));

  print('<div id="mcneese-bottom" class="mcneese-bottom">');
  print('<!--(begin-bottom)-->');
  mcneese_do_print($cf, 'bottom');
  if (isset($page_bottom)) print($page_bottom);
  print('<!--(end-bottom)-->');
  print('</div>');

  print('<!--(end-body)-->');
  print('</body>');
  print('</html>');

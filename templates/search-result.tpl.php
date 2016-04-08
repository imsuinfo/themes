<?php
/**
 * @file
 * Search result theme implementation.
 */
  $cf = & drupal_static('cf_theme_get_variables', array());

  print('<li class="search_result">');
  print(theme('mcneese_tag', $cf['search_result']['tags']['mcneese_search_result_section_open']));
  print(theme('mcneese_tag', $cf['search_result']['tags']['mcneese_search_result_header_open']));
  print(theme('mcneese_tag', $cf['generic']['tags']['mcneese_hgroup_open']));
  print(render($title_prefix));

  print('<h4 class="title"');
  print($title_attributes);
  print('><a href="');
  print($url);
  print('">');
  print($title);
  print('</a></h4>');

  print(render($title_suffix));
  print(theme('mcneese_tag', $cf['generic']['tags']['mcneese_hgroup_close']));
  print(theme('mcneese_tag', $cf['search_result']['tags']['mcneese_search_result_header_close']));

  print('<div class="search_result-snippet"');
  print($content_attributes);
  print('>');

  if (!empty($snippet)) print($snippet);

  print('</div>');
  print(theme('mcneese_tag', $cf['search_result']['tags']['mcneese_search_result_section_close']));
  print('</li>');

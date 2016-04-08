<?php
/**
 * @file
 * Search Block theme implementation.
 */
  $cf = & drupal_static('cf_theme_get_variables', array());

  print(theme('mcneese_tag', $cf['search_block_form']['tags']['mcneese_search_block_form_section_open']));
  print('<!--(begin-search_block_form)-->');
  print(theme('mcneese_tag', $cf['search_block_form']['tags']['mcneese_search_block_form_header_open']));
  print(theme('mcneese_tag', $cf['generic']['tags']['mcneese_hgroup_open']));

  print('<h2 class="element-invisible html_tag-heading">');
  print("Search Form");
  print('</h2>');

  print(theme('mcneese_tag', $cf['generic']['tags']['mcneese_hgroup_close']));
  print(theme('mcneese_tag', $cf['search_block_form']['tags']['mcneese_search_block_form_header_close']));

  print($search_form);

  print('<!--(end-search_block_form)-->');
  print(theme('mcneese_tag', $cf['search_block_form']['tags']['mcneese_search_block_form_section_close']));

<?php
/**
 * @file
 * Search results theme implementation.
 */
  $cf = & drupal_static('cf_theme_get_variables', array());

  print(theme('mcneese_tag', $cf['search_results']['tags']['mcneese_search_results_section_open']));

  if (empty($search_results)) {
    print(theme('mcneese_tag', $cf['search_results']['tags']['mcneese_search_results_header_open']));
    print(theme('mcneese_tag', $cf['generic']['tags']['mcneese_hgroup_open']));

    print('<h3 class="html_tag-heading">');
    print("Your search yielded no results");
    print('</h3>');

    print(theme('mcneese_tag', $cf['generic']['tags']['mcneese_hgroup_close']));
    print(theme('mcneese_tag', $cf['search_results']['tags']['mcneese_search_results_header_close']));

    print search_help('search#noresults', drupal_help_arg());
  } else {
    print(theme('mcneese_tag', $cf['search_results']['tags']['mcneese_search_results_header_open']));
    print(theme('mcneese_tag', $cf['generic']['tags']['mcneese_hgroup_open']));

    print('<h3 class="html_tag-heading">');
    print("Search Results");
    print('</h3>');

    print(theme('mcneese_tag', $cf['generic']['tags']['mcneese_hgroup_close']));
    print(theme('mcneese_tag', $cf['search_results']['tags']['mcneese_search_results_header_close']));

    print('<ol class="search_results ');
    print($module);
    print('-results">');
    print($search_results);
    print('</ol>');

    print($pager);
  }

  print(theme('mcneese_tag', $cf['search_results']['tags']['mcneese_search_results_section_close']));

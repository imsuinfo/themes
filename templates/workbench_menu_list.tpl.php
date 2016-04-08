<?php
/**
 * @file
 * Default theme implementation to display a single workbench menu list.
 */
  $cf = & drupal_static('cf_theme_get_variables', array());

if (!$child) {
  print(theme('mcneese_tag', $cf['workbench_menu_list']['tags']['menu_list_open']));
    print(theme('mcneese_tag', $cf['generic']['tags']['mcneese_header_open']));
    print(theme('mcneese_tag', $cf['generic']['tags']['mcneese_hgroup_open']));
    print('<h2 class="html_tag-heading">Menu</h2>');
    print(theme('mcneese_tag', $cf['generic']['tags']['mcneese_hgroup_close']));
    print(theme('mcneese_tag', $cf['generic']['tags']['mcneese_header_close']));

    print('<ul class="navigation_list html_tag-list">');
    foreach ((array) $list['items'] as $item) {
      print($item);
    }
    print('</ul>');

  print(theme('mcneese_tag', $cf['workbench_menu_list']['tags']['menu_list_close']));
} else {
  print('<ul class="navigation_list html_tag-list menu_item-children">');
  foreach ((array) $list['items'] as $item) {
    print($item);
  }
  print('</ul>');
}

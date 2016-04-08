<?php
/**
 * @file
 * Toolbar theme implementation.
 */
  $cf = & drupal_static('cf_theme_get_variables', array());

  if ($cf['is']['toolbar'] === FALSE) {
    // prevent drupal from not using this theme because it prints nothing.
    print('<!-->');
    return;
  }

  print(theme('mcneese_tag', $cf['toolbar']['tags']['mcneese_toolbar_open']));
  print('<!--(begin-toolbar)-->');

  if (isset($heading)) print($heading);

  print(theme('mcneese_tag', $cf['toolbar']['tags']['mcneese_toolbar_menu']));
  print('<ul class="navigation_list html_tag-list">');
  print(theme('items_list', $mcneese_toolbar['menu']));
  print('</ul>');

  print(theme('mcneese_tag', $cf['toolbar']['tags']['mcneese_toolbar_nav_close']));

  print(theme('mcneese_tag', $cf['toolbar']['tags']['mcneese_toolbar_shortcuts']));

  print('<ul class="navigation_list html_tag-list">');
  print(theme('items_list', $mcneese_toolbar['shortcuts']));
  print('</ul>');

  print(theme('mcneese_tag', $cf['toolbar']['tags']['mcneese_toolbar_nav_close']));

  print('<!--(end-toolbar)-->');
  print(theme('mcneese_tag', $cf['toolbar']['tags']['mcneese_toolbar_close']));

<?php
/**
 * @file
 * user Profile theme implementation.
 */
  $cf = & drupal_static('cf_theme_get_variables', array());

  print(theme('mcneese_tag', $cf['user_profile_category']['tags']['mcneese_user_profile_category_open']));
  if (!empty($title)) {
    print(theme('mcneese_tag', $cf['user_profile_category']['tags']['mcneese_user_profile_category_header_open']));
    print(theme('mcneese_tag', $cf['generic']['tags']['mcneese_hgroup_open']));

    print('<h2 class="user_profile-title html_tag-heading">');
    print($title);
    print('</h2>');

    print(theme('mcneese_tag', $cf['generic']['tags']['mcneese_hgroup_close']));
    print(theme('mcneese_tag', $cf['user_profile_category']['tags']['mcneese_user_profile_category_header_close']));
  }

  print($profile_items);
  print(theme('mcneese_tag', $cf['user_profile_category']['tags']['mcneese_user_profile_category_close']));

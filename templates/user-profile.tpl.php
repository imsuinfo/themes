<?php
/**
 * @file
 * user Profile theme implementation.
 */
  $cf = & drupal_static('cf_theme_get_variables', array());

  print(theme('mcneese_tag', $cf['user_profile']['tags']['mcneese_user_profile_open']));
  print(theme('mcneese_tag', $cf['user_profile']['tags']['mcneese_user_profile_header_open']));
  print(theme('mcneese_tag', $cf['generic']['tags']['mcneese_hgroup_open']));

  print('<h2 class="user_profile-title element-invisible html_tag-heading">');
  print("User Profile");
  print('</h2>');

  print(theme('mcneese_tag', $cf['generic']['tags']['mcneese_hgroup_close']));
  print(theme('mcneese_tag', $cf['user_profile']['tags']['mcneese_user_profile_header_close']));
  print(render($user_profile));
  print(theme('mcneese_tag', $cf['user_profile']['tags']['mcneese_user_profile_close']));

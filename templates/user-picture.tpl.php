<?php
/**
 * @file
 * User Picture theme implementation.
 */
  $cf = & drupal_static('cf_theme_get_variables', array());

  if (!empty($user_picture)) {
    print(theme('mcneese_tag', $cf['user_picture']['tags']['mcneese_user_picture_open']));
    print($user_picture);

    if ($cf['is']['html5']) print('<figcaption class="html_tag-figcaption">');

    print(t("A picture that represents the user: @name.", array('@name' => $user_name)));
    print('</figcaption>');
    print(theme('mcneese_tag', $cf['user_picture']['tags']['mcneese_user_picture_close']));
  }

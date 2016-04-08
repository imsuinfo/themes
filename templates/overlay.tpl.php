<?php
/**
 * @file
 * Block theme implementation.
 */
  $cf = & drupal_static('cf_theme_get_variables', array());

  print(render($disable_overlay));
  print('<div id="overlay" class="overlay">');
  print('<div id="overlay-titlebar" class="overlay-titlebar">');
  print('<div id="overlay-close-wrapper" class="overlay-close-wrapper">');
  print('<a id="overlay-close" href="#" class="overlay-close"><span class="element-invisible">');
  print('Close overlay');
  print('</span></a>');
  print('</div>');

  if ($tabs) {
    print(theme('mcneese_tag', $cf['generic']['tags']['mcneese_hgroup_open']));
      print(theme('mcneese_tag', $cf['generic']['tags']['mcneese_hgroup_open']));
        print('<h2 class="element-invisible html_tag-heading">');
        print('Primary tabs');
        print('</h2>');
      print(theme('mcneese_tag', $cf['generic']['tags']['mcneese_hgroup_close']));
    print(theme('mcneese_tag', $cf['generic']['tags']['mcneese_hgroup_close']));

    print('<ul id="overlay-tabs">');
      print(render($tabs));
    print('</ul>');
  }
  print('</div>');

  print('<div id="overlay-content" class="overlay-content">');
  print($page);
  print('</div>');
  print('</div>');

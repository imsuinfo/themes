<?php
/**
 * @file
 * Field theme implementation.
 */
  $cf = & drupal_static('cf_theme_get_variables', array());

  if (count($items) <= 1) {
    print(render($item));
  }
  else {
    foreach ($items as $delta => $item) {
      $tag = $cf['field']['tags']['mcneese_field__body_open'];
      $tag['attributes']['class'][] = $delta % 2 ? 'odd' : 'even';

      print(theme('mcneese_tag', $tag) . "\n");
      print(render($item));
      print(theme('mcneese_tag', $cf['field']['tags']['mcneese_field__body_close']) . "\n");
    }
  }

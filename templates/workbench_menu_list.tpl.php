<?php
/**
 * @file
 * Default theme implementation to display a single workbench menu list.
 */

  $cf = & drupal_static('cf_theme_get_variables', array());
?>
<?php print(theme('mcneese_tag', $cf['workbench_menu_list']['tags']['menu_list_open'])); ?>
  <?php foreach ((array) $list['items'] as $item) { print($item . "\n"); } ?>
<?php print(theme('mcneese_tag', $cf['workbench_menu_list']['tags']['menu_list_close'])); ?>

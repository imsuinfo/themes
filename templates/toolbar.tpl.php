<?php
/**
 * @file
 * Toolbar theme implementation.
 */
  $cf = & drupal_static('cf_theme_get_variables', array());
?>
<?php print(theme('mcneese_tag', $cf['toolbar']['tags']['mcneese_toolbar_open'])); ?>
  <?php if (isset($heading)) print($heading); ?>

  <?php print(theme('mcneese_tag', $cf['toolbar']['tags']['mcneese_toolbar_menu'])); ?>
    <?php print(theme('items_list', $mcneese_toolbar['menu'])); ?>
  <?php print(theme('mcneese_tag', $cf['toolbar']['tags']['mcneese_toolbar_nav_close'])); ?>

  <?php print(theme('mcneese_tag', $cf['toolbar']['tags']['mcneese_toolbar_shortcuts'])); ?>
    <?php print(theme('items_list', $mcneese_toolbar['shortcuts'])); ?>
  <?php print(theme('mcneese_tag', $cf['toolbar']['tags']['mcneese_toolbar_nav_close'])); ?>
<?php print(theme('mcneese_tag', $cf['toolbar']['tags']['mcneese_toolbar_close'])); ?>

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
?>
<?php print(theme('mcneese_tag', $cf['toolbar']['tags']['mcneese_toolbar_open'])); ?>
  <!--(begin-toolbar)-->
  <?php if (isset($heading)) print($heading); ?>

  <?php print(theme('mcneese_tag', $cf['toolbar']['tags']['mcneese_toolbar_menu'])); ?>
    <ul class="navigation_list html_tag-list">
      <?php print(theme('items_list', $mcneese_toolbar['menu'])); ?>
    </ul>
  <?php print(theme('mcneese_tag', $cf['toolbar']['tags']['mcneese_toolbar_nav_close'])); ?>

  <?php print(theme('mcneese_tag', $cf['toolbar']['tags']['mcneese_toolbar_shortcuts'])); ?>
    <ul class="navigation_list html_tag-list">
      <?php print(theme('items_list', $mcneese_toolbar['shortcuts'])); ?>
    </ul>
  <?php print(theme('mcneese_tag', $cf['toolbar']['tags']['mcneese_toolbar_nav_close'])); ?>
  <!--(end-toolbar)-->
<?php print(theme('mcneese_tag', $cf['toolbar']['tags']['mcneese_toolbar_close'])); ?>

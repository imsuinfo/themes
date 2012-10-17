<?php
/**
 * @file
 * Default theme implementation to display a single workbench menu list.
 */
  $cf = & drupal_static('cf_theme_get_variables', array());
?>
<?php if (!$child) { ?>
  <?php print(theme('mcneese_tag', $cf['workbench_menu_list']['tags']['menu_list_open'])); ?>
    <?php print(theme('mcneese_tag', $cf['generic']['tags']['mcneese_header_open']) . "\n"); ?>
      <?php print(theme('mcneese_tag', $cf['generic']['tags']['mcneese_hgroup_open']) . "\n"); ?>
        <h2 class="html_tag-heading">Menu</h2>
      <?php print(theme('mcneese_tag', $cf['generic']['tags']['mcneese_hgroup_close']) . "\n"); ?>
    <?php print(theme('mcneese_tag', $cf['generic']['tags']['mcneese_header_close']) . "\n"); ?>

    <ul class="navigation_list html_tag-list">
      <?php foreach ((array) $list['items'] as $item) { print($item . "\n"); } ?>
    </ul>
  <?php print(theme('mcneese_tag', $cf['workbench_menu_list']['tags']['menu_list_close'])); ?>
<?php } else { ?>
  <ul class="navigation_list html_tag-list menu_item-children">
    <?php foreach ((array) $list['items'] as $item) { print($item . "\n"); } ?>
  </ul>
<?php } ?>

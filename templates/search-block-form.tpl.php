<?php
/**
 * @file
 * Search Block theme implementation.
 */
  $cf = & drupal_static('cf_theme_get_variables', array());
?>
<!--(begin_search_block_form)-->
<?php print(theme('mcneese_tag', $cf['search_block_form']['tags']['mcneese_search_block_form_header_open']) . "\n"); ?>
  <h2 class="element-invisible"><?php print t("Search Form"); ?></h2>
<?php print(theme('mcneese_tag', $cf['search_block_form']['tags']['mcneese_search_block_form_header_close']) . "\n"); ?>

<?php print($search_form); ?>
<!--(end_search_block_form)-->

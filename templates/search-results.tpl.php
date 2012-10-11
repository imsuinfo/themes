<?php
/**
 * @file
 * Search results theme implementation.
 */
  $cf = & drupal_static('cf_theme_get_variables', array());
?>
<?php print(theme('mcneese_tag', $cf['search_results']['tags']['mcneese_search_results_section_open']) . "\n"); ?>
  <?php if (empty($search_results)) { ?>
    <?php print(theme('mcneese_tag', $cf['search_results']['tags']['mcneese_search_results_header_open']) . "\n"); ?>
      <?php print(theme('mcneese_tag', $cf['generic']['tags']['mcneese_hgroup_open']) . "\n"); ?>
        <h3 class="html_tag-heading"><?php print t("Your search yielded no results"); ?></h3>
      <?php print(theme('mcneese_tag', $cf['generic']['tags']['mcneese_hgroup_close']) . "\n"); ?>
    <?php print(theme('mcneese_tag', $cf['search_results']['tags']['mcneese_search_results_header_close']) . "\n"); ?>

    <?php print search_help('search#noresults', drupal_help_arg()); ?>
  <?php } else { ?>
    <?php print(theme('mcneese_tag', $cf['search_results']['tags']['mcneese_search_results_header_open']) . "\n"); ?>
      <?php print(theme('mcneese_tag', $cf['generic']['tags']['mcneese_hgroup_open']) . "\n"); ?>
        <h3 class="html_tag-heading"><?php print t("Search Results"); ?></h3>
      <?php print(theme('mcneese_tag', $cf['generic']['tags']['mcneese_hgroup_close']) . "\n"); ?>
    <?php print(theme('mcneese_tag', $cf['search_results']['tags']['mcneese_search_results_header_close']) . "\n"); ?>

    <ol class="search_results <?php print($module); ?>-results">
      <?php print($search_results); ?>
    </ol>

    <?php print($pager); ?>
  <?php } ?>
<?php print(theme('mcneese_tag', $cf['search_results']['tags']['mcneese_search_results_section_close']) . "\n"); ?>

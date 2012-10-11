<?php
/**
 * @file
 * Search result theme implementation.
 */
  $cf = & drupal_static('cf_theme_get_variables', array());
?>
<li class="search_result">
  <?php print(theme('mcneese_tag', $cf['search_result']['tags']['mcneese_search_result_section_open']) . "\n"); ?>
    <?php print(theme('mcneese_tag', $cf['search_result']['tags']['mcneese_search_result_header_open']) . "\n"); ?>
      <?php print(theme('mcneese_tag', $cf['generic']['tags']['mcneese_hgroup_open']) . "\n"); ?>
        <?php print(render($title_prefix)); ?>
        <h4 class="title"<?php print($title_attributes); ?>><a href="<?php print($url); ?>"><?php print($title); ?></a></h4>
        <?php print(render($title_suffix)); ?>
      <?php print(theme('mcneese_tag', $cf['generic']['tags']['mcneese_hgroup_close']) . "\n"); ?>
    <?php print(theme('mcneese_tag', $cf['search_result']['tags']['mcneese_search_result_header_close']) . "\n"); ?>

    <div class="search_result-snippet"<?php print($content_attributes); ?>>
      <?php if (!empty($snippet)) print($snippet); ?>
    </div>
  <?php print(theme('mcneese_tag', $cf['search_result']['tags']['mcneese_search_result_section_close']) . "\n"); ?>
</li>

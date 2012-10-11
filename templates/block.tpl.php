<?php
/**
 * @file
 * Block theme implementation.
 */
  $cf = & drupal_static('cf_theme_get_variables', array());
  mcneese_render_block();
?>
<?php if ($cf['show']['block']['content']) { ?>
  <?php if (isset($cf['block']['tags']['mcneese_block_open'])) print(theme('mcneese_tag', $cf['block']['tags']['mcneese_block_open']) . "\n"); ?>
  <!--(begin-block_content)-->
  <?php if ($cf['show']['block']['header']) { ?>
    <?php print(theme('mcneese_tag', $cf['block']['tags']['mcneese_block_header_open']) . "\n"); ?>
      <?php print(theme('mcneese_tag', $cf['generic']['tags']['mcneese_hgroup_open']) . "\n"); ?>
        <!--(begin-block_title)-->
        <?php if ($cf['show']['block']['title_prefix']) print($cf['data']['block']['title_prefix'] . "\n"); ?>
        <h<?php print($cf['block']['heading']); ?> class="block-title html_tag-heading"><?php print($cf['data']['block']['header']); ?></h<?php print($cf['block']['heading']); ?>>
        <?php if ($cf['show']['block']['title_suffix']) print($cf['data']['block']['title_suffix'] . "\n"); ?>
        <!--(end-block_title)-->
      <?php print(theme('mcneese_tag', $cf['generic']['tags']['mcneese_hgroup_close']) . "\n"); ?>
    <?php print(theme('mcneese_tag', $cf['block']['tags']['mcneese_block_header_close']) . "\n"); ?>
  <?php } ?>

  <?php print($cf['data']['block']['content'] . "\n"); ?>
  <!--(end-block_content)-->
  <?php if (isset($cf['block']['tags']['mcneese_block_close'])) print(theme('mcneese_tag', $cf['block']['tags']['mcneese_block_close']) . "\n"); ?>
<?php } ?>

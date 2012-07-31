<?php
/**
 * @file
 * Node theme implementation.
 */
  $cf = & drupal_static('cf_theme_get_variables', array());
  mcneese_render_node();
?>
<?php if ($cf['show']['node']['content']) { ?>
  <?php print(theme('mcneese_tag', $cf['node']['tags']['mcneese_node_open']) . "\n"); ?>
  <!--(begin_node_content)-->
  <?php if ($cf['show']['node']['header']) { ?>
    <?php print(theme('mcneese_tag', $cf['node']['tags']['mcneese_node_header_open']) . "\n"); ?>
      <!--(begin_node_title)-->
      <?php if ($cf['show']['node']['title_prefix']) print($cf['data']['node']['title_prefix'] . "\n"); ?>
      <h2 class="node-title"><?php print($cf['data']['node']['header']); ?></h2>
      <?php if ($cf['show']['node']['title_suffix']) print($cf['data']['node']['title_suffix'] . "\n"); ?>
      <!--(end_node_title)-->
    <?php print(theme('mcneese_tag', $cf['node']['tags']['mcneese_node_header_close']) . "\n"); ?>
  <?php } ?>


  <?php print($cf['data']['node']['content'] . "\n"); ?>
  <!--(end_node_content)-->
  <?php print(theme('mcneese_tag', $cf['node']['tags']['mcneese_node_close']) . "\n"); ?>
<?php } ?>

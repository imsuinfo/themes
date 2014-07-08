<?php
/**
 * @file
 * Page theme implementation.
 */
  $cf = & drupal_static('cf_theme_get_variables', array());

  mcneese_render_page();

  $float_side = in_array('fixed', $cf['page']['tags']['mcneese_page_side_open']['attributes']['class']);
  $split_page = !$float_side && ($cf['show']['page']['menus'] || $cf['show']['page']['asides']);
?>

<?php mcneese_do_print($cf, 'page_header'); ?>

<?php mcneese_do_print($cf, 'messages', FALSE); ?>
<?php mcneese_do_print($cf, 'bulletin', FALSE); ?>
<?php mcneese_do_print($cf, 'help', FALSE); ?>
<?php mcneese_do_print($cf, 'information', FALSE); ?>

<div id="mcneese-float-right" class="expanded fixed">
  <?php mcneese_do_print($cf, 'messages', TRUE, TRUE); ?>
  <?php mcneese_do_print($cf, 'help', TRUE, TRUE); ?>
  <?php mcneese_do_print($cf, 'information', TRUE, TRUE); ?>
  <?php mcneese_do_print($cf, 'work_area_menu', TRUE, TRUE); ?>
</div>

<?php if ($split_page) { ?>
  <div id="mcneese-page-content" class="mcneese-content split" role="main">
    <?php mcneese_do_print($cf, 'side', FALSE); ?>

    <div class="column-2">
<?php } else { ?>
  <div id="mcneese-page-content" class="mcneese-content full" role="main">
<?php } ?>

  <?php mcneese_do_print($cf, 'page_title'); ?>

  <div id="mcneese-float-left" class="expanded fixed">
    <?php mcneese_do_print($cf, 'menu_tabs'); ?>
    <?php mcneese_do_print($cf, 'action_links'); ?>
    <?php mcneese_do_print($cf, 'breadcrumb'); ?>
    <?php mcneese_do_print($cf, 'side'); ?>
  </div>

  <?php mcneese_do_print($cf, 'menu_tabs', FALSE); ?>
  <?php mcneese_do_print($cf, 'breadcrumb', FALSE); ?>
  <?php mcneese_do_print($cf, 'action_links', FALSE); ?>
  <?php mcneese_do_print($cf, 'watermarks-pre'); ?>

  <div id="mcneese-content-main" class="mcneese-content-main" role="main">
    <!--(begin-page-main)-->
    <?php if ($cf['show']['page']['content']) { ?>
      <?php print($cf['data']['page']['content']); ?>
    <?php } ?>
    <!--(end-page-main)-->
  </div>

  <?php mcneese_do_print($cf, 'watermarks-post'); ?>

<?php if ($split_page) { ?>
    </div>
  </div>
<?php } else { ?>
  </div>
<?php } ?>

<?php mcneese_do_print($cf, 'page_footer'); ?>

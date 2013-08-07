<?php
/**
 * @file
 * Page theme implementation.
 */
  $cf = & drupal_static('cf_theme_get_variables', array());

  mcneese_render_page();
  mcneese_www_render_page();

  $float_side = in_array('fixed', $cf['page']['tags']['mcneese_page_side_open']['attributes']['class']);
  $split_page = !$float_side && ($cf['show']['page']['menus'] || $cf['show']['page']['asides']);
?>
<?php if ($cf['is']['unsupported']) { ?>
  <div id="mcneese-unsupported-message">
    <?php print($cf['is_data']['unsupported']['message']); ?>
  </div>
<?php } ?>

<?php mcneese_do_print($cf, 'page_header'); ?>

<?php mcneese_do_print($cf, 'messages', FALSE); ?>
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

  <?php if ($cf['show']['page']['group_image']) { ?>
    <!--(begin-page-group_image)-->
    <img usemap="#group_image_map" class="<?php print($cf['data']['page']['group_image']['class']); ?>" title="<?php print($cf['data']['page']['group_image']['title']); ?>" alt="<?php print($cf['data']['page']['group_image']['alt']); ?>" src="<?php print($cf['data']['page']['group_image']['src']); ?>" other="<?php print($cf['data']['page']['group_image']['other']); ?>">
    <!--(end-page-group_image)-->
  <?php } ?>

  <?php mcneese_do_print($cf, 'page_title'); ?>

  <div id="mcneese-float-left" class="expanded fixed">
    <?php mcneese_do_print($cf, 'menu_tabs'); ?>
    <?php mcneese_do_print($cf, 'action_links'); ?>

    <?php if (!$cf['is']['front']) { ?>
      <?php mcneese_do_print($cf, 'breadcrumb'); ?>
    <?php } ?>

    <?php mcneese_do_print($cf, 'side'); ?>

    <?php if ($cf['show']['page']['document_outline']) { ?>
      <?php print(theme('mcneese_tag', $cf['page']['tags']['mcneese_www_document_outline_open']) . "\n"); ?>
        <!--(begin-page-document-outline)-->
        <?php print(theme('mcneese_tag', $cf['generic']['tags']['mcneese_header_open']) . "\n"); ?>
          <?php print(theme('mcneese_tag', $cf['generic']['tags']['mcneese_hgroup_open']) . "\n"); ?>
            <h2 class="html_tag-heading">Outline</h2>
          <?php print(theme('mcneese_tag', $cf['generic']['tags']['mcneese_hgroup_close']) . "\n"); ?>
        <?php print(theme('mcneese_tag', $cf['generic']['tags']['mcneese_header_close']) . "\n"); ?>

        <ul class="navigation_list html_tag-list">
          <?php print($cf['data']['page']['document_outline']['markup']); ?>
        </ul>
        <!--(end-page-document-outline)-->
      <?php print(theme('mcneese_tag', $cf['page']['tags']['mcneese_www_document_outline_close']) . "\n"); ?>
    <?php } ?>
  </div>

  <?php mcneese_do_print($cf, 'menu_tabs', FALSE); ?>

  <?php if (!$cf['is']['front']) { ?>
    <?php mcneese_do_print($cf, 'breadcrumb', FALSE); ?>
  <?php } ?>

  <?php mcneese_do_print($cf, 'action_links', FALSE); ?>
  <?php mcneese_do_print($cf, 'watermarks-pre'); ?>

  <div id="mcneese-content-main" class="mcneese-content-main" role="main">
    <!--(begin-page-main)-->
    <?php if ($cf['show']['page']['content']) { ?>
      <?php if ($cf['show']['page']['document_header']) { ?>
        <?php print(theme('mcneese_tag', $cf['page']['tags']['mcneese_www_document_header_open']) . "\n"); ?>
          <?php print(theme('mcneese_tag', $cf['generic']['tags']['mcneese_hgroup_open']) . "\n"); ?>
            <!--(begin-page-document-header)-->
              <?php print($cf['data']['page']['document_header']['markup']); ?>
            <!--(end-page-document-header)-->
          <?php print(theme('mcneese_tag', $cf['generic']['tags']['mcneese_hgroup_close']) . "\n"); ?>
        <?php print(theme('mcneese_tag', $cf['page']['tags']['mcneese_www_document_header_close']) . "\n"); ?>
      <?php } ?>

      <?php print($cf['data']['page']['content']); ?>

      <?php if ($cf['show']['page']['document_footer']) { ?>
        <?php print(theme('mcneese_tag', $cf['page']['tags']['mcneese_www_document_footer_open']) . "\n"); ?>
          <!--(begin-page-document-footer)-->
            <?php print($cf['data']['page']['document_footer']['markup']); ?>
          <!--(end-page-document-footer)-->
        <?php print(theme('mcneese_tag', $cf['page']['tags']['mcneese_www_document_footer_close']) . "\n"); ?>
      <?php } ?>
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

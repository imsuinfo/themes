<?php
/**
 * @file
 * Page theme implementation.
 */
  $cf = & drupal_static('cf_theme_get_variables', array());

  mcneese_render_page();

  $float_tabs = in_array('fixed', $cf['page']['tags']['mcneese_page_tabs_open']['attributes']['class']);
  $float_action_links = in_array('fixed', $cf['page']['tags']['mcneese_page_action_links_open']['attributes']['class']);
?>

<?php if ($cf['show']['page']['header']) { ?>
  <?php print(theme('mcneese_tag', $cf['page']['tags']['mcneese_page_header_open']) . "\n"); ?>
    <!--(begin-page_header)-->
    <div class="header-section header-top">
      <?php print($cf['data']['page']['header'] . "\n"); ?>

      <?php if ($cf['show']['page']['logo']) { ?>
        <a href="<?php print($cf['data']['page']['logo']['href']); ?>" class="site-logo" title="<?php print($cf['data']['page']['logo']['alt']); ?>" alt="<?php print($cf['data']['page']['logo']['alt']); ?>" role="image"><?php print($cf['data']['page']['logo']['alt']); ?></a>
      <?php } ?>

      <?php if ($cf['show']['page']['header_menu_1']) { ?>
        <div class="header-menu header-menu-1" role="navigation">
          <?php print($cf['data']['page']['header_menu_1'] . "\n"); ?>
        </div>
      <?php } ?>
    </div>

    <div class="header-separator"></div>

    <div class="header-section header-bottom">
      <?php if ($cf['show']['page']['header_menu_2']) { ?>
        <div class="header-menu header-menu-2" role="navigation">
          <?php print($cf['data']['page']['header_menu_2'] . "\n"); ?>
        </div>
      <?php } ?>
    </div>
    <!--(end-page_header)-->
  <?php print(theme('mcneese_tag', $cf['page']['tags']['mcneese_page_header_close']) . "\n"); ?>
<?php } ?>

<?php if ($cf['show']['page']['messages']) { ?>
  <?php print(theme('mcneese_tag', $cf['page']['tags']['mcneese_page_messages_open']) . "\n"); ?>
    <!--(begin-page_messages)-->
    <?php print($cf['data']['page']['messages']['renderred'] . "\n"); ?>
    <!--(end-page_messages)-->
  <?php print(theme('mcneese_tag', $cf['page']['tags']['mcneese_page_messages_close']) . "\n"); ?>
<?php } ?>

<?php if ($cf['show']['page']['help']) { ?>
  <?php print(theme('mcneese_tag', $cf['page']['tags']['mcneese_page_help_open']) . "\n"); ?>
    <?php print(theme('mcneese_tag', $cf['page']['tags']['mcneese_page_help_header_open']) . "\n"); ?>
      <h2>Help</h2>
    <?php print(theme('mcneese_tag', $cf['page']['tags']['mcneese_page_help_header_close']) . "\n"); ?>
    <div class="help-wrapper">
      <!--(begin-page_help)-->
      <?php print($cf['data']['page']['help'] . "\n"); ?>
      <!--(end-page_help)-->
    </div>
  <?php print(theme('mcneese_tag', $cf['page']['tags']['mcneese_page_help_close']) . "\n"); ?>
<?php } ?>

<?php if ($cf['show']['page']['information']) { ?>
  <?php print(theme('mcneese_tag', $cf['page']['tags']['mcneese_page_information_open']) . "\n"); ?>
    <?php print(theme('mcneese_tag', $cf['page']['tags']['mcneese_page_information_header_open']) . "\n"); ?>
      <h2>Information</h2>
    <?php print(theme('mcneese_tag', $cf['page']['tags']['mcneese_page_information_header_close']) . "\n"); ?>
    <div class="information-wrapper">
      <!--(begin-page_information)-->
      <?php print($cf['data']['page']['information'] . "\n"); ?>
      <!--(end-page_information)-->
    </div>
  <?php print(theme('mcneese_tag', $cf['page']['tags']['mcneese_page_information_close']) . "\n"); ?>
<?php } ?>

<?php if ($cf['show']['page']['editing']) { ?>
  <?php print(theme('mcneese_tag', $cf['page']['tags']['mcneese_page_editing_open']) . "\n"); ?>
    <!--(begin-page_editing)-->
    <?php print($cf['data']['page']['editing'] . "\n"); ?>
    <!--(end-page_editing)-->
  <?php print(theme('mcneese_tag', $cf['page']['tags']['mcneese_page_editing_close']) . "\n"); ?>
<?php } ?>

<?php if ($cf['show']['page']['menus'] || $cf['show']['page']['asides']) { ?>
  <div id="mcneese-page-content" class="split" role="main">
    <div id="mcneese-page-side" class="column-1">
      <?php if ($cf['show']['page']['menus']) { ?>
        <?php print($cf['data']['page']['menus']); ?>
      <?php } ?>

      <?php if ($cf['show']['page']['asides']) { ?>
        <?php print($cf['data']['page']['asides']); ?>
      <?php } ?>
    </div>

    <div class="column-2">
<?php } else { ?>
  <div id="mcneese-page-content" class="full" role="main">
<?php } ?>

  <?php if ($cf['show']['page']['title']) { ?>
    <?php print(theme('mcneese_tag', $cf['page']['tags']['mcneese_page_title_open']) . "\n"); ?>
      <!--(begin-page_title)-->
      <?php if ($cf['show']['page']['title_prefix']) print($cf['data']['page']['title_prefix'] . "\n"); ?>
      <h1 class="page-title"><?php print($cf['data']['page']['title']); ?></h1>
      <?php if ($cf['show']['page']['title_suffix']) print($cf['data']['page']['title_suffix'] . "\n"); ?>
      <!--(end-page_title)-->
    <?php print(theme('mcneese_tag', $cf['page']['tags']['mcneese_page_title_close']) . "\n"); ?>
  <?php } ?>

  <?php if ($cf['show']['page']['tabs'] || $cf['show']['page']['action_links']) { ?>
    <div id="mcneese-float-left" role="navigation">
      <?php if ($cf['show']['page']['tabs'] && $float_tabs) { ?>
        <?php print(theme('mcneese_tag', $cf['page']['tags']['mcneese_page_tabs_open']) . "\n"); ?>
        <!--(begin-page_tabs)-->
        <?php print($cf['data']['page']['tabs'] . "\n"); ?>
        <!--(end-page_tabs)-->
        <?php print(theme('mcneese_tag', $cf['page']['tags']['mcneese_page_tabs_close']) . "\n"); ?>
      <?php } ?>

      <?php if ($cf['show']['page']['action_links'] && $float_action_links) { ?>
        <?php print(theme('mcneese_tag', $cf['page']['tags']['mcneese_page_action_links_open']) . "\n"); ?>
        <!--(begin-page_action_links)-->
        <?php print($cf['data']['page']['action_links'] . "\n"); ?>
        <!--(end-page_action_links)-->
        <?php print(theme('mcneese_tag', $cf['page']['tags']['mcneese_page_action_links_close']) . "\n"); ?>
      <?php } ?>
    </div>
  <?php } ?>

  <?php if ($cf['show']['page']['tabs'] && !$float_tabs) { ?>
    <?php print(theme('mcneese_tag', $cf['page']['tags']['mcneese_page_tabs_open']) . "\n"); ?>
    <!--(begin-page_tabs)-->
    <?php print($cf['data']['page']['tabs'] . "\n"); ?>
    <!--(end-page_tabs)-->
    <?php print(theme('mcneese_tag', $cf['page']['tags']['mcneese_page_tabs_close']) . "\n"); ?>
  <?php } ?>

  <?php if (!$cf['is']['front']) { ?>
    <?php print(theme('mcneese_tag', $cf['page']['tags']['mcneese_page_breadcrumb_open']) . "\n"); ?>
      <?php if ($cf['show']['page']['breadcrumb'] || $cf['show']['page']['precrumb'] || $cf['show']['page']['postcrumb']) { ?>
        <?php if ($cf['show']['page']['precrumb']) { ?>
          <!--(begin-page_precrumb)-->
          <?php print($cf['data']['page']['precrumb'] . "\n"); ?>
          <!--(end-page_precrumb)-->
        <?php } ?>

        <?php if ($cf['show']['page']['breadcrumb']) { ?>
          <!--(begin-page_breadcrumb)-->
          <?php print($cf['data']['page']['breadcrumb'] . "\n"); ?>
          <!--(end-page_breadcrumb)-->
        <?php } ?>

        <?php if ($cf['show']['page']['postcrumb']) { ?>
          <!--(begin-page_postcrumb)-->
          <?php print($cf['data']['page']['postcrumb'] . "\n"); ?>
          <!--(end-page_postcrumb)-->
        <?php } ?>
      <?php } ?>
    <?php print(theme('mcneese_tag', $cf['page']['tags']['mcneese_page_breadcrumb_close']) . "\n"); ?>
  <?php } ?>

  <?php if ($cf['show']['page']['action_links'] && !$float_action_links) { ?>
    <?php print(theme('mcneese_tag', $cf['page']['tags']['mcneese_page_action_links_open']) . "\n"); ?>
    <!--(begin-page_action_links)-->
    <?php print($cf['data']['page']['action_links'] . "\n"); ?>
    <!--(end-page_action_links)-->
    <?php print(theme('mcneese_tag', $cf['page']['tags']['mcneese_page_action_links_close']) . "\n"); ?>
  <?php } ?>

  <?php if ($cf['show']['page']['watermarks-pre']) { ?>
    <div id="mcneese-page-watermarks-pre">
      <?php print($cf['data']['page']['watermarks-pre']); ?>
    </div>
  <?php } ?>

  <div id="mcneese-page-main" role="main">
    <!--(begin-page_main)-->
    <?php if ($cf['show']['page']['content']) { ?>
      <?php print($cf['data']['page']['content']); ?>
    <?php } ?>
    <!--(end-page_main)-->
  </div>

  <?php if ($cf['show']['page']['watermarks-post']) { ?>
    <div id="mcneese-page-watermarks-post">
      <?php print($cf['data']['page']['watermarks-post']); ?>
    </div>
  <?php } ?>

<?php if ($cf['show']['page']['menus'] || $cf['show']['page']['asides']) { ?>
    </div>
  </div>
<?php } else { ?>
  </div>
<?php } ?>



<?php print(theme('mcneese_tag', $cf['page']['tags']['mcneese_page_footer_open']) . "\n"); ?>
  <!--(begin-page_footer)-->
  <?php if ($cf['show']['page']['footer']) { ?>
    <?php print($cf['data']['page']['footer']); ?>
  <?php } ?>
  <!--(end-page_footer)-->
<?php print(theme('mcneese_tag', $cf['page']['tags']['mcneese_page_footer_close']) . "\n"); ?>

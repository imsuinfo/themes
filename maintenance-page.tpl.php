<?php print($cf['agent']['doctype'] . "\n");?>
<html lang="<?php print($language->language); ?>" dir="<?php print $language->dir; ?>" version="HTML+RDFa 1.1"

<head>
  <!--(begin_head)-->
  <?php print($head); ?>
  <title><?php print($head_title); ?></title>
  <?php print($styles); ?>
  <?php print($scripts);?>
  <?php if (function_exists('cf_theme_generate_headers')) print(cf_theme_generate_headers($cf)); ?>
  <!--(end_head)-->
</head>
<body id="mcneese_drupal-body" class="mcneese_drupal-body <?php print($cf['markup_css']['body']['class']); ?>" <?php print($attributes);?>>
<?php  if ($cf['is']['overlay']){ ?><div id="mcneese_drupal-skip_nav" class="clearfix">
    <!--(begin_skipnav)-->
    <div id="mcneese_drupal-skip_nav-list">
      <div id="mcneese_drupal-skip_nav-list-content"><a id="mcneese_drupal-skip_nav-list-content-link" class="mcneese_drupal-skipnav-link element-invisible element-focusable" href="#mcneese_drupal-content"><?php print t("Skip to main content"); ?></a></div>
      <?php if (isset($search)){ ?><div id="mcneese_drupal-skip_nav-list-search"><a id="mcneese_drupal-skip_nav-list-search-link" class="mcneese_drupal-skipnav-link element-invisible element-focusable" href="#mcneese_drupal-search"><?php print t("Skip to search"); ?></a></div><?php } ?>
    </div>
    <!--(end_skipnav)-->
  </div><?php } ?>

  <!--(begin_unsupported)-->
  <?php if ($cf['is']['unsupported']){ ?>
    <div id="unsupported" class="mcneese_drupal clearfix">
      <?php print($cf['is_data']['unsupported']['message']); ?>
    </div>
  <?php } ?>
  <!--(end_unsupported)-->

  <!--(begin_body)-->
  <div id="mcneese_drupal-page_top" class="mcneese_drupal">
    <!--(begin_page_top)-->
    <?php print($page_top); ?>
    <!--(end_page_top)-->
  </div>

  <div id="mcneese_drupal-page" class="mcneese_drupal drupal-page">
    <!--(begin_page)-->
    <?php if (!$cf['is']['overlay']){ ?>
      <div id='mcneese_drupal-header_region' class='clearfix page-header'>
        <div id='mcneese_drupal-header'>
          <!--(begin_header)-->
          <?php if ($cf['show']['logo']){ ?>
            <div id='mcneese_drupal-website_logo'>
              <!--(begin_website_logo)-->
              <a id="mcneese_drupal-website_logo-link" rel="home" title="<?php print(t("Home Page")); ?>" href="/">
                <img id="mcneese_drupal-website_logo-image" alt="<?php print($cf['at']['human_name']); ?>" title="<?php print($cf['at']['human_name']); ?>" src="<?php print($base_path . path_to_theme());?>/images/web_logo.png">
              </a>
              <!--(end_website_logo)-->
            </div>
          <?php } ?>

          <?php if ($cf['show']['header']){ ?>
            <div id='mcneese_drupal-header-blocks'>
              <!--(begin_header_blocks)-->
              <?php print($header); ?>
              <!--(end_header_blocks)-->
            </div>
          <?php } ?>
          <!--(end_header)-->
        </div>

        <div id="mcneese_drupal-header-horizontal_ruler"></div>

        <div id='mcneese_drupal-sub_header'>
          <!--(begin_sub_header)-->
          <?php if ($cf['show']['sub_header']){ ?>
            <div id='mcneese_drupal-sub_header-blocks'>
              <!--(begin_sub_header_blocks)-->
              <?php print($sub_header); ?>
              <!--(end_sub_header_blocks)-->
            </div>
          <?php } ?>
          <!--(end_sub_header)-->
        </div>
      </div>
  <?php } ?>
    <div id='mcneese_drupal-message_region' class='clearfix'>
      <?php if ($cf['show']['messages']){ ?>
        <!--(begin_messages)-->
        <div id='mcneese_drupal-messages' class='clearfix'>
          <h2 class='element-invisible'><?php print(t("Messages")); ?></h2>
          <?php print($messages); ?>
        </div>
        <!--(end_messages)-->
      <?php } ?>

      <?php if ($cf['show']['help']){ ?>
        <!--(begin_help)-->
        <div id='mcneese_drupal-help' class='clearfix'>
          <h2 class='element-invisible'><?php print(t("Help")); ?></h2>
          <?php print($help); ?>
        </div>
        <!--(end_help)-->
      <?php } ?>
    </div>

    <div id='mcneese_drupal-title_region' class='clearfix page-title'>
      <div id='mcneese_drupal-title'>
        <!--(begin_title)-->
        <?php if ($cf['show']['title_prefix']) print($title_prefix); ?>
        <?php if ($cf['show']['title']){ ?>
          <h1 id='mcneese_drupal-page_title' class="drupal_page_title"><?php print($title); ?></h1>
        <?php } ?>
        <?php if ($cf['show']['title_suffix']) print($title_suffix); ?>
        <!--(end_title)-->
      </div>

      <?php if ($cf['show']['side_links']) { ?>
        <div id='mcneese_drupal-side_links'>
          <!--(begin_side_links)-->
          <h2 class='element-invisible'><?php print t("Side Links"); ?></h2>
          <ul class='links'><?php print($side_links); ?></ul>
          <!--(end_side_links)-->
        </div>
      <?php } ?>

      <?php if ($cf['show']['primary_local_tasks']){ ?>
        <div id='mcneese_drupal-primary_tabs'>
          <!--(begin_primary_tabs)-->
          <h2 class='element-invisible'><?php print t("Primary Tabs"); ?></h2>
          <ul class='tabs primary'><?php print($primary_local_tasks); ?></ul>
          <!--(end_primary_tabs)-->
        </div>
      <?php } ?>

      <?php if ($cf['show']['breadcrumb'] || $cf['show']['sidenote']){ ?>
        <div id="mcneese_drupal-breadcrumb">
          <?php if ($cf['show']['breadcrumb']){ print($breadcrumb); } ?>

          <?php if ($cf['show']['sidenote']){ ?>
            <!--(begin_sidenote)-->
            <div class="sidenote"><?php print($cf['data']['sidenote']['content']); ?></div>
            <!--(end_sidenote)-->
          <?php } ?>
        </div>
      <?php } ?>
    </div>

    <div id='mcneese_drupal-content_region' class='page-title'>
      <?php if ($cf['show']['secondary_local_tasks']){ ?>
        <div id='mcneese_drupal-secondary_tabs'>
          <!--(begin_secondary_tabs)-->
          <h2 class='element-invisible'><?php print(t("Secondary Tabs")); ?></h2>
          <ul class='tabs secondary'><?php print($secondary_local_tasks); ?></ul>
          <!--(end_secondary_tabs)-->
        </div>
      <?php } ?>

      <?php if ($cf['show']['action_links']){ ?>
        <div id='mcneese_drupal-action_links'>
          <!--(begin_action_links)-->
          <h2 class='element-invisible'><?php print(t("Action Links")); ?></h2>
          <ul class="action-links"><?php print($action_links); ?></ul>
          <!--(end_action_links)-->
        </div>
      <?php } ?>

      <?php if ($cf['show']['sidebar_left']){ ?>
        <div id="mcneese_drupal-sidebar_left" class="page-sidebar_left clearfix">
          <!--(begin_sidebar_left)-->
          <h2 class='element-invisible'><?php print(t("Sidebar Left")); ?></h2>
          <?php print($sidebar_left); ?>
          <!--(end_sidebar_left)-->
        </div>
      <?php } ?>

      <?php if ($cf['show']['sidebar_right']){ ?>
        <div id="mcneese_drupal-sidebar_right" class="page-sidebar_right clearfix">
          <!--(begin_sidebar_right)-->
          <h2 class='element-invisible'><?php print(t("Sidebar Right")); ?></h2>
          <?php print($sidebar_right); ?>
          <!--(end_sidebar_right)-->
        </div>
      <?php } ?>

      <?php if ($cf['show']['content']){ ?>
        <div id='mcneese_drupal-content' class='drupal_content clearfix <?php print($cf['markup_css']['content']['class']); ?>'>
          <!--(begin_content)-->
          <h2 class='element-invisible'><?php print(t("Primary Content")); ?></h2>
          <?php print($content); ?>
          <!--(end_content)-->
        </div>
      <?php } ?>
    </div>

    <div id="mcneese_drupal-content_bottom"></div>

    <?php if ($cf['is']['overlay']){ ?>
      <div id="mcneese_drupal-undercontent" class="drupal_footer">
        <!--(begin_undercontent)-->
        <div id="mcneese_drupal-MSU" class="mcneese_drupal-outline" title='McNeese State University'>McNeese State University</div>
        <div id="mcneese_drupal-UCS" class="mcneese_drupal-outline" title='University Computing Services'>University Computing Services</div>
        <!--(end_undercontent)-->
      </div>
    <?php } else { ?>
      <?php if ($cf['show']['footer']) { ?>
        <div id="mcneese_drupal-footer" class="drupal_footer">
          <!--(begin_footer)-->
          <?php print($footer); ?>
          <!--(end_footer)-->
        </div>
      <?php } ?>
    <?php } ?>
    <!--(end_page)-->
  </div>

  <div id="mcneese_drupal-page_bottom" class="mcneese_drupal">
    <!--(begin_page_bottom)-->
    <?php print($page_bottom); ?>
    <!--(end_page_bottom)-->
  </div>
  <!--(end_body)-->
</body>
</html>

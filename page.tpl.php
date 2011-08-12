<?php if (!$cf['is']['overlay']){ ?>
    <div id='mcneese_drupal-header_region' class='clearfix page-header'>
      <div id='mcneese_drupal-header'>
        <!--(begin_header)-->
        <?php if ($cf['show']['logo']){ ?>
          <div id='mcneese_drupal-website_logo'>
            <!--(begin_website_logo)-->
            <a id="mcneese_drupal-website_logo-link" href="<?php print($cf['at']['path'] . '/'); ?>" title="<?php print(t("Home Page")); ?>">
              <img id="mcneese_drupal-website_logo-image" src="<?php print($logo); ?>" alt="<?php print($cf['at']['human_name']); ?>">
            </a>
            <!--(end_website_logo)-->
          </div>
        <?php } ?>

        <?php if ($cf['show']['page']['header']){ ?>
          <div id='mcneese_drupal-header-blocks'>
            <!--(begin_header_blocks)-->
            <?php print($page['header']); ?>
            <!--(end_header_blocks)-->
          </div>
        <?php } ?>
        <!--(end_header)-->
      </div>

      <div id="mcneese_drupal-header-horizontal_ruler"></div>

      <div id='mcneese_drupal-sub_header'>
        <!--(begin_sub_header)-->
        <?php if ($cf['show']['page']['sub_header']){ ?>
          <div id='mcneese_drupal-sub_header-blocks'>
            <!--(begin_sub_header_blocks)-->
            <?php print($page['sub_header']); ?>
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

    <?php if ($cf['show']['page']['help']){ ?>
      <!--(begin_help)-->
      <div id='mcneese_drupal-help' class='clearfix'>
        <h2 class='element-invisible'><?php print(t("Help")); ?></h2>
        <?php print($page['help']); ?>
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

    <?php if ($cf['show']['breadcrumb'] || $cf['show']['page']['subtitle']){ ?>
      <div id="mcneese_drupal-breadcrumb">
        <?php if ($cf['show']['breadcrumb']){ print($breadcrumb); } ?>
        <?php if ($cf['show']['page']['subtitle']){ ?>
          <div class="subtitle"><?php print($page['subtitle']);?></div>
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

    <?php if ($cf['show']['page']['sidebar_left']){ ?>
      <div id="mcneese_drupal-sidebar_left" class="page-sidebar_left clearfix">
        <!--(begin_sidebar_left)-->
        <h2 class='element-invisible'><?php print(t("Sidebar Left")); ?></h2>
        <?php print($page['sidebar_left']); ?>
        <!--(end_sidebar_left)-->
      </div>
    <?php } ?>

    <?php if ($cf['show']['page']['sidebar_right']){ ?>
      <div id="mcneese_drupal-sidebar_right" class="page-sidebar_right clearfix">
        <!--(begin_sidebar_right)-->
        <h2 class='element-invisible'><?php print(t("Sidebar Right")); ?></h2>
        <?php print($page['sidebar_right']); ?>
        <!--(end_sidebar_right)-->
      </div>
    <?php } ?>

    <?php if ($cf['show']['page']['content']){ ?>
      <div id='mcneese_drupal-content' class='drupal_content clearfix <?php print($cf['markup_css']['content']['class']); ?>'>
        <!--(begin_content)-->
        <h2 class='element-invisible'><?php print(t("Primary Content")); ?></h2>
        <?php print($page['content']); ?>
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
    <?php if ($cf['show']['page']['footer']) { ?>
      <div id="mcneese_drupal-footer" class="drupal_footer">
        <!--(begin_footer)-->
        <?php print($page['footer']); ?>
        <!--(end_footer)-->
      </div>
    <?php } ?>
  <?php } ?>

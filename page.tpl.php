<?php
  global $base_dir;
?><?php  if (!isset($in_overlay) || $in_overlay != 'child'){ ?>    <div id='mcneese_drupal-header_region' class='clearfix page-header'>
      <div id='mcneese_drupal-header'>
        <!--(begin_header)-->
        <?php if (isset($logo) && !empty($logo)){ ?>
          <div id='mcneese_drupal-website_logo'>
            <!--(begin_website_logo)-->
            <a id="mcneese_drupal-website_logo-link" href="<?php print($base_dir . '/'); ?>" title="Home Page">
              <img id="mcneese_drupal-website_logo-image" src="<?php print($logo); ?>" alt="<?php if (isset($site_name) && !empty($site_name)) print($site_name); ?>">
            </a>
            <!--(end_website_logo)-->
          </div>
        <?php } ?>

        <?php if (isset($page['header']) && !empty($page['header'])){ ?>
            <div id='mcneese_drupal-header-blocks'>
              <!--(begin_header_blocks)-->
              <?php print(render($page['header'])); ?>
              <!--(end_header_blocks)-->
            </div>
        <?php } ?>
        <!--(end_header)-->
      </div>

      <div id="mcneese_drupal-header-horizontal_ruler"></div>

      <div id='mcneese_drupal-sub_header'>
        <!--(begin_sub_header)-->
        <?php if (isset($page['sub_header']) && !empty($page['sub_header'])){ ?>
            <div id='mcneese_drupal-sub_header-blocks'>
              <!--(begin_sub_header_blocks)-->
              <?php print(render($page['sub_header'])); ?>
              <!--(end_sub_header_blocks)-->
            </div>
        <?php } ?>
        <!--(end_sub_header)-->
      </div>
    </div>
<?php } ?>
  <div id='mcneese_drupal-message_region' class='clearfix'>
    <?php if (isset($messages) && is_string($messages)){ ?>
      <!--(begin_messages)-->
      <div id='mcneese_drupal-messages' class='clearfix'>
        <h2 class='element-invisible'><?php print t("Messages"); ?></h2>
        <?php print($messages); ?>
      </div>
      <!--(end_messages)-->
    <?php } ?>

    <?php if (is_array($page) && array_key_exists('help', $page)){ ?>
      <!--(begin_help)-->
      <div id='mcneese_drupal-help' class='clearfix'>
        <h2 class='element-invisible'><?php print t("Help"); ?></h2>
        <?php print(render($page['help'])); ?>
      </div>
      <!--(end_help)-->
    <?php } ?>
  </div>

  <div id='mcneese_drupal-title_region' class='clearfix page-title'>
    <div id='mcneese_drupal-title'>
      <!--(begin_title)-->
      <?php if (isset($title_prefix) && !empty($title_prefix)) print(render($title_prefix)); ?>
      <?php if (isset($title) && is_string($title)){ ?>
        <h1 id='mcneese_drupal-page_title' class="drupal_page_title"><?php print($title); ?></h1>
      <?php } ?>
      <?php if (isset($title_suffix) && !empty($title_suffix)) print(render($title_suffix)); ?>
      <!--(end_title)-->
    </div>

    <?php if (isset($side_links) && is_string($side_links)) { ?>
      <div id='mcneese_drupal-side_links'>
        <!--(begin_side_links)-->
        <h2 class='element-invisible'><?php print t("Side Links"); ?></h2>
        <ul class='links'><?php print($side_links); ?></ul>
        <!--(end_side_links)-->
      </div>
    <?php } ?>

    <?php if (is_array($primary_local_tasks)){ ?>
      <div id='mcneese_drupal-primary_tabs'>
        <!--(begin_primary_tabs)-->
        <h2 class='element-invisible'><?php print t("Primary Tabs"); ?></h2>
        <ul class='tabs primary'><?php print render($primary_local_tasks); ?></ul>
        <!--(end_primary_tabs)-->
      </div>
    <?php } ?>

    <?php if (!empty($breadcrumb)){ ?>
      <div id="mcneese_drupal-breadcrumb">
        <?php print $breadcrumb; ?>
        <?php if (!empty($page['subtitle'])){ ?>
          <div class="subtitle"><?php print($page['subtitle']);?></div>
        <?php } ?>
      </div>
    <?php } ?>
  </div>

  <div id='mcneese_drupal-content_region' class='page-title'>
    <?php if ($secondary_local_tasks){ ?>
      <div id='mcneese_drupal-secondary_tabs'>
        <!--(begin_secondary_tabs)-->
        <h2 class='element-invisible'><?php print(t("Secondary Tabs")); ?></h2>
        <ul class='tabs secondary'><?php print(render($secondary_local_tasks)); ?></ul>
        <!--(end_secondary_tabs)-->
      </div>
    <?php } ?>

    <?php if ($action_links){ ?>
      <div id='mcneese_drupal-action_links'>
        <!--(begin_action_links)-->
        <h2 class='element-invisible'><?php print(t("Action Links")); ?></h2>
        <ul class="action-links"><?php print render($action_links); ?></ul>
        <!--(end_action_links)-->
      </div>
    <?php } ?>

    <?php if (!empty($page['sidebar_left'])): ?>
      <div id="mcneese_drupal-sidebar_left" class="page-sidebar_left clearfix">
        <!--(begin_sidebar_left)-->
        <h2 class='element-invisible'><?php print(t("Sidebar Left")); ?></h2>
        <?php print(render($page['sidebar_left'])); ?>
        <!--(end_sidebar_left)-->
      </div>
    <?php endif; ?>

    <?php if (!empty($page['sidebar_right'])): ?>
      <div id="mcneese_drupal-sidebar_right" class="page-sidebar_right clearfix">
        <!--(begin_sidebar_right)-->
        <h2 class='element-invisible'><?php print(t("Sidebar Right")); ?></h2>
        <?php print(render($page['sidebar_right'])); ?>
        <!--(end_sidebar_right)-->
      </div>
    <?php endif; ?>

    <?php if (is_array($page) && array_key_exists('content', $page)){ ?>
      <div id='mcneese_drupal-content' class='drupal_content clearfix <?php print($msu['markup_css']['content']['class']); ?>'>
        <!--(begin_content)-->
        <h2 class='element-invisible'><?php print(t("Primary Content")); ?></h2>
        <?php print(render($page['content'])); ?>
        <!--(end_content)-->
      </div>
    <?php } ?>
  </div>

  <div id="mcneese_drupal-content_bottom"></div>

  <?php if (!isset($in_overlay) || !$in_overlay){ ?>
    <div id="mcneese_drupal-footer" class="drupal_footer">
      <?php if (isset($page['footer']) && !empty($page['footer'])){ ?>
        <!--(begin_footer)-->
        <?php print(render($page['footer'])); ?>
        <!--(end_footer)-->
      <?php } ?>
    </div>
  <?php } else { ?>
    <div id="mcneese_drupal-undercontent" class="drupal_footer">
      <!--(begin_undercontent)-->
      <div id="mcneese_drupal-MSU" class="mcneese_drupal-outline" title='McNeese State University'>McNeese State University</div>
      <div id="mcneese_drupal-UCS" class="mcneese_drupal-outline" title='University Computing Services'>University Computing Services</div>
      <!--(end_undercontent)-->
    </div>
  <?php } ?>

<?php
/**
 * @file
 * Html theme implementation.
 */
  $cf = & drupal_static('cf_theme_get_variables', array());

  if (function_exists('mcneese_initialize_generic_tags')) {
    mcneese_initialize_generic_tags($cf);
  }

  if (function_exists('cf_theme_generate_headers')) {
    $cf['headers'] = cf_theme_generate_headers($cf);
  }

  mcneese_render_page();

  print($cf['agent']['doctype'] . "\n");
?>
<html lang="<?php if (isset($language->language)) { print($language->language); } ?>" dir="<?php if (isset($language->language)) { print($language->dir); } ?>"<?php if (isset($cf['show']['html']['rdf_namespaces']) && $cf['show']['html']['rdf_namespaces']) { print($cf['data']['html']['rdf_namespaces']); } ?>>
<head>
  <!--(begin-head)-->
  <?php print($head . "\n"); ?>
  <?php if (isset($cf['headers'])) { print($cf['headers'] . "\n"); } ?>
  <title><?php print($head_title); ?></title>
  <?php print($styles . "\n"); ?>
  <script type="text/javascript">
    // This script detects whether or not javascript is enabled and if it does, removes the no-script from the body class.
    // This allows for CSS code to react to whether or not javascript is enabled.
    function mcneese_html_body_javascript_detection() {
      document.body.removeAttribute('onLoad');
      document.body.className = document.body.className.replace(/\bno-script\b/i, 'script');
    }
  </script>
  <?php print($scripts . "\n");?>
  <!--(end-head)-->
</head>

<body class="mcneese no-script <?php if (isset($cf['markup_css']['body']['class'])) { print($cf['markup_css']['body']['class']); } ?> is-html5" <?php print($attributes);?> onload="mcneese_html_body_javascript_detection();">
  <?php if (isset($cf['is']['overlay']) && !$cf['is']['overlay'] && $cf['show']['skipnav']){ ?>
    <!--(begin-skipnav)-->
    <a id="mcneese-skip_nav" class="mcneese-skip_nav" href="#mcneese-content-main"><?php print t("Skip to main content"); ?></a>
    <!--(end-skipnav)-->
  <?php } ?>

  <!--(begin-body)-->
  <div id="mcneese-top" class="mcneese-top">
    <!--(begin-page_top)-->
    <?php if (isset($page_top)) print($page_top . "\n"); ?>
    <?php mcneese_do_print($cf, 'top'); ?>
    <!--(end-page_top)-->
  </div>

  <div id="mcneese-page" class="mcneese-page">
    <!--(begin-page)-->
    <?php
      if (function_exists('menu_local_tabs')) {
        if (isset($cf['is_data']['maintenance']['vars'])) {
          print(theme('page', $cf['is_data']['maintenance']['vars']) . "\n");
        }
      }
      else {
    ?>
        <aside id="mcneese-header" class="noscript relative expanded html_tag-aside " role="banner">
          <!--(begin-page-header)-->
          <div class="header-section header-top">
            <div id="mcneese-site-logo"><a href="/" class="site-logo" title="McNeese State University" role="img">McNeese State University</a></div>
          </div>
          <div class="header-separator"></div>
          <div class="header-section header-bottom">
            <div class="header-menu header-menu-2" role="navigation">
            </div>
          </div>
          <!--(end-page-header)-->
        </aside>

        <?php if (!empty($messages)) { ?>
          <aside title="Messages" class="relative html_tag-aside expanded" id="mcneese-messages">
            <!--(begin-page-messages)-->
            <?php print($messages); ?>
            <!--(end-page-messages)-->
          </aside>
        <?php } ?>

        <div id="mcneese-page-content" class="mcneese-content full" role="main">
          <header class="page-title html_tag-header ">
            <hgroup class="html_tag-hgroup ">
              <!--(begin-page-title)-->
              <h1 class="page-title html_tag-heading">Failed to Connect to the Database</h1>
              <!--(end-page-title)-->
            </hgroup>
          </header>

          <div id="mcneese-float-left" class="expanded fixed">
          </div>

        <div id="mcneese-content-main" role="main">
          <!--(begin-page-main)-->
          The website is unable to connect to the database.<br>
          Please contact the site administrator.
          <!--(end-page-main)-->
        </div>
      </div>
    <?php
      }
    ?>
    <!--(end-page)-->
  </div>

  <div id="mcneese-bottom" class="mcneese-bottom">
    <!--(begin-page_bottom)-->
    <?php mcneese_do_print($cf, 'bottom'); ?>
    <?php if (isset($page_bottom)) print($page_bottom . "\n"); ?>
    <!--(end-page_bottom)-->
  </div>
  <!--(end-body)-->
</body>
</html>

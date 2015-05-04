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

  if (!isset($cf['headers'])) {
    $cf['headers'] = '';
  }

  mcneese_render_page();


  // when the database is not available or the site is in maintenance mode, provide a custom page that includes common links and critical pages.
  $service_unavailable = FALSE;
  $is_unavailable = "";
  $is_maintenance = FALSE;
  $body_class = "";
  if (!function_exists('menu_local_tabs')) {
    $service_unavailable = TRUE;
    $is_unavailable = "is-unavailable ";
  }
  elseif (isset($cf['is']['maintenance']) && $cf['is']['maintenance']) {
    $service_unavailable = TRUE;
    $is_maintenance = TRUE;
  }

  if ($service_unavailable) {
    $path = NULL;
    $cf['is']['front'] = TRUE;
    $show_breadcrumb = FALSE;
    $body_class = "is-front is-anonymous is-published is-html5 is-fixed_width";

    global $base_url;
    $cf['at']['machine_name'] = preg_replace('/^.*\/\//i', '', $base_url);
    $at_sitename = preg_replace('/(\W)+/i', '_', $cf['at']['machine_name']);
    $at_sitename = 'at-' . drupal_clean_css_identifier($at_sitename, array(' ' => '-', '_' => '_', '/' => '-', '[' => '-', ']' => ''));

    if (!$is_maintenance) {
      drupal_add_http_header('Status', '503 Service Unavailable', FALSE, 503);
      drupal_send_headers();
    }
  }
  else {
    if (isset($cf['markup_css']['body']['class'])) {
      $body_class = $cf['markup_css']['body']['class'];
    }
  }

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

<body class="mcneese no-script is-maintenance <?php print($is_unavailable . $at_sitename . ' ' . $body_class); ?>" <?php print($attributes);?> onload="mcneese_html_body_javascript_detection();">
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
        <div role="navigation" class="header-menu header-menu-1">
          <nav class="menu html_tag-nav">
            <ul class="navigation_list html_tag-list">
              <li class="leaf menu_link-wrapper menu_link-my_mcneese-wrapper last"><a title="Go Back to MyMcNeese Portal" href="https://mymcneese.mcneese.edu/" class="menu_link menu_link-my_mcneese">MyMcneese</a></li>
            </ul>
          </nav>
        </div>
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
      <!--(begin-node_content)-->
      <header class="node-header html_tag-header ">
        <hgroup class="html_tag-hgroup ">
          <!--(begin-node_title)-->
          <h1 class="node-title html_tag-heading">Website Currently Unavailable</h1>
          <!--(end-node_title)-->
        </hgroup>
      </header>

      <div id="mcneese-float-left" class="expanded fixed">
      </div>

      <div id="mcneese-content-main" role="main">
        <!--(begin-page-main)-->
        <section class="html_tag-section">
          <!--(begin-node_content)-->
          <header class="node-header element-invisible html_tag-header ">
            <hgroup class="html_tag-hgroup ">
              <!--(begin-node_title)-->
              <h2 class="node-title html_tag-heading">Website Currently Unavailable</h2>
              <!--(end-node_title)-->
            </hgroup>
          </header>
          <div>
            The website is not available at this time. We apologize for any inconvenience.<br>
            <br>
          </div>
          <!--(end-page-main)-->
        </section>
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

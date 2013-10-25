<?php
/**
 * @file
 * Html theme implementation.
 */
  $cf = & drupal_static('cf_theme_get_variables', array());

  mcneese_initialize_generic_tags($cf);

  if (function_exists('cf_theme_generate_headers')) {
    $cf['headers'] = cf_theme_generate_headers($cf);
  }

  print($cf['agent']['doctype'] . "\n");
?>
<html lang="<?php print($language->language); ?>" dir="<?php print $language->dir; ?>"<?php if ($cf['show']['html']['rdf_namespaces']) print($cf['data']['html']['rdf_namespaces']); ?>>
<head>
  <!--(begin-head)-->
  <?php print($head . "\n"); ?>
  <?php print($cf['headers'] . "\n"); ?>
  <title><?php print($head_title); ?></title>
  <?php print($styles . "\n"); ?>
  <?php print($scripts . "\n");?>
  <!--(end-head)-->
</head>

<?php if (function_exists('menu_local_actions')) { ?>
  <body class="mcneese <?php print($cf['markup_css']['body']['class']); ?>" <?php print($attributes);?>>
  <?php if (isset($cf['is']['overlay']) && !$cf['is']['overlay'] && $cf['show']['skipnav']){ ?>
    <!--(begin-skipnav)-->
    <a id="mcneese-skip_nav" href="#mcneese-content-main"><?php print t("Skip to main content"); ?></a>
    <!--(end-skipnav)-->
  <?php } ?>

  <!--(begin-body)-->
  <div id="mcneese-top">
    <!--(begin-page_top)-->
    <?php if (isset($page_top)) print($page_top . "\n"); ?>
    <!--(end-page_top)-->
  </div>

  <div id="mcneese-page" >
    <!--(begin-page)-->
    <?php
      if (isset($cf['is_data']['maintenance']['vars'])) {
        print(theme('page', $cf['is_data']['maintenance']['vars']) . "\n");
      }
    ?>
    <!--(end-page)-->
  </div>

  <div id="mcneese-bottom">
    <!--(begin-page_bottom)-->
    <?php if (isset($page_bottom)) print($page_bottom . "\n"); ?>
    <!--(end-page_bottom)-->
  </div>
  <!--(end-body)-->
  </body>
<?php } else { ?>
  <body class="mcneese <?php print($cf['markup_css']['body']['class']); ?> is-html5 is-flex_width" <?php print($attributes);?>>
    <div id="mcneese-skip_nav">
      <!--(begin-skipnav)-->
      <a href="#mcneese-content-main">Skip to main content</a>
      <!--(end-skipnav)-->
    </div>

    <!--(begin-body)-->
    <div id="mcneese-top">
      <!--(begin-page_top)-->
      <!--(end-page_top)-->
    </div>

    <div id="mcneese-page">
      <!--(begin-page)-->

      <aside id="mcneese-header" class="noscript relative expanded html_tag-aside " role="banner">
        <!--(begin-page-header)-->
        <div class="header-section header-top">
          <div id="mcneese-site-logo"><a href="/" class="site-logo" title="McNeese State University" role="img"><?php print($head_title); ?></a></div>
        </div>
        <div class="header-separator"></div>
        <div class="header-section header-bottom"></div>
        <!--(end-page-header)-->
      </aside>

      <?php if (!empty($messages)) { ?>
        <aside title="Messages" class="relative html_tag-aside expanded" id="mcneese-messages">
          <!--(begin-page-messages)-->
          <?php print($messages); ?>
          <!--(end-page-messages)-->
        </aside>
      <?php } ?>

      <div id="mcneese-float-right" class="expanded fixed"></div>
      <div id="mcneese-page-content" class="full" role="main">
        <header class="page-title html_tag-header ">
          <hgroup class="html_tag-hgroup ">
            <!--(begin-page-title)-->
            <h1 class="page-title html_tag-heading">Failed to Connect to the Database</h1>
            <!--(end-page-title)-->
          </hgroup>
        </header>

        <div id="mcneese-float-left" class="expanded fixed"></div>

        <div id="mcneese-content-main" role="main">
          <!--(begin-page-main)-->
          The website is unable to connect to the database.<br>
          Please contact the site administrator.
          <!--(end-page-main)-->
        </div>
      </div>

      <aside id="mcneese-footer" class="expanded noscript html_tag-aside ">
        <!--(begin-page-footer)-->
        <!--(end-page-footer)-->
      </aside>
      <!--(end-page)-->
    </div>
    <aside id="mcneese-footer" class="expanded noscript html_tag-aside ">
    <!--(begin-page-footer)-->
    <!--(end-page-footer)-->
    </aside>

    <div id="mcneese-bottom">
      <!--(begin-page_bottom)-->
      <!--(end-page_bottom)-->
    </div>
    <!--(end-body)-->
  </body>
<?php } ?>
</html>

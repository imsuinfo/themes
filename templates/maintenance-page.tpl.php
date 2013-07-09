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

  $stp = base_path() . drupal_get_path('theme', 'mcneese_www');

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
    <div id="mcneese-skip_nav">
      <!--(begin-skipnav)-->
      <a href="#mcneese-page-main"><?php print t("Skip to main content"); ?></a>
      <!--(end-skipnav)-->
    </div>
  <?php } ?>

  <!--(begin-body)-->
  <div id="mcneese-top">
    <!--(begin-page_top)-->
    <?php if (isset($page_top)) print($page_top . "\n"); ?>
    <!--(end-page_top)-->
  </div>

  <div id="mcneese-page" >
    <!--(begin-page)-->
    <?php print(theme('page', $cf['is_data']['maintenance']['vars']) . "\n"); ?>
    <!--(end-page)-->
  </div>

  <?php print(theme('mcneese_tag', $cf['html']['tags']['mcneese_www_html_footer_open']) . "\n"); ?>
    <!--(begin-www-footer)-->
    <?php print(theme('mcneese_tag', $cf['html']['tags']['mcneese_www_html_footer_heading_open']) . "\n"); ?>
      <h2>Website Footer</h2>
    <?php print(theme('mcneese_tag', $cf['html']['tags']['mcneese_www_html_footer_heading_close']) . "\n"); ?>

    <div class="columns columns-right">
      <div class="column column-1">
        <img src="<?php print($stp); ?>/images/footer-columns-right.png" alt="" width="3" height="169">
      </div>

      <div class="column column-2">
        <?php print(theme('mcneese_tag', $cf['html']['tags']['mcneese_www_html_footer_column_heading_open']) . "\n"); ?>
          <h3 class="column-header">Contact Information</h3>
        <?php print(theme('mcneese_tag', $cf['html']['tags']['mcneese_www_html_footer_column_heading_close']) . "\n"); ?>
        <ul>
          <li>Campus: 4205 Ryan Street</li>
          <li>Lake Charles, LA</li>
          <li>Tel: 337-475-5000,</li>
          <li>or 800.622.3352</li>
        </ul>
      </div>

      <div class="column column-3">
        <?php print(theme('mcneese_tag', $cf['html']['tags']['mcneese_www_html_footer_column_heading_open']) . "\n"); ?>
          <h3 class="column-header">Map &amp; Directions</h3>
        <?php print(theme('mcneese_tag', $cf['html']['tags']['mcneese_www_html_footer_column_heading_close']) . "\n"); ?>
        <ul>
          <li>
            <a href="http://maps.google.com/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=&amp;q=McNeese+State+University,+Ryan+Street,+Lake+Charles,+LA&amp;aq=0&amp;sll=37.0625,-95.677068&amp;sspn=52.107327,76.992187&amp;ie=UTF8&amp;hq=McNeese+State+University,+Ryan+Street,+Lake+Charles,+LA&amp;z=15&amp;iwloc=A&amp;ved=0CDQQpQY&amp;sa=X&amp;ei=uX8kTpXcK5OSsAOqs6nZAw" title="Google Map of McNeese State University">
              <img alt="Snippet of Google Map for Campus" src="/files/footer/footer_map.png">
            </a>
          </li>
        </ul>
      </div>
    </div>

    <div class="columns columns-left">
    </div>

    <div class="copyright">
      <img class="copyright-logo" alt="McNeese Footer Logo" src="<?php print($stp); ?>/images/footer-logo.png" title="McNeese State University">
      <div class="copyright-menus">
      </div>
    </div>
    <!--(end-www-footer)-->
  <?php print(theme('mcneese_tag', $cf['html']['tags']['mcneese_www_html_footer_close']) . "\n"); ?>

  <div id="mcneese-bottom">
    <!--(begin-page_bottom)-->
    <?php if (isset($page_bottom)) print($page_bottom . "\n"); ?>
    <!--(end-page_bottom)-->
  </div>
  <!--(end-body)-->
  </body>
<?php } else { ?>
  <body class="mcneese <?php print($cf['markup_css']['body']['class']); ?> is-html5 is-fixed_width" <?php print($attributes);?>>
    <div id="mcneese-skip_nav">
      <!--(begin-skipnav)-->
      <a href="#mcneese-page-main">Skip to main content</a>
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
          <a href="/" class="site-logo" title="Sandbox of McNeese State University" role="image"><?php print($head_title); ?></a>
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

        <div id="mcneese-page-main" role="main">
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
    <aside id="mcneese-www-footer" class="html_tag-aside " role="navigation">
      <!--(begin-www-footer)-->
      <header class="element-invisible html_tag-header ">
        <h2>Website Footer</h2>
      </header>

      <div class="columns columns-right">
        <div class="column column-1">
          <img src="/sites/all/themes/mcneese_www/images/footer-columns-right.png" alt="" width="3" height="169">
        </div>

        <div class="column column-2">
          <header class="column-heading html_tag-header ">
            <h3 class="column-header">Contact Information</h3>
          </header>
          <ul>
            <li>Campus: 4205 Ryan Street</li>
            <li>Lake Charles, LA</li>
            <li>Tel: 337-475-5000,</li>
            <li>or 800.622.3352</li>
          </ul>
        </div>

        <div class="column column-3">
          <header class="column-heading html_tag-header ">
            <h3 class="column-header">Map &amp; Directions</h3>
          </header>
          <ul>
            <li>
              <a href="http://maps.google.com/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=&amp;q=McNeese+State+University,+Ryan+Street,+Lake+Charles,+LA&amp;aq=0&amp;sll=37.0625,-95.677068&amp;sspn=52.107327,76.992187&amp;ie=UTF8&amp;hq=McNeese+State+University,+Ryan+Street,+Lake+Charles,+LA&amp;z=15&amp;iwloc=A&amp;ved=0CDQQpQY&amp;sa=X&amp;ei=uX8kTpXcK5OSsAOqs6nZAw" title="Google Map of McNeese State University">
                <img alt="Snippet of Google Map for Campus" src="/files/footer/footer_map.png">
              </a>
            </li>
          </ul>
        </div>
      </div>

      <div class="columns columns-left">
      </div>

      <div class="copyright">
        <img class="copyright-logo" alt="McNeese Footer Logo" src="/sites/all/themes/mcneese_www/images/footer-logo.png" title="McNeese State University">
        <div class="copyright-menus">
        </div>
      </div>
      <!--(end-www-footer)-->
    </aside>

    <div id="mcneese-bottom">
      <!--(begin-page_bottom)-->
      <!--(end-page_bottom)-->
    </div>
    <!--(end-body)-->
  </body>
<?php } ?>
</html>

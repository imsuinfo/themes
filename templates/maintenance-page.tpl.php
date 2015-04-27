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
  mcneese_www_render_page();

  $stp = base_path() . drupal_get_path('theme', 'mcneese_www');

  print($cf['agent']['doctype'] . "\n");
?>
<html lang="<?php if (isset($language->language)) { print($language->language); } ?>" dir="<?php if (isset($language->language)) { print($language->dir); } ?>"<?php if (isset($cf['show']['html']['rdf_namespaces']) && $cf['show']['html']['rdf_namespaces']) { print($cf['data']['html']['rdf_namespaces']); } ?>>
<head>
  <!--(begin-head)-->
  <?php print($head . "\n"); ?>
  <?php print($cf['headers'] . "\n"); ?>
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

<body class="mcneese no-script <?php if (isset($cf['markup_css']['body']['class'])) { print($cf['markup_css']['body']['class']); } ?>" <?php print($attributes);?> onload="mcneese_html_body_javascript_detection();">
  <?php if (isset($cf['is']['overlay']) && !$cf['is']['overlay'] && $cf['show']['skipnav']){ ?>
    <div id="mcneese-skip_nav">
      <!--(begin-skipnav)-->
      <a href="#mcneese-content-main"><?php print t("Skip to main content"); ?></a>
      <!--(end-skipnav)-->
    </div>
  <?php } ?>

  <!--(begin-body)-->
  <div id="mcneese-top" class="mcneese-top">
    <!--(begin-page_top)-->
    <?php if (isset($page_top)) print($page_top . "\n"); ?>
    <?php mcneese_do_print($cf, 'top'); ?>
    <!--(end-top)-->
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
      <aside role="banner" class="noscript relative expanded html_tag-aside " id="mcneese-header">
        <!--(begin-page-header)-->
        <div class="header-section header-top">
        <div id="mcneese-site-logo"><a role="img" title="McNeese State University" class="site-logo" href="/">McNeese State University</a></div>
          <div id="mcneese-search-box">
            <div class="search_box-links">
              <ul class="navigation_list">
                <li class="search_form-top_box_links-ada"><a href="/ada">ADA</a></li>
                <div class="search_form-top_box_links-bar">|</div>
                <li class="search_form-top_box_links-staff"><a href="/search/people">Faculty &amp; Staff Search</a></li>
                <div class="search_form-top_box_links-bar">|</div>
                <li class="search_form-top_box_links-index"><a href="/index">A-Z Index</a></li>
              </ul>
            </div>
          </div>

        <div role="navigation" class="header-menu header-menu-1">
        <nav class="menu html_tag-nav">
          <ul class="navigation_list html_tag-list">
            <li class="first leaf menu_link-apply-now menu_link-apply_now menu-link-name-menu-primary-navigation menu-link-mlid-4682 id-menu-link-menu-primary-navigation-4682"><a href="https://lucy.mcneese.edu:8099/dbServer_PROD8/bwskalog.P_DispLoginNon" title="">Apply Now</a></li>
            <li class="leaf menu_link-future_students menu-link-name-menu-primary-navigation menu-link-mlid-799 id-menu-link-menu-primary-navigation-799"><a href="/future-students" title="">Future Students</a></li>
            <li class="leaf menu_link-current_students menu-link-name-menu-primary-navigation menu-link-mlid-898 id-menu-link-menu-primary-navigation-898"><a href="/current-students" title="">Students</a></li>
            <li class="leaf menu_link-online_learning menu-link-name-menu-primary-navigation menu-link-mlid-6060 id-menu-link-menu-primary-navigation-6060"><a href="/alearn" title="">Online Learning</a></li>
            <li class="leaf menu_link-faculty_staff menu-link-name-menu-primary-navigation menu-link-mlid-385 id-menu-link-menu-primary-navigation-385"><a href="/faculty-staff">Faculty &amp; Staff</a></li>
            <li class="leaf menu_link-alumni_friends menu-link-name-menu-primary-navigation menu-link-mlid-1273 id-menu-link-menu-primary-navigation-1273"><a href="/alumni-friends" title="">Alumni &amp; Friends</a></li>
            <li class="last leaf menu_link-my_mcneese menu-link-name-menu-primary-navigation menu-link-mlid-388 id-menu-link-menu-primary-navigation-388"><a href="https://mymcneese.mcneese.edu/" title="">MyMcNeese</a></li>
          </ul>
        </nav>

        </div>
        </div>
        <div class="header-separator"></div>
        <div class="header-section header-bottom">
        <div role="navigation" class="header-menu header-menu-2">
        <nav class="menu html_tag-nav">
          <ul class="navigation_list html_tag-list">
            <li class="first leaf menu_link-academics menu-link-name-menu-secondary-navigation menu-link-mlid-849 id-menu-link-menu-secondary-navigation-849"><a href="/academics">Academics</a></li>
            <li class="leaf menu_link-athletics menu-link-name-menu-secondary-navigation menu-link-mlid-850 id-menu-link-menu-secondary-navigation-850"><a href="/athletics">Athletics</a></li>
            <li class="leaf menu_link-bookstore menu-link-name-menu-secondary-navigation menu-link-mlid-851 id-menu-link-menu-secondary-navigation-851"><a href="/bookstore">Bookstore</a></li>
            <li class="leaf menu_link-calendar menu-link-name-menu-secondary-navigation menu-link-mlid-2210 id-menu-link-menu-secondary-navigation-2210"><a href="/calendar" title="">Calendar</a></li>
            <li class="leaf menu_link-campus-map menu-link-name-menu-secondary-navigation menu-link-mlid-852 id-menu-link-menu-secondary-navigation-852"><a href="/campusmaps" title="">Campus Map</a></li>
            <li class="leaf menu_link-catalog menu-link-name-menu-secondary-navigation menu-link-mlid-6058 id-menu-link-menu-secondary-navigation-6058"><a href="http://catalog.mcneese.edu" title="">Catalog</a></li>
            <li class="leaf menu_link-employment menu-link-name-menu-secondary-navigation menu-link-mlid-853 id-menu-link-menu-secondary-navigation-853"><a href="/hr/employment">Employment</a></li>
            <li class="leaf menu_link-library menu-link-name-menu-secondary-navigation menu-link-mlid-854 id-menu-link-menu-secondary-navigation-854"><a href="/library">Library</a></li>
            <li class="leaf menu_link-research menu-link-name-menu-secondary-navigation menu-link-mlid-858 id-menu-link-menu-secondary-navigation-858"><a href="/research">Research</a></li>
            <li class="last leaf menu_link-presidents-message menu-link-name-menu-secondary-navigation menu-link-mlid-1564 id-menu-link-menu-secondary-navigation-1564"><a href="/president" title="">President's Message</a></li>
          </ul>
        </nav>

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

<aside id="mcneese-www-footer" role="navigation" class="html_tag-aside ">
  <!--(begin-www-footer)-->
  <header class="element-invisible html_tag-header ">
    <h2>Website Footer</h2>
  </header>

  <div class="columns columns-right">
    <div class="column column-1">
      <img src="<?php print($stp); ?>/images/footer-columns-right.png" alt="" width="3" height="169">
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
            <img alt="Snippet of Google Map for Campus" src="<?php print($stp); ?>/images/footer_map.png">
          </a>
        </li>
      </ul>
    </div>
  </div>

  <div class="columns columns-left">
    <div class="column column-1">
      <header class="column-heading html_tag-header ">
        <h3 class="column-header"><a href="https://lucy.mcneese.edu:8099/dbServer_PROD8/bwskalog.P_DispLoginNon" title="Application">Apply Now</a></h3>
      </header>
    </div>

    <div class="column column-2">
      <header class="column-heading html_tag-header ">
        <h3 class="column-header">Courses</h3>
      </header>
      <ul>
        <li><a href="http://catalog.mcneese.edu/">Catalog</a></li>
        <li><a href="/node/412">Schedule</a></li>
      </ul>
    </div>

    <div class="column column-3">
      <header class="column-heading html_tag-header ">
        <h3 class="column-header">Explore</h3>
      </header>
      <ul>
        <li><a href="/node/250">History</a></li>
        <li><a href="/node/3287">Quick Facts</a></li>
        <li><a href="/node/5530">Campus Maps</a></li>
        <li><a href="/node/251">Mission</a></li>
        <li><a href="/node/5675">A-Z Index</a></li>
      </ul>
    </div>

    <div class="column column-4">
      <header class="column-heading html_tag-header ">
        <h3 class="column-header">Social Connection</h3>
      </header>
      <ul>
        <li class="social facebook"><a href="http://www.facebook.com/McNeeseStateU"><img alt="Facebook Icon" src="<?php print($stp); ?>/images/facebook_icon.png" title="Facebook"></a></li>
        <li class="social twitter"><a href="http://twitter.com/#!/McNeese"><img alt="Twitter Icon" src="<?php print($stp); ?>/images/twitter_icon.png" title="Twitter"></a></li>
        <li class="social linked_ln"><a href="http://www.linkedin.com/edu/mcneese-state-university-18431"><img alt="LinkedIn Icon" src="<?php print($stp); ?>/images/linkedin_icon.png" title="LinkedIn"></a></li>
        <li class="social google_plus"><a href="https://plus.google.com/101409453884998941600" rel="noreferrer"><img alt="Google+ Icon" src="<?php print($stp); ?>/images/google_plus.png" title="Google+"></a></li>
      </ul>
    </div>
  </div>

  <div class="copyright">
    <img class="copyright-logo" alt="McNeese Footer Logo" src="<?php print($stp); ?>/images/footer-logo.png" title="McNeese State University">
    <div class="copyright-menus">
      <ul class="copyright-menu copyright-menu-1">
        <li><a href="/node/269">EOE/AA/ADA</a> |</li>
        <li><a href="http://www.ulsystem.net/" title="University of Louisiana System">a member of the University of Louisiana System</a> |</li>
        <li><a href="/node/524">Web Disclaimer</a></li>
      </ul>

      <ul class="copyright-menu copyright-menu-2">
        <li><a href="/policy" title="Policy Statements">Policy Statements</a> |</li>
        <li><a href="/node/1064">University Status &amp; Emergency Preparedness</a></li>
      </ul>
    </div>
  </div>
  <!--(end-www-footer)-->
</aside>

<div id="mcneese-bottom" class="mcneese-bottom">
  <!--(begin-bottom)-->
  <?php mcneese_do_print($cf, 'bottom'); ?>
  <?php if (isset($page_bottom)) print($page_bottom . "\n"); ?>
  <!--(end-bottom)-->
</div>

<!--(end-body)-->
</body>
</html>

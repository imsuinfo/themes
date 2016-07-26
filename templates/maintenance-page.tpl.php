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
    drupal_set_title(t('Site Under Maintenance'));

    $path = NULL;
    $cf['is']['front'] = TRUE;
    $show_breadcrumb = FALSE;
    $show_title = FALSE;
    $page_title = "Website Currently Unavailable";
    $section_class = "node node-id-819 node-revision-47097 node-type-system_page node-published system_page_type-default html_tag-section";
    $body_class = "is-front is-node is-anonymous is-published is-html5 is-node-view is-fixed_width path-node_819 node-id-819 node-type-system_page system_page_type-default";

    global $base_url;
    $cf['at']['machine_name'] = preg_replace('/^.*\/\//i', '', $base_url);
    $at_sitename = preg_replace('/(\W)+/i', '_', $cf['at']['machine_name']);
    $at_sitename = 'at-' . drupal_clean_css_identifier($at_sitename, array(' ' => '-', '_' => '_', '/' => '-', '[' => '-', ']' => ''));


    if (!empty($_GET['q'])) {
      if ($_GET['q'] == 'campus_maps.html') {
        $path = 'campus_maps.html';
        $page_title = "Campus Maps";
        $body_class = "is-node is-anonymous is-published is-html5 is-node-view is-fixed_width path-node_5530 alias-campusmaps node-id-5530 node-type-document node-path-campusmaps alias-part-0-campusmaps node-theme-document-671";
        $section_class = "node node-id-5530 node-revision-41173 node-type-document node-published node-theme-document-671 html_tag-section";
      }
      elseif ($_GET['q'] == 'final_exams.html') {
        $path = 'final_exams.html';
        $show_breadcrumb = TRUE;
        $show_title = TRUE;
        $page_title = "Spring 2015 Final Exam Schedule";
        $body_class = "is-node is-anonymous is-published is-html5 is-node-view is-fixed_width path-node_5835 alias-schedule_spring2015_final_exam_schedule node-id-5835 node-type-page node-path-schedule_spring2015_final_exam_schedule alias-part-0-schedule alias-part-1-spring2015 alias-part-2-final_exam_schedule node-theme-page-650";
        $section_class = "node node-id-5835 node-revision-48634 node-type-page node-published node-theme-page-650 html_tag-section ";
      }
      elseif ($_GET['q'] == 'fee_payments.html') {
        $path = 'fee_payments.html';
        $show_breadcrumb = TRUE;
        $show_title = TRUE;
        $page_title = "Summer 2015 Fee Payment";
        $body_class = "is-node is-anonymous is-published is-html5 is-node-view is-fixed_width path-node_6497 alias-schedule_summer2015 node-id-6497 node-type-page node-path-schedule_summer2015 alias-part-0-schedule alias-part-1-summer2015 node-theme-page-650";
        $section_class = "node node-id-6497 node-revision-50913 node-type-page node-published node-theme-page-650 html_tag-section ";
      }
      elseif ($_GET['q'] == 'emergency_information.html') {
        $path = 'emergency_information.html';
        $show_breadcrumb = TRUE;
        $show_title = TRUE;
        $page_title = "Emergency Communications";
        $body_class = "is-node is-anonymous is-published is-html5 is-node-view is-fixed_width path-node_1064 alias-emergency node-id-1064 node-type-page node-path-emergency alias-part-0-emergency node-theme-page-650";
        $section_class = "node node-id-1064 node-revision-41385 node-type-page node-published node-theme-page-650 html_tag-section";
      }
      elseif ($_GET['q'] == 'ada_information.html') {
        $path = 'ada_information.html';
        $show_breadcrumb = TRUE;
        $show_title = TRUE;
        $page_title = "ADA";
        $body_class = "is-node is-anonymous is-published is-html5 is-node-view is-fixed_width path-node_269 alias-ada node-id-269 node-type-page node-path-ada alias-part-0-ada node-theme-page-650";
        $section_class = "";
      }
    }

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

<body id="mcneese-body" class="mcneese no-script is-maintenance <?php print($is_unavailable . $at_sitename . ' ' . $body_class); ?>" <?php print($attributes);?> onload="mcneese_html_body_javascript_detection();">
  <div id="mcneese-skip_nav">
    <!--(begin-skipnav)-->
    <a href="#mcneese-content-main"><?php print t("Skip to main content"); ?></a>
    <!--(end-skipnav)-->
  </div>

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
      <?php if ($service_unavailable) { ?>
      <div class="header-section header-top">
        <div id="mcneese-site-logo">
          <a href="/" class="site-logo" title="McNeese State University" role="img">McNeese State University</a>
        </div>
        <div class="header-menu header-menu-1" role="navigation">
            <!--(begin-block_content)-->
          <nav class="menu html_tag-nav ">
            <ul class="navigation_list html_tag-list">
              <li class="first leaf menu_link-apply-now menu-link-name-menu-primary-navigation menu-link-mlid-4682 id-menu-link-menu-primary-navigation-4682"><a href="https://lucy.mcneese.edu:8099/dbServer_PROD8/bwskalog.P_DispLoginNon" title="">Apply Now</a></li>
              <li class="last leaf menu_link-mymcneese menu-link-name-menu-primary-navigation menu-link-mlid-388 id-menu-link-menu-primary-navigation-388"><a href="https://mymcneese.mcneese.edu/" title="">MyMcNeese</a></li>
            </ul>
          </nav>
          <!--(end-block_content)-->
        </div>
      </div>
      <div class="header-separator"></div>
      <div class="header-section header-bottom">
        <div class="header-menu header-menu-2" role="navigation">
          <!--(begin-block_content)-->
            <?php if (!is_null($path)) { ?>
              <a href="/" class="inline-block font-size-16 line-height-20 padding-top-3 padding-left-10">Website is Currently Unavailable</a>
            <?php } ?>
          <!--(end-block_content)-->
        </div>
      </div>
      <?php } else { ?>
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
      <?php } ?>
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
      <div id="mcneese-float-left" class="expanded fixed">
      </div>

      <?php if ($show_title) { ?>
      <header class="page-title html_tag-header ">
        <hgroup class="html_tag-hgroup ">
          <!--(begin-page-title)-->
          <h1 class="page-title html_tag-heading"><?php print($page_title); ?></h1>
          <!--(end-page-title)-->
        </hgroup>
      </header>
      <?php } ?>

      <?php if ($show_breadcrumb) { ?>
        <nav role="navigation" class="relative expanded html_tag-nav" id="mcneese-breadcrumb">
        <header class="html_tag-header ">
          <hgroup class="html_tag-hgroup ">
            <h2 class="html_tag-heading">Breadcrumb</h2>
          </hgroup>
        </header>
        <!--(begin-page-breadcrumb)-->
        <ul class="navigation_list html_tag-list">
          <!--(begin-page-breadcrumb)-->
          <li class="crumb"><a title="Home" class="link-home" href="/">Home</a></li>
          <div class="crumb-trail">>></div>
          <?php if ($path == 'emergency_information.html') { ?>
          <li class="crumb"><a title="Emergency Communications" class="workbench_menu-breadcrumb" href="emergency_information.html">Emergency Communications</a></li>
          <?php } else if ($path == 'final_exams.html') { ?>
          <li class="crumb"><a title="Spring 2015 Final Exam Schedule" class="workbench_menu-breadcrumb" href="final_exams.html">Spring 2015 Final Exam Schedule</a></li>
          <?php } else if ($path == 'fee_payments.html') { ?>
          <li class="crumb"><a title="Summer 2015 Fee Payment" class="workbench_menu-breadcrumb" href="fee_payments.html">Summer 2015 Fee Payment</a></li>
          <?php } else if ($path == 'ada_information.html') { ?>
          <li class="crumb"><a title="ADA" class="workbench_menu-breadcrumb" href="ada_information.html">ADA</a></li>
          <?php } ?>
          <!--(end-page-breadcrumb)-->
        </ul>
        <!--(end-page-breadcrumb)-->
      </nav>
      <?php } ?>

      <div id="mcneese-content-main" class="mcneese-content-main" role="main">
        <!--(begin-page-main)-->
        <section class="<?php print($section_class); ?>">
          <!--(begin-node_content)-->
          <header class="node-header element-invisible html_tag-header ">
            <hgroup class="html_tag-hgroup ">
              <!--(begin-node_title)-->
              <h2 class="node-title html_tag-heading"><?php print($page_title); ?></h2>
              <!--(end-node_title)-->
            </hgroup>
          </header>

        <?php if (is_null($path)) { ?>
          <br>
          <header class="page-title html_tag-header ">
            <hgroup class="html_tag-hgroup ">
              <!--(begin-page-title)-->
              <h1 style="text-align: center;">Website Currently Unvailable</h1>
              <!--(end-page-title)-->
            </hgroup>
          </header>
          <div>
            The McNeese State University Website is not available at this time. A small set of links are provided below.  We apologize for any inconvenience. <br>
            <br>
            <ul class="inline-block vertical-align-top margin-left-44 margin-right-44 margin-bottom-25">
              <li><a href="https://lucy.mcneese.edu:8099/dbServer_PROD8/bwskalog.P_DispLoginNon">Apply Online</a></li>
              <li><a href="http://catalog.mcneese.edu/">Catalog</a></li>
              <li><a href="http://library.mcneese.edu">Library</a></li>
              <li><a href="https://mymcneese.mcneese.edu/">MyMcNeese Portal</a></li>
            </ul>
            <ul class="inline-block vertical-align-top margin-left-44 margin-right-44 margin-bottom-25">
              <li><a href="http://schedule.mcneese.edu/">Schedule</a></li>
              <li><a href="final_exams.html">Spring 2015 Final Exams</a></li>
              <li><a href="fee_payments.html">Summer 2015 Fee Payment</a></li>
            </ul>
            <ul class="inline-block vertical-align-top margin-left-44 margin-right-44 margin-bottom-25">
              <li><a href="emergency_information.html">Emergency Information</a></li>
              <li><a href="ada_information.html">EOE/AA/ADA</a></li>
              <li><a href="campus_maps.html">Campus Map</a></li>
            </ul>
          </div>

        <?php } elseif ($path == 'emergency_information.html') { ?>
          <h3 style="line-height: 1.2em; background-color: rgb(255, 255, 102);"><span style="font-size: x-large; text-decoration: underline;">Current Status:</span> - McNeese is operating under normal conditions.</h3>
          <br>
          Crisis and Emergency Communication
          <p>The safety of our students, faculty, staff and visitors is important and the University will provide current information about any situation that impacts the campus as soon as it is available using a combination of the following methods:</p>
          <ul style="list-style-image: none;">
            <li>Web (<a href="/">Home page</a>, <a href="http://mymcneese.mcneese.edu">Portal</a>, <a href="http://moodle2.mcneese.edu/">Moodle</a>)</li>
            <li>E-mail: Broadcast message to campus e-mail addresses</li>
            <li>Campus Telephones: Broadcast voice mail to all campus extensions</li>
            <li>Text Messaging: Broadcast message to cell phones of students, faculty and staff (FirstCall)</li>
            <li>Call Boxes: Broadcast message to outdoor public areas and parking lots</li>
            <li>Campus Information Line (475-5000)</li>
            <li>1-800-622-3352</li>
            <li>Outdoor Public Address System</li>
          </ul>
          <p>McNeese has an agreement with FirstCall, an emergency notification service, to deliver e-mail, voice or text messages to cell phones and land lines. Your McNeese e-mail address is automatically entered in the FirstCall database. You can add cell and land line phone numbers to receive First Call emergency messages to your home phone, cell phone and text messages to your smart phone. Additions or changes to your FirstCall information must be done through the <a href="http://mymcneese.mcneese.edu">MyMcNeese Portal.</a></p>
          <br>
          <h4>Weather Resources</h4>
          <ul>
            <li><a href="http://www.weather.gov">National Weather Service</a></li>
            <li><a href="http://www.nhc.noaa.gov">National Hurricane Center</a></li>
            <li><a href="http://www.weather.com">Weather Channel Online</a></li>
          </ul>
          <br>
          <h4>Local Media</h4>
          <ul>
            <li><a href="http://www.kplctv.com">KPLC-TV</a></li>
            <li>Radio 92.9 FM (The Lake)</li>
            <li>Radio 107.5 FM (JAMZ)</li>
            <li>Radio 99.5 FM (Gator)</li>
            <li>Radio 92.1 FM (KISS FM)</li>
            <li>Radio 1290 AM (JKEF-Jennings)</li>
            <li>Radio 1470 AM (KLCL)</li>
            <li>Radio 96.1 FM (KYKZ)</li>
            <li>Radio 101.3 FM (Rock 101)</li>
            <li>Radio 103.3 FM (Jack FM)</li>
            <li>Radio 97.9 FM (Hot 97.9)</li>
            <li>Radio 1400 AM (KAOK)</li>
            <li>Radio 104.9 FM (KZWA)</li>
            <li><a href="http://www.americanpress.com">American Press Newspaper</a></li>
          </ul>
        <?php } elseif ($path == 'campus_maps.html') { ?>
          <div style="position: relative; text-align: center; margin-left: auto; margin-right: auto;">
            <div style="position: relative;">
              <map name="mcneese_campus_map" id="mcneese_campus_map">
                <area title="Stream Alumni Center" shape="rect" href="#stream_alumni_center" coords="552,62,608,116" alt="Stream Alumni Center">
                <area title="Burton Business Center" shape="rect" href="#burton_business_center" coords="208,218,245,284" alt="Burton Business Center">
                <area title="F.G. Bulber Auditorium" shape="rect" href="#fg_bulber_auditorium" coords="136,281,182,334" alt="F.G. Bulber Auditorium">
                <area title="Jack V. Doland Athletics Complex" shape="rect" href="#jack_v_doland_athletics_complex" coords="580,0,680,55" alt="Jack V. Doland Athletics Complex">
                <area title="Drew Hall" shape="poly" href="#drew_hall" coords="428,167,494,167,494,196,518,196,518,239,474,238,474,200,428,200" alt="Drew Hall">
                <area title="H.C. Drew Enrollment Information Center" shape="rect" href="#hc_drew_enrollment_information_center" coords="444,338,476,370" alt="H.C. Drew Enrollment Information Center">
                <area title="Facility Planning &amp; Management Complex" shape="rect" href="#facility_planning_management_complex" coords="460,124,520,172" alt="Facility Planning &amp; Management Complex">
                <area title="Farrar Hall" shape="rect" href="#farrar_hall" coords="252,185,280,224" alt="Farrar Hall">
                <area title="Office of Financial Aid" shape="rect" href="#office_of_financial_aid" coords="182,200,246,212" alt="Office of Financial Aid">
                <area title="Frasch Hall" shape="rect" href="#frasch_hall" coords="328,307,422,362" alt="Frasch Hall">
                <area title="Frazar Memorial Library" shape="rect" href="#frazar_memorial_library" coords="432,265,500,327" alt="Frazar Memorial Library">
                <area title="Gayle Hall" shape="rect" href="#gayle_hall" coords="504,403,561,445" alt="Gayle Hall">
                <area title="Holbrook Student Union/Student Union Complex" shape="rect" href="#hollbrook_student_union" coords="352,216,429,260" alt="Holbrook Student Union/Student Union Complex">
                <area title="Hardtner Hall" shape="rect" href="#hardtner_hall" coords="93,122,156,163" alt="Hardtner Hall">
                <area title="Watkins Infirmary" shape="rect" href="#watkins_infirmary" coords="481,241,517,269" alt="Watkins Infirmary">
                <area title="Kaufman Hall" shape="rect" href="#kaufman_hall" coords="251,303,319,356" alt="Kaufman Hall">
                <area title="Kirkman Hall" shape="rect" href="#kirkman_hall" coords="428,201,473,257" alt="Kirkman Hall">
                <area title="President's Home" shape="rect" href="#president_home" coords="107,395,177,447" alt="President's Home">
                <area title="Shearman Fine Arts Center" shape="rect" href="#shearman_fine_arts_center" coords="45,281,135,386" alt="Shearman Fine Arts Center">
                <area title="Smith Hall" shape="rect" href="#smith_hall" coords="188,329,250,367" alt="Smith Hall">
                <area title="Student Housing and Residence Life/King, Watkins and Zigler Halls" shape="rect" href="#king_watkins_zigler_halls" coords="338,119,415,195" alt="Student Housing and Residence Life/King, Watkins and Zigler Halls">
                <area title="Student Services/Student Union Complex" shape="rect" href="#student_union_complex" coords="394,261,426,281" alt="Student Services/Student Union Complex">
                <area title="Student Union Annex" shape="rect" href="#student_union_annex" coords="331,244,355,296" alt="Student Union Annex">
                <area title="University Bookstore" shape="rect" href="#university_bookstore" coords="355,273,395,303" alt="University Bookstore">
                <area title="University Police" shape="rect" href="#university_police" coords="372,399,423,442" alt="University Police">
                <area title="Memorial Gymnasium" shape="rect" href="#memorial_gymnasium" coords="283,185,309,218" alt="Memorial Gymnasium">
                <area title="Ward Memorial Arena/Rec Complex" shape="poly" href="#recreation_complex" coords="139,165,293,165,293,183,243,183,243,199,195,199,181,199,182,212,131,212" alt="Ward Memorial Arena/Rec Complex">
                <area title="Burton Hall" shape="rect" href="#burton_hall" coords="505,312,555,385" alt="Burton Hall">
                <area title="Chozen Hall" shape="rect" href="#chozen_hall" coords="555,311,608,368" alt="Chozen Hall">
                <area title="Collette Hall" shape="rect" href="#collette_hall" coords="558,204,594,273" alt="Collette Hall">
                <area title="Sallier/Bel Gardens" shape="rect" href="#sallier_bel_gardens" coords="544,118,579,193" alt="Sallier/Bel Gardens">
                <area title="Hodges Street Field House" shape="rect" href="#hodges_street_field_house" coords="8,260,35,291" alt="Hodges Street Field House">
                <area title="Frank E. Landry Jr. Baseball Complex" shape="rect" href="#frank_e_landry_jr_baseball_complex" coords="655,68,701,115" alt="Frank E. Landry Jr. Baseball Complex">
                <area title="The Quad" shape="rect" href="#quad" coords="243,228,327,303" alt="The Quad">
                <area title="The Parking Garage" shape="rect" href="#parking_garage" coords="197,109,335,163" alt="The Parking Garage">
                <area title="The SEED Center" shape="rect" href="#seed_center" coords="296,416,344,462" alt="The SEED Center">
              </map>
              <div style="display: block; background: url(&quot;<?php print($stp); ?>/images/Campus_Map.png?&quot;) repeat scroll 0% 0% transparent; position: relative; padding: 0px; width: 720px; height: 473px;" class="jq_maphilight jq_maphilight-center"><canvas style="width: 720px; height: 473px; position: absolute; left: 0px; top: 0px; padding: 0px; border: 0px none;" height="473" width="720"></canvas><img width="720" height="473" usemap="#mcneese_campus_map" src="<?php print($stp); ?>/images/Campus_Map.png" class="jq_maphilight jq_maphilight-center maphilighted" alt="McNeese Campus Map" style="opacity: 0; position: absolute; left: 0px; top: 0px; padding: 0px; border: 0px none;"></div>
            </div>
            <hgroup style="position: relative; top: -470px; left: -100px; height: 0px;">
              <h1 style="display: inline-block; font-size: 42px; line-height: 50px; font-weight: bold; padding: 8px 0px; margin: 0px;">McNeese Campus Map</h1>
              <br>
              <h2 style="display: inline-block; font-size: 14px; line-height: 17px; font-weight: bold; padding: 6px 0px; margin: 0px;">4205 Ryan Street, Lake Charles, LA | 337-475-5000 | 800-622-3352</h2>
            </hgroup>
          </div>
          <div style="clear: both; width: 720px; margin-left: auto; margin-right: auto;" class="print-font-size-12 print-line-height-14">
            <ol style="display: inline-block; max-width: 195px; padding-right: 15px; vertical-align: top;">
              <li id="stream_alumni_center">Stream Alumni Center</li>
              <li id="burton_business_center">Burton Business Center</li>
              <li id="fg_bulber_auditorium">F.G. Bulber Auditorium</li>
              <li id="jack_v_doland_athletics_complex">Jack V. Doland Athletics Complex</li>
              <li id="drew_hall">Drew Hall</li>
              <li id="hc_drew_enrollment_information_center">H.C. Drew Enrollment Information Center</li>
              <li id="facility_planning_management_complex">Facility Planning &amp; Management Complex</li>
              <li id="farrar_hall">Farrar Hall</li>
              <li id="office_of_financial_aid">Office of Financial Aid</li>
              <li id="frasch_hall">Frasch Hall</li>
              <li id="frazar_memorial_library">Frazar Memorial Library</li>
              <li id="gayle_hall">Gayle Hall</li>
            </ol>
            <ol style="display: inline-block; max-width: 195px; padding-right: 15px; vertical-align: top;" start="13">
              <li id="hollbrook_student_union">Holbrook Student Union/Student Union Complex</li>
              <li id="hardtner_hall">Hardtner Hall</li>
              <li id="watkins_infirmary">Watkins Infirmary</li>
              <li id="kaufman_hall">Kaufman Hall</li>
              <li id="kirkman_hall">Kirkman Hall</li>
              <li id="president_home">President's Home</li>
              <li id="shearman_fine_arts_center">Shearman Fine Arts Center</li>
              <li id="smith_hall">Smith Hall</li>
              <li id="king_watkins_zigler_halls">Student Housing and Residence Life/King, Watkins and Zigler Halls</li>
              <li id="student_union_complex">Student Services/Student Union Complex</li>
              <li id="student_union_annex">Student Union Annex</li>
              <li id="university_bookstore">University Bookstore</li>
            </ol>
            <ol style="display: inline-block; max-width: 195px; padding-right: 15px; vertical-align: top;" start="25">
              <li id="university_police">University Police</li>
              <li id="memorial_gymnasium">Memorial Gymnasium</li>
              <li id="recreation_complex">Ward Memorial Arena/Rec Complex</li>
              <li id="burton_hall">Burton Hall</li>
              <li id="chozen_hall">Chozen Hall</li>
              <li id="collette_hall">Collette Hall</li>
              <li id="sallier_bel_gardens">Sallier/Bel Gardens</li>
              <li id="hodges_street_field_house">Hodges Street Field House</li>
              <li id="frank_e_landry_jr_baseball_complex">Frank E. Landry Jr. Baseball Complex</li>
              <li id="quad">The Quad</li>
              <li id="parking_garage">The Parking Garage</li>
              <li id="seed_center">The SEED Center</li>
            </ol>
          </div>
          <hr style="display: inline-block; width: 100%; height: 0px; border-style: solid; border-color: #555555; border-width: 0px 0px 1px 0px; margin: 0px; padding: 0px;">
          <h3 style="font-size: 15px; font-style: italic; font-weight: bold;">Driving Directions from the East</h3>
          <div class="print-font-size-12 print-line-height-14">Take Interstate Highway 10 west into Calcasieu Parish, Louisiana, then change to Interstate Highway 210 when approaching Lake Charles. From Interstate Highway 210, take exit 6A (Ryan Street) in Lake Charles. Turn left from the exit ramp onto Ryan Street and drive south for one mile. After you cross Sale Road, you will see the university on your left.</div>
          <br>
          <h3 style="font-size: 15px; font-style: italic; font-weight: bold;">Driving Directions from the West</h3>
          <div style="">Take Interstate Highway 10 east into Calcasieu Parish, Louisiana, then change to Interstate Highway 210 when approaching Lake Charles. From Interstate Highway 210, take exit 6A (Ryan Street) in Lake Charles. Turn left from the exit ramp onto College Street and drive east for 1/10 of a mile to the intersection of College Street and Ryan Street. Turn right onto Ryan Street and drive south for one mile. After you cross Sale Road, you will see the university on your left.</div>
          <br>
          <h3 style="font-size: 15px; font-style: italic; font-weight: bold;">Parking Map</h3>
          <div class="print-font-size-12 print-line-height-14">Visitors are entitled to the use of all parking areas and zones except specially designated reserved parking spaces for administrative personnel and service vehicles. Visitors receiving citations for parking violations are not subject to the payment of fines and penalties, but are requested to comply with the instructions found on the back of the ticket. For more information, call the McNeese Police Department at 337-475-5711.</div>
          <br>
        <?php } elseif ($path == 'final_exams.html') { ?>
          To determine the time of the final for a class, locate on the chart the class time which coincides with the day and hour of the class. Then, read the left column for the date of the final and the entry at the top of the chart for the time of the final.<br>
          <table cellspacing="1" cellpadding="1" border="1" summary="A listing of days and times of each final exam group." style="height: 327px; width: 745px;" id="table-Fxb5WyJt23Eu">
            <caption>Final Exam Schedule</caption>
            <thead>
              <tr>
                <th scope="col"></th>
                <th scope="col">09:00<br>
                  -11:00</th>
                <th scope="col">11:30<br>
                  -13:30</th>
                <th scope="col">14:00<br>
                  -16:00</th>
                <th scope="col">17:00<br>
                  -19:00</th>
                <th scope="col">19:15<br>
                  -21:15</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <th scope="row">Tuesday,<br>
                  May 5</th>
                <td></td>
                <td></td>
                <td></td>
                <td>T&nbsp;16:00-18:55<br>
                  TR 16:00-17:20</td>
                <td>T 17:30-20:25<br>
                  T 19:00-21:55<br>
                  TR 19:00-20:20</td>
              </tr>
              <tr>
                <th scope="row">Wednesday,<br>
                  May 6</th>
                <td>MWF 11:15-12:10</td>
                <td>TR 12:30-13:50</td>
                <td>MWF 14:30-15:25<br>
                  MW 14:55-16:15</td>
                <td>W 16:00-18:55<br>
                  MW 16:00-17:20</td>
                <td>W 17:30-20:25<br>
                  W 19:00-21:55</td>
              </tr>
              <tr>
                <th scope="row">Thursday,<br>
                  May 7</th>
                <td>MWF 10:10-11:05</td>
                <td>TR 09:30-10:50</td>
                <td>TR 15:30-16:50</td>
                <td>TR 17:30-18:50</td>
                <td></td>
              </tr>
              <tr>
                <th scope="row">Friday,<br>
                  May 8</th>
                <td>
                  <div style="text-align: center;">MWF 09:05-10:00</div>
                </td>
                <td>
                  <div style="text-align: center;">TR 08:00-09:20</div>
                </td>
                <td>
                  <div style="text-align: center;">MWF 13:25-14:20<br>
                    MW 13:25-14:45</div>
                </td>
                <td>M 16:00-18:55<br>
                  MW 17:30-18:50</td>
                <td>
                  <div style="text-align: center;">M&nbsp;17:30-20:25<br>
                    M&nbsp;19:00-21:55<br>
                    MW&nbsp;19:00-20:20</div>
                </td>
              </tr>
              <tr>
                <th scope="row">Saturday,<br>
                  May 9</th>
                <td>
                  <div style="text-align: center;">MWF&nbsp;08:00-8:55</div>
                </td>
                <td>
                  <div style="text-align: center;">TR&nbsp;11:00-12:20</div>
                </td>
                <td>
                  <div style="text-align: center;">MW&nbsp;15:35-16:30</div>
                </td>
                <td>
                  <div style="text-align: center;">R&nbsp;16:00-18:55<br>
                    TR 17:30-18:55</div>
                </td>
                <td>
                  <div style="text-align: center;">R&nbsp;17:30-20:25<br>
                    R&nbsp;19:00-21:55</div>
                </td>
              </tr>
              <tr>
                <th scope="row">Monday,&nbsp;<br>
                  May 11</th>
                <td>MWF 12:20-13:15</td>
                <td>TR 14:00-15:20</td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
            </tbody>
          </table>
          <br>
          <ul>
            <li>Examinations are grouped by the beginning hour and the first day that the class meets.&nbsp; (Example:&nbsp; Finals for classes that meet 13:00-14:15 are grouped with 13:00-13:50 classes.)</li>
            <br>
            <li>If final examinations for classes not conforming to the regular schedule conflict with other examinations, those classes may have examinations at the last regularly scheduled class meeting.&nbsp; Students should inform their instructor at the beginning of the semester of any conflicts in their examination schedule. Final exams for 7:00 a.m. classes should be held at the last regularly scheduled class meeting.</li>
            <br>
            <li>The Office of the Registrar does not give out the dates or times of finals over the phone.</li>
            <br>
            <li>Spring Session 4A finals are scheduled for February 2, Spring Session 4B finals are scheduled for March 5, Spring Session 4C finals are scheduled for April 2, and Spring Session 4D finals are scheduled for May 8.</li>
            <li>Spring Session 7A finals are scheduled for March 12 and Spring Session 7B finals are scheduled for May 11.</li>
            <li>Spring Session M1 finals are scheduled for March 9 and Spring Session M2 finals are scheduled for May 5.</li>
            <br>
            <li>Grades will be available May 19 &nbsp;through the Banner Self-Service link on the McNeese State University web site.</li>
          </ul>
        <?php } elseif ($path == 'fee_payments.html') { ?>
          <h3>Summer 2015 Registration Fees</h3>
          (All Fees Are Subject to Change)<br>
          <table width="80%" cellspacing="3" cellpadding="3" border="1" summary="Registration Fees for Undergraduates" id="table-nld9aEFn3KNC">
            <caption>Undergraduate Fees</caption>
            <thead>
              <tr>
                <th scope="col">Semester Hours</th>
                <th scope="col">Regular Fees</th>
                <th scope="col"><a href="#non_res_undergraduates">*Non Resident</a></th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <th scope="row">0</th>
                <td>815.75</td>
                <td>0.00</td>
              </tr>
              <tr>
                <th scope="row">1</th>
                <td>820.75</td>
                <td>0.00</td>
              </tr>
              <tr>
                <th scope="row">2</th>
                <td>848.75</td>
                <td>0.00</td>
              </tr>
              <tr>
                <th scope="row">3</th>
                <td>875.75</td>
                <td>0.00</td>
              </tr>
              <tr>
                <th scope="row">4</th>
                <td>1128.00</td>
                <td>0.00</td>
              </tr>
              <tr>
                <th scope="row">5</th>
                <td>1370.25</td>
                <td>0.00</td>
              </tr>
              <tr>
                <th scope="row">6</th>
                <td>1622.50</td>
                <td>0.00</td>
              </tr>
              <tr>
                <th scope="row">7</th>
                <td>1864.75</td>
                <td>3350.50</td>
              </tr>
              <tr>
                <th scope="row">8</th>
                <td>2107.00</td>
                <td>3787.50</td>
              </tr>
              <tr>
                <th scope="row">9</th>
                <td>2349.25</td>
                <td>4224.50</td>
              </tr>
              <tr>
                <th scope="row">10</th>
                <td>2591.50</td>
                <td>4661.00</td>
              </tr>
              <tr>
                <th scope="row">11</th>
                <td>2833.75</td>
                <td>5097.50</td>
              </tr>
              <tr>
                <th scope="row">12</th>
                <td>3076.00</td>
                <td>5534.75</td>
              </tr>
              <tr>
                <th scope="row">13</th>
                <td>3081.00</td>
                <td>5534.75</td>
              </tr>
              <tr>
                <th scope="row">14</th>
                <td>3086.00</td>
                <td>5534.75</td>
              </tr>
              <tr>
                <th scope="row">15</th>
                <td>3091.00</td>
                <td>5534.75</td>
              </tr>
              <tr>
                <th scope="row">16</th>
                <td>3096.00</td>
                <td>5534.75</td>
              </tr>
              <tr>
                <th scope="row">17</th>
                <td>3101.00</td>
                <td>5534.75</td>
              </tr>
              <tr>
                <th scope="row">18</th>
                <td>3106.00</td>
                <td>5534.75</td>
              </tr>
              <tr>
                <th scope="row">19</th>
                <td>3111.00</td>
                <td>5534.75</td>
              </tr>
              <tr>
                <th scope="row">20 &amp; Above</th>
                <td>3116.00</td>
                <td>5534.75</td>
              </tr>
            </tbody>
          </table>
          <div id="non_res_undergraduates">* Non-resident fees are in addition to regular fees.</div>
          <br>
          <br>
          <table width="80%" cellspacing="3" cellpadding="3" border="1" summary="Registration Fees for Graduate Students" id="table-w1-_La4CykvH">
            <caption>Graduate Fees</caption>
            <thead>
              <tr>
                <th scope="col">Semester Hours</th>
                <th scope="col">Regular Fees</th>
                <th scope="col"><a href="#non_res_graduates">*Non Resident</a></th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <th scope="row">0</th>
                <td>831.75</td>
                <td>0.00</td>
              </tr>
              <tr>
                <th scope="row">1</th>
                <td>866.75</td>
                <td>0.00</td>
              </tr>
              <tr>
                <th scope="row">2</th>
                <td>924.25</td>
                <td>0.00</td>
              </tr>
              <tr>
                <th scope="row">3</th>
                <td>983.25</td>
                <td>0.00</td>
              </tr>
              <tr>
                <th scope="row">4</th>
                <td>1373.00</td>
                <td>3350.50</td>
              </tr>
              <tr>
                <th scope="row">5</th>
                <td>1752.75</td>
                <td>3787.50</td>
              </tr>
              <tr>
                <th scope="row">6</th>
                <td>2142.50</td>
                <td>4224.50</td>
              </tr>
              <tr>
                <th scope="row">7</th>
                <td>2526.75</td>
                <td>4661.00</td>
              </tr>
              <tr>
                <th scope="row">8</th>
                <td>2911.00</td>
                <td>5097.50</td>
              </tr>
              <tr>
                <th scope="row">9</th>
                <td>3295.00</td>
                <td>5534.75</td>
              </tr>
              <tr>
                <th scope="row">10</th>
                <td>3308.50</td>
                <td>5534.75</td>
              </tr>
              <tr>
                <th scope="row">11</th>
                <td>3322.00</td>
                <td>5534.75</td>
              </tr>
              <tr>
                <th scope="row">12</th>
                <td>3335.50</td>
                <td>5534.75</td>
              </tr>
              <tr>
                <th scope="row">13</th>
                <td>3340.50</td>
                <td>5534.75</td>
              </tr>
              <tr>
                <th scope="row">14</th>
                <td>3345.50</td>
                <td>5534.75</td>
              </tr>
              <tr>
                <th scope="row">15</th>
                <td>3350.50</td>
                <td>5534.75</td>
              </tr>
              <tr>
                <th scope="row">16</th>
                <td>3355.50</td>
                <td>5534.75</td>
              </tr>
              <tr>
                <th scope="row">17</th>
                <td>3360.50</td>
                <td>5534.75</td>
              </tr>
              <tr>
                <th scope="row">18</th>
                <td>3365.50</td>
                <td>5534.75</td>
              </tr>
              <tr>
                <th scope="row">19</th>
                <td>3370.50</td>
                <td>5534.75</td>
              </tr>
              <tr>
                <th scope="row">20 &amp; Above</th>
                <td>3375.50</td>
                <td>5534.75</td>
              </tr>
            </tbody>
          </table>
          <div id="non_res_graduates">* Non-resident fees are in addition to regular fees.</div>
          <br>
          <br>
          <h4>Additional Course Fees</h4>
          <table width="80%" cellspacing="3" cellpadding="3" border="1" summary="Listing of additional course fees." id="table-4jXeDe021IQ3">
            <caption>Additional Course Fees</caption>
            <thead>
              <tr>
                <th scope="col">Course</th>
                <th scope="col">Fee</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Engineering 350, 450</td>
                <td>$115.00</td>
              </tr>
              <tr>
                <td>Engineering 550</td>
                <td>$165.00</td>
              </tr>
              <tr>
                <td>Electronic fee added for CZ or W sections; per credit hour</td>
                <td>$20.00</td>
              </tr>
            </tbody>
          </table>
          Tuition and fees are subject to change by action of the Louisiana Legislature, the Board of Regents, or by student vote on special assessment.<br>
          <br>
          <h3>Fee Payments</h3>
          Questions concerning fees should be addressed to Administrative Accounting at 475-5107.<br>
          <br>
          <h4><u>Regular Registration Payments Due 4:30 p.m., June 3.</u></h4>
          <br>
          A fee bill and schedule of classes will be available online on April 29. It is the student's responsibility to sign in to Banner Self-Service to view or print their bill and subsequently pay tuition and fees by the June 3 deadline.<br>
          <br>
          Fee payments may be made in person at the Cashier's Office in Smith Hall, on the web, or by mail to Administrative Accounting, Box 92935, Lake Charles, LA 70609. Payments may also be placed in the drop box located on the north side of Smith Hall. If payment is mailed, a printed copy of the online bill must be included with any check or Financial Aid/Scholarship Authorization form. Receipts for paid registration fees (whether mailed or paid in person) can be picked up at the Cashier's Office located in Smith Hall.<br>
          <br>
          Please see the payment policy for credit card payments and online payment charges.<br>
          <br>
          Payments must be received by Administrative Accounting no later than 4:30 p.m., June 3. Students who do not pay fees by this deadline will have their scheduled classes dropped and will have to reschedule classes during late registration.<br>
          <br>
          <h4><u>Late Registration Payments Due 4:30 p.m., June 11.</u></h4>
          <br>
          There will be a $75 late fee for students enrolling in more than three hours during late registration; however, first-time freshmen are exempt from this fee. All payments for late registration must be received by the Cashier's Office in Smith Hall no later than 4:30 p.m., June 11. An invoice will not be mailed for classes scheduled during late registration.<br>
          <br>
          <h4><u>Fee Deferral Plan</u></h4>
          <br>
          A student may sign a promissory note for the fee deferral plan and pay 50 percent of the account balance at the beginning of the semester. When the student completes the form, a $30 processing fee is assessed, and the student agrees to make one additional payment. The second payment (remaining balance) is due on July 1. Continuous eligibility is available to students who make payments on time. Questions about fee deferral may be addressed to Administrative Accounting at 475-5107.<br>
          <br>
          <h4>Personal Touch Account (PTA)</h4>
          <br>
          McNeese University Bookstore offers an interest-free, student charge account to all enrolled students (Personal Touch Account-PTA). With a valid McNeese ID and a current paid fee receipt, the student charge account can be opened. It can be used at the beginning of each semester for approximately one month for the purchase of up to $600 in books and supplies. At the close of the purchase deadline, each student will be billed for purchases made. Financial Aid will NOT be applied to Bookstore charges. When refund checks are received, students should make payments on PTA Accounts. Questions about PTA Accounts may be addressed to the Bookstore at 475-5494.<br>
          <table width="80%" cellspacing="3" cellpadding="3" border="1" summary="A listing of Summer 2013 important dates." id="table-E0TZuxwMcoXT">
            <caption><br>
              <strong><u>Summer 2015 Important Dates:</u></strong></caption>
            <thead>
              <tr>
                <th scope="col">Date</th>
                <th scope="col">Event</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>May 26</td>
                <td>PTA accounts open</td>
              </tr>
              <tr>
                <td>June 26</td>
                <td>PTA accounts close</td>
              </tr>
              <tr>
                <td>July 9</td>
                <td>PTA payment due</td>
              </tr>
            </tbody>
          </table>
          <br>
          <h3>Scholarships</h3>
          <br>
          Scholarships may be credited to a student's tuition and fee charges if they are available prior to the final fee payment date. To authorize the use of scholarship funds for fee payment, <strong>scholarship recipients must sign and return a printed copy of the Financial Aid/Scholarship Authorization or complete the electronic authorization through Banner Self-Service</strong>. Residual scholarship funds and those received after the final payment date will be disbursed to the students' Banner accounts on June 16. On June 16, Administrative Accounting will begin processing scholarship refunds. Please be reminded that this process can take up to two weeks to complete for all students. Beginning June 17, refunds processed by Administrative Accounting will be sent daily to Higher One for them to distribute to students based on the preference that was selected.<br>
          <br>
          PTA (Bookstore) and housing charges (Ambling) are not automatically deducted from scholarship awards. Each student is responsible for paying these charges directly.<br>
          <br>
          Full scholarship awards credited to a registered student's account will constitute a completed enrollment. It is the student's responsibility to cancel registration and resign if he or she does not plan to attend McNeese this semester. Failure to properly resign with the Office of the Registrar will result in the receipt of failing grades, and any refund of scholarship awards is the responsibility of the student.<br>
          <br>
          <h3>Financial Aid</h3>
          <br>
          <h4><u>June 16</u></h4>
          <br>
          <strong>Federal Pell Grant, Federal Perkins Loan, and Federal Stafford Loan awards will appear on the statement of account. Students must make payment arrangements on their own until they have received notification of their financial aid award.</strong><br>
          <br>
          All Financial Aid recipients must sign a financial aid authorization each semester to allow the Business Office to use their financial aid awards to assist in covering expenses. A financial aid authorization must be signed each semester even if you have a zero ($0) balance on your statement of account. Financial Aid recipients can electronically sign their financial aid authorization through <a href="/bannerselfservice/">Banner Self Service</a>.<br>
          <br>
          Pay by mail: Students who have registered during regular registration may pay by mail. Students should sign and return the online Financial Aid/Scholarship Authorization form, along with a personal check for the balance of fees not covered by financial aid, to the Cashier's Office. <strong>The signed Financial Aid/Scholarship Authorization must be returned for payment with financial aid, even if the account balance is zero.</strong><br>
          <br>
          Pay in person: Students who do not wish to pay by mail may pay fees in person at the Cashier's Office in Smith Hall until 4:30 p.m., June 3.<br>
          <br>
          Tuition, fees, and meal plan (board expense) will be charged against financial aid awards of students who sign the Financial Aid/Scholarship Authorization by mail or in person; however, the student remains responsible for full payment of charges in the event that he or she becomes ineligible for financial aid for any reason.<br>
          <br>
          Federal Perkins Loan recipients must report to the Office of Financial Aid to sign a Federal Perkins Loan promissory note prior to having funds disbursed to their student account.<br>
          <br>
          <h4 class="uppercase"><u>Financial Aid Appeals</u></h4>
          <br>
          Students who did not make satisfactory academic progress for federal student aid programs, as defined in the McNeese Catalog, will be notified by mail. These students will have a hold placed on their financial aid awards and will not be awarded financial assistance. These students have the right to appeal in writing for financial aid based on documented extenuating circumstances. Contact the Office of Financial Aid for appeal dates. All appeal letters must be received no later than two weeks before the scheduled meeting in order for the Office of Financial Aid to acquire any transcripts needed for the meeting. Appeal results will be available after 3 p.m. on the day following the appeal meeting. Additional appeal meetings will occur once every two months thereafter. Denied appeals result in the cancellation of financial aid awards, and students must re-establish their eligibility.<br>
          <br>
          <h4><u>June 3</u></h4>
          Fee Payment Deadline: All registrants, including financial aid recipients, who have not paid tuition by the published deadlines will have their schedules purged. Even if the statement of account has a zero ($0) balance, all financial aid recipients must sign and return the Financial Aid/Scholarship Authorization to the Cashier's Office by the payment deadline in order to use the financial assistance to pay for semester expenses.<br>
          <br>
          <h4><u>June 16</u></h4>
          Financial Aid Awards Disbursed: Federal Pell Grant, GO Grant, Federal Perkins Loan and Federal Stafford Loan awards will be disbursed to the Banner student accounts on this date. All federal programs will be disbursed based on your actual enrollment. A student must be enrolled and attending at least six hours at the time of disbursement for federal student loans. All graduate students in a graduate program must be enrolled and attending at least six graduate hours at the time of disbursement to remain eligible for any federal student loans. All funds for these programs must be disbursed before the last day of classes. This process can take up to two weeks to complete for all students.<br>
          <br>
          Exception: First-time Stafford Loan recipients with less than 30 semester hours earned cannot be processed until July 9. Any refunds are processed by Administrative Accounting and then sent to Higher One for distribution.<br>
          <br>
          <h4><u>June 17</u></h4>
          Administrative Accounting will send the first refunds to Higher One for them to distribute those refunds to students based on the preference that was selected. Refunds will be sent daily to Higher One for students as their refunds are processed by Administrative Accounting.<br>
          <br>
          Financial Aid and PTA Accounts: PTA charges are not automatically deducted from financial aid awards. Each student is responsible for paying the entire balance owed on a PTA account at the McNeese Bookstore.<br>
          <br>
          Financial Aid and Housing Expense: Housing expenses are not automatically deducted from financial aid awards. Students are responsible for paying their housing expenses directly to Ambling.<br>
          <br>
          Students who have received a financial aid notification letter stating the amount of financial aid awarded need to confirm their award amount with financial aid and be sure to sign the financial aid authorization after they register for classes. Students, who have not received a financial aid notification letter, should check with the Office of Financial Aid after registering to determine eligibility to use financial aid toward semester expenses.<br>
          <br>
          <h4><u>How Dropping or Adding Classes Affects Financial Aid</u></h4>
          <br>
          All federal programs will be disbursed based on your actual enrollment and degree status at the end of late registration. However, attendance is monitored during the semester and all federal programs will be reduced for financial aid recipients who never attend classes. These students may owe a refund to the Federal aid program and should inquire at the Office of Financial Aid prior to dropping or withdrawing from classes. Students who add classes during registration should notify the Office of Financial Aid to verify award(s).<br>
          <br>
          <h4><u>How Withdrawal from the University Affects Financial Aid</u></h4>
          <br>
          To withdraw from a class after late registration ends, a student must complete a Course Withdrawal Form with his/her advisor and submit the completed form to the Office of the Registrar. The student will then be withdrawn from the course, and a grade of "WZ" will be assigned. Withdrawal from a course becomes official only after the properly completed Course Withdrawal Form has been received and recorded in the Office of the Registrar. Students must keep a copy of the official form in the case of a discrepancy.<br>
          <br>
          Although 'WZ' grades do not affect a student's grade point average, excessive course withdrawals reflect negatively on the student's record, increase the amount of time needed for degree completion, and may result in the loss of scholarships and other types of financial aid. Because of this, the University will assess a per course withdrawal fee of $50 for excessive course withdrawals beyond an established limit.
          <div><br>
            <ol style="list-style-type: lower-alpha;" start="1">
              <li>Effective Fall 2013, an undergraduate student may only withdraw ('WZ' grade) from six undergraduate courses (numbered 000-499) during his/her academic career at McNeese, without incurring the per course withdrawal fee for excessive withdrawals.</li>
              <li>If a student exceeds the specified number of course withdrawals ('WZ' grades), the student will be assessed the course withdrawal fee for each course over the limit. The student must pay the charges prior to submitting the course withdrawal form to the Registrar's Office for processing.</li>
              <li>The following drops and withdrawals are not counted toward the course withdrawal limit:
                <ol style="list-style-type: lower-roman;" start="1">
                  <li>Course drops during registration where withdrawal grades are not assigned</li>
                  <li>Any withdrawal before Fall 2013</li>
                  <li>Withdrawals resulting from resignation from the University ('W' grades)</li>
                  <li>Withdrawals resulting from military activation ('WM' grades)</li>
                  <li>Withdrawals resulting from administrative action ('WX' grades)</li>
                  <li>Withdrawals from ORIN 101 or MAAP 200</li>
                  <li>Withdrawals from linked lab sections where no credit or grade is assigned (Ex: BIOL 225 LA - 0 credits)</li>
                  <li>Withdrawals earned at other institutions</li>
                </ol>
              </li>
              <li>The amount of the per course withdrawal fee for excessive course withdrawals is subject to change without prior notice.</li>
            </ol>
          </div>
          <div></div>
          A student who wishes to withdraw from all courses must resign from the University. A student who officially resigns from the University prior to a designated date will receive a grade of 'W' in all courses; however, if a student previously withdrew from any courses, the assigned grade of 'WZ' will not be replaced with a grade of 'W'. Courses with a grade of 'W' are not counted toward the course withdrawal limit. A resignation form may be completed at the Office of the Registrar. A student who is unable to come to campus may mail or fax a signed letter of resignation to the Office of the Registrar. The letter of resignation must include the student's full printed name, address, student ID number, signature and date. The resignation is effective when the completed form or letter is received in the Office of the Registrar.
          <div></div>
          <div>The deadline for withdrawing from a course or resigning from the University is approximately 75 percent into the semester or summer session. After this date, a student may not withdraw from a course or resign from the University. In extraordinary cases, a student may appeal to his/her academic dean to withdraw from a course or resign after the published deadline, but before final exams begin. Any approved requests must be submitted by the dean to the Registrar's Office prior to the beginning of the final exams.</div>
          <h4>Cancellation of Registration</h4>
          Students who complete the registration process then decide not to attend McNeese must officially cancel their registration and resign from the university before the end of late registration or tuition and fees will be assessed. To officially resign, a student must notify the Office of the Registrar in writing by mail or fax (337) 562-4234, or in person before the end of late registration. The statement requesting cancellation of registration must include the student's full printed name, address, student ID number, signature and date.<br>
          <br>
          <h4>Refund Schedule</h4>
          <br>
          The date that a student officially resigns (withdraws from all classes for which he or she is registered) from the University with the Office of the Registrar determines whether fees are refunded and at what percentage. Resignations received after close of business in the Office of the Registrar are considered received on the next business day and are processed as such. The refund policy and resignation dates apply to off-campus classes as well as on-campus classes. The schedule refers to calendar days, including weekends, beginning with the first day of classes as designated in the official University calendar. If the deadline falls on a weekend or holiday on which the University is closed for business, the student will have until close of business the following business day. Fees paid for late registration or fee deferral processing will not be refunded. Refer to the current issue of the McNeese Catalog for more information.<br>
          <br>
          <table width="80%" cellspacing="3" cellpadding="3" border="1" summary="The Registrar Defined Refund Schedule" id="table-sQcLlS4U9rBD">
            <caption><strong>Summer 2015 Refund Schedule</strong></caption>
            <thead>
              <tr>
                <th scope="col">If the student resigns</th>
                <th scope="col">The percent of fees refunded will be</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <th scope="row">by June 9</th>
                <td>100 percent less $10</td>
              </tr>
              <tr>
                <th scope="row">June 10 - June 14</th>
                <td>80 percent</td>
              </tr>
              <tr>
                <th scope="row">June 15 - June 17</th>
                <td>60 percent</td>
              </tr>
              <tr>
                <th scope="row">June 18 - June 21</th>
                <td>40 percent</td>
              </tr>
              <tr>
                <th scope="row">June 22 - June 24</th>
                <td>20 percent</td>
              </tr>
              <tr>
                <th scope="row">After June 24</th>
                <td>There is no refund of fees for resigning from the University.</td>
              </tr>
            </tbody>
          </table>
          <br>
          <div style="text-align: center;"><strong>Refunds for mini-sessions will be prorated accordingly.<br>
          <br>
          There is no refund for withdrawn classes when the student remains enrolled in other classes.</strong></div>
        <?php } elseif ($path == 'ada_information.html') { ?>
          <div class="margin-bottom-10">
            <p>McNeese State University is committed to ensuring equal access to information for all its constituencies. This policy establishes minimum standards for the accessibility of web based information and services considered necessary to meet this goal and ensure compliance with applicable state and federal regulations. Official Web Pages and associated web based services developed by or for a college, department, program or unit of the University. All new and redesigned Web pages published by any university college, department, program, or unit after 9/1/2010 must be in compliance with the DOJ agreement to meet accessible web standards Web pages published prior to 8/31/2010 are considered Legacy Pages. Legacy pages are to be brought into compliance over the course of updating university web site.</p>
          </div>
          <h3>Reporting</h3>
          <div class="margin-bottom-10">
            <p>A status report summarizing the progress towards fully accessible web space over the past year and targets for the upcoming year shall be included in the annual reports to the ADA Coordinator.</p>
          </div>
          <h3>Review</h3>
          <div class="margin-bottom-10">
            <p>The ADA Coordinator will initiate a review and necessary revisions of this policy the associated standards at least once every three years. The review group will include designees from the University's Chief Information Officer, Academic Affairs, Diversity and the office currently responsible for managing the University's home page.</p>
          </div>
          <h3>Priorities</h3>
          <div class="margin-bottom-10">
            <p>For setting priorities to make Legacy Pages accessible, the following guidance is suggested:</p>
          </div>
          <div class="margin-bottom-10">
            <h4>First Year: Tier 1 pages</h4>
            <div class="margin-bottom-10">
              <p>Tier 1 Pages are:</p>
              <ul>
                <li>The first two layers of the university website</li>
                <li>Pages that individuals must access to effectively participate in a program to utilize a service offered by any unit of the University.</li>
                <li>Web pages specifically requested to be made accessible as part of a formal accommodation request shall be made accessible as soon as possible, or an equally effective alternative shall be provided. Equally effective means that it communicates the same information in as timely a fashion as does the web page.</li>
              </ul>
            </div>
            <h4>Second Year: remaining Legacy Pages</h4>
            <div class="margin-bottom-10">
              <p>University entities developing Web pages for a federal or state agency may use the University's Web accessibility policy standards. Exception: Where a federal agency requires a Web page to be developed to a higher standard of accessibility than does the University, the higher standard shall be used.</p>
            </div>
          </div>
          <h3>The university homepage and the ADA page should have a statement reading:</h3>
          <div class="margin-bottom-10">
            <p>If you have trouble accessing any page on the MSU site, contact <a href="mailto:access@mcneese.edu">access@mcneese.edu</a>. Note: The addition of a contact person is not sufficient, in and of itself, in meeting accessibility guidelines.</p>
          </div>
          <h3>Academic Adjustments, Accommodations, or Reasonable Accommodations</h3>
          <div class="margin-bottom-10">
            <p>Persons needing academic adjustments, accommodations, or reasonable accommodations as provided by the Americans with Disabilities Act (ADA) of 1990, as Amended, should contact the following University personnel:</p>
          </div>
          <div class="margin-bottom-10">
            <h4>Employment and Applicants:</h4>
            <div class="margin-bottom-10">
              <p>Dr. Michael T. Snowden, Ph.D., Chief Diversity Officer, Title IX Coordinator, and ADA Coordinator, BBC, Room 404, (337) 475-5428; TDD/TTY, hearing impaired (337) 562-4227 or <a href="mailto:cdo@mcneese.edu">cdo@mcneese.edu</a>. Dr. Snowden ensures compliance with the Americans with Disabilities Act of 1990, the ADA Amendments of 2008, Sections 503 and 504 of the Rehabilitation of 1973, other federal and state laws and regulations pertaining to persons with disabilities, and receives complaints regarding ADA access issues. Individuals interested in additional information can visit the U.S. Department of Labor website or contact Dr. Michael Snowden, ADA Coordinator.</p>
            </div>
            <h4>Accommodations for Students:</h4>
            <div class="margin-bottom-10">
              <p>Tim Delaney, Director of Services for Students with Disabilities, Drew Hall, Room 200, (337) 475-5916; TDD/TTY, hearing impaired (337) 562-4227 or ssd@mcneese.edu or tdelaney@mcneese.edu. Tim Delaney is the contact person for academic adjustments for students and student concerns.</p>
            </div>
            <h4>Accommodations for Visitors:</h4>
            <div class="margin-bottom-10">
              <p>Visitors requesting accommodations should contact the department sponsoring the program, event, or activity. On the MSU campus, visitors should contact Dr. Michael Snowden, Chief Diversity Officer and ADA Coordinator, BBC, Room 404, (337) 475-5428; TDD/TTY, hearing impaired (337) 562-4227 or <a href="mailto:cdo@mcneese.edu">cdo@mcneese.edu</a>.</p>
            </div>
          </div>
          <h3>Resources for web design</h3>
          <div class="margin-bottom-10">
            <ul>
              <li><a href="http://www.ada.gov/websites2.htm">Accessibility of State and Local Government Websites to People with Disabilities <img style="width: 12px; height: 12px;" src="/images/Icon_External_Link.png" alt="external link"></a></li>
              <li><a href="http://www.ada.gov/pcatoolkit/chap5toolkit.htm">ADA Tool Kit: Website Accessibility Under Title II of the ADA <img style="width: 12px; height: 12px;" src="/images/Icon_External_Link.png" alt="external link"></a></li>
            </ul>
          </div>
        <?php } ?>
          <!--(end-node_content)-->
        </section>
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
        <?php if (!$service_unavailable) { ?>
        <li><a href="/node/412">Schedule</a></li>
        <?php } ?>
      </ul>
    </div>

    <?php if (!$service_unavailable) { ?>
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
    <?php } ?>

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
        <?php if ($service_unavailable) { ?>
        <li><a href="/ada_information.html">EOE/AA/ADA</a> |</li>
        <li><a href="http://www.ulsystem.net/" title="University of Louisiana System">a member of the University of Louisiana System</a> |</li>
        <?php } else { ?>
        <li><a href="/node/269">EOE/AA/ADA</a> |</li>
        <li><a href="http://www.ulsystem.net/" title="University of Louisiana System">a member of the University of Louisiana System</a> |</li>
        <li><a href="/node/524">Web Disclaimer</a></li>
        <?php } ?>
      </ul>

      <ul class="copyright-menu copyright-menu-2">
        <?php if ($service_unavailable) { ?>
        <li><a href="/node/1064">University Status &amp; Emergency Preparedness</a></li>
        <?php } else { ?>
        <li><a href="/policy" title="Policy Statements">Policy Statements</a> |</li>
        <li><a href="/node/1064">University Status &amp; Emergency Preparedness</a></li>
        <?php } ?>
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

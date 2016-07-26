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

    // when TRUE, desginates that the headers and then only the content are immediately sent.
    // this is used to transfer things such as PDF files (as opposed to an HTML page).
    $send_content_then_exit = FALSE;

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

    $page_content = NULL;
    if (!empty($_GET['q'])) {
      if ($_GET['q'] == 'campus_maps.html') {
        $path = 'campus_maps.html';
        $page_title = "Campus Maps";
        $body_class = "is-node is-anonymous is-published is-html5 is-node-view is-fixed_width path-node_5530 alias-campusmaps node-id-5530 node-type-document node-path-campusmaps alias-part-0-campusmaps node-theme-document-671";
        $section_class = "node node-id-5530 node-type-document node-published node-theme-document-671 html_tag-section";
        $page_content_file = variable_get('error_document_file-content_unavailable-campus_maps', '/var/www/error_documents/content_unavailable/www/campus_maps.html');
        if (is_string($page_content_file) && file_exists($page_content_file)) {
          $page_content = file_get_contents($page_content_file);
        }
        unset($page_content_file);
      }
      elseif ($_GET['q'] == 'final_exams.html') {
        $path = 'final_exams.html';
        $show_breadcrumb = TRUE;
        $show_title = TRUE;
        $page_title = variable_get('error_document_file-content_unavailable-final_exams-page_title', 'Final Exam Schedule');
        $page_class_body = variable_get('error_document_file-content_unavailable-final_exams-page_class_body', '');
        $page_class_section = variable_get('error_document_file-content_unavailable-final_exams-page_class_section', '');
        $body_class = "is-node is-anonymous is-published is-html5 is-node-view is-fixed_width node-type-page ";
        $section_class = "node node-type-page node-published html_tag-section ";
        $page_content_file = variable_get('error_document_file-content_unavailable-final_exams', '/var/www/error_documents/content_unavailable/www/final_exams.html');
        if (is_string($page_content_file) && file_exists($page_content_file)) {
          $page_content = file_get_contents($page_content_file);
        }
        unset($page_content_file);

        if (is_string($page_class_body)) {
          $body_class .= $page_class_body;
        }
        unset($page_class_body);

        if (is_string($page_class_section)) {
          $section_class .= $page_class_section;
        }
        unset($page_class_body);
      }
      elseif ($_GET['q'] == 'fee_payments.html') {
        $path = 'fee_payments.html';
        $show_breadcrumb = TRUE;
        $show_title = TRUE;
        $page_title = variable_get('error_document_file-content_unavailable-final_exams-fee_payments', 'Registrations Fees');
        $page_class_body = variable_get('error_document_file-content_unavailable-fee_payments-page_class_body', '');
        $page_class_section = variable_get('error_document_file-content_unavailable-fee_payments-page_class_section', '');
        $body_class = "is-node is-anonymous is-published is-html5 is-node-view is-fixed_width node-type-page ";
        $section_class = "node node-type-page node-published html_tag-section ";
        $page_content_file = variable_get('error_document_file-content_unavailable-fee_payments', '/var/www/error_documents/content_unavailable/www/fee_payments.html');
        if (is_string($page_content_file) && file_exists($page_content_file)) {
          $page_content = file_get_contents($page_content_file);
        }
        unset($page_content_file);

        if (is_string($page_class_body)) {
          $body_class .= $page_class_body;
        }
        unset($page_class_body);

        if (is_string($page_class_section)) {
          $section_class .= $page_class_section;
        }
        unset($page_class_body);
      }
      elseif ($_GET['q'] == 'emergency_information.html') {
        $path = 'emergency_information.html';
        $show_breadcrumb = TRUE;
        $show_title = TRUE;
        $page_title = "Emergency Communications";
        $body_class = "is-node is-anonymous is-published is-html5 is-node-view is-fixed_width path-node_1064 alias-emergency node-id-1064 node-type-page node-path-emergency alias-part-0-emergency node-theme-page-650";
        $section_class = "node node-id-1064 node-type-page node-published node-theme-page-650 html_tag-section";
        $page_content_file = variable_get('error_document_file-content_unavailable-emergency_information', '/var/www/error_documents/content_unavailable/www/emergency_information.html');
        if (is_string($page_content_file) && file_exists($page_content_file)) {
          $page_content = file_get_contents($page_content_file);

          $page_content = preg_replace('@node/2657@i', 'emergency_information-hurricane.html', $page_content);
        }
        unset($page_content_file);
      }
      elseif ($_GET['q'] == 'emergency_information-hurricane.html') {
        $path = 'emergency_information-hurricane.html';
        $show_breadcrumb = TRUE;
        $show_title = TRUE;
        $page_title = "Hurricane Season";
        $body_class = "is-node is-anonymous is-published is-html5 is-node-view is-fixed_width path-node_2657 alias-emergency_hurricane node-id-2657 node-type-page node-path-emergency_hurricane alias-part-0-emergency alias-part-1-hurricane node-theme-page-650";
        $section_class = "node node-id-2657 node-type-page node-published node-theme-page-650 html_tag-section";
        $page_content_file = variable_get('error_document_file-content_unavailable-emergency_information-hurricane', '/var/www/error_documents/content_unavailable/www/emergency_information-hurricane.html');
        if (is_string($page_content_file) && file_exists($page_content_file)) {
          $page_content = file_get_contents($page_content_file);
        }
        unset($page_content_file);
      }
      elseif ($_GET['q'] == 'ada_information.html') {
        $path = 'ada_information.html';
        $show_breadcrumb = TRUE;
        $show_title = TRUE;
        $page_title = "ADA";
        $body_class = "is-node is-anonymous is-published is-html5 is-node-view is-fixed_width path-node_269 alias-ada node-id-269 node-type-page node-path-ada alias-part-0-ada node-theme-page-650";
        $section_class = "";
        $page_content_file = variable_get('error_document_file-content_unavailable-ada_information', '/var/www/error_documents/content_unavailable/www/ada_information.html');
        if (is_string($page_content_file) && file_exists($page_content_file)) {
          $page_content = file_get_contents($page_content_file);
        }
        unset($page_content_file);
      }
      else {
        // handle special cases (files), but if the files do not exist fallback to the default catch-all URL.
        if ($_GET['q'] == '/f/c/c368d763' || $_GET['q'] == '/f/c/c368d763/GetAGamePlan.jpg') {
          $send_content_then_exit = TRUE;
          $page_content_file = variable_get('error_document_file-content_unavailable-GetAGamePlan.jpg', '/var/www/error_documents/content_unavailable/www/files/GetAGamePlan.jpg');
        }
        elseif ($_GET['q'] == '/f/c/27420ea7' || $_GET['q'] == '/f/c/27420ea7/page_white_acrobat.png') {
          $send_content_then_exit = TRUE;
          $page_content_file = variable_get('error_document_file-content_unavailable-page_white_acrobat.png', '/var/www/error_documents/content_unavailable/www/files/page_white_acrobat.png');
        }
        elseif ($_GET['q'] == '/f/c/97e1b35f' || $_GET['q'] == '/f/c/97e1b35f/EPT_Phases.pdf') {
          $send_content_then_exit = TRUE;
          $page_content_file = variable_get('error_document_file-content_unavailable-EPT_Phases.pdf', '/var/www/error_documents/content_unavailable/www/files/EPT_Phases.pdf');
        }
        elseif ($_GET['q'] == '/f/c/039a0197' || $_GET['q'] == '/f/c/039a0197/Hurricane%20Checklist.pdf') {
          $send_content_then_exit = TRUE;
          $page_content_file = variable_get('error_document_file-content_unavailable-Hurricane_Checklist.pdf', '/var/www/error_documents/content_unavailable/www/files/Hurricane_Checklist.pdf');
        }
        elseif ($_GET['q'] == '/f/c/c2bb1689' || $_GET['q'] == '/f/c/c2bb1689/Student%20Disaster%20Preparedness%20Tips.pdf') {
          $send_content_then_exit = TRUE;
          $page_content_file = variable_get('error_document_file-content_unavailable-Student_Disaster_Preparedness_tips.pdf', '/var/www/error_documents/content_unavailable/www/files/Student_Disaster_Preparedness_tips.pdf');
        }
        elseif ($_GET['q'] == '/f/c/ce4e048b' || $_GET['q'] == '/f/c/ce4e048b/disasterpreparedness-2015.pdf') {
          $send_content_then_exit = TRUE;
          $page_content_file = variable_get('error_document_file-content_unavailable-Hurricane_Emergency_Operations_Plan.pdf', '/var/www/error_documents/content_unavailable/www/files/Hurricane_Emergency_Operations_Plan.pdf');
        }
      }
    }

    if (!$is_maintenance) {
      drupal_add_http_header('Status', '503 Service Unavailable', FALSE, 503);
      drupal_send_headers();
    }


    if ($send_content_then_exit && file_exists($page_content_file)) {
      if (readfile($page_content_file)) {
        exit();
      }
    }
  }
  else {
    if (isset($cf['markup_css']['body']['class'])) {
      $body_class = $cf['markup_css']['body']['class'];
    }
  }

  if (is_null($page_content)) {
    $registration_fees_title = variable_get('error_document_file-content_unavailable-final_exams-fee_payments', 'Registrations Fees');
    $final_exams_title = variable_get('error_document_file-content_unavailable-final_exams-final_exams', 'Final Exams');

    if (!is_string($registration_fees_title)) {
      $registration_fees_title = 'Registrations Fees';
    }

    if (!is_string($final_exams_title)) {
      $final_exams_title = 'Final Exams';
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
          <div class="crumb-trail">»</div>
          <?php if ($path == 'emergency_information.html') { ?>
          <li class="crumb"><a title="Emergency Communications" class="workbench_menu-breadcrumb" href="emergency_information.html">Emergency Communications</a></li>
          <?php } elseif ($path == 'emergency_information-hurricane.html') { ?>
          <li class="crumb"><a title="Hurricane Season" class="workbench_menu-breadcrumb" href="emergency_information-hurricane.html">Hurricane Season</a></li>
          <?php } elseif ($path == 'final_exams.html') { ?>
          <li class="crumb"><a title="Final Exam Schedule" class="workbench_menu-breadcrumb" href="final_exams.html"><?php print($page_title); ?></a></li>
          <?php } elseif ($path == 'fee_payments.html') { ?>
          <li class="crumb"><a title="Registration Fees" class="workbench_menu-breadcrumb" href="fee_payments.html"><?php print($page_title); ?></a></li>
          <?php } elseif ($path == 'ada_information.html') { ?>
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

        <?php if (is_null($page_content)) { ?>
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
              <li><a href="final_exams.html"><?php print($final_exams_title); ?></a></li>
              <li><a href="fee_payments.html"><?php print($registration_fees_title); ?></a></li>
            </ul>
            <ul class="inline-block vertical-align-top margin-left-44 margin-right-44 margin-bottom-25">
              <li><a href="emergency_information.html">Emergency Information</a></li>
              <li><a href="ada_information.html">EOE/AA/ADA</a></li>
              <li><a href="campus_maps.html">Campus Map</a></li>
            </ul>
          </div>
        <?php } else {
          print($page_content);
        } ?>
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

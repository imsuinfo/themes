<?php
/**
 * @file
 * Html theme implementation.
 */
  $cf = & drupal_static('cf_theme_get_variables', array());

  if (!isset($cf['generic']['tags'])) {
    mcneese_initialize_generic_tags($cf);
  }

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
  <meta name="viewport" content="width=device-width, initial-scale=1">
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

<body id="mcneese-body" class="mcneese no-script <?php print($cf['markup_css']['body']['class']); ?>" <?php print($attributes);?> onload="mcneese_html_body_javascript_detection();">
<?php if (!$cf['is']['overlay'] && $cf['show']['skipnav']){ ?>
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
  <?php if (isset($page)) print($page . "\n"); ?>
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
            <img alt="Snippet of Google Map for Campus" src="<?php print($stp); ?>/images/footer_map.png">
          </a>
        </li>
      </ul>
    </div>
  </div>

  <div class="columns columns-left">
    <div class="column column-1">
      <?php print(theme('mcneese_tag', $cf['html']['tags']['mcneese_www_html_footer_column_heading_open']) . "\n"); ?>
        <h3 class="column-header"><a href="/node/5683" title="Application">Apply Now</a></h3>
      <?php print(theme('mcneese_tag', $cf['html']['tags']['mcneese_www_html_footer_column_heading_close']) . "\n"); ?>
    </div>

    <div class="column column-2">
      <?php print(theme('mcneese_tag', $cf['html']['tags']['mcneese_www_html_footer_column_heading_open']) . "\n"); ?>
        <h3 class="column-header">Courses</h3>
      <?php print(theme('mcneese_tag', $cf['html']['tags']['mcneese_www_html_footer_column_heading_close']) . "\n"); ?>
      <ul>
        <li><a href="/node/1117">Catalog</a></li>
        <li><a href="/node/412">Schedule</a></li>
      </ul>
    </div>

    <div class="column column-3">
      <?php print(theme('mcneese_tag', $cf['html']['tags']['mcneese_www_html_footer_column_heading_open']) . "\n"); ?>
        <h3 class="column-header">Explore</h3>
      <?php print(theme('mcneese_tag', $cf['html']['tags']['mcneese_www_html_footer_column_heading_close']) . "\n"); ?>
      <ul>
        <li><a href="/node/250">History</a></li>
        <li><a href="/node/3287">Quick Facts</a></li>
        <li><a href="/node/5530">Campus Maps</a></li>
        <li><a href="/node/251">Mission</a></li>
        <li><a href="/node/5675">A-Z Index</a></li>
        <li><a href="/node/8713">Consumer Disclosures</a></li>
      </ul>
    </div>

    <div class="column column-4">
      <?php print(theme('mcneese_tag', $cf['html']['tags']['mcneese_www_html_footer_column_heading_open']) . "\n"); ?>
        <h3 class="column-header">Social Connection</h3>
      <?php print(theme('mcneese_tag', $cf['html']['tags']['mcneese_www_html_footer_column_heading_close']) . "\n"); ?>
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
<?php print(theme('mcneese_tag', $cf['html']['tags']['mcneese_www_html_footer_close']) . "\n"); ?>

<div id="mcneese-bottom" class="mcneese-bottom">
  <!--(begin-bottom)-->
  <?php mcneese_do_print($cf, 'bottom'); ?>
  <?php if (isset($page_bottom)) print($page_bottom . "\n"); ?>
  <!--(end-bottom)-->
</div>

<!--(end-body)-->
</body>
</html>

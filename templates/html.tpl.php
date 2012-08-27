<?php
/**
 * @file
 * Html theme implementation.
 */
  $cf = & drupal_static('cf_theme_get_variables', array());

  if (function_exists('cf_theme_generate_headers')) {
    $cf['headers'] = cf_theme_generate_headers($cf);
  }

  $stp = base_path() . drupal_get_path('theme', 'mcneese_www');

  print($cf['agent']['doctype'] . "\n");
?>
<html lang="<?php print($language->language); ?>"dir="<?php print $language->dir; ?>"<?php if ($cf['show']['html']['rdf_namespaces']) print($cf['data']['html']['rdf_namespaces']); ?>>
<head profile="<?php print $grddl_profile; ?>">
  <!--(begin-head)-->
  <?php print($head . "\n"); ?>
  <?php print($cf['headers'] . "\n"); ?>
  <title><?php print($head_title); ?></title>
  <?php print($styles . "\n"); ?>
  <?php print($scripts . "\n");?>
  <!--(end-head)-->
</head>

<body class="mcneese <?php print($cf['markup_css']['body']['class']); ?>" <?php print($attributes);?>>
<?php if (!$cf['is']['overlay'] && $cf['show']['skipnav']){ ?>
  <div id="mcneese-skip_nav">
    <!--(begin-skipnav)-->
    <a href="#mcneese-page-main"><?php print t("Skip to main content"); ?></a>
    <!--(end-skipnav)-->
  </div>
<?php } ?>

<!--(begin-body)-->
<div id="mcneese-top">
  <!--(begin-page_top)-->
  <?php print($page_top . "\n"); ?>
  <!--(end-page_top)-->
</div>

<div id="mcneese-page" >
  <!--(begin-page)-->
  <?php print($page . "\n"); ?>
  <!--(end-page)-->
</div>

<?php print(theme('mcneese_tag', $cf['html']['tags']['mcneese_www_html_footer_open']) . "\n"); ?>
  <!--(begin-www-footer)-->
  <?php print(theme('mcneese_tag', $cf['html']['tags']['mcneese_www_html_footer_heading_open']) . "\n"); ?>
    <h2>Website Footer</h2>
  <?php print(theme('mcneese_tag', $cf['html']['tags']['mcneese_www_html_footer_heading_close']) . "\n"); ?>

  <div class="columns columns-right">
    <div class="column column-1">
      <img src="<?php print($stp); ?>/images/footer-columns-right.png" alt="" width="3px" height="169px">
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
    <div class="column column-1">
      <?php print(theme('mcneese_tag', $cf['html']['tags']['mcneese_www_html_footer_column_heading_open']) . "\n"); ?>
        <h3 class="column-header">Apply Now</h3>
      <?php print(theme('mcneese_tag', $cf['html']['tags']['mcneese_www_html_footer_column_heading_close']) . "\n"); ?>
      <ul>
        <li><a href="https://apply.mcneese.edu/node/add/apply-00001" title="Undergraduate Application">Undergraduate</a></li>
        <li><a href="https://apply.mcneese.edu/node/add/apply-00002" title="Graduate Application">Graduate</a></li>
      </ul>
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
        <li><a href="/node/355">Campus Maps</a></li>
        <li><a href="/node/251">Mission</a></li>
        <li><a href="/index">A-Z Index</a></li>
      </ul>
    </div>

    <div class="column column-4">
      <?php print(theme('mcneese_tag', $cf['html']['tags']['mcneese_www_html_footer_column_heading_open']) . "\n"); ?>
        <h3 class="column-header">Social Connection</h3>
      <?php print(theme('mcneese_tag', $cf['html']['tags']['mcneese_www_html_footer_column_heading_close']) . "\n"); ?>
      <ul>
        <li class="social"><a href="http://www.facebook.com/McNeeseStateU"><img alt="Facebook Icon" src="/files/footer/facebook_icon.png" title="Facebook"></a></li>
        <li class="social"><a href="http://twitter.com/#!/McNeese"><img alt="Twitter Icon" src="/files/footer/twitter_icon.png" title="Twitter"></a></li>
        <li class="social"><a href="http://www.linkedin.com/"><img alt="LinkedIn Icon" src="/files/footer/linkedin_icon.png" title="LinkedIn"></a></li>
        <li class="social"><a href="https://plus.google.com/101409453884998941600?prsrc=3" style="text-decoration: none;"><img alt="" src="https://ssl.gstatic.com/images/icons/gplus-16.png" style="border: 0pt none; width: 21px; height: 21px;"></a></li>
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

<div id="mcneese-bottom">
  <!--(begin-page_bottom)-->
  <?php print($page_bottom . "\n"); ?>
  <!--(end-page_bottom)-->
</div>
<!--(end-body)-->
</body>
</html>

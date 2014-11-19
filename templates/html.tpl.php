<?php
/**
 * @file
 * Html theme implementation.
 */
  $cf = & drupal_static('cf_theme_get_variables', array());

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
  <script>
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

<body class="mcneese no-script <?php print($cf['markup_css']['body']['class']); ?>" <?php print($attributes);?> onload="mcneese_html_body_javascript_detection();">
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

<div id="mcneese-bottom" class="mcneese-bottom">
  <!--(begin-page_bottom)-->
  <?php mcneese_do_print($cf, 'bottom'); ?>
  <?php if (isset($page_bottom)) print($page_bottom . "\n"); ?>
  <!--(end-page_bottom)-->
</div>

<!--(end-body)-->
</body>
</html>

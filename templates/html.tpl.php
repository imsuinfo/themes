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
  <?php print($scripts . "\n");?>
  <!--(end-head)-->
</head>

<body class="mcneese <?php print($cf['markup_css']['body']['class']); ?>" <?php print($attributes);?>>
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

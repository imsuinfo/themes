<?php
/**
 * @file
 * Html theme implementation.
 */
  $cf = & drupal_static('cf_theme_get_variables', array());
  print($cf['agent']['doctype'] . "\n");
?>
<html lang="<?php print($language->language); ?>"dir="<?php print $language->dir; ?>"<?php if ($cf['show']['html']['rdf_namespaces']) print($cf['data']['html']['rdf_namespaces']); ?>>
<head profile="<?php print $grddl_profile; ?>">

<!--(begin_head)-->
<?php print($head . "\n"); ?>
<title><?php print($head_title); ?></title>
<?php print($styles . "\n"); ?>
<?php print($scripts . "\n");?>
<?php print($cf['headers'] . "\n"); ?>
<!--(end_head)-->
</head>

<body class="mcneese <?php print($cf['markup_css']['body']['class']); ?>" <?php print($attributes);?>>
<?php if (!$cf['is']['overlay'] && $cf['show']['skipnav']){ ?>
  <div id="mcneese-skip_nav">
    <!--(begin_skipnav)-->
    <a href="#mcneese-page-content"><?php print t("Skip to main content"); ?></a>
    <!--(end_skipnav)-->
  </div>
<?php } ?>

<!--(begin_body)-->
<div id="mcneese-top">
  <!--(begin_page_top)-->
  <?php print($page_top . "\n"); ?>
  <!--(end_page_top)-->
</div>

<div id="mcneese-page" >
  <!--(begin_page)-->
  <?php print($page . "\n"); ?>
  <!--(end_page)-->
</div>

<div id="mcneese-bottom">
  <!--(begin_page_bottom)-->
  <?php print($page_bottom . "\n"); ?>
  <!--(end_page_bottom)-->
</div>
<!--(end_body)-->
</body>
</html>

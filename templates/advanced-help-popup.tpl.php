<?php
/**
 * @file
 * Advanced Help Popup theme implementation.
 */
  $cf = & drupal_static('cf_theme_get_variables', array());

  if (function_exists('cf_theme_generate_headers')) {
    $cf['headers'] = cf_theme_generate_headers($cf);
  }

  mcneese_render_page();

  // define these variables for mcneese_do_print().
  $cf['data']['page']['title'] = $title;

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

<body class="mcneese mcneese-advanced_help_popup <?php print($cf['markup_css']['body']['class']); ?>" <?php print($attributes);?>>
<!--(begin-body)-->
<?php if (!$cf['is']['overlay'] && $cf['show']['skipnav']){ ?>
  <!--(begin-skipnav)-->
  <a id="mcneese-skip_nav" class="mcneese-skip_nav" href="#mcneese-content-main"><?php print t("Skip to main content"); ?></a>
  <!--(end-skipnav)-->
<?php } ?>

<div id="mcneese-page" class="mcneese-page">
  <!--(begin-page)-->
  <div id="mcneese-page-content" class="mcneese-content full" role="main">
    <?php print(theme('mcneese_tag', $cf['page']['tags']['mcneese_page_title_open']) . "\n"); ?>
      <!--(begin-advanced_help_popup-header)-->
      <?php mcneese_do_print($cf, 'page_title', FALSE); ?>
      <!--(end-advanced_help_popup-header)-->
    <?php print(theme('mcneese_tag', $cf['page']['tags']['mcneese_page_title_close']) . "\n"); ?>

    <?php mcneese_do_print($cf, 'messages', FALSE); ?>

    <div id="mcneese-content-main" class="mcneese-content-main" role="main">
      <!--(begin-advanced_help_popup-content)-->
      <?php print($content . "\n"); ?>
      <!--(end-advanced_help_popup-content)-->
    </div>

    <?php print $closure; ?>
  </div>
  <!--(end-page)-->
</div>
<!--(end-body)-->
</body>
</html>

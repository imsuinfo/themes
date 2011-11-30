<?php print($cf['agent']['doctype'] . "\n");?>
<html lang="<?php print($language->language); ?>" dir="<?php print $language->dir; ?>" version="HTML+RDFa 1.1" <?php print $rdf_namespaces; ?>>

<head profile="<?php print $grddl_profile; ?>">
  <!--(begin_head)-->
  <?php print($head); ?>
  <title><?php print($head_title); ?></title>
  <?php print($styles); ?>
  <?php print($scripts);?>
  <?php print(cf_theme_generate_headers($cf)); ?>
  <!--(end_head)-->
</head>
<body id="mcneese_drupal-body" class="mcneese_drupal-body <?php print($cf['markup_css']['body']['class']); ?>" <?php print($attributes);?>>
<?php  if ($cf['is']['overlay']){ ?><div id="mcneese_drupal-skip_nav" class="clearfix">
    <!--(begin_skipnav)-->
    <div id="mcneese_drupal-skip_nav-list">
      <div id="mcneese_drupal-skip_nav-list-content"><a id="mcneese_drupal-skip_nav-list-content-link" class="mcneese_drupal-skipnav-link element-invisible element-focusable" href="#mcneese_drupal-content"><?php print t("Skip to main content"); ?></a></div>
      <?php if (isset($search)){ ?><div id="mcneese_drupal-skip_nav-list-search"><a id="mcneese_drupal-skip_nav-list-search-link" class="mcneese_drupal-skipnav-link element-invisible element-focusable" href="#mcneese_drupal-search"><?php print t("Skip to search"); ?></a></div><?php } ?>
    </div>
    <!--(end_skipnav)-->
  </div><?php } ?>

  <!--(begin_unsupported)-->
  <?php if ($cf['is']['unsupported']){ ?>
    <div id="unsupported" class="mcneese_drupal clearfix">
      <?php print($cf['is_data']['unsupported']['message']); ?>
    </div>
  <?php } ?>
  <!--(end_unsupported)-->

  <!--(begin_body)-->
  <div id="mcneese_drupal-page_top" class="mcneese_drupal">
    <!--(begin_page_top)-->
    <?php print($page_top); ?>
    <!--(end_page_top)-->
  </div>

  <div id="mcneese_drupal-page" class="mcneese_drupal">
    <!--(begin_page)-->
    <?php print($page); ?>
    <!--(end_page)-->
  </div>

  <div id="mcneese_drupal-page_bottom" class="mcneese_drupal">
    <!--(begin_page_bottom)-->
    <?php print($page_bottom); ?>
    <!--(end_page_bottom)-->
  </div>
  <!--(end_body)-->
</body>
</html>

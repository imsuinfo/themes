<?php
/**
 * @file
 * Advanced Help Popup theme implementation.
 */
  $cf = & drupal_static('cf_theme_get_variables', array());

  mcneese_render_page();

  print($cf['agent']['doctype'] . "\n");
?>
<?php
/**
 * @file
 * Html theme implementation.
 */
  $cf = & drupal_static('cf_theme_get_variables', array());
  print($cf['agent']['doctype'] . "\n");
?>

<html lang="<?php print($language->language); ?>"dir="<?php print $language->dir; ?>"<?php if ($cf['show']['html']['rdf_namespaces']) print($cf['data']['html']['rdf_namespaces']); ?>>
<head>
  <!--(begin-head)-->
  <?php print($head . "\n"); ?>
  <title><?php print($head_title); ?></title>
  <?php print($styles . "\n"); ?>
  <?php print($scripts . "\n");?>
  <?php print($cf['headers'] . "\n"); ?>
  <!--(end-head)-->
</head>

<body class="mcneese mcneese-advanced_help_popup <?php print($cf['markup_css']['body']['class']); ?>" <?php print($attributes);?>>
  <!--(begin-body)-->
  <?php if (!empty($title)) { ?>
    <?php print(theme('mcneese_tag', $cf['advanced_help_popup']['tags']['mcneese_advanced_help_popup_header_open']) . "\n"); ?>
      <!--(begin-advanced_help_popup-header)-->
      <h1 class="page-title"><?php print($title); ?></h1>
      <!--(end-advanced_help_popup-header)-->
    <?php print(theme('mcneese_tag', $cf['advanced_help_popup']['tags']['mcneese_advanced_help_popup_header_close']) . "\n"); ?>
  <?php } ?>

  <?php print(theme('mcneese_tag', $cf['advanced_help_popup']['tags']['mcneese_advanced_help_popup_header_open']) . "\n"); ?>
    <!--(begin-advanced_help_popup-header)-->
    <div id="mcneese-breadcrumb"><?php print $breadcrumb; ?></div>
    <!--(end-advanced_help_popup-header)-->
  <?php print(theme('mcneese_tag', $cf['advanced_help_popup']['tags']['mcneese_advanced_help_popup_header_close']) . "\n"); ?>

  <?php if ($cf['show']['page']['messages']) { ?>
    <?php print(theme('mcneese_tag', $cf['page']['tags']['mcneese_page_messages_open']) . "\n"); ?>
      <!--(begin-advanced_help_popup-messages)-->
      <?php print($cf['data']['page']['messages']['renderred'] . "\n"); ?>
      <!--(end-advanced_help_popup-messages)-->
    <?php print(theme('mcneese_tag', $cf['page']['tags']['mcneese_page_messages_close']) . "\n"); ?>
  <?php } ?>

  <div id="mcneese-advanced_help_popup-main" role="main">
    <!--(begin-advanced_help_popup-content)-->
    <?php print($content . "\n"); ?>
    <!--(end-advanced_help_popup-content)-->
  </div>

  <?php print $closure; ?>
  <!--(end-body)-->
</body>
</html>

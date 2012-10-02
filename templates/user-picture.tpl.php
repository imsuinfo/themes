<?php
/**
 * @file
 * User Picture theme implementation.
 */
  $cf = & drupal_static('cf_theme_get_variables', array());
?>
<?php if (!empty($user_picture)) { ?>
  <?php print(theme('mcneese_tag', $cf['user_picture']['tags']['mcneese_user_picture_open']) . "\n"); ?>
    <?php print($user_picture); ?>
    <?php if ($cf['is']['html5']) ?><figcaption class="html_tag-figcaption"><?php print(t("A picture that represents the user: @name.", array('@name' => $user_name))); ?></figcaption>
  <?php print(theme('mcneese_tag', $cf['user_picture']['tags']['mcneese_user_picture_close']) . "\n"); ?>
<?php } ?>

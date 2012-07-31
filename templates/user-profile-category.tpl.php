<?php
/**
 * @file
 * user Profile theme implementation.
 */
  $cf = & drupal_static('cf_theme_get_variables', array());
?>
<?php print(theme('mcneese_tag', $cf['user_profile_category']['tags']['mcneese_user_profile_category_open']) . "\n"); ?>
  <?php if (!empty($title)) { ?>
    <?php print(theme('mcneese_tag', $cf['user_profile_category']['tags']['mcneese_user_profile_category_header_open']) . "\n"); ?>
      <h2 class="user_profile-title"><?php print($title); ?></h2>
    <?php print(theme('mcneese_tag', $cf['user_profile_category']['tags']['mcneese_user_profile_category_header_close']) . "\n"); ?>
  <?php } ?>
  <?php print($profile_items); ?>
<?php print(theme('mcneese_tag', $cf['user_profile_category']['tags']['mcneese_user_profile_category_close']) . "\n"); ?>

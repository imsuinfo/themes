<?php
/**
 * @file
 * user Profile theme implementation.
 */
  $cf = & drupal_static('cf_theme_get_variables', array());
?>
<?php print(theme('mcneese_tag', $cf['user_profile']['tags']['mcneese_user_profile_open']) . "\n"); ?>
  <?php print(theme('mcneese_tag', $cf['user_profile']['tags']['mcneese_user_profile_header_open']) . "\n"); ?>
    <?php print(theme('mcneese_tag', $cf['generic']['tags']['mcneese_hgroup_open']) . "\n"); ?>
      <h2 class="user_profile-title element-invisible html_tag-heading"><?php print(t("User Profile")); ?></h2>
    <?php print(theme('mcneese_tag', $cf['generic']['tags']['mcneese_hgroup_close']) . "\n"); ?>
  <?php print(theme('mcneese_tag', $cf['user_profile']['tags']['mcneese_user_profile_header_close']) . "\n"); ?>
  <?php print(render($user_profile)); ?>
<?php print(theme('mcneese_tag', $cf['user_profile']['tags']['mcneese_user_profile_close']) . "\n"); ?>

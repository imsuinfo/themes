<?php
/**
 * @file
 * Block theme implementation.
 */
  $cf = & drupal_static('cf_theme_get_variables', array());
?>

<?php print(render($disable_overlay)); ?>
<div id="overlay" class="overlay">
  <div id="overlay-titlebar" class="overlay-titlebar">
    <div id="overlay-title-wrapper" class="overlay-title-wrapper">
      <h1 id="overlay-title" class="overlay-title"><?php print($title); ?></h1>
    </div>
    <div id="overlay-close-wrapper" class="overlay-close-wrapper">
      <a id="overlay-close" href="#" class="overlay-close"><span class="element-invisible"><?php print(t('Close overlay')); ?></span></a>
    </div>
    <?php if ($tabs) { ?>
      <h2 class="element-invisible"><?php print(t('Primary tabs')); ?></h2>
      <ul id="overlay-tabs"><?php print(render($tabs)); ?></ul>
    <?php } ?>
  </div>

  <div id="overlay-content" class="overlay-content">
    <?php print($page); ?>
  </div>
</div>

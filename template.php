<?php

/**
 * @file
 * McNeese - www Theme.
 */

/**
 * @defgroup mcneese McNeese - www Theme
 * @ingroup mcneese
 * @{
 * Provides the www.mcneese.edu mcneese theme.
 */

/**
 * Implements hook_preprocess_html().
 */
function mcneese_www_preprocess_html(&$vars) {
  $cf = & drupal_static('cf_theme_get_variables', array());

  if ($cf['at']['machine_name'] == 'sandbox.mcneese.edu' || $cf['at']['machine_name'] == 'sandbox') {
    $vars['head_title'] = "Sandbox of (" . $vars['head_title'] . ")";
  } else if ($cf['at']['machine_name'] == 'wwwdev.mcneese.edu' || $cf['at']['machine_name'] == 'wwwdev') {
    $vars['head_title'] = $vars['head_title'] . " | Development";
  } else if ($cf['at']['machine_name'] == 'wwwdev2.mcneese.edu' || $cf['at']['machine_name'] == 'wwwdev2') {
    $vars['head_title'] = $vars['head_title'] . " | Development";
  }


  // show google verification on front page and then only on www.mcneese.edu.
  if ($cf['is']['front']) {
    if ($cf['at']['machine_name'] == 'www.mcneese.edu' || $cf['at']['machine_name'] == 'www') {
      $cf['meta']['name']['google-site-verification'] = 'zvxqEbtWmsaA-WXhhueU_iVFT0I9HJRH-QO0ecOL1XI';
    }
  }
}

/**
 * Implements hook_preprocess_page().
 */
function mcneese_www_preprocess_page(&$vars) {
  $cf = & drupal_static('cf_theme_get_variables', array());

  if ($cf['at']['machine_name'] == 'sandbox.mcneese.edu' || $cf['at']['machine_name'] == 'sandbox') {
    $cf['data']['page']['logo']['title'] = 'Sandbox of ' . $cf['data']['page']['logo']['title'];
    $cf['data']['page']['logo']['alt'] = 'Sandbox of ' . $cf['data']['page']['logo']['alt'];
    $cf['data']['page']['logo']['href'] = base_path();
    $cf['show']['page']['logo'] = TRUE;
  }
}

/**
 * @} End of '@defgroup mcneese McNeese - www Theme'.
 */

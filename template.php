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

  $cf['show']['page']['group_image'] = FALSE;
  $cf['data']['page']['group_image'] = array();

  if ($cf['at']['machine_name'] == 'sandbox.mcneese.edu' || $cf['at']['machine_name'] == 'sandbox') {
    $cf['data']['page']['logo']['title'] = 'Sandbox of ' . $cf['data']['page']['logo']['title'];
    $cf['data']['page']['logo']['alt'] = 'Sandbox of ' . $cf['data']['page']['logo']['alt'];
    $cf['data']['page']['logo']['href'] = base_path();
    $cf['show']['page']['logo'] = TRUE;
  }
}

/**
 * Render all data for: page.
 */
function mcneese_www_render_page() {
  global $base_url;
  $cf = & drupal_static('cf_theme_get_variables', array());


  // node-specific content
  if ($cf['is']['node']) {
    // group image
    if (property_exists($cf['is_data']['node']['object'], 'field_group_image_show') && !empty($cf['is_data']['node']['object']->field_group_image_show)) {
      if (property_exists($cf['is_data']['node']['object'], 'field_group_image_custom') && !empty($cf['is_data']['node']['object']->field_group_image_custom)) {
        $cf['data']['page']['group_image']['class'] = 'noscript group_image ';
        $cf['data']['page']['group_image']['height'] = '200px';

        $url = file_create_url($cf['is_data']['node']['object']->field_group_image_custom['und'][0]['uri']);

        if ($cf['show']['page']['menus'] || $cf['show']['page']['asides']) {
          $cf['data']['page']['group_image']['class'] .= 'group_image-small';
          $cf['data']['page']['group_image']['width'] = '755px';

          $cf['data']['page']['group_image']['src'] = image_style_url('group_image', $cf['is_data']['node']['object']->field_group_image_custom['und'][0]['uri']);
          $cf['data']['page']['group_image']['other'] = image_style_url('group_image_large', $cf['is_data']['node']['object']->field_group_image_custom['und'][0]['uri']);
        }
        else {
          $cf['data']['page']['group_image']['class'] .= 'group_image-large';
          $cf['data']['page']['group_image']['width'] = '960px';

          $cf['data']['page']['group_image']['src'] = image_style_url('group_image_large', $cf['is_data']['node']['object']->field_group_image_custom['und'][0]['uri']);
          $cf['data']['page']['group_image']['other'] = image_style_url('group_image', $cf['is_data']['node']['object']->field_group_image_custom['und'][0]['uri']);
        }

        $cf['data']['page']['group_image']['original'] = preg_replace('`^' . $base_url . '`i', '', $url);
        $cf['data']['page']['group_image']['title'] = $cf['is_data']['node']['object']->field_group_image_custom['und'][0]['title'];
        $cf['data']['page']['group_image']['alt'] = $cf['is_data']['node']['object']->field_group_image_custom['und'][0]['alt'];
        $cf['show']['page']['group_image'] = TRUE;
      }
    }
  }
}

/**
 * @} End of '@defgroup mcneese McNeese - www Theme'.
 */

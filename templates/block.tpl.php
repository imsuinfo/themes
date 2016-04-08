<?php
/**
 * @file
 * Block theme implementation.
 */
  $cf = & drupal_static('cf_theme_get_variables', array());
  mcneese_render_block();

  if (!isset($cf['generic']['tags'])) {
    mcneese_initialize_generic_tags($cf);
  }

  if ($cf['show']['block']['content'] && isset($cf['data']['block']['content'])) {
    if (isset($cf['block']['tags']['mcneese_block_open'])) print(theme('mcneese_tag', $cf['block']['tags']['mcneese_block_open']));
    print('<!--(begin-block_content)-->');
    if ($cf['show']['block']['header']) {
      print(theme('mcneese_tag', $cf['block']['tags']['mcneese_block_header_open']));
      print(theme('mcneese_tag', $cf['generic']['tags']['mcneese_hgroup_open']));
      print('<!--(begin-block_title)-->');
      if ($cf['show']['block']['title_prefix']) print($cf['data']['block']['title_prefix']);

      print('<h');
      print($cf['block']['heading']);
      print(' class="block-title html_tag-heading">');
      print($cf['data']['block']['header']);
      print('</h');
      print($cf['block']['heading']);
      print('>');

      if ($cf['show']['block']['title_suffix']) print($cf['data']['block']['title_suffix']);

      print('<!--(end-block_title)-->');
      print(theme('mcneese_tag', $cf['generic']['tags']['mcneese_hgroup_close']));
      print(theme('mcneese_tag', $cf['block']['tags']['mcneese_block_header_close']));
    }

    print($cf['data']['block']['content']);
    print('<!--(end-block_content)-->');
    if (isset($cf['block']['tags']['mcneese_block_close'])) print(theme('mcneese_tag', $cf['block']['tags']['mcneese_block_close']));
  }

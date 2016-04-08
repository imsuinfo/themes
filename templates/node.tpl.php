<?php
/**
 * @file
 * Node theme implementation.
 */
  $cf = & drupal_static('cf_theme_get_variables', array());
  mcneese_render_node();

  if ($cf['show']['node']['content']) {
    print(theme('mcneese_tag', $cf['node']['tags']['mcneese_node_open']));
    print('<!--(begin-node_content)-->');

    if ($cf['show']['node']['header']) {
      print(theme('mcneese_tag', $cf['node']['tags']['mcneese_node_header_open']));
      print(theme('mcneese_tag', $cf['generic']['tags']['mcneese_hgroup_open']));
      print('<!--(begin-node_title)-->');
      if ($cf['show']['node']['title_prefix']) print($cf['data']['node']['title_prefix']);

      print('<h2 class="node-title html_tag-heading">');
      print($cf['data']['node']['header']);
      print('</h2>');

      if ($cf['show']['node']['title_suffix']) print($cf['data']['node']['title_suffix']);
      print('<!--(end-node_title)-->');
      print(theme('mcneese_tag', $cf['generic']['tags']['mcneese_hgroup_close']));
      print(theme('mcneese_tag', $cf['node']['tags']['mcneese_node_header_close']));
    }

    print($cf['data']['node']['content']);
    print('<!--(end-node_content)-->');
    print(theme('mcneese_tag', $cf['node']['tags']['mcneese_node_close']));
  }

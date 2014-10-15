<?php

/**
 * @file
 * McNeese - Faculty Use Theme.
 */

/**
 * @defgroup mcneese McNeese - Faculty Use Theme
 * @ingroup mcneese
 * @{
 * Provides the fcs.mcneese.edu mcneese theme.
 */


/**
 * Implements hook_preprocess_page().
 */
function mfcs_preprocess_page(&$vars) {
  $cf = &drupal_static('cf_theme_get_variables', array());

  if (empty($cf)) {
    mcneese_initialize_variables($vars);
  }

  $mfcs_canonical = &drupal_static('mfcs_canonical', array());

  if (!empty($mfcs_canonical)) {
    if (!isset($cf['link'])) {
      $cf['link'] = array();
    }

    $cf['link'] = array_merge($cf['link'], $mfcs_canonical);
  }

  // provide margin information in the printer-friendly version of the page.
  // (A4 = 210mm x 297mm) (US-Letter = 216mm x 279mm)
  $print_css = '@page { ' . "\n";
  $print_css .= '  size: US-Letter;' . "\n";
  $print_css .= '  margin: 10mm 10mm 10mm 10mm;' . "\n";

  $page_title = drupal_get_title();

  // note: @top-left, and @top-right are currently not supported by most major browsers.
  $print_css .= '  @top-left { content: "' . $page_title . '"; }' . "\n";
  $print_css .= '  @top-right { content: "Page " counter(page); }' . "\n";
  $print_css .= '}' . "\n";
  drupal_add_css($print_css, array('type' => 'inline', 'group' => CSS_THEME, 'weight' => 10, 'media' => 'print', 'preprocess' => FALSE));
}

/**
 * Render all data for: page.
 */
function mfcs_render_page() {
  global $base_path;
  global $mfcs_determined;

  $url_arguments = '';
  if (!empty($mfcs_determined['complete'])) {
    $url_arguments .= '?' . $mfcs_determined['complete'];
    $rebuild_breadcrumb = TRUE;
  }

  $cf = &drupal_static('cf_theme_get_variables', array());

  // build & render precrumb
  if (!isset($cf['show']['page']['precrumb'])) {
    $cf['data']['page']['precrumb'] = '';
    $cf['show']['page']['precrumb'] = FALSE;
  }

  $dont_append_title = FALSE;
  $rebuild_breadcrumb = FALSE;
  if (isset($cf['user']['object']) && is_object($cf['user']['object']) && $cf['user']['object']->uid > 0) {
    $path_parts = explode('/', $cf['at']['path']);
    $count_parts = count($path_parts);

    $pre_crumb_title = "Requests Dashboard";

    if ($count_parts > 0 && $path_parts[0] == 'requests') {
      if ($count_parts == 1) {
        if ($path_parts[0] == 'requests') {
          $title = "Requests Dashboard";

          $cf['data']['page']['precrumb'] = '<div class="crumb-request_id">' . $title . '</div>';
          $cf['show']['page']['precrumb'] = TRUE;

          $rebuild_breadcrumb = TRUE;
          $dont_append_title = TRUE;
        }
      }
      else {
        $rebuild_breadcrumb = TRUE;
        $dont_append_title = TRUE;

        if ($path_parts[1] == 'create-0') {
          $title = "New Request";

          $cf['data']['page']['precrumb'] = '<div class="crumb-request_id">' . $title . '</div>';
          $cf['show']['page']['precrumb'] = TRUE;

          if ($count_parts == 2) {
            $cf['page']['breadcrumb'][] = '<a href="' . $base_path . 'requests/' . $path_parts[1] . $url_arguments . '" title="' . $title  .'">' . $title . '</a>';
          }
          elseif ($count_parts == 3) {
            array_pop($cf['page']['breadcrumb']);

            $title = "Copy Request";
            $cf['page']['breadcrumb'][] = '<a href="' . $base_path . 'requests/' . $path_parts[1] . $url_arguments . '" title="' . $title .'">' . $title . '</a>';
          }

          unset($cf['page']['breadcrumb'][1]);
        }
        elseif ($path_parts[1] == 'list-0') {
          $title = "List Requests";

          $cf['data']['page']['precrumb'] = '<div class="crumb-request_id">' . $title . '</div>';
          $cf['show']['page']['precrumb'] = TRUE;

          if ($count_parts == 2) {
            $cf['page']['breadcrumb'][] = '<a href="' . $base_path . 'requests/' . $path_parts[1] . $url_arguments .'" title="' . $title . '">' . $title . '</a>';
          }

          unset($cf['page']['breadcrumb'][1]);
        }
        elseif ($path_parts[1] == 'review-0') {
          $title = "Review Requests";

          $cf['data']['page']['precrumb'] = '<div class="crumb-request_id">' . $title . '</div>';
          $cf['show']['page']['precrumb'] = TRUE;

          if ($count_parts == 2) {
            $cf['page']['breadcrumb'][] = '<a href="' . $base_path . 'requests/' . $path_parts[1] . $url_arguments . '" title="' . $title . '">' . $title . '</a>';
          }

          unset($cf['page']['breadcrumb'][1]);
        }
        elseif ($path_parts[1] == 'reviewers-0') {
          $title = "Manage Reviewers";
          $pre_crumb_title = "Requests Management";

          $cf['data']['page']['precrumb'] = '<div class="crumb-request_id">' . $title . '</div>';
          $cf['show']['page']['precrumb'] = TRUE;

          $new_breadcrumb = array();
          $new_breadcrumb[] = array_shift($cf['page']['breadcrumb']);
          $new_breadcrumb[] = '<a href="' . $base_path . 'requests/management' . $url_arguments . '" title="' . $pre_crumb_title . '">' . $pre_crumb_title . '</a>';
          $new_breadcrumb[] = '<a href="' . $base_path . 'requests/' . $path_parts[1] . $url_arguments . '" title="' . $title . '">' . $title . '</a>';

          if ($count_parts == 5) {
            $title = drupal_get_title();
            $new_breadcrumb[] = '<a href="' . $base_path . 'requests/' . $path_parts[1] . '/' . $path_parts[2] . '/' . $path_parts[3] . '/' . $path_parts[4] . $url_arguments . '" title="' . $title . '">' . $title . '</a>';
          }
          elseif ($count_parts == 4 && $path_parts[2] == 'delete') {
            $title = "Delete Reviewer";
            $new_breadcrumb[] = '<a href="' . $base_path . 'requests/' . $path_parts[1] . '/' . $path_parts[2] . '/' . $path_parts[3] . $url_arguments . '" title="' . $title . '">' . $title . '</a>';
          }

          // original breadcrumb gets overridden to remove extra/invalid url paths.
          $cf['page']['breadcrumb'] = $new_breadcrumb;
        }
        elseif ($path_parts[1] == 'proxy-0') {
          $title = "Manage Proxies";
          $pre_crumb_title = "Requests Management";

          $cf['data']['page']['precrumb'] = '<div class="crumb-request_id">' . $title . '</div>';
          $cf['show']['page']['precrumb'] = TRUE;

          $new_breadcrumb = array();
          $new_breadcrumb[] = array_shift($cf['page']['breadcrumb']);
          $new_breadcrumb[] = '<a href="' . $base_path . 'requests/management' . $url_arguments . '" title="' . $pre_crumb_title . '">' . $pre_crumb_title . '</a>';
          $new_breadcrumb[] = '<a href="' . $base_path . 'requests/' . $path_parts[1] . $url_arguments . '" title="' . $title . '">' . $title . '</a>';

          if ($count_parts == 4 && $path_parts[2] == 'delete') {
            $title = "Delete Proxy";
            $new_breadcrumb[] = '<a href="' . $base_path . 'requests/' . $path_parts[1] . '/' . $path_parts[2] . '/' . $path_parts[3] . $url_arguments . '" title="' . $title . '">' . $title . '</a>';
          }

          // original breadcrumb gets overridden to remove extra/invalid url paths.
          $cf['page']['breadcrumb'] = $new_breadcrumb;
        }
        elseif ($path_parts[1] == 'calendar-0') {
          if ($count_parts > 2 && ($path_parts[2] == 'month' || $path_parts[2] == 'day')) {
            $title = "Request Calendar";

            if ($path_parts[2] == 'month') {
              $title = "Request Calendar - Month";
            }
            elseif ($path_parts[2] == 'day') {
              $title = "Request Calendar - Day";
            }

            $cf['data']['page']['precrumb'] = '<div class="crumb-request_id">' . $title . '</div>';
            $cf['show']['page']['precrumb'] = TRUE;

            $new_breadcrumb = array();
            $new_breadcrumb[] = array_shift($cf['page']['breadcrumb']);
            $new_breadcrumb[] = '<a href="' . $base_path . 'requests/' . $path_parts[1] . '/' . $path_parts[2] . $url_arguments . '" title="' . $title . '">' . $title . '</a>';

            if ($count_parts > 4 && $path_parts[2] == 'month') {
              $date_string = date('F Y', strtotime($path_parts[4] . ' 1, ' . $path_parts[3]));
              $title = "Monthly Calendar for " . $date_string;
              $new_breadcrumb[] = '<a href="' . $base_path . 'requests/' . $path_parts[1] . '/' . $path_parts[2] . '/' . $path_parts[3] . '/' . $path_parts[4] . $url_arguments . '" title="' . $title . '">' . $date_string . '</a>';
            }
            elseif ($count_parts > 5 && $path_parts[2] == 'day') {
              $date_string = date('F j, Y', strtotime($path_parts[4] . ' ' . $path_parts[5] . ', ' . $path_parts[3]));
              $title = "Daily Calendar for " . $date_string;
              $new_breadcrumb[] = '<a href="' . $base_path . 'requests/' . $path_parts[1] . '/' . $path_parts[2] . '/' . $path_parts[3] . '/' . $path_parts[4] . '/' . $path_parts[5] . $url_arguments . '" title="' . $title . '">' . $date_string . '</a>';
            }

            // original breadcrumb gets overridden to remove extra/invalid url paths.
            $cf['page']['breadcrumb'] = $new_breadcrumb;
          }
        }
        elseif ($path_parts[1] == 'search-0') {
          $title = "Search Requests";

          $cf['data']['page']['precrumb'] = '<div class="crumb-request_id">' . $title . '</div>';
          $cf['show']['page']['precrumb'] = TRUE;

          if ($count_parts == 2) {
            $cf['page']['breadcrumb'][] = '<a href="' . $base_path . 'requests/' . $path_parts[1] . $url_arguments . '" title="' . $title . '">' . $title . '</a>';
          }

          unset($cf['page']['breadcrumb'][1]);
        }
        elseif ($path_parts[1] == 'statistics-0') {
          $title = "Request Statistics";
          $pre_crumb_title = "Requests Management";

          $cf['data']['page']['precrumb'] = '<div class="crumb-request_id">' . $title . '</div>';
          $cf['show']['page']['precrumb'] = TRUE;

          $new_breadcrumb = array();
          $new_breadcrumb[] = array_shift($cf['page']['breadcrumb']);
          $new_breadcrumb[] = '<a href="' . $base_path . 'requests/management' . $url_arguments . '" title="' . $pre_crumb_title . '">' . $pre_crumb_title . '</a>';

          $new_breadcrumb[] = '<a href="' . $base_path . 'requests/' . $path_parts[1] . $url_arguments . '" title="' . $title . '">' . $title . '</a>';

          // original breadcrumb gets overridden to remove extra/invalid url paths.
          $cf['page']['breadcrumb'] = $new_breadcrumb;
        }
        elseif ($path_parts[1] == 'email_log-0') {
          $title = "E-mail Logs";
          $pre_crumb_title = "Requests Management";

          $cf['data']['page']['precrumb'] = '<div class="crumb-request_id">' . $title . '</div>';
          $cf['show']['page']['precrumb'] = TRUE;

          $new_breadcrumb = array();
          $new_breadcrumb[] = array_shift($cf['page']['breadcrumb']);
          $new_breadcrumb[] = '<a href="' . $base_path . 'requests/management' . $url_arguments . '" title="' . $pre_crumb_title . '">' . $pre_crumb_title . '</a>';

          $new_breadcrumb[] = '<a href="' . $base_path . 'requests/' . $path_parts[1] . $url_arguments . '" title="' . $title . '">' . $title . '</a>';

          if (count($path_parts) > 3 && $path_parts[2] == 'view' && cf_is_integer($path_parts[3])) {
            $new_breadcrumb[] = '<a href="' . $base_path . 'requests/' . $path_parts[1] . '/view/' . $path_parts[3] . $url_arguments . '" title="View E-mail Logs">View E-mail Log</a>';
            $cf['data']['page']['precrumb'] = '<div class="crumb-request_id">E-mail ' . $path_parts[3] . '</div>';
          }

          // original breadcrumb gets overridden to remove extra/invalid url paths.
          $cf['page']['breadcrumb'] = $new_breadcrumb;
        }
        elseif ($path_parts[1] == 'management') {
          $title = "Requests Management";
          $pre_crumb_title = "Requests Management";

          $cf['data']['page']['precrumb'] = '<div class="crumb-request_id">' . $title . '</div>';
          $cf['show']['page']['precrumb'] = TRUE;

          $new_breadcrumb = array();
          $new_breadcrumb[] = array_shift($cf['page']['breadcrumb']);
          $new_breadcrumb[] = '<a href="' . $base_path . 'requests/' . $path_parts[1] . $url_arguments . '" title="' . $title . '">' . $title . '</a>';

          // original breadcrumb gets overridden to remove extra/invalid url paths.
          $cf['page']['breadcrumb'] = $new_breadcrumb;
        }
        else {
          unset($cf['page']['breadcrumb'][1]);
        }

        if ($count_parts > 2) {
          if ($path_parts[1] == 'view-0' && cf_is_integer($path_parts[2])) {
            $cf['data']['page']['precrumb'] = '<div class="crumb-request_id">' . "Request " . $path_parts[2] . '</div>';
            $cf['show']['page']['precrumb'] = TRUE;

            if ($count_parts == 3) {
              $cf['page']['breadcrumb'][] = '<a href="' . $base_path . 'requests/' . $path_parts[1] . '/' . $path_parts[2] . $url_arguments . '" title="View Request">' . "View Request" . '</a>';
            }
            elseif ($count_parts == 4) {
              if ($path_parts[3] == 'display') {
                $cf['page']['breadcrumb'][] = '<a href="' . $base_path . 'requests/' . $path_parts[1] . '/' . $path_parts[2] . '/' . $path_parts[3] . $url_arguments . '" title="Request (Display)">' . "Request (Display)" . '</a>';
              }
              elseif ($path_parts[3] == 'log') {
                $cf['page']['breadcrumb'][] = '<a href="' . $base_path . 'requests/' . $path_parts[1] . '/' . $path_parts[2] . '/' . $path_parts[3] . $url_arguments . '" title="Review Log">' . "Review Log" . '</a>';
              }
              elseif ($path_parts[3] == 'operations') {
                $cf['page']['breadcrumb'][] = '<a href="' . $base_path . 'requests/' . $path_parts[1] . '/' . $path_parts[2] . '/' . $path_parts[3] . $url_arguments . '" title="Review Operations">' . "Review Operations" . '</a>';
              }
            }
            elseif (cf_is_integer($path_parts[4])) {
              if ($path_parts[3] == 'normal') {
                $cf['data']['page']['precrumb'] = '<div class="crumb-request_id">' . "Request " . $path_parts[2] . ', Revision ' . $path_parts[4] . '</div>';
                $cf['page']['breadcrumb'][] = '<a href="' . $base_path . 'requests/' . $path_parts[1] . '/' . $path_parts[2] . '/' . $path_parts[3] . '/' . $path_parts[4] . $url_arguments . '" title="Request Revision">' . "Request Revision" . '</a>';
              }
              elseif ($path_parts[3] == 'display') {
                $cf['data']['page']['precrumb'] = '<div class="crumb-request_id">' . "Request " . $path_parts[2] . ', Revision ' . $path_parts[4] . '</div>';
                $cf['page']['breadcrumb'][] = '<a href="' . $base_path . 'requests/' . $path_parts[1] . '/' . $path_parts[2] . '/' . $path_parts[3] . '/' . $path_parts[4] . $url_arguments . '" title="Request Revision (Display)">' . "Request Revision (Display)" . '</a>';
              }
              elseif ($path_parts[3] == 'log') {
                $cf['data']['page']['precrumb'] = '<div class="crumb-request_id">' . "Request " . $path_parts[2] . ', Revision ' . $path_parts[4] . '</div>';
                $cf['page']['breadcrumb'][] = '<a href="' . $base_path . 'requests/' . $path_parts[1] . '/' . $path_parts[2] . '/' . $path_parts[3] . '/' . $path_parts[4] . $url_arguments . '" title="Revision Review Log">' . "Revision Review Log" . '</a>';
              }
            }
          }
          elseif ($path_parts[1] == 'edit-0' && cf_is_integer($path_parts[2])) {
            $cf['data']['page']['precrumb'] = '<div class="crumb-request_id">' . "Request " . $path_parts[2] . '</div>';
            $cf['show']['page']['precrumb'] = TRUE;

            $cf['page']['breadcrumb'][] = '<a href="' . $base_path . 'requests/' . $path_parts[1] . '/' . $path_parts[2] . $url_arguments . '" title="Edit Request">' . "Edit Request" . '</a>';
          }
          elseif ($path_parts[1] == 'history-0' && cf_is_integer($path_parts[2])) {
            $cf['data']['page']['precrumb'] = '<div class="crumb-request_id">' . "Request " . $path_parts[2] . '</div>';
            $cf['show']['page']['precrumb'] = TRUE;

            $cf['page']['breadcrumb'][] = '<a href="' . $base_path . 'requests/view-0/' . $path_parts[2] . $url_arguments . '" title="View Request">' . "View Request" . '</a>';
            $cf['page']['breadcrumb'][] = '<a href="' . $base_path . 'requests/' . $path_parts[1] . '/' . $path_parts[2] . $url_arguments . '" title="Request History">' . "Request History" . '</a>';
          }
          elseif ($path_parts[1] == 'agreement-0' && cf_is_integer($path_parts[2])) {
            $cf['data']['page']['precrumb'] = '<div class="crumb-request_id">' . "Request " . $path_parts[2] . '</div>';
            $cf['show']['page']['precrumb'] = TRUE;

            $cf['page']['breadcrumb'][] = '<a href="' . $base_path . 'requests/view-0/' . $path_parts[2] . $url_arguments . '" title="View Request">' . "View Request" . '</a>';
            $cf['page']['breadcrumb'][] = '<a href="' . $base_path . 'requests/' . $path_parts[1] . '/' . $path_parts[2] . $url_arguments . '" title="Facilities Use Agreement">' . "View Agreement" . '</a>';
          }
        }
      }
    }
  }

  if ($rebuild_breadcrumb) {
    $cf['data']['page']['breadcrumb'] = theme('breadcrumb', array('breadcrumb' => $cf['page']['breadcrumb']));
  }
}

/**
 * Custom implementation of theme_breadcrumb().
 *
 * This removes the breadcrumb wrapper, requiring the theme to specify the
 * wrapper tag.
 *
 * @see theme_breadcrumb()
 */
function mcneese_fcs_breadcrumb($vars) {
  global $mfcs_determined;

  $url_arguments = '';
  if (!empty($mfcs_determined['complete'])) {
    $url_arguments .= '?' . $mfcs_determined['complete'];
    $rebuild_breadcrumb = TRUE;
  }

  $breadcrumb = (array) $vars['breadcrumb'];
  $output = '';

  $breadcrumb[0] = '<a href="' . base_path() . 'requests' . $url_arguments . '" class="link-home" title="Home">' . t("Home") . '</a>';

  $count = 0;
  $total = count($breadcrumb);

  foreach ($breadcrumb as $key => &$crumb) {
    $output .= '<li class="crumb">' . $crumb . '</li>';

    $count++;
    if ($count < $total) {
      $output .= '<div class="crumb-trail">»</div>';
    }
  }

  return $output;
}

/**
 * Implements hook_preprocess_toolbar().
 */
function mfcs_preprocess_toolbar(&$vars) {
  $cf = &drupal_static('cf_theme_get_variables', array());

  if (empty($cf)) {
    mcneese_initialize_variables($vars);
  }

  if (!function_exists('toolbar_menu_navigation_links')) {
    return;
  }

  if (!function_exists('mfcs_menu')) {
    return;
  }

  $requests_tree = menu_build_tree('navigation', array(
    'conditions' => array('ml.link_path' => 'requests'),
    'min_depth' => 1,
    'max_depth' => 1,
  ));

  $management_tree = menu_build_tree('navigation', array(
    'conditions' => array('ml.link_path' => 'requests/management'),
    'min_depth' => 1,
    'max_depth' => 1,
  ));

  $review_tree = menu_build_tree('navigation', array(
    'conditions' => array('ml.link_path' => 'requests/review-0'),
    'min_depth' => 2,
    'max_depth' => 2,
  ));

  $tree = toolbar_get_menu_tree();

  $tree = array_merge($review_tree, $tree);
  $tree = array_merge($management_tree, $tree);
  $tree = array_merge($requests_tree, $tree);

  $links = toolbar_menu_navigation_links($tree);

  global $mfcs_determined;

  $url_arguments = '';
  if (!empty($mfcs_determined['complete'])) {
    $url_arguments .= '?' . $mfcs_determined['complete'];
  }

  foreach ($links as $key => $link) {
    if (empty($link['href'])) continue;

    $links[$key]['href'] .= $url_arguments;
  }

  $vars['toolbar']['toolbar_menu']['#links'] = $links;

  if (!empty($vars['toolbar']['toolbar_user']['#links'])) {
    foreach ($vars['toolbar']['toolbar_user']['#links'] as $key => $link) {
      if (empty($link['href'])) continue;
      if ($link['href'] == 'user/logout') continue;

      $vars['toolbar']['toolbar_user']['#links'][$key]['href'] .= $url_arguments;
    }
  }

  if (!empty($vars['toolbar']['toolbar_drawer'][0]['shortcuts'])) {
    foreach ($vars['toolbar']['toolbar_drawer'][0]['shortcuts'] as $key => $link) {
      if (empty($link['#href'])) continue;

      $vars['toolbar']['toolbar_drawer'][0]['shortcuts'][$key]['#href'] .= $url_arguments;
    }
  }
}

/**
 * @} End of '@defgroup mcneese McNeese - Faculty Use Theme'.
 */

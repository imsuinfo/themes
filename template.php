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
}


/**
 * Render all data for: page.
 */
function mfcs_render_page() {
  global $base_path;

  $cf = &drupal_static('cf_theme_get_variables', array());

  // build & render precrumb
  if (!isset($cf['show']['page']['precrumb'])) {
    $cf['data']['page']['precrumb'] = '';
    $cf['show']['page']['precrumb'] = FALSE;
  }

  $rebuild_breadcrumb = FALSE;
  if (isset($cf['user']['object']) && is_object($cf['user']['object']) && $cf['user']['object']->uid > 0) {
    $path_parts = explode('/', $cf['at']['path']);
    $count_parts = count($path_parts);

    if ($count_parts > 0 && $path_parts[0] == 'requests') {
      if ($count_parts == 1) {
        if ($path_parts[0] == 'requests') {
          $title = "Requests Dashboard";

          $cf['data']['page']['precrumb'] = '<div class="crumb-request_id">' . $title . '</div>';
          $cf['show']['page']['precrumb'] = TRUE;

          $cf['page']['breadcrumb'][] = '<a href="' . $base_path . 'requests" title="' . $title . '">' . drupal_get_title() . '</a>';
          $rebuild_breadcrumb = TRUE;
        }
      }
      else {
        if ($path_parts[1] == 'create-0') {
          $title = "New Request";

          $cf['data']['page']['precrumb'] = '<div class="crumb-request_id">' . $title . '</div>';
          $cf['show']['page']['precrumb'] = TRUE;

          $cf['page']['breadcrumb'][] = '<a href="' . $base_path . 'requests/' . $path_parts[1] . '" title="' . $title . '">' . drupal_get_title() . '</a>';
          $rebuild_breadcrumb = TRUE;
        }
        elseif ($path_parts[1] == 'list-0') {
          $title = "List Requests";

          $cf['data']['page']['precrumb'] = '<div class="crumb-request_id">' . $title . '</div>';
          $cf['show']['page']['precrumb'] = TRUE;

          $cf['page']['breadcrumb'][] = '<a href="' . $base_path . 'requests/' . $path_parts[1] . '" title="' . $title . '">' . drupal_get_title() . '</a>';
          $rebuild_breadcrumb = TRUE;
        }
        elseif ($path_parts[1] == 'review-0') {
          $title = "Review Requests";

          $cf['data']['page']['precrumb'] = '<div class="crumb-request_id">' . $title . '</div>';
          $cf['show']['page']['precrumb'] = TRUE;

          $cf['page']['breadcrumb'][] = '<a href="' . $base_path . 'requests/' . $path_parts[1] . '" title="' . $title . '">' . drupal_get_title() . '</a>';
          $rebuild_breadcrumb = TRUE;
        }
        elseif ($path_parts[1] == 'reviewers-0') {
          $title = "Manage Reviewers";

          $cf['data']['page']['precrumb'] = '<div class="crumb-request_id">' . $title . '</div>';
          $cf['show']['page']['precrumb'] = TRUE;

          $cf['page']['breadcrumb'][] = '<a href="' . $base_path . 'requests/' . $path_parts[1] . '" title="' . $title . '">' . drupal_get_title() . '</a>';
          $rebuild_breadcrumb = TRUE;
        }
        elseif ($path_parts[1] == 'calendar-0') {
          if ($count_parts > 2) {
            $title = "Request Calendar";

            $cf['data']['page']['precrumb'] = '<div class="crumb-request_id">' . $title . '</div>';
            $cf['show']['page']['precrumb'] = TRUE;

            $cf['page']['breadcrumb'][] = '<a href="' . $base_path . 'requests/' . $path_parts[1] . '/' . $path_parts[2] . '" title="' . $title . '">' . drupal_get_title() . '</a>';
            $rebuild_breadcrumb = TRUE;
          }
        }
        elseif ($path_parts[1] == 'search-0') {
          $title = "Search Requests";

          $cf['data']['page']['precrumb'] = '<div class="crumb-request_id">' . $title . '</div>';
          $cf['show']['page']['precrumb'] = TRUE;

          $cf['page']['breadcrumb'][] = '<a href="' . $base_path . 'requests/' . $path_parts[1] . '" title="' . $title . '">' . drupal_get_title() . '</a>';
          $rebuild_breadcrumb = TRUE;
        }

        if ($count_parts > 2) {
          if ($path_parts[1] == 'view-0' && cf_is_integer($path_parts[2])) {
            $cf['data']['page']['precrumb'] = '<div class="crumb-request_id">' . "Request " . $path_parts[2] . '</div>';
            $cf['show']['page']['precrumb'] = TRUE;

            if ($count_parts == 3) {
              $cf['page']['breadcrumb'][] = '<a href="' . $base_path . 'requests/' . $path_parts[1] . '/' . $path_parts[2] . '" title="View Request">' . "View Request" . '</a>';
              $rebuild_breadcrumb = TRUE;
            }
            else {
              if ($path_parts[3] == 2) {
                $cf['page']['breadcrumb'][] = '<a href="' . $base_path . 'requests/' . $path_parts[1] . '/' . $path_parts[2] . '/' . $path_parts[3] . '" title="Review Log">' . "Review Log" . '</a>';
                $rebuild_breadcrumb = TRUE;
              }
              elseif ($path_parts[3] == 3) {
                $cf['page']['breadcrumb'][] = '<a href="' . $base_path . 'requests/' . $path_parts[1] . '/' . $path_parts[2] . '/' . $path_parts[3] . '" title="Review Operations">' . "Review Operations" . '</a>';
                $rebuild_breadcrumb = TRUE;
              }
            }
          }
          elseif ($path_parts[1] == 'edit-0' && cf_is_integer($path_parts[2])) {
            $cf['data']['page']['precrumb'] = '<div class="crumb-request_id">' . "Request " . $path_parts[2] . '</div>';
            $cf['show']['page']['precrumb'] = TRUE;

            $cf['page']['breadcrumb'][] = '<a href="' . $base_path . 'requests/' . $path_parts[1] . '/' . $path_parts[2] . '" title="Edit Request">' . "Edit Request" . '</a>';
            $rebuild_breadcrumb = TRUE;
          }
          elseif ($path_parts[1] == 'history-0' && cf_is_integer($path_parts[2])) {
            $cf['data']['page']['precrumb'] = '<div class="crumb-request_id">' . "Request " . $path_parts[2] . '</div>';
            $cf['show']['page']['precrumb'] = TRUE;

            $cf['page']['breadcrumb'][] = '<a href="' . $base_path . 'requests/view-0/' . $path_parts[2] . '" title="View Request">' . "View Request" . '</a>';
            $cf['page']['breadcrumb'][] = '<a href="' . $base_path . 'requests/' . $path_parts[1] . '/' . $path_parts[2] . '" title="Request History">' . "Request History" . '</a>';
            $rebuild_breadcrumb = TRUE;
          }
          elseif ($path_parts[1] == 'agreement-0' && cf_is_integer($path_parts[2])) {
            $cf['data']['page']['precrumb'] = '<div class="crumb-request_id">' . "Request " . $path_parts[2] . '</div>';
            $cf['show']['page']['precrumb'] = TRUE;

            $cf['page']['breadcrumb'][] = '<a href="' . $base_path . 'requests/view-0/' . $path_parts[2] . '" title="View Request">' . "View Request" . '</a>';
            $cf['page']['breadcrumb'][] = '<a href="' . $base_path . 'requests/' . $path_parts[1] . '/' . $path_parts[2] . '" title="Facilities Use Agreement">' . "View Agreement" . '</a>';
            $rebuild_breadcrumb = TRUE;
          }
        }

        if (!$rebuild_breadcrumb) {
          $title = "Event Requests Dashboard";

          $cf['data']['page']['precrumb'] = '<div class="crumb-request_id">' . $title . '</div>';
          $cf['show']['page']['precrumb'] = TRUE;

          $cf['page']['breadcrumb'][] = '<a href="' . $base_path . 'requests" title="' . $title . '">' . drupal_get_title() . '</a>';
          $rebuild_breadcrumb = TRUE;
        }
      }
    }
  }

  if ($rebuild_breadcrumb) {
    $cf['data']['page']['breadcrumb'] = theme('breadcrumb', array('breadcrumb' => $cf['page']['breadcrumb']));
  }
}

/**
 * Implements hook_preprocess_toolbar().
 */
function mfcs_preprocess_toolbar(&$vars) {
  $cf = &drupal_static('cf_theme_get_variables', array());

  if (empty($cf)) {
    mcneese_initialize_variables($vars);
  }

  $requests_tree = menu_build_tree('navigation', array(
    'conditions' => array('ml.link_path' => 'requests'),
    'min_depth' => 1,
    'max_depth' => 1,
  ));

  if (!function_exists('toolbar_menu_navigation_links')) {
    return;
  }

  if (!function_exists('mfcs_menu')) {
    return;
  }

  $toolbar_tree = toolbar_get_menu_tree();
  $tree = array_merge($requests_tree, $toolbar_tree);

  $links = toolbar_menu_navigation_links($tree);
  $vars['toolbar']['toolbar_menu']['#links'] = $links;

}

/**
 * @} End of '@defgroup mcneese McNeese - Faculty Use Theme'.
 */

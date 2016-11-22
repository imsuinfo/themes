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
  global $user;

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

    $pre_crumb_title = 'Dashboard';

    if ($count_parts > 0 && $path_parts[0] == 'requests') {
      if ($count_parts == 1) {
        if ($path_parts[0] == 'requests') {
          $title = 'Dashboard';

          $cf['data']['page']['precrumb'] = '<div class="crumb-right_side">' . $title . '</div>';
          $cf['show']['page']['precrumb'] = TRUE;

          $rebuild_breadcrumb = TRUE;
          $dont_append_title = TRUE;
        }
      }
      else {
        $rebuild_breadcrumb = TRUE;
        $dont_append_title = TRUE;

        if ($path_parts[1] == 'create-0') {
          $title = 'Create Request';

          $cf['data']['page']['precrumb'] = '<div class="crumb-right_side">' . $title . '</div>';
          $cf['show']['page']['precrumb'] = TRUE;

          if ($count_parts == 2) {
            $cf['page']['breadcrumb'][] = '<a href="' . $base_path . 'requests/' . $path_parts[1] . $url_arguments . '" title="' . $title  .'">' . $title . '</a>';
          }
          elseif ($count_parts == 3) {
            array_pop($cf['page']['breadcrumb']);

            $title = 'Copy Request';
            $cf['page']['breadcrumb'][] = '<a href="' . $base_path . 'requests/' . $path_parts[1] . '/' . $path_parts[2] . $url_arguments . '" title="' . $title .'">' . $title . '</a>';
          }

          unset($cf['page']['breadcrumb'][1]);
        }
        elseif ($path_parts[1] == 'list-0') {
          $title = 'List Requests';

          $cf['data']['page']['precrumb'] = '<div class="crumb-right_side">' . $title . '</div>';
          $cf['show']['page']['precrumb'] = TRUE;

          if ($count_parts == 2) {
            $cf['page']['breadcrumb'][] = '<a href="' . $base_path . 'requests/' . $path_parts[1] . $url_arguments .'" title="' . $title . '">' . $title . '</a>';
          }

          unset($cf['page']['breadcrumb'][1]);
        }
        elseif ($path_parts[1] == 'review-0') {
          $title = 'Review';

          $cf['data']['page']['precrumb'] = '<div class="crumb-right_side">' . $title . '</div>';
          $cf['show']['page']['precrumb'] = TRUE;

          if ($count_parts == 2) {
            $cf['page']['breadcrumb'][] = '<a href="' . $base_path . 'requests/' . $path_parts[1] . $url_arguments . '" title="' . $title . '">' . $title . '</a>';
          }

          unset($cf['page']['breadcrumb'][1]);
        }
        elseif ($path_parts[1] == 'cancelling-0') {
          if ($count_parts >= 3) {
            $title = 'Cancelling Request';

            $cf['data']['page']['precrumb'] = '<div class="crumb-right_side">Request ' . $path_parts[2] . '</div>';
            $cf['show']['page']['precrumb'] = TRUE;

            $cf['page']['breadcrumb'][] = '<a href="' . $base_path . 'requests/view-0/' . $path_parts[2] . $url_arguments . '" title="View Request">View Request</a>';

            $cf['page']['breadcrumb'][] = '<a href="' . $base_path . 'requests/cancelling-0/' . $path_parts[2] . $url_arguments . '" title="' . $title . '">' . $title . '</a>';

            unset($cf['page']['breadcrumb'][1]);
          }
        }
        elseif ($path_parts[1] == 'reviewers-0') {
          $title = 'Manage Reviewers';
          $pre_crumb_title = 'Management';

          $cf['data']['page']['precrumb'] = '<div class="crumb-right_side">' . $title . '</div>';
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
            $title = 'Delete Reviewer';
            $new_breadcrumb[] = '<a href="' . $base_path . 'requests/' . $path_parts[1] . '/' . $path_parts[2] . '/' . $path_parts[3] . $url_arguments . '" title="' . $title . '">' . $title . '</a>';
          }

          // original breadcrumb gets overridden to remove extra/invalid url paths.
          $cf['page']['breadcrumb'] = $new_breadcrumb;
        }
        elseif ($path_parts[1] == 'manage-0') {
          if ($count_parts > 2) {
            $title = 'Manage Request';

            $cf['data']['page']['precrumb'] = '<div class="crumb-right_side">Request ' . $path_parts[2] . '</div>';
            $cf['show']['page']['precrumb'] = TRUE;

            if ($count_parts == 3) {
              $cf['page']['breadcrumb'][] = '<a href="' . $base_path . 'requests/view-0/' . $path_parts[2] . $url_arguments . '" title="View Request">View Request</a>';
              $cf['page']['breadcrumb'][] = '<a href="' . $base_path . 'requests/manage-0/' . $path_parts[2] . $url_arguments . '" title="' . $title . '">' . $title . '</a>';
            }
            else {
              if ($path_parts[2] == 'override') {
                $cf['page']['breadcrumb'][] = '<a href="' . $base_path . 'requests/view-0/' . $path_parts[3] . $url_arguments . '" title="View Request">View Request</a>';
                $cf['page']['breadcrumb'][] = '<a href="' . $base_path . 'requests/manage-0/' . $path_parts[3] . $url_arguments . '" title="' . $title . '">' . $title . '</a>';

                $title = 'Override Request';
                $cf['page']['breadcrumb'][] = '<a href="' . $base_path . 'requests/manage-0/override/' . $path_parts[3] . $url_arguments . '" title="' . $title . '">' . $title . '</a>';
              }
              elseif ($path_parts[2] == 'reassign' && isset($path_parts[4])) {
                $cf['data']['page']['precrumb'] = '<div class="crumb-right_side">Request ' . $path_parts[4] . '</div>';

                $cf['page']['breadcrumb'][] = '<a href="' . $base_path . 'requests/view-0/' . $path_parts[4] . $url_arguments . '" title="View Request">View Request</a>';
                $cf['page']['breadcrumb'][] = '<a href="' . $base_path . 'requests/manage-0/' . $path_parts[4] . $url_arguments . '" title="' . $title . '">' . $title . '</a>';

                if ($path_parts[3] == 'requester') {
                  $title = 'Re-assign Requester';
                  $cf['page']['breadcrumb'][] = '<a href="' . $base_path . 'requests/manage-0/reassign/requester/' . $path_parts[4] . $url_arguments . '" title="' . $title . '">' . $title . '</a>';
                }
                elseif ($path_parts[3] == 'coordinator') {
                  $title = 'Re-assign Coordinator';
                  $cf['page']['breadcrumb'][] = '<a href="' . $base_path . 'requests/manage-0/reassign/coordinator/' . $path_parts[4] . $url_arguments . '" title="' . $title . '">' . $title . '</a>';
                }
              }
            }

            unset($cf['page']['breadcrumb'][1]);
          }
        }
        elseif ($path_parts[1] == 'problems-0') {
          $title = 'Manage Problems';
          $pre_crumb_title = 'Management';

          $cf['data']['page']['precrumb'] = '<div class="crumb-right_side">' . $title . '</div>';
          $cf['show']['page']['precrumb'] = TRUE;

          $new_breadcrumb = array();
          $new_breadcrumb[] = array_shift($cf['page']['breadcrumb']);
          $new_breadcrumb[] = '<a href="' . $base_path . 'requests/management' . $url_arguments . '" title="' . $pre_crumb_title . '">' . $pre_crumb_title . '</a>';
          $new_breadcrumb[] = '<a href="' . $base_path . 'requests/' . $path_parts[1] . $url_arguments . '" title="' . $title . '">' . $title . '</a>';

          if ($count_parts > 2 && $path_parts[2] == 'users') {
            $title = 'User Problems';
            $new_breadcrumb[] = '<a href="' . $base_path . 'requests/' . $path_parts[1] . '/' . $path_parts[2] . $url_arguments . '" title="' . $title . '">' . $title . '</a>';
          }
          elseif ($count_parts > 2 && $path_parts[2] == 'requests') {
            $title = 'Request Problems';
            $new_breadcrumb[] = '<a href="' . $base_path . 'requests/' . $path_parts[1] . '/' . $path_parts[2] . $url_arguments . '" title="' . $title . '">' . $title . '</a>';
          }

          if ($count_parts > 4 && $path_parts[3] == 'user') {
            $title = 'User Problem';
            $new_breadcrumb[] = '<a href="' . $base_path . 'requests/' . $path_parts[1] . '/' . $path_parts[2] . '/user/' . $path_parts[4] . $url_arguments . '" title="' . $title . '">' . $title . '</a>';
          }
          elseif ($count_parts > 4 && $path_parts[3] == 'request') {
            $title = 'Request Problem';
            $new_breadcrumb[] = '<a href="' . $base_path . 'requests/' . $path_parts[1] . '/' . $path_parts[2] . '/request/' . $path_parts[4] . $url_arguments . '" title="' . $title . '">' . $title . '</a>';
          }

          // original breadcrumb gets overridden to remove extra/invalid url paths.
          $cf['page']['breadcrumb'] = $new_breadcrumb;
        }
        elseif ($path_parts[1] == 'proxy-0') {
          $title = 'Manage Proxies';
          $pre_crumb_title = 'Management';

          $cf['data']['page']['precrumb'] = '<div class="crumb-right_side">' . $title . '</div>';
          $cf['show']['page']['precrumb'] = TRUE;

          $new_breadcrumb = array();
          $new_breadcrumb[] = array_shift($cf['page']['breadcrumb']);
          $new_breadcrumb[] = '<a href="' . $base_path . 'requests/management' . $url_arguments . '" title="' . $pre_crumb_title . '">' . $pre_crumb_title . '</a>';
          $new_breadcrumb[] = '<a href="' . $base_path . 'requests/' . $path_parts[1] . $url_arguments . '" title="' . $title . '">' . $title . '</a>';

          if ($count_parts == 4 && $path_parts[2] == 'delete') {
            $title = 'Delete Proxy';
            $new_breadcrumb[] = '<a href="' . $base_path . 'requests/' . $path_parts[1] . '/' . $path_parts[2] . '/' . $path_parts[3] . $url_arguments . '" title="' . $title . '">' . $title . '</a>';
          }

          // original breadcrumb gets overridden to remove extra/invalid url paths.
          $cf['page']['breadcrumb'] = $new_breadcrumb;
        }
        elseif ($path_parts[1] == 'calendar-0') {
          if ($count_parts > 2 && ($path_parts[2] == 'month' || $path_parts[2] == 'day')) {
            $title = 'Request Calendar';

            if ($path_parts[2] == 'month') {
              $title = 'Request Calendar - Month';
            }
            elseif ($path_parts[2] == 'day') {
              $title = 'Request Calendar - Day';
            }

            $cf['data']['page']['precrumb'] = '<div class="crumb-right_side">' . $title . '</div>';
            $cf['show']['page']['precrumb'] = TRUE;

            $new_breadcrumb = array();
            $new_breadcrumb[] = array_shift($cf['page']['breadcrumb']);
            $new_breadcrumb[] = '<a href="' . $base_path . 'requests/' . $path_parts[1] . '/' . $path_parts[2] . $url_arguments . '" title="' . $title . '">' . $title . '</a>';

            if ($count_parts > 4 && $path_parts[2] == 'month') {
              $date_string = date('F Y', strtotime($path_parts[4] . ' 1, ' . $path_parts[3]));
              $title = 'Monthly Calendar for ' . $date_string;
              $new_breadcrumb[] = '<a href="' . $base_path . 'requests/' . $path_parts[1] . '/' . $path_parts[2] . '/' . $path_parts[3] . '/' . $path_parts[4] . $url_arguments . '" title="' . $title . '">' . $date_string . '</a>';
            }
            elseif ($count_parts > 5 && $path_parts[2] == 'day') {
              $date_string = date('l, F j, Y', strtotime($path_parts[4] . ' ' . $path_parts[5] . ', ' . $path_parts[3]));
              $title = 'Daily Calendar for ' . $date_string;
              $new_breadcrumb[] = '<a href="' . $base_path . 'requests/' . $path_parts[1] . '/' . $path_parts[2] . '/' . $path_parts[3] . '/' . $path_parts[4] . '/' . $path_parts[5] . $url_arguments . '" title="' . $title . '">' . $date_string . '</a>';
            }

            // original breadcrumb gets overridden to remove extra/invalid url paths.
            $cf['page']['breadcrumb'] = $new_breadcrumb;
          }
        }
        elseif ($path_parts[1] == 'search-0') {
          $title = 'Search Requests';

          $cf['data']['page']['precrumb'] = '<div class="crumb-right_side">' . $title . '</div>';
          $cf['show']['page']['precrumb'] = TRUE;

          if ($count_parts == 2) {
            $cf['page']['breadcrumb'][] = '<a href="' . $base_path . 'requests/' . $path_parts[1] . $url_arguments . '" title="' . $title . '">' . $title . '</a>';
          }

          unset($cf['page']['breadcrumb'][1]);
        }
        elseif ($path_parts[1] == 'statistics-0') {
          $title = 'Request Statistics';
          $pre_crumb_title = 'Management';

          $cf['data']['page']['precrumb'] = '<div class="crumb-right_side">' . $title . '</div>';
          $cf['show']['page']['precrumb'] = TRUE;

          $new_breadcrumb = array();
          $new_breadcrumb[] = array_shift($cf['page']['breadcrumb']);
          $new_breadcrumb[] = '<a href="' . $base_path . 'requests/management' . $url_arguments . '" title="' . $pre_crumb_title . '">' . $pre_crumb_title . '</a>';

          $new_breadcrumb[] = '<a href="' . $base_path . 'requests/' . $path_parts[1] . $url_arguments . '" title="' . $title . '">' . $title . '</a>';

          // original breadcrumb gets overridden to remove extra/invalid url paths.
          $cf['page']['breadcrumb'] = $new_breadcrumb;
        }
        elseif ($path_parts[1] == 'email_log-0') {
          $title = 'E-mail Logs';
          $pre_crumb_title = 'Management';

          $cf['data']['page']['precrumb'] = '<div class="crumb-right_side">' . $title . '</div>';
          $cf['show']['page']['precrumb'] = TRUE;

          $new_breadcrumb = array();
          $new_breadcrumb[] = array_shift($cf['page']['breadcrumb']);
          $new_breadcrumb[] = '<a href="' . $base_path . 'requests/management' . $url_arguments . '" title="' . $pre_crumb_title . '">' . $pre_crumb_title . '</a>';

          $new_breadcrumb[] = '<a href="' . $base_path . 'requests/' . $path_parts[1] . $url_arguments . '" title="' . $title . '">' . $title . '</a>';

          if (count($path_parts) > 3 && $path_parts[2] == 'view' && cf_is_integer($path_parts[3])) {
            $new_breadcrumb[] = '<a href="' . $base_path . 'requests/' . $path_parts[1] . '/view/' . $path_parts[3] . $url_arguments . '" title="View E-mail Logs">View E-mail Log</a>';
            $cf['data']['page']['precrumb'] = '<div class="crumb-right_side">E-mail ' . $path_parts[3] . '</div>';
          }

          // original breadcrumb gets overridden to remove extra/invalid url paths.
          $cf['page']['breadcrumb'] = $new_breadcrumb;
        }
        elseif ($path_parts[1] == 'synchronize-0') {
          $title = 'Synchronize';
          $pre_crumb_title = 'Management';

          $cf['data']['page']['precrumb'] = '<div class="crumb-right_side">' . $title . '</div>';
          $cf['show']['page']['precrumb'] = TRUE;

          $new_breadcrumb = array();
          $new_breadcrumb[] = array_shift($cf['page']['breadcrumb']);
          $new_breadcrumb[] = '<a href="' . $base_path . 'requests/management' . $url_arguments . '" title="' . $pre_crumb_title . '">' . $pre_crumb_title . '</a>';

          $new_breadcrumb[] = '<a href="' . $base_path . 'requests/' . $path_parts[1] . $url_arguments . '" title="' . $title . '">' . $title . '</a>';

          // original breadcrumb gets overridden to remove extra/invalid url paths.
          $cf['page']['breadcrumb'] = $new_breadcrumb;
        }
        elseif ($path_parts[1] == 'holiday-0') {
          $year = NULL;
          $type = 0;
          if ($count_parts == 2) {
            $year = date('Y');
          }
          elseif ($count_parts > 2) {
            if ($count_parts == 3) {
              $year = (int) $path_parts[2];
            }
            else {
              if ($path_parts[2] == 'view') {
                if ($count_parts == 5) {
                  $type = 2;
                  $year = (int) $path_parts[3];
                }
              }
              elseif ($path_parts[2] == 'create') {
                if ($count_parts == 4) {
                  $type = 3;
                  $year = (int) $path_parts[3];
                }
                elseif ($count_parts == 5) {
                  $type = 3;
                  $year = (int) $path_parts[3];
                }
              }
              elseif ($path_parts[2] == 'edit') {
                if ($count_parts == 5) {
                  $type = 4;
                  $year = (int) $path_parts[3];
                }
              }
              elseif ($path_parts[2] == 'delete') {
                if ($count_parts == 5) {
                  $type = 5;
                  $year = (int) $path_parts[3];
                }
              }
            }
          }

          if (!is_null($year)) {
            $cf['show']['page']['precrumb'] = TRUE;

            $new_breadcrumb = array();
            $new_breadcrumb[] = array_shift($cf['page']['breadcrumb']);

            $title = 'Management';
            $new_breadcrumb[] = '<a href="' . $base_path . 'requests/management' . $url_arguments . '" title="' . $title . '">' . $title . '</a>';

            $title = 'Holidays ' . $year;
            $new_breadcrumb[] = '<a href="' . $base_path . 'requests/' . $path_parts[1] . $url_arguments . '" title="' . $title . '">' . $title . '</a>';

            if ($type == 0) {
              $cf['data']['page']['precrumb'] = '<div class="crumb-right_side">' . $title . '</div>';
            }

            if ($type == 2) {
              $title = 'View Holiday';
              $new_breadcrumb[] = '<a href="' . $base_path . 'requests/' . $path_parts[1]  . '/view/' . $year. '/' . $path_parts[4] . $url_arguments . '" title="' . $title . '">' . $title . '</a>';

              $cf['data']['page']['precrumb'] = '<div class="crumb-right_side">Holiday ' . $path_parts[4] . '</div>';
            }

            if ($type == 3) {
              if (isset($path_parts[5])) {
                $title = 'Copy Holiday';
                $new_breadcrumb[] = '<a href="' . $base_path . 'requests/' . $path_parts[1]  . '/create/' . $year. '/' . $path_parts[4] . $url_arguments . '" title="' . $title . '">' . $title . '</a>';

                $cf['data']['page']['precrumb'] = '<div class="crumb-right_side">Copy Holiday ' . $path_parts[4] . '</div>';
              }
              else {
                $title = 'Create Holiday';
                $new_breadcrumb[] = '<a href="' . $base_path . 'requests/' . $path_parts[1]  . '/create/' . $year. $url_arguments . '" title="' . $title . '">' . $title . '</a>';

                $cf['data']['page']['precrumb'] = '<div class="crumb-right_side">Create Holiday</div>';
              }
            }

            if ($type == 4) {
              $title = 'Edit Holiday';
              $new_breadcrumb[] = '<a href="' . $base_path . 'requests/' . $path_parts[1]  . '/edit/' . $year. '/' . $path_parts[4] . $url_arguments . '" title="' . $title . '">' . $title . '</a>';

              $cf['data']['page']['precrumb'] = '<div class="crumb-right_side">Holiday ' . $path_parts[4] . '</div>';
            }

            if ($type == 5) {
              $title = 'Delete Holiday';
              $new_breadcrumb[] = '<a href="' . $base_path . 'requests/' . $path_parts[1]  . '/delete/' . $year. '/' . $path_parts[4] . $url_arguments . '" title="' . $title . '">' . $title . '</a>';

              $cf['data']['page']['precrumb'] = '<div class="crumb-right_side">Holiday ' . $path_parts[4] . '</div>';
            }

            // original breadcrumb gets overridden to remove extra/invalid url paths.
            $cf['page']['breadcrumb'] = $new_breadcrumb;
          }
        }
        elseif ($path_parts[1] == 'management') {
          $title = 'Management';
          $pre_crumb_title = 'Management';

          $cf['data']['page']['precrumb'] = '<div class="crumb-right_side">' . $title . '</div>';
          $cf['show']['page']['precrumb'] = TRUE;

          $new_breadcrumb = array();
          $new_breadcrumb[] = array_shift($cf['page']['breadcrumb']);
          $new_breadcrumb[] = '<a href="' . $base_path . 'requests/' . $path_parts[1] . $url_arguments . '" title="' . $title . '">' . $title . '</a>';

          // original breadcrumb gets overridden to remove extra/invalid url paths.
          $cf['page']['breadcrumb'] = $new_breadcrumb;
        }
        elseif ($path_parts[1] == 'troubleshoot-0') {
          $title = 'Manage Problems';
          $pre_crumb_title = 'Management';

          $cf['data']['page']['precrumb'] = '<div class="crumb-right_side">' . $title . '</div>';
          $cf['show']['page']['precrumb'] = TRUE;

          $new_breadcrumb = array();
          $new_breadcrumb[] = array_shift($cf['page']['breadcrumb']);
          $new_breadcrumb[] = '<a href="' . $base_path . 'requests/management' . $url_arguments . '" title="' . $pre_crumb_title . '">' . $pre_crumb_title . '</a>';
          $new_breadcrumb[] = '<a href="' . $base_path . 'requests/problems-0' . $url_arguments . '" title="' . $title . '">' . $title . '</a>';

          $title = 'Troubleshooting Tools';
          $new_breadcrumb[] = '<a href="' . $base_path . 'requests/' . $path_parts[1] . $url_arguments . '" title="' . $title . '">' . $title . '</a>';

          if ($count_parts > 2 && $path_parts[2] == 'locations') {
            $title = 'Locations';
            $new_breadcrumb[] = '<a href="' . $base_path . 'requests/' . $path_parts[1] . '/' . $path_parts[2] . $url_arguments . '" title="' . $title . '">' . $title . '</a>';
          }
          elseif ($count_parts > 2 && $path_parts[2] == 'buildings') {
            $title = 'Buildings';
            $new_breadcrumb[] = '<a href="' . $base_path . 'requests/' . $path_parts[1] . '/' . $path_parts[2] . $url_arguments . '" title="' . $title . '">' . $title . '</a>';
          }
          elseif ($count_parts > 2 && $path_parts[2] == 'rooms') {
            $title = 'Rooms';
            $new_breadcrumb[] = '<a href="' . $base_path . 'requests/' . $path_parts[1] . '/' . $path_parts[2] . $url_arguments . '" title="' . $title . '">' . $title . '</a>';
          }

          // original breadcrumb gets overridden to remove extra/invalid url paths.
          $cf['page']['breadcrumb'] = $new_breadcrumb;
        }
        elseif ($path_parts[1] == 'unavailable-0') {
          $year = NULL;
          $type = 0;
          $room_id = NULL;
          if ($count_parts == 2) {
            $year = date('Y');
          }
          elseif ($count_parts > 2) {
            if ($count_parts < 5) {
              $type = 1;

              if ($count_parts == 4) {
                $year = (int) $path_parts[2];
                $room_id = (int) $path_parts[3];

                if ($path_parts[3] == 'all') {
                  $room_id = 'all';
                }
              }
              else {
                $year = date('Y');
                $room_id = (int) $path_parts[2];

                if ($path_parts[2] == 'all') {
                  $room_id = 'all';
                }
              }
            }
            elseif ($count_parts > 4) {
              $room_id = (int) $path_parts[4];

              if ($path_parts[2] == 'view') {
                if ($count_parts == 6) {
                  $type = 2;
                  $year = (int) $path_parts[3];
                }
              }
              elseif ($path_parts[2] == 'create') {
                if ($count_parts == 5) {
                  $type = 3;
                  $year = (int) $path_parts[3];
                }
                elseif ($count_parts == 6) {
                  $type = 3;
                  $year = (int) $path_parts[3];
                }
              }
              elseif ($path_parts[2] == 'edit') {
                if ($count_parts == 6) {
                  $type = 4;
                  $year = (int) $path_parts[3];
                }
              }
              elseif ($path_parts[2] == 'delete') {
                if ($count_parts == 6) {
                  $type = 5;
                  $year = (int) $path_parts[3];
                }
              }
            }
          }

          if (!is_null($year)) {
            $cf['show']['page']['precrumb'] = TRUE;

            $new_breadcrumb = array();
            $new_breadcrumb[] = array_shift($cf['page']['breadcrumb']);

            $title = 'Management';
            $new_breadcrumb[] = '<a href="' . $base_path . 'requests/management' . $url_arguments . '" title="' . $title . '">' . $title . '</a>';

            $title = 'Room Unavailability';
            $new_breadcrumb[] = '<a href="' . $base_path . 'requests/' . $path_parts[1] . $url_arguments . '" title="' . $title . '">' . $title . '</a>';

            if ($type == 0) {
              $pre_crumb_title = 'Room Unavailability';
              $cf['data']['page']['precrumb'] = '<div class="crumb-right_side">' . $pre_crumb_title . '</div>';
            }
            else {
              if (is_numeric($room_id)) {
                $title = 'Room ' . $room_id . ', Year ' . $year;
              }
              elseif ($room_id == 'all') {
                $title = 'All Rooms, Year ' . $year;
              }
              $new_breadcrumb[] = '<a href="' . $base_path . 'requests/' . $path_parts[1] . '/' . $year . '/' . $room_id . $url_arguments . '" title="' . $title . '">' . $title . '</a>';
            }

            if ($type == 1) {
              if (is_numeric($room_id)) {
                $cf['data']['page']['precrumb'] = '<div class="crumb-right_side">Room ' . $room_id . ', Year ' . $year . '</div>';
              }
              elseif ($room_id == 'all') {
                $cf['data']['page']['precrumb'] = '<div class="crumb-right_side">All Rooms, Year ' . $year . '</div>';
              }
            }

            if ($type == 2) {
              $title = 'View Unavailability';
              $new_breadcrumb[] = '<a href="' . $base_path . 'requests/' . $path_parts[1]  . '/view/' . $year . '/' . $room_id . '/' . $path_parts[5] . $url_arguments . '" title="' . $title . '">' . $title . '</a>';

              $cf['data']['page']['precrumb'] = '<div class="crumb-right_side">Unavailability ' . $path_parts[5] . '</div>';
            }

            if ($type == 3) {
              if (isset($path_parts[5])) {
                $title = 'Copy Unavailability';
                $new_breadcrumb[] = '<a href="' . $base_path . 'requests/' . $path_parts[1]  . '/create/' . $year . '/' . $room_id . '/' . $path_parts[5] . $url_arguments . '" title="' . $title . '">' . $title . '</a>';

                $cf['data']['page']['precrumb'] = '<div class="crumb-right_side">Copy Unavailability ' . $path_parts[5] . '</div>';
              }
              else {
                $title = 'Create Unavailability';
                $new_breadcrumb[] = '<a href="' . $base_path . 'requests/' . $path_parts[1]  . '/create/' . $year . '/' . $room_id . $url_arguments . '" title="' . $title . '">' . $title . '</a>';

                $cf['data']['page']['precrumb'] = '<div class="crumb-right_side">Create Unavailability</div>';
              }
            }

            if ($type == 4) {
              $title = 'Edit Unavailability';
              $new_breadcrumb[] = '<a href="' . $base_path . 'requests/' . $path_parts[1]  . '/edit/' . $year . '/' . $room_id . '/' . $path_parts[5] . $url_arguments . '" title="' . $title . '">' . $title . '</a>';

              $cf['data']['page']['precrumb'] = '<div class="crumb-right_side">Unavailability ' . $path_parts[5] . '</div>';
            }

            if ($type == 5) {
              $title = 'Delete Unavailability';
              $new_breadcrumb[] = '<a href="' . $base_path . 'requests/' . $path_parts[1]  . '/delete/' . $year . '/' . $room_id . '/' . $path_parts[5] . $url_arguments . '" title="' . $title . '">' . $title . '</a>';

              $cf['data']['page']['precrumb'] = '<div class="crumb-right_side">Unavailability ' . $path_parts[5] . '</div>';
            }

            // original breadcrumb gets overridden to remove extra/invalid url paths.
            $cf['page']['breadcrumb'] = $new_breadcrumb;
          }
        }
        else {
          unset($cf['page']['breadcrumb'][1]);
        }

        if ($count_parts > 2) {
          if ($path_parts[1] == 'view-0' && cf_is_integer($path_parts[2])) {
            $cf['data']['page']['precrumb'] = '<div class="crumb-right_side">Request ' . $path_parts[2] . '</div>';
            $cf['show']['page']['precrumb'] = TRUE;

            if ($count_parts == 3) {
              $cf['page']['breadcrumb'][] = '<a href="' . $base_path . 'requests/' . $path_parts[1] . '/' . $path_parts[2] . $url_arguments . '" title="View Request">View Request</a>';
            }
            elseif ($count_parts == 4) {
              if ($path_parts[3] == 'display') {
                $cf['page']['breadcrumb'][] = '<a href="' . $base_path . 'requests/' . $path_parts[1] . '/' . $path_parts[2] . '/' . $path_parts[3] . $url_arguments . '" title="Request (Display)">Request (Display)</a>';
              }
              elseif ($path_parts[3] == 'log') {
                $cf['page']['breadcrumb'][] = '<a href="' . $base_path . 'requests/' . $path_parts[1] . '/' . $path_parts[2] . '/' . $path_parts[3] . $url_arguments . '" title="Review Log">Review Log</a>';
              }
              elseif ($path_parts[3] == 'operations') {
                $cf['page']['breadcrumb'][] = '<a href="' . $base_path . 'requests/' . $path_parts[1] . '/' . $path_parts[2] . '/' . $path_parts[3] . $url_arguments . '" title="Review Operations">Review Operations</a>';
              }
            }
            elseif (cf_is_integer($path_parts[4])) {
              if ($path_parts[3] == 'normal') {
                $cf['data']['page']['precrumb'] = '<div class="crumb-right_side">' . 'Request ' . $path_parts[2] . ', Revision ' . $path_parts[4] . '</div>';
                $cf['page']['breadcrumb'][] = '<a href="' . $base_path . 'requests/' . $path_parts[1] . '/' . $path_parts[2] . '/' . $path_parts[3] . '/' . $path_parts[4] . $url_arguments . '" title="Request Revision">Request Revision</a>';
              }
              elseif ($path_parts[3] == 'display') {
                $cf['data']['page']['precrumb'] = '<div class="crumb-right_side">' . 'Request ' . $path_parts[2] . ', Revision ' . $path_parts[4] . '</div>';
                $cf['page']['breadcrumb'][] = '<a href="' . $base_path . 'requests/' . $path_parts[1] . '/' . $path_parts[2] . '/' . $path_parts[3] . '/' . $path_parts[4] . $url_arguments . '" title="Request Revision (Display)">Request Revision (Display)</a>';
              }
              elseif ($path_parts[3] == 'log') {
                $cf['data']['page']['precrumb'] = '<div class="crumb-right_side">' . 'Request ' . $path_parts[2] . ', Revision ' . $path_parts[4] . '</div>';
                $cf['page']['breadcrumb'][] = '<a href="' . $base_path . 'requests/' . $path_parts[1] . '/' . $path_parts[2] . '/' . $path_parts[3] . '/' . $path_parts[4] . $url_arguments . '" title="Revision Review Log">Revision Review Log</a>';
              }
            }
          }
          elseif ($path_parts[1] == 'edit-0' && cf_is_integer($path_parts[2])) {
            $cf['data']['page']['precrumb'] = '<div class="crumb-right_side">Request ' . $path_parts[2] . '</div>';
            $cf['show']['page']['precrumb'] = TRUE;

            $new_breadcrumb = array();
            $new_breadcrumb[] = array_shift($cf['page']['breadcrumb']);
            $new_breadcrumb[] = '<a href="' . $base_path . 'requests/view-0/' . $path_parts[2] . $url_arguments . '" title="View Request">View Request</a>';

            $edit_region = 'requests';
            if (!empty($path_parts[3])) {
              $edit_region = $path_parts[3];
            }

            $new_breadcrumb[] = '<a href="' . $base_path . 'requests/' . $path_parts[1] . '/' . $path_parts[2] . '/' . $edit_region . $url_arguments . '" title="Edit Request">Edit Request</a>';
            unset($edit_region);

            $cf['page']['breadcrumb'] = $new_breadcrumb;
            unset($new_breadcrumb);
          }
          elseif ($path_parts[1] == 'history-0' && cf_is_integer($path_parts[2])) {
            $cf['data']['page']['precrumb'] = '<div class="crumb-right_side">Request ' . $path_parts[2] . '</div>';
            $cf['show']['page']['precrumb'] = TRUE;

            $cf['page']['breadcrumb'][] = '<a href="' . $base_path . 'requests/view-0/' . $path_parts[2] . $url_arguments . '" title="View Request">View Request</a>';
            $cf['page']['breadcrumb'][] = '<a href="' . $base_path . 'requests/' . $path_parts[1] . '/' . $path_parts[2] . $url_arguments . '" title="Request History">Request History</a>';
          }
          elseif ($path_parts[1] == 'agreement-0' && cf_is_integer($path_parts[2])) {
            $cf['data']['page']['precrumb'] = '<div class="crumb-right_side">Request ' . $path_parts[2] . '</div>';
            $cf['show']['page']['precrumb'] = TRUE;

            $cf['page']['breadcrumb'][] = '<a href="' . $base_path . 'requests/view-0/' . $path_parts[2] . $url_arguments . '" title="View Request">View Request</a>';
            $cf['page']['breadcrumb'][] = '<a href="' . $base_path . 'requests/' . $path_parts[1] . '/' . $path_parts[2] . $url_arguments . '" title="Facilities Use Agreement">View Agreement</a>';
          }
        }
      }
    }
    elseif ($count_parts > 0) {
      if ($path_parts[0] == 'user' && isset($path_parts[1]) && is_numeric($path_parts[1])) {
        $rebuild_breadcrumb = TRUE;

        $title = 'View User';
        $cf['page']['breadcrumb'][] = '<a href="' . $base_path . 'user/' . $path_parts[1] . $url_arguments . '" title="' . $title  .'">' . $title . '</a>';

        if (isset($path_parts[2])) {
          $title = $path_parts[2] . ' User';
          $cf['page']['breadcrumb'][] = '<a href="' . $base_path . 'user/' . $path_parts[1] . '/' . $path_parts[2] . $url_arguments . '" title="' . $title  .'">' . $title . '</a>';
        }
      }
      elseif ($path_parts[0] == 'file' && isset($path_parts[1]) && $path_parts[1] == 'add') {
        $rebuild_breadcrumb = TRUE;

        $title = 'Add File';
        $cf['page']['breadcrumb'][] = '<a href="' . $base_path . 'file/add' . $url_arguments . '" title="' . $title  .'">' . $title . '</a>';

        if (isset($path_parts[2]) && $path_parts[2] == 'upload' && isset($path_parts[3]) && $path_parts[3] == 'archive') {
          $new_breadcrumb = array();
          $new_breadcrumb[] = $cf['page']['breadcrumb'][0];

          $title = 'Add Archive File';
          $new_breadcrumb[] = '<a href="' . $base_path . 'file/add/upload/archive' . $url_arguments . '" title="' . $title  .'">' . $title . '</a>';

          $cf['page']['breadcrumb'] = $new_breadcrumb;
          unset($new_breadcrumb);
        }
      }
      elseif ($path_parts[0] == 'help-0') {
        $title = 'Help';
        $title_tag = 'Help';
        $pre_crumb_title = 'Help';

        $cf['data']['page']['precrumb'] = '<div class="crumb-right_side">' . $title . '</div>';
        $cf['show']['page']['precrumb'] = TRUE;

        $new_breadcrumb = array();
        $new_breadcrumb[] = array_shift($cf['page']['breadcrumb']);

        $new_breadcrumb[] = '<a href="' . $base_path . $path_parts[0] . $url_arguments . '" title="' . $pre_crumb_title . '">' . $pre_crumb_title . '</a>';

        if ($count_parts > 1) {
          $title = mcneese_fcs_url_cleanup_for_title($path_parts[1]);
          $title_tag .= ' - ' . $title;
          $new_breadcrumb[] = '<a href="' . $base_path . $path_parts[0] . '/' . $path_parts[1] . $url_arguments . '" title="' . $title_tag . '">' . $title . '</a>';
        }

        if ($count_parts > 2) {
          $title = mcneese_fcs_url_cleanup_for_title($path_parts[2]);
          $title_tag .= ' - ' . $title;
          $new_breadcrumb[] = '<a href="' . $base_path . $path_parts[0] . '/' . $path_parts[1] . '/' . $path_parts[2] . $url_arguments . '" title="' . $title_tag . '">' . $title . '</a>';
        }

        if ($count_parts > 3) {
          $title = mcneese_fcs_url_cleanup_for_title($path_parts[3]);
          $title_tag .= ' - ' . $title;
          $new_breadcrumb[] = '<a href="' . $base_path . $path_parts[0] . '/' . $path_parts[1] . '/' . $path_parts[2] . '/' . $path_parts[3] . $url_arguments . '" title="' . $title_tag . '">' . $title . '</a>';
        }

        // original breadcrumb gets overridden to remove extra/invalid url paths.
        $cf['page']['breadcrumb'] = $new_breadcrumb;
        unset($new_breadcrumb);

        $rebuild_breadcrumb = TRUE;
      }
      elseif ($path_parts[0] == 'users-0') {
        $pre_crumb_title = 'Users';
        $title = 'Management';

        $cf['data']['page']['precrumb'] = '<div class="crumb-right_side">' . $pre_crumb_title . '</div>';
        $cf['show']['page']['precrumb'] = TRUE;

        $new_breadcrumb = array();
        $new_breadcrumb[] = array_shift($cf['page']['breadcrumb']);
        $new_breadcrumb[] = '<a href="' . $base_path . 'requests/management' . $url_arguments . '" title="' . $title . '">' . $title . '</a>';

        $title = $pre_crumb_title;
        $new_breadcrumb[] = '<a href="' . $base_path . 'users-0/list' . $url_arguments . '" title="' . $title . '">' . $title . '</a>';

        if ($count_parts == 2) {
          if ($path_parts[1] == 'add') {
            $title = 'Add User';
            $cf['data']['page']['precrumb'] = '<div class="crumb-right_side">' . $title . '</div>';
            $new_breadcrumb[] = '<a href="' . $base_path . 'users-0/add' . $url_arguments . '" title="' . $title . '">' . $title . '</a>';
          }
        }
        elseif ($count_parts > 2 && cf_is_integer($path_parts[2])) {
          $cf['data']['page']['precrumb'] = '<div class="crumb-right_side">User ' . $path_parts[2] . '</div>';

          if ($path_parts[1] == 'view') {
            $title = 'View User';
          }
          elseif ($path_parts[1] == 'edit') {
            $title = 'View User';
            $new_breadcrumb[] = '<a href="' . $base_path . 'users-0/view/' . $path_parts[2] . $url_arguments . '" title="' . $title . '">' . $title . '</a>';

            $title = 'Edit User';
          }
          elseif ($path_parts[1] == 'delete') {
            $title = 'Delete User';
          }

          $new_breadcrumb[] = '<a href="' . $base_path . 'users-0/' . $path_parts[1] . '/' . $path_parts[2] . $url_arguments . '" title="' . $title . '">' . $title . '</a>';
        }

        // original breadcrumb gets overridden to remove extra/invalid url paths.
        $cf['page']['breadcrumb'] = $new_breadcrumb;
        $rebuild_breadcrumb = TRUE;
      }
    }
  }

  if ($rebuild_breadcrumb) {
    $cf['data']['page']['breadcrumb'] = theme('breadcrumb', array('breadcrumb' => $cf['page']['breadcrumb']));
  }


  // overrride and replace header_menu_1 with custom header menu.
  $markup = '<nav class="menu html_tag-nav">';
  $markup .= '  <ul class="navigation_list html_tag-list">';
  if (isset($cf['user']['object']) && is_object($cf['user']['object']) && $cf['user']['object']->uid == 0) {
    $markup .= '    <li class="leaf menu_link-wrapper menu_link-wrapper-standout menu_link-login-wrapper first"><a class="menu_link menu_link-login" href="' . $base_path . 'login' . $url_arguments . '" title="Login to the System">Login</a></li>';
  }
  else {
    if (!isset($path_parts)) {
      $path_parts = explode('/', $cf['at']['path']);
      $count_parts = count($path_parts);
    }

    if ($count_parts >= 3 && $path_parts[0] == 'requests' && $path_parts[1] == 'create-0' && is_numeric($path_parts[2])) {
      $markup .= '    <li class="leaf menu_link-wrapper menu_link-wrapper-standout menu_link-copy_request-wrapper first"><a class="menu_link menu_link-copy_request" href="' . $base_path . 'requests/create-0' . $url_arguments . '" title="Copy an Existing Request">Copy Request</a></li>';
    }
    else {
      $markup .= '    <li class="leaf menu_link-wrapper menu_link-wrapper-standout menu_link-create_request-wrapper first"><a class="menu_link menu_link-create_request" href="' . $base_path . 'requests/create-0' . $url_arguments . '" title="Create a New Request">Create Request</a></li>';
    }

    $markup .= '    <li class="leaf menu_link-wrapper menu_link-this_month-wrapper"><a class="menu_link menu_link-this_month" href="' . $base_path . 'requests/calendar-0/month' . $url_arguments . '" title="View Calendar for This Month">This Month</a></li>';
    $markup .= '    <li class="leaf menu_link-wrapper menu_link-this_day-wrapper"><a class="menu_link menu_link-this_day" href="' . $base_path . 'requests/calendar-0/day' . $url_arguments . '" title="View Calendar for Today">This Day</a></li>';
  }

  $markup .= '    <li class="leaf menu_link-wrapper menu_link-facilities_use_information-wrapper"><a class="menu_link menu_link-facilities_use_information" href="//www.mcneese.edu/facilities/facilitiesuse" title="Facilities Use Information">Facilities Use Information</a></li>';
  $markup .= '    <li class="leaf menu_link-wrapper menu_link-my_mcneese-wrapper last"><a class="menu_link menu_link-my_mcneese" href="//mymcneese.mcneese.edu/" title="Go Back to MyMcNeese Portal">MyMcneese</a></li>';

  // show help tab for logged in users.
  if ($user->uid > 0) {
    $markup .= '    <li class="leaf menu_link-wrapper menu_link-help-wrapper"><a class="menu_link menu_link-help" href="' . $base_path . 'help-0' . $url_arguments . '" title="View Online Documentation">Help</a></li>';
  }


  $markup .= '  </ul>';
  $markup .= '</nav>';

  $cf['data']['page']['header_menu_1'] = $markup;
  $cf['show']['page']['header_menu_1'] = TRUE;
  $cf['show']['page']['header'] = TRUE;
  unset($markup);
}

/**
 * Converts a given text message into a more title-friendly format.
 *
 * Underscores are converted to spaces.
 * Dashes have spaces added to either side.
 *
 * @return string
 *   A converted string.
 */
function mcneese_fcs_url_cleanup_for_title($text) {
  if (!is_string($text)) {
    return '';
  }

  // remove leading and trailing spaces.
  $text = trim($text);

  // add spacing before and after dashes.
  $text = str_replace('-', ' - ', $text);

  // replace underscores with spaces
  $text = str_replace('_', ' ', $text);

  // replace all consecutive spaces with a single space (also replacing tabs and other whitespace with a single space).
  $text = preg_replace('/\s+/i', ' ', $text);

  // convert each word to have the first character uppercased.
  $parts = explode(' ', $text);

  if (empty($parts)) {
    return ucfirst($text);
  }

  $text = NULL;
  foreach ($parts as $part) {
    if (is_null($text)) {
      $text = '';
    }
    else {
      $text .= ' ';
    }

    $text .= ucfirst($part);
  }
  unset($part);
  unset($parts);

  return $text;
}

/**
 * Custom implementation of theme_breadcrumb().
 *
 * This removes the breadcrumb wrapper, requiring the theme to specify the
 * wrapper tag.
 *
 * @see: theme_breadcrumb()
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

  $extra_class = '';
  if (count($breadcrumb) < 2) {
    $extra_class = ' display_home_text';
  }

  $breadcrumb[0] = '<a href="' . base_path() . 'requests' . $url_arguments . '" class="link-home' . $extra_class . '" title="Facilities Use System Dashboard">Facilities Use System Dashboard</a>';

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

  global $base_path;
  global $user;

  $is_requester = user_access('mfcs request', $user);
  $is_reviewer = user_access('mfcs review', $user);
  $is_manager = user_access('mfcs manage', $user);
  $is_administer = user_access('mfcs administer', $user);

  $tree = toolbar_get_menu_tree();

  if ($is_manager || $is_administer) {
    $custom_tree = menu_build_tree('navigation', array(
      'conditions' => array('ml.link_path' => 'requests/troubleshoot-0'),
      'min_depth' => 2,
      'max_depth' => 2,
    ));
    $tree = array_merge($custom_tree, $tree);

    $custom_tree = menu_build_tree('navigation', array(
      'conditions' => array('ml.link_path' => 'requests/unavailable-0'),
      'min_depth' => 2,
      'max_depth' => 2,
    ));
    $tree = array_merge($custom_tree, $tree);

    $custom_tree = menu_build_tree('navigation', array(
      'conditions' => array('ml.link_path' => 'requests/holiday-0'),
      'min_depth' => 2,
      'max_depth' => 2,
    ));
    $tree = array_merge($custom_tree, $tree);

    $custom_tree = menu_build_tree('navigation', array(
      'conditions' => array('ml.link_path' => 'users-0/list'),
      'min_depth' => 1,
      'max_depth' => 1,
    ));
    $tree = array_merge($custom_tree, $tree);

    // do not add proxy and reviewers to admin menus because there is simply not enough room.
    if (!$is_administer) {
      $custom_tree = menu_build_tree('navigation', array(
        'conditions' => array('ml.link_path' => 'requests/proxy-0'),
        'min_depth' => 2,
        'max_depth' => 2,
      ));
      $tree = array_merge($custom_tree, $tree);

      $custom_tree = menu_build_tree('navigation', array(
        'conditions' => array('ml.link_path' => 'requests/reviewers-0'),
        'min_depth' => 2,
        'max_depth' => 2,
      ));
      $tree = array_merge($custom_tree, $tree);
    }
  }
  elseif ($is_requester) {
    $custom_tree = menu_build_tree('navigation', array(
      'conditions' => array('ml.link_path' => 'requests/unavailable-0'),
      'min_depth' => 2,
      'max_depth' => 2,
    ));
    $tree = array_merge($custom_tree, $tree);

    $custom_tree = menu_build_tree('navigation', array(
      'conditions' => array('ml.link_path' => 'requests/holiday-0'),
      'min_depth' => 2,
      'max_depth' => 2,
    ));
    $tree = array_merge($custom_tree, $tree);
  }

  if (($is_reviewer || $is_manager) && !$is_administer) {
    $custom_tree = menu_build_tree('navigation', array(
      'conditions' => array('ml.link_path' => 'requests/review-0'),
      'min_depth' => 2,
      'max_depth' => 2,
    ));
    $tree = array_merge($custom_tree, $tree);
  }

  if ($is_requester) {
    $custom_tree = menu_build_tree('navigation', array(
      'conditions' => array('ml.link_path' => 'requests/list-0'),
      'min_depth' => 2,
      'max_depth' => 2,
    ));
    $tree = array_merge($custom_tree, $tree);

    $custom_tree = menu_build_tree('navigation', array(
      'conditions' => array('ml.link_path' => 'requests/management'),
      'min_depth' => 2,
      'max_depth' => 2,
    ));
    $tree = array_merge($custom_tree, $tree);
  }

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

      $vars['toolbar']['toolbar_user']['#links'][$key]['href'] = 'users-0/view/' . $user->uid . $url_arguments;
    }
  }

  if (!empty($vars['toolbar']['toolbar_drawer'][0]['shortcuts'])) {
    foreach ($vars['toolbar']['toolbar_drawer'][0]['shortcuts'] as $key => $link) {
      if (empty($link['#href'])) continue;

      $vars['toolbar']['toolbar_drawer'][0]['shortcuts'][$key]['#href'] .= $url_arguments;
    }
  }

  // Build a custom home link.
  $vars['toolbar']['custom_home_link'] = array();
  $vars['toolbar']['custom_home_link']['attributes'] = array();
  $vars['toolbar']['custom_home_link']['attributes']['class'] = array();
  $vars['toolbar']['custom_home_link']['attributes']['class'][] = 'mfcs-toolbar-dashboard';
  $vars['toolbar']['custom_home_link']['attributes']['class'][] = 'mcneese-toolbar-home';
  $vars['toolbar']['custom_home_link']['attributes']['class'][] = 'item';
  $vars['toolbar']['custom_home_link']['attributes']['title'] = 'View your dashboard.';
  $vars['toolbar']['custom_home_link']['markup'] = '<a href="' . $base_path . 'requests' . $url_arguments . '" class="link noscript" tabindex="1">View Dashboard</a>';
}

/**
 * @} End of '@defgroup mcneese McNeese - Faculty Use Theme'.
 */

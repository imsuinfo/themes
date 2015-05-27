<?php
/**
 * @file
 * Page theme implementation.
 */
  $cf = & drupal_static('cf_theme_get_variables', array());

  $title = check_plain($node->title);
  $nid = check_plain($node->nid);

  $old_timezone = date_default_timezone_get();
  date_default_timezone_set($node->field_police_date['und'][0]['timezone_db']);
  $police_date_stamp = strtotime($node->field_police_date['und'][0]['value']);
  date_default_timezone_set($old_timezone);

  $police_date = date("m/d/Y - h:ia", $police_date_stamp);
?>

<div id="police_complaint_log-entry-<?php print($nid); ?>" class="police_complaint_log-entry">
  <h2 class="police_complaint_log-title">Complaint Number: <?php print($title); ?></h2>
  <div class="police_complaint_log-block">
    <div class="police_complaint_log-item police_complaint_log-item-odd police_complaint_log-nature_of_complaint">
      <h3 class="police_complaint_log-item-label police_complaint_log-nature_of_complaint-label"><strong>Nature of Complaint:</strong></h3>
      <div class="police_complaint_log-item-value police_complaint_log-nature_of_complaint-value"><?php print($node->field_police_nat_of_comp['und'][0]['safe_value']); ?></div>
    </div>

    <div class="police_complaint_log-item police_complaint_log-item-even police_complaint_log-disposition">
      <h3 class="police_complaint_log-item-label police_complaint_log-disposition-label"><strong>Disposition:</strong></h3>
      <div class="police_complaint_log-item-value police_complaint_log-disposition-value"><?php print($node->field_police_disposition['und'][0]['safe_value']); ?></div>
    </div>

    <div class="police_complaint_log-item police_complaint_log-item-odd police_complaint_log-date">
      <h3 class="police_complaint_log-item-label police_complaint_log-date-label"><strong>Date:</strong></h3>
      <div class="police_complaint_log-item-value police_complaint_log-date-value"><?php print($police_date); ?></div>
    </div>

    <div class="police_complaint_log-item police_complaint_log-item-even police_complaint_log-offense_location">
      <h3 class="police_complaint_log-item-label police_complaint_log-offense_location-label"><strong>Offense Location:</strong></h3>
      <div class="police_complaint_log-item-value police_complaint_log-offense_location-value"><?php print($node->field_police_off_loc['und'][0]['safe_value']); ?></div>
    </div>

    <div class="police_complaint_log-item police_complaint_log-item-odd police_complaint_log-synopsis">
      <h3 class="police_complaint_log-item-label police_complaint_log-synopsis-label"><strong>Synopsis:</strong></h3>
      <div class="police_complaint_log-item-value police_complaint_log-synopsis-value"><?php print($node->field_police_synopsis['und'][0]['safe_value']); ?></div>
    </div>
  </div>
</div>

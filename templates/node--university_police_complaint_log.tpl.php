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
  $occurred_date_stamp = strtotime($node->field_occured_date['und'][0]['value']);
  date_default_timezone_set($old_timezone);
  date_default_timezone_set($old_timezone);

  $police_date = date("m/d/Y - h:ia", $police_date_stamp);
  $occurred_date = date("m/d/Y - h:ia", $occurred_date_stamp);

  print('<div id="police_complaint_log-entry-print($nid); " class="police_complaint_log-entry">');
  print('<h2 class="police_complaint_log-title">Complaint Number:');
  print($title);
  print('</h2>');

  print('<div class="police_complaint_log-block">');
  print('<div class="police_complaint_log-item police_complaint_log-item-odd police_complaint_log-nature_of_complaint">');
  print('<h3 class="police_complaint_log-item-label police_complaint_log-nature_of_complaint-label"><strong>Nature of Complaint:</strong></h3>');
  print('<div class="police_complaint_log-item-value police_complaint_log-nature_of_complaint-value">');
  print($node->field_police_nat_of_comp['und'][0]['safe_value']);
  print('</div>');
  print('</div>');

  print('<div class="police_complaint_log-item police_complaint_log-item-even police_complaint_log-disposition">');
  print('<h3 class="police_complaint_log-item-label police_complaint_log-disposition-label"><strong>Disposition:</strong></h3>');
  print('<div class="police_complaint_log-item-value police_complaint_log-disposition-value">');
  print($node->field_police_disposition['und'][0]['safe_value']);
  print('</div>');
  print('</div>');

  print('<div class="police_complaint_log-item police_complaint_log-item-odd police_complaint_log-date">');
  print('<h3 class="police_complaint_log-item-label police_complaint_log-reported-date-label"><strong>Reported Date:</strong></h3>');
  print('<div class="police_complaint_log-item-value police_complaint_log-reported-date-value">');
  print($police_date);
  print('</div>');
  print('</div>');

  print('<div class="police_complaint_log-item police_complaint_log-item-even police_complaint_log-date">');
  print('<h3 class="police_complaint_log-item-label police_complaint_log-ocurred-date-label"><strong>Occurred Date:</strong></h3>');
  print('<div class="police_complaint_log-item-value police_complaint_log-ocurred-date-value">');
  print($occurred_date);
  print('</div>');
  print('</div>');

  print('<div class="police_complaint_log-item police_complaint_log-item-odd police_complaint_log-offense_location">');
  print('<h3 class="police_complaint_log-item-label police_complaint_log-offense_location-label"><strong>Offense Location:</strong></h3>');
  print('<div class="police_complaint_log-item-value police_complaint_log-offense_location-value">');
  print($node->field_police_off_loc['und'][0]['safe_value']);
  print('</div>');
  print('</div>');

  print('<div class="police_complaint_log-item police_complaint_log-item-even police_complaint_log-synopsis">');
  print('<h3 class="police_complaint_log-item-label police_complaint_log-synopsis-label"><strong>Synopsis:</strong></h3>');
  print('<div class="police_complaint_log-item-value police_complaint_log-synopsis-value">');
  print($node->field_police_synopsis['und'][0]['safe_value']);
  print('</div>');
  print('</div>');
  print('</div>');
  print('</div>');

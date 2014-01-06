<?php
/**************
*  Commonly used functions are stored here
***************/

/**
 * GeneratePanel
 * $panelName : The title for the panel
 * $dates :
 * Array of dates and hours.
 * We expect each item in the array to be structured like this:
    Array
    (
      [2013-12-16] => 8
      [2013-12-17] => 8
      ...   
    )
 * ---
 * Returns:
 * Html block for the project
 */
function GenerateProjectPanel($panelName, $dates, $groupBy = 'user') {
  // Calculate total hours
  $totalHours = 0;

  // Total Hours calc
  switch($groupBy) {
    case 'user':
      foreach($dates as $key => $hours)
        $totalHours += $hours;  
      break;
    
    case 'month':
      foreach($dates as $days)
        foreach($days as $hours)
          $totalHours += $hours;      
      break;
  }

  // build the top
  $html = '<div class="panel panel-info project-details">'."\n".
          '  <div class="panel-heading">'."\n".
          '    <div class="row">'."\n".
          '      <div class="col-sm-10 panel-title">'.$panelName.'</div>'."\n";
          // optional TotalHours
  $html .='      <div style="text-align:right" class="col-sm-2">Total hours: '.$totalHours.'</div>'."\n";          
  $html .='    </div>'."\n".
          '  </div> ';
  
  // Fill the calendar
  if($groupBy == 'user')
    $html .= GeneratePanelCalendar($dates);
  else if ($groupBy == 'month') {
    // If we're grouped by month, we're already formatted corectly
    foreach($dates as $title => $days)
      $html .= GenerateMonth($title, $days);
  }
  
  // close up shop
  $html .='</div>';
  
  return $html;
}


/**
 * this will build a calendar (multiple months) if given the below data
 * Pass me an array of date => hours arrays
 * [Dates] => Array
 * (
 *   [2013-12-16] => 8
 *   [2013-11-16] => 2
 *   [2013-01-16] => 5
 *   ...
 *
 */
function GeneratePanelCalendar($dates){ 

  // Arrange the dates in desc order
  krsort($dates);
  
  // $months will store our processed data like this:
  /*
  
  $months => (
    ['November'] => (
      [1] => 8,
      [2] => 4,
      ...
      [31] => 0
    )
  )
  */
  $months = array(); 
  
  // Run thru each date and stick it in its respective month and date
  foreach($dates as $date => $hours) {
    // Convert the date string to unix timestamp
    $unix = strtotime($date);
    // Get the Month (full name: November)
    $month = date('F', $unix);
    // Get the Day (as in number, like 31)
    $day = intval(date('j', $unix));
    
    // If the month doesn't exist, create it
    if(!array_key_exists($month, $months)) {
      $months[$month] = array_fill(1,31,0);
    }
    
    // Add the data to the array
    $months[$month][$day] = $hours;    
  }
  
  // Store our finished HTML here
  $html = '';
  
  // Now loop thru each month and print it
  foreach($months as $month => $dates) {
    $html .= GenerateMonth($month, $dates);
  }
  
  return $html;
}

/**
* Generate Month
* Params
* $month : (String) Full name of this month
* $days  : (Array of days) Days can be either Ints or Strings. 
*           Array's index is assumed the date.
*           If array is < 31 days, mising days will be 0.
* ---
* Returns:
* String of HTML representing the month passed in.
*/
function GenerateMonth($month, $inDays) {
  // Start off with the month header
  $html = '<h3 class="monthHeader">'.$month.'</h3>'."\n".
          '<div class="month">'."\n";
  
  // Create the days array filling it with zeros (default)
  $days = array_fill(1,31,0);
  
  // Merge the input days with days
  foreach($inDays as $day => $hours) {
    $days[$day] = $hours;
  }
  
  // Print the 31 calendar days
  for($i=1; $i < 32; $i++) {
    $html .= '  '.GenerateDay($i);
  }
  
  // Total up the hours
  $totalHours = 0;
  
  // Print the actual $days
  foreach($days as $day) {
    $html .= '  '.GenerateDay($day);
    $totalHours += $day;
  }
  
  // add the total element
  $html .= '<span><span>Month\'s Total:</span> '.$totalHours.'</span>'."\n";
  
  // Close up the month
  $html .= "</div>\n";

  return $html;  
}

// Genrates a day with $content inside;
function GenerateDay($content) {
  return '<span>'.sprintf("%-2s", $content).'</span>'."\n";
}

?>
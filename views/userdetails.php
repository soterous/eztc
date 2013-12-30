<?php
require_once('header.php');

$qUserProjects = "SELECT `Project`.`Code` as Code, `TimeEntry`.`Hours` as Hours, `TimeEntry`.`Date` as Date, `Employee`.`Name`
 as Name FROM `TimeEntry` LEFT JOIN `Project` ON `TimeEntry`.`ProjectId` = `Project`.`Id` LEFT JOIN `Employee` ON 
 `TimeEntry`.`EmployeeId` = `Employee`.`Id` WHERE `Employee`.`Name` = ? Order By `TimeEntry`.`Date` DESC, `Project`.`Code` ASC;";
 
// Get instance of statement
$stmt = $mysqli->stmt_init();
// Pull all the user's projects and entries
$stmt->prepare($qUserProjects) or die("Couldn't prepare user project query");
$stmt->bind_param("s", $user);
$stmt->execute();
$res = $stmt->get_result() or die("Could not get results for user project query");

$data = array();

/* This properly formats our $data so we can use it easier
 * Basic final structure is something like this:
 * Array
  (
    [INDIR.0001.UTRI.LABV] => Array
    (
      [TotalHours] => 32
      [Dates] => Array
      (
        [2013-12-16] => 8
        [2013-12-17] => 8
        [2013-12-18] => 8
        [2013-12-19] => 8
        [2013-12-20] => 0
        [2013-12-21] => 0
        [2013-12-22] => 0
        [2013-12-23] => 0
        [2013-12-24] => 0
        [2013-12-25] => 0
        [2013-12-26] => 0
        [2013-12-27] => 0
        [2013-12-28] => 0
        [2013-12-29] => 0
        [2013-12-30] => 0
        [2013-12-31] => 0
      )
    )
  )
*/
while($row = $res->fetch_array(MYSQLI_ASSOC)) {
  if(!isset($sqlUser))
    $sqlUser = $row['Name'];
  
  // Initialize this project's entry if it doesn't exist
  if(!array_key_exists($row['Code'], $data))
    $data[$row['Code']] = array('TotalHours' => 0, 'Dates' => array());
    
  // add hours
  $data[$row['Code']]['TotalHours'] += $row['Hours'];
  
  // Add the Date and Hours to the project entry
  $data[$row['Code']]['Dates'][$row['Date']] = $row['Hours'];
  
}
?> 
  <div class="container">
  
  <?php
    // This is a test of the new functions (omg what's unit testing?)
    
    $fakeItem = array
    (
      '2013-12-16' => 8,
      '2013-12-17' => 8,
      '2013-12-18' => 8,
      '2013-12-19' => 8,
      '2013-12-20' => 0,
      '2013-12-21' => 0,
      '2013-12-22' => 0,
      '2013-12-23' => 0,
      '2013-12-24' => 0,
      '2013-12-25' => 8,
      '2013-12-26' => 0,
      '2013-12-27' => 0,
      '2013-12-28' => 0,
      '2013-12-29' => 0,
      '2013-12-30' => 0,
      '2013-12-31' => 0
    
    );
    
    echo 'test start';
    echo GenerateProjectPanel('INDIR.000001', $fakeItem);
    echo GeneratePanelCalendar($fakeItem);
    echo 'test end';
 
  ?>

  
  
  
      <div class="row details-title">
        <div class="col-sm-8"><h2><?php if(isset($sqlUser)) echo $sqlUser; else echo 'No user data found'; ?></h2></div>
        <div class="col-sm-4"><h3><!-- Charge Rate: $23 Not Yet Implemented --></h3></div>
      </div>     
      
      

    <?php
      // Iterate over each project string
      foreach($data as $projectCode => $project):
    ?>
      <div class="panel panel-info project-details">
        <div class="panel-heading">
          <div class="row">
            <div class="col-sm-10"><?php echo $projectCode; ?></div>
            <div class="col-sm-2" style="text-align:right">Total hours: <?php echo $project['TotalHours']; ?></div>
          </div>
        </div> 
      <?php        
        // We use this to know when the month has changed
        $currentMonth = 'first';
        $calendar = array_fill(1, 31, 0);
        // Sort the dates so they're all in order
        //asort($project['Dates']);
        foreach($project['Dates'] as $sDate => $hours) {
          // Convert the date string to unix timestamp
          $unix = strtotime($sDate);
          // Get the Month
          $month = date('F', $unix);
          // Get the Day
          $day = intval(date('j', $unix));
          
          // Are we starting a new month?
          if($month != $currentMonth){
          
          if($currentMonth != 'first') {
              //echo '      <div class="month">'."\n";
              
              foreach($calendar as $hours)
                echo "      <span>$hours </span>\n";
            
              echo "        <span><span>Total:</span> ".array_sum($calendar)."</span>\n";
              echo '      </div> <!-- /week -->'."\n";
            }
          
            // If we're not on the first month, dump the calendar and close it up
            // Spit the month header
            echo '        <h3 class="monthHeader">'.$month.'</h3>'."\n";
            echo '        <div class="month">'."\n";
            for($i = 1; $i <= 31; $i++)
              echo '        <span>'.$i.'</span>'."\n";
            //echo '        </div>'."\n";
            
            
            
            
            
            // Reset the calendar
            $calendar = array_fill(1, 31, 0);
            // Save the current month
            $currentMonth = $month;
          }
          
          
          // Add today's hours to the calendar
          $calendar[$day] = $hours;
        }
          //echo '      <div class="month">'."\n";
          
          foreach($calendar as $hours)
            echo "      <span>$hours </span>\n";
        
          echo "        <span><span>Total:</span> ".array_sum($calendar)."</span>\n";
          echo '      </div> <!-- /week -->'."\n";
        echo '</div> <!-- /panel -->'."\n";
      endforeach;
    ?>
      
    </div> <!-- /container -->
  
    
<?php require_once('footer.php'); ?>
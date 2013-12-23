<?php
require_once('header.php');

$qUserProjects = "SELECT `project`.`Code` as Code, `timeentry`.`Hours` as Hours, `timeentry`.`Date` as Date, `employee`.`Name`
 as Name FROM timeentry LEFT JOIN project ON `timeentry`.`ProjectId` = `project`.`Id` LEFT JOIN employee ON 
 `timeentry`.`EmployeeId` = `employee`.`Id` WHERE `employee`.`Name` = ? Order By `timeentry`.`Date`;";
 
// Get instance of statement
$stmt = $mysqli->stmt_init();
// Pull all the user's projects and entries
$stmt->prepare($qUserProjects) or die("Couldn't prepare user project query");
$stmt->bind_param("s", $user);
$stmt->execute();
$res = $stmt->get_result() or die("Could not get results for user project query");

$data = array();

/* This properly formats our $data so we can use it easier
 * Basic structure is something like this:
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
      <div class="row details-title">
        <div class="col-sm-8"><h2><?php echo $sqlUser; ?></h2></div>
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
      // Iterate over each date for the current project string
      $currentMonth = 'first';
      $thisMonthsTotal = 0;
      foreach($project['Dates'] as $sDate => $hours){
        $unix = strtotime($sDate);
        // Parse the shorthand month
        $month = date('M', $unix);
        // Parse the date format eg: 12/28
        $date = date('n/j', $unix);
        // Are we starting a new month?
        if($month != $currentMonth){
          // If we're not the first month, close the previous month's div and spit their monthly total
          if($currentMonth != 'first') {
            echo "          <div><span>Total:</span>$thisMonthsTotal</div>\n";
            echo '        </div> <!-- /calendar -->'."\n";
          }
          
          // Start the month block
          echo '    <div class="calendar">'."\n";
          // Echo the month
          echo "          <div>$month</div>\n";
          
          // Save the month
          $currentMonth = $month;
          
          // Reset
          $thisMonthsTotal = 0;
        }
        
        $thisMonthsTotal += $hours;
        echo "          <div><span>$date</span>$hours</div>\n";
      }
    // Close the last Calendar
    echo "          <div><span>Total:</span>$thisMonthsTotal</div>\n";
    echo '        </div> <!-- /calendar -->'."\n";
    // Close the panel div
    echo "      </div> <!-- /panel -->\n";
    endforeach;
    ?>
      
     
    </div> <!-- /container -->
    
    
    
<?php require_once('footer.php'); ?>
<?php
require_once('header.php');

// Build the correct query (only sorting is different)
switch ($groupBy) {
  case 'user':
    $qProjectDetails = "SELECT `Project`.`Code`, `Employee`.`Name`, `TimeEntry`.`Date`, `TimeEntry`.`Hours`
                    FROM `TimeEntry` LEFT JOIN `Employee` ON `TimeEntry`.`EmployeeId`=`Employee`.`Id`
                    LEFT JOIN `Project` ON `TimeEntry`.`ProjectId` = `Project`.`Id` WHERE
                    `Project`.`Code`= ? AND `TimeEntry`.`Hours` <> 0 ORDER BY `Employee`.`Name`, `TimeEntry`.`Date`";
    break;
  case 'month':
    $qProjectDetails = "SELECT `Project`.`Code`, `Employee`.`Name`, `TimeEntry`.`Date`, `TimeEntry`.`Hours`
                    FROM `TimeEntry` LEFT JOIN `Employee` ON `TimeEntry`.`EmployeeId`=`Employee`.`Id`
                    LEFT JOIN `Project` ON `TimeEntry`.`ProjectId` = `Project`.`Id` WHERE
                    `Project`.`Code`= ? AND `TimeEntry`.`Hours` <> 0 ORDER BY `TimeEntry`.`Date` DESC";
    break;
 default:
      echo '<h1>Bad grouping passed</h1>';
      exit;
      break;
}
// Get instance of statement
$stmt = $mysqli->stmt_init();
// Pull all the projects in panels
$stmt->prepare($qProjectDetails) or die("Couldn't prepare project query");
$stmt->bind_param("s", $project);
$stmt->execute();
$res = $stmt->get_result() or die("Could not get results for user project query");

$data = array();

/* This properly formats our $data so we can use it easier
 * Basic final structure is something like this: (ONLY FOR USER BUT SIMILAR FOR MONTH)
 * Array
  (
    ['John Smith'] => Array
    (     
      [2013-12-16] => 8
      [2013-12-17] => 8
      [2013-12-18] => 8
      [2013-12-19] => 8
      [2013-12-30] => 3
      [2013-12-31] => 5      
    ),
    ['Abraham Lincoln'] => Array
    (     
      [2013-11-16] => 8
      [2013-12-17] => 8
      [2013-11-18] => 8
      [2013-12-19] => 8
      [2013-12-23] => 3   
    )
  )
*/
// GrandTotal will be the total number of hours billed against this project
$GrandTotal = 0;
// Our data comes to us sorted by name so they're in the proper order already
while($row = $res->fetch_array(MYSQLI_ASSOC)) {
  if(!isset($sqlProject))
    $sqlProject = $row['Code'];      
    
  // Different sorting methods for different params
  switch($groupBy) {
    case 'user':
      // Initialize this user's entry if it doesn't exist
      if(!array_key_exists($row['Name'], $data))
        $data[$row['Name']] = array();
      
      // Add the Date and Hours to the project entry
      $data[$row['Name']][$row['Date']] = $row['Hours'];
      break;
      
    case 'month':
      // Convert month (number) to Month (word)
      $expMonth = explode('-', $row['Date']);
      //$month = date("F", mktime(0, 0, 0, $expMonth[1], 1));
      $month = $expMonth[0].'-'.$expMonth[1];
      
      // Initialize if not created
      if(!array_key_exists($month, $data))
        $data[$month] = array();
      
      if(!array_key_exists($row['Name'], $data[$month]))
        $data[$month][$row['Name']] = array();
      
      // Build the data chunk
      $data[$month][$row['Name']][$row['Date']] = $row['Hours'];
      
      break;    
  }
  
  $GrandTotal += $row['Hours'];  
}

?> 
  <div class="container">
  
    <div class="row details-title">
      <div class="col-sm-8"><h2><?php if(isset($sqlProject)) echo $sqlProject; else echo 'No user data found'; ?></h2></div>
      <div class="col-sm-4" style="text-align:right"><h3>Summed Total Hours: <?php echo $GrandTotal; ?></h3></div>
    </div>     
    
    <div id="projects-list">
    
   
        <div class="row">
          <div class="col-sm-4">
            <?php if($groupBy == 'user') echo '<input class="clearable search" placeholder="Filter By Name" value="" autocomplete="off" />'; ?>
            <?php if($groupBy == 'month') echo '<input class="clearable search" placeholder="Filter By Month" value="" autocomplete="off" />'; ?>
          </div>
          <div class="col-sm-8">
            <div class="btn-group pull-right">
              <a <?php echo 'href="'.substr($FullUrl, 0, strrpos($FullUrl, '/')).'/user"'; ?> class="btn <?php echo ($groupBy == 'user' ? 'btn-success disabled' : 'btn-default'); ?>">User</a>
              <a <?php echo 'href="'.substr($FullUrl, 0, strrpos($FullUrl, '/')).'/month"'; ?> class="btn <?php echo ($groupBy == 'month' ? 'btn-success disabled' : 'btn-default'); ?>">Month</a>
            </div>
          </div>          
        </div>      

    



      <?php
        foreach($data as $projCode => $dates) {
          if($groupBy == 'month') {
            $expMonth = explode('-', $projCode);
            $projCode = date("F", mktime(0, 0, 0, $expMonth[1], 1));
          }
          echo GenerateProjectPanel($projCode, $dates, $groupBy);
        }
      ?>
    </div>  
    
  </div> <!-- /container -->

    
<?php require_once('footer.php'); ?>
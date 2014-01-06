<?php
require_once('header.php');

$qProjectDetails = "SELECT `Project`.`Code`, `Employee`.`Name`, `TimeEntry`.`Date`, `TimeEntry`.`Hours`
                    FROM `TimeEntry` LEFT JOIN `Employee` ON `TimeEntry`.`EmployeeId`=`Employee`.`Id`
                    LEFT JOIN `Project` ON `TimeEntry`.`ProjectId` = `Project`.`Id` WHERE
                    `Project`.`Code`= ? ORDER BY `Employee`.`Name`, `TimeEntry`.`Date`";
 
// Get instance of statement
$stmt = $mysqli->stmt_init();
// Pull all the projects in panels
$stmt->prepare($qProjectDetails) or die("Couldn't prepare project query");
$stmt->bind_param("s", $project);
$stmt->execute();
$res = $stmt->get_result() or die("Could not get results for user project query");

$data = array();

/* This properly formats our $data so we can use it easier
 * Basic final structure is something like this:
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
// Our data comes to us sorted by name so they're in the proper order already
while($row = $res->fetch_array(MYSQLI_ASSOC)) {
  if(!isset($sqlProject))
    $sqlProject = $row['Code'];      
  
  // Initialize this project's entry if it doesn't exist
  if(!array_key_exists($row['Name'], $data))
    $data[$row['Name']] = array();
  
  // Add the Date and Hours to the project entry
  $data[$row['Name']][$row['Date']] = $row['Hours'];
  
}
?> 
  <div class="container">
  
    <div class="row details-title">
      <div class="col-sm-8"><h2><?php if(isset($sqlProject)) echo $sqlProject; else echo 'No user data found'; ?></h2></div>
      <div class="col-sm-4"><h3><!-- Charge Rate: $23 Not Yet Implemented --></h3></div>
    </div>     
    
    <div id="projects-list">
      <input class="clearable search" placeholder="Filter By Name" value="" autocomplete="off" />

      <?php
        foreach($data as $projCode => $dates) {
          echo GenerateProjectPanel($projCode, $dates);
        }
      ?>
    </div>  
    
  </div> <!-- /container -->

    
<?php require_once('footer.php'); ?>
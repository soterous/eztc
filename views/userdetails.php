<?php
require_once('header.php');

$qUserProjects = "SELECT `Project`.`Code` as Code, `TimeEntry`.`Hours` as Hours, `TimeEntry`.`Date` as Date, `Employee`.`Name`
 as Name FROM `TimeEntry` LEFT JOIN `Project` ON `TimeEntry`.`ProjectId` = `Project`.`Id` LEFT JOIN `Employee` ON 
 `TimeEntry`.`EmployeeId` = `Employee`.`Id` WHERE `Employee`.`Name` = ? AND `TimeEntry`.`Hours` <> 0 Order By 
 `TimeEntry`.`Date` DESC";
 
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
    ),
    [INDIR.2222.ABC.LEE] => Array
    (     
      [2013-11-16] => 8
      [2013-12-17] => 8
      [2013-11-18] => 8
      [2013-12-19] => 8
      [2013-11-20] => 0
      [2013-12-21] => 0
      [2013-11-22] => 0
      [2013-12-23] => 3
      [2013-11-24] => 0
      [2013-11-21] => 0
      [2013-12-26] => 0
      [2013-12-27] => 0
      [2013-12-28] => 0
      [2013-12-29] => 0
      [2013-12-30] => 0
      [2013-12-31] => 0      
    )
  )
*/
// Our data comes to us sorted by time stamp so the first entry is the newest. 
// We'll print the projects by newest first simply because that's how it's fed.
while($row = $res->fetch_array(MYSQLI_ASSOC)) {
  if(!isset($sqlUser))
    $sqlUser = $row['Name'];      
  
  // Initialize this project's entry if it doesn't exist
  if(!array_key_exists($row['Code'], $data))
    $data[$row['Code']] = array();
  
  // Add the Date and Hours to the project entry
  $data[$row['Code']][$row['Date']] = $row['Hours'];
  
}
?> 
  <script type="text/javascript">
    // do something
  </script>
  <div class="container">
  
    <div class="row details-title">
      <div class="col-sm-8"><h2><?php if(isset($sqlUser)) echo $sqlUser; else echo 'No user data found'; ?></h2></div>
      <div class="col-sm-4"><h3><!-- Charge Rate: $23 Not Yet Implemented --></h3></div>
    </div>     
    
    <div id="projects-list">
      <input class="search" />
      <?php
        foreach($data as $projCode => $dates) {
          echo GenerateProjectPanel($projCode, $dates);
        }
      ?>
    </div>  
    
  </div> <!-- /container -->

    
<?php require_once('footer.php'); ?>
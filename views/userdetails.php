<?php
require_once('header.php');

$qUserProjects = "SELECT `project`.`Code` as Code, `timeentry`.`Hours` as Hours, `timeentry`.`Date` as Date FROM timeentry LEFT JOIN project ON `timeentry`.`ProjectId` = `project`.`Id` LEFT 
JOIN employee ON `timeentry`.`EmployeeId` = `employee`.`Id` WHERE `employee`.`Name` = ? Order By `timeentry`.`Date`;";
 
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
  //print var_dump($row);
  
  // Initialize this project's entry if it doesn't exist
  if(!array_key_exists($row['Code'], $data))
    $data[$row['Code']] = array('TotalHours' => 0, 'Dates' => array());
    
  // add hours
  $data[$row['Code']]['TotalHours'] += $row['Hours'];
  
  // Add the Date and Hours to the project entry
  $data[$row['Code']]['Dates'][$row['Date']] = $row['Hours'];
  
}

// DEBUG
print '<pre>'.print_r($data, true).'</pre>';

foreach($data as $projectCode => $project) {
  echo $projectCode . "\n";
  var_dump($project);
}
?>  
    <div class="container">
      <div class="row details-title">
        <div class="col-sm-8"><h2><?php echo $user /*this is a XSS vuln, don't keep this here */ ?></h2></div>
        <div class="col-sm-4"><h3>Charge Rate: $23</h3></div>
      </div>
    
      <div class="panel panel-info project-details">
        <div class="panel-heading">
          <div class="row">
            <div class="col-sm-10">PROJECT STRING GOES HERE</div>
            <div class="col-sm-2">Total hours: 80</div>
          </div>
        </div>
        <div class="calendar">
          <div>NOV</div>
          <div><span>12/1</span>8</div>
          <div><span>12/2</span>8</div>
          <div><span>12/3</span>8</div>
          <div><span>12/4</span>8</div>
          <div><span>12/5</span>8</div>
          <div><span>12/6</span>8</div>
          <div><span>12/7</span>8</div>
          <div><span>12/8</span>8</div>
          <div><span>12/9</span>8</div>
          <div><span>12/10</span>8</div>
          <div><span>12/11</span>8</div>
          <div><span>12/12</span>8</div>
          <div><span>12/13</span>8</div>
          <div><span>12/14</span>8</div>
          <div><span>12/15</span>8</div>
          <div><span>12/16</span>8</div>
          <div><span>12/17</span>8</div>
          <div><span>12/18</span>8</div>
          <div><span>12/19</span>8</div>
          <div><span>12/20</span>8</div>
          <div><span>12/21</span>8</div>
          <div><span>12/22</span>8</div>
          <div><span>12/23</span>8</div>
          <div><span>12/24</span>8</div>
          <div><span>12/25</span>8</div>
          <div><span>12/26</span>8</div>
          <div><span>12/27</span>8</div>
          <div><span>12/28</span>8</div>
          <div><span>12/29</span>8</div>
          <div><span>12/30</span>8</div>
          <div><span>12/31</span>8</div>  
          <div><span>Total:</span>6969</div>  
        </div>
        
        <div class="calendar">
          <div>DEC</div>
          <div><span>12/1</span>8</div>
          <div><span>12/2</span>8</div>
          <div><span>12/3</span>8</div>
          <div><span>12/4</span>8</div>
          <div><span>12/5</span>8</div>
          <div><span>12/6</span>8</div>
          <div><span>12/7</span>8</div>
          <div><span>12/8</span>8</div>
          <div><span>12/9</span>8</div>
          <div><span>12/10</span>8</div>
          <div><span>12/11</span>8</div>
          <div><span>12/12</span>8</div>
          <div><span>12/13</span>8</div>
          <div><span>12/14</span>8</div>
          <div><span>12/15</span>8</div>
          <div><span>12/16</span>8</div>
          <div><span>12/17</span>8</div>
          <div><span>12/18</span>8</div>
          <div><span>12/19</span>8</div>
          <div><span>12/20</span>8</div>
          <div><span>12/21</span>8</div>
          <div><span>12/22</span>8</div>
          <div><span>12/23</span>8</div>
          <div><span>12/24</span>8</div>
          <div><span>12/25</span>8</div>
          <div><span>12/26</span>8</div>
          <div><span>12/27</span>8</div>
          <div><span>12/28</span>8</div>
          <div><span>12/29</span>8</div>
          <div><span>12/30</span>8</div>
          <div><span>12/31</span>8</div>
          <div><span>Total</span>88</div>
        </div>        
      </div>  
  
      <div class="panel panel-info project-details">
        <div class="panel-heading">
          <div class="row">
            <div class="col-sm-10">INDIR.0001.MOBI.PILO</div>
            <div class="col-sm-2">Total hours: 80</div>
          </div>
        </div>
        <div class="calendar">
          <div>NOV</div>
          <div><span>12/1</span>8</div>
          <div><span>12/2</span>8</div>
          <div><span>12/3</span>8</div>
          <div><span>12/4</span>8</div>
          <div><span>12/5</span>8</div>
          <div><span>12/6</span>8</div>
          <div><span>12/7</span>8</div>
          <div><span>12/8</span>8</div>
          <div><span>12/9</span>8</div>
          <div><span>12/10</span>8</div>
          <div><span>12/11</span>8</div>
          <div><span>12/12</span>8</div>
          <div><span>12/13</span>8</div>
          <div><span>12/14</span>8</div>
          <div><span>12/15</span>8</div>
          <div><span>12/16</span>8</div>
          <div><span>12/17</span>8</div>
          <div><span>12/18</span>8</div>
          <div><span>12/19</span>8</div>
          <div><span>12/20</span>8</div>
          <div><span>12/21</span>8</div>
          <div><span>12/22</span>8</div>
          <div><span>12/23</span>8</div>
          <div><span>12/24</span>8</div>
          <div><span>12/25</span>8</div>
          <div><span>12/26</span>8</div>
          <div><span>12/27</span>8</div>
          <div><span>12/28</span>8</div>
          <div><span>12/29</span>8</div>
          <div><span>12/30</span>8</div>
          <div><span>12/31</span>8</div>  
          <div><span>Total:</span>6969</div>  
        </div>
        
        <div class="calendar">
          <div>DEC</div>
          <div><span>12/1</span>8</div>
          <div><span>12/2</span>8</div>
          <div><span>12/3</span>8</div>
          <div><span>12/4</span>8</div>
          <div><span>12/5</span>8</div>
          <div><span>12/6</span>8</div>
          <div><span>12/7</span>8</div>
          <div><span>12/8</span>8</div>
          <div><span>12/9</span>8</div>
          <div><span>12/10</span>8</div>
          <div><span>12/11</span>8</div>
          <div><span>12/12</span>8</div>
          <div><span>12/13</span>8</div>
          <div><span>12/14</span>8</div>
          <div><span>12/15</span>8</div>
          <div><span>12/16</span>8</div>
          <div><span>12/17</span>8</div>
          <div><span>12/18</span>8</div>
          <div><span>12/19</span>8</div>
          <div><span>12/20</span>8</div>
          <div><span>12/21</span>8</div>
          <div><span>12/22</span>8</div>
          <div><span>12/23</span>8</div>
          <div><span>12/24</span>8</div>
          <div><span>12/25</span>8</div>
          <div><span>12/26</span>8</div>
          <div><span>12/27</span>8</div>
          <div><span>12/28</span>8</div>
          <div><span>12/29</span>8</div>
          <div><span>12/30</span>8</div>
          <div><span>12/31</span>8</div>
          <div><span>Total</span>88</div>
        </div>        
      </div>  
    </div> 
    
    
    
<?php require_once('footer.php'); ?>
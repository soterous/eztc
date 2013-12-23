<?php
error_reporting(E_ALL);
require_once('header.php');

$qProjectDetails = "SELECT `Project`.`Code`, `Employee`.`Name`, `TimeEntry`.`Date`, `TimeEntry`.`Hours`
                    FROM `TimeEntry` LEFT JOIN `Employee` ON `TimeEntry`.`EmployeeId`=`Employee`.`Id`
                    LEFT JOIN `Project` ON `TimeEntry`.`ProjectId` = `Project`.`Id` WHERE
                    `Project`.`Code`= ? ORDER BY `Employee`.`Name`, `TimeEntry`.`Date`";

// Get instance of statement
$stmt = $mysqli->stmt_init();
// Grab Project Details
$stmt->prepare($qProjectDetails) or die("Could not prepare SQL Statement");
$stmt->bind_param("s", $project);
echo var_dump($stmt);
echo "|" . $project . "|";

$stmt->execute();
$res = $stmt->get_result() or die("Could not get results for user project query");

while ($row = $res->fetch_array(MYSQLI_ASSOC))
{
  echo var_dump($row);
}





?>  
    <div class="container">
      <div class="row details-title">
        <div class="col-sm-8"><h2><?php echo $project; /*this is a XSS vuln, don't keep this here */ ?></h2></div>
        <div class="col-sm-4">&nbsp;</div>
      </div>
      
      <div class="panel panel-info project-details">
        <div class="panel-heading">
          <div class="row">
            <div class="col-sm-10">Smith, John</div>
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
            <div class="col-sm-10">Foo, Bar</div>
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
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
    // This is the search code
    $(function() {
      // find the search box and compile a list of bocks that we can search, then save them.
      $('input.search').each(function() {
        var searchInput = $(this);
        
        // Loop thru each panel div
        searchInput.parent().children('div').each(function() {
          var theDiv = $(this);
          var title = theDiv.find('div.panel-heading').first().find('div.row').first().children('div').first().text();
                   
          // Add the data we've pulled (titles are unique)
          var data = searchInput.data('panels');
          if(data == undefined)
            data = [];
          data[title] = theDiv;
          searchInput.data('panels', data);
        });
      });     
      
      // Listen for changes
      $('input.search').change(function() {
        var search = $(this);
        var text = search.val();
        var dataArray = search.data('panels');
        
        // Check for clear
        if(text == '' || text == ' ' || text == '  ') {
          // Loop thru each panel and display it          
          for (var key in dataArray) {
            if (dataArray.hasOwnProperty(key)) {
              dataArray[key].css('display','block');                
            }
          }          
        }
        else {
          // Parse the text value
          for (var key in dataArray) {
            if (dataArray.hasOwnProperty(key)) {
              // If the search text is not contained in the key, hide it
              if(key.toLowerCase().indexOf(text.toLowerCase()) === -1) {
                // text not found in key
                dataArray[key].css('display','none');
              } else {
                // text found in key
                dataArray[key].css('display','block');
              }
            }
          }          
        }        
      }).keyup(function() {
        // If the value changes, we need to re-parse
        $(this).trigger( "change" );
      });
      
    });
  </script>
  <div class="container">
  
    <div class="row details-title">
      <div class="col-sm-8"><h2><?php if(isset($sqlUser)) echo $sqlUser; else echo 'No user data found'; ?></h2></div>
      <div class="col-sm-4"><h3><!-- Charge Rate: $23 Not Yet Implemented --></h3></div>
    </div>     
    
    <div id="projects-list">
      <input class="clearable search" placeholder="Filter By Project String" value="" autocomplete="off" />

      <?php
        foreach($data as $projCode => $dates) {
          echo GenerateProjectPanel($projCode, $dates);
        }
      ?>
    </div>  
    
  </div> <!-- /container -->

    
<?php require_once('footer.php'); ?>
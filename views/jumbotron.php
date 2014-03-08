<?php
  // We must have a value for $user and $project for the select boxes
  if(!isset($user))
    $user = null;
  if(!isset($project))
    $project = null;

  require_once('config.inc.php');
  require_once('functions.php');
?>
    <script type="text/javascript">
      $(document).ready(function() { 
        // Sets up the select boxes on the top of the main page and handles their submit
        $("#sel-users, #sel-projects").select2({width: 'resolve'})
          .on('change', function(e){                   
            var value = $(this).val();
            
            if(value.length < 1)
              return;
            
            var url = $(this).attr('name');
            
            window.location = '<?php echo $GlobalRoot; ?>' + url + '/'+value;
          });     

        // Clearable
        function tog(v){return v?'addClass':'removeClass';}         
        $(document).on('input', '.clearable', function(){
          $(this)[tog(this.value)]('x');
        }).on('mousemove', '.x', function( e ){
          $(this)[tog(this.offsetWidth-18 < e.clientX-this.getBoundingClientRect().left)]('onX');   
        }).on('click', '.onX', function(){
          $(this).removeClass('x onX').val('').trigger('change');
        });
        
        // Filter panels
        // find the search box and compile a list of bocks that we can search, then save them.
        $('input.search').each(function() {
          var searchInput = $(this);
          
          // Loop thru each panel div
          searchInput.parents('div#projects-list').first().children('div.panel').each(function() {
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

      <!-- Main component for a primary marketing message or call to action -->
      <div class="jumbotron">
        <h1>Easy Timeclock</h1>
        
        <?php      
          $result = $mysqli->query("SELECT `Employee`.`Name` as Name FROM `Employee` Order By Name") or die("User query failed");
        ?>       
        
        <form class="form-horizontal" role="form">
          <div class="form-group">
            <label class="col-sm-4 control-label nopadding">View details for employee:</label>
            <div class="col-sm-8">
              <select id="sel-users" name="user" style="width:100%" data-placeholder="Select an Employee">
                <?php 
                  if($user == null)
                    echo '<option selected="selected"></option>'."\n";
                  while($row = $result->fetch_assoc()){ echo '<option'.($row['Name'] == $user ? ' selected="selected"' : '').' value="'.$row['Name'].'">'.$row['Name']."</option>\n"; }
                  $result->close();
                ?>
              </select>
            </div>
          </div>
        </form>
        
        <?php      
          $result = $mysqli->query("SELECT `Project`.`Code` as `ProjectName`, SUM(`TimeEntry`.`Hours`) as `TotalHours` FROM `Project` JOIN `TimeEntry` ON `Project`.`Id` = `TimeEntry`.`ProjectId` GROUP BY `ProjectName` HAVING `TotalHours` > 0 ORDER BY `ProjectName`");
        ?>      
        
        <form class="form-horizontal" role="form">
          <div class="form-group">
            <label class="col-sm-4 control-label nopadding">View details for project:</label>
            <div class="col-sm-8">
              <select id="sel-projects" name="project" style="width:100%" data-placeholder="Select a project" >
                <?php 
                  if($project == null)
                    echo '<option selected="selected"></option>'."\n";
                  while($row = $result->fetch_assoc()){ echo '<option'.($row['ProjectName'] == $project ? ' selected="selected"' : '').' value="'.$row['ProjectName'].'">'.$row['ProjectName']."</option>\n"; }
                  $result->close();
                ?>
              </select>
            </div>
          </div>
        </form>
      </div>

    </div> <!-- /Jumbo -->

<?php
require_once('header.php');

$qRecentProjects = "SELECT `Project`.`Code` AS `Code`, SUM(`TimeEntry`.`Hours`) AS `Hours` FROM `TimeEntry` LEFT JOIN `Project` ON `TimeEntry`.`ProjectId`=`Project`.`Id` GROUP BY `Project`.`Code` ORDER BY `TimeEntry`.`LastUpdated` DESC LIMIT 5";
?>  
    <div class="container">
      <div class="panel panel-info">
        <div class="panel-heading">
          <h3 class="panel-title">Most Recently Updated Project Strings <span>(These are links)</span></h3>
        </div>

        <table class="table table-hover recentprojects">
          <thead>
            <tr>
              <th>#</th>
              <th>Project String</th>
              <th>Total Hours</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if (!$result = $mysqli->query($qRecentProjects)){
              ?>
              <tr>
                <td span="3">Unable to run query! <?$mysqli->error?></td>
              </tr>
              <?php
            } else {
              $counter = 1;
              while($row = $result->fetch_assoc()){
                echo '<tr>';
                echo '<td><a href="project/' . $row["Code"] . '" class="disabled">' . $counter . '</a></td>';
                echo '<td>' . $row["Code"] . '</td>';
                echo '<td>' . $row["Hours"] . '</td>';
                echo '</tr>';

                $counter++;
              }
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>  
  <script type="text/javascript">
    // Adds clicks to the project string rows
    $('.recentprojects tr td').css('cursor','pointer').click(function(){       
      window.location = '<?php echo $GlobalRoot; ?>' + $(this).parent().find('a').attr('href');
    })
    .mouseenter(function(){$(this).siblings().andSelf().css('text-decoration', 'underline')})
    .mouseleave(function(){$(this).siblings().andSelf().css('text-decoration', 'none')});
  </script>
<?php require_once('footer.php'); ?>
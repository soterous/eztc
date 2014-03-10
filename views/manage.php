<?php require_once('navigation.php'); 
// Queries
$qGetProjects = "SELECT `Code`, `Name` FROM `Project` ORDER BY `Code`";
$qSetProjects = "UPDATE `Project` SET `Name` = ? WHERE `project`.`Code` = ?";

// Get instance of statement
$stmt = $mysqli->stmt_init();

if (isset($projectNames))
{
  // Iterate through all the projects
  foreach ($projectNames as $projectCode => $projectName ) {
  // Fix up formatting
  $code = str_replace('_','.',$projectCode);

   if ($stmt->prepare($qSetProjects)) {
       $stmt->bind_param("ss", $projectName, $code);
       $stmt->execute();
    } else {
      die("Unable to update project string " . $code . "!");
    }

  }
}

?>
<!-- This is a cheap way to hide the above -->
<style type="text/css">
  .jumbotron  {
    display: none !important;
  }
</style>

<div class="container">
  <div class="panel panel-info">
    <div class="panel-heading">Project Management</div>
    <div class="panel-body">
      <p>Assign human friendly names to project strings.</p>
    </div>  

    <form method="post" action="<?php echo $GlobalRoot.'manage' ?>">
      <table class="table">
        <tr>
          <th>Project String</th>
          <th>Project Name</th>    
        </tr>

        <?php 
          // Pull all the projects in
          $stmt->prepare($qGetProjects) or die("Couldn't prepare project query");
          $stmt->execute();
          $res = $stmt->get_result() or die("Could not get results for user project query");
          // Our data comes to us sorted by name so they're in the proper order already
          while($row = $res->fetch_array(MYSQLI_ASSOC)) {
            ?>
            <tr>
              <td>
                <?php echo trim($row['Code']);?>
              </td>

              <td>
                <input type="text" class="form-control" name="<?php echo trim($row['Code']);?>" value="<?php echo trim($row['Name']);?>" />
              </td>
            </tr>
            <?php
          }
        ?>
        <tr>
          <td colspan="2" style="text-align: right;">
            <button type="submit" class="btn btn-success btn-md"><span class="glyphicon glyphicon-floppy-disk"></span> Save</button>
          </td>
        </tr>
      </table>
    </form>

  </div>
  
</div>

<?php require_once('footer.php'); ?>
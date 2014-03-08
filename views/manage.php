<?php require_once('navigation.php'); 

// Queries
$qGetProjects = "SELECT `Code`, `Name` FROM `Project` ORDER BY `Code`";


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

    <table class="table">
      <tr>
        <th>Project String</th>
        <th>Project Name</th>    
      </tr>

      <?php 
        // Get instance of statement
        $stmt = $mysqli->stmt_init();
        // Pull all the projects in
        $stmt->prepare($qGetProjects) or die("Couldn't prepare project query");
        $stmt->execute();
        $res = $stmt->get_result() or die("Could not get results for user project query");
        // Our data comes to us sorted by name so they're in the proper order already
        while($row = $res->fetch_array(MYSQLI_ASSOC)) {
          ?>
          <tr>
            <td>
              <?php echo $row['Code'];?>
            </td>
          </tr>
          <tr>
            <td>
              <input type="text" class="form-control" value="<?php echo $row['Name'];?>" />
            </td>
          </tr>
          <?php
        }
      ?>
    </table>

  </div>
  
</div>

<?php require_once('footer.php'); ?>
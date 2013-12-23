<?php
require_once('config.inc.php');

$json = json_decode($jason, true);

// Queries
$qCheckEmployee = "SELECT `Id` FROM `Employee` WHERE `Name`=?";
$qInsertEmployee = "INSERT INTO `Employee` SET `Name`=?";

$qCheckProject = "SELECT `Id` FROM `Project` WHERE `Code`=?";
$qInsertProject = "INSERT INTO `Project` SET `Code`=?";

$qCheckDay = "SELECT `TimeEntry`.`Id` FROM `TimeEntry` LEFT OUTER JOIN `Project` ON `TimeEntry`.`ProjectId` = `Project`.`Id` LEFT OUTER JOIN `Employee` ON `TimeEntry`.`EmployeeId` = `Employee`.`Id` WHERE `Project`.`Code`=? AND `Employee`.`Name`=? AND `TimeEntry`.`Date`=?";
$qInsertDay = "INSERT INTO `TimeEntry` SET `ProjectId`=(SELECT `Id` FROM `Project` WHERE `Code`=?), `EmployeeId`=(SELECT `Id` FROM `Employee` WHERE `Name`=?), `Date`=?, `Hours`=?";
$qUpdateDay = "UPDATE `TimeEntry` SET `Hours`=? WHERE `ProjectId`=(SELECT `Id` FROM `Project` WHERE `Code`=?) AND `EmployeeId`=(SELECT `Id` FROM `Employee` WHERE `Name`=?) AND `Date`=?";

// Setup parameters to be bound into query
$employeeName = $json["employee"];
$projects = $json["projects"];


// Get instance of statement
$stmt = $mysqli->stmt_init();
// Check if user exists, if not then create.
if($stmt->prepare($qCheckEmployee)) {
  $stmt->bind_param("s", $employeeName);
  $stmt->execute();
  if (!$stmt->fetch()){
    if ($stmt->prepare($qInsertEmployee)) {
       $stmt->bind_param("s", $employeeName);
       $stmt->execute();
    } else {
      die("Unable to create user " . $employeeName . " and user not found in database!");
    }
  }
}

// Iterate through projects
foreach ($projects as $project=>$days) {
  // Check if project exists, if not then create.
  if($stmt->prepare($qCheckProject)) {
    $stmt->bind_param("s", $project);
    $stmt->execute();
    if (!$stmt->fetch()) {
      if ($stmt->prepare($qInsertProject)) {
         $stmt->bind_param("s", $project);
         $stmt->execute();
      } else {
        die("Unable to create project with string " . $project . " and project not found in database!");
      }
    }
  }

  // Iterate through dates associated with project
  foreach ($days as $day=>$hours) {
    // Convert day to appropriately formatted for mysql
    $safeDate = date("Y-m-d", strtotime($day));

    // Check if day exists associated with employee and project, 
    // if not add it, if so then update it's value
    if($stmt->prepare($qCheckDay)) {
      $stmt->bind_param("sss", $project, $employeeName, $safeDate);
      $stmt->execute();
      // If it's not there, insert it
      if (!$stmt->fetch()) {
        if ($stmt->prepare($qInsertDay)) {
           $stmt->bind_param("sssi", $project, $employeeName, $safeDate, $hours);
           $stmt->execute();
        } else {
          die("Unable to create time entry with for " . $project . ", " . $employeeName . ", " . $safeDate . ", " . $hours . " and date not found in database!");
        }
    } else { // Otherwise update it
      $stmt->bind_result($idee);
      while ($stmt->fetch())
      {
        printf ("%s\n", $idee);
      }
      
      if ($stmt->prepare($qUpdateDay)) {
         $stmt->bind_param("isss", $hours, $project, $employeeName, $safeDate);
         $stmt->execute();
      } else {
        die("Unable to update time entry with for " . $project . ", " . $employeeName . ", " . $safeDate . ", " . $hours . "!" . "<br />" . $stmt->error);
      }
    }
  }
}
}

// Close the istance of statement
$stmt->close();
// Close connection
$mysqli->close();

?>
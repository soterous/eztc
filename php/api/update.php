<?php
/****
  This script is responsible for receiving updates and updating the database accordingly.
***/
require_once 'config.php';
require_once 'db.php';

$db = new DB();

// Get User ID, will create user if not already in there
$userId = $db->getUserId($_POST['employee']);

// Iterate over each project and insert them into the db
foreach ($_POST['projects'] as $project => $days){

  // Skip the project if it's in blacklist specified in config.php
  if(in_array($project, $GLOBALS['cfg']['projectBlacklist']))
  {
    continue;
  }

  // Get the project id, will create the project if it doesn't exist
  $projectId = $db->getProjectId($project);

  // Iterate through dates associated with project
  foreach($days as $date => $hours){
    // Convert the date to a time we can use
    $safeDate = date('Y-m-d', strtotime($date));

    // Update the day with the hours
    $db->updateDaysHours($userId, $projectId, $safeDate, $hours);

  }
}

?>
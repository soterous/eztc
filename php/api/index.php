<?php
require 'flight/Flight.php';

// This is for the GM script to push to
Flight::route('POST /update', function(){
  $json = Flight::request()->data;
  require 'update.php';
});

// This route is for resolving the GM script.
// this route will not work in dev because of an issue with the php internal server
Flight::route('GET /gm/@scriptName', function($scriptName){
  switch($scriptName){
    case 'eztc.user.js':
      include 'userscripts/eztc.user.php';
      break;
    case 'eztc.meta.js':
      include 'userscripts/eztc.meta.php';
      break;
    default:
      return;
  }

  // Send JS headers so this thing works
  Flight::response()->header('Content-Type', 'application/javascript');
});

// Returns contents of the specified tables
Flight::route('GET /list/@table', function($table){
  require_once 'config.php';
  require_once 'db.php';

  $db = new DB();

  switch($table){
    case 'employees' :
      echo json_encode($db->getAllEmployees());
    break;

    case 'projects' :
      echo json_encode($db->getAllProjects());
    break;

    case 'recentprojects' :
      echo json_encode($db->getRecentProjects());
    break;
  }
});

Flight::route('GET /data/@table/@query', function($table, $query){
  require_once 'config.php';
  require_once 'db.php';

  $db = new DB();

  switch($table){
    case 'project' :
      // Ghetto Bandaid warning. Flight can't accept routes with periods (.) in them so we swap them to dashes (-) in js and decode them here.
      echo json_encode($db->getProjectData(str_replace('-','.',$query)));
    break;
    case 'employee' :
      echo json_encode($db->getEmployeeData(str_replace('-','.',$query)));
    break;
  }
});


// Generic catch (LEAVE LAST)
Flight::route('*', function(){
    echo 'You don\'t belong here';
});

Flight::start();
?>

<?php
require 'flight/Flight.php';

// This is for the GM script to push to
Flight::route('POST /update', function(){
  $json = Flight::request()->data;
  require 'update.php';
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


// Generic catch (LEAVE LAST)
Flight::route('*', function(){
    echo 'You don\'t belong here';
});

Flight::start();
?>

<?php
require 'flight/Flight.php';

// Set this to the global application root path
Flight::view()->set('GlobalRoot', GlobalRoot());

/**************
    ROUTES
***************/
Flight::route('/submit', function($user){
  
});

Flight::route('/user/@user', function($user){
  Flight::render('userdetails', array('user' => $user, 'page' => 'userdetails'));  
});

Flight::route('/project/@project', function($project){
  Flight::render('projectdetails', array('project' => $project, 'page' => 'projectdetails'));
});

// catchall home
Flight::route('*', function(){
  Flight::render('home', array('page' => 'home'));
});

Flight::start();


function GlobalRoot() {
 $pageURL = $_SERVER['REQUEST_SCHEME'];
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].Flight::request()->base.'/';
 }
 return $pageURL;
}
?>

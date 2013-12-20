<?php
require 'flight/Flight.php';

// Set this to the global application root path
Flight::view()->set('GlobalRoot', GlobalRoot());

Flight::route('/submit', function($user){
  
});

Flight::route('/user/@user', function($user){
  Flight::render('userdetails', array('page' => 'userdetails'));  
});

Flight::route('/project/@user', function($user){
  Flight::render('projectdetails', array('page' => 'projectdetails'));
});

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

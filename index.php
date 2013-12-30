<?php
require 'flight/Flight.php';

// Set this to the global (www) application root path.
Flight::view()->set('GlobalRoot', GlobalRoot());

/**************
    ROUTES
***************/
Flight::route('/user/@user', function($user){
  Flight::render('userdetails', array('user' => $user, 'page' => 'userdetails'));  
});

Flight::route('/project/@project', function($project){
  Flight::render('projectdetails', array('project' => $project, 'page' => 'projectdetails'));
});

// This is for the GM script to push to
Flight::route('POST /update', function(){ 
  $json = Flight::request()->data;
  require 'updateApi.php';
});

// catchall home
Flight::route('*', function(){
  Flight::render('home', array('page' => 'home'));
});

// DEV ONLY!
// Barfs out errors a little nicer
Flight::map('error', function(Exception $ex){
    // Handle error
    
    //echo '<div><pre>'.print_r($ex, true).'</pre></div>';
    echo '<div><pre>'.$ex->getMessage().'<br>'.$ex->getTraceAsString().'</pre></div>';
});

Flight::start();

/**************
   FUNCTIONS
***************/

// Figures out what the global root (www) is so we can pass it to the templates to load scripts properly.
// This is better than hard-coding.
// Eg: http://localhost/eztc
function GlobalRoot() {
 $pageURL = '';
 
 if(!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == 'off')
  $pageURL .= 'http';
 else
  $pageURL .= 'https';
 
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].Flight::request()->base.'/';
 }
 return $pageURL;
}
?>

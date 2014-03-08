<?php
require 'flight/Flight.php';

// Set this to the global (www) application root path.
Flight::view()->set('GlobalRoot', GlobalRoot());
Flight::view()->set('FullUrl', trim(GlobalRoot(), '/').Flight::request()->url);


/**************
    ROUTES
***************/
Flight::route('/user/@user', function($user){
  Flight::render('userdetails', array('user' => $user, 'page' => 'userdetails'));  
});

// Projects has a 2nd param that specifies group by user or month, if none are passed, default to user
Flight::route('/project/@project', function($project){
  Flight::redirect(GlobalRoot().'project/'.$project.'/user');
});

Flight::route('/project/@project/@groupBy', function($project, $groupBy){
  Flight::render('projectdetails', array(
    'project' => $project, 
    'groupBy' => $groupBy,
    'page' => 'projectdetails'));
});

// Management
Flight::route('GET /manage', function(){
  Flight::render('manage', array('page' => 'manage'));
});

Flight::route('POST /manage', function(){
  echo '<h1>DEBUG</h1><p>This is what I see from Flight::request()->data</p><pre>'.var_dump(Flight::request()->data).'</pre>';
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

// START DEV ONLY!
// Barfs out errors a little nicer
Flight::map('error', function(Exception $ex){
    // Handle error    

      echo '<div>';
      echo '<pre>'.$ex->getMessage().' '.$ex->getFile().' ('.$ex->getLine().")\n";
      echo $ex->getTraceAsString().'</pre>';
      echo '</div>';
});
// END DEV ONLY!

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
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].Flight::request()->base.'/';
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].Flight::request()->base.'/';
 }
 return $pageURL;
}
?>

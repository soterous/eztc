<?php
require 'flight/Flight.php';

Flight::route('/submit', function($user){
  
}

Flight::route('/user/@user', function($user){
  Flight::render('userdetails');  
});

Flight::route('/project/@user', function($user){
  Flight::render('projectdetails');
});

Flight::route('*', function(){
  Flight::render('home', array('page' => 'home'));
});


Flight::start();
?>

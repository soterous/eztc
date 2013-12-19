<?php
require 'flight/Flight.php';

Flight::route('*', function(){
  Flight::render('home', array('page' => 'home'));
});

Flight::route('/user/@user', function($user){
  Flight::render('userdetails');
});

Flight::route('/project/@user', function($user){
  Flight::render('projectdetails');
});

Flight::start();
?>

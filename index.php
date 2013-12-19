<?php
require 'flight/Flight.php';

Flight::route('/', function(){
  Flight::render('home');
});

Flight::route('/user/@user', function($user){
  
}

Flight::start();
?>

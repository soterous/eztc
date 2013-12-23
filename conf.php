<?php
// Common MySQL Setup
// Connection Variables
$host = "localhost";
$user = "root";
$password = "root";
$database = "eztc";

// Create connection
$mysqli= new mysqli($host, $user, $password, $database);

// Check connection
if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') '
            . $mysqli->connect_error);
}

?>
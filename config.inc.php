<?php
// Common MySQL Setup
// Connection Variables
$dbHost = "localhost";
$dbUser = "root";
$dbPassword = "root";
$dbDatabase = "eztc";

// Create connection
$mysqli= new mysqli($dbHost, $dbUser, $dbPassword, $dbDatabase);

// Check connection
if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') '
            . $mysqli->connect_error);
}

?>
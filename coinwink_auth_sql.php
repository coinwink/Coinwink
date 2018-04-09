<?php

//mySQL

$servername = "localhost";
$username = "USERNAME";
$password = "PASS";
$dbname = "DATABASENAME";

// Create connection

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 


?>
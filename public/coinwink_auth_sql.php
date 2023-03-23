<?php

// Env
$cw_env = "dev";

// mySQL
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "";


// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 


// MySQL lib for standalone PHP files
require_once 'lib/php/meekrodb.2.3.class.php';

DB::$host = $servername;
DB::$dbName = $dbname;
DB::$user = $username;
DB::$password = $password;

DB::$connect_options = array(MYSQLI_OPT_CONNECT_TIMEOUT => 10);
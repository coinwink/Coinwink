<?php


$cw_env = "dev";
// mysql:
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "coinwink";


// $cw_env = "live";
// // mysql:
// $servername = "localhost";
// $username = "root";
// $password = "";
// $dbname = "coinwink";


// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

// wpdb
function load_global_wpdb() {
    define( 'SHORTINIT', true );
    require( 'wp-load.php' );
}


?>
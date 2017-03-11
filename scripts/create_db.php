<?php

//Connect to Mysql
include_once "coinwink_sql.php";

// sql to create 'coinwink' table
$sql = "CREATE TABLE `coinwink` (
 `ID` TINYINT(10) NOT NULL AUTO_INCREMENT,
 `coin` VARCHAR(99) NOT NULL,
 `symbol` VARCHAR(20) NOT NULL,
 `below` VARCHAR(99) NOT NULL,
 `below_currency` VARCHAR(20) NOT NULL,
 `above` VARCHAR(99) NOT NULL,
 `above_currency` VARCHAR(20) NOT NULL,
 `below_sent` TINYINT(1) NOT NULL,
 `above_sent` TINYINT(1) NOT NULL,
 `email` VARCHAR(99) NOT NULL,
 `unique_id` VARCHAR(13) NOT NULL,
 PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1";


// sql to create 'coinwink_json' table
$sql2 = "CREATE TABLE `coinwink_json` (
 `json` mediumtext NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1";


// create default column with empty string
$sql22 = "INSERT INTO `coinwink_json`(`json`) VALUES ('')";


// sql to create 'coinwink_html' table
$sql3 = "CREATE TABLE `coinwink_html` (
 `html` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1";


// create default column with empty string
$sql33 = "INSERT INTO `coinwink_html`(`html`) VALUES ('')";


// execute queries

if ($conn->query($sql) === TRUE) {
    echo "Table 'coinwink' created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}

echo "<br />";

if ($conn->query($sql2) === TRUE) {
    echo "Table 'coinwink_json' created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}

$conn->query($sql22);

echo "<br />";

if ($conn->query($sql3) === TRUE) {
    echo "Table 'coinwink_html' created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}

$conn->query($sql33);

$conn->close();
?>
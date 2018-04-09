<?php

//Connect to Mysql
include_once "coinwink_auth_sql.php";

// sql to create 'coinwink' table
$sql = "CREATE TABLE `coinwink` (
 `ID` INT(10) NOT NULL AUTO_INCREMENT,
 `coin` VARCHAR(100) NOT NULL,
 `coin_id` VARCHAR(100) NOT NULL,
 `symbol` VARCHAR(20) NOT NULL,
 `below` VARCHAR(100) NOT NULL,
 `below_currency` VARCHAR(20) NOT NULL,
 `above` VARCHAR(100) NOT NULL,
 `above_currency` VARCHAR(20) NOT NULL,
 `below_sent` TINYINT(1) NOT NULL,
 `above_sent` TINYINT(1) NOT NULL,
 `email` VARCHAR(100) NOT NULL,
 `unique_id` VARCHAR(13) NOT NULL,
 PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1";


// sql to create 'coinwink_json' table
$sql2 = "CREATE TABLE `coinwink_json` (
 `json` mediumtext NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1";


// create default column with empty string
$sql22 = "INSERT INTO `coinwink_json`(`json`) VALUES ('')";


// sql to create 'coinwink_settings' table
$sql3 = "CREATE TABLE `coinwink_settings` (
 `user_ID` INT(10) NOT NULL AUTO_INCREMENT,
 `unique_id` VARCHAR(13) NOT NULL,
 `email` VARCHAR(100) NOT NULL,
 `phone_nr` VARCHAR(100) NOT NULL,
 `nexmo_nr_short` VARCHAR(100) NOT NULL,
 `nexmo1` VARCHAR(100) NOT NULL,
 `nexmo2` VARCHAR(100) NOT NULL,
 `plivo1` VARCHAR(100) NOT NULL,
 `plivo2` TINYINT(100) NOT NULL,
 `portfolio` TEXT NOT NULL,
 PRIMARY KEY (`user_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1";


// sql to create 'coinwink_sms' table
$sql4 = "CREATE TABLE `coinwink_sms` (
`ID` INT(10) NOT NULL AUTO_INCREMENT,
`coin` VARCHAR(100) NOT NULL,
`coin_id` VARCHAR(100) NOT NULL,
`symbol` VARCHAR(20) NOT NULL,
`below` VARCHAR(100) NOT NULL,
`below_currency` VARCHAR(20) NOT NULL,
`above` VARCHAR(100) NOT NULL,
`above_currency` VARCHAR(20) NOT NULL,
`below_sent` TINYINT(1) NOT NULL,
`above_sent` TINYINT(1) NOT NULL,
`phone` VARCHAR(100) NOT NULL,
`user_id` VARCHAR(10) NOT NULL,
PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1";


// sql to create 'coinwink_percent' table
$sql5 = "CREATE TABLE `coinwink_percent` (
`ID` INT(10) NOT NULL AUTO_INCREMENT,
`coin` VARCHAR(100) NOT NULL,
`coin_id` VARCHAR(100) NOT NULL,
`symbol` VARCHAR(64) NOT NULL,
`price_set_btc` VARCHAR(64) NOT NULL,
`price_set_usd` VARCHAR(64) NOT NULL,
`price_set_eth` VARCHAR(64) NOT NULL,
`plus_percent` VARCHAR(64) NOT NULL,
`plus_change` VARCHAR(64) NOT NULL,
`plus_compared` VARCHAR(64) NOT NULL,
`minus_percent` VARCHAR(64) NOT NULL,
`minus_change` VARCHAR(64) NOT NULL,
`minus_compared` VARCHAR(64) NOT NULL,
`plus_sent` VARCHAR(1) NOT NULL,
`minus_sent` VARCHAR(1) NOT NULL,
`email` VARCHAR(300) NOT NULL,
`unique_id` VARCHAR(64) NOT NULL,
PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1";


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
    echo "Table 'coinwink_settings' created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}

echo "<br />";

if ($conn->query($sql4) === TRUE) {
    echo "Table 'coinwink_sms' created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}

echo "<br />";

if ($conn->query($sql5) === TRUE) {
    echo "Table 'coinwink_percent' created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();

?>
<?php

//
// Get coin data from coinmarketcap.com API
//
// create curl resource 
$ch = curl_init(); 
// set url 
curl_setopt($ch, CURLOPT_URL, "https://api.coinmarketcap.com/v1/ticker/"); 
//return the transfer as a string 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
// $output contains the output string 
$output = curl_exec($ch); 
// close curl resource to free up system resources 
curl_close($ch);


//
// Convert coin data to html select options
//
$outputdecoded = json_decode($output, true);

foreach ($outputdecoded as $coinlink) {  
      $coin_list .= "<option value=\"".$coinlink['symbol']."\">".$coinlink['name']." (". $coinlink['symbol'] .")</option>"; 
}


//
// Add html select options to database
//

// Connect to Mysql
include_once "coinwink_sql.php";

// Update database
$sqlhtml = "UPDATE coinwink_html SET html = '$coin_list'";
$conn->query($sqlhtml);

?>
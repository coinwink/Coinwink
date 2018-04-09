<?php
// Increase default (256M) memory limit
ini_set('memory_limit', '512M');


// Check processing time - START
$rustart = getrusage();


// Connect to Mysql
include_once "coinwink_auth_sql.php";


// Select all data from alerts database
$sql = "SELECT * FROM coinwink_sms";
$resultdb = $conn->query($sql);


// Get data from coinmarketcap.com
// create curl resource 
$ch = curl_init(); 
// set url 
curl_setopt($ch, CURLOPT_URL, "https://api.coinmarketcap.com/v1/ticker/?convert=ETH&limit=0"); 
//return the transfer as a string 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
// $output contains the output string 
$output = curl_exec($ch); 
// close curl resource to free up system resources 
curl_close($ch);

// Remove possible single quotes from JSON string
$output = str_replace("'", "", $output);

// decode json
$outputdecoded = json_decode($output, true);


// Group alerts for each coin into new assoc array to optimize the later foreach loop
foreach($resultdb as $row)
{
    $alerts[$row["coin_id"]][] = $row;
}

// Send alerts for each coin from Coinmarketcap api
foreach ($outputdecoded as $jsoncoin) {

    if ($alerts[$jsoncoin["id"]]) {
        
        foreach ($alerts[$jsoncoin["id"]] as $alert) {
  
            // BELOW BTC

            if ($alert['below_currency'] == 'BTC') {
                if ($jsoncoin['price_btc'] < $alert['below'] && !$alert['below_sent'] && is_numeric($alert['below'])){ 
                
                echo($alert['ID'] . $alert['coin'] . "BTC below SMS sent");

                //
                // Setup SMS sending
                //
                $user_ID = $alert['user_ID'];
                $settings = "SELECT * FROM coinwink_settings WHERE user_ID = '".$user_ID."'";
                $settings_result = mysqli_fetch_assoc($conn->query($settings));

                $nexmo1 = $settings_result[nexmo1];
                $nexmo2 = $settings_result[nexmo2];
                $plivo1 = $settings_result[plivo1];
                $plivo2 = $settings_result[plivo2];
                $nexmo_nr_short = $settings_result[nexmo_nr_short];

                # SMS sender ID
                if ($nexmo_nr_short) {
                    $src = $nexmo_nr_short;
                }
                else {
                    $src = 'Coinwink';
                }
                # SMS destination number
                $dst = $alert['phone'];
                # SMS text
                $text = 'Alert: '. ucfirst($alert['coin']) .' ('. ucfirst($alert['symbol']) .') is below '. $alert['below'] .' BTC | coinwink.com';
 
                //
                // 1. Nexmo
                //
                $ch = curl_init();
                
                curl_setopt($ch, CURLOPT_URL, "https://rest.nexmo.com/sms/json");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, "api_key=$nexmo1&api_secret=$nexmo2&to=$dst&from=$src&text=$text");
                curl_setopt($ch, CURLOPT_POST, true);

                $headers = array();
                $headers[] = "Content-Type: application/x-www-form-urlencoded";
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                $result = curl_exec($ch);
                $resultarray = json_decode($result,true);
                $result_statuscode = $resultarray['messages'][0]['status'];
                echo "\nNexmo status code: " . $result_statuscode . "\n";

                curl_close ($ch);
                
                //
                // 2. Plivo
                //
                if ($result_statuscode > 0) {
                
                $src = '+11111111';

                $url = 'https://api.plivo.com/v1/Account/'.$plivo1.'/Message/';
                $data = array("src" => "$src", "dst" => "$dst", "text" => "$text");
                $data_string = json_encode($data);
                $ch=curl_init($url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
                curl_setopt($ch, CURLOPT_HEADER, true);
                curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
                curl_setopt($ch, CURLOPT_USERPWD, $plivo1 . ":" . $plivo2);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                
                echo "\nPlivo result:";
                $result = curl_exec($ch);
                
                /* $resultStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                   echo("Result: ".$resultStatus); // 401 = error, 202 = success */

                curl_close($ch);

                }

                //
                // Update DB
                //
                $ID = $alert['ID'];
                $sqlbelow = "UPDATE coinwink_sms SET below_sent=1 WHERE ID = $ID";
                $conn->query($sqlbelow);

                }
            }

            // BELOW USD

            if ($alert['below_currency'] == 'USD') {

                if ($jsoncoin['price_usd'] < $alert['below'] && !$alert['below_sent'] && is_numeric($alert['below'])) { 

                echo($alert['ID'] . $alert['coin'] . "USD BELOW SMS sent");

                //
                // Setup SMS sending
                //
                $user_ID = $alert['user_ID'];
                $settings = "SELECT * FROM coinwink_settings WHERE user_ID = '".$user_ID."'";
                $settings_result = mysqli_fetch_assoc($conn->query($settings));

                $plivo1 = $settings_result[plivo1];
                $plivo2 = $settings_result[plivo2];
                $nexmo1 = $settings_result[nexmo1];
                $nexmo2 = $settings_result[nexmo2];
                $nexmo_nr_short = $settings_result[nexmo_nr_short];

                # SMS sender ID
                if ($nexmo_nr_short) {
                    $src = $nexmo_nr_short;
                }
                else {
                    $src = 'Coinwink';
                }
                # SMS destination number
                $dst = $alert['phone'];
                # SMS text
                $text = 'Alert: '. ucfirst($alert['coin']) .' ('. ucfirst($alert['symbol']) .') is below '. $alert['below'] .' USD | coinwink.com';
 
                //
                // 1. Nexmo
                //
                $ch = curl_init();
                
                curl_setopt($ch, CURLOPT_URL, "https://rest.nexmo.com/sms/json");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, "api_key=$nexmo1&api_secret=$nexmo2&to=$dst&from=$src&text=$text");
                curl_setopt($ch, CURLOPT_POST, true);

                $headers = array();
                $headers[] = "Content-Type: application/x-www-form-urlencoded";
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                $result = curl_exec($ch);
                $resultarray = json_decode($result,true);
                $result_statuscode = $resultarray['messages'][0]['status'];
                echo "\nNexmo status code: " . $result_statuscode . "\n";

                curl_close ($ch);               

                //
                // 2. Plivo
                //
                if ($result_statuscode > 0) {
                    
                $src = '+11111111';

                $url = 'https://api.plivo.com/v1/Account/'.$plivo1.'/Message/';
                $data = array("src" => "$src", "dst" => "$dst", "text" => "$text");
                $data_string = json_encode($data);
                $ch=curl_init($url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
                curl_setopt($ch, CURLOPT_HEADER, true);
                curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
                curl_setopt($ch, CURLOPT_USERPWD, $plivo1 . ":" . $plivo2);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                
                echo "\nPlivo result:";
                $result = curl_exec($ch);
                
                /* $resultStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    echo("Result: ".$resultStatus); // 401 = error, 202 = success */

                curl_close($ch);

                }
                
                //
                // Update DB
                //
                $ID = $alert['ID'];
                $sqlbelow = "UPDATE coinwink_sms SET below_sent=1 WHERE ID = $ID";
                $conn->query($sqlbelow);
                
                }
            }

            // BELOW ETH

            if ($alert['below_currency'] == 'ETH') {
                if ($jsoncoin['price_eth'] < $alert['below'] && !$alert['below_sent'] && is_numeric($alert['below'])){ 
                
                echo($alert['ID'] . $alert['coin'] . "ETH below SMS sent");

                //
                // Setup SMS sending
                //
                $user_ID = $alert['user_ID'];
                $settings = "SELECT * FROM coinwink_settings WHERE user_ID = '".$user_ID."'";
                $settings_result = mysqli_fetch_assoc($conn->query($settings));

                $nexmo1 = $settings_result[nexmo1];
                $nexmo2 = $settings_result[nexmo2];
                $plivo1 = $settings_result[plivo1];
                $plivo2 = $settings_result[plivo2];
                $nexmo_nr_short = $settings_result[nexmo_nr_short];

                # SMS sender ID
                if ($nexmo_nr_short) {
                    $src = $nexmo_nr_short;
                }
                else {
                    $src = 'Coinwink';
                }
                # SMS destination number
                $dst = $alert['phone'];
                # SMS text
                $text = 'Alert: '. ucfirst($alert['coin']) .' ('. ucfirst($alert['symbol']) .') is below '. $alert['below'] .' ETH | coinwink.com';
 
                //
                // 1. Nexmo
                //
                $ch = curl_init();
                
                curl_setopt($ch, CURLOPT_URL, "https://rest.nexmo.com/sms/json");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, "api_key=$nexmo1&api_secret=$nexmo2&to=$dst&from=$src&text=$text");
                curl_setopt($ch, CURLOPT_POST, true);

                $headers = array();
                $headers[] = "Content-Type: application/x-www-form-urlencoded";
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                $result = curl_exec($ch);
                $resultarray = json_decode($result,true);
                $result_statuscode = $resultarray['messages'][0]['status'];
                echo "\nNexmo status code: " . $result_statuscode . "\n";

                curl_close ($ch);
                
                //
                // 2. Plivo
                //
                if ($result_statuscode > 0) {
                
                $src = '+11111111';

                $url = 'https://api.plivo.com/v1/Account/'.$plivo1.'/Message/';
                $data = array("src" => "$src", "dst" => "$dst", "text" => "$text");
                $data_string = json_encode($data);
                $ch=curl_init($url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
                curl_setopt($ch, CURLOPT_HEADER, true);
                curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
                curl_setopt($ch, CURLOPT_USERPWD, $plivo1 . ":" . $plivo2);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                
                echo "\nPlivo result:";
                $result = curl_exec($ch);
                
                /* $resultStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                   echo("Result: ".$resultStatus); // 401 = error, 202 = success */

                curl_close($ch);

                }

                //
                // Update DB
                //
                $ID = $alert['ID'];
                $sqlbelow = "UPDATE coinwink_sms SET below_sent=1 WHERE ID = $ID";
                $conn->query($sqlbelow);

                }
            }

            // ABOVE USD

            if ($alert['above_currency'] == 'USD') {

                if ($jsoncoin['price_usd'] > $alert['above'] && !$alert['above_sent'] && is_numeric($alert['above']) ) { 

                echo($alert['ID'] . $alert['coin'] . "USD ABOVE SMS sent");  
               
                //
                // Setup SMS sending
                //
                $user_ID = $alert['user_ID'];
                $settings = "SELECT * FROM coinwink_settings WHERE user_ID = '".$user_ID."'";
                $settings_result = mysqli_fetch_assoc($conn->query($settings));

                $plivo1 = $settings_result[plivo1];
                $plivo2 = $settings_result[plivo2];
                $nexmo1 = $settings_result[nexmo1];
                $nexmo2 = $settings_result[nexmo2];
                $nexmo_nr_short = $settings_result[nexmo_nr_short];

                # SMS sender ID
                if ($nexmo_nr_short) {
                    $src = $nexmo_nr_short;
                }
                else {
                    $src = 'Coinwink';
                }
                # SMS destination number
                $dst = $alert['phone'];
                # SMS text
                $text = 'Alert: '. ucfirst($alert['coin']) .' ('. ucfirst($alert['symbol']) .') is above '. $alert['above'] .' USD | coinwink.com';

                //
                // 1. Nexmo
                //
                $ch = curl_init();
                
                curl_setopt($ch, CURLOPT_URL, "https://rest.nexmo.com/sms/json");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, "api_key=$nexmo1&api_secret=$nexmo2&to=$dst&from=$src&text=$text");
                curl_setopt($ch, CURLOPT_POST, true);

                $headers = array();
                $headers[] = "Content-Type: application/x-www-form-urlencoded";
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                $result = curl_exec($ch);
                $resultarray = json_decode($result,true);
                $result_statuscode = $resultarray['messages'][0]['status'];
                echo "\nNexmo status code: " . $result_statuscode . "\n";

                curl_close ($ch);

                //
                // 2. Plivo
                //
                if ($result_statuscode > 0) {
                
                $src = '+11111111';

                $url = 'https://api.plivo.com/v1/Account/'.$plivo1.'/Message/';
                $data = array("src" => "$src", "dst" => "$dst", "text" => "$text");
                $data_string = json_encode($data);
                $ch=curl_init($url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
                curl_setopt($ch, CURLOPT_HEADER, true);
                curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
                curl_setopt($ch, CURLOPT_USERPWD, $plivo1 . ":" . $plivo2);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                
                echo "\nPlivo result:";
                $result = curl_exec($ch);
                
                /* $resultStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                   echo("Result: ".$resultStatus); // 401 = error, 202 = success */

                curl_close($ch);

                }

                //
                // Update DB
                //
                $ID = $alert['ID'];
                $sqlabove = "UPDATE coinwink_sms SET above_sent=1 WHERE ID = $ID";
                $conn->query($sqlabove);

                }
            }                

            // ABOVE BTC

            if ($alert['above_currency'] == 'BTC') {

                if ($jsoncoin['price_btc'] > $alert['above'] && !$alert['above_sent'] && is_numeric($alert['above'])) { 
                
                echo($alert['ID'] . $alert['coin'] . "BTC ABOVE SMS sent");  
                
                //
                // Setup SMS sending
                //
                $user_ID = $alert['user_ID'];
                $settings = "SELECT * FROM coinwink_settings WHERE user_ID = '".$user_ID."'";
                $settings_result = mysqli_fetch_assoc($conn->query($settings));

                $plivo1 = $settings_result[plivo1];
                $plivo2 = $settings_result[plivo2];
                $nexmo1 = $settings_result[nexmo1];
                $nexmo2 = $settings_result[nexmo2];
                $nexmo_nr_short = $settings_result[nexmo_nr_short];

                # SMS sender ID.
                if ($nexmo_nr_short) {
                    $src = $nexmo_nr_short;
                }
                else {
                    $src = 'Coinwink';
                }
                # SMS destination number
                $dst = $alert['phone'];
                # SMS text
                $text = 'Alert: '. ucfirst($alert['coin']) .' ('. ucfirst($alert['symbol']) .') is above '. $alert['above'] .' BTC | coinwink.com';
                
                
                //
                // 1. Nexmo
                //
                $ch = curl_init();
                
                curl_setopt($ch, CURLOPT_URL, "https://rest.nexmo.com/sms/json");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, "api_key=$nexmo1&api_secret=$nexmo2&to=$dst&from=$src&text=$text");
                curl_setopt($ch, CURLOPT_POST, true);

                $headers = array();
                $headers[] = "Content-Type: application/x-www-form-urlencoded";
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                $result = curl_exec($ch);
                $resultarray = json_decode($result,true);
                $result_statuscode = $resultarray['messages'][0]['status'];
                echo "\nNexmo status code: " . $result_statuscode . "\n";

                curl_close ($ch);

                //
                // 2. Plivo
                //
                if ($result_statuscode > 0) {
                
                $src = '+11111111';

                $url = 'https://api.plivo.com/v1/Account/'.$plivo1.'/Message/';
                $data = array("src" => "$src", "dst" => "$dst", "text" => "$text");
                $data_string = json_encode($data);
                $ch=curl_init($url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
                curl_setopt($ch, CURLOPT_HEADER, true);
                curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
                curl_setopt($ch, CURLOPT_USERPWD, $plivo1 . ":" . $plivo2);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                
                echo "\nPlivo result:";
                $result = curl_exec($ch);
                
                /* $resultStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                   echo("Result: ".$resultStatus); // 401 = error, 202 = success */

                curl_close($ch);

                }
                
                //
                // Update DB
                //
                $ID = $alert['ID'];
                $sqlabove = "UPDATE coinwink_sms SET above_sent=1 WHERE ID = $ID";
                $conn->query($sqlabove);
            
                }

            }   
            
            // ABOVE ETH

            if ($alert['above_currency'] == 'ETH') {

                if ($jsoncoin['price_eth'] > $alert['above'] && !$alert['above_sent'] && is_numeric($alert['above'])) { 
                
                echo($alert['ID'] . $alert['coin'] . "ETH ABOVE SMS sent");  
                
                //
                // Setup SMS sending
                //
                $user_ID = $alert['user_ID'];
                $settings = "SELECT * FROM coinwink_settings WHERE user_ID = '".$user_ID."'";
                $settings_result = mysqli_fetch_assoc($conn->query($settings));

                $plivo1 = $settings_result[plivo1];
                $plivo2 = $settings_result[plivo2];
                $nexmo1 = $settings_result[nexmo1];
                $nexmo2 = $settings_result[nexmo2];
                $nexmo_nr_short = $settings_result[nexmo_nr_short];

                # SMS sender ID.
                if ($nexmo_nr_short) {
                    $src = $nexmo_nr_short;
                }
                else {
                    $src = 'Coinwink';
                }
                # SMS destination number
                $dst = $alert['phone'];
                # SMS text
                $text = 'Alert: '. ucfirst($alert['coin']) .' ('. ucfirst($alert['symbol']) .') is above '. $alert['above'] .' ETH | coinwink.com';
                
                
                //
                // 1. Nexmo
                //
                $ch = curl_init();
                
                curl_setopt($ch, CURLOPT_URL, "https://rest.nexmo.com/sms/json");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, "api_key=$nexmo1&api_secret=$nexmo2&to=$dst&from=$src&text=$text");
                curl_setopt($ch, CURLOPT_POST, true);

                $headers = array();
                $headers[] = "Content-Type: application/x-www-form-urlencoded";
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                $result = curl_exec($ch);
                $resultarray = json_decode($result,true);
                $result_statuscode = $resultarray['messages'][0]['status'];
                echo "\nNexmo status code: " . $result_statuscode . "\n";

                curl_close ($ch);

                //
                // 2. Plivo
                //
                if ($result_statuscode > 0) {
                
                $src = '+11111111';

                $url = 'https://api.plivo.com/v1/Account/'.$plivo1.'/Message/';
                $data = array("src" => "$src", "dst" => "$dst", "text" => "$text");
                $data_string = json_encode($data);
                $ch=curl_init($url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
                curl_setopt($ch, CURLOPT_HEADER, true);
                curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
                curl_setopt($ch, CURLOPT_USERPWD, $plivo1 . ":" . $plivo2);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                
                echo "\nPlivo result:";
                $result = curl_exec($ch);
                
                /* $resultStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                   echo("Result: ".$resultStatus); // 401 = error, 202 = success */

                curl_close($ch);

                }
                
                //
                // Update DB
                //
                $ID = $alert['ID'];
                $sqlabove = "UPDATE coinwink_sms SET above_sent=1 WHERE ID = $ID";
                $conn->query($sqlabove);
            
                }

            } 

        }

    }

}

//
//
// PROCESS EUR
//
//

// Get data from coinmarketcap.com
// create curl resource 
$ch = curl_init(); 
// set url 
curl_setopt($ch, CURLOPT_URL, "https://api.coinmarketcap.com/v1/ticker/?convert=EUR&limit=0"); 
//return the transfer as a string 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
// $output contains the output string 
$output = curl_exec($ch); 
// close curl resource to free up system resources 
curl_close($ch);

// Remove possible single quotes from JSON string
$output = str_replace("'", "", $output);

// decode json
$outputdecoded = json_decode($output, true);

// Send alerts for each coin from Coinmarketcap api
foreach ($outputdecoded as $jsoncoin) {

    if ($alerts[$jsoncoin["id"]]) {
        
        foreach ($alerts[$jsoncoin["id"]] as $alert) {
  
            // BELOW EUR

            if ($alert['below_currency'] == 'EUR') {
                if ($jsoncoin['price_eur'] < $alert['below'] && !$alert['below_sent'] && is_numeric($alert['below'])){ 
                
                echo($alert['ID'] . $alert['coin'] . "EUR below SMS sent");

                //
                // Setup SMS sending
                //
                $user_ID = $alert['user_ID'];
                $settings = "SELECT * FROM coinwink_settings WHERE user_ID = '".$user_ID."'";
                $settings_result = mysqli_fetch_assoc($conn->query($settings));

                $nexmo1 = $settings_result[nexmo1];
                $nexmo2 = $settings_result[nexmo2];
                $plivo1 = $settings_result[plivo1];
                $plivo2 = $settings_result[plivo2];
                $nexmo_nr_short = $settings_result[nexmo_nr_short];

                # SMS sender ID
                if ($nexmo_nr_short) {
                    $src = $nexmo_nr_short;
                }
                else {
                    $src = 'Coinwink';
                }
                # SMS destination number
                $dst = $alert['phone'];
                # SMS text
                $text = 'Alert: '. ucfirst($alert['coin']) .' ('. ucfirst($alert['symbol']) .') is below '. $alert['below'] .' EUR | coinwink.com';
 
                //
                // 1. Nexmo
                //
                $ch = curl_init();
                
                curl_setopt($ch, CURLOPT_URL, "https://rest.nexmo.com/sms/json");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, "api_key=$nexmo1&api_secret=$nexmo2&to=$dst&from=$src&text=$text");
                curl_setopt($ch, CURLOPT_POST, true);

                $headers = array();
                $headers[] = "Content-Type: application/x-www-form-urlencoded";
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                $result = curl_exec($ch);
                $resultarray = json_decode($result,true);
                $result_statuscode = $resultarray['messages'][0]['status'];
                echo "\nNexmo status code: " . $result_statuscode . "\n";

                curl_close ($ch);
                
                //
                // 2. Plivo
                //
                if ($result_statuscode > 0) {
                
                $src = '+11111111';

                $url = 'https://api.plivo.com/v1/Account/'.$plivo1.'/Message/';
                $data = array("src" => "$src", "dst" => "$dst", "text" => "$text");
                $data_string = json_encode($data);
                $ch=curl_init($url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
                curl_setopt($ch, CURLOPT_HEADER, true);
                curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
                curl_setopt($ch, CURLOPT_USERPWD, $plivo1 . ":" . $plivo2);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                
                echo "\nPlivo result:";
                $result = curl_exec($ch);
                
                /* $resultStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                   echo("Result: ".$resultStatus); // 401 = error, 202 = success */

                curl_close($ch);

                }

                //
                // Update DB
                //
                $ID = $alert['ID'];
                $sqlbelow = "UPDATE coinwink_sms SET below_sent=1 WHERE ID = $ID";
                $conn->query($sqlbelow);

                }
            }

            // ABOVE EUR

            if ($alert['above_currency'] == 'EUR') {

                if ($jsoncoin['price_eur'] > $alert['above'] && !$alert['above_sent'] && is_numeric($alert['above'])) { 
                
                echo($alert['ID'] . $alert['coin'] . "EUR ABOVE SMS sent");  
                
                //
                // Setup SMS sending
                //
                $user_ID = $alert['user_ID'];
                $settings = "SELECT * FROM coinwink_settings WHERE user_ID = '".$user_ID."'";
                $settings_result = mysqli_fetch_assoc($conn->query($settings));

                $plivo1 = $settings_result[plivo1];
                $plivo2 = $settings_result[plivo2];
                $nexmo1 = $settings_result[nexmo1];
                $nexmo2 = $settings_result[nexmo2];
                $nexmo_nr_short = $settings_result[nexmo_nr_short];

                # SMS sender ID.
                if ($nexmo_nr_short) {
                    $src = $nexmo_nr_short;
                }
                else {
                    $src = 'Coinwink';
                }
                # SMS destination number
                $dst = $alert['phone'];
                # SMS text
                $text = 'Alert: '. ucfirst($alert['coin']) .' ('. ucfirst($alert['symbol']) .') is above '. $alert['above'] .' EUR | coinwink.com';
                
                
                //
                // 1. Nexmo
                //
                $ch = curl_init();
                
                curl_setopt($ch, CURLOPT_URL, "https://rest.nexmo.com/sms/json");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, "api_key=$nexmo1&api_secret=$nexmo2&to=$dst&from=$src&text=$text");
                curl_setopt($ch, CURLOPT_POST, true);

                $headers = array();
                $headers[] = "Content-Type: application/x-www-form-urlencoded";
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                $result = curl_exec($ch);
                $resultarray = json_decode($result,true);
                $result_statuscode = $resultarray['messages'][0]['status'];
                echo "\nNexmo status code: " . $result_statuscode . "\n";

                curl_close ($ch);

                //
                // 2. Plivo
                //
                if ($result_statuscode > 0) {
                
                $src = '+11111111';

                $url = 'https://api.plivo.com/v1/Account/'.$plivo1.'/Message/';
                $data = array("src" => "$src", "dst" => "$dst", "text" => "$text");
                $data_string = json_encode($data);
                $ch=curl_init($url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
                curl_setopt($ch, CURLOPT_HEADER, true);
                curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
                curl_setopt($ch, CURLOPT_USERPWD, $plivo1 . ":" . $plivo2);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                
                echo "\nPlivo result:";
                $result = curl_exec($ch);
                
                /* $resultStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                   echo("Result: ".$resultStatus); // 401 = error, 202 = success */

                curl_close($ch);

                }
                
                //
                // Update DB
                //
                $ID = $alert['ID'];
                $sqlabove = "UPDATE coinwink_sms SET above_sent=1 WHERE ID = $ID";
                $conn->query($sqlabove);
            
                }

            }
        
        }
    
    }

}


//
//
// PROCESS AUD
//
//

// Get data from coinmarketcap.com
// create curl resource 
$ch = curl_init(); 
// set url 
curl_setopt($ch, CURLOPT_URL, "https://api.coinmarketcap.com/v1/ticker/?convert=AUD&limit=0"); 
//return the transfer as a string 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
// $output contains the output string 
$output = curl_exec($ch); 
// close curl resource to free up system resources 
curl_close($ch);

// Remove possible single quotes from JSON string
$output = str_replace("'", "", $output);

// decode json
$outputdecoded = json_decode($output, true);

// Send alerts for each coin from Coinmarketcap api
foreach ($outputdecoded as $jsoncoin) {

    if ($alerts[$jsoncoin["id"]]) {
        
        foreach ($alerts[$jsoncoin["id"]] as $alert) {
  
            // BELOW AUD

            if ($alert['below_currency'] == 'AUD') {
                if ($jsoncoin['price_aud'] < $alert['below'] && !$alert['below_sent'] && is_numeric($alert['below'])){ 
                
                echo($alert['ID'] . $alert['coin'] . "AUD below SMS sent");

                //
                // Setup SMS sending
                //
                $user_ID = $alert['user_ID'];
                $settings = "SELECT * FROM coinwink_settings WHERE user_ID = '".$user_ID."'";
                $settings_result = mysqli_fetch_assoc($conn->query($settings));

                $nexmo1 = $settings_result[nexmo1];
                $nexmo2 = $settings_result[nexmo2];
                $plivo1 = $settings_result[plivo1];
                $plivo2 = $settings_result[plivo2];
                $nexmo_nr_short = $settings_result[nexmo_nr_short];

                # SMS sender ID
                if ($nexmo_nr_short) {
                    $src = $nexmo_nr_short;
                }
                else {
                    $src = 'Coinwink';
                }
                # SMS destination number
                $dst = $alert['phone'];
                # SMS text
                $text = 'Alert: '. ucfirst($alert['coin']) .' ('. ucfirst($alert['symbol']) .') is below '. $alert['below'] .' AUD | coinwink.com';
 
                //
                // 1. Nexmo
                //
                $ch = curl_init();
                
                curl_setopt($ch, CURLOPT_URL, "https://rest.nexmo.com/sms/json");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, "api_key=$nexmo1&api_secret=$nexmo2&to=$dst&from=$src&text=$text");
                curl_setopt($ch, CURLOPT_POST, true);

                $headers = array();
                $headers[] = "Content-Type: application/x-www-form-urlencoded";
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                $result = curl_exec($ch);
                $resultarray = json_decode($result,true);
                $result_statuscode = $resultarray['messages'][0]['status'];
                echo "\nNexmo status code: " . $result_statuscode . "\n";

                curl_close ($ch);
                
                //
                // 2. Plivo
                //
                if ($result_statuscode > 0) {
                
                $src = '+11111111';

                $url = 'https://api.plivo.com/v1/Account/'.$plivo1.'/Message/';
                $data = array("src" => "$src", "dst" => "$dst", "text" => "$text");
                $data_string = json_encode($data);
                $ch=curl_init($url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
                curl_setopt($ch, CURLOPT_HEADER, true);
                curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
                curl_setopt($ch, CURLOPT_USERPWD, $plivo1 . ":" . $plivo2);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                
                echo "\nPlivo result:";
                $result = curl_exec($ch);
                
                /* $resultStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                   echo("Result: ".$resultStatus); // 401 = error, 202 = success */

                curl_close($ch);

                }

                //
                // Update DB
                //
                $ID = $alert['ID'];
                $sqlbelow = "UPDATE coinwink_sms SET below_sent=1 WHERE ID = $ID";
                $conn->query($sqlbelow);

                }
            }

            // ABOVE AUD

            if ($alert['above_currency'] == 'AUD') {

                if ($jsoncoin['price_aud'] > $alert['above'] && !$alert['above_sent'] && is_numeric($alert['above'])) { 
                
                echo($alert['ID'] . $alert['coin'] . "AUD ABOVE SMS sent");  
                
                //
                // Setup SMS sending
                //
                $user_ID = $alert['user_ID'];
                $settings = "SELECT * FROM coinwink_settings WHERE user_ID = '".$user_ID."'";
                $settings_result = mysqli_fetch_assoc($conn->query($settings));

                $plivo1 = $settings_result[plivo1];
                $plivo2 = $settings_result[plivo2];
                $nexmo1 = $settings_result[nexmo1];
                $nexmo2 = $settings_result[nexmo2];
                $nexmo_nr_short = $settings_result[nexmo_nr_short];

                # SMS sender ID.
                if ($nexmo_nr_short) {
                    $src = $nexmo_nr_short;
                }
                else {
                    $src = 'Coinwink';
                }
                # SMS destination number
                $dst = $alert['phone'];
                # SMS text
                $text = 'Alert: '. ucfirst($alert['coin']) .' ('. ucfirst($alert['symbol']) .') is above '. $alert['above'] .' AUD | coinwink.com';
                
                
                //
                // 1. Nexmo
                //
                $ch = curl_init();
                
                curl_setopt($ch, CURLOPT_URL, "https://rest.nexmo.com/sms/json");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, "api_key=$nexmo1&api_secret=$nexmo2&to=$dst&from=$src&text=$text");
                curl_setopt($ch, CURLOPT_POST, true);

                $headers = array();
                $headers[] = "Content-Type: application/x-www-form-urlencoded";
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                $result = curl_exec($ch);
                $resultarray = json_decode($result,true);
                $result_statuscode = $resultarray['messages'][0]['status'];
                echo "\nNexmo status code: " . $result_statuscode . "\n";

                curl_close ($ch);

                //
                // 2. Plivo
                //
                if ($result_statuscode > 0) {
                
                $src = '+11111111';

                $url = 'https://api.plivo.com/v1/Account/'.$plivo1.'/Message/';
                $data = array("src" => "$src", "dst" => "$dst", "text" => "$text");
                $data_string = json_encode($data);
                $ch=curl_init($url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
                curl_setopt($ch, CURLOPT_HEADER, true);
                curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
                curl_setopt($ch, CURLOPT_USERPWD, $plivo1 . ":" . $plivo2);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                
                echo "\nPlivo result:";
                $result = curl_exec($ch);
                
                /* $resultStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                   echo("Result: ".$resultStatus); // 401 = error, 202 = success */

                curl_close($ch);

                }
                
                //
                // Update DB
                //
                $ID = $alert['ID'];
                $sqlabove = "UPDATE coinwink_sms SET above_sent=1 WHERE ID = $ID";
                $conn->query($sqlabove);
            
                }

            }
        
        }
    
    }

}


//
//
// PROCESS CAD
//
//

// Get data from coinmarketcap.com
// create curl resource 
$ch = curl_init(); 
// set url 
curl_setopt($ch, CURLOPT_URL, "https://api.coinmarketcap.com/v1/ticker/?convert=CAD&limit=0"); 
//return the transfer as a string 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
// $output contains the output string 
$output = curl_exec($ch); 
// close curl resource to free up system resources 
curl_close($ch);

// Remove possible single quotes from JSON string
$output = str_replace("'", "", $output);

// decode json
$outputdecoded = json_decode($output, true);

// Send alerts for each coin from Coinmarketcap api
foreach ($outputdecoded as $jsoncoin) {

    if ($alerts[$jsoncoin["id"]]) {
        
        foreach ($alerts[$jsoncoin["id"]] as $alert) {
  
            // BELOW CAD

            if ($alert['below_currency'] == 'CAD') {
                if ($jsoncoin['price_cad'] < $alert['below'] && !$alert['below_sent'] && is_numeric($alert['below'])){ 
                
                echo($alert['ID'] . $alert['coin'] . "CAD below SMS sent");

                //
                // Setup SMS sending
                //
                $user_ID = $alert['user_ID'];
                $settings = "SELECT * FROM coinwink_settings WHERE user_ID = '".$user_ID."'";
                $settings_result = mysqli_fetch_assoc($conn->query($settings));

                $nexmo1 = $settings_result[nexmo1];
                $nexmo2 = $settings_result[nexmo2];
                $plivo1 = $settings_result[plivo1];
                $plivo2 = $settings_result[plivo2];
                $nexmo_nr_short = $settings_result[nexmo_nr_short];

                # SMS sender ID
                if ($nexmo_nr_short) {
                    $src = $nexmo_nr_short;
                }
                else {
                    $src = 'Coinwink';
                }
                # SMS destination number
                $dst = $alert['phone'];
                # SMS text
                $text = 'Alert: '. ucfirst($alert['coin']) .' ('. ucfirst($alert['symbol']) .') is below '. $alert['below'] .' CAD | coinwink.com';
 
                //
                // 1. Nexmo
                //
                $ch = curl_init();
                
                curl_setopt($ch, CURLOPT_URL, "https://rest.nexmo.com/sms/json");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, "api_key=$nexmo1&api_secret=$nexmo2&to=$dst&from=$src&text=$text");
                curl_setopt($ch, CURLOPT_POST, true);

                $headers = array();
                $headers[] = "Content-Type: application/x-www-form-urlencoded";
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                $result = curl_exec($ch);
                $resultarray = json_decode($result,true);
                $result_statuscode = $resultarray['messages'][0]['status'];
                echo "\nNexmo status code: " . $result_statuscode . "\n";

                curl_close ($ch);
                
                //
                // 2. Plivo
                //
                if ($result_statuscode > 0) {
                
                $src = '+11111111';

                $url = 'https://api.plivo.com/v1/Account/'.$plivo1.'/Message/';
                $data = array("src" => "$src", "dst" => "$dst", "text" => "$text");
                $data_string = json_encode($data);
                $ch=curl_init($url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
                curl_setopt($ch, CURLOPT_HEADER, true);
                curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
                curl_setopt($ch, CURLOPT_USERPWD, $plivo1 . ":" . $plivo2);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                
                echo "\nPlivo result:";
                $result = curl_exec($ch);
                
                /* $resultStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                   echo("Result: ".$resultStatus); // 401 = error, 202 = success */

                curl_close($ch);

                }

                //
                // Update DB
                //
                $ID = $alert['ID'];
                $sqlbelow = "UPDATE coinwink_sms SET below_sent=1 WHERE ID = $ID";
                $conn->query($sqlbelow);

                }
            }

            // ABOVE CAD

            if ($alert['above_currency'] == 'CAD') {

                if ($jsoncoin['price_cad'] > $alert['above'] && !$alert['above_sent'] && is_numeric($alert['above'])) { 
                
                echo($alert['ID'] . $alert['coin'] . "CAD ABOVE SMS sent");  
                
                //
                // Setup SMS sending
                //
                $user_ID = $alert['user_ID'];
                $settings = "SELECT * FROM coinwink_settings WHERE user_ID = '".$user_ID."'";
                $settings_result = mysqli_fetch_assoc($conn->query($settings));

                $plivo1 = $settings_result[plivo1];
                $plivo2 = $settings_result[plivo2];
                $nexmo1 = $settings_result[nexmo1];
                $nexmo2 = $settings_result[nexmo2];
                $nexmo_nr_short = $settings_result[nexmo_nr_short];

                # SMS sender ID.
                if ($nexmo_nr_short) {
                    $src = $nexmo_nr_short;
                }
                else {
                    $src = 'Coinwink';
                }
                # SMS destination number
                $dst = $alert['phone'];
                # SMS text
                $text = 'Alert: '. ucfirst($alert['coin']) .' ('. ucfirst($alert['symbol']) .') is above '. $alert['above'] .' CAD | coinwink.com';
                
                
                //
                // 1. Nexmo
                //
                $ch = curl_init();
                
                curl_setopt($ch, CURLOPT_URL, "https://rest.nexmo.com/sms/json");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, "api_key=$nexmo1&api_secret=$nexmo2&to=$dst&from=$src&text=$text");
                curl_setopt($ch, CURLOPT_POST, true);

                $headers = array();
                $headers[] = "Content-Type: application/x-www-form-urlencoded";
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                $result = curl_exec($ch);
                $resultarray = json_decode($result,true);
                $result_statuscode = $resultarray['messages'][0]['status'];
                echo "\nNexmo status code: " . $result_statuscode . "\n";

                curl_close ($ch);

                //
                // 2. Plivo
                //
                if ($result_statuscode > 0) {
                
                $src = '+11111111';

                $url = 'https://api.plivo.com/v1/Account/'.$plivo1.'/Message/';
                $data = array("src" => "$src", "dst" => "$dst", "text" => "$text");
                $data_string = json_encode($data);
                $ch=curl_init($url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
                curl_setopt($ch, CURLOPT_HEADER, true);
                curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
                curl_setopt($ch, CURLOPT_USERPWD, $plivo1 . ":" . $plivo2);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                
                echo "\nPlivo result:";
                $result = curl_exec($ch);
                
                /* $resultStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                   echo("Result: ".$resultStatus); // 401 = error, 202 = success */

                curl_close($ch);

                }
                
                //
                // Update DB
                //
                $ID = $alert['ID'];
                $sqlabove = "UPDATE coinwink_sms SET above_sent=1 WHERE ID = $ID";
                $conn->query($sqlabove);
            
                }

            }
        
        }
    
    }

}

//
//
// PROCESS KRW
//
//

// Get data from coinmarketcap.com
// create curl resource 
$ch = curl_init(); 
// set url 
curl_setopt($ch, CURLOPT_URL, "https://api.coinmarketcap.com/v1/ticker/?convert=KRW&limit=0"); 
//return the transfer as a string 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
// $output contains the output string 
$output = curl_exec($ch); 
// close curl resource to free up system resources 
curl_close($ch);

// Remove possible single quotes from JSON string
$output = str_replace("'", "", $output);

// decode json
$outputdecoded = json_decode($output, true);

// Send alerts for each coin from Coinmarketcap api
foreach ($outputdecoded as $jsoncoin) {

    if ($alerts[$jsoncoin["id"]]) {
        
        foreach ($alerts[$jsoncoin["id"]] as $alert) {
  
            // BELOW KRW

            if ($alert['below_currency'] == 'KRW') {
                if ($jsoncoin['price_krw'] < $alert['below'] && !$alert['below_sent'] && is_numeric($alert['below'])){ 
                
                echo($alert['ID'] . $alert['coin'] . "KRW below SMS sent");

                //
                // Setup SMS sending
                //
                $user_ID = $alert['user_ID'];
                $settings = "SELECT * FROM coinwink_settings WHERE user_ID = '".$user_ID."'";
                $settings_result = mysqli_fetch_assoc($conn->query($settings));

                $nexmo1 = $settings_result[nexmo1];
                $nexmo2 = $settings_result[nexmo2];
                $plivo1 = $settings_result[plivo1];
                $plivo2 = $settings_result[plivo2];
                $nexmo_nr_short = $settings_result[nexmo_nr_short];

                # SMS sender ID
                if ($nexmo_nr_short) {
                    $src = $nexmo_nr_short;
                }
                else {
                    $src = 'Coinwink';
                }
                # SMS destination number
                $dst = $alert['phone'];
                # SMS text
                $text = 'Alert: '. ucfirst($alert['coin']) .' ('. ucfirst($alert['symbol']) .') is below '. $alert['below'] .' KRW | coinwink.com';
 
                //
                // 1. Nexmo
                //
                $ch = curl_init();
                
                curl_setopt($ch, CURLOPT_URL, "https://rest.nexmo.com/sms/json");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, "api_key=$nexmo1&api_secret=$nexmo2&to=$dst&from=$src&text=$text");
                curl_setopt($ch, CURLOPT_POST, true);

                $headers = array();
                $headers[] = "Content-Type: application/x-www-form-urlencoded";
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                $result = curl_exec($ch);
                $resultarray = json_decode($result,true);
                $result_statuscode = $resultarray['messages'][0]['status'];
                echo "\nNexmo status code: " . $result_statuscode . "\n";

                curl_close ($ch);
                
                //
                // 2. Plivo
                //
                if ($result_statuscode > 0) {
                
                $src = '+11111111';

                $url = 'https://api.plivo.com/v1/Account/'.$plivo1.'/Message/';
                $data = array("src" => "$src", "dst" => "$dst", "text" => "$text");
                $data_string = json_encode($data);
                $ch=curl_init($url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
                curl_setopt($ch, CURLOPT_HEADER, true);
                curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
                curl_setopt($ch, CURLOPT_USERPWD, $plivo1 . ":" . $plivo2);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                
                echo "\nPlivo result:";
                $result = curl_exec($ch);
                
                /* $resultStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                   echo("Result: ".$resultStatus); // 401 = error, 202 = success */

                curl_close($ch);

                }

                //
                // Update DB
                //
                $ID = $alert['ID'];
                $sqlbelow = "UPDATE coinwink_sms SET below_sent=1 WHERE ID = $ID";
                $conn->query($sqlbelow);

                }
            }

            // ABOVE KRW

            if ($alert['above_currency'] == 'KRW') {

                if ($jsoncoin['price_krw'] > $alert['above'] && !$alert['above_sent'] && is_numeric($alert['above'])) { 
                
                echo($alert['ID'] . $alert['coin'] . "KRW ABOVE SMS sent");  
                
                //
                // Setup SMS sending
                //
                $user_ID = $alert['user_ID'];
                $settings = "SELECT * FROM coinwink_settings WHERE user_ID = '".$user_ID."'";
                $settings_result = mysqli_fetch_assoc($conn->query($settings));

                $plivo1 = $settings_result[plivo1];
                $plivo2 = $settings_result[plivo2];
                $nexmo1 = $settings_result[nexmo1];
                $nexmo2 = $settings_result[nexmo2];
                $nexmo_nr_short = $settings_result[nexmo_nr_short];

                # SMS sender ID.
                if ($nexmo_nr_short) {
                    $src = $nexmo_nr_short;
                }
                else {
                    $src = 'Coinwink';
                }
                # SMS destination number
                $dst = $alert['phone'];
                # SMS text
                $text = 'Alert: '. ucfirst($alert['coin']) .' ('. ucfirst($alert['symbol']) .') is above '. $alert['above'] .' KRW | coinwink.com';
                
                
                //
                // 1. Nexmo
                //
                $ch = curl_init();
                
                curl_setopt($ch, CURLOPT_URL, "https://rest.nexmo.com/sms/json");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, "api_key=$nexmo1&api_secret=$nexmo2&to=$dst&from=$src&text=$text");
                curl_setopt($ch, CURLOPT_POST, true);

                $headers = array();
                $headers[] = "Content-Type: application/x-www-form-urlencoded";
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                $result = curl_exec($ch);
                $resultarray = json_decode($result,true);
                $result_statuscode = $resultarray['messages'][0]['status'];
                echo "\nNexmo status code: " . $result_statuscode . "\n";

                curl_close ($ch);

                //
                // 2. Plivo
                //
                if ($result_statuscode > 0) {
                
                $src = '+11111111';

                $url = 'https://api.plivo.com/v1/Account/'.$plivo1.'/Message/';
                $data = array("src" => "$src", "dst" => "$dst", "text" => "$text");
                $data_string = json_encode($data);
                $ch=curl_init($url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
                curl_setopt($ch, CURLOPT_HEADER, true);
                curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
                curl_setopt($ch, CURLOPT_USERPWD, $plivo1 . ":" . $plivo2);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                
                echo "\nPlivo result:";
                $result = curl_exec($ch);
                
                /* $resultStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                   echo("Result: ".$resultStatus); // 401 = error, 202 = success */

                curl_close($ch);

                }
                
                //
                // Update DB
                //
                $ID = $alert['ID'];
                $sqlabove = "UPDATE coinwink_sms SET above_sent=1 WHERE ID = $ID";
                $conn->query($sqlabove);
            
                }

            }
        
        }
    
    }

}

//
//
// PROCESS JPY
//
//

// Get data from coinmarketcap.com
// create curl resource 
$ch = curl_init(); 
// set url 
curl_setopt($ch, CURLOPT_URL, "https://api.coinmarketcap.com/v1/ticker/?convert=JPY&limit=0"); 
//return the transfer as a string 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
// $output contains the output string 
$output = curl_exec($ch); 
// close curl resource to free up system resources 
curl_close($ch);

// Remove possible single quotes from JSON string
$output = str_replace("'", "", $output);

// decode json
$outputdecoded = json_decode($output, true);

// Send alerts for each coin from Coinmarketcap api
foreach ($outputdecoded as $jsoncoin) {

    if ($alerts[$jsoncoin["id"]]) {
        
        foreach ($alerts[$jsoncoin["id"]] as $alert) {
  
            // BELOW JPY

            if ($alert['below_currency'] == 'JPY') {
                if ($jsoncoin['price_jpy'] < $alert['below'] && !$alert['below_sent'] && is_numeric($alert['below'])){ 
                
                echo($alert['ID'] . $alert['coin'] . "JPY below SMS sent");

                //
                // Setup SMS sending
                //
                $user_ID = $alert['user_ID'];
                $settings = "SELECT * FROM coinwink_settings WHERE user_ID = '".$user_ID."'";
                $settings_result = mysqli_fetch_assoc($conn->query($settings));

                $nexmo1 = $settings_result[nexmo1];
                $nexmo2 = $settings_result[nexmo2];
                $plivo1 = $settings_result[plivo1];
                $plivo2 = $settings_result[plivo2];
                $nexmo_nr_short = $settings_result[nexmo_nr_short];

                # SMS sender ID
                if ($nexmo_nr_short) {
                    $src = $nexmo_nr_short;
                }
                else {
                    $src = 'Coinwink';
                }
                # SMS destination number
                $dst = $alert['phone'];
                # SMS text
                $text = 'Alert: '. ucfirst($alert['coin']) .' ('. ucfirst($alert['symbol']) .') is below '. $alert['below'] .' JPY | coinwink.com';
 
                //
                // 1. Nexmo
                //
                $ch = curl_init();
                
                curl_setopt($ch, CURLOPT_URL, "https://rest.nexmo.com/sms/json");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, "api_key=$nexmo1&api_secret=$nexmo2&to=$dst&from=$src&text=$text");
                curl_setopt($ch, CURLOPT_POST, true);

                $headers = array();
                $headers[] = "Content-Type: application/x-www-form-urlencoded";
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                $result = curl_exec($ch);
                $resultarray = json_decode($result,true);
                $result_statuscode = $resultarray['messages'][0]['status'];
                echo "\nNexmo status code: " . $result_statuscode . "\n";

                curl_close ($ch);
                
                //
                // 2. Plivo
                //
                if ($result_statuscode > 0) {
                
                $src = '+11111111';

                $url = 'https://api.plivo.com/v1/Account/'.$plivo1.'/Message/';
                $data = array("src" => "$src", "dst" => "$dst", "text" => "$text");
                $data_string = json_encode($data);
                $ch=curl_init($url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
                curl_setopt($ch, CURLOPT_HEADER, true);
                curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
                curl_setopt($ch, CURLOPT_USERPWD, $plivo1 . ":" . $plivo2);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                
                echo "\nPlivo result:";
                $result = curl_exec($ch);
                
                /* $resultStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                   echo("Result: ".$resultStatus); // 401 = error, 202 = success */

                curl_close($ch);

                }

                //
                // Update DB
                //
                $ID = $alert['ID'];
                $sqlbelow = "UPDATE coinwink_sms SET below_sent=1 WHERE ID = $ID";
                $conn->query($sqlbelow);

                }
            }

            // ABOVE JPY

            if ($alert['above_currency'] == 'JPY') {

                if ($jsoncoin['price_jpy'] > $alert['above'] && !$alert['above_sent'] && is_numeric($alert['above'])) { 
                
                echo($alert['ID'] . $alert['coin'] . "JPY ABOVE SMS sent");  
                
                //
                // Setup SMS sending
                //
                $user_ID = $alert['user_ID'];
                $settings = "SELECT * FROM coinwink_settings WHERE user_ID = '".$user_ID."'";
                $settings_result = mysqli_fetch_assoc($conn->query($settings));

                $plivo1 = $settings_result[plivo1];
                $plivo2 = $settings_result[plivo2];
                $nexmo1 = $settings_result[nexmo1];
                $nexmo2 = $settings_result[nexmo2];
                $nexmo_nr_short = $settings_result[nexmo_nr_short];

                # SMS sender ID.
                if ($nexmo_nr_short) {
                    $src = $nexmo_nr_short;
                }
                else {
                    $src = 'Coinwink';
                }
                # SMS destination number
                $dst = $alert['phone'];
                # SMS text
                $text = 'Alert: '. ucfirst($alert['coin']) .' ('. ucfirst($alert['symbol']) .') is above '. $alert['above'] .' JPY | coinwink.com';
                
                
                //
                // 1. Nexmo
                //
                $ch = curl_init();
                
                curl_setopt($ch, CURLOPT_URL, "https://rest.nexmo.com/sms/json");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, "api_key=$nexmo1&api_secret=$nexmo2&to=$dst&from=$src&text=$text");
                curl_setopt($ch, CURLOPT_POST, true);

                $headers = array();
                $headers[] = "Content-Type: application/x-www-form-urlencoded";
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                $result = curl_exec($ch);
                $resultarray = json_decode($result,true);
                $result_statuscode = $resultarray['messages'][0]['status'];
                echo "\nNexmo status code: " . $result_statuscode . "\n";

                curl_close ($ch);

                //
                // 2. Plivo
                //
                if ($result_statuscode > 0) {
                
                $src = '+11111111';

                $url = 'https://api.plivo.com/v1/Account/'.$plivo1.'/Message/';
                $data = array("src" => "$src", "dst" => "$dst", "text" => "$text");
                $data_string = json_encode($data);
                $ch=curl_init($url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
                curl_setopt($ch, CURLOPT_HEADER, true);
                curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
                curl_setopt($ch, CURLOPT_USERPWD, $plivo1 . ":" . $plivo2);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                
                echo "\nPlivo result:";
                $result = curl_exec($ch);
                
                /* $resultStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                   echo("Result: ".$resultStatus); // 401 = error, 202 = success */

                curl_close($ch);

                }
                
                //
                // Update DB
                //
                $ID = $alert['ID'];
                $sqlabove = "UPDATE coinwink_sms SET above_sent=1 WHERE ID = $ID";
                $conn->query($sqlabove);
            
                }

            }
        
        }
    
    }

}

// Check memory used in megabytes
function convert($size)
{
    $unit=array('b','kb','mb','gb','tb','pb');
    return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
}
echo ("\r\nMemory used: " . convert(memory_get_usage(true)));


// Check processing time - END
function rutime($ru, $rus, $index) {
    return ($ru["ru_$index.tv_sec"]*1000 + intval($ru["ru_$index.tv_usec"]/1000))
     -  ($rus["ru_$index.tv_sec"]*1000 + intval($rus["ru_$index.tv_usec"]/1000));
}


$ru = getrusage();
echo "\r\nThis process used " . rutime($ru, $rustart, "utime") .
    " ms for its computations\n";
echo "It spent " . rutime($ru, $rustart, "stime") .
    " ms in system calls\n";


?>
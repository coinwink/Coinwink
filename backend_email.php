<?php
// Delay this script to allow other processes to complete first
sleep(20);

// Increase default (256M) memory limit
ini_set('memory_limit', '512M');

// Check processing time - START
$rustart = getrusage();


// Connect to Mysql
include_once "coinwink_auth_sql.php";
include_once "coinwink_auth_email.php";


// Select all data from alerts database
$sql = "SELECT * FROM coinwink";
$resultdb = $conn->query($sql);


// Get data from coinmarketcap.com
// create curl resource 
$ch = curl_init(); 
// set url 
curl_setopt($ch, CURLOPT_URL, "https://api.coinmarketcap.com/v1/ticker/?convert=ETH&limit=0"); 
// return the transfer as a string 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
// $output contains the output string 
$output = curl_exec($ch); 
// close curl resource to free up system resources 
curl_close($ch);


// Update market data in the database for the front end
// remove possible single quotes from JSON string
$output = str_replace("'", "", $output);

// Decode
$outputdecoded = json_decode($output, true);

// Remove unecessary data to save space on the front end
function unsetSomeProperties($outputdecoded) {
    foreach ($outputdecoded as &$jsoncoin) {
        unset($jsoncoin["rank"]);
        unset($jsoncoin["24h_volume_usd"]);
        unset($jsoncoin["market_cap_usd"]);
        unset($jsoncoin["available_supply"]);
        unset($jsoncoin["total_supply"]);
        unset($jsoncoin["max_supply"]);
        unset($jsoncoin["percent_change_7d"]);
        unset($jsoncoin["last_updated"]);
        unset($jsoncoin["24h_volume_eth"]);
        unset($jsoncoin["market_cap_eth"]);
        $jsoncoin["text"] = $jsoncoin["name"] . ' (' . $jsoncoin["symbol"] . ')';
    }
    return $outputdecoded;
}

$outputdecoded = unsetSomeProperties($outputdecoded);

// Serialize and update db
$outputserialized = serialize($outputdecoded);
$sqljson = "UPDATE coinwink_json SET json = '$outputserialized'";
$conn->query($sqljson);

// Group alerts for each coin into new assoc array to optimize the later foreach loop
// @todo: separate alerts into several arrays, each 100k alerts, to free up the memory
// 512 mb can hold only ~180 alerts, 748 mb can hold 250k and bigger mb increase doesn't help at all

// $i = 0;
foreach($resultdb as $row)
{
    $alerts[$row["coin_id"]][] = $row;
    // if ($i++ == 50000) {   break; }
}

// Send alerts for each coin from Coinmarketcap api
foreach ($outputdecoded as $jsoncoin) {

    if ($alerts[$jsoncoin["id"]]) {
        
        foreach ($alerts[$jsoncoin["id"]] as $alert) {

            // BELOW BTC

            if ($alert['below_currency'] == 'BTC') {
                if ($jsoncoin['price_btc'] < $alert['below'] && !$alert['below_sent'] && is_numeric($alert['below'])){ 

                // Echo
                echo($alert['ID'] . " " . $alert['coin'] . " BELOW BTC \r\n");
                    
                // Email
                $mail->addAddress($alert['email']);
                
                $mail->Subject  = 'Alert: '. ucfirst($alert['coin']) .' ('. ucfirst($alert['symbol']) .') is below '. $alert['below'] .' BTC';
                
                $mail->Body = ''. ucfirst($alert['coin']) .' ('. ucfirst($alert['symbol']) .') is below '. $alert['below'] .' BTC.
                
You can manage your alert(-s) with this unique id: '. $alert['unique_id'] .' or with your account at https://coinwink.com
                
Wink,
Coinwink';

                // Send + success or error
                if(!$mail->Send()) {
                    echo 'Message could not be sent. ';
                    echo 'Mailer Error: ' . $mail->ErrorInfo . "\r\n";
                    mail($adminaddress,"Coinwink error", $mail->ErrorInfo . ' Email: ' . $alert['email']);
                    // exit;
                }
                else {
                    echo "Message has been sent \r\n";
                }

                $mail->ClearAllRecipients();

                // Update DB
                $ID = $alert['ID'];
                $sqlbelow = "UPDATE coinwink SET below_sent=1 WHERE ID = $ID";
                $conn->query($sqlbelow);

                }
            }

            // BELOW USD

            if ($alert['below_currency'] == 'USD') {
                if ($jsoncoin['price_usd'] < $alert['below'] && !$alert['below_sent'] && is_numeric($alert['below'])){ 

                // Echo
                echo($alert['ID'] . " " . $alert['coin'] . " BELOW USD\r\n");

                // Email
                $mail->addAddress($alert['email']);
                
                $mail->Subject  = 'Alert: '. ucfirst($alert['coin']) .' ('. ucfirst($alert['symbol']) .') is below '. $alert['below'] .' USD';
                
                $mail->Body = ''. ucfirst($alert['coin']) .' ('. ucfirst($alert['symbol']) .') is below '. $alert['below'] .' USD.
                
You can manage your alert(-s) with this unique id: '. $alert['unique_id'] .' or with your account at https://coinwink.com
                
Wink,
Coinwink';

                // Send + success or error
                if(!$mail->Send()) {
                    echo 'Message could not be sent. ';
                    echo 'Mailer Error: ' . $mail->ErrorInfo . "\r\n";
                    mail($adminaddress,"Coinwink error", $mail->ErrorInfo . ' Email: ' . $alert['email']);
                    // exit;
                }
                else {
                    echo "Message has been sent \r\n";
                }

                $mail->ClearAllRecipients();

                // Update DB
                $ID = $alert['ID'];
                $sqlbelow = "UPDATE coinwink SET below_sent=1 WHERE ID = $ID";
                $conn->query($sqlbelow);                    

                }
            }

            // BELOW ETH

            if ($alert['below_currency'] == 'ETH') {
                if ($jsoncoin['price_eth'] < $alert['below'] && !$alert['below_sent'] && is_numeric($alert['below'])){ 

                // Echo
                echo($alert['ID'] . " " . $alert['coin'] . " BELOW ETH \r\n");
                    
                // Email
                $mail->addAddress($alert['email']);
                
                $mail->Subject  = 'Alert: '. ucfirst($alert['coin']) .' ('. ucfirst($alert['symbol']) .') is below '. $alert['below'] .' ETH';
                
                $mail->Body = ''. ucfirst($alert['coin']) .' ('. ucfirst($alert['symbol']) .') is below '. $alert['below'] .' ETH.
                
You can manage your alert(-s) with this unique id: '. $alert['unique_id'] .' or with your account at https://coinwink.com
                
Wink,
Coinwink';

                // Send + success or error
                if(!$mail->Send()) {
                    echo 'Message could not be sent. ';
                    echo 'Mailer Error: ' . $mail->ErrorInfo . "\r\n";
                    mail($adminaddress,"Coinwink error", $mail->ErrorInfo . ' Email: ' . $alert['email']);
                    // exit;
                }
                else {
                    echo "Message has been sent \r\n";
                }

                $mail->ClearAllRecipients();

                // Update DB
                $ID = $alert['ID'];
                $sqlbelow = "UPDATE coinwink SET below_sent=1 WHERE ID = $ID";
                $conn->query($sqlbelow);

                }
            }

            // ABOVE USD

            if ($alert['above_currency'] == 'USD') {
                if ($jsoncoin['price_usd'] > $alert['above'] && !$alert['above_sent'] && is_numeric($alert['above'])){ 

                // Echo
                echo($alert['ID'] . " " . $alert['coin'] . " ABOVE USD\r\n");

                // Email
                $mail->addAddress($alert['email']);
                
                $mail->Subject  = 'Alert: '. ucfirst($alert['coin']) .' ('. ucfirst($alert['symbol']) .') is above '. $alert['above'] .' USD';
                
                $mail->Body = ''. ucfirst($alert['coin']) .' ('. ucfirst($alert['symbol']) .') is above '. $alert['above'] .' USD.
                
You can manage your alert(-s) with this unique id: '. $alert['unique_id'] .' or with your account at https://coinwink.com
                
Wink,
Coinwink';

                // Send + success or error
                if(!$mail->Send()) {
                    echo 'Message could not be sent. ';
                    echo 'Mailer Error: ' . $mail->ErrorInfo . "\r\n";
                    mail($adminaddress,"Coinwink error", $mail->ErrorInfo . ' Email: ' . $alert['email']);
                    // exit;
                }
                else {
                    echo "Message has been sent \r\n";
                }

                $mail->ClearAllRecipients();

                // Update DB
                $ID = $alert['ID'];
                $sqlabove = "UPDATE coinwink SET above_sent=1 WHERE ID = $ID";
                $conn->query($sqlabove);                    

                }
            }

            // ABOVE BTC

            if ($alert['above_currency'] == 'BTC') {
                if ($jsoncoin['price_btc'] > $alert['above'] && !$alert['above_sent'] && is_numeric($alert['above'])){ 
                
                // Echo
                echo($alert['ID'] . " " . $alert['coin'] . " ABOVE BTC\r\n");
                    
                // Email
                $mail->addAddress($alert['email']);
                
                $mail->Subject  = 'Alert: '. ucfirst($alert['coin']) .' ('. ucfirst($alert['symbol']) .') is above '. $alert['above'] .' BTC';
                
                $mail->Body = ''. ucfirst($alert['coin']) .' ('. ucfirst($alert['symbol']) .') is above '. $alert['above'] .' BTC.
                
You can manage your alert(-s) with this unique id: '. $alert['unique_id'] .' or with your account at https://coinwink.com
                
Wink,
Coinwink';

                // Send + success or error
                if(!$mail->Send()) {
                    echo 'Message could not be sent. ';
                    echo 'Mailer Error: ' . $mail->ErrorInfo . "\r\n";
                    mail($adminaddress,"Coinwink error", $mail->ErrorInfo . ' Email: ' . $alert['email']);
                    // exit;
                }
                else {
                    echo "Message has been sent \r\n";
                }

                $mail->ClearAllRecipients();                    

                // Update DB                
                $ID = $alert['ID'];
                $sqlabove = "UPDATE coinwink SET above_sent=1 WHERE ID = $ID";
                $conn->query($sqlabove);

                }
            }

            // ABOVE ETH

            if ($alert['above_currency'] == 'ETH') {
                if ($jsoncoin['price_eth'] > $alert['above'] && !$alert['above_sent'] && is_numeric($alert['above'])){ 
                
                // Echo
                echo($alert['ID'] . " " . $alert['coin'] . " ABOVE ETH\r\n");
                    
                // Email
                $mail->addAddress($alert['email']);
                
                $mail->Subject  = 'Alert: '. ucfirst($alert['coin']) .' ('. ucfirst($alert['symbol']) .') is above '. $alert['above'] .' ETH';
                
                $mail->Body = ''. ucfirst($alert['coin']) .' ('. ucfirst($alert['symbol']) .') is above '. $alert['above'] .' ETH.
                
You can manage your alert(-s) with this unique id: '. $alert['unique_id'] .' or with your account at https://coinwink.com
                
Wink,
Coinwink';

                // Send + success or error
                if(!$mail->Send()) {
                    echo 'Message could not be sent. ';
                    echo 'Mailer Error: ' . $mail->ErrorInfo . "\r\n";
                    mail($adminaddress,"Coinwink error", $mail->ErrorInfo . ' Email: ' . $alert['email']);
                    // exit;
                }
                else {
                    echo "Message has been sent \r\n";
                }

                $mail->ClearAllRecipients();                    

                // Update DB                
                $ID = $alert['ID'];
                $sqlabove = "UPDATE coinwink SET above_sent=1 WHERE ID = $ID";
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
// return the transfer as a string 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
// $output contains the output string 
$output = curl_exec($ch); 
// close curl resource to free up system resources 
curl_close($ch);

// Update market data in the database for the front end
// remove possible single quotes from JSON string
$output = str_replace("'", "", $output);

// Decode
$outputdecoded = json_decode($output, true);

// Send alerts for each coin from Coinmarketcap api
foreach ($outputdecoded as $jsoncoin) {

    if ($alerts[$jsoncoin["id"]]) {
        
        foreach ($alerts[$jsoncoin["id"]] as $alert) {

            // BELOW EUR

            if ($alert['below_currency'] == 'EUR') {
                if ($jsoncoin['price_eur'] < $alert['below'] && !$alert['below_sent'] && is_numeric($alert['below'])){ 

                // Echo
                echo($alert['ID'] . " " . $alert['coin'] . " BELOW EUR \r\n");
                    
                // Email
                $mail->addAddress($alert['email']);
                
                $mail->Subject  = 'Alert: '. ucfirst($alert['coin']) .' ('. ucfirst($alert['symbol']) .') is below '. $alert['below'] .' EUR';
                
                $mail->Body = ''. ucfirst($alert['coin']) .' ('. ucfirst($alert['symbol']) .') is below '. $alert['below'] .' EUR.
                
You can manage your alert(-s) with this unique id: '. $alert['unique_id'] .' or with your account at https://coinwink.com
                
Wink,
Coinwink';

                // Send + success or error
                if(!$mail->Send()) {
                    echo 'Message could not be sent. ';
                    echo 'Mailer Error: ' . $mail->ErrorInfo . "\r\n";
                    mail($adminaddress,"Coinwink error", $mail->ErrorInfo . ' Email: ' . $alert['email']);
                    // exit;
                }
                else {
                    echo "Message has been sent \r\n";
                }

                $mail->ClearAllRecipients();

                // Update DB
                $ID = $alert['ID'];
                $sqlbelow = "UPDATE coinwink SET below_sent=1 WHERE ID = $ID";
                $conn->query($sqlbelow);

                }
            }

            // ABOVE EUR

            if ($alert['above_currency'] == 'EUR') {
                if ($jsoncoin['price_eur'] > $alert['above'] && !$alert['above_sent'] && is_numeric($alert['above'])){ 

                // Echo
                echo($alert['ID'] . " " . $alert['coin'] . " ABOVE EUR\r\n");

                // Email
                $mail->addAddress($alert['email']);
                
                $mail->Subject  = 'Alert: '. ucfirst($alert['coin']) .' ('. ucfirst($alert['symbol']) .') is above '. $alert['above'] .' EUR';
                
                $mail->Body = ''. ucfirst($alert['coin']) .' ('. ucfirst($alert['symbol']) .') is above '. $alert['above'] .' EUR.
                
You can manage your alert(-s) with this unique id: '. $alert['unique_id'] .' or with your account at https://coinwink.com
                
Wink,
Coinwink';

                // Send + success or error
                if(!$mail->Send()) {
                    echo 'Message could not be sent. ';
                    echo 'Mailer Error: ' . $mail->ErrorInfo . "\r\n";
                    mail($adminaddress,"Coinwink error", $mail->ErrorInfo . ' Email: ' . $alert['email']);
                    // exit;
                }
                else {
                    echo "Message has been sent \r\n";
                }

                $mail->ClearAllRecipients();

                // Update DB
                $ID = $alert['ID'];
                $sqlabove = "UPDATE coinwink SET above_sent=1 WHERE ID = $ID";
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
// return the transfer as a string 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
// $output contains the output string 
$output = curl_exec($ch); 
// close curl resource to free up system resources 
curl_close($ch);

// Update market data in the database for the front end
// remove possible single quotes from JSON string
$output = str_replace("'", "", $output);

// Decode
$outputdecoded = json_decode($output, true);

// Send alerts for each coin from Coinmarketcap api
foreach ($outputdecoded as $jsoncoin) {

    if ($alerts[$jsoncoin["id"]]) {

        foreach ($alerts[$jsoncoin["id"]] as $alert) {

            // BELOW AUD

            if ($alert['below_currency'] == 'AUD') {
            
                if ($jsoncoin['price_aud'] < $alert['below'] && !$alert['below_sent'] && is_numeric($alert['below'])){ 
              
                // Echo
                echo($alert['ID'] . " " . $alert['coin'] . " BELOW AUD \r\n");
                    
                // Email
                $mail->addAddress($alert['email']);
                
                $mail->Subject  = 'Alert: '. ucfirst($alert['coin']) .' ('. ucfirst($alert['symbol']) .') is below '. $alert['below'] .' AUD';
                
                $mail->Body = ''. ucfirst($alert['coin']) .' ('. ucfirst($alert['symbol']) .') is below '. $alert['below'] .' AUD.
                
You can manage your alert(-s) with this unique id: '. $alert['unique_id'] .' or with your account at https://coinwink.com
                
Wink,
Coinwink';

                // Send + success or error
                if(!$mail->Send()) {
                    echo 'Message could not be sent. ';
                    echo 'Mailer Error: ' . $mail->ErrorInfo . "\r\n";
                    mail($adminaddress,"Coinwink error", $mail->ErrorInfo . ' Email: ' . $alert['email']);
                    // exit;
                }
                else {
                    echo "Message has been sent \r\n";
                }

                $mail->ClearAllRecipients();

                // Update DB
                $ID = $alert['ID'];
                $sqlbelow = "UPDATE coinwink SET below_sent=1 WHERE ID = $ID";
                $conn->query($sqlbelow);

                }
            }

            // ABOVE AUD

            if ($alert['above_currency'] == 'AUD') {
                if ($jsoncoin['price_aud'] > $alert['above'] && !$alert['above_sent'] && is_numeric($alert['above'])){ 

                // Echo
                echo($alert['ID'] . " " . $alert['coin'] . " ABOVE AUD\r\n");

                // Email
                $mail->addAddress($alert['email']);
                
                $mail->Subject  = 'Alert: '. ucfirst($alert['coin']) .' ('. ucfirst($alert['symbol']) .') is above '. $alert['above'] .' AUD';
                
                $mail->Body = ''. ucfirst($alert['coin']) .' ('. ucfirst($alert['symbol']) .') is above '. $alert['above'] .' AUD.
                
You can manage your alert(-s) with this unique id: '. $alert['unique_id'] .' or with your account at https://coinwink.com
                
Wink,
Coinwink';

                // Send + success or error
                if(!$mail->Send()) {
                    echo 'Message could not be sent. ';
                    echo 'Mailer Error: ' . $mail->ErrorInfo . "\r\n";
                    mail($adminaddress,"Coinwink error", $mail->ErrorInfo . ' Email: ' . $alert['email']);
                    // exit;
                }
                else {
                    echo "Message has been sent \r\n";
                }

                $mail->ClearAllRecipients();

                // Update DB
                $ID = $alert['ID'];
                $sqlabove = "UPDATE coinwink SET above_sent=1 WHERE ID = $ID";
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
// return the transfer as a string 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
// $output contains the output string 
$output = curl_exec($ch); 
// close curl resource to free up system resources 
curl_close($ch);

// Update market data in the database for the front end
// remove possible single quotes from JSON string
$output = str_replace("'", "", $output);

// Decode
$outputdecoded = json_decode($output, true);

// Send alerts for each coin from Coinmarketcap api
foreach ($outputdecoded as $jsoncoin) {

    if ($alerts[$jsoncoin["id"]]) {
        
        foreach ($alerts[$jsoncoin["id"]] as $alert) {

            // BELOW CAD

            if ($alert['below_currency'] == 'CAD') {
                if ($jsoncoin['price_cad'] < $alert['below'] && !$alert['below_sent'] && is_numeric($alert['below'])){ 

                // Echo
                echo($alert['ID'] . " " . $alert['coin'] . " BELOW CAD \r\n");
                    
                // Email
                $mail->addAddress($alert['email']);
                
                $mail->Subject  = 'Alert: '. ucfirst($alert['coin']) .' ('. ucfirst($alert['symbol']) .') is below '. $alert['below'] .' CAD';
                
                $mail->Body = ''. ucfirst($alert['coin']) .' ('. ucfirst($alert['symbol']) .') is below '. $alert['below'] .' CAD.
                
You can manage your alert(-s) with this unique id: '. $alert['unique_id'] .' or with your account at https://coinwink.com
                
Wink,
Coinwink';

                // Send + success or error
                if(!$mail->Send()) {
                    echo 'Message could not be sent. ';
                    echo 'Mailer Error: ' . $mail->ErrorInfo . "\r\n";
                    mail($adminaddress,"Coinwink error", $mail->ErrorInfo . ' Email: ' . $alert['email']);
                    // exit;
                }
                else {
                    echo "Message has been sent \r\n";
                }

                $mail->ClearAllRecipients();

                // Update DB
                $ID = $alert['ID'];
                $sqlbelow = "UPDATE coinwink SET below_sent=1 WHERE ID = $ID";
                $conn->query($sqlbelow);

                }
            }

            // ABOVE CAD

            if ($alert['above_currency'] == 'CAD') {
                if ($jsoncoin['price_cad'] > $alert['above'] && !$alert['above_sent'] && is_numeric($alert['above'])){ 

                // Echo
                echo($alert['ID'] . " " . $alert['coin'] . " ABOVE CAD\r\n");

                // Email
                $mail->addAddress($alert['email']);
                
                $mail->Subject  = 'Alert: '. ucfirst($alert['coin']) .' ('. ucfirst($alert['symbol']) .') is above '. $alert['above'] .' CAD';
                
                $mail->Body = ''. ucfirst($alert['coin']) .' ('. ucfirst($alert['symbol']) .') is above '. $alert['above'] .' CAD.
                
You can manage your alert(-s) with this unique id: '. $alert['unique_id'] .' or with your account at https://coinwink.com
                
Wink,
Coinwink';

                // Send + success or error
                if(!$mail->Send()) {
                    echo 'Message could not be sent. ';
                    echo 'Mailer Error: ' . $mail->ErrorInfo . "\r\n";
                    mail($adminaddress,"Coinwink error", $mail->ErrorInfo . ' Email: ' . $alert['email']);
                    // exit;
                }
                else {
                    echo "Message has been sent \r\n";
                }

                $mail->ClearAllRecipients();

                // Update DB
                $ID = $alert['ID'];
                $sqlabove = "UPDATE coinwink SET above_sent=1 WHERE ID = $ID";
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
// return the transfer as a string 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
// $output contains the output string 
$output = curl_exec($ch); 
// close curl resource to free up system resources 
curl_close($ch);

// Update market data in the database for the front end
// remove possible single quotes from JSON string
$output = str_replace("'", "", $output);

// Decode
$outputdecoded = json_decode($output, true);

// Send alerts for each coin from Coinmarketcap api
foreach ($outputdecoded as $jsoncoin) {

    if ($alerts[$jsoncoin["id"]]) {
        
        foreach ($alerts[$jsoncoin["id"]] as $alert) {

            // BELOW KRW

            if ($alert['below_currency'] == 'KRW') {
                if ($jsoncoin['price_krw'] < $alert['below'] && !$alert['below_sent'] && is_numeric($alert['below'])){ 

                // Echo
                echo($alert['ID'] . " " . $alert['coin'] . " BELOW KRW \r\n");
                    
                // Email
                $mail->addAddress($alert['email']);
                
                $mail->Subject  = 'Alert: '. ucfirst($alert['coin']) .' ('. ucfirst($alert['symbol']) .') is below '. $alert['below'] .' KRW';
                
                $mail->Body = ''. ucfirst($alert['coin']) .' ('. ucfirst($alert['symbol']) .') is below '. $alert['below'] .' KRW.
                
You can manage your alert(-s) with this unique id: '. $alert['unique_id'] .' or with your account at https://coinwink.com
                
Wink,
Coinwink';

                // Send + success or error
                if(!$mail->Send()) {
                    echo 'Message could not be sent. ';
                    echo 'Mailer Error: ' . $mail->ErrorInfo . "\r\n";
                    mail($adminaddress,"Coinwink error", $mail->ErrorInfo . ' Email: ' . $alert['email']);
                    // exit;
                }
                else {
                    echo "Message has been sent \r\n";
                }

                $mail->ClearAllRecipients();

                // Update DB
                $ID = $alert['ID'];
                $sqlbelow = "UPDATE coinwink SET below_sent=1 WHERE ID = $ID";
                $conn->query($sqlbelow);

                }
            }

            // ABOVE KRW

            if ($alert['above_currency'] == 'KRW') {
                if ($jsoncoin['price_krw'] > $alert['above'] && !$alert['above_sent'] && is_numeric($alert['above'])){ 

                // Echo
                echo($alert['ID'] . " " . $alert['coin'] . " ABOVE KRW\r\n");

                // Email
                $mail->addAddress($alert['email']);
                
                $mail->Subject  = 'Alert: '. ucfirst($alert['coin']) .' ('. ucfirst($alert['symbol']) .') is above '. $alert['above'] .' KRW';
                
                $mail->Body = ''. ucfirst($alert['coin']) .' ('. ucfirst($alert['symbol']) .') is above '. $alert['above'] .' KRW.
                
You can manage your alert(-s) with this unique id: '. $alert['unique_id'] .' or with your account at https://coinwink.com
                
Wink,
Coinwink';

                // Send + success or error
                if(!$mail->Send()) {
                    echo 'Message could not be sent. ';
                    echo 'Mailer Error: ' . $mail->ErrorInfo . "\r\n";
                    mail($adminaddress,"Coinwink error", $mail->ErrorInfo . ' Email: ' . $alert['email']);
                    // exit;
                }
                else {
                    echo "Message has been sent \r\n";
                }

                $mail->ClearAllRecipients();

                // Update DB
                $ID = $alert['ID'];
                $sqlabove = "UPDATE coinwink SET above_sent=1 WHERE ID = $ID";
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
// return the transfer as a string 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
// $output contains the output string 
$output = curl_exec($ch); 
// close curl resource to free up system resources 
curl_close($ch);

// Update market data in the database for the front end
// remove possible single quotes from JSON string
$output = str_replace("'", "", $output);

// Decode
$outputdecoded = json_decode($output, true);

// Send alerts for each coin from Coinmarketcap api
foreach ($outputdecoded as $jsoncoin) {

    if ($alerts[$jsoncoin["id"]]) {
        
        foreach ($alerts[$jsoncoin["id"]] as $alert) {

            // BELOW JPY

            if ($alert['below_currency'] == 'JPY') {
                if ($jsoncoin['price_jpy'] < $alert['below'] && !$alert['below_sent'] && is_numeric($alert['below'])){ 

                // Echo
                echo($alert['ID'] . " " . $alert['coin'] . " BELOW JPY \r\n");
                    
                // Email
                $mail->addAddress($alert['email']);
                
                $mail->Subject  = 'Alert: '. ucfirst($alert['coin']) .' ('. ucfirst($alert['symbol']) .') is below '. $alert['below'] .' JPY';
                
                $mail->Body = ''. ucfirst($alert['coin']) .' ('. ucfirst($alert['symbol']) .') is below '. $alert['below'] .' JPY.
                
You can manage your alert(-s) with this unique id: '. $alert['unique_id'] .' or with your account at https://coinwink.com
                
Wink,
Coinwink';

                // Send + success or error
                if(!$mail->Send()) {
                    echo 'Message could not be sent. ';
                    echo 'Mailer Error: ' . $mail->ErrorInfo . "\r\n";
                    mail($adminaddress,"Coinwink error", $mail->ErrorInfo . ' Email: ' . $alert['email']);
                    // exit;
                }
                else {
                    echo "Message has been sent \r\n";
                }

                $mail->ClearAllRecipients();

                // Update DB
                $ID = $alert['ID'];
                $sqlbelow = "UPDATE coinwink SET below_sent=1 WHERE ID = $ID";
                $conn->query($sqlbelow);

                }
            }

            // ABOVE JPY

            if ($alert['above_currency'] == 'JPY') {
                if ($jsoncoin['price_jpy'] > $alert['above'] && !$alert['above_sent'] && is_numeric($alert['above'])){ 

                // Echo
                echo($alert['ID'] . " " . $alert['coin'] . " ABOVE JPY\r\n");

                // Email
                $mail->addAddress($alert['email']);
                
                $mail->Subject  = 'Alert: '. ucfirst($alert['coin']) .' ('. ucfirst($alert['symbol']) .') is above '. $alert['above'] .' JPY';
                
                $mail->Body = ''. ucfirst($alert['coin']) .' ('. ucfirst($alert['symbol']) .') is above '. $alert['above'] .' JPY.
                
You can manage your alert(-s) with this unique id: '. $alert['unique_id'] .' or with your account at https://coinwink.com
                
Wink,
Coinwink';

                // Send + success or error
                if(!$mail->Send()) {
                    echo 'Message could not be sent. ';
                    echo 'Mailer Error: ' . $mail->ErrorInfo . "\r\n";
                    mail($adminaddress,"Coinwink error", $mail->ErrorInfo . ' Email: ' . $alert['email']);
                    // exit;
                }
                else {
                    echo "Message has been sent \r\n";
                }

                $mail->ClearAllRecipients();

                // Update DB
                $ID = $alert['ID'];
                $sqlabove = "UPDATE coinwink SET above_sent=1 WHERE ID = $ID";
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
    " ms for its computations\r\n";
echo "It spent " . rutime($ru, $rustart, "stime") .
    " ms in system calls\r\n";

?>
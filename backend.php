<?php

// Connect to Mysql
include_once "coinwink_sql.php";



// Select all data from alerts database
$sql = "SELECT * FROM coinwink";
$resultdb = $conn->query($sql);
$masyvas = (mysqli_fetch_assoc($resultdb));



// Get data from coinmarketcap.com

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



// Update market data in the database for the front end
$outputdecoded = json_decode($output, true);
$output2 = serialize($outputdecoded);
$sqljson = "UPDATE coinwink_json SET json = '$output2'";
$conn->query($sqljson);



// Checking alerts and sending e-mails
foreach ($resultdb as $row) {

foreach ($outputdecoded as $jsoncoin)
{

if ($jsoncoin['name'] == $row['coin']) {


if ($row['below_currency'] == 'BTC') {

                if ($jsoncoin['price_btc'] < $row['below'] && !$row['below_sent'] && is_numeric($row['below'])){ 
                
                echo($row['ID'] . $row['coin'] . "BTC BELOW email sent");
                
                $to  = $row['email'];
                $subject = ''. ucfirst($row['coin']) .' ('. ucfirst($row['symbol']) .') is below '. $row['below'] .' BTC';
                
                $message = ''. ucfirst($row['coin']) .' ('. ucfirst($row['symbol']) .') is below '. $row['below'] .' BTC.
                
You can manage your alert(-s) with this unique id: '. $row['unique_id'] .' at https://coinwink.com
                
Wink,
Coinwink';
                
                $headers = 'From: "Coinwink" <alert@coinwink.com>' . "\r\n" .
                    'Reply-To: alert@coinwink.com' . "\r\n" .
                    'X-Mailer: PHP/' . phpversion();
                
                mail($to, $subject, $message, $headers);
          
                $ID = $row['ID'];
                $sqlbelow = "UPDATE coinwink SET below_sent=1 WHERE ID = $ID";
                $conn->query($sqlbelow);
                
                }
}

if ($row['below_currency'] == 'USD') {

                if ($jsoncoin['price_usd'] < $row['below'] && !$row['below_sent'] && is_numeric($row['below'])) { 

                echo($row['ID'] . $row['coin'] . "USD BELOW email sent");
                
                $to  = $row['email'];
                $subject = ''. ucfirst($row['coin']) .' ('. ucfirst($row['symbol']) .') is below '. $row['below'] .' USD';
                
                $message = ''. ucfirst($row['coin']) .' ('. ucfirst($row['symbol']) .') is below '. $row['below'] .' USD.
                
You can manage your alert(-s) with this unique id: '. $row['unique_id'] .' at https://coinwink.com
                
Wink,
Coinwink';
                
                $headers = 'From: "Coinwink" <alert@coinwink.com>' . "\r\n" .
                    'Reply-To: alert@coinwink.com' . "\r\n" .
                    'X-Mailer: PHP/' . phpversion();
                
                mail($to, $subject, $message, $headers);
                
                $ID = $row['ID'];
                $sqlbelow = "UPDATE coinwink SET below_sent=1 WHERE ID = $ID";
                $conn->query($sqlbelow);
                
                }
}


if ($row['above_currency'] == 'USD') {

                if ($jsoncoin['price_usd'] > $row['above'] && !$row['above_sent'] && is_numeric($row['above']) ) { 

                echo($row['ID'] . $row['coin'] . "USD ABOVE email sent");  
                
                $to  = $row['email'];
                $subject = ''. ucfirst($row['coin']) .' ('. ucfirst($row['symbol']) .') is above '. $row['above'] .' USD';
                
                $message = ''. ucfirst($row['coin']) .' ('. ucfirst($row['symbol']) .') is above '. $row['above'] .' USD.
                
You can manage your alert(-s) with this unique id: '. $row['unique_id'] .' at https://coinwink.com
                
Wink,
Coinwink';
                
                $headers = 'From: "Coinwink" <alert@coinwink.com>' . "\r\n" .
                    'Reply-To: alert@coinwink.com' . "\r\n" .
                    'X-Mailer: PHP/' . phpversion();
                
                mail($to, $subject, $message, $headers);
                
                $ID = $row['ID'];
                $sqlabove = "UPDATE coinwink SET above_sent=1 WHERE ID = $ID";
                $conn->query($sqlabove);
                
                }
}                
                
    
if ($row['above_currency'] == 'BTC') {

                if ($jsoncoin['price_btc'] > $row['above'] && !$row['above_sent'] && is_numeric($row['above'])) { 
                
                echo($row['ID'] . $row['coin'] . "BTC ABOVE email sent");  

                ///
                
                $to  = $row['email'];
                $subject = ''. ucfirst($row['coin']) .' ('. ucfirst($row['symbol']) .') is above '. $row['above'] .' BTC';
                
$message = ''. ucfirst($row['coin']) .' ('. ucfirst($row['symbol']) .') is above '. $row['above'] .' BTC.
                
You can manage your alert(-s) with this unique id: '. $row['unique_id'] .' at https://coinwink.com
                
Wink,
Coinwink';
                
                $headers = 'From: "Coinwink" <alert@coinwink.com>' . "\r\n" .
                    'Reply-To: alert@coinwink.com' . "\r\n" .
                    'X-Mailer: PHP/' . phpversion();
                
                mail($to, $subject, $message, $headers);

                ///
                
                $ID = $row['ID'];
                $sqlabove = "UPDATE coinwink SET above_sent=1 WHERE ID = $ID";
                $conn->query($sqlabove);

                }
}    

}

}

}


// Delete e-mail addresses that have no active alerts left

$delete_emails = "DELETE FROM coinwink WHERE (below_sent=1 AND above_sent=1)";
$conn->query($delete_emails);

$delete_emails = "DELETE FROM coinwink WHERE (below_sent=1 AND above_sent='' AND above='')";
$conn->query($delete_emails);

$delete_emails = "DELETE FROM coinwink WHERE (below_sent='' AND below='' AND above_sent=1)";
$conn->query($delete_emails);


?>
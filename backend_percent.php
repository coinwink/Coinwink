<?php
// Check processing time - START
$rustart = getrusage();

// Connect to Mysql
include_once "coinwink_auth_sql.php";
include_once "coinwink_auth_email.php";


// Select all data from alerts database
$sql = "SELECT * FROM coinwink_percent";
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

// Decode JSON
$outputdecoded = json_decode($output, true);



// Checking alerts and sending e-mails
foreach ($resultdb as $row) {
    foreach ($outputdecoded as $jsoncoin) {
        if ($jsoncoin['id'] == $row['coin_id']) {

            //
            // When plus_percent from_now compared to btc
            //
            if ($row['plus_change'] == 'from_now' && $row['plus_compared'] == 'BTC' && !$row['plus_sent'] && is_numeric($row['plus_percent']) && $row['price_set_btc'] > 0) {
    
                if (((1 - $row['price_set_btc'] / $jsoncoin['price_btc']) * 100) > $row['plus_percent'] ){ 
                // Echo
                echo($row['ID'] . " " . $row['coin'] . "plus_percent from_now compared to btc email sent \r\n");
                
                // Email
                $mail->addAddress($row['email']);
                
                $mail->Subject  = 'Alert: '. ucfirst($row['coin']) .' ('. ucfirst($row['symbol']) .') increased by '. $row['plus_percent'] .'%';
                
                $mail->Body = ''. ucfirst($row['coin']) .' ('. ucfirst($row['symbol']) .') price increased by '. $row['plus_percent'] .'% compared to BTC.

You can manage your alert(-s) with this unique id: '. $row['unique_id'] .' or with your account at https://coinwink.com

Wink,
Coinwink';

                // Send + success or error
                if(!$mail->Send()) {
                    echo 'Message could not be sent.';
                    echo 'Mailer Error: ' . $mail->ErrorInfo;
                    mail($adminaddress,"Coinwink error", $mail->ErrorInfo . ' Email: ' . $row['email']);
                    // exit;
                }
                echo "Message has been sent \r\n";

                $mail->ClearAllRecipients();

                // Update DB
                $ID = $row['ID'];
                $sqlbelow = "UPDATE coinwink_percent SET plus_sent=1 WHERE ID = $ID";
                $conn->query($sqlbelow);
                
                }
            }

            //
            // When minus_percent from_now compared to btc
            //
            if ($row['minus_change'] == 'from_now' && $row['minus_compared'] == 'BTC' && !$row['minus_sent'] && is_numeric($row['minus_percent']) && $row['price_set_btc'] > 0) {
                              
                if (((1 - $row['price_set_btc'] / $jsoncoin['price_btc']) * 100) < (-1 * $row['minus_percent']) ){ 
                // Echo
                echo($row['ID'] . " " . $row['coin'] . "minus_percent from_now compared to btc email sent \r\n");
                
                // Email
                $mail->addAddress($row['email']);
                
                $mail->Subject  = 'Alert: '. ucfirst($row['coin']) .' ('. ucfirst($row['symbol']) .') decreased by '. $row['minus_percent'] .'%';
                
                $mail->Body = ''. ucfirst($row['coin']) .' ('. ucfirst($row['symbol']) .') price decreased by '. $row['minus_percent'] .'% compared to BTC.
                
You can manage your alert(-s) with this unique id: '. $row['unique_id'] .' or with your account at https://coinwink.com
                
Wink,
Coinwink';

                // Send + success or error
                if(!$mail->Send()) {
                    echo 'Message could not be sent.';
                    echo 'Mailer Error: ' . $mail->ErrorInfo;
                    mail($adminaddress,"Coinwink error", $mail->ErrorInfo . ' Email: ' . $row['email']);
                    // exit;
                }
                echo "Message has been sent \r\n";

                $mail->ClearAllRecipients();

                // Update DB
                $ID = $row['ID'];
                $sqlbelow = "UPDATE coinwink_percent SET minus_sent=1 WHERE ID = $ID";
                $conn->query($sqlbelow);
                
                }
            }

            //
            // When plus_percent from_now compared to eth
            //
            if ($row['plus_change'] == 'from_now' && $row['plus_compared'] == 'ETH' && !$row['plus_sent'] && is_numeric($row['plus_percent']) && $row['price_set_eth'] > 0) {
                
                if (((1 - $row['price_set_eth'] / $jsoncoin['price_eth']) * 100) > $row['plus_percent'] ){ 
                // Echo
                echo($row['ID'] . " " . $row['coin'] . "plus_percent from_now compared to eth email sent \r\n");
                
                // Email
                $mail->addAddress($row['email']);
                
                $mail->Subject  = 'Alert: '. ucfirst($row['coin']) .' ('. ucfirst($row['symbol']) .') increased by '. $row['plus_percent'] .'%';
                
                $mail->Body = ''. ucfirst($row['coin']) .' ('. ucfirst($row['symbol']) .') price increased by '. $row['plus_percent'] .'% compared to ETH.

You can manage your alert(-s) with this unique id: '. $row['unique_id'] .' or with your account at https://coinwink.com

Wink,
Coinwink';

                // Send + success or error
                if(!$mail->Send()) {
                    echo 'Message could not be sent.';
                    echo 'Mailer Error: ' . $mail->ErrorInfo;
                    mail($adminaddress,"Coinwink error", $mail->ErrorInfo . ' Email: ' . $row['email']);
                    // exit;
                }
                echo "Message has been sent \r\n";

                $mail->ClearAllRecipients();

                // Update DB
                $ID = $row['ID'];
                $sqlbelow = "UPDATE coinwink_percent SET plus_sent=1 WHERE ID = $ID";
                $conn->query($sqlbelow);
                
                }
            }

            //
            // When minus_percent from_now compared to eth
            //
            if ($row['minus_change'] == 'from_now' && $row['minus_compared'] == 'ETH' && !$row['minus_sent'] && is_numeric($row['minus_percent']) && $row['price_set_eth'] > 0) {
                
                if (((1 - $row['price_set_eth'] / $jsoncoin['price_eth']) * 100) < (-1 * $row['minus_percent']) ){ 
                // Echo
                echo($row['ID'] . " " . $row['coin'] . "minus_percent from_now compared to eth email sent \r\n");
                
                // Email
                $mail->addAddress($row['email']);
                
                $mail->Subject  = 'Alert: '. ucfirst($row['coin']) .' ('. ucfirst($row['symbol']) .') decreased by '. $row['minus_percent'] .'%';
                
                $mail->Body = ''. ucfirst($row['coin']) .' ('. ucfirst($row['symbol']) .') price decreased by '. $row['minus_percent'] .'% compared to ETH.
    
You can manage your alert(-s) with this unique id: '. $row['unique_id'] .' or with your account at https://coinwink.com
    
Wink,
Coinwink';

                // Send + success or error
                if(!$mail->Send()) {
                    echo 'Message could not be sent.';
                    echo 'Mailer Error: ' . $mail->ErrorInfo;
                    mail($adminaddress,"Coinwink error", $mail->ErrorInfo . ' Email: ' . $row['email']);
                    // exit;
                }
                echo "Message has been sent \r\n";

                $mail->ClearAllRecipients();

                // Update DB
                $ID = $row['ID'];
                $sqlbelow = "UPDATE coinwink_percent SET minus_sent=1 WHERE ID = $ID";
                $conn->query($sqlbelow);
                
                }
            }

            //
            // When plus_percent from_now compared to usd
            //
            if ($row['plus_change'] == 'from_now' && $row['plus_compared'] == 'USD' && !$row['plus_sent'] && is_numeric($row['plus_percent']) && $row['price_set_usd'] > 0) {
                
                if (((1 - $row['price_set_usd'] / $jsoncoin['price_usd']) * 100) > $row['plus_percent'] ){ 
                // Echo
                echo($row['ID'] . " " . $row['coin'] . "plus_percent from_now compared to usd email sent \r\n");
                
                // Email
                $mail->addAddress($row['email']);
                
                $mail->Subject  = 'Alert: '. ucfirst($row['coin']) .' ('. ucfirst($row['symbol']) .') increased by '. $row['plus_percent'] .'%';
                
                $mail->Body = ''. ucfirst($row['coin']) .' ('. ucfirst($row['symbol']) .') price increased by '. $row['plus_percent'] .'% compared to USD.

You can manage your alert(-s) with this unique id: '. $row['unique_id'] .' or with your account at https://coinwink.com

Wink,
Coinwink';

                // Send + success or error
                if(!$mail->Send()) {
                    echo 'Message could not be sent.';
                    echo 'Mailer Error: ' . $mail->ErrorInfo;
                    mail($adminaddress,"Coinwink error", $mail->ErrorInfo . ' Email: ' . $row['email']);
                    // exit;
                }
                echo "Message has been sent \r\n";

                $mail->ClearAllRecipients();

                // Update DB
                $ID = $row['ID'];
                $sqlbelow = "UPDATE coinwink_percent SET plus_sent=1 WHERE ID = $ID";
                $conn->query($sqlbelow);
                
                }
            }

            //
            // When minus_percent from_now compared to usd
            //
            if ($row['minus_change'] == 'from_now' && $row['minus_compared'] == 'USD' && !$row['minus_sent'] && is_numeric($row['minus_percent']) && $row['price_set_usd'] > 0) {
                
                if (((1 - $row['price_set_usd'] / $jsoncoin['price_usd']) * 100) < (-1 * $row['minus_percent']) ){ 
                // Echo
                echo($row['ID'] . " " . $row['coin'] . "minus_percent from_now compared to usd email sent \r\n");
                
                // Email
                $mail->addAddress($row['email']);
                
                $mail->Subject  = 'Alert: '. ucfirst($row['coin']) .' ('. ucfirst($row['symbol']) .') decreased by '. $row['minus_percent'] .'%';
                
                $mail->Body = ''. ucfirst($row['coin']) .' ('. ucfirst($row['symbol']) .') price decreased by '. $row['minus_percent'] .'% compared to USD.
    
You can manage your alert(-s) with this unique id: '. $row['unique_id'] .' or with your account at https://coinwink.com
    
Wink,
Coinwink';

                // Send + success or error
                if(!$mail->Send()) {
                    echo 'Message could not be sent.';
                    echo 'Mailer Error: ' . $mail->ErrorInfo;
                    mail($adminaddress,"Coinwink error", $mail->ErrorInfo . ' Email: ' . $row['email']);
                    // exit;
                }
                echo "Message has been sent \r\n";

                $mail->ClearAllRecipients();

                // Update DB
                $ID = $row['ID'];
                $sqlbelow = "UPDATE coinwink_percent SET minus_sent=1 WHERE ID = $ID";
                $conn->query($sqlbelow);
                
                }
            }

            //
            //
            // 1h change loops
            //
            //

            //
            // When plus_percent 1h compared to usd
            //
            if ($row['plus_change'] == '1h' && !$row['plus_sent'] && is_numeric($row['plus_percent']) && $row['price_set_btc'] > 0) {
                
                if ( $jsoncoin['percent_change_1h'] > $row['plus_percent'] ){ 
                // Echo
                echo($row['ID'] . " " . $row['coin'] . "plus_percent 1h compared to usd email sent \r\n");
                
                // Email
                $mail->addAddress($row['email']);
                
                $mail->Subject  = 'Alert: '. ucfirst($row['coin']) .' ('. ucfirst($row['symbol']) .') increased by '. $row['plus_percent'] .'% in 1h. period';
                
                $mail->Body = ''. ucfirst($row['coin']) .' ('. ucfirst($row['symbol']) .') price increased by '. $row['plus_percent'] .'% in 1h. period compared to USD.

You can manage your alert(-s) with this unique id: '. $row['unique_id'] .' or with your account at https://coinwink.com

Wink,
Coinwink';

                // Send + success or error
                if(!$mail->Send()) {
                    echo 'Message could not be sent.';
                    echo 'Mailer Error: ' . $mail->ErrorInfo;
                    mail($adminaddress,"Coinwink error", $mail->ErrorInfo . ' Email: ' . $row['email']);
                    // exit;
                }
                echo "Message has been sent \r\n";

                $mail->ClearAllRecipients();

                // Update DB
                $ID = $row['ID'];
                $sqlbelow = "UPDATE coinwink_percent SET plus_sent=1 WHERE ID = $ID";
                $conn->query($sqlbelow);
                
                }
            }

            //
            // When minus_percent 1h compared to usd
            //
            if ($row['minus_change'] == '1h' && !$row['minus_sent'] && is_numeric($row['minus_percent']) && $row['price_set_btc'] > 0) {

                if ( (-1 * $jsoncoin['percent_change_1h']) > $row['minus_percent'] ){ 
                // Echo
                echo($row['ID'] . " " . $row['coin'] . "minus_percent 1h compared to usd email sent \r\n");
                
                // Email
                $mail->addAddress($row['email']);
                
                $mail->Subject  = 'Alert: '. ucfirst($row['coin']) .' ('. ucfirst($row['symbol']) .') decreased by '. $row['minus_percent'] .'% in 1h. period';
                
                $mail->Body = ''. ucfirst($row['coin']) .' ('. ucfirst($row['symbol']) .') price decreased by '. $row['minus_percent'] .'%  in 1h. period compared to USD.

You can manage your alert(-s) with this unique id: '. $row['unique_id'] .' or with your account at https://coinwink.com

Wink,
Coinwink';

                // Send + success or error
                if(!$mail->Send()) {
                    echo 'Message could not be sent.';
                    echo 'Mailer Error: ' . $mail->ErrorInfo;
                    mail($adminaddress,"Coinwink error", $mail->ErrorInfo . ' Email: ' . $row['email']);
                    // exit;
                }
                echo "Message has been sent \r\n";

                $mail->ClearAllRecipients();

                // Update DB
                $ID = $row['ID'];
                $sqlbelow = "UPDATE coinwink_percent SET minus_sent=1 WHERE ID = $ID";
                $conn->query($sqlbelow);
                
                }
            }

            //
            //
            // 24h change loops
            //
            //

            //
            // When plus_percent 24h compared to usd
            //
            if ($row['plus_change'] == '24h' && !$row['plus_sent'] && is_numeric($row['plus_percent']) && $row['price_set_btc'] > 0) {

                if ( $jsoncoin['percent_change_24h'] > $row['plus_percent'] ){ 
                // Echo
                echo($row['ID'] . " " . $row['coin'] . "plus_percent 24h compared to usd email sent \r\n");
                
                // Email
                $mail->addAddress($row['email']);
                
                $mail->Subject  = 'Alert: '. ucfirst($row['coin']) .' ('. ucfirst($row['symbol']) .') increased by '. $row['plus_percent'] .'% in 24h. period';
                
                $mail->Body = ''. ucfirst($row['coin']) .' ('. ucfirst($row['symbol']) .') price increased by '. $row['plus_percent'] .'% in 24h. period compared to USD.

You can manage your alert(-s) with this unique id: '. $row['unique_id'] .' or with your account at https://coinwink.com

Wink,
Coinwink';

                // Send + success or error
                if(!$mail->Send()) {
                    echo 'Message could not be sent.';
                    echo 'Mailer Error: ' . $mail->ErrorInfo;
                    mail($adminaddress,"Coinwink error", $mail->ErrorInfo . ' Email: ' . $row['email']);
                    // exit;
                }
                echo "Message has been sent \r\n";

                $mail->ClearAllRecipients();

                // Update DB
                $ID = $row['ID'];
                $sqlbelow = "UPDATE coinwink_percent SET plus_sent=1 WHERE ID = $ID";
                $conn->query($sqlbelow);
                
                }
            }

            //
            // When minus_percent 24h compared to usd
            //
            if ($row['minus_change'] == '24h' && !$row['minus_sent'] && is_numeric($row['minus_percent']) && $row['price_set_btc'] > 0) {

                if ( (-1 * $jsoncoin['percent_change_24h']) > $row['minus_percent'] ){ 
                // Echo
                echo($row['ID'] . " " . $row['coin'] . "minus_percent 24h compared to usd email sent \r\n");
                
                // Email
                $mail->addAddress($row['email']);
                
                $mail->Subject  = 'Alert: '. ucfirst($row['coin']) .' ('. ucfirst($row['symbol']) .') decreased by '. $row['minus_percent'] .'% in 24h. period';
                
                $mail->Body = ''. ucfirst($row['coin']) .' ('. ucfirst($row['symbol']) .') price decreased by '. $row['minus_percent'] .'% in 24h. period compared to USD.

You can manage your alert(-s) with this unique id: '. $row['unique_id'] .' or with your account at https://coinwink.com

Wink,
Coinwink';

                // Send + success or error
                if(!$mail->Send()) {
                    echo 'Message could not be sent.';
                    echo 'Mailer Error: ' . $mail->ErrorInfo;
                    mail($adminaddress,"Coinwink error", $mail->ErrorInfo . ' Email: ' . $row['email']);
                    // exit;
                }
                echo "Message has been sent \r\n";

                $mail->ClearAllRecipients();

                // Update DB
                $ID = $row['ID'];
                $sqlbelow = "UPDATE coinwink_percent SET minus_sent=1 WHERE ID = $ID";
                $conn->query($sqlbelow);
                
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
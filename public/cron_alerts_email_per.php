<?php

// Check execution time - Start time
$time_start = microtime(true);

// Check processing time - START
$rustart = getrusage();

// Increase default (256M) memory limit
// 800M = 500k alerts
ini_set('memory_limit', '512M');

// Increase allowed php script processing time to 30 min
set_time_limit(1800);

// Connect to Mysql
include_once "coinwink_auth_sql.php";
include_once "coinwink_auth_email.php";

// Kill switch
include_once "coinwink_auth_crons.php";
if (!$cron_alerts_email_per) { 
    echo('Kill switch active!');
    exit(); 
}

// Wait for new CMC data
sleep(7);

// Email template and sending
function sendEmail($unique_id, $email, $coin, $symbol, $currency, $change, $i_or_d, $coin_ID, $price_usd) {

    global $adminaddress;
    global $mail;
    global $conn;

    if($price_usd > 0.01) {
        $price_usd = number_format((float)$price_usd, 2, '.', '');
    }
    else if($price_usd > 0.001) {
        $price_usd = number_format((float)$price_usd, 3, '.', '');
    }
    else if($price_usd > 0.0001) {
        $price_usd = number_format((float)$price_usd, 4, '.', '');
    }
    else if($price_usd > 0.00001) {
        $price_usd = number_format((float)$price_usd, 5, '.', '');
    }
    else if($price_usd > 0.000001) {
        $price_usd = number_format((float)$price_usd, 6, '.', '');
    }
    else if($price_usd > 0.0000001) {
        $price_usd = number_format((float)$price_usd, 7, '.', '');
    }

    // Email
    $mail->addAddress($email);
                    
    $mail->Subject  = 'Alert: '. ucfirst($coin) .' ('. $symbol .') '. $i_or_d .' by '. $change .'%';

    $mail->Body = ''. ucfirst($coin) .' ('. $symbol .') price '. $i_or_d .' by '. $change .'% compared to '. $currency .'.

'. $symbol .' current price: '. $price_usd .' USD

You can manage your alert(-s) with your account at https://coinwink.com

Wink,
Coinwink';

    // Send + success or error
    if(!$mail->Send()) {
        echo 'Message could not be sent. ';
        echo 'Mailer Error: ' . $mail->ErrorInfo . "\r\n";

        // mail($adminaddress,"ERROR: cron_alerts_email_cur", $mail->ErrorInfo . ' Email: ' . $email);

        // EMAIL ERROR TO ADMIN
        $GLOBALS['mail']->addAddress($GLOBALS['adminaddress']);
        $GLOBALS['mail']->Subject  = "ERROR: cron_alerts_email_per Nr.1";
        $GLOBALS['mail']->Body = $mail->ErrorInfo . ' Email: ' . $email;
        $GLOBALS['mail']->Send();
        
        // Create db log
        $content = ''. ucfirst($coin) .' ('. ucfirst($symbol) .') price '. $i_or_d .' by '. $change .'% compared to '. $currency .'.';

        $alert_ID = time() . '' . join('', array_map(function($value) { return $value == 1 ? mt_rand(1, 9) : mt_rand(0, 9); }, range(1, 6)));
        
        $name = ucfirst($coin);
        $time = time();

        $sql = "INSERT INTO cw_logs_alerts_email (user_ID, alert_ID, coin_ID, name, content, type, symbol, destination, time, status, error) VALUES ('$unique_id', '$alert_ID', '$coin_ID', '$name', '$content', 'email_per', '$symbol', '$email', '$time', 'error', '$mail->ErrorInfo')";
        $conn->query($sql);
    }
    else {
        echo "Message has been sent \r\n";

        // Create db log
        $content = ''. ucfirst($coin) .' ('. ucfirst($symbol) .') price '. $i_or_d .' by '. $change .'% compared to '. $currency .'.';

        $alert_ID = time() . '' . join('', array_map(function($value) { return $value == 1 ? mt_rand(1, 9) : mt_rand(0, 9); }, range(1, 6)));

        $name = ucfirst($coin);
        $time = time();

        $sql = "INSERT INTO cw_logs_alerts_email (user_ID, alert_ID, coin_ID, name, content, type, symbol, destination, time, status) VALUES ('$unique_id', '$alert_ID', '$coin_ID', '$name', '$content', 'email_per', '$symbol', '$email', '$time', 'sent')";
        $conn->query($sql);
    }

    $mail->ClearAllRecipients();

}


// Email template and sending 1h. & 24h.
function sendEmail2($unique_id, $email, $coin, $symbol, $currency, $change, $i_or_d, $period, $coin_ID) {

    global $adminaddress;
    global $mail;
    global $conn;
    
    // Email
    $mail->addAddress($email);
                    
    $mail->Subject  = 'Alert: '. ucfirst($coin) .' ('. ucfirst($symbol) .') '. $i_or_d .' by '. $change .'% in '. $period .' period';

    $mail->Body = ''. ucfirst($coin) .' ('. ucfirst($symbol) .') price '. $i_or_d .' by '. $change .'% in '. $period .' period compared to '. $currency .'.

You can manage your alert(-s) with your account at https://coinwink.com

Wink,
Coinwink';

    // Send + success or error
    if(!$mail->Send()) {
        echo 'Message could not be sent. ';
        echo 'Mailer Error: ' . $mail->ErrorInfo . "\r\n";

        // EMAIL ERROR TO ADMIN
        $GLOBALS['mail']->addAddress($GLOBALS['adminaddress']);
        $GLOBALS['mail']->Subject  = "ERROR: cron_alerts_email_per Nr.2";
        $GLOBALS['mail']->Body = $mail->ErrorInfo . ' Email: ' . $email;
        $GLOBALS['mail']->Send();
        
        // Create db log
        $content = ''. ucfirst($coin) .' ('. ucfirst($symbol) .') price '. $i_or_d .' by '. $change .'% in '. $period .' period, compared to '. $currency .'.';

        $alert_ID = time() . '' . join('', array_map(function($value) { return $value == 1 ? mt_rand(1, 9) : mt_rand(0, 9); }, range(1, 6)));
        
        $name = ucfirst($coin);
        $time = time();

        $sql = "INSERT INTO cw_logs_alerts_email (user_ID, alert_ID, coin_ID, name, content, type, symbol, destination, time, status, error) VALUES ('$unique_id', '$alert_ID', '$coin_ID', '$name', '$content', 'email_per', '$symbol', '$email', '$time', 'error', '$mail->ErrorInfo')";
        $conn->query($sql);
    }
    else {
        echo "Message has been sent \r\n";

        // Create db log
        $content = ''. ucfirst($coin) .' ('. ucfirst($symbol) .') price '. $i_or_d .' by '. $change .'% in '. $period .' period, compared to '. $currency .'.';

        $alert_ID = time() . '' . join('', array_map(function($value) { return $value == 1 ? mt_rand(1, 9) : mt_rand(0, 9); }, range(1, 6)));

        $name = ucfirst($coin);
        $time = time();

        $sql = "INSERT INTO cw_logs_alerts_email (user_ID, alert_ID, coin_ID, name, content, type, symbol, destination, time, status) VALUES ('$unique_id', '$alert_ID', '$coin_ID', '$name', '$content', 'email_per', '$symbol', '$email', '$time', 'sent')";
        $conn->query($sql);
    }

    $mail->ClearAllRecipients();

}


// Select coin price data from db - 1st part
$sql = "SELECT json FROM cw_data_cmc WHERE ID = 1";
$result = $conn->query($sql);
foreach($result as $row)
{
    // $dataCMC = unserialize($row["json"]);
    $dataCMC = json_decode($row["json"], TRUE);
}


unset($result);




// Select alerts data from coinwink db
$sql = "SELECT * FROM cw_alerts_email_per";
$dbalerts = $conn->query($sql);




// Alerts array builder
function buildAlertsArray() {
    global $dbalerts;

    foreach($dbalerts as $row)
    {

        $alerts[$row["coin_id"]][] = $row;

    }

    processAlerts($alerts);

}
buildAlertsArray();




//
// PROCESS ALERTS
//
function processAlerts($alerts) {

    global $dataCMC;
    global $conn;
    global $mail_queue;
    global $mail_queue2;
    static $i = 0;
    static $i2 = 0;


    // PROCESS BTC + USD
    foreach ($dataCMC as $jsoncoin) {
        
        if (isset($alerts[$jsoncoin["id"]])) {
            
            foreach ($alerts[$jsoncoin["id"]] as $row) {

                //
                // When plus_percent from_now compared to btc
                //
                if ($row['plus_change'] == 'from_now' && $row['plus_compared'] == 'BTC' && !$row['plus_sent'] && is_numeric($row['plus_percent']) && $row['price_set_btc'] > 0) {
                    
                    if (((1 - $row['price_set_btc'] / $jsoncoin['price_btc']) * 100) > $row['plus_percent'] ){ 
                    
                        // Echo
                        echo($row['ID'] . " " . $row['coin'] . " plus_percent from_now compared to btc email sent \r\n");
                    
                        // Email
                        sendEmail($row['unique_id'], $row['email'], $row['coin'], $row['symbol'], 'BTC', $row['plus_percent'], 'increased', $row['ID']);

                        // Update DB
                        $ID = $row['ID'];
                        $sqlbelow = "UPDATE cw_alerts_email_per SET plus_sent=1 WHERE ID = $ID";
                        $conn->query($sqlbelow);
                    
                    }

                }

                //
                // When minus_percent from_now compared to btc
                //
                if ($row['minus_change'] == 'from_now' && $row['minus_compared'] == 'BTC' && !$row['minus_sent'] && is_numeric($row['minus_percent']) && $row['price_set_btc'] > 0) {

                    if (((1 - $row['price_set_btc'] / $jsoncoin['price_btc']) * 100) < (-1 * $row['minus_percent']) ){ 
                        
                        // Echo
                        echo($row['ID'] . " " . $row['coin'] . " minus_percent from_now compared to btc email sent \r\n");

                        // Email
                        sendEmail($row['unique_id'], $row['email'], $row['coin'], $row['symbol'], 'BTC', $row['minus_percent'], 'decreased', $row['ID']);

                        // Update DB
                        $ID = $row['ID'];
                        $sqlbelow = "UPDATE cw_alerts_email_per SET minus_sent=1 WHERE ID = $ID";
                        $conn->query($sqlbelow);

                    }

                }

                //
                // When plus_percent from_now compared to usd
                //
                if ($row['plus_change'] == 'from_now' && $row['plus_compared'] == 'USD' && !$row['plus_sent'] && is_numeric($row['plus_percent']) && $row['price_set_usd'] > 0) {
                            
                    if (((1 - $row['price_set_usd'] / $jsoncoin['price_usd']) * 100) > $row['plus_percent'] ){ 
                    
                        // Echo
                        echo($row['ID'] . " " . $row['coin'] . " plus_percent from_now compared to usd email sent \r\n");

                        // Email
                        sendEmail($row['unique_id'], $row['email'], $row['coin'], $row['symbol'], 'USD', $row['plus_percent'], 'increased', $row['ID'], $jsoncoin['price_usd']);

                        // Update DB
                        $ID = $row['ID'];
                        $sqlbelow = "UPDATE cw_alerts_email_per SET plus_sent=1 WHERE ID = $ID";
                        $conn->query($sqlbelow);

                    }

                }

                //
                // When minus_percent from_now compared to usd
                //
                if ($row['minus_change'] == 'from_now' && $row['minus_compared'] == 'USD' && !$row['minus_sent'] && is_numeric($row['minus_percent']) && $row['price_set_usd'] > 0) {
                                
                    if (((1 - $row['price_set_usd'] / $jsoncoin['price_usd']) * 100) < (-1 * $row['minus_percent']) ){ 

                        // Echo
                        echo($row['ID'] . " " . $row['coin'] . " minus_percent from_now compared to usd email sent \r\n");

                        // Email
                        sendEmail($row['unique_id'], $row['email'], $row['coin'], $row['symbol'], 'USD', $row['minus_percent'], 'decreased', $row['ID'], $jsoncoin['price_usd']);

                        // Update DB
                        $ID = $row['ID'];
                        $sqlbelow = "UPDATE cw_alerts_email_per SET minus_sent=1 WHERE ID = $ID";
                        $conn->query($sqlbelow);

                    }

                }

                //
                // When plus_percent from_now compared to eth
                //
                if ($row['plus_change'] == 'from_now' && $row['plus_compared'] == 'ETH' && !$row['plus_sent'] && is_numeric($row['plus_percent']) && $row['price_set_eth'] > 0) {
                    
                    if (((1 - $row['price_set_eth'] / $jsoncoin['price_eth']) * 100) > $row['plus_percent'] ){ 
                   
                        // Echo
                        echo($row['ID'] . " " . $row['coin'] . " plus_percent from_now compared to eth email sent \r\n");

                        // Email
                        sendEmail($row['unique_id'], $row['email'], $row['coin'], $row['symbol'], 'ETH', $row['plus_percent'], 'increased', $row['ID'], $jsoncoin['price_usd']);

                        // Update DB
                        $ID = $row['ID'];
                        $sqlbelow = "UPDATE cw_alerts_email_per SET plus_sent=1 WHERE ID = $ID";
                        $conn->query($sqlbelow);

                    }
                
                }

                //
                // When minus_percent from_now compared to eth
                //
                if ($row['minus_change'] == 'from_now' && $row['minus_compared'] == 'ETH' && !$row['minus_sent'] && is_numeric($row['minus_percent']) && $row['price_set_eth'] > 0) {
                    
                    if (((1 - $row['price_set_eth'] / $jsoncoin['price_eth']) * 100) < (-1 * $row['minus_percent']) ){ 
                    
                        // Echo
                        echo($row['ID'] . " " . $row['coin'] . " minus_percent from_now compared to eth email sent \r\n");

                        // Email
                        sendEmail($row['unique_id'], $row['email'], $row['coin'], $row['symbol'], 'ETH', $row['minus_percent'], 'decreased', $row['ID'], $jsoncoin['price_usd']);

                        // Update DB
                        $ID = $row['ID'];
                        $sqlbelow = "UPDATE cw_alerts_email_per SET minus_sent=1 WHERE ID = $ID";
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
                    
                    if ( $jsoncoin['per_1h'] > $row['plus_percent'] ){ 
                 
                        // Echo
                        echo($row['ID'] . " " . $row['coin'] . " plus_percent 1h compared to usd email sent \r\n");
                        
                        // Email
                        sendEmail2($row['unique_id'], $row['email'], $row['coin'], $row['symbol'], 'USD', $row['plus_percent'], 'increased', '1h.', $row['coin_id']);

                        // Update DB
                        $ID = $row['ID'];
                        $sqlbelow = "UPDATE cw_alerts_email_per SET plus_sent=1 WHERE ID = $ID";
                        $conn->query($sqlbelow);
                    
                    }

                }

                //
                // When minus_percent 1h compared to usd
                //
                if ($row['minus_change'] == '1h' && !$row['minus_sent'] && is_numeric($row['minus_percent']) && $row['price_set_btc'] > 0) {
                    
                    if ( (-1 * $jsoncoin['per_1h']) > $row['minus_percent'] ){ 
                   
                        // Echo
                        echo($row['ID'] . " " . $row['coin'] . " minus_percent 1h compared to usd email sent \r\n");
                        
                        // Email
                        sendEmail2($row['unique_id'], $row['email'], $row['coin'], $row['symbol'], 'USD', $row['minus_percent'], 'decreased', '1h.', $row['coin_id']);

                        // Update DB
                        $ID = $row['ID'];
                        $sqlbelow = "UPDATE cw_alerts_email_per SET minus_sent=1 WHERE ID = $ID";
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
                    
                    if ( $jsoncoin['per_24h'] > $row['plus_percent'] ){ 
                    
                        // Echo
                        echo($row['ID'] . " " . $row['coin'] . " plus_percent 24h compared to usd email sent \r\n");
                        
                        // Email
                        sendEmail2($row['unique_id'], $row['email'], $row['coin'], $row['symbol'], 'USD', $row['plus_percent'], 'increased', '24h.', $row['coin_id']);

                        // Update DB
                        $ID = $row['ID'];
                        $sqlbelow = "UPDATE cw_alerts_email_per SET plus_sent=1 WHERE ID = $ID";
                        $conn->query($sqlbelow);
                    
                    }

                }

                //
                // When minus_percent 24h compared to usd
                //
                if ($row['minus_change'] == '24h' && !$row['minus_sent'] && is_numeric($row['minus_percent']) && $row['price_set_btc'] > 0) {
                    
                    if ( (-1 * $jsoncoin['per_24h']) > $row['minus_percent'] ){ 
                    
                        // Echo
                        echo($row['ID'] . " " . $row['coin'] . " minus_percent 24h compared to usd email sent \r\n");
                        
                        // Email
                        sendEmail2($row['unique_id'], $row['email'], $row['coin'], $row['symbol'], 'USD', $row['minus_percent'], 'decreased', '24h.', $row['coin_id']);

                        // Update DB
                        $ID = $row['ID'];
                        $sqlbelow = "UPDATE cw_alerts_email_per SET minus_sent=1 WHERE ID = $ID";
                        $conn->query($sqlbelow);
                    
                    }

                }

            }
        
        }
    
    }

}




///
/// Done! Some stats below
///


// Check memory used in megabytes
function convert($size)
{
    $unit=array('b','kb','mb','gb','tb','pb');
    return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
}
echo ("\r\nMemory used: " . convert(memory_get_usage(true)));


// End time
$time_end = microtime(true);

// Total time
$execution_time = ($time_end - $time_start);
echo ("\r\nExecution time: " . $execution_time . " sec");


// Check processing time - END
function rutime($ru, $rus, $index) {
    return ($ru["ru_$index.tv_sec"]*1000 + intval($ru["ru_$index.tv_usec"]/1000))
     -  ($rus["ru_$index.tv_sec"]*1000 + intval($rus["ru_$index.tv_usec"]/1000));
}

$ru = getrusage();
echo "\r\n\nThis process used " . rutime($ru, $rustart, "utime") .
    " ms for its computations\r\n";
echo "It spent " . rutime($ru, $rustart, "stime") .
    " ms in system calls\r\n";


?>
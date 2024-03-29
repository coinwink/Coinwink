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
if (!$cron_alerts_sms_per) { 
    echo('Kill switch active!');
    exit(); 
}

// Wait for new CMC data
sleep(7);

// Load Twilio
include_once 'coinwink_auth_sms.php';
include_once 'lib/php/twilio/autoload.php'; // Loads the library 
use Twilio\Rest\Client;
$client = new Client($account_sid, $auth_token);



// SMS template and sending
function sendSMS($coin_ID, $user_ID, $phone, $coin, $symbol, $currency, $change, $i_or_d, $period) {
    
    global $conn;
    global $client;

    # SMS destination number
    $dst = $phone;

    # SMS text
    if (isset($period["period"])) {
        $text = 'Alert: '. ucfirst($coin) .' ('. ucfirst($symbol) .') '. $i_or_d .' by '. $change .'% in '. $period["period"] .' period - coinwink.com';
        $text_log = ucfirst($coin) .' ('. ucfirst($symbol) .') '. $i_or_d .' by '. $change .'% in '. $period["period"] .' period.';
    }
    else {
        $text = 'Alert: '. ucfirst($coin) .' ('. ucfirst($symbol) .') '. $i_or_d .' by '. $change .'% compared to '. $currency .' - coinwink.com';
        $text_log = ucfirst($coin) .' ('. ucfirst($symbol) .') '. $i_or_d .' by '. $change .'% compared to '. $currency .'.';
    }

    // Get user SMS settings
    $sql = "SELECT * FROM cw_settings WHERE user_ID = '".$user_ID."'";
    $settings = mysqli_fetch_assoc($conn->query($sql));

    $sms = $settings['sms'];
    $subs = $settings['subs'];

    global $to_nrs;
    global $from_nr;
    global $from_nr_2;
    $from_nr_end = substr($dst, -4);
    if (in_array($from_nr_end, $to_nrs)) {
        $from_nr = $from_nr_2;
    }

    // 1. Start with paid
    if ($sms > 0 && $subs == 1) {

        $status = 'sent';

        // Twilio paid
        try {
            $messages = $client->messages->create($dst, array( 
                'From' => $from_nr,  
                'Body' => $text,      
            ));
        } catch (Exception $e) {
            echo ("Error\n");
            $status = 'error';

            // EMAIL ERROR TO ADMIN
            $GLOBALS['mail']->addAddress($GLOBALS['adminaddress']);
            $GLOBALS['mail']->Subject  = "ERROR: cron_alerts_sms_per";
            $GLOBALS['mail']->Body = "Catched exception: " . $e->getMessage();
            $GLOBALS['mail']->Send();
            $GLOBALS['mail']->ClearAllRecipients();


            // Get the user's email address
            $sql = "SELECT email FROM users WHERE id='$user_ID' limit 1";
            $result = $conn->query($sql);
            $user_email = mysqli_fetch_object($result);
            $user_email = $user_email->email;

            // Email to the user
            $GLOBALS['mail']->addAddress($user_email);
            $GLOBALS['mail']->Subject  = "Undelivered SMS alert";
            $GLOBALS['mail']->Body = "Hello,
            
Your Coinwink SMS alert was triggered, but we couldn't deliver it because of the incorrect phone number formatting. Phone numbers should be formatted in the international standard, usually starting with the '+' sign.

Please refer to this article for more info: https://coinwink.com/blog/how-to-start-receiving-sms-crypto-alerts

Regards,
Coinwink";
            $GLOBALS['mail']->Send();
            $GLOBALS['mail']->ClearAllRecipients();

        }

        // -1 SMS
        $sms = $sms - 1;
        $sql = "UPDATE cw_settings SET sms = $sms WHERE user_ID = $user_ID";
        $conn->query($sql);

        // Create db log
        $content = $text_log;

        $alert_ID = time() . '' . join('', array_map(function($value) { return $value == 1 ? mt_rand(1, 9) : mt_rand(0, 9); }, range(1, 6)));
        
        $name = ucfirst($coin);
        $time = time();

        $sql = "INSERT INTO cw_logs_alerts_sms (user_ID, alert_ID, coin_ID, name, symbol, content, type, destination, status, time) VALUES ('$user_ID', '$alert_ID', '$coin_ID', '$name', '$symbol', '$content', 'sms_per', '$dst', '$status', '$time')";
        $conn->query($sql);
    }

    // 2. If paid does not exist, inform the user
    else {

        // Create db log
        $content = $text_log;

        $alert_ID = time() . '' . join('', array_map(function($value) { return $value == 1 ? mt_rand(1, 9) : mt_rand(0, 9); }, range(1, 6)));
        
        $name = ucfirst($coin);
        $time = time();

        $sql = "INSERT INTO cw_logs_alerts_sms (user_ID, alert_ID, coin_ID, name, symbol, content, type, destination, status, error, time) VALUES ('$user_ID', '$alert_ID', '$coin_ID', '$name', '$symbol', '$content', 'sms_per', '$dst', 'failed', 'No subs or credits', '$time')";
        $conn->query($sql);
        
        // get the user email address
        $sql = "SELECT email FROM users WHERE id='$user_ID' limit 1";
        $result = $conn->query($sql);
        $user_email = mysqli_fetch_object($result);
        $user_email = $user_email->email;

        // Email to the user
        $GLOBALS['mail']->addAddress($user_email);
        $GLOBALS['mail']->Subject  = "Undelivered SMS alert";
        $GLOBALS['mail']->Body = "Hello,
        
Your Coinwink SMS alert was triggered, but we couldn't deliver it because either your subscription has expired, or your account has no more monthly SMS credits left.

You can manage your subscription or buy additional monthly SMS credits at https://coinwink.com

Kind regards,
Coinwink";
        $GLOBALS['mail']->Send();
        $GLOBALS['mail']->ClearAllRecipients();

    }
    
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
$sql = "SELECT * FROM cw_alerts_sms_per";
$dbalerts = $conn->query($sql);




// Alerts array builder
function buildAlertsArray() {
    global $dbalerts;

    foreach($dbalerts as $row)
    {

        $alerts[$row["coin_id"]][] = $row;

    }

    processAlerts($alerts, false);

}
buildAlertsArray();




//
// PROCESS ALERTS
//
function processAlerts($alerts, $loop) {

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
                        sendSMS($jsoncoin['id'], $row['user_ID'], $row['phone'], $row['coin'], $row['symbol'], 'BTC', $row['plus_percent'], 'increased', array());

                        // Update DB
                        $ID = $row['ID'];
                        $sqlbelow = "UPDATE cw_alerts_sms_per SET plus_sent=1 WHERE ID = $ID";
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
                        sendSMS($jsoncoin['id'], $row['user_ID'], $row['phone'], $row['coin'], $row['symbol'], 'BTC', $row['minus_percent'], 'decreased', array());

                        // Update DB
                        $ID = $row['ID'];
                        $sqlbelow = "UPDATE cw_alerts_sms_per SET minus_sent=1 WHERE ID = $ID";
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
                        sendSMS($jsoncoin['id'], $row['user_ID'], $row['phone'], $row['coin'], $row['symbol'], 'USD', $row['plus_percent'], 'increased', array());

                        // Update DB
                        $ID = $row['ID'];
                        $sqlbelow = "UPDATE cw_alerts_sms_per SET plus_sent=1 WHERE ID = $ID";
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
                        sendSMS($jsoncoin['id'], $row['user_ID'], $row['phone'], $row['coin'], $row['symbol'], 'USD', $row['minus_percent'], 'decreased', array());

                        // Update DB
                        $ID = $row['ID'];
                        $sqlbelow = "UPDATE cw_alerts_sms_per SET minus_sent=1 WHERE ID = $ID";
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
                        sendSMS($jsoncoin['id'], $row['user_ID'], $row['phone'], $row['coin'], $row['symbol'], 'ETH', $row['plus_percent'], 'increased', array());

                        // Update DB
                        $ID = $row['ID'];
                        $sqlbelow = "UPDATE cw_alerts_sms_per SET plus_sent=1 WHERE ID = $ID";
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
                        sendSMS($jsoncoin['id'], $row['user_ID'], $row['phone'], $row['coin'], $row['symbol'], 'ETH', $row['minus_percent'], 'decreased', array());

                        // Update DB
                        $ID = $row['ID'];
                        $sqlbelow = "UPDATE cw_alerts_sms_per SET minus_sent=1 WHERE ID = $ID";
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
                        sendSMS($jsoncoin['id'], $row['user_ID'], $row['phone'], $row['coin'], $row['symbol'], 'USD', $row['plus_percent'], 'increased', array('period' => '1h.'));

                        // Update DB
                        $ID = $row['ID'];
                        $sqlbelow = "UPDATE cw_alerts_sms_per SET plus_sent=1 WHERE ID = $ID";
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
                        sendSMS($jsoncoin['id'], $row['user_ID'], $row['phone'], $row['coin'], $row['symbol'], 'USD', $row['minus_percent'], 'decreased', array('period' => '1h.'));

                        // Update DB
                        $ID = $row['ID'];
                        $sqlbelow = "UPDATE cw_alerts_sms_per SET minus_sent=1 WHERE ID = $ID";
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
                        sendSMS($jsoncoin['id'], $row['user_ID'], $row['phone'], $row['coin'], $row['symbol'], 'USD', $row['plus_percent'], 'increased', array('period' => '24h.'));

                        // Update DB
                        $ID = $row['ID'];
                        $sqlbelow = "UPDATE cw_alerts_sms_per SET plus_sent=1 WHERE ID = $ID";
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
                        sendSMS($jsoncoin['id'], $row['user_ID'], $row['phone'], $row['coin'], $row['symbol'], 'USD', $row['minus_percent'], 'decreased', array('period' => '24h.'));

                        // Update DB
                        $ID = $row['ID'];
                        $sqlbelow = "UPDATE cw_alerts_sms_per SET minus_sent=1 WHERE ID = $ID";
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
    - ($rus["ru_$index.tv_sec"]*1000 + intval($rus["ru_$index.tv_usec"]/1000));
}
$ru = getrusage();
echo "\r\n\nThis process used " . rutime($ru, $rustart, "utime") . " ms for its computations\r\n";
echo "It spent " . rutime($ru, $rustart, "stime") . " ms in system calls\r\n";


?>
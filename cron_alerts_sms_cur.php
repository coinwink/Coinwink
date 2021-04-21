<?php

// Check execution time - Start time
$time_start = microtime(true);

// Check processing time - START
$rustart = getrusage();

// Increase default (256M) memory limit
// 800M = 500k alerts
ini_set('memory_limit', '256M');

// Increase allowed php script processing time to 30 min
set_time_limit(1800);


// Connect to Mysql
require_once "auth_sql.php";
require_once "auth_email.php";
include_once 'auth_twilio.php';


// Load Twilio
include_once 'lib/twilio/vendor/autoload.php'; // Loads the library 
use Twilio\Rest\Client;
$client = new Client($account_sid, $auth_token);



// SMS template and sending
function sendSMS($coin_ID, $user_ID, $phone, $coin, $symbol, $amount, $currency, $b_or_a) {

    global $conn;
    global $client;

    # SMS destination number
    $dst = $phone;

    # SMS text
    $text = 'Alert: '. ucfirst($coin) .' ('. ucfirst($symbol) .') is '. $b_or_a .' '. $amount .' '. $currency .' - coinwink.com';

    // Get user SMS settings
    $sql = "SELECT * FROM cw_settings WHERE user_ID = '".$user_ID."'";
    $settings = mysqli_fetch_assoc($conn->query($sql));

    $sms = $settings['sms'];
    $subs = $settings['subs'];

    // 1. Start with paid
    if ($sms > 0 && $subs == 1) {

        $status = 'sent';

        // Twilio paid
        try {
            $messages = $client->messages->create($dst, array( 
                'From' => "+16506677900",  
                'Body' => $text,      
            ));
        } catch (Exception $e) {
            
            echo ("Error\n");

            // EMAIL ERROR TO ADMIN
            $GLOBALS['mail']->addAddress($GLOBALS['adminaddress']);
            $GLOBALS['mail']->Subject  = "ERROR: cron_alerts_sms_cur";
            $GLOBALS['mail']->Body = "Catched exception: " . $e->getMessage();
            $GLOBALS['mail']->Send();
            $GLOBALS['mail']->ClearAllRecipients();

            $status = 'error';
        }

        // -1 paid SMS
        $sms = $sms - 1;
        $sql = "UPDATE cw_settings SET sms = $sms WHERE user_ID = $user_ID";
        $conn->query($sql);

        // Create db log
        $content = ucfirst($coin) .' ('. ucfirst($symbol) .') is '. $b_or_a .' '. $amount .' '. $currency .'.';
        $alert_ID = time() . '' . join('', array_map(function($value) { return $value == 1 ? mt_rand(1, 9) : mt_rand(0, 9); }, range(1, 6)));
        $name = ucfirst($coin);
        $time = time();
        $sql = "INSERT INTO cw_logs_alerts_sms (user_ID, alert_ID, coin_ID, name, symbol, content, type, destination, status, time) VALUES ('$user_ID', '$alert_ID', '$coin_ID', '$name', '$symbol', '$content', 'sms_cur', '$dst', '$status', '$time')";
        $conn->query($sql);
    }

    // 2. If paid does not exist, inform the user
    else {

        // Create db log
        $content = ucfirst($coin) .' ('. ucfirst($symbol) .') is '. $b_or_a .' '. $amount .' '. $currency .'.';
        $alert_ID = time() . '' . join('', array_map(function($value) { return $value == 1 ? mt_rand(1, 9) : mt_rand(0, 9); }, range(1, 6)));
        $name = ucfirst($coin);
        $time = time();
        $sql = "INSERT INTO cw_logs_alerts_sms (user_ID, alert_ID, coin_ID, name, symbol, content, type, destination, status, error, time) VALUES ('$user_ID', '$alert_ID', '$coin_ID', '$name', '$symbol', '$content', 'sms_cur', '$dst', 'failed', 'No subs or credits', '$time')";
        $conn->query($sql);
        
        // get the user email address
        $sql = "SELECT user_email FROM wp_users WHERE ID='$user_ID' limit 1";
        $result = $conn->query($sql);
        $user_email = mysqli_fetch_object($result);
        $user_email = $user_email->user_email;

        // Email to the user
        $GLOBALS['mail']->addAddress($user_email);
        $GLOBALS['mail']->Subject  = "Undelivered SMS alert";
        $GLOBALS['mail']->Body = "Hello,
        
Your Coinwink SMS alert was triggered, but we couldn't deliver it because your subscription has expired.

You can create a new subscription at https://coinwink.com

Regards,
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
$sql = "SELECT * FROM cw_alerts_sms_cur";
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
            
            foreach ($alerts[$jsoncoin["id"]] as $alert) {
  
                // BELOW BTC

                if ($alert['below_currency'] == 'BTC') {

                    if ($jsoncoin['price_btc'] < $alert['below'] && !$alert['below_sent'] && is_numeric($alert['below'])){ 
                    
                        echo($alert['ID'] . $alert['coin'] . "BTC below SMS sent \r\n");

                        // Send SMS alerts
                        sendSMS($jsoncoin['id'], $alert['user_ID'], $alert['phone'], $alert['coin'], $alert['symbol'], $alert['below'], $alert['below_currency'], 'below');

                        // Update DB
                        $ID = $alert['ID'];
                        $sqlbelow = "UPDATE cw_alerts_sms_cur SET below_sent=1 WHERE ID = $ID";
                        $conn->query($sqlbelow);

                    }
                }

                // BELOW USD

                if ($alert['below_currency'] == 'USD') {

                    if ($jsoncoin['price_usd'] < $alert['below'] && !$alert['below_sent'] && is_numeric($alert['below'])) { 

                        echo($alert['ID'] . $alert['coin'] . "USD BELOW SMS sent \r\n");

                        // Send SMS alerts
                        sendSMS($jsoncoin['id'], $alert['user_ID'], $alert['phone'], $alert['coin'], $alert['symbol'], $alert['below'], $alert['below_currency'], 'below');
                        
                        // Update DB
                        $ID = $alert['ID'];
                        $sqlbelow = "UPDATE cw_alerts_sms_cur SET below_sent=1 WHERE ID = $ID";
                        $conn->query($sqlbelow);
                    
                    }
                }

                // BELOW ETH

                if ($alert['below_currency'] == 'ETH') {

                    if ($jsoncoin['price_eth'] < $alert['below'] && !$alert['below_sent'] && is_numeric($alert['below'])){ 
                    
                        echo($alert['ID'] . $alert['coin'] . "ETH below SMS sent \r\n");

                        // Send SMS alerts
                        sendSMS($jsoncoin['id'], $alert['user_ID'], $alert['phone'], $alert['coin'], $alert['symbol'], $alert['below'], $alert['below_currency'], 'below');

                        // Update DB
                        $ID = $alert['ID'];
                        $sqlbelow = "UPDATE cw_alerts_sms_cur SET below_sent=1 WHERE ID = $ID";
                        $conn->query($sqlbelow);

                    }
                }

                // ABOVE USD

                if ($alert['above_currency'] == 'USD') {

                    if ($jsoncoin['price_usd'] > $alert['above'] && !$alert['above_sent'] && is_numeric($alert['above']) ) { 

                        echo($alert['ID'] . $alert['coin'] . "USD ABOVE SMS sent \r\n");  
                    
                        // Send SMS alerts
                        sendSMS($jsoncoin['id'], $alert['user_ID'], $alert['phone'], $alert['coin'], $alert['symbol'], $alert['above'], $alert['above_currency'], 'above');

                        // Update DB
                        $ID = $alert['ID'];
                        $sqlabove = "UPDATE cw_alerts_sms_cur SET above_sent=1 WHERE ID = $ID";
                        $conn->query($sqlabove);

                    }
                }                

                // ABOVE BTC

                if ($alert['above_currency'] == 'BTC') {

                    if ($jsoncoin['price_btc'] > $alert['above'] && !$alert['above_sent'] && is_numeric($alert['above'])) { 
                    
                        echo($alert['ID'] . $alert['coin'] . "BTC ABOVE SMS sent \r\n");  
                        
                        // Send SMS alerts
                        sendSMS($jsoncoin['id'], $alert['user_ID'], $alert['phone'], $alert['coin'], $alert['symbol'], $alert['above'], $alert['above_currency'], 'above');

                        // Update DB
                        $ID = $alert['ID'];
                        $sqlabove = "UPDATE cw_alerts_sms_cur SET above_sent=1 WHERE ID = $ID";
                        $conn->query($sqlabove);
                
                    }

                }   
                
                // ABOVE ETH

                if ($alert['above_currency'] == 'ETH') {

                    if ($jsoncoin['price_eth'] > $alert['above'] && !$alert['above_sent'] && is_numeric($alert['above'])) { 
                    
                        echo($alert['ID'] . $alert['coin'] . "ETH ABOVE SMS sent \r\n");  
                        
                        // Send SMS alerts
                        sendSMS($jsoncoin['id'], $alert['user_ID'], $alert['phone'], $alert['coin'], $alert['symbol'], $alert['above'], $alert['above_currency'], 'above');

                        // Update DB
                        $ID = $alert['ID'];
                        $sqlabove = "UPDATE cw_alerts_sms_cur SET above_sent=1 WHERE ID = $ID";
                        $conn->query($sqlabove);
                
                    }

                } 
            
            }

        }

    }


    //             //
    // SECOND PART //
    //             //

    $sql = "SELECT * FROM cw_data_cur_rates";
    $result = $conn->query($sql);
    $result = mysqli_fetch_array($result);
    // var_dump($result);

    $rate_eur = $result['EUR'];
    $rate_gbp = $result['GBP'];
    $rate_cad = $result['CAD'];
    $rate_aud = $result['AUD'];
    $rate_brl = $result['BRL'];
    $rate_mxn = $result['MXN'];
    $rate_jpy = $result['JPY'];
    $rate_sgd = $result['SGD'];


    // PROCESS EUR
    foreach ($dataCMC as $jsoncoin) {

        if (isset($alerts[$jsoncoin["id"]])) {
            
            foreach ($alerts[$jsoncoin["id"]] as $alert) {

                
                $price = $jsoncoin['price_usd'] * $rate_eur;

                // BELOW EUR
                if ($alert['below_currency'] == 'EUR') {
                    
                    if ($price < $alert['below'] && !$alert['below_sent'] && is_numeric($alert['below'])) { 
                
                        echo($alert['ID'] . $alert['coin'] . "EUR below SMS sent \r\n");
                            
                        // Send SMS alerts
                        sendSMS($jsoncoin['id'], $alert['user_ID'], $alert['phone'], $alert['coin'], $alert['symbol'], $alert['below'], $alert['below_currency'], 'below');

                        // Update DB
                        $ID = $alert['ID'];
                        $sqlbelow = "UPDATE cw_alerts_sms_cur SET below_sent=1 WHERE ID = $ID";
                        $conn->query($sqlbelow);

                    }
                }

                // ABOVE EUR
                if ($alert['above_currency'] == 'EUR') {

                    if ($price > $alert['above'] && !$alert['above_sent'] && is_numeric($alert['above'])) { 
                
                        echo($alert['ID'] . $alert['coin'] . "EUR ABOVE SMS sent \r\n");

                        // Send SMS alerts
                        sendSMS($jsoncoin['id'], $alert['user_ID'], $alert['phone'], $alert['coin'], $alert['symbol'], $alert['above'], $alert['above_currency'], 'above');
                        
                        // Update DB
                        $ID = $alert['ID'];
                        $sqlabove = "UPDATE cw_alerts_sms_cur SET above_sent=1 WHERE ID = $ID";
                        $conn->query($sqlabove);

                    }
                }


                $price = $jsoncoin['price_usd'] * $rate_gbp;
            
                // BELOW GBP
                if ($alert['below_currency'] == 'GBP') {
                    if ($price < $alert['below'] && !$alert['below_sent'] && is_numeric($alert['below'])){ 
                
                        echo($alert['ID'] . $alert['coin'] . "GBP below SMS sent \r\n");                    
                        
                        // Send SMS alerts
                        sendSMS($jsoncoin['id'], $alert['user_ID'], $alert['phone'], $alert['coin'], $alert['symbol'], $alert['below'], $alert['below_currency'], 'below');
                        
                        // Update DB
                        $ID = $alert['ID'];
                        $sqlbelow = "UPDATE cw_alerts_sms_cur SET below_sent=1 WHERE ID = $ID";
                        $conn->query($sqlbelow);
                    }
                }

                // ABOVE GBP
                if ($alert['above_currency'] == 'GBP') {
                    if ($price > $alert['above'] && !$alert['above_sent'] && is_numeric($alert['above'])) { 
                
                        echo($alert['ID'] . $alert['coin'] . "GBP ABOVE SMS sent \r\n");
                        
                        // Send SMS alerts
                        sendSMS($jsoncoin['id'], $alert['user_ID'], $alert['phone'], $alert['coin'], $alert['symbol'], $alert['above'], $alert['above_currency'], 'above');

                        // Update DB
                        $ID = $alert['ID'];
                        $sqlabove = "UPDATE cw_alerts_sms_cur SET above_sent=1 WHERE ID = $ID";
                        $conn->query($sqlabove);

                    }
                }


                $price = $jsoncoin['price_usd'] * $rate_aud;

                // BELOW AUD
                if ($alert['below_currency'] == 'AUD') {

                    if ($price < $alert['below'] && !$alert['below_sent'] && is_numeric($alert['below'])) { 
                
                        echo($alert['ID'] . $alert['coin'] . "AUD below SMS sent \r\n");

                        // Send SMS alerts
                        sendSMS($jsoncoin['id'], $alert['user_ID'], $alert['phone'], $alert['coin'], $alert['symbol'], $alert['below'], $alert['below_currency'], 'below');
                        
                        // Update DB
                        $ID = $alert['ID'];
                        $sqlbelow = "UPDATE cw_alerts_sms_cur SET below_sent=1 WHERE ID = $ID";
                        $conn->query($sqlbelow);

                    }
                }

                // ABOVE AUD
                if ($alert['above_currency'] == 'AUD') {

                    if ($price > $alert['above'] && !$alert['above_sent'] && is_numeric($alert['above'])) { 
                
                        echo($alert['ID'] . $alert['coin'] . "AUD ABOVE SMS sent \r\n");  

                        // Send SMS alerts
                        sendSMS($jsoncoin['id'], $alert['user_ID'], $alert['phone'], $alert['coin'], $alert['symbol'], $alert['above'], $alert['above_currency'], 'above');

                        // Update DB
                        $ID = $alert['ID'];
                        $sqlabove = "UPDATE cw_alerts_sms_cur SET above_sent=1 WHERE ID = $ID";
                        $conn->query($sqlabove);

                    }
                }


                $price = $jsoncoin['price_usd'] * $rate_cad;

                // BELOW CAD
                if ($alert['below_currency'] == 'CAD') {

                    if ($price < $alert['below'] && !$alert['below_sent'] && is_numeric($alert['below'])) { 

                        echo($alert['ID'] . $alert['coin'] . "CAD below SMS sent \r\n");

                        // Send SMS alerts
                        sendSMS($jsoncoin['id'], $alert['user_ID'], $alert['phone'], $alert['coin'], $alert['symbol'], $alert['below'], $alert['below_currency'], 'below');

                        // Update DB
                        $ID = $alert['ID'];
                        $sqlbelow = "UPDATE cw_alerts_sms_cur SET below_sent=1 WHERE ID = $ID";
                        $conn->query($sqlbelow);

                    }
                }

                // ABOVE CAD
                if ($alert['above_currency'] == 'CAD') {
                    
                    if ($price > $alert['above'] && !$alert['above_sent'] && is_numeric($alert['above'])) { 
                
                        echo($alert['ID'] . $alert['coin'] . "CAD ABOVE SMS sent \r\n"); 

                        // Send SMS alerts
                        sendSMS($jsoncoin['id'], $alert['user_ID'], $alert['phone'], $alert['coin'], $alert['symbol'], $alert['above'], $alert['above_currency'], 'above');

                        // Update DB
                        $ID = $alert['ID'];
                        $sqlabove = "UPDATE cw_alerts_sms_cur SET above_sent=1 WHERE ID = $ID";
                        $conn->query($sqlabove);

                    }
                }




                $price = $jsoncoin['price_usd'] * $rate_brl;
                
                // BELOW BRL
                if ($alert['below_currency'] == 'BRL') {

                    if ($price < $alert['below'] && !$alert['below_sent'] && is_numeric($alert['below'])) { 
                
                        echo($alert['ID'] . $alert['coin'] . "BRL below SMS sent \r\n");
                            
                        // Send SMS alerts
                        sendSMS($jsoncoin['id'], $alert['user_ID'], $alert['phone'], $alert['coin'], $alert['symbol'], $alert['below'], $alert['below_currency'], 'below');

                        // Update DB
                        $ID = $alert['ID'];
                        $sqlbelow = "UPDATE cw_alerts_sms_cur SET below_sent=1 WHERE ID = $ID";
                        $conn->query($sqlbelow);

                    }
                }

                // ABOVE BRL
                if ($alert['above_currency'] == 'BRL') {

                    if ($price > $alert['above'] && !$alert['above_sent'] && is_numeric($alert['above'])) { 
                
                        echo($alert['ID'] . $alert['coin'] . "BRL ABOVE SMS sent \r\n");

                        // Send SMS alerts
                        sendSMS($jsoncoin['id'], $alert['user_ID'], $alert['phone'], $alert['coin'], $alert['symbol'], $alert['above'], $alert['above_currency'], 'above');
                        
                        // Update DB
                        $ID = $alert['ID'];
                        $sqlabove = "UPDATE cw_alerts_sms_cur SET above_sent=1 WHERE ID = $ID";
                        $conn->query($sqlabove);

                    }
                }


                $price = $jsoncoin['price_usd'] * $rate_mxn;

                // BELOW MXN
                if ($alert['below_currency'] == 'MXN') {
                    if ($price < $alert['below'] && !$alert['below_sent'] && is_numeric($alert['below'])){ 
                
                        echo($alert['ID'] . $alert['coin'] . "MXN below SMS sent \r\n");                    
                        
                        // Send SMS alerts
                        sendSMS($jsoncoin['id'], $alert['user_ID'], $alert['phone'], $alert['coin'], $alert['symbol'], $alert['below'], $alert['below_currency'], 'below');
                        
                        // Update DB
                        $ID = $alert['ID'];
                        $sqlbelow = "UPDATE cw_alerts_sms_cur SET below_sent=1 WHERE ID = $ID";
                        $conn->query($sqlbelow);
                    }
                }

                // ABOVE MXN
                if ($alert['above_currency'] == 'MXN') {
                    if ($price > $alert['above'] && !$alert['above_sent'] && is_numeric($alert['above'])) { 
                
                        echo($alert['ID'] . $alert['coin'] . "MXN ABOVE SMS sent \r\n");
                        
                        // Send SMS alerts
                        sendSMS($jsoncoin['id'], $alert['user_ID'], $alert['phone'], $alert['coin'], $alert['symbol'], $alert['above'], $alert['above_currency'], 'above');

                        // Update DB
                        $ID = $alert['ID'];
                        $sqlabove = "UPDATE cw_alerts_sms_cur SET above_sent=1 WHERE ID = $ID";
                        $conn->query($sqlabove);

                    }
                }


                $price = $jsoncoin['price_usd'] * $rate_jpy;

                // BELOW JPY
                if ($alert['below_currency'] == 'JPY') {

                    if ($price < $alert['below'] && !$alert['below_sent'] && is_numeric($alert['below'])) { 
                
                        echo($alert['ID'] . $alert['coin'] . "JPY below SMS sent \r\n");

                        // Send SMS alerts
                        sendSMS($jsoncoin['id'], $alert['user_ID'], $alert['phone'], $alert['coin'], $alert['symbol'], $alert['below'], $alert['below_currency'], 'below');
                        
                        // Update DB
                        $ID = $alert['ID'];
                        $sqlbelow = "UPDATE cw_alerts_sms_cur SET below_sent=1 WHERE ID = $ID";
                        $conn->query($sqlbelow);

                    }
                }

                // ABOVE JPY
                if ($alert['above_currency'] == 'JPY') {

                    if ($price > $alert['above'] && !$alert['above_sent'] && is_numeric($alert['above'])) { 
                
                        echo($alert['ID'] . $alert['coin'] . "JPY ABOVE SMS sent \r\n");  

                        // Send SMS alerts
                        sendSMS($jsoncoin['id'], $alert['user_ID'], $alert['phone'], $alert['coin'], $alert['symbol'], $alert['above'], $alert['above_currency'], 'above');

                        // Update DB
                        $ID = $alert['ID'];
                        $sqlabove = "UPDATE cw_alerts_sms_cur SET above_sent=1 WHERE ID = $ID";
                        $conn->query($sqlabove);

                    }
                }


                $price = $jsoncoin['price_usd'] * $rate_sgd;

                // BELOW SGD
                if ($alert['below_currency'] == 'SGD') {

                    if ($price < $alert['below'] && !$alert['below_sent'] && is_numeric($alert['below'])) { 

                        echo($alert['ID'] . $alert['coin'] . "SGD below SMS sent \r\n");

                        // Send SMS alerts
                        sendSMS($jsoncoin['id'], $alert['user_ID'], $alert['phone'], $alert['coin'], $alert['symbol'], $alert['below'], $alert['below_currency'], 'below');

                        // Update DB
                        $ID = $alert['ID'];
                        $sqlbelow = "UPDATE cw_alerts_sms_cur SET below_sent=1 WHERE ID = $ID";
                        $conn->query($sqlbelow);

                    }
                }

                // ABOVE SGD
                if ($alert['above_currency'] == 'SGD') {
                    
                    if ($price > $alert['above'] && !$alert['above_sent'] && is_numeric($alert['above'])) { 
                
                        echo($alert['ID'] . $alert['coin'] . "SGD ABOVE SMS sent \r\n"); 

                        // Send SMS alerts
                        sendSMS($jsoncoin['id'], $alert['user_ID'], $alert['phone'], $alert['coin'], $alert['symbol'], $alert['above'], $alert['above_currency'], 'above');

                        // Update DB
                        $ID = $alert['ID'];
                        $sqlabove = "UPDATE cw_alerts_sms_cur SET above_sent=1 WHERE ID = $ID";
                        $conn->query($sqlabove);

                    }
                }






            }
        }
    }
    
    // Try a new loop
    unset($alerts);
    if ($loop) {
        buildAlertsArray();
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
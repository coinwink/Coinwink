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
if (!$cron_alerts_tg_cur) { 
    echo('Kill switch active!');
    exit(); 
}

// Wait for new CMC data
sleep(5);


// Load Telegram
include_once 'coinwink_auth_tg.php';

// Load composer
include_once '../vendor/autoload.php';


// Telegram template and sending
function sendTelegram($coin_ID, $user_ID, $tg_id, $tg_user, $coin, $symbol, $amount, $currency, $b_or_a) {

    global $conn;
    global $bot_api_key;
    global $bot_username;

    $dst = $tg_id;

    # Telegram text
    $text = 'Alert: '. ucfirst($coin) .' ('. ucfirst($symbol) .') is '. $b_or_a .' '. $amount .' '. $currency .'

coinwink.com';

    $status = 'sent';

    // Telegram send
    try {
        
        // Create Telegram API object
        $telegram = new Longman\TelegramBot\Telegram($bot_api_key, $bot_username);

        $result = Longman\TelegramBot\Request::sendMessage([
            'chat_id' => $tg_id,
            'text'    => $text,
        ]);

        // SEND ALERT
        echo ("ALERT SENT<br>");


    } catch (Exception $e) {
        echo ("Error\n");
        $status = 'error';

        // EMAIL ERROR TO ADMIN
        $GLOBALS['mail']->addAddress($GLOBALS['adminaddress']);
        $GLOBALS['mail']->Subject  = "ERROR: cron_alerts_tg_cur";
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
        $GLOBALS['mail']->Subject  = "Undelivered Telegram alert";
        $GLOBALS['mail']->Body = "Hello,
        
Your Coinwink Telegram alert was triggered, but we couldn't deliver it because of an unknnown error.

Regards,
Coinwink";
        $GLOBALS['mail']->Send();
        $GLOBALS['mail']->ClearAllRecipients();

    }

    // Create db log
    $content = ucfirst($coin) .' ('. ucfirst($symbol) .') is '. $b_or_a .' '. $amount .' '. $currency .'.';
    $alert_ID = time() . '' . join('', array_map(function($value) { return $value == 1 ? mt_rand(1, 9) : mt_rand(0, 9); }, range(1, 6)));
    $name = ucfirst($coin);
    $time = time();
    $sql = "INSERT INTO cw_logs_alerts_tg (user_ID, alert_ID, coin_ID, name, symbol, content, type, destination, tg_user, status, time) VALUES ('$user_ID', '$alert_ID', '$coin_ID', '$name', '$symbol', '$content', 'tg_cur', '$dst', '$tg_user', '$status', '$time')";
    $conn->query($sql);

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
$sql = "SELECT * FROM cw_alerts_tg_cur";
$dbalerts = $conn->query($sql);



// Alerts array builder
function buildAlertsArray() {
    global $dbalerts;

    foreach($dbalerts as $row)
    {
        $alerts[$row["coin_id"]][] = $row;
    }

    if (isset($alerts)) {
        processAlerts($alerts, false);
    }
    else {
        echo("No alerts");
        exit;
    }

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
                    
                        echo($alert['ID'] . $alert['coin'] . "BTC below Telegram sent \r\n");

                        // Send Telegram alerts
                        sendTelegram($jsoncoin['id'], $alert['user_ID'], $alert['tg_id'],$alert['tg_user'], $alert['coin'], $alert['symbol'], $alert['below'], $alert['below_currency'], 'below');

                        // Update DB
                        $ID = $alert['ID'];
                        $sqlbelow = "UPDATE cw_alerts_tg_cur SET below_sent=1 WHERE ID = $ID";
                        $conn->query($sqlbelow);

                    }
                }

                // BELOW USD

                if ($alert['below_currency'] == 'USD') {

                    if ($jsoncoin['price_usd'] < $alert['below'] && !$alert['below_sent'] && is_numeric($alert['below'])) { 

                        echo($alert['ID'] . $alert['coin'] . "USD BELOW Telegram sent \r\n");

                        // Send Telegram alerts
                        sendTelegram($jsoncoin['id'], $alert['user_ID'], $alert['tg_id'],$alert['tg_user'], $alert['coin'], $alert['symbol'], $alert['below'], $alert['below_currency'], 'below');
                        
                        // Update DB
                        $ID = $alert['ID'];
                        $sqlbelow = "UPDATE cw_alerts_tg_cur SET below_sent=1 WHERE ID = $ID";
                        $conn->query($sqlbelow);
                    
                    }
                }

                // BELOW ETH

                if ($alert['below_currency'] == 'ETH') {

                    if ($jsoncoin['price_eth'] < $alert['below'] && !$alert['below_sent'] && is_numeric($alert['below'])){ 
                    
                        echo($alert['ID'] . $alert['coin'] . "ETH below Telegram sent \r\n");

                        // Send Telegram alerts
                        sendTelegram($jsoncoin['id'], $alert['user_ID'], $alert['tg_id'],$alert['tg_user'], $alert['coin'], $alert['symbol'], $alert['below'], $alert['below_currency'], 'below');

                        // Update DB
                        $ID = $alert['ID'];
                        $sqlbelow = "UPDATE cw_alerts_tg_cur SET below_sent=1 WHERE ID = $ID";
                        $conn->query($sqlbelow);

                    }
                }

                // ABOVE USD

                if ($alert['above_currency'] == 'USD') {

                    if ($jsoncoin['price_usd'] > $alert['above'] && !$alert['above_sent'] && is_numeric($alert['above']) ) { 

                        echo($alert['ID'] . $alert['coin'] . "USD ABOVE Telegram sent \r\n");  
                    
                        // Send Telegram alerts
                        sendTelegram($jsoncoin['id'], $alert['user_ID'], $alert['tg_id'],$alert['tg_user'], $alert['coin'], $alert['symbol'], $alert['above'], $alert['above_currency'], 'above');

                        // Update DB
                        $ID = $alert['ID'];
                        $sqlabove = "UPDATE cw_alerts_tg_cur SET above_sent=1 WHERE ID = $ID";
                        $conn->query($sqlabove);

                    }
                }                

                // ABOVE BTC

                if ($alert['above_currency'] == 'BTC') {

                    if ($jsoncoin['price_btc'] > $alert['above'] && !$alert['above_sent'] && is_numeric($alert['above'])) { 
                    
                        echo($alert['ID'] . $alert['coin'] . "BTC ABOVE Telegram sent \r\n");  
                        
                        // Send Telegram alerts
                        sendTelegram($jsoncoin['id'], $alert['user_ID'], $alert['tg_id'],$alert['tg_user'], $alert['coin'], $alert['symbol'], $alert['above'], $alert['above_currency'], 'above');

                        // Update DB
                        $ID = $alert['ID'];
                        $sqlabove = "UPDATE cw_alerts_tg_cur SET above_sent=1 WHERE ID = $ID";
                        $conn->query($sqlabove);
                
                    }

                }   
                
                // ABOVE ETH

                if ($alert['above_currency'] == 'ETH') {

                    if ($jsoncoin['price_eth'] > $alert['above'] && !$alert['above_sent'] && is_numeric($alert['above'])) { 
                    
                        echo($alert['ID'] . $alert['coin'] . "ETH ABOVE Telegram sent \r\n");  
                        
                        // Send Telegram alerts
                        sendTelegram($jsoncoin['id'], $alert['user_ID'], $alert['tg_id'],$alert['tg_user'], $alert['coin'], $alert['symbol'], $alert['above'], $alert['above_currency'], 'above');

                        // Update DB
                        $ID = $alert['ID'];
                        $sqlabove = "UPDATE cw_alerts_tg_cur SET above_sent=1 WHERE ID = $ID";
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
                
                        echo($alert['ID'] . $alert['coin'] . "EUR below Telegram sent \r\n");
                            
                        // Send Telegram alerts
                        sendTelegram($jsoncoin['id'], $alert['user_ID'], $alert['tg_id'],$alert['tg_user'], $alert['coin'], $alert['symbol'], $alert['below'], $alert['below_currency'], 'below');

                        // Update DB
                        $ID = $alert['ID'];
                        $sqlbelow = "UPDATE cw_alerts_tg_cur SET below_sent=1 WHERE ID = $ID";
                        $conn->query($sqlbelow);

                    }
                }

                // ABOVE EUR
                if ($alert['above_currency'] == 'EUR') {

                    if ($price > $alert['above'] && !$alert['above_sent'] && is_numeric($alert['above'])) { 
                
                        echo($alert['ID'] . $alert['coin'] . "EUR ABOVE Telegram sent \r\n");

                        // Send Telegram alerts
                        sendTelegram($jsoncoin['id'], $alert['user_ID'], $alert['tg_id'],$alert['tg_user'], $alert['coin'], $alert['symbol'], $alert['above'], $alert['above_currency'], 'above');
                        
                        // Update DB
                        $ID = $alert['ID'];
                        $sqlabove = "UPDATE cw_alerts_tg_cur SET above_sent=1 WHERE ID = $ID";
                        $conn->query($sqlabove);

                    }
                }


                $price = $jsoncoin['price_usd'] * $rate_gbp;
            
                // BELOW GBP
                if ($alert['below_currency'] == 'GBP') {
                    if ($price < $alert['below'] && !$alert['below_sent'] && is_numeric($alert['below'])){ 
                
                        echo($alert['ID'] . $alert['coin'] . "GBP below Telegram sent \r\n");                    
                        
                        // Send Telegram alerts
                        sendTelegram($jsoncoin['id'], $alert['user_ID'], $alert['tg_id'],$alert['tg_user'], $alert['coin'], $alert['symbol'], $alert['below'], $alert['below_currency'], 'below');
                        
                        // Update DB
                        $ID = $alert['ID'];
                        $sqlbelow = "UPDATE cw_alerts_tg_cur SET below_sent=1 WHERE ID = $ID";
                        $conn->query($sqlbelow);
                    }
                }

                // ABOVE GBP
                if ($alert['above_currency'] == 'GBP') {
                    if ($price > $alert['above'] && !$alert['above_sent'] && is_numeric($alert['above'])) { 
                
                        echo($alert['ID'] . $alert['coin'] . "GBP ABOVE Telegram sent \r\n");
                        
                        // Send Telegram alerts
                        sendTelegram($jsoncoin['id'], $alert['user_ID'], $alert['tg_id'],$alert['tg_user'], $alert['coin'], $alert['symbol'], $alert['above'], $alert['above_currency'], 'above');

                        // Update DB
                        $ID = $alert['ID'];
                        $sqlabove = "UPDATE cw_alerts_tg_cur SET above_sent=1 WHERE ID = $ID";
                        $conn->query($sqlabove);

                    }
                }


                $price = $jsoncoin['price_usd'] * $rate_aud;

                // BELOW AUD
                if ($alert['below_currency'] == 'AUD') {

                    if ($price < $alert['below'] && !$alert['below_sent'] && is_numeric($alert['below'])) { 
                
                        echo($alert['ID'] . $alert['coin'] . "AUD below Telegram sent \r\n");

                        // Send Telegram alerts
                        sendTelegram($jsoncoin['id'], $alert['user_ID'], $alert['tg_id'],$alert['tg_user'], $alert['coin'], $alert['symbol'], $alert['below'], $alert['below_currency'], 'below');
                        
                        // Update DB
                        $ID = $alert['ID'];
                        $sqlbelow = "UPDATE cw_alerts_tg_cur SET below_sent=1 WHERE ID = $ID";
                        $conn->query($sqlbelow);

                    }
                }

                // ABOVE AUD
                if ($alert['above_currency'] == 'AUD') {

                    if ($price > $alert['above'] && !$alert['above_sent'] && is_numeric($alert['above'])) { 
                
                        echo($alert['ID'] . $alert['coin'] . "AUD ABOVE Telegram sent \r\n");  

                        // Send Telegram alerts
                        sendTelegram($jsoncoin['id'], $alert['user_ID'], $alert['tg_id'],$alert['tg_user'], $alert['coin'], $alert['symbol'], $alert['above'], $alert['above_currency'], 'above');

                        // Update DB
                        $ID = $alert['ID'];
                        $sqlabove = "UPDATE cw_alerts_tg_cur SET above_sent=1 WHERE ID = $ID";
                        $conn->query($sqlabove);

                    }
                }


                $price = $jsoncoin['price_usd'] * $rate_cad;

                // BELOW CAD
                if ($alert['below_currency'] == 'CAD') {

                    if ($price < $alert['below'] && !$alert['below_sent'] && is_numeric($alert['below'])) { 

                        echo($alert['ID'] . $alert['coin'] . "CAD below Telegram sent \r\n");

                        // Send Telegram alerts
                        sendTelegram($jsoncoin['id'], $alert['user_ID'], $alert['tg_id'],$alert['tg_user'], $alert['coin'], $alert['symbol'], $alert['below'], $alert['below_currency'], 'below');

                        // Update DB
                        $ID = $alert['ID'];
                        $sqlbelow = "UPDATE cw_alerts_tg_cur SET below_sent=1 WHERE ID = $ID";
                        $conn->query($sqlbelow);

                    }
                }

                // ABOVE CAD
                if ($alert['above_currency'] == 'CAD') {
                    
                    if ($price > $alert['above'] && !$alert['above_sent'] && is_numeric($alert['above'])) { 
                
                        echo($alert['ID'] . $alert['coin'] . "CAD ABOVE Telegram sent \r\n"); 

                        // Send Telegram alerts
                        sendTelegram($jsoncoin['id'], $alert['user_ID'], $alert['tg_id'],$alert['tg_user'], $alert['coin'], $alert['symbol'], $alert['above'], $alert['above_currency'], 'above');

                        // Update DB
                        $ID = $alert['ID'];
                        $sqlabove = "UPDATE cw_alerts_tg_cur SET above_sent=1 WHERE ID = $ID";
                        $conn->query($sqlabove);

                    }
                }


                $price = $jsoncoin['price_usd'] * $rate_brl;
                
                // BELOW BRL
                if ($alert['below_currency'] == 'BRL') {

                    if ($price < $alert['below'] && !$alert['below_sent'] && is_numeric($alert['below'])) { 
                
                        echo($alert['ID'] . $alert['coin'] . "BRL below Telegram sent \r\n");
                            
                        // Send Telegram alerts
                        sendTelegram($jsoncoin['id'], $alert['user_ID'], $alert['tg_id'],$alert['tg_user'], $alert['coin'], $alert['symbol'], $alert['below'], $alert['below_currency'], 'below');

                        // Update DB
                        $ID = $alert['ID'];
                        $sqlbelow = "UPDATE cw_alerts_tg_cur SET below_sent=1 WHERE ID = $ID";
                        $conn->query($sqlbelow);

                    }
                }

                // ABOVE BRL
                if ($alert['above_currency'] == 'BRL') {

                    if ($price > $alert['above'] && !$alert['above_sent'] && is_numeric($alert['above'])) { 
                
                        echo($alert['ID'] . $alert['coin'] . "BRL ABOVE Telegram sent \r\n");

                        // Send Telegram alerts
                        sendTelegram($jsoncoin['id'], $alert['user_ID'], $alert['tg_id'],$alert['tg_user'], $alert['coin'], $alert['symbol'], $alert['above'], $alert['above_currency'], 'above');
                        
                        // Update DB
                        $ID = $alert['ID'];
                        $sqlabove = "UPDATE cw_alerts_tg_cur SET above_sent=1 WHERE ID = $ID";
                        $conn->query($sqlabove);

                    }
                }


                $price = $jsoncoin['price_usd'] * $rate_mxn;

                // BELOW MXN
                if ($alert['below_currency'] == 'MXN') {
                    if ($price < $alert['below'] && !$alert['below_sent'] && is_numeric($alert['below'])){ 
                
                        echo($alert['ID'] . $alert['coin'] . "MXN below Telegram sent \r\n");                    
                        
                        // Send Telegram alerts
                        sendTelegram($jsoncoin['id'], $alert['user_ID'], $alert['tg_id'],$alert['tg_user'], $alert['coin'], $alert['symbol'], $alert['below'], $alert['below_currency'], 'below');
                        
                        // Update DB
                        $ID = $alert['ID'];
                        $sqlbelow = "UPDATE cw_alerts_tg_cur SET below_sent=1 WHERE ID = $ID";
                        $conn->query($sqlbelow);
                    }
                }

                // ABOVE MXN
                if ($alert['above_currency'] == 'MXN') {
                    if ($price > $alert['above'] && !$alert['above_sent'] && is_numeric($alert['above'])) { 
                
                        echo($alert['ID'] . $alert['coin'] . "MXN ABOVE Telegram sent \r\n");
                        
                        // Send Telegram alerts
                        sendTelegram($jsoncoin['id'], $alert['user_ID'], $alert['tg_id'],$alert['tg_user'], $alert['coin'], $alert['symbol'], $alert['above'], $alert['above_currency'], 'above');

                        // Update DB
                        $ID = $alert['ID'];
                        $sqlabove = "UPDATE cw_alerts_tg_cur SET above_sent=1 WHERE ID = $ID";
                        $conn->query($sqlabove);

                    }
                }


                $price = $jsoncoin['price_usd'] * $rate_jpy;

                // BELOW JPY
                if ($alert['below_currency'] == 'JPY') {

                    if ($price < $alert['below'] && !$alert['below_sent'] && is_numeric($alert['below'])) { 
                
                        echo($alert['ID'] . $alert['coin'] . "JPY below Telegram sent \r\n");

                        // Send Telegram alerts
                        sendTelegram($jsoncoin['id'], $alert['user_ID'], $alert['tg_id'],$alert['tg_user'], $alert['coin'], $alert['symbol'], $alert['below'], $alert['below_currency'], 'below');
                        
                        // Update DB
                        $ID = $alert['ID'];
                        $sqlbelow = "UPDATE cw_alerts_tg_cur SET below_sent=1 WHERE ID = $ID";
                        $conn->query($sqlbelow);

                    }
                }

                // ABOVE JPY
                if ($alert['above_currency'] == 'JPY') {

                    if ($price > $alert['above'] && !$alert['above_sent'] && is_numeric($alert['above'])) { 
                
                        echo($alert['ID'] . $alert['coin'] . "JPY ABOVE Telegram sent \r\n");  

                        // Send Telegram alerts
                        sendTelegram($jsoncoin['id'], $alert['user_ID'], $alert['tg_id'],$alert['tg_user'], $alert['coin'], $alert['symbol'], $alert['above'], $alert['above_currency'], 'above');

                        // Update DB
                        $ID = $alert['ID'];
                        $sqlabove = "UPDATE cw_alerts_tg_cur SET above_sent=1 WHERE ID = $ID";
                        $conn->query($sqlabove);

                    }
                }


                $price = $jsoncoin['price_usd'] * $rate_sgd;

                // BELOW SGD
                if ($alert['below_currency'] == 'SGD') {

                    if ($price < $alert['below'] && !$alert['below_sent'] && is_numeric($alert['below'])) { 

                        echo($alert['ID'] . $alert['coin'] . "SGD below Telegram sent \r\n");

                        // Send Telegram alerts
                        sendTelegram($jsoncoin['id'], $alert['user_ID'], $alert['tg_id'],$alert['tg_user'], $alert['coin'], $alert['symbol'], $alert['below'], $alert['below_currency'], 'below');

                        // Update DB
                        $ID = $alert['ID'];
                        $sqlbelow = "UPDATE cw_alerts_tg_cur SET below_sent=1 WHERE ID = $ID";
                        $conn->query($sqlbelow);

                    }
                }

                // ABOVE SGD
                if ($alert['above_currency'] == 'SGD') {
                    
                    if ($price > $alert['above'] && !$alert['above_sent'] && is_numeric($alert['above'])) { 
                
                        echo($alert['ID'] . $alert['coin'] . "SGD ABOVE Telegram sent \r\n"); 

                        // Send Telegram alerts
                        sendTelegram($jsoncoin['id'], $alert['user_ID'], $alert['tg_id'],$alert['tg_user'], $alert['coin'], $alert['symbol'], $alert['above'], $alert['above_currency'], 'above');

                        // Update DB
                        $ID = $alert['ID'];
                        $sqlabove = "UPDATE cw_alerts_tg_cur SET above_sent=1 WHERE ID = $ID";
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
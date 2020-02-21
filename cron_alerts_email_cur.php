<?php

// Check execution time - Start time
$time_start = microtime(true);

// Check processing time - START
$rustart = getrusage();

// Increase default (256M) memory limit
// 800M = 500k alerts
ini_set('memory_limit', '768M');

// Increase allowed php script processing time to 30 min
set_time_limit(1800);

// Connect to Mysql
include_once "auth_sql.php";
include_once "auth_email.php";



// Email template and sending
function sendEmail($unique_id, $email, $coin, $symbol, $currency, $amount, $b_or_a) {

    global $mail;
    global $adminaddress;
    global $conn;

    // Email
    $mail->addAddress($email);
                
    $mail->Subject  = 'Alert: '. ucfirst($coin) .' ('. ucfirst($symbol) .') is '. $b_or_a .' '. $amount .' '. $currency;
    
    $mail->Body = ''. ucfirst($coin) .' ('. ucfirst($symbol) .') is '. $b_or_a .' '. $amount .' '. $currency .'.
    
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
        $GLOBALS['mail']->Subject  = "ERROR: cron_alerts_email_cur";
        $GLOBALS['mail']->Body = $mail->ErrorInfo . ' Email: ' . $email;
        $GLOBALS['mail']->Send();
        
        // Create db log
        $timestamp = date("Y-m-d H:i:s");
        $sql = "INSERT INTO cw_logs_alerts_email (user_ID, type, symbol, destination, timestamp, status, error) VALUES ('$unique_id', 'email_cur', '$symbol', '$email', '$timestamp', 'error', '$mail->ErrorInfo')";
        $conn->query($sql);
    }
    else {
        echo "Message has been sent \r\n";

        // Create db log
        $timestamp = date("Y-m-d H:i:s");
        $sql = "INSERT INTO cw_logs_alerts_email (user_ID, type, symbol, destination, timestamp, status) VALUES ('$unique_id', 'email_cur', '$symbol', '$email', '$timestamp', 'sent')";
        $conn->query($sql);
    }

    $mail->ClearAllRecipients();
}




// Select coin price data from db - 1st part
$sql = "SELECT json FROM cw_data_cmc WHERE ID = 1";
$result = $conn->query($sql);
foreach($result as $row)
{
    $dataCMC = unserialize($row["json"]);
}

unset($result);




// Select alerts data from cw_alerts_email_cur db
$sql = "SELECT * FROM cw_alerts_email_cur";
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

                    // Echo
                    echo($alert['ID'] . " " . $alert['coin'] . " BELOW BTC \r\n");
                    
                    // Add to mail queue
                    $mail_queue[$i] = new stdClass();
                    $mail_queue[$i]->unique_id = $alert['unique_id'];
                    $mail_queue[$i]->email = $alert['email'];
                    $mail_queue[$i]->coin = $alert['coin'];
                    $mail_queue[$i]->symbol = $alert['symbol'];
                    $mail_queue[$i]->currency = $alert['below_currency'];
                    $mail_queue[$i]->amount = $alert['below'];
                    $mail_queue[$i]->b_or_a = 'below';
                    $i++;

                    // Update DB
                    $ID = $alert['ID'];
                    $sqlbelow = "UPDATE cw_alerts_email_cur SET below_sent=1 WHERE ID = $ID";
                    $conn->query($sqlbelow);

                    }
                }

                // BELOW USD

                if ($alert['below_currency'] == 'USD') {
                    if ($jsoncoin['price_usd'] < $alert['below'] && !$alert['below_sent'] && is_numeric($alert['below'])){ 

                    // Echo
                    echo($alert['ID'] . " " . $alert['coin'] . " BELOW USD\r\n");

                    // Add to mail queue
                    $mail_queue[$i] = new stdClass();
                    $mail_queue[$i]->unique_id = $alert['unique_id'];
                    $mail_queue[$i]->email = $alert['email'];
                    $mail_queue[$i]->coin = $alert['coin'];
                    $mail_queue[$i]->symbol = $alert['symbol'];
                    $mail_queue[$i]->currency = $alert['below_currency'];
                    $mail_queue[$i]->amount = $alert['below'];
                    $mail_queue[$i]->b_or_a = 'below';
                    $i++;

                    // Update DB
                    $ID = $alert['ID'];
                    $sqlbelow = "UPDATE cw_alerts_email_cur SET below_sent=1 WHERE ID = $ID";
                    $conn->query($sqlbelow);                    

                    }
                }

                // ABOVE USD

                if ($alert['above_currency'] == 'USD') {
                    if ($jsoncoin['price_usd'] > $alert['above'] && !$alert['above_sent'] && is_numeric($alert['above'])){ 

                    // Echo
                    echo($alert['ID'] . " " . $alert['coin'] . " ABOVE USD\r\n");

                    // Add to mail queue
                    $mail_queue[$i] = new stdClass();
                    $mail_queue[$i]->unique_id = $alert['unique_id'];
                    $mail_queue[$i]->email = $alert['email'];
                    $mail_queue[$i]->coin = $alert['coin'];
                    $mail_queue[$i]->symbol = $alert['symbol'];
                    $mail_queue[$i]->currency = $alert['above_currency'];
                    $mail_queue[$i]->amount = $alert['above'];
                    $mail_queue[$i]->b_or_a = 'above';
                    $i++;

                    // Update DB
                    $ID = $alert['ID'];
                    $sqlabove = "UPDATE cw_alerts_email_cur SET above_sent=1 WHERE ID = $ID";
                    $conn->query($sqlabove);                    

                    }
                }

                // ABOVE BTC

                if ($alert['above_currency'] == 'BTC') {
                    if ($jsoncoin['price_btc'] > $alert['above'] && !$alert['above_sent'] && is_numeric($alert['above'])){ 
                    
                    // Echo
                    echo($alert['ID'] . " " . $alert['coin'] . " ABOVE BTC\r\n");
                        
                    // Add to mail queue
                    $mail_queue[$i] = new stdClass();
                    $mail_queue[$i]->unique_id = $alert['unique_id'];
                    $mail_queue[$i]->email = $alert['email'];
                    $mail_queue[$i]->coin = $alert['coin'];
                    $mail_queue[$i]->symbol = $alert['symbol'];
                    $mail_queue[$i]->currency = $alert['above_currency'];
                    $mail_queue[$i]->amount = $alert['above'];
                    $mail_queue[$i]->b_or_a = 'above';
                    $i++;
                    
                    // Update DB                
                    $ID = $alert['ID'];
                    $sqlabove = "UPDATE cw_alerts_email_cur SET above_sent=1 WHERE ID = $ID";
                    $conn->query($sqlabove);

                    }
                }

            }
        }
    }


    // PROCESS ETH
    foreach ($dataCMC as $jsoncoin) {
        
        if (isset($alerts[$jsoncoin["id"]])) {
            
            foreach ($alerts[$jsoncoin["id"]] as $alert) {

                // BELOW ETH

                if ($alert['below_currency'] == 'ETH') {
                    if ($jsoncoin['price_eth'] < $alert['below'] && !$alert['below_sent'] && is_numeric($alert['below'])){ 

                    // Echo
                    echo($alert['ID'] . " " . $alert['coin'] . " BELOW ETH \r\n");
                        
                    // Add to mail queue
                    $mail_queue[$i] = new stdClass();
                    $mail_queue[$i]->unique_id = $alert['unique_id'];
                    $mail_queue[$i]->email = $alert['email'];
                    $mail_queue[$i]->coin = $alert['coin'];
                    $mail_queue[$i]->symbol = $alert['symbol'];
                    $mail_queue[$i]->currency = $alert['below_currency'];
                    $mail_queue[$i]->amount = $alert['below'];
                    $mail_queue[$i]->b_or_a = 'below';
                    $i++;
                    
                    // Update DB
                    $ID = $alert['ID'];
                    $sqlbelow = "UPDATE cw_alerts_email_cur SET below_sent=1 WHERE ID = $ID";
                    $conn->query($sqlbelow);

                    }
                }

                // ABOVE ETH

                if ($alert['above_currency'] == 'ETH') {
                    if ($jsoncoin['price_eth'] > $alert['above'] && !$alert['above_sent'] && is_numeric($alert['above'])){ 
                    
                    // Echo
                    echo($alert['ID'] . " " . $alert['coin'] . " ABOVE ETH\r\n");
                        
                    // Add to mail queue
                    $mail_queue[$i] = new stdClass();
                    $mail_queue[$i]->unique_id = $alert['unique_id'];
                    $mail_queue[$i]->email = $alert['email'];
                    $mail_queue[$i]->coin = $alert['coin'];
                    $mail_queue[$i]->symbol = $alert['symbol'];
                    $mail_queue[$i]->currency = $alert['above_currency'];
                    $mail_queue[$i]->amount = $alert['above'];
                    $mail_queue[$i]->b_or_a = 'above';
                    $i++;

                    // Update DB                
                    $ID = $alert['ID'];
                    $sqlabove = "UPDATE cw_alerts_email_cur SET above_sent=1 WHERE ID = $ID";
                    $conn->query($sqlabove);

                    }
                }

            }
        }
    }


    //             //
    // SECOND PART //
    //             //


    // PROCESS EUR
    foreach ($dataCMC as $jsoncoin) {

        if (isset($alerts[$jsoncoin["id"]])) {
            
            foreach ($alerts[$jsoncoin["id"]] as $alert) {

                // BELOW EUR

                if ($alert['below_currency'] == 'EUR') {
                    if ($jsoncoin['price_eur'] < $alert['below'] && !$alert['below_sent'] && is_numeric($alert['below'])){ 

                    // Echo
                    echo($alert['ID'] . " " . $alert['coin'] . " BELOW EUR \r\n");
                        
                    // Add to mail queue
                    $mail_queue2[$i2] = new stdClass();
                    $mail_queue2[$i2]->unique_id = $alert['unique_id'];
                    $mail_queue2[$i2]->email = $alert['email'];
                    $mail_queue2[$i2]->coin = $alert['coin'];
                    $mail_queue2[$i2]->symbol = $alert['symbol'];
                    $mail_queue2[$i2]->currency = $alert['below_currency'];
                    $mail_queue2[$i2]->amount = $alert['below'];
                    $mail_queue2[$i2]->b_or_a = 'below';
                    $i2++;

                    // Update DB
                    $ID = $alert['ID'];
                    $sqlbelow = "UPDATE cw_alerts_email_cur SET below_sent=1 WHERE ID = $ID";
                    $conn->query($sqlbelow);

                    }
                }

                // ABOVE EUR

                if ($alert['above_currency'] == 'EUR') {
                    if ($jsoncoin['price_eur'] > $alert['above'] && !$alert['above_sent'] && is_numeric($alert['above'])){ 

                    // Echo
                    echo($alert['ID'] . " " . $alert['coin'] . " ABOVE EUR\r\n");

                    // Add to mail queue
                    $mail_queue2[$i2] = new stdClass();
                    $mail_queue2[$i2]->unique_id = $alert['unique_id'];
                    $mail_queue2[$i2]->email = $alert['email'];
                    $mail_queue2[$i2]->coin = $alert['coin'];
                    $mail_queue2[$i2]->symbol = $alert['symbol'];
                    $mail_queue2[$i2]->currency = $alert['above_currency'];
                    $mail_queue2[$i2]->amount = $alert['above'];
                    $mail_queue2[$i2]->b_or_a = 'above';
                    $i2++;

                    // Update DB
                    $ID = $alert['ID'];
                    $sqlabove = "UPDATE cw_alerts_email_cur SET above_sent=1 WHERE ID = $ID";
                    $conn->query($sqlabove);                    

                    }
                }
            }
        }
    }


    // PROCESS GBP
    foreach ($dataCMC as $jsoncoin) {

        if (isset($alerts[$jsoncoin["id"]])) {
            
            foreach ($alerts[$jsoncoin["id"]] as $alert) {

                // BELOW GBP

                if ($alert['below_currency'] == 'GBP') {
                    if ($jsoncoin['price_gbp'] < $alert['below'] && !$alert['below_sent'] && is_numeric($alert['below'])){ 

                    // Echo
                    echo($alert['ID'] . " " . $alert['coin'] . " BELOW GBP \r\n");
                        
                    // Add to mail queue
                    $mail_queue2[$i2] = new stdClass();
                    $mail_queue2[$i2]->unique_id = $alert['unique_id'];
                    $mail_queue2[$i2]->email = $alert['email'];
                    $mail_queue2[$i2]->coin = $alert['coin'];
                    $mail_queue2[$i2]->symbol = $alert['symbol'];
                    $mail_queue2[$i2]->currency = $alert['below_currency'];
                    $mail_queue2[$i2]->amount = $alert['below'];
                    $mail_queue2[$i2]->b_or_a = 'below';
                    $i2++;

                    // Update DB
                    $ID = $alert['ID'];
                    $sqlbelow = "UPDATE cw_alerts_email_cur SET below_sent=1 WHERE ID = $ID";
                    $conn->query($sqlbelow);

                    }
                }

                // ABOVE GBP

                if ($alert['above_currency'] == 'GBP') {
                    if ($jsoncoin['price_gbp'] > $alert['above'] && !$alert['above_sent'] && is_numeric($alert['above'])){ 

                    // Echo
                    echo($alert['ID'] . " " . $alert['coin'] . " ABOVE GBP\r\n");

                    // Add to mail queue
                    $mail_queue2[$i2] = new stdClass();
                    $mail_queue2[$i2]->unique_id = $alert['unique_id'];
                    $mail_queue2[$i2]->email = $alert['email'];
                    $mail_queue2[$i2]->coin = $alert['coin'];
                    $mail_queue2[$i2]->symbol = $alert['symbol'];
                    $mail_queue2[$i2]->currency = $alert['above_currency'];
                    $mail_queue2[$i2]->amount = $alert['above'];
                    $mail_queue2[$i2]->b_or_a = 'above';
                    $i2++;

                    // Update DB
                    $ID = $alert['ID'];
                    $sqlabove = "UPDATE cw_alerts_email_cur SET above_sent=1 WHERE ID = $ID";
                    $conn->query($sqlabove);                    

                    }
                }
            }
        }
    }


    // PROCESS AUD
    foreach ($dataCMC as $jsoncoin) {

        if (isset($alerts[$jsoncoin["id"]])) {

            foreach ($alerts[$jsoncoin["id"]] as $alert) {

                // BELOW AUD

                if ($alert['below_currency'] == 'AUD') {
                
                    if ($jsoncoin['price_aud'] < $alert['below'] && !$alert['below_sent'] && is_numeric($alert['below'])){ 
                
                    // Echo
                    echo($alert['ID'] . " " . $alert['coin'] . " BELOW AUD \r\n");
                        
                    // Add to mail queue
                    $mail_queue2[$i2] = new stdClass();
                    $mail_queue2[$i2]->unique_id = $alert['unique_id'];
                    $mail_queue2[$i2]->email = $alert['email'];
                    $mail_queue2[$i2]->coin = $alert['coin'];
                    $mail_queue2[$i2]->symbol = $alert['symbol'];
                    $mail_queue2[$i2]->currency = $alert['below_currency'];
                    $mail_queue2[$i2]->amount = $alert['below'];
                    $mail_queue2[$i2]->b_or_a = 'below';
                    $i2++;

                    // Update DB
                    $ID = $alert['ID'];
                    $sqlbelow = "UPDATE cw_alerts_email_cur SET below_sent=1 WHERE ID = $ID";
                    $conn->query($sqlbelow);

                    }
                }

                // ABOVE AUD

                if ($alert['above_currency'] == 'AUD') {
                    if ($jsoncoin['price_aud'] > $alert['above'] && !$alert['above_sent'] && is_numeric($alert['above'])){ 

                    // Echo
                    echo($alert['ID'] . " " . $alert['coin'] . " ABOVE AUD\r\n");

                    // Add to mail queue
                    $mail_queue2[$i2] = new stdClass();
                    $mail_queue2[$i2]->unique_id = $alert['unique_id'];
                    $mail_queue2[$i2]->email = $alert['email'];
                    $mail_queue2[$i2]->coin = $alert['coin'];
                    $mail_queue2[$i2]->symbol = $alert['symbol'];
                    $mail_queue2[$i2]->currency = $alert['above_currency'];
                    $mail_queue2[$i2]->amount = $alert['above'];
                    $mail_queue2[$i2]->b_or_a = 'above';
                    $i2++;

                    // Update DB
                    $ID = $alert['ID'];
                    $sqlabove = "UPDATE cw_alerts_email_cur SET above_sent=1 WHERE ID = $ID";
                    $conn->query($sqlabove);                    

                    }
                }
            }
        }
    }


    // PROCESS CAD
    foreach ($dataCMC as $jsoncoin) {

        if (isset($alerts[$jsoncoin["id"]])) {
            
            foreach ($alerts[$jsoncoin["id"]] as $alert) {

                // BELOW CAD

                if ($alert['below_currency'] == 'CAD') {
                    if ($jsoncoin['price_cad'] < $alert['below'] && !$alert['below_sent'] && is_numeric($alert['below'])){ 

                    // Echo
                    echo($alert['ID'] . " " . $alert['coin'] . " BELOW CAD \r\n");
                        
                    // Add to mail queue
                    $mail_queue2[$i2] = new stdClass();
                    $mail_queue2[$i2]->unique_id = $alert['unique_id'];
                    $mail_queue2[$i2]->email = $alert['email'];
                    $mail_queue2[$i2]->coin = $alert['coin'];
                    $mail_queue2[$i2]->symbol = $alert['symbol'];
                    $mail_queue2[$i2]->currency = $alert['below_currency'];
                    $mail_queue2[$i2]->amount = $alert['below'];
                    $mail_queue2[$i2]->b_or_a = 'below';
                    $i2++;

                    // Update DB
                    $ID = $alert['ID'];
                    $sqlbelow = "UPDATE cw_alerts_email_cur SET below_sent=1 WHERE ID = $ID";
                    $conn->query($sqlbelow);

                    }
                }

                // ABOVE CAD

                if ($alert['above_currency'] == 'CAD') {
                    if ($jsoncoin['price_cad'] > $alert['above'] && !$alert['above_sent'] && is_numeric($alert['above'])){ 

                    // Echo
                    echo($alert['ID'] . " " . $alert['coin'] . " ABOVE CAD\r\n");

                    // Add to mail queue
                    $mail_queue2[$i2] = new stdClass();
                    $mail_queue2[$i2]->unique_id = $alert['unique_id'];
                    $mail_queue2[$i2]->email = $alert['email'];
                    $mail_queue2[$i2]->coin = $alert['coin'];
                    $mail_queue2[$i2]->symbol = $alert['symbol'];
                    $mail_queue2[$i2]->currency = $alert['above_currency'];
                    $mail_queue2[$i2]->amount = $alert['above'];
                    $mail_queue2[$i2]->b_or_a = 'above';
                    $i2++;

                    // Update DB
                    $ID = $alert['ID'];
                    $sqlabove = "UPDATE cw_alerts_email_cur SET above_sent=1 WHERE ID = $ID";
                    $conn->query($sqlabove);                    

                    }
                }
            }
        }
    }
    

    // PROCESS BRL
    foreach ($dataCMC as $jsoncoin) {

        if (isset($alerts[$jsoncoin["id"]])) {
            
            foreach ($alerts[$jsoncoin["id"]] as $alert) {

                // BELOW BRL

                if ($alert['below_currency'] == 'BRL') {
                    if ($jsoncoin['price_brl'] < $alert['below'] && !$alert['below_sent'] && is_numeric($alert['below'])){ 

                    // Echo
                    echo($alert['ID'] . " " . $alert['coin'] . " BELOW BRL \r\n");
                        
                    // Add to mail queue
                    $mail_queue2[$i2] = new stdClass();
                    $mail_queue2[$i2]->unique_id = $alert['unique_id'];
                    $mail_queue2[$i2]->email = $alert['email'];
                    $mail_queue2[$i2]->coin = $alert['coin'];
                    $mail_queue2[$i2]->symbol = $alert['symbol'];
                    $mail_queue2[$i2]->currency = $alert['below_currency'];
                    $mail_queue2[$i2]->amount = $alert['below'];
                    $mail_queue2[$i2]->b_or_a = 'below';
                    $i2++;

                    // Update DB
                    $ID = $alert['ID'];
                    $sqlbelow = "UPDATE cw_alerts_email_cur SET below_sent=1 WHERE ID = $ID";
                    $conn->query($sqlbelow);

                    }
                }

                // ABOVE BRL

                if ($alert['above_currency'] == 'BRL') {
                    if ($jsoncoin['price_brl'] > $alert['above'] && !$alert['above_sent'] && is_numeric($alert['above'])){ 

                    // Echo
                    echo($alert['ID'] . " " . $alert['coin'] . " ABOVE BRL\r\n");

                    // Add to mail queue
                    $mail_queue2[$i2] = new stdClass();
                    $mail_queue2[$i2]->unique_id = $alert['unique_id'];
                    $mail_queue2[$i2]->email = $alert['email'];
                    $mail_queue2[$i2]->coin = $alert['coin'];
                    $mail_queue2[$i2]->symbol = $alert['symbol'];
                    $mail_queue2[$i2]->currency = $alert['above_currency'];
                    $mail_queue2[$i2]->amount = $alert['above'];
                    $mail_queue2[$i2]->b_or_a = 'above';
                    $i2++;

                    // Update DB
                    $ID = $alert['ID'];
                    $sqlabove = "UPDATE cw_alerts_email_cur SET above_sent=1 WHERE ID = $ID";
                    $conn->query($sqlabove);                    

                    }
                }
            }
        }
    }


    // PROCESS MXN
    foreach ($dataCMC as $jsoncoin) {

        if (isset($alerts[$jsoncoin["id"]])) {
            
            foreach ($alerts[$jsoncoin["id"]] as $alert) {

                // BELOW MXN

                if ($alert['below_currency'] == 'MXN') {
                    if ($jsoncoin['price_mxn'] < $alert['below'] && !$alert['below_sent'] && is_numeric($alert['below'])){ 

                    // Echo
                    echo($alert['ID'] . " " . $alert['coin'] . " BELOW MXN \r\n");
                        
                    // Add to mail queue
                    $mail_queue2[$i2] = new stdClass();
                    $mail_queue2[$i2]->unique_id = $alert['unique_id'];
                    $mail_queue2[$i2]->email = $alert['email'];
                    $mail_queue2[$i2]->coin = $alert['coin'];
                    $mail_queue2[$i2]->symbol = $alert['symbol'];
                    $mail_queue2[$i2]->currency = $alert['below_currency'];
                    $mail_queue2[$i2]->amount = $alert['below'];
                    $mail_queue2[$i2]->b_or_a = 'below';
                    $i2++;

                    // Update DB
                    $ID = $alert['ID'];
                    $sqlbelow = "UPDATE cw_alerts_email_cur SET below_sent=1 WHERE ID = $ID";
                    $conn->query($sqlbelow);

                    }
                }

                // ABOVE MXN

                if ($alert['above_currency'] == 'MXN') {
                    if ($jsoncoin['price_mxn'] > $alert['above'] && !$alert['above_sent'] && is_numeric($alert['above'])){ 

                    // Echo
                    echo($alert['ID'] . " " . $alert['coin'] . " ABOVE MXN\r\n");

                    // Add to mail queue
                    $mail_queue2[$i2] = new stdClass();
                    $mail_queue2[$i2]->unique_id = $alert['unique_id'];
                    $mail_queue2[$i2]->email = $alert['email'];
                    $mail_queue2[$i2]->coin = $alert['coin'];
                    $mail_queue2[$i2]->symbol = $alert['symbol'];
                    $mail_queue2[$i2]->currency = $alert['above_currency'];
                    $mail_queue2[$i2]->amount = $alert['above'];
                    $mail_queue2[$i2]->b_or_a = 'above';
                    $i2++;

                    // Update DB
                    $ID = $alert['ID'];
                    $sqlabove = "UPDATE cw_alerts_email_cur SET above_sent=1 WHERE ID = $ID";
                    $conn->query($sqlabove);                    

                    }
                }
            }
        }
    }


    // PROCESS JPY
    foreach ($dataCMC as $jsoncoin) {

        if (isset($alerts[$jsoncoin["id"]])) {

            foreach ($alerts[$jsoncoin["id"]] as $alert) {

                // BELOW JPY

                if ($alert['below_currency'] == 'JPY') {
                
                    if ($jsoncoin['price_jpy'] < $alert['below'] && !$alert['below_sent'] && is_numeric($alert['below'])){ 
                
                    // Echo
                    echo($alert['ID'] . " " . $alert['coin'] . " BELOW JPY \r\n");
                        
                    // Add to mail queue
                    $mail_queue2[$i2] = new stdClass();
                    $mail_queue2[$i2]->unique_id = $alert['unique_id'];
                    $mail_queue2[$i2]->email = $alert['email'];
                    $mail_queue2[$i2]->coin = $alert['coin'];
                    $mail_queue2[$i2]->symbol = $alert['symbol'];
                    $mail_queue2[$i2]->currency = $alert['below_currency'];
                    $mail_queue2[$i2]->amount = $alert['below'];
                    $mail_queue2[$i2]->b_or_a = 'below';
                    $i2++;

                    // Update DB
                    $ID = $alert['ID'];
                    $sqlbelow = "UPDATE cw_alerts_email_cur SET below_sent=1 WHERE ID = $ID";
                    $conn->query($sqlbelow);

                    }
                }

                // ABOVE JPY

                if ($alert['above_currency'] == 'JPY') {
                    if ($jsoncoin['price_jpy'] > $alert['above'] && !$alert['above_sent'] && is_numeric($alert['above'])){ 

                    // Echo
                    echo($alert['ID'] . " " . $alert['coin'] . " ABOVE JPY\r\n");

                    // Add to mail queue
                    $mail_queue2[$i2] = new stdClass();
                    $mail_queue2[$i2]->unique_id = $alert['unique_id'];
                    $mail_queue2[$i2]->email = $alert['email'];
                    $mail_queue2[$i2]->coin = $alert['coin'];
                    $mail_queue2[$i2]->symbol = $alert['symbol'];
                    $mail_queue2[$i2]->currency = $alert['above_currency'];
                    $mail_queue2[$i2]->amount = $alert['above'];
                    $mail_queue2[$i2]->b_or_a = 'above';
                    $i2++;

                    // Update DB
                    $ID = $alert['ID'];
                    $sqlabove = "UPDATE cw_alerts_email_cur SET above_sent=1 WHERE ID = $ID";
                    $conn->query($sqlabove);                    

                    }
                }
            }
        }
    }


    // PROCESS SGD
    foreach ($dataCMC as $jsoncoin) {

        if (isset($alerts[$jsoncoin["id"]])) {
            
            foreach ($alerts[$jsoncoin["id"]] as $alert) {

                // BELOW SGD

                if ($alert['below_currency'] == 'SGD') {
                    if ($jsoncoin['price_sgd'] < $alert['below'] && !$alert['below_sent'] && is_numeric($alert['below'])){ 

                    // Echo
                    echo($alert['ID'] . " " . $alert['coin'] . " BELOW SGD \r\n");
                        
                    // Add to mail queue
                    $mail_queue2[$i2] = new stdClass();
                    $mail_queue2[$i2]->unique_id = $alert['unique_id'];
                    $mail_queue2[$i2]->email = $alert['email'];
                    $mail_queue2[$i2]->coin = $alert['coin'];
                    $mail_queue2[$i2]->symbol = $alert['symbol'];
                    $mail_queue2[$i2]->currency = $alert['below_currency'];
                    $mail_queue2[$i2]->amount = $alert['below'];
                    $mail_queue2[$i2]->b_or_a = 'below';
                    $i2++;

                    // Update DB
                    $ID = $alert['ID'];
                    $sqlbelow = "UPDATE cw_alerts_email_cur SET below_sent=1 WHERE ID = $ID";
                    $conn->query($sqlbelow);

                    }
                }

                // ABOVE SGD

                if ($alert['above_currency'] == 'SGD') {
                    if ($jsoncoin['price_sgd'] > $alert['above'] && !$alert['above_sent'] && is_numeric($alert['above'])){ 

                    // Echo
                    echo($alert['ID'] . " " . $alert['coin'] . " ABOVE SGD\r\n");

                    // Add to mail queue
                    $mail_queue2[$i2] = new stdClass();
                    $mail_queue2[$i2]->unique_id = $alert['unique_id'];
                    $mail_queue2[$i2]->email = $alert['email'];
                    $mail_queue2[$i2]->coin = $alert['coin'];
                    $mail_queue2[$i2]->symbol = $alert['symbol'];
                    $mail_queue2[$i2]->currency = $alert['above_currency'];
                    $mail_queue2[$i2]->amount = $alert['above'];
                    $mail_queue2[$i2]->b_or_a = 'above';
                    $i2++;

                    // Update DB
                    $ID = $alert['ID'];
                    $sqlabove = "UPDATE cw_alerts_email_cur SET above_sent=1 WHERE ID = $ID";
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



// Send emails for the first part
if (isset($mail_queue)) {
    foreach ($mail_queue as $alert) {
        $alert = json_decode(json_encode($alert),true);
        sendEmail($alert['unique_id'], $alert['email'], $alert['coin'], $alert['symbol'], $alert['currency'], $alert['amount'], $alert['b_or_a']);
    }
}
unset($mail_queue);


// Send emails for the second part
if (isset($mail_queue2)) {
    foreach ($mail_queue2 as $alert) {
        $alert = json_decode(json_encode($alert),true);
        sendEmail($alert['unique_id'], $alert['email'], $alert['coin'], $alert['symbol'], $alert['currency'], $alert['amount'], $alert['b_or_a']);
    }
}
unset($mail_queue2);




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
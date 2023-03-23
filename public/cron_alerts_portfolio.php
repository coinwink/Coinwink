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
if (!$cron_alerts_portfolio) { 
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


// Load Telegram
include_once 'coinwink_auth_tg.php';
// Load composer
include_once '../vendor/autoload.php';


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
$sql = "SELECT * FROM cw_alerts_portfolio";
$dbalerts = $conn->query($sql);




// Alerts array builder
function buildAlertsArray() {
    global $dbalerts;
    global $conn;

    $newAlertsArray = [];
    $portfolio = "";

    foreach($dbalerts as $user_alerts)
    {
        // for each user get portfolio
        $user_ID = $user_alerts['user_ID'];
        $sql = "SELECT * FROM cw_settings WHERE user_ID = '".$user_ID."'";
        $result = mysqli_fetch_assoc($conn->query($sql));
        if(isset($result["portfolio"])) {
            $portfolio = $result["portfolio"];
            $portfolio = stripslashes($portfolio);
            $portfolio = json_decode($portfolio);
        }

        $newAlertsArray[$user_ID] = $user_alerts;

        if (isset($portfolio)) {
            foreach ($portfolio as $coin) {
                if (isset($coin->slug)) {
                    $newAlertsArray[$user_ID]['coin_slugs'][] = $coin->slug;
                }
                // else {
                //     var_dump($coin);
                // }
            }
        }

        // var_dump($newAlertsArray);

    }
    // var_dump($newAlertsArray);
    
    // var_dump($newAlertsArray[2]);
    // var_dump($user_alerts);
    // exit();
    processAlertsNew($newAlertsArray);

}
buildAlertsArray();


$alerts_queue = [];


function processAlertsNew($newAlertsArray) {
    global $dataCMC;
    global $conn;

    foreach ($newAlertsArray as $user) {
        if (isset($user["coin_slugs"])) {
            foreach ($user["coin_slugs"] as $coin) {
                // echo $coin;

                // echo ("\n\n\n" . $user["user_ID"]);
                // echo ("\n" . $user["type"]);
                // echo ("\n" . $user["destination"]);

                $user_ID = $user["user_ID"];
                $delivery_type = $user["type"];
                $destination = $user["destination"];

                foreach ($dataCMC as $jsoncoin) {
                    if ($jsoncoin["slug"] == $coin) {
                        // var_dump($jsoncoin);
                        // echo(abs($jsoncoin["per_24h"]));
                        
                        //
                        //
                        //
                        if ($user["on_1h_plus"] == "on") {
                            if ($user["change_1h_plus"] < $jsoncoin["per_1h"]) {
                                // echo ("\n" . $coin . "alert plus change 1h for " . $user["destination"] . " " . $user["type"]);
                                $timestamp = date("Y-m-d H:i:s");
                                
                                $sql = "SELECT * FROM cw_logs_alerts_portfolio WHERE user_ID = '".$user_ID."' AND coin = '".$coin."' AND type = 'change_1h_plus' ORDER BY ID DESC LIMIT 1";
                                $result = mysqli_fetch_assoc($conn->query($sql));

                                if ($result) {
                                    if (strtotime($timestamp) - strtotime($result["timestamp"]) > 86400) {
                                        $alerts_queue[$user["type"]]['change_1h_plus'][] = [ $coin, $user["change_1h_plus"], $user["user_ID"], $user["destination"], $user["type"], $jsoncoin["name"], $jsoncoin["symbol"], $jsoncoin["id"], $jsoncoin['price_usd']];
                                    
                                        // // Create db log
                                        // $change_1h_plus = $user["change_1h_plus"];
                                        // $sql = "INSERT INTO cw_logs_alerts_portfolio (user_ID, coin, type, destination, status, error, timestamp) VALUES ('$user_ID', '$coin', 'change_1h_plus', '$destination', 'sent', '', '$timestamp')";
                                        // $conn->query($sql);
                                    }
                                }
                                else {
                                    $alerts_queue[$user["type"]]['change_1h_plus'][] = [ $coin, $user["change_1h_plus"], $user["user_ID"], $user["destination"], $user["type"], $jsoncoin["name"], $jsoncoin["symbol"], $jsoncoin["id"], $jsoncoin['price_usd']];
                                    
                                    // // Create db log
                                    // $change_1h_plus = $user["change_1h_plus"];
                                    // $sql = "INSERT INTO cw_logs_alerts_portfolio (user_ID, coin, type, destination, status, error, timestamp) VALUES ('$user_ID', '$coin', 'change_1h_plus', '$destination', 'sent', '', '$timestamp')";
                                    // $conn->query($sql);
                                }
                                
                            }
                        }
                                                
                        //
                        //
                        //
                        if ($user["on_1h_minus"] == "on" && ($jsoncoin["per_1h"] < 0)) {
                            if ($user["change_1h_minus"] < abs($jsoncoin["per_1h"])) {
                                // echo ("\n" . $coin . "alert minus change 1h for " . $user["destination"] . " " . $user["type"]);
                                $timestamp = date("Y-m-d H:i:s");
                                
                                $sql = "SELECT * FROM cw_logs_alerts_portfolio WHERE user_ID = '".$user_ID."' AND coin = '".$coin."' AND type = 'change_1h_minus' ORDER BY ID DESC LIMIT 1";
                                $result = mysqli_fetch_assoc($conn->query($sql));

                                if ($result) {
                                    if (strtotime($timestamp) - strtotime($result["timestamp"]) > 86400) {
                                        $alerts_queue[$user["type"]]['change_1h_minus'][] = [ $coin, $user["change_1h_minus"], $user["user_ID"], $user["destination"], $user["type"], $jsoncoin["name"], $jsoncoin["symbol"], $jsoncoin["id"], $jsoncoin['price_usd']];
                                    
                                        // // Create db log
                                        // $change_1h_minus = $user["change_1h_minus"];
                                        // $sql = "INSERT INTO cw_logs_alerts_portfolio (user_ID, coin, type, destination, status, error, timestamp) VALUES ('$user_ID', '$coin', 'change_1h_minus', '$destination', 'sent', '', '$timestamp')";
                                        // $conn->query($sql);
                                    }
                                }
                                else {
                                    $alerts_queue[$user["type"]]['change_1h_minus'][] = [ $coin, $user["change_1h_minus"], $user["user_ID"], $user["destination"], $user["type"], $jsoncoin["name"], $jsoncoin["symbol"], $jsoncoin["id"], $jsoncoin['price_usd']];
                                    
                                    // // Create db log
                                    // $change_1h_minus = $user["change_1h_minus"];
                                    // $sql = "INSERT INTO cw_logs_alerts_portfolio (user_ID, coin, type, destination, status, error, timestamp) VALUES ('$user_ID', '$coin', 'change_1h_minus', '$destination', 'sent', '', '$timestamp')";
                                    // $conn->query($sql);
                                }

                            }
                        }

                        //
                        //
                        //
                        if ($user["on_24h_plus"] == "on") {
                            if ($user["change_24h_plus"] < $jsoncoin["per_24h"]) {
                                // echo ("\n" . $coin . "alert plus change 24h for " . $user["destination"] . " " . $user["type"]);

                                $timestamp = date("Y-m-d H:i:s");
                                
                                $sql = "SELECT * FROM cw_logs_alerts_portfolio WHERE user_ID = '".$user_ID."' AND coin = '".$coin."' AND type = 'change_24h_plus' ORDER BY ID DESC LIMIT 1";
                                $result = mysqli_fetch_assoc($conn->query($sql));

                                if ($result) {
                                    if (strtotime($timestamp) - strtotime($result["timestamp"]) > 86400) {
                                        $alerts_queue[$user["type"]]['change_24h_plus'][] = [ $coin, $user["change_24h_plus"], $user["user_ID"], $user["destination"], $user["type"], $jsoncoin["name"], $jsoncoin["symbol"], $jsoncoin["id"], $jsoncoin['price_usd'] ];
                                    
                                        // // Create db log
                                        // $change_24h_plus = $user["change_24h_plus"];
                                        // $sql = "INSERT INTO cw_logs_alerts_portfolio (user_ID, coin, type, destination, status, error, timestamp) VALUES ('$user_ID', '$coin', 'change_24h_plus', '$destination', 'sent', '', '$timestamp')";
                                        // $conn->query($sql);
                                    }
                                }
                                else {
                                    $alerts_queue[$user["type"]]['change_24h_plus'][] = [ $coin, $user["change_24h_plus"], $user["user_ID"], $user["destination"], $user["type"], $jsoncoin["name"], $jsoncoin["symbol"], $jsoncoin["id"], $jsoncoin['price_usd'] ];
                                    
                                    // // Create db log
                                    // $change_24h_plus = $user["change_24h_plus"];
                                    // $sql = "INSERT INTO cw_logs_alerts_portfolio (user_ID, coin, type, destination, status, error, timestamp) VALUES ('$user_ID', '$coin', 'change_24h_plus', '$destination', 'sent', '', '$timestamp')";
                                    // $conn->query($sql);
                                }

                            }
                        }

                        //
                        //
                        //
                        if ($user["on_24h_minus"] == "on" && ($jsoncoin["per_24h"] < 0)) {
                            if ($user["change_24h_minus"] < abs($jsoncoin["per_24h"])) {
                                // echo ("\n" . $coin . "alert minus change 24h for " . $user["destination"] . " " . $user["type"]);

                                $timestamp = date("Y-m-d H:i:s");
                                
                                $sql = "SELECT * FROM cw_logs_alerts_portfolio WHERE user_ID = '".$user_ID."' AND coin = '".$coin."' AND type = 'change_24h_minus' ORDER BY ID DESC LIMIT 1";
                                $result = mysqli_fetch_assoc($conn->query($sql));
                                // var_dump($result);
                                if ($result) {
                                    if (strtotime($timestamp) - strtotime($result["timestamp"]) > 86400) {
                                        $alerts_queue[$user["type"]]['change_24h_minus'][] = [ $coin, $user["change_24h_minus"], $user["user_ID"], $user["destination"], $user["type"], $jsoncoin["name"], $jsoncoin["symbol"], $jsoncoin["id"], $jsoncoin['price_usd']];
                                    
                                        // // Create db log
                                        // $change_24h_minus = $user["change_24h_minus"];
                                        // $sql = "INSERT INTO cw_logs_alerts_portfolio (user_ID, coin, type, destination, status, error, timestamp) VALUES ('$user_ID', '$coin', 'change_24h_minus', '$destination', 'sent', '', '$timestamp')";
                                        // $conn->query($sql);
                                    }
                                }
                                else {
                                    $alerts_queue[$user["type"]]['change_24h_minus'][] = [ $coin, $user["change_24h_minus"], $user["user_ID"], $user["destination"], $user["type"], $jsoncoin["name"], $jsoncoin["symbol"], $jsoncoin["id"], $jsoncoin['price_usd'] ];
                                    
                                    // // Create db log
                                    // $change_24h_minus = $user["change_24h_minus"];
                                    // $sql = "INSERT INTO cw_logs_alerts_portfolio (user_ID, coin, type, destination, status, error, timestamp) VALUES ('$user_ID', '$coin', 'change_24h_minus', '$destination', 'sent', '', '$timestamp')";
                                    // $conn->query($sql);
                                }

                            }
                        }

                    
                        // echo ("\n" . $coin . " 1h: " . $jsoncoin["per_1h"] . " 24h: " . $jsoncoin["per_24h"]);
                    }
                }

            } 
        }

    }

    if(isset($alerts_queue)) {
        sendAlerts($alerts_queue);
    }
    else {
        echo "no alerts to send";
    }
    
}


//
//
//
function sendAlerts($alerts_queue) {

    // var_dump($alerts_queue);
    echo("\n\n\n");


    //
    //
    // Send emails
    if (isset($alerts_queue['email'])) {
        if (isset($alerts_queue['email']["change_1h_plus"])) {
            foreach ($alerts_queue['email']["change_1h_plus"] as $alert) {

                $coin = $alert[0]; // slug
                $change = $alert[1];
                $user_ID = $alert[2];
                $destination = $alert[3];
                $type = $alert[4];
                $name = $alert[5];
                $symbol = $alert[6];
                $coin_ID = $alert[7];
                $price_usd = $alert[8];

                // echo("\nsend email through function: " . $coin . $user_ID . $destination . $type);

                sendEmail($coin_ID, $coin, "change_1h_plus", $change, $destination, $type, $name, $symbol, $user_ID, $price_usd);
            }
        }

        if (isset($alerts_queue['email']["change_1h_minus"])) {
            foreach ($alerts_queue['email']["change_1h_minus"] as $alert) {

                $coin = $alert[0];
                $change = $alert[1];
                $user_ID = $alert[2];
                $destination = $alert[3];
                $type = $alert[4];
                $name = $alert[5];
                $symbol = $alert[6];
                $coin_ID = $alert[7];
                $price_usd = $alert[8];

                // echo("\nsend email through function: " . $coin . $user_ID . $destination . $type);

                sendEmail($coin_ID, $coin, "change_1h_minus", $change, $destination, $type, $name, $symbol, $user_ID, $price_usd);
            }
        }

        if (isset($alerts_queue['email']["change_24h_plus"])) {
            foreach ($alerts_queue['email']["change_24h_plus"] as $alert) {

                $coin = $alert[0];
                $change = $alert[1];
                $user_ID = $alert[2];
                $destination = $alert[3];
                $type = $alert[4];
                $name = $alert[5];
                $symbol = $alert[6];
                $coin_ID = $alert[7];
                $price_usd = $alert[8];

                // echo("\nsend email through function: " . $coin . $user_ID . $destination . $type);

                sendEmail($coin_ID, $coin, "change_24h_plus", $change, $destination, $type, $name, $symbol, $user_ID, $price_usd);
            }
        }

        if (isset($alerts_queue['email']["change_24h_minus"])) {
            foreach ($alerts_queue['email']["change_24h_minus"] as $alert) {

                $coin = $alert[0];
                $change = $alert[1];
                $user_ID = $alert[2];
                $destination = $alert[3];
                $type = $alert[4];
                $name = $alert[5];
                $symbol = $alert[6];
                $coin_ID = $alert[7];
                $price_usd = $alert[8];

                // echo("\nsend email through function: " . $coin . $user_ID . $destination . $type);
                
                sendEmail($coin_ID, $coin, "change_24h_minus", $change, $destination, $type, $name, $symbol, $user_ID, $price_usd);
            }
        }
    }

    
    //
    //
    // Send sms
    if (isset($alerts_queue['sms'])) {
        if (isset($alerts_queue['sms']["change_1h_plus"])) {
            foreach ($alerts_queue['sms']["change_1h_plus"] as $alert) {

                $coin = $alert[0]; // slug
                $change = $alert[1];
                $user_ID = $alert[2];
                $destination = $alert[3];
                $type = $alert[4];
                $name = $alert[5];
                $symbol = $alert[6];
                $coin_ID = $alert[7];
                $price_usd = $alert[8];

                // echo("\nsend sms through function: " . $coin . $user_ID . $destination . $type);

                sendSms($coin_ID, $coin, "change_1h_plus", $change, $destination, $type, $name, $symbol, $user_ID);
            }
        }

        if (isset($alerts_queue['sms']["change_1h_minus"])) {
            foreach ($alerts_queue['sms']["change_1h_minus"] as $alert) {

                $coin = $alert[0];
                $change = $alert[1];
                $user_ID = $alert[2];
                $destination = $alert[3];
                $type = $alert[4];
                $name = $alert[5];
                $symbol = $alert[6];
                $coin_ID = $alert[7];
                $price_usd = $alert[8];

                // echo("\nsend sms through function: " . $coin . $user_ID . $destination . $type);

                sendSms($coin_ID, $coin, "change_1h_minus", $change, $destination, $type, $name, $symbol, $user_ID);
            }
        }

        if (isset($alerts_queue['sms']["change_24h_plus"])) {
            foreach ($alerts_queue['sms']["change_24h_plus"] as $alert) {

                $coin = $alert[0];
                $change = $alert[1];
                $user_ID = $alert[2];
                $destination = $alert[3];
                $type = $alert[4];
                $name = $alert[5];
                $symbol = $alert[6];
                $coin_ID = $alert[7];
                $price_usd = $alert[8];

                // echo("\nsend sms through function: " . $coin . $user_ID . $destination . $type);

                sendSms($coin_ID, $coin, "change_24h_plus", $change, $destination, $type, $name, $symbol, $user_ID);
            }
        }

        if (isset($alerts_queue['sms']["change_24h_minus"])) {
            foreach ($alerts_queue['sms']["change_24h_minus"] as $alert) {

                $coin = $alert[0];
                $change = $alert[1];
                $user_ID = $alert[2];
                $destination = $alert[3];
                $type = $alert[4];
                $name = $alert[5];
                $symbol = $alert[6];
                $coin_ID = $alert[7];
                $price_usd = $alert[8];

                // echo("\nsend sms through function: " . $coin . $user_ID . $destination . $type);
                
                sendSms($coin_ID, $coin, "change_24h_minus", $change, $destination, $type, $name, $symbol, $user_ID);
            }
        }   
    }

    
    //
    //
    // Send Telegram
    if (isset($alerts_queue['telegram'])) {
        if (isset($alerts_queue['telegram']["change_1h_plus"])) {
            foreach ($alerts_queue['telegram']["change_1h_plus"] as $alert) {

                $coin = $alert[0]; // slug
                $change = $alert[1];
                $user_ID = $alert[2];
                $destination = $alert[3];
                $type = $alert[4];
                $name = $alert[5];
                $symbol = $alert[6];
                $coin_ID = $alert[7];
                $price_usd = $alert[8];

                // echo("\nsend sms through function: " . $coin . $user_ID . $destination . $type);

                sendTelegram($coin_ID, $coin, "change_1h_plus", $change, $destination, $type, $name, $symbol, $user_ID, $price_usd);
            }
        }

        if (isset($alerts_queue['telegram']["change_1h_minus"])) {
            foreach ($alerts_queue['telegram']["change_1h_minus"] as $alert) {

                $coin = $alert[0];
                $change = $alert[1];
                $user_ID = $alert[2];
                $destination = $alert[3];
                $type = $alert[4];
                $name = $alert[5];
                $symbol = $alert[6];
                $coin_ID = $alert[7];
                $price_usd = $alert[8];

                // echo("\nsend sms through function: " . $coin . $user_ID . $destination . $type);

                sendTelegram($coin_ID, $coin, "change_1h_minus", $change, $destination, $type, $name, $symbol, $user_ID, $price_usd);
            }
        }

        if (isset($alerts_queue['telegram']["change_24h_plus"])) {
            foreach ($alerts_queue['telegram']["change_24h_plus"] as $alert) {

                $coin = $alert[0];
                $change = $alert[1];
                $user_ID = $alert[2];
                $destination = $alert[3];
                $type = $alert[4];
                $name = $alert[5];
                $symbol = $alert[6];
                $coin_ID = $alert[7];
                $price_usd = $alert[8];

                // echo("\nsend sms through function: " . $coin . $user_ID . $destination . $type);

                sendTelegram($coin_ID, $coin, "change_24h_plus", $change, $destination, $type, $name, $symbol, $user_ID, $price_usd);
            }
        }

        if (isset($alerts_queue['telegram']["change_24h_minus"])) {
            foreach ($alerts_queue['telegram']["change_24h_minus"] as $alert) {

                $coin = $alert[0];
                $change = $alert[1];
                $user_ID = $alert[2];
                $destination = $alert[3];
                $type = $alert[4];
                $name = $alert[5];
                $symbol = $alert[6];
                $coin_ID = $alert[7];
                $price_usd = $alert[8];

                // echo("\nsend sms through function: " . $coin . $user_ID . $destination . $type);
                
                sendTelegram($coin_ID, $coin, "change_24h_minus", $change, $destination, $type, $name, $symbol, $user_ID, $price_usd);
            }
        }   
    }

}


//
//
//
function sendEmail ($coin_ID, $coin, $change_type, $change, $destination, $alert_type, $name, $symbol, $user_ID, $price_usd) {
    // echo($coin. $change_type. $change. $destination. $alert_type);

    // echo("\nTo: " . $destination . " Subject: New Portfolio Alert " . $coin . " has " . $change_type . " by " . $change . " in " . $change_type . " symbol: " . $symbol . " name: " . $name);

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

    if ($change_type == "change_1h_plus" || $change_type == "change_24h_plus") {
        $action = "increased";
    }
    else {
        $action = "decreased";
    }

    if ($change_type == "change_1h_plus" || $change_type == "change_1h_minus") {
        $timeframe = "1h";
    }
    else {
        $timeframe = "24h";
    }

    // Email
    $mail->addAddress($destination);
    $mail->Subject  = "Alert: " . $name . " (" . $symbol . ") " . $action . " by " . $change . "% in " . $timeframe;

    $mail->Body = "Portfolio alert: 

" . $name . " (" . $symbol . ")" . " has " . $action . " by " . $change . "% in " . $timeframe . " period.

". $symbol ." current price: ". $price_usd ." USD

You can manage your portfolio alerts with your account at https://coinwink.com/portfolio

Wink,
Coinwink";

    // Send + success or error
    if(!$mail->Send()) {
        echo 'Message could not be sent. ';
        echo 'Mailer Error: ' . $mail->ErrorInfo . "\r\n";

        // EMAIL ERROR TO ADMIN
        
        $GLOBALS['mail']->ClearAllRecipients();
        $GLOBALS['mail']->addAddress($GLOBALS['adminaddress']);
        $GLOBALS['mail']->Subject  = "ERROR: Portfolio alerts - Mail error";
        $GLOBALS['mail']->Body = $mail->ErrorInfo . ' Email: ' . $destination;
        $GLOBALS['mail']->Send();

        // Create db log
        $content = $name . " (" . $symbol . ")" . " has " . $action . " by " . $change . "% in " . $timeframe . '.';

        $alert_ID = time() . '' . join('', array_map(function($value) { return $value == 1 ? mt_rand(1, 9) : mt_rand(0, 9); }, range(1, 6)));

        $time = time();

        $sql = "INSERT INTO cw_logs_alerts_portfolio (user_ID, alert_ID, coin_ID, name, content, coin, symbol, type, destination, status, error, time) VALUES ('$user_ID', '$alert_ID', '$coin_ID', '$name', '$content', '$coin', '$symbol', '$change_type', '$destination', 'error', '$mail->ErrorInfo', '$time')";
        $conn->query($sql);
    }
    else {
        echo "Message has been sent \r\n";

        // Create db log
        $content = $name . " (" . $symbol . ")" . " has " . $action . " by " . $change . "% in " . $timeframe . '.';

        $alert_ID = time() . '' . join('', array_map(function($value) { return $value == 1 ? mt_rand(1, 9) : mt_rand(0, 9); }, range(1, 6)));

        $time = time();

        $sql = "INSERT INTO cw_logs_alerts_portfolio (user_ID, alert_ID, coin_ID, name, content, coin, symbol, type, destination, status, error, time) VALUES ('$user_ID', '$alert_ID', '$coin_ID', '$name', '$content', '$coin', '$symbol', '$change_type', '$destination', 'sent', '', '$time')";
        $conn->query($sql);
    }

    $mail->ClearAllRecipients();


}


//
//
//
function sendSms ($coin_ID, $coin, $change_type, $change, $destination, $alert_type, $name, $symbol, $user_ID) {
    global $conn;
    global $client;

    # SMS destination number
    $dst = $destination;

    if ($change_type == "change_1h_plus" || $change_type == "change_24h_plus") {
        $action = "increased";
    }
    else {
        $action = "decreased";
    }

    if ($change_type == "change_1h_plus" || $change_type == "change_1h_minus") {
        $timeframe = "1h";
    }
    else {
        $timeframe = "24h";
    }

    # SMS text
    $text = 'Alert: '. $name .' ('. $symbol .') '. $action .' by '. $change .'% in '. $timeframe .' period - coinwink.com/portfolio';

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
            echo ("Error: " . $e->getMessage() . "\n");
            $status = 'error';

            // EMAIL ERROR TO ADMIN
            $GLOBALS['mail']->ClearAllRecipients();
            $GLOBALS['mail']->addAddress($GLOBALS['adminaddress']);
            $GLOBALS['mail']->Subject  = "ERROR: cron_alerts_portfolio";
            $GLOBALS['mail']->Body = "Catched exception: " . $e->getMessage();
            $GLOBALS['mail']->Send();
        }

        // -1 SMS
        $sms = $sms - 1;
        $sql = "UPDATE cw_settings SET sms = $sms WHERE user_ID = $user_ID";
        $conn->query($sql);


        // Create db log
        $content = $name . " (" . $symbol . ")" . " has " . $action . " by " . $change . "% in " . $timeframe . '.';

        $alert_ID = time() . '' . join('', array_map(function($value) { return $value == 1 ? mt_rand(1, 9) : mt_rand(0, 9); }, range(1, 6)));

        $time = time();

        $sql = "INSERT INTO cw_logs_alerts_portfolio (user_ID, alert_ID, coin_ID, name, symbol, content, coin, type, destination, status, error, time) VALUES ('$user_ID', '$alert_ID', '$coin_ID', '$name', '$symbol', '$content', '$coin', '$change_type', '$destination', 'sent', '', '$time')";
        $conn->query($sql);


        // Create db log (extra for sms only)
        $timestamp = date("Y-m-d H:i:s");
        $sql = "INSERT INTO cw_logs_alerts_sms (user_ID, type, destination, status, timestamp) VALUES ('$user_ID', 'sms_por', '$dst', '$status', '$timestamp')";
        $conn->query($sql);

        echo ("\nsms done\n");
    }

    // 2. If paid does not exist, inform the user
    else {

        // Create db log
        $content = $name . " (" . $symbol . ")" . " has " . $action . " by " . $change . "% in " . $timeframe . '.';

        $alert_ID = time() . '' . join('', array_map(function($value) { return $value == 1 ? mt_rand(1, 9) : mt_rand(0, 9); }, range(1, 6)));

        $time = time();

        $sql = "INSERT INTO cw_logs_alerts_portfolio (user_ID, alert_ID, coin_ID, name, symbol, content, coin, type, destination, status, error, time) VALUES ('$user_ID', '$alert_ID', '$coin_ID', '$name', '$symbol', '$content', '$coin', '$change_type', '$destination', 'error', 'No subs or credits', '$time')";
        $conn->query($sql);

        // Create db log (extra for sms only)
        $timestamp = date("Y-m-d H:i:s");
        $sql = "INSERT INTO cw_logs_alerts_sms (user_ID, type, destination, status, error, timestamp) VALUES ('$user_ID', 'sms_por', '$dst', 'failed', 'No subs or credits', '$timestamp')";
        $conn->query($sql);
        
        // get the user's email address
        $sql = "SELECT email FROM users WHERE id='$user_ID' limit 1";
        $result = $conn->query($sql);
        $user_email = mysqli_fetch_object($result);
        $user_email = $user_email->email;

        // Email to the user
        $GLOBALS['mail']->ClearAllRecipients();
        $GLOBALS['mail']->addAddress($user_email);
        $GLOBALS['mail']->Subject  = "Undelivered SMS alert";
        $GLOBALS['mail']->Body = "Hello,
        
Your Coinwink Portfolio SMS alert was triggered, but we couldn't deliver it because either your subscription has expired, or your account has no more monthly SMS credits left.

You can manage your subscription or buy additional monthly SMS credits at https://coinwink.com

Kind regards,
Coinwink";
        $GLOBALS['mail']->Send();

        echo ("error: no subscription");

    }
}



// Telegram template and sending
function sendTelegram($coin_ID, $coin, $change_type, $change, $destination, $alert_type, $name, $symbol, $user_ID, $price_usd) {

    global $conn;
    global $bot_api_key;
    global $bot_username;

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

    // Telegram user id
    $dst = $destination;

    if ($change_type == "change_1h_plus" || $change_type == "change_24h_plus") {
        $action = "increased";
    }
    else {
        $action = "decreased";
    }

    if ($change_type == "change_1h_plus" || $change_type == "change_1h_minus") {
        $timeframe = "1h";
    }
    else {
        $timeframe = "24h";
    }

    # Telegram text
    $text = $name .' ('. $symbol .') '. $action .' by '. $change .'% in '. $timeframe .' period.
    
'. $symbol .' current price: '. $price_usd .' USD

Manage your portfolio alerts at https://coinwink.com/portfolio

Wink,
Coinwink';

    $status = 'sent';

    // Telegram send
    try {
        
        // Create Telegram API object
        $telegram = new Longman\TelegramBot\Telegram($bot_api_key, $bot_username);

        $result = Longman\TelegramBot\Request::sendMessage([
            'chat_id' => $dst,
            'text'    => $text,
        ]);


        // Create db log
        $content = $name . " (" . $symbol . ")" . " has " . $action . " by " . $change . "% in " . $timeframe . '.';

        $alert_ID = time() . '' . join('', array_map(function($value) { return $value == 1 ? mt_rand(1, 9) : mt_rand(0, 9); }, range(1, 6)));

        $time = time();

        $sql = "INSERT INTO cw_logs_alerts_portfolio (user_ID, alert_ID, coin_ID, name, symbol, content, coin, type, destination, status, error, time) VALUES ('$user_ID', '$alert_ID', '$coin_ID', '$name', '$symbol', '$content', '$coin', '$change_type', '$destination', 'sent', '', '$time')";
        $conn->query($sql);

        echo ("\ntelegram done\n");


    } catch (Exception $e) {
        
        echo 'Message could not be sent. ';
        echo 'Mailer Error: ' . $mail->ErrorInfo . "\r\n";

        // EMAIL ERROR TO ADMIN
        
        $GLOBALS['mail']->ClearAllRecipients();
        $GLOBALS['mail']->addAddress($GLOBALS['adminaddress']);
        $GLOBALS['mail']->Subject  = "ERROR: Portfolio alerts - Mail error";
        $GLOBALS['mail']->Body = $mail->ErrorInfo . ' Email: ' . $destination;
        $GLOBALS['mail']->Send();
        $GLOBALS['mail']->ClearAllRecipients();

        // Create db log
        $content = $name . " (" . $symbol . ")" . " has " . $action . " by " . $change . "% in " . $timeframe . '.';

        $alert_ID = time() . '' . join('', array_map(function($value) { return $value == 1 ? mt_rand(1, 9) : mt_rand(0, 9); }, range(1, 6)));

        $time = time();

        $sql = "INSERT INTO cw_logs_alerts_portfolio (user_ID, alert_ID, coin_ID, name, content, coin, symbol, type, destination, status, error, time) VALUES ('$user_ID', '$alert_ID', '$coin_ID', '$name', '$content', '$coin', '$symbol', '$change_type', '$destination', 'error', '$mail->ErrorInfo', '$time')";
        $conn->query($sql);

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
echo ("\r\n\r\n\r\nMemory used: " . convert(memory_get_usage(true)));


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
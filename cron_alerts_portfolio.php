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
include_once "auth_sql.php";
include_once "auth_email.php";
include_once 'auth_twilio.php';


// Load Twilio
include_once 'lib/twilio/vendor/autoload.php'; // Loads the library 
use Twilio\Rest\Client;
$client = new Client($account_sid, $auth_token);


// Select coin price data from db - 1st part
$sql = "SELECT json FROM cw_data_cmc WHERE ID = 1";
$result = $conn->query($sql);
foreach($result as $row)
{
    $dataCMC = unserialize($row["json"]);
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
    
    foreach($dbalerts as $user_alerts)
    {
        // for each user get portfolio
        $user_ID = $user_alerts['user_ID'];
        $sql = "SELECT * FROM cw_settings WHERE user_ID = '".$user_ID."'";
        $result = mysqli_fetch_assoc($conn->query($sql));
        $portfolio = $result["portfolio"];
        $portfolio = stripslashes($portfolio);
        $portfolio = json_decode($portfolio);
        
        $newAlertsArray[$user_ID] = $user_alerts;

        if (isset($portfolio)) {
            foreach ($portfolio as $coin) {
                $newAlertsArray[$user_ID]['coin_slugs'][] = $coin->slug;
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
                                        $alerts_queue[$user["type"]]['change_1h_plus'][] = [ $coin, $user["change_1h_plus"], $user["user_ID"], $user["destination"], $user["type"], $jsoncoin["name"], $jsoncoin["symbol"]];
                                    
                                        // if ($user["type"] == "email") {
                                        //     $alerts_queue["email"]['change_1h_plus'][] = [ $coin, $user["change_1h_plus"], $user["user_ID"], $user["destination"], $user["type"], $jsoncoin["name"], $jsoncoin["symbol"]];
                                        // }
                                        // else {
                                        //     $alerts_queue["sms"]['change_1h_plus'][] = [ $coin, $user["change_1h_plus"], $user["user_ID"], $user["destination"], $user["type"], $jsoncoin["name"], $jsoncoin["symbol"]];
                                        // }


                                        // Create db log
                                        $change_1h_plus = $user["change_1h_plus"];
                                        $sql = "INSERT INTO cw_logs_alerts_portfolio (user_ID, coin, type, destination, status, error, timestamp) VALUES ('$user_ID', '$coin', 'change_1h_plus', '$destination', 'sent', '', '$timestamp')";
                                        $conn->query($sql);
                                    }
                                }
                                else {
                                    $alerts_queue[$user["type"]]['change_1h_plus'][] = [ $coin, $user["change_1h_plus"], $user["user_ID"], $user["destination"], $user["type"], $jsoncoin["name"], $jsoncoin["symbol"]];
                                    
                                    // Create db log
                                    $change_1h_plus = $user["change_1h_plus"];
                                    $sql = "INSERT INTO cw_logs_alerts_portfolio (user_ID, coin, type, destination, status, error, timestamp) VALUES ('$user_ID', '$coin', 'change_1h_plus', '$destination', 'sent', '', '$timestamp')";
                                    $conn->query($sql);
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
                                        $alerts_queue[$user["type"]]['change_1h_minus'][] = [ $coin, $user["change_1h_minus"], $user["user_ID"], $user["destination"], $user["type"], $jsoncoin["name"], $jsoncoin["symbol"]];
                                    
                                        // Create db log
                                        $change_1h_minus = $user["change_1h_minus"];
                                        $sql = "INSERT INTO cw_logs_alerts_portfolio (user_ID, coin, type, destination, status, error, timestamp) VALUES ('$user_ID', '$coin', 'change_1h_minus', '$destination', 'sent', '', '$timestamp')";
                                        $conn->query($sql);
                                    }
                                }
                                else {
                                    $alerts_queue[$user["type"]]['change_1h_minus'][] = [ $coin, $user["change_1h_minus"], $user["user_ID"], $user["destination"], $user["type"], $jsoncoin["name"], $jsoncoin["symbol"]];
                                    
                                    // Create db log
                                    $change_1h_minus = $user["change_1h_minus"];
                                    $sql = "INSERT INTO cw_logs_alerts_portfolio (user_ID, coin, type, destination, status, error, timestamp) VALUES ('$user_ID', '$coin', 'change_1h_minus', '$destination', 'sent', '', '$timestamp')";
                                    $conn->query($sql);
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
                                        $alerts_queue[$user["type"]]['change_24h_plus'][] = [ $coin, $user["change_24h_plus"], $user["user_ID"], $user["destination"], $user["type"], $jsoncoin["name"], $jsoncoin["symbol"]];
                                    
                                        // Create db log
                                        $change_24h_plus = $user["change_24h_plus"];
                                        $sql = "INSERT INTO cw_logs_alerts_portfolio (user_ID, coin, type, destination, status, error, timestamp) VALUES ('$user_ID', '$coin', 'change_24h_plus', '$destination', 'sent', '', '$timestamp')";
                                        $conn->query($sql);
                                    }
                                }
                                else {
                                    $alerts_queue[$user["type"]]['change_24h_plus'][] = [ $coin, $user["change_24h_plus"], $user["user_ID"], $user["destination"], $user["type"], $jsoncoin["name"], $jsoncoin["symbol"]];
                                    
                                    // Create db log
                                    $change_24h_plus = $user["change_24h_plus"];
                                    $sql = "INSERT INTO cw_logs_alerts_portfolio (user_ID, coin, type, destination, status, error, timestamp) VALUES ('$user_ID', '$coin', 'change_24h_plus', '$destination', 'sent', '', '$timestamp')";
                                    $conn->query($sql);
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
                                        $alerts_queue[$user["type"]]['change_24h_minus'][] = [ $coin, $user["change_24h_minus"], $user["user_ID"], $user["destination"], $user["type"], $jsoncoin["name"], $jsoncoin["symbol"]];
                                    
                                        // Create db log
                                        $change_24h_minus = $user["change_24h_minus"];
                                        $sql = "INSERT INTO cw_logs_alerts_portfolio (user_ID, coin, type, destination, status, error, timestamp) VALUES ('$user_ID', '$coin', 'change_24h_minus', '$destination', 'sent', '', '$timestamp')";
                                        $conn->query($sql);
                                    }
                                }
                                else {
                                    $alerts_queue[$user["type"]]['change_24h_minus'][] = [ $coin, $user["change_24h_minus"], $user["user_ID"], $user["destination"], $user["type"], $jsoncoin["name"], $jsoncoin["symbol"]];
                                    
                                    // Create db log
                                    $change_24h_minus = $user["change_24h_minus"];
                                    $sql = "INSERT INTO cw_logs_alerts_portfolio (user_ID, coin, type, destination, status, error, timestamp) VALUES ('$user_ID', '$coin', 'change_24h_minus', '$destination', 'sent', '', '$timestamp')";
                                    $conn->query($sql);
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

                // echo("\nsend email through function: " . $coin . $user_ID . $destination . $type);

                sendEmail($coin, "change_1h_plus", $change, $destination, $type, $name, $symbol);
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

                // echo("\nsend email through function: " . $coin . $user_ID . $destination . $type);

                sendEmail($coin, "change_1h_minus", $change, $destination, $type, $name, $symbol);
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

                // echo("\nsend email through function: " . $coin . $user_ID . $destination . $type);

                sendEmail($coin, "change_24h_plus", $change, $destination, $type, $name, $symbol);
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

                // echo("\nsend email through function: " . $coin . $user_ID . $destination . $type);
                
                sendEmail($coin, "change_24h_minus", $change, $destination, $type, $name, $symbol);
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

                // echo("\nsend sms through function: " . $coin . $user_ID . $destination . $type);

                sendSms($coin, "change_1h_plus", $change, $destination, $type, $name, $symbol, $user_ID);
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

                // echo("\nsend sms through function: " . $coin . $user_ID . $destination . $type);

                sendSms($coin, "change_1h_minus", $change, $destination, $type, $name, $symbol, $user_ID);
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

                // echo("\nsend sms through function: " . $coin . $user_ID . $destination . $type);

                sendSms($coin, "change_24h_plus", $change, $destination, $type, $name, $symbol, $user_ID);
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

                // echo("\nsend sms through function: " . $coin . $user_ID . $destination . $type);
                
                sendSms($coin, "change_24h_minus", $change, $destination, $type, $name, $symbol, $user_ID);
            }
        }
    }
}


//
//
//
function sendEmail ($coin, $change_type, $change, $destination, $alert_type, $name, $symbol) {
    // echo($coin. $change_type. $change. $destination. $alert_type);

    // echo("\nTo: " . $destination . " Subject: New Portfolio Alert " . $coin . " has " . $change_type . " by " . $change . " in " . $change_type . " symbol: " . $symbol . " name: " . $name);

    global $adminaddress;
    global $mail;
    global $conn;
    
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

    $mail->Body = "Coinwink portfolio alert: 

" . $name . " (" . $symbol . ")" . " has " . $action . " by " . $change . "% in " . $timeframe . ".

You can manage your alert(-s) with your account at https://coinwink.com

Wink,
Coinwink";

    // Send + success or error
    if(!$mail->Send()) {
        echo 'Message could not be sent. ';
        echo 'Mailer Error: ' . $mail->ErrorInfo . "\r\n";

        // mail($adminaddress,"ERROR: cron_alerts_email_cur", $mail->ErrorInfo . ' Email: ' . $email);

        // EMAIL ERROR TO ADMIN
        $GLOBALS['mail']->addAddress($GLOBALS['adminaddress']);
        $GLOBALS['mail']->Subject  = "ERROR: Portfolio alerts - Mail error";
        $GLOBALS['mail']->Body = $mail->ErrorInfo . ' Email: ' . $destination;
        $GLOBALS['mail']->Send();

    }
    else {
        echo "Message has been sent \r\n";
    }
    $mail->ClearAllRecipients();
}


//
//
//
function sendSms ($coin, $change_type, $change, $destination, $alert_type, $name, $symbol, $user_ID) {
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
    $text = 'Alert: '. $name .' ('. $symbol .') '. $action .' by '. $change .'% in '. $timeframe .' period - coinwink.com';

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
            echo ("Error: " . $e->getMessage() . "\n");
            $status = 'error';

            // EMAIL ERROR TO ADMIN
            $GLOBALS['mail']->addAddress($GLOBALS['adminaddress']);
            $GLOBALS['mail']->Subject  = "ERROR: cron_alerts_portfolio";
            $GLOBALS['mail']->Body = "Catched exception: " . $e->getMessage();
            $GLOBALS['mail']->Send();
        }

        // -1 SMS
        $sms = $sms - 1;
        $sql = "UPDATE cw_settings SET sms = $sms WHERE user_ID = $user_ID";
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
        $timestamp = date("Y-m-d H:i:s");
        $sql = "INSERT INTO cw_logs_alerts_sms (user_ID, type, destination, status, error, timestamp) VALUES ('$user_ID', 'sms_por', '$dst', 'failed', 'No subs or credits', '$timestamp')";
        $conn->query($sql);
        
        // get the user's email address
        $sql = "SELECT user_email FROM wp_users WHERE ID='$user_ID' limit 1";
        $result = $conn->query($sql);
        $user_email = mysqli_fetch_object($result);
        $user_email = $user_email->user_email;

        // Email to the user
        $GLOBALS['mail']->addAddress($user_email);
        $GLOBALS['mail']->Subject  = "Undelivered SMS alert";
        $GLOBALS['mail']->Body = "Hello,
        
Your recent Coinwink SMS alert was triggered but not delivered, because your subscription has expired.

You can create a new subscription at https://coinwink.com

Wink,
Coinwink";
        $GLOBALS['mail']->Send();

        // @todo-feature: Renew your subscription with the bonus code for 5 usd discount for the first month: 4adsawea65sa4d.

        echo ("error: no subscription");
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
<?php


include_once "auth_sql.php";
include_once "auth_email.php";


// Get your free API keys:
// A: apilayer.net
// B: currconv.com
$plan_a_key = "YOUR_API_KEY";
$plan_b_key = "YOUR_API_KEY";


//
// PLAN A
//

$ch = curl_init(); 
curl_setopt($ch, CURLOPT_URL, "http://apilayer.net/api/live?access_key=".$plan_a_key."&currencies=EUR,GBP,CAD,AUD,BRL,MXN,JPY,SGD&source=USD&format=1"); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

if ($cw_env == "dev") {
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
}

$output = curl_exec($ch);
curl_close($ch);

$output = json_decode($output, true);

if (!$output["success"]) {
    // var_dump($output);
    echo("Error PLAN A\n");

    // EMAIL ERROR TO ADMIN
    $GLOBALS['mail']->addAddress($GLOBALS['adminaddress']);
    $GLOBALS['mail']->Subject  = "ERROR: cron_data_cur_rates - PLAN A";
    $GLOBALS['mail']->Body = "Currency rates Error for PLAN A" . "\n\nCode: " . $output["error"]["code"] . "\nType: " . $output["error"]["type"] . "\nInfo: " . $output["error"]["info"];
    $GLOBALS['mail']->Send();

    // Go for plan B
    planB();
}
else {
    $EUR = $output["quotes"]["USDEUR"];
    $GBP = $output["quotes"]["USDGBP"];
    $CAD = $output["quotes"]["USDCAD"];
    $AUD = $output["quotes"]["USDAUD"];
    $BRL = $output["quotes"]["USDBRL"];
    $MXN = $output["quotes"]["USDMXN"];
    $JPY = $output["quotes"]["USDJPY"];
    $SGD = $output["quotes"]["USDSGD"];

    echo("PLAN A -> EUR:" . $EUR . " GBP:" . $GBP . " CAD:" . $CAD . " AUD:" . $AUD . " BRL:" . $BRL . " MXN:" . $MXN . " JPY:" . $JPY . " SGD:" . $SGD);
    
    // @todo: extra validation to see if the returned data is appropriate

    // Update DB
    $sql = "UPDATE cw_data_cur_rates SET EUR = $EUR, GBP = $GBP, CAD = $CAD, AUD = $AUD, BRL = $BRL, MXN = $MXN, JPY = $JPY, SGD = $SGD WHERE ID = 1";
    $GLOBALS['conn']->query($sql);

}


//
// PLAN B
//

function planB() {

    $ch = curl_init(); 
    curl_setopt($ch, CURLOPT_URL, "https://free.currconv.com/api/v7/convert?q=USD_EUR,USD_GBP&compact=ultra&apiKey=".$plan_b_key); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    
    global $cw_env;

    if ($cw_env == "dev") {
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    }

    $output = curl_exec($ch);
    
    $output = json_decode($output, true);
    
    if (sizeof($output) < 1) {
        echo("Error PLAN B");

        // EMAIL ERROR TO ADMIN
        $GLOBALS['mail']->addAddress($GLOBALS['adminaddress']);
        $GLOBALS['mail']->Subject  = "ERROR: cron_data_cur_rates - PLAN B";
        $GLOBALS['mail']->Body = "Currency rates Error for PLAN B";
        $GLOBALS['mail']->Send();

        exit();
    }

    $EUR = $output["USD_EUR"];
    $GBP = $output["USD_GBP"];
    
    

    curl_setopt($ch, CURLOPT_URL, "https://free.currconv.com/api/v7/convert?q=USD_CAD,USD_AUD&compact=ultra&apiKey=".$plan_b_key); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    
    if ($cw_env == "dev") {
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    }
    
    $output = curl_exec($ch);

    $output = json_decode($output, true);
    
    if (sizeof($output) < 1) {
        echo("Error PLAN B");
        
        // EMAIL ERROR TO ADMIN
        $GLOBALS['mail']->addAddress($GLOBALS['adminaddress']);
        $GLOBALS['mail']->Subject  = "ERROR: cron_data_cur_rates - PLAN B";
        $GLOBALS['mail']->Body = "Currency rates Error for PLAN B";
        $GLOBALS['mail']->Send();

        exit();
    }
    
    $CAD = $output["USD_CAD"];
    $AUD = $output["USD_AUD"];



    curl_setopt($ch, CURLOPT_URL, "https://free.currconv.com/api/v7/convert?q=USD_BRL,USD_MXN&compact=ultra&apiKey=".$plan_b_key); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    
    if ($cw_env == "dev") {
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    }
    
    $output = curl_exec($ch);
    
    $output = json_decode($output, true);
    
    if (sizeof($output) < 1) {
        echo("Error PLAN B");
        
        // EMAIL ERROR TO ADMIN
        $GLOBALS['mail']->addAddress($GLOBALS['adminaddress']);
        $GLOBALS['mail']->Subject  = "ERROR: cron_data_cur_rates - PLAN B";
        $GLOBALS['mail']->Body = "Currency rates Error for PLAN B";
        $GLOBALS['mail']->Send();

        exit();
    }
    
    $BRL = $output["USD_BRL"];
    $MXN = $output["USD_MXN"];



    curl_setopt($ch, CURLOPT_URL, "https://free.currconv.com/api/v7/convert?q=USD_JPY,USD_SGD&compact=ultra&apiKey=".$plan_b_key); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    
    if ($cw_env == "dev") {
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    }
    
    $output = curl_exec($ch);
    curl_close($ch);
    
    $output = json_decode($output, true);
    
    if (sizeof($output) < 1) {
        echo("Error PLAN B");
        
        // EMAIL ERROR TO ADMIN
        $GLOBALS['mail']->addAddress($GLOBALS['adminaddress']);
        $GLOBALS['mail']->Subject  = "ERROR: cron_data_cur_rates - PLAN B";
        $GLOBALS['mail']->Body = "Currency rates Error for PLAN B";
        $GLOBALS['mail']->Send();

        exit();
    }
    
    $JPY = $output["USD_JPY"];
    $SGD = $output["USD_SGD"];


    
    echo("PLAN B -> EUR: " . $EUR . " GBP: " . $GBP . " CAD: " . $CAD . " AUD: " . $AUD . " BRL: " . $BRL . " MXN: " . $MXN . " JPY: " . $JPY . " SGD: " . $SGD);
    

    // Update DB
    $sql = "UPDATE cw_data_cur_rates SET EUR = $EUR, GBP = $GBP, CAD = $CAD, AUD = $AUD, BRL = $BRL, MXN = $MXN, JPY = $JPY, SGD = $SGD WHERE ID = 1";
    $GLOBALS['conn']->query($sql);

}


?>
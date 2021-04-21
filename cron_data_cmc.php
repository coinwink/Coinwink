<?php

// Check execution time - Define time start
$time_start = microtime(true);

// Connect to Mysql
include_once "auth_sql.php";
include_once "auth_email.php";
include_once "auth_cmc.php";


// GET CMC DATA
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://pro-api.coinmarketcap.com/v1/cryptocurrency/listings/latest?sort=market_cap&start=1&limit=3600&convert=USD"); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_ENCODING, "");
curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "cache-control: no-cache",
    "x-cmc_pro_api_key: $cmc_pro_api_key"
));
if ($cw_env == "dev") {
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
}

$response = curl_exec($ch);
$err = curl_error($ch);

curl_close($ch);

if ($err) {
    echo "Error Nr.1 (cURL): " . $err;
    
    // EMAIL ERROR TO ADMIN
    $GLOBALS['mail']->addAddress($GLOBALS['adminaddress']);
    $GLOBALS['mail']->Subject  = "ERROR: cron_data_cmc";
    $GLOBALS['mail']->Body = "Coinmarketcap API Error Nr.1\n\n" . $err;
    $GLOBALS['mail']->Send();

    exit();
}
else {
	// Remove possible single quotes from JSON string and decode
	$output = str_replace("'", "", $response);
	$outputdecoded = json_decode($output, true);

    if (empty($outputdecoded["data"])) {
        echo "Error Nr.2";

        // EMAIL ERROR TO ADMIN
        $GLOBALS['mail']->addAddress($GLOBALS['adminaddress']);
        $GLOBALS['mail']->Subject  = "ERROR: cron_data_cmc";
        $GLOBALS['mail']->Body = "Coinmarketcap API Error Nr.2\n\n" . $outputdecoded["status"]["error_code"] . ": " . $outputdecoded["status"]["error_message"];
        $GLOBALS['mail']->Send();

        exit();
    }

    $dataArray = $outputdecoded["data"];

    // Get USD/BTC rate
    foreach ($dataArray as &$jsoncoin) {
        if ($jsoncoin["id"] == 1) {
            $BTC = 1 / $jsoncoin["quote"]["USD"]["price"];
        }
    }

    // Get USD/ETH rate
    foreach ($dataArray as &$jsoncoin) {
        if ($jsoncoin["id"] == 1027) {
            $ETH = 1 / $jsoncoin["quote"]["USD"]["price"];
        }
    }

    // Remove unnecessary data to save space & update with additional data
    function unsetSomeProperties($dataArray, $BTC, $ETH) {
        foreach ($dataArray as &$jsoncoin) {
            
            unset($jsoncoin["cmc_rank"]);
            unset($jsoncoin["date_added"]);
            unset($jsoncoin["circulating_supply"]);
            unset($jsoncoin["total_supply"]);
            unset($jsoncoin["last_updated"]);
            unset($jsoncoin["max_supply"]);
            unset($jsoncoin["num_market_pairs"]);
            unset($jsoncoin["tags"]);
            unset($jsoncoin["platform"]);

            $jsoncoin["price_usd"] = $jsoncoin["quote"]["USD"]["price"];

            if ($jsoncoin["name"] == "Bitcoin") { $jsoncoin["price_btc"] = 1; }
            else { $jsoncoin["price_btc"] = $jsoncoin["quote"]["USD"]["price"] * $BTC; }

            if ($jsoncoin["name"] == "Ethereum") { $jsoncoin["price_eth"] = 1; }
            else { $jsoncoin["price_eth"] = $jsoncoin["quote"]["USD"]["price"] * $ETH; }
            
            $jsoncoin["text"] = $jsoncoin["name"] . ' (' . $jsoncoin["symbol"] . ')';

            $jsoncoin["per_1h"] = $jsoncoin["quote"]["USD"]["percent_change_1h"];
            $jsoncoin["per_24h"] = $jsoncoin["quote"]["USD"]["percent_change_24h"];
            $jsoncoin["per_7d"] = $jsoncoin["quote"]["USD"]["percent_change_7d"];

            $jsoncoin["vol"] = $jsoncoin["quote"]["USD"]["volume_24h"];

            $jsoncoin["cap"] = $jsoncoin["quote"]["USD"]["market_cap"];


            unset($jsoncoin["quote"]);
        }
        return ($dataArray);
    }
    
    $dataArray = unsetSomeProperties($dataArray, $BTC, $ETH);

    // Serialize and update db
    // $outputserialized = serialize($dataArray);
    $outputserialized = json_encode($dataArray);
    $sqljson = "UPDATE cw_data_cmc SET json = '$outputserialized' WHERE ID = 1";
    $conn->query($sqljson);
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

?>
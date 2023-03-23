<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

// 
//  EMAIL - CURRENCY
// 
Route::post('/alert_email_cur', function (Request $request) {

    // Get user IP for rate limiter
    if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) { 
        $ip = $_SERVER["HTTP_CF_CONNECTING_IP"];
    }
    else {
        $ip = 'local';
    }
    
    $req_count = DB::table('cw_rate_limiter_alerts')->where('ip', $ip)->count();

    if ($req_count > 10) {
        return("Limit error: IP");
    }

    $coin = htmlspecialchars($request['coinName']);
    $coin_id = htmlspecialchars($request['coinId']);
    $symbol = htmlspecialchars($request['coinSymbol']);
    if ($request['belowPrice'] != null) {
        $below = str_replace(',', '.', htmlspecialchars($request['belowPrice']));
    }
    else {
        $below = "";
    }
    $below_currency = htmlspecialchars($request['belowCur']);
    if ($request['abovePrice'] != null) {
        $above = str_replace(',', '.', htmlspecialchars($request['abovePrice']));
    }
    else {
        $above = "";
    }
    $above_currency = htmlspecialchars($request['aboveCur']);
    $email = htmlspecialchars($request['email']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo("Email error");
        exit();
    }

    // Get alerts count for user without acc
    // $alerts_count_cur = $wpdb->get_var( "SELECT COUNT(*) FROM cw_alerts_email_cur WHERE email = '".$email."'" );
    // $alerts_count_per = $wpdb->get_var( "SELECT COUNT(*) FROM cw_alerts_email_per WHERE email = '".$email."'" );
    $alerts_count_cur = DB::table('cw_alerts_email_cur')->where('email', $email)->count();
    $alerts_count_per = DB::table('cw_alerts_email_per')->where('email', $email)->count();
    $alerts_count = $alerts_count_cur + $alerts_count_per;
    if ($alerts_count >= 5) {
        echo("Limit error");
        exit();
    }
    
    $unique_id = DB::table('cw_settings')->where('email', $email)->value('unique_id');
    if (!$unique_id) {
        $unique_id = DB::table('cw_alerts_email_cur')->where('email', $email)->value('unique_id');
        if (!$unique_id) {
            $unique_id = DB::table('cw_alerts_email_per')->where('email', $email)->value('unique_id');
            if (!$unique_id) {
                $unique_id = uniqid();
            }
        }
    } 

    $timestamp = date("Y-m-d H:i:s");

    if (DB::table('cw_alerts_email_cur')->insert(array(
    'coin' => $coin,
    'coin_id' => $coin_id,
    'symbol' => $symbol,
    'below' => $below,
    'below_currency' => $below_currency,
    'above' => $above,
    'above_currency' => $above_currency,
    'below_sent' => '',
    'above_sent' => '',
    'email' => $email,
    'unique_id' => $unique_id,
    'user_id' => 0,
    'timestamp' => $timestamp )) === FALSE) {
        echo "DB Error";
    }
    else {
        $to = $email;
        $subject = 'New alert for '. $coin .' ('. $symbol .')';
        $msg_part = "";

        if ($below && !$above) {
            $msg_part = 'an email alert when '. $coin .' ('. $symbol .') price will be below: '. $below .' '. $below_currency .'.';
        }
        else if ($above && !$below) {
            $msg_part = 'an email alert when '. $coin .' ('. $symbol .') price will be above: '. $above .' '. $above_currency .'.';
        }
        else if ($below && $above) {
            $msg_part = 'email alerts when '. $coin .' ('. $symbol .') price will be above: '. $above .' '. $above_currency .' and below: '. $below .' '. $below_currency .'.';
        }

        $message = ''. $coin .' ('. $symbol .') price alert has been created.

You will receive '.$msg_part.'

You can manage your alert(-s) with a free Coinwink account: https://coinwink.com/account

Wink,
Coinwink';

        Mail::raw($message, function ($message) use ($subject, $to) {
            $message->subject($subject)->to($to);
        });

    }

    DB::table('cw_rate_limiter_alerts')->insert(array(
        'ip' => $ip,
        'action' => 'free_cur_alert',
        'unique_id' => $unique_id
    ));

    return('success');

});

// 
//  EMAIL - CURRENCY - ACC
// 
Route::middleware(['auth:sanctum', 'verified'])->post('/alert_email_cur_acc', function (Request $request) {
    $id_user = Auth::user()->id;

    $coin = htmlspecialchars($request['coinName']);
    $coin_id = htmlspecialchars($request['coinId']);
    $symbol = htmlspecialchars($request['coinSymbol']);
    if ($request['belowPrice'] != null) {
        $below = str_replace(',', '.', htmlspecialchars($request['belowPrice']));
    }
    else {
        $below = "";
    }
    $below_currency = htmlspecialchars($request['belowCur']);
    if ($request['abovePrice'] != null) {
        $above = str_replace(',', '.', htmlspecialchars($request['abovePrice']));
    }
    else {
        $above = "";
    }
    $above_currency = htmlspecialchars($request['aboveCur']);
    $email = htmlspecialchars($request['email']);
    $timestamp = date("Y-m-d H:i:s");
    $user_ID = $id_user;

    // Get alerts count for user with acc
    $settings = DB::table('cw_settings')->where('user_ID', $user_ID)->get();
    $settings = json_decode($settings,true);
    $subs = $settings[0]['subs'];
    $unique_id = $settings[0]['unique_id'];

    if ($subs == 0) {
        // $alerts_count_cur = DB::select("SELECT COUNT(*) FROM cw_alerts_email_cur WHERE unique_id = '".$unique_id."'");
        // $alerts_count_per = DB::select("SELECT COUNT(*) FROM cw_alerts_email_per WHERE unique_id = '".$unique_id."'" );
        $alerts_count_cur = DB::table('cw_alerts_email_cur')->where('unique_id', $unique_id)->count();
        $alerts_count_per = DB::table('cw_alerts_email_per')->where('unique_id', $unique_id)->count();
        $alerts_count = $alerts_count_cur + $alerts_count_per;
        
        // Special users
        if ($user_ID == 24301 || $user_ID == 19762 || $user_ID == 7929) {
            if ($alerts_count >= 10) {
                echo("Limit error");
                exit();
            }
        }
        else if ($alerts_count >= 5) {
        // if ($alerts_count >= 10) {
            echo("Limit error");
            exit();
        }
    }

    // Save email for later use
    DB::table('cw_settings')->where('unique_id', $unique_id)->update(['email' => $email]);

    DB::table('cw_alerts_email_cur')->insert(array(
        'coin' => $coin,
        'coin_id' => $coin_id,
        'symbol' => $symbol,
        'below' => $below,
        'below_currency' => $below_currency,
        'above' => $above,
        'above_currency' => $above_currency,
        'below_sent' => '',
        'above_sent' => '',
        'email' => $email,
        'unique_id' => $unique_id,
        'user_id' => $user_ID,
        'timestamp' => $timestamp 
    ));

    return('success');
});


//
// EMAIL - PERCENTAGE
//
Route::post('/alert_email_per', function (Request $request) {

    // Get user IP for rate limiter
    if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) { 
        $ip = $_SERVER["HTTP_CF_CONNECTING_IP"];
    }
    else {
        $ip = 'local';
    }

    $req_count = DB::table('cw_rate_limiter_alerts')->where('ip', $ip)->count();

    if ($req_count > 10) {
        return("Limit error: IP");
    }

    $coin = htmlspecialchars($request['coinName']);
    $coin_id = htmlspecialchars($request['coinId']);
    $symbol = htmlspecialchars($request['coinSymbol']);

    $price_set_btc = htmlspecialchars($request['price_set_btc']);
    $price_set_usd = htmlspecialchars($request['price_set_usd']);
    $price_set_eth = htmlspecialchars($request['price_set_eth']);
    $search  = array(',', '-', '+');
    $replace = array('.', '', '');
    if ($request['plus_percent'] != null) {
        $plus_percent = str_replace($search, $replace, htmlspecialchars($request['plus_percent']));
    }
    else {
        $plus_percent = "";
    }
    $plus_change = htmlspecialchars($request['plus_change']);
    $plus_compared = htmlspecialchars($request['plus_compared']);
    if ($request['minus_percent'] != null) {
        $minus_percent = str_replace($search, $replace, htmlspecialchars($request['minus_percent']));
    }
    else {
        $minus_percent = "";
    }
    $minus_change = htmlspecialchars($request['minus_change']);
    $minus_compared = htmlspecialchars($request['minus_compared']);

    $email = htmlspecialchars($request['email']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo("Email error");
        exit();
    }

    $alerts_count_cur = DB::table('cw_alerts_email_cur')->where('email', $email)->count();
    $alerts_count_per = DB::table('cw_alerts_email_per')->where('email', $email)->count();
    $alerts_count = $alerts_count_cur + $alerts_count_per;
    if ($alerts_count >= 5) {
        echo("Limit error");
        exit();
    }

    $unique_id = DB::table('cw_settings')->where('email', $email)->value('unique_id');
    if (!$unique_id) {
        $unique_id = DB::table('cw_alerts_email_cur')->where('email', $email)->value('unique_id');
        if (!$unique_id) {
            $unique_id = DB::table('cw_alerts_email_per')->where('email', $email)->value('unique_id');
            if (!$unique_id) {
                $unique_id = uniqid();
            }
        }
    } 
    
    $timestamp = date("Y-m-d H:i:s");

    if (DB::table('cw_alerts_email_per')->insert(array(
    'coin' => $coin,
    'coin_id' => $coin_id,
    'symbol' => $symbol,
    'price_set_btc' => $price_set_btc,
    'price_set_usd' => $price_set_usd,
    'price_set_eth' => $price_set_eth,
    'plus_percent' => $plus_percent,
    'plus_change' => $plus_change,
    'plus_compared' => $plus_compared,
    'minus_percent' => $minus_percent,
    'minus_change' => $minus_change,
    'minus_compared' => $minus_compared,
    'plus_sent' => '',
    'minus_sent' => '',
    'email' => $email,
    'unique_id' => $unique_id,
    'user_id' => 0,
    'timestamp' => $timestamp )) === FALSE) {
        return "Error";
    }

    $to = $email;
    $subject = 'New percentage alert for '. $coin .' ('. $symbol .')';

    $message = 'A new '. $coin .' ('. $symbol .') percentage alert has been created.

You can manage your alert(-s) with a free Coinwink account: https://coinwink.com/account

Wink,
Coinwink';

    Mail::raw($message, function ($message) use ($subject, $to) {
        $message->subject($subject)->to($to);
    });


    DB::table('cw_rate_limiter_alerts')->insert(array(
        'ip' => $ip,
        'action' => 'free_per_alert',
        'unique_id' => $unique_id
    ));

    return('success');

});


//
// EMAIL - PERCENTAGE - ACC
//
Route::post('/alert_email_per_acc', function (Request $request) {
    $id_user = Auth::user()->id;

    $coin = htmlspecialchars($request['coinName']);
    $coin_id = htmlspecialchars($request['coinId']);
    $symbol = htmlspecialchars($request['coinSymbol']);

    $price_set_btc = htmlspecialchars($request['price_set_btc']);
    $price_set_usd = htmlspecialchars($request['price_set_usd']);
    $price_set_eth = htmlspecialchars($request['price_set_eth']);
    $search  = array(',', '-', '+');
    $replace = array('.', '', '');
    if ($request['plus_percent'] != null) {
        $plus_percent = str_replace($search, $replace, htmlspecialchars($request['plus_percent']));
    }
    else {
        $plus_percent = "";
    }
    $plus_change = htmlspecialchars($request['plus_change']);
    $plus_compared = htmlspecialchars($request['plus_compared']);
    if ($request['minus_percent'] != null) {
        $minus_percent = str_replace($search, $replace, htmlspecialchars($request['minus_percent']));
    }
    else {
        $minus_percent = "";
    }
    $minus_change = htmlspecialchars($request['minus_change']);
    $minus_compared = htmlspecialchars($request['minus_compared']);

    $email = htmlspecialchars($request['email']);

    $timestamp = date("Y-m-d H:i:s");
    $user_ID = $id_user;

    // Get alerts count for user with acc
    $settings = DB::table('cw_settings')->where('user_ID', $user_ID)->get();
    $settings = json_decode($settings,true);
    $subs = $settings[0]['subs'];
    $unique_id = $settings[0]['unique_id'];

    // Get alerts count for user with acc
    if ($subs == 0) {
        $alerts_count_cur = DB::table('cw_alerts_email_cur')->where('unique_id', $unique_id)->count();
        $alerts_count_per = DB::table('cw_alerts_email_per')->where('unique_id', $unique_id)->count();
        $alerts_count = $alerts_count_cur + $alerts_count_per;
        
        // Special users
        if ($user_ID == 24301 || $user_ID == 19762 || $user_ID == 7929) {
            if ($alerts_count >= 10) {
                echo("Limit error");
                exit();
            }
        }
        else if ($alerts_count >= 5) {
        // if ($alerts_count >= 10) {
            echo("Limit error");
            exit();
        }
    }

    // Save email for later use
    DB::table('cw_settings')->where('unique_id', $unique_id)->update(['email' => $email]);

    if (DB::table('cw_alerts_email_per')->insert(array(
    'coin' => $coin,
    'coin_id' => $coin_id,
    'symbol' => $symbol,
    'price_set_btc' => $price_set_btc,
    'price_set_usd' => $price_set_usd,
    'price_set_eth' => $price_set_eth,
    'plus_percent' => $plus_percent,
    'plus_change' => $plus_change,
    'plus_compared' => $plus_compared,
    'minus_percent' => $minus_percent,
    'minus_change' => $minus_change,
    'minus_compared' => $minus_compared,
    'plus_sent' => '',
    'minus_sent' => '',
    'email' => $email,
    'unique_id' => $unique_id,
    'user_id' => $user_ID,
    'timestamp' => $timestamp ))===FALSE){
        echo "Error";
    }

    return('success');
});


//
// SMS - CURRENCY - ACC
//
Route::post('/alert_sms_cur', function (Request $request) {
        $id_user = Auth::user()->id;

        $coin = htmlspecialchars($request['coinName']);
        $coin_id = htmlspecialchars($request['coinId']);
        $symbol = htmlspecialchars($request['coinSymbol']);
        if ($request['belowPrice'] != null) {
            $below = str_replace(',', '.', htmlspecialchars($request['belowPrice']));
        }
        else {
            $below = "";
        }
        $below_currency = htmlspecialchars($request['belowCur']);
        if ($request['abovePrice'] != null) {
            $above = str_replace(',', '.', htmlspecialchars($request['abovePrice']));
        }
        else {
            $above = "";
        }
        $above_currency = htmlspecialchars($request['aboveCur']);
		$phone = htmlspecialchars($request['phone']);
        $timestamp = date("Y-m-d H:i:s");
        $user_ID = $id_user;

		// Check if subscription is active
        $subs = DB::table('cw_settings')->where('user_ID', $user_ID)->value('subs');
		if ($subs == 0) {
            return("Subs error");
            exit();
		}

        // Save phone for later use
        DB::table('cw_settings')->where('user_ID', $user_ID)->update([ 'phone_nr' => $phone ]);

        // Insert alert into db
        DB::table('cw_alerts_sms_cur')->insert(array(
            'coin' => $coin,
            'coin_id' => $coin_id,
            'symbol' => $symbol,
            'below' => $below,
            'below_currency' => $below_currency,
            'above' => $above,
            'above_currency' => $above_currency,
            'below_sent' => '',
            'above_sent' => '',
            'unique_id' => '',
            'phone' => $phone,
            'user_ID' => $user_ID,
            'timestamp' => $timestamp
        ));

        return('success');
});


//
// SMS - PERCENTAGE - ACC
//
Route::post('/alert_sms_per', function (Request $request) {
    $id_user = Auth::user()->id;

    $coin = htmlspecialchars($request['coinName']);
    $coin_id = htmlspecialchars($request['coinId']);
    $symbol = htmlspecialchars($request['coinSymbol']);
    $phone = htmlspecialchars($request['phone']);
    $timestamp = date("Y-m-d H:i:s");
    $user_ID = $id_user;

    $price_set_btc = htmlspecialchars($request['price_set_btc']);
    $price_set_usd = htmlspecialchars($request['price_set_usd']);
    $price_set_eth = htmlspecialchars($request['price_set_eth']);
    $search  = array(',', '-', '+');
    $replace = array('.', '', '');
    if ($request['plus_percent'] != null) {
        $plus_percent = str_replace($search, $replace, htmlspecialchars($request['plus_percent']));
    }
    else {
        $plus_percent = "";
    }
    $plus_change = htmlspecialchars($request['plus_change']);
    $plus_compared = htmlspecialchars($request['plus_compared']);
    if ($request['minus_percent'] != null) {
        $minus_percent = str_replace($search, $replace, htmlspecialchars($request['minus_percent']));
    }
    else {
        $minus_percent = "";
    }
    $minus_change = htmlspecialchars($request['minus_change']);
    $minus_compared = htmlspecialchars($request['minus_compared']);


    // Check if subscription is active
    $subs = DB::table('cw_settings')->where('user_ID', $user_ID)->value('subs');
    if ($subs == 0) {
        return("Subs error");
        exit();
    }

    // Save phone for later use
    DB::table('cw_settings')->where('user_ID', $user_ID)->update([ 'phone_nr' => $phone ]);

    // Insert alert into db
    DB::table('cw_alerts_sms_per')->insert(array(
        'coin' => $coin,
        'coin_id' => $coin_id,
        'symbol' => $symbol,
        'price_set_btc' => $price_set_btc,
        'price_set_usd' => $price_set_usd,
        'price_set_eth' => $price_set_eth,
        'plus_percent' => $plus_percent,
        'plus_change' => $plus_change,
        'plus_compared' => $plus_compared,
        'minus_percent' => $minus_percent,
        'minus_change' => $minus_change,
        'minus_compared' => $minus_compared,
        'plus_sent' => '',
        'minus_sent' => '',
        'phone' => $phone,
        'user_ID' => $user_ID,
        'timestamp' => $timestamp 
    ));

    return('success');
});


// 
//  TELEGRAM - CURRENCY - ACC
// 
Route::middleware(['auth:sanctum', 'verified'])->post('/alert_tg_cur', function (Request $request) {
    $id_user = Auth::user()->id;

    $coin = htmlspecialchars($request['coinName']);
    $coin_id = htmlspecialchars($request['coinId']);
    $symbol = htmlspecialchars($request['coinSymbol']);
    if ($request['belowPrice'] != null) {
        $below = str_replace(',', '.', htmlspecialchars($request['belowPrice']));
    }
    else {
        $below = "";
    }
    $below_currency = htmlspecialchars($request['belowCur']);
    if ($request['abovePrice'] != null) {
        $above = str_replace(',', '.', htmlspecialchars($request['abovePrice']));
    }
    else {
        $above = "";
    }
    $above_currency = htmlspecialchars($request['aboveCur']);
    $timestamp = date("Y-m-d H:i:s");
    $user_ID = $id_user;

    // Get alerts count for user with acc
    $settings = DB::table('cw_settings')->where('user_ID', $user_ID)->get();
    $settings = json_decode($settings,true);
    $subs = $settings[0]['subs'];
    $unique_id = $settings[0]['unique_id'];

    $tg_user = $settings[0]['tg_user'];
    $tg_id = $settings[0]['tg_id'];

    if ($tg_id == '') {
        return("Huh?");
    }

    if ($subs == 0) {
        $alerts_count_tg_cur = DB::table('cw_alerts_tg_cur')->where('user_ID', $id_user)->count();
        $alerts_count_tg_per = DB::table('cw_alerts_tg_per')->where('user_ID', $id_user)->count();

        $alerts_count = $alerts_count_tg_cur + $alerts_count_tg_per;
        
        if ($alerts_count >= 5) {
            echo("Limit error");
            exit();
        }
    }

    DB::table('cw_alerts_tg_cur')->insert(array(
        'coin' => $coin,
        'coin_id' => $coin_id,
        'symbol' => $symbol,
        'below' => $below,
        'below_currency' => $below_currency,
        'above' => $above,
        'above_currency' => $above_currency,
        'below_sent' => '',
        'above_sent' => '',
        'tg_id' => $tg_id,
        'tg_user' => $tg_user,
        'user_id' => $user_ID,
        'timestamp' => $timestamp 
    ));

    return('success');
});


//
// TELEGRAM - PERCENTAGE - ACC
//
Route::post('/alert_tg_per', function (Request $request) {
    $id_user = Auth::user()->id;

    $coin = htmlspecialchars($request['coinName']);
    $coin_id = htmlspecialchars($request['coinId']);
    $symbol = htmlspecialchars($request['coinSymbol']);

    $price_set_btc = htmlspecialchars($request['price_set_btc']);
    $price_set_usd = htmlspecialchars($request['price_set_usd']);
    $price_set_eth = htmlspecialchars($request['price_set_eth']);
    $search  = array(',', '-', '+');
    $replace = array('.', '', '');
    if ($request['plus_percent'] != null) {
        $plus_percent = str_replace($search, $replace, htmlspecialchars($request['plus_percent']));
    }
    else {
        $plus_percent = "";
    }
    $plus_change = htmlspecialchars($request['plus_change']);
    $plus_compared = htmlspecialchars($request['plus_compared']);
    if ($request['minus_percent'] != null) {
        $minus_percent = str_replace($search, $replace, htmlspecialchars($request['minus_percent']));
    }
    else {
        $minus_percent = "";
    }
    $minus_change = htmlspecialchars($request['minus_change']);
    $minus_compared = htmlspecialchars($request['minus_compared']);

    // $email = htmlspecialchars($request['email']);

    $timestamp = date("Y-m-d H:i:s");
    $user_ID = $id_user;

    // Get alerts count for user with acc
    $settings = DB::table('cw_settings')->where('user_ID', $user_ID)->get();
    $settings = json_decode($settings,true);
    $subs = $settings[0]['subs'];
    $unique_id = $settings[0]['unique_id'];

    $tg_user = $settings[0]['tg_user'];
    $tg_id = $settings[0]['tg_id'];

    if ($tg_id == '') {
        return("Huh?");
    }

    // Get alerts count for user with acc
    if ($subs == 0) {
        $alerts_count_tg_cur = DB::table('cw_alerts_tg_cur')->where('user_ID', $id_user)->count();
        $alerts_count_tg_per = DB::table('cw_alerts_tg_per')->where('user_ID', $id_user)->count();

        $alerts_count = $alerts_count_tg_cur + $alerts_count_tg_per;
        
        if ($alerts_count >= 5) {
            echo("Limit error");
            exit();
        }
    }

    if (DB::table('cw_alerts_tg_per')->insert(array(
    'coin' => $coin,
    'coin_id' => $coin_id,
    'symbol' => $symbol,
    'price_set_btc' => $price_set_btc,
    'price_set_usd' => $price_set_usd,
    'price_set_eth' => $price_set_eth,
    'plus_percent' => $plus_percent,
    'plus_change' => $plus_change,
    'plus_compared' => $plus_compared,
    'minus_percent' => $minus_percent,
    'minus_change' => $minus_change,
    'minus_compared' => $minus_compared,
    'plus_sent' => '',
    'minus_sent' => '',
    'tg_id' => $tg_id,
    'tg_user' => $tg_user,
    'user_id' => $user_ID,
    'timestamp' => $timestamp ))===FALSE){
        echo "Error";
    }

    return('success');
});



///////////////////
// MANAGE ALERTS //
///////////////////


// 
// GET MY ALERTS
// 
Route::middleware(['auth:sanctum', 'verified'])->get('/manage_alerts_acc', function (Request $request) {

    $id_user = Auth::user()->id;   
    $id_unique = DB::table('cw_settings')->where('user_ID', $id_user)->value('unique_id');

    $sms_alerts = DB::table('cw_alerts_sms_cur')->where('user_ID', $id_user)->get();
    $sms_alerts_per = DB::table('cw_alerts_sms_per')->where('user_ID', $id_user)->get();

    $email_alerts = DB::table('cw_alerts_email_cur')->where('unique_id', $id_unique)->get();
    $email_alerts_per = DB::table('cw_alerts_email_per')->where('unique_id', $id_unique)->get();

    $tg_alerts = DB::table('cw_alerts_tg_cur')->where('user_ID', $id_user)->get();
    $tg_alerts_per = DB::table('cw_alerts_tg_per')->where('user_ID', $id_user)->get();

    $alerts['sms_alerts'] = $sms_alerts;
    $alerts['sms_alerts_per'] = $sms_alerts_per;
    $alerts['email_alerts'] = $email_alerts;
    $alerts['email_alerts_per'] = $email_alerts_per;
    $alerts['tg_alerts'] = $tg_alerts;
    $alerts['tg_alerts_per'] = $tg_alerts_per;

    if (sizeof($email_alerts) + sizeof($email_alerts_per) + sizeof($sms_alerts) + sizeof($sms_alerts_per) + sizeof($tg_alerts) + sizeof($tg_alerts_per) == 0) {
        echo ("zero_alerts");
    }
    else {
        echo json_encode($alerts);
    }
});


// 
// GET LOGS
// 
Route::middleware(['auth:sanctum', 'verified'])->get('/get_logs', function (Request $request) {

    $id_user = Auth::user()->id;   
    $id_unique = DB::table('cw_settings')->where('user_ID', $id_user)->value('unique_id');

    $alerts_email = DB::table('cw_logs_alerts_email')->where('user_ID', $id_unique)->orderBy('ID', 'DESC')->limit(100)->get();
    $alerts_email = json_decode($alerts_email, true);

    $i = 0;
    foreach ($alerts_email as $alert) {
        $alerts_email[$i]["ID"] = $i;
        unset($alerts_email[$i]['user_ID']);
        unset($alerts_email[$i]['type']);
        $i++;
    }

    $alerts_sms = DB::table('cw_logs_alerts_sms')->where('user_ID', $id_user)->where('type', '!=', 'sms_por')->orderBy('ID', 'DESC')->limit(100)->get();
    $alerts_sms = json_decode($alerts_sms, true);

    // $alerts_sms = DB::statement( "SELECT * FROM cw_logs_alerts_sms WHERE user_ID = '".$id_user."' AND type != 'sms_por' ORDER BY ID DESC LIMIT 100" );

    $i = 0;
    foreach ($alerts_sms as $alert) {
        $alerts_sms[$i]["ID"] = $i;
        unset($alerts_sms[$i]['user_ID']);
        unset($alerts_sms[$i]['type']);
        $i++;
    }

    $alerts_tg = DB::table('cw_logs_alerts_tg')->where('user_ID', $id_user)->where('type', '!=', 'tg_por')->orderBy('ID', 'DESC')->limit(100)->get();
    $alerts_tg = json_decode($alerts_tg, true);

    $i = 0;
    foreach ($alerts_tg as $alert) {
        $alerts_tg[$i]["ID"] = $i;
        unset($alerts_tg[$i]['user_ID']);
        unset($alerts_tg[$i]['type']);
        $alerts_tg[$i]['destination'] = '@'.$alerts_tg[$i]['tg_user'];
        $i++;
    }

    // $alerts_portfolio = DB::statement( "SELECT * FROM cw_logs_alerts_portfolio WHERE user_ID = '".$id_user."' ORDER BY ID DESC LIMIT 100" );

    $alerts_portfolio = DB::table('cw_logs_alerts_portfolio')->where('user_ID', $id_user)->orderBy('ID', 'DESC')->limit(100)->get();
    $alerts_portfolio = json_decode($alerts_portfolio, true);

    $i = 0;
    foreach ($alerts_portfolio as $alert) {
        $alerts_portfolio[$i]["ID"] = $i;
        unset($alerts_portfolio[$i]['user_ID']);
        unset($alerts_portfolio[$i]['type']);
        $i++;
    }

    return json_encode([$alerts_email, $alerts_sms, $alerts_tg, $alerts_portfolio]);
});


// 
// DELETE ALERT
// 
Route::middleware(['auth:sanctum', 'verified'])->post('/delete_alert', function (Request $request) {
    $id_user = Auth::user()->id;
    $action = $request['action'];
    $id_alert = $request['alert_id'];
    $table = '';

    if ($action == 'delete_alert_acc_email') {
        $table = 'cw_alerts_email_cur';
    }
    else if ($action == 'delete_alert_percent_acc') {

        $table = 'cw_alerts_email_per';
    }
    else if ($action == 'delete_alert_acc_sms') {
        $table = 'cw_alerts_sms_cur';
    }
    else if ($action == 'delete_alert_acc_sms_per') {
        $table = 'cw_alerts_sms_per';
    }
    else if ($action == 'delete_alert_acc_tg') {
        $table = 'cw_alerts_tg_cur';
    }
    else if ($action == 'delete_alert_acc_tg_per') {
        $table = 'cw_alerts_tg_per';
    }

    // Validate
    $alert_id_user = DB::table($table)->where('ID', $id_alert)->value('user_ID');

    // Delete alert
    if ($alert_id_user == $id_user) {
        DB::table($table)->where( 'ID', $id_alert )->delete();
        return('success');
    }
    else {
        // 2nd attempt with unique_id
        $id_unique =  DB::table('cw_settings')->where('user_ID', $id_user)->value('unique_id');
        $alert_id_unique = DB::table($table)->where('ID', $id_alert)->value('unique_id');
        if ($id_unique == $alert_id_unique) {
            DB::table($table)->where( 'ID', $id_alert )->delete();
            return('success');
        }
        else {
            return('Huh?');
        }
    }
});


//
// ALERT RE-ENABLE
//
Route::middleware(['auth:sanctum', 'verified'])->post('/alert_reenable', function (Request $request) {

    $alert_id = htmlspecialchars($request['alert_id']);
    $alert_type = htmlspecialchars($request['type']); // cw_alerts_email_cur
    $microType = htmlspecialchars($request['microType']);
    
    $user_ID = Auth::user()->id;

    if ($alert_type == "email_alerts") {
        $alert_type = 'cw_alerts_email_cur';
        
        // $alert_user_unique_ID = $wpdb->get_var( "SELECT unique_id FROM cw_alerts_email_cur WHERE ID = '".$alert_id."'" );
        $alert_user_unique_ID = DB::table('cw_alerts_email_cur')->where('ID', $alert_id)->value('unique_id');
        // $alert_user_ID = $wpdb->get_var( "SELECT user_ID FROM cw_settings WHERE unique_id = '".$alert_user_unique_ID."'" );
        $alert_user_ID = DB::table('cw_settings')->where('unique_id', $alert_user_unique_ID)->value('user_ID');

        if ($alert_user_ID != $user_ID) {
            return("Huh?");
            die();
        }
    }
    else if ($alert_type == "email_alerts_per") {
        $alert_type = 'cw_alerts_email_per';
        
        // $alert_user_unique_ID = $wpdb->get_var( "SELECT unique_id FROM cw_alerts_email_per WHERE ID = '".$alert_id."'" );
        // $alert_user_ID = $wpdb->get_var( "SELECT user_ID FROM cw_settings WHERE unique_id = '".$alert_user_unique_ID."'" );

        $alert_user_unique_ID = DB::table('cw_alerts_email_per')->where('ID', $alert_id)->value('unique_id');
        $alert_user_ID = DB::table('cw_settings')->where('unique_id', $alert_user_unique_ID)->value('user_ID');

        if ($alert_user_ID != $user_ID) {
            return("Huh?");
            die();
        }
    }
    else if ($alert_type == "sms_alerts") {
        $alert_type = 'cw_alerts_sms_cur';

        // $alert_user_ID = $wpdb->get_var( "SELECT user_ID FROM cw_alerts_sms_cur WHERE ID = '".$alert_id."'" );
        $alert_user_ID = DB::table('cw_alerts_sms_cur')->where('ID', $alert_id)->value('user_ID');
        
        if ($alert_user_ID != $user_ID) {
            return("Huh?");
            die();
        }
    }
    else if ($alert_type == "sms_alerts_per") {
        $alert_type = 'cw_alerts_sms_per';
        
        // $alert_user_ID = $wpdb->get_var( "SELECT user_ID FROM cw_alerts_sms_per WHERE ID = '".$alert_id."'" );
        $alert_user_ID = DB::table('cw_alerts_sms_per')->where('ID', $alert_id)->value('user_ID');
        
        if ($alert_user_ID != $user_ID) {
            return("Huh?");
            die();
        }
    }
    else if ($alert_type == "tg_alerts") {
        $alert_type = 'cw_alerts_tg_cur';

        // $alert_user_ID = $wpdb->get_var( "SELECT user_ID FROM cw_alerts_sms_cur WHERE ID = '".$alert_id."'" );
        $alert_user_ID = DB::table('cw_alerts_tg_cur')->where('ID', $alert_id)->value('user_ID');
        
        if ($alert_user_ID != $user_ID) {
            return("Huh?");
            die();
        }
    }
    else if ($alert_type == "tg_alerts_per") {
        $alert_type = 'cw_alerts_tg_per';
        
        // $alert_user_ID = $wpdb->get_var( "SELECT user_ID FROM cw_alerts_sms_per WHERE ID = '".$alert_id."'" );
        $alert_user_ID = DB::table('cw_alerts_tg_per')->where('ID', $alert_id)->value('user_ID');
        
        if ($alert_user_ID != $user_ID) {
            return("Huh?");
            die();
        }
    }

    // echo($alert_id . $alert_type . $microType);
    // $alertState = $wpdb->get_var( "SELECT ".$microType." FROM ".$alert_type." WHERE ID = '".$alert_id."'" );
    $alertState = DB::table($alert_type)->where('ID', $alert_id)->value($microType);

    if ($alertState == "") {
        $alertState = 1;
    }
    else {
        $alertState = "";
    }
    // echo('state'.$alertState);
    
    if (DB::table($alert_type)->where('ID', $alert_id)->update(array( $microType => $alertState ))) {
        echo('success');
    }

    exit();
});


//
// ALERT PER PRICE REFRESH
//
Route::middleware(['auth:sanctum', 'verified'])->post('/alert_per_price_refresh', function (Request $request) {

    $alert_id = htmlspecialchars($request['alert_id']);
    $cur = htmlspecialchars($request['cur']);
    $price_new = htmlspecialchars($request['price_new']);
    $delivery_type = htmlspecialchars($request['delivery_type']);

    // echo($alert_id . $cur . $price_new . $delivery_type);

    $user_ID = Auth::user()->id;

    // Validate and update
    if ($delivery_type == "email_alerts_per") {
        // $alert_user_unique_ID = $wpdb->get_var( "SELECT unique_id FROM cw_alerts_email_per WHERE ID = '".$alert_id."'" );
        // $alert_user_ID = $wpdb->get_var( "SELECT user_ID FROM cw_settings WHERE unique_id = '".$alert_user_unique_ID."'" );
        $alert_user_unique_ID = DB::table('cw_alerts_email_per')->where('ID', $alert_id)->value('unique_id');
        $alert_user_ID = DB::table('cw_settings')->where('unique_id', $alert_user_unique_ID)->value('user_ID');

        if ($alert_user_ID == $user_ID) {
            if ($cur == "USD") {
                DB::table('cw_alerts_email_per')->where('ID', $alert_id)->update(array( 'price_set_usd' => $price_new ));
                echo("success");
            }
            else if ($cur == "ETH") {
                // $wpdb->update( 'cw_alerts_email_per',  array( 'price_set_eth' => $price_new ), array( 'ID' => $alert_id ) );
                DB::table('cw_alerts_email_per')->where('ID', $alert_id)->update(array( 'price_set_eth' => $price_new ));
                echo("success");
            }
            else if ($cur == "BTC") {
                // $wpdb->update( 'cw_alerts_email_per',  array( 'price_set_btc' => $price_new ), array( 'ID' => $alert_id ) );
                DB::table('cw_alerts_email_per')->where('ID', $alert_id)->update(array( 'price_set_btc' => $price_new ));
                echo("success");
            }
        }
        else {
            echo('Huh?');
        }
        die();
    }
    else if ($delivery_type == "sms_alerts_per") {
        // $alert_user_ID = $wpdb->get_var( "SELECT user_ID FROM cw_alerts_sms_per WHERE ID = '".$alert_id."'" );
        $alert_user_ID = DB::table('cw_alerts_sms_per')->where('ID', $alert_id)->value('user_ID');

        if ($alert_user_ID == $user_ID) {
            if ($cur == "USD") {
                DB::table('cw_alerts_sms_per')->where('ID', $alert_id)->update(array( 'price_set_usd' => $price_new ));
                echo("success");
            }
            else if ($cur == "ETH") {
                // $wpdb->update( 'cw_alerts_sms_per',  array( 'price_set_eth' => $price_new ), array( 'ID' => $alert_id ) );
                DB::table('cw_alerts_sms_per')->where('ID', $alert_id)->update(array( 'price_set_eth' => $price_new ));
                echo("success");
            }
            else if ($cur == "BTC") {
                // $wpdb->update( 'cw_alerts_sms_per',  array( 'price_set_btc' => $price_new ), array( 'ID' => $alert_id ) );
                DB::table('cw_alerts_sms_per')->where('ID', $alert_id)->update(array( 'price_set_btc' => $price_new ));
                echo("success");
            }
        }
        else {
            echo('Huh?');
        }
        exit();
    }
    else if ($delivery_type == "tg_alerts_per") {
        // $alert_user_ID = $wpdb->get_var( "SELECT user_ID FROM cw_alerts_sms_per WHERE ID = '".$alert_id."'" );
        $alert_user_ID = DB::table('cw_alerts_tg_per')->where('ID', $alert_id)->value('user_ID');

        if ($alert_user_ID == $user_ID) {
            if ($cur == "USD") {
                DB::table('cw_alerts_tg_per')->where('ID', $alert_id)->update(array( 'price_set_usd' => $price_new ));
                echo("success");
            }
            else if ($cur == "ETH") {
                // $wpdb->update( 'cw_alerts_sms_per',  array( 'price_set_eth' => $price_new ), array( 'ID' => $alert_id ) );
                DB::table('cw_alerts_tg_per')->where('ID', $alert_id)->update(array( 'price_set_eth' => $price_new ));
                echo("success");
            }
            else if ($cur == "BTC") {
                // $wpdb->update( 'cw_alerts_sms_per',  array( 'price_set_btc' => $price_new ), array( 'ID' => $alert_id ) );
                DB::table('cw_alerts_tg_per')->where('ID', $alert_id)->update(array( 'price_set_btc' => $price_new ));
                echo("success");
            }
        }
        else {
            echo('Huh?');
        }
        exit();
    }
});


/////////////////////
// TELEGRAM ALERTS //
/////////////////////


// 
// VALIDATE TELEGRAM USERNAME
// 

Route::middleware(['auth:sanctum', 'verified'])->post('/telegram_validate_username', function (Request $request) {

    $username = htmlspecialchars($request['username']);
    if (substr($username, 0, 1) == '@') {
        $username = substr($username, 1);
    }

    $user_ID = Auth::user()->id;

    // connect to cw-telegram db and get user id by username
    $tg_id = DB::connection('tg')->table('user')->where('username', $username)->value('id');
    // DB::table('cw_alerts_email_per')->where('ID', $alert_id)->value('unique_id');

    // if user exists in cw-telegram, add username to cw_settings tg_user and id to tg_id
    if ($tg_id != '') {
        DB::table('cw_settings')->where('user_ID', $user_ID)->update(array( 'tg_user' => $username, 'tg_id' => $tg_id ));
        return('success');
    }

    else {
        return('error');
    }
    
});


// 
// DISCONNECT TELEGRAM USERNAME
// 

Route::middleware(['auth:sanctum', 'verified'])->post('/telegram_disconnect', function (Request $request) {

    $user_ID = Auth::user()->id;

    if(DB::table('cw_settings')->where('user_ID', $user_ID)->update(array( 'tg_user' => '', 'tg_id' => ''))) {
        $timestamp = date("Y-m-d H:i:s");
        
        DB::table('cw_alerts_portfolio')->where('user_ID', $user_ID)->update( array( 
        'change_1h_plus' => 10,
        'change_1h_minus' => 10,
        'change_24h_plus' => 10,
        'change_24h_minus' => 10,
        'on_1h_plus' => 'off',
        'on_1h_minus' => 'off',
        'on_24h_plus' => 'off',
        'on_24h_minus' => 'off',
        'type' => 'email',
        'destination' => '',
        'timestamp' => $timestamp
        ));

        return('success');
    }

    else {
        return('error');
    }
    
});
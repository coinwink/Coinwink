<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

use App\Models\GetAppData;

use Illuminate\Http\Request;

// TESTING

// Route::get('/testing', function (Request $request) {
//     if (env('APP_ENV') == 'live') {
//         return abort(404);
//     }
//     else {
//         return view('testing', array( 'cmc' => 'TEST' ));    
//     }
// })->name('testing');


// AUTH
require __DIR__.'/web_auth.php';


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function (Request $request) {
    $meta = [];
    $meta['title'] = "Coinwink - Crypto Alerts for Bitcoin, Ethereum, and More";
    $meta['description'] = "Email, Telegram and SMS crypto alerts app for Bitcoin (BTC), Ethereum (ETH), and other 3600 coins and tokens. Cryptocurrency price alerts, alarms, notifications, reminders in USD, EUR, GBP, AUD, CAD, BRL, MXN, JPY, and SGD.";
    $meta['soc_title'] = "Coinwink - Crypto Alerts for Bitcoin, Ethereum, and More";
    $meta['soc_description'] = "Email, Telegram and SMS crypto alerts app for Bitcoin (BTC), Ethereum (ETH), and other 3600 coins and tokens.";
    $meta['image'] = "thumb-main.png";
    $meta['image_w'] = 1200;
    $meta['image_h'] = 1200;

    if (Auth::user()) {
        $id_user = Auth::user()->id;
        $data = GetAppData::get($id_user);
        $cw_tab = $data[1]->cw_tab;
        if ($cw_tab == 'telegram') {
            return redirect("/telegram");
        }
        else if ($cw_tab == 'telegram-per') {
            return redirect("/telegram-per");
        }
        else if ($cw_tab == 'sms') {
            return redirect("/sms");
        }
        else if ($cw_tab == 'sms-per') {
            return redirect("/sms-per");
        }
        else if ($cw_tab == 'email-per') {
            return redirect("/email-per");
        }
        else {
            return view('welcome', array( 'rates' => $data[0], 'settings' => $data[1], 'subs' => $data[2], 'meta' => $meta ));
        }
    }
    else {
        $data = GetAppData::get(null);
        return view('landing', array( 'rates' => $data[0], 'settings' => null, 'subs' => null, 'meta' => $meta ));
    }
})->name('home');

Route::get('/email', function (Request $request) {
    $meta = [];
    $meta['title'] = "Coinwink - Email Crypto Price Alert";
    $meta['description'] = "Free, fast and reliable e-mail crypto price alerts app for Bitcoin, Ethereum and other 3600 cryptocurrencies. Create alerts in USD, EUR, GBP, AUD, CAD, BRL, MXN, JPY and SGD currencies.";
    $meta['soc_title'] = "Coinwink";
    $meta['soc_description'] = "Free Email Crypto Alerts for 3600 Cryptocurrencies";
    $meta['image'] = "thumb-email-crypto-alerts.png";
    $meta['image_w'] = 1664;
    $meta['image_h'] = 936;

    if (Auth::user()) {
        $id_user = Auth::user()->id;
        $data = GetAppData::get($id_user);
        return view('welcome', array( 'rates' => $data[0], 'settings' => $data[1], 'subs' => $data[2], 'meta' => $meta ));
    }
    else {
        $data = GetAppData::get(null);
        return view('welcome', array( 'rates' => $data[0], 'settings' => null, 'subs' => null, 'meta' => $meta ));    
    }
})->name('NewEmailAlert');

Route::get('/email-per', function (Request $request) {
    $meta = [];
    $meta['title'] = "Coinwink - Email Crypto Percentage Alert";
    $meta['description'] = "Free, fast and reliable email percentage alerts for Bitcoin, Ethereum and other 3600 cryptocurrencies. Create alerts in USD, EUR, GBP, AUD, CAD, BRL, MXN, JPY and SGD currencies.";
    $meta['soc_title'] = "Coinwink";
    $meta['soc_description'] = "Free Email Crypto Percentage Alerts for 3600 Cryptocurrencies";
    $meta['image'] = "thumb-email-crypto-alerts.png";
    $meta['image_w'] = 1664;
    $meta['image_h'] = 936;

    if (Auth::user()) {
        $id_user = Auth::user()->id;
        $data = GetAppData::get($id_user);
        return view('welcome', array( 'rates' => $data[0], 'settings' => $data[1], 'subs' => $data[2], 'meta' => $meta ));
    }
    else {
        $data = GetAppData::get(null);
        return view('welcome', array( 'rates' => $data[0], 'settings' => null, 'subs' => null, 'meta' => $meta ));    
    }
})->name('NewEmailAlertPer');

Route::get('/sms', function (Request $request) {
    $meta = [];
    $meta['title'] = "Coinwink - SMS Crypto Price Alert";
    $meta['description'] = "Fast & reliable SMS crypto price alerts app for Bitcoin (BTC), Ethereum (ETH) and other 3600 cryptocurrencies. Global reach! Create alerts in USD, EUR, GBP, AUD, CAD, BRL, MXN, JPY and SGD currencies.";
    $meta['soc_title'] = "Coinwink";
    $meta['soc_description'] = "SMS Crypto Price Alerts for 3600 Cryptocurrencies";
    $meta['image'] = "thumb-sms-crypto-alerts.png";
    $meta['image_w'] = 1664;
    $meta['image_h'] = 936;

    if (Auth::user()) {
        $id_user = Auth::user()->id;
        $data = GetAppData::get($id_user);
        return view('welcome', array( 'rates' => $data[0], 'settings' => $data[1], 'subs' => $data[2], 'meta' => $meta ));
    }
    else {
        $data = GetAppData::get(null);
        return view('welcome', array( 'rates' => $data[0], 'settings' => null, 'subs' => null, 'meta' => $meta ));    
    }
})->name('NewSMSAlert');

Route::get('/sms-per', function (Request $request) {
    $meta = [];
    $meta['title'] = "Coinwink - SMS Crypto Percentage Alert";
    $meta['description'] = "Fast & reliable SMS crypto percentage alerts app for Bitcoin (BTC), Ethereum (ETH) and other 3600 cryptocurrencies. Global reach! Create alerts in USD, EUR, GBP, AUD, CAD, BRL, MXN, JPY and SGD currencies.";
    $meta['soc_title'] = "Coinwink";
    $meta['soc_description'] = "SMS Crypto Percentage Alerts for 3600 Cryptocurrencies";
    $meta['image'] = "thumb-sms-crypto-alerts.png";
    $meta['image_w'] = 1664;
    $meta['image_h'] = 936;

    if (Auth::user()) {
        $id_user = Auth::user()->id;
        $data = GetAppData::get($id_user);
        return view('welcome', array( 'rates' => $data[0], 'settings' => $data[1], 'subs' => $data[2], 'meta' => $meta ));
    }
    else {
        $data = GetAppData::get(null);
        return view('welcome', array( 'rates' => $data[0], 'settings' => null, 'subs' => null, 'meta' => $meta ));    
    }
})->name('NewSMSAlertPer');

Route::get('/telegram', function (Request $request) {
    $meta = [];
    $meta['title'] = "Coinwink - Telegram Crypto Alerts";
    $meta['description'] = "Free, fast and reliable Telegram crypto price alerts app for Bitcoin, Ethereum and other 3600 cryptocurrencies. Create alerts in USD, EUR, GBP, AUD, CAD, BRL, MXN, JPY and SGD currencies.";
    $meta['soc_title'] = "Telegram Crypto Alerts";
    $meta['soc_description'] = "Free Telegram Crypto Alerts for 3600 Cryptocurrencies";
    $meta['image'] = "thumb-main.png";
    $meta['image_w'] = 1200;
    $meta['image_h'] = 1200;

    if (Auth::user()) {
        $id_user = Auth::user()->id;
        $data = GetAppData::get($id_user);
        return view('welcome', array( 'rates' => $data[0], 'settings' => $data[1], 'subs' => $data[2], 'meta' => $meta ));
    }
    else {
        $data = GetAppData::get(null);
        return view('welcome', array( 'rates' => $data[0], 'settings' => null, 'subs' => null, 'meta' => $meta ));    
    }
})->name('NewTelegramAlert');

Route::get('/telegram-per', function (Request $request) {
    $meta = [];
    $meta['title'] = "Coinwink - Telegram Crypto Percentage Alerts";
    $meta['description'] = "Free, fast and reliable Telegram crypto percentage alerts app for Bitcoin, Ethereum and other 3600 cryptocurrencies. Create alerts in USD, EUR, GBP, AUD, CAD, BRL, MXN, JPY and SGD currencies.";
    $meta['soc_title'] = "Telegram Crypto Percentage Alerts";
    $meta['soc_description'] = "Free Telegram Crypto Percentage Alerts for 3600 Cryptocurrencies";
    $meta['image'] = "thumb-main.png";
    $meta['image_w'] = 1200;
    $meta['image_h'] = 1200;

    if (Auth::user()) {
        $id_user = Auth::user()->id;
        $data = GetAppData::get($id_user);
        return view('welcome', array( 'rates' => $data[0], 'settings' => $data[1], 'subs' => $data[2], 'meta' => $meta ));
    }
    else {
        $data = GetAppData::get(null);
        return view('welcome', array( 'rates' => $data[0], 'settings' => null, 'subs' => null, 'meta' => $meta ));    
    }
})->name('NewTelegramPerAlert');

Route::get('/portfolio', function (Request $request) {
    $meta = [];
    $meta['title'] = "Coinwink - Crypto Portfolio Tracker App";
    $meta['description'] = "Track and manage your cryptocurrency assets. Return on investment (ROI) calculator, notes, automated multiple-coin alerts, currency converter. Portfolio tracking app for Bitcoin, Ethereum, and other 3600 crypto coins and tokens.";
    $meta['soc_title'] = "Coinwink Portfolio";
    $meta['soc_description'] = "Cryptocurrency portfolio tracker with multiple-coin alerts for Bitcoin, Ethereum, and other 3600 crypto coins and tokens";
    $meta['image'] = "thumb-portfolio.png";
    $meta['image_w'] = 1200;
    $meta['image_h'] = 900;

    if (Auth::user()) {
        $id_user = Auth::user()->id;
        $data = GetAppData::get($id_user);
        return view('welcome', array( 'rates' => $data[0], 'settings' => $data[1], 'subs' => $data[2], 'meta' => $meta ));
    }
    else {
        $data = GetAppData::get(null);
        return view('welcome', array( 'rates' => $data[0], 'settings' => null, 'subs' => null, 'meta' => $meta ));    
    }
})->name('Portfolio');

Route::get('/watchlist', function (Request $request) {
    $meta = [];
    $meta['title'] = "Coinwink - Crypto Watchlist App for 3500+ Cryptocurrencies";
    $meta['description'] = "Keep an eye on your favorite crypto assets. Track price change, volume, market cap, and other data with the cryptocurrency watchlist app.";
    $meta['soc_title'] = "Coinwink - Watchlist";
    $meta['soc_description'] = "Crypto watchlist app for 3500+ cryptocurrencies. Keep an eye on your favorite crypto coins and tokens.";
    $meta['image'] = "thumb-crypto-watchlist-2.png";
    $meta['image_w'] = 1000;
    $meta['image_h'] = 1000;

    if (Auth::user()) {
        $id_user = Auth::user()->id;
        $data = GetAppData::get($id_user);
        return view('welcome', array( 'rates' => $data[0], 'settings' => $data[1], 'subs' => $data[2], 'meta' => $meta ));
    }
    else {
        $data = GetAppData::get(null);
        return view('welcome', array( 'rates' => $data[0], 'settings' => null, 'subs' => null, 'meta' => $meta ));    
    }
})->name('Watchlist');

Route::get('/manage-alerts', function (Request $request) {
    $meta = [];
    $meta['title'] = "Coinwink - Manage Alerts";
    $meta['description'] = "";
    $meta['soc_title'] = "Coinwink";
    $meta['soc_description'] = "Alerts Management";
    $meta['image'] = "thumb-main.png";
    $meta['image_w'] = 1200;
    $meta['image_h'] = 1200;

    if (Auth::user()) {
        $id_user = Auth::user()->id;
        $data = GetAppData::get($id_user);
        return view('welcome', array( 'rates' => $data[0], 'settings' => $data[1], 'subs' => $data[2], 'meta' => $meta ));
    }
    else {
        $data = GetAppData::get(null);
        return view('welcome', array( 'rates' => $data[0], 'settings' => null, 'subs' => null, 'meta' => $meta ));    
    }
})->name('ManageAlerts');

Route::get('/subscription', function (Request $request) {
    $meta = [];
    $meta['title'] = "Coinwink - Subscribe to Premium Plan";
    $meta['description'] = "";
    $meta['soc_title'] = "Coinwink";
    $meta['soc_description'] = "Subscribe to Premium Plan";
    $meta['image'] = "thumb-main.png";
    $meta['image_w'] = 1200;
    $meta['image_h'] = 1200;

    if (Auth::user()) {
        $id_user = Auth::user()->id;
        $data = GetAppData::get($id_user);
        return view('welcome', array( 'rates' => $data[0], 'settings' => $data[1], 'subs' => $data[2], 'meta' => $meta ));
    }
    else {
        $data = GetAppData::get(null);
        return view('welcome', array( 'rates' => $data[0], 'settings' => null, 'subs' => null, 'meta' => $meta ));    
    }
})->name('Subscription');


/*
|--------------------------------------------------------------------------
| Static pages
|--------------------------------------------------------------------------
*/

Route::get('/about', function () {
    $meta = [];
    $meta['title'] = "Coinwink - About";
    $meta['description'] = "";
    $meta['soc_title'] = "Coinwink";
    $meta['soc_description'] = "About";
    $meta['image'] = "thumb-main.png";
    $meta['image_w'] = 1200;
    $meta['image_h'] = 1200;

    if (Auth::user()) {
        $id_user = Auth::user()->id;
        $data = GetAppData::get($id_user);
        return view('welcome', array( 'rates' => $data[0], 'settings' => $data[1], 'subs' => $data[2], 'meta' => $meta ));
    }
    else {
        $data = GetAppData::get(null);
        return view('welcome', array( 'rates' => $data[0], 'settings' => null, 'subs' => null, 'meta' => $meta ));    
    }
})->name('About');


Route::get('/pricing', function (Request $request) {
    $meta = [];
    $meta['title'] = "Coinwink - Pricing";
    $meta['description'] = "";
    $meta['soc_title'] = "Coinwink";
    $meta['soc_description'] = "Pricing";
    $meta['image'] = "thumb-main.png";
    $meta['image_w'] = 1200;
    $meta['image_h'] = 1200;

    if (Auth::user()) {
        $id_user = Auth::user()->id;
        $data = GetAppData::get($id_user);
        return view('welcome', array( 'rates' => $data[0], 'settings' => $data[1], 'subs' => $data[2], 'meta' => $meta ));
    }
    else {
        $data = GetAppData::get(null);
        return view('welcome', array( 'rates' => $data[0], 'settings' => null, 'subs' => null, 'meta' => $meta ));    
    }
})->name('Pricing');

Route::get('/privacy', function (Request $request) {
    $meta = [];
    $meta['title'] = "Coinwink - Privacy Policy";
    $meta['description'] = "";
    $meta['soc_title'] = "Coinwink";
    $meta['soc_description'] = "Privacy Policy";
    $meta['image'] = "thumb-main.png";
    $meta['image_w'] = 1200;
    $meta['image_h'] = 1200;

    // if (Auth::user()) {
    //     $id_user = Auth::user()->id;
    //     $data = GetAppData::get($id_user);
    //     return view('welcome', array( 'rates' => $data[0], 'settings' => $data[1], 'subs' => $data[2], 'meta' => $meta ));
    // }
    // else {
    //     $data = GetAppData::get(null);
    //     return view('welcome', array( 'rates' => $data[0], 'settings' => null, 'subs' => null, 'meta' => $meta ));    
    // }

    return view('static-page', array( 'meta' => $meta )); 
})->name('Privacy');

Route::get('/terms', function (Request $request) {
    $meta = [];
    $meta['title'] = "Coinwink - Terms and Conditions";
    $meta['description'] = "";
    $meta['soc_title'] = "Coinwink";
    $meta['soc_description'] = "Terms and Conditions";
    $meta['image'] = "thumb-main.png";
    $meta['image_w'] = 1200;
    $meta['image_h'] = 1200;

    // if (Auth::user()) {
    //     $id_user = Auth::user()->id;
    //     $data = GetAppData::get($id_user);
    //     return view('welcome', array( 'rates' => $data[0], 'settings' => $data[1], 'subs' => $data[2], 'meta' => $meta ));
    // }
    // else {
    //     $data = GetAppData::get(null);
    //     return view('welcome', array( 'rates' => $data[0], 'settings' => null, 'subs' => null, 'meta' => $meta ));    
    // }
    
    return view('static-page', array( 'meta' => $meta )); 
})->name('Terms');

Route::get('/press', function (Request $request) {
    $meta = [];
    $meta['title'] = "Coinwink - Press Kit";
    $meta['description'] = "";
    $meta['soc_title'] = "Coinwink";
    $meta['soc_description'] = "Press";
    $meta['image'] = "thumb-main.png";
    $meta['image_w'] = 1200;
    $meta['image_h'] = 1200;

    if (Auth::user()) {
        $id_user = Auth::user()->id;
        $data = GetAppData::get($id_user);
        return view('welcome', array( 'rates' => $data[0], 'settings' => $data[1], 'subs' => $data[2], 'meta' => $meta ));
    }
    else {
        $data = GetAppData::get(null);
        return view('welcome', array( 'rates' => $data[0], 'settings' => null, 'subs' => null, 'meta' => $meta ));    
    }
})->name('Press');

Route::get('/contacts', function (Request $request) {
    $meta = [];
    $meta['title'] = "Coinwink - Contacts";
    $meta['description'] = "";
    $meta['soc_title'] = "Coinwink";
    $meta['soc_description'] = "Contacts";
    $meta['image'] = "thumb-main.png";
    $meta['image_w'] = 1200;
    $meta['image_h'] = 1200;

    if (Auth::user()) {
        $id_user = Auth::user()->id;
        $data = GetAppData::get($id_user);
        return view('welcome', array( 'rates' => $data[0], 'settings' => $data[1], 'subs' => $data[2], 'meta' => $meta ));
    }
    else {
        $data = GetAppData::get(null);
        return view('welcome', array( 'rates' => $data[0], 'settings' => null, 'subs' => null, 'meta' => $meta ));    
    }
})->name('Contacts');


/*
|--------------------------------------------------------------------------
| Individual pages
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:sanctum', 'verified'])->get('/my-invoices', function () {
    $stripe = new \Stripe\StripeClient(
        env('STRIPE_API_KEY')
    );
    $id_user = Auth::user()->id;

    $customer = DB::table('cw_subs')->where('user_ID', $id_user)->value('customer');
    // echo($customer);
    if (!$customer) {
        echo("Error: No such customer");
        exit();
    }

    $result = $stripe->invoices->all(['customer' => $customer, 'limit' => 12]);

    $invoices = [];
    $i = 1;
    foreach($result['data'] as $invoice) {
        $invoices[$i]['url'] = $invoice['hosted_invoice_url'];
        $invoices[$i]['pdf'] = $invoice['invoice_pdf'];
        $invoices[$i]['created'] = $invoice['created'];
        $i++;
    }

    return view('static-invoices', array( 'invoices' => $invoices )); 
})->name('my_invoices');


// @TODO PR2: Update meta with individual alert info
Route::get('/alert/{id_alert}', function($id_alert) {
    $alert_ID = $id_alert;

    $alert = DB::table('cw_logs_alerts_sms')->select('coin_ID', 'name', 'symbol', 'timestamp', 'time', 'content')->where('alert_ID', '=', $alert_ID)->get();

    if (sizeof($alert) == 0) {
        $alert = DB::table('cw_logs_alerts_email')->select('coin_ID', 'name', 'symbol', 'timestamp', 'time', 'content')->where('alert_ID', '=', $alert_ID)->get();

        if (sizeof($alert) == 0) {
            $alert = DB::table('cw_logs_alerts_portfolio')->select('coin_ID', 'name', 'symbol', 'timestamp', 'time', 'content')->where('alert_ID', '=', $alert_ID)->get();

            if (sizeof($alert) == 0) {
                $alert = DB::table('cw_logs_alerts_tg')->select('coin_ID', 'name', 'symbol', 'timestamp', 'time', 'content')->where('alert_ID', '=', $alert_ID)->get();

                if (sizeof($alert) == 0) {
                    return abort(404);
                }
            }
        }
    }
    
    $cmc = DB::table('cw_data_cmc')->where('ID', '=', 1)->select('json')->get();

    return view('static-alert', array( 'alert' => $alert, 'cmc' => $cmc )); 
})->where('id_alert', '.*')->name('individual_alert');


Route::get('/{coin_symbol}', function($coin_symbol) {
    $cmc = DB::table('cw_data_cmc')->where('ID', '=', 1)->value('json');
    $cmc = json_decode($cmc);
    // var_dump($cmc);

    $found = false;

    foreach($cmc as $coin) {
        // var_dump($coin);

        if ($coin->symbol == strtoupper($coin_symbol) && !$found) {
            // echo($coin->name);
            $found = true;

            $meta_name = $coin->name;
            $meta_symbol = $coin->symbol;
            $meta_price_btc = $coin->price_btc;
            $meta_price_usd = $coin->price_usd;
        }
    }

    if(!$found) {
        return abort(404);
    }
    else {
        $meta = [];
        $meta['title'] = $meta_name .' ('. $meta_symbol .') Price Alert - Coinwink Crypto Alerts';
        $meta['description'] = 'Email & SMS crypto price alerts for '. $meta_name .' ('. $meta_symbol .') and other 3600 coins and tokens. '. $meta_symbol .' price now: '. number_format($meta_price_btc, 8, '.', '') .' BTC | '. number_format($meta_price_usd, 4, '.', '') .' USD.';
        $meta['soc_title'] = "Coinwink";
        $meta['soc_description'] = $meta_name .' ('. $meta_symbol .') Price Alerts, Watchlist & Portfolio Tracking App';

        $meta_symbol_lower = strtolower($meta_symbol);

        if ($meta_symbol_lower == 'eth') {
            $meta['image'] = "thumb-eth.png?v=001";
            $meta['image_w'] = 1200;
            $meta['image_h'] = 1200;
        }
        else if ($meta_symbol_lower == 'doge') {
            $meta['image'] = "thumb-doge-2.png?v=001";
            $meta['image_w'] = 1200;
            $meta['image_h'] = 900;
        }
        else if ($meta_symbol_lower == 'req') {
            $meta['image'] = "thumb-req.png";
            $meta['image_w'] = 1200;
            $meta['image_h'] = 1000;
        }
        else if ($meta_symbol_lower == 'rdd') {
            $meta['image'] = "thumb-rdd.png";
            $meta['image_w'] = 1200;
            $meta['image_h'] = 900;
        }
        else if ($meta_symbol_lower == 'vtc') {
            $meta['image'] = "thumb-vtc.png";
            $meta['image_w'] = 1200;
            $meta['image_h'] = 900;
        }
        else if ($meta_symbol_lower == 'xmr') {
            $meta['image'] = "thumb-xmr.png";
            $meta['image_w'] = 1200;
            $meta['image_h'] = 900;
        }
        else if ($meta_symbol_lower == 'xrp') {
            $meta['image'] = "thumb-xrp.png";
            $meta['image_w'] = 1200;
            $meta['image_h'] = 900;
        }
        else if ($meta_symbol_lower == 'ada') {
            $meta['image'] = "thumb-ada.png";
            $meta['image_w'] = 1200;
            $meta['image_h'] = 900;
        }
        else if ($meta_symbol_lower == 'ltc') {
            $meta['image'] = "thumb-ltc.png";
            $meta['image_w'] = 1200;
            $meta['image_h'] = 900;
        }
        else if ($meta_symbol_lower == 'bat') {
            $meta['image'] = "thumb-bat.png";
            $meta['image_w'] = 1200;
            $meta['image_h'] = 900;
        }
        else {
            $meta['image'] = "thumb-main.png";
            $meta['image_w'] = 1200;
            $meta['image_h'] = 1200;
        }

        if (Auth::user()) {
            $id_user = Auth::user()->id;
            $data = GetAppData::get($id_user);
            return view('welcome', array( 'rates' => $data[0], 'settings' => $data[1], 'subs' => $data[2], 'meta' => $meta, 'cmc' => json_encode($cmc)  ));
        }
        else {
            $data = GetAppData::get(null);
            return view('welcome', array( 'rates' => $data[0], 'settings' => null, 'subs' => null, 'meta' => $meta, 'cmc' => json_encode($cmc) ));    
        }
    }

})->where('coin_symbol', '.*')->name('individual_coin');

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Mail;

use App\Models\GetAppData;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// Load routes
require __DIR__.'/api_alerts.php';
require __DIR__.'/api_alerts_portfolio.php';
require __DIR__.'/api_themes.php';
require __DIR__.'/api_stripe.php';


// 
// GENERAL
// 

Route::get('/app_data', function () {
    $cmc = DB::table('cw_data_cmc')->where('ID', '=', 1)->value('json');
    return (array( 'cmc' => json_decode($cmc) ));    
});


Route::middleware(['auth:sanctum', 'verified'])->get('/portfolio', function () {
    $id_user = Auth::user()->id;
    $alerts = DB::table('cw_settings')->where('user_ID', $id_user)->get();

    $result = json_decode($alerts, true);

    return($result[0]['portfolio']);
});


Route::middleware(['auth:sanctum', 'verified'])->post('/update_portfolio', function (Request $request) {
    $id_user = Auth::user()->id;   

    DB::table('cw_settings')
    ->where('user_ID', $id_user)
    ->update(['portfolio' => $request['data']]);
});


Route::middleware(['auth:sanctum', 'verified'])->get('/watchlist', function () {
    $id_user = Auth::user()->id;
    $alerts = DB::table('cw_settings')->where('user_ID', $id_user)->get();

    $result = json_decode($alerts, true);

    return($result[0]['watchlist']);
});


Route::middleware(['auth:sanctum', 'verified'])->post('/update_watchlist', function (Request $request) {
    $id_user = Auth::user()->id;   

    DB::table('cw_settings')
    ->where('user_ID', $id_user)
    ->update(['watchlist' => $request['data']]);
});


// Watchlist right column view
Route::middleware(['auth:sanctum', 'verified'])->post('/config_conf_w', function (Request $request) {
    $id_user = Auth::user()->id;   

    DB::table('cw_settings')
    ->where('user_ID', $id_user)
    ->update(['conf_w' => $request['conf_w']]);
});


Route::middleware(['auth:sanctum', 'verified'])->post('/config_cur_p', function (Request $request) {
    $id_user = Auth::user()->id;   

    DB::table('cw_settings')
    ->where('user_ID', $id_user)
    ->update(['cur_p' => $request['cur_p']]);
});


Route::middleware(['auth:sanctum', 'verified'])->post('/config_cur_w', function (Request $request) {
    $id_user = Auth::user()->id;   

    DB::table('cw_settings')
    ->where('user_ID', $id_user)
    ->update(['cur_w' => $request['cur_w']]);
});


Route::middleware(['auth:sanctum', 'verified'])->post('/config_cur_main', function (Request $request) {
    $id_user = Auth::user()->id;   

    DB::table('cw_settings')
    ->where('user_ID', $id_user)
    ->update(['cur_main' => $request['cur_main']]);
});


// Save currently opened tab
Route::middleware(['auth:sanctum', 'verified'])->post('/cw_tab', function (Request $request) {
    $id_user = Auth::user()->id;   
    $cw_tab = $request['cw_tab'];
    
    if ($cw_tab == 'email' || $cw_tab == 'email-per' || $cw_tab == 'telegram' || $cw_tab == 'telegram-per' || $cw_tab == 'sms' || $cw_tab == 'sms-per') {
        DB::table('cw_settings')
        ->where('user_ID', $id_user)
        ->update(['cw_tab' => $cw_tab]);
    }
});


// CryptoConverter SHOW-HIDE
Route::middleware(['auth:sanctum', 'verified'])->post('/cryptocurrency_converter_expanded', function (Request $request) {
    $id_user = Auth::user()->id;
    $expanded = $request['expanded'];

    DB::table('cw_settings')->where('user_ID', $id_user)->update(array('conv_exp' => $expanded));
    echo('success');
});



// ACCOUNT FEEDBACK
Route::middleware(['auth:sanctum', 'verified'])->post('/feedback', function (Request $request) {
    $id_user = Auth::user()->id;
    $feedback = htmlspecialchars($request['feedback']);

    // return('f:'.$feedback);

    if (DB::table('cw_feedback')->insert(array('message' => $feedback, 'user_id' => $id_user)) === FALSE) {
        echo('error');
    }
    else {
        echo('success');
    }

    $user_email = DB::table('users')->where('id', $id_user)->value('email');
    
    // EMAIL NOTICE TO ADMIN
    $message = "User ID: " . $id_user . "\nUser email: " . $user_email . "\n\n" . $feedback;
    Mail::raw($message, function ($message) use ($id_user) {
        $message->subject("New Feedback Received: " . $id_user)->to("feedback@coinwink.com");
    });

    exit();
});

<?php

use Illuminate\Http\Request;


//
// PORTFOLIO ALERTS | SHOW-HIDE / INITIALIZE ON FIRST OPEN
//
Route::middleware(['auth:sanctum', 'verified'])->post('/portfolio_alerts_expanded', function (Request $request) {
    $expanded = $request['expanded'];
    $id_user = Auth::user()->id;
    $expanded_current = DB::table('cw_alerts_portfolio')->where('user_ID', $id_user)->value('expanded');
    $timestamp = date("Y-m-d H:i:s");

    if ($expanded_current != "") {
        DB::table('cw_alerts_portfolio')->where('user_ID', $id_user)->update(array('expanded' => $expanded, 'timestamp' => $timestamp));
        echo("success");
    }
    else {
        DB::table('cw_alerts_portfolio')->insert(array(
            'expanded' => $expanded, 
            'timestamp' => $timestamp, 
            'user_ID' => $id_user,
            'change_1h_plus' => '10',
            'change_1h_minus' => '10',
            'change_24h_plus' => '10',
            'change_24h_minus' => '10',
            'on_1h_plus' => 'off',
            'on_1h_minus' => 'off',
            'on_24h_plus' => 'off',
            'on_24h_minus' => 'off',
            'type' => 'email',
            'destination' => ''
        ));
        echo("success");
    }
});


// PORTFOLIO ALERTS | GET
Route::middleware(['auth:sanctum', 'verified'])->get('/portfolio_alerts', function() {
    $id_user = Auth::user()->id;
    $alerts = DB::table('cw_alerts_portfolio')->where('user_ID', $id_user)->first();
    return($alerts);
});


// PORTFOLIO ALERTS | CLEAR
Route::middleware(['auth:sanctum', 'verified'])->post('/portfolio_alerts_clear', function() {
    $id_user = Auth::user()->id;
    $timestamp = date("Y-m-d H:i:s");

    DB::table('cw_alerts_portfolio')->where('user_ID', $id_user)->update( array( 
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
    
    return('alerts cleared');
});


//
// PORTFOLIO ALERTS | CREATE
//
Route::middleware(['auth:sanctum', 'verified'])->post('/portfolio_alerts_create', function(Request $request) {

    $on_1h_plus = "off";
    $on_1h_minus = "off";
    $on_24h_plus = "off";
    $on_24h_minus = "off";

    if ($request['on_1h_plus'] == 'true') {
        $on_1h_plus = "on";
    }
    if ($request['on_1h_minus'] == 'true') {
        $on_1h_minus = "on";
    }
    if ($request['on_24h_plus'] == 'true') {
        $on_24h_plus = "on";
    }
    if ($request['on_24h_minus'] == 'true') {
        $on_24h_minus = "on";
    }

    $change_1h_plus = htmlspecialchars($request['change_1h_plus']);
    $change_1h_minus = htmlspecialchars($request['change_1h_minus']);
    $change_24h_plus = htmlspecialchars($request['change_24h_plus']);
    $change_24h_minus = htmlspecialchars($request['change_24h_minus']);

    if ($change_1h_plus > 1000 || $change_1h_plus < 5 || $change_1h_minus > 1000 || $change_1h_minus < 5 || $change_24h_plus > 1000 || $change_24h_plus < 5 || $change_24h_minus > 1000 || $change_24h_minus < 5 ) {
        return('error');
    }

    $destination = htmlspecialchars($request['destination']);
    $alert_type = htmlspecialchars($request['type']);

    $user_ID = Auth::user()->id;
    
    if ($alert_type == 'telegram') {
        $tg_id = DB::table('cw_settings')->where('user_ID', $user_ID)->value('tg_id');
        if ($tg_id == '') {
            return('error');
        }
        else {
            $destination = $tg_id;
        }
    }
    
    $timestamp = date("Y-m-d H:i:s");

    if (DB::table('cw_alerts_portfolio')->where('user_ID', $user_ID)->update( array( 
    'change_1h_plus' => $change_1h_plus,
    'change_1h_minus' => $change_1h_minus,
    'change_24h_plus' => $change_24h_plus,
    'change_24h_minus' => $change_24h_minus,
    'on_1h_plus' => $on_1h_plus,
    'on_1h_minus' => $on_1h_minus,
    'on_24h_plus' => $on_24h_plus,
    'on_24h_minus' => $on_24h_minus,
    'type' => $alert_type,
    'destination' => $destination,
    'timestamp' => $timestamp
    ))) {
        return('update success');
    }
    else {
        return('update error');
    }

});
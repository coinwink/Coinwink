<?php

use Illuminate\Http\Request;

// Switch theme
Route::middleware(['auth:sanctum', 'verified'])->post('/account_theme', function (Request $request) {
    $id_user = Auth::user()->id;   

    DB::table('cw_settings')
    ->where('user_ID', $id_user)
    ->update(['theme' => $request['theme']]);
});


//
// MATRIX: THEME STATIC
//
Route::middleware(['auth:sanctum', 'verified'])->post('/theme_static', function (Request $request) {
    $id_user = Auth::user()->id;
    $t_s = htmlspecialchars($request['t_s']);
    
    if (DB::table('cw_settings')->where('user_ID', $id_user)->update( array( 't_s' => $t_s ))) {
        return('success');
    }
    else {
        return('error');
    }
});


//
// MATRIX: THEME INTENSITY
//
Route::middleware(['auth:sanctum', 'verified'])->post('/theme_intensity', function (Request $request) {
    $id_user = Auth::user()->id;
    $t_i = htmlspecialchars($request['t_i']);
    
    if (DB::table('cw_settings')->where('user_ID', $id_user)->update( array( 't_i' => $t_i ))) {
        return('success');
    }
    else {
        return('error');
    }

});
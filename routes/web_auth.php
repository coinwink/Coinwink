<?php

use App\Models\GetAppData;
use App\Models\User;

use Illuminate\Http\Request;
use Laravel\Fortify\Fortify;

/*
|--------------------------------------------------------------------------
| AUTH Routes
|--------------------------------------------------------------------------
*/


Route::get('/account', function (Request $request) {
    $meta = [];
    $meta['title'] = "Coinwink - My Account";
    $meta['description'] = "";
    $meta['soc_title'] = "Coinwink";
    $meta['soc_description'] = "Access Your Account Settings";
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
})->name('account');


Route::get('/login', function (Request $request) {
    $meta = [];
    $meta['title'] = "Coinwink - Login";
    $meta['description'] = "Log in to your Coinwink account.";
    $meta['soc_title'] = "Coinwink";
    $meta['soc_description'] = "Log in to your Coinwink account.";
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
})->name('login');


Route::get('/register', function (Request $request) {
    $meta = [];
    $meta['title'] = "Coinwink - Sign Up for a Free Account";
    $meta['description'] = "";
    $meta['soc_title'] = "Coinwink";
    $meta['soc_description'] = "Sign Up for a Free Account";
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
})->name('register');


// Fortify::verifyEmailView(function (Request $request) {
Route::get('/email/verify', function (Request $request) {
    $meta = [];
    $meta['title'] = "Coinwink - Verify Your Email Address";
    $meta['description'] = "";
    $meta['soc_title'] = "Coinwink";
    $meta['soc_description'] = "Verify Your Email Address";
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
});


Route::get('password/reset/{token}', function() {
    $meta = [];
    $meta['title'] = "Coinwink - Reset Password";
    $meta['description'] = "";
    $meta['soc_title'] = "Coinwink";
    $meta['soc_description'] = "Reset Password";
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
})->name('password.reset');


Route::get('/forgot-password', function (Request $request) {
    $meta = [];
    $meta['title'] = "Coinwink - Password Recovery";
    $meta['description'] = "";
    $meta['soc_title'] = "Coinwink";
    $meta['soc_description'] = "Password Recovery";
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
})->name('forgotPassword');


Route::middleware('auth:sanctum')->post('/user/delete', function (Request $request) {
	$id_user = Auth::user()->id;
    $user = User::find($id_user);
    if (Hash::check($request['password'], $user->password)) {

        // If subscription exists, then cancel
        $subscription = DB::table('cw_subs')->where('user_ID', $id_user)->where('status', 'active')->orWhere('status', 'suspended')->get();

        if(sizeof($subscription) > 0) {
            
            if ($subscription[0]->payment_ID == "") {

                \Stripe\Stripe::setApiKey(env('STRIPE_API_KEY'));
                $sub = \Stripe\Subscription::retrieve($subscription[0]->subscription);
                $sub->cancel();

                if ($sub["status"] == 'canceled') {
                    DB::table('cw_subs')->where('user_ID', $id_user)->update([
                        'status' => 'cancelled',
                        'date_cancelled' => date("Y-m-d H:i:s")
                    ]);

                    $new_user_id = 0 - $id_user;
                    DB::table('cw_subs')->where('user_ID', $id_user)->update([
                        'status' => 'acc deleted',
                        'user_ID' => $new_user_id,
                        'date_cancelled' => date("Y-m-d H:i:s")
                    ]);
                    // continue
                }
                else {
                    return('Error: Cannot cancel subscription');
                }
            }
        }

        // Get user's unique ID
        $unique_id = DB::table('cw_settings')->where('user_ID', $id_user)->value('unique_id');

        // Delete additional user data
        DB::table('cw_alerts_email_cur')->where('unique_id', $unique_id)->delete();
        DB::table('cw_alerts_email_per')->where('unique_id', $unique_id)->delete();
        DB::table('cw_alerts_sms_cur')->where('user_ID', $id_user)->delete();
        DB::table('cw_alerts_sms_per')->where('user_ID', $id_user)->delete();
        DB::table('cw_alerts_portfolio')->where('user_ID', $id_user)->delete();
        DB::table('cw_settings')->where('user_ID', $id_user)->delete();
        
        // Delete user logs
        DB::table('cw_logs_alerts_email')->where('user_ID', $unique_id)->delete();
        DB::table('cw_logs_alerts_sms')->where('user_ID', $id_user)->delete();
        DB::table('cw_logs_alerts_portfolio')->where('user_ID', $id_user)->delete();

        // Get user IP for rate limiter
        // function getUserIP() {
        //     $client  = @$_SERVER['HTTP_CLIENT_IP'];
        //     $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        //     $remote  = $_SERVER['REMOTE_ADDR'];
        //     if (filter_var($client, FILTER_VALIDATE_IP)) { $ip = $client; }
        //     elseif (filter_var($forward, FILTER_VALIDATE_IP)) { $ip = $forward; }
        //     else { $ip = $remote; }
        //     return $ip;
        // }
        // $ip = getUserIP();
        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) { 
            $ip = $_SERVER["HTTP_CF_CONNECTING_IP"];
        }
        else {
            $ip = 'local';
        }
        // Delete user from rate limiter
        DB::table('cw_rate_limiter_alerts')->where('ip', $ip)->delete();
        // DB::table('cw_rate_limiter_alerts')->where('unique_id', $unique_id)->delete();
        

        // Log out user
        Session::flush();

        // Delete user
        if ($user->delete()) {
            echo('success');
        }
    }
    else {
        return('Wrong password');
    }
});


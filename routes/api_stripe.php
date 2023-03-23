<?php

use Illuminate\Support\Facades\Mail;

// 
// STRIPE PAYMENTS
// 

Route::middleware(['auth:sanctum', 'verified'])->post('/stripe_order_100_credits', function () {
    $stripe_price = env('STRIPE_PRICE');
    \Stripe\Stripe::setApiKey(env('STRIPE_API_KEY'));

    $user_id = Auth::user()->id;

    $session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items' => [
            [
            'price' =>  $stripe_price,
            'quantity' => 1,
            ],
        ],
        'success_url' => env('STRIPE_SUCCESS_URL'),
        'cancel_url' => env('STRIPE_CANCEL_URL'),
        
        'mode' => 'payment',
    ]);

    DB::table('cw_stripe_orders')->insert(array(
        'session_id' => $session['id'],
        'user_id' => $user_id,
        'status' => 'started_100'
    ));

    // EMAIL NOTICE TO ADMIN
    $message = "User started the 100 extra credits checkout session ID: " .  $session['id'];
    Mail::raw($message, function ($message) use ($user_id) {
        $message->subject("100 credits checkout user ID: " . $user_id)->to(env('ADMIN_EMAIL'));
    });

    echo(json_encode($session));
    exit;
});


Route::middleware(['auth:sanctum', 'verified'])->post('/stripe_order', function () {
    $user_id = Auth::user()->id;

    $stripe_plan = env('STRIPE_PLAN');
    \Stripe\Stripe::setApiKey(env('STRIPE_API_KEY'));

    // Known hakerz
	if($user_id == 29097 || $user_id == 24916) {
		return("Huh?");
	}

    // RATE LIMITER
    // @todo PR2: better rate limiter
	// $orders_count = $wpdb->get_var("SELECT COUNT(*) FROM cw_stripe_orders WHERE user_id = $user_id");
    $orders_count = DB::table('cw_stripe_orders')->where('user_id', $user_id)->count();
	if ($orders_count > 1000) {
		echo("Too many attempts. Please contact support.");
		exit();
	}

    $session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'subscription_data' => [
            'items' => [[
            'plan' => $stripe_plan,
            ]],
        ],
        'success_url' => env('STRIPE_SUCCESS_URL'),
        'cancel_url' => env('STRIPE_CANCEL_URL'),
    ]);

    DB::table('cw_stripe_orders')->insert(array(
        'session_id' => $session['id'],
        'user_id' => $user_id,
        'status' => 'started'
    ));

    // EMAIL NOTICE TO ADMIN
    $message = "User started the checkout session ID: " .  $session['id'];
    Mail::raw($message, function ($message) use ($user_id) {
        $message->subject("New PREMIUM checkout user ID: " . $user_id)->to(env('ADMIN_EMAIL'));
    });

    echo(json_encode($session));
    exit;
});


Route::middleware(['auth:sanctum', 'verified'])->post('/stripe_order_standard', function () {
    $user_id = Auth::user()->id;

    $stripe_plan = env('STRIPE_PLAN_STANDARD');
    \Stripe\Stripe::setApiKey(env('STRIPE_API_KEY'));

    // Known hakerz
	if($user_id == 29097 || $user_id == 24916) {
		return("Huh?");
	}

    // RATE LIMITER
    // @todo PR2: better rate limiter
    $orders_count = DB::table('cw_stripe_orders')->where('user_id', $user_id)->count();
	if ($orders_count > 1000) {
		echo("Too many attempts. Please contact support.");
		exit();
	}

    $session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'subscription_data' => [
            'items' => [[
            'plan' => $stripe_plan,
            ]],
        ],
        'success_url' => env('STRIPE_SUCCESS_URL'),
        'cancel_url' => env('STRIPE_CANCEL_URL'),
    ]);

    DB::table('cw_stripe_orders')->insert(array(
        'session_id' => $session['id'],
        'user_id' => $user_id,
        'status' => 'started_standard'
    ));

    // EMAIL NOTICE TO ADMIN
    $message = "User started the checkout session ID: " .  $session['id'];
    Mail::raw($message, function ($message) use ($user_id) {
        $message->subject("New STANDARD checkout user ID: " . $user_id)->to(env('ADMIN_EMAIL'));
    });

    echo(json_encode($session));
    exit;
});


Route::middleware(['auth:sanctum', 'verified'])->post('/stripe_cancel_subscription', function () {
	// Get the current user
	$id_user = Auth::user()->id;

	// if subscription exists, then cancel
    $subscription = DB::table('cw_subs')->where('user_ID', $id_user)->where('status', 'active')->get();

	if(isset($subscription)) {
        if ($subscription[0]->payment_ID == "") {

            \Stripe\Stripe::setApiKey(env('STRIPE_API_KEY'));
            $sub = \Stripe\Subscription::retrieve($subscription[0]->subscription);
            $sub->cancel();

            if ($sub["status"] == 'canceled') {
                DB::table('cw_subs')->where('user_ID', $id_user)->update([
                    'status' => 'cancelled',
                    'date_cancelled' => date("Y-m-d H:i:s")
                ]);

                return('success');
            }
            else {
                return('error');
            }
		}
	}

});
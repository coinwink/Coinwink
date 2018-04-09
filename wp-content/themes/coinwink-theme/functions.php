<?php

// Email config
include ABSPATH . "/coinwink_auth_email_functions.php";



//
// Required by Captcha by BestWebSoft plugin
//
function add_my_forms( $forms ) {
    $forms['form_slug']   = "Coinwink";
    return $forms;
}
add_filter( 'cptch_add_form', 'add_my_forms' );


//
// AJAX FUNCTION - GET PORTFOLIO
//
function get_portfolio(){

	// WP NONCE CHECK
	check_ajax_referer( 'my-special-string', 'security' );
	//
	
	global $wpdb;

	// Get the current user
	$user_id = get_current_user_id();

	$data = $wpdb->get_var( "SELECT portfolio FROM coinwink_settings WHERE user_ID = '".$user_id."'" );

	echo($data);
	
	wp_die();
	
	}
add_action('wp_ajax_get_portfolio', 'get_portfolio');
add_action('wp_ajax_nopriv_get_portfolio', 'get_portfolio');



//
// AJAX FUNCTION - UPDATE PORTFOLIO
//
function update_portfolio(){

	// WP NONCE CHECK
	check_ajax_referer( 'my-special-string', 'security' );
	//
	
	global $wpdb;

	// Get the current user
	$user_id = get_current_user_id();
	
	$data = $_POST['data'];

	$wpdb->update(
		'coinwink_settings', 
		array(
			'portfolio' => $data
		),
		array(
			'user_ID' => $user_id
		)
	);
	
	wp_die();	
	
	}
add_action('wp_ajax_update_portfolio', 'update_portfolio');
add_action('wp_ajax_nopriv_update_portfolio', 'update_portfolio');


//
// AJAX FUNCTION - NEW EMAIL ALERT - PERCENT WITHOUT ACC
//
function create_alert_percent(){

// WP NONCE CHECK
check_ajax_referer( 'my-special-string', 'security' );
//

global $wpdb;
if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'create_alert_percent' ) {

$error = apply_filters( 'cptch_verify', true );

if ( true === $error ) { 

	$coin = htmlspecialchars($_POST['coin']);
	$coin_id = htmlspecialchars($_POST['id']);
	$symbol = htmlspecialchars($_POST['symbol']);
	$price_set_btc = htmlspecialchars($_POST['price_set_btc']);
	$price_set_usd = htmlspecialchars($_POST['price_set_usd']);
	$price_set_eth = htmlspecialchars($_POST['price_set_eth']);
	$search  = array(',', '-', '+');
	$replace = array('.', '', '');	
	$plus_percent = str_replace($search, $replace, htmlspecialchars($_POST['plus_percent']));
	$plus_change = htmlspecialchars($_POST['plus_change']);
	$plus_compared = htmlspecialchars($_POST['plus_compared']);
	$minus_percent = str_replace($search, $replace, htmlspecialchars($_POST['minus_percent']));
	$minus_change = htmlspecialchars($_POST['minus_change']);
	$minus_compared = htmlspecialchars($_POST['minus_compared']);
	$email = htmlspecialchars($_POST['email_percent']);


$unique_id = $wpdb->get_var( "SELECT unique_id FROM coinwink WHERE email = '".$email."'" );
	if (!$unique_id) {
		$unique_id = $wpdb->get_var( "SELECT unique_id FROM coinwink_percent WHERE email = '".$email."'" );
		if (!$unique_id){
			$unique_id = uniqid();
		}
	} 


if($wpdb->insert('coinwink_percent', array(
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
	'email' => $email,
	'unique_id' => $unique_id ))===FALSE){
		echo "Error";
}
else {

	$to = $email;
	$subject = 'New percentage alert for '. $coin .' ('. $symbol .')';

	$message = 'A new '. $coin .' ('. $symbol .') percentage alert has been created.
	
You can manage your alert(-s) with this unique id: '. $unique_id .' at https://coinwink.com

Wink,
Coinwink';

	wp_mail($to, $subject, $message);

}
die();
}
else {
	echo($error);
}
}
}
add_action('wp_ajax_create_alert_percent', 'create_alert_percent');
add_action('wp_ajax_nopriv_create_alert_percent', 'create_alert_percent');



//
// AJAX FUNCTION - NEW EMAIL ALERT - PERCENT WITH ACC
//
function create_alert_percent_acc(){

	// WP NONCE CHECK
	check_ajax_referer( 'my-special-string', 'security' );
	//
	
	global $wpdb;
	if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'create_alert_percent_acc' ) {
	
	
		$coin = htmlspecialchars($_POST['coin']);
		$coin_id = htmlspecialchars($_POST['id']);
		$symbol = htmlspecialchars($_POST['symbol']);
		$price_set_btc = htmlspecialchars($_POST['price_set_btc']);
		$price_set_usd = htmlspecialchars($_POST['price_set_usd']);
		$price_set_eth = htmlspecialchars($_POST['price_set_eth']);
		$search  = array(',', '-', '+');
		$replace = array('.', '', '');	
		$plus_percent = str_replace($search, $replace, htmlspecialchars($_POST['plus_percent']));
		$plus_change = htmlspecialchars($_POST['plus_change']);
		$plus_compared = htmlspecialchars($_POST['plus_compared']);
		$minus_percent = str_replace($search, $replace, htmlspecialchars($_POST['minus_percent']));
		$minus_change = htmlspecialchars($_POST['minus_change']);
		$minus_compared = htmlspecialchars($_POST['minus_compared']);
		$email = htmlspecialchars($_POST['email_percent']);
		$unique_id = htmlspecialchars($_POST['unique_id']);
	

		// Save email for later use
		$wpdb->update(
			'coinwink_settings', 
			array(
				'email' => $email
			),
			array(
				'unique_id' => $unique_id
			)
		);
	
	if($wpdb->insert('coinwink_percent', array(
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
		'email' => $email,
		'unique_id' => $unique_id ))===FALSE){
			echo "Error";
	}
	else {
	
	$to = $email;
	$subject = 'New percentage alert for '. $coin .' ('. $symbol .')';
	
	$message = 'A new '. $coin .' ('. $symbol .') percentage alert has been created.
	
You can manage your alert(-s) with this unique id: '. $unique_id .' at https://coinwink.com
	
Wink,
Coinwink';
	
	wp_mail($to, $subject, $message);
	
	}
	die();

	}
}
add_action('wp_ajax_create_alert_percent_acc', 'create_alert_percent_acc');
add_action('wp_ajax_nopriv_create_alert_percent_acc', 'create_alert_percent_acc');	



//
// AJAX FUNCTION - NEW EMAIL CURRENCY ALERT WITH ACC
//
function create_alert_acc(){

// WP NONCE CHECK
check_ajax_referer( 'my-special-string', 'security' );
//

global $wpdb;
if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'create_alert_acc' ) {


	$coin = htmlspecialchars($_POST['coin']);
	$coin_id = htmlspecialchars($_POST['id']);
	$symbol = htmlspecialchars($_POST['symbol']);
	$below = str_replace(',', '.', htmlspecialchars($_POST['below']));
	$below_currency = htmlspecialchars($_POST['below_currency']);
	$above = str_replace(',', '.', htmlspecialchars($_POST['above']));
	$above_currency = htmlspecialchars($_POST['above_currency']);
	$email = htmlspecialchars($_POST['email']);
//	$user_ID = htmlspecialchars($_POST['user_ID']);
	$unique_id = htmlspecialchars($_POST['unique_id']);

// $unique_id = $wpdb->get_var( "SELECT unique_id FROM coinwink_settings WHERE user_ID = '".$user_ID."'" );

// Save email for later use
$wpdb->update(
	'coinwink_settings', 
	array(
		'email' => $email
	),
	array(
		'unique_id' => $unique_id
	)
);

if($wpdb->insert('coinwink', array(
		'coin' => $coin,
		'coin_id' => $coin_id,
		'symbol' => $symbol,
		'below' => $below,
		'below_currency' => $below_currency,
		'above' => $above,
		'above_currency' => $above_currency,
		'email' => $email,
		'unique_id' => $unique_id ))===FALSE){

echo "Error";

}
else {

$to      = $email;
$subject = 'New alert for '. $coin .' ('. $symbol .')';
if ($below && !$above) {
$message = ''. $coin .' ('. $symbol .') price alert has been created.

You will receive an email alert when '. $coin .' ('. $symbol .') price will be below: '. $below .' '. $below_currency .'.

You can manage your alert(-s) at https://coinwink.com

Wink,
Coinwink';
}
if ($below && $above)
{
$message = ''. $coin .' ('. $symbol .') price alert has been created.

You will receive email alerts when '. $coin .' ('. $symbol .') price will be above: '. $above .' '. $above_currency .' and below: '. $below .' '. $below_currency .'.

You can manage your alert(-s) at https://coinwink.com

Wink,
Coinwink';
}
if ($above && !$below)
{
$message = ''. $coin .' ('. $symbol .') price alert has been created.

You will receive an email alert when '. $coin .' ('. $symbol .') price will be above: '. $above .' '. $above_currency .'.

You can manage your alert(-s) at https://coinwink.com

Wink,
Coinwink';
}
/*
$headers = 'From: "Coinwink" <alert@coinwink.com>' . "\r\n" .
    'Reply-To: donotreply@coinwink.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion(); */

wp_mail($to, $subject, $message/*, $headers*/);

}
die();

}
}
add_action('wp_ajax_create_alert_acc', 'create_alert_acc');
add_action('wp_ajax_nopriv_create_alert_acc', 'create_alert_acc');



//
// AJAX FUNCTION - NEW EMAIL CURRENCY ALERT WITHOUT ACC
//
function create_alert(){

// WP NONCE CHECK
check_ajax_referer( 'my-special-string', 'security' );
//

global $wpdb;
if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'create_alert' ) {

$error = apply_filters( 'cptch_verify', true );

if ( true === $error ) { 

	$coin = htmlspecialchars($_POST['coin']);
	$coin_id = htmlspecialchars($_POST['id']);
	$symbol = htmlspecialchars($_POST['symbol']);
	$below = str_replace(',', '.', htmlspecialchars($_POST['below']));
	$below_currency = htmlspecialchars($_POST['below_currency']);
	$above = str_replace(',', '.', htmlspecialchars($_POST['above']));
	$above_currency = htmlspecialchars($_POST['above_currency']);
	$email = htmlspecialchars($_POST['email']);

// Get alerts count for user without acc
$alerts_count = $wpdb->get_var( "SELECT COUNT(*) FROM coinwink WHERE email = '".$email."'" );
if ($alerts_count >= 10) {
	echo("Limit error");
	exit();
}

$unique_id = $wpdb->get_var( "SELECT unique_id FROM coinwink WHERE email = '".$email."'" );
	if (!$unique_id) {
		$unique_id = $wpdb->get_var( "SELECT unique_id FROM coinwink_percent WHERE email = '".$email."'" );
		if (!$unique_id){
			$unique_id = uniqid();
		}
	} 

if($wpdb->insert('coinwink', array(
		'coin' => $coin,
		'coin_id' => $coin_id,
		'symbol' => $symbol,
		'below' => $below,
		'below_currency' => $below_currency,
		'above' => $above,
		'above_currency' => $above_currency,
		'email' => $email,
		'unique_id' => $unique_id ))===FALSE){

echo "Error";

}
else {

$to      = $email;
$subject = 'New alert for '. $coin .' ('. $symbol .')';
if ($below && !$above) {
$message = ''. $coin .' ('. $symbol .') price alert has been created.

You will receive an email alert when '. $coin .' ('. $symbol .') price will be below: '. $below .' '. $below_currency .'.

You can manage your alert(-s) with this unique id: '. $unique_id .' at https://coinwink.com

Wink,
Coinwink';
}
if ($below && $above)
{
$message = ''. $coin .' ('. $symbol .') price alert has been created.

You will receive email alerts when '. $coin .' ('. $symbol .') price will be above: '. $above .' '. $above_currency .' and below: '. $below .' '. $below_currency .'.

You can manage your alert(-s) with this unique id: '. $unique_id .' at https://coinwink.com

Wink,
Coinwink';
}
if ($above && !$below)
{
$message = ''. $coin .' ('. $symbol .') price alert has been created.

You will receive an email alert when '. $coin .' ('. $symbol .') price will be above: '. $above .' '. $above_currency .'.

You can manage your alert(-s) with this unique id: '. $unique_id .' at https://coinwink.com

Wink,
Coinwink';
}
/*$headers = 'From: "Coinwink" <alert@coinwink.com>' . "\r\n" .
    'Reply-To: donotreply@coinwink.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();*/

wp_mail($to, $subject, $message/*, $headers*/);

}
die();

}

else {
	
	echo($error);
	}
}
}
add_action('wp_ajax_create_alert', 'create_alert');
add_action('wp_ajax_nopriv_create_alert', 'create_alert');



//
// AJAX FUNCTION SMS
//
function create_alert_sms(){

// WP NONCE CHECK
check_ajax_referer( 'my-special-string', 'security' );
//

global $wpdb;
if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'create_alert_sms' ) {

	$coin = htmlspecialchars($_POST['coin']);
	$coin_id = htmlspecialchars($_POST['id']);
	$symbol = htmlspecialchars($_POST['symbol']);
	$below = str_replace(',', '.', htmlspecialchars($_POST['below_sms']));
	$below_currency = htmlspecialchars($_POST['below_currency_sms']);
	$above = str_replace(',', '.', htmlspecialchars($_POST['above_sms']));
	$above_currency = htmlspecialchars($_POST['above_currency_sms']);
	$phone = htmlspecialchars($_POST['phone']);
//	$user_ID = htmlspecialchars($_POST['user_ID']);
	$unique_id = htmlspecialchars($_POST['unique_id']);

//	$unique_id = $wpdb->get_var( "SELECT unique_id FROM coinwink_settings WHERE user_ID = '".$user_ID."'" );
	$user_ID = $wpdb->get_var( "SELECT user_ID FROM coinwink_settings WHERE unique_id = '".$unique_id."'" );


	// Save phone number for later use
	$wpdb->update(
		'coinwink_settings', 
		array(
			'phone_nr' => $phone
		),
		array(
			'user_ID' => $user_ID
		)
	);

	if($wpdb->insert('coinwink_sms', array(
			'coin' => $coin,
			'coin_id' => $coin_id,
			'symbol' => $symbol,
			'below' => $below,
			'below_currency' => $below_currency,
			'above' => $above,
			'above_currency' => $above_currency,
			'phone' => $phone,
			'user_ID' => $user_ID,
			'unique_ID' => $unique_id ))===FALSE){
	echo "Error"; }

	die();

}

}
add_action('wp_ajax_create_alert_sms', 'create_alert_sms');
add_action('wp_ajax_nopriv_create_alert_sms', 'create_alert_sms');


//
// AJAX FUNCTION - MANAGE ALERTS
//
function manage_alerts(){

// WP NONCE CHECK
check_ajax_referer( 'my-special-string', 'security' );
//

global $wpdb;
if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'manage_alerts' ) {

$error = apply_filters( 'cptch_verify', true );
if ( true === $error ) { 

$unique_id = htmlspecialchars($_POST['unique_id']);

$unique_id = $wpdb->get_results( "SELECT * FROM coinwink WHERE unique_id = '".$unique_id."' ORDER BY ID" );

if($unique_id) {
	?><br><div id="alerttitle">Email alerts</div><br><?php
	foreach($unique_id as $alert) {
		echo('<div><br>
			<h3 class="fs-labels">
			Alert when <b>'. $alert->coin .' ('. $alert->symbol .')</b> is:<br>');
		if ($alert->above && $alert->above_sent) {
			echo('<s>above: '. $alert->above .' '. $alert->above_currency .'</s>');
		}
		elseif ($alert->above && !$alert->above_sent) {
			echo('above: '. $alert->above .' '. $alert->above_currency .'');
		}
		if ($alert->above && $alert->below) {
			echo('<br>');
		}
		if ($alert->below && $alert->below_sent) {
			echo('<s>below: '. $alert->below .' '. $alert->below_currency .'</s>');
		}
		elseif ($alert->below && !$alert->below_sent) {
			echo('below: '. $alert->below .' '. $alert->below_currency .'');
		}
		echo('<br>
		<form type="post" id="form_delete_alert"  action="">
			 <input name="action" type="hidden" value="delete_alert" />
			  <input type="hidden" name="alert_id" id="alert_id" value="'. $alert->ID .'">
			  <input type="submit" style="margin-top:5px;" class="submit" value="Delete alert" />');
		echo('</h3></form></div>');
		}
}

$unique_id_percent = htmlspecialchars($_POST['unique_id']);

$unique_id_percent = $wpdb->get_results( "SELECT * FROM coinwink_percent WHERE unique_id = '".$unique_id_percent."' ORDER BY ID" );

if($unique_id_percent) {
	?><br><br><div id="alerttitle">Email percentage alerts</div><br><?php
	foreach($unique_id_percent as $alert) {
		$plus_change = $alert->plus_change;
		$minus_change = $alert->minus_change;
		echo('<div><br>
			<h3 class="fs-labels">
			Alert when <b>'. $alert->coin .' ('. $alert->symbol .')</b>:<br>');
		if ($alert->plus_percent && $alert->plus_sent && $plus_change == 'from_now') {
			echo('<s>+'. $alert->plus_percent .'% compared to '. $alert->plus_compared .'</s>');
		}
		elseif ($alert->plus_percent && !$alert->plus_sent && $plus_change == 'from_now') {
			echo('+'. $alert->plus_percent .'% compared to '. $alert->plus_compared .'');
		}
		if ($alert->plus_percent && $alert->plus_sent && $plus_change == '1h') {
			echo('<s>+'. $alert->plus_percent .'% in 1h. period</s>');
		}
		elseif ($alert->plus_percent && !$alert->plus_sent && $plus_change == '1h') {
			echo('+'. $alert->plus_percent .'% in 1h. period');
		}
		if ($alert->plus_percent && $alert->plus_sent && $plus_change == '24h') {
			echo('<s>+'. $alert->plus_percent .'% in 24h. period</s>');
		}
		elseif ($alert->plus_percent && !$alert->plus_sent && $plus_change == '24h') {
			echo('+'. $alert->plus_percent .'% in 24h. period');
		}
		if ($alert->plus_percent && $alert->minus_percent) {
			echo('<br>');
		}
		if ($alert->minus_percent && $alert->minus_sent && $minus_change == 'from_now') {
			echo('<s>-'. $alert->minus_percent .'% compared to '. $alert->minus_compared .'</s>');
		}
		elseif ($alert->minus_percent && !$alert->minus_sent && $minus_change == 'from_now') {
			echo('-'. $alert->minus_percent .'% compared to '. $alert->minus_compared .'');
		}
		if ($alert->minus_percent && $alert->minus_sent && $minus_change == '1h') {
			echo('<s>-'. $alert->minus_percent .'% in 24h. period</s>');
		}
		elseif ($alert->minus_percent && !$alert->minus_sent && $minus_change == '1h') {
			echo('-'. $alert->minus_percent .'% in 24h. period');
		}
		if ($alert->minus_percent && $alert->minus_sent && $minus_change == '24h') {
			echo('<s>-'. $alert->minus_percent .'% in 24h. period</s>');
		}
		elseif ($alert->minus_percent && !$alert->minus_sent && $minus_change == '24h') {
			echo('-'. $alert->minus_percent .'% in 24h. period');
		}

		echo('<br>
		<form type="post" id="form_delete_alert_percent"  action="">
			 <input name="action" type="hidden" value="delete_alert_percent" />
			  <input type="hidden" name="alert_id" id="alert_id" value="'. $alert->ID .'">
			  <input type="submit" style="margin-top:5px;" class="submit" value="Delete alert" />');
		echo('</h3></form></div>');
		}
}

if(!$unique_id && !$unique_id_percent) {
	echo("ID does not exist..");
}
	
die();

}

else {
	
	echo($error);
	}
}
}
add_action('wp_ajax_manage_alerts', 'manage_alerts');
add_action('wp_ajax_nopriv_manage_alerts', 'manage_alerts');



//
// AJAX FUNCTION - DELETE ALERT
//
function delete_alert(){

// WP NONCE CHECK
check_ajax_referer( 'my-special-string', 'security' );
//

global $wpdb;
if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'delete_alert' ) {

$alert_id = htmlspecialchars($_POST['alert_id']);

$wpdb->delete( coinwink,  array( 'ID' => $alert_id ) );

die();

}
}
add_action('wp_ajax_delete_alert', 'delete_alert');
add_action('wp_ajax_nopriv_delete_alert', 'delete_alert');



//
// AJAX FUNCTION - DELETE ALERT PERCENT
//
function delete_alert_percent(){

	// WP NONCE CHECK
	check_ajax_referer( 'my-special-string', 'security' );
	//
	
	global $wpdb;
	if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'delete_alert_percent' ) {
	
	$alert_id = htmlspecialchars($_POST['alert_id']);
	
	$wpdb->delete( coinwink_percent,  array( 'ID' => $alert_id ) );
	
	die();
	
	}
	
	}
add_action('wp_ajax_delete_alert_percent', 'delete_alert_percent');
add_action('wp_ajax_nopriv_delete_alert_percent', 'delete_alert_percent');



//
// AJAX FUNCTION - DELETE ALERT PERCENT ACC
//
function delete_alert_percent_acc(){

	// WP NONCE CHECK
	check_ajax_referer( 'my-special-string', 'security' );
	//
	
	global $wpdb;
	if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'delete_alert_percent_acc' ) {
	
	$alert_id = htmlspecialchars($_POST['alert_id']);
	
	$wpdb->delete( coinwink_percent,  array( 'ID' => $alert_id ) );
	
	die();
	
	}
	
	}
add_action('wp_ajax_delete_alert_percent_acc', 'delete_alert_percent_acc');
add_action('wp_ajax_nopriv_delete_alert_percent_acc', 'delete_alert_percent_acc');



//
// AJAX FUNCTION - MANAGE ALERTS ACC
//

function manage_alerts_acc(){

// WP NONCE CHECK
check_ajax_referer( 'my-special-string', 'security' );
//


global $wpdb;
if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'manage_alerts_acc' ) {

$unique_id = htmlspecialchars($_POST['unique_id']);

// $unique_id = $current_user->ID;

$unique_id = $wpdb->get_results( "SELECT * FROM coinwink_sms WHERE unique_id = '".$unique_id."' ORDER BY ID" );


if ($unique_id) {
?><br><div id="alerttitle">SMS alerts</div><br><?php
foreach($unique_id as $alert) {
	echo('<div><br>
		<h3 class="fs-labels">
		Alert when <b>'. $alert->coin .' ('. $alert->symbol .')</b> is:<br>');
	if ($alert->above && $alert->above_sent) {
		echo('<s>above: '. $alert->above .' '. $alert->above_currency .'</s>');
	}
	elseif ($alert->above && !$alert->above_sent) {
		echo('above: '. $alert->above .' '. $alert->above_currency .'');
	}
	if ($alert->above && $alert->below) {
		echo('<br>');
	}
	if ($alert->below && $alert->below_sent) {
		echo('<s>below: '. $alert->below .' '. $alert->below_currency .'</s>');
	}
	elseif ($alert->below && !$alert->below_sent) {
		echo('below: '. $alert->below .' '. $alert->below_currency .'');
	}
	echo('<br>
	<form type="post" id="delete_alert_acc_sms_form"  action="">
			<input name="action" type="hidden" value="delete_alert_acc_sms" />
			<input type="hidden" name="alert_id" id="alert_id" value="'. $alert->ID .'">
			<input type="submit" style="margin-top:5px;" class="submit" value="Delete alert" />');
	echo('</h3></form></div>');
}
}

$unique_id_email = htmlspecialchars($_POST['unique_id']);

$unique_id_email = $wpdb->get_results( "SELECT * FROM coinwink WHERE unique_id = '".$unique_id_email."'  ORDER BY ID" );

if($unique_id_email) {
	?><br><br><div id="alerttitle">Email alerts</div><br><?php
	foreach($unique_id_email as $alert) {
	echo('<div><br>
		<h3 class="fs-labels">
		Alert when <b>'. $alert->coin .' ('. $alert->symbol .')</b> is:<br>');
	if ($alert->above && $alert->above_sent) {
		echo('<s>above: '. $alert->above .' '. $alert->above_currency .'</s>');
	}
	elseif ($alert->above && !$alert->above_sent) {
		echo('above: '. $alert->above .' '. $alert->above_currency .'');
	}
	if ($alert->above && $alert->below) {
		echo('<br>');
	}
	if ($alert->below && $alert->below_sent) {
		echo('<s>below: '. $alert->below .' '. $alert->below_currency .'</s>');
	}
	elseif ($alert->below && !$alert->below_sent) {
		echo('below: '. $alert->below .' '. $alert->below_currency .'');
	}
	echo('<br>
	<form type="post" id="delete_alert_acc_email_form"  action="">
			<input name="action" type="hidden" value="delete_alert_acc_email" />
			<input type="hidden" name="alert_id" id="alert_id" value="'. $alert->ID .'">
			<input type="submit" style="margin-top:5px;" class="submit" value="Delete alert" />');
	echo('</h3></form></div>');
}
}

$unique_id_percent = htmlspecialchars($_POST['unique_id']);

$unique_id_percent = $wpdb->get_results( "SELECT * FROM coinwink_percent WHERE unique_id = '".$unique_id_percent."' ORDER BY ID" );

if($unique_id_percent) {
	?><br><br><div id="alerttitle">Email percentage alerts</div><br><?php
	foreach($unique_id_percent as $alert) {
		$plus_change = $alert->plus_change;
		$minus_change = $alert->minus_change;
		echo('<div><br>
			<h3 class="fs-labels">
			Alert when <b>'. $alert->coin .' ('. $alert->symbol .')</b>:<br>');
		if ($alert->plus_percent && $alert->plus_sent && $plus_change == 'from_now') {
			echo('<s>+'. $alert->plus_percent .'% compared to '. $alert->plus_compared .'</s>');
		}
		elseif ($alert->plus_percent && !$alert->plus_sent && $plus_change == 'from_now') {
			echo('+'. $alert->plus_percent .'% compared to '. $alert->plus_compared .'');
		}
		if ($alert->plus_percent && $alert->plus_sent && $plus_change == '1h') {
			echo('<s>+'. $alert->plus_percent .'% in 1h. period</s>');
		}
		elseif ($alert->plus_percent && !$alert->plus_sent && $plus_change == '1h') {
			echo('+'. $alert->plus_percent .'% in 1h. period');
		}
		if ($alert->plus_percent && $alert->plus_sent && $plus_change == '24h') {
			echo('<s>+'. $alert->plus_percent .'% in 24h. period</s>');
		}
		elseif ($alert->plus_percent && !$alert->plus_sent && $plus_change == '24h') {
			echo('+'. $alert->plus_percent .'% in 24h. period');
		}
		if ($alert->plus_percent && $alert->minus_percent) {
			echo('<br>');
		}
		if ($alert->minus_percent && $alert->minus_sent && $minus_change == 'from_now') {
			echo('<s>-'. $alert->minus_percent .'% compared to '. $alert->minus_compared .'</s>');
		}
		elseif ($alert->minus_percent && !$alert->minus_sent && $minus_change == 'from_now') {
			echo('-'. $alert->minus_percent .'% compared to '. $alert->minus_compared .'');
		}
		if ($alert->minus_percent && $alert->minus_sent && $minus_change == '1h') {
			echo('<s>-'. $alert->minus_percent .'% in 24h. period</s>');
		}
		elseif ($alert->minus_percent && !$alert->minus_sent && $minus_change == '1h') {
			echo('-'. $alert->minus_percent .'% in 24h. period');
		}
		if ($alert->minus_percent && $alert->minus_sent && $minus_change == '24h') {
			echo('<s>-'. $alert->minus_percent .'% in 24h. period</s>');
		}
		elseif ($alert->minus_percent && !$alert->minus_sent && $minus_change == '24h') {
			echo('-'. $alert->minus_percent .'% in 24h. period');
		}

		echo('<br>
		<form type="post" id="form_delete_alert_percent_acc"  action="">
			 <input name="action" type="hidden" value="delete_alert_percent_acc" />
			  <input type="hidden" name="alert_id" id="alert_id" value="'. $alert->ID .'">
			  <input type="submit" style="margin-top:5px;" class="submit" value="Delete alert" />');
		echo('</h3></form></div>');
		}
}

if (!$unique_id_percent && !$unique_id_email && !$unique_id) {
	echo ("<br>You have no alerts to manage.");
}

die();
}
}
add_action('wp_ajax_manage_alerts_acc', 'manage_alerts_acc');
add_action('wp_ajax_nopriv_manage_alerts_acc', 'manage_alerts_acc');



//
// AJAX FUNCTION - DELETE ALERT - ACC SMS
//
function delete_alert_acc_sms(){

// WP NONCE CHECK
check_ajax_referer( 'my-special-string', 'security' );
//

global $wpdb;
if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'delete_alert_acc_sms' ) {

$alert_id = htmlspecialchars($_POST['alert_id']);

$wpdb->delete( coinwink_sms,  array( 'ID' => $alert_id ) );

die();

}

}
add_action('wp_ajax_delete_alert_acc_sms', 'delete_alert_acc_sms');
add_action('wp_ajax_nopriv_delete_alert_acc_sms', 'delete_alert_acc_sms');



//
// AJAX FUNCTION - DELETE ALERT - ACC EMAIL
//
function delete_alert_acc_email(){

// WP NONCE CHECK
check_ajax_referer( 'my-special-string', 'security' );
//

global $wpdb;

if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'delete_alert_acc_email' ) {

$alert_id = htmlspecialchars($_POST['alert_id']);

$wpdb->delete( coinwink,  array( 'ID' => $alert_id ) );

die();

}

}
add_action('wp_ajax_delete_alert_acc_email', 'delete_alert_acc_email');
add_action('wp_ajax_nopriv_delete_alert_acc_email', 'delete_alert_acc_email');



//
// AJAX FUNCTION - DELETE MY ACC
//
function delete_my_acc(){

	// WP NONCE CHECK
	check_ajax_referer( 'my-special-string', 'security' );
	
	global $wpdb;
	
		// Get the current user
		$user_id = get_current_user_id();

		// Get user meta
		$meta = get_user_meta( $user_id );

		// Delete user's meta
		foreach ( $meta as $key => $val ) {
			delete_user_meta( $user_id, $key );
		}

        // Get user's unique ID
        $unique_id = $wpdb->get_var( "SELECT unique_id FROM coinwink_settings WHERE user_ID = '".$user_id."'" ); 

        // Delete any additional user data
        $wpdb->delete( 'coinwink', array( 'unique_id' => $unique_id ));
        $wpdb->delete( 'coinwink_sms', array( 'user_ID' => $user_id ));
        $wpdb->delete( 'coinwink_settings', array( 'user_ID' => $user_id ));
        
		// Delete the user's account
		wp_delete_user( $user_id );

		// Destroy user's session
		// wp_logout();
		
	wp_die();

}
add_action('wp_ajax_delete_my_acc', 'delete_my_acc');
add_action('wp_ajax_nopriv_delete_my_acc', 'delete_my_acc');



//
// Footer
//
function footer_shortcode_func() {
	ob_start();
	?> 
	<footer style="text-align: center;">
	
	<div id="link_new_alert" style="display:none;margin:0 auto;">
		<a href="#" class="whitelink">
		New alert
		</a>
	</div>
	
	<?php if (is_page_template( 'template-account.php' )) {
	?>
		<div   style="margin:0 auto;">
		<a href="<?php echo site_url(); ?>/#sms" class="whitelink">
		New alert
		</a>
		</div>
	<?php
	}
	if (is_page_template( 'template-home.php' )) {
	?>
	<div id="links_manage_alerts">
	<?php if ( !is_user_logged_in() ) { ?>

	<div style="margin:0 auto;">
		<a href="#container3" class="whitelink hashlink">
		Manage alerts
		</a>
	</div>

	<?php } else { ?>

	<div style="margin:0 auto;">
	<a href="#manage_alerts_acc" id="manage_alerts_acc_link" class="whitelink hashlink">
	Manage alerts
	</a>
	</div>
	<?php } ?>
	</div>
	<?php } ?>
	
	<div style="margin-top:35px;margin-bottom:0px;font-size:10px;color:#bfbfbf;">
	
	Free, automated, privacy-focused service<br>
	Based on Coinmarketcap.com API<br>
	Open source<br>
	
	</div>

	<div style="margin:0 auto;margin-top:22px;">
	<a href="https://twitter.com/Coinwink" target="_blank">
	<img src="<?php echo get_stylesheet_directory_uri(); ?>/img/icon_twitter.png" alt="Twitter" title="Twitter" width="20"></a>
	<a href="https://github.com/dziungles/Coinwink" target="_blank" style="padding-left:6px;">
	<img src="<?php echo get_stylesheet_directory_uri(); ?>/img/icon_github.png" title="GitHub" alt="GitHub" width="20"></a>
	</div>	

	<div style="color:#bfbfbf;margin:0 auto;margin-top:12px;font-size: 10px; margin-bottom: 35px;">
	cont&#97;&#99;&#x74;&#x40;&#x63;&#x6f;&#x69;nwin&#107;&#46;&#x63;&#x6f;&#x6d;
	<br>
	ETH: 0x1095C4Dcc8FCd28f35bb4180A4dc8e15A80cf424
	</div>
	</div>

	</footer>
	<?php
	return ob_get_clean();
}
add_shortcode( 'footer_shortcode', 'footer_shortcode_func' );



//
// Deregister dashicons from Wordpress for better loading time
//
add_action( 'wp_print_styles',     'my_deregister_styles', 100 );
function my_deregister_styles()    { 
   if (current_user_can( 'update_core' )) {
     return;
   }
   wp_deregister_style( 'dashicons' );
}

//
// Remove emojicons from Wordpress for better loading time 
//
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' ); 



// Custom Page Titles
add_action('admin_menu', 'custom_title');
add_action('save_post', 'save_custom_title');
add_action('wp_head','insert_custom_title');
function custom_title() {
	add_meta_box('custom_title', 'Change page title', 'custom_title_input_function', 'post', 'normal', 'high');
	add_meta_box('custom_title', 'Change page title', 'custom_title_input_function', 'page', 'normal', 'high');
}
function custom_title_input_function() {
	global $post;
	echo '<input type="hidden" name="custom_title_input_hidden" id="custom_title_input_hidden" value="'.wp_create_nonce('custom-title-nonce').'" />';
	echo '<input type="text" name="custom_title_input" id="custom_title_input" style="width:100%;" value="'.get_post_meta($post->ID,'_custom_title',true).'" />';
}
function save_custom_title($post_id) {
	if (!wp_verify_nonce($_POST['custom_title_input_hidden'], 'custom-title-nonce')) return $post_id;
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;
	$customTitle = $_POST['custom_title_input'];
	update_post_meta($post_id, '_custom_title', $customTitle);
}
function insert_custom_title() {
	if (have_posts()) : the_post();
	  $customTitle = get_post_meta(get_the_ID(), '_custom_title', true);
	  if ($customTitle) {
		echo "<title>$customTitle</title>";
      } else {
    	echo "<title>Coinwink - Crypto Currency (Bitcoin, Ethereum...) Price Alerts, Alarms, Notifications</title>";
    }
    else :
      echo "<title>Coinwink - Crypto Currency (Bitcoin, Ethereum...) Price Alerts, Alarms, Notifications</title>";
	endif;
	rewind_posts();
}



// Remove Admin bar
function remove_admin_bar()
{
    return false;
}
add_filter('show_admin_bar', 'remove_admin_bar'); // Remove Admin bar



?>
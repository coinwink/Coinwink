<?php

// Email config
include ABSPATH . "auth_email_functions.php";
include ABSPATH . "auth_email.php";





//
// Router, page faker
//
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	// Get request url
	$url = parse_url($_SERVER['REQUEST_URI']);
	$url_slug = basename($url['path']);

	// Get coin price data from the database
	$resultdb2 = $wpdb->get_results( "SELECT json FROM cw_data_cmc" , ARRAY_A);
	if ($resultdb2 != null) {
		$newarrayjson = $resultdb2[0]['json'];
		// $newarrayunserialized = unserialize($newarrayjson);
		$newarrayunserialized = json_decode($newarrayjson, TRUE);

		// (Re-use same cmc data on front-end)
		add_filter( 'cmc_data_backend', 'return_cmc_data_backend' );
		function return_cmc_data_backend( $arg = '' ) {
			global $newarrayunserialized;
			return $newarrayunserialized;
		}

		// If the url path is some coin's symbol, then create its page
		if(is_array($newarrayunserialized)) {
			foreach ($newarrayunserialized as $jsoncoin) {
				if ($jsoncoin['symbol'] == strtoupper($url_slug)) {
					if ($jsoncoin['symbol'] == 'ES') {
						break;
					}
					add_filter( 'the_posts', 'generate_fake_page', -10 );
					break;
				}
			}
		}
	}
}




/**
 * Create a fake page
 * https://geek.hellyer.kiwi/2018/08/02/creating-fake-wordpress-pages/
 * 
 * @param   object  $posts  Original posts object
 * @global  object  $wp     The main WordPress object
 * @global  object  $wp     The main WordPress query object
 * @return  object  $posts  Modified posts object
 */
function generate_fake_page( $posts ) {
	global $wp, $wp_query, $newarrayunserialized;

	$url = parse_url($_SERVER['REQUEST_URI']);
	$url_slug = basename($url['path']);

    foreach ($newarrayunserialized as $jsoncoin) {
		if ($jsoncoin['symbol'] == strtoupper($url_slug)) {

			if ( ! defined( 'FAKE_PAGE' ) && ( strtolower( $wp->request ) == $url_slug ) ) {

				// stop interferring with other $posts arrays on this page (only works if the sidebar is rendered *after* the main page)
				define( 'FAKE_PAGE', true );

				// create a fake virtual page
				$post = new stdClass;
				$post->post_author    = 1;
				$post->post_name      = $url_slug;
				$post->guid           = home_url() . '/' . $url_slug;
				$post->post_title     = 'Individual';
				$post->post_content   = '';
				$post->ID             = -999;
				$post->post_type      = 'page';
				$post->post_status    = 'static';
				$post->comment_status = 'closed';
				$post->ping_status    = 'open';
				$post->comment_count  = 0;
				$post->post_date      = current_time( 'mysql' );
				$post->post_date_gmt  = current_time( 'mysql', 1 );
				$posts                = NULL;
				$posts[]              = $post;

				// make wpQuery believe this is a real page too
				$wp_query->is_page             = true;
				$wp_query->is_singular         = true;
				$wp_query->is_home             = false;
				$wp_query->is_archive          = false;
				$wp_query->is_category         = false;
				unset( $wp_query->query[ 'error' ] );
				$wp_query->query_vars[ 'error' ] = '';
				$wp_query->is_404 = false;
			}

			return $posts;
		}
	}
}




//
// THEME SWITCH
//
function account_theme(){

	// WP NONCE CHECK
	check_ajax_referer( 'my-special-string', 'security' );

	global $wpdb;

	if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'account_theme' ) {

        $theme = htmlspecialchars($_POST['theme']);

        // Get the current user
        $user_ID = get_current_user_id();
		
		if ($wpdb->update( 'cw_settings', array( 'theme' => $theme, 't_s' => 0, 't_i' => '' ), array ( 'user_ID' => $user_ID ))) {
			echo('success');
		}

		die();
	}
}
add_action('wp_ajax_account_theme', 'account_theme');
add_action('wp_ajax_nopriv_account_theme', 'account_theme');



//
// THEME STATIC
//
function theme_static(){

	// WP NONCE CHECK
	check_ajax_referer( 'my-special-string', 'security' );

	global $wpdb;

	if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'theme_static' ) {

        $t_s = htmlspecialchars($_POST['t_s']);

        // Get the current user
        $user_ID = get_current_user_id();
		
		if ($wpdb->update( 'cw_settings', array( 't_s' => $t_s ), array ( 'user_ID' => $user_ID ))) {
			echo('success');
		}

		die();
	}
}
add_action('wp_ajax_theme_static', 'theme_static');
add_action('wp_ajax_nopriv_theme_static', 'theme_static');


//
// THEME INTENSITY
//
function theme_intensity(){

	// WP NONCE CHECK
	check_ajax_referer( 'my-special-string', 'security' );

	global $wpdb;

	if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'theme_intensity' ) {

        $t_i = htmlspecialchars($_POST['t_i']);

        // Get the current user
        $user_ID = get_current_user_id();
		
		if ($wpdb->update( 'cw_settings', array( 't_i' => $t_i ), array ( 'user_ID' => $user_ID ))) {
			echo('success');
		}

		die();
	}
}
add_action('wp_ajax_theme_intensity', 'theme_intensity');
add_action('wp_ajax_nopriv_theme_intensity', 'theme_intensity');


//
// ACCOUNT FEEDBACK
//

function account_feedback() {

	// WP NONCE CHECK
	check_ajax_referer( 'my-special-string', 'security' );

	global $wpdb;

	if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'account_feedback' ) {

		$feedback = htmlspecialchars($_POST['feedback']);

		// Get the current user
		$user_id = get_current_user_id();

		if( $wpdb->insert('cw_feedback', array('message' => $feedback, 'user_id' => $user_id )) === FALSE ) {
			echo "error";
		}
		else {
			echo "success";
		}

		$user_email = $wpdb->get_var( "SELECT user_email FROM wp_users WHERE ID = '".$user_id."'" );

		global $adminaddress;
		// EMAIL NOTICE TO ADMIN
		$GLOBALS['mail']->addAddress($adminaddress);
		$GLOBALS['mail']->Subject  = "Feedback";
		$GLOBALS['mail']->Body = "User ID: " . $user_id . "\nUser email: " . $user_email . "\n\n" . $feedback;
		$GLOBALS['mail']->Send();
		$GLOBALS['mail']->ClearAllRecipients();

        die();

	}

}
add_action('wp_ajax_account_feedback', 'account_feedback');
add_action('wp_ajax_nopriv_account_feedback', 'account_feedback');



// Get user IP for rate limiter
function getUserIP()
{
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];

    if(filter_var($client, FILTER_VALIDATE_IP))
    {
        $ip = $client;
    }
    elseif(filter_var($forward, FILTER_VALIDATE_IP))
    {
        $ip = $forward;
    }
    else
    {
        $ip = $remote;
    }

    return $ip;
}



//
// AJAX - GET PORTFOLIO
//
function get_portfolio(){

	// WP NONCE CHECK
	check_ajax_referer( 'my-special-string', 'security' );
	//
	
	global $wpdb;

	// Get the current user
	$user_id = get_current_user_id();

	$data = $wpdb->get_var( "SELECT portfolio FROM cw_settings WHERE user_ID = '".$user_id."'" );

	echo($data);
	
	wp_die();
	
}
add_action('wp_ajax_get_portfolio', 'get_portfolio');
add_action('wp_ajax_nopriv_get_portfolio', 'get_portfolio');



//
// AJAX - UPDATE PORTFOLIO
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
		'cw_settings', 
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


function config_cur_p(){

	// WP NONCE CHECK
	check_ajax_referer( 'my-special-string', 'security' );

	global $wpdb;

	if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'config_cur_p' ) {

        $config_cur_p = htmlspecialchars($_POST['cur_p']);

        // Get the current user
        $user_ID = get_current_user_id();
		
		if ($wpdb->update( 'cw_settings', array( 'cur_p' => $config_cur_p ), array ( 'user_ID' => $user_ID ))) {
			echo('success');
		}

		die();
	}
}
add_action('wp_ajax_config_cur_p', 'config_cur_p');
add_action('wp_ajax_nopriv_config_cur_p', 'config_cur_p');


/////////////////
/// WATCHLIST ///
/////////////////


//
// AJAX - GET WATCHLIST
//
function get_watchlist(){

	// WP NONCE CHECK
	check_ajax_referer( 'my-special-string', 'security' );
	//
	
	global $wpdb;

	// Get the current user
	$user_id = get_current_user_id();

	$data = $wpdb->get_var( "SELECT watchlist FROM cw_settings WHERE user_ID = '".$user_id."'" );

	echo($data);
	
	wp_die();
	
}
add_action('wp_ajax_get_watchlist', 'get_watchlist');
add_action('wp_ajax_nopriv_get_watchlist', 'get_watchlist');


//
// AJAX - UPDATE WATCHLIST
//
function update_watchlist(){

	// WP NONCE CHECK
	check_ajax_referer( 'my-special-string', 'security' );
	//
	
	global $wpdb;

	// Get the current user
	$user_id = get_current_user_id();
	
	$data = $_POST['data'];

	$wpdb->update(
		'cw_settings', 
		array(
			'watchlist' => $data
		),
		array(
			'user_ID' => $user_id
		)
	);
	
	wp_die();
	
}
add_action('wp_ajax_update_watchlist', 'update_watchlist');
add_action('wp_ajax_nopriv_update_watchlist', 'update_watchlist');




function config_cur_w(){

	// WP NONCE CHECK
	check_ajax_referer( 'my-special-string', 'security' );

	global $wpdb;

	if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'config_cur_w' ) {

        $config_cur_w = htmlspecialchars($_POST['cur_w']);

        // Get the current user
        $user_ID = get_current_user_id();
		
		if ($wpdb->update( 'cw_settings', array( 'cur_w' => $config_cur_w ), array ( 'user_ID' => $user_ID ))) {
			echo('success');
		}

		die();
	}
}
add_action('wp_ajax_config_cur_w', 'config_cur_w');
add_action('wp_ajax_nopriv_config_cur_w', 'config_cur_w');


function config_conf_w(){

	// WP NONCE CHECK
	check_ajax_referer( 'my-special-string', 'security' );

	global $wpdb;

	if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'config_conf_w' ) {

        $config_conf_w = htmlspecialchars($_POST['conf_w']);

        // Get the current user
        $user_ID = get_current_user_id();
		
		if ($wpdb->update( 'cw_settings', array( 'conf_w' => $config_conf_w ), array ( 'user_ID' => $user_ID ))) {
			echo('success');
		}

		die();
	}
}
add_action('wp_ajax_config_conf_w', 'config_conf_w');
add_action('wp_ajax_nopriv_config_conf_w', 'config_conf_w');


function config_cur_main(){

	// WP NONCE CHECK
	check_ajax_referer( 'my-special-string', 'security' );

	global $wpdb;

	if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'config_cur_main' ) {

        $config_cur_main = htmlspecialchars($_POST['cur_main']);

        // Get the current user
        $user_ID = get_current_user_id();
		
		if ($wpdb->update( 'cw_settings', array( 'cur_main' => $config_cur_main ), array ( 'user_ID' => $user_ID ))) {
			echo('success');
		}

		die();
	}
}
add_action('wp_ajax_config_cur_main', 'config_cur_main');
add_action('wp_ajax_nopriv_config_cur_main', 'config_cur_main');



/////////////////////
/// CREATE ALERTS ///
/////////////////////


//
// AJAX - NEW EMAIL ALERT - PERCENTAGE
//
function create_alert_percent(){

// WP NONCE CHECK
check_ajax_referer( 'my-special-string', 'security' );
//

global $wpdb;
if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'create_alert_percent' ) {



$ip = getUserIP();
// echo($ip);


$req_count = $wpdb->get_var("SELECT COUNT(*) FROM cw_rate_limiter WHERE ip = '".$ip."'" );
if ($req_count > 100 && $ip != '176.223.143.200') {
	echo("Limit error");
	exit();
}


if ( true ) { 

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

	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		echo("Limit error");
		exit();
	}

	// Get alerts count for user without acc
	$alerts_count_cur = $wpdb->get_var( "SELECT COUNT(*) FROM cw_alerts_email_cur WHERE email = '".$email."'" );
	$alerts_count_per = $wpdb->get_var( "SELECT COUNT(*) FROM cw_alerts_email_per WHERE email = '".$email."'" );
	$alerts_count = $alerts_count_cur + $alerts_count_per;
	if ($alerts_count >= 5) {
		echo("Limit error");
		exit();
	}

	$unique_id = $wpdb->get_var( "SELECT unique_id FROM cw_alerts_email_cur WHERE email = '".$email."'" );
	if (!$unique_id) {
		$unique_id = $wpdb->get_var( "SELECT unique_id FROM cw_alerts_email_per WHERE email = '".$email."'" );
		if (!$unique_id){
			$unique_id = uniqid();
		}
	}
	
	$timestamp = date("Y-m-d H:i:s");

if($wpdb->insert('cw_alerts_email_per', array(
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
	'unique_id' => $unique_id,
	'timestamp' => $timestamp ))===FALSE){
		echo "Error";
}
else {

	$to = $email;
	$subject = 'New percentage alert for '. $coin .' ('. $symbol .')';

	$message = 'A new '. $coin .' ('. $symbol .') percentage alert has been created.
	
You can manage your alert(-s) with a free Coinwink account: https://coinwink.com/account

Wink,
Coinwink';

	wp_mail($to, $subject, $message);

}

$wpdb->insert( 'cw_rate_limiter',
	array(
		'ip' => $ip,
		'action' => 'free_per_alert'
	)
);

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
// AJAX - NEW EMAIL ALERT - PERCENTAGE - ACC
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
		$user_ID = get_current_user_id();
		$timestamp = date("Y-m-d H:i:s");

		// Get alerts count for user with acc
		$subs = $wpdb->get_var( "SELECT subs FROM cw_settings WHERE user_ID = '".$user_ID."'" );
		if ($subs == 0) {
			$alerts_count_cur = $wpdb->get_var( "SELECT COUNT(*) FROM cw_alerts_email_cur WHERE unique_id = '".$unique_id."'" );
			$alerts_count_per = $wpdb->get_var( "SELECT COUNT(*) FROM cw_alerts_email_per WHERE unique_id = '".$unique_id."'" );
			// $alerts_count_sms_cur = $wpdb->get_var( "SELECT COUNT(*) FROM cw_alerts_sms_cur WHERE user_ID = '".$user_ID."'" );
			// $alerts_count_sms_per = $wpdb->get_var( "SELECT COUNT(*) FROM cw_alerts_sms_per WHERE user_ID = '".$user_ID."'" );
            // $alerts_count = $alerts_count_cur + $alerts_count_per + $alerts_count_sms_cur + $alerts_count_sms_per;
			$alerts_count = $alerts_count_cur + $alerts_count_per;
			
			
			if ($user_ID == 24301 || $user_ID == 19762 || $user_ID == 7929) {
				if ($alerts_count >= 10) {
					echo("Limit error");
					exit();
				}
			}
			else if ($alerts_count >= 5) {
				echo("Limit error");
				exit();
			}


		}

		// Save email for later use
		$wpdb->update(
			'cw_settings', 
			array(
				'email' => $email
			),
			array(
				'unique_id' => $unique_id
			)
		);
	
	if($wpdb->insert('cw_alerts_email_per', array(
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
		'unique_id' => $unique_id,
		'timestamp' => $timestamp ))===FALSE){
			echo "Error";
	}
	else {
	
	$to = $email;
	$subject = 'New percentage alert for '. $coin .' ('. $symbol .')';
	
	$message = 'A new '. $coin .' ('. $symbol .') percentage alert has been created.
	
You can manage your alert(-s) with a free Coinwink account: https://coinwink.com/account
	
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
// AJAX - NEW EMAIL ALERT - CURRENCY - ACC
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
	$unique_id = htmlspecialchars($_POST['unique_id']);
	$timestamp = date("Y-m-d H:i:s");
	$user_ID = get_current_user_id();

	// Get alerts count for user with acc
	$subs = $wpdb->get_var( "SELECT subs FROM cw_settings WHERE user_ID = '".$user_ID."'" );
	if ($subs == 0) {
		$alerts_count_cur = $wpdb->get_var( "SELECT COUNT(*) FROM cw_alerts_email_cur WHERE unique_id = '".$unique_id."'" );
		$alerts_count_per = $wpdb->get_var( "SELECT COUNT(*) FROM cw_alerts_email_per WHERE unique_id = '".$unique_id."'" );
		// $alerts_count_sms_cur = $wpdb->get_var( "SELECT COUNT(*) FROM cw_alerts_sms_cur WHERE user_ID = '".$user_ID."'" );
		// $alerts_count_sms_per = $wpdb->get_var( "SELECT COUNT(*) FROM cw_alerts_sms_per WHERE user_ID = '".$user_ID."'" );
        // $alerts_count = $alerts_count_cur + $alerts_count_per + $alerts_count_sms_cur + $alerts_count_sms_per;
		$alerts_count = $alerts_count_cur + $alerts_count_per;
		

		if ($user_ID == 24301 || $user_ID == 19762 || $user_ID == 7929) {
			if ($alerts_count >= 10) {
				echo("Limit error");
				exit();
			}
		}
		else if ($alerts_count >= 5) {
			echo("Limit error");
			exit();
		}


	}

// Save email for later use
$wpdb->update(
	'cw_settings', 
	array(
		'email' => $email
	),
	array(
		'unique_id' => $unique_id
	)
);

if($wpdb->insert('cw_alerts_email_cur', array(
		'coin' => $coin,
		'coin_id' => $coin_id,
		'symbol' => $symbol,
		'below' => $below,
		'below_currency' => $below_currency,
		'above' => $above,
		'above_currency' => $above_currency,
		'email' => $email,
		'unique_id' => $unique_id,
		'timestamp' => $timestamp ))===FALSE){

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
// AJAX - NEW EMAIL ALERT - CURRENCY
//
function create_alert(){

// WP NONCE CHECK
check_ajax_referer( 'my-special-string', 'security' );
//

global $wpdb;
if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'create_alert' ) {



$ip = getUserIP();
// echo($ip);


$req_count = $wpdb->get_var("SELECT COUNT(*) FROM cw_rate_limiter WHERE ip = '".$ip."'" );
// echo($req_count);
if ($req_count > 100 && $ip != '176.223.143.200') {
	echo("Limit error");
	exit();
}


if ( true ) { 

	$coin = htmlspecialchars($_POST['coin']);
	$coin_id = htmlspecialchars($_POST['id']);
	$symbol = htmlspecialchars($_POST['symbol']);
	$below = str_replace(',', '.', htmlspecialchars($_POST['below']));
	$below_currency = htmlspecialchars($_POST['below_currency']);
	$above = str_replace(',', '.', htmlspecialchars($_POST['above']));
	$above_currency = htmlspecialchars($_POST['above_currency']);
	$email = htmlspecialchars($_POST['email']);

	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		echo("Limit error");
		exit();
	}

// Get alerts count for user without acc
$alerts_count_cur = $wpdb->get_var( "SELECT COUNT(*) FROM cw_alerts_email_cur WHERE email = '".$email."'" );
$alerts_count_per = $wpdb->get_var( "SELECT COUNT(*) FROM cw_alerts_email_per WHERE email = '".$email."'" );
$alerts_count = $alerts_count_cur + $alerts_count_per;
if ($alerts_count >= 5) {
	echo("Limit error");
	exit();
}

$unique_id = $wpdb->get_var( "SELECT unique_id FROM cw_alerts_email_cur WHERE email = '".$email."'" );
	if (!$unique_id) {
		$unique_id = $wpdb->get_var( "SELECT unique_id FROM cw_alerts_email_per WHERE email = '".$email."'" );
		if (!$unique_id){
			$unique_id = uniqid();
		}
	} 

	$timestamp = date("Y-m-d H:i:s");


if($wpdb->insert('cw_alerts_email_cur', array(
		'coin' => $coin,
		'coin_id' => $coin_id,
		'symbol' => $symbol,
		'below' => $below,
		'below_currency' => $below_currency,
		'above' => $above,
		'above_currency' => $above_currency,
		'email' => $email,
		'unique_id' => $unique_id,
		'timestamp' => $timestamp ))===FALSE){

echo "Error";

}
else {

$to      = $email;
$subject = 'New alert for '. $coin .' ('. $symbol .')';
if ($below && !$above) {
$message = ''. $coin .' ('. $symbol .') price alert has been created.

You will receive an email alert when '. $coin .' ('. $symbol .') price will be below: '. $below .' '. $below_currency .'.

You can manage your alert(-s) with a free Coinwink account: https://coinwink.com/account

Wink,
Coinwink';
}
if ($below && $above)
{
$message = ''. $coin .' ('. $symbol .') price alert has been created.

You will receive email alerts when '. $coin .' ('. $symbol .') price will be above: '. $above .' '. $above_currency .' and below: '. $below .' '. $below_currency .'.

You can manage your alert(-s) with a free Coinwink account: https://coinwink.com/account

Wink,
Coinwink';
}
if ($above && !$below)
{
$message = ''. $coin .' ('. $symbol .') price alert has been created.

You will receive an email alert when '. $coin .' ('. $symbol .') price will be above: '. $above .' '. $above_currency .'.

You can manage your alert(-s) with a free Coinwink account: https://coinwink.com/account

Wink,
Coinwink';
}
/*$headers = 'From: "Coinwink" <alert@coinwink.com>' . "\r\n" .
    'Reply-To: donotreply@coinwink.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();*/

wp_mail($to, $subject, $message/*, $headers*/);

}

$wpdb->insert( 'cw_rate_limiter',
	array(
		'ip' => $ip,
		'action' => 'free_cur_alert'
	)
);

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
// AJAX - NEW SMS ALERT - CURRENCY - ACC
//
function create_alert_sms() {

	// WP NONCE CHECK
	check_ajax_referer( 'my-special-string', 'security' );

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
		$user_ID = get_current_user_id();
		$timestamp = date("Y-m-d H:i:s");

		// Get alerts count for user with acc
		$subs = $wpdb->get_var( "SELECT subs FROM cw_settings WHERE user_ID = '".$user_ID."'" );
		if ($subs == 0) {
            echo("Subs error");
            exit();
		}

		// Save phone number for later use
		$wpdb->update(
			'cw_settings', 
			array(
				'phone_nr' => $phone
			),
			array(
				'user_ID' => $user_ID
			)
		);

		if ($wpdb->insert('cw_alerts_sms_cur', array(
            'coin' => $coin,
            'coin_id' => $coin_id,
            'symbol' => $symbol,
            'below' => $below,
            'below_currency' => $below_currency,
            'above' => $above,
            'above_currency' => $above_currency,
            'phone' => $phone,
            'user_ID' => $user_ID,
            'timestamp' => $timestamp )) === FALSE) {
            echo "Error";
        }

        die();

	}

}
add_action('wp_ajax_create_alert_sms', 'create_alert_sms');
add_action('wp_ajax_nopriv_create_alert_sms', 'create_alert_sms');



//
// AJAX - NEW SMS ALERT - PERCENTAGE
//
function create_alert_sms_per(){

	// WP NONCE CHECK
	check_ajax_referer( 'my-special-string', 'security' );
	
	global $wpdb;
	if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'create_alert_sms_per' ) {
	
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
		$phone = htmlspecialchars($_POST['phone']);
		$user_ID = get_current_user_id();
		$timestamp = date("Y-m-d H:i:s");

		// Get alerts count for user with acc
		$subs = $wpdb->get_var( "SELECT subs FROM cw_settings WHERE user_ID = '".$user_ID."'" );
		if ($subs == 0) {
            echo("Subs error");
            exit();
		}

		// Save email for later use
		$wpdb->update(
			'cw_settings', 
			array(
				'phone_nr' => $phone
			),
			array(
				'user_ID' => $user_ID
			)
		);
	
		if ($wpdb->insert('cw_alerts_sms_per', array(
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
			'phone' => $phone,
			'user_ID' => $user_ID,
			'timestamp' => $timestamp )) === FALSE) { 
			echo "Error";
		}

		die();

	}
}
add_action('wp_ajax_create_alert_sms_per', 'create_alert_sms_per');
add_action('wp_ajax_nopriv_create_alert_sms_per', 'create_alert_sms_per');	



/// /// ///
/// /// ///
/// /// ///

/// /// ///
/// /// ///
/// /// ///

/// /// ///
/// /// ///
/// /// ///



//
// AJAX - MANAGE ALERTS | ACC
//
function manage_alerts_acc(){

	// WP NONCE CHECK
	check_ajax_referer( 'my-special-string', 'security' );

	global $wpdb;

	if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'manage_alerts_acc' ) {

        $user_ID = get_current_user_id();
        $unique_id = htmlspecialchars($_POST['unique_id']);
        
		$sms_alerts = $wpdb->get_results( "SELECT * FROM cw_alerts_sms_cur WHERE user_ID = '".$user_ID."'" );
		$sms_alerts_per = $wpdb->get_results( "SELECT * FROM cw_alerts_sms_per WHERE user_ID = '".$user_ID."'" );

		$email_alerts = $wpdb->get_results( "SELECT * FROM cw_alerts_email_cur WHERE unique_id = '".$unique_id."'" );
		$email_alerts_per = $wpdb->get_results( "SELECT * FROM cw_alerts_email_per WHERE unique_id = '".$unique_id."'" );

        $alerts['sms_alerts'] = $sms_alerts;
        $alerts['sms_alerts_per'] = $sms_alerts_per;
        $alerts['email_alerts'] = $email_alerts;
        $alerts['email_alerts_per'] = $email_alerts_per;

        if (!$email_alerts && !$email_alerts_per && !$sms_alerts && !$sms_alerts_per) {
			echo ("zero_alerts");
        }
        else {
            echo json_encode($alerts);
        }

		die();
	}
}
add_action('wp_ajax_manage_alerts_acc', 'manage_alerts_acc');
add_action('wp_ajax_nopriv_manage_alerts_acc', 'manage_alerts_acc');



//
// ALERT RE-ENABLE
//
function alert_reenable(){

	// WP NONCE CHECK
	check_ajax_referer( 'my-special-string', 'security' );

	global $wpdb;

	if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'alert_reenable' ) {

        $alert_id = htmlspecialchars($_POST['alert_id']);
        $alert_type = htmlspecialchars($_POST['type']); // cw_alerts_email_cur
		$microType = htmlspecialchars($_POST['microType']);
		
		$user_ID = get_current_user_id();

        if ($alert_type == "email_alerts") {
			$alert_type = 'cw_alerts_email_cur';
			
			$alert_user_unique_ID = $wpdb->get_var( "SELECT unique_id FROM cw_alerts_email_cur WHERE ID = '".$alert_id."'" );
			$alert_user_ID = $wpdb->get_var( "SELECT user_ID FROM cw_settings WHERE unique_id = '".$alert_user_unique_ID."'" );

			if ($alert_user_ID != $user_ID) {
				return("Huh?");
				die();
			}
        }
        else if ($alert_type == "sms_alerts") {
			$alert_type = 'cw_alerts_sms_cur';

			$alert_user_ID = $wpdb->get_var( "SELECT user_ID FROM cw_alerts_sms_cur WHERE ID = '".$alert_id."'" );
			
			if ($alert_user_ID != $user_ID) {
				return("Huh?");
				die();
			}
        }
        else if ($alert_type == "email_alerts_per") {
			$alert_type = 'cw_alerts_email_per';
			
			$alert_user_unique_ID = $wpdb->get_var( "SELECT unique_id FROM cw_alerts_email_per WHERE ID = '".$alert_id."'" );
			$alert_user_ID = $wpdb->get_var( "SELECT user_ID FROM cw_settings WHERE unique_id = '".$alert_user_unique_ID."'" );

			if ($alert_user_ID != $user_ID) {
				return("Huh?");
				die();
			}
        }
        else if ($alert_type == "sms_alerts_per") {
			$alert_type = 'cw_alerts_sms_per';
			
			$alert_user_ID = $wpdb->get_var( "SELECT user_ID FROM cw_alerts_sms_per WHERE ID = '".$alert_id."'" );
			
			if ($alert_user_ID != $user_ID) {
				return("Huh?");
				die();
			}
        }

        // echo($alert_id . $alert_type . $microType);
        $alertState = $wpdb->get_var( "SELECT ".$microType." FROM ".$alert_type." WHERE ID = '".$alert_id."'" );

        if ($alertState == "") {
            $alertState = 1;
        }
        else {
            $alertState = "";
        }
        echo('state'.$alertState);
        if ($wpdb->update( $alert_type, array( $microType => $alertState ), array( 'ID' => $alert_id ))) {
            echo('success');
        }

		die();
	}
}
add_action('wp_ajax_alert_reenable', 'alert_reenable');
add_action('wp_ajax_nopriv_alert_reenable', 'alert_reenable');



//
// ALERT PER PRICE REFRESH
//
function alert_per_price_refresh(){

	// echo('success');
	// exit();

	// WP NONCE CHECK
	check_ajax_referer( 'my-special-string', 'security' );

	global $wpdb;

	if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'alert_per_price_refresh' ) {

        $alert_id = htmlspecialchars($_POST['alert_id']);
        $cur = htmlspecialchars($_POST['cur']);
        $price_new = htmlspecialchars($_POST['price_new']);
		$delivery_type = htmlspecialchars($_POST['delivery_type']);

		// echo($alert_id . $cur . $price_new . $delivery_type);

		// Validate and update
		if ($delivery_type == "email_alerts_per") {
			$alert_user_unique_ID = $wpdb->get_var( "SELECT unique_id FROM cw_alerts_email_per WHERE ID = '".$alert_id."'" );
			$alert_user_ID = $wpdb->get_var( "SELECT user_ID FROM cw_settings WHERE unique_id = '".$alert_user_unique_ID."'" );
			$user_ID = get_current_user_id();
			
			if ($alert_user_ID == $user_ID) {
				if ($cur == "USD") {
					$wpdb->update( 'cw_alerts_email_per',  array( 'price_set_usd' => $price_new ), array( 'ID' => $alert_id ) );
					echo("success");
				}
				else if ($cur == "ETH") {
					$wpdb->update( 'cw_alerts_email_per',  array( 'price_set_eth' => $price_new ), array( 'ID' => $alert_id ) );
					echo("success");
				}
				else if ($cur == "BTC") {
					$wpdb->update( 'cw_alerts_email_per',  array( 'price_set_btc' => $price_new ), array( 'ID' => $alert_id ) );
					echo("success");
				}
			}
			else {
				echo('Huh?');
			}
			die();
		}
        else if ($delivery_type == "sms_alerts_per") {
			$alert_user_ID = $wpdb->get_var( "SELECT user_ID FROM cw_alerts_sms_per WHERE ID = '".$alert_id."'" );
			$user_ID = get_current_user_id();
			
			if ($alert_user_ID == $user_ID) {
				if ($cur == "USD") {
					$wpdb->update( 'cw_alerts_sms_per',  array( 'price_set_usd' => $price_new ), array( 'ID' => $alert_id ) );
					echo("success");
				}
				else if ($cur == "ETH") {
					$wpdb->update( 'cw_alerts_sms_per',  array( 'price_set_eth' => $price_new ), array( 'ID' => $alert_id ) );
					echo("success");
				}
				else if ($cur == "BTC") {
					$wpdb->update( 'cw_alerts_sms_per',  array( 'price_set_btc' => $price_new ), array( 'ID' => $alert_id ) );
					echo("success");
				}
			}
			else {
				echo('Huh?');
			}
			die();
		}
	}
}
add_action('wp_ajax_alert_per_price_refresh', 'alert_per_price_refresh');
add_action('wp_ajax_nopriv_alert_per_price_refresh', 'alert_per_price_refresh');


//
// PORTFOLIO ALERTS | SHOW-HIDE
//
function portfolio_get_alerts(){

	// WP NONCE CHECK
	check_ajax_referer( 'my-special-string', 'security' );

	global $wpdb;

	if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'portfolio_get_alerts' ) {

        // Get the current user
        $user_ID = get_current_user_id();
        
        $alerts = $wpdb->get_row( "SELECT * FROM cw_alerts_portfolio WHERE user_ID = '".$user_ID."'" );
        
        echo json_encode($alerts);
        exit();
        
	}
}
add_action('wp_ajax_portfolio_get_alerts', 'portfolio_get_alerts');
add_action('wp_ajax_nopriv_portfolio_get_alerts', 'portfolio_get_alerts');



//
// PORTFOLIO SHOW-HIDE
//
function portfolio_alerts_expanded(){

	// WP NONCE CHECK
	check_ajax_referer( 'my-special-string', 'security' );

	global $wpdb;

	if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'portfolio_alerts_expanded' ) {

        $expanded = htmlspecialchars($_POST['expanded']);

        // Get the current user
        $user_ID = get_current_user_id();
        
        $expanded_current = $wpdb->get_var( "SELECT expanded FROM cw_alerts_portfolio WHERE user_ID = '".$user_ID."'" );
        echo ($expanded_current);
        
        $timestamp = date("Y-m-d H:i:s");

        if ($expanded_current != "") {
            if ($wpdb->update( 'cw_alerts_portfolio', array( 'expanded' => $expanded, 'timestamp' => $timestamp ), array ( 'user_ID' => $user_ID ))) {
                echo('success');
            }
        }
        else {
            if ($wpdb->insert( 'cw_alerts_portfolio', array( 'expanded' => $expanded, 'timestamp' => $timestamp, 'user_ID' => $user_ID ))) {
                echo('success');
            }
        }

		die();
	}
}
add_action('wp_ajax_portfolio_alerts_expanded', 'portfolio_alerts_expanded');
add_action('wp_ajax_nopriv_portfolio_alerts_expanded', 'portfolio_alerts_expanded');



//
// PORTFOLIO ALERTS | GET ALERTS
//
function get_logs(){

	// WP NONCE CHECK
	check_ajax_referer( 'my-special-string', 'security' );
    
	global $wpdb;

	if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'get_logs' ) {

        // Get the current user
        $user_ID = get_current_user_id();

        // $user_ID = 1;

        // Get user's unique ID for email alerts
        // @todo: update db to use user_ID instead of unique_ID
        $unique_ID = $wpdb->get_var( "SELECT unique_id FROM cw_settings WHERE user_ID = '".$user_ID."'" );
        $alerts_email = $wpdb->get_results( "SELECT * FROM cw_logs_alerts_email WHERE user_ID = '".$unique_ID."'", ARRAY_A );

        $i = 0;
        foreach ($alerts_email as $alert) {
            $alerts_email[$i]["ID"] = $i;
            unset($alerts_email[$i]['user_ID']);
            unset($alerts_email[$i]['type']);
            $i++;
        }

        $alerts_sms = $wpdb->get_results( "SELECT * FROM cw_logs_alerts_sms WHERE user_ID = '".$user_ID."' AND type != 'sms_por'", ARRAY_A );

        $i = 0;
        foreach ($alerts_sms as $alert) {
            $alerts_sms[$i]["ID"] = $i;
            unset($alerts_sms[$i]['user_ID']);
            unset($alerts_sms[$i]['type']);
            $i++;
        }

        $alerts_portfolio = $wpdb->get_results( "SELECT * FROM cw_logs_alerts_portfolio WHERE user_ID = '".$user_ID."'", ARRAY_A );

        $i = 0;
        foreach ($alerts_portfolio as $alert) {
            $alerts_portfolio[$i]["ID"] = $i;
            unset($alerts_portfolio[$i]['user_ID']);
            unset($alerts_portfolio[$i]['type']);
            $i++;
        }

        echo json_encode([$alerts_email, $alerts_sms, $alerts_portfolio]);
        exit();
        
	}
}
add_action('wp_ajax_get_logs', 'get_logs');
add_action('wp_ajax_nopriv_get_logs', 'get_logs');



function portfolio_alerts_clear() {

	// WP NONCE CHECK
	check_ajax_referer( 'my-special-string', 'security' );

	global $wpdb;

	if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'portfolio_alerts_clear' ) {

        // Get the current user
        $user_ID = get_current_user_id();
        
        $timestamp = date("Y-m-d H:i:s");

        if ($wpdb->update( 'cw_alerts_portfolio', array( 
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
        ), array ( 'user_ID' => $user_ID ))) {
            echo('update success');
        }

        exit();
        
	}
}
add_action('wp_ajax_portfolio_alerts_clear', 'portfolio_alerts_clear');
add_action('wp_ajax_nopriv_portfolio_alerts_clear', 'portfolio_alerts_clear');


//
// PORTFOLIO ALERTS | CREATE
//
function portfolio_alerts_create(){

	// WP NONCE CHECK
	check_ajax_referer( 'my-special-string', 'security' );

	global $wpdb;

	if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'portfolio_alerts_create' ) {

        $on_1h_plus = htmlspecialchars($_POST['portfolio-alert-1']);
        if ($on_1h_plus != "on") {
            $on_1h_plus = "off";
        }
        $on_1h_minus = htmlspecialchars($_POST['portfolio-alert-2']);
        if ($on_1h_minus != "on") {
            $on_1h_minus = "off";
        }
        $on_24h_plus = htmlspecialchars($_POST['portfolio-alert-3']);
        if ($on_24h_plus != "on") {
            $on_24h_plus = "off";
        }
        $on_24h_minus = htmlspecialchars($_POST['portfolio-alert-4']);
        if ($on_24h_minus != "on") {
            $on_24h_minus = "off";
        }

        $change_1h_plus = htmlspecialchars($_POST['portfolio-alert-1-value']);
        $change_1h_minus = htmlspecialchars($_POST['portfolio-alert-2-value']);
        $change_24h_plus = htmlspecialchars($_POST['portfolio-alert-3-value']);
        $change_24h_minus = htmlspecialchars($_POST['portfolio-alert-4-value']);

        if ($change_1h_plus > 1000 || $change_1h_plus < 5 || $change_1h_minus > 1000 || $change_1h_minus < 5 || $change_24h_plus > 1000 || $change_24h_plus < 5 || $change_24h_minus > 1000 || $change_24h_minus < 5 ) {
            echo('error');
            exit();
        }

        $destination = htmlspecialchars($_POST['destination']);
        $alert_type = htmlspecialchars($_POST['alert_type']);

        // Get the current user
        $user_ID = get_current_user_id();
        
        $user_alerts = $wpdb->get_var( "SELECT ID FROM cw_alerts_portfolio WHERE user_ID = '".$user_ID."'" );
        echo($user_alerts);

        $timestamp = date("Y-m-d H:i:s");

        if ($user_alerts != "") {
            if ($wpdb->update( 'cw_alerts_portfolio', array( 
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
            ), array ( 'user_ID' => $user_ID ))) {
                echo('update success');
            }
        }
        else {
            if ($wpdb->insert( 'cw_alerts_portfolio', array(
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
            'timestamp' => $timestamp, 
            'user_ID' => $user_ID ))) {
                echo('insert success');
            }
        }

        exit();
        
	}
}
add_action('wp_ajax_portfolio_alerts_create', 'portfolio_alerts_create');
add_action('wp_ajax_nopriv_portfolio_alerts_create', 'portfolio_alerts_create');



// DELETE EMAIL ALERT | ACC
function delete_alert_acc_email(){

	// WP NONCE CHECK
	check_ajax_referer( 'my-special-string', 'security' );

	global $wpdb;

	if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'delete_alert_acc_email' ) {

        $alert_id = htmlspecialchars($_POST['alert_id']);
        
        // Validate
        $alert_user_unique_ID = $wpdb->get_var( "SELECT unique_id FROM cw_alerts_email_cur WHERE ID = '".$alert_id."'" );
        $alert_user_ID = $wpdb->get_var( "SELECT user_ID FROM cw_settings WHERE unique_id = '".$alert_user_unique_ID."'" );
        
        $user_ID = get_current_user_id();

        if ($alert_user_ID == $user_ID) {
            $wpdb->delete( 'cw_alerts_email_cur',  array( 'ID' => $alert_id ) );
        }
        else {
            echo('Huh?');
        }

		die();
	}
}
add_action('wp_ajax_delete_alert_acc_email', 'delete_alert_acc_email');
add_action('wp_ajax_nopriv_delete_alert_acc_email', 'delete_alert_acc_email');



// DELETE EMAIL ALERT PERCENTAGE | ACC
function delete_alert_percent_acc(){

	// WP NONCE CHECK
	check_ajax_referer( 'my-special-string', 'security' );
	
	global $wpdb;

	if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'delete_alert_percent_acc' ) {
		
		$alert_id = htmlspecialchars($_POST['alert_id']);
        
        // Validate
        $alert_user_unique_ID = $wpdb->get_var( "SELECT unique_id FROM cw_alerts_email_per WHERE ID = '".$alert_id."'" );
        $alert_user_ID = $wpdb->get_var( "SELECT user_ID FROM cw_settings WHERE unique_id = '".$alert_user_unique_ID."'" );
        
        $user_ID = get_current_user_id();

        if ($alert_user_ID == $user_ID) {
            $wpdb->delete( 'cw_alerts_email_per',  array( 'ID' => $alert_id ) );
        }
        else {
            echo('Huh?');
        }
		
		die();
	}
}
add_action('wp_ajax_delete_alert_percent_acc', 'delete_alert_percent_acc');
add_action('wp_ajax_nopriv_delete_alert_percent_acc', 'delete_alert_percent_acc');



// DELETE SMS ALERT | ACC
function delete_alert_acc_sms(){

	// WP NONCE CHECK
	check_ajax_referer( 'my-special-string', 'security' );

	global $wpdb;

	if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'delete_alert_acc_sms' ) {

		$alert_id = htmlspecialchars($_POST['alert_id']);

        // Validate
        $alert_user_ID = $wpdb->get_var( "SELECT user_ID FROM cw_alerts_sms_cur WHERE ID = '".$alert_id."'" );
        
        $user_ID = get_current_user_id();

        if ($alert_user_ID == $user_ID) {
            $wpdb->delete( 'cw_alerts_sms_cur',  array( 'ID' => $alert_id ) );
        }
        else {
            echo('Huh?');
        }
		
		die();
	}
}
add_action('wp_ajax_delete_alert_acc_sms', 'delete_alert_acc_sms');
add_action('wp_ajax_nopriv_delete_alert_acc_sms', 'delete_alert_acc_sms');



// DELETE SMS ALERT PERCENTAGE | ACC
function delete_alert_acc_sms_per(){

	// WP NONCE CHECK
	check_ajax_referer( 'my-special-string', 'security' );

	global $wpdb;

	if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'delete_alert_acc_sms_per' ) {

		$alert_id = htmlspecialchars($_POST['alert_id']);

        // Validate
        $alert_user_ID = $wpdb->get_var( "SELECT user_ID FROM cw_alerts_sms_per WHERE ID = '".$alert_id."'" );
        
        $user_ID = get_current_user_id();

        if ($alert_user_ID == $user_ID) {
            $wpdb->delete( 'cw_alerts_sms_per',  array( 'ID' => $alert_id ) );
        }
        else {
            echo('Huh?');
        }
		
		die();
	}
}
add_action('wp_ajax_delete_alert_acc_sms_per', 'delete_alert_acc_sms_per');
add_action('wp_ajax_nopriv_delete_alert_acc_sms_per', 'delete_alert_acc_sms_per');



/// /// ///
/// /// ///
/// /// ///



//
// AJAX FUNCTION - DELETE MY ACC
//
function delete_my_acc(){

	// WP NONCE CHECK
	check_ajax_referer( 'my-special-string', 'security' );
	
	global $wpdb;
	
	// Get the current user
	$user_id = get_current_user_id();
    
    
    // if subscription, then cancel
	$subscription = $wpdb->get_results( "SELECT * FROM cw_subs WHERE user_ID = '".$user_id."' AND (status = 'active' OR status = 'suspended')", ARRAY_A );

	if(isset($subscription[0])) {

		// if ID higher than 77 (after Cardinity) and is not a promo user, then call Stripe to unsubscribe
		if ($subscription[0]["ID"] > 77 && $subscription[0]["payment_ID"] != "X") {

			$cancelled = cancel_stripe($subscription[0]["subscription"]);
	
			if (!$cancelled) { 
				return('error');
			}
		}

		// update db
		$new_user_id = 0 - $user_id;
		$wpdb->update( 'cw_subs', array( 'status' => 'acc deleted', 'user_ID' => $new_user_id, 'date_cancelled' => date("Y-m-d H:i:s") ), array( 'user_ID' => $user_id ));

	}


	// Get user meta
	$meta = get_user_meta( $user_id );

	// Delete user's meta
	foreach ( $meta as $key => $val ) {
		delete_user_meta( $user_id, $key );
	}

	// Get user's unique ID
	$unique_id = $wpdb->get_var( "SELECT unique_id FROM cw_settings WHERE user_ID = '".$user_id."'" ); 

	// Delete any additional user data
	$wpdb->delete( 'cw_alerts_email_cur', array( 'unique_id' => $unique_id ));
	$wpdb->delete( 'cw_alerts_email_per', array( 'unique_id' => $unique_id ));
	$wpdb->delete( 'cw_alerts_sms_cur', array( 'user_ID' => $user_id ));
    $wpdb->delete( 'cw_alerts_sms_per', array( 'user_ID' => $user_id ));
    $wpdb->delete( 'cw_alerts_portfolio', array( 'user_ID' => $user_id ));
    $wpdb->delete( 'cw_settings', array( 'user_ID' => $user_id ));
    
    // Delete logs
    $wpdb->delete( 'cw_logs_alerts_email', array( 'user_ID' => $unique_id ));
    $wpdb->delete( 'cw_logs_alerts_sms', array( 'user_ID' => $user_id ));
    $wpdb->delete( 'cw_logs_alerts_portfolio', array( 'user_ID' => $user_id ));

	// Delete the user's account
	wp_delete_user( $user_id );

	// Destroy user's session
	// wp_logout();
		
	wp_die();

}
add_action('wp_ajax_delete_my_acc', 'delete_my_acc');
add_action('wp_ajax_nopriv_delete_my_acc', 'delete_my_acc');



//
// AJAX FUNCTION - CANCEL SUBSCRIPTION
//
function cancel_subscription(){

	// WP NONCE CHECK
	check_ajax_referer( 'my-special-string', 'security' );
	
	global $wpdb;
	
	// Get the current user
	$user_id = get_current_user_id();


	// if subscription exists, then cancel
    $subscription = $wpdb->get_results( "SELECT * FROM cw_subs WHERE user_ID = '".$user_id."' AND status = 'active'", ARRAY_A );

	if(isset($subscription[0])) {
		// if ID higher than 77 (after Cardinity) and is not a promo user, then call Stripe to unsubscribe
		if ($subscription[0]["ID"] > 77 && $subscription[0]["payment_ID"] != "X") {

			$cancelled = cancel_stripe($subscription[0]["subscription"]);
	
			if (!$cancelled) { 
				return('error');
			}
		}
	}


	// update db
	$wpdb->update ( 'cw_subs', 
		array( 
			'status' => 'cancelled',
			'date_cancelled' => date("Y-m-d H:i:s")
		), 
		array( 
			'user_ID' => $user_id
		)
	);
		
	wp_die();

}
add_action('wp_ajax_cancel_subscription', 'cancel_subscription');
add_action('wp_ajax_nopriv_cancel_subscription', 'cancel_subscription');



// Call Stripe to cancel subscription
function cancel_stripe($subscription) {
    $sub = \Stripe\Subscription::retrieve($subscription);
    $sub->cancel();
    return true/false;
}



/// /// ///
/// /// ///
/// /// ///



//
// Promo
//
function promo_shortcode_func() {

    include_once 'promo_IDs.php';

    $user_ID = get_current_user_id();

    foreach ($promo_IDs as $ID) {
        if ($user_ID == $ID) {
            ob_start();	?>

                <br><br><br>
                <hr style="width:260px;margin:0 auto;">
                <br><br>
                <b style="font-size:14px;">Promotional Free Upgrade</b>
                <br>
                <br>
                <span style="line-height:160%;">
                    Premium plan for 1 year<br>
                    Unlimited email alerts and coins in portfolio<br>
                    A total of 100 SMS credits
                </span>
                <br>
                <br>
                Available to your account only
                <br>
                <br>
                <br>
                <span onclick="promoActivate()" class="blacklink" style="text-decoration:underline;cursor:pointer;font-weight:bold;">Activate Premium</span>
                <br>
                <br>
                <br>

            <?php return ob_get_clean(); 
        }

    }

}
add_shortcode( 'promo_shortcode', 'promo_shortcode_func' );


//
// Promo activate
//
function promo_activate() {

    // WP NONCE CHECK
    check_ajax_referer( 'my-special-string', 'security' );
    
    global $wpdb;

    include_once 'promo_IDs.php';
    
    $user_ID = get_current_user_id();

    // $user_ID = 1;

    $exists_in_promo_IDs = in_array($user_ID, $promo_IDs);
    $exists_in_cw_settings = $wpdb->get_var( "SELECT subs FROM cw_settings WHERE user_ID = '".$user_ID."'" );

    if ($exists_in_promo_IDs && !$exists_in_cw_settings) {

        // Update DB
        
        $wpdb->insert( 'cw_subs', 
        array( 
            'user_ID' => $user_ID, 
            'payment_ID' => 'X',
            'date_start' => date("Y-m-d H:i:s"),
            'date_end' => date("Y-m-d H:i:s", strtotime("+12 months", strtotime(date("Y-m-d H:i:s")))),
            'status' => "active",
            )
        );

        $wpdb->update( 'cw_settings', 
            array( 
                'subs' => '1',
                'sms' => '100'
            ), 
            array( 'user_ID' => $user_ID )
        );


        // EMAIL GREETING TO USER
        $userEmail = $wpdb->get_var( "SELECT user_email FROM wp_users WHERE ID = '$user_ID'");

        $GLOBALS['mail']->setFrom('support@coinwink.com', 'Coinwink');
        $GLOBALS['mail']->addAddress($userEmail);
        $GLOBALS['mail']->Subject  = "Welcome to Coinwink Premium";
        $GLOBALS['mail']->Body = "Thank you for subscribing to Coinwink Premium plan.

For questions or feedback, contact us at support@coinwink.com.

Kind regards,
Coinwink";
        $GLOBALS['mail']->Send();
        $GLOBALS['mail']->ClearAllRecipients();

        echo('success');
        exit;

    }
    else {
        echo('Promotion already used');
        exit;
    }

}
add_action('wp_ajax_promo_activate', 'promo_activate');
add_action('wp_ajax_nopriv_promo_activate', 'promo_activate');



/// /// ///
/// /// ///
/// /// ///



// User last login time
function user_last_login( $user_login, $user ) {
	$time = date("Y-m-d H:i:s");
	global $wpdb;
	if ($user->ID != 1) {
		$wpdb->update(
			'cw_settings', 
			array(
				'last_login' => $time
			),
			array(
				'user_ID' => $user->ID
			)
		);
	}
}
add_action( 'wp_login', 'user_last_login', 10, 2 );



//
// Footer
//
function footer_shortcode_func() {
	ob_start();	?>

		<footer style="text-align: center;">

			<?php 
				global $post; 
				// $post_slug = $post->post_name; 
			?>

			<?php 
				if (is_page_template( 'template-home.php' ) || $post->post_title == "Individual" || is_404()) {

					$url = parse_url($_SERVER['REQUEST_URI']);
					$url_slug = $url['path'];
					$slashes = substr_count($url_slug, '/');
					$url_prefix = '';
					if ($slashes == 3) {
						$url_prefix = '..';
					}
					if ($slashes == 4) {
						$url_prefix = '../..';
					}
			?>

				<?php if ( is_user_logged_in() ) { ?>

                    <div style="margin:0 auto;padding-top:4px;padding-bottom:10px;" class="text-footer-links">
                        <a href="<?php echo($url_prefix); ?>/manage-alerts" class="whitelink link-manage-alerts">Alerts</a>
                        &nbsp;|&nbsp;
                        <a href="<?php echo site_url(); ?>/account" class="whitelink">Account</a>    
                        &nbsp;|&nbsp;
                        <a href="<?php echo($url_prefix); ?>/portfolio" class="whitelink link-portfolio">Portfolio</a>
                        &nbsp;|&nbsp;
                        <a href="<?php echo($url_prefix); ?>/watchlist" class="whitelink link-watchlist">Watchlist</a>                    
                    </div>

				<?php } else { ?>

                    <div style="margin:0 auto;padding-top:4px;padding-bottom:10px;" class="text-footer-links">
                        <a href="<?php echo site_url(); ?>/account/#login" class="whitelink">Log in</a>
                        &nbsp;|&nbsp;
                        <a href="<?php echo($url_prefix); ?>/manage-alerts" class="whitelink link-manage-alerts">Alerts</a>
                        &nbsp;|&nbsp;
                        <a href="<?php echo($url_prefix); ?>/portfolio" class="whitelink link-portfolio">Portfolio</a>
                        &nbsp;|&nbsp;
                        <a href="watchlist" class="whitelink link-watchlist">Watchlist</a>
                    </div>

				<?php } ?>

				<div style="margin-top:18px;font-size:10px;" class="text-footer-links">

					<a href="<?php echo($url_prefix); ?>/about" class="whitelink link-about">About</a>&nbsp;|&nbsp;
					<a href="<?php echo($url_prefix); ?>/pricing" class="whitelink link-pricing">Pricing</a>&nbsp;|&nbsp;
					<a href="<?php echo($url_prefix); ?>/terms" class="whitelink link-terms">Terms</a>&nbsp;|&nbsp;
                    <a href="<?php echo($url_prefix); ?>/privacy" class="whitelink link-privacy">Privacy</a>&nbsp;|&nbsp;
                    <a href="<?php echo($url_prefix); ?>/press" class="whitelink link-press">Press</a>&nbsp;|&nbsp;
					<a href="https://coinwink.com/blog/" class="whitelink" target="_blank">Blog</a>&nbsp;|&nbsp;
					<a href="<?php echo($url_prefix); ?>/contacts" class="whitelink link-contacts">Contacts</a>

				</div>
			
			<?php } ?>


			<?php if (is_page_template('template-account.php') || is_page_template('template-changepass.php') || is_page_template('template-portfolio.php') || is_page_template('template-alert.php') ) { ?>

                <div style="margin:0 auto;padding-top:4px;padding-bottom:10px;" class="text-footer-links">
                    <a href="<?php echo site_url(); ?>" class="whitelink">Home</a>
                    &nbsp;|&nbsp;
                    <a href="<?php echo site_url(); ?>/manage-alerts" class="whitelink">Alerts</a>
                    &nbsp;|&nbsp;
                    <a href="<?php echo site_url(); ?>/portfolio" class="whitelink">Portfolio</a>
                    &nbsp;|&nbsp;
                    <a href="<?php echo site_url(); ?>/watchlist" class="whitelink">Watchlist</a>
                </div>

				<div style="margin-top:18px;font-size:10px;" class="text-footer-links">
					
					<a href="<?php echo site_url(); ?>/about" class="whitelink">About</a>&nbsp;|&nbsp;
					<a href="<?php echo site_url(); ?>/pricing" class="whitelink">Pricing</a>&nbsp;|&nbsp;
					<a href="<?php echo site_url(); ?>/terms" class="whitelink">Terms</a>&nbsp;|&nbsp;
                    <a href="<?php echo site_url(); ?>/privacy" class="whitelink">Privacy</a>&nbsp;|&nbsp;
                    <a href="<?php echo site_url(); ?>/press" class="whitelink">Press</a>&nbsp;|&nbsp;
					<a href="https://coinwink.com/blog/" class="whitelink" target="_blank">Blog</a>&nbsp;|&nbsp;
					<a href="<?php echo site_url(); ?>/contacts" class="whitelink">Contacts</a>

				</div>

			<?php } ?>
					
				<div style="height:15px;"></div>

				<span style="line-height:150%;font-size:10px;" class="text-footer">
					<!-- Cryptocurrency Alerts, Watchlist & Portfolio Tracking App -->
					<a href="https://coinwink.com" style="text-decoration:none!important;color:#bfbfbf!important;">Crypto Price Alerts, Watchlist and Portfolio Tracking App</a>
					<br>
					Privacy-Focused, Based on CoinMarketCap
				</span>
					
				<div style="height:40px;"></div>

			</div>
			
		</footer>

	<?php return ob_get_clean(); 
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



// No wp dashboard for users
function blockusers_init() {
    if ( is_admin() && ! current_user_can( 'administrator' ) && 
       ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
        wp_redirect( home_url() );
        exit;
    }
}
add_action( 'init', 'blockusers_init' );



// Remove Admin bar
function remove_admin_bar()
{
    return false;
}
add_filter('show_admin_bar', 'remove_admin_bar'); // Remove Admin bar





//
// On theme's activation, create db tables, default pages and promote admin user to Premium plan
// 
add_action("after_switch_theme", "create_db_tables_and_default_pages");

function create_db_tables_and_default_pages(){

    global $wpdb;
    $table_name = "cw_alerts_email_cur";
	
	// If tables don't exist, then create
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {

        include ABSPATH . "auth_sql.php";

        $dbName = $dbname;
        $dbHost     = $servername;
        $dbUsername = $username;
        $dbPassword = $password;
        $dbName     = $dbname;
        $filePath   = get_template_directory_uri().'/coinwink_db.sql';

        function restoreDatabaseTables($dbHost, $dbUsername, $dbPassword, $dbName, $filePath){
            // Connect & select the database
            $db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName); 
        
            // Temporary variable, used to store current query
            $templine = '';
            
            // Read in entire file
            $lines = file($filePath);
            
            $error = '';
            
            // Loop through each line
            foreach ($lines as $line){
                // Skip it if it's a comment
                if(substr($line, 0, 2) == '--' || $line == ''){
                    continue;
                }
                
                // Add this line to the current segment
                $templine .= $line;
                
                // If it has a semicolon at the end, it's the end of the query
                if (substr(trim($line), -1, 1) == ';'){
                    // Perform the query
                    if(!$db->query($templine)){
                        $error .= 'Error performing query "<b>' . $templine . '</b>": ' . $db->error . '<br /><br />';
                    }
                    
                    // Reset temp variable to empty
                    $templine = '';
                }
            }
            return !empty($error)?$error:true;
        }
        
        restoreDatabaseTables($dbHost, $dbUsername, $dbPassword, $dbName, $filePath);
    
        // Create/restore default pages
        function page_home() {
    
            $post_details = array(
                'post_title'    => 'Home',
                'post_content'  => '',
                'post_status'   => 'publish',
                'post_author'   => 1,
                'post_type' => 'page'
            );
            wp_insert_post( $post_details );
    
            $page = get_page_by_title( 'Home', OBJECT, 'page' );
            $page_id = null == $page ? -1 : $page->ID;
    
            add_post_meta( $page_id, '_wp_page_template', 'template-home.php' );
    
            update_option( 'page_on_front', $page_id );
            update_option( 'show_on_front', 'page' );
	
			
            $post_details = array(
                'post_title'    => 'Portfolio',
                'post_content'  => '',
                'post_status'   => 'publish',
                'post_author'   => 1,
                'post_type' => 'page'
            );
            wp_insert_post( $post_details );
    
            $page = get_page_by_title( 'Portfolio', OBJECT, 'page' );
            $page_id = null == $page ? -1 : $page->ID;
    
			add_post_meta( $page_id, '_wp_page_template', 'template-home.php' );
			

			$post_details = array(
                'post_title'    => 'Watchlist',
                'post_content'  => '',
                'post_status'   => 'publish',
                'post_author'   => 1,
                'post_type' => 'page'
            );
            wp_insert_post( $post_details );
    
            $page = get_page_by_title( 'Watchlist', OBJECT, 'page' );
            $page_id = null == $page ? -1 : $page->ID;
    
			add_post_meta( $page_id, '_wp_page_template', 'template-home.php' );

			
			$post_details = array(
                'post_title'    => 'Alert',
                'post_content'  => '',
                'post_status'   => 'publish',
                'post_author'   => 1,
                'post_type' => 'page'
            );
            wp_insert_post( $post_details );
    
            $page = get_page_by_title( 'Alert', OBJECT, 'page' );
            $page_id = null == $page ? -1 : $page->ID;
    
            add_post_meta( $page_id, '_wp_page_template', 'template-alert.php' );

    
            $post_details = array(
                'post_title'    => 'Account',
                'post_content'  => '[custom-register-form]',
                'post_status'   => 'publish',
                'post_author'   => 1,
                'post_type' => 'page'
            );
            wp_insert_post( $post_details );
    
            $page = get_page_by_title( 'Account', OBJECT, 'page' );
            $page_id = null == $page ? -1 : $page->ID;
    
            add_post_meta( $page_id, '_wp_page_template', 'template-account.php' );
    
    
            $post_details = array(
                'post_title'    => 'Changepass',
                'post_content'  => '',
                'post_status'   => 'publish',
                'post_author'   => 1,
                'post_type' => 'page'
            );
            wp_insert_post( $post_details );
    
            $page = get_page_by_title( 'Changepass', OBJECT, 'page' );
            $page_id = null == $page ? -1 : $page->ID;
    
            add_post_meta( $page_id, '_wp_page_template', 'template-changepass.php' );
    
        }
    
        page_home();

        
        $unique_ID = uniqid();
        $user_ID = 1;

		$wpdb->insert( 'cw_settings', array( 'user_ID' => $user_ID, 'unique_id' => $unique_ID ));

        $wpdb->insert( 'cw_subs', 
        array( 
            'user_ID' => $user_ID, 
            'payment_ID' => 'X',
            'date_start' => date("Y-m-d H:i:s"),
            'date_end' => date("Y-m-d H:i:s", strtotime("+12 months", strtotime(date("Y-m-d H:i:s")))),
            'status' => "active",
            )
        );

        $wpdb->update( 'cw_settings', 
            array( 
                'subs' => '1',
                'sms' => '100'
            ), 
            array( 'user_ID' => $user_ID )
        );

        // Add CMC demo data
        $string_data = file_get_contents(get_template_directory_uri().'/cmc_demo_data.txt');

        $wpdb->update( 'cw_data_cmc', 
            array( 
                'json' => $string_data,
            ), 
            array( 'ID' => 1 )
        );
    }

}
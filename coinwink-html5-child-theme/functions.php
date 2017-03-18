<?php


//
// Required by Captcha by BestWebSoft plugin
//
function add_my_forms( $forms ) {
    $forms['form_slug']   = "Coinwink";
    return $forms;
}
add_filter( 'cptch_add_form', 'add_my_forms' );


//
// FAVICON
//
function coinwink_favicon(){ ?>
    <!-- Custom Favicons -->
    <link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/img/favicon.ico" type="image/x-icon">
	<link rel="icon" href="<?php echo get_stylesheet_directory_uri(); ?>/img/favicon.ico" type="image/x-icon">
    <?php }
add_action('wp_head','coinwink_favicon');


//
// AJAX FUNCTION
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
	$symbol = htmlspecialchars($_POST['symbol']);
	$below = str_replace(',', '.', htmlspecialchars($_POST['below']));
	$below_currency = htmlspecialchars($_POST['below_currency']);
	$above = str_replace(',', '.', htmlspecialchars($_POST['above']));
	$above_currency = htmlspecialchars($_POST['above_currency']);
	$email = htmlspecialchars($_POST['email']);

// Check if the e-mail exist. If yes, then use the same ID, if no, then generate new ID
$unique_id = $wpdb->get_results( "SELECT unique_id FROM coinwink WHERE email = '".$email."'" );
$unique_id = $unique_id[0]->unique_id;
		if (!$unique_id) {
		$unique_id = uniqid();
		} 

if($wpdb->insert('coinwink', array(
		'coin' => $coin,
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

You will receive e-mail alert when '. $coin .' ('. $symbol .') price will be below: '. $below .' '. $below_currency .'.

You can manage your alert(-s) with this unique id: '. $unique_id .' at https://coinwink.com

Wink,
Coinwink';
}
if ($below && $above)
{
$message = ''. $coin .' ('. $symbol .') price alert has been created.

You will receive e-mail alerts when '. $coin .' ('. $symbol .') price will be above: '. $above .' '. $above_currency .' and below: '. $below .' '. $below_currency .'.

You can manage your alert(-s) with this unique id: '. $unique_id .' at https://coinwink.com

Wink,
Coinwink';
}
if ($above && !$below)
{
$message = ''. $coin .' ('. $symbol .') price alert has been created.

You will receive e-mail alert when '. $coin .' ('. $symbol .') price will be above: '. $above .' '. $above_currency .'.

You can manage your alert(-s) with this unique id: '. $unique_id .' at https://coinwink.com

Wink,
Coinwink';
}
$headers = 'From: "Coinwink" <alert@coinwink.com>' . "\r\n" .
    'Reply-To: donotreply@coinwink.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

mail($to, $subject, $message, $headers);

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

$unique_id = $wpdb->get_results( "SELECT * FROM coinwink WHERE unique_id = '".$unique_id."'" );


if($unique_id) {
	foreach($unique_id as $alert) {
		echo('<div><br><form type="post" id="form_delete_alert"  action="">
			<h3 class="fs-labels">
			Alert when <b>'. $alert->coin .' ('. $alert->symbol .')</b> is:<br>');
		if ($alert->below && $alert->below_sent) {
			echo('<s>below: '. $alert->below .' '. $alert->below_currency .'</s>');
		}
		elseif ($alert->below && !$alert->below_sent) {
			echo('below: '. $alert->below .' '. $alert->below_currency .'');
		}
		if ($alert->below && $alert->above) {
			echo('<br>');
		}
		if ($alert->above && $alert->above_sent) {
			echo('<s>above: '. $alert->above .' '. $alert->above_currency .'</s>');
		}
		elseif ($alert->above && !$alert->above_sent) {
			echo('above: '. $alert->above .' '. $alert->above_currency .'');
		}
		echo('<br>
			 <input name="action" type="hidden" value="delete_alert" />
			  <input type="hidden" name="alert_id" id="alert_id" value="'. $alert->ID .'">
			  <input type="submit" style="margin-top:5px;" class="submit" value="Delete alert" />');
		echo('</h3></form></div>');
		}
}
else {
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


//
// Required by Theme
//
function my_theme_enqueue_styles() {

    $parent_style = 'parent-style'; // This is 'twentyfifteen-style' for the Twenty Fifteen theme.

    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( $parent_style ),
        wp_get_theme()->get('Version')
    );
}
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );


?>
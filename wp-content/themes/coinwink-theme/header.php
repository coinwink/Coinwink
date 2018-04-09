<!doctype html>

<?php 
	global $post;
    $post_slug=$post->post_name; //eth

	// Get coin price data from the database
	$resultdb2 = $wpdb->get_results( "SELECT json FROM coinwink_json" , ARRAY_A);
	$newarrayjson = $resultdb2[0][json];
	$newarrayunserialized = unserialize($newarrayjson);

	foreach ($newarrayunserialized as $jsoncoin) {
		if ($jsoncoin['symbol'] == strtoupper($post_slug)) {
			$meta_name = $jsoncoin['name'];
			$meta_symbol = $jsoncoin['symbol'];
			$meta_price_btc = $jsoncoin['price_btc'];
			$meta_price_usd = $jsoncoin['price_usd'];
			break;
			}
	}
	
	if (($post_slug != "home") && ($post_slug != "account") && ($post_slug != "changepass")) {
	$coinwink_excerpt = 'Free email & SMS alerts service for '. $meta_name .' ('. $meta_symbol .') and other crypto currencies.'; 
	// $coinwink_excerpt = 'Free Email & SMS alerts service. '. $meta_name .' ('. $meta_symbol .') price now: '. $meta_price_btc .' BTC | '. $meta_price_usd .' USD.'; 
	}
	else {
	$coinwink_excerpt = 'Free, open source, privacy focused email & SMS crypto currency price alerts service. Create alerts for 1500+ coins in BTC, ETH, EUR, AUD, CAD, KRW and JPY.'; 	
	}
?>

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="<?php echo($coinwink_excerpt); ?>">
	<link href="//www.google-analytics.com" rel="dns-prefetch">

	<!-- Favicon -->
	<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png?v=5A549r9XJg">
	<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png?v=5A549r9XJg">
	<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png?v=5A549r9XJg">
	<link rel="manifest" href="/manifest.json?v=5A549r9XJg">
	<link rel="mask-icon" href="/safari-pinned-tab.svg?v=5A549r9XJg" color="#008db5">
	<link rel="shortcut icon" href="/favicon.ico?v=5A549r9XJg">
	<meta name="apple-mobile-web-app-title" content="Coinwink">
	<meta name="application-name" content="Coinwink">
	<meta name="theme-color" content="#ffffff">

	<!-- Social sharing presentation -->
	<meta property="og:image" content="<?php echo get_stylesheet_directory_uri(); ?>/img/logo_shadow.png"/>
	<meta property="og:title" content="Coinwink.com - Cryptocurrency Price Alerts" />
	<meta property="og:description" content="Free, open source, privacy-focused email & SMS cryptocurrency price alerts service." />
	<meta property="og:image" content="<?php echo get_stylesheet_directory_uri(); ?>/img/fb_share.png"/>

	<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
	<link href="<?php echo get_stylesheet_directory_uri(); ?>/style.css" rel="stylesheet" />

	<?php wp_head(); ?>

	<!-- Select2 -->
	<link href="<?php echo get_stylesheet_directory_uri(); ?>/css/select2.css" rel="stylesheet" />
	<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/select2.min.js"></script>

	<!-- Sidr slide menu -->
	<meta name="viewport" content="width=device-width,minimum-scale=1">
	<link href="<?php echo get_stylesheet_directory_uri(); ?>/css/jquery.sidr.dark.css" rel="stylesheet" />

	<style>
	#mobile-header {
		display: none;
	}
	@media only screen and (max-width: 767px){
		#mobile-header {
			display: block;
		}
	}
	#navigation {
		display: none;
	}
	</style>

</head>


<body <?php body_class(); ?>>



<!doctype html>

<html <?php language_attributes(); ?> class="no-js">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="<?php bloginfo('description'); ?>">
	<link href="//www.google-analytics.com" rel="dns-prefetch">

	<title>Coinwink: Crypto-currency (Bitcoin, Ethereum...) price alerts, alarms, reminders</title>

	<!-- Favicon -->
	<link rel="apple-touch-icon" sizes="120x120" href="apple-touch-icon.png">
	<link rel="icon" type="image/png" href="favicon-32x32.png" sizes="32x32">
	<link rel="icon" type="image/png" href="favicon-16x16.png" sizes="16x16">
	<link rel="manifest" href="manifest.json">
	<link rel="mask-icon" href="safari-pinned-tab.svg" color="#2b7d97">
	<link rel="shortcut icon" href="favicon.ico">
	<meta name="msapplication-config" content="browserconfig.xml">
	<meta name="theme-color" content="#ffffff">

	<!-- Social sharing presentation -->
	<meta property="og:image" content="<?php echo get_stylesheet_directory_uri(); ?>/img/logo_shadow.png"/>
	<meta property="og:title" content="Coinwink.com - Crypto currency price alerts" />
	<meta property="og:description" content="Create and manage your crypto currency price alerts for free." />
	<meta property="og:image" content="<?php echo get_stylesheet_directory_uri(); ?>/img/fb_share.png"/>

	<?php wp_head(); ?>

	<script>
	// conditionizr.com
	// configure environment tests
	conditionizr.config({
		assets: '<?php echo get_template_directory_uri(); ?>',
		tests: {}
	});
	</script>

	<!-- Select2 -->
	<link href="<?php echo get_stylesheet_directory_uri(); ?>/css/select2.css" rel="stylesheet" />
	<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/select2.min.js"></script>
</head>

<body <?php body_class(); ?>>

<!-- wrapper -->
<div class="wrapper">
<!doctype html>
<html lang="en">

<?php
    global $post;
    global $wpdb;
?>

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<link href="//www.google-analytics.com" rel="dns-prefetch">

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="https://coinwink.com/img/favicon/apple-touch-icon.png?v=2bBgz68qLw">
    <link rel="icon" type="image/png" sizes="32x32" href="https://coinwink.com/img/favicon/favicon-32x32.png?v=2bBgz68qLw">
    <link rel="icon" type="image/png" sizes="16x16" href="https://coinwink.com/img/favicon/favicon-16x16.png?v=2bBgz68qLw">
    <link rel="shortcut icon" href="https://coinwink.com/img/favicon/favicon.ico?v=2bBgz68qLw">
    <meta name="apple-mobile-web-app-title" content="Coinwink">
    <meta name="application-name" content="Coinwink">

    <meta name="twitter:card" content="summary" />
    <meta name="twitter:site" content="@coinwink" />


    <!-- CSS -->
    <link href="<?php echo get_stylesheet_directory_uri(); ?>/style.css?v=601" rel="stylesheet" />
    <link href="<?php echo get_stylesheet_directory_uri(); ?>/style-select2.css?v=601" rel="stylesheet" />
    
    <?php
        if ( is_user_logged_in() ) {
            $user_ID = get_current_user_id();
            $result = $wpdb->get_results( "SELECT theme, t_s, t_i, cur_main, cur_p, cur_w, conf_w FROM cw_settings WHERE user_ID = '".$user_ID."'", ARRAY_A);
            $theme = $result[0]["theme"];
            $t_s = $result[0]["t_s"];
            $t_i = $result[0]["t_i"];
            $cur_p = $result[0]["cur_p"];
            if ($cur_p == "") { $cur_p = "USD"; }
            $cur_w = $result[0]["cur_w"];
            if ($cur_w == "") { $cur_w = "USD"; }
            $conf_w = $result[0]["conf_w"];
            if ($conf_w == "") { $conf_w = "price"; }
            $cur_main = $result[0]["cur_main"];
            if ($cur_main == "") { $cur_main = "USD"; }
            // var_dump($result);
        }

        if ($theme == 'matrix') {
            ?>
                <link href="<?php echo get_stylesheet_directory_uri(); ?>/style-matrix.css?v=600" rel="stylesheet" />

                <link rel="manifest" href="https://coinwink.com/img/favicon/site.webmanifest-matrix?v=2bBgz68qLw">
                <meta name="msapplication-TileColor" content="#000">
                <meta name="theme-color" content="#000">
                <link rel="mask-icon" href="https://coinwink.com/img/favicon/safari-pinned-tab.svg?v=2bBgz68qLw" color="#000">
                <meta name="msapplication-config" content="https://coinwink.com/img/favicon/browserconfig-matrix.xml?v=2bBgz68qLw">
            <?php
        }

        else {
            ?>
                <link rel="manifest" href="https://coinwink.com/img/favicon/site.webmanifest?v=2bBgz68qw">
                <meta name="msapplication-TileColor" content="#4f585b">
                <meta name="theme-color" content="#4f585b">
                <link rel="mask-icon" href="https://coinwink.com/img/favicon/safari-pinned-tab.svg?v=2bBgz68qLw" color="#4f585b">
                <meta name="msapplication-config" content="https://coinwink.com/img/favicon/browserconfig.xml?v=2bBgz68qLw">
            <?php
        }
    ?>



    <!-- ---------------- -->
    <!-- Custom META tags -->
    <!-- ---------------- -->

    <?php

        $urls_pages = array (
            0 => 'about',
            1 => 'pricing',
            2 => 'terms',
            3 => 'privacy',
            4 => 'press',
            5 => 'contacts',
            6 => 'subscription',
            7 => 'manage-alerts',
            8 => 'portfolio',
            9 => 'email',
            10 => 'email-per',
            11 => 'sms',
            12 => 'sms-per',
            13 => 'account',
            14 => 'changepass',
            15 => 'home',
            16 => 'watchlist',
            17 => 'es',
        );

        $post_slug = $post->post_name; // e.g. 'portfolio', 'eth'...

        // var_dump($post);

        if ($post_slug == 'alert') {
            $type = "page";
        }
        else if (in_array($post_slug, $urls_pages)) {
            $type = "page";
        }
        else if ($post->post_title == "Individual") {
            $type = "coin";
        }


        // If current page is for coin
        if ($type == 'coin') {

            // // Get coin price data from the database
            // $resultdb2 = $wpdb->get_results( "SELECT json FROM cw_data_cmc" , ARRAY_A);
            // $newarrayjson = $resultdb2[0]['json'];
            // $newarrayunserialized = unserialize($newarrayjson);

            // Get coin price data from functions.php
            $newarrayunserialized = apply_filters( 'cmc_data_backend', '' );

            foreach ($newarrayunserialized as $jsoncoin) {
                if ($jsoncoin['symbol'] == strtoupper($post_slug)) {
                    $meta_name = $jsoncoin['name'];
                    $meta_symbol = $jsoncoin['symbol'];
                    $meta_price_btc = $jsoncoin['price_btc'];
                    $meta_price_usd = $jsoncoin['price_usd'];
                    break;
                }
            }

            $cw_title = $meta_name .' ('. $meta_symbol .') Price Alerts, Watchlist and Portfolio App';
            // $cw_title = $meta_name .' ('. $meta_symbol .') Price Alerts App, Alarms, Notifications';
            $cw_description = 'Email & SMS crypto price alerts, watchlist and portfolio tracking app for '. $meta_name .' ('. $meta_symbol .') and other 3500+ coins and tokens. '. $meta_symbol .' price now: '. number_format($meta_price_btc, 8, '.', '') .' BTC | '. number_format($meta_price_usd, 4, '.', '') .' USD.';

            ?>

                <meta property="og:title" content="Coinwink" />
                <meta property="og:description" content="<?php echo($meta_name .' ('. $meta_symbol .') Price Alerts, Watchlist & Portfolio Tracking App'); ?>" />
                
                <?php
                    if ($post_slug == 'eth') {
                        ?>
                            <meta property="og:image:width" content="1200">
                            <meta property="og:image:height" content="1200">
                            <meta property="og:image" content="https://coinwink.com/img/thumb-eth.png"/>
                            <meta name="twitter:image" content="https://coinwink.com/img/thumb-eth.png"/>
                        <?php
                    }
                    else if ($post_slug == 'doge') {
                        ?>
                            <meta property="og:image:width" content="1200">
                            <meta property="og:image:height" content="900">
                            <meta property="og:image" content="https://coinwink.com/img/thumb-doge.png?v=001"/>
                            <meta name="twitter:image" content="https://coinwink.com/img/thumb-doge.png?v=001"/>
                        <?php
                    }
                    else if ($post_slug == 'req') {
                        ?>
                            <meta property="og:image:width" content="1200">
                            <meta property="og:image:height" content="1000">
                            <meta property="og:image" content="https://coinwink.com/img/thumb-req.png"/>
                            <meta name="twitter:image" content="https://coinwink.com/img/thumb-req.png"/>
                        <?php
                    }
                    else if ($post_slug == 'rdd') {
                        ?>
                            <meta property="og:image:width" content="1200">
                            <meta property="og:image:height" content="900">
                            <meta property="og:image" content="https://coinwink.com/img/thumb-rdd.png"/>
                            <meta name="twitter:image" content="https://coinwink.com/img/thumb-rdd.png"/>
                        <?php
                    }
                    else {
                        ?>
                            <meta property="og:image" content="https://coinwink.com/img/thumb-main.png"/>
                            <meta name="twitter:image" content="https://coinwink.com/img/thumb-main.png"/>
                        <?php
                    }
                ?>

                <meta name="twitter:title" content="Coinwink" />
                <meta name="twitter:description" content="<?php echo($meta_name .' ('. $meta_symbol .') Price Alerts, Watchlist & Portfolio Tracking App'); ?>" />
                <meta name="twitter:image:alt" content="logo" />

            <?php

        }


        // If current page is NOT for coin
        else if ($type == "page") {
            if ($post_slug == "about") {
                $cw_title = "Coinwink - About";

                ?>

                <meta property="og:title" content="Coinwink" />
                <meta property="og:description" content="About" />
                <meta property="og:image" content="https://coinwink.com/img/thumb-main.png"/>

                <meta name="twitter:title" content="Coinwink" />
                <meta name="twitter:description" content="About" />
                <meta name="twitter:image" content="https://coinwink.com/img/thumb-main.png"/>
                <meta name="twitter:image:alt" content="logo" />

                <meta property="og:image:width" content="1200">
                <meta property="og:image:height" content="1200">

                <?php

            }
            else if ($post_slug == "pricing") {
                
                $cw_title = "Coinwink - Pricing";

                ?>

                <meta property="og:title" content="Coinwink" />
                <meta property="og:description" content="Pricing" />
                <meta property="og:image" content="https://coinwink.com/img/thumb-main.png"/>

                <meta name="twitter:title" content="Coinwink" />
                <meta name="twitter:description" content="Pricing" />
                <meta name="twitter:image" content="https://coinwink.com/img/thumb-main.png"/>
                <meta name="twitter:image:alt" content="logo" />

                <meta property="og:image:width" content="1200">
                <meta property="og:image:height" content="1200">

                <?php

            }
            else if ($post_slug == "terms") {
                
                $cw_title = "Coinwink - Terms & Conditions";

                ?>

                <meta property="og:title" content="Coinwink" />
                <meta property="og:description" content="Terms and Conditions" />
                <meta property="og:image" content="https://coinwink.com/img/thumb-main.png"/>

                <meta name="twitter:title" content="Coinwink" />
                <meta name="twitter:description" content="Terms and Conditions" />
                <meta name="twitter:image" content="https://coinwink.com/img/thumb-main.png"/>
                <meta name="twitter:image:alt" content="logo" />

                <meta property="og:image:width" content="1200">
                <meta property="og:image:height" content="1200">

                <?php

            }
            else if ($post_slug == "privacy") {
             
                $cw_title = "Coinwink - Privacy Policy";

                ?>

                <meta property="og:title" content="Coinwink" />
                <meta property="og:description" content="Privacy Policy" />
                <meta property="og:image" content="https://coinwink.com/img/thumb-main.png"/>

                <meta name="twitter:title" content="Coinwink" />
                <meta name="twitter:description" content="Privacy Policy" />
                <meta name="twitter:image" content="https://coinwink.com/img/thumb-main.png"/>
                <meta name="twitter:image:alt" content="logo" />

                <meta property="og:image:width" content="1200">
                <meta property="og:image:height" content="1200">

                <?php

            }
            else if ($post_slug == "press") {
                
                $cw_title = "Coinwink - Press Kit";

                ?>

                <meta property="og:title" content="Coinwink" />
                <meta property="og:description" content="Press" />
                <meta property="og:image" content="https://coinwink.com/img/thumb-main.png"/>

                <meta name="twitter:title" content="Coinwink" />
                <meta name="twitter:description" content="Press" />
                <meta name="twitter:image" content="https://coinwink.com/img/thumb-main.png"/>
                <meta name="twitter:image:alt" content="logo" />

                <meta property="og:image:width" content="1200">
                <meta property="og:image:height" content="1200">

                <?php

            }
            else if ($post_slug == "contacts") {
                
                $cw_title = "Coinwink - Contacts";

                ?>

                <meta property="og:title" content="Coinwink" />
                <meta property="og:description" content="Contacts" />
                <meta property="og:image" content="https://coinwink.com/img/thumb-main.png"/>

                <meta name="twitter:title" content="Coinwink" />
                <meta name="twitter:description" content="Contacts" />
                <meta name="twitter:image" content="https://coinwink.com/img/thumb-main.png"/>
                <meta name="twitter:image:alt" content="logo" />

                <meta property="og:image:width" content="1200">
                <meta property="og:image:height" content="1200">

                <?php

            }
            else if ($post_slug == "subscription") {

                $cw_title = "Coinwink - Subscribe to Premium Plan";

                ?>

                <meta property="og:title" content="Coinwink" />
                <meta property="og:description" content="Subscribe to Premium Plan" />
                <meta property="og:image" content="https://coinwink.com/img/thumb-main.png"/>

                <meta name="twitter:title" content="Coinwink" />
                <meta name="twitter:description" content="Subscribe to Premium Plan" />
                <meta name="twitter:image" content="https://coinwink.com/img/thumb-main.png"/>
                <meta name="twitter:image:alt" content="logo" />

                <meta property="og:image:width" content="1200">
                <meta property="og:image:height" content="1200">

                <?php

            }
            else if ($post_slug == "manage-alerts") {

                $cw_title = "Coinwink - Manage Alerts";

                ?>

                <meta property="og:title" content="Coinwink" />
                <meta property="og:description" content="Alerts Management" />
                <meta property="og:image" content="https://coinwink.com/img/thumb-main.png"/>

                <meta name="twitter:title" content="Coinwink" />
                <meta name="twitter:description" content="Alerts Management" />
                <meta name="twitter:image" content="https://coinwink.com/img/thumb-main.png"/>
                <meta name="twitter:image:alt" content="logo" />

                <meta property="og:image:width" content="1200">
                <meta property="og:image:height" content="1200">

                <?php

            }
            else if ($post_slug == "portfolio") {
                $cw_title = "Coinwink - Crypto Portfolio Tracker and Manager App";
                $cw_description = 'Track and manage your cryptocurrency assets. Return on investment (ROI) calculator, notes, automated multiple-coin alerts, currency converter. Portfolio tracking app for Bitcoin, Ethereum, and other 3500+ crypto coins and tokens.';
            
                ?>

                <meta property="og:title" content="Coinwink - Portfolio" />
                <meta property="og:description" content="Crypto portfolio tracker for 3500+ cryptocurrencies. Return on investment (ROI) calculator, notes, automated multiple-coin alerts, currency converter. Portfolio tracking app for Bitcoin, Ethereum, and other 3500+ crypto coins and tokens." />

                <meta property="og:image" content="https://coinwink.com/img/thumb-portfolio-tracker.png"/>

                <meta name="twitter:title" content="Coinwink - Portfolio" />
                <meta name="twitter:description" content="Crypto portfolio tracker for 3500+ cryptocurrencies. Return on investment (ROI) calculator, notes, automated multiple-coin alerts, currency converter. Portfolio tracking app for Bitcoin, Ethereum, and other 3500+ crypto coins and tokens." />

                <meta name="twitter:image" content="https://coinwink.com/img/thumb-portfolio-tracker.png" />

                <meta name="twitter:image:alt" content="App screenshot" />

                <meta property="og:image:width" content="1664">
                <meta property="og:image:height" content="936">

                <?php
            }
            else if ($post_slug == "watchlist") {
                $cw_title = "Coinwink - Crypto Watchlist App for 3500+ Cryptocurrencies";
                $cw_description = 'Keep an eye on your favorite crypto assets. Track price change, volume, market cap, and other data with the cryptocurrency watchlist app.';
            
                ?>

                <meta property="og:title" content="Coinwink - Watchlist" />
                <meta property="og:description" content="Crypto watchlist app for 3500+ cryptocurrencies. Keep an eye on your favorite crypto coins and tokens." />
                <meta property="og:image" content="https://coinwink.com/img/thumb-crypto-watchlist-2.png"/>

                <meta name="twitter:title" content="Coinwink - Watchlist" />
                <meta name="twitter:description" content="Crypto watchlist app for 3500+ cryptocurrencies. Keep an eye on your favorite crypto coins and tokens." />

                <meta name="twitter:image" content="https://coinwink.com/img/thumb-crypto-watchlist-2.png" />

                <meta name="twitter:image:alt" content="App screenshot" />

                <meta property="og:image:width" content="1000">
                <meta property="og:image:height" content="1000">

                <?php
            }
            else if ($post_slug == "email") {
                
                $cw_title = "Coinwink - Email Price Alert for Bitcoin, Free Crypto Alerts";
                $cw_description = 'Free, fast and reliable e-mail crypto price alerts app for 3500+ cryptocurrencies. Create alerts in BTC, ETH, EUR, GBP, AUD, CAD, BRL, MXN, JPY and SGD currencies.';

                ?>

                <meta property="og:title" content="Coinwink" />
                <meta property="og:description" content="Free Email Crypto Alerts for 3500+ Cryptocurrencies" />
                <meta property="og:image" content="https://coinwink.com/img/thumb-email-crypto-alerts.png"/>

                <meta name="twitter:title" content="Coinwink" />
                <meta name="twitter:description" content="Free Email Crypto Alerts for 3500+ Cryptocurrencies" />
                <meta name="twitter:image" content="https://coinwink.com/img/thumb-email-crypto-alerts.png"/>

                <meta name="twitter:image:alt" content="App screenshot" />

                <meta property="og:image:width" content="1664">
                <meta property="og:image:height" content="936">

                <?php

            }
            else if ($post_slug == "email-per") {
                
                $cw_title = "Coinwink - Email Percentage Alert for Bitcoin, Free Cryptocurrency Alerts";
                $cw_description = 'Free, fast and reliable e-mail percentage alerts for 3500+ cryptocurrencies. Create alerts in BTC, ETH, EUR, GBP, AUD, CAD, BRL, MXN, JPY and SGD.';

                ?>

                <meta property="og:title" content="Coinwink" />
                <meta property="og:description" content="Email Percentage Crypto Alerts" />
                <meta property="og:image" content="https://coinwink.com/img/thumb-email-crypto-alerts.png"/>

                <meta name="twitter:title" content="Coinwink" />
                <meta name="twitter:description" content="Email Percentage Crypto Alerts" />
                <meta name="twitter:image" content="https://coinwink.com/img/thumb-email-crypto-alerts.png"/>

                <meta name="twitter:image:alt" content="App screenshot" />

                <meta property="og:image:width" content="1664">
                <meta property="og:image:height" content="936">

                <?php

            }
            else if ($post_slug == "sms") {
                
                $cw_title = "Coinwink - SMS Price Alerts for Bitcoin, Ethereum, Cryptocurrency";
                $cw_description = 'Fast and reliable SMS crypto price alerts app. Global, worldwide reach! 3500+ cryptocurrencies. Create alerts in BTC, ETH, EUR, GBP, AUD, CAD, BRL, MXN, JPY and SGD currencies.';
            
                ?>

                <meta property="og:title" content="Coinwink" />
                <meta property="og:description" content="SMS Crypto Alerts" />
                <meta property="og:image" content="https://coinwink.com/img/thumb-sms-crypto-alerts.png"/>

                <meta name="twitter:title" content="Coinwink" />
                <meta name="twitter:description" content="SMS Crypto Alerts" />
                <meta name="twitter:image" content="https://coinwink.com/img/thumb-sms-crypto-alerts.png"/>

                <meta name="twitter:image:alt" content="App screenshot" />

                <meta property="og:image:width" content="1664">
                <meta property="og:image:height" content="936">

                <?php

            }
            else if ($post_slug == "sms-per") {

                $cw_title = "Coinwink - SMS Percentage Alerts for Bitcoin, 3500+ Crypto Coins and Tokens";
                $cw_description = 'Fast & reliable SMS crypto percentage alerts. Global, worldwide reach! 3500+ cryptocurrencies. Set alerts in BTC, ETH, EUR, GBP, AUD, CAD, BRL, MXN, JPY and SGD currencies.';

                ?>

                <meta property="og:title" content="Coinwink" />
                <meta property="og:description" content="SMS Percentage Crypto Alerts" />
                <meta property="og:image" content="https://coinwink.com/img/thumb-sms-crypto-alerts.png"/>

                <meta name="twitter:title" content="Coinwink" />
                <meta name="twitter:description" content="SMS Percentage Crypto Alerts" />
                <meta name="twitter:image" content="https://coinwink.com/img/thumb-sms-crypto-alerts.png"/>

                <meta name="twitter:image:alt" content="App screenshot" />

                <meta property="og:image:width" content="1664">
                <meta property="og:image:height" content="936">
                
                <?php
                
            }
            else if ($post_slug == "account") {
                $cw_title = "Coinwink - My Account";

                ?>

                <meta property="og:title" content="Coinwink" />
                <meta property="og:description" content="Access Your Account" />
                <meta property="og:image" content="https://coinwink.com/img/thumb-main.png"/>

                <meta name="twitter:title" content="Coinwink" />
                <meta name="twitter:description" content="Access Your Account" />
                <meta name="twitter:image" content="https://coinwink.com/img/thumb-main.png"/>

                <meta name="twitter:image:alt" content="logo" />

                <meta property="og:image:width" content="1200">
                <meta property="og:image:height" content="1200">

                <?php
                
            }
            else if ($post_slug == "changepass") {
                $cw_title = "Coinwink - Password Management";

                ?>

                <meta property="og:title" content="Coinwink" />
                <meta property="og:description" content="Password Management" />
                <meta property="og:image" content="https://coinwink.com/img/thumb-main.png"/>

                <meta name="twitter:title" content="Coinwink" />
                <meta name="twitter:description" content="Password Management" />
                <meta name="twitter:image" content="https://coinwink.com/img/thumb-main.png"/>

                <meta name="twitter:image:alt" content="logo" />

                <meta property="og:image:width" content="1200">
                <meta property="og:image:height" content="1200">

                <?php
            }
            else if ($post_slug == "alert") {
                $cw_title = "Coinwink - Crypto Alert";

                ?>

                <meta property="og:title" content="Coinwink" />
                <meta property="og:description" content="Crypto Alert" />
                <meta property="og:image" content="https://coinwink.com/img/thumb-main.png"/>

                <meta name="twitter:title" content="Coinwink" />
                <meta name="twitter:description" content="Crypto Alert" />
                <meta name="twitter:image" content="https://coinwink.com/img/thumb-main.png"/>

                <meta name="twitter:image:alt" content="logo" />

                <meta property="og:image:width" content="1200">
                <meta property="og:image:height" content="1200">

                <?php
            }
            else if ($post_slug == "home") {
                $cw_title = "Coinwink - Bitcoin BTC Price Alert, Cryptocurrency Alerts App";
                $cw_description = "Free email & SMS crypto alerts app. Cryptocurrency price alerts, alarms, notifications, reminders for Bitcoin (BTC) and other 3500+ crypto coins and tokens. Create alerts in BTC, ETH, EUR, GBP, AUD, CAD, BRL, MXN, JPY and SGD currencies.";
                
                ?>

                <meta property="og:title" content="Coinwink - Crypto Alerts, Watchlist & Portfolio" />
                <meta property="og:description" content="Cryptocurrency price alerts, watchlist & portfolio tracking app for Bitcoin, Ethereum, and other 3500+ crypto coins and tokens." />

                <meta property="og:image" content="https://coinwink.com/img/thumb-main.png"/>

                <meta name="twitter:title" content="Coinwink - Crypto Alerts, Watchlist & Portfolio" />
                <meta name="twitter:description" content="Cryptocurrency price alerts, watchlist & portfolio tracking app for Bitcoin, Ethereum, and other 3500+ crypto coins and tokens." />

                <meta name="twitter:image" content="https://coinwink.com/img/thumb-main.png"/>

                <meta name="twitter:image:alt" content="logo" />

                <meta property="og:image:width" content="1200">
                <meta property="og:image:height" content="1200">

                <?php
            }
            else if ($post_slug == "es") {
                
                $cw_title = "Coinwink - Alerta por criptomonedas para Bitcoin BTC, Ethereum ETH y otra 3500+ coins y tokens";
                $cw_description = 'Aplicación gratuita, rápida y confiable de alertas de precios de criptomonedas para más de 3500 criptomonedas. Cree alertas en monedas BTC, ETH, EUR, GBP, AUD, CAD, BRL, MXN, JPY y SGD.';

                ?>

                <meta property="og:title" content="Coinwink" />
                <meta property="og:description" content="Alerta por criptomonedas para Bitcoin BTC, Ethereum ETH y otra 3500+ coins y tokens" />
                <meta property="og:image" content="https://coinwink.com/img/thumb-main.png"/>

                <meta name="twitter:title" content="Coinwink" />
                <meta name="twitter:description" content="Alerta por criptomonedas para Bitcoin BTC, Ethereum ETH y otra 3500+ coins y tokens" />

                <meta name="twitter:image" content="https://coinwink.com/img/thumb-main.png"/>

                <meta name="twitter:image:alt" content="logo" />

                <meta property="og:image:width" content="1200">
                <meta property="og:image:height" content="1200">

                <?php
            }
        }

        else {
            // 404
            $cw_title = "404";
        }

    ?>


    <title><?php echo($cw_title); ?></title>

    <?php if($cw_description) { ?>
        <meta name="description" content="<?php echo($cw_description); ?>">
    <?php } ?>
    

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-57930548-9"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-57930548-9');
    </script>


    <!-- GLOBAL JS -->
    <script>
        coinwinkEnv = 'dev';
        // coinwinkEnv = 'live';

        // Navigo js router
        if (coinwinkEnv == 'dev') {
            homePath = "http://localhost/coinwink/";
        }
        else {
            homePath = "https://coinwink.com/";
        }

        empty = ' ';

        var isLoggedIn = false;
    </script>


    <?php if ( is_user_logged_in() ) { ?>

        <script>
            var t_s = '<?php echo($t_s) ?>';
            var t_i = '<?php echo($t_i) ?>';

            if (t_i == '') {
                t_i = '0.65';
            }
            t_i = Number(t_i);
            // console.log(t_s, t_i)

            var cur_main = '<?php echo($cur_main) ?>';
            var cur_p = '<?php echo($cur_p) ?>';
            var cur_w = '<?php echo($cur_w) ?>';
            var conf_w = '<?php echo($conf_w) ?>';

            isLoggedIn = true;
            // console.log(cur_p, cur_w, conf_w);
        </script>

    <?php } ?>


    <?php wp_head(); ?>


</head>


<body <?php body_class(); ?>>


<!-- SVG icons -->
<svg style="display: none">
    <defs>

        <symbol id="svg-alert-delete" viewBox="0 0 12.45 12.45">
            <path d="M299.6,390.6l11.25,11.25m0-11.25L299.6,401.85" transform="translate(-299 -390)" 
            style="fill:none;stroke:#888888;stroke-linecap:round;stroke-linejoin:round;stroke-width:1px"/>
        </symbol>

        <symbol id="checkmark" viewBox="0 0 512 444.03">
            <polygon points="202.62 444.03 0 257.38 70.51 180.82 191.97 292.67 431.44 0 512 65.92 202.62 444.03"></polygon> 
        </symbol>

        <symbol id="radiomark" viewBox="0 0 200 200">
            <circle cx="100" cy="100" r="100" />
        </symbol>

    </defs>
</svg>



<?php if ($theme == 'matrix') { ?>
    
    <canvas id="canv" style="position:fixed;z-index:-2;top:0;left:0;"></canvas>
    <div class="overlay" id="matrix-overlay" style="z-index:-1;"></div>


    <script>
        var matrixTransp = t_i;

        var bg = 'rgba(0,0,0,'+matrixTransp+')';
        document.getElementById('matrix-overlay').style.backgroundColor = bg; 

        function moreTransp() {
            
            if (typeof(matrixTr) == 'undefined') {
                matrixTr = t_i;
                if (t_i == '') {t_i = 0.65}
            }
            // console.log(matrixTr, matrixTransp)
            if (matrixTransp < 0) { matrixTransp == 0.00; return; }

            if (matrixTransp == 0.00) {
                // console.log(matrixTransp.toFixed(2), Number(matrixTr).toFixed(2))
                if (matrixTransp.toFixed(2) == Number(matrixTr).toFixed(2)) {
                    matrixTransp = 0.00;
                    matrixTr = 0.00;
                    return;
                }
                else {
                    matrixTransp = 0.00;
                    matrixTr = 0.00;
                }
            }

            if (matrixTransp < 0) { matrixTransp = 0.00 }
            else if (matrixTransp > 1.00) { matrixTransp = 1.00 }

            matrixTr = matrixTransp.toFixed(2)
            if (matrixTr <= 0.15 || matrixTr > 0.85) {
                matrixTransp -= 0.05;
            }
            else {
                matrixTransp -= 0.10;
            }
            var bg = 'rgba(0,0,0,'+matrixTransp.toFixed(2)+')';
            jQuery('.overlay').css("background-color", bg);
            newRequest(matrixTransp.toFixed(2));
        }

        function lessTransp() {

            if (typeof(matrixTr) == 'undefined') {
                matrixTr = t_i;
                if (t_i == '') {t_i = 0.65}
            }

            if (matrixTransp > 1) { matrixTransp = 1.00; return; }

            if (matrixTransp == 1.00) {
                if (matrixTransp.toFixed(2) == Number(matrixTr).toFixed(2)) {
                    matrixTransp = 1.00;
                    matrixTr = 1.00;
                    return;
                }
                else {
                    matrixTransp = 1.00;
                    matrixTr = 1.00;
                }
            }

            if (matrixTransp < 0) { matrixTransp = 0.00 }
            else if (matrixTransp > 1.00) { matrixTransp = 1.00 }

            matrixTr = matrixTransp.toFixed(2);
            if (matrixTr < 0.15 || matrixTr >= 0.85) {
                matrixTransp += 0.05;
            }
            else {
                matrixTransp += 0.10;
            }
            var bg = 'rgba(0,0,0,'+matrixTransp.toFixed(2)+')';
            jQuery('.overlay').css("background-color", bg); 
            newRequest(matrixTransp.toFixed(2));
        }

        var canvas = document.getElementById('canv');
        var ctx = canvas.getContext('2d');

        var w = canvas.width = document.body.offsetWidth;
        var h = canvas.height = document.body.offsetHeight;
        var cols = Math.floor(w / 20) + 1;
        var ypos = Array(cols).fill(0);

        ctx.fillStyle = '#000';
        ctx.fillRect(0, 0, w, h);

        // ctx.fillStyle = '#0f0';
        // ctx.font = '15pt monospace';

        bottomReached = false;

        function matrix() {
            if (bottomReached && t_s) { clearInterval(matrixAnim); }
            ctx.fillStyle = '#0001';
            ctx.fillRect(0, 0, w, h);
            
            ctx.fillStyle = '#0f0';
            ctx.font = '13pt monospace';

            ypos.forEach((y, ind) => {
                const text = String.fromCharCode(Math.random() * 128);
                const x = ind * 20;
                ctx.fillText(text, x, y);
                if (y > 100 + Math.random() * 10000) ypos[ind] = 0;else
                ypos[ind] = y + 20;
                if (y > window.innerHeight && t_s != 0) {
                    bottomReached = true;
                }
            });
        }
        // setInterval(matrix, 50);
        var matrixAnim = setInterval(matrix, 50);

        var isAnim = true;
        if (t_s) { isAnim = false; }

        if (screen.width > 800) {
            window.addEventListener("resize", function() {
                canvas = document.getElementById('canv');
                ctx = canvas.getContext('2d');

                w = canvas.width = document.body.offsetWidth;
                h = canvas.height = document.body.offsetHeight;
                cols = Math.floor(w / 20) + 1;
                ypos = Array(cols).fill(0);

                ctx.fillStyle = '#000';
                ctx.fillRect(0, 0, w, h);

                canvas.width = window.innerWidth;
                canvas.height = window.innerHeight;
            });
        }

        var requests = 0;
        
        function newRequest(matrixTr) {
            requests++;
            var i = requests;

            setTimeout(function() {
                if (i == requests) {
                    requests = 0;

                    var data = 'action=theme_intensity&t_i='+matrixTr;
                    
                    jQuery.ajax({
                        type:"POST",
                        url: ajax_url,
                        data: data+security_url
                    });

                    console.log('Intensity saved!')
                }
            }, 350);
        }

    </script>

<?php } ?>

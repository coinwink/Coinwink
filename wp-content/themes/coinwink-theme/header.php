<!doctype html>
<html lang="en">


<?php

    global $post;
    global $wpdb;

?>


<head>

	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="//www.google-analytics.com" rel="dns-prefetch">

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="https://coinwink.com/img/favicon/apple-touch-icon.png?v=2bBgz68qLw">
    <link rel="icon" type="image/png" sizes="32x32" href="https://coinwink.com/img/favicon/favicon-32x32.png?v=2bBgz68qLw">
    <link rel="icon" type="image/png" sizes="16x16" href="https://coinwink.com/img/favicon/favicon-16x16.png?v=2bBgz68qLw">
    <link rel="manifest" href="https://coinwink.com/img/favicon/site.webmanifest?v=2bBgz68qLw">
    <link rel="mask-icon" href="https://coinwink.com/img/favicon/safari-pinned-tab.svg?v=2bBgz68qLw" color="#4f585b">
    <link rel="shortcut icon" href="https://coinwink.com/img/favicon/favicon.ico?v=2bBgz68qLw">
    <meta name="apple-mobile-web-app-title" content="Coinwink">
    <meta name="application-name" content="Coinwink">
    <meta name="msapplication-TileColor" content="#4f585b">
    <meta name="msapplication-config" content="https://coinwink.com/img/favicon/browserconfig.xml?v=2bBgz68qLw">
    <meta name="theme-color" content="#4f585b">

    <meta name="twitter:card" content="summary" />
    <meta name="twitter:site" content="@coinwink" />

    <!-- CSS -->
    <link href="<?php echo get_stylesheet_directory_uri(); ?>/style.css?v=426" rel="stylesheet" />


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
        );

        $urls_coins = array (
            0 => 'DGB',
            1 => 'POT',
            2 => 'EMC2',
            3 => 'MIOTA',
            4 => 'ETH',
            5 => 'XRP',
            6 => 'LTC',
            7 => 'DASH',
            8 => 'XMR',
            9 => 'ETC',
            10 => 'XEM',
            11 => 'REP',
            12 => 'MAID',
            13 => 'ZEC',
            14 => 'VTC',
            15 => 'GNT',
            16 => 'DCR',
            17 => 'USDT',
            18 => 'PIVX',
            19 => 'STRAT',
            20 => 'DOGE',
            21 => 'BSV',
            22 => 'WAVES',
            23 => 'FCT',
            24 => 'STEEM',
            25 => 'DGD',
            26 => 'GAME',
            27 => 'SNGLS',
            28 => 'LSK',
            29 => 'BCN',
            30 => 'XLM',
            31 => 'ARDR',
            32 => 'BTS',
            33 => 'PPC',
            34 => 'SC',
            35 => 'MLN',
            36 => 'EMC',
            37 => 'KMD',
            38 => 'NXT',
            39 => 'XZC',
            40 => 'BEAM',
            41 => 'SYS',
            42 => 'NXS',
            43 => 'NMC',
            44 => 'BCH',
            45 => 'BAT',
            46 => 'NEO',
            47 => 'QTUM',
            48 => 'OMG',
            49 => 'EOS',
            50 => 'PAY',
            51 => 'VERI',
            52 => 'SNT',
            53 => 'PPT',
            54 => 'CVC',
            55 => 'BNT',
            56 => 'FUN',
            57 => 'ZRX',
            58 => 'EGT',
            59 => 'ADA',
            60 => 'TRX',
            61 => 'ICX',
            62 => 'BNB',
            63 => 'XVG',
            64 => 'REQ',
            65 => 'KNC',
            66 => 'QASH',
            67 => 'ARK',
            68 => 'SMART',
            69 => 'DRGN',
            70 => 'VET',
            71 => 'GRIN',
            72 => 'KCS',
            73 => 'ETN',
            74 => 'GAS',
            75 => 'ETP',
            76 => 'BCD',
            77 => 'ONT',
            78 => 'NANO',
            79 => 'ZIL',
            80 => 'MKR',
            81 => 'AE',
            82 => 'NPXS',
            83 => 'HOT',
            84 => 'WTC',
            85 => 'BTM',
            86 => 'XTZ',
            87 => 'WIN',
            88 => 'LEO',
            89 => 'HT',
            90 => 'LINK',
            91 => 'IOST',
            92 => 'CRO',
            93 => 'VSYS',
            94 => 'HEDG',
            95 => 'ZB',
            96 => 'INB',
            97 => 'ALGO',
            98 => 'NRG',
            99 => 'PAX',
        );


        $post_slug = $post->post_name; // e.g. 'portfolio', 'eth'...


        if (in_array($post_slug, $urls_pages)) {
            $type = "page";
        }
        else if (in_array(strtoupper($post_slug), $urls_coins)) {
            $type = "coin";
        }


        // If current page is for coin
        if ($type == 'coin') {

            // Get coin price data from the database
            $resultdb2 = $wpdb->get_results( "SELECT json FROM cw_data_cmc" , ARRAY_A);
            $newarrayjson = $resultdb2[0]['json'];
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

            $cw_title = $meta_name .' ('. $meta_symbol .') Price Alerts, Watchlist and Portfolio Tracking App';
            // $cw_title = $meta_name .' ('. $meta_symbol .') Price Alerts App, Alarms, Notifications';
            $cw_description = 'Email & SMS crypto alerts app for '. $meta_name .' ('. $meta_symbol .') and other 2500+ crypto currencies. '. $meta_symbol .' price now: '. number_format($meta_price_btc, 6, '.', '') .' BTC | '. number_format($meta_price_usd, 4, '.', '') .' USD.';

            ?>

                <meta property="og:title" content="Coinwink" />
                <meta property="og:description" content="<?php echo($meta_name .' ('. $meta_symbol .') Price Alerts, Watchlist and Portfolio Tracking App'); ?>" />
                
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
                            <meta property="og:image:height" content="1200">
                            <meta property="og:image" content="https://coinwink.com/img/thumb-doge.png"/>
                            <meta name="twitter:image" content="https://coinwink.com/img/thumb-doge.png"/>
                        <?php
                    }
                    else {
                        ?>
                            <meta property="og:image" content="<?php echo get_stylesheet_directory_uri(); ?>/img/fb_share.png"/>
                            <meta name="twitter:image" content="<?php echo get_stylesheet_directory_uri(); ?>/img/fb_share.png"/>
                        <?php
                    }
                ?>

                <meta name="twitter:title" content="Coinwink" />
                <meta name="twitter:description" content="<?php echo($meta_name .' ('. $meta_symbol .') Price Alerts, Watchlist and Portfolio Tracking App'); ?>" />
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
                
                $cw_title = "Coinwink - Terms and Conditions";

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
                
                $cw_title = "Coinwink - Press";

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
                $cw_title = "Crypto Portfolio - Track & Manage Your Cryptocurrency Assets";
                $cw_description = 'Cryptocurrency portfolio tracking app for Bitcoin, Ethereum, Ripple XRP, Litecoin, EOS and other 2500+ crypto coins and tokens. Return on investment (ROI) calculator, notes, multiple-coin alerts.';
            
                ?>

                <meta property="og:title" content="Coinwink - Crypto Portfolio" />
                <meta property="og:description" content="Track & Manage Your Cryptocurrency Assets. Cryptocurrency portfolio management app for Bitcoin, Ethereum, Ripple (XRP), Litecoin, EOS and other 2000+ crypto assets - coins and tokens. Return on investment (ROI) calculator, see profit/loss, make notes, create multiple-coin alerts." />

                <meta property="og:image" content="https://coinwink.com/img/thumb-portfolio-tracker.png"/>

                <meta name="twitter:title" content="Coinwink - Crypto Portfolio" />
                <meta name="twitter:description" content="Track & Manage Your Cryptocurrency Assets. Cryptocurrency portfolio management app for Bitcoin, Ethereum, Ripple (XRP), Litecoin, EOS and other 2000+ crypto assets - coins and tokens. Return on investment (ROI) calculator, see profit/loss, make notes, create multiple-coin alerts." />

                <meta name="twitter:image" content="https://coinwink.com/img/thumb-portfolio-tracker.png" />

                <meta name="twitter:image:alt" content="App screenshot" />

                <meta property="og:image:width" content="1664">
                <meta property="og:image:height" content="936">

                <?php
            }
            else if ($post_slug == "watchlist") {
                $cw_title = "Crypto Watchlist - Keep an Eye on Promising Cryptocurrencies";
                $cw_description = 'Cryptocurrency watchlist app for Bitcoin, Ethereum, Ripple XRP, Litecoin, EOS and other 2500+ crypto coins and tokens.';
            
                ?>

                <meta property="og:title" content="Coinwink - Crypto Watchlist" />
                <meta property="og:description" content="Cryptocurrency watchlist app for Bitcoin, Ethereum, Ripple XRP, Litecoin, EOS and other 2500+ crypto coins and tokens." />
                <meta property="og:image" content="https://coinwink.com/img/thumb-crypto-watchlist-2.png"/>

                <meta name="twitter:title" content="Coinwink - Crypto Watchlist" />
                <meta name="twitter:description" content="Cryptocurrency watchlist app for Bitcoin, Ethereum, Ripple XRP, Litecoin, EOS and other 2500+ crypto coins and tokens." />

                <meta name="twitter:image" content="https://coinwink.com/img/thumb-crypto-watchlist-2.png" />

                <meta name="twitter:image:alt" content="App screenshot" />

                <meta property="og:image:width" content="1000">
                <meta property="og:image:height" content="1000">

                <?php
            }
            else if ($post_slug == "email") {
                
                $cw_title = "Coinwink - Free Email Crypto Alerts for 2500+ Cryptocurrencies";

                ?>

                <meta property="og:title" content="Coinwink" />
                <meta property="og:description" content="Free Email Crypto Alerts for 2500+ Cryptocurrencies" />
                <meta property="og:image" content="https://coinwink.com/img/thumb-email-crypto-alerts.png"/>

                <meta name="twitter:title" content="Coinwink" />
                <meta name="twitter:description" content="Free Email Crypto Alerts for 2500+ Cryptocurrencies" />
                <meta name="twitter:image" content="https://coinwink.com/img/thumb-email-crypto-alerts.png"/>

                <meta name="twitter:image:alt" content="App screenshot" />

                <meta property="og:image:width" content="1664">
                <meta property="og:image:height" content="936">

                <?php

            }
            else if ($post_slug == "email-per") {
                
                $cw_title = "Coinwink - Email Percentage Crypto Alerts for 2500+ Cryptocurrencies";

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
                
                $cw_title = "Coinwink - SMS Crypto Alerts";

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

                $cw_title = "Coinwink - SMS Percentage Crypto Alerts for 2500+ Cryptocurrencies";

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
            else if ($post_slug == "home") {
                $cw_title = "Coinwink - Bitcoin BTC Price Alert, Cryptocurrency Alerts App";
                $cw_description = "Fast, free, open source, privacy-focused email & SMS crypto alerts app. Create cryptocurrency alerts, alarms, notifications for Bitcoin (BTC) and other 2500+ crypto coins and tokens in BTC, ETH, EUR, GBP, AUD, CAD, BRL, MXN, JPY and SGD currencies.";
                
                ?>

                <meta property="og:title" content="Coinwink - Bitcoin BTC Price Alert, Cryptocurrency Alerts App" />
                <meta property="og:description" content="Email and SMS crypto price alerts for 2500+ cryptocurrencies. Create alerts, alarms, notifications in BTC, ETH, EUR, GBP, AUD and CAD, BRL, MXN, JPY and SGD currencies for Bitcoin (BTC) and other 2500+ crypto coins and tokens." />

                <meta property="og:image" content="https://coinwink.com/img/thumb-main.png"/>

                <meta name="twitter:title" content="Coinwink - Bitcoin BTC Price Alert, Cryptocurrency Alerts App" />
                <meta name="twitter:description" content="Email and SMS crypto price alerts for 2500+ cryptocurrencies. Create alerts, alarms, notifications in BTC, ETH, EUR, GBP, AUD and CAD, BRL, MXN, JPY and SGD currencies for Bitcoin (BTC) and other 2500+ crypto coins and tokens." />

                <meta name="twitter:image" content="https://coinwink.com/img/thumb-main.png"/>

                <meta name="twitter:image:alt" content="logo" />

                <meta property="og:image:width" content="1200">
                <meta property="og:image:height" content="1200">

                <?php
            
            }
        }

        else {
            // 404
        }

    ?>


    <title><?php echo($cw_title); ?></title>

    <?php if($cw_description) { ?>
        <meta name="description" content="<?php echo($cw_description); ?>">
    <?php } ?>
    

    <!-- GLOBAL JS -->
    <script>
        // Set to dev if your WP home directory is a subfolder (required for select2_optimization.js)
        coinwinkEnv = 'dev';
        // coinwinkEnv = 'live';

        // Navigo js router
        if (coinwinkEnv == 'dev') {
            homePath = "http://localhost/coinwink/";
        }
        else {
            homePath = "https://coinwink.com/"
        }

        empty = ' ';
    </script>


    <?php wp_head(); ?>


</head>


<body <?php body_class(); ?>>
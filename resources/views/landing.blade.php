<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8"> 
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        
		<title>Coinwink - Crypto Alerts for Bitcoin, Ethereum, and More</title>

		<meta property="og:title" content="Coinwink">
		<meta property="og:description" content="Crypto Alerts App">
		<meta property="og:image" content="https://coinwink.com/img/thumb-main.png">

		<meta name="twitter:title" content="Coinwink">
		<meta name="twitter:description" content="Crypto Alerts App">
		<meta name="twitter:image" content="https://coinwink.com/img/thumb-main.png">

		<meta name="twitter:image:alt" content="logo">

		<meta property="og:image:width" content="1200">
		<meta property="og:image:height" content="1200">

		<meta name="description" content="Email, Telegram and SMS crypto alerts app for Bitcoin (BTC), Ethereum (ETH), and other 3600 coins and tokens. Cryptocurrency price alerts, alarms, notifications, reminders in USD, EUR, GBP, AUD, CAD, BRL, MXN, JPY, and SGD.">

		<meta property="og:type" content="website">
		<meta property="og:site_name" content="Coinwink">
		<meta property="og:image" content="{{env('APP_URL')}}/img/thumb-main.png">
		<meta property="og:url" content="{{ $_SERVER['REQUEST_URI'] }}">

		<meta name="twitter:card" content="summary_large_image">
		<meta name="twitter:site" content="@coinwink">
		<meta name="twitter:creator" content="@coinwink">
		<meta name="twitter:image:src" content="{{env('APP_URL')}}/img/thumb-main.png">
		<meta name="twitter:domain" content="{{env('APP_URL')}}">

		<!-- Favicon -->
		<link rel="apple-touch-icon" sizes="180x180" href="https://coinwink.com/img/favicon/apple-touch-icon.png?v=2bBgz68qL">
		<link rel="icon" type="image/png" sizes="32x32" href="https://coinwink.com/img/favicon/favicon-32x32.png?v=2bBgz68qL">
		<link rel="icon" type="image/png" sizes="16x16" href="https://coinwink.com/img/favicon/favicon-16x16.png?v=2bBgz68qL">
		<link rel="shortcut icon" href="https://coinwink.com/img/favicon/favicon.ico?v=2bBgz68qL">
		<meta name="apple-mobile-web-app-title" content="Coinwink">
		<meta name="application-name" content="Coinwink">

		<!-- <link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet"> -->

		<!-- https://stackoverflow.com/a/60477207/10559033 -->
		<link
			rel="preload"
			href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap"
			as="style"
			onload="this.onload=null;this.rel='stylesheet'"
		/>
		<noscript>
			<link
				href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap"
				rel="stylesheet"
				type="text/css"
			/>
		</noscript>

        <!-- Favicon -->
        <link rel="apple-touch-icon" sizes="180x180" href="{{env('APP_URL')}}/img/favicon/apple-touch-icon.png?v=2bBgz68qL">
        <link rel="icon" type="image/png" sizes="32x32" href="{{env('APP_URL')}}/img/favicon/favicon-32x32.png?v=2bBgz68qL">
        <link rel="icon" type="image/png" sizes="16x16" href="{{env('APP_URL')}}/img/favicon/favicon-16x16.png?v=2bBgz68qL">
        <link rel="shortcut icon" href="{{env('APP_URL')}}/img/favicon/favicon.ico?v=2bBgz68qL">
        <meta name="apple-mobile-web-app-title" content="Coinwink">
        <meta name="application-name" content="Coinwink">

		<!-- METAVERSE WEBAPP THEME -->
		<link rel="manifest" href="{{env('APP_URL')}}/img/favicon/site.webmanifest-metaverse?v=2bBgz68qL">
		<meta name="msapplication-TileColor" content="#060512">
		<meta name="theme-color" content="#060512">
		<link rel="mask-icon" href="{{env('APP_URL')}}/img/favicon/safari-pinned-tab.svg?v=2bBgz68qL" color="#060512">
		<meta name="msapplication-config" content="{{env('APP_URL')}}/img/favicon/browserconfig-metaverse.xml?v=2bBgz68qL">

		<script>
            var cw_cmc = null;

			// var cw_cmc = ?php echo($cmc)
			
            if (!cw_cmc) {
                var http = new XMLHttpRequest();
                http.responseType = 'json';
                var token = document.querySelector('meta[name="csrf-token"]').content;
                var url = '/api/app_data';
                http.open('get', url, true);

                http.onreadystatechange = function() {
                    if(http.readyState == 4 && http.status == 200) {
                        cw_cmc = http.response.cmc;
                        console.log("App data loaded!")
                    }
                }
                http.send();
            }

            var cw_rates = {}
            cw_rates['eur'] = "<?php echo($rates[0]->EUR); ?>";
            cw_rates['gbp'] = "<?php echo($rates[0]->GBP); ?>";
            cw_rates['cad'] = "<?php echo($rates[0]->CAD); ?>";
            cw_rates['aud'] = "<?php echo($rates[0]->AUD); ?>";
            cw_rates['brl'] = "<?php echo($rates[0]->BRL); ?>";
            cw_rates['mxn'] = "<?php echo($rates[0]->MXN); ?>";
            cw_rates['jpy'] = "<?php echo($rates[0]->JPY); ?>";
            cw_rates['sgd'] = "<?php echo($rates[0]->SGD); ?>";

			portfolio = '[{"coin_id":"1","amount":"0.5555","invested":"4000","invested_c":"USD","note":"","slug":"bitcoin"},{"coin_id":"1027","amount":"10","invested":"0.5","invested_c":"BTC","note":"","slug":"ethereum"},{"coin_id":"1839","amount":"10","invested":"0","invested_c":"USD","note":"","slug":"bnb"},{"coin_id":"2","amount":"20","invested":"0","invested_c":"USD","note":"","slug":"litecoin"},{"coin_id":"1697","amount":"2500","invested":"0","invested_c":"USD","note":"","slug":"basic-attention-token"},{"coin_id":"2916","amount":"250000","invested":"0","invested_c":"USD","note":"","slug":"nimiq"},{"coin_id":"52","amount":"5000","invested":"0","invested_c":"USD","note":"","slug":"xrp"},{"coin_id":"74","amount":"25000","invested":"0","invested_c":"USD","note":"","slug":"dogecoin"},{"coin_id":"1966","amount":"3000","invested":"0","invested_c":"USD","note":"","slug":"decentraland"},{"coin_id":"2280","amount":"50","invested":"0","invested_c":"USD","note":"","slug":"filecoin"},{"coin_id":"2243","amount":"5000","invested":"0","invested_c":"USD","note":"","slug":"dragonchain"},{"coin_id":"5994","amount":"150000000","invested":"0","invested_c":"USD","note":"","slug":"shiba-inu"},{"coin_id":"5426","amount":"50","invested":"0","invested_c":"USD","note":"","slug":"solana"}]';
			portfolio = JSON.parse(portfolio);

			watchlist = '[{"coin_id":"1","note":"","slug":"bitcoin"},{"coin_id":"1027","note":"","slug":"ethereum"},{"coin_id":"21414","note":"","slug":"dogechain"},{"coin_id":"21794","note":"","slug":"aptos"},{"coin_id":"1966","note":"","slug":"decentraland"},{"coin_id":"2943","note":"","slug":"rocket-pool"},{"coin_id":"14280","note":"","slug":"euler-finance"},{"coin_id":"6210","note":"","slug":"the-sandbox"},{"coin_id":"7083","note":"","slug":"uniswap"},{"coin_id":"74","note":"","slug":"dogecoin"},{"coin_id":"8200","note":"","slug":"fox-token"},{"coin_id":"2694","note":"","slug":"nexo"},{"coin_id":"4030","note":"","slug":"algorand"},{"coin_id":"2058","note":"","slug":"airswap"},{"coin_id":"3890","note":"","slug":"polygon"},{"coin_id":"1518","note":"","slug":"maker"},{"coin_id":"4847","note":"","slug":"stacks"},{"coin_id":"5805","note":"","slug":"avalanche"},{"coin_id":"4705","note":"","slug":"pax-gold"},{"coin_id":"1455","note":"","slug":"golem-network-tokens"}]';
			watchlist = JSON.parse(watchlist);

			cur_p = 'USD';
			conf_w = 'price';
			cur_w = 'USD';
		</script>

        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-57930548-9"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', 'UA-57930548-9');
        </script>

		<link rel="stylesheet" href="/assets/landing/css/style-select2.css?v=047">
		<link rel="stylesheet" href="/assets/landing/css/style-select2-metaverse.css?v=047">

		<style>
			/* *********** */
			/* GENERAL CSS */
			/* *********** */

			body, html {
				height: 100%;
				width: 100%;
				margin: 0;
				padding: 0;
				/* background: #060512; */
				background-color: #080510;
				color: #b7c0ec;
				font-family: 'Arial', 'Helvetica', sans-serif;
			}

			* {
				box-sizing: border-box;
			}

			h1{
				font-size: 28px;
				margin-bottom: 0px;
			}

			/* Heading font */
			/* h1 {
				font-family: 'IBM Plex Sans', sans-serif;
				font-weight: 800;
			} */

			h2 {
				font-size: 21px;
			}

			a, a:visited {
				transition: 0.3s;
				color: #00b2c4;
				/* text-decoration: none; */
			}

			a:active, a:hover {
				color: #0ae7fd;
			}

			.content {
				max-width: 900px;
				margin: 0 auto;
			}

			.grid-1-2 {
				display: grid;
				grid-template-columns: 1fr 2fr;
			}

			.grid-1-1-1 {
				display: grid;
				grid-template-columns: 1fr 1fr 1fr;
			}


			.grid-1-1 {
				display: grid;
				grid-template-columns: 1fr 1fr;
			}

			.spacer-top {
				height: 80px;
			}

			.spacer-bottom {
				height: 60px;
			}

			@media only screen and (max-width: 400px) {
				.spacer-top {
					height: 40px;
				}
				.spacer-bottom {
					height: 40px;
				}
			}

			.content-box {
				text-align: center;
				margin-top: 180px;
			}

			.image {
				max-width: 100%;
			}

			.button-main {
				width: 160px;
				height: 40px;
				/* background: #4a00b7; */
				/* background: #008792; */
				/* background: #007995; */
				background: #0081a1;
				cursor: pointer;
				color: white;
				font-size: 16px;
				font-weight: bold;
				border: 0;
				transition: 0.2s;
				border-radius: 3px;  
				box-shadow: rgba(255, 255, 255, .4) 0 1px 0 0 inset;
			}

			.button-main:hover, .button-main:active {
				/* background: #0087a6; */
				background: #0091b5;
			}

			.button-main:active {
				box-shadow: none;
			}

			.button-main-top {
				width: 120px;
				height: 35px;
				font-size:14px;
			}

			#top-menu {
				width: 100%;
				display:none;
				/* border-bottom: 1px solid #4a00b7;
				background: #130227; */
				border-bottom: 1px solid #b700005e;
				background: #000000;
				padding-top:15px;
				padding-bottom:15px;
				position:fixed;
				z-index: 999;
			}

			.top-menu-inner {
				grid-template-columns: 1fr 1fr;
				display: grid;
				width: 900px;
				margin: 0 auto;
			}

			.soc-icons {
				width: 78px;
				margin: 0 auto;
				display: grid;
				grid-template-columns: 1fr 1fr;
				grid-column-gap: 12px;
			}

			.soc-icons > div {
				width:33px;
			}

			.soc-icon {
				fill:#8A8F98;
				fill-rule:evenodd;
				transition: fill 0.2s ease;
			}

			.soc-icon:hover, .soc-icon:active {
				fill:#d6d6d6;
			}

			.cursor-pointer {
				cursor:pointer;
			}

			.line-through {
				text-decoration:line-through;
			}

			.noselect {
				-webkit-touch-callout:none;
				-webkit-user-select:none;
				-moz-user-select:none;
				-ms-user-select:none;
				user-select:none;
			}

			.themes {
				background:url('/assets/landing/img/themes.png?v=047');
				background-size: auto 333px;
				height:325px;
				background-repeat: no-repeat;
				background-position: center top 0px;
				
			}

			.mobile-friendly {
				width:70%;
			}

			.alert-box {
				border-radius:3px;
				line-height:14px;
				/* margin:25px 0; */
				margin-bottom: 25px;
				padding-bottom: 15px;
				padding-top:15px;
				position:relative;
				font-family: 'Montserrat', sans-serif;
				font-size: 12.5px;
				/* border: 1px solid #0a5555;
				transition: 0.3s; */
				background:#041023;
				text-align:center;
				color:#dddddd;
			}

			/* .alert-box:hover, .alert-box:active {
				border:1px solid #26cccc;
			} */

			.alert-box-mobile {
				max-width: 280px;
				margin: 0 auto;
				margin-bottom: 20px;
			}

			.font-montserrat {
				font-family: 'Montserrat', sans-serif;
			}

			.mobile {
				display: none;
				margin-left: 10px;
				margin-right: 10px;
			}

			.image-mobile {
				max-width: 280px;
			}

			.footer-menu {
				font-size: 18px;
			}

			.footer-menu-2 {
				font-size:14px;
			}

			.footer-menu-2 a, .footer-menu-2 a:visited {
				/* color: #008391; */
				color: #b7c0ec;
			}

			/* .footer-menu-2 a:active, .footer-menu-2 a:hover {
				color: #2eb3c2;
			} */

			.footer-menu-div {
				display:none;
				height:8px;			
			}

			@media screen and (max-width: 420px) {
				.footer-menu {
					font-size: 16px;
				}
				.footer-menu-2 {
					font-size: 13px;
				}
			}

			@media screen and (max-width: 360px) {
				.footer-menu-div {
					display:block;					
				}
				.footer-menu {
					font-size: 14px;
				}
			}

			@media screen and (max-width: 940px) {
				.content-box {
					padding: 15px;
				}
				.desktop {
					display: none;
				}
				.mobile {
					display: block;
				}
				h1 {
					font-size: 24px;
				}
				.grid-email-alerts-mobile {
					display: block;
				}
				.grid-email-alerts-mobile .alert-box {
					max-width: 280px;
					margin: 0 auto;
					margin-bottom: 20px;
				}
				.content-box {
					margin-top: 100px;
				}
				.alerts-last3-mob {
					display: none;
				}
				.themes {
					background:url('/assets/landing/img/themes-mobile.png?v=047');
					background-size:cover;
					height:1000px;
					background-repeat: no-repeat;
					background-position: center top;
					background-size: auto 1000px;
				}
				.mobile-friendly {
					width: 100%;
				}
				.top-menu-inner {
					/* grid-template-columns: 37px 100%; */
					grid-template-columns: 110px 1fr;
					width: 100%;
					padding-left: 15px;
					padding-right: 15px;
				}
				.mobile-pricing {
					padding-left: 47px!important;
				}
			}
			
			.alert-icon-svg {
				fill: none;
				stroke: #405372;
				stroke-width: 8.9999;
				stroke-linejoin: round;
				stroke-miterlimit: 22.9256;
			}

			a.log-in-link, a.log-in-link:visited {
				color:#00a4c4!important;
			} 

			a.log-in-link:active, a.log-in-link:hover {
				color:#00cff8!important;
			} 
		</style>

    </head>
    <body>
		<div id="top-menu">
			<div class="top-menu-inner">
				<div>
					<img onclick="window.scrollTo(0, 0);" src="/assets/landing/img/coinwink-logo-horizontal.svg?v_logo=004" style="width:110px;cursor:pointer;" alt="Coinwink logo">
				</div>
				<div style="text-align:right;padding-top:5px;">
					<a href="/register"><button class="button-main button-main-top">Sign Up</button></a>&nbsp;&nbsp;&nbsp;<a href="/login" class="log-in-link" style="font-size:14px;">Log in</a>
				</div>
			</div>
		</div>

		<div class="content">
			<div class="spacer-top"></div>

			<div class="grid-1-2 desktop">
				<div>
					<div style="height:40px;"></div>
					<img src="/assets/landing/img/coinwink-logo-horizontal.svg?v_logo=004" alt="Coinwink logo" style="width:180px;">
					<br><br>
					<!-- Never miss important price changes of your favorite cryptocurrencies with the help of Coinwink crypto alerts -->
					<!-- Keep track of your favorite cryptocurrencies with the help of Coinwink crypto alerts -->
					<!-- Keep track of your favorite coins and tokens with the help of Coinwink crypto alerts -->
					Track important price changes of your favorite cryptocurrencies with the help of Coinwink crypto alerts
					<!-- <span style="font-size:17px;">Don't waste your time watching cryptocurrency prices, automate this task with Coinwink crypto alerts</span> -->
					<div style="height:25px;"></div>
					<div style="width:160px;text-align:center;">
						<a href="/register"><button class="button-main">Sign Up</button></a>
						<div style="height:20px;"></div>
						<a href="/login" class="log-in-link">Log in</a>
					</div>
				</div>
				<div style="text-align:right;width:600px;height:423px;">
					<img src="/assets/landing/img/main-visual.png?v=047" alt="Main visual" class="image" style="width:85%;">
				</div>
			</div>

			<div class="mobile">
				<div style="text-align:center;">
					<!-- <div style="height:40px;"></div> -->
					<img src="/assets/landing/img/coinwink-logo-vertical.svg?v_logo=004" alt="Coinwink logo" style="width:90px;">
					<br><br>
					<!-- Never miss important price changes of your favorite cryptocurrencies with the help of Coinwink crypto alerts -->
					<!-- Keep track of your favorite cryptocurrencies with the help of Coinwink crypto alerts -->
					Track important price changes of your favorite cryptocurrencies with the help of Coinwink crypto alerts
					<!-- Don't waste your time watching cryptocurrency prices, automate this task with Coinwink crypto alerts -->
					<div style="height:35px;"></div>
					<div>
						<a href="/register"><button class="button-main">Sign Up</button></a>
						<div style="height:25px;"></div>
						<a href="/login" class="log-in-link">Log in</a>
					</div>
					<div style="height:60px;"></div>
				</div>
				<div style="text-align: center;">
					<img src="/assets/landing/img/main-visual.png?v=047" alt="Main visual" class="image">
				</div>
			</div>

			<div class="content-box">
				<h1>Email and Telegram Crypto Alerts</h1>
				<p>Free email and Telegram crypto alerts and multiple-coin portfolio alerts with a free Coinwink account</p>
				<div style="height:35px;"></div>
				<!-- <img src="/assets/landing/img/email-crypto-alerts.png" class="image"> -->
				
				<div style="position:relative;">
					<div class="grid-1-1-1 grid-email-alerts-mobile" style="grid-column-gap:20px;">
						<div>
							<div>
								<div class="alert-box"><div style='position:absolute;width:13px;height:15px;left:6px;top:5px;' title="Email"><svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 149.6 107.2" style="enable-background:new 0 0 149.6 107.2;" xml:space="preserve"><path class="alert-icon-svg" d="M10.8,4.5h128c3.5,0,6.3,2.9,6.3,6.3V92c0,5.9-4.8,10.7-10.7,10.7H15.2c-5.9,0-10.7-4.8-10.7-10.7V10.8C4.5,7.4,7.4,4.5,10.8,4.5L10.8,4.5z"/><path class="alert-icon-svg" d="M141,8.4l-59,59c-3.9,3.9-10.4,3.9-14.3,0l-59-59"/></svg></div><a target="_blank" class="portfoliocoin" href="/btc"><img width="18" height="18" class="noselect" src="/img/coins/32x32/1.png" alt="Coin logo"></a><br>Bitcoin (BTC)<br>satoshi@nakamoto.com<br><div style="margin-top:8px;line-height:18px;"><span onclick="alertReenable(this)" class="cursor-pointer">Above: <b>80000</b> USD</span><br><span onclick="alertReenable(this)" class="cursor-pointer line-through">Below: <b >30000</b> USD</span><br></div></div>
							</div>
							<div>
								<div class="alert-box"><div style='position:absolute;width:13px;height:15px;left:6px;top:5px;' title="Email"><svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 149.6 107.2" style="enable-background:new 0 0 149.6 107.2;" xml:space="preserve"><path class="alert-icon-svg" d="M10.8,4.5h128c3.5,0,6.3,2.9,6.3,6.3V92c0,5.9-4.8,10.7-10.7,10.7H15.2c-5.9,0-10.7-4.8-10.7-10.7V10.8C4.5,7.4,7.4,4.5,10.8,4.5L10.8,4.5z"/><path class="alert-icon-svg" d="M141,8.4l-59,59c-3.9,3.9-10.4,3.9-14.3,0l-59-59"/></svg></div><a target="_blank" class="portfoliocoin" href="/req"><img width="18" height="18" class="noselect" src="/img/coins/32x32/2071.png" alt="Coin logo"></a><br>Request (REQ)<br>satoshi@nakamoto.com<br><div style="margin-top:8px;line-height:18px;"><span onclick="alertReenable(this)" class="cursor-pointer">Above: <b>0.00001341</b> BTC</span><br></div></div>
							</div>
							<div>
								<div class="alert-box"><div style='position:absolute;width:13px;height:15px;left:6px;top:5px;' title="Email"><svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 149.6 107.2" style="enable-background:new 0 0 149.6 107.2;" xml:space="preserve"><path class="alert-icon-svg" d="M10.8,4.5h128c3.5,0,6.3,2.9,6.3,6.3V92c0,5.9-4.8,10.7-10.7,10.7H15.2c-5.9,0-10.7-4.8-10.7-10.7V10.8C4.5,7.4,7.4,4.5,10.8,4.5L10.8,4.5z"/><path class="alert-icon-svg" d="M141,8.4l-59,59c-3.9,3.9-10.4,3.9-14.3,0l-59-59"/></svg></div><a target="_blank" class="portfoliocoin" href="/dot"><img width="18" height="18" class="noselect" src="/img/coins/32x32/6636.png" alt="Coin logo"></a><br>Polkadot (DOT)<br>satoshi@nakamoto.com<br><div style="margin-top:8px;line-height:18px;"><span onclick="alertReenable(this)" class="cursor-pointer"><b>+15%</b> in 1h. period</span><br></div></div>
							</div>
						</div>
						<div>
							<div>
								<div class="alert-box"><div style='position:absolute;width:13px;height:15px;left:6px;top:5px;' title="Telegram"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 149.7 129.6" style="enable-background:new 0 0 149.7 129.6;" xml:space="preserve"><path class="alert-icon-svg" d="M10.9,49.5c44.8-15,89.5-30,134.3-45c-8.8,37.3-17.5,74.5-26.3,111.8c-1,4.2-1.5,7.2-3.1,8.2c-2.9,2-7.1-1.2-8.9-2.9c-14.4-12.4-28.8-24.8-43.2-37.3c15.7-16.3,31.4-32.5,47.1-48.8C88.3,48,65.7,60.4,43.1,72.8C32,67.9,20.8,62.9,9.6,58C2.6,54.6,2.6,51.9,10.9,49.5L10.9,49.5z"/><path class="alert-icon-svg" d="M43.1,72.8l8.8,46.9l11.7-35.4 M52.1,121.1l28.1-22.5"/></svg></div><a target="_blank" class="portfoliocoin" href="/eth"><img width="18" height="18" class="noselect" src="/img/coins/32x32/1027.png" alt="Coin logo"></a><br>Ethereum (ETH)<br>@satoshi<br><div style="margin-top:8px;line-height:18px;"><span onclick="alertReenable(this)" class="cursor-pointer line-through">Above: <b>1200</b> EUR</span><br><span onclick="alertReenable(this)" class="cursor-pointer">Below: <b>800</b> EUR</span><br></div></div>
							</div>
							<div>
								<div class="alert-box"><div style='position:absolute;width:13px;height:15px;left:6px;top:5px;' title="Telegram"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 149.7 129.6" style="enable-background:new 0 0 149.7 129.6;" xml:space="preserve"><path class="alert-icon-svg" d="M10.9,49.5c44.8-15,89.5-30,134.3-45c-8.8,37.3-17.5,74.5-26.3,111.8c-1,4.2-1.5,7.2-3.1,8.2c-2.9,2-7.1-1.2-8.9-2.9c-14.4-12.4-28.8-24.8-43.2-37.3c15.7-16.3,31.4-32.5,47.1-48.8C88.3,48,65.7,60.4,43.1,72.8C32,67.9,20.8,62.9,9.6,58C2.6,54.6,2.6,51.9,10.9,49.5L10.9,49.5z"/><path class="alert-icon-svg" d="M43.1,72.8l8.8,46.9l11.7-35.4 M52.1,121.1l28.1-22.5"/></svg></div><a target="_blank" class="portfoliocoin" href="/doge"><img width="18" height="18" class="noselect" src="/img/coins/32x32/74.png" alt="Coin logo"></a><br>Dogecoin (DOGE)<br>@satoshi<br><div style="margin-top:8px;line-height:18px;"><span onclick="alertReenable(this)" class="cursor-pointer">Above: <b>100</b> JPY</span><br><span onclick="alertReenable(this)" class="cursor-pointer">Below: <b>0.00003</b> ETH</span><br></div></div>
							</div>
							<div>
								<div class="alert-box"><div style='position:absolute;width:13px;height:15px;left:6px;top:5px;' title="Telegram"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 149.7 129.6" style="enable-background:new 0 0 149.7 129.6;" xml:space="preserve"><path class="alert-icon-svg" d="M10.9,49.5c44.8-15,89.5-30,134.3-45c-8.8,37.3-17.5,74.5-26.3,111.8c-1,4.2-1.5,7.2-3.1,8.2c-2.9,2-7.1-1.2-8.9-2.9c-14.4-12.4-28.8-24.8-43.2-37.3c15.7-16.3,31.4-32.5,47.1-48.8C88.3,48,65.7,60.4,43.1,72.8C32,67.9,20.8,62.9,9.6,58C2.6,54.6,2.6,51.9,10.9,49.5L10.9,49.5z"/><path class="alert-icon-svg" d="M43.1,72.8l8.8,46.9l11.7-35.4 M52.1,121.1l28.1-22.5"/></svg></div><a target="_blank" class="portfoliocoin" href="/bat"><img width="18" height="18" class="noselect" src="/img/coins/32x32/1697.png" alt="Coin logo"></a><br>Basic Attention Token (BAT)<br>@satoshi<br><div style="margin-top:8px;line-height:18px;"><span onclick="alertReenable(this)" class="cursor-pointer"><b>+10%</b> in 24h. period</span><br><span onclick="alertReenable(this)" class="cursor-pointer"><b>-5%</b></span> from <span title="Update price" onclick="alertPerPriceRefresh(1, 10009, &quot;ETH&quot;, &quot;minus&quot;, &quot;email_alerts_per&quot;)" class="cur-span noselect 10009-spin-ETH">0.00001345</span> BTC</div></div>
							</div>
						</div>
						<div class="alerts-last3-mob">
							<div>
								<div class="alert-box"><div style='position:absolute;width:13px;height:15px;left:6px;top:5px;' title="Email"><svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 149.6 107.2" style="enable-background:new 0 0 149.6 107.2;" xml:space="preserve"><path class="alert-icon-svg" d="M10.8,4.5h128c3.5,0,6.3,2.9,6.3,6.3V92c0,5.9-4.8,10.7-10.7,10.7H15.2c-5.9,0-10.7-4.8-10.7-10.7V10.8C4.5,7.4,7.4,4.5,10.8,4.5L10.8,4.5z"/><path class="alert-icon-svg" d="M141,8.4l-59,59c-3.9,3.9-10.4,3.9-14.3,0l-59-59"/></svg></div><a target="_blank" class="portfoliocoin" href="https://coinmarketcap.com/currencies/decentraland/"><img width="18" height="18" class="noselect" src="/img/coins/32x32/1966.png"></a><br>Decentraland (MANA)<br>satoshi@nakamoto.com<br><div style="margin-top:8px;line-height:18px;"><span onclick="alertReenable(this)" class="cursor-pointer line-through" >Above: <b>0.8</b> CAD</span><br><span onclick="alertReenable(this)" class="cursor-pointer">Below: <b>0.5</b> AUD</span><br></div></div>
							</div>
							<div>
								<div class="alert-box"><div style='position:absolute;width:13px;height:15px;left:6px;top:5px;' title="Email"><svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 149.6 107.2" style="enable-background:new 0 0 149.6 107.2;" xml:space="preserve"><path class="alert-icon-svg" d="M10.8,4.5h128c3.5,0,6.3,2.9,6.3,6.3V92c0,5.9-4.8,10.7-10.7,10.7H15.2c-5.9,0-10.7-4.8-10.7-10.7V10.8C4.5,7.4,7.4,4.5,10.8,4.5L10.8,4.5z"/><path class="alert-icon-svg" d="M141,8.4l-59,59c-3.9,3.9-10.4,3.9-14.3,0l-59-59"/></svg></div><a target="_blank" class="portfoliocoin" href="/link"><img width="18" height="18" class="noselect" src="/img/coins/32x32/1975.png" alt="Coin logo"></a><br>Chainlink (LINK)<br>satoshi@nakamoto.com<br><div style="margin-top:8px;line-height:18px;"><span onclick="alertReenable(this)" class="cursor-pointer">Above: <b>17</b> GBP</span><br></div></div>
							</div>
							<div>
								<div class="alert-box"><div style='position:absolute;width:13px;height:15px;left:6px;top:5px;' title="Email"><svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 149.6 107.2" style="enable-background:new 0 0 149.6 107.2;" xml:space="preserve"><path class="alert-icon-svg" d="M10.8,4.5h128c3.5,0,6.3,2.9,6.3,6.3V92c0,5.9-4.8,10.7-10.7,10.7H15.2c-5.9,0-10.7-4.8-10.7-10.7V10.8C4.5,7.4,7.4,4.5,10.8,4.5L10.8,4.5z"/><path class="alert-icon-svg" d="M141,8.4l-59,59c-3.9,3.9-10.4,3.9-14.3,0l-59-59"/></svg></div><a target="_blank" class="portfoliocoin" href="/xrp"><img width="18" height="18" class="noselect" src="/img/coins/32x32/52.png" alt="Coin logo"></a><br>XRP (XRP)<br>satoshi@nakamoto.com<br><div style="margin-top:8px;line-height:18px;"><span onclick="alertReenable(this)" class="cursor-pointer line-through" ><b>+10%</b> in 24h. period</span><br></div></div>
							</div>
						</div>
					</div>
					<div class="widget-overlay mobile" style="background-position: bottom -130px center;"></div>
				</div>
			</div>
		</div>
		 
		<div class="content">

			<div class="content-box">
				<h1>Global SMS Crypto Alerts</h1>
				<!-- <h2>Global Reach</h2> -->
				<p>Receive SMS crypto alerts no matter where you are and even without the internet connection</p>
				<!-- <p>You can set up alerts in 9 fiat currencies: USD, EUR, GBP, AUD, CAD, MXN, BRL, SGD and JPY</p> -->
				<div style="height:35px;"></div>

				<div class="grid-1-2 desktop" style="grid-column-gap: 40px;">
					<div>
						<div>
							<div class="alert-box"><div style='position:absolute;width:13px;height:15px;left:6px;top:5px;' title="SMS"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 151 137.6" style="enable-background:new 0 0 151 137.6;" xml:space="preserve"><path class="alert-icon-svg" d="M30.2,99.8v33.3l23.5-23.5c6.9,1.7,14.2,2.6,21.8,2.6c39.2,0,71-24.1,71-53.9s-31.8-53.9-71-53.9c-39.2,0-71,24.1-71,53.9C4.5,75,14.5,89.9,30.2,99.8L30.2,99.8z"/><path class="alert-icon-svg" d="M46.7,46h57.5 M46.7,71.7h57.5"/></svg></div><a target="_blank" class="portfoliocoin" href="/btc"><img width="18" height="18" class="noselect" src="/img/coins/32x32/1.png" alt="Coin logo"></a><br>Bitcoin (BTC)<br>+15512345678<br><div style="margin-top:8px;line-height:18px;"><span onclick="alertReenable(this)" class="cursor-pointer line-through" >Above: <b>12</b> ETH</span><br><span onclick="alertReenable(this)" class="cursor-pointer">Below: <b>15000</b> USD</span><br></div></div>
						</div>
						<div>
							<div class="alert-box"><div style='position:absolute;width:13px;height:15px;left:6px;top:5px;' title="SMS"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 151 137.6" style="enable-background:new 0 0 151 137.6;" xml:space="preserve"><path class="alert-icon-svg" d="M30.2,99.8v33.3l23.5-23.5c6.9,1.7,14.2,2.6,21.8,2.6c39.2,0,71-24.1,71-53.9s-31.8-53.9-71-53.9c-39.2,0-71,24.1-71,53.9C4.5,75,14.5,89.9,30.2,99.8L30.2,99.8z"/><path class="alert-icon-svg" d="M46.7,46h57.5 M46.7,71.7h57.5"/></svg></div><a target="_blank" class="portfoliocoin" href="/eth"><img width="18" height="18" class="noselect" src="/img/coins/32x32/1027.png" alt="Coin logo"></a><br>Ethereum (ETH)<br>+34912345678<br><div style="margin-top:8px;line-height:18px;"><span onclick="alertReenable(this)" class="cursor-pointer"><b>+10%</b> in 1h. period</span><br><span onclick="alertReenable(this)" class="cursor-pointer"><b>-20%</b></span> from <span title="Update price" onclick="alertPerPriceRefresh(1, 10009, &quot;ETH&quot;, &quot;minus&quot;, &quot;email_alerts_per&quot;)" class="cur-span noselect 10009-spin-ETH">1320.34</span> EUR</div></div>
						</div>
						<div>
							<div class="alert-box" style="margin-bottom:0px;"><div style='position:absolute;width:13px;height:15px;left:6px;top:5px;' title="SMS"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 151 137.6" style="enable-background:new 0 0 151 137.6;" xml:space="preserve"><path class="alert-icon-svg" d="M30.2,99.8v33.3l23.5-23.5c6.9,1.7,14.2,2.6,21.8,2.6c39.2,0,71-24.1,71-53.9s-31.8-53.9-71-53.9c-39.2,0-71,24.1-71,53.9C4.5,75,14.5,89.9,30.2,99.8L30.2,99.8z"/><path class="alert-icon-svg" d="M46.7,46h57.5 M46.7,71.7h57.5"/></svg></div><a class="portfoliocoin" href="/sol" target="_blank"><img width="18" height="18" class="noselect" src="/img/coins/32x32/5426.png" alt="Coin logo"></a><br>Solana (SOL)<br>+6012345678<br><div style="margin-top:8px;line-height:18px;"><span onclick="alertReenable(this)" class="cursor-pointer">Above: <b>0.002</b> BTC</span><br><span onclick="alertReenable(this)" class="cursor-pointer">Below: <b>30</b> SGD</span><br></div></div>
						</div>
					</div>
					<div style="position:relative;">
						<div class="countries-supported">More Than<br>150 Countries<br>Supported</div>
						<img loading="lazy" src="/assets/landing/img/country-codes.png?v=047" class="image" alt="Country codes">
					</div>
				</div>
			</div>
			
			<style>
				.countries-supported {
					position: absolute;
					right: 35px;
					top: 131px;
					width: 200px;
					font-size: 23px;
					font-family: 'Montserrat', sans-serif;
					/* font-weight: 600; */
					/* color: #999; */
					color: #949494;
					letter-spacing: 1px;
					line-height: 31px;
				}
			</style>

		</div>

		<div class="mobile" style="text-align:center;margin-top: -20px;">
			<div>
				<div>
					<div class="alert-box alert-box-mobile"><div style='position:absolute;width:13px;height:15px;left:6px;top:5px;'><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 151 137.6" style="enable-background:new 0 0 151 137.6;" xml:space="preserve"><path class="alert-icon-svg" d="M30.2,99.8v33.3l23.5-23.5c6.9,1.7,14.2,2.6,21.8,2.6c39.2,0,71-24.1,71-53.9s-31.8-53.9-71-53.9c-39.2,0-71,24.1-71,53.9C4.5,75,14.5,89.9,30.2,99.8L30.2,99.8z"/><path class="alert-icon-svg" d="M46.7,46h57.5 M46.7,71.7h57.5"/></svg></div><a target="_blank" class="portfoliocoin" href="/btc" ><img width="18" height="18" class="noselect" src="/img/coins/32x32/1.png" alt="Coin logo"></a><br>Bitcoin (BTC)<br>+15512345678<br><div style="margin-top:8px;line-height:18px;"><span onclick="alertReenable(this)" class="cursor-pointer line-through">Above: <b>12</b> ETH</span><br><span onclick="alertReenable(this)" class="cursor-pointer">Below: <b>15000</b> USD</span><br></div></div>
				</div>
				<div>
					<div class="alert-box alert-box-mobile"><div style='position:absolute;width:13px;height:15px;left:6px;top:5px;'><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 151 137.6" style="enable-background:new 0 0 151 137.6;" xml:space="preserve"><path class="alert-icon-svg" d="M30.2,99.8v33.3l23.5-23.5c6.9,1.7,14.2,2.6,21.8,2.6c39.2,0,71-24.1,71-53.9s-31.8-53.9-71-53.9c-39.2,0-71,24.1-71,53.9C4.5,75,14.5,89.9,30.2,99.8L30.2,99.8z"/><path class="alert-icon-svg" d="M46.7,46h57.5 M46.7,71.7h57.5"/></svg></div><a target="_blank" class="portfoliocoin" href="/eth" ><img width="18" height="18" class="noselect" src="/img/coins/32x32/1027.png" alt="Coin logo"></a><br>Ethereum (ETH)<br>+34912345678<br><div style="margin-top:8px;line-height:18px;"><span onclick="alertReenable(this)" class="cursor-pointer"><b>+10%</b> in 1h. period</span><br><span onclick="alertReenable(this)" class="cursor-pointer"><b>-20%</b></span> from <span title="Update price" onclick="alertPerPriceRefresh(1, 10009, &quot;ETH&quot;, &quot;minus&quot;, &quot;email_alerts_per&quot;)" class="cur-span noselect 10009-spin-ETH">1320.34</span> EUR</div></div>
				</div>
				<div>
					<div class="alert-box alert-box-mobile" style="margin-bottom:0px;"><div style='position:absolute;width:13px;height:15px;left:6px;top:5px;'><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 151 137.6" style="enable-background:new 0 0 151 137.6;" xml:space="preserve"><path class="alert-icon-svg" d="M30.2,99.8v33.3l23.5-23.5c6.9,1.7,14.2,2.6,21.8,2.6c39.2,0,71-24.1,71-53.9s-31.8-53.9-71-53.9c-39.2,0-71,24.1-71,53.9C4.5,75,14.5,89.9,30.2,99.8L30.2,99.8z"/><path class="alert-icon-svg" d="M46.7,46h57.5 M46.7,71.7h57.5"/></svg></div><a  class="portfoliocoin" href="/sol" target="_blank"><img width="18" height="18" class="noselect" src="/img/coins/32x32/5426.png" alt="Coin logo"></a><br>Solana (SOL)<br>+6012345678<br><div style="margin-top:8px;line-height:18px;"><span onclick="alertReenable(this)" class="cursor-pointer">Above: <b>0.002</b> BTC</span><br><span onclick="alertReenable(this)" class="cursor-pointer">Below: <b>30</b> SGD</span><br></div></div>
				</div>
			</div>

			<div style="height:30px;"></div>
			<p>More than 150 countries supported</p>

			<div style="height:10px;"></div>
			<div class="content-full-w" style="background:url('/assets/landing/img/country-codes-mobile.png?v=047');background-position:top center;background-size:500px;background-repeat:no-repeat;height:415px;">
			</div>
		</div>
		
		
		<!-- **** -->
		<!-- 3600 -->
		<!-- **** -->
		<div class="content">
			<div class="content-box">
				<h1>3600 Cryptocurrencies</h1>
				<p>Check below if your coin or token is available on Coinwink</p>
			</div>
		</div>
		<div style="height:20px;"></div>

		<div class="widget-container widget-container-full" style="text-align: center; min-height: 40px;">
			<header>
				<h3 style="cursor: pointer;">CHECK AVAILABILITY</h3>
			</header>
			<div style="height:25px;"></div>

			<select class="selectcoin" id="id"></select>
			
			<div style="height:20px;"></div>
			<div class="dropdown-price-div" id="pricediv">
				Loading...
			</div>

			<div style="height:30px;"></div>

			<p style="color:#777;font-size:14px;">Powered by <a href="https://coinmarketcap.com" target="_blank" style="color:#777;">CoinMarketCap</a><p>

			<div style="height:25px;"></div>
		</div>


		<style>
			.span-click {
				border-bottom: 1px dashed #a6f6f4;
				cursor:pointer;
				color: white;
			}

			.span-click:hover, .span-click:active {
				color: #a6f6f4;
			}

			.widget-container-full {
				max-width:100%!important;
				border-left: 0!important;
				border-right: 0!important;
				border-radius: 0px;
			}

			.container-3600 {
				background:black;border-radius:5px;border:1px solid #0a5555;max-width:900px;height:290px;margin:0 auto;text-align:center;
				
				font-family: 'Montserrat', sans-serif;
			}
			@media screen and (max-width: 900px) {
				.container-3600 {
					border-left: 0;
					border-right: 0;
					border-radius: 0;
				}
			}

			.select-css-3600{-moz-appearance:none;-webkit-appearance:none;appearance:none;background-color:#333;background-image:url("data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%23C7C7C7%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E"),linear-gradient(180deg,#3b3b3b 0,#717171);background-position:right 5px top 50%,0 0;background-repeat:no-repeat,repeat;background-size:.65em auto,100%;border:1px solid #aaa;border-radius:2px;box-shadow:0 1px 0 1px rgba(0,0,0,.04);color:#c7c7c7!important;display:inline;font-family:Montserrat,sans-serif;font-size:12px;height:22px;line-height:1.3;margin:0;padding-left:4px;width:56px;}
			.select-css-3600::-ms-expand{display:none;}
			.select-css-3600:focus{border-color:#aaa;color:#222;outline:none;}
			.select-css-3600 option{font-weight:400;}
			.select-css-3600:disabled{background-image:url("data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22graytext%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E"),linear-gradient(180deg,#3b3b3b 0,#717171);color:graytext;}
			@media only screen and (min-width:801px){
			.select-css-3600:hover{border:1px solid #a5a5a5!important;}
			.select-css-3600:disabled:hover{border-color:#aaa;}
			}
			.select-css-3600:active{border:1px solid #a5a5a5!important;}
			.select-css-3600:disabled:active{border-color:#aaa;}
			/*! CSS Used from: https://coinwink.com/lib/css/style-matrix.css?v=047 */
			.select-css-3600:focus{border:1px solid #118082!important;}
			.select-css-3600{background-color:black;color:#64a7a6!important;border-color:#004a01!important;background-image:url(data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%23128a13%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E), linear-gradient(to bottom, #000 0%,#000 100%);}
			@media only screen and (min-width: 801px){
			.select-css-3600:hover{border:1px solid #118082!important;}
			}
			.select-css-3600:active{border:1px solid #118082!important;}
		</style>


		<!-- ********* -->
		<!-- PORTFOLIO -->
		<!-- ********* -->
		<div class="content">

			<div class="content-box">
				<h1>Crypto Portfolio</h1>
				<p>Manage your all coins and tokens in one single place</p>
				<div style="height:10px;"></div>

				<div class="container">
					<div id="portfolio_container">
						<div id="portfolio_content"><!-- Inject Portfolio --></div>
						
						<div id="portfolio_overlay"  class="widget-overlay"></div>
					</div>
				</div>
			</div>

			<style>
				.note-tr {
					color: #e2e2e2;
				}

				#portfolio_container {
					position: relative;
					height: 900px;
				}
				
				#portfolio_content {
					font-family: 'Montserrat', sans-serif;
					font-size: 12px;
				}

				#portfolio_content a {
					text-decoration: none;
				}

				#portfolio_content {
					width: 100%;
					height: 100%;
					position: absolute;
					top: 0;
					left: 0;
					overflow: hidden;
				}

				/* #portfolio_overlay {
					z-index: 10;
					background: url('/assets/landing/img/bottom-gradient.png?v=047');
					background-position: bottom center;
					background-repeat: no-repeat;
					pointer-events: none
				} */

				.widget-overlay {
					width: 100%;
					height: 100%;
					position: absolute;
					top: 0;
					left: 0;
					overflow: hidden;
					z-index: 10;
					background: url('/assets/landing/img/bottom-gradient.png?v=047');
					background-position: bottom center;
					background-repeat: no-repeat;
					pointer-events: none
				}

				.grid-portfolio-structure-inner {
					color: #e2e2e2;
				}

				.container{max-width:330px;margin:0 auto;text-align:center;position:relative;margin-top:10px;margin-bottom:20px;background:#fcfbee;background:-webkit-linear-gradient(#fcfbee, white);background:-o-linear-gradient(#fcfbee, white);background:-moz-linear-gradient(#fcfbee, white);background:linear-gradient(#fcfbee, white);border:0 none;border-top-left-radius:20px;border-top-right-radius:20px;border-bottom-left-radius:3px;border-bottom-right-radius:3px;box-shadow:0 0 12px 0px rgba(0, 0, 0, 0.3);position:relative;}
				.container header{background:#6a6a6a;color:#ffffff;height:50px;margin-bottom:30px;border-top-left-radius:3px;border-top-right-radius:3px;}

				@media screen and (max-width: 680px){
				.container{margin-top:28px;}
				}
				.svg-portfolio-icon{stroke:#888;pointer-events:bounding-box;cursor:pointer;}
				.grid-portfolio-titles{background-color:#8F8F8F;color:white;padding-bottom:8px;padding-top:8px;font-weight:bold;display:grid;grid-template-columns:45px 9.7fr 7fr 10fr 10fr;}
				.grid-portfolio-structure-outer-0{display:grid;padding-top:8px;grid-template-rows:49px;padding-bottom:1px;}
				.grid-portfolio-structure-outer-1{display:grid;grid-template-columns:45px 1fr;}
				.grid-portfolio-structure-outer-2{display:grid;grid-template-rows:1fr 1fr;}
				.grid-portfolio-structure-inner{display:grid;grid-template-columns:26.8% 19.5% 26.2% 28%;padding-top:1px;}
				@media screen and (max-width: 330px){
				.grid-portfolio-structure-inner{grid-template-columns:10fr 7fr 10fr 10fr;}
				}
				#portfolio-message{padding-left:10px;padding-right:10px;display:none;}
				.portfoliotable{width:100%;height:100%;display:block;table-layout:fixed;border-collapse:collapse;position:relative;margin-bottom:15px;}
				.portfolioinputdiv{margin-top:-3px;padding-left:1px;padding-right:1px;}
				.portfoliocoinamount{width:66px;margin-bottom:0!important;height:21px;font-size:11.5px;padding-left:3px!important;font-family:'Montserrat', sans-serif;border:1px solid rgb(153, 153, 153)!important;}
				@media screen and (min-width: 340px){
				.portfoliocoinamount{width:100%;}
				.portfolioinputdiv{padding-left:2px;}
				}
				.portfolio-tools{width:70px;float:right;border-radius:10px 0 0 0;}
				.pw-separator{border-bottom:1px solid #918f7b;}
				.pw-line{border-bottom:1px solid #918f7b;max-width:270px;margin:0 auto;padding-top:15px;}
				.text-header{color:#fff;}
				.note-img{float:left;margin-left:10.5px;padding-top:3px;}
				.note-tr{display:none;background-color:#ebebeb;}
				.note-textarea{width:290px;height:115px;font-size:12px;resize:none;padding:4px;font-family:'Montserrat', sans-serif;vertical-align:top;}
				.note-textarea:focus{outline:0;border:1px solid rgb(115, 124, 127)!important;}
				.profit{height:52px;margin-top:11.5px;background-color:#ebebeb;}
				.total-div{float:left;height:30px;width:130px;text-align:left;}
				.profit-div{float:right;height:30px;width:133px;text-align:center;white-space:nowrap;text-align:left;}
				.invested{font-size:11.5px;width:75px;height:20px;margin:0px 5px 0px 0px;}
				.plus-minus{font-size:17px;width:28px;height:28px;padding:0px!important;margin-bottom:10px!important;cursor:pointer;border-radius:3px;background-color:#efefef;border:1px solid #858585;}
				.plus-minus:focus{outline:none;background-color:rgb(246, 246, 246);border:1px solid #2e83ae!important;}
				.plus-minus-svg{fill:#666;fill-rule:evenodd;}
				.pw-minus{color:#d14836;}
				.pw-plus{color:#093;}
				.portfolio-buttons{box-sizing:content-box;width:69px;margin:0 auto;margin-top:5px;padding:2px;}
				select,input{border:1px solid #888!important;border-radius:2px;font-family:'Montserrat', sans-serif;font-size:13px;color:#000000;padding-left:4px;}
				input[type=text]:focus,input[type=number]:focus,.select-css-currency:focus{outline:none;border:1px solid #2e83ae!important;}
				input[type=number]{-moz-appearance:textfield;}
				.note-div{padding-top:10px;padding-bottom:10px;}
				.note-div textarea{cursor:auto;padding:4px;border:1px solid #aaa;}
				.note-div textarea::-webkit-scrollbar{width:7px;}
				.note-div textarea::-webkit-scrollbar-track{box-shadow:inset 0 0 6px rgba(0, 0, 0, 0.00);}
				.note-div textarea::-webkit-scrollbar-thumb{background-color:rgb(208, 208, 208);}
				.text-label{font-size:13px;color:#666;margin-bottom:2px;margin-top:20px;clear:both;}
				.select-css{display:block;font-size:12px;color:rgb(199, 199, 199)!important;line-height:1.3;width:56px;height:22px;margin:0;border:1px solid #aaa;box-shadow:0 1px 0 1px rgba(0,0,0,.04);border-radius:2px;padding-left:4px;-moz-appearance:none;-webkit-appearance:none;appearance:none;background-color:#333;background-image:url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%23C7C7C7%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E'), linear-gradient(to bottom, #3b3b3b 0%,#717171 100%);background-repeat:no-repeat, repeat;background-position:right 5px top 50%, 0 0;background-size:.65em auto, 100%;position:absolute;top:15px;right:10px;font-family:'Montserrat', sans-serif;}
				.select-css::-ms-expand{display:none;}
				.select-css:focus{border-color:#aaa;color:#222;outline:none;}
				.select-css option{font-weight:normal;}
				.select-css:disabled{color:graytext;background-image:url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22graytext%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E'), linear-gradient(to bottom, #3b3b3b 0%,#717171 100%);}
				/* .select-css-currency{width:100%;height:28px;margin:0;padding-left:4px;border:1px solid #aaa;border-radius:2px;-moz-appearance:none;-webkit-appearance:none;appearance:none;background-color:#fff;background-image:url(data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%23888888%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E), linear-gradient(to bottom, #fff 0%,#fff 100%);background-repeat:no-repeat, repeat;background-position:right 5px top 50%, 0 0;background-size:.65em auto, 100%;font-family:'Montserrat', sans-serif;} */
				.select-css-currency::-ms-expand{display:none;}
				.select-css-currency option{font-weight:normal;font-family:'Montserrat', sans-serif;}
				.roi{width:49px;height:20px;margin-left:1px;font-size:11.5px;padding-left:0px;}
				.total-val{width:55px;height:21px;margin-left:5px;font-size:11.5px;}
				.link-subscription{color:#222!important;}
				@media only screen and (min-width: 801px){
				.svg-portfolio-icon:hover{stroke:#222;}
				.note-textarea:hover{border:1px solid rgb(115, 124, 127)!important;}
				.plus-minus:hover{background-color:rgb(246, 246, 246);border:1px solid #2e83ae!important;box-shadow:0 0 2px rgb(201, 217, 233, 1)!important;}
				input[type=text]:hover,input[type=number]:hover,.select-css-currency:hover{border:1px solid #2e83ae!important;}
				.note-div textarea::-webkit-scrollbar-thumb:hover{background-color:#aaa;}
				.select-css:hover{border:1px solid rgb(165, 165, 165)!important;}
				.select-css:disabled:hover{border-color:#aaa;}
				}
				.svg-portfolio-icon:active{stroke:#222;}
				.note-textarea:active{border:1px solid rgb(115, 124, 127)!important;}
				.plus-minus:active{background-color:rgb(246, 246, 246);border:1px solid #2e83ae!important;box-shadow:0 0 2px rgb(201, 217, 233, 1)!important;}
				input[type=text]:active,input[type=number]:active,.select-css-currency:active{border:1px solid #2e83ae!important;}
				.note-div textarea::-webkit-scrollbar-thumb:active{background-color:#aaa;}
				.select-css:active{border:1px solid rgb(165, 165, 165)!important;}
				.select-css:disabled:active{border-color:#aaa;}

				/*! CSS Used from: /themes/coinwink-theme/style-matrix.css?v=601 */
				.container{background:linear-gradient(#000000, #0000005e);}
				.text-label{color:#4b7259;}
				/* a{color:#00c32e!important;} */
				select,input{background-color:black;}
				.container header{background-color:#242424;}
				.grid-portfolio-titles{background-color:#383838;color:#bebebe;}
				.pw-minus{color:#d5331e;}
				.pw-plus{color:#00b83d;}
				.portfoliocoinid a{color:white!important;}
				input,select{border-color:#128a13!important;color:#a6f6f4!important;}
				.link-subscription{color:#00c3a3!important;}
				.note-tr,.profit{background-color:transparent!important;}
				.note-textarea{background-color:black;color:#a6f6f4!important;border-color:#123f8a!important;}
				.note-textarea:focus{border:1px solid #2e83ae!important;}
				.note-div textarea::-webkit-scrollbar-thumb{background-color:rgb(108, 108, 108);}
				.portfoliocoinamount{border-color:#128a13!important;}
				.pw-separator{border-bottom:1px solid #19542e;}
				.pw-line{border-bottom:1px solid #19542e;}
				.svg-portfolio-icon{stroke:#008499;}
				.blacklink,a.blacklink{color:#00c3a3!important;text-decoration:underline;}
				.text-header{color:#b1b1b1;}

				@media only screen and (min-width: 801px){
				.link-subscription:hover{color:#a6f6f4!important;}
				.note-textarea:hover{border:1px solid #2e83ae!important;}
				.select-css:hover{border:1px solid #118082!important;}
				.note-div textarea::-webkit-scrollbar-thumb:hover{background-color:rgb(199, 199, 199);}
				.svg-portfolio-icon:hover{stroke:#a6f6f4;}
				.plus-minus:hover{background-color:rgb(20, 20, 20);border:1px solid #63c1bf!important;box-shadow:none!important;}
				.plus-minus:hover .plus-minus-svg{fill:#63c1bf;}
				.blacklink:hover,a.blacklink:hover{color:#a6f6f4!important;}
				}
				.link-subscription:active{color:#a6f6f4!important;}
				.note-textarea:active{border:1px solid #2e83ae!important;}
				.note-div textarea::-webkit-scrollbar-thumb:active{background-color:rgb(199, 199, 199);}
				.svg-portfolio-icon:active{stroke:#a6f6f4;}
				.blacklink:active,a.blacklink:active{color:#a6f6f4!important;}
				/*! CSS Used from: Embedded */
				.portfoliotable{height:0px!important;}
				.grid-portfolio-titles{margin-bottom:2px;}
			</style>

			<style>
				/*! CSS Used from: https://coinwink.com/css/app.css?id=c127a95cc3e5966a56eeb2e44cc9b418 */
				*{box-sizing:border-box;}
				select{border:1px solid #888!important;border-radius:2px;color:#000;font-family:Montserrat,sans-serif;font-size:13px;padding-left:4px;}
				.select-css{-moz-appearance:none;-webkit-appearance:none;appearance:none;background-color:#333;background-image:url("data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%23C7C7C7%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E"),linear-gradient(180deg,#3b3b3b 0,#717171);background-position:right 5px top 50%,0 0;background-repeat:no-repeat,repeat;background-size:.65em auto,100%;border:1px solid #aaa;border-radius:2px;box-shadow:0 1px 0 1px rgba(0,0,0,.04);color:#c7c7c7!important;display:block;font-family:Montserrat,sans-serif;font-size:12px;height:22px;line-height:1.3;margin:0;padding-left:4px;position:absolute;right:10px;top:15px;width:56px;}
				.select-css::-ms-expand{display:none;}
				.select-css:focus{border-color:#aaa;color:#222;outline:none;}
				.select-css option{font-weight:400;}
				.select-css:disabled{background-image:url("data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22graytext%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E"),linear-gradient(180deg,#3b3b3b 0,#717171);color:graytext;}
				@media only screen and (min-width:801px){
				.select-css:hover{border:1px solid #a5a5a5!important;}
				.select-css:disabled:hover{border-color:#aaa;}
				}
				.select-css:active{border:1px solid #a5a5a5!important;}
				.select-css:disabled:active{border-color:#aaa;}
				/*! CSS Used from: https://coinwink.com/lib/css/style-matrix.css?v=047 */
				select{background-color:black;}
				select{border-color:#128a13!important;color:#a6f6f4!important;}
				.select-css:focus{border:1px solid #118082!important;}
				.select-css{background-color:black;color:#64a7a6!important;border-color:#004a01!important;background-image:url(data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%23128a13%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E), linear-gradient(to bottom, #000 0%,#000 100%);}
				@media only screen and (min-width: 801px){
				.select-css:hover{border:1px solid #118082!important;}
				}
				.select-css:active{border:1px solid #118082!important;}
			</style>
		</div>


		<!-- **************** -->
		<!-- PORTFOLIO ALERTS -->
		<!-- **************** -->
		<div class="content">
			<div class="content-box" style="margin-top:10px;">
				
				<div style="height:600px;">
					<div style="height:10px;"></div>
					<h2>Multiple-Coin Portfolio Alerts</h2>
					<p>Track all your portfolio coins at once with continuous multiple-coin alerts</p>
					
					<div style="height:20px;"></div>
					
					<div class="widget-container" style="text-align: center; min-height: 40px;"><header><h3 style="cursor: pointer;">Portfolio Alerts</h3></header><div class="portfolio-alerts-content" id="portfolio-alerts-content" style="font-size: 13px; line-height: 150%;"><div style="height: 30px;"></div><form id="portfolio-alerts-form"> Alert me by: <br><span><select id="portfolio-alert-type" name="alert_type" class="select-css-currency" style="width: 220px; margin-top: 3px; margin-bottom: 8px; height: 28px;" onchange="destChanged()"><option value="email">E-mail</option><option value="telegram">Telegram</option><option value="sms">SMS</option></select></span><br><input type="text" id="portfolio-alert-destination" name="destination" placeholder="Email address" value="satoshi@nakamoto.com" style="width: 220px;"><div style="height: 30px;"></div><div><div class="portfolio-alerts-conditions" style="margin-left: -2px;"> When any coin in my portfolio: <div style="height: 15px;"></div><div style="padding-left: 8px;"><div class="ma-label"><div class="appify-checkbox" style="width: 231px; margin: 0px auto;"><input id="portfolio-alert-1-checkbox" name="portfolio-alert-1" type="checkbox" class="appify-input-checkbox"><label for="portfolio-alert-1-checkbox"><div class="checkbox-box"><svg><use xlink:href="#checkmark"></use></svg></div> Is up by </label></div></div><div class="ma-input"><input name="portfolio-alert-1-value" id="portfolio-alert-1-value" min="5" max="1000" value="20" type="number">&nbsp;<b>%</b> in 1h. </div><div style="height: 12px;"></div><div class="ma-label"><div class="appify-checkbox" style="width: 231px; margin: 0px auto;"><input id="portfolio-alert-2-checkbox" name="portfolio-alert-2" type="checkbox" class="appify-input-checkbox"><label for="portfolio-alert-2-checkbox"><div class="checkbox-box"><svg><use xlink:href="#checkmark"></use></svg></div> Is down by </label></div></div><div class="ma-input"><input name="portfolio-alert-2-value" id="portfolio-alert-2-value" min="5" max="1000" type="number" value="20">&nbsp;<b>%</b> in 1h. </div><div style="clear: both; height: 20px;"></div><div class="ma-label-2" style="margin-left: -4px;"><div class="appify-checkbox" style="width: 231px; margin: 0px auto;"><input id="portfolio-alert-3-checkbox" name="portfolio-alert-3" type="checkbox" class="appify-input-checkbox"><label for="portfolio-alert-3-checkbox"><div class="checkbox-box"><svg><use xlink:href="#checkmark"></use></svg></div> Is up by </label></div></div><div class="ma-input-2"><input name="portfolio-alert-3-value" id="portfolio-alert-3-value" min="5" max="1000" value="40" type="number">&nbsp;<b>%</b> in 24h. </div><div style="height: 12px;"></div><div class="ma-label-2" style="margin-left: -4px;"><div class="appify-checkbox" style="width: 231px; margin: 0px auto;"><input id="portfolio-alert-4-checkbox" name="portfolio-alert-4" type="checkbox" class="appify-input-checkbox"><label for="portfolio-alert-4-checkbox"><div class="checkbox-box"><svg><use xlink:href="#checkmark"></use></svg></div> Is down by </label></div></div><div class="ma-input-2"><input name="portfolio-alert-4-value" id="portfolio-alert-4-value" min="5" max="1000" type="number" value="40">&nbsp;<b>%</b> in 24h. </div><div style="height: 10px;"></div></div></div></div></form></div></div>

				</div>

			</div>

			<style>
				.widget-container {
					font-family: 'Montserrat', sans-serif;
				}
				h3{margin-bottom:20px;}
				h3{color:#fff;font-size:12px;font-weight:400!important;padding-top:19px;text-transform:uppercase;}

				.ma-label,.ma-label-2{clear:both;width:103px;}
				.ma-input,.ma-label,.ma-label-2{display:inline-block;text-align:left;white-space:nowrap;}
				.ma-input{width:110px;}
				.ma-input-2{display:inline-block;text-align:left;white-space:nowrap;width:106px;}
				.svg-show-hide{fill:#4b4b4d;}
				.portfolio-alerts-about{border:1px dashed #999;border-radius:2px;font-size:12px;line-height:100%;margin:15px auto -5px;min-height:28px;padding-top:7px;width:80px;}
				.portfolio-alerts-about-title{color:#444;}
				.appify-input-checkbox{display:none;}
				.appify-checkbox input:checked+label .checkbox-box>svg{height:15px;width:100%;}
				.appify-checkbox label{color:#424242;cursor:pointer;position:relative;}
				.appify-checkbox svg{height:100%;width:0;}
				.appify-checkbox div{display:grid;height:17px;padding:1px;text-align:center;vertical-align:middle;width:17px;}
				.appify-checkbox div{background-color:#f7f7f7;border:1px solid grey;box-sizing:border-box;content:"";float:left;margin-right:6px;}
				input,select{border:1px solid #888!important;border-radius:2px;color:#000;font-family:Montserrat,sans-serif;font-size:13px;padding-left:4px;}
				form input{height:28px;padding-left:5px;width:100%;}
				.select-css-currency:focus,input[type=number]:focus,input[type=text]:focus{border:1px solid #2e83ae!important;outline:none;}
				input[type=number]{-moz-appearance:textfield;}
				.portfolio-alerts-conditions input{font-size:13px;height:24px;padding-left:4px;width:44px;}

				.select-css-currency::-ms-expand{display:none;}
				.select-css-currency option{font-family:Montserrat,sans-serif;font-weight:400;}
				@media only screen and (min-width:801px){
				.portfolio-alerts-about:hover{background-color:#ececec;cursor:pointer;}
				.select-css-currency:hover,input[type=number]:hover,input[type=text]:hover{border:1px solid #2e83ae!important;}
				.expand-collapse:hover path{fill:#888;}
				}
				.portfolio-alerts-about:active{background-color:#ececec;cursor:pointer;}
				.select-css-currency:active,input[type=number]:active,input[type=text]:active{border:1px solid #2e83ae!important;}
				.expand-collapse:active path{fill:#888;}
				.widget-container{background:#fcfbee;background:linear-gradient(#fcfbee,#fff);border:0;border-bottom-left-radius:3px;border-bottom-right-radius:3px;border-top-left-radius:20px;border-top-right-radius:20px;box-shadow:0 0 12px 0 rgba(0,0,0,.3);color:#313131;margin:0 auto;max-width:330px;min-height:100px;position:relative;}
				header{background:#6a6a6a;border-top-left-radius:3px;border-top-right-radius:3px;color:#fff;margin:0 auto;max-width:330px;min-height:50px;text-align:center;}
				/*! CSS Used from: https://coinwink.com/lib/css/style-metaverse.css?v=0471 */
				.widget-container{color:#bbb;}
				.widget-container{background:#000108;border:1px solid #330030;border-radius:3px;}
				select,input{background-color:black;}
				.widget-container header{background-color:transparent;}
				.portfolio-alerts-about{border:1px dashed #555;}
				.portfolio-alerts-about-title{color:white;}



				input,select{border-color:#128a13!important;color:#a6f6f4!important;}

				.select-css-currency {
					border-color: #128a13;
					color: #a6f6f4!important;
					background-color: black;
					background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%23128a13%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E'), none;
				}

				.widget-container input, .widget-container select{border-color:#6b4cdb!important;color:#a6f6f4!important;}




				.widget-container .select-css-currency{border-color:#6b4cdb;color:#a6f6f4!important;background-color:black;background-image:url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%2364a7a6%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E'), none;}



				.svg-show-hide{fill:#111;}
				.appify-checkbox label{color:white;}
				.appify-checkbox div{background-color:black;border:1px solid #6b4cdb;}
				@media only screen and (min-width: 801px){
				.portfolio-alerts-about:hover{background-color:#111;}
				.appify-checkbox:hover div{border:1px solid #007bb5;}
				}
				.portfolio-alerts-about:active{background-color:#111;}
				.appify-checkbox:active div{border:1px solid #007bb5;}
				h1{padding-top:12px;}
				.portfolio-alerts-content{margin-bottom:30px;}




				#checkmark {
					fill: #00b8b8!important;
				}

				.portfolio-alerts-about {
					border: 1px dashed #999;
					border-radius: 2px;
					font-size: 12px;
					line-height: 100%;
					margin: 15px auto -5px;
					min-height: 28px;
					padding-top: 7px;
					width: 80px;
				}

				.portfolio-alerts-about-content {
				padding-top:20px;
				padding-bottom: 5px;
				line-height:140%;
				text-align: left;
				display: none;
				width: 280px;
				margin:0 auto;
				}

				.portfolio-alerts-about-expanded {
				width: 100%;
				padding:20px;
				border-top:1px dashed #999999;
				border-bottom:1px dashed #999999;
				border-left:0px;
				border-right:0px;
				padding-top:20px;
				padding-bottom:14px;
				}

				.portfolio-alerts-about-expanded {
					background: #111111;
				}

				.portfolio-alerts-about-title-bold {
				font-weight: bold;
				color: #fff;
				}
			</style>
		</div>


		<!-- ********* -->
		<!-- CONVERTER -->
		<!-- ********* -->
		<div class="content">
			<div class="content-box" style="margin-top:50px;">
				<h2>Crypto Converter</h2>
				<p>Convert between 9 fiat currencies and 3600 cryptocurrencies</p>
				<div style="height:20px;"></div>

				<div id="portfolio-currency-converter"></div>

				<template>
					<div class="widget-container" style="text-align: center; min-height: 40px;">

						<header style="margin-bottom:0px;display:block!important;" id="converter-header">
							<h3 class="text-header">Cryptocurrency Converter</h3>
						</header>

						<div class="animated-gif-base64-spinner-loader" id="ajax_loader_converter" style="margin-top:30px;">
							<div style="height:32px;background-repeat: no-repeat;background-position: center;background-image: url('data:image/gif;base64,R0lGODlhEAAQAPIAAP///wAAAMLCwkJCQgAAAGJiYoKCgpKSkiH/C05FVFNDQVBFMi4wAwEAAAAh/hpDcmVhdGVkIHdpdGggYWpheGxvYWQuaW5mbwAh+QQJCgAAACwAAAAAEAAQAAADMwi63P4wyklrE2MIOggZnAdOmGYJRbExwroUmcG2LmDEwnHQLVsYOd2mBzkYDAdKa+dIAAAh+QQJCgAAACwAAAAAEAAQAAADNAi63P5OjCEgG4QMu7DmikRxQlFUYDEZIGBMRVsaqHwctXXf7WEYB4Ag1xjihkMZsiUkKhIAIfkECQoAAAAsAAAAABAAEAAAAzYIujIjK8pByJDMlFYvBoVjHA70GU7xSUJhmKtwHPAKzLO9HMaoKwJZ7Rf8AYPDDzKpZBqfvwQAIfkECQoAAAAsAAAAABAAEAAAAzMIumIlK8oyhpHsnFZfhYumCYUhDAQxRIdhHBGqRoKw0R8DYlJd8z0fMDgsGo/IpHI5TAAAIfkECQoAAAAsAAAAABAAEAAAAzIIunInK0rnZBTwGPNMgQwmdsNgXGJUlIWEuR5oWUIpz8pAEAMe6TwfwyYsGo/IpFKSAAAh+QQJCgAAACwAAAAAEAAQAAADMwi6IMKQORfjdOe82p4wGccc4CEuQradylesojEMBgsUc2G7sDX3lQGBMLAJibufbSlKAAAh+QQJCgAAACwAAAAAEAAQAAADMgi63P7wCRHZnFVdmgHu2nFwlWCI3WGc3TSWhUFGxTAUkGCbtgENBMJAEJsxgMLWzpEAACH5BAkKAAAALAAAAAAQABAAAAMyCLrc/jDKSatlQtScKdceCAjDII7HcQ4EMTCpyrCuUBjCYRgHVtqlAiB1YhiCnlsRkAAAOwAAAAAAAAAAAA==');"></div>
							<div style="height:30px;"></div>
						</div>

						<div id="converter-content" style="font-size:13px;line-height:150%;display:none;">

							<div style="height:30px;"></div>

							<div style="display:grid;width:285px;margin:0 auto;grid-template-columns: 110px 10px 165px;">
								<div><input type="text" id="conv-input-1" style="padding-left: 5px; width: 100%; height: 30px;"></div>
								<div></div>
								<div>
									<select class="selectcoin-conv-1" id="conv-select-1" style="width:100%;"></select>
								</div>
							</div>

							<div style="height:10px;"></div>

							<div style="display:grid;width:285px;margin:0 auto;grid-template-columns: 110px 10px 165px;">
								<div><input type="text" id="conv-input-2" style="padding-left: 5px; width: 100%; height: 30px;"></div>
								<div></div>
								<div>
									<select class="selectcoin-conv-2" id="conv-select-2" style="width:100%;"></select>
								</div>
							</div>
							
							<div style="height:25px;"></div>
						</div>

						<style>
							.conv-header-closed {
								border-radius: 3px;
							}

							.converter-arrow {
								display: none;
							}
						</style>

					</div>
				</template>
			</div>
		</div>


		<!-- ********* -->
		<!-- WATCHLIST -->
		<!-- ********* -->
		<div class="content">
			<div class="content-box">
				<h1>Watchlist</h1>
				<p>Track your crypto favorites with Volume, Price, and Market Cap views</p>
				<div style="height:10px;"></div>
				
				<div class="container" style="margin-top:10px;">
					<header style="margin-bottom:0px;display:block!important;">
						<h3 class="text-header">WATCHLIST</h3>
					</header>

					<div id="watchlist_container">
						<div id="watchlist_content"></div>
						<div class="widget-overlay"></div>
					</div>
				</div>
			</div>

			<style>
				#watchlist_container {
					overflow: hidden;
				}

				.watchlist-col-labels {
					display: grid;
					grid-template-columns: 1.45fr 0.8fr 1.1fr 1fr 1.65fr;
					background-color: #8F8F8F;
					color: white;
					padding-bottom: 8px;
					font-weight: bold;
					padding-top: 6px;
				}

				.watchlist-col-labels, .grid-portfolio-titles {
					background-color: #383838;
					color: #bebebe;
				}

				.watchlist-grid {
					display: grid;
					grid-template-columns: 1.45fr 0.85fr 1.1fr 1fr 1.65fr;
					padding-top: 12px;
					padding-bottom: 8px;
				}

				#watchlist_content {
					font-family: 'Montserrat', sans-serif;
					font-weight: bold;
					font-size: 12px;
					color: #bbb;
					height:760px;
				}

				#watchlist_content a, #watchlist_content a:visited, #watchlist_content a:active, #watchlist_content a:hover {
					text-decoration: none;
					color: #bbb;
				}
				
				#watchlist-span {
					border-bottom: 1px dashed #a6f6f4;
					cursor: pointer;
				}
			</style>
		</div>


		<!-- ****** -->
		<!-- THEMES -->
		<!-- ****** -->
		<div class="content-full-w">
			<div class="content-box" style="margin-top:80px;">
				<h1>Themes</h1>
				<div style="height:30px;"></div>
			</div>

			<div class="content-full-w themes">
			</div>
		</div>


		<!-- *************** -->
		<!-- MOBILE FRIENDLY -->
		<!-- *************** -->
		<div class="content">
			<div class="content-box">
				<h1>Mobile Friendly</h1>
				<p>
					<a href="https://coinwink.com/blog/how-to-use-coinwink-as-a-mobile-app" target="_blank" style="color:#b7c0ec;">Add Coinwink</a> app icon to your mobile home screen for quick access and a native-like experience
				</p>
				<div style="height:20px;"></div>
				<img src="/assets/landing/img/coinwink-app.png?v=047" loading="lazy" alt="Coinwink mobile app" class="mobile-friendly">
			</div>
		</div>


		<!-- ******* -->
		<!-- PRICING -->
		<!-- ******* -->
		<div class="content">
			<div class="content-box">
				<h1>Pricing</h1>
				<div style="height:35px;"></div>
				<!-- <img src="/assets/landing/img/email-crypto-alerts.png" class="image"> -->
				
				<div style="position:relative;">
					<div class="grid-1-1-1 grid-email-alerts-mobile" style="grid-column-gap:20px;">
						<div>
							<div>
								<!-- <div class="alert-box" style="height:208px;margin-bottom: 35px;"> -->
								<div class="alert-box" style="height:190px;margin-bottom: 35px;">
									<div class="rounded-border">
										<b style="font-size:15px;">Free</b>
										<div style="height:25px;"></div>
										<div style="padding-left: 65px; margin-bottom: 16px" class="mobile-pricing">
										<div style="float: left; width: 12px">
											<svg viewBox="0 0 512 444.03">
											<polygon
												points="202.62 444.03 0 257.38 70.51 180.82 191.97 292.67 431.44 0 512 65.92 202.62 444.03"
												style="fill: #777777"
											></polygon>
											</svg>
										</div>
										<div style="float: left; margin-left: 7px">5 active Email alerts</div>
										<div style="clear: both; height: 3px"></div>
										<div style="float: left; width: 12px">
											<svg viewBox="0 0 512 444.03">
											<polygon
												points="202.62 444.03 0 257.38 70.51 180.82 191.97 292.67 431.44 0 512 65.92 202.62 444.03"
												style="fill: #777777"
											></polygon>
											</svg>
										</div>
										<div style="float: left; margin-left: 7px">5 active Telegram alerts</div>
										<!-- <div style="clear: both; height: 3px"></div>
										<div style="float: left; width: 12px">
											<svg viewBox="0 0 512 444.03">
											<polygon
												points="202.62 444.03 0 257.38 70.51 180.82 191.97 292.67 431.44 0 512 65.92 202.62 444.03"
												style="fill: #777777"
											></polygon>
											</svg>
										</div>
										<div style="float: left; margin-left: 7px">5 active Portfolio alerts</div> -->
										<div style="clear: both; height: 3px"></div>
										<div style="float: left; width: 12px">
											<svg viewBox="0 0 512 444.03">
											<polygon
												points="202.62 444.03 0 257.38 70.51 180.82 191.97 292.67 431.44 0 512 65.92 202.62 444.03"
												style="fill: #777777"
											></polygon>
											</svg>
										</div>
										<div style="float: left; margin-left: 7px">5 coins in Portfolio</div>
										<div style="clear: both; height: 3px"></div>
										<div style="float: left; width: 12px">
											<svg viewBox="0 0 512 444.03">
											<polygon
												points="202.62 444.03 0 257.38 70.51 180.82 191.97 292.67 431.44 0 512 65.92 202.62 444.03"
												style="fill: #777777"
											></polygon>
											</svg>
										</div>
										<div style="float: left; margin-left: 7px">5 coins in Watchlist</div>
										</div>
										<div style="height:37px;"></div>
										<b>$0&nbsp;/&nbsp;month</b>
									</div>	
								</div>
							</div>
						</div>
						<div>
							<div>
								<div class="alert-box" style="height:190px;margin-bottom: 35px;">
									<div class="rounded-border">
										<b style="font-size:15px;">Standard</b>
										<div style="height:25px;"></div>
										<div style="padding-left: 40px; margin-bottom: 18px" class="mobile-pricing">
										<div style="float: left; width: 12px">
											<svg viewBox="0 0 512 444.03">
											<polygon
												points="202.62 444.03 0 257.38 70.51 180.82 191.97 292.67 431.44 0 512 65.92 202.62 444.03"
												style="fill: #777777"
											></polygon>
											</svg>
										</div>
										<div style="float: left; margin-left: 7px">
											Unlimited Email alerts
										</div>
										<div style="clear: both; height: 3px"></div>
										<div style="float: left; width: 12px">
											<svg viewBox="0 0 512 444.03">
											<polygon
												points="202.62 444.03 0 257.38 70.51 180.82 191.97 292.67 431.44 0 512 65.92 202.62 444.03"
												style="fill: #777777"
											></polygon>
											</svg>
										</div>
										<div style="float: left; margin-left: 7px">
											Unlimited Telegram alerts
										</div>
										<!-- <div style="clear: both; height: 3px"></div>
										<div style="float: left; width: 12px">
											<svg viewBox="0 0 512 444.03">
											<polygon
												points="202.62 444.03 0 257.38 70.51 180.82 191.97 292.67 431.44 0 512 65.92 202.62 444.03"
												style="fill: #777777"
											></polygon>
											</svg>
										</div>
										<div style="float: left; margin-left: 7px">
											Unlimited Portfolio alerts
										</div> -->
										<div style="clear: both; height: 3px"></div>
										<div style="float: left; width: 12px">
											<svg viewBox="0 0 512 444.03">
											<polygon
												points="202.62 444.03 0 257.38 70.51 180.82 191.97 292.67 431.44 0 512 65.92 202.62 444.03"
												style="fill: #777777"
											></polygon>
											</svg>
										</div>
										<div style="float: left; margin-left: 7px">
											Unlimited coins in Portfolio
										</div>
										<div style="clear: both; height: 3px"></div>
										<div style="float: left; width: 12px">
											<svg viewBox="0 0 512 444.03">
											<polygon
												points="202.62 444.03 0 257.38 70.51 180.82 191.97 292.67 431.44 0 512 65.92 202.62 444.03"
												style="fill: #777777"
											></polygon>
											</svg>
										</div>
										<div style="float: left; margin-left: 7px">
											Unlimited coins in Watchlist
										</div>
										</div>
										<div style="height:35px;"></div>
										<b>$8&nbsp;/&nbsp;month</b>
									</div>	
								</div>
							</div>
						</div>
						<div>
							<div>
								<div class="alert-box" style="height:190px;">
									<div class="rounded-border">
										<b style="font-size:15px;">Premium</b>
										<div style="height:25px;"></div>
										<div style="padding-left: 40px; margin-bottom: 18px"  class="mobile-pricing">
										<div style="float: left; width: 12px">
											<svg viewBox="0 0 512 444.03">
											<polygon
												points="202.62 444.03 0 257.38 70.51 180.82 191.97 292.67 431.44 0 512 65.92 202.62 444.03"
												style="fill: #777777"
											></polygon>
											</svg>
										</div>
										<div style="float: left; margin-left: 7px">
											Unlimited Email alerts
										</div>
										<div style="clear: both; height: 3px"></div>
										<div style="float: left; width: 12px">
											<svg viewBox="0 0 512 444.03">
											<polygon
												points="202.62 444.03 0 257.38 70.51 180.82 191.97 292.67 431.44 0 512 65.92 202.62 444.03"
												style="fill: #777777"
											></polygon>
											</svg>
										</div>
										<div style="float: left; margin-left: 7px">
											Unlimited Telegram alerts
										</div>
										<!-- <div style="clear: both; height: 3px"></div>
										<div style="float: left; width: 12px">
											<svg viewBox="0 0 512 444.03">
											<polygon
												points="202.62 444.03 0 257.38 70.51 180.82 191.97 292.67 431.44 0 512 65.92 202.62 444.03"
												style="fill: #777777"
											></polygon>
											</svg>
										</div>
										<div style="float: left; margin-left: 7px">
											Unlimited Portfolio alerts
										</div> -->
										<div style="clear: both; height: 3px"></div>
										<div style="float: left; width: 12px">
											<svg viewBox="0 0 512 444.03">
											<polygon
												points="202.62 444.03 0 257.38 70.51 180.82 191.97 292.67 431.44 0 512 65.92 202.62 444.03"
												style="fill: #777777"
											></polygon>
											</svg>
										</div>
										<div style="float: left; margin-left: 7px">
											Unlimited coins in Portfolio
										</div>
										<div style="clear: both; height: 3px"></div>
										<div style="float: left; width: 12px">
											<svg viewBox="0 0 512 444.03">
											<polygon
												points="202.62 444.03 0 257.38 70.51 180.82 191.97 292.67 431.44 0 512 65.92 202.62 444.03"
												style="fill: #777777"
											></polygon>
											</svg>
										</div>
										<div style="float: left; margin-left: 7px">
											Unlimited coins in Watchlist
										</div>
										<div style="clear: both; height: 3px"></div>
										<div style="float: left; width: 12px">
											<svg viewBox="0 0 512 444.03">
											<polygon
												points="202.62 444.03 0 257.38 70.51 180.82 191.97 292.67 431.44 0 512 65.92 202.62 444.03"
												style="fill: #777777"
											></polygon>
											</svg>
										</div>
										<div style="float: left; margin-left: 7px">
											100 SMS alerts per month
										</div>
										</div>
										<div style="height:18px;"></div>
										<b>$12&nbsp;/&nbsp;month</b>
									</div>	
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>


		<!-- ******* -->
		<!-- ACCOUNT -->
		<!-- ******* -->
		<div class="content">
			<div class="content-box" style="margin-top:60px;">
				<div style="text-align:center;margin:0 auto;">
					Start with a free Coinwink account
					<div style="height:15px;"></div>
					<a href="/register"><button class="button-main" style="width:160px;">Sign Up</button></a>
					<div style="height:50px;"></div>
					Already have an account?
					<div style="height:10px;"></div>
					<a href="/login">Log in</a>
				</div>
			</div>
		</div>

		
		<!-- ****** -->
		<!-- FOOTER -->
		<!-- ****** -->
		<div class="content">
			<div class="content-box" style="margin-top: 140px;">
				<div class="soc-icons">
					<div>
						<a href="https://twitter.com/coinwink" title="Twitter" target="_blank">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 124.11 124.11" class="soc-icon"><title>Twitter</title><path d="M199.05,335a62.05,62.05,0,1,0,62.05,62.05A62.05,62.05,0,0,0,199.05,335h0Zm46.81,39.49a35.82,35.82,0,0,1-10.3,2.82,18,18,0,0,0,7.88-9.92,35.93,35.93,0,0,1-11.39,4.35,17.94,17.94,0,0,0-30.55,16.35,50.9,50.9,0,0,1-37-18.74,18,18,0,0,0,5.55,23.94,17.87,17.87,0,0,1-8.12-2.24c0,0.07,0,.15,0,0.22a18,18,0,0,0,14.38,17.58,17.95,17.95,0,0,1-8.09.31A18,18,0,0,0,185,421.62,36.16,36.16,0,0,1,158.48,429,50.74,50.74,0,0,0,186,437.1c33,0,51-27.32,51-51q0-1.17-.05-2.32a36.45,36.45,0,0,0,8.94-9.28h0Z" transform="translate(-137 -335)"></path></svg>
						</a>
					</div>
					<div>
						<a href="https://github.com/coinwink/Coinwink" title="GitHub" target="_blank">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 124.11 124.11" class="soc-icon"><title>GitHub</title><path d="M411.45,335a62.06,62.06,0,0,0-19.61,120.94c3.1,0.57,4.24-1.35,4.24-3,0-1.47-.05-5.38-0.08-10.55-17.26,3.75-20.9-8.32-20.9-8.32-2.82-7.17-6.89-9.08-6.89-9.08-5.63-3.85.43-3.77,0.43-3.77,6.23,0.44,9.51,6.39,9.51,6.39,5.53,9.48,14.52,6.74,18.06,5.16,0.56-4,2.17-6.74,3.94-8.3-13.78-1.57-28.27-6.89-28.27-30.67a24,24,0,0,1,6.39-16.65c-0.64-1.57-2.77-7.88.61-16.42,0,0,5.21-1.67,17.06,6.36a58.79,58.79,0,0,1,31.07,0c11.85-8,17-6.36,17-6.36,3.39,8.54,1.26,14.85.62,16.42A23.94,23.94,0,0,1,451,393.81c0,23.84-14.51,29.08-28.33,30.62,2.22,1.91,4.21,5.7,4.21,11.49,0,8.29-.08,15-0.08,17,0,1.66,1.12,3.59,4.27,3A62.06,62.06,0,0,0,411.45,335h0Z" transform="translate(-349.4 -335)"></path></svg>
						</a>
					</div>
				</div>

				<div style="height:30px;"></div>
				
				<div class="footer-menu">
					<a href="/about" target="_blank">About</a>&nbsp;
					<a href="/pricing" target="_blank">Pricing</a>&nbsp;
					<a href="/terms" target="_blank">Terms</a>&nbsp;
					<a href="/privacy" target="_blank">Privacy</a>&nbsp;
					<a href="https://coinwink.com/blog/" target="_blank">Blog</a>&nbsp;
					<a href="/contacts" target="_blank">Contacts</a>
				</div>
				<div style="height:15px;"></div>
				<div class="footer-menu-2">
					<a href="/btc" target="_blank">Bitcoin Alert</a>&nbsp;
					<a href="/eth" target="_blank">Ethereum Alert</a>&nbsp;
					<div class="footer-menu-div"></div>
					<a href="/xrp" target="_blank">XRP Alert</a>&nbsp;
					<a href="/doge" target="_blank">Dogecoin Alert</a>
				</div>
				<div style="height:30px;"></div>
				Coinwink © 2016-2023
				<br>
				<br>
				Privacy Focused and Open Source
				<br>
				Based on <a href="https://coinmarketcap.com" target="_blank" style="color:#b7c0ec;">CoinMarketCap</a>
			</div>
			<div class="spacer-bottom"></div>
		</div>

		<!-- Footer background -->
		<!-- <div style="position:relative;">
			<div style="position:absolute;bottom:0px;background:url('/assets/landing/img/bg-bottom.png');height:500px;width:100%;background-size: auto 300px;background-repeat: no-repeat;background-position: bottom center;height: 300px;">
			</div>
		</div> -->

		<!-- ********* -->
        <!-- SVG icons -->
		<!-- ********* -->
        <svg style="display: none">
            <defs>
                <symbol id="checkmark" viewBox="0 0 512 444.03">
                    <polygon points="202.62 444.03 0 257.38 70.51 180.82 191.97 292.67 431.44 0 512 65.92 202.62 444.03"></polygon> 
                </symbol>
            </defs>
        </svg>

    </body>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
	<script src="/assets/landing/js/select2.min.js?v=047"></script>
	<script src="/assets/landing/js/select2_optimization.js?v=047"></script>
	<script src="/assets/landing/js/coinwink_utils.js?v=047"></script>
	<script src="/assets/landing/js/coinwink_portfolio_demo.js?v=047"></script>
	
    <!-- <script src="/lib/js/DragDropTouch.min.js"></script> -->
    <script src="/lib/js/html5sortable.min.js"></script>
	<script src="/assets/landing/js/coinwink_watchlist_demo.js?v=047"></script>

	<script>
		// load_watchlist(watchlist);
	</script>

	<script>
		function alertReenable(el) {
			if (el.classList.contains("line-through")) {
				el.classList.remove("line-through")
			}
			else {
				el.classList.add("line-through")
			}
		}

		// Function for the below function
		function changeCur(self) {
			var keys = Object.keys(curList);
			var nextIndex = keys.indexOf(curCurrent) +1;
			var nextItem = keys[nextIndex];
			curCurrent = nextItem;
			var el = getById(self.id);

			if (typeof(curCurrent) != 'undefined') {
				var price = curList[curCurrent];
				// console.log(curList, price);
				price = showpriceUpdate(price);

				el.innerHTML = price + empty + curCurrent;
				// el.innerHTML = formatCurrencyHome(price, curCurrent) + ' ' + curCurrent;
			}
			else {
				changeCur(self);
			}
		}

		// Show current price and add price to percent input
		function showpriceUpdate(price) {
			// console.log(price);
			if (price < 0.0001) { 
				price = parseFloat(price).toFixed(7); 
			}
			else if (price < 0.001) { 
				price = parseFloat(price).toFixed(6); 
			}
			else if (price < 0.01) { 
				price = parseFloat(price).toFixed(5); 
			}
			else if (price < 0.1) { 
				price = parseFloat(price).toFixed(4); 
			}
			else if (price < 0.9) { 
				price = parseFloat(price).toFixed(3); 
			}
			else { 
				price = parseFloat(price).toFixed(2); 
			}
			return price;
		}


		var priceCurrent3600 = '';
		var curCurrent3600 = '';

		function showprice() {
			curCurrent3600 = 'USD';
			for(var i = 0; i < cw_cmc.length; i++) {
				var coin = cw_cmc[i];

				var eth = coin['price_eth'];
				if (eth % 1 != 0) { eth = parseFloat(eth).toFixed(8); }
				if (!(eth > 0.00000001)) {
				eth = parseFloat(coin['price_eth']).toFixed(9);
				if (!(eth > 0.000000001)) {
					eth = parseFloat(coin['price_eth']).toFixed(10);
				}
				}
				var btc = coin['price_btc'];
				if (btc % 1 != 0) { btc = parseFloat(btc).toFixed(8); }
				if (!(btc > 0.00000001)) {
				btc = parseFloat(coin['price_btc']).toFixed(9);
				if (!(btc > 0.000000001)) {
					btc = parseFloat(coin['price_btc']).toFixed(10);
				}
				}
				var usd = coin['price_usd'];

				var eur = coin['price_usd'] * cw_rates['eur'];
				var gbp = coin['price_usd'] * cw_rates['gbp'];
				var aud = coin['price_usd'] * cw_rates['aud'];
				var cad = coin['price_usd'] * cw_rates['cad'];
				
				var brl = coin['price_usd'] * cw_rates['brl'];
				var mxn = coin['price_usd'] * cw_rates['mxn'];
				var jpy = coin['price_usd'] * cw_rates['jpy'];
				var sgd = coin['price_usd'] * cw_rates['sgd'];


				if (coin['id'] == jQuery("#id").val()) {
					curList = { USD: usd, EUR: eur, GBP: gbp, AUD: aud, CAD: cad, BRL: brl, MXN: mxn, JPY: jpy, SGD: sgd };

					if (curCurrent3600 == 'BTC') {
						priceCurrent3600 = btc;
					}
					else if (curCurrent3600 == 'ETH') {
						priceCurrent3600 = eth;
					}
					else {
						var price = curList[curCurrent3600];
						priceCurrent3600 = showpriceUpdate(price);
					}

					jQuery("#pricediv").html("<a target='_blank' class='portfoliocoin' rel='nofollow' href='https://coinwink.com/" + coin['symbol'].toLowerCase() +"'><img style='vertical-align:middle;' width='50' height='50' src='/img/coins/128x128/"+coin['id']+
					".png'></a><div style='height:10px;'></div>"+coin['name']+" is on Coinwink<div style='height:15px;'></div>Current price:<div style='height:5px;'></div><b style='font-size:22px;'><span id='price-current-3600'  style='color:white;'>"+ priceCurrent3600 + "</span> <span id='cur-current-3600' onclick='changeCur3600()' class='span-click noselect'  title='Change currency'>" + curCurrent3600 + "</span></b>");
				}
			}
		}

		function updatePrice() {

			for(var i = 0; i < cw_cmc.length; i++) {

				var coin = cw_cmc[i];
				if (coin['id'] == jQuery("#id").val()) {

					var eth = coin['price_eth'];
					if (eth % 1 != 0) { eth = parseFloat(eth).toFixed(8); }
					if (!(eth > 0.00000001)) {
					eth = parseFloat(coin['price_eth']).toFixed(9);
					if (!(eth > 0.000000001)) {
						eth = parseFloat(coin['price_eth']).toFixed(10);
					}
					}
					var btc = coin['price_btc'];
					if (btc % 1 != 0) { btc = parseFloat(btc).toFixed(8); }
					if (!(btc > 0.00000001)) {
					btc = parseFloat(coin['price_btc']).toFixed(9);
					if (!(btc > 0.000000001)) {
						btc = parseFloat(coin['price_btc']).toFixed(10);
					}
					}
					var usd = coin['price_usd'];

					var eur = coin['price_usd'] * cw_rates['eur'];
					var gbp = coin['price_usd'] * cw_rates['gbp'];
					var aud = coin['price_usd'] * cw_rates['aud'];
					var cad = coin['price_usd'] * cw_rates['cad'];

					var brl = coin['price_usd'] * cw_rates['brl'];
					var mxn = coin['price_usd'] * cw_rates['mxn'];
					var jpy = coin['price_usd'] * cw_rates['jpy'];
					var sgd = coin['price_usd'] * cw_rates['sgd'];

					curList = { USD: usd, EUR: eur, GBP: gbp, AUD: aud, CAD: cad, BRL: brl, MXN: mxn, JPY: jpy, SGD: sgd };

					if (curCurrent3600 == 'BTC') {
						priceCurrent3600 = btc;
					}
					else if (curCurrent3600 == 'ETH') {
						priceCurrent3600 = eth;
					}
					else {
						var price = curList[curCurrent3600];
						priceCurrent3600 = showpriceUpdate(price);
					}

					document.getElementById('price-current-3600').innerHTML = priceCurrent3600;
					document.getElementById('cur-current-3600').innerHTML = curCurrent3600;
					
				}
			}
		}

		function selectCurrency3600() {
			console.log(document.getElementById('selectcurrency-3600').value)
			curCurrent3600 = document.getElementById('selectcurrency-3600').value;
			updatePrice();
		}

		// jQuery(document).ready(function () {
		// 	showprice();
		// });

		jQuery("#id").change(function () {
			showprice();
		});
		
		function changeCur3600() {
			var curListTemp = { USD: '', EUR: '', GBP: '', AUD: '', CAD: '', BRL: '', MXN: '', JPY: '', SGD: '' };

			if (curCurrent3600 == 'SGD') {
				curCurrent3600 = 'USD';
			}
			else {
				var keys = Object.keys(curListTemp);
				var nextIndex = keys.indexOf(curCurrent3600) +1;
				var nextItem = keys[nextIndex];
				curCurrent3600 = nextItem;
			}
			updatePrice();
		}
	</script>

	<script>
		// 
		// PORTFOLIO ALERTS
		// 

		function destChanged() {
			if (document.getElementById('portfolio-alert-type').value == 'sms') {
				document.getElementById('portfolio-alert-destination').placeholder = 'Phone number';
				document.getElementById('portfolio-alert-destination').value = '';
			}
			else if (document.getElementById('portfolio-alert-type').value == 'telegram') {
				document.getElementById('portfolio-alert-destination').placeholder = 'Telegram username';
				document.getElementById('portfolio-alert-destination').value = '';
			}
			else {
				document.getElementById('portfolio-alert-destination').placeholder = 'Email address';
				document.getElementById('portfolio-alert-destination').value = 'satoshi@nakamoto.com';
			}
		}
	
		jQuery("#portfolio-alerts-about-show-hide").click(function() {
			jQuery("#portfolio-alerts-about-show-hide").toggleClass("portfolio-alerts-about-expanded");
			jQuery(".portfolio-alerts-about-title").toggleClass("portfolio-alerts-about-title-bold");
			jQuery(".portfolio-alerts-about-content").toggle();
		});

		jQuery("#portfolio-alert-1-checkbox").prop( "checked", true );
		jQuery("#portfolio-alert-2-checkbox").prop( "checked", true );
		jQuery("#portfolio-alert-3-checkbox").prop( "checked", true );
		jQuery("#portfolio-alert-4-checkbox").prop( "checked", true );

		function checkAlertsCount() {
			var string = '';
			var alertsCount = 0;
			if (jQuery("#portfolio-alert-1-checkbox").prop( "checked")) {
				alertsCount++;
			}
			if (jQuery("#portfolio-alert-2-checkbox").prop( "checked")) {
				alertsCount++;
			}
			if (jQuery("#portfolio-alert-3-checkbox").prop( "checked")) {
				alertsCount++;
			}
			if (jQuery("#portfolio-alert-4-checkbox").prop( "checked")) {
				alertsCount++;
			}

			if (alertsCount == 0) {
				string = '0 active portfolio alerts';
			}
			else if (alertsCount == 1) {
				string = '1 active portfolio alert';
			}
			else if (alertsCount == 2) {
				string = '2 active portfolio alerts';
			}
			else if (alertsCount == 3) {
				string = '3 active portfolio alerts';
			}
			else if (alertsCount == 4) {
				string = '4 active portfolio alerts';
			}
			
			document.getElementById('portfolio-user-feedback').innerHTML = string;
		}
		// checkAlertsCount();

		// jQuery("#portfolio-alert-1-checkbox").change(function() {
		// 	checkAlertsCount();
		// });
		// jQuery("#portfolio-alert-2-checkbox").change(function() {
		// 	checkAlertsCount();
		// });
		// jQuery("#portfolio-alert-3-checkbox").change(function() {
		// 	checkAlertsCount();
		// });
		// jQuery("#portfolio-alert-4-checkbox").change(function() {
		// 	checkAlertsCount();
		// });
		
	</script>

	<script>
		// 
		// Sticky header
		// 
		var scrollLimit = 350;
		var topMenuVisible = false;
		if (window.scrollY > scrollLimit) {
			topMenuVisible = true;
			document.getElementById('top-menu').style.display = "grid";
		}
		window.onscroll = function() {
			if (window.scrollY > scrollLimit && !topMenuVisible) {
				topMenuVisible = true;
				document.getElementById('top-menu').style.display = "grid";
			}
			if (window.scrollY < scrollLimit && topMenuVisible) {
				topMenuVisible = false;
				document.getElementById('top-menu').style.display = "none";
			}
		};
	</script>

	<script>
		// 
		// CURRENCY CONVERTER
		// 

		function currencyConverterPrep() {
			var temp = document.getElementsByTagName("template")[0];
			var clon = temp.content.cloneNode(true);

			document.getElementById('portfolio-currency-converter').innerHTML = "";

			document.getElementById('portfolio-currency-converter').appendChild(clon);
			initialValue = '1';
			
			var curListArray = Object.keys(cw_rates).map((key) => [String(key), cw_rates[key]]);
			var curListArrayNew = [];

			curListArrayNew[0] = [];
			curListArrayNew[0]['id'] = 'usd';
			curListArrayNew[0]['text'] = 'USD';

			for(var i=0; i < curListArray.length; i++) {
				var coin = curListArray[i];
				var i2 = i + 1;
				curListArrayNew[i2] = [];
				curListArrayNew[i2]['id'] = coin[0];
				curListArrayNew[i2]['text'] = coin[0].toUpperCase();
			}

			// console.log(curListArrayNew)

			var cw_cmcConverter = curListArrayNew.concat(cw_cmc);
			// console.log(cw_cmcConverter)

			// Activate Select2 top dropdown
			myOptions = {
				ajax: {},
				jsonData: cw_cmcConverter,
				jsonMap: {id: "id", text: "text"},
				initialValue: initialValue,
				pageSize: 50,
				dataAdapter: jsonAdapter
			};
			jQuery(".selectcoin-conv-1").select2(myOptions);
			
			// Activate Select2 bottom dropdown
			initialValue = "usd";
			myOptions = {
				ajax: {},
				jsonData: cw_cmcConverter,
				jsonMap: {id: "id", text: "text"},
				initialValue: initialValue,
				pageSize: 50,
				dataAdapter: jsonAdapter
			};
			jQuery(".selectcoin-conv-2").select2(myOptions);

			// Initial values
			jQuery('#conv-input-1').val(1);

			jQuery('#conv-input-1').change(function() {
				currencyConverter('top');
			})

			jQuery('#conv-input-2').change(function() {
				currencyConverter('bottom');
			})

			jQuery('.selectcoin-conv-1').change(function() {
				currencyConverter('top');
			})

			jQuery('.selectcoin-conv-2').change(function() {
				currencyConverter('top');
			})

			jQuery(document).ready(function() {
				currencyConverter('top');
			});
		}

		function currencyConverter(type) {
			if (type == 'top') {
				var convertFromCoinId = jQuery('.selectcoin-conv-1').val();
				var convertToCoinId = jQuery('.selectcoin-conv-2').val();
				
				var convertFromCoinAmount = jQuery('#conv-input-1').val();
			}
			else if (type == 'bottom') {
				var convertFromCoinId = jQuery('.selectcoin-conv-2').val();
				var convertToCoinId = jQuery('.selectcoin-conv-1').val();
				
				var convertFromCoinAmount = jQuery('#conv-input-2').val();
			}

			if (convertFromCoinAmount == '') {
				jQuery('#conv-input-2').val('');
				jQuery('#conv-input-1').val('');
				return;
			}

			if (isNaN(convertFromCoinAmount)) {
				alert('Input field should be a numeric value.');
				return;
			}

			var convertFromCoinPrice = null;
			var convertToCoinPrice = null;

			for(var i=0; i < cw_cmc.length; i++) {
				var coin = cw_cmc[i];
				if (convertFromCoinId == 'usd') {
					convertFromCoinPrice = 1;
					break;
				}
				else if (isNaN(convertFromCoinId)) {
					convertFromCoinPrice = cw_rates[convertFromCoinId];
					break;
				}
				else if (coin['id'] == convertFromCoinId) {
					convertFromCoinPrice = coin['price_usd'];
					break;
				}
			}

			for(var i=0; i < cw_cmc.length; i++) {
				var coin = cw_cmc[i];
				if (convertToCoinId == 'usd') {
					convertToCoinPrice = 1;
					break;
				}
				else if (isNaN(convertToCoinId)) {
					convertToCoinPrice = cw_rates[convertToCoinId];
					break;
				}
				else if (coin['id'] == convertToCoinId) {
					convertToCoinPrice = coin['price_usd'];
					break;
				}
			}

			// if currency-currency conversion
			if (isNaN(convertToCoinId) && isNaN(convertFromCoinId)) {
				// console.log(convertFromCoinPrice, convertToCoinPrice);

				// get usd values
				var fromUsdRate = 1 / convertFromCoinPrice;
				var toUsdRate = 1 / convertToCoinPrice;
				
				// console.log("from", fromUsdRate, "to", toUsdRate);

				var amountUsd = convertFromCoinAmount * fromUsdRate;
				var result = parseFloat(amountUsd / toUsdRate);

				if (result >= 1) {
					result = result.toFixed(2);
				}
			}
			else {
				// if currency-cryptocurrency
				if (isNaN(convertFromCoinId)) {
					// console.log("THIS")
					// get usd values
					var fromUsdRate = 1 / convertFromCoinPrice;
					var toUsdRate = convertToCoinPrice;
					
					// console.log("from", fromUsdRate, "to", toUsdRate);

					var valueUsd = convertFromCoinAmount * fromUsdRate;
					// console.log(valueUsd)
					// console.log(convertToCoinPrice, valueUsd)
					// console.log(parseFloat(convertToCoinPrice / valueUsd))
					// console.log((valueUsd / toUsdRate) + '')
					var result = parseFloat(valueUsd / toUsdRate);
					if (result >= 1) {
						result = result.toFixed(2);
					}
				}
				// if vice-versa
				else if (isNaN(convertToCoinId)) {
					// console.log("THIS2");
					// get usd values
					var fromUsdRate = 1 / convertFromCoinPrice;
					var toUsdRate = 1 / convertToCoinPrice;
					
					// console.log("from", fromUsdRate, "to", toUsdRate);

					var valueUsd = fromUsdRate / convertFromCoinAmount;
					// console.log(valueUsd)
					var result = parseFloat(convertToCoinPrice / valueUsd);
					if (result >= 1) {
						result = result.toFixed(2);
					}
				}
				// if cryptocurrency conversion
				else {
					var result = parseFloat(convertFromCoinAmount * convertFromCoinPrice / convertToCoinPrice);
				}
			}
			if (typeof(result) == 'number') {
				if (result == 0) {
					result = "0.00";
				}
				else if (result > 10000000000) {
					result = result.toFixed(0);
				}
				else if (result > 1000000000) {
					result = result.toFixed(2);
				}
				else if (result > 100000000) {
					result = result.toFixed(3);
				}
				else if (result > 10000000) {
					result = result.toFixed(4);
				}
				else {
					if (result < 0.000000001) {
						result = (result).toFixed(12);
					}
					else if (result < 0.0000001) {
						result = (result).toFixed(11);
					}
					else if (result < 0.0000001) {
						result = (result).toFixed(10);
					}
					else if (result < 0.000001) {
						result = (result).toFixed(9);
					}
					else if (result < 0.00001) {
						result = (result).toFixed(8);
					}
					else if (result < 0.0001) {
						result = (result).toFixed(7);
					}
					else {
						result = (result).toFixed(6);
					}
				}
			}
			
			jQuery('#ajax_loader_converter').hide();
			jQuery('#converter-content').show();

			if (type == 'top') {
				jQuery('#conv-input-2').val(result);
			}
			else {
				jQuery('#conv-input-1').val(result);
			}
		}

		// currencyConverterPrep()
	</script>

	<script>
		// Select2 autofocus input field on open
		jQuery(document).on('select2:open', () => {
			document.querySelector('.select2-search__field').focus();
		});
	</script>

	<script>
		function cwInit() {
			currencyConverterPrep();
			openPortfolio();
			load_watchlist(watchlist);

			jQuery(document).ready(function() {
				if (typeof(initialValue) == 'undefined') {
				initialValue = "1";
				}
				var myOptions = {
					ajax: {},
					jsonData: cw_cmc,
					jsonMap: {id: "id", text: "text"},
					initialValue: initialValue,
					pageSize: 50,
					dataAdapter: jsonAdapter
				};
				jQuery(".selectcoin").select2(myOptions);
			});

			jQuery(document).ready(function () {
				showprice();
			});
		}

		// INIT
		if (cw_cmc) {
			cwInit();
		}
		else {
			updateRetry();
		}
		function updateRetry() {
			setTimeout(() => {
				if (cw_cmc) {
					cwInit();
				}
				else {
					console.log("Trying again...")
					updateRetry();
				}
			}, 100);
		}
	</script>

</html>
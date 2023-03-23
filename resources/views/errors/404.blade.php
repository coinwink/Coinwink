<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8"> 
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

	<title>404 Error</title>

	<!-- Favicon -->
	<link rel="apple-touch-icon" sizes="180x180" href="https://coinwink.com/img/favicon/apple-touch-icon.png?v=2bBgz68qL">
	<link rel="icon" type="image/png" sizes="32x32" href="https://coinwink.com/img/favicon/favicon-32x32.png?v=2bBgz68qL">
	<link rel="icon" type="image/png" sizes="16x16" href="https://coinwink.com/img/favicon/favicon-16x16.png?v=2bBgz68qL">
	<link rel="shortcut icon" href="https://coinwink.com/img/favicon/favicon.ico?v=2bBgz68qL">
	<meta name="apple-mobile-web-app-title" content="Coinwink">
	<meta name="application-name" content="Coinwink">

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-57930548-9"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-57930548-9');
    </script>

	<style>
		html, body {
			height: 100%;
			margin: 0;
		}
		body {
			background-color: #4f585b;
			font-family: montserrat, arial, verdana, sans-serif;
			font-size: 12px;
			color: #222;
		}
		* {
			box-sizing: border-box;
		}
		h2 {
			font-size: 14px;
		}
		.text-header {
			font-size: 12px;
			text-transform: uppercase;
			color: #313131;
			margin-bottom: 20px;
			padding-top: 19px;
			font-weight: 400 !important;
		}
		a {
			color: #fff !important;
		}
		a.whitelink {
			color: #fff !important;
			text-decoration: none;
		}
		.container {
			max-width: 330px;
			margin: 0 auto;
			text-align: center;
			position: relative;
			margin-top: 10px;
			margin-bottom: 20px;
			background: #fcfbee;
			background: -webkit-linear-gradient(#fcfbee, white);
			background: -o-linear-gradient(#fcfbee, white);
			background: -moz-linear-gradient(#fcfbee, white);
			background: linear-gradient(#fcfbee, white);
			border: 0;
			border-top-left-radius: 20px;
			border-top-right-radius: 20px;
			border-bottom-left-radius: 3px;
			border-bottom-right-radius: 3px;
			box-shadow: 0 0 12px 0 rgba(0, 0, 0, 0.3);
			padding-bottom: 35px;
			position: relative;
		}
		.container header {
			background: #6a6a6a;
			color: #fff;
			height: 50px;
			margin-bottom: 30px;
			border-top-left-radius: 3px;
			border-top-right-radius: 3px;
		}
		.content {
			max-width: 270px;
			margin: 0 auto;
			margin-bottom: 7px;
		}
		#logo {
			margin: 0 auto;
			text-align: center;
			margin-top: 9px;
			margin-bottom: 9px;
			height: 44px;
		}
		#txtlogo {
			text-align: center;
			font-size: 13px;
			color: #fff;
			margin: 0 auto;
			margin-bottom: 27px;
			margin-top: 0;
		}
		#txtlogo > a {
			text-decoration: none !important;
		}
		@media screen and (max-width: 680px) {
			#logo {
			margin-top: 8px;
			}
			.container {
			margin-top: 28px;
			}
		}
		.icon-hovered {
			pointer-events: bounding-box;
			transition: fill 0.2s ease;
			fill: #c1c1c1;
		}
		.text-header {
			color: #fff;
		}
		body::-webkit-scrollbar {
			width: 12px;
		}
		body::-webkit-scrollbar-track {
			background-color: #495254;
			box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
		}
		body::-webkit-scrollbar-thumb {
			background-color: #7d7d7d;
		}
		#checkmark {
			fill: #424242;
		}
		#radiomark {
			fill: #555;
		}
		.text-footer {
			color: #bfbfbf;
		}
		.text-footer-links {
			color: #bfbfbf;
		}
		@media only screen and (min-width: 801px) {
			a.whitelink:hover {
			color: #fff !important;
			text-decoration: underline;
			}
			#icon-home:hover path {
			fill: #eee;
			}
			.icon-portfolio-outer:hover .icon-hovered {
			fill: #eee;
			}
			body::-webkit-scrollbar-thumb:hover {
			background-color: #8e8e8e;
			}
		}
		a.whitelink:active {
			color: #fff !important;
			text-decoration: underline;
		}
		#icon-home:active path {
			fill: #eee;
		}
		.icon-portfolio-outer:active .icon-hovered {
			fill: #eee;
		}
		body::-webkit-scrollbar-thumb:active {
			background-color: #8e8e8e;
		}
	</style>
</head>
<body>
	
	<div style="position:relative;max-width:800px;margin:0 auto;" class="outer-buttons">

		<div style="position:absolute;top:18px;right:15px;width:33px;">
			<a href="/" style="cursor:pointer;">
				<div title="Home" style="padding:3px;margin:0 auto;" class="icon-portfolio-outer">
					<svg id="icon-home" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 588.83 524.89">
						<path class="icon-hovered" d="M593.57-933.69L314.45-1138.05a15.28,15.28,0,0,0-8.65-2.95H305a15.27,15.27,0,0,0-8.65,2.95L17.26-933.69A15.3,15.3,0,0,0,14-912.31,15.3,15.3,0,0,0,35.34-909l270.08-197.75L575.49-909a15.24,15.24,0,0,0,9,3,15.28,15.28,0,0,0,12.36-6.26,15.3,15.3,0,0,0-3.31-21.38h0Z" transform="translate(-11 1141)"/><path class="icon-hovered" d="M519.61-905.54a15.3,15.3,0,0,0-15.3,15.3v243.52H381.92V-779.64a76.59,76.59,0,0,0-76.5-76.5,76.59,76.59,0,0,0-76.5,76.5v132.93H106.52V-890.24a15.3,15.3,0,0,0-15.3-15.3,15.3,15.3,0,0,0-15.3,15.3v258.82a15.3,15.3,0,0,0,15.3,15.3h153a15.29,15.29,0,0,0,15.24-14.11,11.61,11.61,0,0,0,.06-1.19V-779.64a46,46,0,0,1,45.9-45.9,46,46,0,0,1,45.9,45.9v148.23a11.17,11.17,0,0,0,.06,1.18,15.29,15.29,0,0,0,15.24,14.12h153a15.3,15.3,0,0,0,15.3-15.3V-890.24a15.3,15.3,0,0,0-15.3-15.3h0Z" transform="translate(-11 1141)"/>
					</svg>
				</div>
			</a>
		</div>
		
	</div>

	<!-- Header - Logo -->
	<div style="text-align: center;">
		<div style="height:27px;"></div>
		<div id="logo" style="width:44px;-webkit-filter: drop-shadow(0 1px 1px rgba(0,0,0,.7));filter: drop-shadow(0 1px 1px rgba(0,0,0,.7));">
			<a href="/">
				<img src="https://coinwink.com/img/coinwink-crypto-alerts-logo.png" width="44" alt="Coinwink Crypto Alerts">

			</a>
		</div>
		<div id="txtlogo"><a href="/">Coinwink</a></div>
	</div>


	<div class="container" id="account">

		<header>
		<h2 class="text-header" style="color:white;">404 ERROR</h2>
		</header>

		<div class="content">

			<div style="font-size:18px;line-height:140%;">
			
				<div style="height:21px;"></div>

				This page doesn't exist.

				<div style="height:7px;"></div>

			</div>
		</div>
	</div>

	<div style="height:10px;"></div>

	<!-- FOOTER -->
	<footer style="text-align: center;">
		<div style="margin:0 auto;padding-top:4px;padding-bottom:10px;" class="text-footer-links">
		<a href="/" class="whitelink">Home</a>
		&nbsp;|&nbsp;
		<a href="/manage-alerts" class="whitelink">Alerts</a>
		&nbsp;|&nbsp;
		<a href="/portfolio" class="whitelink">Portfolio</a>
		&nbsp;|&nbsp;
		<a href="/watchlist" class="whitelink">Watchlist</a>
		</div>
		<div style="margin-top:18px;font-size:10px;" class="text-footer-links">
		<a href="/about" class="whitelink">About</a>&nbsp;|&nbsp;
		<a href="/pricing" class="whitelink">Pricing</a>&nbsp;|&nbsp;
		<a href="/terms" class="whitelink">Terms</a>&nbsp;|&nbsp;
		<a href="/privacy" class="whitelink">Privacy</a>&nbsp;|&nbsp;
		<a href="/press" class="whitelink">Press</a>&nbsp;|&nbsp;
		<a href="https://coinwink.com/blog/" class="whitelink" target="_blank">Blog</a>&nbsp;|&nbsp;
		<a href="/contacts" class="whitelink">Contacts</a>
		</div>
		<div style="height:15px;"></div>
		<span style="line-height:150%;font-size:10px;" class="text-footer">

		Crypto Price Alerts, Watchlist and Portfolio Tracking App
		<br>
		Privacy-Focused, Based on CoinMarketCap
		</span>
		<div style="height:40px;"></div>
	</footer>

</body>
</html>
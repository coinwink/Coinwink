<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8"> 
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<title>{{ $meta['title'] }}</title>
	<!-- <meta name="description" content=" $meta['description'] "/> -->

	<meta property="og:title" content="{{ $meta['soc_title'] }}">
	<meta property="og:description" content="{{ $meta['soc_description'] }}">
	<meta property="og:site_name" content="Coinwink">
	<meta property="og:image" content="{{env('APP_URL')}}/img/{{ $meta['image'] }}">
	<meta property="og:image:width" content="{{ $meta['image_w'] }}" />
	<meta property="og:image:height" content="{{ $meta['image_h'] }}" />
	<meta property="og:url" content="{{Request::url()}}">

	<meta name="twitter:title" content="{{ $meta['soc_title'] }}">
	<meta name="twitter:description" content="{{ $meta['soc_description'] }}">
	<meta name="twitter:card" content="summary_large_image">
	<meta name="twitter:site" content="@coinwink">
	<meta name="twitter:creator" content="@coinwink">
	<meta name="twitter:image" content="{{env('APP_URL')}}/img/{{ $meta['image'] }}">
	<meta name="twitter:image:alt" content="{{ $meta['soc_title'] }}">
	<meta name="twitter:domain" content="{{env('APP_URL')}}">
	
	<!-- Favicon -->
	<link rel="apple-touch-icon" sizes="180x180" href="{{env('APP_URL')}}/img/favicon/apple-touch-icon.png?v=2bBgz68qL">
	<link rel="icon" type="image/png" sizes="32x32" href="{{env('APP_URL')}}/img/favicon/favicon-32x32.png?v=2bBgz68qL">
	<link rel="icon" type="image/png" sizes="16x16" href="{{env('APP_URL')}}/img/favicon/favicon-16x16.png?v=2bBgz68qL">
	<link rel="shortcut icon" href="{{env('APP_URL')}}/img/favicon/favicon.ico?v=2bBgz68qL">
	<meta name="apple-mobile-web-app-title" content="Coinwink">
	<meta name="application-name" content="Coinwink">

	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">

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
			max-width: 290px;
			margin: 0 auto;
			margin-bottom: 7px;
			text-align: left;
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

		/* PRIVACY */

		ul {
			padding-left: 15px;
		}
		li {
			margin-bottom: 10px;
		}
		h3 {
			font-size: 16px;
		}
		h4 {
			font-size: 14px;
		}

		/*! CSS Used from: http://localhost:3000/css/app.css?id=84b0fed40a9110238658b6045fd16360 */
		*{box-sizing:border-box;}
		p{margin-bottom:20px;}
		h3{margin-top:35px;margin-bottom:18px;}
		h4{margin-top:30px;margin-bottom:15px;}
		.blacklink{color:black!important;text-decoration:underline;cursor:pointer;}
		a{color:#ffffff!important;}
		a.blacklink{color:black!important;text-decoration:underline;}
		.noselect{-webkit-touch-callout:none;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;}
		.reverse{unicode-bidi:bidi-override;direction:rtl;}
		/*! CSS Used from: Embedded */
		ul[data-v-5652380f]{padding-left:15px;}
		li[data-v-5652380f]{margin-bottom:10px;}
		h3[data-v-5652380f]{font-size:16px;}
		h4[data-v-5652380f]{font-size:14px;}
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


		@if (Route::currentRouteName() == 'Privacy')
		<header>
			<h2 class="text-header" style="color:white;">Privacy</h2>
		</header>

		<div class="content">

			<h3>Privacy Policy</h3>

			<p>Last updated: September 14, 2022</p>

			<p>Privacy Policy is effective immediately after being posted on this page and might be changed periodically.</p>

			<p>This Privacy Policy ("Policy") sets out the terms and conditions regarding the collection, use and disclosure of Personal Data received from users of the cryptocurrency price alerts, watchlist and portfolio tracking web-app (the "App").</p> 

			<h4>The controller of Personal Data</h4>

			<p><i>UAB Pictoproof</i> is the data controller in respect of all Personal Data collected and used by Coinwink ("We" or "Us").</p>

			<p>We are registered at Olandu Street 53-36, Vilnius, Lithuania. You can contact us by e-mail <span class="reverse noselect">moc.kniwnioc@ofni</span>.</p>

			<h4>Personal Data collected and used by us</h4>

			<p>Personal Data is information that identifies an individual. We collect and use Personal Data from App users, such as their e-mail address and phone number ("User Data"), cookies and information that your browser sends whenever you visit our App ("Log Data").</p>

			<h4>Purpose and legal basis for processing Personal Data</h4>

			<p>We process Personal Data for the following purposes and under the following legal grounds under the General Data Protection Regulation ("GDPR"):</p>

			<ul>
				<li>
					<b>to verify the user for registration and accessing Coinwink’s account</b> – we use User Data to verify user’s identity and to manage your account registration. For the App to be more user-friendly, we also collect the last e-mail address and phone number used with the account, and later pre-fill this information in the New Alert creation form. The processing of this data is necessary to fulfil the terms regulating the use of the App (Article 6 (1) (b) of the GDPR);
				</li>

				<li>
					<b>to alert you about your tracked crypto holdings and favourites from different blockchains</b> – in order to properly provide these services, we use User Data. In these activities we rely on your consent (Article) 6 (1) (a) of the GDPR). Please note that our cryptocurrency exchange rate alerts sent to you as email or SMS messages are considered to be <b>service messages</b> and do not contain any direct marketing material;
				</li>
				<li>
					<b>to provide user behaviour insights and statistics for our App</b> – we collect Log Data, that may include information such as your device Internet Protocol ("IP") address, browser type, browser version, the pages of our App that you visit, the time and date of your visit, the time spent on those pages and other statistics. This information is used in order to provide user behaviour insights and statistics for our App. In these activities we rely on our legitimate interest to improve our services and user experience (Article 6 (1) (f) of the GDPR);
				</li>
				<li>
					<b>to record logs of communication alerts delivered to the user</b> – starting from 2019, we record logs of all delivered e-mail, SMS and Portfolio alerts. Every user can view his or her alerts history under "Manage Alerts" section. In these activities we rely on our legitimate interest to provide the best user experience (Article) 6 (1) (f) of the GDPR);
				</li>
				<li>
					<b>for processing your payment data</b> – Stripe is directly responsible for processing your payment data. When processing your payment, we never observe your credit or debit card details, as they are only collected by the Stripe service. For more details, please refer to the <a href="https://stripe.com/privacy" class="blacklink" target="_blank">Stripe privacy policy</a>. The processing of this data is necessary for the performance of a contract (Article 6 (1) (b) of the GDPR).
				</li>
			</ul>

			<h4>Data retention period</h4>

			<p>You can permanently delete your account anytime. Once you delete your account, all the resources and data associated with you as a user are automatically deleted from our database. <b>Financial records are retained for 10 years</b> after the purchase transaction.</p>

			<h4>Security</h4>

			<p>Coinwink is a privacy-focused service. When processing and storing your personal data, we implement organisational and technical measures to ensure that personal data is protected against <b>accidental or unlawful destruction</b> (e.g., backups on a regular schedule), <b>alteration</b>, <b>disclosure</b>, and any other <b>unlawful processing</b>. Nevertheless, no method of transmission over the Internet, or method of electronic storage, is 100% secure. While we strive to use commercially acceptable means to protect your Personal Data, we cannot guarantee its absolute security. Therefore, we recommend you to process your Personal Data responsibly (use strong passwords so that access to data would be restricted, be wary of suspicious e-mails and messages, restrict the access to your Personal Data, etc.).</p>

			<h4>Data disclosure</h4>

			<p>We never share Personal Data of the user with third parties. As it was mentioned before, for processing your payment, your personal and payment details are processed by Stripe. For more details, please refer to the <a href="https://stripe.com/privacy" class="blacklink" target="_blank">Stripe privacy policy</a>.</p>

			<p>Where necessary (e.g., when we are required to comply with applicable laws), we may transfer and/or otherwise disclose your personal data to the law enforcement authorities, courts and other authorised governmental bodies.</p>

			<p>When you communicate with us via social networks, you should inquire about their applicable data protection terms and conditions and read their privacy policy. All personal data you provide to us via social networks is controlled and managed by that particular social network:</p>

			<ul>
				<li>
					Twitter, Inc. (USA). Please find the respective data protection policy <a href="https://twitter.com/en/privacy" class="blacklink" target="_blank">here</a>.
				</li>
				<li>
					GitHub, Inc. (USA). Please find the respective data protection policy <a href="https://docs.github.com/en/site-policy/privacy-policies/github-privacy-statement" class="blacklink" target="_blank">here</a>.
				</li>
			</ul>

			<p>If at some point Coinwink, as a business, is sold, the transfer of the users' Personal Data to the new business owner will be done under similar "privacy-focused" conditions.</p>

			<h4>Rights of the data subject</h4>

			<p>You have the right to <b>request access</b> to your personal data and to request <b>rectification</b>, <b>erasure</b> or <b>restriction</b> of the processing of personal data. If you have given us your explicit consent to the processing of your data, you can <b>withdraw</b> it by using the appropriate functionalities of the App.</p>

			<p>Where the processing of your data is based on our <b>legitimate interest</b>, you have the right <b>to object</b> to the processing of your data.</p>

			<p><b>You can exercise rights over your data by reaching out to <span class="reverse noselect">moc.kniwnioc@ofni</span>.</b></p>

			<p>If you believe that CoinWink is unlawfully processing your personal data or is not implementing your rights, you have the right to file a claim before the responsible Data Protection regulatory authority, in particular, before the <a href="https://vdai.lrv.lt/en/" class="blacklink" target="_blank">Lithuanian State Data Protection Inspectorate</a> (L. Sapiegos street 17, 10312 Vilnius, ada@ada.lt).</p>

			<h4>Cookies</h4>

			<p>To ensure the proper functioning of the website, we use cookies to collect information.</p>

			<p><i>What are cookies?</i></p>

			<p>Cookies are files with a small amount of data, which may include an anonymous unique identifier. Cookies are sent to your browser from a web-app and stored on your computer's hard drive. </p>

			<p><i>Why are cookies used?</i></p>

			<p>Cookies help the website to remember your actions and settings for a certain period (e.g., language preference, time, duration of visit, etc.) so that you do not have to re-enter them each time you visit the website or browse its individual pages. Cookies may also be used to collect anonymous browsing statistics.</p>

			<p><i>Can I refuse the cookie?</i></p>

			<p>Please note that deleting and blocking Cookies may have an impact on your user experience. Nevertheless, you can instruct your browser to refuse all cookies or to indicate when a cookie is being sent.</p>

			<p><i>What cookies does Coinwink use?</i></p>

			<p>
				We use Necessary cookies and Statistical cookies:
				<ul>
					<li><b>Necessary cookies</b> help a website function properly by enabling basic functions (i.e., page navigation and access to secure areas of the website).</li>
					<li><b>Statistical cookies</b> help a website owner to understand how visitors interact with websites by collecting and reporting information anonymously.</li>
				</ul>
			</p>

			<p>
				These cookies are used on this website:
				
				<ul>
					<li>
						<b>__cf_bm</b> (Necessary, 1 day)
						<br>
						To manage incoming traffic that matches criteria associated with bots
					</li>
					<li>
						<b>XSRF-TOKEN</b> (Necessary, 1 day)
						<br>
						To ensure visitors‘ browsing security by preventing cross-site request forgery
					</li>
					<li>
						<b>_ga</b> (Statistical, 2 years)
						<br>
						<b>_gid</b> (Statistical, 1 day)
						<br>
						<b>_gad</b> (Statistical, 1 day)
						<br>
						Used by Google Analytics to evaluate your use of our App. These cookies are used to collect information and report App usage statistics without personally identifying individual visitors.
						<br>
						<b><a href="https://policies.google.com/technologies/partner-sites" class="blacklink" target="_blank">Here</a> you can find more information about Google Analytics cookies.</b>
					</li>
				</ul>
			</p>

			<h4>Changes to this Privacy Policy</h4>

			<p>This Policy is effective as of 14, September 2022 and will remain in effect except with respect to any changes in its provisions in the future, which will be in effect immediately after being posted on this page.</p>

			<p>We reserve the right to update or change our Policy at any time and you should check this Policy periodically. Your continued use of the service after we post any modifications to the Policy on this page will constitute your acknowledgment of the modifications.</p>

			<p>If you have any questions regarding this Policy, please contact us via <span class="reverse noselect">moc.kniwnioc@ofni</span>.</p>
		</div>

		@elseif (Route::currentRouteName() == 'Terms')
		<header>
			<h2 class="text-header" style="color:white;">Terms</h2>
		</header>
		
		<div class="content">
			
            <h3>Terms &amp; Conditions</h3>

            <p>Thank you for visiting our website https://coinwink.com (hereinafter – “<b>Website</b>”).</p>

            <p>Before using any feature of the Website or services accessible via the Website, please take your time to thoroughly read and understand these Terms & Conditions (hereinafter – “<b>Terms</b>”), as these Terms shall govern the entire relationship between you (hereinafter – “<b>User</b>”, “<b>You</b>” or “<b>Customer</b>”) and us.</p>

            <b><p>Please do not proceed with using the Website and our services or making any purchases on the Website if You have not thoroughly read and understood the provisions of these Terms, as whenever You’ll be using our services and/or purchasing subscription from us these Terms shall be considered as a legally binding contract between You and us, so it is important that you are fully aware of all the conditions set out herein.</p></b>

            <h4>1. ABOUT US</h4>

            <p>1.1. This Website and any services labelled under the brand name Coinwink is operated by Pictoproof, UAB, which is a limited liability company incorporated under the laws of Lithuania (company reg. No. 305968420) with a registered address at Olandų g. 53-36, Vilnius, Lithuania, European Union (hereinafter – “<b>Company</b>”, “<b>Us</b>”, “<b>We</b>” or “<b>Coinwink</b>”).</p>

            <p>1.2. Coinwink is an online service provider that provides Users with cryptocurrency price alerts, watchlist and portfolio for over 3600 crypto coins and tokens that monitors cryptocurrency prices 24/7, alerts Users about the changes of cryptocurrency prices when defined conditions are met and offers other additional services for Users who are interested in getting latest cryptocurrency exchange rate information. We provide no warranties that our services will be suitable and useful for everyone or that they will be error-free, thus before using the Website and/or making any subscriptions on the Website, please evaluate individually if Coinwink services are suitable for you personally.</p>

            <p>1.3. Coinwink is not a financial institution and does not accept or transmit cryptocurrency. Coinwink does not provide investment or trade advice and does not execute, clear or settle cryptocurrency transactions. Coinwink’s only intended purpose is to provide general information about cryptocurrency exchange rates, however we do not advise you or anyone to rely on our information when making a financial or investment decision.</p>

            <p>1.4. For cryptocurrency price information, Coinwink uses Coinmarketcap.com professional API where the price of a particular coin is determined averagely, based on different exchanges where that coin is traded. Coinwink shall never be held responsible for the accuracy and correctness of cryptocurrency price because there may be times where data is not up to date due to possible malfunction of the Coinmarketcap.com API, other technical issues beyond Coinwink control or other reasons. Users are strongly advised to always double-check current cryptocurrency exchange rates from other sources too.</p>

            <p>1.5. If you have any questions, you can contact us by sending an email at <span class="reverse noselect">moc.kniwnioc@ofni</span>.</p>
            
            <h4>2. ABOUT YOU</h4>

            <p>2.1. If you want to use the Website, you have to meet the following requirements:</p>
            <ul>
                <li>
                    (a) You are at least 18 years of age. The Website and any of the services available on the Website are designed and intended to be used by adults only.
                </li>
                <li>
                    (b) You have read these Terms and agree to be bound by them.
                </li>
                <li>
                    (c) You must provide us with your correct contact information when requested.
                </li>    
                <li>
                    (d) If You are using paid Services of the Website, You must use credit card that belongs to you or the owner of the credit card has authorized you to use the card and such authorizations were issued in a form which is required in your place of residence.
                </li>
            </ul>

            <p>2.2. In order to use Our services, you may be required to provide your personal details such as e-mail address and telephone number. Please be noted that we will be able to provide you with services only if you will give us your correct and accurate information.</p>

            <b><p>2.3. By Agreeing to these Terms, you confirm that the services shall be considered as provided to You from the moment You are provided with the access to the Website’s services and, if using paid services, since Your first log in into your Website’s user area after setting up your subscription. You can cancel your subscription any time and if you do so, we will not renew your subscription and will stop charging you for consecutive periods.</p></b>

            <p>2.4. You acknowledge that from time to time, the Website, its services or separate functionalities of services may be inaccessible, not fit for purpose or inoperable for any reason, including, but not limited to hardware or software malfunctions, periodic maintenance procedures or repairs, causes beyond Our reasonable control and/or any other Our non-performance or default.</p>

            <p>2.5. Please be noted that for delivering SMS alerts to You, Coinwink uses Twilio SMS messaging API which provides a worldwide coverage for SMS delivery and some countries have specific restrictions for inbound SMS messages which could be the reason you are not able to receive SMS alerts (messages) from Us. In such case it is your duty to inform Us about You not receiving SMS alerts and We will do our best to resolve the matter as soon as possible.</p>

            <b><p>2.6. Please be noted that this Website, our services and any information that you receive from us may be used for your own needs only. You may not use any of the Website’s contents or our Services for any type of resale, redistribution, lease or any other type of commercial reuse.</p></b>

            <b><p>2.7. Coinwink grants You a limited, non-exclusive, non-transferable license, subject to these Terms, to access and use the Coinwink Services and related content, materials, and information solely for the purposes of accessing and receiving Coinwink Services.</p></b>

            <p>2.8. You may not use the Website or our Services for any illegal or unauthorized purpose nor may you, in the use of the Service, violate any laws. All contents of the Website and the contents of all materials received from us (including graphic designs and other contents) and the relevant parts belong to the ownership of the Company and are protected by the copyright laws. Any use of any copyrights for purposes other than the personal use of Our Services, without the Company’s license constitutes a breach of copyright.</p>

            <h4>3. OUR SERVICES PRICING AND PAYMENTS</h4>
            
            <p>3.1. Coinwink is an online service that provides Users with the ability to: (1) receive e-mail and/or SMS alerts when the exchange rate of your chosen cryptocurrency falls and/or rises to the value set by You; (2) create Portfolio in which you can keep track of your crypto holdings in a single structured dashboard, convert between different currencies, calculate return on investment, make notes for individual coins and other features; (3) create crypto Watchlist where you can create your own list of cryptocurrencies to follow (hereinafter – “Services”). The contents of the Website and the Services may be changed from time to time, as we keep updating and improving them.</p>

            <p>3.2. Please be noted that all our Services are provided in digital form only. Accordingly, our Services shall be considered as provided to You on the moment when Services becomes available to You, i.e. if you use paid Services, then such services shall become available to you from the moment you enter Website’s user area.</p>

            <b><p>3.3. All Services provided by Us are for educational and informational purposes only and shall not be considered nor as investment advice nor endorsement or recommendation to buy trade or invest in cryptocurrency.</p></b>

            <p>
                3.4. We have the following types of Service plans currently available via our Website:
                <ul>
                    <li>(a) Free of charge and without registration: 5 (five) e-mail alerts if using Website without creating account in the Website;</li>
                    <li>(b) Free of charge and with registration: 10 (ten) e-mail alerts, 10 (ten) coins to add in Portfolio and 10 (ten) coins to add in Watchlist if creating account in the Website;</li>
                    <li>(c) Paid subscription: Unlimited e-mail alerts, unlimited coins to add in Portfolio and Watchlist, 100 (one hundred) SMS alerts per month if creating account (with registration) in the Website (“<b>Premium Service</b>”).</li>
                </ul>
            </p>

            <p>3.5. Our Service plan described in Clause 3.4. (a) above is free and You can use this Service plan by just entering your e-mail and preferred alert parameters, while the registration on the Website is not required. This Service plan expires after 5 (five) e-mail alerts are sent to You.</p>

            <p>3.6. Our Service plan described in Clause 3.4. (b) above is also free and You can use this Service plan after making registration on the Website. By choosing this Service plan you can get 10 (ten) e-mail alerts and 10 (ten) coins to add in your Portfolio and Watchlist.</p>

            <p>3.7. Our Premium Service plan described in Clause 3.4. (c) may be acquired by a monthly subscription for only $12,00. Immediately after receiving your payment for a monthly subscription plan, we will provide You with an unlimited e-mail alerts, 100 (one hundred) SMS alerts a month as well as possibility to add unlimited coins in your Portfolio and Watchlist.</p>

            <p>3.8. If You subscribed for Premium Service plan and have reached the limit of available SMS alerts per month (which is 100), You can purchase additional SMS alerts for an ongoing month. The fee for every additional 100 (one hundred) SMS alerts is $12,00. Please be noted that additionally purchased SMS alerts are not carried over to the next month.</p>

            <p>3.9. The fee of monthly subscription of Premium Service plan are inclusive of all tax. However, the fee may be subject to change depending on applicable VAT tax rates and changes of such rates in the place of your residence from where you are making the purchase.</p>

            <b><p>3.10. Please be noted that we will never apply any conversion rates or charges dependable on your chosen payment method. However, some banks apply conversion rates for outgoing payments and international transfers – thus, we are not responsible for any bank fees or conversion rates that your bank would apply for any payment made to Us. If you notice any differences between Premium Service plan fee on our Website or purchase receipt and your bank account statement, please refer to your bank for a detailed explanation of the additional charges.</p></b>

            <p>3.11. We accept payments by credit card only. We will not accept checks, cash or other means for payment.</p>

            <h4>4. CANCELING YOUR SUBSCRIPTION</h4>

            <p>4.1. If you want to cancel your Premium Services plan subscription, you can do that any time by logging into your account on the Website and clicking ‘Cancel Subscription’ button. Your cancelation shall become effective upon expiration of the term of the Premium Service plan for which you have subscribed and already paid, and you will not be charged for consecutive periods.</p>

            <p>4.2. If you terminate your account from the Website, it automatically cancels your subscription, and you will not be charged for consecutive periods. Please be noted that in such case you will not be able to use Premium Services until expiration of the term for which you have subscribed and already paid, because Premium Services cannot be provided without having an account on the Website.</p>

            <p>4.3. Please be noted that if you cancel your subscription or terminate your account, we will not issue a refund for the past periods during which you have used or had the ability to use the Premium Services plan. For more information about refunds please see the sections below (respectively Section 5 or Section 6 according where your residence is).</p>

            <h4>5. REFUND POLICY</h4>

            <p>5.1. Since all Services, including Premium Services, are in digital form only, we accept refund requests only in an extremely limited capacity. We will not issue any refunds if after purchasing Premium Service plan you would start using any features of our Services while being logged to your account. The use of our Services means the use of any feature available for your subscription plan regardless of whether that feature is available only with a Premium Service plan or not. For example, if you purchase Premium Service plan and then create any alert or add any coin to your Portfolio or Watchlist while being logged in to your Website account, it shall be considered as the usage of our Services.</p>

            <p>5.2. If you have purchased a Premium Service plan on the Website but changed your mind before starting to use any features of our Services, we will issue a refund if you request it within 14 days after you made your purchase.</p> 

            <p>5.3. Please be noted that you will be provided with access to Premium services immediately after we receive your payment for Premium service plan subscription. IF YOU CHANGE YOUR MIND AFTER PURCHASING PREMIUM SERVICE PLAN – PLEASE DO NOT USE ANY FEATURE OF OUR SERVICES, AS FROM THE MOMENT YOU USE ANY FEATURE OF OUR SERVICES, IT SHALL BE CONSIDERED THAT THE SERVICES ARE FULLY AND DULY AVAILABLE AND PROVIDED TO YOU. This means that if you did not use any feature of our Services and you want a refund, you should cancel your subscription on the Website by clicking ‘Cancel Subscription’ button and then request for a refund by contacting our customer support by email <span class="reverse noselect">moc.kniwnioc@ofni</span> within 14 days after you made your purchase.</p>

            <b><p>5.4. Please be noted that our cancelations and refunds policy is prepared in accordance to the provisions of the Directive 2011/83/EU of the European Parliament and of the Council of 25 October 2011 on the protection of consumers, which specifies that digital services (content) cannot be cancelled and returned from the moment that such content is delivered to the consumer. Accordingly, Coinwink shall have no obligation to make any refunds for any cancelations of Services which were made available to you and you started to use it before canceling subscription.</p></b>

            <b><p>5.5. We are only able to issue a refund through the same payment method that you have used for paying us for the Services.</p></b>

            <h4>6. PERSONAL DATA AND CONTACTING</h4>

            <p>6.1. To protect your personal information, we take reasonable precautions and follow industry best practices to make sure it is not inappropriately lost, misused, accessed, disclosed, altered or destroyed.</p>

            <p>6.2. Under no circumstance we will not reveal or share your personal information to any third party.</p>

            <p>6.3. Please be noted that we may contact you via phone or email if we need to confirm any details of your subscription or if your subscription request was not processed successfully due to technical matters. If your subscription was not successful due to payment processing errors or other reasons we might send you a text message or email with a reminder to carry out necessary actions or we might contact you by phone if you have provided your phone number to us.</p>

            <p>6.4. We ensure you that all personal data shall be collected and processed in accordance to all applicable laws. To find out more about how we use and process personal data please read our <b>Privacy Policy</b>.</p> 

            <h4>7. RULES OF CONDUCT</h4>

            <p>7.1. You may not use our Services and/or the Website for any illegal or unauthorized purpose nor may you, in the use of the Website, violate any laws. All contents of the Website and the contents of all materials received or made available from us and the relevant parts of the Website belong to the ownership of Coinwink and are protected by the copyright laws. Any use of any copyrights for purposes other than personal use, without our license, constitutes a breach of our copyright or other intellectual property right.</p>

            <p>
                7.2. We have the right, but not obligation, to investigate any illegal and/or unauthorized use of the Website and take appropriate legal action, including without limitation, civil, and injunctive relief if we have a reason to believe that you are violating these Terms or applicable laws. While using the Website, you must:

                <ul>
                    <li>(a) Not use the Website or any of its contents for any illegal purpose, or in violation of any local, state, national, or international law;</li>
                    <li>(b) Not violate or encourage others to violate the rights of third parties, including intellectual property rights;</li>
                    <li>(c) Comply with all policies posted on the Website;</li>
                    <li>(d) Not transfer, legally or factually, your registered account to any other person without our written consent;</li>
                    <li>(e) Provide honest and accurate information to us;</li>
                    <li>(f) Not use the Website or any of its contents for any commercial purpose, including distribution of any advertising or solicitation;</li>
                    <li>(g) Not reformat, format, or mirror any portion of any web page of the Website;</li>
                    <li>(h) Not create any links or redirections to the Website through other websites or emails, without prior written consent given by us;</li>
                    <li>(i) Not make any attempts to interfere with the proper functioning of the Website or the use and enjoyment of the Website by other users;</li>
                    <li>(j) Not commercially resell, redistribute or transfer any Services that you received from us;</li>
                    <li>(k) Not interfere in any way with security-related features of the Website;</li>
                    <li>(l) Not access, monitor or copy any content or information of the Website using any robot, spider, scraper, or other automated means or any manual process for any purpose without our express written permission;</li>
                    <li>(m) Not claim false affiliations, access the accounts of other users without permission, or falsify your identity or any information about you;</li>
                    <li>(n) Not perform any other activity or action which would be incompliant with these Terms or applicable laws.</li>
                </ul>
            </p>
            
            
            <p>
                7.3. We have the right to terminate your subscription immediately without a refund and/or limit your access to the Website if we have a reason to believe that:
                <ul>
                    <li>(a) You are not compliant with the requirements specified in Section 2.1 of these Terms above;</li>
                    <li>(b) You have breached any provision of the Section 7.2 or 7.3 of these Terms above;</li>
                    <li>(c) You are using the Website or any of its contents in any other illegal way or causing harm to the Website or other users of the Website.</li>
                </ul>
            </p>
            
            <h4>8. DISCLAIMERS</h4>
            
            <p>8.1. Nothing on this Website or Services you receives from Us shall be considered as an investment advice and construed as an endorsement or recommendation to buy, sell or hold cryptocurrency or other digital assets.</p>

            <p>8.2. Always seek the advice of professional with any questions you may have regarding investments or trading in digital assets and never disregard professional advice because of something you have read on this Website. Your use of this Website and any information contained herewith is entirely at your own risk and We shall not be liable or responsible for any loss or damage caused, including but not limited to, indirect, special, incidental or punitive loss to person or computer equipment, sustained as a result of the use of this website, the information contained or suggested therein or any errors or omissions contained in such information. All customers using our Services agree to accept full responsibility for any results experienced from using the Services.</p>

            <p>8.3. The Website may provide links to other websites maintained by third parties. Any information, products, software, or services provided on or through third-party sites are controlled by the operators of such sites and not by us or our subsidiary companies. When you access third-party sites, you do so at your own risk.</p>

            <p>8.4. Unless otherwise indicated, this Website is our property and all source code, databases, functionality, software, designs, text, photographs, and graphics on the website are owned or controlled by us and are protected by copyright and trademark laws. It is forbidden to copy or use any of the website's contents without prior written approval by us.</p>

            <p>8.5. THE SERVICES OFFERED ON OR THROUGH THE WEBSITE ARE PROVIDED “AS IS” AND WITHOUT WARRANTIES OF ANY KIND EITHER EXPRESS OR IMPLIED. TO THE FULLEST EXTENT PERMISSIBLE UNDER APPLICABLE LAW, WE DISCLAIM ALL WARRANTIES, EXPRESS OR IMPLIED, INCLUDING, BUT NOT LIMITED TO, IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE.</p>

            <p>8.6. WE DO NOT WARRANT THAT THE WEBSITE OR ANY OF ITS SERVICES AND FUNCTIONS WILL BE UNINTERRUPTED OR ERROR-FREE, THAT DEFECTS WILL BE CORRECTED, OR THAT ANY PART OF THIS SITE OR THE SERVERS THAT MAKE THE SITE AVAILABLE, ARE FREE OF VIRUSES OR OTHER HARMFUL COMPONENTS.  WE EXPRESSLY DISCLAIM LIABILITY FOR ANY DAMAGES OR INJURY CAUSED BY ANY FAILURE OF PERFORMANCE, ERROR, OMISSION, INTERRUPTION, DELETION, DEFECT, DELAY IN OPERATION OR TRANSMISSION, COMPUTER VIRUS, COMMUNICATION LINE FAILURE, THEFT OR DESTRUCTION OR UNAUTHORIZED ACCESS TO, ALTERATION OF, OR USE OF RECORD, WHETHER FOR BREACH OF CONTRACT, TORTIOUS BEHAVIOR, NEGLIGENCE, OR UNDER ANY OTHER CAUSE OF ACTION. EACH USER SPECIFICALLY ACKNOWLEDGES THAT WE ARE NOT LIABLE FOR THE DEFAMATORY, OFFENSIVE, OR ILLEGAL CONDUCT OF OTHER THIRD-PARTIES, SUBSCRIBERS, MEMBERS, OR OTHER USERS OF THE WEBSITE AND THAT THE RISK OF INJURY FROM THE FOREGOING RESTS ENTIRELY WITH EACH USER.</p>

            <p>8.7. Any information provided on the Website is for informational and educational purposes only and may not be used as investment advice. The Website should not be used in any high-risk activities where damage or injury to persons, property, environment, finances, or business may result if an error occurs. You assume all risk for your use of information provided on the Website.</p>

            <h4>9. INDEMNIFICATION</h4>

            <p>
                9.1. You agree to indemnify, defend, and hold us and our affiliates, and respective officers, directors, owners, agents, information providers, and licensors harmless from and against all claims, liability, losses, damages, costs, and expenses (including attorneys' fees) in connection with:
                <ul>
                    <li>(a) Your use of, or connection to, Our Website;</li>
                    <li>(b) Any use or alleged use of Your account or Your account password by any person, whether or not authorized by You;</li>
                    <li>(c) The content of information submitted by You to Us;</li>
                    <li>(d) Your violation of the rights of any other person or entity;</li>
                    <li>(e) Your violation of any applicable laws, rules, or regulations.</li>
                </ul>
            </p>

            <p>9.2. We reserve the right, at our own expense, to assume defence and control of any matter otherwise subject to indemnification by you, and in such case, you agree to cooperate with us in defence of such claim.</p>

            <h4>10. LIMITATION OF LIABILITY</h4>
            
            <p>10.1. In no case shall we, our directors, officers, employees, affiliates, agents, contractors, interns, suppliers, service providers or licensors be liable for any injury, health issues, sickness, physical problems, loss, claim, or any direct, indirect, incidental, punitive, special, or consequential damages of any kind, including, without limitation lost profits, lost revenue, lost savings, loss of data, replacement costs, or any similar damages, whether based in contract, tort (including negligence), strict liability or otherwise, arising from using the Services, or for any other claim related in any way to your use of the Services, including, but not limited to, any errors or omissions in any content, or any loss or damage of any kind incurred as a result of the use of the service or any content posted, transmitted, or otherwise made available via the Services, even if advised of their possibility.</p>

            <p>10.2. If You are dissatisfied with the Website, any materials or services displayed on the Website, or with any of the Website’s terms and conditions, your sole and exclusive remedy is to discontinue using the Website.</p>

            <h4>11. INTELLECTUAL PROPERTY</h4>
            
            <p>11.1. With regards to these Terms, intellectual property rights mean such rights as trademarks, copyright, domain names, database rights, design rights, patents, and all other intellectual property rights of any kind whether or not they are registered ("<b>Intellectual Property</b>").</p>

            <p>11.2. All Intellectual Property displayed on the Website or provided to You in any other form are protected by law. You may not copy, repurpose, or distribute any Intellectual Property or any other content received from us or found on the Website, for any purpose, without our express written permission. Without limiting the foregoing, the use of our content for commercial purposes is forbidden unless you have our express written permission.</p>

            <p>11.3. All Intellectual Property displayed on the Website or provided to you in any other form belong to Coinwink, except third-party trademarks, service marks, or other materials, which are used by us. None of such Intellectual Property may be used without the prior written consent of us or the third party to whom such Intellectual Property belongs.</p>

            <h4>12. GOVERNING LAW AND DISPUTES</h4>

            <b><p>12.1. If You have any complaints regarding the Website, fees, refunds, quality of Services, or anything related to the use of the Website, You must first contact our customer support team by email (<span class="reverse noselect">moc.kniwnioc@ofni</span>) before taking any action through third parties. Please be noted that by agreeing to these Terms you explicitly agree not the request for any refunds from your bank or credit card operator without priory contacting us and without giving us a chance to settle any issues that you might have.</p></b>

            <p>12.2. All complaints or claims provided by you shall be processed within 30 days from receiving. We always put our best efforts into the positive settlement of the complaint or claim. When addressing us with your complaints, you must always identify yourself by the same e-mail address you have provided to us when registered on the Website.</p>

            <p>12.3. These Terms and the entire legal relation between you and us shall be subject to the law of the Republic of Lithuania, except when consumer laws would set a specific applicable law or jurisdiction, or would allow to apply the laws of the consumer’s place of residence.</p>

            <p>
                12.4. If we are unable to reach an amicable settlement with You or if you have any other complaints about our goods or services, You may:
                <ul>
                    <li>(a) submit a request or complaint regarding your purchase to State Consumer Rights Protection Authority (SCRPA) of Lithuanian Republic (address Vilniaus g. 25, 01402 Vilnius, Lithuania, email: tarnyba@vvtat.lt, telephone 8 5 262 67 51, website www.vvtat.lt) or you can also contact any territorial division of the SCRPA; or</li>
                    <li>(b) fill out a request / complaint form on the EGS platform at http://ec.europa.eu/odr; or</li>
                    <li>(c) appeal to court of the Republic of Lithuania (according to the headquarters of the Company); or</li>
                    <li>(d) submit a complaint to your local consumer protection authority or national court if consumers laws of User place of residence provides such a right for consumers regardless of the rules provided in these Terms.</li>
                </ul>
            </p>
            
            <h4>13. MISCELLANEOUS</h4>
            
            <p>13.1. If any provision of these Terms is determined to be unlawful, void, or unenforceable, such provision shall nonetheless be enforceable to the fullest extent permitted by applicable law, and the unenforceable portion shall be deemed to be severed from these terms of service, such determination shall not affect the validity and enforceability of any other remaining provisions.</p>

            <p>13.2. You can review the most current version of the Terms at any time on this page. We reserve the right, at our sole discretion, to update, change, or replace any part of these terms of service by posting updates and changes to our website.</p>
            
            <h4>14. AMENDMENTS</h4>

            <p>14.1. We reserve the right to amend these Terms at any time and under our own discretion. Please check these Terms from time to time in order to verify the existence of any new amendments. On this site, we will publish any announcements pertaining to any amendments and additions that will be made to the provisions of these Terms. Amendments will not apply retroactively and will be applicable starting from the date of publication.</p>

            <h4>15. CONTACT INFORMATION</h4>

            <p>
                15.1. You can contact us by the following details:
                <br>
                Email - <b><span class="reverse noselect">moc.kniwnioc@ofni</span></b>
            </p>
		</div>

		@endif
	</div>

	<div style="height:10px;"></div>

	<!-- FOOTER -->
	<footer style="text-align: center;">
		<div style="margin:0 auto;padding-top:4px;padding-bottom:10px;" class="text-footer-links">
		<a href="/manage-alerts" class="whitelink">Alerts</a>
		&nbsp;|&nbsp;
		<a href="/account" class="whitelink">Account</a>
		&nbsp;|&nbsp;
		<a href="/portfolio" class="whitelink">Portfolio</a>
		&nbsp;|&nbsp;
		<a href="/watchlist" class="whitelink">Watchlist</a>
		</div>
		<div style="margin-top:18px;font-size:11px;" class="text-footer-links">
		<a href="/about" class="whitelink">About</a>&nbsp;|&nbsp;
		<a href="/pricing" class="whitelink">Pricing</a>&nbsp;|&nbsp;
		<a href="/terms" class="whitelink">Terms</a>&nbsp;|&nbsp;
		<a href="/privacy" class="whitelink">Privacy</a>&nbsp;|&nbsp;
		<!-- <a href="/press" class="whitelink">Press</a>&nbsp;|&nbsp; -->
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
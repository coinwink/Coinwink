<?php /* Template Name: Coinwink - Account */ get_header(); ?>

<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/jquery-3.3.1.min.js"></script>

<?php $ajax_nonce = wp_create_nonce( "my-special-string" ); ?>

<?php
	if ( is_user_logged_in() ) {
		$user_ID = get_current_user_id();
		$result = $wpdb->get_results( "SELECT subs, sms, theme FROM cw_settings WHERE user_ID = '".$user_ID."'", ARRAY_A);
		$subs = $result[0]["subs"];
		$sms = $result[0]["sms"];
		$theme = $result[0]["theme"];
		$result = $wpdb->get_results( "SELECT date_end, status, date_renewed, months FROM cw_subs WHERE user_ID = '".$user_ID."'", ARRAY_A);
		$status = $result[0]["status"];
		$months = $result[0]["months"];
		$date_renewed = $result[0]["date_renewed"];
		$date_end = $result[0]["date_end"];
		$date_end = new DateTime($date_end);
        $date_end = $date_end->format('Y-m-d');
		$acc_email = $wpdb->get_var( "SELECT user_email FROM wp_users WHERE ID = '".$user_ID."'" );
	}
?>

<div style="position:relative;max-width:800px;margin:0 auto;" class="outer-buttons">

	<div style="position:absolute;top:18px;right:15px;width:33px;">
		<a href="<?php echo site_url(); ?>" style="cursor:pointer;">
			<div title="Home" style="padding:3px;margin:0 auto;" class="icon-portfolio-outer">
				<svg id="icon-home" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 588.83 524.89">
					<path class="icon-hovered" d="M593.57-933.69L314.45-1138.05a15.28,15.28,0,0,0-8.65-2.95H305a15.27,15.27,0,0,0-8.65,2.95L17.26-933.69A15.3,15.3,0,0,0,14-912.31,15.3,15.3,0,0,0,35.34-909l270.08-197.75L575.49-909a15.24,15.24,0,0,0,9,3,15.28,15.28,0,0,0,12.36-6.26,15.3,15.3,0,0,0-3.31-21.38h0Z" transform="translate(-11 1141)"/><path class="icon-hovered" d="M519.61-905.54a15.3,15.3,0,0,0-15.3,15.3v243.52H381.92V-779.64a76.59,76.59,0,0,0-76.5-76.5,76.59,76.59,0,0,0-76.5,76.5v132.93H106.52V-890.24a15.3,15.3,0,0,0-15.3-15.3,15.3,15.3,0,0,0-15.3,15.3v258.82a15.3,15.3,0,0,0,15.3,15.3h153a15.29,15.29,0,0,0,15.24-14.11,11.61,11.61,0,0,0,.06-1.19V-779.64a46,46,0,0,1,45.9-45.9,46,46,0,0,1,45.9,45.9v148.23a11.17,11.17,0,0,0,.06,1.18,15.29,15.29,0,0,0,15.24,14.12h153a15.3,15.3,0,0,0,15.3-15.3V-890.24a15.3,15.3,0,0,0-15.3-15.3h0Z" transform="translate(-11 1141)"/></svg>
				</svg>
			</div>
		</a>
	</div>

</div>


<!-- Header - Logo -->
<div style="text-align: center;">
	<div style="height:27px;"></div>
	<div id="logo" style="width:44px;-webkit-filter: drop-shadow(0 1px 1px rgba(0,0,0,.7));filter: drop-shadow(0 1px 1px rgba(0,0,0,.7));">
		<a href="<?php echo site_url(); ?>">
			<?php if ($theme == '' || $theme == 'classic') { ?>
			<img src="https://coinwink.com/img/coinwink-crypto-alerts-logo.png?v=002" width="44" alt="Coinwink Crypto Alerts">
			<?php } else if ($theme == 'matrix') { ?>
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 61.36 61.36"><title>Coinwink Matrix Logo</title><path d="M30.68,0A30.68,30.68,0,1,1,0,30.68,30.69,30.69,0,0,1,30.68,0Z" class="logo-matrix-base" /><path d="M30.68,6.41a24.29,24.29,0,1,1-17.16,7.11A24.27,24.27,0,0,1,30.68,6.41ZM52.76,30.68A22.08,22.08,0,1,0,30.68,52.76,22.09,22.09,0,0,0,52.76,30.68Z"/><path d="M41.05,36.22a10.37,10.37,0,0,1-20.74,0H23a7.73,7.73,0,1,0,15.46,0Z"/><path d="M28.2,23c-.49,2.16-2.85,4.38-4.51,5.41a14.61,14.61,0,0,1-13.83.86C7.62,28-2.49,19.26,3.69,17.61c4.72-1.27,13.78-.41,18.6.69l1,.25a9.28,9.28,0,0,1,4.25,2.06h6.36a9.27,9.27,0,0,1,4.26-2.06l1-.25c4.83-1.11,13.88-2,18.6-.69,6.18,1.65-3.93,10.4-6.17,11.67a14.61,14.61,0,0,1-13.83-.86c-1.63-1-3.93-3.17-4.49-5.3a5,5,0,0,0-2.58-.73h0A5,5,0,0,0,28.2,23Z" style="fill-rule:evenodd"/></svg>
			<?php } ?>
		</a>
	</div>
	<div id="txtlogo"><a href="<?php echo site_url(); ?>">Coinwink</a></div>
</div>


<?php if ( is_user_logged_in() ) { ?>

   <div class="container" id="account">

	<header>
	<h2 class="text-header" style="color:white;">Account</h2>
	</header>

	<div id="login-form" class="login-form-container">
		<div class="content" style="margin-bottom:0px;">

			<div style="margin:0 auto;">
			
				<div class="rounded-border">
					<b>Subscription</b>

					<br><br>

					<span style="font-size:14px;">Your current plan: 
						<?php if ($subs == 0 && $status != 'suspended' ) { ?><b>Free</b>
						<?php } else if ($subs == 1 && $status == 'active') { ?><b>Premium</b>
						<?php } else if ($subs == 1 && $status == 'cancelled') { ?><b>Cancelled</b>
						<?php } else if ($subs == 0 && $status == 'suspended') { ?><b>Suspended</b>
						<?php } ?>
					</span>

					<br><br>

					<?php if ($subs == 0 && $status != 'suspended') { ?>
						<?php if ($user_ID == 24301 || $user_ID == 19762 || $user_ID == 7929) { ?>
							10 email alerts<br>
							10 coins in portfolio<br>
							10 coins in watchlist
						<?php } else { ?>
							5 email alerts<br>
							5 coins in portfolio<br>
							5 coins in watchlist
						<?php } ?>
						<br><br>
						<a href="<?php echo site_url(); ?>/subscription" class="blacklink blacklink-acc">Upgrade</a>
					<?php } ?>

					<?php if ($subs == 1 && $status == 'active') { ?>
						<div style="height:10px;">&nbsp;</div>
						Unlimited alerts<br>
						Unlimited coins in portfolio<br>
                        Unlimited coins in watchlist<br>
						SMS left this month: <?php echo $sms; ?>
						<br>
						<br>
						<span style="cursor:pointer;text-decoration:underline;" class="blacklink blacklink-acc" id="extracredits">Get more SMS</span>
						<div id="extracreditsdiv" style="display:none;">
							<br>
							You can buy packs of additional SMS credits for an ongoing month.
							<br><br>
							<div id="credits-buy-button" style="height:24px;">
								<input type="submit" id="buy_100_credits_button" style="padding:3px;" value="Buy 100 credits" />
							</div>
							<div style="height:10px;"></div>
						</div>
						<script>
							extraCreditsShow = false;
							jQuery("#extracredits").click(function() {
								extraCreditsShow = !extraCreditsShow;
								if (extraCreditsShow) {
									jQuery("#extracreditsdiv").show();
								}
								else {
									jQuery("#extracreditsdiv").hide();
								}
							});
						</script>
						<br>
						<br>
                        <!-- Status update: Received a strange BTC alert? Find mode details <a href="https://twitter.com/Coinwink/status/1174162988791713793" target="_blank" class="blacklink">here</a>. For Premium accounts, SMS credits are now reset to monthly 100.
                        <br>
                        <br> -->
						<?php if ($subs == 1 && $status == 'active' && $months > 1) { ?>
							Subscription renewed on:<br><?php echo($date_renewed); ?>
							<br>
						<?php } ?>
						<span style="cursor:pointer;text-decoration:underline;" class="blacklink blacklink-acc" id="cancelsubs">Cancel subscription</span><br>
						<div id="cancelsubsdiv" style="display:none;">
							<br>
							If you cancel your subscription, you will be able to receive SMS alerts and use other Premium features until the end of the ongoing month.
							<br><br>
							<input type="submit" id="cancel_subs_button" style="padding:3px;" value="Cancel subscription" />
						</div>
						<script>
							cancelSubsShow = false;
							jQuery("#cancelsubs").click(function() {
								cancelSubsShow = !cancelSubsShow;
								if (cancelSubsShow) {
									jQuery("#cancelsubsdiv").show();
								}
								else {
									jQuery("#cancelsubsdiv").hide();
								}
							});
						</script>
						<br><br>
						Support:<br>
						&#x73;&#x75;&#x70;&#x70;&#x6f;&#x72;&#x74;&#x40;&#99;&#111;&#105;&#110;&#119;&#105;&#110;k&#46;co&#x6d;
					<?php } ?>

					<?php if ($subs == 1 && $status == 'cancelled') { ?>
						<div style="margin-bottom:5px;">
							Your subscription was cancelled.
							<br><br>
							You can continue using Premium features until <?php echo $date_end; ?>.
                            <br><br>
							After that, it will automatically switch to a free plan.
						</div>
					<?php } ?>

					<?php if ($subs == 0 && $status == 'suspended') { ?>
						<div>
                            Your Premium plan is temporarily suspended because Stripe was unable to charge your credit card and extend the subscription.
                            <br><br>
                            Stripe will try to charge your credit card again during the next few days. If the payment is received, the Premium features will be re-enabled automatically.
                            <br><br>
                            If you need to update your credit card details, please contact Coinwink support.
						</div>
					<?php } ?>

					</div>
				</div>

				<div class="rounded-border">
					<?php if ( is_user_logged_in() && !current_user_can( 'manage_options' ) ) {  ?>
					<b>Delete account</b>
					<br><br>
					<span style="cursor:pointer;text-decoration:underline;" class="blacklink blacklink-acc" id="deleteacchref">Delete</span><br>
					<div id="deleteaccdiv" style="display:none;">
					<br>
					Are you sure you want to delete your account? This cannot be undone. Your all data will be lost.
					<br><br>
					<input type="submit" id="delete_my_acc_button" style="padding:3px;" value="Delete my account" />
					<br>
					</div>
					<script>
					deleteShow = false;
					jQuery("#deleteacchref").click(function() {
						deleteShow = !deleteShow;
						if (deleteShow) {
							jQuery("#deleteaccdiv").show();
						}
						else {
							jQuery("#deleteaccdiv").hide();
						}
					});
					</script>
					<?php }; ?>
				</div>

				<div class="rounded-border">
					<b>Change password</b>
					<br><br>
					To change your password, please log out and then use the password recovery form.
				</div>
				
				<div class="rounded-border">
					<b>Did you know?</b>
					<br><br>
					You can drag & drop coins in your watchlist to change their order.
					<div style="height:10px;"></div>
					In the watchlist, click the price column title to switch to volume and market cap.
				</div>

				<?php if (true || $user_ID == 24916 || $user_ID == 24457 || $user_ID == 23975 ||  $user_ID == 15423 ||  $user_ID == 28268 ) { ?>

					<div class="rounded-border">
						<b>Themes</b>
						<!-- <br>
						<span style="font-size:11px;letter-spacing:1px;">Beta version</span> -->
						<div style="height:15px;"></div>
						<div style="width:74px;margin:0 auto;text-align:left;letter-spacing:1px;">
						
							<div class="appify-radio">
								<input id="themeClassic" type="radio" class="appify-radio-input themeClassic" value="classic"/>
								<label for="themeClassic" class="themeClassic rad">
									<div class="appify-radio-box">  
										<svg><use xlink:href="#radiomark" /></svg>
									</div>
									<span class="appify-radio-label noselect">Classic</span>
								</label>
							</div>

							<div style="clear:both;height:8px;"></div>

							<div class="appify-radio">
								<input id="themeMatrix" type="radio" class="appify-radio-input themeMatrix" value="matrix"/>
								<label for="themeMatrix" class="themeMatrix">
									<div class="appify-radio-box">  
										<svg><use xlink:href="#radiomark" /></svg>
									</div>
									<span class="appify-radio-label noselect">Matrix</span>
								</label>
							</div>

							<div style="clear:both;height:5px;"></div>

						</div>

						<?php if ($theme == "matrix") { ?>

							<div style="height:10px;"></div>

							<div style="clear:both;width:220px;border:1px solid #272727;margin:0 auto;padding-top:12px;margin-bottom:5px;padding-bottom:12px;border-radius:3px;line-height:160%;background:black;" class="noselect">
								
								<div style="display:grid;grid-template-columns:0.66fr 1fr;">
									<div style="display:grid;grid-template-columns:57px 25px;">
										<div style="text-align:right;padding-right:5px;">
											Static:
										</div>
										<div>
											<div class="appify-checkbox" style="width:231px;margin:0 auto;">
												<input id="static-check" name="portfolio-alert-3" type="checkbox" class="appify-input-checkbox" />
												<label for="static-check">
													<div class="checkbox-box">  
														<svg><use xlink:href="#checkmark" /></svg>
													</div>
												</label>
											</div>
										</div>
									</div>

									<div style="display:grid;grid-template-columns:63px 50px;">
										<div style="text-align:right;padding-right:4px;">
											Intensity:
										</div>
										<div style="display:grid;grid-template-columns:23px 8px 23px;">
											<div>
												<button onclick="lessTransp()" class="plus-minus" style="width:21px;height:21px;margin-bottom:0px!important;margin-top:-2px;">
													<svg style="margin-bottom:-4px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 849.52 849.52"><title>Remove</title><polygon points="155.51 463.46 155.51 386.06 694.01 386.06 694.01 463.46 155.51 463.46 155.51 463.46" class="plus-minus-svg" /></svg>
												</button>
											</div>
											<div></div>
											<div>
												<button onclick="moreTransp()" class="plus-minus" style="width:21px;height:21px;margin-bottom:0px!important;margin-top:-2px;">
													<svg style="margin-bottom:-4px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 849.52 849.52"><title>Add</title><polygon points="392.51 155.51 457.01 155.51 457.01 392.51 694.01 392.51 694.01 457.01 457.01 457.01 457.01 694.01 392.51 694.01 392.51 457.01 155.51 457.01 155.51 392.51 392.51 392.51 392.51 155.51 392.51 155.51" class="plus-minus-svg" /></svg>
												</button>
											</div>
										</div>
									</div>
								</div>

							</div>

						<?php } ?>

					</div>

				<?php } ?>

				<div class="rounded-border">
					<b>Feedback</b>
					<br><br>
					<div style="line-height:155%;">
						Let us know your feedback by <a href="mailto:feedback@coinwink.com" class="blacklink blacklink-acc">e-mail</a> or by a quick <span style="cursor:pointer;text-decoration:underline;" class="blacklink blacklink-acc" id="feedback-link">message</span>
					</div>

					<div style="height:10px;"></div>

					<div id="feedback-div" style="display:none;">

						<div class="np-modal-window" id="cw-feedback-modal">
							<div class="cw-feedback">
								<div class="cw-feedback-inner">
									<a href="#" id="np-modal-close"><div class="np-modal-close" title="Close">✕</div></a>
									<div id="cw-feedback-content">
										<div id="feedback-prep">
											<div style="height:15px;"></div>
											<span style="color:#c5c5c5;">Your message</span>
											<div style="height:25px;"></div>
											<form method="post" id="form-account-feedback">
												<textarea name="feedback" class="cw-feedback-input" maxlength="5000" required minlength="4"></textarea>
												<div style="height:20px;"></div>
												
												<input name="action" type="hidden" value="account_feedback" />

												<button class="btn-feedback-submit" id="btn-feedback-submit" type="submit">
													Submit
												</button>

												<div id="loader-feedback-submit" class="create-alert-spinner" style="padding-top:8px;">
													<div class="appify-spinner-div-feedback"></div>
												</div>
											</form>
										</div>

										<div id="feedback-success">
											<div style="height:30px;"></div>
											We have received your feedback.
											<br>
											<br>
											Thank you!
											<div style="height:30px;"></div>
										</div>

										<div id="feedback-error">
											<div style="height:30px;"></div>
											Error!
											<br>
											<br>
											The message was not submitted.
											<br>
											<br>
											Please contact us by email:
											<br>
											feedback@coinwink.com
											<div style="height:30px;"></div>
										</div>

									</div>
								</div>
							</div>
						</div>

					</div>

				</div>


				<div style="height:3px;"></div>
				
                Logged in as:
                <br>
                <span id="acc-email"></span>
                <br>
                
				<br>
				<a href="<?php echo wp_logout_url(get_permalink()); ?>" class="blacklink blacklink-acc">Log out</a>
				<br>

			</div>

		</div>
	</div>

<?php } else { ?>

	<div class="container" style="padding-bottom:30px;">

		<header style="height:90px;">
		<h2 class="text-header" style="color:white;">Account</h2>
		
		<div style="margin-top:-10px;margin-bottom:35px;">
			<div class="switch-acc">
				<input type="radio" class="switch-3-input" name="switch-acc" id="signupSwitch" checked="checked">
				<label for="signupSwitch" id="switch-label-signup" class="switch-3-label switch-label-signup">Sign up</label>
				<input type="radio" class="switch-3-input" name="switch-acc" id="loginSwitch">
				<label for="loginSwitch" class="switch-3-label switch-label-login">Log in</label>
				<span class="switch-3-selection"></span>
			</div>
		</div>
		</header>
		<?php 
				echo do_shortcode('[custom-register-form]'); 
			} 
		?>
	
	</div>


<?php echo do_shortcode('[footer_shortcode]'); ?>


<div class="transition-matrix">
	<div class="black-stripe"></div>
	<div class="black-stripe"></div>
	<div class="black-stripe"></div>
	<div class="black-stripe"></div>
	<div class="black-stripe"></div>
	<div class="black-stripe"></div>
	<div class="black-stripe"></div>
	<div class="black-stripe"></div>
	<div class="black-stripe"></div>
	<div class="black-stripe"></div>

	<div class="black-stripe"></div>
	<div class="black-stripe"></div>
	<div class="black-stripe"></div>
	<div class="black-stripe"></div>
	<div class="black-stripe"></div>
	<div class="black-stripe"></div>
	<div class="black-stripe"></div>
	<div class="black-stripe"></div>
	<div class="black-stripe"></div>
	<div class="black-stripe"></div>

	<div class="black-stripe"></div>
	<div class="black-stripe"></div>
	<div class="black-stripe"></div>
	<div class="black-stripe"></div>
	<div class="black-stripe"></div>
	<div class="black-stripe"></div>
	<div class="black-stripe"></div>
	<div class="black-stripe"></div>
	<div class="black-stripe"></div>
	<div class="black-stripe"></div>

	<div class="t-centered" id="t-centered">
	</div>
</div>


<style>
	/* SCOPED: Account only */

	.np-modal-close {
		position: absolute;
		cursor: pointer;
		font-size: 22px;
		font-weight: bold;
		color: #777;    
		right: 10px;
		top: 9px;
		width: 30px;
		height: 30px;
	}

	.np-modal-close:hover {
		color: #999;
	}

	#np-modal-close:focus div {
		color: #999;
	}

	.np-modal-window {
		height: 100%;
		width: 100%;
		position: fixed;
		z-index: 999;
		left: 0;
		top: 0;
		background-color: rgb(0,0,0);
		background-color: rgba(0,0,0, 0.95);
		overflow-x: hidden;
		box-sizing: content-box;
		align-items: center;
	}

	.cw-feedback {
		color: white;
		font-size:14px;
		font-weight: normal;
		background-color: black;
		border: 1px solid #333;
		border-radius: 5px;
		height: auto;
		padding: 40px 15px 45px 15px;
		width: 560px;
		max-width: 95%;
		position: absolute;
		top: 50%;
		left: 50%;
		transform: translate(-50%, -51%);
		text-align: center;
		box-shadow: 0 0 15px 5px rgba(0, 0, 0, 0.5);
	}

	.cw-feedback-input {
		margin-top: 10px;
		margin-bottom: 15px;
		/* background-color: #2b3845; */
		background-color: #151a1f;
		color: white;
		border: 1px solid #666;
		padding: 10px;
		font-size: 14px;
		font-family: inherit;
		max-width:400px;
		width:95%;
		height:240px;
		resize: none;
	}

	.cw-feedback-input:invalid {
		box-shadow: none;
	}

	.cw-feedback-input::-webkit-scrollbar {
		width: 10px;
	}

	.cw-feedback-input::-webkit-scrollbar-thumb {
		background-color: #555;
	}

	.cw-feedback-input::-webkit-scrollbar-thumb:hover {
		background-color: #555;
	}

	.cw-feedback-input:hover, .cw-feedback-input:focus {
		border: 1px solid #888!important;
		outline: 0;
	}

	.btn-feedback-submit {
		width:130px;
		height: 40px;
		/* padding-bottom:6px; */
		border:1px solid #666;
		cursor:pointer;
		margin:0 auto;
		font-size:15px;
		color:#999;
		border-radius: 5px;
		background-color: black;
		font-family: 'Montserrat', sans-serif;
		font-weight: bold;
	}

	.btn-feedback-submit:hover, .btn-feedback-submit:active {
		border:1px solid #999;
		color: #aeaeae;
		outline: none;
	}

	.btn-feedback-submit:focus {
		outline: none;
		border:1px solid #999;
		color: #999;
	}

	#feedback-error {
		display: none;
	}

	#feedback-success {
		display: none;
	}


	/* Themes Transition */

	.transition-matrix {
		position: fixed;
		height: 100%;
		width: 100%;
		display: grid;
		grid-template-columns: repeat(30, 1fr);
		top: 0;
		left: 0;
		z-index: -999;
	}

	.black-stripe {
		height: 0%;
		background: black;
		transition: height 0.5s;
	}

	.full-height {
		height: 100%;
	}

	.t-centered {
		width: 100%;
		margin: 0 auto;
		text-align: center;
		position: fixed;
		top: 50%;
		left: 50%;
		-ms-transform: translateX(-50%) translateY(-50%);
		-webkit-transform: translate(-50%,-50%);
		transform: translate(-50%,-50%);
		/* color: #00ff3c; */
		color: #23ff57;
		font-size: 20px;
		/* font-weight: bold; */
		letter-spacing:2px;
	}
</style>


<script type="text/javascript">
	var ajax_url = "<?php echo site_url(); ?>/wp-admin/admin-ajax.php";
	var security_url = "&security=<?php echo $ajax_nonce; ?>";
	var jqueryarray = "";
	var cw_theme = "";
</script>

<?php if ( is_user_logged_in() ) { 	?>
	<script>
		cw_theme = '<?php echo($theme) ?>';
        var accEmail = "<?php echo($acc_email); ?>";
        jQuery("#acc-email").html(accEmail);
    </script>
<?php } ?>

<script>
	if (cw_theme == '') {
		cw_theme = 'classic';
	}
</script>

<!-- Stripe Payment -->
<?php if ( is_user_logged_in() ) { 	?>
    <script src="https://js.stripe.com/v3/"></script>
<?php } ?>

<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/coinwink_account.js?v=558"></script>


</body>
</html>
<?php /* Template Name: Coinwink - Account */ get_header(); ?>

<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/jquery-3.3.1.min.js"></script>

<?php $ajax_nonce = wp_create_nonce( "my-special-string" ); ?>

<?php
	if ( is_user_logged_in() ) {
		$user_ID = get_current_user_id();
		$result = $wpdb->get_results( "SELECT subs, sms FROM cw_settings WHERE user_ID = '".$user_ID."'", ARRAY_A);
		$subs = $result[0]["subs"];
		$sms = $result[0]["sms"];
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

    <div style="position:absolute;top:20px;right:15px;padding-left:2px;width:26px;">
        <a href="<?php echo site_url(); ?>">
            <svg id="icon-home" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 588.83 524.89">
                <title>Home</title>
                <?php echo file_get_contents(get_stylesheet_directory_uri() . "/img/icon-home.svg"); ?>
            </svg>
        </a>
    </div>

</div>


<div style="text-align:center;">
    <div style="height:27px;"></div>
    <div id="logo" style="width:44px;-webkit-filter: drop-shadow(0 1px 1px rgba(0,0,0,.7));filter: drop-shadow(0 1px 1px rgba(0,0,0,.7));">
        <a href="<?php echo site_url(); ?>">
            <img src="https://coinwink.com/img/coinwink-crypto-alerts-logo.png" width="44" alt="Coinwink Crypto Alerts">
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
		<div class="content">

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
						5 email alerts<br>
						5 coins in portfolio<br>
                        5 coins in watchlist
						<br><br>
						<a href="<?php echo site_url(); ?>/subscription" class="blacklink">Upgrade</a>
					<?php } ?>

					<?php if ($subs == 1 && $status == 'active') { ?>
						<div style="height:10px;">&nbsp;</div>
						Unlimited alerts<br>
						Unlimited coins in portfolio<br>
                        Unlimited coins in watchlist<br>
						SMS left this month: <?php echo $sms; ?>
						<br>
						<br>
                        <!-- Status update: Received a strange BTC alert? Find mode details <a href="https://twitter.com/Coinwink/status/1174162988791713793" target="_blank" class="blacklink">here</a>. For Premium accounts, SMS credits are now reset to monthly 100.
                        <br>
                        <br> -->
						<?php if ($subs == 1 && $status == 'active' && $months > 1) { ?>
							Subscription renewed on:<br><?php echo($date_renewed); ?>
							<br>
						<?php } ?>
						<span style="cursor:pointer;text-decoration:underline;" class="blacklink" id="cancelsubs">Cancel subscription</span><br>
						<div id="cancelsubsdiv" style="display:none;">
							<br>
							If you cancel your subscription, you will be able to receive SMS alerts and use other Premium features until the end of the ongoing month.
							<br><br>
							<input type="submit" id="cancel_subs_button" value="Cancel subscription" />
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
					<span style="cursor:pointer;text-decoration:underline;" class="blacklink" id="deleteacchref">Delete</span><br>
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
                    
                Logged in as:
                <br>
                <span id="acc-email"></span>
                <br>
                
				<br>
				<a href="<?php echo wp_logout_url(get_permalink()); ?>" class="blacklink">Log out</a>
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
		<?php echo do_shortcode('[custom-register-form]'); } ?>
	
	</div>

<?php echo do_shortcode('[footer_shortcode]'); ?>


<script type="text/javascript">
	var ajax_url = "<?php echo site_url(); ?>/wp-admin/admin-ajax.php";
	var security_url = "&security=<?php echo $ajax_nonce; ?>";
	var jqueryarray = "";
</script>

<?php if ( is_user_logged_in() ) { 	?>
	<script>
        var accEmail = "<?php echo($acc_email); ?>";
        jQuery("#acc-email").html(accEmail);
    </script>
<?php } ?>

<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/coinwink_account.js?v=238"></script>

</body>
</html>
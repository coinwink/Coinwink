<?php /* Template Name: Coinwink - Home */ get_header(); ?>

<?php $ajax_nonce = wp_create_nonce( "my-special-string" ); ?>

<?php
	// Get coin price data from the database
	$resultdb = $wpdb->get_results( "SELECT json FROM coinwink_json" , ARRAY_A);
	$newarrayjson = $resultdb[0][json];
	$newarrayunserialized = unserialize($newarrayjson);

?>

<?php
	// Update API settings
	if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'update-settings' ) {

		if ( ( $_POST['plivo1'] ) ){
		$wpdb->update('coinwink_settings', array( 'plivo1' => sanitize_text_field( $_POST['plivo1'] )), array('user_ID' => $current_user->ID));
		}
		else {
		$wpdb->update('coinwink_settings', array( 'plivo1' => '' ), array('user_ID' => $current_user->ID));	
		}
		
		if ( ( $_POST['plivo2'] ) ){
		$wpdb->update('coinwink_settings', array( 'plivo2' => sanitize_text_field( $_POST['plivo2'] )), array('user_ID' => $current_user->ID));
		}
		else {
		$wpdb->update('coinwink_settings', array( 'plivo2' => '' ), array('user_ID' => $current_user->ID));	
		}

		if ( ( $_POST['nexmo1'] ) ){
		$wpdb->update('coinwink_settings', array( 'nexmo1' => sanitize_text_field( $_POST['nexmo1'] )), array('user_ID' => $current_user->ID));
		}
		else {
		$wpdb->update('coinwink_settings', array( 'nexmo1' => '' ), array('user_ID' => $current_user->ID));	
		}

		if ( ( $_POST['nexmo2'] ) ){
		$wpdb->update('coinwink_settings', array( 'nexmo2' => sanitize_text_field( $_POST['nexmo2'] )), array('user_ID' => $current_user->ID));
		}
		else {
		$wpdb->update('coinwink_settings', array( 'nexmo2' => '' ), array('user_ID' => $current_user->ID));	
		}

		if ( ( $_POST['nexmo_nr_short'] ) ){
		$wpdb->update('coinwink_settings', array( 'nexmo_nr_short' => sanitize_text_field( $_POST['nexmo_nr_short'] )), array('user_ID' => $current_user->ID));
		}
		else {
		$wpdb->update('coinwink_settings', array( 'nexmo_nr_short' => '' ), array('user_ID' => $current_user->ID));	
		}

		$message = '<b>Settings updated!</b><br><div style="margin-top:10px;margin-bottom:40px;"><a href="#sms" class="blacklink hashlink" id="newalertaccsms">Create SMS alert</a></div>';
	}
?>

<?php
	if ( is_user_logged_in() ) {
		$unique_id = $wpdb->get_var( "SELECT unique_id FROM coinwink_settings WHERE user_ID = '".$user_ID."'" ); 
	}
?>

<div id="navigation">
    <nav class="nav">
        <ul>
			<li><a href="#about" class="hashlink">About</a></li>
            <?php if ( !is_user_logged_in() ) {  ?><li><a href="<?php echo site_url(); ?>/account/#login">Log in</a></li><?php } ?>
			<?php if ( is_user_logged_in() ) {  ?><li><a href="<?php echo site_url(); ?>/account">Account</a></li><?php } ?>
			<?php if ( is_user_logged_in() ) {  ?><li><a href="<?php echo wp_logout_url(get_permalink()); ?>">Log out</a></li><?php } ?>
        </ul>
    </nav>
</div>

<div style="position:absolute;top:5px;right:20px;">
<a href="#email" id="newalertemailbutton" style="text-decoration:none;">
	<div id="currencybutton" class="circle">$</div>
</a>
<?php if ( is_user_logged_in() ) {  ?>
	<a href="#percent" id="newalertemailpercentbutton" class="hashLink" style="text-decoration:none;">
		<div id="percentbutton" class="circlegrey">%</div>
	</a>
<?php } else { ?>
	<a href="#percent" id="newalertemailpercentbutton" class="hashLink" style="text-decoration:none;">
		<div id="percentbutton" class="circlegrey">%</div>
	</a>
<?php } ?>
</div>

<script>
jQuery(document).ready(function() {
    jQuery('#left-menu').sidr({
      name: 'sidr-left',
      source: '#navigation'
    });
});

jQuery("body").on("click",function(e) {
	jQuery.sidr('close','sidr-left');
});

// This not working - when clicked on menu it closes
jQuery("#sidr-left").on("click",function(e) {
	e.stopPropagation();
});

</script>



<div style="position:absolute;top:20px;left:20px;"><a id="left-menu" href="#left-menu"><img title="Menu" src="<?php echo get_stylesheet_directory_uri(); ?>/img/menu.png" width="27px"></a></div>
<div style="position:absolute;top:72px;left:20px;padding-left:2px;"><a href="#portfolio" class="hashlink"><img title="Portfolio" src="<?php echo get_stylesheet_directory_uri(); ?>/img/portfolio.png" width="23px"></a></div>

<div style="text-align: center;">
	<br>
	<br>
	<div id="logo"><a href="<?php echo site_url(); ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/img/logo_shadow.png?8017248" width="50"></a></div>
	<div id="txtlogo"><a href="<?php echo site_url(); ?>">Coinwink</a></div>
</div>
 
 <div class="switch">
    <input type="radio" class="switch-input" name="view3" value="emailSwitch" id="emailSwitch" checked>
    <label for="emailSwitch" id="switch-label-email" class="switch-label switch-label-off">Email</label>
    <input type="radio" class="switch-input" name="view3" value="smsSwitch" id="smsSwitch">
    <label for="smsSwitch" class="switch-label switch-label-on">SMS</label>
    <span class="switch-selection"></span>
  </div>

<div class="containerloader" >


<!-- PORTFOLIO -->
<div class="container" id="portfolio" style="display:none;">

	<header>
	<h2 class="fs-title" style="color:white;">PORTFOLIO</h2>
	</header>


<?php
if ( is_user_logged_in() ) { 
	$user_ID = get_current_user_id();

?>

<!-- Logged in PORTFOLIO -->


<div id="portfolio_container">
	<div id="portfolio_empty" class="content">
	Add your first coin using the 'plus' button below.
	<br><br>
	To remove the added coin, select it in the dropdown list and click the 'minus' button.
	</div>
	
	<div id="portfolio_content" style="display:none;">
	Portfolio content
	</div>
</div>
________________________________________
<br><br>
<h3 class="fs-labels">Add or remove coin:</h3>
<select class="selectcoin" id="portfolio_dropdown" ></select>
<br>
<input type="submit" id="portfolio_add_coin" style="width:24px;" value="+" />&nbsp;&nbsp;<input type="submit" id="portfolio_remove_coin" style="width:24px;" value="-" />





<?php }
else { ?>

In order to use the PORTFOLIO feature,<br>please log in.
<br><br>
<form action="<?php echo site_url(); ?>/account/#login">
<input type="submit" class="blacklink hashLink" value="Log in">
</form>
<a href="<?php echo site_url(); ?>/account/#forgotpass" class="blacklink hashLink">Password recovery</a>
<br><br><br>
Don't have an account? SIgn up, it's free.
<br><br>
<form action="<?php echo site_url(); ?>/account/#signup">
<input type="submit" class="blacklink hashLink" value="Sign up">
</form>
<?php } ?>

</div>




<!-- Email forms -  PERCENT -->
<div class="container" id="percent" style="display:none;">

	<header>
	<h2 class="fs-title" style="color:white;">New email alert</h2>
	</header>


<?php
if ( is_user_logged_in() ) { 
	$user_ID = get_current_user_id();
?>

<!-- Logged in Email form PERCENT -->

	<form type="post" id="form_new_alert_percent_acc" action="">

	<h3 class="fs-labels">Coin to watch:</h3>
	<select class="selectcoin" name="id" id="id_percent_acc" ></select>

	<div style="font-size:10px;margin-top:-8px;margin-bottom:15px;height:12px;" id="pricediv_percent_acc"></div>		  
	<input name="coin" id="coin_percent_acc" type="hidden" value="Bitcoin" />
	<input name="symbol" id="symbol_percent_acc" type="hidden" value="BTC" />
	<input name="price_set_btc" id="price_set_btc_acc" type="hidden" value="" />
	<input name="price_set_usd" id="price_set_usd_acc" type="hidden" value="" />
	<input name="price_set_eth" id="price_set_eth_acc" type="hidden" value="" />	

	<h3 class="fs-labels">Alert me by e-mail:</h3>
	<input value="" maxlength="99" class="inputemail" id="email_percent_acc" name="email_percent" type="text" required>

	<h3 class="fs-labels" style="margin-top:10px;">Alert when price increases by:</h3>
	<input value="" maxlength="99" class="percentinput" size="8" id="plus_percent_acc" name="plus_percent" type="number" step="any" autocomplete="off">&nbsp;%&nbsp;&nbsp;
	<select name="plus_change" id="plus_change_acc" class="percentchange">
	<option value="from_now">from now</option>
	<option value="1h">in 1h. period</option>
	<option value="24h">in 24h. period</option>
	</select>
	<div id="div_plus_compared_acc" style="margin-left:40px;margin-top:-3px;margin-bottom:5px;">
	Compared to:&nbsp;
	<select name="plus_compared" id="plus_compared_acc" class="selectcurrency">
	<option value="BTC">BTC</option>
	<option value="USD">USD</option>
	<option value="ETH">ETH</option>
	</select>
	</div>
	<div id="plus_usd_acc" style="display:none;margin-left:78px;margin-top:-3px;margin-bottom:13px!important;">
	Compared to: USD
	</div>

	<h3 class="fs-labels">And/or when price decreases by:</h3>
	<input value="" maxlength="99" class="percentinput"  size="8" id="minus_percent_acc"  name="minus_percent" type="number" step="any" autocomplete="off">&nbsp;%&nbsp;&nbsp;
	<select name="minus_change" id="minus_change_acc" class="percentchange">
	<option value="from_now">from now</option>
	<option value="1h">in 1h. period</option>
	<option value="24h">in 24h. period</option>
	</select>
	<div id="div_minus_compared_acc" style="margin-left:40px;margin-top:-3px;margin-bottom:10px;">
	Compared to:&nbsp;
	<select name="minus_compared" id="minus_compared_acc" class="selectcurrency">
	<option value="BTC">BTC</option>
	<option value="USD">USD</option>
	<option value="ETH">ETH</option>
	</select>
	</div>
	<div id="minus_usd_acc" style="display:none;margin-left:78px;margin-top:-3px;margin-bottom:13px!important;">
	Compared to: USD
	</div>

	<div id="feedback_percent_acc" style="color:red;" class="feedback"></div>

	<input name="action" type="hidden" value="create_alert_percent_acc" />
	<input name="unique_id" type="hidden" value="<?php echo($unique_id); ?>">
	<input type="submit" id="create_alert_button_percent_acc" class="submit action-button" value="Create alert" />
	<div id="ajax_loader_percent_acc" style="display:none;"><img src="<?php echo get_stylesheet_directory_uri(); ?>/img/ajax_loader.gif"></div>

	</form>

<?php }
else { ?>

<!-- Logged out Email form PERCENT -->

	<form type="post" id="form_new_alert_percent" action="">

	<h3 class="fs-labels">Coin to watch:</h3>
	<select class="selectcoin" name="id" id="id_percent" ></select>

	<div style="font-size:10px;margin-top:-8px;margin-bottom:15px;height:12px;" id="pricediv_percent"></div>		  
	<input name="coin" id="coin_percent" type="hidden" value="Bitcoin" />
	<input name="symbol" id="symbol_percent" type="hidden" value="BTC" />
	<input name="price_set_btc" id="price_set_btc" type="hidden" value="" />
	<input name="price_set_usd" id="price_set_usd" type="hidden" value="" />
	<input name="price_set_eth" id="price_set_eth" type="hidden" value="" />	
	
	<h3 class="fs-labels">Alert me by e-mail:</h3>
	<input value="" maxlength="99" class="inputemail" id="email_percent" name="email_percent" type="text" required>

	<h3 class="fs-labels" style="margin-top:10px;">Alert when price increases by:</h3>
	<input value="" maxlength="99" class="percentinput" size="8" id="plus_percent" name="plus_percent" type="number" step="any" autocomplete="off">&nbsp;%&nbsp;&nbsp;
	<select name="plus_change" id="plus_change" class="percentchange">
	<option value="from_now">from now</option>
	<option value="1h">in 1h. period</option>
	<option value="24h">in 24h. period</option>
	</select>
	<div id="div_plus_compared" style="margin-left:40px;margin-top:-3px;margin-bottom:5px;">
	Compared to:&nbsp;
	<select name="plus_compared" id="plus_compared" class="selectcurrency">
	<option value="BTC">BTC</option>
	<option value="USD">USD</option>
	<option value="ETH">ETH</option>
	</select>
	</div>
	<div id="plus_usd" style="display:none;margin-left:78px;margin-top:-3px;margin-bottom:13px!important;">
	Compared to: USD
	</div>

	<h3 class="fs-labels">And/or when price decreases by:</h3>
	<input value="" maxlength="99" class="percentinput"  size="8" id="minus_percent"  name="minus_percent" type="number" step="any" autocomplete="off">&nbsp;%&nbsp;&nbsp;
	<select name="minus_change" id="minus_change" class="percentchange">
	<option value="from_now">from now</option>
	<option value="1h">in 1h. period</option>
	<option value="24h">in 24h. period</option>
	</select>
	<div id="div_minus_compared" style="margin-left:40px;margin-top:-3px;margin-bottom:10px;">
	Compared to:&nbsp;
	<select name="minus_compared" id="minus_compared" class="selectcurrency">
	<option value="BTC">BTC</option>
	<option value="USD">USD</option>
	<option value="ETH">ETH</option>
	</select>
	</div>
	<div id="minus_usd" style="display:none;margin-left:78px;margin-top:-3px;margin-bottom:13px!important;">
	Compared to: USD
	</div>

	<h3 class="fs-labels">I am not a robot:</h3>
	<div style="margin:0 auto;display:table;margin-top:3px;margin-bottom:13px;"><?php echo apply_filters( 'cptch_display', '', 'Coinwink' ); ?></div>

	<div id="feedback_percent" style="color:red;" class="feedback"></div>

	<input name="action" type="hidden" value="create_alert_percent" />
	<input type="submit" id="create_alert_button_percent" class="submit action-button" value="Create alert" />
	<div id="ajax_loader_percent" style="display:none;"><img src="<?php echo get_stylesheet_directory_uri(); ?>/img/ajax_loader.gif"></div>

	</form>

<?php } ?>

</div>




<!-- Email forms - CURRENCY -->
<div class="container" id="email" style="display:none;">

	<header>
	<h2 class="fs-title" style="color:white;">New email alert</h2>
	</header>


<?php
if ( is_user_logged_in() ) { 
	$user_ID = get_current_user_id();
?>

<!-- Logged in Email form CURRENCY -->

	<form type="post" id="form_new_alert_acc" style="margin-bottom:7px;" action="">

	<h3 class="fs-labels">Coin to watch:</h3>
	<select class="selectcoin" name="id" id="id_acc" ></select>

	<div style="font-size:10px;margin-top:-8px;margin-bottom:15px;height:12px;" id="pricediv_acc"></div>		  
	<input name="coin" id="coin_acc" type="hidden" value="Bitcoin" />
	<input name="symbol" id="symbol_acc" type="hidden" value="BTC" />

	<h3 class="fs-labels">Alert me by email:</h3>
	<input maxlength="99" class="inputemail" id="email_acc" name="email" type="text" required />

	<h3 class="fs-labels">Alert when price is above:</h3>
	<input value="" maxlength="99" class="inputdefault" size="8" id="above_acc" name="above" type="number" step="any" autocomplete="off">
	<select name="above_currency" id="above_currency_acc" class="selectcurrency">
	<option value="BTC">BTC</option>
	<option value="USD">USD</option>
	<option value="ETH">ETH</option>
	<option value="EUR">EUR</option>
	<option value="AUD">AUD</option>
	<option value="CAD">CAD</option>
	<option value="KRW">KRW</option>
	<option value="JPY">JPY</option>
	</select>

	<h3 class="fs-labels">And/or when price is below:</h3>
	<input value="" maxlength="99" class="inputdefault"  size="8" id="below_acc"  name="below" type="number" step="any" autocomplete="off">
	<select name="below_currency" id="below_currency_acc" class="selectcurrency">
	<option value="BTC">BTC</option>
	<option value="USD">USD</option>
	<option value="ETH">ETH</option>
	<option value="EUR">EUR</option>
	<option value="AUD">AUD</option>
	<option value="CAD">CAD</option>
	<option value="KRW">KRW</option>
	<option value="JPY">JPY</option>
	</select>

	<div id="feedback_acc" style="color:red;" class="feedback"></div>

	<input name="action" type="hidden" value="create_alert_acc" />
	<input name="unique_id" type="hidden" value="<?php echo($unique_id); ?>">
	<input type="submit" id="create_alert_button_acc" class="submit action-button" value="Create alert" />
	<div id="ajax_loader_acc" style="display:none;"><img src="<?php echo get_stylesheet_directory_uri(); ?>/img/ajax_loader.gif"></div>

	</form>

<?php }
else { ?>

<!-- Logged out Email form CURRENCY -->

	<form type="post" id="form_new_alert" action="">

	<h3 class="fs-labels">Coin to watch:</h3>
	<select class="selectcoin" name="id" id="id" ></select>

	<div style="font-size:10px;margin-top:-8px;margin-bottom:15px;height:12px;" id="pricediv"></div>		  
	<input name="coin" id="coin" type="hidden" value="Bitcoin" />
	<input name="symbol" id="symbol" type="hidden" value="BTC" />

	<h3 class="fs-labels">Alert me by e-mail:</h3>
	<input value="" maxlength="99" class="inputemail" id="email_out" name="email" type="text" required>

	<h3 class="fs-labels">Alert when price is above:</h3>
	<input value="" maxlength="99" class="inputdefault" size="8" id="above" name="above" type="number" step="any" autocomplete="off">
	<select name="above_currency" id="above_currency" class="selectcurrency">
	<option value="BTC">BTC</option>
	<option value="USD">USD</option>
	<option value="ETH">ETH</option>
	<option value="EUR">EUR</option>
	<option value="AUD">AUD</option>
	<option value="CAD">CAD</option>
	<option value="KRW">KRW</option>
	<option value="JPY">JPY</option>
	</select>

	<h3 class="fs-labels">And/or when price is below:</h3>
	<input value="" maxlength="99" class="inputdefault"  size="8" id="below"  name="below" type="number" step="any" autocomplete="off">
	<select name="below_currency" id="below_currency" class="selectcurrency">
	<option value="BTC">BTC</option>
	<option value="USD">USD</option>
	<option value="ETH">ETH</option>
	<option value="EUR">EUR</option>
	<option value="AUD">AUD</option>
	<option value="CAD">CAD</option>
	<option value="KRW">KRW</option>
	<option value="JPY">JPY</option>
	</select>

	<h3 class="fs-labels">I am not a robot:</h3>
	<div style="margin:0 auto;display:table;margin-top:3px;margin-bottom:13px;"><?php echo apply_filters( 'cptch_display', '', 'Coinwink' ); ?></div>

	<div class="content" style="width:260px;"><div id="feedback" style="color:red;" class="feedback"></div></div>

	<input name="action" type="hidden" value="create_alert" />
	<input type="submit" id="create_alert_button" class="submit action-button" value="Create alert" />
	<div id="ajax_loader" style="display:none;"><img src="<?php echo get_stylesheet_directory_uri(); ?>/img/ajax_loader.gif"></div>

	</form>

<?php } ?>

</div>




<!-- SMS forms - CURRENCY -->

<div class="container" id="sms" style="display:none;">

<?php $current_user = wp_get_current_user(); ?>

<?php if ( is_user_logged_in() ) { 	?>

	<header>
	<h2 class="fs-title" style="color:white;">New SMS alert</h2>
	</header>
	<div style="margin-top:-15px;margin-bottom:25px;">
	<a href="#settings" class="blacklink hashLink">API settings</a><br>
	<?php
	foreach( $wpdb->get_results("SELECT plivo1, plivo2, nexmo1, nexmo2 FROM coinwink_settings WHERE user_ID = $current_user->ID") as $key => $row) {
	if ($row->plivo1 && $row->plivo2) {
		$plivo = true;
	}
	if ($row->nexmo1 && $row->nexmo2) {
		$nexmo = true;
	}
	}
	if ($plivo && !$nexmo)
	{ ?>
	<div>Using Plivo</div>
	<?php }
	else if ($plivo && $nexmo)
	{ ?>
	<div>Using Nexmo and Plivo</div>
	<?php }
	else if (!$plivo && $nexmo)
	{ ?>
	<div>Using Nexmo</div>
	<?php }
	else { ?>
	<div style="color:red;font-weight:bold;">Please configure</div>
	<?php } ?>
	</div>
	<form type="post" id="form_new_alert_sms" action="">

	<h3 class="fs-labels">Coin to watch:</h3>
	<select class="selectcoin" name="id" id="id_sms" ></select>

	<div style="font-size:10px;margin-top:-8px;margin-bottom:14px;height:12px;" id="pricediv_sms"></div>		  
	<input name="coin" id="coin_sms" type="hidden" value="Bitcoin" />
	<input name="symbol" id="symbol_sms" type="hidden" value="BTC" />

	<h3 class="fs-labels">Phone number:<br><div style="font-size:10px;margin-bottom:2px;">It should start with the plus sign. <a href="https://support.twilio.com/hc/en-us/articles/223183008-Formatting-International-Phone-Numbers" class="blacklink" target="blank">More info</a></div></h3>
	<input maxlength="99" class="inputemail" id="phone" name="phone" type="text" placeholder="e.g. +14155552671" required>

	<h3 class="fs-labels">Alert when price is above:</h3>
	<input value="" maxlength="99" class="inputdefault" size="8" id="above_sms" name="above_sms" type="number" step="any" autocomplete="off">
	<select name="above_currency_sms" id="above_currency_sms" class="selectcurrency">
	<option value="BTC">BTC</option>
	<option value="USD">USD</option>
	<option value="ETH">ETH</option>
	<option value="EUR">EUR</option>
	<option value="AUD">AUD</option>
	<option value="CAD">CAD</option>
	<option value="KRW">KRW</option>
	<option value="JPY">JPY</option>
	</select>

	<h3 class="fs-labels">And/or when price is below:</h3>
	<input value="" maxlength="99" class="inputdefault"  size="8" id="below_sms"  name="below_sms" type="number" step="any" autocomplete="off">
	<select name="below_currency_sms" id="below_currency_sms" class="selectcurrency">
	<option value="BTC">BTC</option>
	<option value="USD">USD</option>
	<option value="ETH">ETH</option>
	<option value="EUR">EUR</option>
	<option value="AUD">AUD</option>
	<option value="CAD">CAD</option>
	<option value="KRW">KRW</option>
	<option value="JPY">JPY</option>
	</select>

	<div style="height:5px;">&nbsp;</div>
	<div id="reserved_message"></div>
	<div style="height:5px;">&nbsp;</div>

	<div id="feedback_sms" class="feedback" style="color:red;"></div>

	<input name="action" type="hidden" value="create_alert_sms" />
	<input name="unique_id" type="hidden" value="<?php echo($unique_id); ?>">
	<?php if (!$plivo && !$nexmo) { ?>
	<input type="submit" style="cursor:not-allowed!important;" value="Create alert" disabled/>
	<div style="margin-top:-5px;"><a href="#settings" class="blacklink hashLink" style="color:red;">Configure API settings first</a></div>
	<?php }	else { ?>
	<input type="submit" id="create_alert_button_sms" class="submit action-button" value="Create alert" />
	<div id="ajax_loader_sms" style="display:none;"><img src="<?php echo get_stylesheet_directory_uri(); ?>/img/ajax_loader.gif"></div>
	<?php } ?>
	</form>
</div>

<?php } else { ?>

	<header>
	<h2 class="fs-title" style="color:white;">SMS alerts</h2>
	</header>
<div style="width:250px;margin: 0 auto;">
<!-- Account is needed only for SMS alerts. You can use email alerts without the account.<br><br><br> -->
<b>Have an account?</b><br><br>
<form action="<?php echo site_url(); ?>/account/#login">
<input type="submit" class="blacklink hashLink" value="Log in">
</form>
<a href="<?php echo site_url(); ?>/account/#forgotpass" class="blacklink hashLink">Password recovery</a><br>
<br>
<br>
<br>
<b>New user?</b><br><br>
To start using SMS alerts service, please create an account.
<br>
<br>
<form action="<?php echo site_url(); ?>/account/#signup">
<input type="submit" class="blacklink hashLink" value="Sign up">
</form>
<br>
SMS alerts service is free, Coinwink does not charge anything. You will need to use your personal API key that you can get for free from Plivo or Nexmo.
<br>
<br>

</div>
</div>

<?php } ?>



<div class="container" id="settings" style="display:none;">
	<header>
	<h2 class="fs-title" style="color:white;">API settings</h2>
	</header>

	<div class="content">
	<div style="color:green;margin-bottom:10px;margin-top:10px;" class="feedback"><?php echo ($message); ?></div>
<b>Nexmo API Settings</b><br>
<br>
<form method="post" action="<?php the_permalink(); ?>#settings">
				<p>
					Nexmo KEY:<br>
					<input type="text" name="nexmo1" value="<?php echo(esc_html($wpdb->get_var( "SELECT nexmo1 FROM coinwink_settings WHERE user_ID = $current_user->ID" ))); ?>">
				</p>
				<p>
					Nexmo SECRET:<br>
					<input type="text" name="nexmo2" value="<?php echo(esc_html($wpdb->get_var( "SELECT nexmo2 FROM coinwink_settings WHERE user_ID = $current_user->ID" ))); ?>">
				</p>
				<p>
					Nexmo phone nr or shortcode:<br>
					<div style="font-size:10px;margin-bottom:2px;">Required for USA and some other countries.<br><a href="https://medium.com/@coinwink/how-to-setup-coinwink-sms-crypto-price-alerts-6d0adf7d43aa" class="blacklink" target="blank">More info</a></div>
					<input type="text" name="nexmo_nr_short" value="<?php echo(esc_html($wpdb->get_var( "SELECT nexmo_nr_short FROM coinwink_settings WHERE user_ID = $current_user->ID" ))); ?>">
				</p>

				<p>
					<input type="submit" value="Save">
				</p>
			
<br>
<br>
<b>Plivo API Settings</b><br>
<br>
				<p>
					Plivo AUTH ID:<br>
					<input type="text" name="plivo1" value="<?php echo(esc_html($wpdb->get_var( "SELECT plivo1 FROM coinwink_settings WHERE user_ID = $current_user->ID" ))); ?>">
				</p>
				<p>
					Plivo AUTH TOKEN:<br>
					<input type="text" name="plivo2" value="<?php echo(esc_html($wpdb->get_var( "SELECT plivo2 FROM coinwink_settings WHERE user_ID = $current_user->ID" ))); ?>">
				</p>

				<input name="action" type="hidden" id="action" value="update-settings" />
				<p>
					<input type="submit" value="Save">
				</p>
</form>
<br>
<br>

Coinwink is not charging anything for SMS service, but you need to provide your own SMS API details. To get these, you need to sign up on <a href="https://www.nexmo.com/" target="_blank" class="blacklink">Nexmo</a> or <a href="https://www.plivo.com/" target="_blank" class="blacklink">Plivo</a> service. The sign up is free and they give 2$ free SMS credit. The free credit works only with the single number that is linked to your Nexmo or Plivo account.
<br>
<br>
For both Nexmo and Plivo, you will find your API details on the first page after you log in to your Nexmo or Plivo account. Enter these API details here and click Save button. Then you can start using SMS alerts service.
<br>
<br>
Please enter details for at least one SMS service provider. You can also enter details for both. When sending alerts, Coinwink first checks Nexmo, if the service is not configured or unavailable, then it tries to use Plivo. In any case, Coinwink will always use only 1 service - the first one that's available.
<br>
<br>
Coinwink cannot check if you entered the correct settings. If you are not sure, please create a test alert first. Coinwink is sending alerts every 3 minutes.
<br>
<br>
For the USA you need to buy a virtual number in order to receive alerts. This can be done easily on Nexmo. You will then receive SMS alerts. For other countries there can be some other specific restrictions. If you do not receive alerts, please send and email to cont&#97;&#99;&#x74;&#x40;&#x63;&#x6f;&#x69;nwin&#107;&#46;&#x63;&#x6f;&#x6d; and we will try to find a solution.
<br>
<br>
For a quick step-by-step guide how to set up alerts (USA included), please see this <a target="_blank" class="blacklink" href="https://medium.com/@coinwink/how-to-setup-coinwink-sms-crypto-price-alerts-6d0adf7d43aa">Medium post</a>.
<br>
<br>
<br>
<div style="margin-bottom:6px;"><a href="#sms" class="blacklink hashLink">Create SMS alert</a></div>
</div>
</div>

<div class="container" id="created_alert" style="display:none;">
	<h3 class="fs-labels">Your alert has been created.<br><br>Please check your e-mail<br> for your unique ID that you can<br> use to manage your alert(-s).
	<br><br>
	<a href="#email" id="newalertemail" class="blacklink hashlink">Create new alert</a>
	</h3>
</div>

<div class="container" id="created_alert_acc_sms" style="display:none;">
	<h3 class="fs-labels">Your SMS alert has been created.
	<br><br>
	<a href="#sms" id="newalertaccsms" class="blacklink hashlink">Create new alert</a>
	</h3>
</div>

<div class="container" id="created_alert_acc_email" style="display:none;">
	<h3 class="fs-labels">Your email alert has been created.
	<br><br>
	<a href="#email" id="newalertaccemail" class="blacklink hashlink">Create new alert</a>
	</h3>
</div>

<div class="container" id="created_alert_percent" style="display:none;">
	<h3 class="fs-labels">Your email alert has been created.
	<br><br>
	<a href="#percent" id="newalertemailpercent" class="blacklink hashlink">Create new alert</a>
	</h3>
</div>

<div class="container" id="created_alert_percent_acc" style="display:none;">
	<h3 class="fs-labels">Your email alert has been created.
	<br><br>
	<a href="#percent" id="newalertemailpercentacc" class="blacklink hashlink">Create new alert</a>
	</h3>
</div>


<div class="container" style="display:none;" id="container3">
	<header>
	<h2 class="fs-title" style="color:white;">Manage alerts</h2>
	</header>

	<form type="post" id="manage_alerts" action="">
	<h3 class="fs-labels" style="margin-bottom:4px;">Enter your unique ID<br> that you got when created alert:</h3>
	<input value="" maxlength="99" class="inputemail" id="unique_id" name="unique_id" type="text" required>

	<h3 class="fs-labels" style="margin-top:12px;">I am not a robot:</h3>
	<div style="margin:0 auto;display:table;margin-top:3px;margin-bottom:15px;"><?php echo apply_filters( 'cptch_display', '', 'Coinwink' ); ?></div>

	<div id="feedback3" class="feedback" style="color:red;"></div>

	<input name="action" type="hidden" value="manage_alerts" />
	<input type="submit" class="submit action-button" value="Enter" />
	</form> 
	
	<br>
	<br>
	To manage alerts more comfortably,<br> use Coinwink with the <a href="https://coinwink.com/account/#signup" class="blacklink hashlink">account</a>.
</div>


<div class="container" style="display:none;" id="container4"> 
	<header>
	<h2 class="fs-title" style="color:white;">My alerts</h2>
	</header>
	<div style="margin-top:-20px;margin-bottom:10px;" class="feedback"  id="feedback4"><div id="manage_alerts_loader" style="padding-top:20px;"><img src="<?php echo get_stylesheet_directory_uri(); ?>/img/ajax_loader.gif"></div></div>

	<br>
	<div class="content">With the last deleted alert, your unique ID and email associated with it will also be deleted from the database. After this, when you create a new alert, you will receive a new ID.</div>
</div>

<?php if ( is_user_logged_in() ) { ?>

<div class="container" style="display:none;" id="manage_alerts_acc"> 
	<header>
	<h2 class="fs-title" style="color:white;">My alerts</h2>
	</header>
	<form type="post" id="manage_alerts_acc_form" action="">
	<input type="hidden" name="unique_id" value="<?php echo($unique_id); ?>">
	<input type="hidden" name="action" value="manage_alerts_acc">

	</form>

	
	<div style="margin-top:-20px;margin-bottom:10px;" id="manage_alerts_acc_feedback"><div id="manage_alerts_acc_loader" style="padding-top:20px;"><img src="<?php echo get_stylesheet_directory_uri(); ?>/img/ajax_loader.gif"></div></div>
</div>

<?php } ?>

<!-- </div> -->

<div class="container" style="display:none;" id="about"> 
	<header>
	<h2 class="fs-title" style="color:white;">About</h2>
	</header>
	<div style="padding-left:20px; padding-right:20px;text-align:left;overflow:visible;">
<b>About Coinwink</b>
<br><br>
Coinwink is an open source, privacy-focused, cryptocurrency price alerts service.
<br><br>
Currently, Coinwink has more than 10k registered users and more than 100k active alerts. Because of the thorough optimization and a well maintained server, the app works fast and delivers alerts on time. It can easily be scaled ten times or more.
<br><br>
The aim of Coinwink is to free people from constant crypto price checking. Coinwink works like a bot that checks prices and alerts the user when the threshold is reached. This creates a more detached perspective for the user and can result in better overall trading outcomes.
<br><br>
Coinwink is a privacy-focused service. The data of the user is never shared with third parties. If the user deletes the account (or when the last alert is deleted, when using Coinwink without the account), all data related to the user is also deleted.
<br><br><br>
<b>Tips & Tricks</b>
<br><br>
You can place Coinwink app icon on your mobile device desktop. To do this, open coinwink.com in your mobile browser, click settings and then Add to Home Screen. You will then be able to use Coinwink similarly as a mobile app.
<br><br>
You can use (and bookmark) a custom url address to pre-select your coin, e.g. https://coinwink.com/eth (put a coin symbol after the slash).
<br><br>
<a href="https://medium.com/@coinwink/coinwink-tips-and-tricks-a4ff230de7c6" target="_blank" class="blacklink">Read more</a>
<br><br><br>
<b>Support Coinwink</b>
<br><Br>
You can donate ETH or tokens and support the further development of Coinwink: <span style="font-size:10px;">0x1095C4Dcc8FCd28f35bb4180A4dc8e15A80cf424</span>
<br><br><br>
<b>Keep in touch</b>
<br><br>
<a href="https://twitter.com/coinwink" target="_blank" class="blacklink">Twitter</a><br>
<a href="https://facebook.com/coinwink" target="_blank" class="blacklink">Facebook</a><br>
<a href="https://medium.com/@coinwink" target="_blank" class="blacklink">Medium</a><br>
<a href="https://github.com/dziungles/Coinwink" target="_blank" class="blacklink">GitHub</a>
<br><br>
cont&#97;&#99;&#x74;&#x40;&#x63;&#x6f;&#x69;nwin&#107;&#46;&#x63;&#x6f;&#x6d;
<br><br></div>
</div>

</div>

<?php echo do_shortcode('[footer_shortcode]'); ?>

<script type="text/javascript">
	var jqueryarray = <?php echo json_encode($newarrayunserialized); ?>;
	var ajax_url = "<?php echo site_url(); ?>/wp-admin/admin-ajax.php";
	var security_url = "&security=<?php echo $ajax_nonce; ?>";
</script>


<script type="text/javascript">

// Select2 optimization as described here:
// https://stackoverflow.com/questions/32756698/how-to-enable-infinite-scrolling-in-select2-4-0-without-ajax

jQuery.fn.select2.amd.define('select2/data/customAdapter', ['select2/data/array', 'select2/utils'],
function (ArrayData, Utils) {
    function CustomDataAdapter($element, options) {
        CustomDataAdapter.__super__.constructor.call(this, $element, options);
    }
 
    Utils.Extend(CustomDataAdapter, ArrayData);
 
    CustomDataAdapter.prototype.current = function (callback) {
         var found = [],
            findValue = null,
             initialValue = this.options.options.initialValue,
            selectedValue = this.$element.val(),
            jsonData = this.options.options.jsonData,
            jsonMap = this.options.options.jsonMap;
        
        if (initialValue !== null){
            findValue = initialValue;
            this.options.options.initialValue = null;  // <-- set null after initialized              
        }
         else if (selectedValue !== null){
            findValue = selectedValue;
        }
        
         if(!this.$element.prop('multiple')){
            findValue = [findValue];
            this.$element.html();     // <-- if I do this for multiple then it breaks
        }

        // Query value(s)
        for (var v = 0; v < findValue.length; v++) {              
            for (var i = 0, len = jsonData.length; i < len; i++) {
                if (findValue[v] == jsonData[i][jsonMap.id]){
                   found.push({id: jsonData[i][jsonMap.id], text: jsonData[i][jsonMap.text]}); 
                   if(this.$element.find("option[value='" + findValue[v] + "']").length == 0) {
                       this.$element.append(new Option(jsonData[i][jsonMap.text], jsonData[i][jsonMap.id]));
                   }
                   break;   
                }
            }
        }
        
        // Set found matches as selected
        this.$element.find("option").prop("selected", false).removeAttr("selected");            
        for (var v = 0; v < found.length; v++) {            
            this.$element.find("option[value='" + found[v].id + "']").prop("selected", true).attr("selected","selected");            
        }

        // If nothing was found, then set to top option (for single select)
        if (!found.length && !this.$element.prop('multiple')) {  // default to top option 
            found.push({id: jsonData[0][jsonMap.id], text: jsonData[0][jsonMap.text]}); 
             this.$element.html(new Option(jsonData[0][jsonMap.text], jsonData[0][jsonMap.id], true, true));
        }
        
        callback(found);
    };        
 
    CustomDataAdapter.prototype.query = function (params, callback) {
        if (!("page" in params)) {
            params.page = 1;
        }

        var jsonData = this.options.options.jsonData,
            pageSize = this.options.options.pageSize,
            jsonMap = this.options.options.jsonMap;

        var results = jQuery.map(jsonData, function(obj) {
            // Search
            if(new RegExp(params.term, "i").test(obj[jsonMap.text])) {
                return {
                    id:obj[jsonMap.id],
                    text:obj[jsonMap.text]
                };
            }
        });

        callback({
            results:results.slice((params.page - 1) * pageSize, params.page * pageSize),
            pagination:{
                more:results.length >= params.page * pageSize
            }
        });
    };

    return CustomDataAdapter;

});

var jsonAdapter=jQuery.fn.select2.amd.require('select2/data/customAdapter');

// Get coin symbol from url
var link = document.location.href.split('/');
var lastSymbol = link.length - 1;
var urlSymbol = link[lastSymbol].replace(/[^a-z0-9]/gi,'');
var urlSymbolUp = urlSymbol.toUpperCase();
if (urlSymbol && (urlSymbolUp != "EMAIL") && (urlSymbolUp != "SMS") && (urlSymbolUp != "MANAGEALERTSACC") && (urlSymbolUp != "SETTINGS") && (urlSymbolUp != "PERCENT") && (urlSymbolUp != "PORTFOLIO")) {
	for(var i=0; i < jqueryarray.length; i++) {
		var coin = jqueryarray[i];
		if (coin['symbol'] == urlSymbolUp) {
			initialValue = coin['id'];
			break;
		}
	}
}
else initialValue = "bitcoin";

jQuery(document).ready(function() {
    var myOptions = {
        ajax: {},
        jsonData: jqueryarray,
        jsonMap: {id: "id", text: "text"},
        initialValue: initialValue,
        pageSize: 50,
        dataAdapter: jsonAdapter
    };
 
jQuery(".selectcoin").select2(myOptions);
});
</script>


<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/coinwink_portfolio.js?0002"></script>

<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/coinwink.js?1552"></script>

<script>
	// @todo: Need to to show email/phone instantly after the initial input, and then if data is changed
	var user_email = "<?php echo(esc_html($wpdb->get_var( "SELECT email FROM coinwink_settings WHERE user_ID = $current_user->ID" ))); ?>";
	jQuery("#email_acc").val(user_email);
	jQuery("#email_percent_acc").val(user_email);
	var user_phone_nr = "<?php echo(esc_html($wpdb->get_var( "SELECT phone_nr FROM coinwink_settings WHERE user_ID = $current_user->ID" ))); ?>";
	jQuery("#phone").val(user_phone_nr);
</script>

<script src="//cdn.jsdelivr.net/jquery.sidr/2.2.1/jquery.sidr.min.js"></script>

</body>
</html>

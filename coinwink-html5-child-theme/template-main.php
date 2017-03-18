<?php /* Template Name: Coinwink Template */ get_header(); ?>

<?php $ajax_nonce = wp_create_nonce( "my-special-string" ); ?>

<?php
	// Get coin price data from the database
	$resultdb = $wpdb->get_results( "SELECT json FROM coinwink_json" , ARRAY_A);
	$newarrayjson = $resultdb[0][json];
	$newarrayunserialized = unserialize($newarrayjson);
?>


<div style="text-align: center;">
	<br>
	<br>
	<div id="logo"><a href="https://coinwink.com"><img src="<?php echo get_stylesheet_directory_uri(); ?>/img/logo_shadow.png" width="50"></a></div>
	<div id="txtlogo"><a href="https://coinwink.com">Coinwink</a></div>
</div>


<div class="container" id="container1">

	<header>
	<h2 class="fs-title" style="color:white;">Create new alert</h2>
	</header>

	<form type="post" id="form_new_alert" action="">

	<h3 class="fs-labels">Coin to watch:</h3>
	<select class="selectcoin" name="symbol" id="symbol" >
	<?php 
	// Gets coin data in html from the database
	$coin_list = $wpdb->get_var( "SELECT html FROM coinwink_html"); 
	echo($coin_list);
	?>	
	</select>

	<div style="font-size:10px;margin-top:-8px;margin-bottom:13px;" id="pricediv"></div>		  
	<input name="coin" id="coin" type="hidden" value="Bitcoin" />

	<h3 class="fs-labels">Alert me by e-mail:</h3>
	<input value="" maxlength="99" class="inputemail" id="email" name="email" type="text" required>

	<h3 class="fs-labels">Alert when price is above:</h3>
	<input value="" maxlength="99" class="inputdefault" size="8" id="above" name="above" type="text" autocomplete="off">
	<select name="above_currency" id="above_currency" class="selectcurrency">
	<option value="BTC">BTC</option>
	<option value="USD">USD</option>
	</select>

	<h3 class="fs-labels">And/or when price is below:</h3>
	<input value="" maxlength="99" class="inputdefault"  size="8" id="below"  name="below" type="text" autocomplete="off">
	<select name="below_currency" id="below_currency" class="selectcurrency">
	<option value="BTC">BTC</option>
	<option value="USD">USD</option>
	</select>

	<h3 class="fs-labels">I am not a robot:</h3>
	<div style="margin:0 auto;display:table;margin-top:3px;margin-bottom:13px;"><?php echo apply_filters( 'cptch_display', '', 'Coinwink' ); ?></div>

	<div id="feedback" style="color:red;"></div>

	<input name="action" type="hidden" value="create_alert" />
	<input type="submit" id="create_alert_button" class="submit action-button" value="Create alert" />
	<div id="ajax_loader" style="display:none;"><img src="<?php echo get_stylesheet_directory_uri(); ?>/img/ajax_loader.gif"></div>

	</form>

</div>


<div class="container" id="container2" style="display:none;">
	<h3 class="fs-labels">Your alert has been created.<br><br>Please check your e-mail<br> for your unique ID that you can<br> use to manage your alert(-s).
	<br><br>
	<a href="#" id="link_create_alert">Create new alert</a>
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

	<div id="feedback3" style="color:red;"></div>

	<input name="action" type="hidden" value="manage_alerts" />
	<input type="submit" class="submit action-button" value="Enter" />
	</form> 
</div>


<div class="container" style="display:none;" id="container4"> 
	<header>
	<h2 class="fs-title" style="color:white;">My alerts</h2>
	</header>
	<div style="margin-top:-20px;margin-bottom:10px;" id="feedback4"></div>
</div>


<div class="container" style="display:none;" id="container5"> 
	<header>
	<h2 class="fs-title" style="color:white;">Tips &amp; tricks</h2>
	</header>
	<div style="margin-bottom:0px;padding-left:25px;padding-right:25px;">
	You can use (and bookmark) a custom url address to pre-select your coin, e.g. https://coinwink.com/eth (put coin symbol after the slash).
	<br><br>
	You can place a Coinwink icon on your mobile device desktop. To do this, open Coinwink on your mobile, click settings and then Add to Home Screen.
	<br><br>
	To create an alert you don't need an account or to click on a confirmation link. Your alert will be created instantly. You will receive an unique ID to your e-mail. You can use this ID to manage your alerts.
	<br><br>
	Your e-mail address will be deleted from Coinwink database after the last alert will be sent. You can also delete your alerts manually - your e-mail address and unique ID will be deleted also. When you create a new alert, you will get a new ID.
	<br><br>
	<a href="#" id="link_create_alert2">Create new alert</a>
	</div>
</div>


<footer style="text-align: center;">
	
	<div id="link_new_alert" style="display:none;margin:0 auto;">
		<a href="#">
		New alert
		</a>
	</div>
	
	<div id="link_manage_alerts" style="margin:0 auto;">
		<a href="#">
		Manage alerts
		</a>
	</div>
	
	<div style="margin-top:35px;margin-bottom:0px;font-size:10px;color:#bfbfbf;">
	Free automated service. Open source.<br>
	No accounts. E-mails are not kept in the database.<br>
	Based on Coinmarketcap.com API.<br>
	<a href="#" id="link_tips_tricks">Tips &amp; tricks</a></div>

	<div style="margin:0 auto;margin-top:22px;">
	<a href="https://twitter.com/Coinwink" target="_blank">
	<img src="<?php echo get_stylesheet_directory_uri(); ?>/img/icon_twitter.png" alt="Twitter" title="Twitter" width="20"></a>
	<a href="https://github.com/dziungles/Coinwink" target="_blank" style="padding-left:6px;">
	<img src="<?php echo get_stylesheet_directory_uri(); ?>/img/icon_github.png" title="GitHub" alt="GitHub" width="20"></a>
	</div>	

	<div style="color:#bfbfbf;margin:0 auto;margin-top:8px;margin-bottom:20px;font-size: 10px;">
	cont&#97;&#99;&#x74;&#x40;&#x63;&#x6f;&#x69;nwin&#107;&#46;&#x63;&#x6f;&#x6d;<br>
	BTC: 14n5HkSSBMYSeZwfTdbys2Pnbhoh7TgT9b
	</div>

</footer>


<script type="text/javascript">
	jQuery('.selectcoin').select2();
</script>

<script type="text/javascript">
	var jqueryarray = <?php echo json_encode($newarrayunserialized); ?>;
	var ajax_url = "<?php echo site_url(); ?>/wp-admin/admin-ajax.php";
	var security_url = "&security=<?php echo $ajax_nonce; ?>";
</script>

<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/coinwink.js"></script>

<script>
	(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
	ga('create', 'UA-57930548-9', 'auto');
	ga('send', 'pageview');
</script>

</body>
</html>
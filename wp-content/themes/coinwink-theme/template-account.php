<?php /* Template Name: Coinwink - Account */ get_header(); ?>

<?php $ajax_nonce = wp_create_nonce( "my-special-string" ); ?>

<?php
	// Get coin price data from the database
	$resultdb = $wpdb->get_results( "SELECT json FROM coinwink_json" , ARRAY_A);
	$newarrayjson = $resultdb[0][json];
	$newarrayunserialized = unserialize($newarrayjson);
?>


<div id="navigation">
    <nav class="nav">
        <ul>
			<li><a href="<?php echo site_url(); ?>/#about">About</a></li>
            <li><a href="<?php echo site_url(); ?>/#sms">New alert</a></li>
			<?php if ( is_user_logged_in() ) {  ?><li><a href="<?php echo wp_logout_url(get_permalink()); ?>">Log out</a></li><?php } ?>
        </ul>
    </nav>
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
<div style="position:absolute;top:72px;left:20px;padding-left:2px;"><a href="/#portfolio"><img title="Portfolio" src="<?php echo get_stylesheet_directory_uri(); ?>/img/portfolio.png" width="23px"></a></div>

<div style="text-align: center;">
	<br>
	<br>
	<div id="logo"><a href="<?php echo site_url(); ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/img/logo_shadow.png" width="50"></a></div>
	<div id="txtlogo"><a href="<?php echo site_url(); ?>">Coinwink</a></div>
</div>

<?php 
if ( is_user_logged_in() ) {
   ?>
   <div class="container" id="account"">

	<header>
	<h2 class="fs-title" style="color:white;">Account</h2>
	</header>


<div id="login-form" class="login-form-container">
<div class="content">
	<b>You are logged in</b><br>
	<br><br>
	<div style="margin:0 auto;">
	<a href="<?php echo site_url(); ?>/#manage_alerts_acc" id="manage_alerts_acc_link" class="blacklink hashlink">
	Manage alerts
	</a>
	</div>
	<br><br>
	<?php if ( is_user_logged_in() && !current_user_can( 'manage_options' ) ) {  ?>
	<a href="#" class="blacklink" id="deleteacchref">Delete account</a><br>
	<div id="deleteaccdiv" style="display:none;">
	<br>
	Are you sure you want to delete your account? This cannot be undone. Your all data will be lost.
	<br><br>
	<input type="submit" id="delete_my_acc_button" value="Delete my account" />
	<br>
	</div>
	<script>
	jQuery("#deleteacchref").click(function() {
		jQuery("#deleteaccdiv").attr("style", "display:inline");
	});
	</script>
	<?php }; ?>
	<br><br>
	To change your password, please log out and then use password recovery form.
		

	<br><br>
	<a href="<?php echo wp_logout_url(get_permalink()); ?>" class="blacklink">Log out</a>
	<br>
</div>
</div>
</div>
<?php
}
else {
echo do_shortcode('[custom-register-form]');

} ?>


<?php echo do_shortcode('[footer_shortcode]'); ?>


<script type="text/javascript">
	jQuery('.selectcoin').select2();
</script>

<script type="text/javascript">
	var jqueryarray = <?php echo json_encode($newarrayunserialized); ?>;
	var ajax_url = "<?php echo site_url(); ?>/wp-admin/admin-ajax.php";
	var security_url = "&security=<?php echo $ajax_nonce; ?>";
</script>

<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/coinwink.js?1534"></script>



<script src="//cdn.jsdelivr.net/jquery.sidr/2.2.1/jquery.sidr.min.js"></script>

</body>
</html>
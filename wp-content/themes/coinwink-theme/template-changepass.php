<?php /* Template Name: Coinwink - Changepass */ get_header(); ?>

<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/jquery-3.3.1.min.js"></script>

<?php $ajax_nonce = wp_create_nonce( "my-special-string" ); ?>

<?php

?>

<div style="text-align: center;">
    <div style="height:27px;"></div>
    <div id="logo" style="width:44px;-webkit-filter: drop-shadow(0 1px 1px rgba(0,0,0,.7));filter: drop-shadow(0 1px 1px rgba(0,0,0,.7));">
        <a href="<?php echo site_url(); ?>">
            <img src="https://coinwink.com/img/coinwink-crypto-alerts-logo.png" width="44" alt="Coinwink Crypto Alerts">
        </a>
    </div>
	<div id="txtlogo"><a href="<?php echo site_url(); ?>">Coinwink</a></div>
</div>

<?php 
if ( is_user_logged_in() ) {
   ?>
   <div class="container" id="account"">

	<header>
	<h2 class="text-header" style="color:white;">Account</h2>
	</header>


<div id="login-form" class="login-form-container">
	You are already logged in.

</div>
</div>
<?php
}
else { 
	echo do_shortcode('[custom-password-reset-form]');
}
?>


<?php echo do_shortcode('[footer_shortcode]'); ?>



<script type="text/javascript">
	var jqueryarray = "";
	var ajax_url = "<?php echo site_url(); ?>/wp-admin/admin-ajax.php";
	var security_url = "&security=<?php echo $ajax_nonce; ?>";
</script>


</body>
</html>
<?php /* Template Name: Coinwink - Changepass */ get_header(); ?>

<?php $ajax_nonce = wp_create_nonce( "my-special-string" ); ?>


<div id="navigation">
    <nav class="nav">
        <ul>
            <li><a href="<?php echo site_url(); ?>/#sms">SMS alert</a></li>
            <li><a href="<?php echo site_url(); ?>/#email">Email alert</a></li>
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




<div style="position:absolute;top:20px;left:20px;"><a id="left-menu" href="#left-menu"><img id="Menu" src="<?php echo get_stylesheet_directory_uri(); ?>/img/menu.png" width="27px"></a></div>


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
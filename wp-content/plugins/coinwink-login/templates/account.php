<!-- Sign up -->
<div class="container" id="signup" style="display:none;">

	<header>
	<h2 class="fs-title" style="color:white;">Account</h2>
	</header>

	<a href="#signup" class="blacklink hashLink">Sign up</a> | <a href="#login" class="blacklink hashLink">Login</a>
	<br>
	<br>
	

	<div class="content">

		<!-- Show errors if there are any -->
		<?php if ( count( $attributes['errors'] ) > 0 ) : ?>
			<?php foreach ( $attributes['errors'] as $error ) : ?>
				<p style="color:red;font-weight:bold;margin-top:10px;margin-bottom:10px;">
					Error: <?php echo $error; ?>
				</p>
			<?php endforeach; ?>
		<?php endif; ?>

		<div id="register-form" class="widecolumn">
			<form id="signupform" action="<?php echo wp_registration_url(); ?>" method="post">

				<p class="fs-labels" style="margin-bottom:15px;">
					<b>Create new account</b>
				</p>

				<p class="fs-labels">
					<label for="email">Your email address:</label><br>
					<input type="text" name="email" id="email_address" required>
				</p>

				<h3 class="fs-labels">I am not a robot:</h3>
				<div style="margin:0 auto;display:table;margin-top:3px;margin-bottom:15px;"><?php echo apply_filters( 'cptch_display', '', 'Coinwink' ); ?></div>

				<p class="signup-submit">
					<input type="submit" name="submit" class="register-button" value="Sign up"/>
				</p>
				

				
				<p class="form-row" style="margin-top:10px;">
					You will receive a link to set your password to the address you specify above.
				</p>
			</form>
		</div>
	</div>
</div>


<!-- Login -->
<div class="container" id="login" style="display:none;">

	<header>
	<h2 class="fs-title" style="color:white;">Account</h2>
	</header>

	<a href="#signup" class="blacklink hashLink">Sign up</a> | <a href="#login" class="blacklink hashLink">Login</a>
	<br>
	<br>

	<div class="content">

		<p class="fs-labels" style="margin-bottom:15px;">
			<b>Login to your account</b>
		</p>

		<?php
			// Error messages
			$errors = array();
			if ( isset( $_REQUEST['login'] ) ) {
				$error_codes = explode( ',', $_REQUEST['login'] );

				foreach ( $error_codes as $code ) {
					$errors []= $this->get_error_message( $code );
				}
			}
			$attributes['errors'] = $errors;

			// Check if user just logged out
			$attributes['logged_out'] = isset( $_REQUEST['logged_out'] ) && $_REQUEST['logged_out'] == true;

			// Check if the user just registered
			$attributes['registered'] = isset( $_REQUEST['registered'] );

			// Check if the user just requested a new password
			$attributes['lost_password_sent'] = isset( $_REQUEST['checkemail'] ) && $_REQUEST['checkemail'] == 'confirm';

			// Check if user just updated password
			$attributes['password_updated'] = isset( $_REQUEST['password'] ) && $_REQUEST['password'] == 'changed';
		?>

		<!-- Show errors if there are any -->
		<?php if ( count( $attributes['errors'] ) > 0 ) : ?>
			<div style="margin-top:10px;margin-bottom:15px;">
			<?php foreach ( $attributes['errors'] as $error ) : ?>
				<div style="color:red;font-weight:bold;">
					Error: <?php echo $error; ?>
				</div>
			<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<div id="login-form" class="login-form-container">
		
			<form method="post" action="<?php echo wp_login_url(); ?>">
				<p class="login-username">
					<label for="user_login">Email: </label><br>
					<input type="text" name="log" id="user_login" required>
				</p>
				<p class="login-password">
					<label for="user_pass">Password: </label><br>
					<input type="password" name="pwd" id="user_pass" required>
				</p>

				<label><input name="rememberme" type="checkbox" id="rememberme" value="forever" <?php checked( $rememberme ); ?> /> Remember me</label>
				<p class="login-submit">
					<input type="submit" value="<?php _e( 'Login', 'personalize-login' ); ?>">
				</p>
			</form>
			
			<a class="blacklink hashLink" href="#forgotpass">
			Forgot your password?
			</a>
		</div>
	
	</div>
	
</div>

<!-- Password recovery - #confirm -->
<div class="container" id="confirm" style="display:none;">

	<header>
	<h2 class="fs-title" style="color:white;">Account</h2>
	</header>

	<a href="#signup" class="blacklink hashLink">Sign up</a> | <a href="#login" class="blacklink hashLink">Login</a>
	<br>
	<br>

	<div class="content">

		<div id="login-form" class="login-form-container">
			Please check your email to confirm password reset.
		</div>

	</div>

</div>

<!-- Password recovery - #forgotpass -->
<div class="container" id="forgotpass" style="display:none;">

	<header>
	<h2 class="fs-title" style="color:white;">Account</h2>
	</header>

	<a href="#signup" class="blacklink hashLink">Sign up</a> | <a href="#login" class="blacklink hashLink">Login</a>
	<br>
	<br>

	<div class="content">

		<div id="password-lost-form" class="widecolumn">
			<p class="fs-labels" style="margin-bottom:15px;">
				<b>Password recovery</b>
			</p>

			<!-- Show errors if there are any -->
			<?php
			$attributes['errors'] = array();
				if ( isset( $_REQUEST['errors'] ) ) {
					$error_codes = explode( ',', $_REQUEST['errors'] );

					foreach ( $error_codes as $error_code ) {
						$attributes['errors'] []= $this->get_error_message( $error_code );
					}
				} 
				
			?>

			<?php if ( count( $attributes['errors'] ) > 0 ) : ?>
				<div style="margin-top:10px;margin-bottom:15px;">
				<?php foreach ( $attributes['errors'] as $error ) : ?>
					<div style="color:red;font-weight:bold;">
						Error: <?php echo $error; ?>
					</div>
				<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<p>Enter your email address and we'll send you a link you can use to pick a new password:</p>

			<form id="lostpasswordform" action="<?php echo wp_lostpassword_url(); ?>" method="post">
				<p class="form-row">
				
					<input type="text" name="user_login" id="user_login" required>
				</p>

				<h3 class="fs-labels">I am not a robot:</h3>
				<div style="margin:0 auto;display:table;margin-top:3px;margin-bottom:15px;"><?php echo apply_filters( 'cptch_display', '', 'Coinwink' ); ?></div>

				<p class="lostpassword-submit">
					<input type="submit" name="submit" class="lostpassword-button" value="Reset Password"/>
				</p>
			</form>
		</div>

	</div>

</div>


<!-- #registered -->
<div class="container" id="registered" style="display:none;">

	<header>
	<h2 class="fs-title" style="color:white;">Account</h2>
	</header>

	<a href="#signup" class="blacklink hashLink">Sign up</a> | <a href="#login" class="blacklink hashLink">Login</a>
	<br>
	<br>

	<div class="content">

	<div id="login-form" class="login-form-container">
		Success!<br>
		Please check your email to set a new password.
	</div>

	</div>

</div>

<!-- #keyerror -->
<div class="container" id="keyerror" style="display:none;">

	<header>
	<h2 class="fs-title" style="color:white;">Account</h2>
	</header>

	<a href="#signup" class="blacklink hashLink">Sign up</a> | <a href="#login" class="blacklink hashLink">Login</a>
	<br>
	<br>

	<div class="content">

		<div id="login-form" class="login-form-container">
			<div style="color:red;font-weight:bold;">
			Error: The password reset link you used is not valid.
			</div>
		</div>

	</div>

</div>


<!-- #changed -->
<div class="container" id="changed" style="display:none;">

	<header>
	<h2 class="fs-title" style="color:white;">Account</h2>
	</header>

	<div class="content">

		<div id="login-form" class="login-form-container">
			Password successfully changed.<br>
			<br>
			<a href="#login" class="blacklink hashLink">Login</a>
		</div>

	</div>

</div>


<!-- #loggedout -->
<div class="container" id="loggedout" style="display:none;">

	<header>
	<h2 class="fs-title" style="color:white;">Account</h2>
	</header>

	<div class="content">

		<div id="login-form" class="login-form-container">
		You have successfully logged out.<br>
		<br>
		<a href="#forgotpass" class="blacklink hashLink">Password recovery</a>		
		<br>
		<br>
		<a href="#login" class="blacklink hashLink">Login again</a>
		</div>

	</div>

</div>
<!-- Sign up -->
<div class="acc-form" id="signup" style="display:none;padding-left:15px;padding-right:15px;">

	<div id="register-form">
        
        <p style="margin-bottom:25px;font-size:13px;">
            <b>Create account</b>
        </p>

        <!-- FREE -->
        <div id="free" style="clear:both;">

            <form id="signupform" action="<?php echo wp_registration_url(); ?>" method="post">
                
                    <!-- Show errors if there are any -->
                    <?php if ( count( $attributes['errors'] ) > 0 ) : ?>
                        <?php foreach ( $attributes['errors'] as $error ) : ?>
                            <p style="color:red;font-weight:bold;margin-top:5px;margin-bottom:20px;padding-left:10px;padding-right:10px;">
                                <?php echo $error; ?>
                            </p>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <div style="height:5px;"></div>

                    <div style="margin-bottom:2px;">E-mail address:</div>
                    <input type="text" name="email" class="input-general" autocapitalize="off" id="email_address_free" style="padding-left:5px;height:29px;" required>
                    
                    <div style="height:11px;">&nbsp;</div>

                    <div>
                        
                        
                    </div>
                    
                    <p class="signup-submit">
                        <input type="submit" name="submit" class="button-acc" value="Sign up"  style="margin-top:5px;padding:2px 4px;"/>
                    </p>

                    <div style="height:1px;"></div>

                    <span style="font-size:11px;line-height:130%;">
                        You will receive a link to set a password<br>to the address you specify above.
                    </span>

                    <div style="height:7px;"></div>

            </form>

        </div>
    
    </div>
                
</div>


<!-- Login -->
<div class="content acc-form" id="login" style="display:none;">

	<p class="text-label" style="margin-bottom:15px;">
		<b>Log in to your account</b>
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
				<?php echo $error; ?>
			</div>
		<?php endforeach; ?>
		</div>
	<?php endif; ?>

	<div id="login-form" class="login-form-container">
	
		<form method="post" action="<?php echo wp_login_url(); ?>" style="margin-top:20px;">

			<p class="login-username">
				<label for="user_login">E-mail: </label><br>
				<input type="text" name="log" id="user_login" autocapitalize="off" class="input-general" style="padding-left:5px;height:29px;"  required>
			</p>

			<p class="login-password" style="margin-top:-3px;margin-bottom:18px;">
				<label for="user_pass">Password: </label><br>
				<input type="password" name="pwd" id="user_pass" class="input-general" style="font:small-caption;padding-bottom:1px;padding-left:5px;height:29px;letter-spacing: 2px;" required>
			</p>

			<div style="height:10px;"></div>

			<div class="appify-checkbox" style="width:122px;margin:0 auto;padding-left:4px;">
				<input id="rememberme" type="checkbox" name="rememberme" class="appify-input-checkbox" checked="checked"/>
				<label for="rememberme">
				<div class="checkbox-box" style="margin-top:-1px;">  
					<svg><use xlink:href="#checkmark" /></svg>
				</div> 
				Remember me
				</label>
			</div>

			<div style="height:2px;"></div>

			
			<p class="login-submit" style="margin-bottom:17px;">
				<input type="submit" name="submit" class="button-acc" value="Log in" style="padding:2px 4px;"/>
            </p>

		</form>
		   
        <a class="blacklink hashLink" href="#forgotpass">Forgot your password?</a>

	</div>
	
</div>

<!-- Password recovery - #confirm -->
<div class="content acc-form" id="confirm" style="display:none;">

	<div id="login-form" class="login-form-container" style="font-size:13px;padding-top:18px;padding-bottom:15px;line-height:140%;">
		Please check your email to confirm the password reset.
	</div>

</div>

<!-- Password recovery - #forgotpass -->
<div class="content acc-form" id="forgotpass" style="display:none;">

	<div id="password-lost-form" class="widecolumn">
		<p class="text-label" style="margin-bottom:15px;">
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
			<div style="margin-top:20px;margin-bottom:-10px;">
			<?php foreach ( $attributes['errors'] as $error ) : ?>
				<div style="color:red;font-weight:bold;">
					<?php echo $error; ?>
				</div>
			<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<p style="margin-top:39px;margin-bottom:5px;">Enter your email address and we'll send you a link to set a new password:</p>

		<form id="lostpasswordform" action="<?php echo wp_lostpassword_url(); ?>" method="post">
			<input type="text" name="user_login" id="user_login_recover" class="input-general" required>

            <div style="height:5px;"></div>

			
			

			<p class="lostpassword-submit" style="margin-top:1px;margin-bottom:12px;">
				<input type="submit" name="submit" class="button-acc" style="width:130px;padding-right:1px!important;" value="Reset password"/>
			</p>
		</form>
	</div>

</div>


<!-- #registered -->
<div class="content acc-form" id="registered" style="display:none;">

	<div id="login-form" class="login-form-container">
        <div style="height:10px;">&nbsp;</div>
		<b style="font-size:13px;line-height:140%;">Check your email to<br>set a password and log in.</b>
        <div style="height:25px;">&nbsp;</div>
		<span style="font-size:13px;">Account created!</span>
        <div style="height:10px;">&nbsp;</div>
	</div>

</div>


<!-- #keyerror -->
<div class="content acc-form" id="keyerror" style="display:none;">

	<div id="login-form" class="login-form-container">
		<div style="color:red;font-weight:bold;">
		The password reset link you used is not valid.
		</div>
	</div>

</div>


<!-- #changed -->
<div class="content acc-form" id="changed" style="display:none;">

	<div id="login-form" class="login-form-container" style="padding-bottom:6px;">
        <br>
		Password successfully changed
        <br>
		<br>
		<a href="#login" class="blacklink hashLink">Login</a>
	</div>

</div>


<!-- #loggedout -->
<div class="content acc-form" id="loggedout" style="display:none;">

	<div id="login-form" class="login-form-container">
	You have successfully logged out.<br>
	<br>
	<a href="#forgotpass" class="blacklink hashLink">Password recovery</a>		
	<br>
	<br>
	<a href="#login" class="blacklink hashLink">Login again</a>
	</div>

</div>
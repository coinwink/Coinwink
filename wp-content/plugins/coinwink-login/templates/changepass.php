<div class="container" id="changepass">

	<header>
	<h2 class="text-header" style="color:white;">Account</h2>
	</header>

	<div id="password-reset-form" class="content">
		<p class="text-label" style="margin-bottom:15px;">
			<b>Pick a new password</b>
		</p>

		<form name="resetpassform" id="resetpassform" action="<?php echo site_url( 'wp-login.php?action=resetpass' ); ?>" method="post" autocomplete="off">
			<input type="hidden" id="user_login" name="rp_login" value="<?php echo esc_attr( $attributes['login'] ); ?>" autocomplete="off" />
			<input type="hidden" name="rp_key" value="<?php echo esc_attr( $attributes['key'] ); ?>" />

			<?php if ( count( $attributes['errors'] ) > 0 ) : ?>
				<div style="margin-top:10px;margin-bottom:15px;">
				<?php foreach ( $attributes['errors'] as $error ) : ?>
					<div style="color:red;font-weight:bold;">
						<?php echo $error; ?>
					</div>
				<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<p style="margin-top:30px;margin-bottom:10px;">
				<label for="pass1">New password:</label><br>
				<input type="password" name="pass1" id="pass1" class="input-general" style="font:small-caption;padding-bottom:1px;letter-spacing: 2px;" value="" autocomplete="off" required/>
			</p>
			<p style="margin-top:15px;">
				<label for="pass2">Repeat the password:</label><br>
				<input type="password" name="pass2" id="pass2" class="input-general" style="font:small-caption;padding-bottom:1px;letter-spacing: 2px;" value="" autocomplete="off" required/>
			</p>

			<p class="description" style="margin-top:10px;margin-bottom:10px;">The password should be at least eight characters long.</p>

			<p class="resetpass-submit" style="margin-top:25px;margin-bottom:-4px;">
				<input type="submit" name="submit" id="resetpass-button"
                class="button-acc" value="<?php _e( 'Submit', 'personalize-login' ); ?>" 
                style="margin-top:2px;padding:4px 12px 4px 12px!important;" />
			</p>
		</form>
	</div>
</div>
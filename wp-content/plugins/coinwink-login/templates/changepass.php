<div class="container" id="changepass">

	<header>
	<h2 class="fs-title" style="color:white;">Account</h2>
	</header>

	<div id="password-reset-form" class="content">
		<p class="fs-labels" style="margin-bottom:15px;">
			<b>Pick a new password</b>
		</p>

		<form name="resetpassform" id="resetpassform" action="<?php echo site_url( 'wp-login.php?action=resetpass' ); ?>" method="post" autocomplete="off">
			<input type="hidden" id="user_login" name="rp_login" value="<?php echo esc_attr( $attributes['login'] ); ?>" autocomplete="off" />
			<input type="hidden" name="rp_key" value="<?php echo esc_attr( $attributes['key'] ); ?>" />

			<?php if ( count( $attributes['errors'] ) > 0 ) : ?>
				<div style="margin-top:10px;margin-bottom:15px;">
				<?php foreach ( $attributes['errors'] as $error ) : ?>
					<div style="color:red;font-weight:bold;">
						Error: <?php echo $error; ?>
					</div>
				<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<p>
				<label for="pass1">New password:</label><br>
				<input type="password" name="pass1" id="pass1" class="input" size="20" value="" autocomplete="off" required/>
			</p>
			<p>
				<label for="pass2">Repeat new password:</label><br>
				<input type="password" name="pass2" id="pass2" class="input" size="20" value="" autocomplete="off" required/>
			</p>

			<p class="description">The password should be at least eight characters long.</p>
			<br>
			<p class="resetpass-submit">
				<input type="submit" name="submit" id="resetpass-button"
					class="button" value="<?php _e( 'Submit', 'personalize-login' ); ?>" />
			</p>
		</form>
	</div>
</div>
<?php
/**
 * Plugin Name:       Coinwink login
 * Description:       A plugin that replaces the WordPress login flow with a custom page.
 * Version:           1.0.0
 * Author:            Coinwink (based on Jarkko Laine)
 * URL:               https://code.tutsplus.com/tutorials/build-a-custom-wordpress-user-flow-part-1-replace-the-login-page--cms-23627
 * Text Domain:       coinwink-login
 */

class Personalize_Login_Plugin {

	/**
	 * Initializes the plugin.
	 *
	 * To keep the initialization fast, only add filter and action
	 * hooks in the constructor.
	 */
	public function __construct() {

		// Redirects
		add_action( 'login_form_login', array( $this, 'redirect_to_custom_login' ) );
		add_filter( 'authenticate', array( $this, 'maybe_redirect_at_authenticate' ), 101, 3 );
		add_filter( 'login_redirect', array( $this, 'redirect_after_login' ), 10, 3 );
		add_action( 'wp_logout', array( $this, 'redirect_after_logout' ) );

		add_action( 'login_form_register', array( $this, 'redirect_to_custom_register' ) );
		add_action( 'login_form_lostpassword', array( $this, 'redirect_to_custom_lostpassword' ) );
		add_action( 'login_form_rp', array( $this, 'redirect_to_custom_password_reset' ) );
		add_action( 'login_form_resetpass', array( $this, 'redirect_to_custom_password_reset' ) );

		// Handlers for form posting actions
		add_action( 'login_form_register', array( $this, 'do_register_user' ) );
		add_action( 'login_form_lostpassword', array( $this, 'do_password_lost' ) );
		add_action( 'login_form_rp', array( $this, 'do_password_reset' ) );
		add_action( 'login_form_resetpass', array( $this, 'do_password_reset' ) );

		// Other customizations
		add_filter( 'retrieve_password_message', array( $this, 'replace_retrieve_password_message' ), 10, 4 );

		// Shortcodes
		add_shortcode( 'custom-register-form', array( $this, 'render_register_form' ) );
		add_shortcode( 'custom-password-reset-form', array( $this, 'render_password_reset_form' ) );
	}


	//
	// REDIRECT FUNCTIONS
	//

	/**
	 * Redirect the user to the custom login page instead of wp-login.php.
	 */
	public function redirect_to_custom_login() {
		if ( $_SERVER['REQUEST_METHOD'] == 'GET' ) {
			if ( is_user_logged_in() ) {
				$this->redirect_logged_in_user();
				exit;
			}

			// The rest are redirected to the login page
			$login_url = home_url( 'account' );
			if ( ! empty( $_REQUEST['redirect_to'] ) ) {
				$login_url = add_query_arg( 'redirect_to', $_REQUEST['redirect_to'], $login_url );
			}

			if ( ! empty( $_REQUEST['checkemail'] ) ) {
				$login_url = add_query_arg( 'checkemail', $_REQUEST['checkemail'], $login_url );
			}

			wp_redirect( $login_url );
			exit;
		}
	}

	/**
	 * Redirect the user after authentication if there were any errors.
	 *
	 * @param Wp_User|Wp_Error  $user       The signed in user, or the errors that have occurred during login.
	 * @param string            $username   The user name used to log in.
	 * @param string            $password   The password used to log in.
	 *
	 * @return Wp_User|Wp_Error The logged in user, or error information if there were errors.
	 */
	public function maybe_redirect_at_authenticate( $user, $username, $password ) {
		// Check if the earlier authenticate filter (most likely,
		// the default WordPress authentication) functions have found errors
		if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
			if ( is_wp_error( $user ) ) {
				$error_codes = join( ',', $user->get_error_codes() );

				$login_url = home_url( 'account' . "#login" );
				$login_url = add_query_arg( 'login', $error_codes, $login_url );

				wp_redirect( $login_url );
				exit;
			}
		}

		return $user;
	}

	/**
	 * Returns the URL to which the user should be redirected after the (successful) login.
	 *
	 * @param string           $redirect_to           The redirect destination URL.
	 * @param string           $requested_redirect_to The requested redirect destination URL passed as a parameter.
	 * @param WP_User|WP_Error $user                  WP_User object if login was successful, WP_Error object otherwise.
	 *
	 * @return string Redirect URL
	 */
	public function redirect_after_login( $redirect_to, $requested_redirect_to, $user ) {
		// $redirect_url = home_url() . "/#sms";
		$redirect_url = home_url();

		if ( ! isset( $user->ID ) ) {
			return $redirect_url;
		}

		if ( user_can( $user, 'manage_options' ) ) {
			// Use the redirect_to parameter if one is set, otherwise redirect to admin dashboard.
			if ( $requested_redirect_to == '' ) {
				$redirect_url = admin_url();
			} else {
				$redirect_url = $redirect_to;
			}
		} else {
			// Non-admin users always go to their account page after login
			// $redirect_url = home_url() . "/#sms";
			$redirect_url = home_url();
		}

		return wp_validate_redirect( $redirect_url, home_url() );
	}

	/**
	 * Redirect to custom login page after the user has been logged out.
	 */
	public function redirect_after_logout() {
		$redirect_url = home_url() . "/account/#loggedout";
		wp_redirect( $redirect_url );
		exit;
	}

	/**
	 * Redirects the user to the custom registration page instead
	 * of wp-login.php?action=register.
	 */
	public function redirect_to_custom_register() {
		if ( 'GET' == $_SERVER['REQUEST_METHOD'] ) {
			if ( is_user_logged_in() ) {
				$this->redirect_logged_in_user();
			} else {
				wp_redirect( home_url( 'account' ) );
			}
			exit;
		}
	}

	/**
	 * Redirects the user to the custom "Forgot your password?" page instead of
	 * wp-login.php?action=lostpassword.
	 */
	public function redirect_to_custom_lostpassword() {
		if ( 'GET' == $_SERVER['REQUEST_METHOD'] ) {
			if ( is_user_logged_in() ) {
				$this->redirect_logged_in_user();
				exit;
			}

			wp_redirect( home_url( 'account' . '#forgotpass' ) );
			exit;
		}
	}

	/**
	 * Redirects to the custom password reset page, or the login page
	 * if there are errors.
	 */
	public function redirect_to_custom_password_reset() {
		if ( 'GET' == $_SERVER['REQUEST_METHOD'] ) {
			// Verify key / login combo
			$user = check_password_reset_key( $_REQUEST['key'], $_REQUEST['login'] );
			if ( ! $user || is_wp_error( $user ) ) {
				if ( $user && $user->get_error_code() === 'expired_key' ) {
					wp_redirect( home_url( 'account' . '#keyerror' ) );
				} else {
					wp_redirect( home_url( 'account' . '#keyerror' ) );
				}
				exit;
			}

			$redirect_url = home_url() . "/changepass";
			$redirect_url = add_query_arg( 'login', esc_attr( $_REQUEST['login'] ), $redirect_url );
			$redirect_url = add_query_arg( 'key', esc_attr( $_REQUEST['key'] ), $redirect_url );

			wp_redirect( $redirect_url );
			exit;
		}
	}




	/**
	 * A shortcode for rendering the new user registration form.
	 *
	 * @param  array   $attributes  Shortcode attributes.
	 * @param  string  $content     The text content for shortcode. Not used.
	 *
	 * @return string  The shortcode output
	 */
	public function render_register_form( $attributes, $content = null ) {
		// Parse shortcode attributes
		$default_attributes = array( 'show_title' => false );
		$attributes = shortcode_atts( $default_attributes, $attributes );

		if ( is_user_logged_in() ) {
			return __( 'You are already signed in.', 'personalize-login' );
		} elseif ( ! get_option( 'users_can_register' ) ) {
			return __( 'Registering new users is currently not allowed.', 'personalize-login' );
		} else {
			// Retrieve possible errors from request parameters
			$attributes['errors'] = array();
			if ( isset( $_REQUEST['register-errors'] ) ) {
				$error_codes = explode( ',', $_REQUEST['register-errors'] );

				foreach ( $error_codes as $error_code ) {
					$attributes['errors'] []= $this->get_error_message( $error_code );
				}
			}

			return $this->get_template_html( 'account', $attributes );
		}
	}


	/**
	 * A shortcode for rendering the form used to reset a user's password.
	 *
	 * @param  array   $attributes  Shortcode attributes.
	 * @param  string  $content     The text content for shortcode. Not used.
	 *
	 * @return string  The shortcode output
	 */
	public function render_password_reset_form( $attributes, $content = null ) {
		// Parse shortcode attributes
		$default_attributes = array( 'show_title' => false );
		$attributes = shortcode_atts( $default_attributes, $attributes );

		if ( is_user_logged_in() ) {
			return __( 'You are already signed in.', 'personalize-login' );
		} else {
			if ( isset( $_REQUEST['login'] ) && isset( $_REQUEST['key'] ) ) {
				$attributes['login'] = $_REQUEST['login'];
				$attributes['key'] = $_REQUEST['key'];

				// Error messages
				$errors = array();
				if ( isset( $_REQUEST['error'] ) ) {
					$error_codes = explode( ',', $_REQUEST['error'] );

					foreach ( $error_codes as $code ) {
						$errors []= $this->get_error_message( $code );
					}
				}
				$attributes['errors'] = $errors;

				return $this->get_template_html( 'changepass', $attributes );
			} else {
				return __( 'Invalid password reset link.', 'personalize-login' );
			}
		}
	}

	/**

	/**
	 * Renders the contents of the given template to a string and returns it.
	 *
	 * @param string $template_name The name of the template to render (without .php)
	 * @param array  $attributes    The PHP variables for the template
	 *
	 * @return string               The contents of the template.
	 */
	private function get_template_html( $template_name, $attributes = null ) {
		if ( ! $attributes ) {
			$attributes = array();
		}

		ob_start();

		// hooks
		do_action( 'personalize_login_before_' . $template_name );

		require( 'templates/' . $template_name . '.php');

		// hooks
		do_action( 'personalize_login_after_' . $template_name );

		$html = ob_get_contents();
		ob_end_clean();

		return $html;
	}


	//
	// ACTION HANDLERS FOR FORMS IN FLOW
	//

	/**
	 * Handles the registration of a new user.
	 *
	 * Used through the action hook "login_form_register" activated on wp-login.php
	 * when accessed through the registration action.
	 */
	public function do_register_user() {

		// Captcha check, if good, then continue
        // $error = apply_filters( 'cptch_verify', true );
        
        // disable captcha
        $error = true;

		if ( true === $error ) { 

			if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {
				$redirect_url = home_url( 'account' );

				if ( ! get_option( 'users_can_register' ) ) {
					// Registration closed, display error
					$redirect_url = add_query_arg( 'register-errors', 'closed', $redirect_url );

				} else {
					$email = $_POST['email'];

					$result = $this->register_user( $email, '', '' );

					if ( is_wp_error( $result ) ) {
						// Parse errors into a string and append as parameter to redirect
						$errors = join( ',', $result->get_error_codes() );
						$redirect_url = add_query_arg( 'register-errors', $errors, $redirect_url );
					} else {
						// Success, redirect to login page.
						$redirect_url = home_url() . "/account/#registered";
					}
				}

				wp_redirect( $redirect_url );
				exit;
			}

		}
		else {		// Captcha check failed, display error
					$redirect_url = home_url( 'account' );
					$redirect_url = add_query_arg( 'register-errors', 'captcha', $redirect_url );
					wp_redirect( $redirect_url );
						
					exit;
		}

	}

	/**
	 * Initiates password reset.
	 */
	public function do_password_lost() {
		if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {
			$errors = retrieve_password();
			if ( is_wp_error( $errors ) ) {
				// Errors found
				$redirect_url = home_url( 'account' . '#forgotpass' );
				$redirect_url = add_query_arg( 'errors', join( ',', $errors->get_error_codes() ), $redirect_url );
			} else {
				// Email sent
				$redirect_url = home_url( 'account' . '#confirm' );
				$redirect_url = add_query_arg( 'checkemail', 'confirm', $redirect_url );
				if ( ! empty( $_REQUEST['redirect_to'] ) ) {
					$redirect_url = $_REQUEST['redirect_to'];
				}
			}

			wp_safe_redirect( $redirect_url );
			exit;
		}
	}

	/**
	 * Resets the user's password if the password reset form was submitted.
	 */
	public function do_password_reset() {
		if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {
			$rp_key = $_REQUEST['rp_key'];
			$rp_login = $_REQUEST['rp_login'];
	//		$user_ID = $_REQUEST['user_ID'];

			$user = check_password_reset_key( $rp_key, $rp_login );

	//		if (!user_ID) {
			if ( ! $user || is_wp_error( $user ) ) {
				if ( $user && $user->get_error_code() === 'expired_key' ) {
					wp_redirect( home_url( 'account' . '#keyerror' ) );
				} else {
					wp_redirect( home_url( 'account' . '#keyerror' ) );
				}
				exit;
			}
	//		}

			if ( isset( $_POST['pass1'] ) ) {
				if ( strlen($_POST['pass1']) < 8 ) {
					// Password too short
					$redirect_url = home_url() . "/changepass";

					$redirect_url = add_query_arg( 'key', $rp_key, $redirect_url );
					$redirect_url = add_query_arg( 'login', $rp_login, $redirect_url );
					$redirect_url = add_query_arg( 'error', 'password_too_short', $redirect_url );

					wp_redirect( $redirect_url );
					exit;
				}

				if ( $_POST['pass1'] != $_POST['pass2'] ) {
					// Passwords don't match
					$redirect_url = home_url() . "/changepass";

					$redirect_url = add_query_arg( 'key', $rp_key, $redirect_url );
					$redirect_url = add_query_arg( 'login', $rp_login, $redirect_url );
					$redirect_url = add_query_arg( 'error', 'password_reset_mismatch', $redirect_url );

					wp_redirect( $redirect_url );
					exit;
				}

				if ( empty( $_POST['pass1'] ) ) {
					// Password is empty
					$redirect_url = home_url() . "/changepass";

					$redirect_url = add_query_arg( 'key', $rp_key, $redirect_url );
					$redirect_url = add_query_arg( 'login', $rp_login, $redirect_url );
					$redirect_url = add_query_arg( 'error', 'password_reset_empty', $redirect_url );

					wp_redirect( $redirect_url );
					exit;

				}
				// $user = $user_ID;
				// Parameter checks OK, reset password
				reset_password( $user, $_POST['pass1'] );
				wp_redirect( home_url() . "/account/#changed" );
			} else {
				echo "Invalid request.";
			}

			exit;
		}
	}


	//
	// OTHER CUSTOMIZATIONS
	//

	

	/**
	 * Returns the message body for the password reset mail.
	 * Called through the retrieve_password_message filter.
	 *
	 * @param string  $message    Default mail message.
	 * @param string  $key        The activation key.
	 * @param string  $user_login The username for the user.
	 * @param WP_User $user_data  WP_User object.
	 *
	 * @return string   The mail message to send.
	 */
	public function replace_retrieve_password_message( $message, $key, $user_login, $user_data ) {
		// Create new message
		$msg  = __( 'Hello!', 'personalize-login' ) . "\r\n\r\n";
		$msg .= sprintf( __( 'You asked us to reset your password for your account using the email address %s.', 'personalize-login' ), $user_login ) . "\r\n\r\n";
		$msg .= __( "If this was a mistake, or you didn't ask for a password reset, just ignore this email and nothing will happen.", 'personalize-login' ) . "\r\n\r\n";
		$msg .= __( 'To reset your password, visit the following address:', 'personalize-login' ) . "\r\n\r\n";
		$msg .= site_url( "wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user_login ), 'login' ) . "\r\n\r\n";
		$msg .= __( 'Thanks!', 'personalize-login' ) . "\r\n";

		return $msg;
	}


	//
	// HELPER FUNCTIONS
	//

	/**
	 * Validates and then completes the new user signup process if all went well.
	 *
	 * @param string $email         The new user's email address
	 * @param string $first_name    The new user's first name
	 * @param string $last_name     The new user's last name
	 *
	 * @return int|WP_Error         The id of the user that was created, or error if failed.
	 */
	private function register_user( $email, $first_name, $last_name ) {
		$errors = new WP_Error();

		// Email address is used as both username and email. It is also the only
		// parameter we need to validate
		if ( ! is_email( $email ) ) {
			$errors->add( 'email', $this->get_error_message( 'email' ) );
			return $errors;
		}

		if ( username_exists( $email ) || email_exists( $email ) ) {
			$errors->add( 'email_exists', $this->get_error_message( 'email_exists') );
			return $errors;
		}

		// Generate the password so that the subscriber will have to check email...
		$password = wp_generate_password( 12, false );

		$user_data = array(
			'user_login'    => $email,
			'user_email'    => $email,
			'user_pass'     => $password,
		);

		$user_id = wp_insert_user( $user_data );
		wp_new_user_notification( $user_id, $password );

		// Insert new user to coinwink_credits table
		global $wpdb;

		$unique_id = $wpdb->get_var( "SELECT unique_id FROM cw_alerts_email_cur WHERE email = '".$email."'" );
		if (!$unique_id) {
            $unique_id = uniqid();
		} 
		$wpdb->insert( 'cw_settings', array( 'user_ID' => $user_id, 'unique_id' => $unique_id, 'email' => $email ));

		return $user_id;
	}



	/**
	 * Redirects the user to the correct page depending on whether he / she
	 * is an admin or not.
	 *
	 * @param string $redirect_to   An optional redirect_to URL for admin users
	 */
	private function redirect_logged_in_user( $redirect_to = null ) {
		$user = wp_get_current_user();
		if ( user_can( $user, 'manage_options' ) ) {
			if ( $redirect_to ) {
				wp_safe_redirect( $redirect_to );
			} else {
				wp_redirect( admin_url() );
			}
		} else {
			wp_redirect( home_url( 'member-account' ) );
		}
	}

	/**
	 * Finds and returns a matching error message for the given error code.
	 *
	 * @param string $error_code    The error code to look up.
	 *
	 * @return string               An error message.
	 */
	private function get_error_message( $error_code ) {
		switch ( $error_code ) {
			// Login errors

			case 'empty_username':
				return __( 'Please enter your email address.', 'personalize-login' );

			case 'empty_password':
				return __( 'Please enter your password.', 'personalize-login' );

			case 'invalid_username':
				return __(
					"We don't have any users with that email address.",	'personalize-login'	);

			case 'incorrect_password':
				return __(
					"The password you entered is not right.", 'personalize-login' );
				

			// Registration errors

			case 'email':
				return __( 'The email address you entered is not valid.', 'personalize-login' );

			case 'email_exists':
				return __( 'An account exists with this email address.', 'personalize-login' );

			case 'closed':
				return __( 'Registering new users is currently not allowed.', 'personalize-login' );

			case 'captcha':
				return __( 'The CAPTCHA check failed. Are you a robot?', 'personalize-login' );

			// Lost password

			case 'empty_username':
				return __( 'You need to enter your email address to continue.', 'personalize-login' );

			case 'invalid_email':
			case 'invalidcombo':
				return __( 'There are no users registered with this email address.', 'personalize-login' );

			case 'captcha_error':
			return __( 'The CAPTCHA check failed. Are you a robot?', 'personalize-login' );			

			// Reset password

			case 'password_reset_mismatch':
				return __( "The two passwords you entered don't match.", 'personalize-login' );

			case 'password_reset_empty':
				return __( "Sorry, we don't accept empty passwords.", 'personalize-login' );

			case 'password_too_short':
				return __( "Password needs to be at least eight characters long.", 'personalize-login' );

			default:
				break;
		}

		return __( 'An unknown error occurred. Please try again later.', 'personalize-login' );
	}

}

// Initialize the plugin
$personalize_login_pages_plugin = new Personalize_Login_Plugin();


// Disable password change admin notification
if ( ! function_exists( 'wp_password_change_notification' ) ) :
    function wp_password_change_notification( $user ) {
        return;
    }
endif;


// Redefine user notification function
if ( !function_exists('wp_new_user_notification') ) {
  function wp_new_user_notification( $user_id, $deprecated = null, $notify = '' ) {
    if ( $deprecated !== null ) {
        _deprecated_argument( __FUNCTION__, '4.3.1' );
    }
 
    global $wpdb, $wp_hasher;
    $user = get_userdata( $user_id );
 
    // The blogname option is escaped with esc_html on the way into the database in sanitize_option
    // we want to reverse this for the plain text arena of emails.
    $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
 
    if ( 'user' !== $notify ) {
        $switched_locale = switch_to_locale( get_locale() );
        $message  = sprintf( __( 'New user registration on your site %s:' ), $blogname ) . "\r\n\r\n";
        $message .= sprintf( __( 'Username: %s' ), $user->user_login ) . "\r\n\r\n";
        $message .= sprintf( __( 'Email: %s' ), $user->user_email ) . "\r\n";
 
     // Send notification to admin
	 //   @wp_mail( get_option( 'admin_email' ), sprintf( __( '[%s] New User Registration' ), $blogname ), $message );
 
        if ( $switched_locale ) {
            restore_previous_locale();
        }
    }
 
    // `$deprecated was pre-4.3 `$plaintext_pass`. An empty `$plaintext_pass` didn't sent a user notification.
    if ( 'admin' === $notify || ( empty( $deprecated ) && empty( $notify ) ) ) {
        return;
    }
 
    // Generate something random for a password reset key.
    $key = wp_generate_password( 20, false );
 
    /** This action is documented in wp-login.php */
    do_action( 'retrieve_password_key', $user->user_login, $key );
 
    // Now insert the key, hashed, into the DB.
    if ( empty( $wp_hasher ) ) {
        $wp_hasher = new PasswordHash( 8, true );
    }
    $hashed = time() . ':' . $wp_hasher->HashPassword( $key );
    $wpdb->update( $wpdb->users, array( 'user_activation_key' => $hashed ), array( 'user_login' => $user->user_login ) );
 
    $switched_locale = switch_to_locale( get_user_locale( $user ) );
 
    $message = sprintf(__('Username: %s'), $user->user_login) . "\r\n\r\n";
    $message .= __('To set your password, visit the following address:') . "\r\n\r\n";
    $message .=  network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user->user_login), 'login') . "\r\n\r\n";
	$message .= "Wink," . "\r\n";
	$message .= "Coinwink";
 
    wp_mail($user->user_email, sprintf(__('[%s] Your username and password info'), $blogname), $message);
 
    if ( $switched_locale ) {
        restore_previous_locale();
    }
}
}
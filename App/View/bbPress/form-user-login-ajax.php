<?php
	defined( 'ABSPATH' ) || exit;
	use KTPP\bbPressEnhance\App\Common\Helper;
?>
<form method="post" class="bbp-login-form">
	<fieldset class="bbp-form">
		<legend><?php esc_html_e( 'Log In', 'bbpress' ); ?></legend>
		<div class="bbp-username">
			<label for="user_login"><?php esc_html_e( 'Username or Email Address' ); ?>: </label>
			<input type="text" name="log" value="<?php bbp_sanitize_val( 'user_login', 'text' ); ?>" size="20" maxlength="100" id="user_login" autocomplete="off" />
		</div>

		<div class="bbp-password">
			<label for="user_pass"><?php esc_html_e( 'Password', 'bbpress' ); ?>: </label>
			<input type="password" name="pwd" value="<?php bbp_sanitize_val( 'user_pass', 'password' ); ?>" size="20" id="user_pass" autocomplete="off" />
		</div>

		<div class="bbp-remember-me">
			<input type="checkbox" name="rememberme" value="forever" <?php checked( bbp_get_sanitize_val( 'rememberme', 'checkbox' ), true, true ); ?> id="rememberme" />
			<label for="rememberme"><?php esc_html_e( 'Keep me signed in', 'bbpress' ); ?></label>
		</div>
		<div class="custom-login-info"></div>
<?php
		do_action( 'login_form' );

		$_ajax_nonce = wp_create_nonce( 'bbp-user-login' );

		global $bbp_redirect_to;
		global $bbp_lostpass_link_url;
		$bbp_redirect_to = apply_filters( 'bbp_user_login_redirect_to', '' );
		if ( empty( $bbp_redirect_to ) ) {
			if ( isset( $_SERVER['REQUEST_URI'] ) ) {
				$bbp_redirect_to = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
			} else {
				$bbp_redirect_to = wp_get_referer();
			}
		}
		$bbp_redirect_to = esc_url( remove_query_arg( 'loggedout', $bbp_redirect_to ) );
		$bbp_lostpass_link_url = ( ! empty( $bbp_lostpass_link_url ) ) ? esc_url( $bbp_lostpass_link_url ) : '';

		$_is_widget = Helper::is_widget();
?>
		<div class="bbp-submit-wrapper">
<?php
	echo '<p class="hide-if-no-js"><button id="bbp-login-submit" class="button submit bbp-ajax-submit" onclick="WPCustomLogin(\'' . $_ajax_nonce . '\' , \'' . $_is_widget . '\' , \'' . $bbp_redirect_to . '\' , \'' . $bbp_lostpass_link_url . '\' );return false;" >'. __( 'Log In' ) .'</button></p>';
?>
		</div>
		<div class="bbp-login-links">
<?php
	global $bbp_register_link_url;
	global $bbp_lostpass_link_url;

	if ( ! empty( $bbp_register_link_url ) ) {
?>
			<a href="<?php echo esc_url( $bbp_register_link_url ); ?>" title="<?php esc_attr_e( 'Register', 'bbpress' ); ?>" class="bbp-register-link"><?php esc_attr_e( 'Register', 'bbpress' ); ?></a>
<?php
	}
	if ( ! empty( $bbp_lostpass_link_url ) ) {
?>
			<a href="<?php echo esc_url( $bbp_lostpass_link_url ); ?>" title="<?php esc_attr_e( 'Lost Password', 'bbpress' ); ?>" class="bbp-lostpass-link"><?php esc_attr_e( 'Lost Password', 'bbpress' ); ?></a>
<?php
	}
?>
		</div>
	</fieldset>
</form>

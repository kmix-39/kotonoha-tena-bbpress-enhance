<?php
namespace KTPP\bbPressEnhance\App\Part\AjaxLogin;

use KTPP\bbPressEnhance\App\Common\Helper;

class Bootstrap {

	function __construct() {
		add_filter( 'bbp_get_template_part', [ __CLASS__, '_bbp_form_user_template' ], 10, 3 );
		add_action( 'wp_ajax_nopriv_custom_login', [ __CLASS__, '_bbp_ajax_custom_login' ] );
		add_filter( 'widget_display_callback', [ __CLASS__, '_bbp_ajax_login_widget' ], 10, 3 );
		add_action( 'wp_enqueue_scripts', [ __CLASS__, '_enqueue_scripts_ktpp_bbpress_enhance' ] );
	}

	static function _bbp_form_user_template( $_templates, $_slug, $_name ) {
		$_is_bbp_ajax = false;
		if ( 'form' === $_slug && ( 'user-login' === $_name || 'user-logout' === $_name ) ) {
			if ( ! Helper::is_widget() && Helper::get_settings_value( 'no-display-main-form', false ) ) {
				$_widgets = wp_get_sidebars_widgets();
				if ( isset( $_widgets['bbpress-sidebar-widget-area'] ) ) {
					foreach ( $_widgets['bbpress-sidebar-widget-area'] as $_id ) {
						if ( 0 === strpos( $_id, 'bbp_login_widget-' ) ) {
							return false;
						}
					}
				}
				if ( isset( $_widgets['bbpress-sidebar-sticky-widget-area'] ) ) {
					foreach ( $_widgets['bbpress-sidebar-sticky-widget-area'] as $_id ) {
						if ( 0 === strpos( $_id, 'bbp_login_widget-' ) ) {
							return false;
						}
					}
				}
			}
			add_filter( 'bbp_get_template_stack', [ __CLASS__, '_add_template_stack' ] );
			$_templates = [ $_slug . '-' . $_name . '-ajax.php', 'form.php' ];
			$_is_bbp_ajax = true;
		}
		return $_templates;
	}

	static function _enqueue_scripts_ktpp_bbpress_enhance() {
		if ( !is_bbpress() ) return;
		Helper::enqueue_scripts_ktpp_bbpress_enhance( 'login' );
	}

	static function _add_template_stack( $_stack ) {
		return array_merge( $_stack, [ KTPP_BBPRESS_ENHANCE_PATH . '/App/View/bbPress' ] );
	}

	static function _bbp_ajax_custom_login() {
		check_ajax_referer( 'bbp-user-login' );
		if ( ! function_exists( 'login_header' ) ) {
			function login_header() {
				do_action( 'login_enqueue_scripts' );
			}
			login_header();
		}

		$_secure_cookie = '';
		if ( ! empty( $_POST['log'] ) && ! force_ssl_admin() ) {
			$_user_name = sanitize_user( $_POST['log'] );
			$_user = get_user_by( 'login', $_user_name );

			if ( ! $_user && strpos( $_user_name, '@' ) ) {
				$_user = get_user_by( 'email', $_user_name );
			}
			if ( $_user && get_user_option( 'use_ssl', $_user->ID ) ) {
				$_secure_cookie = true;
				force_ssl_admin( true );
			}
		}

		$_redirect_to = home_url();
		if ( isset( $_REQUEST['redirect_to'] ) ) {
			$_redirect_to = esc_url( $_REQUEST['redirect_to'] );
		}
		if ( false !== strpos( $_redirect_to, 'wp-admin' ) ) {
			$_redirect_to = home_url();
		}
		if ( $_secure_cookie ) {
			$_redirect_to = preg_replace( '|^http://|', 'https://', $_redirect_to );
		}
		$_reauth = empty( $_REQUEST['reauth'] ) ? false : true;
		$_user = wp_signon( [], $_secure_cookie );

		if ( empty( $_COOKIE[ LOGGED_IN_COOKIE ] ) && headers_sent() ) {
			$_user = new WP_Error(
				'test_cookie',
				__( '<strong>ERROR</strong>: Cookies are blocked due to unexpected output. Please check your cookie settings and try again.', 'ktpp-bbpress-enhance' )
			);
		}

		$_requested_redirect_to = isset( $_REQUEST['redirect_to'] ) ? esc_url( $_REQUEST['redirect_to'] ) : '';
		$_redirect_to = apply_filters( 'login_redirect', $_redirect_to, $_requested_redirect_to, $_user );

		if ( is_user_logged_in() ) {
			$_user = new WP_Error();
		}

		$_data = [];
		$_html = '';
		if ( ! is_wp_error( $_user ) && ! $_reauth ) {
			if ( empty( $_redirect_to ) || $_redirect_to === admin_url() || false !== strpos( $_redirect_to, 'wp-admin' ) ) {
				$_redirect_to = home_url();
			}
			$_data['result'] = 'login_redirect';
			$_html = $_redirect_to;
		} else {
			$_errors = $_user;
			$_errors = apply_filters( 'wp_login_errors', $_errors, $_redirect_to );
			if ( $_reauth ) {
				wp_clear_auth_cookie();
			}
			if ( is_wp_error( $_errors ) ) {
				$_error_code = $_errors->get_error_code();
				$_data['result'] = 'e_login';
				$_shake_error_codes = [
					'empty_username',
					'invalid_username',
					'empty_email',
					'invalid_email',
					'empty_password',
					'incorrect_password',
					'invalidcombo',
				];
				$_shake_error_codes = apply_filters( 'shake_error_codes', $_shake_error_codes );
				$_html .= '<div class="bbp-template-notice error">';
				foreach ( $_shake_error_codes as $_code ) {
					if ( $_error_message = $_errors->get_error_message( $_code ) ) {
						$_html .= '<p class="error">'. apply_filters( 'login_errors', $_error_message ) .'</p>';
					}
				}
				if ( ! in_array( $_error_code, $_shake_error_codes ) ) {
					$_error_message = $_errors->get_error_message( $_error_code );
					if ( empty( $_error_message ) ) {
						$_error_message = esc_html( $_error_code );
					}
					$_html .= '<p class="error">'. apply_filters( 'login_errors', $_error_message ) .'</p>';
				}
				$_html .= '</div>';
			}
		}
		$_data['info'] = $_html;
		wp_send_json_success( $_data );
	}

	static function _bbp_ajax_login_widget( $_instance, $_widget, $_args ) {
		if ( ! empty( $_instance ) && 'bbp_login_widget' === $_widget->id_base ) {
			global $wp_customize;
			global $bbp_register_link_url;
			global $bbp_lostpass_link_url;
			if ( ! ( isset( $wp_customize ) && $wp_customize->is_preview() ) ) {
				$_settings = bbp_parse_args(
					$_instance,
					[
						'title' => '',
						'register' => '',
						'lostpass' => '',
					],
					'login_widget_settings'
				);
				$_settings['title'] = apply_filters( 'widget_title', $_settings['title'], $_instance, $_widget->id_base );
				$_settings['title'] = apply_filters( 'bbp_login_widget_title', $_settings['title'], $_instance, $_widget->id_base );

				$bbp_register_link_url = apply_filters( 'bbp_login_widget_register', $_settings['register'], $_instance, $_widget->id_base );
				$bbp_lostpass_link_url = apply_filters( 'bbp_login_widget_lostpass', $_settings['lostpass'], $_instance, $_widget->id_base );

				echo $_args['before_widget'];

				if ( ! empty( $_settings['title'] ) ) {
					echo $_args['before_title'] . $_settings['title'] . $_args['after_title'];
				}
				
				is_user_logged_in() ?
					bbp_get_template_part( 'form', 'user-logout' ) :
					bbp_get_template_part( 'form', 'user-login' );

				echo $_args['after_widget'];

				$_instance = false;
			}
		}
		return $_instance;
	}

}

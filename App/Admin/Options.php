<?php
namespace KTPP\bbPressEnhance\App\Admin;

use KTPP\bbPressEnhance\App\Common\Helper;

class Options {

    function __construct() {
        add_action( 'admin_menu', [ __CLASS__, '_admin_menu' ] );
		add_action( 'admin_init', [ __CLASS__, '_admin_init' ] );
    }

    static function _admin_menu() {
        $_page = add_options_page(
            __( 'Kotonoha Tena bbPress Enhance', 'ktpp-bbpress-enhance' ),
            __( 'Kotonoha Tena bbPress Enhance', 'ktpp-bbpress-enhance' ),
            'manage_options',
            'ktpp-bbpress-enhance',
            function() {
?>
                <div class="wrap">
                    <h1><?php esc_html_e( 'Kotonoha Tena bbPress Enhance Settings', 'ktpp-bbpress-enhance' ); ?></h1>
                    <form method="post" action="options.php">
                        <?php
                            settings_fields( 'ktpp-bbpress-enhance' );
                            do_settings_sections( 'ktpp-bbpress-enhance' );
                            submit_button();
                        ?>
                    </form>
                </div>
<?php
            }
		);
		add_action('load-' . $_page, [ __CLASS__, '_load_page' ] );
    }

    static function _admin_init() {
		register_setting(
			'ktpp-bbpress-enhance',
			'ktpp-bbpress-enhance',
			function( $_option ) {
				$_default_option = [
					'display-form-description' => false,
					'ajax-login' => false,
					'no-display-main-form' => false,
					'display-element-form' => false,
					'linkage-wc-regist' => false,
					'linkage-wc-lostpass' => false,
					'is-use-advance-search' => false,
					'always-display-search-form' => false,
					'result-display-search-form' => false,
					'display-alert-no-keyword' => false,
				];
				$_new_option = [];
				foreach ( $_default_option as $_key => $_value ) {
					$_new_option[ $_key ] = isset( $_option[ $_key ] ) ? $_option[ $_key ] : $_value;
				}
				return $_new_option;
			}
		);

		// Forum
		add_settings_section(
			'ktpp-bbpress-enhance-forum',
			__( 'Forum', 'ktpp-bbpress-enhance' ),
			function() {
				echo '<p>' . esc_html__( 'This section summarizes the enhancements to the forum.', 'ktpp-bbpress-enhance' ) . '</p>';
			},
			'ktpp-bbpress-enhance'
		);
		add_settings_field(
			'display-form-description',
			__( 'Display the forum description', 'ktpp-bbpress-enhance' ),
			function() {
?>
	<input name="ktpp-bbpress-enhance[display-form-description]" id="_display-form-description" type="checkbox" value="1"<?php checked( 1, Helper::get_settings_value( 'display-form-description', false ) ); ?>>
	<label for="_display-form-description"><?php esc_html_e( 'Display a description at the after of the forum title.', 'ktpp-bbpress-enhance' ); ?></label>
<?php
			},
			'ktpp-bbpress-enhance',
			'ktpp-bbpress-enhance-forum'
		);

		// Login
		add_settings_section(
			'ktpp-bbpress-enhance-login',
			__( 'Login / Logout', 'ktpp-bbpress-enhance' ),
			function() {
				echo '<p>' . esc_html__( 'This section summarizes the enhancements to the login / logout form.', 'ktpp-bbpress-enhance' ) . '</p>';
			},
			'ktpp-bbpress-enhance'
		);
		add_settings_field(
			'ajax-login',
			__( 'Ajax Form', 'ktpp-bbpress-enhance' ),
			function() {
?>
	<input name="ktpp-bbpress-enhance[ajax-login]" id="_ajax-login" type="checkbox" value="1"<?php checked( 1, Helper::get_settings_value( 'ajax-login', false ) ); ?>>
	<label for="_ajax-login"><?php esc_html_e( 'Use Ajax to make a login / logout form that does not transition.', 'ktpp-bbpress-enhance' ); ?></label>
	<p class="description"><?php esc_html_e( 'Use Ajax.', 'ktpp-bbpress-enhance' ); ?></p>
<?php
			},
			'ktpp-bbpress-enhance',
			'ktpp-bbpress-enhance-login'
		);

		add_settings_field(
			'no-display-main-form',
			__( 'Not display the main form', 'ktpp-bbpress-enhance' ),
			function() {
?>
	<input name="ktpp-bbpress-enhance[no-display-main-form]" id="_no-display-main-form" type="checkbox" value="1"<?php checked( 1, Helper::get_settings_value( 'no-display-main-form', false ) ); ?>>
	<label for="_no-display-main-form"><?php esc_html_e( 'If a login form is set in the widget, the main form will not be displayed.', 'ktpp-bbpress-enhance' ); ?></label>
	<p class="description"><?php esc_html_e( 'To use this feature, you need to have Ajax Form enabled.', 'ktpp-bbpress-enhance' ); ?></p>
<?php
			},
			'ktpp-bbpress-enhance',
			'ktpp-bbpress-enhance-login'
		);
		
		add_settings_field(
			'display-element-form',
			__( 'Display elements in a form', 'ktpp-bbpress-enhance' ),
			function() {
?>
	<input name="ktpp-bbpress-enhance[display-element-form]" id="_display-element-form" type="checkbox" value="1"<?php checked( 1, Helper::get_settings_value( 'display-element-form', false ) ); ?>>
	<label for="_display-element-form"><?php esc_html_e( 'Display role and number of stars in the widget after login.', 'ktpp-bbpress-enhance' ); ?></label>
	<p class="description"><?php esc_html_e( 'To use this feature, you need to have Ajax Form enabled.', 'ktpp-bbpress-enhance' ); ?></p>
<?php
			},
			'ktpp-bbpress-enhance',
			'ktpp-bbpress-enhance-login'
		);

		// WooCommerce
		add_settings_section(
			'ktpp-bbpress-enhance-linkage-wc',
			__( 'Linkage with WooCommerce', 'ktpp-bbpress-enhance' ),
			function() {
				echo '<p>' . esc_html__( 'This section summarizes the enhancements to the linkage with WooCommerce.', 'ktpp-bbpress-enhance' ) . '</p>';
			},
			'ktpp-bbpress-enhance'
		);

		add_settings_field(
			'linkage-wc-regist',
			__( 'Change "Regist" link', 'ktpp-bbpress-enhance' ),
			function() {
?>
	<input name="ktpp-bbpress-enhance[linkage-wc-regist]" id="_linkage-wc-regist" type="checkbox" value="1"<?php checked( 1, Helper::get_settings_value( 'linkage-wc-regist', false ) ); ?>>
	<label for="_linkage-wc-regist"><?php esc_html_e( 'Change the "Regist" link in the login form to the WooCommerce "Regist" page.', 'ktpp-bbpress-enhance' ); ?></label>
	<p class="description"><?php esc_html_e( 'To use this feature, you need to WooCommerce plugin active.', 'ktpp-bbpress-enhance' ); ?></p>
<?php
			},
			'ktpp-bbpress-enhance',
			'ktpp-bbpress-enhance-linkage-wc'
		);

		add_settings_field(
			'linkage-wc-lostpass',
			__( 'Change "Forgot Password" link', 'ktpp-bbpress-enhance' ),
			function() {
?>
	<input name="ktpp-bbpress-enhance[linkage-wc-lostpass]" id="_linkage-wc-lostpass" type="checkbox" value="1"<?php checked( 1, Helper::get_settings_value( 'linkage-wc-lostpass', false ) ); ?>>
	<label for="_linkage-wc-lostpass"><?php esc_html_e( 'Change the "Forgot Password" link in the login form to the WooCommerce "Forgot Password" page.', 'ktpp-bbpress-enhance' ); ?></label>
	<p class="description"><?php esc_html_e( 'To use this feature, you need to WooCommerce plugin active.', 'ktpp-bbpress-enhance' ); ?></p>
<?php
			},
			'ktpp-bbpress-enhance',
			'ktpp-bbpress-enhance-linkage-wc'
		);

		// Advance Search
		add_settings_section(
			'ktpp-bbpress-enhance-advance-search',
			__( 'Advance search', 'ktpp-bbpress-enhance' ),
			function() {
				echo '<p>' . esc_html__( 'This section summarizes the enhancements to the search.', 'ktpp-bbpress-enhance' ) . '</p>';
			},
			'ktpp-bbpress-enhance'
		);

		add_settings_field(
			'is-use-advance-search',
			__( 'Use advance search', 'ktpp-bbpress-enhance' ),
			function() {
?>
	<input name="ktpp-bbpress-enhance[is-use-advance-search]" id="_is-use-advance-search" type="checkbox" value="1"<?php checked( 1, Helper::get_settings_value( 'is-use-advance-search', false ) ); ?>>
	<label for="_is-use-advance-search"><?php esc_html_e( 'Add search criteria to main search form within the forum.', 'ktpp-bbpress-enhance' ); ?></label>
	<p class="description"><?php esc_html_e( 'If you have a plugin running that changes the search criteria for other forums, it may cause a conflict.', 'ktpp-bbpress-enhance' ); ?></p>
<?php
			},
			'ktpp-bbpress-enhance',
			'ktpp-bbpress-enhance-advance-search'
		);

		// Search Results
		add_settings_section(
			'ktpp-bbpress-enhance-search-results',
			__( 'Search Results', 'ktpp-bbpress-enhance' ),
			function() {
				echo '<p>' . esc_html__( 'This section summarizes the enhancements related to search results.', 'ktpp-bbpress-enhance' ) . '</p>';
			},
			'ktpp-bbpress-enhance'
		);

		add_settings_field(
			'expand-the-search-form-display',
			__( 'Expand the search form display', 'ktpp-bbpress-enhance' ),
			function() {
?>
	<p>
		<input name="ktpp-bbpress-enhance[always-display-search-form]" id="_always-display-search-form" type="checkbox" value="1"<?php checked( 1, Helper::get_settings_value( 'always-display-search-form', false ) ); ?>>
		<label for="_always-display-search-form"><?php esc_html_e( 'Displays the search form even if the search result does not exist.', 'ktpp-bbpress-enhance' ); ?></label>
	</p>
	<p>
		<input name="ktpp-bbpress-enhance[result-display-search-form]" id="_result-display-search-form" type="checkbox" value="1"<?php checked( 1, Helper::get_settings_value( 'result-display-search-form', false ) ); ?>>
		<label for="_result-display-search-form"><?php esc_html_e( 'Display a search form on the search results page.', 'ktpp-bbpress-enhance' ); ?></label>
	</p>
<?php
			},
			'ktpp-bbpress-enhance',
			'ktpp-bbpress-enhance-search-results'
		);

		add_settings_field(
			'display-alert-no-keyword',
			__( 'Display no keyword alert', 'ktpp-bbpress-enhance' ),
			function() {
?>
	<input name="ktpp-bbpress-enhance[display-alert-no-keyword]" id="_display-alert-no-keyword" type="checkbox" value="1"<?php checked( 1, Helper::get_settings_value( 'display-alert-no-keyword', false ) ); ?>>
	<label for="_display-alert-no-keyword"><?php esc_html_e( 'Display an alert "Please enter a search keyword."', 'ktpp-bbpress-enhance' ); ?></label>
	<p class="description"><?php esc_html_e( 'This feature will not be enabled if "Allow searches with no input" is allowed.', 'ktpp-bbpress-enhance' ); ?></p>
<?php
			},
			'ktpp-bbpress-enhance',
			'ktpp-bbpress-enhance-search-results'
		);

		

	}

	static function _load_page() {
		Helper::add_help_tabs(
			[
				'overview' => [
					'title' => __( 'Overview', 'ktpp-bbpress-enhance' ),
					'content' => '<p>' . __( 'This screen provides access to all of the Kotonoha Tena bbPress Enhance settings.', 'ktpp-bbpress-enhance' ) . '</p>',
				],
			]
		);

		Helper::set_help_sidebar(
			'<p><strong>' . __( 'For more information:', 'ktpp-bbpress-enhance' ) . '</strong></p>'
		);
	}

}

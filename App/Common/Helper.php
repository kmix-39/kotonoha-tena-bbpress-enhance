<?php
namespace KTPP\bbPressEnhance\App\Common;

class Helper {

	static function is_widget() {
		$_trace = debug_backtrace();
		foreach ( $_trace as $_stp ) {
			if ( isset( $_stp['function'] ) && 'dynamic_sidebar' === $_stp['function'] ) {
				return true;
			}
		}
		return false;
	}

	static function get_settings_value( $_key, $_default = false ) {
		$_option = get_option( 'ktpp-bbpress-enhance' );
		return isset( $_option[ $_key ] ) ? $_option[ $_key ] : $_default;
	}

	static function add_help_tabs( $_items ) {
		foreach ( $_items as $_id => $_description ) {
			get_current_screen()->add_help_tab(
				[
					'id' => $_id,
					'title' => $_description['title'],
					'content' => $_description['content'],
				]
			);
		}
	}

	static function set_help_sidebar( $_content ) {
		get_current_screen()->set_help_sidebar( $_content );
	}

	static function get_bbp_forums() {
		$_args = [
			'numberposts' => -1,
			'post_type' => bbp_get_forum_post_type(),
		];
		return get_posts( $_args );
	}

	static function enqueue_scripts_ktpp_bbpress_enhance( $_slug ) {
		$_path_name = '/assets/scripts/ktpp-bbpe-' . $_slug . '.js';
		if ( ! is_file( KTPP_BBPRESS_ENHANCE_PATH . $_path_name ) ) {
			return;
		}
		wp_enqueue_script(
			'ktpp-bbpe-' . $_slug,
			KTPP_BBPRESS_ENHANCE_URL . $_path_name,
			[ 'snow-monkey-bbpress-support' ],
			filemtime( KTPP_BBPRESS_ENHANCE_PATH . $_path_name ),
			true
		);
		wp_localize_script(
			'ktpp-bbpe-' . $_slug,
			'KTPP_BBPRESS_ENHANCE',
			[
				'endpoint' => admin_url( 'admin-ajax.php' ),
				'topicType' => bbp_get_topic_post_type(),
			]
		);
	}

	static function enqueue_styles_ktpp_bbpress_enhance( $_slug ) {
		$_path_name = '/assets/styles/ktpp-bbpe-' . $_slug . '.css';
		if ( ! is_file( KTPP_BBPRESS_ENHANCE_PATH . $_path_name ) ) {
			return;
		}
		wp_enqueue_style(
			'ktpp-bbpress-enhance',
			KTPP_BBPRESS_ENHANCE_URL . $_path_name,
			[ \Framework\Helper::get_main_style_handle() ],
			filemtime( KTPP_BBPRESS_ENHANCE_PATH . $_path_name )
		);
	}

}

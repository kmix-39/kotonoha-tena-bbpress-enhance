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

	static function enqueue_scripts_ktpp_bbpress_enhance() {
		wp_enqueue_script(
			'ktpp-bbpress-enhance',
			KTPP_BBPRESS_ENHANCE_URL . '/assets/scripts/ktpp-bbpress-enhance.js',
			[ 'snow-monkey-bbpress-support' ],
			filemtime( KTPP_BBPRESS_ENHANCE_PATH . '/assets/scripts/ktpp-bbpress-enhance.js' ),
			true
		);
		wp_localize_script(
			'ktpp-bbpress-enhance',
			'KTPP_BBPRESS_ENHANCE',
			[
				'endpoint' => admin_url( 'admin-ajax.php' ),
				'topicType' => bbp_get_topic_post_type(),
			]
		);
	}

	static function enqueue_styles_ktpp_bbpress_enhance() {
		wp_enqueue_style(
			'ktpp-bbpress-enhance',
			KTPP_BBPRESS_ENHANCE_URL . '/assets/styles/ktpp-bbpress-enhance.css',
			[ \Framework\Helper::get_main_style_handle() ],
			filemtime( KTPP_BBPRESS_ENHANCE_PATH . '/assets/styles/ktpp-bbpress-enhance.css' )
		);
	}

}

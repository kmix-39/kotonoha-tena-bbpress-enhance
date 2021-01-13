<?php
/**
 * Plugin Name: Kotonoha Tena bbPress Enhance
 * Plugin URI: https://reverse-gorilla.netlify.app
 * Description: Enhance the functionality of bbPress
 * Version: 0.1.1
 * Requires at least: 5.6
 * Requires PHP: 7.0
 * Tested up to: 5.6
 * Author: Kotonoha Tena Project
 * Author URI: https://github.com/kmix-39
 * License: GPL2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: ktpp-bbpress-enhance
 * Domain Path: /languages
 */
namespace KTPP\bbPressEnhance;

define( 'KTPP_BBPRESS_ENHANCE_URL', untrailingslashit( plugin_dir_url( __FILE__ ) ) );
define( 'KTPP_BBPRESS_ENHANCE_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
define( 'KTPP_BBPRESS_ENHANCE_FILE', __FILE__ );

class KTPP_bbPressEnhance {

	function __construct() {
		add_action( 'plugins_loaded', [ __CLASS__, '_plugins_loaded' ] );
	}

	static function _plugins_loaded() {
		load_plugin_textdomain( 'ktpp-bbpress-enhance', false, basename( __DIR__ ) . '/languages' );

		$_notices = App\Common\NoticeGroups::instance();
		$_active_check = App\Common\ActiveCheck::instance();
		if ( ! $_active_check->is_theme_active( App\Definition\Active::THEME_SNOW_MONKEY ) ) {
			$_notices->add(
				'ktpp-bbpress-enhance',
				'active_theme_error',
				__( '[Kotonoha Tena bbPress Enhance] To available, Need to enable the "Snow Monkey" theme.', 'ktpp-bbpress-enhance' )
			);
		}
		if ( ! $_active_check->is_plugins_active( App\Definition\Active::PLUGIN_BBPRESS ) ) {
			$_notices->add(
				'ktpp-bbpress-enhance',
				'active_plugin_error',
				__( '[Kotonoha Tena bbPress Enhance] To available, Need to enable the "Snow Monkey bbPress support" plugin.', 'ktpp-bbpress-enhance' )
			);
		}
		if ( 0 < $_notices->count( 'ktpp-bbpress-enhance' ) ) {
			$_notices->display( 'ktpp-bbpress-enhance', 'notice-warning' );
			return;
		}

		new App\Admin\Options();
		new App\Part\Bootstrap();
	}

}

require_once( KTPP_BBPRESS_ENHANCE_PATH . '/vendor/autoload.php' );
new KTPP_bbPressEnhance();

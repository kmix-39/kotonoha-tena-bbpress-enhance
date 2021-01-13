<?php
namespace KTPP\bbPressEnhance\App\Common;

use Inc2734\WP_GitHub_Plugin_Updater;

class Updater {

	function __construct() {
		add_action( 'init', [ __CLASS__, '_activate_autoupdate' ] );
	}

	static function _activate_autoupdate() {
		new WP_GitHub_Plugin_Updater\Bootstrap(
			plugin_basename( KTPP_BBPRESS_ENHANCE_FILE ),
			'kmix-39',
			'kotonoha-tena-bbpress-enhance',
			[]
		);
	}

}

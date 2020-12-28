<?php
namespace KTPP\bbPressEnhance\App\Common;

use Inc2734\WP_Plugin_View_Controller\Bootstrap;

class View {

	static function render( $_slug, $_args = [] ) {
		$_bootstrap = new Bootstrap(
			[
				'prefix' => 'ktpp_bbPressEnhance_',
				'path' => KTPP_BBPRESS_ENHANCE_PATH . '/View/',
			]
		);
		$_bootstrap->render( $_slug, null, $_args );
	}

}

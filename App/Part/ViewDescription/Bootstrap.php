<?php
namespace KTPP\bbPressEnhance\App\Part\ViewDescription;

class Bootstrap {

    function __construct() {
		add_action( 'bbp_template_before_single_forum', [ __CLASS__, '_add_forum_description' ] );
	}

	static function _add_forum_description() {
		echo bbp_get_forum_content();
	}

}

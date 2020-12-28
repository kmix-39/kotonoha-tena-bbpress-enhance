<?php
namespace KTPP\bbPressEnhance\App\Part\WCCoordination;

use KTPP\bbPressEnhance\App\Common\Helper;

class Bootstrap {

	function __construct() {
		if ( class_exists( '\woocommerce' ) ) {
			Helper::get_settings_value( 'linkage-wc-regist', false ) &&
				add_filter(
					'bbp_login_widget_register',
					[ __CLASS__, '_bbp_login_widget_register' ],
					1,
					3
				);
			Helper::get_settings_value( 'linkage-wc-lostpass', false ) &&
				add_filter(
					'bbp_login_widget_lostpass',
					[ __CLASS__, '_bbp_login_widget_lostpass' ],
					1,
					3
				);
		}
	}

	static function _bbp_login_widget_register( $_settings_register, $_instance, $_id_base ) {
		if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) {
			return get_permalink( wc_get_page_id( 'myaccount' ) );
		}
		return $_settings_register;
	}

	static function _bbp_login_widget_lostpass( $_settings_register, $_instance, $_id_base ) {
		return wc_lostpassword_url();
	}

}

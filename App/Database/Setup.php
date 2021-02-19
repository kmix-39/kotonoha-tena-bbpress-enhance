<?php
namespace KTPP\bbPressEnhance\App\Database;

class Setup {

	const OPTION_KEY = 'ktpp_bbpress_enhance_version';
	const PLUGIN_VERSION = '0.3.0';

	static function install() {
		$_installed_version = get_option( self::OPTION_KEY, '' );
		if ( self::PLUGIN_VERSION === $_installed_version ) {
			return false;
		}
		global $wpdb;
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		$_collate = '';
		if ( $wpdb->has_cap( 'collation' ) ) {
			$_collate = $wpdb->get_charset_collate();
		}
		$_tables = self::_get_tables();
		foreach ( $_tables as $_table ) {
			$_table::_create( $_collate );
		}
		update_option( self::OPTION_KEY, self::PLUGIN_VERSION );
		return true;
	}

	static function uninstall() {
		$_tables = self::_get_tables();
		foreach ( $_tables as $_table ) {
			$_table::_drop();
		}
		delete_option( self::OPTION_KEY );
	}

    private static function _get_tables() {
		return [
            new Table\StampLog(),
		];
	}

}

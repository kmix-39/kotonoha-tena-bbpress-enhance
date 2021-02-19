<?php
namespace KTPP\bbPressEnhance\App\Database\Table;

class StampLog {

	const TABLE_NAME = 'ktpp_stamp_log';

	static function _create( $_collate ) {
		global $wpdb;
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		$_table_name = $wpdb->prefix . self::TABLE_NAME;
		$_query = sprintf(
			'CREATE TABLE %1$s (
                ID BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                post_id BIGINT UNSIGNED NOT NULL,
				stamp_id BIGINT UNSIGNED NOT NULL,
				user_id BIGINT UNSIGNED NOT NULL,
				PRIMARY KEY (ID)
			) %2$s;',
			$_table_name,
			$_collate
		);
		dbDelta( $_query );
	}

	static function _drop() {
		global $wpdb;
		$_table_name = $wpdb->prefix . self::TABLE_NAME;
		$_query = sprintf(
			'DROP TABLE IF EXISTS %1$s',
			$_table_name
		);
		$wpdb->query( $_query );
	}

}

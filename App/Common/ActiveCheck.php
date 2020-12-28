<?php
namespace KTPP\bbPressEnhance\App\Common;

class ActiveCheck {

	private $_actived_theme = [];
	private $_actived_plugins = [];

	private function __construct() {}

	static function instance() {
		static $_instance = null;
		if ( null === $_instance ) {
			$_instance = new ActiveCheck();
		}
		return $_instance;
	}

	function is_theme_active( array $_items ) {
		if ( empty( $this->_actived_theme ) ) {
			$_now_theme = wp_get_theme( get_template() );
			$this->_actived_theme[$_now_theme->template] = $_now_theme->get( 'Version' );
		}
		foreach ( $_items as $_name => $_version ) {
			if ( isset( $this->_actived_theme[$_name] ) && version_compare( $this->_actived_theme[$_name], $_version, '>=' ) ) {
				return true;
			}
		}
		return false;
	}

	function is_plugin_active( array $_items ) {
		if ( ! function_exists( 'is_plugin_active' ) || ! function_exists( 'get_plugin_data' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		}
		foreach ( $_items as $_name => $_version ) {
			if ( ! isset( $this->_actived_plugins[$_name] ) ) {
				if ( ! is_file( WP_PLUGIN_DIR . '/' . $_name ) || ! \is_plugin_active( $_name ) ) {
					$this->_actived_plugins[$_name] = '0';
					continue;
				} else {
					$_plugin_data = get_plugin_data( WP_PLUGIN_DIR . '/' . $_name );
					$this->_actived_plugins[$_name] = $_plugin_data['Version'];
				}
			}
			if ( isset( $this->_actived_plugins[$_name] ) && version_compare( $this->_actived_plugins[$_name], $_version, '>=' ) ) {
				return true;
			}
		}
		return false;
	}

	function is_plugins_active( array $_items ) {
		if ( ! function_exists( 'is_plugin_active' ) || ! function_exists( 'get_plugin_data' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		}
		foreach ( $_items as $_name => $_version ) {
			if ( ! isset( $this->_actived_plugins[$_name] ) ) {
				if ( ! is_file( WP_PLUGIN_DIR . '/' . $_name ) || ! \is_plugin_active( $_name ) ) {
					$this->_actived_plugins[$_name] = '0';
					return false;
				} else {
					$_plugin_data = get_plugin_data( WP_PLUGIN_DIR . '/' . $_name );
					$this->_actived_plugins[$_name] = $_plugin_data['Version'];
				}
			}
			if ( ! isset( $this->_actived_plugins[$_name] ) || ! version_compare( $this->_actived_plugins[$_name], $_version, '>=' ) ) {
				return false;
			}
		}
		return true;
	}

}

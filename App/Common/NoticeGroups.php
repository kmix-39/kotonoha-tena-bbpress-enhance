<?php
namespace KTPP\bbPressEnhance\App\Common;

class NoticeGroups {

	private $_groups = [];
	private $_display_options = [];

	private function __construct() {}

	static function instance() {
		static $_instance = null;
		if ( null === $_instance ) {
			$_instance = new NoticeGroups();
		}
		return $_instance;
	}

	function add( $_slug, $_code, $_message ) {
		$this->_init_group( $_slug );
		$this->_groups[$_slug]->add( $_code, $_message );
	}

	function remove( $_slug, $_code ) {
		$this->_init_group( $_slug );
		$this->_groups[$_slug]->remove( $_code );
	}

	function count( $_slug ) {
		return isset( $this->_groups[$_slug] ) ?
			$this->_groups[$_slug]->get_count() :
			0;
	}

	function display( $_slug, $_type = '' ) {
		if ( empty( $this->_display_options ) ) {
			add_action( 'admin_notices', [ $this, '_do_admin_notices' ] );
			add_action( 'network_admin_notices', [ $this, '_do_admin_notices' ] );
		}
		$this->_display_options[$_slug] = $_type;
	}

	function _do_admin_notices() {
		if ( empty( $this->_display_options ) ) {
			return;
		}
		foreach ( $this->_display_options as $_slug => $_type ) {
			if ( ! $this->_groups[$_slug] instanceof Group ) {
				continue;
			}
			$_group = $this->_groups[$_slug];
			$_codes = $_group->get_codes();
			if ( ! empty( $_codes ) ) {
				echo '<div class="notice ' . esc_attr( $_type ) . ' is-dismissible">';
				foreach ( $_codes as $_code ) {
					$_messages = $_group->get_messages( $_code );
					if ( empty( $_messages ) ) {
						continue;
					}
					echo '<ul>';
					foreach ( $_messages as $_message ) {
						if ( '' === esc_html( $_message ) ) {
							continue;
						}
						echo '<li>' . esc_html( $_message ) . '</li>';
					}
					echo '</ul>';
				}
				echo '</div>';
			}
		}
	}

	private function _init_group( $_slug ) {
		if ( ! isset( $this->_groups[$_slug] ) || ! $this->_groups[$_slug] instanceof Group ) {
			$this->_groups[$_slug] = new Group;
		}
	}

}

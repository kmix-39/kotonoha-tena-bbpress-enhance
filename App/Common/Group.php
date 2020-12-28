<?php
namespace KTPP\bbPressEnhance\App\Common;

use WP_Error;

final class Group {

	private $_options = null;

	function init_options() {
		$this->_options = new WP_Error();
	}

	function get_codes() {
		return ( null !== $this->_options ) ?
			$this->_options->get_error_codes() :
			[];
	}

	function get_messages( $_code ) {
		return ( null !== $this->_options ) ?
			$this->_options->get_error_messages( $_code ) :
			[];
	}

	function add( $_code, $_message ) {
		if ( null === $this->_options ) {
			$this->init_options();
		}
		$this->_options->add( $_code, $_message, '' );
	}

	function remove( $_code ) {
		if ( null !== $this->_options ) {
			$this->_options->remove( $_code );
		}
	}

	function get_count() {
		return ( null !== $this->_options ) ?
			count( $this->_options->get_error_messages() ) :
			0;
	}

}

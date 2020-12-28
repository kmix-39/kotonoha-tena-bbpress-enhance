<?php
namespace KTPP\bbPressEnhance\App\Part\AdvanceForumSearch;

use KTPP\bbPressEnhance\App\Common\Helper;

class Bootstrap {

	function __construct() {
		add_action( 'wp_enqueue_scripts', [ __CLASS__, '_enqueue_scripts_ktpp_bbpress_enhance' ] );
		add_filter( 'bbp_before_has_search_results_parse_args' , [ __CLASS__, '_bbp_before_has_search_results_parse_args' ] );
		add_filter( 'bbp_get_template_part', [ __CLASS__, '_bbp_form_search_template' ], 10, 3 );
		//add_action( 'bbp_template_before_search', [ __CLASS__, '_bbp_template_before_search' ], 10, 0 );
	}

	static function _enqueue_scripts_ktpp_bbpress_enhance() {
		wp_enqueue_style(
			'ktpp-bbpress-enhance',
			KTPP_BBPRESS_ENHANCE_URL . '/assets/styles/ktpp-bbpress-enhance.css',
			[ \Framework\Helper::get_main_style_handle() ],
			filemtime( KTPP_BBPRESS_ENHANCE_PATH . '/assets/styles/ktpp-bbpress-enhance.css' )
		);
	}

	static function _bbp_before_has_search_results_parse_args( $r ) {
		$r['meta_query'] = isset( $r['meta_query'] ) ?: [];

		if ( isset( $_GET['bbp_forum_id'] ) ) {
			$forum_id = sanitize_title_for_query( $_GET['bbp_forum_id'] );
			if ( $forum_id && is_numeric( $forum_id ) ) {
				$r['meta_query'] = array_merge(
					$r['meta_query'], [
						[
							'key' => '_bbp_forum_id',
							'value' => $forum_id,
							'compare' => '=',
						]
					]
				);
			}
		}
		if ( isset( $_GET['bbp_type'] ) ) {
			$r['post_type'] = $_GET['bbp_type'];
		}
		if ( isset( $_GET['bbp_topic_status'] ) ) {
			$r['post_status'] = $_GET['bbp_topic_status'];
		}
		if ( isset( $_GET['bbp_replyed'] ) ) {
			if ( in_array( $_GET['bbp_replyed'], [ '=', '>' ] ) ) {
				$r['meta_query'] = array_merge(
					$r['meta_query'], [
						[
							'key' => '_bbp_reply_count',
							'value' => '0',
							'compare' => $_GET['bbp_replyed'],
						]
					]
				);
			}
		}

		

		return $r;
	}

	static function _bbp_form_search_template( $_templates, $_slug, $_name ) {
		if ( 'form' === $_slug && 'search' === $_name && ! Helper::is_widget() ) {
			add_filter( 'bbp_get_template_stack', [ __CLASS__, '_add_template_stack' ] );
			$_templates = [ $_slug . '-' . $_name . '-advance.php', 'form.php' ];
		}
		return $_templates;
	}

	static function _add_template_stack( $_stack ) {
		return array_merge( $_stack, [ KTPP_BBPRESS_ENHANCE_PATH . '/App/View/bbPress' ] );
	}

	static function _bbp_template_before_search() {
		if ( bbp_has_search_results() ) {
			
		}
	}

}

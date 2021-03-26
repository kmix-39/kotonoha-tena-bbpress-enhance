<?php
namespace KTPP\bbPressEnhance\App\Part\AdvanceForumSearch;

use KTPP\bbPressEnhance\App\Common\Helper;

class Bootstrap {

	function __construct() {
		add_action( 'wp_enqueue_scripts', [ __CLASS__, '_enqueue_scripts_ktpp_bbpress_enhance' ] );
		add_filter( 'bbp_before_has_search_results_parse_args', [ __CLASS__, '_bbp_before_has_search_results_parse_args' ] );
		add_filter( 'bbp_get_template_part', [ __CLASS__, '_bbp_form_search_template' ], 10, 3 );
	}

	static function _enqueue_scripts_ktpp_bbpress_enhance() {
		if ( ! is_bbpress() ) return;

		$_is_load = false;
		// フォーラム時対応
		$_is_load |= bbp_is_forum_archive() || bbp_is_topic_archive() || bbp_is_topic_tag();
		// 検索キーワードを入力されていない場合、検索が見つからない場合
		$_is_load |= ( bbp_is_search() && ! bbp_has_search_results() && ! bbp_get_search_terms() );
		// 検索結果がない場合でもフォームを出す場合
		Helper::get_settings_value( 'always-display-search-form', false ) &&
			$_is_load |= ( bbp_is_search() && ! bbp_has_search_results() );
		// 検索結果一覧でフォームが有効な場合
		Helper::get_settings_value( 'result-display-search-form', false ) &&
			$_is_load |= ( bbp_is_search() );

		if ( $_is_load ) {
			Helper::enqueue_scripts_ktpp_bbpress_enhance( 'search' );
			Helper::enqueue_styles_ktpp_bbpress_enhance( 'search' );
		}
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
		if ( isset( $_GET['bbp_type'] ) && ! empty( $_GET['bbp_type'] ) ) {
			$r['post_type'] = $_GET['bbp_type'];
		}
		if ( isset( $_GET['bbp_topic_status'] ) && ! empty( $_GET['bbp_topic_status'] ) ) {
			$r['post_status'] = $_GET['bbp_topic_status'];
		}
		if ( isset( $_GET['bbp_replyed'] ) && ! empty( $_GET['bbp_replyed'] ) ) {
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

}

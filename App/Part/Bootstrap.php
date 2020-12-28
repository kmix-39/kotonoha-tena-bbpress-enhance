<?php
namespace KTPP\bbPressEnhance\App\Part;

use KTPP\bbPressEnhance\App\Common\Helper;

class Bootstrap {

	function __construct() {
		add_action( 'init', [ __CLASS__, '_init' ] );
	}

	static function _init() {
		// 検索結果
		new TermsSearchForm\Bootstrap();
		// ログイン / ログアウト
		Helper::get_settings_value( 'ajax-login', false ) && new AjaxLogin\Bootstrap();
		// WooCommerce 連携
		new WCCoordination\Bootstrap();
		// フォーラム説明表示
		Helper::get_settings_value( 'display-form-description', false ) && new ViewDescription\Bootstrap();
		// 検索条件拡張
		Helper::get_settings_value( 'is-use-advance-search', false ) && new AdvanceForumSearch\Bootstrap();
	}

}

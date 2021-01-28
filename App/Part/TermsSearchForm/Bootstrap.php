<?php
namespace KTPP\bbPressEnhance\App\Part\TermsSearchForm;

use KTPP\bbPressEnhance\App\Common\Helper;

class Bootstrap {

	function __construct() {
		Helper::get_settings_value( 'display-alert-no-keyword', false ) &&
			add_action(
				'bbp_template_before_search',
				[ __CLASS__, '_bbp_template_before_search' ],
				10,
				0
			);

		Helper::get_settings_value( 'always-display-search-form', false ) &&
			add_action(
				'bbp_template_after_search_results',
				[ __CLASS__, '_bbp_template_after_search_results' ],
				10,
				0
			);

		Helper::get_settings_value( 'result-display-search-form', false ) &&
			add_filter(
				'snow_monkey_pre_template_part_render_template-parts/content/entry/content/content',
				[ __CLASS__, '_render_bbp_search_form_before_results' ],
				10,
				3
			);
	}

	static function _bbp_template_before_search() {
		if ( ! bbp_has_search_results() && ! bbp_get_search_terms() ) {
?>
	<div class="bbp-template-notice">
		<ul>
			<li><?php esc_html_e( 'Please enter a search keyword.', 'ktpp-bbpress-enhance' ); ?></li>
		</ul>
	</div>
<?php
		}
	}

	static function _bbp_template_after_search_results() {
		if ( bbp_get_search_terms() && ! bbp_has_search_results() ) {
			bbp_get_template_part( 'form', 'search' );
		}
	}

	static function _render_bbp_search_form_before_results( $html, $name, $vars ) {
		if ( bbp_get_search_terms() && bbp_has_search_results() ) {
			bbp_get_template_part( 'form', 'search' );
		}
		return $html;
	}

}

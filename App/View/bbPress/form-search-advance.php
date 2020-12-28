<?php

/**
 * Search 
 *
 * @package bbPress
 * @subpackage Theme
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

use KTPP\bbPressEnhance\App\Common\Helper;
if ( bbp_allow_search() ) :
?>
	<div class="bbp-search-form">
		<form role="search" method="get" id="bbp-search-form" action="<?php bbp_search_url(); ?>">
			<div>
				<label class="screen-reader-text hidden" for="bbp_search"><?php esc_html_e( 'Search for:', 'bbpress' ); ?></label>
				<input type="text" value="<?php bbp_search_terms(); ?>" name="bbp_search" id="bbp_search" />
				<input class="button" type="submit" id="bbp_search_submit" value="<?php esc_attr_e( 'Search', 'bbpress' ); ?>" />
			</div>
			<div class="ktpp-bbpress-enhance-accordion">
				<div class="ktpp-bbpress-enhance-accordion__item">
					<input type="checkbox" class="ktpp-bbpress-enhance-accordion__item__control">
					<div class="ktpp-bbpress-enhance-accordion__item__title">
						<span class="ktpp-bbpress-enhance-accordion__item__title__label"><?php esc_html_e( 'Search criteria', 'ktpp-bbpress-enhance' ); ?></span>
						<div class="ktpp-bbpress-enhance-accordion__item__title__icon"><i class="fas fa-angle-down"></i></div>
					</div>
					<div class="ktpp-bbpress-enhance-accordion__item__body">
						<div>
							<div><?php esc_html_e( 'Forum', 'ktpp-bbpress-enhance' ); ?></div>
<?php $_forums = Helper::get_bbp_forums(); ?>
							<div class="c-select c-select--block">
								<select class="c-select__control" name="bbp_forum_id">
									<option value=""><?php  esc_html_e( 'all', 'ktpp-bbpress-enhance' ); ?></option>
<?php
	$_selected_forum_id = isset( $_GET['bbp_forum_id'] ) ? $_GET['bbp_forum_id'] : '';
	foreach ( $_forums as $_forum ) :
?>
									<option value="<?php echo esc_attr( $_forum->ID ); ?>"<?php selected( $_selected_forum_id, $_forum->ID ); ?>><?php echo get_the_title( $_forum->ID ); ?></option>
<?php endforeach; ?>
								</select>
								<span class="c-select__toggle"></span>
							</div>
						</div>

						<div>
							<div><?php esc_html_e( 'Type', 'ktpp-bbpress-enhance' ); ?></div>
<?php $_selected_type = isset( $_GET['bbp_type'] ) ? $_GET['bbp_type'] : ''; ?>
							<div class="c-select c-select--block">
								<select class="c-select__control" name="bbp_type" data-name="bbp_type">
									<option value=""><?php  esc_html_e( 'all', 'ktpp-bbpress-enhance' ); ?></option>
									<option value="<?php echo esc_attr( bbp_get_topic_post_type() ); ?>"<?php selected( $_selected_type, bbp_get_topic_post_type() ); ?>><?php  esc_html_e( 'topic', 'ktpp-bbpress-enhance' ); ?></option>
									<option value="<?php echo esc_attr( bbp_get_reply_post_type() ); ?>"<?php selected( $_selected_type, bbp_get_reply_post_type() ); ?>><?php  esc_html_e( 'reply', 'ktpp-bbpress-enhance' ); ?></option>
								</select>
								<span class="c-select__toggle"></span>
							</div>
						</div>

						<div class="ktpp-bbp-criteria-topic">
							<div><?php esc_html_e( 'Topic status', 'ktpp-bbpress-enhance' ); ?></div>
<?php $_selected_topic_status = isset( $_GET['bbp_topic_status'] ) ? $_GET['bbp_topic_status'] : ''; ?>
							<div class="c-select c-select--block">
								<select class="c-select__control" name="bbp_topic_status">
									<option value=""><?php  esc_html_e( 'all', 'ktpp-bbpress-enhance' ); ?></option>
									<option value="<?php echo esc_attr( bbp_get_public_status_id() ); ?>"<?php selected( $_selected_topic_status, bbp_get_public_status_id() ); ?>><?php  esc_html_e( 'open', 'ktpp-bbpress-enhance' ); ?></option>
									<option value="<?php echo esc_attr( bbp_get_closed_status_id() ); ?>"<?php selected( $_selected_topic_status, bbp_get_closed_status_id() ); ?>><?php  esc_html_e( 'closed', 'ktpp-bbpress-enhance' ); ?></option>
								</select>
								<span class="c-select__toggle"></span>
							</div>
						</div>

						<div class="ktpp-bbp-criteria-topic">
							<div><?php esc_html_e( 'Replyed', 'ktpp-bbpress-enhance' ); ?></div>
<?php $_selected_replyed = isset( $_GET['bbp_replyed'] ) ? $_GET['bbp_replyed'] : ''; ?>
							<div class="c-select c-select--block">
								<select class="c-select__control" name="bbp_replyed">
									<option value=""><?php  esc_html_e( 'all', 'ktpp-bbpress-enhance' ); ?></option>
									<option value="<?php echo esc_attr( '=' ); ?>"<?php selected( $_selected_replyed, '=' ); ?>><?php  esc_html_e( 'No replyed', 'ktpp-bbpress-enhance' ); ?></option>
									<option value="<?php echo esc_attr( '>' ); ?>"<?php selected( $_selected_replyed, '>' ); ?>><?php  esc_html_e( 'replyed', 'ktpp-bbpress-enhance' ); ?></option>
								</select>
								<span class="c-select__toggle"></span>
							</div>
						</div>

					</div>
				</div>
			</div>
		</form>
	</div>
<?php
endif;

<?php
	defined( 'ABSPATH' ) || exit;
	use KTPP\bbPressEnhance\App\Common\Helper;

	$_user_id = bbp_get_current_user_id();
?>
<div class="bbp-logged-in">
	<a href="<?php bbp_user_profile_url( $_user_id ); ?>" class="submit user-submit"><?php echo get_avatar( $_user_id, '40' ); ?></a>
	<h4><?php bbp_user_profile_link( $_user_id ); ?></h4>
<?php if ( Helper::get_settings_value( 'display-element-form', false ) ) : ?>
	<p>
		<span class="bbp-author-role"><?php bbp_user_display_role( $_user_id ); ?></span>
<?php
	if ( apply_filters( 'snow_monkey_bbpress_support_activate_replies_stars_feature', '__return_true' ) ) :
		$_stars = get_user_meta( $_user_id, 'smbbpress-support-stars', true );
		$_stars = $_stars ? $_stars : 0;
		$_icon = apply_filters( 'snow_monkey_bbpress_support_replies_stars_icon', '&hearts;' );
?>
		<span class="smbbpress-stars">
			<span class="smbbpress-stars__stars"><?php echo wp_kses_post( $_icon ); ?></span>
			<span class="smbbpress-stars__count"><?php echo wp_kses_post( $_stars ); ?></span>
		</span>
<?php endif; ?>
	</p>
<?php endif; ?>
	<?php bbp_logout_link(); ?>
</div>

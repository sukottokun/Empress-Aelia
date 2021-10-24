<?php
/**
 * Dynamic Posts Condition logic swiftly crafted.
 *
 * @since 4.11.0
 *
 * @package     Divi
 * @sub-package Builder
 */

namespace Module\Field\DisplayConditions;

/**
 * Dynamic Posts Condition Trait.
 */
trait DynamicPostsCondition {

	/**
	 * Processes "Dynamic Posts" condition.
	 *
	 * @since 4.11.0
	 *
	 * @param  array $condition_settings Containing all settings of the condition.
	 *
	 * @return boolean Condition output.
	 */
	protected function _process_dynamic_posts_condition( $condition_settings ) {
		// Only check for Posts.
		if ( ! is_singular() ) {
			return false;
		}

		$display_rule      = isset( $condition_settings['dynamicPostsDisplay'] ) ? $condition_settings['dynamicPostsDisplay'] : '';
		$dynamic_posts_raw = isset( $condition_settings['dynamicPosts'] ) ? $condition_settings['dynamicPosts'] : [];
		$dynamic_posts_ids = array_map(
			function( $item ) {
				return isset( $item['value'] ) ? $item['value'] : '';
			},
			$dynamic_posts_raw
		);
		$is_on_shop_page   = class_exists( 'WooCommerce' ) && is_shop();
		$current_page_id   = $is_on_shop_page ? wc_get_page_id( 'shop' ) : get_queried_object_id();

		$should_display = array_intersect( $dynamic_posts_ids, (array) $current_page_id ) ? true : false;

		return ( 'is' === $display_rule ) ? $should_display : ! $should_display;
	}

}

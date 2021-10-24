<?php
/**
 * Page Visit Condition logic swiftly crafted.
 *
 * @since 4.11.0
 *
 * @package     Divi
 * @sub-package Builder
 */

namespace Module\Field\DisplayConditions;

/**
 * Page Visit Condition Trait.
 */
trait PageVisitCondition {

	/**
	 * Processes "Page Visit" condition.
	 *
	 * @since 4.11.0
	 *
	 * @param  array $condition_settings Containing all settings of the condition.
	 *
	 * @return boolean Condition output.
	 */
	protected function _process_page_visit_condition( $condition_settings ) {
		$display_rule              = isset( $condition_settings['pageVisitDisplay'] ) ? $condition_settings['pageVisitDisplay'] : 'hasVisitedSpecificPage';
		$pages_raw                 = isset( $condition_settings['pages'] ) ? $condition_settings['pages'] : [];
		$pages_ids                 = array_map(
			function( $item ) {
				return isset( $item['value'] ) ? (int) $item['value'] : '';
			},
			$pages_raw
		);
		$has_visited_specific_page = false;

		if ( isset( $_COOKIE['divi_post_visit'] ) ) {
			// phpcs:ignore ET.Sniffs.ValidatedSanitizedInput, WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_decode  -- Cookie is not stored or displayed therefore XSS safe, base64_decode returned data is an array and necessary validation checks are performed.
			$cookie                    = json_decode( base64_decode( $_COOKIE['divi_post_visit'] ), true );
			$col                       = array_column( $cookie, 'id' );
			$has_visited_specific_page = array_intersect( $pages_ids, $col ) ? true : false;
		}

		$should_display = $has_visited_specific_page;

		return ( 'hasVisitedSpecificPage' === $display_rule ) ? $should_display : ! $should_display;
	}


}

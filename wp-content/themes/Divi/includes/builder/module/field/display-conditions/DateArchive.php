<?php
/**
 * Date Archive condition logic swiftly crafted.
 *
 * @since 4.11.0
 *
 * @package     Divi
 * @sub-package Builder
 */

namespace Module\Field\DisplayConditions;

use DateTimeImmutable;

/**
 * Date Archive Condition Trait.
 */
trait DateArchiveCondition {

	/**
	 * Processes "Date Archive" condition.
	 *
	 * @since 4.11.0
	 *
	 * @param  array $all_settings Containing all settings of the condition.
	 *
	 * @return boolean Condition output.
	 */
	protected function _process_date_archive_condition( $all_settings ) {
		if ( ! is_date() ) {
			return false;
		}

		$display_rule = isset( $all_settings['dateArchiveDisplay'] ) ? $all_settings['dateArchiveDisplay'] : 'isAfter';
		$date         = isset( $all_settings['dateArchive'] ) ? $all_settings['dateArchive'] : '';

		$year         = get_query_var( 'year' );
		$monthnum     = get_query_var( 'monthnum' ) === 0 ? 1 : get_query_var( 'monthnum' );
		$day          = get_query_var( 'day' ) === 0 ? 1 : get_query_var( 'day' );
		$archive_date = sprintf( '%s-%s-%s', $year, $monthnum, $day );

		$target_date         = new DateTimeImmutable( $date, wp_timezone() );
		$current_arhive_date = new DateTimeImmutable( $archive_date, wp_timezone() );

		switch ( $display_rule ) {
			case 'isAfter':
				return ( $current_arhive_date > $target_date );

			case 'isBefore':
				return ( $current_arhive_date < $target_date );

			default:
				return ( $current_arhive_date > $target_date );
		}
	}

}

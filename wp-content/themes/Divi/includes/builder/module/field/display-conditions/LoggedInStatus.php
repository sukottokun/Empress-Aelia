<?php
/**
 * Logged In Status Condition logic swiftly crafted.
 *
 * @since 4.11.0
 *
 * @package     Divi
 * @sub-package Builder
 */

namespace Module\Field\DisplayConditions;

/**
 * Logged In Status Condition Trait.
 */
trait LoggedInStatusCondition {

	/**
	 * Processes "Logged In Status" condition.
	 *
	 * @since 4.11.0
	 *
	 * @param  array $condition_settings Containing all settings of the condition.
	 *
	 * @return boolean Condition output.
	 */
	protected function _process_logged_in_status_condition( $condition_settings ) {
		$logged_in_status = isset( $condition_settings['loggedInStatus'] ) ? $condition_settings['loggedInStatus'] : 'loggedIn';
		$should_display   = ( is_user_logged_in() ) ? true : false;
		return ( 'loggedIn' === $logged_in_status ) ? $should_display : ! $should_display;
	}

	/**
	 * Checks logged in status for possible conflicts.
	 *
	 * @param string $current_value      Curent setting value.
	 * @param string $prev_value         Previous setting value.
	 * @param array  $conflicting_value  Defined conflicting value.
	 * @return boolean
	 */
	protected function _is_logged_in_status_conflicted( $current_value, $prev_value, $conflicting_value ) {
		$is_current_value_conflicted = in_array( $current_value, $conflicting_value, true );
		$is_prev_value_conflicted    = in_array( $prev_value, $conflicting_value, true );
		if ( $is_current_value_conflicted && $is_prev_value_conflicted ) {
			return true;
		}
		return false;
	}

}

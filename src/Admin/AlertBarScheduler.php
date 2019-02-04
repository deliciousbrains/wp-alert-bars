<?php


namespace DeliciousBrains\WPAlertBars\Admin;

class AlertBarScheduler {

	public function init() {
		add_filter( 'acf/update_value', array( $this, 'maybe_schedule_alert_bar_expiry' ), 10, 3 );
		add_action( 'expire_alert_bar', array( $this, 'cron_expire_alert_bar' ) );
	}

	/**
	 * If the expiry date of the alert bar has changed schedule the event to trash the post.
	 *
	 * @param mixed $value
	 * @param int   $post_id
	 * @param array $field
	 *
	 * @return mixed
	 */
	public function maybe_schedule_alert_bar_expiry( $value, $post_id, $field ) {
		if ( 'ends' !== $field['name'] ) {
			return $value;
		}

		$old_value = get_post_meta( $post_id, 'ends', true );

		if ( $old_value === $value ) {
			return $value;
		}

		if ( empty( $value ) ) {
			$this->unschedule_alert_bar_expiry( $post_id, $old_value );

			return $value;
		}


		$this->schedule_alert_bar_expiry( $post_id, $value, $old_value );

		return $value;
	}

	/**
	 * Schedule the trash event
	 *
	 * @param int    $post_id
	 * @param string $new_expiry
	 * @param string $old_expiry
	 */
	protected function schedule_alert_bar_expiry( $post_id, $new_expiry, $old_expiry ) {
		if ( $old_expiry ) {
			$this->unschedule_alert_bar_expiry( $post_id, $old_expiry );
		}

		wp_schedule_single_event( strtotime( $new_expiry ), 'expire_alert_bar', array( 'post_id' => $post_id ) );
	}

	/**
	 * Unschedule the trash event
	 *
	 * @param int    $post_id
	 * @param string $expiry
	 */
	protected function unschedule_alert_bar_expiry( $post_id, $expiry ) {
		wp_unschedule_event( strtotime( $expiry ), 'expire_alert_bar', array( 'post_id' => $post_id ) );
	}

	/**
	 * Trash the alert bar
	 *
	 * @param int $post_id
	 */
	public function cron_expire_alert_bar( $post_id ) {
		wp_trash_post( $post_id );
	}
}
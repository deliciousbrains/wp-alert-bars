<?php
namespace DeliciousBrains\WPAlertBars\Admin;

use DeliciousBrains\WPAlertBars\PostType\AlertBar;

class ACF {

	public function init() {
		add_action( 'admin_init', array( $this, 'load_field_group_config' ) );
	}

	/**
	 * Load field group config from PHP files.
	 */
	public function load_field_group_config() {
		if ( ! function_exists( 'acf_add_local_field_group' ) ) {
			return;
		}

		if ( ! is_admin() && ! ( defined( 'WP_CLI' ) && WP_CLI ) ) {
			return;
		}

		$post_type = AlertBar::get_post_type();

		foreach ( glob( DBI_ALERT_BAR_BASE_DIR . '/config/*.php' ) as $file ) {
			require_once $file;
		}
	}
}
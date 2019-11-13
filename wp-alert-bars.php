<?php
/**
 * Plugin Name: Delicious Brains WP Alert Bars
 * Plugin URI: https://deliciousbrains.com
 * Description: WordPress must-use plugin for managing alert bars.
 * Author: Delicious Brains
 * Version: 1.0
 * Author URI: https://deliciousbrains.com
 **/

define( 'DBI_ALERT_BAR_BASE_DIR', WPMU_PLUGIN_DIR . '/' . basename( __DIR__ ) );
define( 'DBI_ALERT_BAR_BASE_URL', WPMU_PLUGIN_URL . '/' . basename( __DIR__ ) );

if ( ! class_exists( 'ACF' ) ) {
	return;
}

( new DeliciousBrains\WPAlertBars\Admin\AlertBarScheduler() )->init();
( new DeliciousBrains\WPAlertBars\Admin\AlertBarRules() )->init();
( new DeliciousBrains\WPAlertBars\PostType\AlertBar() )->init();
( new DeliciousBrains\WPAlertBars\Display() )->init();

if ( is_admin() ) {
	( new \DeliciousBrains\WPAlertBars\Admin\ACF() )->init();
}

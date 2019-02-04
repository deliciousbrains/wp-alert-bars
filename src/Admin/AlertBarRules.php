<?php

namespace DeliciousBrains\WPAlertBars\Admin;

require_once WP_PLUGIN_DIR . '/advanced-custom-fields-pro/includes/admin/admin-field-group.php';

use DeliciousBrains\WPAlertBars\PostType\AlertBar;

class AlertBarRules extends \acf_admin_field_group {

	protected $post_type;
	const META_KEY = 'alert_bar_locations';

	public function __construct() {
		$this->post_type = AlertBar::get_post_type();
	}

	public function init() {
		add_action( 'current_screen', array( $this, 'current_screen' ) );
		add_action( 'save_post', array( $this, 'save_post' ), 10, 2 );
		add_action( 'wp_ajax_acf/field_group/render_location_rule', array( $this, 'ajax_render_location_rule' ) );
	}

	public function current_screen() {
		if ( ! acf_is_screen( $this->post_type ) ) {
			return;
		}

		acf_enqueue_scripts();

		add_action( 'acf/input/admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'acf/input/admin_head', array( $this, 'admin_head' ) );
		add_action( 'acf/input/form_data', array( $this, 'form_data' ) );
		add_action( 'acf/input/admin_footer', array( $this, 'admin_footer' ) );
		add_action( 'acf/input/admin_footer_js', array( $this, 'admin_footer_js' ) );
	}

	public function admin_head() {
		add_meta_box( 'acf-field-group-locations', __( "Location", 'acf' ), array( $this, 'mb_locations' ), $this->post_type, 'normal', 'high' );
	}

	public function mb_locations() {
		global $post, $field_group;

		$locations               = get_post_meta( $post->ID, self::META_KEY, true );
		$field_group['location'] = $locations;

		// UI needs at lease 1 location rule
		if ( empty( $field_group['location'] ) ) {
			$field_group['location'] = array(
				array(
					array(
						'param'    => 'page',
						'operator' => '!=',
						'value'    => '8',
					),
				),

			);
		}

		acf_get_view( 'field-group-locations' );
	}

	public function save_post( $post_id, $post ) {
		// do not save if this is an auto save routine
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// bail early if not acf-field-group
		if ( $post->post_type !== $this->post_type ) {
			return;
		}

		// only save once! WordPress save's a revision as well.
		if ( wp_is_post_revision( $post_id ) ) {
			return;
		}


		// Bail early if request came from an unauthorised user.
		if ( ! current_user_can( acf_get_setting( 'capability' ) ) ) {
			return;
		}

		if ( ! isset( $_POST['acf_field_group'] ) ) {
			return;
		}

		$field_group = $_POST['acf_field_group'];

		// locations may contain 'uniquid' array keys
		$field_group['location'] = array_values( $field_group['location'] );
		$locations               = array();

		foreach ( $field_group['location'] as $k => $v ) {
			$locations[ $k ] = array_values( $v );
		}

		update_post_meta( $post_id, self::META_KEY, $locations );
	}
}
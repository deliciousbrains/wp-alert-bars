<?php

namespace DeliciousBrains\WPAlertBars\Model;

use DeliciousBrains\WPAlertBars\Admin\AlertBarRules;
use DeliciousBrains\WPPostTypes\Model\Post;

class AlertBar extends Post {

	/**
	 * AlertBar constructor.
	 *
	 * @param mixed $data
	 */
	public function __construct( $data = null ) {
		parent::__construct( $data );
		if ( is_null( $this->post ) ) {
			return;
		}

		$this->key       = $this->post_name;
		$this->message   = $this->post_content;
		$this->cta       = $this->meta( 'cta', array() );
		$this->is_footer = 'header' !== $this->meta( 'type', 'header' );
		$this->is_sticky = $this->meta( 'sticky', false );
		$this->locations = $this->meta( AlertBarRules::META_KEY, array() );
	}

	/**
	 * Should this alert bar be displayed based on the page and rules defined?
	 *
	 * @return bool
	 */
	public function can_show() {
		if ( empty( $this->locations ) ) {
			return true;
		}

		$args = array(
			'post_id'   => -1,
			'post_type' => 'page',
		);

		if ( ! is_home() && ! is_archive() && ! is_search() ) {
			global $post;

			if ( ! empty( $post ) ) {
				$args = array(
					'post_id'   => $post->ID,
					'post_type' => $post->post_type,
				);
			}
		}

		$screen = acf_get_location_screen( $args, array() );

		foreach ( $this->locations as $group_id => $group ) {
			if ( empty( $group ) ) {
				continue;
			}

			$match_group = true;
			foreach ( $group as $rule_id => $rule ) {

				if ( ! acf_match_location_rule( $rule, $screen ) ) {
					$match_group = false;
					break;
				}
			}

			// this group matches screen. Ignore remaining groups and return true
			if ( $match_group ) {
				return true;
			}
		}

		return false;
	}
}
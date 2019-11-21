<?php


namespace DeliciousBrains\WPAlertBars\PostType;

use DeliciousBrains\WPAlertBars\Display;
use DeliciousBrains\WPPostTypes\PostType\AbstractPostType;

class AlertBar extends AbstractPostType {

	protected $single = 'alert bar';
	protected $icon = 'megaphone';
	protected $supports = array( 'title', 'editor' );

	public function init() {
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );

		parent::init();
	}

	protected static function get_posts( $query ) {
		$posts = $query->get_posts();
		foreach ( $posts as $key => $post ) {
			$class = self::get_model_class();
			$bar   = new $class( $post );
			if ( ! $bar->can_show() ) {
				unset( $posts[ $key ] );
				continue;
			}
			$posts[ $key ] = $bar;
		}

		return array_values( $posts );
	}

	public function admin_scripts() {
		global $post_type;
		if ( ! isset( $post_type ) || self::get_post_type() !== $post_type ) {
			return;
		}

		Display::enqueue( 'admin/alert-bars.js', 'dbi-alertbars', array( 'jquery' ), null, true );
		Display::enqueue( 'admin/alert-bars.css', 'dbi-alertbars' );
	}
}
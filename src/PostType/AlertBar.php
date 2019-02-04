<?php


namespace DeliciousBrains\WPAlertBars\PostType;

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

		wp_enqueue_script( 'dbi-alertbars', DBI_ALERT_BAR_BASE_URL . '/assets/js/alertbars.min.js', array( 'jquery' ), null, true );
		wp_enqueue_style( 'dbi-alertbars', DBI_ALERT_BAR_BASE_URL . '/assets/css/alertbars.min.css' );
	}
}
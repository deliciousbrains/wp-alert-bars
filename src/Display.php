<?php

namespace DeliciousBrains\WPAlertBars;

class Display {

	/**
	 * @var array
	 */
	protected static $bars;

	public function init() {
		add_action( 'wp_head', array( $this, 'render' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts' ), 100 );
	}

	/**
	 * Register alert bars HTML
	 */
	public function render() {
		foreach ( self::$bars as $alert_bar ) {
			$this->template( 'alert-bar', array( 'alert_bar' => $alert_bar ) );
		}
	}

	/**
	 * Register alert bars and JS
	 */
	public function register_scripts() {
		self::$bars = PostType\AlertBar::all();
		if ( empty( self::$bars ) ) {
			return;
		}

		self::enqueue( 'jquery.cookie.min.js', 'dbi-cookie', array( 'jquery' ), null, true );
		self::enqueue( 'alert-bar.min.js', 'dbi-alertbar', array( 'dbi-cookie' ), null, true );
		self::enqueue( 'alert-bar.min.css', 'dbi-alertbar' );
	}

	/**
	 * Render a template file
	 *
	 * @param string $part Part filename without the extension
	 * @param array  $args Arguments to pass to the view
	 */
	protected function template( $part, $args = array() ) {
		extract( $args );
		include DBI_ALERT_BAR_BASE_DIR . '/templates/' . $part . '.php';
	}

	/**
	 * @param       $file
	 * @param       $name
	 * @param array $deps
	 * @param null  $version
	 * @param bool  $in_footer
	 *
	 * @return mixed
	 */
	public static function enqueue( $file, $name, $deps = array(), $version = null, $in_footer = false ) {
		$parts = explode( '.', $file );
		$ext   = array_pop( $parts );
		$file  = implode( '.', $parts );

		$base = '/assets/' . $ext . '/' . $file  . '.' . $ext;

		$path = DBI_ALERT_BAR_BASE_DIR . $base;
		$src  = DBI_ALERT_BAR_BASE_URL . $base;

		if ( is_null( $version ) ) {
			$version = filemtime( $path );
		}

		if ( 'js' === $ext ) {
			wp_enqueue_script( $name, $src, $deps, $version, $in_footer );
		} else {
			wp_enqueue_style( $name, $src, $deps, $version );
		}

		return $name;
	}
}
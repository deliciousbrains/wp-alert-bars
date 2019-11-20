<?php

namespace DeliciousBrains\WPAlertBars;

class Display {

	/**
	 * @var array
	 */
	protected static $bars = false;

	public function init() {
		add_action( 'wp_body_open', array( $this, 'render' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts' ), 100 );
		add_filter( 'body_class', array( $this, 'add_body_class' ) );
	}

	/**
	 * @return array
	 */
	protected function get_bars() {
		if ( false !== self::$bars ) {
			return self::$bars;
		}

		self::$bars = PostType\AlertBar::all();

		return self::$bars;
	}

	/**
	 * Register alert bars HTML
	 */
	public function render() {
		foreach ( $this->get_bars() as $alert_bar ) {
			$this->template( 'alert-bar', array( 'alert_bar' => $alert_bar ) );
		}
	}

	/**
	 * Register alert bars and JS
	 */
	public function register_scripts() {
		if ( empty( $this->get_bars() ) ) {
			return;
		}

		self::enqueue( 'jquery.cookie.js', 'dbi-cookie', array( 'jquery' ), null, true );
		self::enqueue( 'alert-bar.js', 'dbi-alertbar', array( 'dbi-cookie' ), null, true );
		self::enqueue( 'alert-bar.css', 'dbi-alertbar' );
	}

	/**
	 * Add class to body element if there are alert bars
	 *
	 * @param array $classes
	 *
	 * @return array
	 */
	public function add_body_class( $classes ) {
		if ( ! empty( $this->get_bars() ) ) {
			$classes[] = 'has-alert-bar';
		}

		return $classes;
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

		$suffix = '';
		if ( ! defined( 'SCRIPT_DEBUG' ) || ! SCRIPT_DEBUG ) {
			$suffix = '.min';
		}

		$base = '/assets/' . $ext . '/' . $file  . $suffix  . '.' . $ext;

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
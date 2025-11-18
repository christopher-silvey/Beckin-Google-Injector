<?php
/**
 * Frontend Google injection for Beckin Google Injector.
 *
 * @package Beckin_Google_Injector
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handles injecting the Google scripts on the frontend.
 */
class Beckin_Google_Injector_Frontend {

	/**
	 * Bootstraps hooks.
	 *
	 * @return void
	 */
	public static function init(): void {
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'maybe_enqueue_ga4' ) );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'maybe_enqueue_gtm' ) );
		add_action( 'wp_body_open', array( __CLASS__, 'output_gtm_noscript_iframe' ) );
		// Force async attribute for our Google scripts.
		add_filter( 'script_loader_tag', array( __CLASS__, 'add_async_attribute' ), 10, 2 );
	}

	/**
	 * Conditionally enqueue GA4 tracking based on plugin settings.
	 *
	 * @return void
	 */
	public static function maybe_enqueue_ga4(): void {
		if ( is_admin() ) {
			return;
		}

		// Avoid running in feeds or other non standard views.
		if ( is_feed() ) {
			return;
		}

		$options = self::get_options();

		$measurement_id = isset( $options['measurement_id'] ) ? (string) $options['measurement_id'] : '';
		$measurement_id = trim( $measurement_id );

		if ( '' === $measurement_id ) {
			// Nothing to inject without a Measurement ID.
			return;
		}

		$load_for_staff = ! empty( $options['load_for_staff'] );

		if ( ! $load_for_staff && is_user_logged_in() && current_user_can( 'edit_posts' ) ) {
			// Staff tracking is disabled and user can edit posts
			// (contributors, authors, editors, administrators).
			return;
		}

		$placement = isset( $options['placement'] ) ? (string) $options['placement'] : 'head';
		$in_footer = ( 'footer' === $placement );

		$handle = 'beckin-google-injector-gtag';

		// Load the GA4 library from Google Tag Manager.
		wp_enqueue_script(
			$handle,
			'https://www.googletagmanager.com/gtag/js?id=' . rawurlencode( $measurement_id ),
			array(),
			BECKIN_GOOGLE_INJECTOR_VERSION,
			$in_footer
		);

		// Mark script as async for better loading behavior.
		wp_script_add_data( $handle, 'async', true );

		// Inline config script to initialize gtag.
		$script  = "window.dataLayer = window.dataLayer || [];\n";
		$script .= "function gtag(){dataLayer.push(arguments);}\n";
		$script .= "gtag('js', new Date());\n";
		$script .= 'gtag(\'config\', ' . wp_json_encode( $measurement_id ) . ");\n";

		wp_add_inline_script( $handle, $script, 'after' );
	}

	/**
	 * Conditionally enqueue GTM tracking based on plugin settings.
	 *
	 * @return void
	 */
	public static function maybe_enqueue_gtm(): void {
		if ( is_admin() ) {
			return;
		}

		// Avoid running in feeds or other non standard views.
		if ( is_feed() ) {
			return;
		}

		$options = self::get_options();

		$container_id = isset( $options['container_id'] ) ? (string) $options['container_id'] : '';
		$container_id = trim( $container_id );

		if ( '' === $container_id ) {
			// Nothing to inject without a Container ID.
			return;
		}

		$load_for_staff = ! empty( $options['load_for_staff'] );

		if ( ! $load_for_staff && is_user_logged_in() && current_user_can( 'edit_posts' ) ) {
			// Staff tracking is disabled and user can edit posts
			// (contributors, authors, editors, administrators).
			return;
		}

		$placement = isset( $options['placement'] ) ? (string) $options['placement'] : 'head';
		$in_footer = ( 'footer' === $placement );

		$handle = 'beckin-google-injector-gtm';

		// Load the GTM library from Google Tag Manager.
		wp_enqueue_script(
			$handle,
			'https://www.googletagmanager.com/gtm.js?id=' . rawurlencode( $container_id ),
			array(),
			BECKIN_GOOGLE_INJECTOR_VERSION,
			$in_footer
		);

		// Mark script as async for better loading behavior.
		wp_script_add_data( $handle, 'async', true );

		// Bootstrap dataLayer + gtm.js event before the script runs.
		$inline  = "window.dataLayer = window.dataLayer || [];\n";
		$inline .= "window.dataLayer.push({'gtm.start': new Date().getTime(), event: 'gtm.js'});\n";

		wp_add_inline_script( $handle, $inline, 'before' );
	}

	/**
	 * Gets the current options array with safe defaults.
	 *
	 * @return array<string,mixed>
	 */
	private static function get_options(): array {
		$options = get_option( BECKIN_GOOGLE_INJECTOR_OPTION_KEY, array() );

		if ( ! is_array( $options ) ) {
			$options = array();
		}

		$defaults = array(
			'measurement_id' => '',
			'container_id'   => '',
			'load_for_staff' => false,
			'placement'      => 'head',
		);

		return array_merge( $defaults, $options );
	}

	/**
	 * Outputs the GTM noscript iframe right after <body>.
	 *
	 * @return void
	 */
	public static function output_gtm_noscript_iframe(): void {
		if ( is_admin() || is_feed() ) {
			return;
		}

		$options = self::get_options();

		$container_id = isset( $options['container_id'] ) ? (string) $options['container_id'] : '';
		$container_id = trim( $container_id );

		if ( '' === $container_id ) {
			return;
		}

		$load_for_staff = ! empty( $options['load_for_staff'] );

		if ( ! $load_for_staff && is_user_logged_in() && current_user_can( 'edit_posts' ) ) {
			return;
		}

		$src = 'https://www.googletagmanager.com/ns.html?id=' . rawurlencode( $container_id );

		echo "<!-- Google Tag Manager (noscript) -->\n";
		echo '<noscript><iframe src="' . esc_url( $src ) . '" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>' . "\n";
		echo '<!-- End Google Tag Manager (noscript) -->';
	}

	/**
	 * Adds async attribute to our Google script tags.
	 *
	 * @param string $tag    The <script> HTML.
	 * @param string $handle Script handle.
	 * @return string
	 */
	public static function add_async_attribute( string $tag, string $handle ): string {
		$handles = array(
			'beckin-google-injector-gtag',
			'beckin-google-injector-gtm',
		);

		if ( in_array( $handle, $handles, true ) ) {
			if ( false === strpos( $tag, ' async' ) ) {
				$tag = str_replace( '<script ', '<script async ', $tag );
			}

			if ( 'beckin-google-injector-gtag' === $handle ) {
				// Match the GA4 docs comment.
				$tag = "<!-- Google tag (gtag.js) -->\n" . $tag . "<!-- End Google tag (gtag.js) -->\n";
			} elseif ( 'beckin-google-injector-gtm' === $handle ) {
				// Match the GTM docs comments around the loader.
				$tag = "<!-- Google Tag Manager -->\n" . $tag . "<!-- End Google Tag Manager -->\n";
			}
		}

		return $tag;
	}
}

Beckin_Google_Injector_Frontend::init();

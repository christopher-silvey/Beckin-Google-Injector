<?php
/**
 * Plugin Name:       Beckin Google Injector
 * Description:       Adds Google Analytics 4 and Google Tag Manager with simple controls for admin exclusion and header or footer placement.
 * Text Domain:       beckin-google-injector
 * Version: 1.0.3
 * Requires at least: 6.8
 * Tested up to: 6.8
 * Requires PHP: 8.0
 * Author: Beckin - Christopher Silvey
 * Author URI: https://www.beckin.com
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Domain Path:       /languages
 *
 * @package Beckin_Google_Injector
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Core constants */
if ( ! defined( 'BECKIN_GOOGLE_INJECTOR_VERSION' ) ) {
	define( 'BECKIN_GOOGLE_INJECTOR_VERSION', '1.0.3' );
}

if ( ! defined( 'BECKIN_GOOGLE_INJECTOR_PLUGIN_FILE' ) ) {
	define( 'BECKIN_GOOGLE_INJECTOR_PLUGIN_FILE', __FILE__ );
}

if ( ! defined( 'BECKIN_GOOGLE_INJECTOR_PLUGIN_DIR' ) ) {
	define( 'BECKIN_GOOGLE_INJECTOR_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'BECKIN_GOOGLE_INJECTOR_PLUGIN_URL' ) ) {
	define( 'BECKIN_GOOGLE_INJECTOR_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

/** Plugin settings/capability */
if ( ! defined( 'BECKIN_GOOGLE_INJECTOR_OPTION_KEY' ) ) {
	define( 'BECKIN_GOOGLE_INJECTOR_OPTION_KEY', 'beckin_google_injector_options' );
}

if ( ! defined( 'BECKIN_GOOGLE_INJECTOR_CAPABILITY' ) ) {
	define( 'BECKIN_GOOGLE_INJECTOR_CAPABILITY', 'manage_options' );
}

/** Includes */
require_once BECKIN_GOOGLE_INJECTOR_PLUGIN_DIR . 'includes/class-beckin-google-injector-admin.php';
require_once BECKIN_GOOGLE_INJECTOR_PLUGIN_DIR . 'includes/class-beckin-google-injector-frontend.php';
require_once BECKIN_GOOGLE_INJECTOR_PLUGIN_DIR . 'includes/class-beckin-google-injector-updates.php';

/** Activation: add default options */
register_activation_hook(
	__FILE__,
	function () {
		$defaults = array(
			'measurement_id' => '',
			'container_id'   => '',
			// Default to not loading scripts for logged in admins to avoid skewing data.
			'load_for_staff' => false,
			// Best practice: GA4 and/or GTM in head for earlier firing, but allow footer as an option.
			'placement'      => 'head',
		);

		add_option( BECKIN_GOOGLE_INJECTOR_OPTION_KEY, $defaults );
	}
);

/** Deactivation: no action (keep settings). */
register_deactivation_hook(
	__FILE__,
	function () {
		// Intentionally left blank, settings are preserved.
	}
);

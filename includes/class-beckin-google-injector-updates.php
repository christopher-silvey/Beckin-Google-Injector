<?php
/**
 * Update handling for Beckin Google Injector.
 *
 * @package Beckin_Google_Injector
 */

declare(strict_types=1);

use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handles update integration for the plugin.
 */
class Beckin_Google_Injector_Updates {

	/**
	 * Bootstraps update checks.
	 *
	 * @return void
	 */
	public static function init(): void {
		self::setup_plugin_update_checker();
	}

	/**
	 * Configure Plugin Update Checker for Bitbucket.
	 *
	 * Expects the plugin update checker library to be located at:
	 * includes/vendor/plugin-update-checker/plugin-update-checker.php
	 *
	 * @return void
	 */
	private static function setup_plugin_update_checker(): void {
		$vendor_dir   = BECKIN_GOOGLE_INJECTOR_PLUGIN_DIR . 'includes/vendor/plugin-update-checker/';
		$library_file = $vendor_dir . 'plugin-update-checker.php';

		if ( ! file_exists( $library_file ) ) {
			// Library is missing, so skip setting up automatic updates.
			return;
		}

		require_once $library_file;

		// Make sure the v5 factory class from Plugin Update Checker is available.
		if ( ! class_exists( '\\YahnisElsts\\PluginUpdateChecker\\v5\\PucFactory' ) ) {
			return;
		}

		/**
		 * Repository URL for your plugin.
		 *
		 * Bitbucket example from the PUC readme:
		 * https://bitbucket.org/user-name/repo-name
		 */
		$repo_url = 'https://github.com/christopher-silvey/Beckin-Google-Injector';

		$update_checker = PucFactory::buildUpdateChecker(
			$repo_url,
			BECKIN_GOOGLE_INJECTOR_PLUGIN_FILE,
			'beckin-google-injector'
		);

		// Optional: set the branch that contains the stable release.
		// If you tag releases, you can remove this and rely on tags instead.
		$update_checker->setBranch( 'main' );

		/*
		Optional: If the Bitbucket repo is private, use OAuth consumer credentials.
		$update_checker->setAuthentication(
			array(
				'consumer_key'    => '...',
				'consumer_secret' => '...',
			)
		);
		*/
	}
}

Beckin_Google_Injector_Updates::init();

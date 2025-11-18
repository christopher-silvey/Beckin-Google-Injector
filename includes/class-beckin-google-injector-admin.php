<?php
/**
 * Admin settings screen for Beckin Google Injector.
 *
 * @package Beckin_Google_Injector
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handles the admin settings page and registration.
 */
class Beckin_Google_Injector_Admin {

	/**
	 * Option group slug for the Settings API.
	 *
	 * @var string
	 */
	private static string $option_group = 'beckin_google_injector';

	/**
	 * Settings page slug.
	 *
	 * @var string
	 */
	private static string $page_slug = 'beckin-google-injector';

	/**
	 * Bootstraps hooks.
	 *
	 * @return void
	 */
	public static function init(): void {
		add_action( 'admin_menu', array( __CLASS__, 'add_settings_page' ) );
		add_action( 'admin_init', array( __CLASS__, 'register_settings' ) );
	}

	/**
	 * Adds the options page under the Settings menu.
	 *
	 * @return void
	 */
	public static function add_settings_page(): void {
		add_options_page(
			esc_html__( 'Beckin Google Injector', 'beckin-google-injector' ),
			esc_html__( 'Google Injector', 'beckin-google-injector' ),
			BECKIN_GOOGLE_INJECTOR_CAPABILITY,
			self::$page_slug,
			array( __CLASS__, 'render_settings_page' )
		);
	}

	/**
	 * Registers settings, section, and fields.
	 *
	 * @return void
	 */
	public static function register_settings(): void {
		register_setting(
			self::$option_group,
			BECKIN_GOOGLE_INJECTOR_OPTION_KEY,
			array(
				'type'              => 'array',
				'sanitize_callback' => array( __CLASS__, 'sanitize_options' ),
				'default'           => array(
					'measurement_id' => '',
					'container_id'   => '',
					'load_for_staff' => false,
					'placement'      => 'head',
				),
			)
		);

		add_settings_section(
			'beckin_google_injector_main',
			esc_html__( 'GA4 Tracking Settings', 'beckin-google-injector' ),
			array( __CLASS__, 'render_main_section_intro' ),
			self::$page_slug
		);

		add_settings_field(
			'beckin_google_injector_measurement_id',
			esc_html__( 'GA4 Measurement ID', 'beckin-google-injector' ),
			array( __CLASS__, 'field_measurement_id' ),
			self::$page_slug,
			'beckin_google_injector_main'
		);

		add_settings_field(
			'beckin_google_injector_container_id',
			esc_html__( 'GTM Container ID', 'beckin-google-injector' ),
			array( __CLASS__, 'field_container_id' ),
			self::$page_slug,
			'beckin_google_injector_main'
		);

		add_settings_field(
			'beckin_google_injector_load_for_staff',
			esc_html__( 'Script Loading', 'beckin-google-injector' ),
			array( __CLASS__, 'field_load_for_staff' ),
			self::$page_slug,
			'beckin_google_injector_main'
		);

		add_settings_field(
			'beckin_google_injector_placement',
			esc_html__( 'Script Placement', 'beckin-google-injector' ),
			array( __CLASS__, 'field_placement' ),
			self::$page_slug,
			'beckin_google_injector_main'
		);
	}

	/**
	 * Settings section intro.
	 *
	 * @return void
	 */
	public static function render_main_section_intro(): void {
		echo '<p>';
		esc_html_e( 'Configure how Google Analytics 4 & Google Tag Manager are loaded on your site.', 'beckin-google-injector' );
		echo '</p>';
	}

	/**
	 * Renders the Measurement ID field.
	 *
	 * @return void
	 */
	public static function field_measurement_id(): void {
		$options        = self::get_options();
		$measurement_id = isset( $options['measurement_id'] ) ? (string) $options['measurement_id'] : '';
		?>

		<input
			type="text"
			id="beckin_google_injector_measurement_id"
			name="<?php echo esc_attr( BECKIN_GOOGLE_INJECTOR_OPTION_KEY ); ?>[measurement_id]"
			value="<?php echo esc_attr( $measurement_id ); ?>"
			class="regular-text"
			placeholder="<?php echo esc_attr( 'G-XXXXXXXXXX' ); ?>"
		/>
		<p class="description">
			<?php
			esc_html_e(
				'Paste your GA4 Measurement ID (for example: G-XXXXXXXXXX).',
				'beckin-google-injector'
			);
			?>
		</p>
		<?php
	}

	/**
	 * Renders the Container ID field.
	 *
	 * @return void
	 */
	public static function field_container_id(): void {
		$options      = self::get_options();
		$container_id = isset( $options['container_id'] ) ? (string) $options['container_id'] : '';
		?>

		<input
			type="text"
			id="beckin_google_injector_container_id"
			name="<?php echo esc_attr( BECKIN_GOOGLE_INJECTOR_OPTION_KEY ); ?>[container_id]"
			value="<?php echo esc_attr( $container_id ); ?>"
			class="regular-text"
			placeholder="<?php echo esc_attr( 'GTM-XXXXXXX' ); ?>"
		/>
		<p class="description">
			<?php
			esc_html_e(
				'Paste your Google Tag Manager Container ID (for example: GTM-XXXXXXX).',
				'beckin-google-injector'
			);
			?>
		</p>
		<?php
	}

	/**
	 * Renders the admin script loading dropdown field.
	 *
	 * @return void
	 */
	public static function field_load_for_staff(): void {
		$options = self::get_options();

		$load_for_staff = false;
		if ( isset( $options['load_for_staff'] ) ) {
			$load_for_staff = (bool) $options['load_for_staff'];
		}

		$current_value = $load_for_staff ? 'yes' : 'no';
		?>
		<select
			id="beckin_google_injector_load_for_staff"
			name="<?php echo esc_attr( BECKIN_GOOGLE_INJECTOR_OPTION_KEY ); ?>[load_for_staff]"
		>
			<option value="no" <?php selected( $current_value, 'no' ); ?>>
				<?php esc_html_e( 'Do not load for logged in staff (recommended)', 'beckin-google-injector' ); ?>
			</option>
			<option value="yes" <?php selected( $current_value, 'yes' ); ?>>
				<?php esc_html_e( 'Load for logged in staff', 'beckin-google-injector' ); ?>
			</option>
		</select>
		<p class="description">
			<?php
			esc_html_e(
				'Staff users who work on the site usually do not behave like normal visitors.',
				'beckin-google-injector'
			);
			?>
			<br>
			<?php
			esc_html_e(
				'Excluding them keeps your analytics & event reporting cleaner. e.g. admins, editors, authors, contributors',
				'beckin-google-injector'
			);
			?>
		</p>
		<?php
	}

	/**
	 * Renders the header/footer placement dropdown field.
	 *
	 * @return void
	 */
	public static function field_placement(): void {
		$options   = self::get_options();
		$placement = isset( $options['placement'] ) ? (string) $options['placement'] : 'head';

		if ( 'head' !== $placement && 'footer' !== $placement ) {
			$placement = 'head';
		}
		?>
		<select
			id="beckin_google_injector_placement"
			name="<?php echo esc_attr( BECKIN_GOOGLE_INJECTOR_OPTION_KEY ); ?>[placement]"
		>
			<option value="head" <?php selected( $placement, 'head' ); ?>>
				<?php esc_html_e( 'Header (recommended)', 'beckin-google-injector' ); ?>
			</option>
			<option value="footer" <?php selected( $placement, 'footer' ); ?>>
				<?php esc_html_e( 'Footer', 'beckin-google-injector' ); ?>
			</option>
		</select>
		<p class="description">
			<?php
			esc_html_e(
				'Header placement lets GA4 & GTM load earlier for more accurate tracking and firing. Footer is available if you prefer it.',
				'beckin-google-injector'
			);
			?>
		</p>
		<?php
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

		// array_merge is fine here, defaults first then override with saved values.
		return array_merge( $defaults, $options );
	}

	/**
	 * Sanitizes and validates options before saving.
	 *
	 * @param mixed $raw_input Raw input from the settings form.
	 * @return array<string,mixed>
	 */
	public static function sanitize_options( $raw_input ): array {
		$input = array();
		if ( is_array( $raw_input ) ) {
			$input = $raw_input;
		}

		$clean = array(
			'measurement_id' => '',
			'container_id'   => '',
			'load_for_staff' => false,
			'placement'      => 'head',
		);

		// Measurement ID.
		if ( isset( $input['measurement_id'] ) ) {
			$measurement_id = sanitize_text_field( (string) $input['measurement_id'] );
			$measurement_id = strtoupper( $measurement_id );

			if ( '' !== $measurement_id ) {
				// Simple GA4 Measurement ID check: must start with G- and contain A-Z and 0-9.
				if ( 1 === preg_match( '/^G-[A-Z0-9]+$/', $measurement_id ) ) {
					$clean['measurement_id'] = $measurement_id;
				} else {
					add_settings_error(
						BECKIN_GOOGLE_INJECTOR_OPTION_KEY,
						'beckin_google_injector_measurement_id_invalid',
						esc_html__( 'The GA4 Measurement ID looks invalid. It should look like G-XXXXXXXXXX.', 'beckin-google-injector' ),
						'error'
					);
				}
			}
		}

		// Container ID.
		if ( isset( $input['container_id'] ) ) {
			$container_id = sanitize_text_field( (string) $input['container_id'] );
			$container_id = strtoupper( $container_id );

			if ( '' !== $container_id ) {
				// Simple GTM Container ID check: must start with GTM- and contain A-Z and 0-9.
				if ( 1 === preg_match( '/^GTM-[A-Z0-9]+$/', $container_id ) ) {
					$clean['container_id'] = $container_id;
				} else {
					add_settings_error(
						BECKIN_GOOGLE_INJECTOR_OPTION_KEY,
						'beckin_google_injector_container_id_invalid',
						esc_html__( 'The GTM Container ID looks invalid. It should look like GTM-XXXXXXX.', 'beckin-google-injector' ),
						'error'
					);
				}
			}
		}

		// Admin loading.
		if ( isset( $input['load_for_staff'] ) ) {
			$value = (string) $input['load_for_staff'];

			if ( 'yes' === $value ) {
				$clean['load_for_staff'] = true;
			} else {
				$clean['load_for_staff'] = false;
			}
		}

		// Placement.
		if ( isset( $input['placement'] ) ) {
			$value = (string) $input['placement'];

			if ( 'footer' === $value ) {
				$clean['placement'] = 'footer';
			} else {
				$clean['placement'] = 'head';
			}
		}

		// Show a warning notice when both GA4 and GTM are configured.
		if ( '' !== $clean['measurement_id'] && '' !== $clean['container_id'] ) {
			add_settings_error(
				BECKIN_GOOGLE_INJECTOR_OPTION_KEY,
				'beckin_google_injector_double_ga4_warning',
				esc_html__( 'You entered both a GA4 Measurement ID and a GTM Container ID. Make sure GA4 is not also configured as a tag inside Google Tag Manager with the same Measurement ID, or your pageviews and events may be counted twice.', 'beckin-google-injector' ),
				'warning'
			);
		}

		return $clean;
	}

	/**
	 * Renders the full settings page.
	 *
	 * @return void
	 */
	public static function render_settings_page(): void {
		if ( ! current_user_can( BECKIN_GOOGLE_INJECTOR_CAPABILITY ) ) {
			return;
		}
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Beckin Google Injector', 'beckin-google-injector' ); ?></h1>

			<div style="background:#fff;border:1px solid #ccd0d4;border-radius:6px;padding:14px;margin-top:20px;">
				<h2 style="margin:0 0 6px;font-size:16px;"><?php esc_html_e( 'About Beckin', 'beckin-google-injector' ); ?></h2>
				<p class="coffee-description">
					<?php
					echo wp_kses_post(
						sprintf(
							/* translators: 1: Opening HTML <a> tag, 2: closing HTML </a> tag. */
							__( 'I develop plugins that help WordPress users save time and get more done. If this plugin helped you, please consider %1$sbuying me a coffee%2$s &#9749; to help support future updates and new features.', 'beckin-google-injector' ),
							'<a href="' . esc_url( 'https://buymeacoffee.com/beckin' ) . '" target="_blank" rel="noopener noreferrer">',
							'</a>'
						)
					);
					?>
				</p>
			</div>

			<hr style="margin:20px 0;">

			<form action="options.php" method="post">
				<?php
				settings_fields( self::$option_group );
				do_settings_sections( self::$page_slug );
				submit_button();
				?>
			</form>
		</div>
		<?php
	}
}

Beckin_Google_Injector_Admin::init();
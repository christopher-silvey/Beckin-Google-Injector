# Beckin Google Injector

Beckin Google Injector is a lightweight WordPress plugin that adds Google Analytics 4 and Google Tag Manager to your site with a simple settings screen. Enter your GA4 Measurement ID and/or your GTM Container ID, choose whether to load scripts for logged in administrators, and pick header or footer placement.

## Features

- Simple settings page under **Settings → Beckin Google Injector**
- GA4 Measurement ID field with validation (must look like `G-XXXXXXXXXX`)
- GTM Container ID field with validation (must look like `GTM-XXXXXXX`)
- Supports GA4 only, GTM only, or both (for other tags through GTM)
- Option to load or skip scripts for logged in administrators (recommended: skip)
- Script placement control, header or footer (header is recommended for accuracy)
- Uses `wp_enqueue_script` and `wp_add_inline_script` so it plays nicely with caching and optimization plugins
- Self hosted automatic updates powered by Bitbucket and Plugin Update Checker
- Safety notice when both GA4 and GTM are configured, reminding you not to also fire GA4 as a GTM tag with the same Measurement ID to avoid double counting

## Requirements

- WordPress 6.8 or higher
- PHP 8.0 or higher

## Installation

### Automatic installation (uploading the ZIP)

1. Log into your WordPress admin.
2. Click **Plugins → Add New**.
3. Click the **Upload Plugin** button at the top.
4. Click **Choose File** and select the `beckin-google-injector.zip` file you received.
5. Click **Install Now**.
6. When the installation completes, click **Activate Plugin**.

### Manual installation

1. Download the plugin.
2. Extract the contents of the zip file.
3. Upload the `beckin-google-injector` folder into your `wp-content/plugins` directory.
4. In the WordPress admin, go to **Plugins → Installed Plugins**.
5. Activate **Beckin Google Injector**.

## Configuration

1. Go to **Settings → Beckin Google Injector**.
2. (Optional) Paste your GA4 Measurement ID (for example `G-XXXXXXXXXX`) to inject GA4 directly.
3. (Optional) Paste your GTM Container ID (for example `GTM-XXXXXXX`) to inject Google Tag Manager.
4. Choose whether to load scripts for logged in administrators.
5. Choose script placement:
   - **Header** (recommended) so GA4 and GTM load earlier.
   - **Footer** if you prefer it.
6. Save changes.

Once saved, the plugin will start loading GA4 and/or GTM on the frontend according to your settings. If you configure both GA4 and GTM, make sure you do not also fire GA4 as a tag inside GTM with the same Measurement ID, or your pageviews and events may be double counted.

## Automatic Updates

This plugin uses [Plugin Update Checker](https://github.com/YahnisElsts/plugin-update-checker) and a Bitbucket repository for self hosted updates.

- Repository URL: `https://bitbucket.org/chrissilvey/beckin-google-injector/`

## Changelog

### 1.0.0

- Initial release.
- Adds GA4 Measurement ID setting with validation.
- Adds GTM Container ID setting with validation.
- Header or footer placement option for GA4 and GTM scripts (header recommended).
- Toggle to load or skip script loading for logged in administrators (do not load recommended).
- GA4 loading implemented via `wp_enqueue_script` and `wp_add_inline_script`.
- GTM loading implemented via `wp_enqueue_script`.
- Self hosted automatic updates wired to the Bitbucket repository using Plugin Update Checker.

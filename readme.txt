=== Beckin Google Injector ===
Contributors: beckin
Donate link: https://www.buymeacoffee.com/beckin
Tags: ga4, google analytics 4, google tag manager, analytics, tracking
Stable tag: 1.1.0
Requires at least: 6.8
Tested up to: 6.8
Requires PHP: 8.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Add Google Analytics 4 and Google Tag Manager with a simple settings page, staff exclusion, and header or footer placement.

== Description ==

Beckin Google Injector is a lightweight, focused plugin for adding Google Analytics 4 and Google Tag Manager to your WordPress site without editing theme files.

You can enter a GA4 Measurement ID and/or a GTM Container ID, control whether scripts load for logged in staff, and choose header or footer placement. Everything runs through the WordPress Settings API with proper sanitization and escaping so it plays nicely with caching and optimization plugins.

**Features**

1. **GA4 Measurement ID support** – Enter a GA4 Measurement ID and have the plugin inject the gtag.js loader plus the config snippet automatically.
2. **GTM Container ID support** – Add a Google Tag Manager container and let GTM handle additional tags and events.
3. **Flexible tracking modes** – Use GA4 only, GTM only, or both (for other tags in GTM) while keeping control of how GA4 is fired.
4. **Logged in staff exclusion** – Optionally skip loading GA4 and GTM for logged in staff so administrators, editors, authors, and contributors do not skew your analytics.
5. **Header or footer placement** – Choose whether scripts load in the header (recommended for accuracy) or footer.
6. **Cache friendly** – Uses `wp_enqueue_script` and `wp_add_inline_script` so it integrates cleanly with caching and optimization plugins.
7. **Self hosted automatic updates** – Uses Plugin Update Checker and a GitHub repository for self hosted updates.
8. **Secure and lightweight** – No front end UI, no bloat, and all options are sanitized and escaped following WordPress coding standards.

If this plugin saves you time or helps you ship cleaner analytics for your clients, please consider supporting development by [buying me a coffee](https://www.buymeacoffee.com/beckin).

== Installation ==

= Automatic installation (uploading the ZIP) =

1. Log into your WordPress admin.
2. Click **Plugins**.
3. Click **Add New**.
4. Click the **Upload Plugin** button at the top of the page.
5. Click **Choose File** and select the `beckin-google-injector.zip` file you received.
6. Click **Install Now**.
7. When the installation completes, click **Activate Plugin**.

= Manual installation =

1. Download the plugin zip file.
2. Extract the contents of the zip file.
3. Upload the `beckin-google-injector` folder to the `wp-content/plugins/` directory of your WordPress installation.
4. In the WordPress admin, go to **Plugins → Installed Plugins**.
5. Activate **Beckin Google Injector**.

== Frequently Asked Questions ==

= Do I have to use both GA4 and Google Tag Manager? =

No. You can use GA4 only, GTM only, or both. If you only want basic pageview tracking, you can just enter your GA4 Measurement ID. If you prefer to manage everything in GTM, you can leave GA4 empty and only use the GTM Container ID.

= What happens if I enter both a GA4 Measurement ID and a GTM Container ID? =

The plugin will inject both GA4 and GTM. This is useful if you want GA4 loaded directly while also using GTM for other tags. However, you should avoid also configuring GA4 as a tag inside GTM with the same Measurement ID, or your pageviews and events may be counted twice. The settings page shows a warning if both fields are filled to remind you.

= Who is considered "staff" for the logged in tracking setting? =

Staff users are any logged in users who can edit posts, such as administrators, editors, authors, and contributors. By default, the plugin does not load GA4 or GTM for these users so your analytics are not skewed by people working on the site.

= Can I still track logged in users if I want to? =

Yes. You can switch the setting to load scripts for logged in staff. This is not recommended for most business sites but is available if you need it for a specific use case.

= Where should I place the scripts, header or footer? =

Header is recommended so GA4 and GTM can fire as early as possible on each page load. Footer placement is available if you prefer it for performance reasons or to match an existing setup.

= Does this plugin work with caching plugins and CDNs? =

Yes. The plugin uses the standard WordPress enqueue system, which works well with most caching plugins and CDNs. GA4 and GTM are loaded from Google, and the inline config is attached to the enqueued script handle.

= Does this plugin add any front end UI to my site? =

No. Beckin Google Injector only injects tracking scripts. It does not add visible elements to your site’s front end.

== Changelog ==

= 1.1.0 =
* Switched Plugin Update Checker to use the new GitHub repository instead of Bitbucket.
* Updated the Automatic Updates documentation to reference the GitHub repo for self hosted updates.

= 1.0.6 =
* Removed the `.gitignore` file from the distributed plugin so hidden files are not shipped.
* Added a `languages` directory with a placeholder index file to match the plugin header `Domain Path` and satisfy Plugin Check.

= 1.0.5 =
* Removed `.gitignore` from the distributed plugin so hidden files are not shipped.
* Fixed the plugin header "Domain Path" warning by ensuring the `languages` directory exists and matches the header value.
* Reduced the readme tag list to 5 tags to satisfy the WordPress readme parser and Plugin Check.

= 1.0.4 =
* Ran phpcbf with the WordPress coding standard across all plugin files to normalize formatting, spacing, and docblocks.
* No functional changes in this release. This is a housekeeping update to keep the codebase aligned with WordPress coding standards.

= 1.0.3 =
* Aligned GA4 and GTM markup with Google’s official install snippets by adding the standard <!-- Google tag (gtag.js) --> comment and wrapping the GTM loader script with <!-- Google Tag Manager --> comments.
* Wrapped the GTM <noscript> iframe with matching <!-- Google Tag Manager (noscript) --> comments for clarity. No functional tracking changes, just cleaner, more familiar markup.

= 1.0.2 =
* Updated GTM implementation to bootstrap dataLayer and push the standard gtm.js event before the GTM script runs.
* Added a GTM noscript iframe via wp_body_open so a basic container still fires when JavaScript is disabled.
* Centralized async handling for GA4 and GTM scripts using the script_loader_tag filter so both tags consistently load with the async attribute.
* Kept GTM staff checks and options lookup aligned with the GA4 logic so behavior stays consistent and easier to maintain.

= 1.0.1 =
* Updated logged in tracking logic to treat "staff" as any user who can edit posts (administrators, editors, authors, contributors) and exclude them from tracking by default.
* Renamed the setting label and help text to talk about logged in staff instead of only administrators.
* Updated README wording to reflect the staff behavior and clarify recommended settings.

= 1.0.0 =
* Initial release.
* Adds GA4 Measurement ID setting with validation.
* Adds GTM Container ID setting with validation.
* Header or footer placement option for GA4 and GTM scripts (header recommended).
* Toggle to load or skip script loading for logged in administrators (do not load recommended).
* GA4 loading implemented via `wp_enqueue_script` and `wp_add_inline_script`.
* GTM loading implemented via `wp_enqueue_script`.
* Self hosted automatic updates wired to the Bitbucket repository using Plugin Update Checker.
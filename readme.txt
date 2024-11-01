=== Prevent Indexing on Non-Live Sites ===
Contributors: Steve North
Donate link: https://www.buymeacoffee.com/tex0gen
Tags: prevent, indexing, seo, development, staging
Requires at least: 4.6
Tested up to: 6.2
Requires PHP: 7.1
Stable tag: 1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Prevent your WordPress website from being indexed by search engines when it's not on the live site.

== Description ==

This WordPress plugin ensures that search engine indexing is automatically turned OFF if the site's URL doesn't match the provided live site URL. It's especially useful for development and staging environments, where you might not want content to be indexed by search engines.

**Features:**
- Automatically turns off search engine indexing for non-live sites.
- Provides an admin settings page to specify the live site URL.
- Displays an admin notice if indexing has been turned off due to URL mismatch.
- Encodes the live site URL to prevent inadvertent changes when using migration tools.
- Admin notice prompts for setting the live site URL if it hasn't been configured yet.

== Installation ==

1. Download the plugin and extract the files.
2. Upload the extracted folder to the `/wp-content/plugins/` directory.
3. Activate the plugin through the 'Plugins' menu in WordPress.
4. Navigate to `Settings` -> `Prevent Indexing` to configure your live site URL.

== Usage ==

1. After activating the plugin, go to the admin dashboard.
2. Navigate to `Settings` -> `Prevent Indexing`.
3. Enter your live site URL in the provided input field and save.
4. If the current site's URL doesn't match the provided live site URL, search engine indexing will be turned off, and an admin notice will inform you of the same.

== Frequently Asked Questions ==

= Will this plugin modify my content or themes? =

No, the plugin only alters the "Search Engine Visibility" setting in WordPress.

= What happens if I forget to set the live site URL? =

The plugin will display an admin notice prompting you to set the live site URL. Until set, no actions are taken on search engine visibility.

= Why is the live site URL encoded in the database? =

The encoding is to prevent migration tools from inadvertently changing the URL during a search and replace operation, which is common when moving WordPress sites.

== Changelog ==

= 1.0 =
* Initial release.

== License ==

[GPLv2 or later](https://www.gnu.org/licenses/gpl-2.0.html)

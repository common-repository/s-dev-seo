== S-DEV SEO ==

Contributors: seodevrobin
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Tags: SEO, Title, Meta Description
Stable tag: 1.88
Tested up to: 5.4
Requires at least: 5.2
Requires PHP: 7.0

== Description ==

SEO Tool which replace titles on selected pages and posts. Also adds ability for meta description.

== Important note ==

This plugin uses "Session Buffering" to work with some websites (for forcing replacement of titles). This means that if your host / web server uses a cache service there is a possibility that a conflict might occur. We give no support regarding host / web server support to avoid this.

== Frequently Asked Questions ==

None so far, if you have any questions please publish a post on the Support section of the plugin.

== Planned changes ==

In the future we plan to change the backend overview and settings page so it will use WP Table structure instead for better handling.

== Changelog ==

= 1.88 =
Implemented import support for All in One SEO plugin.

= 1.87 =
Changed default settings upon installation.

= 1.86 =
Bugfixes regarding meta description implementation.

= 1.85 =
Removed Home ("Page for posts") title field in settings. Page for posts instead checks and uses the custom SEO title and second, if no custom SEO title has been set, the standard page title.

= 1.84 =
Added support for dynamic year in title and descriptions using %year%

= 1.83 =
Added field for home / blog title (used for index.php) on settings page.
Bugfix category title replacement.

= 1.5 = 
Bugfix JS character counter.

= 1.4 =
Bugfix import function.

= 1.2 =
Removed check function for WP Title. Force Replace title is now the only replace function used.

= 1.1 =
Bugfixes.

= 1.0 = 
Initial release.
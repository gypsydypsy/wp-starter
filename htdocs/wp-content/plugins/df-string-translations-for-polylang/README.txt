=== Plugin Name ===
Contributors: sgastard
Donate link: https://www.havasdigitalfactory.com/
Tags: polylang, string translations
Requires at least: 5.2.0
Tested up to: 5.2.4
Stable tag: 5.2.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Parse themes and plugins to scan strings, and translate them with polylang string translations

== Description ==

Parse themes and plugins to scan strings, and translate them with polylang string translations
Look for esc_html_e(), esc_html__(), esc_attr_e(), esc_attr__(), __(), _e(), _x(), _ex(), pll__() , pll_e() and pll_translate_string() (used with custom API endpoints)

== Installation ==

This section describes how to install the plugin and get it working.

e.g.

1. Upload `df-string-translations-for-polylang.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Place `<?php do_action('plugin_name_hook'); ?>` in your templates

== Frequently Asked Questions ==

= A question that someone might have =

An answer to that question.

= What about foo bar? =

Answer to foo bar dilemma.

== Screenshots ==

1. This screen shot description corresponds to screenshot-1.(png|jpg|jpeg|gif). Note that the screenshot is taken from
the /assets directory or the directory that contains the stable readme.txt (tags or trunk). Screenshots in the /assets
directory take precedence. For example, `/assets/screenshot-1.png` would win over `/tags/4.3/screenshot-1.png`
(or jpg, jpeg, gif).
2. This is the second screen shot

== Changelog ==

= 1.1.1 =
* Fix get_active_plugins() : return troncated array if multisite

= 1.1.0 =
* Add $this->get_translated_string() in the parse function (used in df-contact for exemple)

= 1.0.4 =
* Bug fix: remove df_get_option and df_update_option, use only update/get_option from WP Core (and not update/get_site_option)

= 1.0.3 =
* Bug fix: scan whole files (without filtering php portion), scan support for pll_translate_string()

= 1.0.2 =
* Bug fix: filter gettext and gettext_with_context bring a mess in wp locale

= 1.0.1 =
* Bug fix: filter gettext and gettext_with_context only for non admin pages

= 1.0.0 =
* Initial version
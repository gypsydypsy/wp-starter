=== Digital Factory Contact ===
Contributors: sgastard
Donate link: https://www.havasdigitalfactory.com/
Tags: contact
Requires at least: 4.9.6
Tested up to: 5.3.3
Stable tag: 5.2.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Create table for contact form / provide API REST / RGPD compliant

== Description ==

Create table for contact form / provide API REST / RGPD compliant / Multisite ready

== Installation ==

1. Upload `df-contact.php` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= xxxxx =

xxxxx

== Changelog ==

= 2.4.2 =
* Fix permission callback argument for REST API
* Fix return statement

= 2.4.1 =
* Fix csv export for field with double quote

= 2.4.0 =
* Rewrite Role, add custom capabilities
* Refactor export function

= 2.3.1 =
* Bugfix export : check empty range date
* Allow DF_CONTACT_SUBJECTS to be defined in wp-config.php

= 2.3.0 =
* Security fix export : check user role
* Bugfix export : Add support for Polylang
* Add range date for export feature

= 2.2.1 =
* Bugfix translation string in REST API

= 2.2.0 =
* Add support for Polylang (and still WPML)

= 2.1.0 =
* Add single view for sumission contact in back-office
* Generate a zip for export (protected with a password)
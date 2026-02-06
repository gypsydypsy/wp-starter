<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.havasdigitalfactory.com/
 * @since             1.0.0
 * @package           Df_String_Translations_For_Polylang
 *
 * @wordpress-plugin
 * Plugin Name:       Digital Factory String Translations for Polylang
 * Plugin URI:        https://www.havasdigitalfactory.com/
 * Description:       Allow to translate strings from themes and/or plugins directly in the back-office, and store it in the database
 * Version:           1.1.1
 * Author:            Sébastien GASTARD
 * Author URI:        https://www.havasdigitalfactory.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       df-string-translations-for-polylang
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'DF_STRING_TRANSLATIONS_FOR_POLYLANG_VERSION', '1.1.1' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-df-string-translations-for-polylang-activator.php
 */
function activate_df_string_translations_for_polylang( $network_wide ) {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-df-string-translations-for-polylang-activator.php';
	Df_String_Translations_For_Polylang_Activator::activate( $network_wide );
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-df-string-translations-for-polylang-deactivator.php
 */
function deactivate_df_string_translations_for_polylang() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-df-string-translations-for-polylang-deactivator.php';
	Df_String_Translations_For_Polylang_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_df_string_translations_for_polylang' );
register_deactivation_hook( __FILE__, 'deactivate_df_string_translations_for_polylang' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-df-string-translations-for-polylang.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_df_string_translations_for_polylang() {
	
	$plugin = new Df_String_Translations_For_Polylang();
	$plugin->run();
	
}

run_df_string_translations_for_polylang();

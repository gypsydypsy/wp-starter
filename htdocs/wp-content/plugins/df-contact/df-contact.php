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
 * @package           Df_Contact
 *
 * @wordpress-plugin
 * Plugin Name:       Digital Factory Contact
 * Plugin URI:        https://www.havasdigitalfactory.com/
 * Description:       Contact form, RGPD compliant
 * Version:           2.4.2
 * Requires at least: 5.0
 * Author:            Sébastien GASTARD
 * Author URI:        https://www.havasdigitalfactory.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       df-contact
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
define( 'DF_CONTACT_VERSION', '2.4.2' );

/**
 * Salt key
 */
define( 'DF_CONTACT_CRYPT_KEY', 'q<dv^^)|h?<K2|whOgYArKU}dGB-?k(z6$e%VBi.nVUM@j:lxSQl?ep>ZD[}|E.,' );
define( 'DF_CONTACT_CRYPT_KEY2', '(`1S-(b]6a1AVW{`/@|5%+lo(V}jVfnc%$qE-1gp>,MK`Jez2rc+K-`P)JpSL/|p' );

/**
 * Another useful config
 */
define( 'DF_CONTACT_FRAGMENT_BY_SUBJECT', true );

if ( ! defined( 'DF_CONTACT_SUBJECTS' ) ):
	define( 'DF_CONTACT_SUBJECTS', array(
		'subject-1' => __( 'Subject 1', 'df-contact' ),
		'subject-2' => __( 'Subject 2', 'df-contact' ),
	) );
endif;

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-df-contact-activator.php
 */
function activate_df_contact( $network_wide ) {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-df-contact-activator.php';
	Df_Contact_Activator::activate( $network_wide );
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-df-contact-deactivator.php
 */
function deactivate_df_contact() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-df-contact-deactivator.php';
	Df_Contact_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_df_contact' );
register_deactivation_hook( __FILE__, 'deactivate_df_contact' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-df-contact.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_df_contact() {
	
	$plugin = new Df_Contact();
	$plugin->run();
	
}

run_df_contact();

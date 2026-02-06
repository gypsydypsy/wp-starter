<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://www.havasdigitalfactory.com/
 * @since             1.0.0
 * @package           Df_guidelines_checker
 *
 * @wordpress-plugin
 * Plugin Name:       Digital Factory Guidelines Checker
 * Plugin URI:        http://www.havasdigitalfactory.com/
 * Description:       A simple tool to check the Digital Factory Guidelines
 * Version:           1.7.1
 * Author:            Sébastien GASTARD
 * Author URI:        http://www.havasdigitalfactory.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       df_guidelines_checker
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-df_guidelines_checker-activator.php
 */
function activate_df_guidelines_checker() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-df_guidelines_checker-activator.php';
	Df_guidelines_checker_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-df_guidelines_checker-deactivator.php
 */
function deactivate_df_guidelines_checker() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-df_guidelines_checker-deactivator.php';
	Df_guidelines_checker_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_df_guidelines_checker' );
register_deactivation_hook( __FILE__, 'deactivate_df_guidelines_checker' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-df_guidelines_checker.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_df_guidelines_checker() {

	$plugin = new Df_guidelines_checker();
	$plugin->run();

}
run_df_guidelines_checker();

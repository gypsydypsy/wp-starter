<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.havasdigitalfactory.com/
 * @since      1.0.0
 *
 * @package    Df_Contact
 * @subpackage Df_Contact/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Df_Contact
 * @subpackage Df_Contact/includes
 * @author     Sébastien GASTARD <sebastien.gastard@havasdigitalfactory.com>
 */
class Df_Contact_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'df-contact',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}

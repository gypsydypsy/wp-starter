<?php

/**
 * Fired during plugin deactivation
 *
 * @link       http://www.havasdigitalfactory.com/
 * @since      1.0.0
 *
 * @package    Df_guidelines_checker
 * @subpackage Df_guidelines_checker/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Df_guidelines_checker
 * @subpackage Df_guidelines_checker/includes
 * @author     Sébastien GASTARD <sebastien.gastard@havasdigitalfactory.com>
 */
class Df_guidelines_checker_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		// clean database
		delete_option('hdf_audit_configuration');
		delete_option('hdf_audit_checklistDEV');
		delete_option('hdf_audit_checklistPROD');
	}

}

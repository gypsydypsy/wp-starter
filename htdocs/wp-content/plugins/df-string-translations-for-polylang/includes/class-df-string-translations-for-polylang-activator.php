<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.havasdigitalfactory.com/
 * @since      1.0.0
 *
 * @package    Df_String_Translations_For_Polylang
 * @subpackage Df_String_Translations_For_Polylang/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Df_String_Translations_For_Polylang
 * @subpackage Df_String_Translations_For_Polylang/includes
 * @author     Sébastien GASTARD <sebastien.gastard@havasdigitalfactory.com>
 */
class Df_String_Translations_For_Polylang_Activator {
	
	/**
	 * Check first if multisite network
	 *
	 * @param $network_wide
	 *
	 * @since    1.0.0
	 */
	public static function activate( $network_wide ) {
		global $wpdb;
		
		if ( is_multisite() && $network_wide ) {
			// Get all blogs in the network and activate plugin on each one
			$blog_ids = $wpdb->get_col( 'SELECT blog_id FROM $wpdb->blogs' );
			
			foreach ( $blog_ids as $blog_id ) {
				switch_to_blog( $blog_id );
				Df_String_Translations_For_Polylang_Activator::install( true );
				restore_current_blog();
			}
		} else {
			Df_String_Translations_For_Polylang_Activator::install();
		}
		
	}
	
	/**
	 * Store version in DDB while activating
	 *
	 * @param $is_multisite
	 *
	 * @since    1.0.0
	 */
	public static function install( $is_multisite = false ) {
		update_option( 'DF_STRING_TRANSLATIONS_FOR_POLYLANG_VERSION', DF_STRING_TRANSLATIONS_FOR_POLYLANG_VERSION );
	}
	
}

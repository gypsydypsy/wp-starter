<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.havasdigitalfactory.com/
 * @since      1.0.0
 *
 * @package    Df_Contact
 * @subpackage Df_Contact/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Df_Contact
 * @subpackage Df_Contact/includes
 * @author     Sébastien GASTARD <sebastien.gastard@havasdigitalfactory.com>
 */
class Df_Contact_Activator {
	
	/**
	 * Check first if multisite network
	 *
	 * @since    1.0.0
	 */
	public static function activate( $network_wide ) {
		global $wpdb;
		
		if ( is_multisite() && $network_wide ) {
			// Get all blogs in the network and activate plugin on each one
			$blog_ids = $wpdb->get_col( 'SELECT blog_id FROM ' . $wpdb->blogs );
			
			foreach ( $blog_ids as $blog_id ) {
				switch_to_blog( $blog_id );
				Df_Contact_Activator::install();
				restore_current_blog();
			}
		} else {
			Df_Contact_Activator::install();
		}
		
	}
	
	public static function install() {
		// Setup BDD
		global $wpdb;
		
		$table_name = $wpdb->prefix . 'df_contacts';
		
		$charset_collate = $wpdb->get_charset_collate();
		
		$sql = "CREATE TABLE $table_name (
				  id bigint(20) NOT NULL AUTO_INCREMENT,
				  id_key varchar(255) NOT NULL,
				  contact_theme varchar(255) NOT NULL,
				  private_key varchar(255) NOT NULL,
				  data text NOT NULL,
				  lang varchar(50) NOT NULL,
				  date_insert timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				  date_delete datetime NOT NULL DEFAULT '1970-01-01 00:00:01',
				  PRIMARY KEY  (id)
				) $charset_collate;";
		
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
		
		update_option( 'DF_CONTACT_VERSION', DF_CONTACT_VERSION );
		
		// Setup WP CRON
		if ( ! wp_next_scheduled( 'df_clean_old_contacts' ) ):
			// Every day clean the old contact entries
			wp_schedule_event( time(), 'daily', 'df_clean_old_contacts' );
		endif;
		
		// Add new role for export
		$custom_capabilities = array(
			'read'                   => true,
			//export contacts
			'manage_export_contacts' => true,
		);
		
		add_role( 'df_contact_data_manager', __( 'Contact Data Manager', 'df-contact' ), $custom_capabilities );
	}
	
}

<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.havasdigitalfactory.com/
 * @since      1.0.0
 *
 * @package    Df_Contact
 * @subpackage Df_Contact/admin/partials
 */
?>
<div class="wrap">
    <h2><?php esc_html_e( 'Forms Datas management', $this->plugin_name ); ?></h2>

    <!-- Export Contact -->
    <h3><?php esc_html_e( 'Export contact', $this->plugin_name ); ?></h3>
    <p><?php printf( __( '%d entrie(s) currently in database.', $this->plugin_name ), $contact_count ); ?></p>
	<?php
	if ( $contact_count > 0 ):
		?>
        <p><?php esc_html_e( 'Current password to open ZIP export : ', $this->plugin_name ); ?><?php echo( get_transient( 'hdf_crypt_key_contact' ) ); ?></p>
        <form action="<?php echo admin_url( 'admin.php?page=forms_datas_management_contact_overview' ); ?>" target="_blank" method="post">
            <input type="hidden" name="action" value="hdf_export_contacts">
			<?php wp_nonce_field( 'export_contact', '_wpnonce' ); ?>
			<?php
			if ( DF_CONTACT_FRAGMENT_BY_SUBJECT && count( DF_CONTACT_SUBJECTS ) > 0 ):
				?>
                <select name="subject" id="subject">
                    <option value="all"><?php _e( 'All subjects', $this->plugin_name ); ?></option>
					<?php
					foreach ( DF_CONTACT_SUBJECTS as $key => $val ):
						?>
                        <option value="<?php echo( $key ); ?>"><?php echo( $val ); ?></option>
					<?php
					endforeach;
					?>
                </select>
                <br/>
			<?php
			endif;
			
			// Multilanguage?
			$langs = array();
			
			// Polylang active ?
			if ( function_exists( 'pll_languages_list' ) ):
				$languages = pll_languages_list( array( 'fields' => 'slug' ) );
				
				if ( ! empty( $languages ) ):
					foreach ( $languages as $l ):
						$langs[] = array(
							'language_code' => $l,
							'native_name'   => $l,
						);
					endforeach;
				endif;
			// WPML active ?
            elseif ( is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' ) ):
				$languages = apply_filters( 'wpml_active_languages', null, 'orderby=id&order=desc' );
				
				if ( ! empty( $languages ) ):
					foreach ( $languages as $l ):
						$langs[] = $l;
					endforeach;
				endif;
			endif;
			
			if ( ! empty( $langs ) ):
				?>
                <select name="lang" id="lang">
                    <option value="all"><?php _e( 'All languages', $this->plugin_name ); ?></option>
					<?php
					foreach ( $langs as $l ):
						?>
                        <option value="<?php echo( esc_attr( mb_convert_case( $l['language_code'], MB_CASE_LOWER ) ) ); ?>"><?php echo( $l['native_name'] ); ?></option>
					<?php
					endforeach;
					?>
                </select>
                <br/>
			<?php
			endif;
			?>
            <input type="text" name="df_contact_start_date" id="df_contact_start_date" placeholder="<?php esc_attr_e( 'Start date', $this->plugin_name ); ?>"> <input type="text" name="df_contact_end_date" id="df_contact_end_date" placeholder="<?php esc_attr_e( 'End date', $this->plugin_name ); ?>"> <?php _e( 'Leave empty if filter not used', $this->plugin_name ); ?><br/><br/>
            <input type="hidden" name="blog_id" id="blog_id" value="<?php echo( get_current_blog_id() ); ?>"/>
            <input type="submit" name="download_csv" class="button-primary" value="<?php esc_attr_e( 'Export contacts', $this->plugin_name ); ?>"/>
        </form>
	<?php
	endif;
	?>
    <!-- Delete Contact -->
    <h3><?php esc_html_e( 'Delete contact', $this->plugin_name ); ?></h3>
    <p><?php esc_html_e( 'Please type email to delete all matching entries in the database.', $this->plugin_name ); ?></p>
    <form action="" method="post">
		<?php wp_nonce_field( 'delete_contact', '_wpnonce_delete' ); ?>
        <input type="email" size="60" name="email_to_delete" id="email_to_delete" value="">
        <input type="submit" class="button button-primary" value="<?php esc_attr_e( 'Delete contact', $this->plugin_name ); ?>"/>
    </form>

</div>

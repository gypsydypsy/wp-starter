<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.havasdigitalfactory.com/
 * @since      1.0.0
 *
 * @package    Df_Contact
 * @subpackage Df_Contact/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Df_Contact
 * @subpackage Df_Contact/admin
 * @author     Sébastien GASTARD <sebastien.gastard@havasdigitalfactory.com>
 */
class Df_Contact_Admin {
	
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;
	
	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;
	
	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version The version of this plugin.
	 *
	 * @since    1.0.0
	 *
	 */
	public function __construct( $plugin_name, $version ) {
		
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		
	}
	
	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Df_Contact_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Df_Contact_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/df-contact-admin.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'jquery-style', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css' );
		
	}
	
	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Df_Contact_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Df_Contact_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/df-contact-admin.js', array( 'jquery', 'jquery-ui-datepicker' ), $this->version, false );
		
	}
	
	/**
	 * Install contact when creating new blog
	 *
	 * @param $blog_id
	 * @param $user_id
	 * @param $domain
	 * @param $path
	 * @param $site_id
	 * @param $meta
	 *
	 * @since    1.0.0
	 */
	public function create_blog( $blog_id, $user_id, $domain, $path, $site_id, $meta ) {
		
		if ( is_plugin_active_for_network( 'hdf-contact/hdf-contact.php' ) ) :
			switch_to_blog( $blog_id );
			require_once plugin_dir_path( __DIR__ ) . 'includes/class-df-contact-activator.php';
			Df_Contact_Activator::install();
			restore_current_blog();
		endif;
		
	}
	
	/**
	 * Check initial settings, display warnings if needed
	 *
	 * @since    1.0.0
	 */
	public function check_initial_settings() {
		$messages                 = array();
		$errors_init_crypt_key    = $this->get_errors_init_crypt_key();
		$errors_init_crypt_key2   = $this->get_errors_init_crypt_key2();
		$errors_init_settings     = $this->get_errors_init_settings();
		$errors_init_data_manager = $this->get_errors_init_data_manager();
		
		if ( ! empty( $errors_init_crypt_key ) ):
			$messages[] = $errors_init_crypt_key;
		endif;
		
		if ( ! empty( $errors_init_crypt_key2 ) ):
			$messages[] = $errors_init_crypt_key2;
		endif;
		
		if ( ! empty( $errors_init_settings ) ):
			$messages[] = $errors_init_settings;
		endif;
		
		if ( ! empty( $errors_init_data_manager ) ):
			$messages[] = $errors_init_data_manager;
		endif;
		
		if ( count( $messages ) > 0 ):
			foreach ( $messages as $message ):
				printf( '<div class="notice notice-error"><p>%1$s</p></div>', $message );
			endforeach;
		endif;
	}
	
	/**
	 * Generate a new transient to store zip password if expired
	 *
	 * @since    2.1.0
	 */
	public function check_crypt_key_contact_exists() {
		if ( false === get_transient( 'hdf_crypt_key_contact' ) ):
			// regenerate crypt key for 1 month (30 days)
			set_transient( 'hdf_crypt_key_contact', wp_generate_password( 30, false, false ), MONTH_IN_SECONDS );
		endif;
	}
	
	/**
	 * Add page to manage contact (delete and export)
	 *
	 * @since    1.0.0
	 */
	public function menu_manage_contacts() {
		add_menu_page(
			__( 'Forms Datas management', $this->plugin_name ),
			__( 'Forms Datas management', $this->plugin_name ),
			'manage_export_contacts',
			'forms_datas_management_contact_overview',
			array(
				$this,
				'forms_datas_management_contact_overview_handler',
			),
			'dashicons-groups'
		);
		
		add_submenu_page(
			'forms_datas_management_contact_overview',
			__( 'Forms Datas single view', $this->plugin_name ),
			__( 'Forms Datas single view', $this->plugin_name ),
			'manage_export_contacts',
			'forms_datas_management_contact_single_view',
			array(
				$this,
				'forms_datas_management_contact_single_view_handler',
			) );
	}
	
	/**
	 * Form Data management markup
	 *
	 * @since    1.0.0
	 */
	public function forms_datas_management_contact_overview_handler() {
		if ( ! current_user_can( 'manage_export_contacts' ) ) :
			return;
		endif;
		
		global $wpdb;
		$contact_db_name = $wpdb->prefix . 'df_contacts';
		
		// delete action ?
		if ( ! empty( $_POST['email_to_delete'] ) && wp_verify_nonce( $_POST['_wpnonce_delete'], 'delete_contact' ) ):
			$email_to_delete = filter_var( trim( $_POST['email_to_delete'] ), FILTER_SANITIZE_EMAIL );
			$email_to_delete = mb_convert_case( $email_to_delete, MB_CASE_LOWER );
			$encrypt_key     = hash( 'sha512', $email_to_delete . DF_CONTACT_CRYPT_KEY2 );
			
			$results    = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ' . $contact_db_name . ' WHERE private_key = %s', $encrypt_key ) );
			$nb_results = count( $results );
			
			if ( $nb_results > 0 ):
				$delete_error = false;
				
				foreach ( $results as $row ):
					$update = $wpdb->update(
						$contact_db_name,
						array(
							'data'          => wp_privacy_anonymize_data( 'text', $row->data ),
							'private_key'   => wp_privacy_anonymize_data( 'text', $row->private_key ),
							'date_delete'   => current_time( 'mysql' ),
							'contact_theme' => wp_privacy_anonymize_data( 'text', $row->data ),
							'lang'          => wp_privacy_anonymize_data( 'text', $row->data ),
						),
						array( 'id' => $row->id ),
						array(
							'%s',
							'%s',
							'%s',
							'%s',
							'%s',
						),
						array( '%d' )
					);
					
					if ( false === $update ):
						// error
						$delete_error = true;
					endif;
				endforeach;
				
				if ( $delete_error ):
					printf( __( '<div class="notice notice-error"><p>An error occured while deleting %1$s from the database.</p></div>', $this->plugin_name ), $email_to_delete );
				else:
					// OK, send notification mail
					$to      = $email_to_delete;
					$subject = get_bloginfo( 'name' ) . ' - ' . __( 'Data deleted', $this->plugin_name );
					$message = __( 'Your data has been deleted.', $this->plugin_name );
					
					wp_mail( $to, $subject, $message );
					printf( __( '<div class="notice notice-success"><p>%1$s successfully deleted from the database.</p></div>', $this->plugin_name ), $email_to_delete );
				endif;
			else:
				// email not found
				printf( __( '<div class="notice notice-error"><p>%1$s does not exist in the database. Please check your spelling.</p></div>', $this->plugin_name ), $email_to_delete );
			endif;
		endif;
		
		// count registered data
		$contact_count = $wpdb->get_var( 'SELECT COUNT(id) FROM ' . $contact_db_name . ' WHERE date_delete = "1970-01-01 00:00:01"' );
		
		require_once plugin_dir_path( __FILE__ ) . 'partials/df-contact-admin-display.php';
	}
	
	/**
	 * Generate a view of a single contact submission
	 *
	 * @throws Exception
	 *
	 * @since    2.1.0
	 */
	public function forms_datas_management_contact_single_view_handler() {
		if ( ! current_user_can( 'manage_export_contacts' ) ) :
			return;
		endif;
		
		$id_key = '';
		
		if ( isset( $_GET['id_key'] ) ):
			$id_key = filter_var( trim( $_GET['id_key'] ), FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES );
		endif;
		
		if ( ! empty( $id_key ) ):
			global $wpdb;
			$sql    = 'SELECT data, date_insert FROM ' . $wpdb->prefix . 'df_contacts WHERE id_key = %s AND date_delete = "1970-01-01 00:00:01"';
			$result = $wpdb->get_row( $wpdb->prepare( $sql, $id_key ) );
			
			if ( null !== $result ) :
				if ( ! class_exists( 'Cryptor', false ) ):
					require_once plugin_dir_path( __DIR__ ) . 'includes/Cryptor.php';
				endif;
				$data = json_decode( Cryptor::Decrypt( $result->data, DF_CONTACT_CRYPT_KEY ), true );
				?>
                <div class="wrap">
                    <h1><?php _e( 'Forms Datas single view', $this->plugin_name ); ?></h1>
                    <p>
						<?php _e( 'Submitted on', $this->plugin_name ); ?> : <?php echo( $result->date_insert ); ?><br/>
						<?php _e( 'Subject', $this->plugin_name ); ?> : <?php echo( $data['subject'] ); ?><br/>
						<?php _e( 'Civility', $this->plugin_name ); ?> : <?php echo( $data['civility'] ); ?><br/>
						<?php _e( 'First Name', $this->plugin_name ); ?> : <?php echo( $data['firstname'] ); ?><br/>
						<?php _e( 'Last Name', $this->plugin_name ); ?> : <?php echo( $data['lastname'] ); ?><br/>
						<?php _e( 'Company', $this->plugin_name ); ?> : <?php echo( $data['company'] ); ?><br/>
						<?php _e( 'Job', $this->plugin_name ); ?> : <?php echo( $data['job'] ); ?><br/>
						<?php _e( 'Email', $this->plugin_name ); ?> : <?php echo( $data['email'] ); ?><br/>
						<?php _e( 'Message', $this->plugin_name ); ?> : <?php echo( $data['message'] ); ?>
                    </p>
                </div>
			<?php
			else:
				wp_die( __( 'Data not found/deleted', $this->plugin_name ) );
			endif;
		else:
			wp_die( __( 'Missing "id_key" parameter', $this->plugin_name ) );
		endif;
	}
	
	/**
	 * Register settings in options table
	 *
	 * @since    2.0.10
	 */
	public function register_option_contacts() {
		register_setting( 'df-contact-settings-group', 'df_contact_recaptcha_public_site_key' );
		register_setting( 'df-contact-settings-group', 'df_contact_recaptcha_secret_site_key' );
		register_setting( 'df-contact-settings-group', 'df_contact_from_name' );
		register_setting( 'df-contact-settings-group', 'df_contact_from_email' );
		register_setting( 'df-contact-settings-group', 'df_contact_from_body' );
		
		if ( DF_CONTACT_FRAGMENT_BY_SUBJECT && count( DF_CONTACT_SUBJECTS ) > 0 ):
			foreach ( DF_CONTACT_SUBJECTS as $key => $val ):
				// which email to send notifications based on subject
				register_setting( 'df-contact-settings-group', 'df_contact_' . $key . '_email_notification_to' );
			endforeach;
		else:
			// one mail to group all notifications
			register_setting( 'df-contact-settings-group', 'df_contact_email_notification_to' );
		endif;
		
		register_setting( 'df-contact-settings-group', 'df_contact_cleaner_delay' );
	}
	
	/**
	 * Add menu option
	 *
	 * @since    1.0.0
	 */
	public function menu_option_contacts() {
		add_options_page(
			__( 'Contact Settings', $this->plugin_name ),
			__( 'Contact Settings', $this->plugin_name ),
			'manage_options',
			'options_df_contact',
			array(
				$this,
				'settings_page',
			)
		);
	}
	
	/**
	 * Settings markup
	 *
	 * @since    1.0.0
	 */
	public function settings_page() {
		if ( ! current_user_can( 'manage_options' ) ):
			return;
		endif;
		
		?>
        <div class="wrap">
            <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<?php
			// first check if the constants have been changed
			$messages               = array();
			$errors_init_crypt_key  = $this->get_errors_init_crypt_key();
			$errors_init_crypt_key2 = $this->get_errors_init_crypt_key2();
			
			if ( ! empty( $errors_init_crypt_key ) ):
				$messages[] = $errors_init_crypt_key;
			endif;
			
			if ( ! empty( $errors_init_crypt_key2 ) ):
				$messages[] = $errors_init_crypt_key2;
			endif;
			
			if ( count( $messages ) > 0 ):
				?>
                <p><?php _e( 'Please setup the required constants in the df-contact.php file before configuring this plugin.', $this->plugin_name ); ?></p>
			<?php
			else:
				?>
                <form method="post" action="options.php">
					<?php settings_fields( 'df-contact-settings-group' ); ?>
					<?php do_settings_sections( 'df-contact-settings-group' ); ?>
                    <table class="form-table">
                        <tr valign="top">
                            <th scope="row"><?php _e( 'Google Recaptcha Public Site Key:', $this->plugin_name ); ?></th>
                            <td><input type="text" name="df_contact_recaptcha_public_site_key" value="<?php echo( esc_attr( get_option( 'df_contact_recaptcha_public_site_key' ) ) ); ?>" required/></td>
                        </tr>
                        <tr valign="top">
                            <th scope="row"><?php _e( 'Google Recaptcha Secret Site Key:', $this->plugin_name ); ?></th>
                            <td><input type="text" name="df_contact_recaptcha_secret_site_key" value="<?php echo( esc_attr( get_option( 'df_contact_recaptcha_secret_site_key' ) ) ); ?>" required/></td>
                        </tr>
                        <tr valign="top">
                            <th scope="row"><?php _e( 'From Name:', $this->plugin_name ); ?></th>
                            <td><input type="text" name="df_contact_from_name" value="<?php echo( esc_attr( get_option( 'df_contact_from_name' ) ) ); ?>" required/></td>
                        </tr>
                        <tr valign="top">
                            <th scope="row"><?php _e( 'From Email:', $this->plugin_name ); ?></th>
                            <td><input type="email" name="df_contact_from_email" value="<?php echo( esc_attr( get_option( 'df_contact_from_email' ) ) ); ?>" required/></td>
                        </tr>
                        <tr valign="top">
                            <th scope="row"><?php _e( 'From Body:', $this->plugin_name ); ?></th>
                            <td><textarea name="df_contact_from_body" required><?php echo( get_option( 'df_contact_from_body' ) ); ?></textarea></td>
                        </tr>
						<?php
						if ( DF_CONTACT_FRAGMENT_BY_SUBJECT && count( DF_CONTACT_SUBJECTS ) > 0 ):
							foreach ( DF_CONTACT_SUBJECTS as $key => $val ):
								// which email to send notifications based on subject
								register_setting( 'df-contact-settings-group', 'df_contact_' . $key . '_email_notification_to' );
								?>
                                <tr valign="top">
                                    <th scope="row"><?php printf( __( 'Notification Email for subject "%s":', $this->plugin_name ), $val ); ?></th>
                                    <td><input type="email" name="df_contact_<?php echo( $key ); ?>_email_notification_to" value="<?php echo( esc_attr( get_option( 'df_contact_' . $key . '_email_notification_to' ) ) ); ?>" required/></td>
                                </tr>
							<?php
							endforeach;
						else:
							?>
                            <tr valign="top">
                                <th scope="row"><?php _e( 'Notification Email:', $this->plugin_name ); ?></th>
                                <td><input type="email" name="df_contact_email_notification_to" value="<?php echo( esc_attr( get_option( 'df_contact_email_notification_to' ) ) ); ?>" required/></td>
                            </tr>
						<?php
						endif;
						?>
                        <tr valign="top">
                            <th scope="row"><?php _e( 'Clean old data beyond ', $this->plugin_name ); ?></th>
                            <td><input type="number" name="df_contact_cleaner_delay" value="<?php echo( esc_attr( get_option( 'df_contact_cleaner_delay' ) ) ); ?>" required/> <?php _e( 'month', $this->plugin_name ); ?></td>
                        </tr>
                    </table>
					
					<?php submit_button(); ?>
                </form>
			<?php
			endif;
			?>
        </div>
		<?php
	}
	
	/**
	 * Exports contact in a CSV file
	 *
	 * @since    2.4.0
	 */
	public function export_contacts() {
		if ( current_user_can( 'manage_export_contacts' ) ):
			if ( isset( $_POST['action'] ) && ( 'hdf_export_contacts' === $_POST['action'] ) && isset( $_POST['_wpnonce'] ) ):
				if ( wp_verify_nonce( $_POST['_wpnonce'], 'export_contact' ) && isset( $_POST['blog_id'] ) && is_numeric( $_POST['blog_id'] ) ):
					global $wpdb;
					// multisite
					if ( is_multisite() ):
						switch_to_blog( $_POST['blog_id'] );
					endif;
					
					// Filters ?
					$filter_subject    = false;
					$filter_lang       = false;
					$filter_start_date = false;
					$filter_end_date   = false;
					$subject           = 'all';
					$lang              = 'all';
					$start_date        = '';
					$end_date          = '';
					
					
					$sql = 'SELECT * FROM ' . $wpdb->prefix . 'df_contacts WHERE date_delete = "1970-01-01 00:00:01"';
					
					if ( isset( $_POST['subject'] ) ):
						$subject = trim( filter_var( $_POST['subject'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES ) );
					endif;
					
					if ( 'all' !== $subject ):
						$filter_subject = true;
						$sql            .= ' AND contact_theme = %s';
					endif;
					
					if ( isset( $_POST['lang'] ) ):
						$lang = trim( filter_var( $_POST['lang'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES ) );
					endif;
					
					if ( 'all' !== $lang ):
						$filter_lang = true;
						$sql         .= ' AND lang = %s';
					endif;
					
					if ( isset( $_POST['df_contact_start_date'] ) ):
						$start_date = trim( filter_var( $_POST['df_contact_start_date'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES ) );
						
						if ( preg_match( "/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $start_date ) ):
							list( $year, $month, $day ) = explode( '-', $start_date );
							
							if ( checkdate( $month, $day, $year ) ):
								$start_date .= ' 00:00:00';
							else:
								$start_date = '';
							endif;
						else:
							$start_date = '';
						endif;
					endif;
					
					if ( isset( $_POST['df_contact_end_date'] ) ):
						$end_date = trim( filter_var( $_POST['df_contact_end_date'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES ) );
						
						if ( preg_match( "/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $end_date ) ):
							list( $year, $month, $day ) = explode( '-', $end_date );
							
							if ( checkdate( $month, $day, $year ) ):
								$end_date .= ' 23:59:59';
							else:
								$end_date = '';
							endif;
						else:
							$end_date = '';
						endif;
					endif;
					
					if ( ! empty( $start_date ) ):
						$filter_start_date = true;
						$sql               .= ' AND date_insert >= %s';
					endif;
					
					if ( ! empty( $end_date ) ):
						$filter_end_date = true;
						$sql             .= ' AND date_insert <= %s';
					endif;
					
					if ( $filter_subject && $filter_lang && $filter_start_date && $filter_end_date ):
						$filename = 'export-contacts_' . $subject . '_' . $lang . '_' . current_time( 'YmdHis' ) . '.csv';
						$result   = $wpdb->get_results( $wpdb->prepare( $sql, $subject, $lang, $start_date, $end_date ), ARRAY_A );
                    elseif ( $filter_subject && $filter_lang && $filter_start_date ):
						$filename = 'export-contacts_' . $subject . '_' . $lang . '_' . current_time( 'YmdHis' ) . '.csv';
						$result   = $wpdb->get_results( $wpdb->prepare( $sql, $subject, $lang, $start_date ), ARRAY_A );
                    elseif ( $filter_subject && $filter_lang && $filter_end_date ):
						$filename = 'export-contacts_' . $subject . '_' . $lang . '_' . current_time( 'YmdHis' ) . '.csv';
						$result   = $wpdb->get_results( $wpdb->prepare( $sql, $subject, $lang, $end_date ), ARRAY_A );
                    elseif ( $filter_subject && $filter_lang ):
						$filename = 'export-contacts_' . $subject . '_' . $lang . '_' . current_time( 'YmdHis' ) . '.csv';
						$result   = $wpdb->get_results( $wpdb->prepare( $sql, $subject, $lang ), ARRAY_A );
                    elseif ( $filter_lang && $filter_start_date && $filter_end_date ):
						$filename = 'export-contacts_' . $lang . '_' . current_time( 'YmdHis' ) . '.csv';
						$result   = $wpdb->get_results( $wpdb->prepare( $sql, $lang, $start_date, $end_date ), ARRAY_A );
                    elseif ( $filter_lang && $filter_start_date ):
						$filename = 'export-contacts_' . $lang . '_' . current_time( 'YmdHis' ) . '.csv';
						$result   = $wpdb->get_results( $wpdb->prepare( $sql, $lang, $start_date ), ARRAY_A );
                    elseif ( $filter_lang && $filter_end_date ):
						$filename = 'export-contacts_' . $lang . '_' . current_time( 'YmdHis' ) . '.csv';
						$result   = $wpdb->get_results( $wpdb->prepare( $sql, $lang, $end_date ), ARRAY_A );
                    elseif ( $filter_lang ):
						$filename = 'export-contacts_' . $lang . '_' . current_time( 'YmdHis' ) . '.csv';
						$result   = $wpdb->get_results( $wpdb->prepare( $sql, $lang ), ARRAY_A );
                    elseif ( $filter_subject && $filter_start_date && $filter_end_date ):
						$filename = 'export-contacts_' . $subject . '_' . current_time( 'YmdHis' ) . '.csv';
						$result   = $wpdb->get_results( $wpdb->prepare( $sql, $subject, $start_date, $end_date ), ARRAY_A );
                    elseif ( $filter_subject && $filter_start_date ):
						$filename = 'export-contacts_' . $subject . '_' . current_time( 'YmdHis' ) . '.csv';
						$result   = $wpdb->get_results( $wpdb->prepare( $sql, $subject, $start_date ), ARRAY_A );
                    elseif ( $filter_subject && $filter_end_date ):
						$filename = 'export-contacts_' . $subject . '_' . current_time( 'YmdHis' ) . '.csv';
						$result   = $wpdb->get_results( $wpdb->prepare( $sql, $subject, $end_date ), ARRAY_A );
                    elseif ( $filter_subject ):
						$filename = 'export-contacts_' . $subject . '_' . current_time( 'YmdHis' ) . '.csv';
						$result   = $wpdb->get_results( $wpdb->prepare( $sql, $subject ), ARRAY_A );
                    elseif ( $filter_start_date && $filter_end_date ):
						$filename = 'export-contacts_' . current_time( 'YmdHis' ) . '.csv';
						$result   = $wpdb->get_results( $wpdb->prepare( $sql, $start_date, $end_date ), ARRAY_A );
                    elseif ( $filter_start_date ):
						$filename = 'export-contacts_' . current_time( 'YmdHis' ) . '.csv';
						$result   = $wpdb->get_results( $wpdb->prepare( $sql, $start_date ), ARRAY_A );
                    elseif ( $filter_end_date ):
						$filename = 'export-contacts_' . current_time( 'YmdHis' ) . '.csv';
						$result   = $wpdb->get_results( $wpdb->prepare( $sql, $end_date ), ARRAY_A );
					else:
						$filename = 'export-contacts_' . current_time( 'YmdHis' ) . '.csv';
						$result   = $wpdb->get_results( $sql, ARRAY_A );
					endif;
					
					if ( is_multisite() ):
						restore_current_blog();
					endif;
					
					if ( isset( $result ) && ( count( $result ) > 0 ) ) :
						$error_zip_generation = false;
						wp_mkdir_p( WP_CONTENT_DIR . '/uploads/zip_contact/' );
						$file_path = WP_CONTENT_DIR . '/uploads/zip_contact/' . $filename;
						
						$df = fopen( $file_path, 'wb' );
						//add BOM to fix UTF-8 in Excel
						fputs( $df, $bom = ( chr( 0xEF ) . chr( 0xBB ) . chr( 0xBF ) ) );
						
						if ( $df ) {
							// Decrypt first row for column title
							if ( ! class_exists( 'Cryptor', false ) ):
								require_once plugin_dir_path( __DIR__ ) . 'includes/Cryptor.php';
							endif;
							$data = json_decode( Cryptor::Decrypt( $result[0]['data'], DF_CONTACT_CRYPT_KEY ), true );
							// Add date
							$data['date_insert'] = $result[0]['date_insert'];
							
							fputcsv( $df, array_keys( $data ), ';' );
							
							if ( "\n" != PHP_EOL && 0 === fseek( $df, - 1, SEEK_CUR ) ):
								fwrite( $df, PHP_EOL );
							endif;
							
							foreach ( $result as $row ) {
								// Decrypt
								$data = json_decode( Cryptor::Decrypt( $row['data'], DF_CONTACT_CRYPT_KEY ), true );
								// Add date
								$data['date_insert'] = $row['date_insert'];
								
								fputcsv( $df, array_map( 'stripslashes', $data ), ';' );
								
								if ( "\n" != PHP_EOL && 0 === fseek( $df, - 1, SEEK_CUR ) ):
									fwrite( $df, PHP_EOL );
								endif;
							}
						}
						
						fclose( $df );
						
						if ( file_exists( $file_path ) && filesize( $file_path ) > 0 ) :
							$zip_path     = str_replace( '.csv', '.zip', $file_path );
							$zip_filename = str_replace( '.csv', '.zip', $filename );
							
							if ( version_compare( phpversion(), '7.2', '>=' ) ) :
								$zip = new ZipArchive;
								$zip->open( $zip_path, ZipArchive::CREATE );
								$zip->addFile( $file_path, $filename );
								$zip->setEncryptionName( $filename, ZipArchive::EM_AES_256, get_transient( 'hdf_crypt_key_contact' ) );
								$zip->setPassword( get_transient( 'hdf_crypt_key_contact' ) );
								$zip->close();
							else:
								exec( 'zip -e -P ' . get_transient( 'hdf_crypt_key_contact' ) . ' -j ' . $zip_path . ' ' . $file_path );
							endif;
						else:
							$error_zip_generation = true;
						endif;
						
						if ( ! $error_zip_generation ):
							header( "Pragma: public" );
							header( "Expires: 0" );
							header( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
							header( "Content-Type: application/zip" );
							header( "Content-Disposition: attachment;filename={$zip_filename}" );
							header( "Content-Transfer-Encoding: binary" );
							readfile( $zip_path );
							unlink( $file_path );
							unlink( $zip_path );
							die();
						else:
							wp_die( __( 'Error while generating ZIP file', $this->plugin_name ) );
						endif;
					else:
						wp_die( __( 'No data to export', $this->plugin_name ) );
					endif;
				endif;
			endif;
		endif;
	}
	
	/**
	 * Utility function to check DF_CONTACT_CRYPT_KEY
	 *
	 * @return mixed|string
	 * @since    2.0.10
	 */
	private function get_errors_init_crypt_key() {
		$message = '';
		
		if ( ! defined( 'DF_CONTACT_CRYPT_KEY' ) ):
			$message = __( 'The constant "DF_CONTACT_CRYPT_KEY" is not defined.', $this->plugin_name );
        elseif ( 'i.nVUM@j:lxSQl?ep>ZD[}|E.,q<dv^^)|h?<K2|whOgYArKU}dGB-?k(z6$e%VB' === DF_CONTACT_CRYPT_KEY ):
			$message = __( 'The constant "DF_CONTACT_CRYPT_KEY" has not been changed.', $this->plugin_name );
		endif;
		
		return $message;
	}
	
	/**
	 * Utility function to check DF_CONTACT_CRYPT_KEY2
	 *
	 * @return mixed|string
	 * @since    2.0.10
	 */
	private function get_errors_init_crypt_key2() {
		$message = '';
		
		if ( ! defined( 'DF_CONTACT_CRYPT_KEY2' ) ):
			$message = __( 'The constant "DF_CONTACT_CRYPT_KEY2" is not defined.', $this->plugin_name );
        elseif ( '$qE-1gp>,MK`Jez2rc+K-`P)JpSL/|p(`1S-(b]6a1AVW{`/@|5%+lo(V}jVfnc%' === DF_CONTACT_CRYPT_KEY2 ):
			$message = __( 'The constant "DF_CONTACT_CRYPT_KEY2" has not been changed.', $this->plugin_name );
		endif;
		
		return $message;
	}
	
	/**
	 * Utility function to check if a data manager exists for export
	 *
	 * @return mixed|string
	 * @since    2.0.10
	 */
	private function get_errors_init_data_manager() {
		$message = '';
		
		$args = array(
			'role' => 'df_contact_data_manager',
		);
		
		$users_data_manager = get_users( $args );
		
		if ( count( $users_data_manager ) < 1 ):
			$message = __( 'Don\'t forget to assign the "Contact Data Manager" role for a user if you want to export the data of the contact form.', $this->plugin_name );
		endif;
		
		return $message;
	}
	
	/**
	 * Utility function to check initial settings
	 *
	 * @return mixed|string
	 * @since    2.0.10
	 */
	private function get_errors_init_settings() {
		$message = '';
		$error   = false;
		
		if ( ! get_option( 'df_contact_recaptcha_public_site_key' ) ):
			$error = true;
		endif;
		
		if ( ! get_option( 'df_contact_recaptcha_secret_site_key' ) ):
			$error = true;
		endif;
		
		if ( ! get_option( 'df_contact_from_name' ) ):
			$error = true;
		endif;
		
		if ( ! get_option( 'df_contact_from_email' ) ):
			$error = true;
		endif;
		
		if ( ! get_option( 'df_contact_from_body' ) ):
			$error = true;
		endif;
		
		if ( DF_CONTACT_FRAGMENT_BY_SUBJECT && count( DF_CONTACT_SUBJECTS ) > 0 ):
			foreach ( DF_CONTACT_SUBJECTS as $key => $val ):
				if ( ! get_option( 'df_contact_' . $key . '_email_notification_to' ) ):
					$error = true;
				endif;
			endforeach;
		else:
			if ( ! get_option( 'df_contact_email_notification_to' ) ):
				$error = true;
			endif;
		endif;
		
		if ( ! get_option( 'df_contact_cleaner_delay' ) ):
			$error = true;
		endif;
		
		if ( $error ):
			$message = sprintf( __( 'Some settings for contact plugin are missing, please <a href="%s">check your setup</a>.', $this->plugin_name ), admin_url( '/options-general.php?page=options_df_contact' ) );
		endif;
		
		return $message;
	}
	
}

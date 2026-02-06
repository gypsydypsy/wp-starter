<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.havasdigitalfactory.com/
 * @since      1.0.0
 *
 * @package    Df_Contact
 * @subpackage Df_Contact/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Df_Contact
 * @subpackage Df_Contact/public
 * @author     Sébastien GASTARD <sebastien.gastard@havasdigitalfactory.com>
 */
class Df_Contact_Public {
	
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
	 * @param string $plugin_name The name of the plugin.
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
	 * Register the stylesheets for the public-facing side of the site.
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
		
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/df-contact-public.css', array(), $this->version, 'all' );
		
	}
	
	/**
	 * Register the JavaScript for the public-facing side of the site.
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
		
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/df-contact-public.js', array( 'jquery' ), $this->version, false );
		
	}
	
	/**
	 * Auto update if needed (db check)
	 *
	 * @since    1.0.0
	 */
	public function update_db_check() {
		if ( get_option( 'DF_CONTACT_VERSION' ) != DF_CONTACT_VERSION ) {
			require_once plugin_dir_path( __DIR__ ) . 'includes/class-df-contact-activator.php';
			Df_Contact_Activator::install();
		}
	}
	
	/**
	 * Add ajax endpoint(s) for API REST
	 *
	 * @since    1.0.0
	 * @modified 2.4.2 add permission callback
	 */
	public function add_ajax_endpoints() {
		$namespace = $this->plugin_name;
		$version   = 1;
		
		register_rest_route( $namespace . '/v' . $version, '/save/', array(
			'methods'             => WP_REST_Server::CREATABLE,
			'callback'            => array( $this, 'save_contact' ),
			'permission_callback' => '__return_true',
		) );
	}
	
	/**
	 * Save encrypted data in database
	 *
	 * @param $request_data
	 *
	 * @return array
	 *
	 * @throws Exception
	 * @since    1.0.0
	 * @modified 2.4.2 fix return statement
	 */
	public function save_contact( $request_data ) {
		$lang = get_locale();
		
		if ( function_exists( 'pll_default_language' ) ):
			$default = pll_default_language();
			$langs   = pll_languages_list();
			
			if ( isset( $_GET['lang'] ) ):
				$lang = $_GET['lang'];
			endif;
			
			if ( ! in_array( $lang, $langs ) ) :
				$lang = $default;
			endif;
		elseif ( defined( 'ICL_LANGUAGE_CODE' ) ):
			$lang = ICL_LANGUAGE_CODE;
		endif;
		
		$response = array(
			'code'    => 'failed_form_validation',
			'message' => $this->get_translated_string( 'Missing field(s)', $lang ),
			'data'    => array( 'status' => 400 ),
		);
		
		// get variables
		// required
		$contact_subject         = '';
		$contact_civility        = '';
		$contact_first_name      = '';
		$contact_last_name       = '';
		$contact_email           = '';
		$contact_confirmed_email = '';
		$contact_message         = '';
		$contact_optin_rgpd      = 0;
		$g_recaptcha_response    = '';
		// optional
		$contact_company = '';
		$contact_job     = '';
		
		if ( isset( $_POST['contact_subject'] ) ) :
			$contact_subject = filter_var( trim( $_POST['contact_subject'] ), FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES );
			$contact_subject = mb_convert_case( $contact_subject, MB_CASE_LOWER );
		endif;
		
		if ( isset( $_POST['contact_civility'] ) ) :
			$contact_civility = filter_var( trim( $_POST['contact_civility'] ), FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES );
			$contact_civility = mb_convert_case( $contact_civility, MB_CASE_TITLE );
		endif;
		
		if ( isset( $_POST['contact_first_name'] ) ) :
			$contact_first_name = filter_var( trim( $_POST['contact_first_name'] ), FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES );
			$contact_first_name = mb_convert_case( $contact_first_name, MB_CASE_TITLE );
		endif;
		
		if ( isset( $_POST['contact_last_name'] ) ) :
			$contact_last_name = filter_var( trim( $_POST['contact_last_name'] ), FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES );
			$contact_last_name = mb_convert_case( $contact_last_name, MB_CASE_UPPER );
		endif;
		
		if ( isset( $_POST['contact_company'] ) ) :
			$contact_company = filter_var( trim( $_POST['contact_company'] ), FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES );
			$contact_company = mb_convert_case( $contact_company, MB_CASE_UPPER );
		endif;
		
		if ( isset( $_POST['contact_job'] ) ) :
			$contact_job = filter_var( trim( $_POST['contact_job'] ), FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES );
			$contact_job = mb_convert_case( $contact_job, MB_CASE_TITLE );
		endif;
		
		if ( isset( $_POST['contact_email'] ) ) :
			$contact_email = filter_var( trim( $_POST['contact_email'] ), FILTER_SANITIZE_EMAIL );
			$contact_email = mb_convert_case( $contact_email, MB_CASE_LOWER );
		endif;
		
		if ( isset( $_POST['contact_confirmed_email'] ) ) :
			$contact_confirmed_email = filter_var( trim( $_POST['contact_confirmed_email'] ), FILTER_SANITIZE_EMAIL );
			$contact_confirmed_email = mb_convert_case( $contact_confirmed_email, MB_CASE_LOWER );
		endif;
		
		if ( isset( $_POST['contact_message'] ) ) :
			$contact_message = filter_var( trim( $_POST['contact_message'] ), FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES );
		endif;
		
		if ( isset( $_POST['g-recaptcha-response'] ) ) :
			$g_recaptcha_response = $_POST['g-recaptcha-response'];
		endif;
		
		if ( isset( $_POST['contact_optin_rgpd'] ) ) :
			$contact_optin_rgpd = (int) filter_var( trim( $_POST['contact_optin_rgpd'] ), FILTER_SANITIZE_NUMBER_INT );
		endif;
		
		// check required fields
		$errors = array();
		/*$recaptcha_response = $this->verify_recaptcha( $g_recaptcha_response );
		
		if ( false === $recaptcha_response ) :
			$errors['g-recaptcha-response'] = $this->get_translated_string( 'Please verify the captcha.', $lang );
		endif;*/
		
		if ( empty( $contact_subject ) ) :
			$errors['contact_subject'] = $this->get_translated_string( 'Please select a subject.', $lang );
		elseif ( ! array_key_exists( $contact_subject, DF_CONTACT_SUBJECTS ) ):
			$errors['contact_subject'] = $this->get_translated_string( 'Invalid subject.', $lang );
		endif;
		
		if ( empty( $contact_civility ) ) {
			$errors['contact_civility'] = $this->get_translated_string( 'Your civility is required.', $lang );
		}
		
		if ( empty( $contact_first_name ) ) {
			$errors['contact_first_name'] = $this->get_translated_string( 'Your first name is required.', $lang );
		}
		
		if ( empty( $contact_last_name ) ) {
			$errors['contact_last_name'] = $this->get_translated_string( 'Your last name is required.', $lang );
		}
		
		if ( empty( $contact_email ) ) {
			$errors['contact_email'] = $this->get_translated_string( 'Your email address is required.', $lang );
		} elseif ( ! filter_var( $contact_email, FILTER_VALIDATE_EMAIL ) ) {
			$errors['contact_email'] = $this->get_translated_string( 'Your email address is not correct.', $lang );
		}
		
		if ( empty( $contact_confirmed_email ) ) {
			$errors['contact_confirmed_email'] = $this->get_translated_string( 'Your email address confirmation is required.', $lang );
		} elseif ( ! filter_var( $contact_confirmed_email, FILTER_VALIDATE_EMAIL ) || ( $contact_confirmed_email !== $contact_email ) ) {
			$errors['contact_confirmed_email'] = $this->get_translated_string( 'Your email address confirmation is not correct.', $lang );
		}
		
		if ( empty( $contact_message ) ) {
			$errors['contact_message'] = $this->get_translated_string( 'Your message is required.', $lang );
		}
		
		if ( 1 !== $contact_optin_rgpd ) {
			$errors['contact_optin_rgpd'] = $this->get_translated_string( 'Please accept RGPD.', $lang );
		}
		
		if ( empty( $errors ) ):
			// Send mail
			add_filter( 'wp_mail_content_type', array( $this, 'wpdocs_set_html_mail_content_type' ) );
			
			// get email notification to
			$email_to = '';
			
			if ( DF_CONTACT_FRAGMENT_BY_SUBJECT && count( DF_CONTACT_SUBJECTS ) > 0 ):
				foreach ( DF_CONTACT_SUBJECTS as $key => $val ):
					if ( $key === $contact_subject ):
						$email_to = get_option( 'df_contact_' . $key . '_email_notification_to' );
					endif;
				endforeach;
			else:
				$email_to = get_option( 'df_contact_email_notification_to' );
			endif;
			
			$subject   = get_bloginfo( 'name' ) . ' - Contact form - ' . $contact_subject;
			$body      = get_option( 'df_contact_from_body' );
			$headers[] = 'From: ' . get_option( 'df_contact_from_name' ) . ' <' . get_option( 'df_contact_from_email' ) . '>';
			$id_key    = hash( 'sha512', uniqid() );
			// add custom link in body to reach single overview
			$body .= '<br/><br/>' . sprintf( $this->get_translated_string( '<a href="%s">Click here to view submission contact</a>', $lang ), admin_url( '/admin.php?page=forms_datas_management_contact_single_view&id_key=' . $id_key ) );
			
			if ( ( ! empty( $email_to ) ) /*&& wp_mail( $email_to, $subject, $body, $headers )*/ ):
				// Database save
				global $wpdb;
				// let's encrypt this !
				$data = array(
					'subject'    => $contact_subject,
					'civility'   => $contact_civility,
					'firstname'  => $contact_first_name,
					'lastname'   => $contact_last_name,
					'company'    => $contact_company,
					'job'        => $contact_job,
					'email'      => $contact_email,
					'message'    => str_replace( ';', ',', $contact_message ),
					'optin_rgpd' => $contact_optin_rgpd,
					'ip_address' => $this->get_ip(),
				);
				
				if ( ! class_exists( 'Cryptor', false ) ):
					require_once plugin_dir_path( __DIR__ ) . 'includes/Cryptor.php';
				endif;
				
				$encrypt_data = Cryptor::Encrypt( json_encode( $data ), DF_CONTACT_CRYPT_KEY );
				$encrypt_key  = hash( 'sha512', $contact_email . DF_CONTACT_CRYPT_KEY2 );
				
				$insert_contact = $wpdb->insert(
					$wpdb->prefix . 'df_contacts',
					array(
						'id_key'        => $id_key,
						'private_key'   => $encrypt_key,
						'data'          => $encrypt_data,
						'contact_theme' => $contact_subject,
						'date_insert'   => current_time( 'Y-m-d H:i:s' ),
						'lang'          => $lang,
					),
					array(
						'%s',
						'%s',
						'%s',
						'%s',
						'%s',
						'%s',
					)
				);
				
				if ( $insert_contact ):
					$response['code']           = 'all_good';
					$response['message']        = $this->get_translated_string( 'RAS', $lang );
					$response['data']['status'] = 200;
					$response['data']['msg']    = $this->get_translated_string( 'Message successfully sent.', $lang );
				else:
					$response['code']           = 'error_db';
					$response['message']        = $this->get_translated_string( 'Save DB error', $lang );
					$response['data']['errors'] = array( 'global' => $this->get_translated_string( 'An error occured while sending your message.', $lang ) );
				endif;
			else:
				$response['code']           = 'error_mail';
				$response['message']        = $this->get_translated_string( 'Send mail error', $lang );
				$response['data']['errors'] = array( 'global' => $this->get_translated_string( 'An error occured while sending your message.', $lang ) );
			endif;
			
			// Reset content-type to avoid conflicts -- https://core.trac.wordpress.org/ticket/23578
			remove_filter( 'wp_mail_content_type', array( $this, 'wpdocs_set_html_mail_content_type' ) );
		else:
			$response['code']           = 'error_validation';
			$response['message']        = $this->get_translated_string( 'Validator failed', $lang );
			$response['data']['errors'] = $errors;
		endif;
		
		return $response;
	}
	
	/**
	 * Set html type for mail
	 *
	 * @return string
	 *
	 * @since    1.0.0
	 */
	public function wpdocs_set_html_mail_content_type() {
		return 'text/html';
	}
	
	/**
	 * Get translated string (trick for REST API)
	 *
	 * @param $string
	 * @param $lang
	 *
	 * @return mixed
	 * @since    2.2.1
	 */
	private function get_translated_string( $string, $lang ) {
		if ( function_exists( 'pll_translate_string' ) ):
			return pll_translate_string( $string, $lang );
		else:
			return __( $string, $this->plugin_name );
		endif;
	}
	
	/**
	 * Get recpatcha response from Google
	 *
	 * @param $captcha_response
	 *
	 * @return bool
	 *
	 * @since    2.0.10
	 */
	private function verify_recaptcha( $captcha_response ) {
		$result                               = false;
		$df_contact_recaptcha_secret_site_key = get_option( 'df_contact_recaptcha_secret_site_key' );
		
		$service_url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . $df_contact_recaptcha_secret_site_key . '&response=' . $captcha_response . '&remoteip=' . $this->get_ip();
		
		$curl = curl_init( $service_url );
		curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, 0 );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13' );
		$curl_response = curl_exec( $curl );
		
		$http_code = curl_getinfo( $curl, CURLINFO_HTTP_CODE );
		
		if ( 200 === $http_code ):
			if ( $curl_response === false ):
				curl_close( $curl );
				error_log( 'error occured during curl exec.' );
			else :
				curl_close( $curl );
				$decoded_result = json_decode( $curl_response );
				$result         = $decoded_result->success;
			endif;
		else:
			error_log( "error: occured during curl_getinfo code ret.$http_code" );
		endif;
		
		return $result;
	}
	
	/**
	 * Get IP
	 *
	 * @return mixed|string
	 *
	 * @since    2.0.10
	 */
	private function get_ip() {
		//Just get the headers if we can or else use the SERVER global
		if ( function_exists( 'apache_request_headers' ) ) :
			$headers = apache_request_headers();
		else:
			$headers = $_SERVER;
		endif;
		//Get the forwarded IP if it exists
		if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) && filter_var( $_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 ) && ( '127.0.0.1' !== $_SERVER['HTTP_CLIENT_IP'] ) ) :
			$the_ip = $_SERVER['HTTP_CLIENT_IP'];
		elseif ( array_key_exists( 'X-Forwarded-For', $headers ) && filter_var( $headers['X-Forwarded-For'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 ) && ( '127.0.0.1' !== $headers['X-Forwarded-For'] ) ) :
			$the_ip = $headers['X-Forwarded-For'];
		elseif ( array_key_exists( 'HTTP_X_FORWARDED_FOR', $headers ) && filter_var( $headers['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 ) && ( '127.0.0.1' !== $headers['HTTP_X_FORWARDED_FOR'] ) ) :
			// Check ip is pass from proxy
			$ip = explode( ',', $headers['HTTP_X_FORWARDED_FOR'] );
			// Can include more than 1 ip, first is the public one
			$the_ip = trim( $ip[0] );
		else:
			$the_ip = filter_var( $_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 );
		endif;
		
		return $the_ip;
	}
	
	/**
	 * Delete old database entries (X month old) of contact form
	 *
	 * @since    1.0.0
	 */
	public function cron_clean_old_contacts() {
		global $wpdb;
		
		$date = new DateTime( 'now' );
		
		$month = get_option( 'df_contact_cleaner_delay' );
		
		if ( empty( $month ) || ! is_numeric( $month ) || ( $month < 1 ) ):
			// default 24 month
			$date->modify( '-24 month' );
		else:
			$date->modify( '-' . $month . ' month' );
		endif;
		
		$old_date = $date->format( 'Y-m-d h:i:s' );
		
		$result = $wpdb->query( $wpdb->prepare( 'DELETE FROM ' . $wpdb->prefix . 'df_contacts WHERE date_insert <= %s', $old_date ) );
		
		if ( false === $result ):
			// mysql error
			error_log( 'Mysql Error in cron_clean_old_contacts (df-contact)' );
		endif;
	}
	
}

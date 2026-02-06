<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.havasdigitalfactory.com/
 * @since      1.0.0
 *
 * @package    Df_guidelines_checker
 * @subpackage Df_guidelines_checker/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Df_guidelines_checker
 * @subpackage Df_guidelines_checker/admin
 * @author     Sébastien GASTARD <sebastien.gastard@havasdigitalfactory.com>
 */
class Df_guidelines_checker_Admin {
	
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
	 * List of required config for each env
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array $hdf_default_config List of required config for each env.
	 */
	private $hdf_default_config;
	
	/**
	 * Checklist
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array $hdf_checklist Checklist.
	 */
	private $hdf_checklist;
	
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
		// list of required plugins, premium or not
		// check for required dependencies
		$hdf_plugins = array(
			'required'     =>
				array(
					'df-guidelines-checker/df_guidelines_checker.php' => array( 'Name' => 'Digital Factory Guidelines Checker' ),
					'ithemes-security-pro/ithemes-security-pro.php'   => array( 'Name' => 'iThemes Security Pro' ),
					'advanced-custom-fields-pro/acf.php'              => array( 'Name' => 'Advanced Custom Fields Pro', ),
					'wp-rocket/wp-rocket.php'                         => array( 'Name' => 'WP Rocket' ),
					'wp-migrate-db-pro/wp-migrate-db-pro.php'         => array( 'Name' => 'WP Migrate DB Pro', ),
					'wp-seopress-pro/seopress-pro.php'                => array( 'Name' => 'SEOPress Pro', ),
				),
			'need_premium' =>
				array(
					'better-wp-security/better-wp-security.php' => array(
						'Name'    => 'iThemes Security',
						'premium' => 'ithemes-security-pro/ithemes-security-pro.php',
					),
					'advanced-custom-fields/acf.php'            => array(
						'Name'    => 'Advanced Custom Fields',
						'premium' => 'advanced-custom-fields-pro/acf.php',
					),
					'wp-migrate-db/wp-migrate-db.php'           => array(
						'Name'    => 'WP Migrate DB',
						'premium' => 'wp-migrate-db-pro/wp-migrate-db-pro.php',
					),
					'wp-seopress/seopress.php'                  => array(
						'Name'    => 'SEOPress',
						'premium' => 'wp-seopress-pro/seopress-pro.php',
					),
					'polylang/polylang.php'                     => array(
						'Name'    => 'Polylang Pro',
						'premium' => 'polylang-pro/polylang.php',
					),
				),
			'dependencies' =>
				array(
					'polylang/polylang.php'                    => array(
						array(
							'Name' => 'ACF Options for Polylang',
							'path' => 'acf-options-for-polylang/bea-acf-options-for-polylang.php'
						),
						array(
							'Name' => 'Digital Factory String Translations for Polylang',
							'path' => 'df-string-translations-for-polylang/df-string-translations-for-polylang.php'
						),
					),
					'polylang-pro/polylang.php'                => array(
						array(
							'Name' => 'ACF Options for Polylang',
							'path' => 'acf-options-for-polylang/bea-acf-options-for-polylang.php'
						),
						array(
							'Name' => 'Digital Factory String Translations for Polylang',
							'path' => 'df-string-translations-for-polylang/df-string-translations-for-polylang.php'
						),
					),
					'sitepress-multilingual-cms/sitepress.php' => array(
						array(
							'Name' => 'WPML String Translation',
							'path' => 'wpml-string-translation/plugin.php'
						)
					),
				),
		);
		
		$this->plugin_name        = $plugin_name;
		$this->version            = $version;
		$this->hdf_default_config = array(
			'dev'  => array(
				'plugins'     => $hdf_plugins,
				'debug'       => true,
				'blog_public' => 0,
			),
			'prod' => array(
				'plugins'     => $hdf_plugins,
				'debug'       => false,
				'blog_public' => 1,
			),
		);
		
		// online save
		$online_save_url = 'https://audit.hdf.local/audit/v1/save/';
		
		$this->hdf_checklist = array();
		
		$checklist_coding = array();
		// Structured theme
		$checklist_coding['structured_theme'] = array(
			'text'     => __( 'The theme is organized and structured (ex : /css, /img, /js, /templates)', $this->plugin_name ),
			'text_nok' => __( 'The theme is not organized or structured (ex : /css, /img, /js, /templates)', $this->plugin_name ),
		);
		
		// Coding Standard
		$checklist_coding['standard_wp'] = array(
			'text'     => sprintf( __( 'The code is commented and respects the <a href="%s" target="_blank">standards of WordPress</a>', $this->plugin_name ), 'https://make.wordpress.org/core/handbook/best-practices/coding-standards/php/' ),
			'text_nok' => sprintf( __( 'The code is not commented and don\'t respect the <a href="%s" target="_blank">standards of WordPress</a>', $this->plugin_name ), 'https://make.wordpress.org/core/handbook/best-practices/coding-standards/php/' ),
		);
		
		// Ajax call
		$checklist_coding['ajax_call'] = array(
			'text'     => sprintf( __( 'The AJAX calls use the REST API (<a href="%s" target="_blank">see the handbook for further informations</a>)', $this->plugin_name ), 'https://developer.wordpress.org/rest-api/' ),
			'text_nok' => sprintf( __( 'The AJAX calls don\'t use the REST API (<a href="%s" target="_blank">see the handbook for further informations</a>)', $this->plugin_name ), 'https://developer.wordpress.org/rest-api/' ),
		);
		
		// Api keys in BO
		$checklist_coding['api_keys'] = array(
			'text'     => __( 'API keys can be managed from a dedicated back-office page (like Google Analytics UA, Google Map API key, Google Recaptcha private/public key etc...)', $this->plugin_name ),
			'text_nok' => __( 'API keys can\'t be managed from a dedicated back-office page (like Google Analytics UA, Google Map API key, Google Recaptcha private/public key etc...)', $this->plugin_name ),
		);
		
		// Favicons
		$checklist_coding['favicons'] = array(
			'text'     => __( 'Favicons are present', $this->plugin_name ),
			'text_nok' => __( 'Favicons are missing', $this->plugin_name ),
		);
		
		$this->hdf_checklist['audit_coding'] = array(
			'label'  => __( 'Code global evaluation', $this->plugin_name ),
			'icon'   => '<span class="dashicons dashicons-html"></span>',
			'checks' => $checklist_coding,
		);
		
		// Security
		$checklist_security = array();
		
		$checklist_security['escape_data'] = array(
			'text'     => __( 'All the variables displayed in front must be escaped (use esc_html (), esc_url (), esc_js (), esc_attr () etc ...)', $this->plugin_name ),
			'text_nok' => __( 'All the variables displayed in front are not escaped (use esc_html (), esc_url (), esc_js (), esc_attr () etc ...)', $this->plugin_name ),
		);
		
		$checklist_security['sanitize_data'] = array(
			'text'     => sprintf( __( 'All the variables posted (AJAX call or classic form) must be cleaned and validated, either with the <a href="%s" target="_blank">functions of WP</a> or with the <a href="%s" target="_blank">filter_var native PHP function</a>', $this->plugin_name ), 'https://codex.wordpress.org/Validating_Sanitizing_and_Escaping_User_Data', 'http://php.net/manual/fr/function.filter-var.php' ),
			'text_nok' => sprintf( __( 'All the variables posted (AJAX call or classic form) are not cleaned and validated, either with the <a href="%s" target="_blank">functions of WP</a> or with the <a href="%s" target="_blank">filter_var native PHP function</a>', $this->plugin_name ), 'https://codex.wordpress.org/Validating_Sanitizing_and_Escaping_User_Data', 'http://php.net/manual/fr/function.filter-var.php' ),
		);
		
		$this->hdf_checklist['audit_security'] = array(
			'label'  => __( 'Security', $this->plugin_name ),
			'icon'   => '<span class="dashicons dashicons-shield-alt"></span>',
			'checks' => $checklist_security,
		);
		
		// Forms
		$checklist_forms = array();
		
		$checklist_forms['tested_forms'] = array(
			'text'     => __( 'All form fields have been tested with special characters, eg: "(- è_çà)', $this->plugin_name ),
			'text_nok' => __( 'Bug in form fields when testing with special characters, like "(- è_çà)', $this->plugin_name ),
		);
		
		$checklist_forms['recaptcha_forms'] = array(
			'text'     => __( 'Each form contains a Google ReCaptcha field', $this->plugin_name ),
			'text_nok' => __( 'Google ReCaptcha is missing on the forms', $this->plugin_name ),
		);
		
		$this->hdf_checklist['audit_forms'] = array(
			'label'  => __( 'Forms', $this->plugin_name ),
			'icon'   => '<span class="dashicons dashicons-edit-page"></span>',
			'checks' => $checklist_forms,
		);
		
		if ( ! function_exists( 'is_plugin_active' ) ):
			require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
		endif;
		
		// Multilingual
		if ( is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' ) || is_plugin_active( 'polylang-pro/polylang.php' ) || is_plugin_active( 'polylang/polylang.php' ) ):
			$checklist_multilingual = array();
			
			$checklist_multilingual['wpml_config_global'] = array(
				'text'     => __( 'WPML > Languages > Make themes work multilingual : Adjust IDs for multilingual functionality must be unchecked', $this->plugin_name ),
				'text_nok' => __( 'WPML > Languages > Make themes work multilingual : Adjust IDs for multilingual functionality must be unchecked', $this->plugin_name ),
			);
			
			$checklist_multilingual['multilingual_config_acf'] = array(
				'text'     => __( 'Field Groups must not be translated (WPML > Translation Management > Multilingual Content Setup, or Polylang > Settings > Custom Post Type)', $this->plugin_name ),
				'text_nok' => __( 'Field Groups must not be translated (WPML > Translation Management > Multilingual Content Setup, or Polylang > Settings > Custom Post Type)', $this->plugin_name ),
			);
			
			$checklist_multilingual['multilingual_config_cpt'] = array(
				'text'     => __( 'Custom Post Types and custom taxonomies can be translated', $this->plugin_name ),
				'text_nok' => __( 'Custom Post Types and custom taxonomies can\'t be translated', $this->plugin_name ),
			);
			
			$this->hdf_checklist['audit_multilingual'] = array(
				'label'  => __( 'Multilingual', $this->plugin_name ),
				'icon'   => '<span class="dashicons dashicons-format-status"></span>',
				'checks' => $checklist_multilingual,
			);
		endif;
		// Global
		$global_warnings = array();
		
		$global_warnings['misc'] = array(
			'text'     => __( 'General appreciation', $this->plugin_name ),
			'text_nok' => __( 'Some points need your attention', $this->plugin_name ),
		);
		
		$this->hdf_checklist['global_warnings'] = array(
			'label'  => __( 'Global', $this->plugin_name ),
			'icon'   => '<span class="dashicons dashicons-warning"></span>',
			'checks' => $global_warnings,
		);
		
	}
	
	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		// Bootstrap
		wp_enqueue_style( 'bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css', false, $this->version, 'all' );
		
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/df_guidelines_checker-admin.css', array( 'bootstrap-css' ), $this->version, 'all' );
		
	}
	
	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		// Bootstrap
		wp_enqueue_script( 'bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js', false, $this->version, true );
		// Icons
		wp_enqueue_script( 'feather-icons', 'https://unpkg.com/feather-icons', false, $this->version, true );
		wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/df_guidelines_checker-admin.js', array( 'jquery', 'bootstrap-js', 'feather-icons' ), $this->version, true );
		// Localize the script
		$translation_array = array(
			'ajax_save_url'   => home_url( '/wp-json/' . $this->plugin_name . '/v1/save-audit/' ),
			'ajax_config_url' => home_url( '/wp-json/' . $this->plugin_name . '/v1/save-config-audit/' ),
			'confirm_save'    => __( 'The audit is finished and you want to save it ?', $this->plugin_name ),
			'nonce'           => wp_create_nonce( 'wp_rest' ),
		);
		
		wp_localize_script( $this->plugin_name, 'js_df_audit', $translation_array );
		wp_enqueue_script( $this->plugin_name );
	}
	
	/**
	 * Add extra attribute for style or javascript
	 *
	 * @param $html
	 * @param $handle
	 *
	 * @return string|string[] $html
	 *
	 * @since    1.7.0
	 */
	public function add_custom_attributes( $html, $handle ) {
		if ( 'bootstrap-css' === $handle ) :
			return str_replace( "media='all'", "media='all' integrity='sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1' crossorigin='anonymous'", $html );
		endif;
		
		if ( 'bootstrap-js' === $handle ) :
			return str_replace( "script src=", "script integrity='sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW' crossorigin='anonymous' src=", $html );
		endif;
		
		return $html;
	}
	
	/**
	 * Add submenu page
	 *
	 * @since    1.0.0
	 */
	public function add_submenu_page_checker() {
		
		add_submenu_page(
			'tools.php',
			__( 'Digital Factory Guidelines Checker', $this->plugin_name ),
			__( 'Guidelines Checker', $this->plugin_name ),
			'manage_options',
			'df-guidelines-checker',
			array( $this, 'display_page_checker' )
		);
		
	}
	
	/**
	 * Add ajax endpoint
	 *
	 * @since    1.1.0
	 * @modified 1.6.1 add permission callback
	 */
	public function add_ajax_endpoints() {
		
		$namespace = $this->plugin_name;
		$version   = 1;
		
		register_rest_route( $namespace . '/v' . $version, '/save-audit/', array(
			'methods'             => WP_REST_Server::CREATABLE,
			'callback'            => array( $this, 'save_audit' ),
			'permission_callback' => function () {
				return current_user_can( 'manage_options' );
			}
		) );
		
		register_rest_route( $namespace . '/v' . $version, '/save-config-audit/', array(
			'methods'             => WP_REST_Server::CREATABLE,
			'callback'            => array( $this, 'save_config_audit' ),
			'permission_callback' => function () {
				return current_user_can( 'manage_options' );
			}
		) );
		
	}
	
	/**
	 * Get checklist
	 *
	 * @since    1.1.0
	 */
	public function get_hdf_checklist() {
		
		return $this->hdf_checklist;
		
	}
	
	/**
	 * Save audit
	 *
	 * @return array
	 *
	 * @since    1.1.0
	 * @modified 1.6.1 fix return statement
	 */
	public function save_audit() {
		$result = array(
			'success' => false,
			'html'    => '<div class="alert alert-danger" role="alert">' . __( 'Sorry an error occurred.', $this->plugin_name ) . '</div>',
		);
		
		$allowed_env_audit = array( 'DEV', 'PROD' );
		
		$env_audit  = '';
		$distant_id = '';
		
		if ( ! empty( $_POST['env_audit'] ) && in_array( $_POST['env_audit'], $allowed_env_audit ) ):
			$env_audit = $_POST['env_audit'];
		else:
			// throw error
			return $result;
		endif;
		
		if ( ! empty( $_POST['distant_id'] ) && filter_var( $_POST['distant_id'], FILTER_SANITIZE_NUMBER_INT ) ):
			$distant_id = intval( $_POST['distant_id'] );
		endif;
		
		$datas = $this->json_to_array_clean( $this->stripslashes_deep( $_POST['automated_audit'] ) );
		
		// create new PDF document
		$pdf = new Audit_PDF( PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false );
		
		// set document information
		$pdf->SetCreator( PDF_CREATOR );
		$pdf->SetAuthor( 'Audit Digital Factory' );
		$pdf->SetTitle( 'Audit Digital Factory' );
		$pdf->SetSubject( 'Audit Digital Factory' );
		$pdf->SetKeywords( 'Audit, Digital, Factory' );
		
		// set default header data
		$pdf->SetHeaderData( PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING );
		
		// set header and footer fonts
		$pdf->setHeaderFont( array( PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN ) );
		$pdf->setFooterFont( array( PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA ) );
		
		// set default monospaced font
		$pdf->SetDefaultMonospacedFont( PDF_FONT_MONOSPACED );
		
		// set margins
		$pdf->SetMargins( PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT );
		$pdf->SetHeaderMargin( PDF_MARGIN_HEADER );
		$pdf->SetFooterMargin( PDF_MARGIN_FOOTER );
		$pdf->setCellPaddings( 1, 1, 1, 1 );
		
		// set auto page breaks
		$pdf->SetAutoPageBreak( true, PDF_MARGIN_BOTTOM );
		
		// set image scale factor
		$pdf->setImageScale( PDF_IMAGE_SCALE_RATIO );
		
		// set font
		$pdf->SetFont( 'helvetica', '', 12 );
		
		// add a page
		$pdf->AddPage();
		
		// draw panel for each category
		foreach ( $datas as $id => $section ):
			$pdf->drawAuditCells( $section['title'], $section['global_status'], $section['content'] );
		endforeach;
		
		// draw panel for each checklist category
		foreach ( $_POST['checklist'] as $id => $section ):
			$pdf->drawAuditChecklistCells( $section['label'], $id, $section['checks'], $this->get_hdf_checklist() );
		endforeach;
		
		// Close and output PDF document
		$filename  = wp_unique_filename( WP_CONTENT_DIR . '/uploads/tcpdf-pohqnz6fhgbselfvn2847mqs3hdvqdsfkvnl/', sanitize_title( 'audit-' . $env_audit . '-' . get_bloginfo( 'name' ) . '-' . date( 'YmdHis' ) ) . '.pdf' );
		$file_path = WP_CONTENT_DIR . '/uploads/tcpdf-pohqnz6fhgbselfvn2847mqs3hdvqdsfkvnl/' . $filename;
		$file_uri  = WP_CONTENT_URL . '/uploads/tcpdf-pohqnz6fhgbselfvn2847mqs3hdvqdsfkvnl/' . $filename;
		
		if ( wp_mkdir_p( WP_CONTENT_DIR . '/uploads/tcpdf-pohqnz6fhgbselfvn2847mqs3hdvqdsfkvnl/' ) ):
			$pdf->Output( $file_path, 'F' );
			
			if ( is_file( $file_path ) ):
				$hash_file         = hash_file( 'sha256', $file_path );
				$result['success'] = true;
				$result['html']    = sprintf( __( '<div class="alert alert-success" role="alert">You can download the audit file by clicking the following link : <a href="%s" download>%s</a></div>', $this->plugin_name ), $file_uri, $filename );
				
				$checklist        = $_POST['checklist'];
				$checklist['env'] = $env_audit;
				
				// send audit
				$hdf_audit_configuration = json_decode( get_option( 'hdf_audit_configuration' ), true );
				$global_score            = $pdf->Get_Score(); // max score : 100
				$checklist['scoring']    = $global_score;
				$distant_save_url        = 'https://audit-hdf.havasdigitalfactory.net/wp-json/audit/v1/save/';
				$ssl_verify              = false;
				$username                = 'hdf-audit';
				$password                = 'QlksndqnZ28sS2';
				$boundary                = wp_generate_password( 24 );
				$data_with_file          = '';
				// First, add the standard text fields
				$data_with_file .= '--' . $boundary;
				$data_with_file .= "\r\n";
				$data_with_file .= 'Content-Disposition: form-data; name="project_name"' . "\r\n\r\n";
				$data_with_file .= $hdf_audit_configuration['project_name'];
				$data_with_file .= "\r\n";
				
				$data_with_file .= '--' . $boundary;
				$data_with_file .= "\r\n";
				$data_with_file .= 'Content-Disposition: form-data; name="id_det"' . "\r\n\r\n";
				$data_with_file .= $hdf_audit_configuration['id_det'];
				$data_with_file .= "\r\n";
				
				$data_with_file .= '--' . $boundary;
				$data_with_file .= "\r\n";
				$data_with_file .= 'Content-Disposition: form-data; name="env"' . "\r\n\r\n";
				$data_with_file .= $env_audit;
				$data_with_file .= "\r\n";
				
				$data_with_file .= '--' . $boundary;
				$data_with_file .= "\r\n";
				$data_with_file .= 'Content-Disposition: form-data; name="score"' . "\r\n\r\n";
				$data_with_file .= $global_score;
				$data_with_file .= "\r\n";
				
				$data_with_file .= '--' . $boundary;
				$data_with_file .= "\r\n";
				$data_with_file .= 'Content-Disposition: form-data; name="url"' . "\r\n\r\n";
				$data_with_file .= home_url( '/' );
				$data_with_file .= "\r\n";
				
				if ( ! empty( $distant_id ) ):
					$data_with_file .= '--' . $boundary;
					$data_with_file .= "\r\n";
					$data_with_file .= 'Content-Disposition: form-data; name="distant_id"' . "\r\n\r\n";
					$data_with_file .= $distant_id;
					$data_with_file .= "\r\n";
					// security hash
					$security_hash  = hash( 'sha256', $hdf_audit_configuration['project_name'] . '@' . $hdf_audit_configuration['id_det'] . '@' . $env_audit . '@' . $global_score . '@' . home_url( '/' ) . '@' . $hash_file . '@' . $distant_id . '4-~6x *`a{].vEf@UI^+`4=E~ak;{@khl|!j DswGd2(,wl@0~%rnE9u6|M9YiF!' );
					$data_with_file .= '--' . $boundary;
					$data_with_file .= "\r\n";
					$data_with_file .= 'Content-Disposition: form-data; name="security_hash"' . "\r\n\r\n";
					$data_with_file .= $security_hash;
					$data_with_file .= "\r\n";
				else:
					// security hash
					$security_hash  = hash( 'sha256', $hdf_audit_configuration['project_name'] . '@' . $hdf_audit_configuration['id_det'] . '@' . $env_audit . '@' . $global_score . '@' . home_url( '/' ) . '@' . $hash_file . '4-~6x *`a{].vEf@UI^+`4=E~ak;{@khl|!j DswGd2(,wl@0~%rnE9u6|M9YiF!' );
					$data_with_file .= '--' . $boundary;
					$data_with_file .= "\r\n";
					$data_with_file .= 'Content-Disposition: form-data; name="security_hash"' . "\r\n\r\n";
					$data_with_file .= $security_hash;
					$data_with_file .= "\r\n";
				endif;
				
				// Second, add file
				$data_with_file .= '--' . $boundary;
				$data_with_file .= "\r\n";
				$data_with_file .= 'Content-Disposition: form-data; name="audit_pdf"; filename="' . basename( $file_path ) . '"' . "\r\n";
				$data_with_file .= "\r\n";
				$data_with_file .= file_get_contents( $file_path );
				$data_with_file .= "\r\n";
				
				$data_with_file .= '--' . $boundary . '--';
				
				$response = wp_remote_post( $distant_save_url, array(
						'timeout'   => 45,
						'headers'   => array(
							'Content-type'  => 'multipart/form-data; boundary=' . $boundary,
							'Authorization' => 'Basic ' . base64_encode( $username . ':' . $password ),
						),
						'body'      => $data_with_file,
						'sslverify' => $ssl_verify,
					)
				);
				
				if ( is_wp_error( $response ) ) :
					$result['html'] .= '<div class="alert alert-warning" role="alert">' . $response->get_error_message() . '</div>';
				else :
					$data = json_decode( $response['body'], true );
					
					if ( ! empty( $data['status'] ) && ! empty( $data['message'] ) ):
						if ( $data['status'] !== 200 || empty( $data['distant_id'] ) ):
							$result['html'] .= '<div class="alert alert-warning" role="alert">' . $data['message'] . '</div>';
						else:
							// Save distant ID
							$checklist['distant_id'] = $data['distant_id'];
							$result['distant_id']    = $data['distant_id'];
							
							// save in DDB
							update_option( 'hdf_audit_checklist' . $env_audit, json_encode( $checklist ) );
							
							// Update progress bar
							$progress_data  = array();
							$progress_class = 'bg-danger';
							$ranges         = array(
								'bg-danger'  => 0,
								'bg-warning' => 25,
								'bg-info'    => 75,
								'bg-success' => 100
							);
							
							foreach ( $ranges as $class => $range ):
								if ( $global_score < $range ):
									continue;
								endif;
								
								$progress_class = $class;
							endforeach;
							
							$progress_data['class']    = 'progress-bar ' . $progress_class . ' progress-bar-striped progress-bar-animated';
							$progress_data['valuenow'] = $global_score;
							$progress_data['style']    = 'width:' . $global_score . '%';
							$progress_data['text']     = sprintf( __( '%d%% Complete', $this->plugin_name ), $global_score );
							
							$result['progress'] = $progress_data;
						endif;
					else:
						$result['html'] .= __( '<div class="alert alert-warning" role="alert">Error in distant save</div>', $this->plugin_name );
					endif;
				endif;
			else:
				// pdf not save
				$result['html'] = '<div class="alert alert-danger" role="alert">' . __( 'Sorry an error occurred while saving the pdf.', $this->plugin_name ) . '</div>';
			endif;
		endif;
		
		return $result;
	}
	
	/**
	 * Save config audit
	 *
	 * @return array
	 *
	 * @since    1.2.0
	 * @modified 1.6.1 fix return statement
	 */
	public function save_config_audit() {
		$result = array(
			'success' => false,
			'html'    => '<div class="alert alert-danger" role="alert">' . __( 'Sorry an error occurred.', $this->plugin_name ) . '</div>',
		);
		
		$project_name = '';
		$id_det       = '';
		
		if ( ! empty( $_POST['project_name'] ) ):
			$project_name = filter_var( $_POST['project_name'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES );
			$project_name = mb_convert_case( $project_name, MB_CASE_TITLE );
		endif;
		
		if ( ! empty( $_POST['id_det'] ) ):
			$id_det = filter_var( $_POST['id_det'], FILTER_SANITIZE_NUMBER_INT );
		endif;
		
		// check
		$error = false;
		$msg   = array();
		
		if ( empty( $project_name ) ):
			$error = true;
			$msg[] = __( 'Project name is required', $this->plugin_name );
		endif;
		
		if ( empty( $id_det ) ):
			$error = true;
			$msg[] = __( 'ID DET is required', $this->plugin_name );
		endif;
		
		if ( ! $error ):
			// save in DDB
			update_option( 'hdf_audit_configuration', json_encode( array( 'project_name' => $project_name, 'id_det' => $id_det ) ) );
			$result['success'] = true;
			$result['html']    = '<div class="alert alert-success" role="alert">' . __( 'Configuration saved successfully !', $this->plugin_name ) . '</div>';
		else:
			$result['html'] = '<div class="alert alert-danger" role="alert">' . implode( '<br>', $msg ) . '</div>';
		endif;
		
		return $result;
	}
	
	/**
	 * Clean json decode
	 *
	 * @since  1.1.0
	 */
	public function json_to_array_clean( $json ) {
		$check_json = $json;
		
		for ( $i = 0; $i <= 31; ++ $i ) :
			$check_json = str_replace( chr( $i ), '', $check_json );
		endfor;
		
		$check_json = str_replace( chr( 127 ), '', $check_json );
		
		// This is the most common part
		// Some file begins with 'efbbbf' to mark the beginning of the file. (binary level)
		// here we detect it and we remove it, basically it's the first 3 characters
		if ( 0 === strpos( bin2hex( $check_json ), 'efbbbf' ) ) {
			$check_json = substr( $check_json, 3 );
		}
		
		return json_decode( $check_json, true );
	}
	
	/**
	 * Clean stripslashes
	 *
	 * @since  1.3.2
	 */
	public function stripslashes_deep( $value ) {
		$value = is_array( $value ) ?
			array_map( array( $this, 'stripslashes_deep' ), $value ) :
			stripslashes( $value );
		
		return $value;
	}
	
	/**
	 * Render the page checker
	 *
	 * @since  1.0.0
	 * @modified 1.7.1 fix array keys for premium plugin dependencies
	 */
	public function display_page_checker() {
		$checks          = array();
		$core            = array();
		$themes          = array();
		$plugins         = array();
		$wp_config       = array();
		$configurations  = array();
		$users           = array();
		$default_content = array();
		
		// switch config checker if dev or prod
		$env       = 'dev';
		$switch_to = array(
			'dev'  => 'prod',
			'prod' => 'dev',
		);
		
		if ( isset( $_GET['env'] ) && array_key_exists( $_GET['env'], $this->hdf_default_config ) ) {
			$env = $_GET['env'];
		}
		
		// WordPress Core section
		$all_good        = true;
		$score           = 0;
		$total           = 0;
		$current_version = get_bloginfo( 'version' );
		
		$url      = 'https://api.wordpress.org/core/version-check/1.7/';
		$args     = array( 'sslverify' => false );
		$response = wp_safe_remote_get( $url, $args );
		
		if ( is_wp_error( $response ) ):
			error_log( $response->get_error_message() );
			$all_good = false;
			$comment  = __( 'Error connecting to api version-check', $this->plugin_name );
			$core[]   = array( 'status' => 'nok', 'text' => $comment );
		else:
			$json           = $response['body'];
			$obj            = json_decode( $json );
			$upgrade        = $obj->offers[0];
			$latest_version = $upgrade->version;
			
			if ( version_compare( $current_version, $latest_version, '<' ) ) :
				$all_good = false;
				$comment  = sprintf( __( 'WordPress %s should be updated (latest version : %s).', $this->plugin_name ), $current_version, $latest_version );
				$core[]   = array( 'status' => 'nok', 'text' => $comment );
			else:
				$comment = sprintf( __( 'WordPress %s is up-to-date.', $this->plugin_name ), $current_version );
				$core[]  = array( 'status' => 'ok', 'text' => $comment );
				$score ++;
			endif;
		endif;
		
		$total ++;
		
		$checks['core'] = array(
			'title'         => __( 'WordPress Core', $this->plugin_name ),
			'icon'          => '<span class="dashicons dashicons-wordpress"></span>',
			'score'         => $score . '/' . $total,
			'global_status' => $all_good,
			'content'       => $core,
		);
		
		// Theme section
		$check_update_themes = get_site_transient( 'update_themes' );
		// keep only one theme
		$all_good      = true;
		$score         = 0;
		$total         = 0;
		$current_theme = wp_get_theme();
		$all_themes    = wp_get_themes();
		$parent_theme  = '';
		
		// check if child theme
		if ( ! empty( $current_theme['Template'] ) ) :
			$parent_theme = $current_theme['Template'];
		endif;
		
		foreach ( $all_themes as $id => $theme ):
			if ( $theme == $current_theme ):
				// Update required ?
				if ( false !== $check_update_themes ):
					if ( array_key_exists( $id, $check_update_themes->response ) ):
						$comment       = sprintf( __( '%s is installed but an update is available (version %s).', $this->plugin_name ), $theme['Name'] . ' ' . $theme['Version'], $check_update_themes->response[ $id ]['new_version'] );
						$themes[ $id ] = array( 'status' => 'nok', 'text' => $comment );
						$all_good      = false;
					else:
						$comment       = sprintf( __( '%s is installed.', $this->plugin_name ), $theme['Name'] . ' ' . $theme['Version'] );
						$themes[ $id ] = array( 'status' => 'ok', 'text' => $comment );
					endif;
				else:
					$comment       = sprintf( __( '%s is installed.', $this->plugin_name ), $theme['Name'] . ' ' . $theme['Version'] );
					$themes[ $id ] = array( 'status' => 'ok', 'text' => $comment );
				endif;
			else:
				if ( $id === $parent_theme ):
					// parent theme, we must keep it
					// Update required ?
					if ( false !== $check_update_themes ):
						if ( array_key_exists( $id, $check_update_themes->response ) ):
							$comment       = sprintf( __( '%s is the parent theme but an update is available (version %s).', $this->plugin_name ), $theme['Name'] . ' ' . $theme['Version'], $check_update_themes->response[ $id ]['new_version'] );
							$themes[ $id ] = array( 'status' => 'nok', 'text' => $comment );
							$all_good      = false;
						else:
							$comment       = sprintf( __( '%s is the parent theme.', $this->plugin_name ), $theme['Name'] . ' ' . $theme['Version'] );
							$themes[ $id ] = array( 'status' => 'ok', 'text' => $comment );
							$score ++;
						endif;
					else:
						$comment       = sprintf( __( '%s is the parent theme.', $this->plugin_name ), $theme['Name'] . ' ' . $theme['Version'] );
						$themes[ $id ] = array( 'status' => 'ok', 'text' => $comment );
						$score ++;
					endif;
				else:
					$comment       = sprintf( __( '%s is inactive and should be deleted.', $this->plugin_name ), $theme['Name'] . ' ' . $theme['Version'] );
					$themes[ $id ] = array( 'status' => 'nok', 'text' => $comment );
					$all_good      = false;
				endif;
			endif;
			$total ++;
		endforeach;
		
		$checks['themes'] = array(
			'title'         => __( 'Themes', $this->plugin_name ),
			'icon'          => '<span class="dashicons dashicons-admin-appearance"></span>',
			'score'         => $score . '/' . $total,
			'global_status' => $all_good,
			'content'       => $themes,
		);
		
		// Plugins section
		$check_update_plugins = get_site_transient( 'update_plugins' );
		$all_good             = true;
		$score                = 0;
		$total                = 0;
		$all_plugins          = get_plugins();
		
		foreach ( $all_plugins as $path => $plugin ):
			if ( ! is_plugin_active( $path ) ) :
				// required one?
				if ( array_key_exists( $path, $this->hdf_default_config[ $env ]['plugins']['required'] ) ) :
					$plugins[ $path ] = array( 'status' => 'nok', 'text' => sprintf( __( '%s is inactive and should be activated.', $this->plugin_name ), $plugin['Name'] . ' ' . $plugin['Version'] ) );
				else:
					if ( array_key_exists( $path, $this->hdf_default_config[ $env ]['plugins']['need_premium'] ) ) {
						$path_required             = $this->hdf_default_config[ $env ]['plugins']['need_premium'][ $path ]['premium'];
						$plugins[ $path_required ] = array( 'status' => 'nok', 'text' => sprintf( __( '%s is inactive and should be deleted. Please install %s.', $this->plugin_name ), $plugin['Name'] . ' ' . $plugin['Version'], $this->hdf_default_config[ $env ]['plugins']['required'][ $path_required ]['Name'] ) );
					} else {
						$plugins[ $path ] = array( 'status' => 'nok', 'text' => sprintf( __( ' %s is inactive and should be deleted.', $this->plugin_name ), $plugin['Name'] . ' ' . $plugin['Version'] ) );
					}
				endif;
				
				$all_good = false;
			else:
				// active plugin, check if a premium version is required
				if ( array_key_exists( $path, $this->hdf_default_config[ $env ]['plugins']['need_premium'] ) ) :
					$path_required    = $this->hdf_default_config[ $env ]['plugins']['need_premium'][ $path ]['premium'];
					$plugins[ $path ] = array( 'status' => 'nok', 'text' => sprintf( __( ' %s is active but you should install %s.', $this->plugin_name ), $plugin['Name'] . ' ' . $plugin['Version'], $this->hdf_default_config[ $env ]['plugins']['need_premium'][ $path ]['Name'] ) );
					$all_good         = false;
				else:
					if ( false !== $check_update_plugins ):
						if ( array_key_exists( $path, $check_update_plugins->response ) ):
							$plugins[ $path ] = array( 'status' => 'nok', 'text' => sprintf( __( ' %s is active but an update is available (version %s).', $this->plugin_name ), $plugin['Name'] . ' ' . $plugin['Version'], $check_update_plugins->response[ $path ]->new_version ) );
							$all_good         = false;
						else:
							$plugins[ $path ] = array( 'status' => 'ok', 'text' => sprintf( __( ' %s is active.', $this->plugin_name ), $plugin['Name'] . ' ' . $plugin['Version'] ) );
							$score ++;
						endif;
					else:
						$plugins[ $path ] = array( 'status' => 'ok', 'text' => sprintf( __( ' %s is active.', $this->plugin_name ), $plugin['Name'] . ' ' . $plugin['Version'] ) );
						$score ++;
					endif;
				endif;
			endif;
			$total ++;
		endforeach;
		
		// check for missing required plugins
		foreach ( $this->hdf_default_config[ $env ]['plugins']['required'] as $path => $plugin ):
			if ( ! array_key_exists( $path, $plugins ) ) :
				if ( ! empty( $plugin['PublicURI'] ) ):
					$plugins[ $path ] = array( 'status' => 'nok', 'text' => sprintf( __( ' %s must be installed.', $this->plugin_name ), $plugin['Name'] ), 'download_uri' => sprintf( __( ' <a href="%s" target="_blank">Download the last version from the WordPress repository.</a>', $this->plugin_name ), $plugin['PublicURI'] ) );
				else:
					$plugins[ $path ] = array( 'status' => 'nok', 'text' => sprintf( __( ' %s must be installed.', $this->plugin_name ), $plugin['Name'] ) );
				endif;
				
				$all_good = false;
				$total ++;
			endif;
		endforeach;
		
		// check for required dependencies
		foreach ( $all_plugins as $path => $plugin ):
			if ( array_key_exists( $path, $this->hdf_default_config[ $env ]['plugins']['dependencies'] ) ):
				foreach ( $this->hdf_default_config[ $env ]['plugins']['dependencies'][ $path ] as $dependencie ):
					if ( is_plugin_active( $path ) && ! array_key_exists( $dependencie['path'], $plugins ) ) :
						$plugins[ $path ]['status'] = 'nok';
						$plugins[ $path ]['text']   .= '<br/>' . sprintf( __( ' %s must be installed.', $this->plugin_name ), $dependencie['Name'] );
						
						$all_good = false;
						$total ++;
					endif;
				endforeach;
			endif;
		endforeach;
		
		$checks['plugins'] = array(
			'title'         => __( 'Plugins', $this->plugin_name ),
			'icon'          => '<span class="dashicons dashicons-admin-plugins"></span>',
			'score'         => $score . '/' . $total,
			'global_status' => $all_good,
			'content'       => $plugins,
		);
		// wp-config Section
		$all_good = true;
		$score    = 0;
		$total    = 0;
		
		// Table prefix has changed ?
		global $table_prefix;
		if ( 'wp_' === $table_prefix ) :
			$comment                   = sprintf( __( 'The table prefix has not been changed (currently set to %s).', $this->plugin_name ), $table_prefix );
			$wp_config['table_prefix'] = array( 'status' => 'nok', 'text' => $comment );
			$all_good                  = false;
		else:
			$comment                   = sprintf( __( 'The table prefix has been changed to %s', $this->plugin_name ), $table_prefix );
			$wp_config['table_prefix'] = array( 'status' => 'ok', 'text' => $comment );
			$score ++;
		endif;
		$total ++;
		
		// Debug is off ?
		if ( WP_DEBUG !== $this->hdf_default_config[ $env ]['debug'] ) :
			$comment            = sprintf( __( 'The WP_DEBUG should be set to %s.', $this->plugin_name ), var_export( $this->hdf_default_config[ $env ]['debug'], true ) );
			$wp_config['debug'] = array( 'status' => 'nok', 'text' => $comment, 'snippet' => sprintf( __( 'define( \'WP_DEBUG\', %s );', $this->plugin_name ), var_export( $this->hdf_default_config[ $env ]['debug'], true ) ) );
			
			$all_good = false;
		else:
			$comment            = sprintf( __( 'The WP_DEBUG is set to %s.', $this->plugin_name ), var_export( $this->hdf_default_config[ $env ]['debug'], true ) );
			$wp_config['debug'] = array( 'status' => 'ok', 'text' => $comment );
			$score ++;
			
			if ( WP_DEBUG ):
				// if you have mismatch notice mysql, you can activate the wp log
				$wp_config['debug']['snippet'] = __( 'If you have warnings like "Headers and client library minor version mismatch", you can activate the <a href="https://codex.wordpress.org/Debugging_in_WordPress#WP_DEBUG_LOG" target="_blank">WordPress debug log</a> :<br />define( \'WP_DEBUG_LOG\', true );<br />define( \'WP_DEBUG_DISPLAY\', false );', 'audit' );
			endif;
		endif;
		$total ++;
		
		// disable auto-update
		if ( defined( 'AUTOMATIC_UPDATER_DISABLED' ) && AUTOMATIC_UPDATER_DISABLED ) :
			$comment                  = __( 'Automatic Updates are disable.', $this->plugin_name );
			$wp_config['auto_update'] = array( 'status' => 'ok', 'text' => $comment );
			$score ++;
		else:
			$comment                  = __( 'Automatic Updates are not disable.', $this->plugin_name );
			$wp_config['auto_update'] = array( 'status' => 'nok', 'text' => $comment, 'snippet' => 'define( \'AUTOMATIC_UPDATER_DISABLED\', true );' );
			$all_good                 = false;
		endif;
		$total ++;
		
		// revision closed or limited ?
		if ( defined( 'WP_POST_REVISIONS' ) && ( true !== WP_POST_REVISIONS ) ) :
			if ( false === WP_POST_REVISIONS ) :
				$comment                = __( 'Revisions are disable.', $this->plugin_name );
				$wp_config['revisions'] = array( 'status' => 'ok', 'text' => $comment );
			else:
				$comment                = sprintf( __( 'Revisions are limited to %d versions.', $this->plugin_name ), WP_POST_REVISIONS );
				$wp_config['revisions'] = array( 'status' => 'ok', 'text' => $comment );
			endif;
			$score ++;
		else:
			$comment                = __( 'Revisions should be limited or disable.', $this->plugin_name );
			$wp_config['revisions'] = array( 'status' => 'nok', 'text' => $comment, 'snippet' => 'define( \'WP_POST_REVISIONS\', 3 );' );
			$all_good               = false;
		endif;
		$total ++;
		
		// memory limit ok ?
		$memory_int = intval( WP_MEMORY_LIMIT );
		
		if ( $memory_int >= 128 ):
			$comment                   = sprintf( __( 'The WP_MEMORY_LIMIT is set to %s.', $this->plugin_name ), WP_MEMORY_LIMIT );
			$wp_config['memory_limit'] = array( 'status' => 'ok', 'text' => $comment );
			$score ++;
		else:
			$comment                   = sprintf( __( 'The WP_MEMORY_LIMIT is set to %s, it should be at least 128M.', $this->plugin_name ), WP_MEMORY_LIMIT );
			$wp_config['memory_limit'] = array( 'status' => 'nok', 'text' => $comment, 'snippet' => 'define( \'WP_MEMORY_LIMIT\', \'128M\' );' );
			$all_good                  = false;
		endif;
		$total ++;
		
		$checks['wp-config'] = array(
			'title'         => __( 'wp-config.php', $this->plugin_name ),
			'icon'          => '<span class="dashicons dashicons-shield"></span>',
			'score'         => $score . '/' . $total,
			'global_status' => $all_good,
			'content'       => $wp_config,
		);
		// Configurations section
		$all_good = true;
		$score    = 0;
		$total    = 0;
		// remove license and readme
		if ( is_file( ABSPATH . 'license.txt' ) ) :
			$comment                   = __( 'license.txt still exists and should be deleted.', $this->plugin_name );
			$configurations['license'] = array( 'status' => 'nok', 'text' => $comment );
			$all_good                  = false;
		else:
			$comment                   = __( 'license.txt has been removed.', $this->plugin_name );
			$configurations['license'] = array( 'status' => 'ok', 'text' => $comment );
			$score ++;
		endif;
		$total ++;
		
		if ( is_file( ABSPATH . 'readme.html' ) ) :
			$comment                  = __( 'readme.html still exists and should be deleted.', $this->plugin_name );
			$configurations['readme'] = array( 'status' => 'nok', 'text' => $comment );
			$all_good                 = false;
		else:
			$comment                  = __( 'readme.html has been removed.', $this->plugin_name );
			$configurations['readme'] = array( 'status' => 'ok', 'text' => $comment );
			$score ++;
		endif;
		$total ++;
		
		// Comments closed ?
		if ( 'open' === get_option( 'default_comment_status' ) ) :
			$comment                    = __( 'The comments are opened.', $this->plugin_name );
			$configurations['comments'] = array( 'status' => 'nok', 'text' => $comment );
			$all_good                   = false;
		else:
			$comment                    = __( 'The comments are closed.', $this->plugin_name );
			$configurations['comments'] = array( 'status' => 'ok', 'text' => $comment );
			$score ++;
		endif;
		$total ++;
		
		// Track back closed ?
		if ( 1 == get_option( 'use_trackback' ) ) :
			$comment                     = __( 'Trackback is not disable.', $this->plugin_name );
			$configurations['trackback'] = array( 'status' => 'nok', 'text' => $comment );
			$all_good                    = false;
		else:
			$comment                     = __( 'Trackback is disable.', $this->plugin_name );
			$configurations['trackback'] = array( 'status' => 'ok', 'text' => $comment );
			$score ++;
		endif;
		$total ++;
		
		// Pingback closed ?
		if ( 'open' === get_option( 'default_ping_status' ) ) :
			$comment                    = __( 'The ping back are opened.', $this->plugin_name );
			$configurations['pingback'] = array( 'status' => 'nok', 'text' => $comment );
			$all_good                   = false;
		else:
			$comment                    = __( 'The ping back are closed.', $this->plugin_name );
			$configurations['pingback'] = array( 'status' => 'ok', 'text' => $comment );
			$score ++;
		endif;
		$total ++;
		
		// Block search engine ?
		$search_text = array(
			0 => __( 'Search engines can\'t crawl your website.', $this->plugin_name ),
			1 => __( 'Search engines can crawl your website.', $this->plugin_name )
		);
		
		if ( get_option( 'blog_public' ) != $this->hdf_default_config[ $env ]['blog_public'] ) :
			$comment                         = $search_text[ get_option( 'blog_public' ) ];
			$configurations['search_engine'] = array( 'status' => 'nok', 'text' => $comment );
		else:
			$comment                         = $search_text[ get_option( 'blog_public' ) ];
			$configurations['search_engine'] = array( 'status' => 'ok', 'text' => $comment );
			$score ++;
		endif;
		$total ++;
		
		// don't show avatar for users?
		if ( 1 == get_option( 'show_avatars' ) ) :
			$comment                  = __( 'Gravatar is not disable.', $this->plugin_name );
			$configurations['avatar'] = array( 'status' => 'nok', 'text' => $comment );
			$all_good                 = false;
		else:
			$comment                  = __( 'Gravatar is disable.', $this->plugin_name );
			$configurations['avatar'] = array( 'status' => 'ok', 'text' => $comment );
			$score ++;
		endif;
		$total ++;
		
		// Good timezone ? Europe/Paris for the most of the site
		$timezone_string = get_option( 'timezone_string' );
		if ( 'Europe/Paris' != $timezone_string ) :
			$comment                    = sprintf( __( 'The timezone is not set to Europe/Paris (currently set to %s).', $this->plugin_name ), $timezone_string );
			$configurations['timezone'] = array( 'status' => 'nok', 'text' => $comment );
			$all_good                   = false;
		else:
			$comment                    = __( 'The timezone is set to Europe/Paris.', $this->plugin_name );
			$configurations['timezone'] = array( 'status' => 'ok', 'text' => $comment );
			$score ++;
		endif;
		$total ++;
		
		// wp-content has been renamed?
		if ( is_dir( ABSPATH . 'wp-content' ) ) :
			$comment                      = __( 'The wp-content directory still exists and should be renamed.', $this->plugin_name );
			$configurations['wp_content'] = array( 'status' => 'nok', 'text' => $comment );
			$all_good                     = false;
		else:
			$comment                      = __( 'The wp-content directory has been removed.', $this->plugin_name );
			$configurations['wp_content'] = array( 'status' => 'ok', 'text' => $comment );
			$score ++;
		endif;
		$total ++;
		
		// acf-json exists for ACF sync
		if ( is_dir( get_template_directory() . '/acf-json/' ) ) :
			$comment                    = __( 'The acf-json directory exists in the theme folder.', $this->plugin_name );
			$configurations['acf_json'] = array( 'status' => 'ok', 'text' => $comment );
			$score ++;
		else:
			$comment                    = __( 'The acf-json directory does not exist in the theme folder. You must create it to save your ACF fields to the Git repository.', $this->plugin_name );
			$configurations['acf_json'] = array( 'status' => 'nok', 'text' => $comment );
			$all_good                   = false;
		endif;
		$total ++;
		
		// file security.txt exists
		$snippet_exemple = '# If you would like to report a security issue<br/>
# you may report it to us on HackerOne.<br/>
Canonical: https://www.example.com/.well-known/security.txt<br/>
Contact: https://hackerone.com/ed<br/>
Encryption: https://keybase.pub/edoverflow/pgp_key.asc<br/>
Policy: https://example.com/disclosure-policy.html<br/>
Acknowledgements: https://hackerone.com/ed/thanks<br/>
Preferred-Languages: en, es, fr<br/>
Hiring: https://example.com/jobs.html';
		
		if ( is_dir( ABSPATH . '.well-known' ) ):
			if ( is_file( ABSPATH . '.well-known/security.txt' ) ):
				require_once( dirname( dirname( __FILE__ ) ) . '/libs/SecurityParser.php' );
				$raw    = file_get_contents( ABSPATH . '.well-known/security.txt' );
				$sectxt = new \SecurityTxt\Parser( $raw );
				
				if ( count( $sectxt->errors() ) > 0 ):
					$comment = __( 'The security.txt exists in the .well-known folder, but the content is not good.', $this->plugin_name );
					
					foreach ( $sectxt->errors() as $error ) :
						$comment .= '<br/>' . "Error: {$error}";
					endforeach;
					
					$comment .= '<br/>' . __( 'Exemple of valid security.txt:', $this->plugin_name );
					
					$configurations['security_txt'] = array( 'status' => 'nok', 'text' => $comment, 'snippet' => $snippet_exemple );
					$all_good                       = false;
				else:
					$comment                        = __( 'The security.txt exists in the .well-known folder, and the content is good.', $this->plugin_name );
					$configurations['security_txt'] = array( 'status' => 'ok', 'text' => $comment );
					$score ++;
				endif;
			else:
				$comment                        = __( 'The security.txt does not exist in the .well-known folder.', $this->plugin_name );
				$comment                        .= '<br/>' . __( 'Exemple of valid security.txt:', $this->plugin_name );
				$configurations['security_txt'] = array( 'status' => 'nok', 'text' => $comment, 'snippet' => $snippet_exemple );
				$all_good                       = false;
			endif;
		else:
			$comment                        = __( 'The .well-known folder does not exist and must contains security.txt.', $this->plugin_name );
			$comment                        .= '<br/>' . __( 'Exemple of valid security.txt:', $this->plugin_name );
			$configurations['security_txt'] = array( 'status' => 'nok', 'text' => $comment, 'snippet' => $snippet_exemple );
			$all_good                       = false;
		endif;
		$total ++;
		
		$checks['configurations'] = array(
			'title'         => __( 'Configuration', $this->plugin_name ),
			'icon'          => '<span class="dashicons dashicons-admin-generic"></span>',
			'score'         => $score . '/' . $total,
			'global_status' => $all_good,
			'content'       => $configurations,
		);
		
		// Users Section
		// admin with id != 1 and login != admin ?
		$all_good    = true;
		$score       = 0;
		$total       = 0;
		$args        = array( 'role' => 'administrator' );
		$admins      = get_users( $args );
		$users_datas = array();
		
		foreach ( $admins as $admin ) {
			$user_data = array();
			
			if ( ( 1 == $admin->ID ) || ( 'admin' === $admin->user_login ) ) :
				$comment               = sprintf( __( '%s (ID : %s - %s) is administrator.', $this->plugin_name ), $admin->user_login, $admin->ID, $admin->user_email );
				$users[]               = array( 'status' => 'nok', 'text' => $comment );
				$all_good              = false;
				$user_data['status']   = 'nok';
				$user_data['comments'] = $comment;
			else:
				$comment               = sprintf( __( '%s (ID : %s - %s) is administrator.', $this->plugin_name ), $admin->user_login, $admin->ID, $admin->user_email );
				$users[]               = array( 'status' => 'ok', 'text' => $comment );
				$user_data['status']   = 'ok';
				$user_data['comments'] = $comment;
				$score ++;
			endif;
			$total ++;
			
			$users_datas[] = $user_data;
		}
		
		$checks['users'] = array(
			'title'         => __( 'Users', $this->plugin_name ),
			'icon'          => '<span class="dashicons dashicons-groups"></span>',
			'score'         => $score . '/' . $total,
			'global_status' => $all_good,
			'notice'        => __( 'Admin user should not have ID = 1 or login = "admin".', $this->plugin_name ),
			'content'       => $users,
		);
		
		// Default Content Section
		// page / article / comment cleaned ?
		$all_good = true;
		$score    = 0;
		$total    = 0;
		
		// default article removed ?
		$default_article = get_post( 1 );
		
		if ( ! is_null( $default_article ) ) :
			$comment                            = __( 'The default article still exists (empty trash if needed).', $this->plugin_name );
			$default_content['default_article'] = array( 'status' => 'nok', 'text' => $comment );
			$all_good                           = false;
		else:
			$comment                            = __( 'The default article has been removed.', $this->plugin_name );
			$default_content['default_article'] = array( 'status' => 'ok', 'text' => $comment );
			$score ++;
		endif;
		$total ++;
		
		// default page removed ?
		$default_page = get_post( 2 );
		
		if ( ! is_null( $default_page ) ) :
			$comment                         = __( 'The default page still exists (empty trash if needed).', $this->plugin_name );
			$default_content['default_page'] = array( 'status' => 'nok', 'text' => $comment );
			$all_good                        = false;
		else:
			$comment                         = __( 'The default page has been removed.', $this->plugin_name );
			$default_content['default_page'] = array( 'status' => 'ok', 'text' => $comment );
			$score ++;
		endif;
		$total ++;
		
		// default comment removed ?
		$default_comment_id = 1;
		$default_comment    = get_comment( $default_comment_id );
		
		if ( ! is_null( $default_comment ) ) :
			$comment                            = __( 'The default comment still exists (empty trash if needed).', $this->plugin_name );
			$default_content['default_comment'] = array( 'status' => 'nok', 'text' => $comment );
			$all_good                           = false;
		else:
			$comment                            = __( 'The default comment has been removed.', $this->plugin_name );
			$default_content['default_comment'] = array( 'status' => 'ok', 'text' => $comment );
			$score ++;
		endif;
		$total ++;
		
		$checks['default_content'] = array(
			'title'         => __( 'Default Content', $this->plugin_name ),
			'icon'          => '<span class="dashicons dashicons-star-empty"></span>',
			'score'         => $score . '/' . $total,
			'global_status' => $all_good,
			'content'       => $default_content,
		);
		
		$url         = admin_url() . 'tools.php?page=df-guidelines-checker&env=' . $switch_to[ $env ];
		$escaped_url = htmlspecialchars( $url, ENT_QUOTES, 'UTF-8' );
		
		include_once 'partials/df_guidelines_checker-admin-display.php';
	}
	
}

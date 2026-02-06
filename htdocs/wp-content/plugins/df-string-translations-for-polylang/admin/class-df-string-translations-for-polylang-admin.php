<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.havasdigitalfactory.com/
 * @since      1.0.0
 *
 * @package    Df_String_Translations_For_Polylang
 * @subpackage Df_String_Translations_For_Polylang/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Df_String_Translations_For_Polylang
 * @subpackage Df_String_Translations_For_Polylang/admin
 * @author     Sébastien GASTARD <sebastien.gastard@havasdigitalfactory.com>
 */
class Df_String_Translations_For_Polylang_Admin {
	
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
	 * Notice(s)
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array $notice Notice(s)
	 */
	private $notices;
	
	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version The version of this plugin.
	 *
	 * @since    1.0.0
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
		 * defined in Df_String_Translations_For_Polylang_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Df_String_Translations_For_Polylang_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/df-string-translations-for-polylang-admin.css', array(), $this->version, 'all' );
		
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
		 * defined in Df_String_Translations_For_Polylang_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Df_String_Translations_For_Polylang_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/df-string-translations-for-polylang-admin.js', array( 'jquery' ), $this->version, false );
		
	}
	
	/**
	 * Add settings page
	 *
	 * @since    1.0.0
	 */
	public function menu_settings() {
		add_submenu_page(
			'mlang',
			__( 'Settings', 'df-string-translations-for-polylang' ) . ' :: ' . __( 'String Translations', 'df-string-translations-for-polylang' ),
			__( 'String Translations Settings', 'df-string-translations-for-polylang' ),
			'manage_options',
			'df-string-translations-for-polylang-settings',
			array( $this, 'display_page_settings' )
		);
		
	}
	
	/**
	 * Save settings
	 *
	 * @since    1.0.0
	 */
	public function save_settings() {
		if ( isset( $_POST['nonce_settings_df-string-translations-for-polylang'] ) && wp_verify_nonce( $_POST['nonce_settings_df-string-translations-for-polylang'], 'save_settings_df-string-translations-for-polylang' ) ):
			// save settings
			$scan_themes            = array();
			$scan_plugins           = array();
			$current_active_plugins = $this->get_active_plugins();
			
			if ( isset( $_POST['dfstfp_settings'] ) && ! empty( $_POST['dfstfp_settings']['scan_themes'] ) ):
				$scan_themes = $_POST['dfstfp_settings']['scan_themes'];
			endif;
			
			if ( isset( $_POST['dfstfp_settings'] ) && ! empty( $_POST['dfstfp_settings']['scan_plugins'] ) ):
				$scan_plugins = $_POST['dfstfp_settings']['scan_plugins'];
			endif;
			
			$settings = array(
				'current_active_plugins' => $current_active_plugins,
				'scan_themes'            => $scan_themes,
				'scan_plugins'           => $scan_plugins,
			);
			
			update_option( 'DF_STRING_TRANSLATIONS_FOR_POLYLANG_SETTINGS', $settings );
		endif;
	}
	
	/**
	 * Display Notice Helper
	 *
	 * @since    1.0.0
	 */
	public function display_admin_notice() {
		
		if ( ! current_user_can( 'activate_plugins' ) ) :
			// If the user does not have the "activate_plugins" capability, do nothing.
			return;
		endif;
		
		$notices = $this->notices;
		
		if ( ! empty( $notices ) ):
			include( dirname( __FILE__ ) . '/partials/df-string-translations-for-polylang-admin-notice.php' );
		endif;
		
	}
	
	/**
	 * Settings page markup
	 *
	 * @since    1.0.0
	 */
	public function display_page_settings() {
		if ( ! current_user_can( 'manage_options' ) ):
			return;
		endif;
		// check if there is previous settings
		$settings       = get_option( 'DF_STRING_TRANSLATIONS_FOR_POLYLANG_SETTINGS' );
		$active_plugins = $this->get_active_plugins();
		
		include( dirname( __FILE__ ) . '/partials/df-string-translations-for-polylang-admin-settings.php' );
		
	}
	
	/**
	 * Perform checks and update notices if needed
	 *
	 * @since    1.0.0
	 */
	public function check_config() {
		$required_plugins = array(
			'Polylang' => array(
				'Polylang'     => 'polylang/polylang.php',
				'Polylang Pro' => 'polylang-pro/polylang.php',
			),
		);
		
		$missing_plugins = array();
		
		foreach ( $required_plugins as $plugin_name => $main_file_path ) :
			if ( is_array( $main_file_path ) ):
				// at least one version must be activated
				$found                = false;
				$temp_missing_plugins = array();
				
				foreach ( $main_file_path as $name => $path ):
					$temp_missing_plugins[] = $name;
					
					if ( $this->is_plugin_active( $path ) ) :
						$found = true;
					endif;
				endforeach;
				
				if ( ! $found ):
					$missing_plugins[] = implode( ' or ', $temp_missing_plugins );
				endif;
			else:
				if ( ! $this->is_plugin_active( $main_file_path ) ) :
					$missing_plugins[] = $plugin_name;
				endif;
			endif;
		endforeach;
		
		if ( count( $missing_plugins ) > 0 ):
			$this->notices[] = '<strong>' . __( 'Error:', 'df-string-translations-for-polylang' ) . '</strong> ' . sprintf( __( 'The <em>Digital Factory String Translations for Polylang</em> plugin won\'t execute because the following required plugins are not active: %s. Please install and/or activate these plugins.', 'df-string-translations-for-polylang' ), implode( ', ', $missing_plugins ) );
		endif;
		
		$settings = get_option( 'DF_STRING_TRANSLATIONS_FOR_POLYLANG_SETTINGS' );
		
		if ( empty( $settings ) ):
			// no settings, the plugin must be configured
			$this->notices[] = '<strong>' . __( 'Error:', 'df-string-translations-for-polylang' ) . '</strong> ' . sprintf( __( 'The <em>Digital Factory String Translations for Polylang</em> plugin must be configured. Please <a href="%s">check the settings</a>.', 'df-string-translations-for-polylang' ), admin_url( 'admin.php?page=df-string-translations-for-polylang-settings' ) );
		else:
			// check if a new plugin has been activated or deactivated
			if ( ! $this->compare_plugins_active( $settings['current_active_plugins'] ) ):
				$this->notices[] = '<strong>' . __( 'Warning:', 'df-string-translations-for-polylang' ) . '</strong> ' . sprintf( __( 'The <em>Digital Factory String Translations for Polylang</em> plugin settings must be updated, 1 or more plugins have been activated/deactivated. Please <a href="%s">check the settings</a>.', 'df-string-translations-for-polylang' ), admin_url( 'admin.php?page=df-string-translations-for-polylang-settings' ) );
			endif;
		endif;
	}
	
	/**
	 * Scan strings
	 *
	 * @since    1.0.0
	 */
	public function scan_strings() {
		if ( isset( $_GET['df-scan-string-translations'] ) && wp_verify_nonce( $_GET['df-scan-string-translations'], 'scan-string-translations' ) ):
			$settings = get_option( 'DF_STRING_TRANSLATIONS_FOR_POLYLANG_SETTINGS' );
			
			if ( isset( $settings['scan_themes'] ) && ( count( $settings['scan_themes'] ) > 0 ) ):
				$this->notices[] = $this->scan_strings_themes( $settings['scan_themes'] );
			else:
				$this->notices[] = __( 'No theme is set.', 'df-string-translations-for-polylang' );
			endif;
			
			if ( isset( $settings['scan_plugins'] ) && ( count( $settings['scan_plugins'] ) > 0 ) ):
				$this->notices[] = $this->scan_strings_plugins( $settings['scan_plugins'] );
			else:
				$this->notices[] = __( 'No plugin is set.', 'df-string-translations-for-polylang' );
			endif;
		endif;
	}
	
	/**
	 * Scan themes
	 *
	 * @param $themes
	 *
	 * @return string
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function scan_strings_themes( $themes ) {
		$msg        = array();
		$theme_path = realpath( WP_CONTENT_DIR ) . '/themes';
		
		foreach ( $themes as $theme_slug ) :
			$data = array(
				'name'    => $theme_slug,
				'strings' => array(),
			);
			
			if ( file_exists( $theme_path . '/' . $theme_slug ) ) :
				$data['strings'] = $this->files_parse( $theme_path . '/' . $theme_slug );
				
				if ( count( $data['strings'] ) > 0 ):
					update_option( 'DF_STRING_TRANSLATIONS_FOR_POLYLANG_DATA_THEME_' . $theme_slug, $data );
					$msg[] = sprintf( __( 'Theme %s : %d strings scanned.', 'df-string-translations-for-polylang' ), $theme_slug, count( $data['strings'] ) );
				else:
					$msg[] = sprintf( __( 'Theme %s : no string scanned.', 'df-string-translations-for-polylang' ), $theme_slug );
				endif;
			endif;
		endforeach;
		
		return implode( '<br />', $msg );
	}
	
	/**
	 * Scan plugins
	 *
	 * @param $plugins
	 *
	 * @return string
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function scan_strings_plugins( $plugins ) {
		$msg         = array();
		$plugin_path = realpath( WP_CONTENT_DIR ) . '/plugins';
		
		foreach ( $plugins as $plugin_slug ) :
			$data = array(
				'name'    => $plugin_slug,
				'strings' => array(),
			);
			
			if ( file_exists( $plugin_path . '/' . $plugin_slug ) ) :
				$data['strings'] = $this->files_parse( $plugin_path . '/' . $plugin_slug );
				
				if ( count( $data['strings'] ) > 0 ):
					update_option( 'DF_STRING_TRANSLATIONS_FOR_POLYLANG_DATA_PLUGIN_' . $plugin_slug, $data );
					$msg[] = sprintf( __( 'Plugin %s : %d strings scanned.', 'df-string-translations-for-polylang' ), $plugin_slug, count( $data['strings'] ) );
				else:
					$msg[] = sprintf( __( 'Plugin %s : no string scanned.', 'df-string-translations-for-polylang' ), $plugin_slug );
				endif;
			endif;
		endforeach;
		
		return implode( '<br />', $msg );
	}
	
	/**
	 * Parse files to extract strings
	 *
	 * @param $dir
	 *
	 * @return array
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function files_parse( $dir ) {
		$strings = array();
		$files   = $this->recursive_get_files( $dir );
		
		if ( is_array( $files ) && count( $files ) ) :
			foreach ( $files as $v ) :
				if ( preg_match( "/\/.*?\.(php[0-9]?|inc)$/uis", $v ) ) :
					$content_to_parse = file_get_contents( $v );
					$pattern          = '/[\s]+(?:pll__|pll_e|pll_translate_string|esc_html__|esc_html_e|esc_attr__|esc_attr_e|__|_e|_x|_ex|\$this->get_translated_string)[\s]*\([\s]*[\\\'\"](.*?)[\\\'\"][\s]*[\),]/uis';
					preg_match_all( $pattern, $content_to_parse, $m );
					
					if ( is_array( $m ) && isset( $m[1] ) && count( $m[1] ) ) :
						foreach ( $m[1] as $mv ) :
							if ( ! in_array( $mv, $strings ) ) :
								$strings[] = $mv;
							endif;
						endforeach;
					endif;
				endif;
			endforeach;
		endif;
		
		return $strings;
	}
	
	/**
	 * Get files recursively
	 *
	 * @param $dir
	 *
	 * @return array
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function recursive_get_files( $dir ) {
		$files = array();
		
		if ( $h = opendir( $dir ) ) :
			while ( ( $item = readdir( $h ) ) !== false ) :
				$f = $dir . '/' . $item;
				
				if ( is_file( $f ) && filesize( $f ) <= 2097152 ) :
					$files[] = $f;
				elseif ( is_dir( $f ) && ! preg_match( "/^[\.]{1,2}$/uis", $item ) ) :
					$files = array_merge( $files, $this->recursive_get_files( $f ) );
				endif;
			endwhile;
			
			closedir( $h );
		endif;
		
		return $files;
	}
	
	/**
	 * Check if a plugin is active
	 *
	 * @param $main_file_path
	 *
	 * @return bool
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function is_plugin_active( $main_file_path ) {
		return in_array( $main_file_path, $this->get_active_plugins() );
	}
	
	/**
	 * Returns an array of active plugins' main files.
	 *
	 * @return mixed|void
	 *
	 * @since    1.0.0
	 * @modified 1.1.1 : merge plugins and sitewide plugins
	 * @access   private
	 */
	private function get_active_plugins() {
		if ( is_multisite() ):
			$sitewide_plugins_active = array();
			$sitewide_plugins        = get_site_option( 'active_sitewide_plugins' );
			
			foreach ( $sitewide_plugins as $path => $timestamp ):
				$sitewide_plugins_active[] = $path;
			endforeach;
			
			return apply_filters( 'active_plugins', array_merge( get_option( 'active_plugins' ), $sitewide_plugins_active ) );
		else:
			return apply_filters( 'active_plugins', get_option( 'active_plugins' ) );
		endif;
	}
	
	/**
	 * Returns true if plugins active = settings
	 *
	 * @param $settings_active_plugins
	 *
	 * @return bool
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function compare_plugins_active( $settings_active_plugins ) {
		$current_active_plugins = $this->get_active_plugins();
		
		foreach ( $settings_active_plugins as $id => $plugin ):
			if ( ! in_array( $plugin, $current_active_plugins ) ):
				return false;
			else:
				// remove found plugins to check if there is new one
				$current_active_plugins = array_diff( $current_active_plugins, array( $plugin ) );
			endif;
		endforeach;
		
		if ( count( $current_active_plugins ) > 0 ):
			return false;
		endif;
		
		return true;
	}
}

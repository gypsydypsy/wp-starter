<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.havasdigitalfactory.com/
 * @since      1.0.0
 *
 * @package    Df_String_Translations_For_Polylang
 * @subpackage Df_String_Translations_For_Polylang/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Df_String_Translations_For_Polylang
 * @subpackage Df_String_Translations_For_Polylang/public
 * @author     Sébastien GASTARD <sebastien.gastard@havasdigitalfactory.com>
 */
class Df_String_Translations_For_Polylang_Public {
	
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
		 * defined in Df_String_Translations_For_Polylang_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Df_String_Translations_For_Polylang_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/df-string-translations-for-polylang-public.css', array(), $this->version, 'all' );
		
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
		 * defined in Df_String_Translations_For_Polylang_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Df_String_Translations_For_Polylang_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/df-string-translations-for-polylang-public.js', array( 'jquery' ), $this->version, false );
		
	}
	
	public function pll_register_string_themes() {
		if ( function_exists( 'pll_register_string' ) ):
			$settings = get_option( 'DF_STRING_TRANSLATIONS_FOR_POLYLANG_SETTINGS' );
			
			if ( isset( $settings['scan_themes'] ) && ( count( $settings['scan_themes'] ) > 0 ) ):
				foreach ( $settings['scan_themes'] as $theme_slug ) :
					$data = get_option( 'DF_STRING_TRANSLATIONS_FOR_POLYLANG_DATA_THEME_' . $theme_slug );
					
					if ( is_array( $data ) && is_array( $data['strings'] ) && count( $data['strings'] ) ) :
						foreach ( $data['strings'] as $v ) :
							pll_register_string( stripslashes( $v ), stripslashes( $v ), sprintf( __( 'Theme %s', 'df-string-translations-for-polylang' ), $data['name'] ) );
						endforeach;
					endif;
				endforeach;
			endif;
		endif;
	}
	
	public function pll_register_string_plugins() {
		if ( function_exists( 'pll_register_string' ) ):
			$settings = get_option( 'DF_STRING_TRANSLATIONS_FOR_POLYLANG_SETTINGS' );
			
			if ( isset( $settings['scan_plugins'] ) && ( count( $settings['scan_plugins'] ) > 0 ) ):
				foreach ( $settings['scan_plugins'] as $plugin_slug ) :
					$data = get_option( 'DF_STRING_TRANSLATIONS_FOR_POLYLANG_DATA_PLUGIN_' . $plugin_slug );
					
					if ( is_array( $data ) && is_array( $data['strings'] ) && count( $data['strings'] ) ) :
						foreach ( $data['strings'] as $v ) :
							pll_register_string( stripslashes( $v ), stripslashes( $v ), sprintf( __( 'Plugin %s', 'df-string-translations-for-polylang' ), $data['name'] ) );
						endforeach;
					endif;
				endforeach;
			endif;
		endif;
	}
	
	/**
	 * Allow use of WordPress native functions for translate
	 *
	 * @param $t_text
	 * @param $ut_text
	 * @param $domain
	 *
	 * @return mixed|string|void
	 *
	 * @since    1.0.0
	 * @fix    1.0.2
	 */
	public function string_filter( $t_text, $ut_text, $domain ) {
		if ( $domain != 'pll_string' ) :
			$t_text = __( $t_text, 'pll_string' );
		endif;
		
		return $t_text;
	}
	
	/**
	 * Allow use of WordPress native functions with context for translate
	 *
	 * @param $translated
	 * @param $text
	 * @param $context
	 * @param $domain
	 *
	 * @return mixed|string|void
	 *
	 * @since    1.0.0
	 * @fix    1.0.2
	 */
	public function string_filter_with_context( $translated, $text, $context, $domain ) {
		if ( $domain != 'pll_string' ) :
			$translated = __( $translated, 'pll_string' );
		endif;
		
		return $translated;
	}
	
}

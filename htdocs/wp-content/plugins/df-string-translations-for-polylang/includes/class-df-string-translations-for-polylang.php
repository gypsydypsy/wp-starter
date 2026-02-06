<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.havasdigitalfactory.com/
 * @since      1.0.0
 *
 * @package    Df_String_Translations_For_Polylang
 * @subpackage Df_String_Translations_For_Polylang/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Df_String_Translations_For_Polylang
 * @subpackage Df_String_Translations_For_Polylang/includes
 * @author     Sébastien GASTARD <sebastien.gastard@havasdigitalfactory.com>
 */
class Df_String_Translations_For_Polylang {
	
	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Df_String_Translations_For_Polylang_Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;
	
	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $plugin_name The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;
	
	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $version The current version of the plugin.
	 */
	protected $version;
	
	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'DF_STRING_TRANSLATIONS_FOR_POLYLANG_VERSION' ) ) {
			$this->version = DF_STRING_TRANSLATIONS_FOR_POLYLANG_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'df-string-translations-for-polylang';
		
		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		
	}
	
	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Df_String_Translations_For_Polylang_Loader. Orchestrates the hooks of the plugin.
	 * - Df_String_Translations_For_Polylang_i18n. Defines internationalization functionality.
	 * - Df_String_Translations_For_Polylang_Admin. Defines all hooks for the admin area.
	 * - Df_String_Translations_For_Polylang_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {
		
		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-df-string-translations-for-polylang-loader.php';
		
		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-df-string-translations-for-polylang-i18n.php';
		
		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-df-string-translations-for-polylang-admin.php';
		
		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-df-string-translations-for-polylang-public.php';
		
		$this->loader = new Df_String_Translations_For_Polylang_Loader();
		
	}
	
	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Df_String_Translations_For_Polylang_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {
		
		$plugin_i18n = new Df_String_Translations_For_Polylang_i18n();
		
		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
		
	}
	
	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		
		$plugin_admin = new Df_String_Translations_For_Polylang_Admin( $this->get_plugin_name(), $this->get_version() );
		
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'menu_settings', 99 );
		$this->loader->add_action( 'init', $plugin_admin, 'save_settings' );
		$this->loader->add_action( 'init', $plugin_admin, 'scan_strings', 99 );
		$this->loader->add_action( 'init', $plugin_admin, 'check_config', 99 );
		$this->loader->add_action( 'admin_notices', $plugin_admin, 'display_admin_notice' );
		
	}
	
	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {
		
		$plugin_public = new Df_String_Translations_For_Polylang_Public( $this->get_plugin_name(), $this->get_version() );
		
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'init', $plugin_public, 'pll_register_string_themes', 99 );
		$this->loader->add_action( 'init', $plugin_public, 'pll_register_string_plugins', 99 );
		
		if ( ! is_admin() ):
			$this->loader->add_filter( 'gettext', $plugin_public, 'string_filter', 20, 3 );
			$this->loader->add_filter( 'gettext_with_context', $plugin_public, 'string_filter_with_context', 20, 4 );
		endif;
		
	}
	
	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}
	
	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @return    string    The name of the plugin.
	 * @since     1.0.0
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}
	
	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @return    Df_String_Translations_For_Polylang_Loader    Orchestrates the hooks of the plugin.
	 * @since     1.0.0
	 */
	public function get_loader() {
		return $this->loader;
	}
	
	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @return    string    The version number of the plugin.
	 * @since     1.0.0
	 */
	public function get_version() {
		return $this->version;
	}
	
}

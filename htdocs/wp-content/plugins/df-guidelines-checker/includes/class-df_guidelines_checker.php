<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://www.havasdigitalfactory.com/
 * @since      1.0.0
 *
 * @package    Df_guidelines_checker
 * @subpackage Df_guidelines_checker/includes
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
 * @package    Df_guidelines_checker
 * @subpackage Df_guidelines_checker/includes
 * @author     Sébastien GASTARD <sebastien.gastard@havasdigitalfactory.com>
 */
class Df_guidelines_checker {
	
	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Df_guidelines_checker_Loader $loader Maintains and registers all hooks for the plugin.
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
		
		$this->plugin_name = 'df_guidelines_checker';
		$this->version     = '1.7.1';
		
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
	 * - Df_guidelines_checker_Loader. Orchestrates the hooks of the plugin.
	 * - Df_guidelines_checker_i18n. Defines internationalization functionality.
	 * - Df_guidelines_checker_Admin. Defines all hooks for the admin area.
	 * - Df_guidelines_checker_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-df_guidelines_checker-loader.php';
		
		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-df_guidelines_checker-i18n.php';
		
		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-df_guidelines_checker-admin.php';
		
		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-df_guidelines_checker-public.php';
		
		/**
		 * The class used for PDF generation
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-audit_tcpdf.php';
		
		$this->loader = new Df_guidelines_checker_Loader();
		
	}
	
	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Df_guidelines_checker_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {
		
		$plugin_i18n = new Df_guidelines_checker_i18n();
		
		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
		
	}
	
	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @modified    1.7.0 add filter style loader tag
	 * @access   private
	 */
	private function define_admin_hooks() {
		
		$plugin_admin = new Df_guidelines_checker_Admin( $this->get_plugin_name(), $this->get_version() );
		// load css/js only when displaying the checker
		if ( ! empty( $_GET['page'] ) && ( 'df-guidelines-checker' === $_GET['page'] ) ):
			$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles', 1 );
			$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
			$this->loader->add_filter( 'style_loader_tag', $plugin_admin, 'add_custom_attributes', 10, 2 );
			$this->loader->add_filter( 'script_loader_tag', $plugin_admin, 'add_custom_attributes', 10, 2 );
		endif;
		
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_submenu_page_checker' );
		
		$this->loader->add_action( 'rest_api_init', $plugin_admin, 'add_ajax_endpoints' );
		
	}
	
	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {
		
		$plugin_public = new Df_guidelines_checker_Public( $this->get_plugin_name(), $this->get_version() );
		
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		
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
	 * @return    Df_guidelines_checker_Loader    Orchestrates the hooks of the plugin.
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

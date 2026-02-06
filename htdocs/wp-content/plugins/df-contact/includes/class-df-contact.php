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
 * @package    Df_Contact
 * @subpackage Df_Contact/includes
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
 * @package    Df_Contact
 * @subpackage Df_Contact/includes
 * @author     Sébastien GASTARD <sebastien.gastard@havasdigitalfactory.com>
 */
class Df_Contact {
	
	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Df_Contact_Loader $loader Maintains and registers all hooks for the plugin.
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
		if ( defined( 'DF_CONTACT_VERSION' ) ) {
			$this->version = DF_CONTACT_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'df-contact';
		
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
	 * - Df_Contact_Loader. Orchestrates the hooks of the plugin.
	 * - Df_Contact_i18n. Defines internationalization functionality.
	 * - Df_Contact_Admin. Defines all hooks for the admin area.
	 * - Df_Contact_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-df-contact-loader.php';
		
		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-df-contact-i18n.php';
		
		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-df-contact-admin.php';
		
		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-df-contact-public.php';
		
		$this->loader = new Df_Contact_Loader();
		
	}
	
	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Df_Contact_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {
		
		$plugin_i18n = new Df_Contact_i18n();
		
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
		
		$plugin_admin = new Df_Contact_Admin( $this->get_plugin_name(), $this->get_version() );
		
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'wpmu_new_blog', $plugin_admin, 'create_blog', 10, 6 );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'menu_manage_contacts' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'menu_option_contacts' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'register_option_contacts' );
		$this->loader->add_action( 'admin_notices', $plugin_admin, 'check_initial_settings' );
		$this->loader->add_action( 'init', $plugin_admin, 'check_crypt_key_contact_exists' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'export_contacts' );
		
	}
	
	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {
		
		$plugin_public = new Df_Contact_Public( $this->get_plugin_name(), $this->get_version() );
		
		$this->loader->add_action( 'plugins_loaded', $plugin_public, 'update_db_check' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'rest_api_init', $plugin_public, 'add_ajax_endpoints' );
		$this->loader->add_action( 'df_clean_old_contacts', $plugin_public, 'cron_clean_old_contacts' );
		
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
	 * @return    Df_Contact_Loader    Orchestrates the hooks of the plugin.
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

<?php
/**
 * Havas Starter Pack functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Havas_Starter_Pack
 */

if ( ! defined( 'HSP_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( 'HSP_VERSION', '2.1.0' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
if ( ! function_exists( 'hsp_setup' ) ) :
	function hsp_setup() {
		/**
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Havas Starter Pack, use a find and replace
		 * to change 'havas_starter_pack' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'havas_starter_pack', get_template_directory() . '/languages' );

		/**
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/**
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in two locations.
		register_nav_menus(
			array(
				'menu-navigation' => esc_html__( 'Main menu', 'havas_starter_pack' ),
				'menu-footer'     => esc_html__( 'Footer menu', 'havas_starter_pack' ),
			)
		);

		/**
		 * Set alt/caption/legend image automatically
		 */
		add_action( 'add_attachment', 'hsp_set_image_meta_upon_image_upload' );

	}
endif;

add_action( 'after_setup_theme', 'hsp_setup' );

if ( ! function_exists( 'hsp_acf_init' ) ) :
	function hsp_acf_init() {
		if ( function_exists( 'acf_add_options_page' ) ):
			/**
			 * Add ACF option page
			 */
			acf_add_options_page( array(
				'page_title' => __( 'Theme Settings', 'havas_starter_pack' ),
				'menu_title' => __( 'Theme Settings', 'havas_starter_pack' ),
				'menu_slug'  => 'theme-general-settings',
				'capability' => 'edit_posts',
				'redirect'   => false,
			) );
		endif;
	}
endif;

add_action( 'acf/init', 'hsp_acf_init' );

/**
 * Enqueue scripts and styles.
 */
if ( ! function_exists( 'hsp_scripts' ) ):
	function hsp_scripts() {
		if ( WP_DEBUG && get_field( 'jira_is_display_issue_collector', 'option' ) ):
			wp_enqueue_script( 'jquery' );
		endif;
		wp_enqueue_script( 'choices-select', 'https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js', array(), false, true );
		wp_enqueue_script( 'main-js', get_template_directory_uri() . '/dist/app.js', array(), false, true );

		wp_enqueue_style( 'main-style', get_template_directory_uri() . '/dist/app.css', array(), false );
	}
endif;

add_action( 'wp_enqueue_scripts', 'hsp_scripts' );

// Disable Gutenberg
add_filter( 'use_block_editor_for_post_type', '__return_false' );
add_filter( 'use_widgets_block_editor', '__return_false' );
// Don't load Gutenberg-related stylesheets.
if ( ! function_exists( 'hsp_remove_block_css' ) ):
	function hsp_remove_block_css() {
		wp_dequeue_style( 'wp-block-library' ); // Wordpress core
		wp_dequeue_style( 'wp-block-library-theme' ); // Wordpress core
		wp_dequeue_style( 'wc-block-style' ); // WooCommerce
		wp_dequeue_style( 'storefront-gutenberg-blocks' ); // Storefront theme
	}
endif;

add_action( 'wp_enqueue_scripts', 'hsp_remove_block_css', 100 );

/**
 * Custom Post News
 */
require get_template_directory() . '/inc/custom_post_news.php';

/**
 * Custom Post Profile
 */
require get_template_directory() . '/inc/custom_post_profile.php';

/**
 * Custom Post Communique Presse
 */
require get_template_directory() . '/inc/custom_post_press_release.php';

/**
 * Utilities function
 */
require_once get_template_directory() . '/inc/extra-utils.php';

/**
 * Custom ACF
 */
require get_template_directory() . '/inc/custom_acf.php';

/**
 * Custom csp
 */
require get_template_directory() . '/inc/custom_csp.php';

/**
 * Custom images
 */
require get_template_directory() . '/inc/custom_images.php';

/**
 * Custom nav Menus
 */
require get_template_directory() . '/inc/custom_nav_menu.php';

/**
 * Custom Polylang
 */
require get_template_directory() . '/inc/custom_polylang.php';

/**
 * Custom SEOPress
 */
require get_template_directory() . '/inc/custom_seopress.php';

// Creation toolbar custom named Core Factory
/**
 * Modify the full bar in tiny mce
 */
require get_template_directory() . '/inc/custom_toolbar_tiny_mce.php';

/**
 * Custom WP admin
 */
require get_template_directory() . '/inc/custom_wp_admin.php';

/**
 * Add custom script for react / API REST
 */
if ( defined( 'HCF_SITE_MODE' ) && 'react' === HCF_SITE_MODE ):
	require_once get_template_directory() . '/inc/react.php';
endif;

if ( ! function_exists( 'add_rgpd' ) ) :
	function add_rgpd() {
		wp_enqueue_style( 'rgpd-css-hdf', get_template_directory_uri() . '/rgpd/css/tarteaucitron-HDF.css', array(), false );
		wp_enqueue_script( 'rgpd-js', get_template_directory_uri() . '/rgpd/tarteaucitron.js', array(), false, false );
		wp_enqueue_script( 'rgpd-js-hdf', get_template_directory_uri() . '/rgpd/tarteaucitronHDF.js', array(), false, false );
	}
endif;

add_action( 'wp_enqueue_scripts', 'add_rgpd' );

// force CPT init after switching theme
add_action( 'after_switch_theme', 'hsp_rewrite_flush' );

if ( ! function_exists( 'hsp_rewrite_flush' ) ):
	function hsp_rewrite_flush() {
		news_init();
		profile_init();
		press_release_init();
		flush_rewrite_rules();
	}
endif;

if ( ! function_exists( 'hsp_get_cpt_data' ) ):
	function hsp_get_cpt_data( $cpt ) {
		$item = array();

		switch ( $cpt->post_type ):
			case 'news':
				$item['permalink'] = get_permalink( $cpt->ID );
				$item['image']     = get_field( 'image', $cpt->ID );
				$item['suptitle']  = get_the_date( __( 'd F Y', 'havas_starter_pack' ), $cpt->ID );
				$item['title']     = $cpt->post_title;
				$item['excerpt']   = $cpt->post_excerpt;
				$item['category']  = get_the_terms( $cpt->ID, 'category' );
				break;

			case 'profile':
				$item['permalink'] = '';
				$item['image']     = get_field( 'photo', $cpt->ID );
				$item['suptitle']  = get_field( 'position', $cpt->ID );
				$item['title']     = $cpt->post_title;
				$item['excerpt']   = get_field( 'text', $cpt->ID );
				$item['category']  = '';
				break;

			case 'press_release':
				$press_release         = get_field( 'attached_file', $cpt->ID );
				$item['attached_file'] = ( empty( $press_release['url'] ) ) ? '' : $press_release['url'];
				$item['image']         = '';
				$item['suptitle']      = get_the_date( __( 'd F Y', 'havas_starter_pack' ), $cpt->ID );
				$item['title']         = $cpt->post_title;
				$item['excerpt']       = $cpt->post_excerpt;
				$item['category']      = '';
				break;
		endswitch;

		return $item;
	}
endif;

add_filter( 'pll_get_post_types', 'hsp_add_cpt_to_pll', 10, 2 );

if ( ! function_exists( 'hsp_add_cpt_to_pll' ) ):
	function hsp_add_cpt_to_pll( $post_types, $is_settings ) {
		// Add no public CPT to Polylang settings
		$post_types['news']          = 'news';
		$post_types['press_release'] = 'press_release';
		$post_types['profile']       = 'profile';

		return $post_types;
	}
endif;

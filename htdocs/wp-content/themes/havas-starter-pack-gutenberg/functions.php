<?php
/**
 * Havas Starter Pack Gutenberg functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Havas_Starter_Pack_Gutenberg
 */

if ( ! defined( 'HSP_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( 'HSP_VERSION', '1.1.2' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
add_action( 'after_setup_theme', 'hsp_gutenberg_setup' );

if ( ! function_exists( 'hsp_gutenberg_setup' ) ) :
	function hsp_gutenberg_setup() {
		/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on Havas Starter Pack, use a find and replace
		* to change 'havas-core-factory' to the name of your theme in all the template files.
		*/
		load_theme_textdomain( 'havas_starter_pack_gutenberg', get_template_directory() . '/languages' );

		/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
		add_theme_support( 'title-tag' );

		add_theme_support( 'editor-styles' );

		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			array(
				'menu-navigation' => esc_html__( 'Menu_navigation', 'havas_starter_pack_gutenberg' ),
				'menu-footer'     => esc_html__( 'Menu_footer', 'havas_starter_pack_gutenberg' ),
			)
		);

		/*
		 * Set alt/caption/legend image automatically
		 */
		add_action( 'add_attachment', 'havas_starter_pack_set_image_meta_upon_image_upload' );
	}
endif;

add_action( 'acf/init', 'hsp_gutenberg_acf_init' );

if ( ! function_exists( 'hsp_gutenberg_acf_init' ) ) :
	function hsp_gutenberg_acf_init() {
		if ( function_exists( 'acf_add_options_page' ) ):
			/**
			 * Add ACF option page
			 */
			acf_add_options_page( array(
				'page_title' => __( 'Theme Settings', 'havas_starter_pack_gutenberg' ),
				'menu_title' => __( 'Theme Settings', 'havas_starter_pack_gutenberg' ),
				'menu_slug'  => 'theme-general-settings',
				'capability' => 'edit_posts',
				'redirect'   => false,
			) );
		endif;
	}
endif;

add_action( 'admin_init', 'hsp_gutenberg_add_editor_styles' );

if ( ! function_exists( 'hsp_gutenberg_add_editor_styles' ) ):
	function hsp_gutenberg_add_editor_styles() {
		add_editor_style( 'dist/app.css' );
	}
endif;

add_action( 'wp_enqueue_scripts', 'hsp_gutenberg_scripts' );

if ( ! function_exists( 'hsp_gutenberg_scripts' ) ) :
	/**
	 * Enqueue scripts and styles.
	 */
	function hsp_gutenberg_scripts() {
		// remove default gutenberg block style
		wp_dequeue_style( 'wp-block-library' );
		wp_dequeue_style( 'wp-block-library-theme' );
		// add app css
		wp_enqueue_style( 'main', get_template_directory_uri() . '/dist/app.css', array(), false );
		// RGPD
		wp_enqueue_style( 'rgpd-hdf', get_template_directory_uri() . '/rgpd/css/tarteaucitron-HDF.css', array(), false );
		wp_enqueue_script( 'rgpd', get_template_directory_uri() . '/rgpd/tarteaucitron.js', array(), false, false );
		wp_enqueue_script( 'rgpd-hdf', get_template_directory_uri() . '/rgpd/tarteaucitronHDF.js', array(), false, false );
		// JS
		wp_enqueue_script( 'main', get_template_directory_uri() . '/dist/app.js', array(), false, true );
	}
endif;

//add_action( 'enqueue_block_editor_assets', 'hsp_gutenberg_js' );

if ( ! function_exists( 'hsp_gutenberg_js' ) ) :
	/**
	 * Enqueue script
	 */
	function hsp_gutenberg_js() {
		// JS
		wp_enqueue_script( 'front-main', get_template_directory_uri() . '/dist/app.js', array(), false, true );
	}
endif;

/**
 * Clean admin menu
 */
require get_template_directory() . '/inc/clean-admin-menu.php';

/**
 * Load CPT
 */
require get_template_directory() . '/inc/custom-posts/actualites.php';
require get_template_directory() . '/inc/custom-posts/portraits.php';
require get_template_directory() . '/inc/custom-posts/communiques.php';

/**
 * Load ACF Blocks
 */
require get_template_directory() . '/inc/init-acf-blocks.php';

/**
 * Customize ACF validation
 */
require get_template_directory() . '/inc/custom-acf-validation.php';

/**
 * Customize Tiny MCE
 */
require_once get_template_directory() . '/inc/customize-tiny-mce.php';

/**
 * Utilities function
 */
require_once get_template_directory() . '/inc/extra-utils.php';

/**
 * Customize login page
 */
require_once get_template_directory() . '/inc/customize-login-page.php';

add_filter( 'https_ssl_verify', '__return_false' );

remove_filter( 'render_block', 'wp_render_layout_support_flag', 10, 2 );
remove_filter( 'render_block', 'gutenberg_render_layout_support_flag', 10, 2 );

add_action( 'after_switch_theme', 'hsp_rewrite_flush' );

if ( ! function_exists( 'hsp_rewrite_flush' ) ):
	function hsp_rewrite_flush() {
		actualites_init();
		portraits_init();
		communiques_init();
		flush_rewrite_rules();
	}
endif;

if ( ! function_exists( 'hsp_get_liste_info' ) ):
	function hsp_get_liste_info( $cpt ) {
		$item = array();

		switch ( $cpt->post_type ):
			case 'actualites':
				$image_id          = get_post_thumbnail_id( $cpt->ID );
				$item['permalink'] = get_permalink( $cpt->ID );
				$item['image_url'] = ( empty( get_the_post_thumbnail_url( $cpt->ID ) ) ? get_field( 'default_image', 'option' )['url'] : get_the_post_thumbnail_url( $cpt->ID ) );
				$item['image_alt'] = $item['image_alt'] = get_post_meta( $image_id, '_wp_attachment_image_alt', true );;
				$item['suptitle'] = get_the_date( __( 'd F Y', 'havas_starter_pack_gutenberg' ), $cpt->ID );
				$item['title']    = $cpt->post_title;
				$item['excerpt']  = $cpt->post_excerpt;
				$item['category'] = get_the_terms( $cpt->ID, 'category' );
				break;

			case 'portraits':
				$item['permalink'] = '';
				$item['image_url'] = get_the_post_thumbnail_url( $cpt->ID );
				$item['image_alt'] = 'todo';
				$item['suptitle']  = get_field( 'fonction', $cpt->ID );
				$item['title']     = $cpt->post_title;
				$item['excerpt']   = $cpt->post_excerpt;
				$item['category']  = '';
				break;

			case 'communique':
				$communique           = get_field( 'piece_jointe', $cpt->ID );
				$item['piece_jointe'] = $communique['url'];
				$item['image_url']    = '';
				$item['image_alt']    = '';
				$item['date']         = get_the_date( __( 'd F Y', 'havas_starter_pack_gutenberg' ), $cpt->ID );
				$item['title']        = $cpt->post_title;
				$item['excerpt']      = $cpt->post_excerpt;
				$item['category']     = '';
				break;
		endswitch;

		return $item;
	}
endif;

add_filter( 'pll_get_post_types', 'hsp_add_cpt_to_pll', 10, 2 );

if ( ! function_exists( 'hsp_add_cpt_to_pll' ) ):
	function hsp_add_cpt_to_pll( $post_types, $is_settings ) {
		// Add no public CPT to Polylang settings
		$post_types['portraits']   = 'portraits';
		$post_types['communiques'] = 'communiques';

		return $post_types;
	}
endif;

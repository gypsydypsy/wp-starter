<?php
/**
 * Digital Factory plugin bootstrap file
 *
 * @link              http://www.havasdigitalfactory.com/
 * @since             1.1.0
 * @package           Digital_Factory_Bootstrap
 *
 * @wordpress-plugin
 * Plugin Name:       Digital Factory Bootstrap
 * Plugin URI:        http://www.havasdigitalfactory.com/
 * Description:       Clean and block unused features / API option page
 * Version:           1.6.0
 * Author:            Sébastien Gastard - Digital Factory
 * Author URI:        http://www.havasdigitalfactory.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       digital-factory-bootstrap
 */

/**
 * On désactive le système de sauvegarde automatique
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'no_autosave' ) ) :
	function no_autosave() {
		wp_deregister_script( 'autosave' );
	}
endif;

/**
 * On supprime le tag generator dans le flux RSS
 *
 * @return string
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'wpt_remove_version' ) ) :
	function wpt_remove_version() {
		return '';
	}
endif;

/**
 * On supprime le numéro de version que Wordpress passe en paramètre des scripts
 *
 * @param $src
 *
 * @return string
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'vc_remove_wp_ver_css_js' ) ) :
	function vc_remove_wp_ver_css_js( $src ) {
		if ( strpos( $src, 'ver=' ) ) {
			$src = remove_query_arg( 'ver', $src );
		}

		return $src;
	}
endif;

/**
 * On désactive l'authentification auto par SMTP pour l'envoi de mail via la fonction wp_mail() dû à un bug PHP 5.6 et SSL
 *
 * @param $phpmailer
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'cetsi_remove_smt_auth' ) ) :
	function cetsi_remove_smt_auth( $phpmailer ) {
		if ( is_ssl() && version_compare( PHP_VERSION, '5.6.0' ) >= 0 ) {
			$phpmailer->SMTPAutoTLS = false;
		}
	}
endif;

/**
 * Require allowed endpoint to be used
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'secure_API' ) ) :
	function secure_API( $access ) {
		// block default endpoint from the REST API users
		$forbidden_endpoints = array(
			'#/wp-json/wp/v2/users(/)?$#',
		);

		foreach ( $forbidden_endpoints as $regex_forbidden_endpoint ) {
			preg_match( $regex_forbidden_endpoint, $_SERVER['REQUEST_URI'], $matches );

			if ( count( $matches ) > 0 ) {
				return new WP_Error( 'rest_cannot_access', __( 'Forbidden access', 'hdf' ), array( 'status' => rest_authorization_required_code() ) );
			}
		}

		return $access;
	}
endif;

/**
 * Nettoyage du header / API / Désactivation des emoji dans header.php
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'clean_theme' ) ) :
	function clean_theme() {
		// WP version.
		remove_action( 'wp_head', 'wp_generator' );
		// Post and comment feed links.
		remove_action( 'wp_head', 'feed_links', 2 );
		// Index link.
		remove_action( 'wp_head', 'index_rel_link' );
		// Shortlink.
		remove_action( 'wp_head', 'wp_shortlink_wp_head', 10 );
		// Windows Live Writer.
		remove_action( 'wp_head', 'wlwmanifest_link' );
		// Category feed links.
		remove_action( 'wp_head', 'feed_links_extra', 3 );
		// Start link.
		remove_action( 'wp_head', 'start_post_rel_link', 10 );
		// Previous link.
		remove_action( 'wp_head', 'parent_post_rel_link', 10 );
		// Canonical.
		remove_action( 'wp_head', 'rel_canonical', 10 );
		// Links for adjacent posts.
		remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10 );
		// Emoji detection script.
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		// Emoji styles.
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
		remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
		remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );

		// REST API
		remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
		remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );
		// hide API link and need tu be logged-in to use
		add_filter( 'rest_authentication_errors', 'secure_API' );

		// XML RPC
		add_filter( 'xmlrpc_enabled', '__return_false' );
		remove_action( 'wp_head', 'rsd_link' );
	}
endif;

/**
 * On bloque le référencement naturel de WP de la liste des users
 *
 * @param $query
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'block_author_archive' ) ) :
	function block_author_archive( &$query ) {
		if ( ! is_admin() && $query->is_author ) {
			wp_redirect( home_url( '/' ), 301 );
			exit();
		}
	}
endif;

/**
 * Restriction d'accès au BO à l'admin et aux éditeurs
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'restrict_access_bo' ) ) :
	function restrict_access_bo() {
		if ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) {
			if ( ! current_user_can( 'administrator' ) && ! current_user_can( 'editor' ) && ! current_user_can( 'df_contact_data_manager' ) && ! current_user_can( 'df_newsletter_data_manager' ) ) {
				wp_redirect( home_url( '/' ), 302 );
				exit();
			}
		}
	}
endif;

/**
 * Remove native search form
 *
 * @param $query
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'remove_native_search' ) ) :
	function remove_native_search( $query ) {

		if ( $query->is_search && $query->is_main_query() ) {
			unset( $_GET['s'] );
			unset( $_POST['s'] );
			unset( $_REQUEST['s'] );
			unset( $query->query['s'] );
			$query->set( 's', '' );
			$query->is_search = false;
			$query->set_404();
			status_header( 404 );
			nocache_headers();
		}
	}
endif;

/**
 * Remove native search form
 *
 * @param $form
 *
 * @return void
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'remove_native_search_form' ) ) :
	function remove_native_search_form( $form ) {

		return '';
	}
endif;

/**
 * Add favicons to the current theme
 *
 * @since 1.2.0
 */
if ( ! function_exists( 'hdf_add_favicon' ) ):
	function hdf_add_favicon() {
		?>
        <link rel="apple-touch-icon" sizes="180x180" href="<?php echo( esc_url( get_stylesheet_directory_uri() . '/favicons/apple-touch-icon.png' ) ); ?>">
        <link rel="icon" type="image/png" sizes="32x32" href="<?php echo( esc_url( get_stylesheet_directory_uri() . '/favicons/favicon-32x32.png' ) ); ?>">
        <link rel="icon" type="image/png" sizes="16x16" href="<?php echo( esc_url( get_stylesheet_directory_uri() . '/favicons/favicon-16x16.png' ) ); ?>">
        <meta name="msapplication-TileColor" content="#da532c">
        <meta name="theme-color" content="#ffffff">
		<?php
	}
endif;

/**
 * Check the favicons, show a warning message in BO if not present
 *
 * @since 1.2.0
 */
if ( ! function_exists( 'hdf_check_favicon' ) ):
	function hdf_check_favicon() {
		$required_files = array(
			'apple-touch-icon.png',
			'favicon-32x32.png',
			'favicon-16x16.png',
		);

		// all files are required
		$message = array();

		foreach ( $required_files as $required_file ):
			if ( ! file_exists( get_stylesheet_directory() . '/favicons/' . $required_file ) ):
				$message[] = sprintf( __( '%s is missing.', 'hdf' ), get_stylesheet_directory_uri() . '/favicons/' . $required_file );
			endif;
		endforeach;

		if ( count( $message ) > 0 ):
			$class        = 'notice notice-error';
			$html_message = __( 'Please don\'t forget to <a href="https://realfavicongenerator.net" target="_blank">provide favicons</a>.', 'hdf' );
			$html_message .= '<br>' . implode( '<br>', $message );
			printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), $html_message );
		endif;
	}
endif;

/**
 * Remove type tag from script and style
 *
 * @since 1.2.0
 */
if ( ! function_exists( 'hdf_remove_type_attr' ) ) :
	function hdf_remove_type_attr( $tag, $handle = '' ) {
		return preg_replace( "/type=['\"]text\/(javascript|css)['\"]/", '', $tag );
	}
endif;

/**
 * Custom snippet to add poylang support in custom endpoitn REST API (via ?lang=XX)
 *
 * @since 1.2.0
 * @modified 1.6.0 change $_GET to $_REQUEST
 */
if ( ! function_exists( 'polylang_json_api_init' ) ) :
	function polylang_json_api_init() {
		if ( function_exists( 'pll_default_language' ) ):
			global $polylang;

			$default  = pll_default_language();
			$langs    = pll_languages_list();
			$cur_lang = '';

			if ( isset( $_REQUEST['lang'] ) ):
				$cur_lang = $_REQUEST['lang'];
			endif;

			if ( ! in_array( $cur_lang, $langs ) ) {
				$cur_lang = $default;
			}

			$polylang->curlang         = $polylang->model->get_language( $cur_lang );
			$GLOBALS['text_direction'] = $polylang->curlang->is_rtl ? 'rtl' : 'ltr';
		endif;
	}
endif;

/**
 * @since 1.4.0
 */
if ( ! function_exists( 'hdf_app_password_availability' ) ) :
	function hdf_app_password_availability( $available, $user ) {
		if ( ! user_can( $user, 'manage_options' ) ) :
			$available = false;
		endif;

		return $available;
	}
endif;

/*
	ACTIONS
*/
add_action( 'wp_print_scripts', 'no_autosave' );
add_action( 'phpmailer_init', 'cetsi_remove_smt_auth' );
add_action( 'init', 'clean_theme' );
add_action( 'pre_get_posts', 'block_author_archive' );
add_action( 'admin_init', 'restrict_access_bo' );
add_action( 'wp_head', 'hdf_add_favicon' );
add_action( 'admin_notices', 'hdf_check_favicon' );
add_action( 'rest_api_init', 'polylang_json_api_init' );

if ( ! is_admin() ) {
	add_action( 'parse_query', 'remove_native_search' );
}

/*
	FILTERS
*/
add_filter( 'the_generator', 'wpt_remove_version' );
add_filter( 'style_loader_src', 'vc_remove_wp_ver_css_js', 9999 );
add_filter( 'script_loader_src', 'vc_remove_wp_ver_css_js', 9999 );
add_filter( 'get_search_form', 'remove_native_search_form' );
add_filter( 'style_loader_tag', 'hdf_remove_type_attr', 10, 2 );
add_filter( 'script_loader_tag', 'hdf_remove_type_attr', 10, 2 );
add_filter( 'autoptimize_html_after_minify', 'hdf_remove_type_attr', 10, 2 );
// since 1.2.0
add_filter( 'admin_email_check_interval', '__return_false' ); // new WP 5.3 : block check email admin
// since 1.3.0
add_filter( 'rocket_cache_reject_wp_rest_api', '__return_false' );
add_filter( 'rocket_cache_reject_wc_rest_api', '__return_false' );
// since 1.4.0
add_filter( 'allow_major_auto_core_updates', '__return_false' ); // new WP 5.6 : block auto update core
add_filter( 'wp_is_application_passwords_available_for_user', 'hdf_app_password_availability', 10, 2 ); // new WP 5.6 : Application Password, filter to allow only administrator to use it
// since 1.6.0
add_filter( 'sanitize_file_name', 'remove_accents' );

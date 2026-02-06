<?php
/**
 * Modified the full bar in tiny mce
 */
if ( ! function_exists( 'havas_starter_pack_gutenberg_buttons_custom_toolbar' ) ):
	function havas_starter_pack_gutenberg_buttons_custom_toolbar( $toolbars ) {
		// Add a new toolbar
		// - this toolbar has only 1 row of buttons
		$toolbars['Core Factory']    = array();
		$toolbars['Core Factory'][1] = array( 'styleselect', 'bold', 'italic', 'bullist', 'numlist', 'link', 'charmap', 'alignleft','aligncenter','alignright','cta_with_picture' );

		// remove default toolbar completely
		unset( $toolbars['Basic'] );
		unset( $toolbars['Full'] );

		// return $toolbars - IMPORTANT!
		return $toolbars;
	}
endif;

add_filter( 'acf/fields/wysiwyg/toolbars', 'havas_starter_pack_gutenberg_buttons_custom_toolbar' );

/**
 * Add custom button and style for the tiny MCE editor
 */
if ( ! function_exists( 'havas_starter_pack_gutenberg_buttons' ) ):
	function havas_starter_pack_gutenberg_buttons() {
		if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
			return;
		}

		if ( get_user_option( 'rich_editing' ) !== 'true' ) {
			return;
		}

		add_filter( 'tiny_mce_before_init', 'havas_starter_pack_gutenberg_before_init_insert_formats' );
		add_filter( 'mce_external_plugins', 'havas_starter_pack_gutenberg_add_buttons' );
		add_filter( 'mce_buttons', 'havas_starter_pack_gutenberg_register_buttons' );
		add_filter( 'mce_buttons_2', 'havas_starter_pack_gutenberg_register_buttons_2' );
	}
endif;

add_action( 'init', 'havas_starter_pack_gutenberg_buttons' );

if ( ! function_exists( 'havas_starter_pack_gutenberg_add_buttons' ) ) :
	/**
	 * Exemple : add a button CTA (image + link) using build-in WP parser of media library and link
	 */
	function havas_starter_pack_gutenberg_add_buttons( $plugin_array ) {
		$plugin_array['cta_with_picture'] = get_template_directory_uri() . '/assets/js/tinymce_buttons.js';

		return $plugin_array;
	}
endif;

if ( ! function_exists( 'havas_starter_pack_gutenberg_register_buttons' ) ) :
	function havas_starter_pack_gutenberg_register_buttons( $buttons ) {
		array_push( $buttons, 'cta_with_picture' );

		return $buttons;
	}
endif;

// Callback function to insert 'styleselect' into the $buttons array
if ( ! function_exists( 'havas_starter_pack_gutenberg_register_buttons_2' ) ):
	function havas_starter_pack_gutenberg_register_buttons_2( $buttons ) {
		array_unshift( $buttons, 'styleselect' );

		return $buttons;
	}
endif;

// Callback function to filter the MCE settings
if ( ! function_exists( 'havas_starter_pack_gutenberg_before_init_insert_formats' ) ):
	function havas_starter_pack_gutenberg_before_init_insert_formats( $init_array ) {
		// Define the style_formats array
		$style_formats = array(
			// Each array child is a format with it's own settings
			array(
				'title'   => __( 'H2', 'havas_starter_pack_gutenberg' ),
				'block'   => 'h2',
				'wrapper' => false,
			),
			array(
				'title'   => __( 'H3', 'havas_starter_pack_gutenberg' ),
				'block'   => 'h3',
				'wrapper' => false,
			),
			array(
				'title'   => __( 'H4', 'havas_starter_pack_gutenberg' ),
				'block'   => 'h4',
				'wrapper' => false,
			),
			array(
				'title'   => __( 'H5', 'havas_starter_pack_gutenberg' ),
				'block'   => 'h5',
				'wrapper' => false,
			),
			array(
				'title'   => __( 'H6', 'havas_starter_pack_gutenberg' ),
				'block'   => 'h6',
				'wrapper' => false,
			),
			array(
				'title'   => __( 'Paragraphe', 'havas_starter_pack_gutenberg' ),
				'block'   => 'p',
				'wrapper' => false,
			),
			array(
				'title'    => __( 'Bouton', 'havas_starter_pack_gutenberg' ),
				'selector' => 'a',
				'classes'  => 'c-button bg--',
				'wrapper'  => false,
			),
			array(
				'title'    => __( 'Lien simple souligné', 'havas_starter_pack_gutenberg' ),
				'selector' => 'a',
				'classes'  => '',
				'wrapper'  => false,
			),
			array(
				'title'    => __( 'Lien avec flèche', 'havas_starter_pack_gutenberg' ),
				'selector' => 'a',
				'classes'  => 'c-button arrow--',
				'wrapper'  => false,
			),
			array(
				'title'    => __( 'Lien de téléchargement', 'havas_starter_pack_gutenberg' ),
				'selector' => 'a',
				'classes'  => 'c-button download--',
				'wrapper'  => false,
			),
		);

		// Insert the array, JSON ENCODED, into 'style_formats'
		$init_array['style_formats'] = json_encode( $style_formats );

		return $init_array;
	}
endif;

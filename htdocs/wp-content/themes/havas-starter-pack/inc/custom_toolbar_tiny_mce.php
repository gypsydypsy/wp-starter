<?php
//  Modify the full bar in tiny mce

if ( ! function_exists( 'hsp_buttons_custom_toolbar' ) ):
	function hsp_buttons_custom_toolbar( $toolbars ) {
		// Add a new toolbar
		// - this toolbar has only 1 row of buttons
		$toolbars['Core Factory']    = array();
		$toolbars['Core Factory'][1] = array(
			'styleselect',
			'bold',
			'italic',
			'bullist',
			'numlist',
			'link',
			'charmap',
			'alignleft',
			'aligncenter',
			'alignright',
			'cta_with_picture'
		);

		// remove default toolbar completely
		unset( $toolbars['Basic'] );
		unset( $toolbars['Full'] );

		// return $toolbars - IMPORTANT!
		return $toolbars;
	}
endif;

add_filter( 'acf/fields/wysiwyg/toolbars', 'hsp_buttons_custom_toolbar' );

/**
 * Add custom button and style for the tiny MCE editor
 */

if ( ! function_exists( 'hsp_buttons' ) ):
	function hsp_buttons() {
		if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
			return;
		}

		if ( get_user_option( 'rich_editing' ) !== 'true' ) {
			return;
		}

		add_filter( 'tiny_mce_before_init', 'hsp_before_init_insert_formats' );
		add_filter( 'mce_external_plugins', 'hsp_add_buttons' );
		add_filter( 'mce_buttons', 'hsp_register_buttons' );
		add_filter( 'mce_buttons_2', 'hsp_register_buttons_2' );
	}
endif;

add_action( 'init', 'hsp_buttons' );

if ( ! function_exists( 'hsp_add_buttons' ) ) :
	/**
	 * Exemple : add a button CTA (image + link) using build-in WP parser of media library and link
	 */

	function hsp_add_buttons( $plugin_array ) {
		$plugin_array['cta'] = get_template_directory_uri() . '/js/tinymce_buttons.js';

		return $plugin_array;
	}
endif;

if ( ! function_exists( 'hsp_register_buttons' ) ) :
	function hsp_register_buttons( $buttons ) {
		array_push( $buttons, 'cta' );

		return $buttons;
	}
endif;

// Callback function to insert 'styleselect' into the $buttons array
if ( ! function_exists( 'hsp_register_buttons_2' ) ):
	function hsp_register_buttons_2( $buttons ) {
		array_unshift( $buttons, 'styleselect' );

		return $buttons;
	}
endif;

// Callback function to filter the MCE settings
if ( ! function_exists( 'hsp_before_init_insert_formats' ) ):
	function hsp_before_init_insert_formats( $init_array ) {
		// Define the style_formats array
		$style_formats = array(
			// Each array child is a format with its own settings
			array(
				'title'   => __( 'Title h2', 'havas_starter_pack' ),
				'block'   => 'h2',
				'wrapper' => false,
			),
			array(
				'title'   => __( 'Title h3', 'havas_starter_pack' ),
				'block'   => 'h3',
				'wrapper' => false,
			),
			array(
				'title'   => __( 'Title h4', 'havas_starter_pack' ),
				'block'   => 'h4',
				'wrapper' => false,
			),
			array(
				'title'   => __( 'Title h5', 'havas_starter_pack' ),
				'block'   => 'h5',
				'wrapper' => false,
			),
			array(
				'title'   => __( 'Title h6', 'havas_starter_pack' ),
				'block'   => 'h6',
				'wrapper' => false,
			),
			array(
				'title'   => __( 'Paragraph', 'havas_starter_pack' ),
				'block'   => 'p',
				'wrapper' => false,
			),
			array(
				'title'    => __( 'Button link', 'havas_starter_pack' ),
				'selector' => 'a',
				'classes'  => 'c-button bg--',
				'wrapper'  => false,
			),
			array(
				'title'    => __( 'Simple link underlined', 'havas_starter_pack' ),
				'selector' => 'a',
				'classes'  => '',
				'wrapper'  => false,
			),
			array(
				'title'    => __( 'Arrow link', 'havas_starter_pack' ),
				'selector' => 'a',
				'classes'  => 'c-button arrow--',
				'wrapper'  => false,
			),
			array(
				'title'    => __( 'Download link', 'havas_starter_pack' ),
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

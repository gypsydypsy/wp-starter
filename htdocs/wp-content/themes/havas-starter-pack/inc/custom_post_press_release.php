<?php
// Custom Post Type for Press Releases
if ( ! function_exists( 'press_release_init' ) ):
	function press_release_init() {
		$labels = array(
			'name'          => __( 'Press Releases', 'havas_starter_pack' ),
			'all_items'     => __( 'All press releases', 'havas_starter_pack' ),
			// affiché dans le sous menu
			'singular_name' => __( 'Press Release', 'havas_starter_pack' ),
			'add_new_item'  => __( 'Add press release', 'havas_starter_pack' ),
			'edit_item'     => __( 'Edit press release', 'havas_starter_pack' ),
			'menu_name'     => __( 'Press Releases', 'havas_starter_pack' ),
		);

		$args = array(
			'labels'        => $labels,
			'public'        => false,
			'show_in_rest'  => true,
			'show_ui'       => true,
			'has_archive'   => false,
			'supports'      => array( 'title' ),
			'menu_position' => 22,
			'rewrite'       => array(
				'slug'       => 'press_release',
				'with_front' => false,
			),
			'menu_icon'     => 'dashicons-format-aside',
		);

		register_post_type( 'press_release', $args );
	}
endif;

add_action( 'init', 'press_release_init' );

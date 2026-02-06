<?php
// Custom Post Type for News
if ( ! function_exists( 'news_init' ) ):
	function news_init() {
		$labels = array(
			'name'          => __( 'News', 'havas_starter_pack' ),
			'all_items'     => __( 'All News', 'havas_starter_pack' ),
			'singular_name' => __( 'News', 'havas_starter_pack' ),
			'add_new_item'  => __( 'Add news', 'havas_starter_pack' ),
			'edit_item'     => __( 'Edit news', 'havas_starter_pack' ),
			'menu_name'     => __( 'News', 'havas_starter_pack' ),
		);

		$args = array(
			'labels'        => $labels,
			'public'        => true,
			'show_in_rest'  => true,
			'has_archive'   => false,
			'supports'      => array( 'title', 'excerpt' ),
			'menu_position' => 20,
			'rewrite'       => array(
				'slug'       => 'news',
				'with_front' => false,
			),
			'menu_icon'     => 'dashicons-list-view',
			'taxonomies'    => array( 'category' ),
		);

		register_post_type( 'news', $args );
	}
endif;

add_action( 'init', 'news_init' );

<?php
// Custom Post Type for Profiles
if ( ! function_exists( 'profile_init' ) ):
	function profile_init() {
		$labels = array(
			'name'          => __( 'Profiles', 'havas_starter_pack' ),
			'all_items'     => __( 'All profiles', 'havas_starter_pack' ),
			'singular_name' => __( 'Profile', 'havas_starter_pack' ),
			'add_new_item'  => __( 'Add profile', 'havas_starter_pack' ),
			'edit_item'     => __( 'Edit profile', 'havas_starter_pack' ),
			'menu_name'     => __( 'Profiles', 'havas_starter_pack' ),
		);

		$args = array(
			'labels'        => $labels,
			'public'        => false,
			'show_in_rest'  => true,
			'show_ui'       => true,
			'has_archive'   => false,
			'supports'      => array( 'title', 'thumbnail' ),
			'menu_position' => 21,
			'rewrite'       => array(
				'slug'       => 'profile',
				'with_front' => false,
			),
			'menu_icon'     => 'dashicons-id-alt',
		);

		register_post_type( 'profile', $args );

		// Declare taxonomy for the teams, groups, etc
		$labels = array(
			'name'          => 'Teams',
			'new_item_name' => 'Name of the Team',
		);

		$args = array(
			'labels'            => $labels,
			'public'            => true,
			'show_in_rest '     => true,
			'hierarchical'      => true,
			'show_admin_column' => true,
		);

		register_taxonomy( 'team', 'profile', $args );
	}
endif;

add_action( 'init', 'profile_init' );

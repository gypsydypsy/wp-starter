<?php
add_action( 'init', 'portraits_init' );

if ( ! function_exists( 'portraits_init' ) ):
	function portraits_init() {
		register_post_type( 'portrait', array(
			'labels'                => array(
				'name'               => __( 'Portraits', 'havas_starter_pack_gutenberg' ),
				'singular_name'      => __( 'Portrait', 'havas_starter_pack_gutenberg' ),
				'all_items'          => __( 'Tous les Portraits', 'havas_starter_pack_gutenberg' ),
				'new_item'           => __( 'Nouveau Portrait', 'havas_starter_pack_gutenberg' ),
				'add_new'            => __( 'Ajouter', 'havas_starter_pack_gutenberg' ),
				'add_new_item'       => __( 'Ajouter un Portrait', 'havas_starter_pack_gutenberg' ),
				'edit_item'          => __( 'Editer ce Portrait', 'havas_starter_pack_gutenberg' ),
				'view_item'          => __( 'Voir ce Portrait', 'havas_starter_pack_gutenberg' ),
				'search_items'       => __( 'Rechercher parmi les Portraits', 'havas_starter_pack_gutenberg' ),
				'not_found'          => __( 'Aucun Portrait trouvé', 'havas_starter_pack_gutenberg' ),
				'not_found_in_trash' => __( 'Aucun Portrait trouvé dans la corbeille', 'havas_starter_pack_gutenberg' ),
				'parent_item_colon'  => __( 'Portrait parent', 'havas_starter_pack_gutenberg' ),
				'menu_name'          => __( 'Portraits', 'havas_starter_pack_gutenberg' ),
			),
			'public'                => false,
			'hierarchical'          => false,
			'show_ui'               => true,
			'show_in_nav_menus'     => true,
			'supports'              => array( 'title', 'thumbnail', 'excerpt' ),
			'has_archive'           => false,
			'rewrite'               => true,
			'query_var'             => true,
			'menu_icon'             => 'dashicons-buddicons-buddypress-logo',
			'show_in_rest'          => true,
			'rest_base'             => 'portraits',
			'rest_controller_class' => 'WP_REST_Posts_Controller',
		) );
	}
endif;

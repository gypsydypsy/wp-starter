<?php
add_action( 'init', 'actualites_init' );

if ( ! function_exists( 'actualites_init' ) ):
	function actualites_init() {
		register_post_type( 'actualites', array(
			'labels'                => array(
				'name'               => __( 'Actualités', 'havas_starter_pack_gutenberg' ),
				'singular_name'      => __( 'Actualité', 'havas_starter_pack_gutenberg' ),
				'all_items'          => __( 'Toutes les Actualités', 'havas_starter_pack_gutenberg' ),
				'new_item'           => __( 'Nouvelle Actualité', 'havas_starter_pack_gutenberg' ),
				'add_new'            => __( 'Ajouter', 'havas_starter_pack_gutenberg' ),
				'add_new_item'       => __( 'Ajouter une Actualité', 'havas_starter_pack_gutenberg' ),
				'edit_item'          => __( 'Editer cette Actualité', 'havas_starter_pack_gutenberg' ),
				'view_item'          => __( 'Voir cette Actualité', 'havas_starter_pack_gutenberg' ),
				'search_items'       => __( 'Rechercher parmi les Actualités', 'havas_starter_pack_gutenberg' ),
				'not_found'          => __( 'Aucune Actualité trouvée', 'havas_starter_pack_gutenberg' ),
				'not_found_in_trash' => __( 'Aucune Actualité trouvée dans la corbeille', 'havas_starter_pack_gutenberg' ),
				'parent_item_colon'  => __( 'Actualité parente', 'havas_starter_pack_gutenberg' ),
				'menu_name'          => __( 'Actualités', 'havas_starter_pack_gutenberg' ),
			),
			'public'                => true,
			'hierarchical'          => false,
			'show_ui'               => true,
			'show_in_nav_menus'     => true,
			'supports'              => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
			'has_archive'           => false,
			'rewrite'               => true,
			'query_var'             => true,
			'menu_icon'             => 'dashicons-admin-site-alt3',
			'show_in_rest'          => true,
			'rest_base'             => 'actualites',
			'rest_controller_class' => 'WP_REST_Posts_Controller',
			'taxonomies'            => array( 'category' ),
		) );
	}
endif;

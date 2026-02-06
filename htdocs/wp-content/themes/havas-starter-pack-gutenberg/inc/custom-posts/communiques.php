<?php
add_action( 'init', 'communiques_init' );

if ( ! function_exists( 'communiques_init' ) ):
	function communiques_init() {
		register_post_type( 'communiques', array(
			'labels'                => array(
				'name'               => __( 'Communiqués', 'havas_starter_pack_gutenberg' ),
				'singular_name'      => __( 'Communiqué', 'havas_starter_pack_gutenberg' ),
				'all_items'          => __( 'Tous les Communiqués', 'havas_starter_pack_gutenberg' ),
				'new_item'           => __( 'Nouveau Communiqué', 'havas_starter_pack_gutenberg' ),
				'add_new'            => __( 'Ajouter', 'havas_starter_pack_gutenberg' ),
				'add_new_item'       => __( 'Ajouter un Communiqué', 'havas_starter_pack_gutenberg' ),
				'edit_item'          => __( 'Editer ce Communiqué', 'havas_starter_pack_gutenberg' ),
				'view_item'          => __( 'Voir ce Communiqué', 'havas_starter_pack_gutenberg' ),
				'search_items'       => __( 'Rechercher parmi les Communiqués', 'havas_starter_pack_gutenberg' ),
				'not_found'          => __( 'Aucun Communiqué trouvé', 'havas_starter_pack_gutenberg' ),
				'not_found_in_trash' => __( 'Aucun Communiqué trouvé dans la corbeille', 'havas_starter_pack_gutenberg' ),
				'parent_item_colon'  => __( 'Communiqué parent', 'havas_starter_pack_gutenberg' ),
				'menu_name'          => __( 'Communiqués', 'havas_starter_pack_gutenberg' ),
			),
			'public'                => false,
			'hierarchical'          => false,
			'show_ui'               => true,
			'show_in_nav_menus'     => true,
			'supports'              => array( 'title', 'editor' ),
			'has_archive'           => false,
			'rewrite'               => true,
			'query_var'             => true,
			'menu_icon'             => 'dashicons-format-aside',
			'show_in_rest'          => true,
			'rest_base'             => 'communiques',
			'rest_controller_class' => 'WP_REST_Posts_Controller',
		) );
	}
endif;

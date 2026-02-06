<?php
// rewrite permalink
if ( ! function_exists( 'hsp_remove_app_folder' ) ):
	function hsp_remove_app_folder( $url ) {
		return str_replace( '/app/', '/', $url );
	}
endif;

add_filter( 'page_link', 'hsp_remove_app_folder', 10, 1 );
add_filter( 'post_type_link', 'hsp_remove_app_folder', 10, 1 );

/**
 * Add REST API support to revision
 */
add_filter( 'register_post_type_args', 'hsp_revision_show_in_rest', 10, 2 );

if ( ! function_exists( 'hsp_revision_show_in_rest' ) ):
	function hsp_revision_show_in_rest( $args, $post_type ) {
		if ( 'revision' === $post_type ) :
			$args['show_in_rest'] = true;
		endif;

		return $args;
	}
endif;

if ( ! function_exists( 'hsp_custom_preview_link' ) ):
	/**
	 * Add custom preview link (need to hack with react)
	 * Add a security token
	 */
	function hsp_custom_preview_link( $link, $post ) {
		// clean url
		$url = str_replace( '/app/', '/', get_permalink( $post ) );

		return $url . '?preview=true&post_id=' . $post->ID . '&token=' . md5( $post->ID . NONCE_SALT );
	}
endif;

add_filter( 'preview_post_link', 'hsp_custom_preview_link', 10, 2 );
add_filter( 'preview_page_link', 'hsp_custom_preview_link', 10, 2 );


// extend data returns by acf, ex: for image field, not only the id, but src/title/alt etc...
add_filter( 'acf/settings/rest_api_format', function () {
	return 'standard';
} );

/**
 * Helpers for the admin dashboard
 */
if ( ! function_exists( 'hsp_add_dashboard_widgets' ) ):
	/**
	 * Add a widget to the dashboard.
	 */
	function hsp_add_dashboard_widgets() {
		wp_add_dashboard_widget(
			'hsp_dashboard_widget',                          // Widget slug.
			esc_html__( 'WordPress API custom Endpoints', 'havas_starter_pack' ), // Title.
			'hsp_dashboard_widget_render'                    // Display function.
		);
	}
endif;

add_action( 'wp_dashboard_setup', 'hsp_add_dashboard_widgets' );

if ( ! function_exists( 'hsp_dashboard_widget_render' ) ):
	/**
	 * Create the function to output the content of our Dashboard Widget.
	 */
	function hsp_dashboard_widget_render() {
		$all_custom_apis = array(
			get_rest_url( null, 'hsp/v1/routes' ),
			get_rest_url( null, 'hsp/v1/global' ),
		);

		$translations = array();

		// multilingual ?
		if ( function_exists( 'pll_the_languages' ) ):
			$translations = pll_the_languages( array( 'raw' => 1 ) );
		endif;

		esc_html_e( 'Quick link to custom API', 'havas_starter_pack' );

		echo( '<br/><br/>' );

		foreach ( $all_custom_apis as $custom_api ):
			if ( count( $translations ) > 0 ):
				foreach ( $translations as $translation ):
					echo( '<a href="' . esc_url( $custom_api . '?lang=' . $translation['slug'] ) . '" target="_blank">' . $custom_api . '?lang=' . $translation['slug'] . '</a><br/>' );
				endforeach;
			else:
				echo( '<a href="' . esc_url( $custom_api ) . '" target="_blank">' . $custom_api . '</a><br/>' );
			endif;
		endforeach;
	}
endif;

if ( ! function_exists( 'hsp_add_custom_endpoints_api' ) ):
	/**
	 * Register custom endpoints : routes and global
	 * Add custom language_switcher attribute to the REST API
	 */
	function hsp_add_custom_endpoints_api() {
		register_rest_route( 'hsp/v1', '/routes', array(
			'methods'             => WP_REST_Server::READABLE,
			'callback'            => 'hsp_custom_endpoints_api_routes',
			'permission_callback' => '__return_true',
		) );

		register_rest_route( 'hsp/v1', '/global', array(
			'methods'             => WP_REST_Server::READABLE,
			'callback'            => 'hsp_custom_endpoints_api_global',
			'permission_callback' => '__return_true',
		) );

		$public_cpts_in_rest = get_post_types( array(
			'public'       => true,
			'show_in_rest' => true,
		) );

		foreach ( $public_cpts_in_rest as $public_cpt_in_rest ):
			register_rest_field( $public_cpt_in_rest, 'language_switcher', array(
					'get_callback' => 'get_language_switcher_for_api',
					'schema'       => null,
				)
			);
		endforeach;
	}
endif;

add_action( 'rest_api_init', 'hsp_add_custom_endpoints_api' );

if ( ! function_exists( 'hsp_acf_to_rest_api' ) ):
	/**
	 * Force ACF to render all fields through the REST API
	 */
	function hsp_acf_to_rest_api( $response, $post, $request ) {
		if ( ! function_exists( 'get_fields' ) ) :
			return $response;
		endif;

		if ( isset( $post ) ) :
			$acf                   = get_fields( $post->ID );
			$response->data['acf'] = $acf;
		endif;

		return $response;
	}
endif;

//add_filter( 'rest_prepare_post', 'hsp_acf_to_rest_api', 10, 3 );
//add_filter( 'rest_prepare_page', 'hsp_acf_to_rest_api', 10, 3 );

/**
 * Custom endpoints callback functions
 */
if ( ! function_exists( 'hsp_custom_endpoints_api_routes' ) ):
	/**
	 *
	 */
	function hsp_custom_endpoints_api_routes( $data ) {
		$routes = array();

		// add global pattern for CPT
		$cpts = get_post_types( array(
			'public'       => true,
			'show_in_rest' => true,
			'_builtin'     => false,
		) );

		foreach ( $cpts as $cpt ):
			$url = rest_url( "wp/v2/$cpt" ) . '?slug=[slug]';

			$routes[] = array(
				'path'      => "/$cpt/:slug",
				'template'  => "single-$cpt",
				'rest_data' => $url,
			);
		endforeach;

		$args = array(
			'post_type'   => get_post_types( array(
				'public'       => true,
				'show_in_rest' => true,
			) ),
			'numberposts' => - 1,
			'post_status' => 'publish',
		);

		$myposts = get_posts( $args );

		if ( $myposts ):
			// keep id home for all languages
			$id_home         = (int) get_option( 'page_on_front' ) ?: 0;
			$translations    = array();
			$id_translations = array();

			if ( function_exists( 'pll_home_url' ) ):
				$home_url     = pll_home_url();
				$translations = pll_get_post_translations( $id_home );

				if ( count( $translations ) > 0 ):
					foreach ( $translations as $id_translation ):
						$id_translations[] = $id_translation;
					endforeach;
				endif;
			else:
				$home_url = home_url( '/' );
			endif;

			foreach ( $myposts as $post ) :
				// ignore attachment
				if ( 'attachment' !== $post->post_type ):
					setup_postdata( $post );
					// clean url
					$url = str_replace( WP_SITEURL, '', get_permalink( $post ) );
					$url = str_replace( 'https://' . $_SERVER['HTTP_HOST'], '', $url );

					$temp_data = array(
						'path'      => $url, // keep language code if provided
						'rest_data' => hsp_get_rest_url( $post, trim( $url, '/' ), $id_translations ),
					);

					$routes[] = $temp_data;
				endif;
			endforeach;

			wp_reset_postdata();
		endif;

		if ( 0 === count( $routes ) ):
			return new WP_Error( 'no_routes', 'Invalid routes', array( 'status' => 404 ) );
		endif;

		$response = new WP_REST_Response();
		$response->set_data( $routes );

		return rest_ensure_response( $response );
	}
endif;

if ( ! function_exists( 'hsp_custom_endpoints_api_global' ) ):
	function hsp_custom_endpoints_api_global( $data ) {
		// Get Social Networks data
		$social_names    = array( 'twitter', 'instagram', 'facebook', 'youtube', 'linkedin', 'tiktok', 'whatsapp' );
		$social_networks = array();

		foreach ( $social_names as $social_name ):
			$social_link = get_field( $social_name, 'option' );

			if ( ! empty( $social_link ) ):
				$social_networks[ $social_name ] = array(
					'link'  => $social_link,
					'title' => mb_convert_case( $social_name, MB_CASE_TITLE ),
				);
			endif;
		endforeach;

		// Get Logo data
		$logo_data = array();

		if ( ! empty( get_field( 'logo', 'option' ) ) ):
			$logo      = get_field( 'logo', 'option' );
			$logo_url  = $logo['url'];
			$logo_id   = $logo['id'];
			$logo_alt  = get_post_meta( $logo_id, '_wp_attachment_image_alt', true );
			$logo_data = array(
				'src' => $logo_url,
				'alt' => $logo_alt,
			);
		endif;

		// Get Menus data
		$locations       = get_nav_menu_locations();
		$menu_navigation = ( isset( $locations['menu-navigation'] ) ) ? hsp_wp_get_menu_array( $locations['menu-navigation'] ) : array();
		$menu_footer     = ( isset( $locations['menu-footer'] ) ) ? hsp_wp_get_menu_array( $locations['menu-footer'] ) : array();

		$global = array(
			'header'  => array(
				'logo'            => $logo_data,
				'menu_navigation' => $menu_navigation,
			),
			'socials' => $social_networks,
			'footer'  => array(
				'menu_footer' => $menu_footer
			),
		);

		if ( 0 === count( $global ) ):
			return new WP_Error( 'no_global', 'Invalid global', array( 'status' => 404 ) );
		endif;

		$response = new WP_REST_Response();
		$response->set_data( $global );

		return rest_ensure_response( $response );
	}
endif;

/**
 * Utilities functions
 */
if ( ! function_exists( 'hsp_clean_url' ) ):
	/**
	 * CLean urls for react : relative urls for internal link, absolute for external
	 */
	function hsp_clean_url( $dirty_url ) {
		$url = str_replace( WP_SITEURL, '', $dirty_url );
		$url = str_replace( 'https://' . $_SERVER['HTTP_HOST'], '', $url );

		return $url;
	}
endif;

if ( ! function_exists( 'hsp_get_rest_url' ) ):
	/**
	 * Extend REST API by adding attributes (then use a filter to retrieve the data)
	 */
	function hsp_get_rest_url( $post, $path, $id_translations ) {
		switch ( $post->post_type ):
			case 'post':
				$endpoint = 'posts';
				break;
			case 'page':
				$endpoint = 'pages';
				break;
			default:
				// for all CPT
				$endpoint = $post->post_type;
				break;
		endswitch;

		switch ( $post->post_type ):
			case 'page':
				$url = rest_url( "wp/v2/{$endpoint}" ) . '?path=' . urlencode( $path );
				// special case if front page
				if ( in_array( $post->ID, $id_translations ) ):
					$url = rest_url( "wp/v2/{$endpoint}" ) . '?slug=' . $post->post_name;
				endif;

				break;
			default:
				$url = rest_url( "wp/v2/{$endpoint}" ) . '?slug=' . $post->post_name;
				break;
		endswitch;

		if ( function_exists( 'pll_get_post_language' ) ):
			$url .= '&lang=' . pll_get_post_language( $post->ID );
		endif;

		return $url;
	}
endif;

if ( ! function_exists( 'hsp_wp_get_menu_array' ) ):
	/**
	 * Get menu datas
	 */
	function hsp_wp_get_menu_array( $current_menu = 'menu_navigation' ) {
		$menu_array = wp_get_nav_menu_items( $current_menu );
		$menu       = array();

		if ( is_array( $menu_array ) ) :
			foreach ( $menu_array as $m ) :

				if ( empty( $m->menu_item_parent ) ) :
					// clean url
					$url = hsp_clean_url( $m->url );

					$menu[ $m->menu_order . '_' . $m->ID ]             = array();
					$menu[ $m->menu_order . '_' . $m->ID ]['ID']       = $m->ID;
					$menu[ $m->menu_order . '_' . $m->ID ]['title']    = $m->title;
					$menu[ $m->menu_order . '_' . $m->ID ]['url']      = $url;
					$menu[ $m->menu_order . '_' . $m->ID ]['target']   = $m->target;
					$menu[ $m->menu_order . '_' . $m->ID ]['children'] = hsp_populate_children( $menu_array, $m );
				endif;
			endforeach;
		endif;

		return $menu;
	}
endif;

if ( ! function_exists( 'hsp_populate_children' ) ):
	function hsp_populate_children( $menu_array, $menu_item ) {
		$children = array();
		if ( ! empty( $menu_array ) ) :
			foreach ( $menu_array as $k => $m ) :
				if ( $m->menu_item_parent == $menu_item->ID ) :
					// clean url
					$url = hsp_clean_url( $m->url );

					$children[ $m->menu_order . '_' . $m->ID ]           = array();
					$children[ $m->menu_order . '_' . $m->ID ]['ID']     = $m->ID;
					$children[ $m->menu_order . '_' . $m->ID ]['title']  = $m->title;
					$children[ $m->menu_order . '_' . $m->ID ]['url']    = $url;
					$children[ $m->menu_order . '_' . $m->ID ]['target'] = $m->target;
					unset( $menu_array[ $k ] );
					$children[ $m->menu_order . '_' . $m->ID ]['children'] = hsp_populate_children( $menu_array, $m );
				endif;
			endforeach;
		endif;

		return $children;
	}
endif;
// END MENU DATA

if ( ! function_exists( 'query_page_by_path' ) ):
	/**
	 * Filter "rest_page_query" hook to use pagename or force preview
	 */
	function query_page_by_path( $args, $request ) {
		if ( ! empty( $request['path'] ) ) :
			$args['pagename'] = $request['path'];
		endif;
		// custom preview ?
		if ( isset( $request['post_id'] ) && is_int( (int) $request['post_id'] ) && isset( $request['token'] ) && ( $request['token'] === md5( $request['post_id'] . NONCE_SALT ) ) && isset( $request['preview'] ) && $request['preview'] ):
			$args = array(
				'post_status'    => 'any',
				'post_parent'    => intval( $request['post_id'] ),
				'post_type'      => 'revision',
				'orderby'        => 'ID',
				'order'          => 'DESC',
				'posts_per_page' => 1,
				'paged'          => 1,
			);
		endif;

		return $args;
	}
endif;

add_filter( 'rest_page_query', 'query_page_by_path', 10, 2 );
add_filter( 'rest_post_query', 'query_page_by_path', 10, 2 );

if ( ! function_exists( 'get_language_switcher_for_api' ) ):
	/**
	 * Add language switcher to the REST API
	 */
	function get_language_switcher_for_api( $object ) {
		$switcher = array();

		if ( function_exists( 'pll_languages_list' ) ):
			$languages = pll_languages_list();

			// tweak for revision : get the parent id
			if ( 'revision' === $object['type'] && ! empty( $object['parent'] ) ):
				$object['id'] = $object['parent'];
			endif;

			foreach ( $languages as $language_slug ) :
				$translated_id = pll_get_post( $object['id'], $language_slug );
				// check if translation exists
				if ( ! empty( $translated_id ) ):
					// clean url
					$url = hsp_clean_url( get_permalink( $translated_id ) );

					$switcher[ $language_slug ] = array(
						'url'    => $url,
						'label'  => mb_convert_case( $language_slug, MB_CASE_UPPER ),
						'active' => ( $translated_id === $object['id'] ),
					);
				endif;
			endforeach;
		endif;

		//return the post meta
		return $switcher;
	}
endif;

if ( ! function_exists( 'hsp_extend_acf_field' ) ):
	/**
	 * Extend ACF Fields : return all data needed (for relation field for exemple)
	 */
	function hsp_extend_acf_field( $value_formatted, $post_id, $field, $value, $format ) {
		if ( is_array( $value ) ):
			foreach ( $value as $id => $block ):
				if ( 'news' === $block['acf_fc_layout'] ):
					$items = array();

					switch ( $block['field_62960a4a80636'] ):
						case 'manuel':
							// specific format
							foreach ( $block['field_62960a4a8063d']['field_62960a4a8063e'] as $post_id ):
								$item = hsp_get_cpt_data( get_post( $post_id ) );

								if ( ! empty( $item ) ):
									$items[] = $item;
								endif;
							endforeach;

							break;
						case 'automatique':
							// specific format
							$trier_par               = $block['field_62960a4a80638']['field_62960a4a8063a'] ?: 'title';
							$ordre                   = $block['field_62960a4a80638']['field_62960a4a8063b'] ?: 'ASC';
							$nombre_items_a_afficher = $block['field_62960a4a80638']['field_62960a4a8063c'] ?: - 1;

							if ( ! empty( $trier_par ) && ! empty( $ordre ) && ! empty( $nombre_items_a_afficher ) ):
								/**
								 * Get CPT with WP Query
								 */
								$args_query = array(
									'post_type'              => 'news',
									'post_status'            => 'publish',
									'posts_per_page'         => $nombre_items_a_afficher,
									'order'                  => $ordre,
									'orderby'                => $trier_par,
									'no_found_rows'          => true,
									'update_post_meta_cache' => false,
									'update_post_term_cache' => false,
								);

								$query_items = new WP_Query( $args_query );

								if ( $query_items->have_posts() ) :
									while ( $query_items->have_posts() ) :
										$query_items->the_post();
										$item = hsp_get_cpt_data( $query_items->post );

										if ( ! empty( $item ) ):
											$items[] = $item;
										endif;
									endwhile;
								endif;

								wp_reset_postdata();
							endif;
							break;
					endswitch;

					// clean output, keep only formated items
					unset( $value_formatted[ $id ]['automatique'] );
					unset( $value_formatted[ $id ]['manuel'] );

					$value_formatted[ $id ]['items'] = $items;

				elseif ( 'facebook' === $block['acf_fc_layout'] ):
					$items = array();

					switch ( $block['field_629f723507ed1'] ):
						case 'manuel':
							// specific format
							foreach ( $block['field_629f723507ed7']['field_629f723507ed8'] as $post_id ):
								$item = hsp_get_cpt_data( get_post( $post_id ) );

								if ( ! empty( $item ) ):
									$items[] = $item;
								endif;
							endforeach;

							break;
						case 'automatique':
							// specific format
							$trier_par               = $block['field_629f723507ed3']['field_629f723507ed4'] ?: 'title';
							$ordre                   = $block['field_629f723507ed3']['field_629f723507ed5'] ?: 'ASC';
							$nombre_items_a_afficher = $block['field_629f723507ed3']['field_629f723507ed6'] ?: - 1;

							if ( ! empty( $trier_par ) && ! empty( $ordre ) && ! empty( $nombre_items_a_afficher ) ):
								/**
								 * Get CPT with WP Query
								 */
								$args_query = array(
									'post_type'              => 'profile',
									'post_status'            => 'publish',
									'posts_per_page'         => $nombre_items_a_afficher,
									'order'                  => $ordre,
									'orderby'                => $trier_par,
									'no_found_rows'          => true,
									'update_post_meta_cache' => false,
									'update_post_term_cache' => false,
								);

								$query_items = new WP_Query( $args_query );

								if ( $query_items->have_posts() ) :
									while ( $query_items->have_posts() ) :
										$query_items->the_post();
										$item = hsp_get_cpt_data( $query_items->post );

										if ( ! empty( $item ) ):
											$items[] = $item;
										endif;
									endwhile;
								endif;

								wp_reset_postdata();
							endif;
							break;
					endswitch;

					// clean output, keep only formated items
					unset( $value_formatted[ $id ]['automatique'] );
					unset( $value_formatted[ $id ]['manuel'] );

					$value_formatted[ $id ]['items'] = $items;

				elseif ( 'press_releases' === $block['acf_fc_layout'] ) :
					$items_global = array();

					foreach ( $block['field_6266736b8e033'] as $module ):
						$titre = $module['field_626672c38e032'];
						$items = array();

						switch ( $module['field_6255798c4f050'] ):
							case 'manuel':
								// specific format
								foreach ( $module['field_6255798d4f052'] as $post_id ):
									$item = hsp_get_cpt_data( get_post( $post_id ) );

									if ( ! empty( $item ) ):
										$items[] = $item;
									endif;
								endforeach;

								break;
							case 'auto':
								// specific format
								$nombre_items_a_afficher = $module['field_6255798c4f051'] ?: - 1;

								if ( ! empty( $nombre_items_a_afficher ) ):
									/**
									 * Get CPT with WP Query
									 */
									$args_query = array(
										'post_type'              => 'press_release',
										'post_status'            => 'publish',
										'posts_per_page'         => $nombre_items_a_afficher,
										'order'                  => 'DESC',
										'orderby'                => 'date',
										'no_found_rows'          => true,
										'update_post_meta_cache' => false,
										'update_post_term_cache' => false,
									);

									$query_items = new WP_Query( $args_query );

									if ( $query_items->have_posts() ) :
										while ( $query_items->have_posts() ) :
											$query_items->the_post();
											$item = hsp_get_cpt_data( $query_items->post );

											if ( ! empty( $item ) ):
												$items[] = $item;
											endif;
										endwhile;
									endif;

									wp_reset_postdata();
								endif;
								break;
						endswitch;

						$items_global[] = array( 'titre' => $titre, 'items' => $items );
					endforeach;

					// clean output
					unset( $value_formatted[ $id ]['press_releases'] );

					$value_formatted[ $id ]['items_global'] = $items_global;
				endif;
			endforeach;
		endif;

		return $value_formatted;
	}
endif;

add_filter( 'acf/rest/format_value_for_rest', 'hsp_extend_acf_field', 10, 5 );

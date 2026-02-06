<?php
add_action( 'admin_head', 'hide_menu' );
/**
 * masquage des menus de wp-admin non souhaités
 */
if ( ! function_exists( 'hide_menu' ) ) :
	function hide_menu() {
		remove_menu_page( 'edit.php' );                   //Posts
		remove_menu_page( 'edit-comments.php' );          //Comments
	}
endif;

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
			esc_html__( 'Flexible content', 'havas_starter_pack' ), // Title.
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
		esc_html_e( 'Which blocks are used and where?', 'havas_starter_pack' );

		echo( '<br/><br/>' );

		if ( false === ( $flexible_content_summary = get_transient( 'flexible_content_summary_' . get_locale() ) ) || 'r3Sc4n' === $_GET['hsp_scan_flexible'] ) :
			// It wasn't there, so regenerate the data and save the transient
			$flexible_content_summary = hsp_get_flexible_content_summary();
			set_transient( 'flexible_content_summary_' . get_locale(), $flexible_content_summary, MONTH_IN_SECONDS );
		endif;

		echo( '<div id="hsp_accordion">' );

		foreach ( $flexible_content_summary['layouts'] as $layout => $summary ):
			echo( '<h3>' . $layout . ' (' . $flexible_content_summary['global_count'][ $layout ] . ')</h3>' );
			echo( '<div>' );

			foreach ( $summary as $page ):
				if ( $page['count'] > 1 ):
					echo( '<a href="' . esc_url( $page['url'] ) . '">' . sprintf( __( '%d occurences in %s', 'havas_starter_pack' ), $page['count'], $page['label'] ) . '</a><br />' );
				else:
					echo( '<a href="' . esc_url( $page['url'] ) . '">' . sprintf( __( '%d occurence in %s', 'havas_starter_pack' ), $page['count'], $page['label'] ) . '</a><br />' );
				endif;
			endforeach;

			echo( '</div>' );
		endforeach;

		echo( '</div>' );
	}
endif;

if ( ! function_exists( 'hsp_get_flexible_content_summary' ) ):
	function hsp_get_flexible_content_summary() {
		// compare with json file
		$allowed_layout = array();
		$contents       = file_get_contents( get_template_directory() . '/acf-json/group_6234e5f520062.json' );
		$data           = json_decode( $contents, true );

		if ( ! empty( $data['fields'][0]['layouts'] ) ):
			foreach ( $data['fields'][0]['layouts'] as $layout ):
				$allowed_layout[] = $layout['name'];
			endforeach;
		endif;

		// search through CPT where flexible content is used
		$target_cpts = array( 'page' );

		if ( ! empty( $data['location'] ) ):
			foreach ( $data['location'] as $locations ):
				// in case of combined rules
				foreach ( $locations as $location ):
					if ( 'post_type' === $location['param'] && '==' === $location['operator'] ):
						$target_cpts[] = $location['value'];
					endif;
				endforeach;
			endforeach;
		endif;

		$summary = array();
		$query   = new WP_Query( array( 'post_type' => $target_cpts, 'posts_per_page' => - 1 ) );
		// count each block in the whole site
		$global_count = array();

		while ( $query->have_posts() ) : $query->the_post();
			// Vérifie si la page a des blocs flexibles
			if ( have_rows( 'flexible_content' ) ) :
				// Boucle à travers tous les blocs flexibles
				while ( have_rows( 'flexible_content' ) ) : the_row();
					$details = array();

					if ( ! empty( $summary['layouts'][ get_row_layout() ] ) ):
						$details = $summary['layouts'][ get_row_layout() ];
					endif;

					if ( array_key_exists( get_row_layout(), $global_count ) ):
						$global_count[ get_row_layout() ] += 1;
					else:
						$global_count[ get_row_layout() ] = 1;
					endif;

					if ( array_key_exists( get_the_ID(), $details ) ):
						// count this block in each page
						$details[ get_the_ID() ]['count'] += 1;
					else:
						$details[ get_the_ID() ] = array(
							'count' => 1,
							'url'   => get_the_permalink(),
							'label' => get_the_title() . ' (ID:' . get_the_ID() . ')'
						);
					endif;

					$summary['layouts'][ get_row_layout() ] = $details;
				endwhile;
			endif;
		endwhile;

		$summary['global_count'] = $global_count;

		wp_reset_postdata();

		if ( ! empty( $data['fields'][0]['layouts'] ) ):
			foreach ( $summary['layouts'] as $id_layout => $content ):
				if ( ! in_array( $id_layout, $allowed_layout ) ):
					unset( $summary['layouts'][ $id_layout ] );
					unset( $summary['global_count'][ $id_layout ] );
				endif;
			endforeach;
		endif;

		return $summary;
	}
endif;

if ( ! function_exists( 'enqueue_flexible_widget_scripts' ) ):
	function enqueue_flexible_widget_scripts( $hook ) {
		if ( 'index.php' != $hook ) :
			return;
		endif;

		wp_enqueue_script( 'jquery-ui-accordion' );
		wp_enqueue_style( 'jquery-style', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css' );

		wp_enqueue_script( 'flexible_widget', get_template_directory_uri() . '/js/flexible_widget.js', array( 'jquery-ui-accordion' ), time() );
	}
endif;

add_action( 'admin_enqueue_scripts', 'enqueue_flexible_widget_scripts' );

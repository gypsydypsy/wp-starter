<?php
// Remove side menu
add_action( 'admin_menu', 'hsp_remove_default_post_type' );

if ( ! function_exists( 'hsp_remove_default_post_type' ) ):
	function hsp_remove_default_post_type() {
		remove_menu_page( 'edit.php' );
	}
endif;

// Remove +New post in top Admin Menu Bar
add_action( 'admin_bar_menu', 'hsp_remove_default_post_type_menu_bar', 999 );

if ( ! function_exists( 'hsp_remove_default_post_type_menu_bar' ) ):
	function hsp_remove_default_post_type_menu_bar( $wp_admin_bar ) {
		$wp_admin_bar->remove_node( 'new-post' );
	}
endif;

// Remove Quick Draft Dashboard Widget
add_action( 'wp_dashboard_setup', 'hsp_remove_draft_widget', 999 );

if ( ! function_exists( 'hsp_remove_draft_widget' ) ):
	function hsp_remove_draft_widget() {
		remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
	}
endif;

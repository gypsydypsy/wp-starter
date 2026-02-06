<?php
add_action( 'wp_enqueue_scripts', 'remove_inline_styles' );

if ( ! function_exists( 'remove_inline_styles' ) ) :
	function remove_inline_styles() {
		wp_dequeue_style( 'global-styles' );
		wp_dequeue_style( 'safe-svg-svg-icon-style' );
		wp_dequeue_style( 'wpseopress-local-business-style' );
	}
endif;

// __return_true by default, __return_false automatically if JSON breadcrumbs is ON from the settings
add_filter( 'seopress_pro_breadcrumbs_html_markup', '__return_false' );

function sp_pro_breadcrumbs_css() {
	//Disable breadcrumbs inline CSS
	return false;
}
add_action('seopress_pro_breadcrumbs_css', 'sp_pro_breadcrumbs_css');

add_filter('wp_img_tag_add_auto_sizes', '__return_false');

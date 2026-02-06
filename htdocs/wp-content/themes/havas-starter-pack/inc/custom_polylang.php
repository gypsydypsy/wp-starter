<?php
// Add lang parameter to force switch lang when editing post
add_filter( 'get_edit_post_link', 'custom_polylang_add_lang_to_edit_post_link', 20, 3 );

if ( ! function_exists( 'custom_polylang_add_lang_to_edit_post_link' ) ):
	function custom_polylang_add_lang_to_edit_post_link( $link, $post_id, $context ) {
		if ( function_exists( 'pll_get_post_language' ) ):
			// add param lang to link
			$link = add_query_arg( 'lang', pll_get_post_language( $post_id ), $link );
		endif;

		return $link;
	}
endif;

add_filter( 'pll_get_new_post_translation_link', 'custom_polylang_add_lang_to_new_post_link', 20, 3 );

if ( ! function_exists( 'custom_polylang_add_lang_to_new_post_link' ) ):
	function custom_polylang_add_lang_to_new_post_link( $link, $language, $post_id ) {
		return add_query_arg( 'lang', $language->slug, $link );
	}
endif;

// add filter to disable default value option
add_filter( 'bea.aofp.get_default', '__return_false' );

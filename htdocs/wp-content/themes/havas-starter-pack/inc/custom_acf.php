<?php
add_filter( 'acf/settings/rest_api_format', function () {
	return 'standard';
} );

add_action( 'acf/render_field/name=anchor', 'hsp_custom_anchor' );

if ( ! function_exists( 'hsp_custom_anchor' ) ):
	function hsp_custom_anchor( $field ) {
		if ( ! empty( $field['value'] ) ):
			global $post;
			$permalink = get_permalink( $post->ID );

			if ( ! empty( $permalink ) ):
				echo( '<p><a href="' . esc_url( $permalink . '#' . sanitize_title( $field['value'] ) ) . '" target="_blank">' . $permalink . '#' . sanitize_title( $field['value'] ) . '</a></p>' );
			endif;
		endif;
	}
endif;

add_filter( 'acf/format_value/type=wysiwyg', 'format_wysiwyg_acf_field', 20, 1 );

if ( ! function_exists( 'format_wysiwyg_acf_field' ) ):
	function format_wysiwyg_acf_field( $content ) {
		/**
		 * Accessibility
		 * Regular expression pattern used for matching specific string formats.
		 *
		 * This variable contains a regular expression that can be used to validate,
		 * search, or extract information from strings based on predefined criteria.
		 *
		 * Remplace exactement <p>&nbsp;</p> par <div class="spacer"></div>
		 * Remplace exactement <em>...</em> by <i>...</i>
		 */
		$pattern     = '/<p>\s*&nbsp;\s*<\/p>/i';
		$replacement = '<div class="spacer"></div>';
		$content     = preg_replace( $pattern, $replacement, $content );

		$content = str_replace( '<em>', '<i>', $content );
		$content = str_replace( '</em>', '</i>', $content );

		return $content;
	}
endif;

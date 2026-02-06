<?php
add_filter( 'acf/validate_value/name=settings_reseaux_sociaux', 'hsp_validate_settings_reseaux_sociaux', 10, 4 );

if ( ! function_exists( 'hsp_validate_settings_reseaux_sociaux' ) ):
	function hsp_validate_settings_reseaux_sociaux( $valid, $value, $field, $input_name ) {
		// Bail early if value is already invalid.
		if ( $valid !== true ) :
			return $valid;
		endif;
		// check for duplicate value of field_62558dceb36d6
		$checked = array();

		foreach ( $value as $item ):
			if ( in_array( $item['field_62558dceb36d6'], $checked ) ):
				return __( 'Attention, réseau social dupliqué.', 'havas_starter_pack_gutenberg' );
			else:
				$checked[] = $item['field_62558dceb36d6'];
			endif;
		endforeach;

		return $valid;
	}
endif;

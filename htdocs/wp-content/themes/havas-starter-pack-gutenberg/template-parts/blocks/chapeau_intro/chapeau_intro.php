<?php
/**
 * Chapeau Intro Block Template.
 *
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML (empty).
 * @param bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

if ( ! get_field( 'masquer_ce_bloc_en_front_office' ) ):
    // Create id attribute allowing for custom "anchor" value.
	$id = 'chapeau-intro-' . $block['id'];

	if ( ! empty( $block['anchor'] ) ) {
		$id = $block['anchor'];
	}

	// Load values and assign defaults if required
	$chapeau = get_field( 'chapeau' ) ?: __( 'Chapeau : lorem ipsum dolor sit amet', 'havas_starter_pack_gutenberg' );
	?>
    <div class="f f-chapeau" id="<?php echo esc_attr( $id ); ?>">
        <div class="container">
            <div class="f-chapeau__ctn"><?php echo $chapeau; ?></div>
        </div>
    </div>
<?php
endif;

<?php
/**
 * Réseaux sociaux Block Template.
 *
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML (empty).
 * @param bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

if ( ! get_field( 'masquer_ce_bloc_en_front_office' ) ):
	// Create id attribute allowing for custom "anchor" value.
	$id = 'reseaux-sociaux-' . $block['id'];

	if ( ! empty( $block['anchor'] ) ) {
		$id = $block['anchor'];
	}

	// Load values and assign defaults if required
	$titre_block = get_field( 'titre_block' );
	?>
    <div class="f f-social" id="<?php echo esc_attr( $id ); ?>">
        <div class="container medium--">
			<?php if ( ! empty( $titre_block ) ): ?>
                <h2 class="h2"><?php echo( $titre_block ); ?></h2>
			<?php endif; ?>
			<?php
			// check if the repeater field has rows of data
			if ( have_rows( 'settings_reseaux_sociaux', 'option' ) ):
				?>
                <div class="f-social__list">
                    <ul class="c-social">
						<?php
						// loop through the rows of data
						while ( have_rows( 'settings_reseaux_sociaux', 'option' ) ) :
							the_row();

							$reseau   = get_sub_field( 'reseau' );
							$url_page = get_sub_field( 'url_page' );

							if ( ! empty( $reseau ) && ! empty( $url_page ) ):
								?>
                                <li><a href="<?php echo( esc_url( $url_page ) ); ?>" title="<?php echo( esc_attr( $reseau ) ); ?>" target="_blank" rel="nofollow"><i class="icon-<?php echo( $reseau ); ?>"></i></a></li>
							<?php
							endif;
						endwhile;
						?>
                    </ul>
                </div>
			<?php
			endif;
			?>
        </div>
    </div>
<?php
endif;

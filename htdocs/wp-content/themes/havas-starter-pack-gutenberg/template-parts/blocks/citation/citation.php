<?php
/**
 * Citation Block Template.
 *
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML (empty).
 * @param bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

if ( ! get_field( 'masquer_ce_bloc_en_front_office' ) ):
	// Create id attribute allowing for custom "anchor" value.
	$id = 'citation-' . $block['id'];

	if ( ! empty( $block['anchor'] ) ) {
		$id = $block['anchor'];
	}

	// Load values and assign defaults if required
	$titre_block       = get_field( 'titre_block' );
	$image             = get_field( 'image' );
	$citation          = get_field( 'citation' ) ?: __( 'Vous ne devez jamais avoir peur de ce que vous faites quand vous faites ce qui est juste.', 'havas_starter_pack_gutenberg' );
	$auteur_prenom_nom = get_field( 'auteur_prenom_nom' ) ?: __( 'Rosa Parks', 'havas_starter_pack_gutenberg' );
	$auteur_fonction   = get_field( 'auteur_fonction' );
	?>
    <div class="f f-quote" id="<?php echo esc_attr( $id ); ?>">
        <div class="container medium--">
			<?php if ( ! empty( $titre_block ) ): ?>
                <h2 class="h2"><?php echo( $titre_block ); ?></h2>
			<?php endif; ?>
            <div class="f-quote__ctn">
				<?php
				if ( ! empty( $image ) ):
					?>
                    <div class="f-quote__ctn-img">
                        <div class="c-img">
                            <figure>
                                <div class="c-img__radius">
                                    <img src="<?php echo( esc_url( $image['url'] ) ); ?>" alt="<?php echo( esc_attr( $image['alt'] ) ); ?>">
                                </div>
								<?php if ( ! empty( $image['caption'] ) ): ?>
                                    <figcaption><?php echo( $image['caption'] ); ?></figcaption>
								<?php endif; ?>
                            </figure>
                        </div>
                    </div>
				<?php
				endif;
				?>
                <div class="f-quote__ctn-text">
                    <div class="f-quote__ctn-text-quote"><?php echo( $citation ); ?></div>
                    <div class="f-quote__ctn-text-author">
						<?php
						echo( $auteur_prenom_nom );

						if ( ! empty( $auteur_fonction ) ):
							?>
                            <span><?php echo( $auteur_fonction ); ?></span>
						<?php
						endif;
						?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
endif;

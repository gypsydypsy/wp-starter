<?php
/**
 * Chiffres clés Block Template.
 *
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML (empty).
 * @param bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

if ( ! get_field( 'masquer_ce_bloc_en_front_office' ) ):
	// Create id attribute allowing for custom "anchor" value.
	$id = 'chiffres-cles-' . $block['id'];

	if ( ! empty( $block['anchor'] ) ) {
		$id = $block['anchor'];
	}

	// Load values and assign defaults if required
	$titre_block = get_field( 'titre_block' );
	?>
    <div class="f f-keyfigures" id="<?php echo esc_attr( $id ); ?>">
		<?php if ( ! empty( $titre_block ) ): ?>
            <div class="container medium--">
                <h2 class="h2"><?php echo( $titre_block ); ?></h2>
            </div>
		<?php endif; ?>
		<?php
		// check if the repeater field has rows of data
		if ( have_rows( 'chiffres_cles' ) ):
			?>
            <div class="container">
                <div class="f-keyfigures__ctn">
					<?php
					// loop through the rows of data
					while ( have_rows( 'chiffres_cles' ) ) :
						the_row();

						$picto = get_sub_field( 'picto' );
						?>
                        <div class="f-keyfigures__ctn-item">
							<?php
							if ( ! empty( $picto ) ):
								?>
                                <div class="f-keyfigures__ctn-item-img">
                                    <div class="c-img">
                                        <div class="c-img__radius">
                                            <img src="<?php echo( esc_url( $picto['url'] ) ); ?>" alt="<?php echo( esc_attr( $picto['alt'] ) ); ?>">
                                        </div>
                                    </div>
                                </div>
							<?php
							endif;
							?>
                            <h3 class="h3">
								<?php echo( get_sub_field( 'surtitre' ) ?: '' ); ?>
                                <span><?php the_sub_field( 'chiffre_cle' ); ?></span>
								<?php echo( get_sub_field( 'sous_titre' ) ?: '' ); ?>
                            </h3>
                            <div class="f-keyfigures__ctn-item-content c-wysiwyg">
								<?php the_sub_field( 'texte' ); ?>
                            </div>
                        </div>
					<?php
					endwhile;
					?>
                </div>
            </div>
		<?php
		endif;
		?>
    </div>
<?php
endif;

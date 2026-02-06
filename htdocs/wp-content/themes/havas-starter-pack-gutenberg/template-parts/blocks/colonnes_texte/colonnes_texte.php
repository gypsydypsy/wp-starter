<?php
/**
 * Colonne(s) texte Block Template.
 *
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML (empty).
 * @param bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

if ( ! get_field( 'masquer_ce_bloc_en_front_office' ) ):
// Create id attribute allowing for custom "anchor" value.
	$id = 'colonne-texte-' . $block['id'];

	if ( ! empty( $block['anchor'] ) ) {
		$id = $block['anchor'];
	}

	// Load values and assign defaults if required
	$titre_block = get_field( 'titre_block' );

    // check if the repeater field has rows of data
	if ( have_rows( 'colonnes' ) ):
		$col_count = count( get_field( 'colonnes' ) );
		?>
        <div class="f f-wysiwyg" id="<?php echo( esc_attr( $id ) ); ?>">
            <div class="container<?php echo $col_count < 3 ? ' medium--' : ''; ?>">
				<?php if ( ! empty( $titre_block ) ): ?>
                    <h2 class="h2"><?php echo( $titre_block ); ?></h2>
				<?php endif; ?>
                <div class="f-wysiwyg__column" data-colcount="<?php echo( esc_attr( $col_count ) ); ?>">
					<?php
					// loop through the rows of data
					while ( have_rows( 'colonnes' ) ) :
						the_row();

						$texte = get_sub_field( 'texte' );

						if ( ! empty( $texte ) ):
							?>
                            <div class="f-wysiwyg__column-content c-wysiwyg">
								<?php echo( $texte ); ?>
                            </div>
						<?php
						endif;
					endwhile;
					?>
                </div>
            </div>
        </div>
	<?php
	endif;
endif;

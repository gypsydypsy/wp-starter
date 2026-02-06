<?php
/**
 * Image(s) 1 ou 2 colonnes Block Template.
 *
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML (empty).
 * @param bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

if ( ! get_field( 'masquer_ce_bloc_en_front_office' ) ):
	// Create id attribute allowing for custom "anchor" value.
	$id = 'images-colonnes' . $block['id'];

	if ( ! empty( $block['anchor'] ) ) {
		$id = $block['anchor'];
	}

	// Load values and assign defaults if required
	$titre_block        = get_field( 'titre_block' );
	$nombre_de_colonnes = get_field( 'nombre_de_colonnes' );

	if ( 'une' === $nombre_de_colonnes ):
		$class_container = 'container';
	else:
		$class_container = 'container medium--';
	endif;

	// check if the repeater field has rows of data
	if ( have_rows( 'images' ) ):
		?>
        <div class="f f-images" id="<?php echo esc_attr( $id ); ?>">
			<?php if ( ! empty( $titre_block ) ): ?>
                <div class="container medium--">
                    <h2 class="h2"><?php echo( $titre_block ); ?></h2>
                </div>
			<?php endif; ?>
            <div class="<?php echo( esc_attr( $class_container ) ); ?>">
                <ul class="f-images__list">
					<?php
					// loop through the rows of data
					while ( have_rows( 'images' ) ) :
						the_row();

						$image = get_sub_field( 'image' );
						// $titre = get_sub_field( 'titre' ); not currently used

						if ( ! empty( $image ) ):
							?>
                            <li>
                                <div class="c-img">
                                    <figure>
                                        <img src="<?php echo( esc_url( $image['url'] ) ); ?>" alt="<?php echo( esc_attr( $image['alt'] ) ); ?>">
										<?php if ( ! empty( $image['caption'] ) ): ?>
                                            <figcaption><?php echo( $image['caption'] ); ?></figcaption>
										<?php endif; ?>
                                    </figure>
                                </div>
                            </li>
						<?php
						endif;
					endwhile;
					?>
                </ul>
            </div>
        </div>
	<?php
	endif;
endif;

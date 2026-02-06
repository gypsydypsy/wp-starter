<?php
/**
 * Accordéon Block Template.
 *
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML (empty).
 * @param bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

if ( ! get_field( 'masquer_ce_bloc_en_front_office' ) ):
	// Create id attribute allowing for custom "anchor" value.
	$id = 'accordeon-' . $block['id'];

	if ( ! empty( $block['anchor'] ) ) {
		$id = $block['anchor'];
	}

	// Load values and assign defaults if required
	$titre_block = get_field( 'titre_block' );

	// check if the repeater field has rows of data
	if ( have_rows( 'accordeon' ) ):
		?>
        <div class="f f-accordion">
			<?php if ( ! empty( $titre_block ) ): ?>
                <div class="container medium--">
                    <h2 class="h2"><?php echo( $titre_block ); ?></h2>
                </div>
			<?php endif; ?>
            <div class="container">
                <div class="f-accordion__list">
					<?php
					// loop through the rows of data
					while ( have_rows( 'accordeon' ) ) :
						the_row();
						?>
                        <div class="f-accordion__list-item">
                            <button class="f-accordion__list-item-title toggle-button"><?php the_sub_field( 'titre' ); ?></button>
                            <div class="f-accordion__list-item-content toggle-content">
                                <div class="c-wysiwyg"><?php the_sub_field( 'contenu' ); ?></div>
                            </div>
                        </div>
					<?php
					endwhile;
					?>
                </div>
            </div>
        </div>
	<?php
	endif;
endif;

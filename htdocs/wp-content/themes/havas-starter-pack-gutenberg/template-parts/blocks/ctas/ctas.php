<?php
/**
 * CTAs Block Template.
 *
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML (empty).
 * @param bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

if ( ! get_field( 'masquer_ce_bloc_en_front_office' ) ):
	// Create id attribute allowing for custom "anchor" value.
	$id = 'ctas-' . $block['id'];

	if ( ! empty( $block['anchor'] ) ) {
		$id = $block['anchor'];
	}

	// Load values and assign defaults if required
	$titre_block = get_field( 'titre_block' );
	$alignement  = get_field( 'alignement' );
	// Match css
	$css_alignement = '';
	$allowed_css    = array( 'left', 'right' );

	if ( in_array( $alignement, $allowed_css ) ):
		$css_alignement = ' ' . $alignement;
	endif;

	$cta_allowed_css = array(
		'gelule'         => ' bg--',
		'souligne'       => '',
		'fleche'         => ' arrow--',
		'telechargement' => ' download--',
	);

	// check if the repeater field has rows of data
	if ( have_rows( 'ctas' ) ):
		?>
        <div class="f f-ctas" id="<?php echo esc_attr( $id ); ?>">
            <div class="container">
				<?php if ( ! empty( $titre_block ) ): ?>
                    <div class="container medium--">
                        <h2 class="h2"><?php echo( $titre_block ); ?></h2>
                    </div>
				<?php endif; ?>
                <div class="f-ctas__ctn c-wysiwyg<?php echo( $css_alignement ); ?>">
					<?php
					// loop through the rows of data
					while ( have_rows( 'ctas' ) ) :
						the_row();

						$style_graphique    = get_sub_field( 'style_graphique' ) ?: 'souligne';
						$lien               = get_sub_field( 'cta' );
						$cta_css_alignement = '';

						if ( ! empty( $lien ) ):
							$link_url = $lien['url'];
							$link_title     = $lien['title'];
							$link_target    = $lien['target'] ? $lien['target'] : '_self';

							if ( array_key_exists( $style_graphique, $cta_allowed_css ) ):
								$cta_css_alignement = $cta_allowed_css[ $style_graphique ];
							endif;
							?>
                            <a href="<?php echo( esc_url( $link_url ) ); ?>" class="c-button<?php echo( $cta_css_alignement ); ?>" target="<?php echo( esc_attr( $link_target ) ); ?>" title="<?php echo( esc_attr( $link_title ) ); ?>"><?php echo( esc_html( $link_title ) ); ?></a>
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

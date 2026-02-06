<?php
/**
 * Image + Texte Block Template.
 *
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML (empty).
 * @param bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

if ( ! get_field( 'masquer_ce_bloc_en_front_office' ) ):
	// Create id attribute allowing for custom "anchor" value.
	$id = 'image-texte-' . $block['id'];

	if ( ! empty( $block['anchor'] ) ) {
		$id = $block['anchor'];
	}

	// Load values and assign defaults if required
	$titre_block = get_field( 'titre_block' );
	$disposition = get_field( 'disposition' ) ?: 'image_gauche_et_texte_droite';
	$image       = get_field( 'image' );
	$texte       = get_field( 'texte' ) ?: __( 'contenu WYSIWYG : lorem ipsum dolor sit amet', 'havas_starter_pack_gutenberg' );
	$legende 	 = get_field('legende');
	?>
    <div class="f f-imgText" id="<?php echo esc_attr( $id ); ?>">
        <div class="container medium--">
			<?php if ( ! empty( $titre_block ) ): ?>
                <h2 class="h2"><?php echo( $titre_block ); ?></h2>
			<?php endif; ?>
            <div class="f-imgText__ctn<?php echo $disposition !== 'image_gauche_et_texte_droite' ? ' reverse--' : ''; ?>">
                <div class="f-imgText__ctn-img">
					<?php
					if ( ! empty( $image ) ):
						?>
                        <div class="c-img">
                            <figure>
                                <img src="<?php echo( esc_url( $image['url'] ) ); ?>" alt="<?php echo( esc_attr( $image['alt'] ) ); ?>">
								<?php if ( ! empty( $image['caption'] ) ): ?>
                                    <figcaption><?php echo( $image['caption'] ); ?></figcaption>
								<?php endif; ?>
                            </figure>
                        </div>
					<?php
					endif;
					?>
                </div>
                <div class="f-imgText__ctn-text c-wysiwyg">
					<?php echo( $texte ) ?>
                </div>
            </div>
        </div>
    </div>
<?php
endif;

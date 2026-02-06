<?php
/**
 * Slider images Block Template.
 *
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML (empty).
 * @param bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

if ( ! get_field( 'masquer_ce_bloc_en_front_office' ) ):
	// Create id attribute allowing for custom "anchor" value.
	$id = 'slider-images-' . $block['id'];

	if ( ! empty( $block['anchor'] ) ) {
		$id = $block['anchor'];
	}

	// Load values and assign defaults if required
	$titre_block          = get_field( 'titre_block' );
	$affichage_des_images = get_field( 'affichage_des_images' ) ?: 'single--';
	$type_de_navigation   = get_field( 'type_de_navigation' ) ?: 'fleches';

	// check if the repeater field has rows of data
	if ( have_rows( 'images' ) ):
		?>
        <div class="f f-slider <?php echo( esc_attr( $affichage_des_images ) ); ?>" id="<?php echo( esc_attr( $id ) ); ?>">
			<?php if ( ! empty( $titre_block ) ): ?>
                <div class="container">
                    <h2 class="h2"><?php echo( $titre_block ); ?></h2>
                </div>
			<?php endif; ?>
			<?php
			$start_tag            = '';
			$end_tag              = '';
			$start_tag_navigation = '';
			$end_tag_navigation   = '';

			if ( 'single--' === $affichage_des_images ):
				$start_tag = '<div class="container small--">';
				$end_tag   = '</div>';
            elseif ( 'multi--' === $affichage_des_images ):
				$start_tag_navigation = '<div class="container">';
				$end_tag_navigation   = '</div>';
			endif;

			echo( $start_tag );
			?>
            <div class="f-slider__ctn swiper" data-navigation="<?php echo( esc_attr( $type_de_navigation ) ); ?>">
				<?php if ( 'fleches' === $type_de_navigation ): ?>
					<?php echo( $start_tag_navigation ); ?>
                    <div class="f-slider__navigation">
                        <button class="f-slider__navigation-prev swiper-button-prev" type="button"
                                title="<?php esc_attr_e( 'Naviguer vers la slide précédente', 'havas_starter_pack_gutenberg' ); ?>"><i class="icon-arrow-left"></i></button>
                        <button class="f-slider__navigation-next swiper-button-next" type="button"
                                title="<?php esc_attr_e( 'Naviguer vers la slide suivante', 'havas_starter_pack_gutenberg' ); ?>"><i class="icon-arrow-right"></i></button>
                    </div>
					<?php echo( $end_tag_navigation ); ?>
				<?php endif; ?>

                <div class="swiper-wrapper">
					<?php
					// loop through the rows of data
					while ( have_rows( 'images' ) ) :
						the_row();

						$image             = get_sub_field( 'image' );
						$lien              = get_sub_field( 'lien' );

						if ( ! empty( $image ) ):
							$start_tag_slide = '<div class="c-img">';
							$end_tag_slide = '</div>';

							if ( ! empty( $lien ) ):
								$link_url    = $lien['url'];
								$link_title  = $lien['title'];
								$link_target = $lien['target'] ? $lien['target'] : '_self';

								$start_tag_slide = '<a href="' . esc_url( $link_url ) . '" target="' . esc_attr( $link_target ) . '" title="' . esc_attr( $link_title ) . '" class="c-img">';
								$end_tag_slide   = '</a>';
							endif;
							?>
                            <div class="swiper-slide">
                                <div class="f-slider__ctn-item">
									<?php echo( $start_tag_slide ); ?>
                                    <figure>
                                        <img src="<?php echo( esc_url( $image['url'] ) ); ?>" alt="<?php echo( esc_attr( $image['alt'] ) ); ?>">
										<?php if ( ! empty( $image['caption'] ) ): ?>
                                            <figcaption><?php echo( $image['caption'] ); ?></figcaption>
										<?php endif; ?>
                                    </figure>
									<?php echo( $end_tag_slide ); ?>
                                </div>
                            </div>
						<?php
						endif;
					endwhile;
					?>
                </div>
				<?php if ( 'scrollbar' === $type_de_navigation ): ?>
                    <div class="f-slider__scrollbar swiper-scrollbar"></div>
				<?php endif; ?>
            </div>
			<?php
			echo( $end_tag );
			?>
        </div>
	<?php
	endif;
endif;

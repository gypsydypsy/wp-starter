<?php
/**
 * Slider type 4 Block Template.
 *
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML (empty).
 * @param bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

if ( ! get_field( 'masquer_ce_bloc_en_front_office' ) ):
	// Create id attribute allowing for custom "anchor" value.
	$id = 'slider-type4-' . $block['id'];

	if ( ! empty( $block['anchor'] ) ) {
		$id = $block['anchor'];
	}

	// Load values and assign defaults if required
	$titre_block        = get_field( 'titre_block' );
	$texte              = get_field( 'texte' );
	$disposition        = get_field( 'disposition' ) ?: 'image_gauche_et_texte_droite';
	$type_de_navigation = get_field( 'type_de_navigation' ) ?: 'fleches';

	// check if the repeater field has rows of data
	if ( have_rows( 'images' ) ):
		?>
        <div class="f f-slider single-- textImgSlider--" id="<?php echo( esc_attr( $id ) ); ?>">
			<?php if ( ! empty( $titre_block ) ): ?>
                <div class="container medium--">
                    <h2 class="h2"><?php echo( $titre_block ); ?></h2>
                </div>
			<?php endif; ?>
            <div class="container">
                <div class="f-imgText__ctn<?php echo $disposition !== 'image_gauche_et_texte_droite' ? ' reverse--' : ''; ?>">
                    <div class="f-imgText__ctn-img">
                        <div class="f-slider__ctn swiper" data-navigation="<?php echo( esc_attr( $type_de_navigation ) ); ?>">
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
                        </div>
						<?php if ( 'fleches' === $type_de_navigation ): ?>
                            <div class="f-slider__navigation">
                                <button class="f-slider__navigation-prev swiper-button-prev" type="button"
                                        title="<?php esc_attr_e( 'Naviguer vers la slide précédente', 'havas_starter_pack_gutenberg' ); ?>"><i class="icon-arrow-left"></i></button>
                                <button class="f-slider__navigation-next swiper-button-next" type="button"
                                        title="<?php esc_attr_e( 'Naviguer vers la slide suivante', 'havas_starter_pack_gutenberg' ); ?>"><i class="icon-arrow-right"></i></button>
                            </div>
						<?php endif; ?>
						<?php if ( 'scrollbar' === $type_de_navigation ): ?>
                            <div class="f-slider__scrollbar swiper-scrollbar"></div>
						<?php endif; ?>
                    </div>
                </div>
                <div class="f-imgText__ctn-text">
					<?php echo( $texte ); ?>
                </div>
            </div>
        </div>
        </div>
	<?php
	endif;
endif;

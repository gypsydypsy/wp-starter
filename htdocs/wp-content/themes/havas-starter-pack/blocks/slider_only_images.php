<?php
// Block slider : only image(s)
if ( ! ( $args['hide_block'] ) ):
	if ( $args['layout'] === 'single' ):
		$class_slider               = 'f f-slider single--';
		$class_container            = 'container small--';
		$class_container_navigation = '';
	else:
		$class_slider               = 'f f-slider multi--';
		$class_container            = '';
		$class_container_navigation = 'container';
	endif;

	$background_color_css = '';
	$anchor               = '';

	if ( ! empty( $args['background_color'] ) ):
		$background_color_css = ' data-bg="' . esc_attr( $args['background_color'] ) . '"';
	endif;

	if ( ! empty( $args['anchor'] ) ):
		$anchor = ' id="' . esc_attr( sanitize_title( $args['anchor'] ) ) . '"';
	endif;
	?>
    <div class="<?php echo( esc_attr( $class_slider ) ); ?>"<?php echo( $background_color_css ); ?><?php echo( $anchor ); ?>>
		<?php if ( ! empty( $args['section_title'] ) ): ?>
            <div class="container medium--">
                <h2 class="h2"><?php echo( $args['section_title'] ); ?></h2>
            </div>
		<?php endif;

		if ( ! empty( $args['slides'] ) ):
			if ( 'container small--' === $class_container ):
				?>
                <div class="<?php echo( esc_attr( $class_container ) ); ?>">
			<?php endif; ?>
            <div class="f-slider__ctn swiper" data-navigation="<?php echo( esc_attr( $args['sliding_type'] ) ); ?>" data-pagination="<?php echo ! empty( $args['is_pagination'] ) ? 'true' : 'false'; ?>" aria-roledescription="<?php esc_attr_e( 'carousel', 'havas_starter_pack' ); ?>" aria-label="<?php echo( esc_attr( $args['slider_title'] ) ); ?>">
				<?php if ( 'arrows' === $args['sliding_type'] ):
					if ( 'container' === $class_container_navigation ): ?>
                        <div class="<?php echo( esc_attr( $class_container_navigation ) ); ?>">
					<?php endif; ?>
                    <div class="f-slider__navigation" role="group" aria-label="<?php esc_attr_e( "Slider controls", 'havas_starter_pack' ) ?>">
                        <button class="f-slider__navigation-prev swiper-button-prev" type="button" data-label="<?php esc_attr_e( 'Navigate to previous slide', 'havas_starter_pack' ) ?>"><span aria-hidden="true" class="icon-arrow-left"></span></button>
                        <button class="f-slider__navigation-next swiper-button-next" type="button" data-label="<?php esc_attr_e( 'Navigate to next slide', 'havas_starter_pack' ) ?>"><span aria-hidden="true" class="icon-arrow-right"></span></button>
                    </div>
					<?php if ( 'container' === $class_container_navigation ): ?>
                    </div>
				<?php endif; ?>
				<?php endif; ?>
                <div class="swiper-wrapper">
					<?php foreach ( $args['slides'] as $item ): ?>
                        <div class="swiper-slide" role="group" aria-roledescription="<?php esc_attr_e( 'slide', 'havas_starter_pack' ); ?>">
                            <div class="f-slider__ctn-item">
								<?php
								$cta = $item['cta'] ?: '';

								if ( ! empty( $cta ) ):
									$link_url    = $cta['url'];
									$link_title  = $cta['title'];
									$link_target = $cta['target'] ?: '_self';

									$start_tag = '<a href="' . esc_url( $link_url ) . '" target="' . esc_attr( $link_target ) . '" title="' . esc_attr( $link_title ) . '"><div class="c-img">';
									$end_tag   = '</div></a>';
								else:
									$start_tag = '<div class="c-img">';
									$end_tag   = '</div>';
								endif;

								echo( $start_tag );
								genere_markup_image( $item['image'], 'todo', 1320, 840, 200, true );
								echo( $end_tag );
								?>
                            </div>
                        </div>
					<?php endforeach; ?>
                </div>
				<?php if ( 'scrollbar' === $args['sliding_type'] ): ?>
                    <div class="f-slider__scrollbar swiper-scrollbar"></div>
				<?php endif; ?>

				<?php if ( $args['is_pagination'] ) : ?>
                    <ul class="f-slider__pagination" aria-label="<?php esc_attr_e( "Slider pagination", 'havas_starter_pack' ) ?>" data-label="<?php esc_attr_e( 'Navigate to slide', 'havas_starter_pack' ) ?>" role="list"></ul>
				<?php endif; ?>

            </div>
			<?php if ( 'container small--' === $class_container ): ?>
            </div>
		<?php endif; ?>
		<?php endif; ?>
    </div>
<?php
endif;

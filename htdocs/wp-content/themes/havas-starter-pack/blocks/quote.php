<?php
// Block quote
if ( ! ( $args['hide_block'] ) ):
	$background_color_css = '';
	$anchor = '';

	if ( ! empty( $args['background_color'] ) ):
		$background_color_css = ' data-bg="' . esc_attr( $args['background_color'] ) . '"';
	endif;

	if ( ! empty( $args['anchor'] ) ):
		$anchor = ' id="' . esc_attr( sanitize_title( $args['anchor'] ) ) . '"';
	endif;
	?>
    <div class="f f-quote"<?php echo( $background_color_css ); ?><?php echo( $anchor ); ?>>
        <div class="container medium--">
			<?php if ( ! empty( $args['section_title'] ) ): ?>
                <h2 class="h2"><?php echo( $args['section_title'] ); ?></h2>
			<?php endif; ?>
            <div class="f-quote__ctn">
				<?php if ( ! empty( $args['photo_portrait'] ) ): ?>
                    <div class="f-quote__ctn-img">
                        <div class="c-img">
                            <figure id="<?php echo( esc_attr( $args['photo_portrait']['ID'] ) ); ?>" role="figure" aria-label="<?php echo( esc_attr( ! empty( $args['photo_portrait']['caption'] ) ? $args['photo_portrait']['caption'] : '' ) ); ?>">
                                <div class="c-img__radius">
                                    <img src="<?php echo( esc_url( $args['photo_portrait']['url'] ) ); ?>" alt="<?php echo( esc_attr( $args['photo_portrait']['alt'] ) ); ?>" loading="lazy"/>
                                </div>
                            </figure>
                        </div>
                    </div>
				<?php endif; ?>
                <figure class="f-quote__ctn-text">
                    <blockquote class="f-quote__ctn-text-quote"><?php echo( $args['quote'] ); ?></blockquote>
                    <figcaption class="f-quote__ctn-text-author">
						<?php echo( $args['author']['firstname_name'] ); ?>
                        <span><?php echo( $args['author']['position'] ); ?></span>
                    </figcaption>
                </figure>
            </div>
        </div>
    </div>
<?php
endif;

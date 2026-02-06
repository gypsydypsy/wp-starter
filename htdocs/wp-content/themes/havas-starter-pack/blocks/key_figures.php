<?php
//Block key figures
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
    <div class="f f-keyfigures"<?php echo( $background_color_css ); ?><?php echo( $anchor ); ?>>
		<?php if ( ! empty( $args['section_title'] ) ): ?>
            <div class="container medium--">
                <h2 class="h2"><?php echo( $args['section_title'] ); ?></h2>
            </div>
		<?php endif; ?>
		<?php if ( ! empty( $args['key_figures'] ) ): ?>
            <div class="container">
                <div class="f-keyfigures__ctn">
					<?php foreach ( $args['key_figures'] as $item ) : ?>
                        <div class="f-keyfigures__ctn-item">
							<?php if ( $item['pictogram'] && ! empty( $item['pictogram']['url'] ) ): ?>
                                <div class="f-keyfigures__ctn-item-img">
                                    <div class="c-img">
                                        <div class="c-img__radius">
                                            <img src="<?php echo( esc_url( $item['pictogram']['url'] ) ); ?>" alt="<?php echo( esc_attr( $item['pictogram']['alt'] ) ); ?>" loading="lazy"/>
                                        </div>
                                    </div>
                                </div>
							<?php endif; ?>
                            <h3 class="h3">
								<?php if ( ! empty( $item['suptitle'] ) ) : ?>
									<?php echo( $item['suptitle'] ); ?>
								<?php endif; ?>
                                <span><?php echo( $item['key_figure'] ); ?></span>
								<?php if ( ! empty( $item['subtitle'] ) ) : ?>
									<?php echo( $item['subtitle'] ); ?>
								<?php endif; ?>
                            </h3>
                            <div class="f-keyfigures__ctn-item-content c-wysiwyg">
								<?php echo( $item['text'] ); ?>
                            </div>
                        </div>
					<?php endforeach; ?>
                </div>
            </div>
		<?php endif; ?>
    </div>
<?php
endif;

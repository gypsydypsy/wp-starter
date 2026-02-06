<?php
//Block Image + Text
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
    <div class="f f-imgText"<?php echo( $background_color_css ); ?><?php echo( $anchor ); ?>>
        <div class="container medium--">
			<?php if ( ! empty( $args['section_title'] ) ): ?>
                <h2 class="h2"><?php echo( $args['section_title'] ); ?></h2>
			<?php endif; ?>
            <div class="f-imgText__ctn<?php echo ( $args['layout'] ) === 'left-right' ? '' : ' reverse--'; ?>">
                <div class="f-imgText__ctn-img">
                    <div class="c-img">
						<?php
						if ( ! empty( $args['image'] ) ):
							generate_markup_image_by_sizes( $args['image'], 'w720', 'w1414', 768, 200, true );
						endif;
						?>
                    </div>
                </div>
				<?php if ( ! empty( $args['text'] ) ): ?>
                    <div class="f-imgText__ctn-text c-wysiwyg">
						<?php echo( $args['text'] ); ?>
                    </div>
				<?php endif; ?>
            </div>
        </div>
    </div>
<?php
endif;

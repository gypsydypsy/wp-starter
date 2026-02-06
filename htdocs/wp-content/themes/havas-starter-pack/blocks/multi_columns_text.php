<?php
// Block multi columns text, max 3 columns
if ( ! empty( $args['columns'] ) && ! ( $args['hide_block'] ) ):
	$col_count = count( $args['columns'] );

	$anchor = '';

	if ( ! empty( $args['anchor'] ) ):
		$anchor = ' id="' . esc_attr( sanitize_title( $args['anchor'] ) ) . '"';
	endif;
	?>
    <div class="f f-wysiwyg"<?php echo( $anchor ); ?>>
        <div class="container<?php echo( $col_count < 3 ? ' medium--' : '' ); ?>">
			<?php if ( ! empty( $args['section_title'] ) ): ?>
                <h2 class="h2"><?php echo( $args['section_title'] ); ?></h2>
			<?php endif; ?>
            <div class="f-wysiwyg__column" data-colcount="<?php echo( esc_attr( $col_count ) ); ?>">
				<?php foreach ( $args['columns'] as $col ) : ?>
                    <div class="f-wysiwyg__column-content c-wysiwyg">
						<?php echo( $col['wysiwyg'] ); ?>
                    </div>
				<?php endforeach; ?>
            </div>
        </div>
    </div>
<?php
endif;

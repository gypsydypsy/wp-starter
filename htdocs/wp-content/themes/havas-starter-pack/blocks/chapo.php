<?php
// Block chapô
if ( ! empty( $args['text'] ) && ( ! $args['hide_block'] ) ):
	$anchor = '';

	if ( ! empty( $args['anchor'] ) ):
		$anchor = ' id="' . esc_attr( sanitize_title( $args['anchor'] ) ) . '"';
	endif;
	?>
    <div class="f f-chapo"<?php echo( $anchor ); ?>>
        <div class="container">
            <div class="f-chapo__ctn"><?php echo( $args['text'] ); ?></div>
        </div>
    </div>
<?php
endif;

<?php
// Block multiple Call to action
if ( ! ( $args['hide_block'] ) ):
	$align = $args['align'] ?: '';
	$anchor = '';

	if ( ! empty( $args['anchor'] ) ):
		$anchor = ' id="' . esc_attr( sanitize_title( $args['anchor'] ) ) . '"';
	endif;
	?>
    <div class="f f-ctas"<?php echo( $anchor ); ?>>
        <div class="container">
            <div class="f-ctas__ctn c-wysiwyg <?php echo( esc_attr( $align ) ) ?>">
				<?php
				foreach ( $args['ctas'] as $item ):
					if ( ! empty ( $item['link'] ) ):
						$link_url = $item['link']['url'];
						$link_title = $item['link']['title'];
						$link_target = $item['link']['target'] ?: '_self';
						$css_cta = '';

						switch ( $item['layout'] ) :
							case 'button':
								$css_cta = 'c-button bg--';
								break;
							case 'underlined':
								$css_cta = 'c-button';
								break;
							case 'arrow':
								$css_cta = 'c-button arrow--';
								break;
							case 'download':
								$css_cta     = 'c-button download--';
								$link_target = '_blank'; // force blank for download file
								break;
						endswitch;
						?>
                        <a href="<?php echo( esc_url( $link_url ) ); ?>" target="<?php echo( esc_attr( $link_target ) ); ?>" title="<?php echo( esc_attr( $link_title ) ); ?>" class="<?php echo( esc_attr( $css_cta ) ); ?>">
							<?php echo( $link_title ); ?>
                        </a>
					<?php endif;
				endforeach; ?>
            </div>
        </div>
    </div>
<?php
endif;

<?php
//Block images (1 or 2)
if ( ! ( $args['hide_block'] ) ):
	$anchor = '';

	if ( ! empty( $args['anchor'] ) ):
		$anchor = ' id="' . esc_attr( sanitize_title( $args['anchor'] ) ) . '"';
	endif;
	?>
    <div class="f f-images"<?php echo( $anchor ); ?>>
		<?php if ( ! empty( $args['section_title'] ) ): ?>
            <div class="container medium--">
                <h2 class="h2"><?php echo( $args['section_title'] ); ?></h2>
            </div>
		<?php endif;

		if ( ! empty( $args['images'] ) ):
			$nb_col = count( $args['images'] );
			$class_container = 'container';

			if ( $nb_col > 1 ):
				$class_container = 'container medium--';
			endif;
			?>
            <div class="<?php echo( esc_attr( $class_container ) ); ?>">
                <ul class="f-images__list" data-colcount="<?php echo( esc_attr( $nb_col ) ); ?>">
					<?php
					foreach ( $args['images'] as $item ):
						if ( ! empty( $item['image'] ) ):
							?>
                            <li>
                                <div class="c-img">
									<?php generate_markup_image_by_sizes( $item['image'], 'w1920', 'w1384', 768, 200, true ); ?>
                                </div>
                            </li>
						<?php
						endif;
					endforeach;
					?>
                </ul>
            </div>
		<?php endif;
		?>
    </div>
<?php
endif;

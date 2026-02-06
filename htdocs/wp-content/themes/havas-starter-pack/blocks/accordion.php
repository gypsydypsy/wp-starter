<?php
// Bloc accordion
if ( ! ( $args['hide_block'] ) ):
	$anchor = '';

	if ( ! empty( $args['anchor'] ) ):
		$anchor = ' id="' . esc_attr( sanitize_title( $args['anchor'] ) ) . '"';
	endif;
	?>
    <div class="f f-accordion"<?php echo( $anchor ); ?>>
		<?php
		if ( ! empty( $args['section_title'] ) ): ?>
            <div class="container medium--">
                <h2 class="h2"><?php echo( $args['section_title'] ); ?></h2>
            </div>
		<?php
		endif;

		if ( ! empty( $args['accordion'] ) ): ?>
            <div class="container">
                <div class="f-accordion__list">
					<?php
					$count = 0;
					foreach ( $args['accordion'] as $item ):
						$count ++;
						?>
                        <div class="f-accordion__list-item<?php echo $count > 2 ? ' hidden' : ''; ?>">
                            <button aria-controls="accordion-<?php echo($count); ?>" aria-expanded="false" class="f-accordion__list-item-title toggle-button"><?php echo( $item['tab_title'] ); ?></button>
                            <div id="accordion-<?php echo($count); ?>" class="f-accordion__list-item-content toggle-content">
                                <div class="c-wysiwyg"><?php echo( $item['content'] ); ?></div>
                            </div>
                        </div>
					<?php endforeach; ?>
                    <div class="f-accordion__list-loadmore<?php echo count( $args['accordion'] ) > 2 ? ' active' : ''; ?>">
                        <button type="button" title="<?php esc_attr_e( 'Load more', 'havas_starter_pack' ); ?>"><span aria-hidden="true" class="icon-plus"></span></button>
                    </div>
                </div>
            </div>
		<?php endif; ?>
    </div>
<?php
endif;

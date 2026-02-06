<?php
// Block social networks
if ( ! ( $args['hide_block'] ) ):
	$anchor = '';

	if ( ! empty( $args['anchor'] ) ):
		$anchor = ' id="' . esc_attr( sanitize_title( $args['anchor'] ) ) . '"';
	endif;
	?>
    <div class="f f-social"<?php echo( $anchor ); ?>>
        <div class="container medium--">
			<?php if ( ! empty( $args['section_title'] ) ): ?>
                <h2 class="h2"><?php echo( $args['section_title'] ); ?></h2>
			<?php endif; ?>
            <div class="f-social__list">
                <ul class="c-social">
					<?php foreach ( $args['social_networks'] as $item ):
						$network = $item['social_network'];
						$network_link = get_field( 'social_networks_' . $network, 'option' );

						if ( ! empty ( $network_link ) ):?>
                            <li><a href="<?php echo( esc_url( $network_link ) ); ?>" target="_blank" aria-label="<?php echo( esc_attr( sprintf( __( 'Follow us on %s', 'havas_starter_pack' ), mb_convert_case( $network, MB_CASE_TITLE ) ) ) ); ?>">
                                    <span aria-hidden="true" class="icon-<?php echo( $network ); ?>"></span>
                                </a>
                            </li>
						<?php endif;
					endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
<?php
endif;

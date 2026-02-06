<?php
// Block press releases
if ( ! ( $args['hide_block'] ) ):
	$items = array();
	$anchor = '';

	if ( ! empty( $args['anchor'] ) ):
		$anchor = ' id="' . esc_attr( sanitize_title( $args['anchor'] ) ) . '"';
	endif;

	$items_global = array();

	foreach ( $args['press_releases'] as $detail ):
		$items = array();

		$display = $detail['display'] ?: 'automatic';

		switch ( $display ):
			case 'automatic':
				$sort_by     = $detail['automatic']['sort_by'] ?: 'date';
				$order_by    = $detail['automatic']['order_by'] ?: 'DESC';
				$count_items = $detail['automatic']['count_items'] ?: - 1;

				if ( ! empty( $sort_by ) && ! empty( $order_by ) && ! empty( $count_items ) ):
					/**
					 * Get CPT with WP Query
					 */
					$args_query = array(
						'post_type'              => 'press_release',
						'post_status'            => 'publish',
						'posts_per_page'         => $count_items,
						'order'                  => $order_by,
						'orderby'                => $sort_by,
						'no_found_rows'          => true,
						'update_post_meta_cache' => false,
						'update_post_term_cache' => false,
					);

					$query_items = new WP_Query( $args_query );

					if ( $query_items->have_posts() ) :
						while ( $query_items->have_posts() ) :
							$query_items->the_post();
							$item = hsp_get_cpt_data( $query_items->post );

							if ( ! empty( $item ) ):
								$items[] = $item;
							endif;
						endwhile;
					endif;

					wp_reset_postdata();
				endif;
				break;

			case 'manual':
				$selection = $detail['manual']['selection'];

				if ( ! empty( $selection ) ):
					foreach ( $selection as $cpt ):
						$item = hsp_get_cpt_data( $cpt );

						if ( ! empty( $item ) ):
							$items[] = $item;
						endif;
					endforeach;
				endif;
				break;
		endswitch;

		$items_global[] = array( 'title' => $detail['list_title'], 'items' => $items );
	endforeach;
	?>
    <div class="f f-press"<?php echo( $anchor ); ?>>
		<?php if ( ! empty( $args['section_title'] ) ): ?>
            <div class="container medium--">
                <h2 class="h2"><?php echo( $args['section_title'] ); ?></h2>
            </div>
		<?php endif;

		foreach ( $items_global as $detail ):
			if ( count( $detail['items'] ) > 0 ):?>
                <div class="f-press__ctn">
                    <div class="container">
                        <h3 class="h3"><?php echo( $detail['title'] ); ?></h3>
                    </div>
                    <div class="container medium--">
                        <ul>
							<?php foreach ( $detail['items'] as $item ): ?>
                                <li class="f-press__ctn-item">
                                    <div>
                                        <div class="f-press__ctn-item-date"><?php echo( $item['suptitle'] ); ?></div>
                                        <div class="f-press__ctn-item-title"><?php echo( $item['title'] ); ?></div>
                                    </div>
                                    <a href="<?php echo( esc_url( $item['attached_file'] ) ); ?>" target="_blank" class="c-button download--"><?php _e( 'Download', 'havas_starter_pack' ); ?></a>
                                </li>
							<?php endforeach; ?>
                        </ul>
                    </div>
                </div>
			<?php endif;
		endforeach; ?>
    </div>
<?php
endif;

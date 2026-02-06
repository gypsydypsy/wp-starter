<?php
// Block dynamic list
if ( ! ( $args['hide_block'] ) ):
	$items = array();
	$background_color_css = '';
	$anchor = '';
	$display = $args['display'] ?: 'automatic';

	if ( ! empty( $args['background_color'] ) ):
		$background_color_css = ' data-bg="' . esc_attr( $args['background_color'] ) . '"';
	endif;

	if ( ! empty( $args['anchor'] ) ):
		$anchor = ' id="' . esc_attr( sanitize_title( $args['anchor'] ) ) . '"';
	endif;

	switch ( $display ):
		case 'automatic':
			$post_type   = $args['automatic']['post_type'];
			$sort_by     = $args['automatic']['sort_by'] ?: 'date';
			$order_by    = $args['automatic']['order_by'] ?: 'DESC';
			$count_items = $args['automatic']['count_items'] ?: - 1;

			if ( ! empty( $post_type ) && ! empty( $sort_by ) && ! empty( $order_by ) && ! empty( $count_items ) ):
				/**
				 * Get CPT with WP Query
				 */
				$args_query = array(
					'post_type'              => $post_type,
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
			$selection = $args['manual']['selection'];

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
	if ( count( $items ) > 0 ):
		?>
        <div class="f f-news"<?php echo( $background_color_css ); ?><?php echo( $anchor ); ?>>
			<?php if ( ! empty( $args['section_title'] ) ): ?>
                <div class="container medium--">
                    <h2 class="h2"><?php echo( $args['section_title'] ); ?></h2>
                </div>
			<?php endif; ?>
            <div class="container">
                <div class="f-news__list">
					<?php
					foreach ( $items as $item ) :
						// add category / prepare for filter
						$categorie_names = '';
						$categorie_slugs = '';

						if ( is_array( $item['category'] ) && count( $item['category'] ) > 0 ):
							$categorie_names = join( ', ', wp_list_pluck( $item['category'], 'name' ) );
							$categorie_slugs = join( ',', wp_list_pluck( $item['category'], 'slug' ) );
						endif;
						?>
                        <div class="c-card news--" data-filter="<?php echo esc_attr( $categorie_slugs ); ?>">
							<?php
							if ( ! empty( $item['permalink'] ) ):
								// markup img
								$start_tag_img = '<a href="' . esc_url( $item['permalink'] ) . '" title="' . esc_attr( $item['title'] ) . '" class="c-card__img">';
								$endtag_img    = '</a>';
								// markup card
								$start_tag_title = '<a href="' . esc_url( $item['permalink'] ) . '" title="' . esc_attr( $item['title'] ) . '">';
								$endtag_title    = '</a>';
							else:
								// markup img
								$start_tag_img = '<div class="c-card__img">';
								$endtag_img    = '</div>';
								// markup card
								$start_tag_title = '';
								$endtag_title    = '';
							endif;

							if ( ! empty( $item['image'] ) ):
								echo( $start_tag_img );
								generate_markup_image_by_sizes( $item['image'], 'w435', 'w666', 768, 200, true );
								echo( $endtag_img );
							endif;

							if ( ! empty( $item['suptitle'] ) ):
								?>
                                <div class="c-card__suptitle"><?php echo( $item['suptitle'] ); ?></div>
							<?php
							endif;

							if ( ! empty( $categorie_names ) ):
								?>
                                <div class="c-card__category"><?php echo( $categorie_names ); ?></div>
							<?php
							endif;
							?>
                            <h5 class="h5 c-card__title">
								<?php
								echo( $start_tag_title );

								echo( $item['title'] );

								echo( $endtag_title );
								?>
                            </h5>
							<?php if ( ! empty( $item['excerpt'] ) ): ?>
                                <div class="c-card__content"><?php echo( $item['excerpt'] ); ?></div>
							<?php endif; ?>
                        </div>
					<?php endforeach; ?>
                </div>
            </div>
        </div>
	<?php endif;
endif;

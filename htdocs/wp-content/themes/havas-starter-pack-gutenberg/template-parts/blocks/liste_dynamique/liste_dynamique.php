<?php
/**
 * Liste dynamique Block Template.
 *
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML (empty).
 * @param bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

if ( ! get_field( 'masquer_ce_bloc_en_front_office' ) ):
	// Create id attribute allowing for custom "anchor" value
	$id = 'liste-dynamique-' . $args['id'];

	if ( ! empty( $block['anchor'] ) ) {
		$id = $block['anchor'];
	}

	$items = array();
	// Load values and assign defaults if required
	$titre_block   = get_field( 'titre_block' );
	$type_de_liste = get_field( 'type_de_liste' ) ?: 'auto';

	switch ( $type_de_liste ):
		case 'auto':
			$type_de_contenu         = $args['type_de_contenu'];
			$trier_par               = get_field( 'automatique_trier_par' ) ?: 'title';
			$ordre                   = get_field( 'automatique_ordre' ) ?: 'ASC';
			$nombre_items_a_afficher = get_field( 'automatique_nombre_items_a_afficher' ) ?: - 1;

			if ( ! empty( $type_de_contenu ) && ! empty( $trier_par ) && ! empty( $ordre ) && ! empty( $nombre_items_a_afficher ) ):
				/**
				 * Get CPT with WP Query
				 */
				$args_query = array(
					'post_type'              => $type_de_contenu,
					'post_status'            => 'publish',
					'posts_per_page'         => $nombre_items_a_afficher,
					'order'                  => $ordre,
					'orderby'                => $trier_par,
					'no_found_rows'          => true,
					'update_post_meta_cache' => false,
					'update_post_term_cache' => false,
				);

				$query_items = new WP_Query( $args_query );

				if ( $query_items->have_posts() ) :
					while ( $query_items->have_posts() ) :
						$query_items->the_post();

						$item = hsp_get_liste_info( $query_items->post );

						if ( ! empty( $item ) ):
							$items[] = $item;
						endif;
					endwhile;
				endif;

				wp_reset_postdata();
			endif;
			break;
		case 'manuel':
			$selection = get_field( 'manuel_selection' );

			if ( ! empty( $selection ) ):
				foreach ( $selection as $cpt ):
					$item = hsp_get_liste_info( $cpt );

					if ( ! empty( $item ) ):
						$items[] = $item;
					endif;
				endforeach;
			endif;
			break;
	endswitch;

	if ( count( $items ) > 0 ):
		?>
        <div class="f f-news" id="<?php echo esc_attr( $id ); ?>">
			<?php if ( ! empty( $titre_block ) ): ?>
                <div class="container medium--">
                    <h2 class="h2"><?php echo( $titre_block ); ?></h2>
                </div>
			<?php endif; ?>
            <div class="container">
                <div class="f-news__list">
					<?php
					foreach ( $items as $item ) :
						// lien éventuel
						if ( ! empty( $item['permalink'] ) ):
							$start_tag = '<a href="' . esc_url( $item['permalink'] ) . '" title="' . esc_attr( $item['title'] ) . '" class="c-card news--">';
							$endtag    = '</a>';
						else:
							$start_tag = '<div class="c-card news--">';
							$endtag    = '</div>';
						endif;

						echo( $start_tag );
						?>
                        <div class="c-card__img">
							<?php if ( ! empty( $item['image_url'] ) ): ?>
                                <img src="<?php echo( esc_url( $item['image_url'] ) ); ?>" alt="<?php echo( esc_attr( $item['image_alt'] ) ); ?>"/>
							<?php endif; ?>
                        </div>
						<?php
						if ( ! empty( $item['suptitle'] ) ):
							?>
                            <div class="c-card__suptitle"><?php echo( $item['suptitle'] ); ?></div>
						<?php
						endif;
						?>
                        <h5 class="h5 c-card__title"><?php echo( $item['title'] ); ?></h5>
						<?php if ( ! empty( $item['excerpt'] ) ): ?>
                        <div class="c-card__content"><?php echo( $item['excerpt'] ); ?></div>
					<?php endif; ?>
						<?php
						echo( $endtag );
					endforeach;
					?>
                </div>
            </div>
        </div>
	<?php
	endif;
endif;

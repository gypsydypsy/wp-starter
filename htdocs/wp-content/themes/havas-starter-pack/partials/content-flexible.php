<?php
//display flexible content - include /blocks/*.php based on layout name
$flexible_content = get_field( 'flexible_content' );

if ( ! empty( $flexible_content ) ):
	foreach ( $flexible_content as $args ) :
		$slug_php_file = $args['acf_fc_layout'];

		switch ( $slug_php_file ):
			case 'listing_news':
				$slug_php_file                  = 'dynamic_list';
				$args['automatic']['post_type'] = 'news';
				$args['image_size']             = 'listing-news';
				break;
			case 'listing_profiles':
				$slug_php_file                  = 'dynamic_list';
				$args['automatic']['post_type'] = 'profile';
				$args['image_size']             = 'listing-profile';
				break;
		endswitch;

		get_template_part( '/blocks/' . $slug_php_file, '', $args );
	endforeach;
endif;

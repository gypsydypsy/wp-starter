<?php
//Create image sizes
add_image_size( 'w1920', 1920, 0, true ); // width exactly 1920 px (bloc images desktop)
add_image_size( 'w1384', 1384, 0, true ); // width exactly 1384 px (bloc images mobile)
add_image_size( 'w720', 720, 0, true ); // width exactly 720 px (bloc texte image dekstop)
add_image_size( 'w1414', 1414, 0, true ); // width exactly 1414 px (bloc texte image mobile)
add_image_size( 'w666', 666, 0, true ); // width exactly 666 px (bloc trombinoscope desktop)
add_image_size( 'w435', 435, 0, true ); // width exactly 435 px (bloc trombinoscope mobile)


if ( ! function_exists( 'generate_markup_image_by_sizes' ) ):
	/**
	 * Generates markup for an image with different sizes for desktop, mobile.
	 *
	 * @param   array|string  $image               The image array or URL, typically containing image details such as 'sizes', 'width', and 'height'.
	 * @param   string        $image_desktop_size  The size key for the desktop (retina) version of the image.
	 * @param   string        $image_mobile_size   The size key for the standard mobile (retina) version of the image.
	 * @param   int           $min_width_desktop   Minimum width (pixels) for desktop media query. Default is 768.
	 * @param   int           $min_width_mobile    Minimum width (pixels) for mobile media query. Default is 200.
	 * @param   bool          $display_caption     Whether to display the caption below the image. Default is false.
	 * @param   string        $loading             Image loading behavior, can be "lazy" or "eager". Default is "lazy".
	 *
	 * @return void Outputs the HTML markup directly for the image and associated elements.
	 */
	function generate_markup_image_by_sizes( $image, $image_desktop_size, $image_mobile_size, $min_width_desktop = 768, $min_width_mobile = 200, $display_caption = false, $loading = "lazy" ) {
		if ( is_array( $image ) ):
			// use original as fallback
			$image_desktop = $image['sizes'][ $image_desktop_size ] ?? $image['url'];
			$image_mobile = ( $image['sizes'][ $image_mobile_size ] ?? $image['url'] );
			$image_width = $image['sizes'][ $image_desktop_size . '-width' ] ?? $image['width'];
			$image_height = $image['sizes'][ $image_desktop_size . '-height' ] ?? $image['height'];
			?>
            <figure aria-label="<?php echo( esc_attr( ! empty( $image['caption'] ) ? $image['caption'] : '' ) ); ?>">
                <picture>
                    <source media="(min-width: <?php echo( $min_width_desktop ); ?>px)" srcset="<?php echo( esc_url( $image_desktop ) ); ?> 1x">
                    <source media="(min-width: <?php echo( $min_width_mobile ); ?>px)" srcset="<?php echo( esc_url( $image_mobile ) ); ?> 1x, <?php echo( esc_url( $image_mobile ) ); ?> 2x">
                    <img loading="<?php echo( $loading ); ?>" src="<?php echo( esc_url( $image_desktop ) ); ?>" alt="<?php echo( esc_attr( $image['alt'] ) ); ?>" width="<?php echo( esc_attr( $image_width ) ); ?>" height="<?php echo( esc_attr( $image_height ) ); ?>"/>
                </picture>
				<?php
				if ( $display_caption && ! empty( $image['caption'] ) ):
					?>
                    <figcaption><?php echo( $image['caption'] ); ?></figcaption>
				<?php
				endif;
				?>
            </figure>
		<?php
		endif;
	}
endif;

if ( ! function_exists( 'generate_markup_image_desktop_and_mobile_by_sizes' ) ):
	/**
	 * Generates responsive markup for an image, including support for desktop and mobile sizes.
	 *
	 * This function dynamically generates HTML markup with appropriate media queries
	 * and attributes for displaying responsive images optimized for desktop and mobile devices.
	 * It includes support for optional captions and lazy loading.
	 *
	 * @param   array       $image_d             The desktop image array containing sizes and other attributes.
	 * @param   array|null  $image_m             The mobile image array or null if the desktop image should be used as fallback.
	 * @param   string      $image_desktop_size  The key or identifier for the desired desktop image size in the `$image_d` array.
	 * @param   string      $image_mobile_size   The key or identifier for the desired mobile image size in the `$image_m` array.
	 * @param   int         $min_width_desktop   Minimum screen width (in pixels) for displaying the desktop image. Default is 1320.
	 * @param   int         $min_width_mobile    Minimum screen width (in pixels) for displaying the mobile image. Default is 200.
	 * @param   bool        $display_caption     Whether to display the image caption, if available. Default is false.
	 * @param   string      $loading             Specifies the lazy loading behavior for the image. Default is 'lazy'.
	 *
	 * @return void This function outputs the markup directly and does not return a value.
	 */
	function generate_markup_image_desktop_and_mobile_by_sizes( $image_d, $image_m, $image_desktop_size, $image_mobile_size, $min_width_desktop = 768, $min_width_mobile = 200, $display_caption = false, $loading = "lazy" ) {
		if ( is_array( $image_d ) ):
			// use original as fallback
			if ( ! $image_m ) {
				$image_m = $image_d;
			}

			$image_desktop = $image_d['sizes'][ $image_desktop_size ] ?? $image_d['url'];
			$image_mobile  = $image_m['sizes'][ $image_mobile_size ] ?? $image_m['url'];
			$image_width   = $image_d['sizes'][ $image_desktop_size . '-width' ] ?? $image_d['width'];
			$image_height  = $image_d['sizes'][ $image_desktop_size . '-height' ] ?? $image_d['height'];

			?>
            <figure aria-label="<?php echo( esc_attr( ! empty( $image['caption'] ) ? $image['caption'] : '' ) ); ?>">
                <picture>
                    <source media="(min-width: <?php echo( $min_width_desktop ); ?>px)" srcset="<?php echo( esc_url( $image_desktop ) ); ?> 1x">
                    <source media="(min-width: <?php echo( $min_width_mobile ); ?>px)" srcset="<?php echo( esc_url( $image_mobile ) ); ?> 1x, <?php echo( esc_url( $image_mobile ) ); ?> 2x">
                    <img loading="<?php echo( $loading ); ?>" src="<?php echo( esc_url( $image_desktop ) ); ?>" alt="<?php echo( esc_attr( $image['alt'] ) ); ?>" width="<?php echo( esc_attr( $image_width ) ); ?>" height="<?php echo( esc_attr( $image_height ) ); ?>"/>
                </picture>
				<?php
				if ( $display_caption && ! empty( $image['caption'] ) ):
					?>
                    <figcaption><?php echo( $image['caption'] ); ?></figcaption>
				<?php
				endif;
				?>
            </figure>
		<?php
		endif;
	}
endif;


// Désactive la génération des tailles intermédiaires par défaut
add_action( 'init', function () {
	// Désactive les tailles d'images par défaut
	remove_image_size( 'thumbnail' );
	remove_image_size( 'medium' );
	remove_image_size( 'medium_large' );
	remove_image_size( 'large' );
	remove_image_size( '1536x1536' );
	remove_image_size( '2048x2048' );
} );

// Désactive les tailles intermédiaires
add_filter( 'intermediate_image_sizes_advanced', function ( $sizes ) {
	unset( $sizes['thumbnail'], $sizes['medium'], $sizes['medium_large'], $sizes['large'], $sizes['1536x1536'], $sizes['2048x2048'] );

	return $sizes;
} );

// Désactive la mise à l'échelle automatique des grandes images
add_filter( 'big_image_size_threshold', '__return_false' );
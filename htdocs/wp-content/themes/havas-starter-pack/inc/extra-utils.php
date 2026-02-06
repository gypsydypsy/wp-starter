<?php
// Since WP 4.5, WordPress perform a compression for mobile first, to avoid this :
add_filter( 'jpeg_quality', function ( $arg ) {
	return 100;
} );

if ( ! function_exists( 'hsp_set_image_meta_upon_image_upload' ) ):
	/**
	 * Set alt/title/caption/description automatically when uploading a picture (based on the filename)
	 */
	function hsp_set_image_meta_upon_image_upload( $post_ID ) {
		// Check if uploaded file is an image, else do nothing
		if ( wp_attachment_is_image( $post_ID ) ) :
			$my_image_title = get_post( $post_ID )->post_title;

			// Sanitize the title:  remove hyphens, underscores & extra spaces:
			$my_image_title = preg_replace( '%\s*[-_\s]+\s*%', ' ', $my_image_title );

			// Sanitize the title:  capitalize first letter of every word (other letters lower case):
			$my_image_title = mb_convert_case( $my_image_title, MB_CASE_TITLE );

			// Create an array with the image meta (Title, Caption, Description) to be updated
			// Note:  comment out the Excerpt/Caption or Content/Description lines if not needed
			$my_image_meta = array(
				'ID'           => $post_ID,            // Specify the image (ID) to be updated
				'post_title'   => $my_image_title,        // Set image Title to sanitized title
				'post_excerpt' => $my_image_title,        // Set image Caption (Excerpt) to sanitized title
				'post_content' => $my_image_title,        // Set image Description (Content) to sanitized title
			);

			// Set the image Alt-Text - disabled for accessibility
			// update_post_meta( $post_ID, '_wp_attachment_image_alt', $my_image_title );
			// Set the image meta (e.g. Title, Excerpt, Content)
			wp_update_post( $my_image_meta );
		endif;
	}
endif;

if ( ! function_exists( 'hsp_get_ip' ) ) :
	/**
	 * Get the ip
	 *
	 * @return mixed
	 */
	function hsp_get_ip() {
		//Just get the headers if we can or else use the SERVER global
		if ( function_exists( 'apache_request_headers' ) ) {
			$headers = apache_request_headers();
		} else {
			$headers = $_SERVER;
		}
		//Get the forwarded IP if it exists
		if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) && filter_var( $_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 ) && ( '127.0.0.1' !== $_SERVER['HTTP_CLIENT_IP'] ) ) {
			$the_ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif ( array_key_exists( 'X-Forwarded-For', $headers ) && filter_var( $headers['X-Forwarded-For'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 ) && ( '127.0.0.1' !== $headers['X-Forwarded-For'] ) ) {
			$the_ip = $headers['X-Forwarded-For'];
		} elseif ( array_key_exists( 'HTTP_X_FORWARDED_FOR', $headers ) && filter_var( $headers['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 ) && ( '127.0.0.1' !== $headers['HTTP_X_FORWARDED_FOR'] ) ) {
			// Check ip is pass from proxy
			$ip = explode( ',', $headers['HTTP_X_FORWARDED_FOR'] );
			// Can include more than 1 ip, first is the public one
			$the_ip = trim( $ip[0] );
		} else {
			$the_ip = filter_var( $_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 );
		}

		return $the_ip;
	}
endif;

if ( ! function_exists( 'hsp_verify_google_recaptcha' ) ) :
	/**
	 * Verify a captcha response
	 */
	function hsp_verify_google_recaptcha( $captcha_response ) {
		$service_url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . get_field( 'google_recpatcha_secret_site_key', 'option' ) . '&response=' . $captcha_response;

		$curl = curl_init( $service_url );
		curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, 0 );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
		$curl_response = curl_exec( $curl );

		$httpCode = curl_getinfo( $curl, CURLINFO_HTTP_CODE );
		if ( $httpCode == 200 ) {
			if ( $curl_response === false ) {
				curl_close( $curl );
				die( 'error1 occured during curl exec.' );
			}
			curl_close( $curl );
			$decoded_result    = json_decode( $curl_response );
			$recaptcha_success = $decoded_result->success;
		} else {
			error_log( "error1: occured during curl_getinfo code ret.$httpCode" );
			$recaptcha_success = false;
		}

		return $recaptcha_success;
	}
endif;

if ( ! function_exists( 'genere_markup_image' ) ):
	function genere_markup_image( $image, $image_size, $min_width_desktop = 1320, $min_width_tablet = 840, $min_width_mobile = 200, $display_caption = false ) {
		if ( is_array( $image ) ):
			// use original as fallback
			$image_desktop = $image['sizes'][ $image_size . '-desktop' ] ?: $image['url'];
			$image_desktop_retina = $image['sizes'][ $image_size . '-desktop-retina' ] ?: $image['url'];
			$image_tablet = $image['sizes'][ $image_size . '-tablet' ] ?: $image['url'];
			$image_tablet_retina = $image['sizes'][ $image_size . '-tablet-retina' ] ?: $image['url'];
			$image_mobile = $image['sizes'][ $image_size . '-mobile' ] ?: $image['url'];
			$image_mobile_retina = $image['sizes'][ $image_size . '-mobile-retina' ] ?: $image['url'];
			$image_width = $image['sizes'][ $image_size . '-desktop-width' ] ?: $image['width'];
			$image_height = $image['sizes'][ $image_size . '-desktop-height' ] ?: $image['height'];

			// Accessibility & SEO
			$aria_label = '';
			$alt_img    = ' aria-hidden="true"';

			if ( ! empty( $image['caption'] ) ):
				$aria_label = ' aria-label="' . esc_attr( $image['caption'] ) . '"';
			endif;

			if ( ! empty( $image['alt'] ) ):
				$alt_img = ' alt="' . esc_attr( $image['alt'] ) . '"';
			endif;

			if ( $display_caption && ! empty( $image['caption'] ) ):
				$start_elem = '<figure' . $aria_label . '>';
				$end_elem   = '</figure>';
				$figcaption = '<figcaption>' . esc_html( $image['caption'] ) . '</figcaption>' . PHP_EOL;
			else:
				$start_elem = '<div role="figure" class="figure"' . $aria_label . '>';
				$end_elem   = '</div>';
				$figcaption = '';
			endif;

			echo( $start_elem );
			?>
            <picture>
                <source media="(min-width: <?php echo( $min_width_desktop ); ?>px)" srcset="<?php echo( esc_url( $image_desktop ) ); ?> 1x, <?php echo( esc_url( $image_desktop_retina ) ); ?> 2x">
                <source media="(min-width: <?php echo( $min_width_tablet ); ?>px)" srcset="<?php echo( esc_url( $image_tablet ) ); ?> 1x, <?php echo( esc_url( $image_tablet_retina ) ); ?> 2x">
                <source media="(min-width: <?php echo( $min_width_mobile ); ?>px)" srcset="<?php echo( esc_url( $image_mobile ) ); ?> 1x, <?php echo( esc_url( $image_mobile_retina ) ); ?> 2x">
                <img loading="lazy" src="<?php echo( esc_url( $image_desktop ) ); ?>"<?php echo( $alt_img ); ?> width="<?php echo( esc_attr( $image_width ) ); ?>" height="<?php echo( esc_attr( $image_height ) ); ?>"/>
            </picture>
			<?php
			echo( $figcaption );
			echo( $end_elem );
		endif;
	}
endif;

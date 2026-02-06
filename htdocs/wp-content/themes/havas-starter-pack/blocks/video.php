<?php
// Block Video
if ( ! ( $args['hide_block'] ) ):
	$background_color_css = '';
	$anchor = '';

	if ( ! empty( $args['background_color'] ) ):
		$background_color_css = ' data-bg="' . esc_attr( $args['background_color'] ) . '"';
	endif;

	if ( ! empty( $args['anchor'] ) ):
		$anchor = ' id="' . esc_attr( sanitize_title( $args['anchor'] ) ) . '"';
	endif;
	?>
    <div class="f f-video"<?php echo( $background_color_css ); ?><?php echo( $anchor ); ?>>
        <div class="container">
			<?php if ( ! empty( $args['section_title'] ) ): ?>
                <h2 class="h2"><?php echo( $args['section_title'] ); ?></h2>
			<?php endif; ?>
            <div class="c-video">
				<?php
				$aria_label = '';

				if ( ! empty( $args['video_title'] ) ):
					$aria_label = ' aria-label="' . esc_attr( $args['video_title'] ) . '"';
				endif;
				?>
                <figure role="figure"<?php echo( $aria_label ); ?>>
                    <div class="c-video__player">
						<?php
						$poster_image_url = ! empty( $args['poster_image']['url'] ) ? $args['poster_image']['url'] : '';

						switch ( $args['video_format'] ):
							case 'internal':
								if ( ! empty( $args['mp4_video']['url'] ) ):
									?>
                                    <video controls poster="<?php echo( esc_url( $poster_image_url ) ); ?>">
                                        <source src="<?php echo( esc_url( $args['mp4_video']['url'] ) ); ?>" type="<?php echo( esc_attr( $args['mp4_video']['mime_type'] ) ); ?>">
                                        <p><?php echo( sprintf( __( 'Your browser does not support HTML5 videos. Here is <a href="%s" target="_blank">a link to download the video</a>.', 'havas_starter_pack' ), esc_url( $args['mp4_video']['url'] ) ) ); ?></p>
                                    </video>
								<?php
								endif;
								break;
							case 'external':
								if ( ! empty( $poster_image_url ) ): ?>
                                    <div class="c-video__player-poster" data-type-video="<?php echo( esc_attr( $args['platform'] ) ); ?>" style="background-image: url(<?php echo( $poster_image_url ); ?>);">
                                        <button type="button" class="c-video__player-poster-play"><span aria-hidden="true" class="icon-play"></span>Access the video player</button>
                                    </div>
								<?php
								endif;
								switch ( $args['platform'] ) :
									case 'youtube':
										echo '<div class="youtube_player" data-title="' . esc_attr( $args['iframe_video_title'] ) . '" data-videoID="' . esc_attr( $args['video_id'] ) . '" data-width="100%" data-height="100%" data-theme="dark" data-rel="0" data-controls="1" data-showinfo="0" data-autoplay="0" data-mute="0" data-srcdoc="srcdoc" data-loop="0" data-loading="1"></div>';
										break;
									case 'dailymotion':
										echo '<div class="dailymotion_player" data-title="' . esc_attr( $args['iframe_video_title'] ) . '" data-videoID="' . esc_attr( $args['video_id'] ) . '" data-width="100%" data-height="100%" data-showinfo="0" data-autoplay="0" data-embedType="video"></div>';
										break;
									case 'vimeo':
										echo '<div class="vimeo_player" data-title="' . esc_attr( $args['iframe_video_title'] ) . '" data-videoID="' . esc_attr( $args['video_id'] ) . '" data-width="100%" data-height="100%"></div>';
										break;
								endswitch;
								break;
						endswitch;
						?>
                    </div>
					<?php if ( ! empty( $args['video_title'] ) ): ?>
                        <figcaption><?php echo( $args['video_title'] ); ?></figcaption>
					<?php endif; ?>
                </figure>

				<?php if ( ! empty( $args['video_transcription'] ) ) : ?>
                    <div class="c-video__transcript">
                        <button class="toggle-button" aria-expanded="false" aria-controls="video-transcript"><?php esc_attr_e( 'Transcription', 'havas_starter_pack' ); ?></button>
                        <div id="video-transcript" class="toggle-content"><?php echo $args['video_transcription']; ?></div>
                    </div>
				<?php endif; ?>
            </div>
        </div>
    </div>
<?php
endif;

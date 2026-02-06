<?php
/**
 * Vidéo Block Template.
 *
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML (empty).
 * @param bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

if ( ! get_field( 'masquer_ce_bloc_en_front_office' ) ):
	// Create id attribute allowing for custom "anchor" value.
	$id = 'video-' . $block['id'];

	if ( ! empty( $block['anchor'] ) ) :
		$id = $block['anchor'];
	endif;

	// Load values and assign defaults if required
	$titre_block = get_field( 'titre_block' );
	$type        = get_field( 'type' ) ?: 'externe';
	$cover_video = get_field( 'cover_video' );
	$titre_video = get_field( 'titre_video' );

	switch ( $type ):
		case 'interne':
			$video_mp4 = get_field( 'video_mp4' );
			break;
		case 'externe':
			$plateforme_externe = get_field( 'plateforme_externe' );
			$id_video           = get_field( 'id_video' );
			break;
	endswitch;

	if ( ! empty( $cover_video ) && ( 'interne' == $type && ! empty( $video_mp4 ) ) || ( 'externe' == $type && ! empty( $plateforme_externe ) && ! empty( $id_video ) ) ):
		?>
        <div class="f f-video" id="<?php echo esc_attr( $id ); ?>">
            <div class="container">
				<?php if ( ! empty( $titre_block ) ): ?>
                    <h2 class="h2"><?php echo( $titre_block ); ?></h2>
				<?php endif; ?>
                <div class="c-video">
                    <figure>
                        <div class="c-video__player">
							<?php
							if ( 'interne' == $type ): ?>
                                <video controls poster="<?php echo( esc_url( $cover_video['url'] ) ); ?>">
                                    <source src="<?php echo( esc_url( $video_mp4['url'] ) ); ?>" type="<?php echo( esc_url( $video_mp4['mime_type'] ) ); ?>">
                                    <p><?php printf( __( 'Votre navigateur ne prend pas en charge les vidéos HTML5. Voici <a href="%s">un lien pour télécharger la vidéo</a>.', 'havas_starter_pack_gutenberg' ), esc_url( $video_mp4['url'] ) ); ?></p>
                                </video>
							<?php
							else:
								switch ( $plateforme_externe ) :
									case 'youtube':
										echo( '<div class="youtube_player" videoID="' . esc_attr( $id_video ) . '" width="100%" height="100%" theme="dark" rel="0" controls="1" showinfo="0" autoplay="0" mute="0" srcdoc="srcdoc" loop="0" loading="1"></div>' );
										break;
									case 'dailymotion':
										echo( '<div class="dailymotion_player" videoID="' . esc_attr( $id_video ) . '" width="100%" height="100%" showinfo="0" autoplay="0" embedType="video"></div>' );
										break;
									case 'vimeo':
										echo( '<div class="vimeo_player" videoID="' . esc_attr( $id_video ) . '" width="100%" height="100%"></div>' );
										break;
								endswitch; ?>

								<?php if ( ! empty( $cover_video ) ): ?>
                                <div class="c-video__player-poster" style="background-image: url(<?php echo( $cover_video['url'] ); ?>);">
                                    <button type="button" class="c-video__player-poster-play"><i class="icon-play"></i></button>
                                </div>
							<?php endif; ?>
							<?php endif; ?>
                        </div>
						<?php if ( ! empty( $titre_video ) ): ?>
                            <figcaption><?php echo( $titre_video ); ?></figcaption>
						<?php endif; ?>
                    </figure>
                </div>
            </div>
        </div>
	<?php
	endif;
endif;

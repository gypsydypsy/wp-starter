<?php
add_filter( 'allowed_block_types_all', 'hsp_allowed_block_types', 10, 2 );

if ( ! function_exists( 'hsp_allowed_block_types' ) ):
	function hsp_allowed_block_types( $block_editor_context, $editor_context ) {
		return array(
			'acf/chapeau-intro',
			'acf/colonnes-texte',
			'acf/images-colonnes',
			'acf/slider-images',
			'acf/slider-type3',
			'acf/slider-type4',
			'acf/accordeon',
			'acf/image-texte',
			'acf/chiffres-cles',
			'acf/video',
			'acf/reseaux-sociaux',
			'acf/citation',
			//'acf/liste-dynamique',
			'acf/ctas',
			'acf/actualites',
		);
	}
endif;

add_action( 'acf/init', 'hsp_acf_init_block_types' );

if ( ! function_exists( 'hsp_acf_init_block_types' ) ):
	function hsp_acf_init_block_types() {
		// Check function exists.
		if ( function_exists( 'acf_register_block_type' ) ) :
			// Chapeau intro
			acf_register_block_type( array(
				'name'            => 'chapeau-intro',
				'title'           => __( 'Chapeau Intro', 'havas_starter_pack_gutenberg' ),
				'description'     => __( 'Bloc texte simple', 'havas_starter_pack_gutenberg' ),
				'render_template' => 'template-parts/blocks/chapeau_intro/chapeau_intro.php',
				'category'        => 'layout',
				'icon'            => 'editor-justify',
				'keywords'        => array( 'chapeau', 'intro' ),
				'supports'        => array(
					'align'    => false,
					'multiple' => false,
					'anchor'   => true,
				),
				'example'         => array(
					'attributes' => array(
						'mode' => 'preview',
						'data' => array(
							'chapeau' => __( 'Intro : lorem ipsum dolor sit amet', 'havas_starter_pack_gutenberg' ),
						)
					)
				),
			) );
			// Colonnes de textes
			acf_register_block_type( array(
				'name'            => 'colonnes-texte',
				'title'           => __( 'Colonne(s) de texte', 'havas_starter_pack_gutenberg' ),
				'description'     => __( 'Texte sur 1, 2 ou 3 colonnes', 'havas_starter_pack_gutenberg' ),
				'render_template' => 'template-parts/blocks/colonnes_texte/colonnes_texte.php',
				'category'        => 'layout',
				'icon'            => 'columns',
				'keywords'        => array( 'texte', 'colonne' ),
				'supports'        => array(
					'align'  => false,
					'anchor' => true,
				),
			) );
			// Image(s) 1 ou 2 colonnes
			acf_register_block_type( array(
				'name'            => 'images-colonnes',
				'title'           => __( 'Image(s) 1 ou 2 colonnes', 'havas_starter_pack_gutenberg' ),
				'description'     => __( 'Image(s) 1 ou 2 colonnes', 'havas_starter_pack_gutenberg' ),
				'render_template' => 'template-parts/blocks/images_colonnes/images_colonnes.php',
				'category'        => 'media',
				'icon'            => 'columns',
				'keywords'        => array( 'image', 'colonne' ),
				'supports'        => array(
					'align'  => false,
					'anchor' => true,
				),
			) );
			// Slider images
			acf_register_block_type( array(
				'name'            => 'slider-images',
				'title'           => __( 'Slider images', 'havas_starter_pack_gutenberg' ),
				'description'     => __( 'Slider images', 'havas_starter_pack_gutenberg' ),
				'render_template' => 'template-parts/blocks/slider_images/slider_images.php',
				'category'        => 'media',
				'icon'            => 'images-alt',
				'keywords'        => array( 'image', 'slider' ),
				'supports'        => array(
					'align'  => false,
					'anchor' => true,
				),
			) );
			// Slider type 3
			acf_register_block_type( array(
				'name'            => 'slider-type3',
				'title'           => __( 'Slider type 3', 'havas_starter_pack_gutenberg' ),
				'description'     => __( 'Slider type 3', 'havas_starter_pack_gutenberg' ),
				'render_template' => 'template-parts/blocks/slider_type3/slider_type3.php',
				'category'        => 'media',
				'icon'            => 'images-alt',
				'keywords'        => array( 'image', 'slider' ),
				'supports'        => array(
					'align'  => false,
					'anchor' => true,
				),
			) );
			// Slider type 4
			acf_register_block_type( array(
				'name'            => 'slider-type4',
				'title'           => __( 'Slider type 4', 'havas_starter_pack_gutenberg' ),
				'description'     => __( 'Slider type 4', 'havas_starter_pack_gutenberg' ),
				'render_template' => 'template-parts/blocks/slider_type4/slider_type4.php',
				'category'        => 'media',
				'icon'            => 'images-alt',
				'keywords'        => array( 'image', 'slider' ),
				'supports'        => array(
					'align'  => false,
					'anchor' => true,
				),
			) );
			// Accordéon
			acf_register_block_type( array(
				'name'            => 'accordeon',
				'title'           => __( 'Accordéon', 'havas_starter_pack_gutenberg' ),
				'description'     => __( 'Accordéon', 'havas_starter_pack_gutenberg' ),
				'render_template' => 'template-parts/blocks/accordeon/accordeon.php',
				'category'        => 'layout',
				'icon'            => 'menu-alt3',
				'keywords'        => array( 'accordéon' ),
				'supports'        => array(
					'align'  => false,
					'anchor' => true,
				),
				'example'         => array(
					'attributes' => array(
						'mode' => 'preview',
						'data' => array(
							'is_preview'  => true,
							'titre_block' => __( 'Titre Bloc Accordéon', 'havas_starter_pack_gutenberg' ),
							'accordeon'   => array(
								array(
									'titre'   => __( 'Lorem ispum', 'havas_starter_pack_gutenberg' ),
									'contenu' => __( 'zerty qsdfgh wxcvbn', 'havas_starter_pack_gutenberg' ),
								),
								array(
									'titre'   => __( 'Dolor sit amet', 'havas_starter_pack_gutenberg' ),
									'contenu' => __( 'zerty qsdfgh wxcvbn', 'havas_starter_pack_gutenberg' ),
								),
							),
						)
					)
				),
			) );
			// Image + Texte
			acf_register_block_type( array(
				'name'            => 'image-texte',
				'title'           => __( 'Image + Texte', 'havas_starter_pack_gutenberg' ),
				'description'     => __( 'Image + Texte', 'havas_starter_pack_gutenberg' ),
				'render_template' => 'template-parts/blocks/image_texte/image_texte.php',
				'category'        => 'layout',
				'icon'            => 'align-pull-left',
				'keywords'        => array( 'image', 'texte' ),
				'supports'        => array(
					'align'  => false,
					'anchor' => true,
				),
				'example'         => array(
					'attributes' => array(
						'mode' => 'preview',
						'data' => array(
							'texte' => __( 'contenu WYSIWYG : lorem ipsum dolor sit amet', 'havas_starter_pack_gutenberg' ),
						)
					)
				),
			) );
			// Chiffres clés
			acf_register_block_type( array(
				'name'            => 'chiffres-cles',
				'title'           => __( 'Chiffres clés', 'havas_starter_pack_gutenberg' ),
				'description'     => __( 'Chiffres clés', 'havas_starter_pack_gutenberg' ),
				'render_template' => 'template-parts/blocks/chiffres_cles/chiffres_cles.php',
				'category'        => 'layout',
				'icon'            => 'ellipsis',
				'keywords'        => array( 'chiffre', 'clé', 'texte' ),
				'supports'        => array(
					'align'  => false,
					'anchor' => true,
				),
				/*'example'         => array(
					'attributes' => array(
						'mode' => 'preview',
						'data' => array(
							'texte' => __( 'contenu WYSIWYG : lorem ipsum dolor sit amet', 'havas_starter_pack_gutenberg' ),
						)
					)
				),*/
			) );
			// Vidéo
			acf_register_block_type( array(
				'name'            => 'video',
				'title'           => __( 'Vidéo', 'havas_starter_pack_gutenberg' ),
				'description'     => __( 'Vidéo', 'havas_starter_pack_gutenberg' ),
				'render_template' => 'template-parts/blocks/video/video.php',
				'category'        => 'media',
				'icon'            => 'format-video',
				'keywords'        => array( 'vidéo' ),
				'supports'        => array(
					'align'  => false,
					'anchor' => true,
				),
				/*'example'         => array(
					'attributes' => array(
						'mode' => 'preview',
						'data' => array(
							'texte' => __( 'contenu WYSIWYG : lorem ipsum dolor sit amet', 'havas_starter_pack_gutenberg' ),
						)
					)
				),*/
			) );
			// Réseaux sociaux
			acf_register_block_type( array(
				'name'            => 'reseaux-sociaux',
				'title'           => __( 'Réseaux sociaux', 'havas_starter_pack_gutenberg' ),
				'description'     => __( 'Réseaux sociaux', 'havas_starter_pack_gutenberg' ),
				'render_template' => 'template-parts/blocks/reseaux_sociaux/reseaux_sociaux.php',
				'category'        => 'widgets',
				'icon'            => 'networking',
				'keywords'        => array( 'réseaux', 'sociaux' ),
				'supports'        => array(
					'align'  => false,
					'anchor' => true,
				),
				/*'example'         => array(
					'attributes' => array(
						'mode' => 'preview',
						'data' => array(
							'texte' => __( 'contenu WYSIWYG : lorem ipsum dolor sit amet', 'havas_starter_pack_gutenberg' ),
						)
					)
				),*/
			) );
			// Citation
			acf_register_block_type( array(
				'name'            => 'citation',
				'title'           => __( 'Citation', 'havas_starter_pack_gutenberg' ),
				'description'     => __( 'Citation', 'havas_starter_pack_gutenberg' ),
				'render_template' => 'template-parts/blocks/citation/citation.php',
				'category'        => 'widgets',
				'icon'            => 'format-quote',
				'keywords'        => array( 'citation' ),
				'supports'        => array(
					'align'  => false,
					'anchor' => true,
				),
				'example'         => array(
					'attributes' => array(
						'mode' => 'preview',
						'data' => array(
							'titre_block'       => __( 'Bloc Citation / titre facultatif', 'havas_starter_pack_gutenberg' ),
							'citation'          => __( 'Vous ne devez jamais avoir peur de ce que vous faites quand vous faites ce qui est juste.', 'havas_starter_pack_gutenberg' ),
							'auteur_prenom_nom' => __( 'Rosa Parks', 'havas_starter_pack_gutenberg' ),
							'auteur_fonction'   => __( 'Artiste, Couturière, Femme politique (1913 - 2005)', 'havas_starter_pack_gutenberg' ),
						)
					)
				),
			) );
			// Liste dynamique
			/*acf_register_block_type( array(
				'name'            => 'liste-dynamique',
				'title'           => __( 'Liste dynamique', 'havas_starter_pack_gutenberg' ),
				'description'     => __( 'Liste dynamique', 'havas_starter_pack_gutenberg' ),
				'render_template' => 'template-parts/blocks/liste_dynamique/liste_dynamique.php',
				'category'        => 'widgets',
				'icon'            => 'format-gallery',
				'keywords'        => array( 'liste' ),
				'supports'        => array(
					'align'  => false,
					'anchor' => true,
				),
			) );*/
			acf_register_block_type( array(
				'name'            => 'actualités',
				'title'           => __( 'Actualités', 'havas_starter_pack_gutenberg' ),
				'description'     => __( 'Actualités', 'havas_starter_pack_gutenberg' ),
				'render_template' => 'template-parts/blocks/liste_dynamique/actualites.php',
				'category'        => 'widgets',
				'icon'            => 'format-gallery',
				'keywords'        => array( 'liste' ),
				'supports'        => array(
					'align'  => false,
					'anchor' => true,
				),
			) );
			// CTAS
			acf_register_block_type( array(
				'name'            => 'ctas',
				'title'           => __( 'CTAs', 'havas_starter_pack_gutenberg' ),
				'description'     => __( 'CTAs', 'havas_starter_pack_gutenberg' ),
				'render_template' => 'template-parts/blocks/ctas/ctas.php',
				'category'        => 'widgets',
				'icon'            => 'button',
				'keywords'        => array( 'CTA', 'lien' ),
				'supports'        => array(
					'align'  => false,
					'anchor' => true,
				),
			) );


		endif;
	}
endif;

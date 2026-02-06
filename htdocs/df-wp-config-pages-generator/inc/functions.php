<?php
//on charge WP
define( 'WP_USE_THEMES', false );
require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php' );

/**
 * fonction qui parse le fichier acf des flexible et retourne un tableau contenant cette liste.
 *
 * @param   string  $fileName
 *
 * @return array|void
 */
if ( ! function_exists( 'getFlexiblesAcfJsonFile' ) ) :
	function getFlexiblesAcfJsonFile( string $fileName = 'group_6234e5f520062.json' ) {

		$jsonFile = get_template_directory() . "/acf-json/" . $fileName;
		$jsonFileContents = file_get_contents( "{$jsonFile}" ) or wp_die( 'Error : impossible de lire le fichier ' . $jsonFile );
		$arrayFileContents = json_decode( $jsonFileContents, true );

		$arrayFlexs = array();
		foreach ( $arrayFileContents['fields'][0]['layouts'] as $layout ) {
			$arrayFlex          = array();
			$arrayFlex['key']   = $layout['key'];
			$arrayFlex['name']  = $layout['name'];
			$arrayFlex['label'] = $layout['label'];
			$arrayFlexs[]       = $arrayFlex;
		}

		return $arrayFlexs;
	}
endif;
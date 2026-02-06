<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://www.havasdigitalfactory.com/
 * @since      1.0.0
 *
 * @package    Df_String_Translations_For_Polylang
 * @subpackage Df_String_Translations_For_Polylang/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Df_String_Translations_For_Polylang
 * @subpackage Df_String_Translations_For_Polylang/includes
 * @author     Sébastien GASTARD <sebastien.gastard@havasdigitalfactory.com>
 */
class Df_String_Translations_For_Polylang_Deactivator {
	
	/**
	 * Clean database when deactivate plugin
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		if ( is_multisite() ):
			delete_site_option( 'DF_STRING_TRANSLATIONS_FOR_POLYLANG_VERSION' );
		else:
			delete_option( 'DF_STRING_TRANSLATIONS_FOR_POLYLANG_VERSION' );
		endif;
	}
	
}

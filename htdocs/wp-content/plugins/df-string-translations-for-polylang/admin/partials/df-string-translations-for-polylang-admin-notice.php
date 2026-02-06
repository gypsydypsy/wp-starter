<?php

/**
 * Provide a admin notice area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.havasdigitalfactory.com/
 * @since      1.0.0
 *
 * @package    Df_String_Translations_For_Polylang
 * @subpackage Df_String_Translations_For_Polylang/admin/partials
 */

foreach ( $notices as $notice ):
	?>
    <div class="notice notice-info">
        <p>
			<?php echo( $notice ); ?>
        </p>
    </div>
<?php
endforeach;

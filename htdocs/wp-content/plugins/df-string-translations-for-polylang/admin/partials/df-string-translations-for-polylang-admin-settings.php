<?php

/**
 * Provide a admin settings area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.havasdigitalfactory.com/
 * @since      1.0.0
 *
 * @package    Df_String_Translations_For_Polylang
 * @subpackage Df_String_Translations_For_Polylang/admin/partials
 */
?>
<div class="wrap">
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
	<?php
	if ( ! empty( $settings ) ):
		// display launch scan button
		$url = wp_nonce_url( admin_url( 'admin.php?page=df-string-translations-for-polylang-settings' ), 'scan-string-translations', 'df-scan-string-translations' );
		?>
        <p><?php _e( 'Current functions supported: esc_html_e(), esc_html__(), esc_attr_e(), esc_attr__(), __(), _e(), _x(), _ex(), pll__(), pll_e() and pll_translate_string() (used with custom API endpoints).', 'df-scan-string-translations' ); ?></p>
        <a href="<?php echo( esc_url( $url ) ); ?>" class="button button-primary"><?php _e( 'Scan strings', 'df-string-translations-for-polylang' ); ?></a>
	<?php
	endif;
	?>
    <form action="<?php echo( esc_url( admin_url( 'admin.php?page=df-string-translations-for-polylang-settings' ) ) ); ?>" method="post">
        <div>
            <h4><?php _e( 'Select themes you want to scan and translate with Polylang', 'df-string-translations-for-polylang' ); ?></h4>
			<?php
			$themes_need_translation = array();
			
			if ( isset( $settings['scan_themes'] ) ):
				$themes_need_translation = $settings['scan_themes'];
			endif;
			
			$themes = wp_get_themes();
			
			if ( count( $themes ) > 0 ):
				?>
                <ul>
					<?php
					foreach ( $themes as $slug => $theme ):
						$id = 'search_themes_strings_' . $slug;
						?>
                        <li>
                            <label for="<?php echo( esc_attr( $id ) ); ?>">
                                <input name="dfstfp_settings[scan_themes][]" type="checkbox" id="<?php echo( esc_attr( $id ) ); ?>" value="<?php echo( esc_attr( $slug ) ); ?>" <?php echo( in_array( $slug, $themes_need_translation ) ? 'checked="checked"' : '' ); ?> />
								<?php echo( $theme->Name ); ?>
                            </label>
                        </li>
					<?php
					endforeach;
					?>
                </ul>
			<?php
			endif;
			?>
        </div>
        <div>
            <h4><?php _e( 'Select plugins you want to scan and translate with Polylang', 'df-string-translations-for-polylang' ); ?></h4>
			<?php
			$plugins_need_translation = array();
			
			if ( isset( $settings['scan_plugins'] ) ):
				$plugins_need_translation = $settings['scan_plugins'];
			endif;
			
			$plugins = get_plugins();
			
			if ( count( $plugins ) > 0 ):
				?>
                <ul>
					<?php
					foreach ( $plugins as $plugin_path => $plugin ):
						$temp = explode( '/', $plugin_path );
						$id = 'search_plugins_strings_' . $temp[0];
						?>
                        <li>
                            <label for="<?php echo( esc_attr( $id ) ); ?>">
                                <input name="dfstfp_settings[scan_plugins][]" type="checkbox" id="<?php echo( esc_attr( $id ) ); ?>" value="<?php echo( esc_attr( $temp[0] ) ); ?>" <?php echo( in_array( $temp[0], $plugins_need_translation ) ? 'checked="checked"' : '' ); ?> />
								<?php
								// plugin active?
								if ( in_array( $plugin_path, $active_plugins ) ):
									echo( '<strong>' . $plugin['Name'] . ' ' . __( ' (active)', 'df-string-translations-for-polylang' ) . '</strong>' );
								else:
									echo( $plugin['Name'] );
								endif;
								?>
                            </label>
                        </li>
					<?php
					endforeach;
					?>
                </ul>
			<?php
			endif;
			?>
        </div>
		<?php
		wp_nonce_field( 'save_settings_df-string-translations-for-polylang', 'nonce_settings_df-string-translations-for-polylang' );
		submit_button();
		?>
    </form>
</div>

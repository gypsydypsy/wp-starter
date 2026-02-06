<?php
/**
 * Plugin Name:       DF Import Sample Medias
 * Plugin URI:        https://www.havasdigitalfactory.com/
 * Description:       Add sample medias files in WP medias
 * Version:           1.0
 * Author:            Frédéric RENOU
 * Text Domain:       df-sample-medias
 */

if ( ! defined( 'ABSPATH' ) ) :
	exit;
endif;

add_action( 'admin_menu', 'df_import_sample_medias_admin_menu' );
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'df_import_sample_medias_plugin_actions', 10 );

if ( ! function_exists( 'df_import_sample_medias_admin_menu' ) ) :
	function df_import_sample_medias_admin_menu() {
		add_submenu_page( 'tools.php', 'DF Import Sample Medias', 'DF Import Sample Medias', 'manage_options', 'df-import-sample-medias', 'df_import_sample_medias_callback', );
	}
endif;

if ( ! function_exists( 'does_media_file_exists' ) ) :
	function does_media_file_exists( $filename ) {
//		global $wpdb;
//		return intval( $wpdb->get_var( "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_value LIKE '%/$filename'" ) );

		global $wpdb;
		$query = "SELECT COUNT(*) FROM {$wpdb->postmeta} WHERE meta_value LIKE '%/$filename'"; //

		return ( $wpdb->get_var( $query ) > 0 );
	}
endif;

/**
 * Adds 'Settings' link to plugin entry in the Plugins list.
 *
 * @param   array  $actions  An array of plugin action links.
 *
 * @return array
 * @see 'plugin_action_links_$plugin_file'
 *
 */
if ( ! function_exists( 'df_import_sample_medias_plugin_actions' ) ) :
	function df_import_sample_medias_plugin_actions( $actions ) {
		$settings_action = [
			'settings' => sprintf(
				'<a href="%1$s" %2$s>%3$s</a>',
				menu_page_url( 'df-import-sample-medias', false ),
				'aria-label="' . __( 'Page d\'import des sample medias' ) . '"',
				esc_html__( 'Page d\'import des sample medias' )
			),
		];

		$actions = ( $settings_action + $actions );

		return $actions;
	}
endif;


if ( ! function_exists( 'df_import_sample_medias_callback' ) ) :
	function df_import_sample_medias_callback() {
		?>
        <div class="wrap">
            <h2>DF Import Sample Medias</h2>
			<?php
			if ( isset( $_POST['importSampleMedias'] ) && wp_verify_nonce( $_POST['postNonceImportSampleMedias'], 'import-sample-medias' ) ) :

				$errors = array();
				//on récupère les liste des fichiers présents dans le dossier assets/medias
				$all_files = array_diff( scandir( __DIR__ . '/assets/medias' ), array( '..', '.' ) );

				foreach ( $all_files as $key => $filename ) {
					if ( ! does_media_file_exists( sanitize_file_name( $filename ) ) ) {

						$image_url  = __DIR__ . '/assets/medias/' . $filename;
						$upload_dir = wp_upload_dir();
						$image_data = file_get_contents( $image_url ) or wp_die( 'Error : file_get_contents( ' . $image_url . ' )' );

						if ( wp_mkdir_p( $upload_dir['path'] ) ) {
							$file = $upload_dir['path'] . '/' . sanitize_file_name( $filename );
						} else {
							$file = $upload_dir['basedir'] . '/' . sanitize_file_name( $filename );
						}

						file_put_contents( $file, $image_data ) or wp_die( 'Error : file_put_contents( ' . $file . ', ' . $image_data . ' )' );

						$wp_filetype = wp_check_filetype( $filename, null );

						$attachment = array(
							'post_mime_type' => $wp_filetype['type'],
							'post_title'     => sanitize_file_name( $filename ),
							'post_content'   => '',
							'post_status'    => 'inherit',
						);

						$attach_id = wp_insert_attachment( $attachment, $file ) or wp_die( 'Error : wp_insert_attachment( ' . $attachment . ', ' . $file . ' )' );
						$attach_data = wp_generate_attachment_metadata( $attach_id, $file );
						wp_update_attachment_metadata( $attach_id, $attach_data );

						$array_details['uploaded_media'][ $key ]['initial_media_url'] = plugin_dir_url( __FILE__ ) . 'assets/medias/' . sanitize_file_name( $filename );
						$array_details['uploaded_media'][ $key ]['wp_attachment_url'] = wp_get_attachment_url( $attach_id );

					} else {
						$array_details['dupplicate_media'][] = plugin_dir_url( __FILE__ ) . 'assets/medias/' . sanitize_file_name( $filename );
					}

				}
				?>
				<?php if ( $errors ) : ?>
                ?>
                <div class="notice notice-error">
                    <p>Problème - TODO</p>
                </div>
			<?php else : ?>

                <div class="notice notice-success">

					<?php if ( ! empty( $array_details['uploaded_media'] ) ) : ?>
                        <p><strong>Félicitation ! Les medias suivants ont bien été importés dans votre bibliothèque medias Wordpress.</strong>
                        <br><br>
                        Détails :
						<?php foreach ( $array_details['uploaded_media'] as $item ) : ?>
                            <ul>
                                <li><?php echo $item['initial_media_url'] ?> <br> a bien été importé vers <a href="<?php echo $item['wp_attachment_url'] ?>"><?php echo $item['wp_attachment_url'] ?></a></li>
                            </ul>
						<?php endforeach; ?>
					<?php endif; ?>

					<?php if ( ! empty( $array_details['dupplicate_media'] ) ) : ?>
                        <p><strong>Certains medias n'ont pas pu être importé car ils existent déjà.</strong>
                        <ul>
							<?php foreach ( $array_details['dupplicate_media'] as $item ) : ?>
                                <li><?php echo $item; ?></li>
							<?php endforeach; ?>
                        </ul>
					<?php endif; ?>
                    <br>

                    <strong>Cette action n'est à effectuer qu'une seule fois.<br>
                        Le plugin DF Import Sample Medias a donc maintenant été désactivé.</strong>
                    </p>
					<?php deactivate_plugins( plugin_basename( __FILE__ ) ); ?>
                </div>
			<?php endif; ?>


			<?php else : ?>
                <p>
                <form method="post" action="<?php echo esc_html( admin_url( 'admin.php?page=df-import-sample-medias' ) ); ?>">
                    <input type="hidden" name="importSampleMedias" value="OK"/>
					<?php $nonce = wp_create_nonce( 'import-sample-medias' ); ?>
                    <input type="hidden" name="postNonceImportSampleMedias" value="<?php echo $nonce; ?>"/>
					<?php submit_button( 'Importer les sample medias' ); ?>
                </form>
                </p>

			<?php endif; ?>

        </div>
		<?php
	}
endif;
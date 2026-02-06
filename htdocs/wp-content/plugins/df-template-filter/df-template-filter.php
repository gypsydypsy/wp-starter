<?php
/**
 * Plugin Name:       DF Template Filter
 * Plugin URI:        https://www.havasdigitalfactory.com/
 * Description:       Add a template column in admin pages
 * Version:           1.1.0
 * Author:            Sébastien GASTARD
 * Text Domain:       df-template-filter
 */

if ( ! defined( 'ABSPATH' ) ) :
	exit;
endif;

if ( ! class_exists( 'DFTemplateFilter' ) ):
	class DFTemplateFilter {
		
		/**
		 * Init construct
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			// add filter
			add_action( 'restrict_manage_posts', array( $this, 'filter_dropdown' ), 10, 1 );
			add_filter( 'parse_query', array( $this, 'filter_post_list' ) );
			// add column
			add_filter( 'manage_pages_columns', array( $this, 'set_template_column' ) );
			add_action( 'manage_pages_custom_column', array( $this, 'custom_template_column' ), 10, 2 );
		}
		
		/**
		 * Generate HTML markup for dropdown list
		 *
		 * @param $post_type
		 *
		 * @since 1.0.0
		 */
		public function filter_dropdown( $post_type ) {
			if ( 'page' !== $post_type ):
				return;
			endif;
			
			$selected_template = isset( $_GET['df_template_filter'] ) ? $_GET['df_template_filter'] : 'all';
			$default_title     = apply_filters( 'default_page_template_title', __( 'Default Template', 'df-template-filter' ), 'meta-box' );
			?>
            <select name="df_template_filter" id="df_template_filter">
                <option value="all"><?php _e( 'All Page Templates', 'df-template-filter' ); ?></option>
                <option value="all_missing" style="color:red" <?php echo ( $selected_template == 'all_missing' ) ? ' selected="selected" ' : ""; ?>><?php _e( 'All Missing Page Templates', 'df-template-filter' ); ?></option>
                <option value="default" <?php echo ( $selected_template == 'default' ) ? ' selected="selected" ' : ""; ?>><?php echo esc_html( $default_title ); ?></option>
				<?php page_template_dropdown( $selected_template ); ?>
            </select>
			<?php
		}
		
		/**
		 * Alter query to filter the results
		 *
		 * @param $vars
		 *
		 * @return array|mixed
		 * @since 1.0.0
		 */
		public function filter_post_list( $query ) {
			
			if ( ! ( is_admin() && $query->is_main_query() ) || ! isset( $_GET['df_template_filter'] ) ) :
				return $query;
			endif;
			
			$template = trim( $_GET['df_template_filter'] );
			
			if ( 'page' !== $query->query['post_type'] || '' === $template || 'all' === $template )  :
				return $query;
			endif;
			
			if ( 'all_missing' === $template ) :
				$my_theme = wp_get_theme();
				
				if ( $my_theme->exists() ):
					$templates        = $my_theme->get_page_templates();
					$template_files   = array_keys( $templates );
					$template_files[] = 'default';
					
					$query->query_vars['meta_query'] = array(
						array(
							'key'     => '_wp_page_template',
							'value'   => $template_files,
							'compare' => 'NOT IN',
						)
					);
				endif;
			else:
				$query->query_vars['meta_query'] = array(
					array(
						'key'     => '_wp_page_template',
						'value'   => $template,
						'compare' => '=',
					)
				);
			endif;
			
			return $query;
		}
		
		/**
		 * Add column name
		 *
		 * @param $columns
		 *
		 * @return mixed
		 * @since 1.0.0
		 */
		public function set_template_column( $columns ) {
			$columns['page-layout'] = __( 'Template', 'df-template-filter' );
			
			return $columns;
		}
		
		
		/**
		 * Generate content template column
		 *
		 * @param $column
		 * @param $post_id
		 *
		 * @since 1.0.0
		 */
		public function custom_template_column( $column, $post_id ) {
			if ( 'page-layout' === $column ) :
				$set_template = get_post_meta( $post_id, '_wp_page_template', true );
				
				if ( 'default' === $set_template || empty( $set_template ) ) :
					_e( 'Default Template', 'df-template-filter' );
				else:
					// check all template
					// check if template file is missing
					$found         = false;
					$all_templates = get_page_templates();
					ksort( $all_templates );
					foreach ( array_keys( $all_templates ) as $template ) :
						if ( $set_template === $all_templates[ $template ] ) :
							echo( $template . '<br />(' . $set_template . ')' );
							$found = true;
						endif;
					endforeach;
					
					if ( ! $found ):
						printf( __( 'Template missing "%s"', 'df-template-filter' ), $set_template );
					endif;
				endif;
			endif;
		}
	}
endif;

if ( is_admin() ):
	new DFTemplateFilter();
endif;

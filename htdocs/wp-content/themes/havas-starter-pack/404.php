<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Havas_Starter_Pack
 */

get_header();
?>
    <main class="main" role="main" id="main">
        <div class="main__header">
            <div class="container">
                <h1>
					<?php the_field( '404_title', 'option' ); ?>
                </h1>
            </div>
        </div>
        <div class="main__flexibles">
            <div class="container">
                <div class="c-wysiwyg">
                    <p><?php the_field( '404_description', 'option' ); ?></p>
                </div>
				<?php
				$home_link_label = get_field( '404_home_link_label', 'option' );

				if ( ! empty( $home_link_label ) ):
					?>
                    <a href="<?php echo( esc_url( home_url( '/' ) ) ); ?>" class="c-button"><?php echo( $home_link_label ); ?></a>
				<?php
				endif;
				?>
            </div>
        </div>
    </main><!-- #main -->
<?php
get_footer();

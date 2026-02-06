<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Havas_Starter_Pack
 */

get_header();
?>
    <main class="main" role="main" id="main">
		<?php
		if ( ! is_front_page() ):
			?>
            <div class="main__header">
                <div class="container">
                    <h1>
						<?php the_title(); ?>
                    </h1>
                </div>
            </div>
		<?php
		endif;
		?>
        <div class="main__flexibles">
			<?php get_template_part( 'partials/content', 'flexible' ); ?>
        </div>
    </main><!-- #main -->
<?php
get_footer();

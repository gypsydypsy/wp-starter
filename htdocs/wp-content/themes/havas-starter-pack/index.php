<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Havas_Starter_Pack
 */

get_header();
?>
    <main class="main" role="main" id="main">
        <div class="main__header">
            <div class="container">
                <h1>
					<?php the_title(); ?>
                </h1>
            </div>
        </div>
        <div class="main__flexibles">
	        <?php get_template_part( 'partials/content', 'flexible' ); ?>
        </div>
    </main><!-- #main -->
<?php
get_footer();

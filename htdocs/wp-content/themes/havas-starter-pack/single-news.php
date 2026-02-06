<?php
/**
 * The template for displaying CPT news
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
        <div class="main__header">
            <div class="container">
                <h1>
					<?php the_title(); ?>
                </h1>
                <h6>
					<?php the_date(); ?>
                </h6>
            </div>
        </div>
        <div class="main__flexibles">
	        <?php get_template_part( 'partials/content', 'flexible' ); ?>
        </div>
    </main><!-- #main -->
<?php
get_footer();

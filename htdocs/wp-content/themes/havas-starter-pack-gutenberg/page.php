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
 * @package Havas_Starter_Pack_Gutenberg
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
			<?php the_content(); ?>
        </div>
    </main><!-- #main -->
<?php
get_footer();

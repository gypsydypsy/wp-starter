<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Havas_Starter_Pack
 */

?>

<!-- Exemple de modale -->
 <div class="container">
	 <button class="js-open-modal" aria-haspopup="dialog" aria-controls="modal">Exemple de modale</button>
 </div>

<div class="c-modal" id="modal" aria-label="AJOUTER UN CHAMP TITRE ACCESSIBLE" role="dialog" aria-modal="true">
	<div class="c-modal__overlay"></div>
	<div class="c-modal__ctn">
		<button class="c-modal__ctn-close js-close-modal" aria-controls="modal"><span aria-hidden="true" class="icon-cross"></span><span class="sr-only"><?php esc_attr_e( 'Fermer', 'havas_starter_pack' ); ?></span></button>
		<div class="c-modal__ctn-inner">
			<div class="c-wysiwyg">
				<h2>Je suis une modale</h2>
				<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Facilis tempore, rem qui sit quod accusamus sunt facere nesciunt sint quidem dolorem optio totam eum odio itaque quisquam maxime doloremque magni.</p>
				<a href="#">Je suis un lien dans une modale</a>
			</div>
		</div>
	</div>
</div>

<!-- Footer -->
<footer class="footer" role="contentinfo" id="footer">
    <div class="container">
	    <?php
	    wp_nav_menu(
		    array(
			    'theme_location' => 'menu-footer',
			    'menu_id'        => 'footer-menu',
			    'fallback_cb'    => false,
		    )
	    );
	    ?>
    </div>
</footer>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>

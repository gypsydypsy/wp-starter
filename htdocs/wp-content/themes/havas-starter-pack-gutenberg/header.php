<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Havas_Starter_Pack_Gutenberg
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
    <script>
        tarteaucitron.init({
            "privacyUrl": "", /* Privacy policy url */
            "hashtag": "#rgpd", /* Open the panel with this hashtag */
            "cookieName": "tartaucitron", /* Cookie name */
            "orientation": "bottom", /* Banner position (top - bottom) */
            "showAlertSmall": false, /* Show the small banner on bottom right */
            "cookieslist": true, /* Show the cookie list */
            "adblocker": false, /* Show a Warning if an adblocker is detected */
            "AcceptAllCta": true, /* Show the accept all button when highPrivacy on */
            "highPrivacy": true, /* Disable auto consent */
            "handleBrowserDNTRequest": false, /* If Do Not Track == 1, disallow all */
            "removeCredit": true, /* Remove credit link */
            "moreInfoLink": true, /* Show more info link */
            "useExternalCss": true /* If false, the tarteaucitron.css file will be loaded */
            //"cookieDomain": ".my-multisite-domaine.fr" /* Shared cookie for subdomain website */
        });
        (tarteaucitron.job = tarteaucitron.job || []).push('youtube');
        (tarteaucitron.job = tarteaucitron.job || []).push('dailymotion');
        (tarteaucitron.job = tarteaucitron.job || []).push('vimeo');
    </script>
	<?php if ( WP_DEBUG && get_field( 'jira_is_display_issue_collector', 'option' ) ): ?>
        <!-- script form Issue collector JIRA-->
		<?php the_field( 'jira_script_issue_collector', 'option' ); ?>
        <!-- /script form Issue collector JIRA-->
	<?php endif; ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="app">

    <header class="header">
        <div class="container">
            <div class="header__main">
				<?php
				the_custom_logo();
				if ( is_front_page() && is_home() ) :
					?>
                    <h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"
                                              rel="home"><?php bloginfo( 'name' ); ?></a></h1>
				<?php
				else :
					?>
                    <p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"
                                             rel="home"><?php bloginfo( 'name' ); ?></a></p>
				<?php
				endif;
				$havas_starter_pack_description = get_bloginfo( 'description', 'display' );
				if ( $havas_starter_pack_description || is_customize_preview() ) :
					?>
                    <p class="site-description"><?php echo $havas_starter_pack_description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						?></p>
				<?php endif; ?>
            </div>

            <nav class="header__nav">
                <button class="menu-toggle" aria-controls="primary-menu"
                        aria-expanded="false"><?php esc_html_e( 'Menu', 'havas-core-factory' ); ?></button>
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'menu-navigation',
						'menu_id'        => 'primary-menu',
						'fallback_cb'    => false,
					)
				);
				?>
            </nav>
        </div>
    </header>

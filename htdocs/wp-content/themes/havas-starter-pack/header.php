<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Havas_Starter_Pack
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
	<?php
	if ( ! isset( $_GET['lhci-test'] ) || ! $_GET['lhci-test'] ) :
		?>
        <script>
            tarteaucitron.init({
                "privacyUrl": "<?php echo( esc_js( get_privacy_policy_url() ) );?>", /* Privacy policy url */
                "hashtag": "<?php echo( get_locale() != "fr_FR" ? '#gdpr' : '#rgpd' );?>", /* Open the panel with this hashtag */
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
		<?php
		$jira_snippet_issue_collector = get_field( 'jira_snippet_issue_collector', 'option' );

		if ( WP_DEBUG && get_field( 'jira_is_display_issue_collector', 'option' ) && ! empty( $jira_snippet_issue_collector ) ): ?>
            <!-- script form Issue Collector JIRA-->
			<?php echo( $jira_snippet_issue_collector ); ?>
            <!-- /script form Issue Collector JIRA-->
		<?php endif; ?>
	<?php
	endif;
	?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="app">

    <header class="header" role="banner">
        <div class="container">
            <nav class="header__skiplinks" role="navigation" aria-label="<?php esc_attr_e( 'Accès rapide', 'havas_starter_pack' ); ?>">
                <ul>
                    <li>
                        <a href="#main"><?php esc_attr_e( 'Contenu', 'havas_starter_pack' ); ?></a>
                    </li>
                    <li>
                        <a href="#footer"><?php esc_attr_e( 'Pied de page', 'havas_starter_pack' ); ?></a>
                    </li>
                </ul>
            </nav>
            <div class="header__ctn">
                <div class="header__ctn-left">
                    <div class="header__main">
						<?php
						$logo = get_field( 'logo', 'option' );

						if ( ! empty( $logo ) ):
							if ( is_front_page() || is_home() ) :
								?>
                                <h1 class="header__main-logo">
                                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" title="<?php echo( esc_attr( get_bloginfo( 'name' ) . ' - ' . __( 'Page d\'accueil', 'havas_starter_pack' ) ) ); ?>"><img src="<?php echo( esc_url( $logo['url'] ) ); ?>" alt="<?php echo( esc_attr( $logo['alt'] ) ); ?>" loading="lazy"/></a>
                                </h1>
							<?php else : ?>
                                <p class="header__main-logo">
                                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" title="<?php echo( esc_attr( get_bloginfo( 'name' ) ) ); ?>"><img src="<?php echo( esc_url( $logo['url'] ) ); ?>" alt="<?php echo( esc_attr( $logo['alt'] ) ); ?>" loading="lazy"/></a>
                                </p>
							<?php
							endif;
						endif;
						?>
                    </div>

                    <nav class="header__nav" role="navigation" aria-label="<?php esc_attr_e( 'Menu principal', 'havas_starter_pack' ); ?>" id="modal-header">
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

                    <button aria-controls="modal-header" aria-expanded="false" type="button" class="header__toggle" aria-label="<?php esc_attr_e( 'Show/hide menu', 'havas_starter_pack' ); ?>">
                        <span class="header__toggle-line top"></span>
                        <span class="header__toggle-line middle"></span>
                        <span class="header__toggle-line bottom"></span>
                        <span class="sr-only"><?php esc_attr_e( 'Menu', 'havas_starter_pack' ); ?></span>
                    </button>
                </div>
                <div class="header__ctn-right">
                    <form class="header__search">
                        <input type="text" name="s" aria-label="<?php esc_attr_e( 'Search', 'havas_starter_pack' ); ?>"/>
                        <button type="submit" aria-label="<?php esc_attr_e( 'Search', 'havas_starter_pack' ); ?>"><span aria-hidden="true" class="icon-zoom"></span></button>
                    </form>
                    <form class="header__accessibility">
                        <label for="accesssibility">Accessibility</label>
                        <div class="header__accessibility-radio">
                            <input type="checkbox" id="accesssibility" name="accessibility"/>
                            <span class="header__accessibility-radio-ui"></span>
                        </div>
                    </form>
					<?php
					if ( function_exists( 'pll_the_languages' ) ):
						$langs_array = pll_the_languages( array( 'dropdown' => 0, 'hide_current' => 0, 'raw' => 1 ) );

						if ( $langs_array && count( $langs_array ) > 1 ) : ?>
                            <div class="header__languages" aria-labelledby="language-label">
                                <p id="language-label" class="visuallyHidden"><?php esc_attr_e( 'Langue actuelle', 'havas_starter_pack' ); ?> : <?php echo ucfirst( pll_current_language( 'name' ) ); ?></p>
                                <ul>
									<?php foreach ( $langs_array as $lang ) : ?>
                                        <li>
                                            <a href="<?php echo( esc_url( $lang['url'] ) ); ?>" class="c-link <?php echo( $lang['slug'] == pll_current_language() ? 'double-- active' : 'simple--' ); ?>" <?php echo( $lang['slug'] == pll_current_language() ? 'aria-current="true"' : '' ); ?> title="<?php echo strtoupper( $lang['slug'] ); ?> - <?php echo $lang['name']; ?>">
												<?php echo strtoupper( $lang['slug'] ); ?>
                                            </a>
                                        </li>
									<?php endforeach; ?>
                                </ul>
                            </div>
						<?php endif;
					endif;
					?>
                </div>
            </div>
        </div>
    </header>

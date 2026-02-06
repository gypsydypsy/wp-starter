<?php
/*
 * Custom Admin Login page
 */
if ( ! function_exists( 'hsp_custom_admin_login_style' ) ) :
	/**
	 * Add custom logo for login page
	 * Image size : 320 x 115 pixels
	 */
	function hsp_custom_admin_login_style() { ?>
        <style type="text/css">
            #login h1 a, .login h1 a {
                background-image: url(<?php echo get_template_directory_uri(); ?>/assets/img/logo-client.jpg);
                width: 320px;
                height: 115px;
                background-size: contain;
                background-repeat: no-repeat;
                padding-bottom: 30px;
            }
        </style>
	<?php }
endif;

if ( ! function_exists( 'hsp_custom_admin_login_logo_url' ) ) :
	function hsp_custom_admin_login_logo_url() {
		return home_url( '/' );
	}
endif;

if ( ! function_exists( 'hsp_custom_admin_login_logo_url_title' ) ) :
	function hsp_custom_admin_login_logo_url_title() {
		return get_bloginfo( 'name' );
	}
endif;

add_action( 'login_enqueue_scripts', 'hsp_custom_admin_login_style' );
add_filter( 'login_headerurl', 'hsp_custom_admin_login_logo_url' );
add_filter( 'login_headertext', 'hsp_custom_admin_login_logo_url_title' );

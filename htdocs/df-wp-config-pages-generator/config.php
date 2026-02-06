<?php
ini_set( 'max_execution_time', '600' ); // for infinite time of execution

define( 'DEBUG', false );

if ( DEBUG == true ) {
	ini_set( 'display_errors', 1 );
	error_reporting( E_ALL );
};

$server = $_SERVER['SERVER_NAME'];

//Email from
define( 'EMAIL_FROM', 'admin@mx.havasdigitalfactory.net' );
define( 'NAME_FROM', 'HAVASFACTORY TEMPLATEWP' );

/** email vers dev back **/
define( 'EMAIL_RECIPIENT_ADMIN', 'back.factory@havas.com');
define( 'EMAIL_RECIPIENT_ADMIN_CC', 'rodrigue.medina@havas.com');
define( 'EMAIL_SUBJECT', 'Une nouvelle configuration de site WP a été créée');

//email dest pour erreurs
define( 'EMAILS_RECIPIENT_API_NOTIFY_ERRORS', array( 'back.factory@havas.com' ) );

<?php
/**
 * Modifies the output of a menu item based on specific conditions.
 * accessibility : modifie le markup du menu de navigation du header pour que les items de menu de niveau 0
 * qui a '#' comme url et ayant des enfants, soient rendus par des balises button au lieu de simple href
 *
 * @param   string  $item_output  The HTML output of the menu item.
 * @param   object  $item         The current menu item object.
 * @param   int     $depth        Depth of the menu item in the menu hierarchy. 0 means top level.
 * @param   object  $args         An object containing wp_nav_menu() arguments.
 *
 * @return string The potentially modified HTML output of the menu item.
 */
if ( ! function_exists( 'edit_navigation_menu_item' ) ) :
	function edit_navigation_menu_item( $item_output, $item, $depth, $args ) {
		if ( ( 'menu-navigation' == $args->theme_location || 'menu-footer' == $args->theme_location )
		     && 0 == $depth
		     && ( '#' == $item->url || empty( $item->url ) )
		) {
			if ( in_array( 'menu-item-has-children', $item->classes ) ) {
				return '<button>' . $item->title . '</button>';
			}
		}

		return $item_output;
	}
endif;

add_filter( 'walker_nav_menu_start_el', 'edit_navigation_menu_item', 10, 4 );
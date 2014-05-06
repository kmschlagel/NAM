<?php

/**
 * Plugin Name: NM Custom MultiSite
 * Description: Limits dashboard and toolbar options to necessary functions. TODO: Auto-configure primary menu.
 * Version: 1.0
 * Author: Adam Gregory
 * Author URI: http://agregory.net
 * License: GNU
 */


class stnt_admin_lmt {

	// Initialize the plugin
	function __construct() {

		// Things that happen as part of a new site activation:
		// TODO: Make this actually work...

		// add_action('wpmu_new_blog', 'new_site_config');

		// function new_site_config($blog_id) {
		// 	echo $blog_id;
		// 	switch_to_blog( $blog_id );
		// 	register_nav_menu( 'student-menu', __( 'Primary Menu' ));
		// 	restore_current_blog();
		// }


		// Remove items from Dashboard menu at left
		add_action('admin_menu', 'custom_admin_menu');

		function custom_admin_menu() {
			if ( !is_super_admin() ) {
				remove_menu_page('tools.php');
				remove_menu_page('themes.php');
 				remove_menu_page('edit.php?post_type=page');
 				remove_menu_page('edit.php?post_type=icf_features');
 				remove_menu_page('edit.php?post_type=icf_partners');
 				remove_menu_page('edit.php?post_type=icf_testimonial');
			}
		}


		// Remove submenu items from Dashboard at left.

		add_action('admin_menu', 'custom_admin_submenus');

		function custom_admin_submenus() {

			if ( !is_super_admin() ) {
				remove_submenu_page('users.php', 'user-new.php');
				remove_submenu_page('users.php', 'users.php');
				remove_submenu_page('options-general.php', 'options-permalink.php');
				remove_submenu_page('users.php', 'user-new.php');
				remove_submenu_page('users.php', 'users.php');
				remove_submenu_page('options-general.php', 'options-permalink.php');
			}
		}

		// Remove "+ New" options from toolbar at top

		add_action( 'wp_before_admin_bar_render', 'custom_bar_menu');

		function custom_bar_menu() {
			if ( !is_super_admin() ) {

				global $wp_admin_bar;

				$wp_admin_bar->remove_node( 'new-page' );
				$wp_admin_bar->remove_node( 'new-icf_partners');
				$wp_admin_bar->remove_node( 'new-icf_testimonial');
				$wp_admin_bar->remove_node( 'new-icf_features');
				$wp_admin_bar->remove_node( 'new-user');
			}
		}

	} //end constructor
}



// instantiate plugin's class
$GLOBALS['student admin limits'] = new stnt_admin_lmt()

?>
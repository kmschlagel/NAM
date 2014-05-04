<?php
/**
 *
 * BoldR Pro WordPress Theme by Iceable Themes | http://www.iceablethemes.com
 *
 * Copyright 2013 - 2014 Mathieu Sarrasin - Iceable Media
 *
 * Editor's buttons
 *
 */

// Lists TineMCE plugins to register and add
function icefit_tinymce_plugins_settings(){

	$mce_plugins_dir = get_template_directory_uri() . '/functions/icefit-mce4';
	$icefit_tinymce_plugins = array(
		array('name' => "columns",		'url' => $mce_plugins_dir . '/icefit_columns/icefit_columns.js'),
		array('name' => "lineafter",	'url' => $mce_plugins_dir . '/icefit_lineafter/icefit_lineafter.js'),
		array('name' => "linebefore",	'url' => $mce_plugins_dir . '/icefit_linebefore/icefit_linebefore.js'),
		array('name' => "dropcap",		'url' => $mce_plugins_dir . '/icefit_dropcap/icefit_dropcap.js'),
		array('name' => "highlight",	'url' => $mce_plugins_dir . '/icefit_highlight/icefit_highlight.js'),
		array('name' => "buttons",		'url' => $mce_plugins_dir . '/icefit_button/icefit_button.js'),
		array('name' => "cta",			'url' => $mce_plugins_dir . '/icefit_cta/icefit_cta.js'),
		array('name' => "alertbox",		'url' => $mce_plugins_dir . '/icefit_alertbox/icefit_alertbox.js'),
		array('name' => "tabs",			'url' => $mce_plugins_dir . '/icefit_tabs/icefit_tabs.js'),
		array('name' => "toggles",		'url' => $mce_plugins_dir . '/icefit_toggles/icefit_toggles.js'),
		array('name' => "accordions",	'url' => $mce_plugins_dir . '/icefit_accordions/icefit_accordions.js'),
		array('name' => "sliders",		'url' => $mce_plugins_dir . '/icefit_sliders/icefit_sliders.js'),
		array('name' => "blogposts",	'url' => $mce_plugins_dir . '/icefit_blogposts/icefit_blogposts.js'),
		array('name' => "portfolio",	'url' => $mce_plugins_dir . '/icefit_portfolio/icefit_portfolio.js'),
		array('name' => "testimonials",	'url' => $mce_plugins_dir . '/icefit_testimonials/icefit_testimonials.js'),
		array('name' => "features",		'url' => $mce_plugins_dir . '/icefit_features/icefit_features.js'),
		array('name' => "partners",		'url' => $mce_plugins_dir . '/icefit_partners/icefit_partners.js'),
		array('name' => "contactform",	'url' => $mce_plugins_dir . '/icefit_contactform/icefit_contactform.js'),
		array('name' => "maps",			'url' => $mce_plugins_dir . '/icefit_maps/icefit_maps.js'),
		array('name' => "icefit_visualshortcodes",		'url' => $mce_plugins_dir . '/icefit_visualshortcodes/plugin.js'),
	);
	return $icefit_tinymce_plugins;
}

// Visual shortcodes: styling for the edit/delete buttons
add_action('admin_head', 'icefit_visualshortcodes_style');
function icefit_visualshortcodes_style() {
	if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') ) return;
	if ( get_user_option('rich_editing') == 'true'):	
		echo '<style>#icefit_visual_buttons, #icefit_visual_button { display:none; background: none; }
.icefit_visual_button_icon { margin-top: 7px;
margin-right: 7px;
padding: 2px;
width: 30px;
height: 30px;
box-shadow: 0 1px 3px rgba(0, 0, 0, 0.5);
background-color: #000;
background-color: rgba(0, 0, 0, 0.9);
cursor: pointer;
color: #fff;
font-size: 30px; }
.icefit_visual_button_icon:hover { box-shadow: 0 1px 3px rgba(0, 0, 0, 0.8); color: #2EA2CC; }
</style>';
  	endif;
}

// init process for button control
add_action('init', 'icefit_custom_addbuttons');
function icefit_custom_addbuttons() {
	// Only process if user is allowed
	if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') ) return;
	// Only add buttons in visual editor
	if ( get_user_option('rich_editing') == 'true') {
		add_filter("mce_external_plugins", "icefit_add_tinymce_plugin");
		add_filter('mce_buttons_3', 'icefit_register_button');
	}
}

// Register TinyMCE buttons
function icefit_register_button($buttons) {
	$icefit_tinymce_plugins = icefit_tinymce_plugins_settings();
	foreach($icefit_tinymce_plugins as $plugin):
		array_push($buttons, "", $plugin['name']);
	endforeach;
	return $buttons;
} 

// Add TinyMCE plugins
function icefit_add_tinymce_plugin($plugin_array) {
	$icefit_tinymce_plugins = icefit_tinymce_plugins_settings();
	foreach($icefit_tinymce_plugins as $plugin):
		$plugin_array[$plugin['name']] = $plugin['url'];
	endforeach;
	return $plugin_array;
}

?>
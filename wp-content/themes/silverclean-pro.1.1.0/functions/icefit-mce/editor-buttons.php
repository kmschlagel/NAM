<?php
/**
 *
 * Silverclean Pro WordPress Theme by Iceable Themes | http://www.iceablethemes.com
 *
 * Copyright 2013 Mathieu Sarrasin - Iceable Media
 *
 * Editor's buttons
 *
 */


// Lists plugins to register and add
function icefit_tinymce_plugins_settings(){

	$icefit_tinymce_plugins = array(
		array('name' => "format_column",
		'url' => get_stylesheet_directory_uri() . '/functions/icefit-mce/icefit_columns/icefit_columns.js'),
		array('name' => "remove_column",
		'url' => get_stylesheet_directory_uri() . '/functions/icefit-mce/icefit_columns/icefit_columns.js'),
		array('name' => "insert_lineBefore",
		'url' => get_stylesheet_directory_uri() . '/functions/icefit-mce/icefit_columns/icefit_columns.js'),
		array('name' => "insert_lineAfter",
		'url' => get_stylesheet_directory_uri() . '/functions/icefit-mce/icefit_columns/icefit_columns.js'),
		array('name' => "dropcap",
		'url' => get_stylesheet_directory_uri() . '/functions/icefit-mce/icefit_dropcap/icefit_dropcap.js'),
		array('name' => "highlight",
		'url' => get_stylesheet_directory_uri() . '/functions/icefit-mce/icefit_highlight/icefit_highlight.js'),
		array('name' => "add_blockquote",
		'url' => get_stylesheet_directory_uri() . '/functions/icefit-mce/icefit_blockquote/icefit_blockquote.js'),
		array('name' => "add_divider",
		'url' => get_stylesheet_directory_uri() . '/functions/icefit-mce/icefit_divider/icefit_divider.js'),
		array('name' => "button",
		'url' => get_stylesheet_directory_uri() . '/functions/icefit-mce/icefit_button/icefit_button.js'),
		array('name' => "cta",
		'url' => get_stylesheet_directory_uri() . '/functions/icefit-mce/icefit_cta/icefit_cta.js'),
		array('name' => "alertbox",
		'url' => get_stylesheet_directory_uri() . '/functions/icefit-mce/icefit_alertbox/icefit_alertbox.js'),
		array('name' => "tabs",
		'url' => get_stylesheet_directory_uri() . '/functions/icefit-mce/icefit_tabs/icefit_tabs.js'),
		array('name' => "toggles",
		'url' => get_stylesheet_directory_uri() . '/functions/icefit-mce/icefit_toggles/icefit_toggles.js'),
		array('name' => "accordions",
		'url' => get_stylesheet_directory_uri() . '/functions/icefit-mce/icefit_accordions/icefit_accordions.js'),
		array('name' => "sliders",
		'url' => get_stylesheet_directory_uri() . '/functions/icefit-mce/icefit_sliders/icefit_sliders.js'),
		array('name' => "blogposts",
		'url' => get_stylesheet_directory_uri() . '/functions/icefit-mce/icefit_blogposts/icefit_blogposts.js'),
		array('name' => "portfolio",
		'url' => get_stylesheet_directory_uri() . '/functions/icefit-mce/icefit_portfolio/icefit_portfolio.js'),
		array('name' => "testimonials",
		'url' => get_stylesheet_directory_uri() . '/functions/icefit-mce/icefit_testimonials/icefit_testimonials.js'),
		array('name' => "features",
		'url' => get_stylesheet_directory_uri() . '/functions/icefit-mce/icefit_features/icefit_features.js'),
		array('name' => "partners",
		'url' => get_stylesheet_directory_uri() . '/functions/icefit-mce/icefit_partners/icefit_partners.js'),
		array('name' => "contactform",
		'url' => get_stylesheet_directory_uri() . '/functions/icefit-mce/icefit_contactform/icefit_contactform.js'),
		array('name' => "maps",
		'url' => get_stylesheet_directory_uri() . '/functions/icefit-mce/icefit_maps/icefit_maps.js'),
	);
	return $icefit_tinymce_plugins;
}

// VISUAL SHORTCODES  
function button_images_filter( $shortcodes ) {
    array_push(
        $shortcodes,
        array(
            'shortcode' => 'blogposts',
            'image'     => get_stylesheet_directory_uri() . '/functions/visual-shortcodes/null.png',
            'command'   => 'insert_blogposts'
        ),
        array(
            'shortcode' => 'portfolio',
            'image'     => get_stylesheet_directory_uri() . '/functions/visual-shortcodes/null.png',
            'command'   => 'insert_portfolio'
        ),
        array(
            'shortcode' => 'testimonials',
            'image'     => get_stylesheet_directory_uri() . '/functions/visual-shortcodes/null.png',
            'command'   => 'insert_testimonials'
        ),
        array(
            'shortcode' => 'features',
            'image'     => get_stylesheet_directory_uri() . '/functions/visual-shortcodes/null.png',
            'command'   => 'insert_features'
        ),
        array(
            'shortcode' => 'partners',
            'image'     => get_stylesheet_directory_uri() . '/functions/visual-shortcodes/null.png',
            'command'   => 'insert_partners'
        ),
        array(
            'shortcode' => 'contact-form',
            'image'     => get_stylesheet_directory_uri() . '/functions/visual-shortcodes/null.png',
            'command'   => 'insert_contactform'
        ),
        array(
            'shortcode' => 'maps',
            'image'     => get_stylesheet_directory_uri() . '/functions/visual-shortcodes/null.png',
            'command'   => 'insert_maps'
        )
    );
    return $shortcodes;
}
add_filter('jpb_visual_shortcodes', 'button_images_filter');

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
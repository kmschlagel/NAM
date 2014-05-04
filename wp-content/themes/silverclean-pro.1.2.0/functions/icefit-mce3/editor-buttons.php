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


include_once('visual-shortcodes/visual-shortcodes.php'); // Visual Shortcodes


// Lists plugins to register and add
function icefit_tinymce_plugins_settings(){

	$template_directory_uri = get_template_directory_uri();

	$icefit_tinymce_plugins = array(
		array('name' => "format_column",
		'url' => $template_directory_uri . '/functions/icefit-mce3/icefit_columns/icefit_columns.js'),
		array('name' => "remove_column",
		'url' => $template_directory_uri . '/functions/icefit-mce3/icefit_columns/icefit_columns.js'),
		array('name' => "insert_lineBefore",
		'url' => $template_directory_uri . '/functions/icefit-mce3/icefit_columns/icefit_columns.js'),
		array('name' => "insert_lineAfter",
		'url' => $template_directory_uri . '/functions/icefit-mce3/icefit_columns/icefit_columns.js'),
		array('name' => "dropcap",
		'url' => $template_directory_uri . '/functions/icefit-mce3/icefit_dropcap/icefit_dropcap.js'),
		array('name' => "highlight",
		'url' => $template_directory_uri . '/functions/icefit-mce3/icefit_highlight/icefit_highlight.js'),
		array('name' => "add_blockquote",
		'url' => $template_directory_uri . '/functions/icefit-mce3/icefit_blockquote/icefit_blockquote.js'),
		array('name' => "add_divider",
		'url' => $template_directory_uri . '/functions/icefit-mce3/icefit_divider/icefit_divider.js'),
		array('name' => "button",
		'url' => $template_directory_uri . '/functions/icefit-mce3/icefit_button/icefit_button.js'),
		array('name' => "cta",
		'url' => $template_directory_uri . '/functions/icefit-mce3/icefit_cta/icefit_cta.js'),
		array('name' => "alertbox",
		'url' => $template_directory_uri . '/functions/icefit-mce3/icefit_alertbox/icefit_alertbox.js'),
		array('name' => "tabs",
		'url' => $template_directory_uri . '/functions/icefit-mce3/icefit_tabs/icefit_tabs.js'),
		array('name' => "toggles",
		'url' => $template_directory_uri . '/functions/icefit-mce3/icefit_toggles/icefit_toggles.js'),
		array('name' => "accordions",
		'url' => $template_directory_uri . '/functions/icefit-mce3/icefit_accordions/icefit_accordions.js'),
		array('name' => "sliders",
		'url' => $template_directory_uri . '/functions/icefit-mce3/icefit_sliders/icefit_sliders.js'),
		array('name' => "blogposts",
		'url' => $template_directory_uri . '/functions/icefit-mce3/icefit_blogposts/icefit_blogposts.js'),
		array('name' => "portfolio",
		'url' => $template_directory_uri . '/functions/icefit-mce3/icefit_portfolio/icefit_portfolio.js'),
		array('name' => "testimonials",
		'url' => $template_directory_uri . '/functions/icefit-mce3/icefit_testimonials/icefit_testimonials.js'),
		array('name' => "features",
		'url' => $template_directory_uri . '/functions/icefit-mce3/icefit_features/icefit_features.js'),
		array('name' => "partners",
		'url' => $template_directory_uri . '/functions/icefit-mce3/icefit_partners/icefit_partners.js'),
		array('name' => "contactform",
		'url' => $template_directory_uri . '/functions/icefit-mce3/icefit_contactform/icefit_contactform.js'),
		array('name' => "maps",
		'url' => $template_directory_uri . '/functions/icefit-mce3/icefit_maps/icefit_maps.js'),
	);
	return $icefit_tinymce_plugins;
}

// VISUAL SHORTCODES  
function button_images_filter( $shortcodes ) {

	$template_directory_uri = get_template_directory_uri();

    array_push(
        $shortcodes,
        array(
            'shortcode' => 'blogposts',
            'image'     => $template_directory_uri . '/functions/icefit-mce3/visual-shortcodes/null.png',
            'command'   => 'insert_blogposts'
        ),
        array(
            'shortcode' => 'portfolio',
            'image'     => $template_directory_uri . '/functions/icefit-mce3/visual-shortcodes/null.png',
            'command'   => 'insert_portfolio'
        ),
        array(
            'shortcode' => 'testimonials',
            'image'     => $template_directory_uri . '/functions/icefit-mce3/visual-shortcodes/null.png',
            'command'   => 'insert_testimonials'
        ),
        array(
            'shortcode' => 'features',
            'image'     => $template_directory_uri . '/functions/icefit-mce3/visual-shortcodes/null.png',
            'command'   => 'insert_features'
        ),
        array(
            'shortcode' => 'partners',
            'image'     => $template_directory_uri . '/functions/icefit-mce3/visual-shortcodes/null.png',
            'command'   => 'insert_partners'
        ),
        array(
            'shortcode' => 'contact-form',
            'image'     => $template_directory_uri . '/functions/icefit-mce3/visual-shortcodes/null.png',
            'command'   => 'insert_contactform'
        ),
        array(
            'shortcode' => 'maps',
            'image'     => $template_directory_uri . '/functions/icefit-mce3/visual-shortcodes/null.png',
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
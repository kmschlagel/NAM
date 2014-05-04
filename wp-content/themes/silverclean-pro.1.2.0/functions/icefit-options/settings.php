<?php
/**
 *
 * Silverclean Pro WordPress Theme by Iceable Themes | http://www.iceablethemes.com
 *
 * Copyright 2013 - 2014 Mathieu Sarrasin - Iceable Media
 *
 * Admin settings template
 *
 */

// Load the icefit options framework
include_once('icefit-options.php');

// Set setting panel name and slug
$icefit_settings_name = "Silverclean Pro Settings";
$icefit_settings_slug = "silverclean_settings";

// Set settings template
function icefit_settings_template() {

	global $icefit_options;

	/* Prepare slider category selector options */
    $cats = get_terms('icf-slides-category');
    $slides_cat[] = array('value' => 'All Slides', 'display' => __('All Slides', 'icefit') );
  	if ($cats):
	  	foreach($cats as $cat):
		  	$slides_cat[] = array('value' => $cat->slug, 'display' => $cat->name);
		endforeach;
	endif;

	/* Prepare sidebars selector */
	$icefit_unlimited_sidebars = $icefit_options['unlimited_sidebar'];
	$icefit_sidebars_list = explode("\n", $icefit_unlimited_sidebars);
	$sidebar_options[] = array('display' => __('Default Sidebar', 'icefit'), 'value' => 'sidebar');
	foreach ($icefit_sidebars_list as $additional_sidebar) {
		if ($additional_sidebar != "") {
			$sidebar_options[] = array(
				'display' => $additional_sidebar,
				'value' => sanitize_title($additional_sidebar)
				);
		}
	}

	$settings_options = array();

// START MAIN SETTINGS SECTION
	$settings_options[] = array(
		'name'          => __('Main settings', 'icefit'),
		'type'          => 'start_menu',
		'id'            => 'main',
		'icon'          => 'control',
	);

		$settings_options[] = array(
			'name'          => __('Favicon', 'icefit'),
			'desc'          => __('Set your favicon. 16x16 or 32x32 pixels, either 8-bit or 24-bit colors. PNG (W3C standard), GIF, or ICO.', 'icefit'),
			'id'            => 'favicon',
			'type'          => 'image',
			'default'       => '',
		);

		$settings_options[] = array(
			'name'          => __('Custom Header Code', 'icefit'),
			'desc'          => __('Paste your own custom code to be added in the page header (before the closing &lt;/head&gt; tag).', 'icefit'),
			'id'            => 'custom_header_code',
			'default'       => '',
			'type'          => 'textarea',
		);

		$settings_options[] = array(
			'name'          => __('Tracking Code', 'icefit'),
			'desc'          => __('Paste your own custom code to be added in the footer (i.e. Google Analytics tracking code, before the closing &lt;/body&gt; tag).', 'icefit'),
			'id'            => 'tracking_code',
			'default'       => '',
			'type'          => 'textarea',
		);

		$settings_options[] = array(
			'name'          => __('Custom CSS', 'icefit'),
			'desc'          => __('Paste your custom CSS here', 'icefit'),
			'id'            => 'custom_css',
			'default'       => '',
			'type'          => 'textarea',
		);

		$settings_options[] = array(
			'name'          => __('Responsive mode', 'icefit'),
			'desc'          => __('Turn this setting off if you want your site to be unresponsive.', 'icefit'),
			'id'            => 'responsive_mode',
			'type'          => 'radio',
			'default'       => 'on',
			'values'		=> array (
								array( 'value' => 'on', 'display' => __('On', 'icefit') ),
								array( 'value' => 'off', 'display' => __('Off', 'icefit') ),
								),
		);

	$settings_options[] = array('type' => 'end_menu');
// END MAIN SETTINGS SECTION

// START STYLING
	$settings_options[] = array(
		'name'          => __('Main Styling', 'icefit'),
		'type'          => 'start_menu',
		'id'            => 'main_styling',
		'icon'          => 'picture',
	);

		$settings_options[] = array(
			'name'          => __('Content Font', 'icefit'),
			'desc'          => '',
			'id'            => 'content_font',
			'type'          => 'font',
			'default'       => 'Lucida Grande',
		);

		$settings_options[] = array(
			'name'          => __('Content Font Size', 'icefit'),
			'desc'          => __('In px. Default is 12.', 'icefit'),
			'id'            => 'content_font_size',
			'type'          => 'text',
			'default'       => '12',
		);

		$settings_options[] = array(
			'name'          => __('Layout', 'icefit'),
			'desc'          => __('Choose between wide or boxed layout', 'icefit'),
			'id'            => 'layout',
			'type'          => 'radio',
			'default'       => 'Boxed',
			'values'		=> array (
								array( 'value' => 'Wide', 'display' => __('Wide', 'icefit') ),
								array( 'value' => 'Boxed', 'display' => __('Boxed', 'icefit') ),
								),
			);

		$settings_options[] = array(
			'name'          => __('Background Color (boxed layout)', 'icefit'),
			'desc'          => __('Set a background color (for boxed layout - used as fallback only if an image is set below)', 'icefit'),
			'id'            => 'background_color',
			'type'          => 'color',
			'default'       => '#eee',
		);

		$settings_options[] = array(
			'name'          => __('Background Image (boxed layout)', 'icefit'),
			'desc'          => __('Upload your own background (for boxed layout)', 'icefit'),
			'id'            => 'background_image',
			'type'          => 'image',
			'default'       => '',
		);

		$settings_options[] = array(
			'name'          => __('Background Image Size (boxed layout)', 'icefit'),
			'desc'          => '',
			'id'            => 'background_image_size',
			'type'          => 'radio',
			'default'       => 'auto',
			'values'		=> array (
								array( 'value' => 'auto', 'display' => __('Default (auto)', 'icefit') ),
								array( 'value' => 'cover', 'display' => __('Cover', 'icefit') ),
								),
		);

		$settings_options[] = array(
			'name'          => __('Background Image Repeat (boxed layout)', 'icefit'),
			'desc'          => '',
			'id'            => 'background_image_repeat',
			'type'          => 'radio',
			'default'       => 'Tile',
			'values'		=> array (
								array( 'value' => 'No Repeat', 'display' => __('No Repeat', 'icefit') ),
								array( 'value' => 'Tile', 'display' => __('Tile', 'icefit') ),
								array( 'value' => 'Tile Horizontally', 'display' => __('Tile Horizontally', 'icefit') ),
								array( 'value' => 'Tile Vertically', 'display' => __('Tile Vertically', 'icefit') ),
								),
		);

		$settings_options[] = array(
			'name'          => __('Background Image Position (boxed layout)', 'icefit'),
			'desc'          => '',
			'id'            => 'background_image_position',
			'type'          => 'radio',
			'default'       => 'Left',
			'values'		=> array (
								array( 'value' => 'Left', 'display' => __('Left', 'icefit') ),
								array( 'value' => 'Center', 'display' => __('Center', 'icefit') ),
								array( 'value' => 'Right', 'display' => __('Right', 'icefit') ),
								),
		);

		$settings_options[] = array(
			'name'          => __('Background Image Attachment (boxed layout)', 'icefit'),
			'desc'          => '',
			'id'            => 'background_image_attachment',
			'type'          => 'radio',
			'default'       => 'Scroll',
			'values'		=> array (
								array( 'value' => 'Scroll', 'display' => __('Scroll', 'icefit') ),
								array( 'value' => 'Fixed', 'display' => __('Fixed', 'icefit') ),
								),
		);		
		
	$settings_options[] = array('type' => 'end_menu', 'id' => 'endpage3');
// END STYLING

// START HEADER SETTINGS
	$settings_options[] = array(
		'name'          => __('Header Settings', 'icefit'),
		'type'          => 'start_menu',
		'id'            => 'header_settings',
		'icon'          => 'up',
	);

		$settings_options[] = array(
			'name'          => __('Logo', 'icefit'),
			'desc'          => __('Upload your own logo', 'icefit'),
			'id'            => 'logo',
			'type'          => 'image',
			'default'       => get_template_directory_uri() .'/img/logo.png',
		);

		$settings_options[] = array(
			'name'          => __('Site Title', 'icefit'),
			'desc'          => __('Choose "display title" if you want to use a text-based title instead of an uploaded logo.', 'icefit'),
			'id'            => 'header_title',
			'type'          => 'radio',
			'default'       => 'Use Logo',
			'values'		=> array (
								array( 'value' => 'Use Logo', 'display' => __('Use Logo', 'icefit') ),
								array( 'value' => 'Display Title', 'display' => __('Display Title', 'icefit') ),
								),
		);

		$settings_options[] = array(
			'name'          => __('Site Title Font', 'icefit'),
			'desc'          => '',
			'id'            => 'header_title_font_family',
			'type'          => 'font',
			'default'       => 'Lucida Grande',
		);

		$settings_options[] = array(
			'name'          => __('Site Title Font Size', 'icefit'),
			'desc'          => __('Font size for the site title, in pixels (default to 21)', 'icefit'),
			'id'            => 'header_title_font_size',
			'type'          => 'text',
			'default'       => '21',
		);

		$settings_options[] = array(
			'name'          => __('Site Title Font Color', 'icefit'),
			'desc'          => __('Font color for the site title, in hex (default to #444444)', 'icefit'),
			'id'            => 'header_title_font_color',
			'type'          => 'color',
			'default'       => '#444444',
		);

		$settings_options[] = array(
			'name'          => __('Header Layout', 'icefit'),
			'desc'          => __('Tagline and Custom HTML can be set below.', 'icefit'),
			'id'            => 'header_layout',
			'type'          => 'radio',
			'default'       => 'Logo Left',
			'values'		=> array (
								array( 'value' => 'Logo Left', 'display' => __('Logo Left', 'icefit') ),
								array( 'value' => 'Logo Center', 'display' => __('Logo Center', 'icefit') ),
								array( 'value' => 'Logo left + Tagline', 'display' => __('Logo left + Tagline', 'icefit') ),
								array( 'value' => 'Logo Left + Social Media', 'display' => __('Logo Left + Social Media', 'icefit') ),
								array( 'value' => 'Logo Left + Custom HTML', 'display' => __('Logo Left + Custom HTML', 'icefit') ),
								),
		);

		$settings_options[] = array(
			'name'          => __('Tagline Font', 'icefit'),
			'desc'          => '',
			'id'            => 'header_tagline_font_family',
			'type'          => 'font',
			'default'       => 'Lucida Grande',
		);

		$settings_options[] = array(
			'name'          => __('Tagline Font Size', 'icefit'),
			'desc'          => __('Font size for the tagline, in pixels (default to 16)', 'icefit'),
			'id'            => 'header_tagline_font_size',
			'type'          => 'text',
			'default'       => '16',
		);

		$settings_options[] = array(
			'name'          => __('Tagline Font Color', 'icefit'),
			'desc'          => __('Font color for the tagline, in hex (default to #707070)', 'icefit'),
			'id'            => 'header_tagline_font_color',
			'type'          => 'color',
			'default'       => '#707070',
		);
		
		$settings_options[] = array(
			'name'          => __('Header Custom HTML', 'icefit'),
			'desc'          => '',
			'id'            => 'header_custom_HTML',
			'type'          => 'textarea',
			'default'       => '',
		);

		$settings_options[] = array(
			'name'          => __('Header Top Padding', 'icefit'),
			'desc'          => __('Adjust the padding top value for the header area, in pixels (default to 25)', 'icefit'),
			'id'            => 'header_padding_top',
			'type'          => 'text',
			'default'       => '25',
		);

		$settings_options[] = array(
			'name'          => __('Header Bottom Padding', 'icefit'),
			'desc'          => __('Adjust the padding bottom value for the header area, in pixels (default to 25)', 'icefit'),
			'id'            => 'header_padding_bottom',
			'type'          => 'text',
			'default'       => '25',
		);
		
		$settings_options[] = array(
			'name'          => __('Sticky Header', 'icefit'),
			'desc'          => '',
			'id'            => 'sticky_header',
			'type'          => 'radio',
			'default'       => 'Normal Header Only',
			'values'		=> array (
								array( 'value' => 'Normal Header Only', 'display' => __('Normal Header Only', 'icefit') ),
								array( 'value' => 'Sticky Header on scroll', 'display' => __('Sticky Header on scroll', 'icefit') ),
								array( 'value' => 'Sticky Navbar on scroll', 'display' => __('Sticky Navbar on scroll', 'icefit') ),
								array( 'value' => 'Sticky Navbar on scroll + Logo', 'display' => __('Sticky Navbar on scroll + Logo', 'icefit') ),
								array( 'value' => 'Sticky Header Only', 'display' => __('Sticky Header Only', 'icefit') ),
								array( 'value' => 'Sticky Navbar Only', 'display' => __('Sticky Navbar Only', 'icefit') ),
								array( 'value' => 'Sticky Navbar Only + Logo', 'display' => __('Sticky Navbar Only + Logo', 'icefit') ),
								array( 'value' => 'No Header', 'display' => __('No Header', 'icefit') ),
								),
		);

	$settings_options[] = array('type' => 'end_menu');
// END HEADER SETTINGS

// START MENU LAYOUT
	$settings_options[] = array(
		'name'          => __('Navbar Layout', 'icefit'),
		'type'          => 'start_menu',
		'id'            => 'menu_layout',
		'icon'          => 'home',
	);

		$settings_options[] = array(
			'name'          => __('Menu Layout', 'icefit'),
			'desc'          => __('"Left + Social media" looks best with no more than 5 icons.', 'icefit'),
			'id'            => 'menu_layout',
			'type'          => 'radio',
			'default'       => 'Left + Search',
			'values'		=> array (
								array( 'value' => 'Left + Search', 'display' => __('Left + Search', 'icefit') ),
								array( 'value' => 'Left No Search', 'display' => __('Left No Search', 'icefit') ),
								array( 'value' => 'Center No Search', 'display' => __('Center No Search', 'icefit') ),
								array( 'value' => 'Left + Social Media', 'display' => __('Left + Social Media', 'icefit') ),
								),
		);
		
		$settings_options[] = array(
			'name'          => __('Menu Font', 'icefit'),
			'desc'          => '',
			'id'            => 'menu_font',
			'type'          => 'font',
			'default'       => 'Lucida Grande',
		);		

		$settings_options[] = array(
			'name'          => __('Menu Font Size', 'icefit'),
			'desc'          => __('In px, default to 12.', 'icefit'),
			'id'            => 'menu_font_size',
			'type'          => 'text',
			'default'       => '12',
		);

	$settings_options[] = array('type' => 'end_menu');
// END MENU LAYOUT

// START HEADINGS STYLING
	$settings_options[] = array(
		'name'          => __('Headings Styling', 'icefit'),
		'type'          => 'start_menu',
		'id'            => 'headings_styling',
		'icon'          => 'list',
	);

		$settings_options[] = array(
			'name'          => __('Headings Font', 'icefit'),
			'desc'          => __('Choose a font for headings', 'icefit'),
			'id'            => 'headings_font',
			'type'          => 'font',
			'default'       => 'Lucida Grande',
		);

		$settings_options[] = array(
			'name'          => __('H1 font size', 'icefit'),
			'desc'          => __('In px. Default = 21.', 'icefit'),
			'id'            => 'h1_font_size',
			'type'          => 'text',
			'default'       => '21',
		);

		$settings_options[] = array(
			'name'          => __('H1 font color', 'icefit'),
			'desc'          => '',
			'id'            => 'h1_font_color',
			'type'          => 'color',
			'default'       => '#333333',
		);

		$settings_options[] = array(
			'name'          => __('H2 font size', 'icefit'),
			'desc'          => __('In px. Default = 18.', 'icefit'),
			'id'            => 'h2_font_size',
			'type'          => 'text',
			'default'       => '18',
		);

		$settings_options[] = array(
			'name'          => __('H2 font color', 'icefit'),
			'desc'          => '',
			'id'            => 'h2_font_color',
			'type'          => 'color',
			'default'       => '#333333',
		);

		$settings_options[] = array(
			'name'          => __('H3 font size', 'icefit'),
			'desc'          => __('In px. Default = 16.', 'icefit'),
			'id'            => 'h3_font_size',
			'type'          => 'text',
			'default'       => '16',
		);

		$settings_options[] = array(
			'name'          => __('H3 font color', 'icefit'),
			'desc'          => '',
			'id'            => 'h3_font_color',
			'type'          => 'color',
			'default'       => '#333333',
		);

		$settings_options[] = array(
			'name'          => __('H4 font size', 'icefit'),
			'desc'          => __('In px. Default = 14.', 'icefit'),
			'id'            => 'h4_font_size',
			'type'          => 'text',
			'default'       => '14',
		);

		$settings_options[] = array(
			'name'          => __('H4 font color', 'icefit'),
			'desc'          => '',
			'id'            => 'h4_font_color',
			'type'          => 'color',
			'default'       => '#333333',
		);

		$settings_options[] = array(
			'name'          => __('H5 font size', 'icefit'),
			'desc'          => __('In px. Default = 13.', 'icefit'),
			'id'            => 'h5_font_size',
			'type'          => 'text',
			'default'       => '13',
		);

		$settings_options[] = array(
			'name'          => __('H5 font color', 'icefit'),
			'desc'          => '',
			'id'            => 'h5_font_color',
			'type'          => 'color',
			'default'       => '#333333',
		);
		
		$settings_options[] = array(
			'name'          => __('H6 font size', 'icefit'),
			'desc'          => __('In px. Default = 12.', 'icefit'),
			'id'            => 'h6_font_size',
			'type'          => 'text',
			'default'       => '12',
		);

		$settings_options[] = array(
			'name'          => __('H6 font color', 'icefit'),
			'desc'          => '',
			'id'            => 'h6_font_color',
			'type'          => 'color',
			'default'       => '#333333',
		);

		$settings_options[] = array(
			'name'          => __('Page Titles font size', 'icefit'),
			'desc'          => __('In px. Default = 21.', 'icefit'),
			'id'            => 'page_title_font_size',
			'type'          => 'text',
			'default'       => '21',
		);

		$settings_options[] = array(
			'name'          => __('Page Titles font color', 'icefit'),
			'desc'          => '',
			'id'            => 'page_title_font_color',
			'type'          => 'color',
			'default'       => '#333333',
		);

		$settings_options[] = array(
			'name'          => __('Blogpost Titles font size', 'icefit'),
			'desc'          => __('In px. Default = 16.', 'icefit'),
			'id'            => 'entry_title_font_size',
			'type'          => 'text',
			'default'       => '16',
		);

		$settings_options[] = array(
			'name'          => __('Blogpost Titles font color', 'icefit'),
			'desc'          => '',
			'id'            => 'entry_title_font_color',
			'type'          => 'color',
			'default'       => '#333333',
		);

	$settings_options[] = array('type' => 'end_menu');
// END HEADINGS STYLING

// START SLIDER SETTINGS
	$settings_options[] = array(
		'name'          => __('Slider Settings', 'icefit'),
		'type'          => 'start_menu',
		'id'            => 'slider_settings',
		'icon'          => 'control-h',
	);

		$settings_options[] = array(
			'name'          => __('Slider animation', 'icefit'),
			'desc'          => '',
			'id'            => 'flexslider_animation',
			'type'          => 'radio',
			'default'       => 'slide',
			'values'		=> array (
								array( 'value' => 'slide', 'display' => __('Slide', 'icefit') ),
								array( 'value' => 'fade', 'display' => __('Fade', 'icefit') ),
								),
		);

		$settings_options[] = array(
			'name'          => __('Slider delay', 'icefit'),
			'desc'          => __('Set the delay between slides, in milliseconds; default to 4000 ( = 4 seconds)', 'icefit'),
			'id'            => 'flexslider_delay',
			'type'          => 'text',
			'default'       => '4000',
		);

		$settings_options[] = array(
			'name'          => __('Animation speed', 'icefit'),
			'desc'          => __('Set the animation speed, in milliseconds; default to 600 ( = 0.6 seconds)', 'icefit'),
			'id'            => 'flexslider_animationspeed',
			'type'          => 'text',
			'default'       => '600',
		);

		$settings_options[] = array(
			'name'          => __('Slide direction', 'icefit'),
			'desc'          => '',
			'id'            => 'flexslider_direction',
			'type'          => 'radio',
			'default'       => 'horizontal',
			'values'		=> array (
								array( 'value' => 'horizontal', 'display' => __('Horizontal', 'icefit') ),
								array( 'value' => 'vertical', 'display' => __('Vertical', 'icefit') ),
								),
		);

		$settings_options[] = array(
			'name'          => __('Reverse animation direction', 'icefit'),
			'desc'          => '',
			'id'            => 'flexslider_reverse',
			'type'          => 'radio',
			'default'       => 'false',
			'values'		=> array (
								array( 'value' => 'false', 'display' => __('No', 'icefit') ),
								array( 'value' => 'true', 'display' => __('Yes', 'icefit') ),
								),
		);

		$settings_options[] = array(
			'name'          => __('Auto animate slideshow', 'icefit'),
			'desc'          => __('Animate the slideshow automatically. Set to false to only allow manual animation (i.e. when users click the slide navigation buttons)', 'icefit'),
			'id'            => 'flexslider_slideshow',
			'type'          => 'radio',
			'default'       => 'true',
			'values'		=> array (
								array( 'value' => 'true', 'display' => __('Yes', 'icefit') ),
								array( 'value' => 'false', 'display' => __('No', 'icefit') ),
								),
		);

		$settings_options[] = array(
			'name'          => __('Init delay', 'icefit'),
			'desc'          => __('You can optionally set a delay before the animation starts, in milliseconds; default to 0', 'icefit'),
			'id'            => 'flexslider_initdelay',
			'type'          => 'text',
			'default'       => '0',
		);

		$settings_options[] = array(
			'name'          => __('Randomize slides', 'icefit'),
			'desc'          => '',
			'id'            => 'flexslider_randomize',
			'type'          => 'radio',
			'default'       => 'false',
			'values'		=> array (
								array( 'value' => 'false', 'display' => __('No', 'icefit') ),
								array( 'value' => 'true', 'display' => __('Yes', 'icefit') ),
								),
		);

	$settings_options[] = array('type' => 'end_menu');
// END SLIDER SETTINGS

// START BLOG SETTINGS
	$settings_options[] = array(
		'name'          => __('Blog settings', 'icefit'),
		'type'          => 'start_menu',
		'id'            => 'blog_settings',
		'icon'          => 'control',
	);

		$settings_options[] = array(
			'name'          => __('Blog Sidebar Side', 'icefit'),
			'desc'          => __('Select the side of the sidebar for the index and single posts of your blog.', 'icefit'),
			'id'            => 'blog_sidebar_side',
			'type'          => 'radio',
			'default'       => 'Right',
			'values'		=> array (
								array( 'value' => 'Right', 'display' => __('Right', 'icefit') ),
								array( 'value' => 'Left', 'display' => __('Left', 'icefit') ),
								),
		);

		$settings_options[] = array(
			'name'          => __('Show Page title on Blog Index Page', 'icefit'),
			'desc'          => '',
			'id'            => 'blog_index_page_title',
			'type'          => 'radio',
			'default'       => 'on',
			'values'		=> array (
								array( 'value' => 'on', 'display' => __('On', 'icefit') ),
								array( 'value' => 'off', 'display' => __('Off', 'icefit') ),
								),
		);

		$settings_options[] = array(
			'name'          => __('Blog Index Content', 'icefit'),
			'desc'          => __('Select what content should be displayed on blog index pages. The Icefit Improved Excerpt is capable of preserving some formatting tags (set below).', 'icefit'),
			'id'            => 'blog_index_content',
			'type'          => 'radio',
			'default'       => 'Full Content',
			'values'		=> array (
								array( 'value' => 'Full Content', 'display' => __('Full Content', 'icefit') ),
								array( 'value' => 'Default Excerpt', 'display' => __('Default Excerpt', 'icefit') ),
								array( 'value' => 'Icefit Improved Excerpt', 'display' => __('Icefit Improved Excerpt', 'icefit') ),
								),
		);

		$settings_options[] = array(
			'name'          => __('Excerpt Lenght', 'icefit'),
			'desc'          => __('Set how many words an excerpt must contains (default: 55)', 'icefit'),
			'id'            => 'blog_excerpt_lenght',
			'type'          => 'text',
			'default'       => '55',
		);

		$settings_options[] = array(
			'name'          => __('Icefit Improved Excerpt: Tags to preserve', 'icefit'),
			'desc'          => __('Set which tags the Improved Excerpt generator should try to preserve, in this format: &lt;br&gt;&lt;p&gt;&lt;i&gt;&lt;em&gt;&lt;b&gt;&lt;a&gt;&lt;strong&gt;', 'icefit'),
			'id'            => 'blog_improved_excerpt_preserved_tags',
			'type'          => 'text',
			'default'       => '<br><p><i><em><b><a><strong>',
		);

		$settings_options[] = array(
			'name'          => __('Activate slider on blog pages', 'icefit'),
			'desc'          => __('Enable slideshow on blog index pages.', 'icefit'),
			'id'            => 'blog_slider',
			'type'          => 'radio',
			'default'       => 'Off',
			'values'		=> array (
								array( 'value' => 'On', 'display' => __('On', 'icefit') ),
								array( 'value' => 'Off', 'display' => __('Off', 'icefit') ),
								),
		);

		$settings_options[] = array(
			'name'          => __('Activate slider on single blogpost pages', 'icefit'),
			'desc'          => '',
			'id'            => 'blog_single_slider',
			'type'          => 'radio',
			'default'       => 'Off',
			'values'		=> array (
								array( 'value' => 'On', 'display' => __('On', 'icefit') ),
								array( 'value' => 'Off', 'display' => __('Off', 'icefit') ),
								),
		);

		$settings_options[] = array(
			'name'          => __('Slides category for blog page', 'icefit'),
			'desc'          => __('Select which slides to use for the blog index slideshow.', 'icefit'),
			'id'            => 'blog_slides_cat',
			'type'          => 'radio',
			'default'       => 'All Slides',
			'values'		=> $slides_cat,
		);

		$settings_options[] = array(
			'name'          => __('Show featured image in single post view', 'icefit'),
			'desc'          => '',
			'id'            => 'show_single_post_thumbnail',
			'type'          => 'radio',
			'default'       => 'On',
			'values'		=> array (
								array( 'value' => 'On', 'display' => __('On', 'icefit') ),
								array( 'value' => 'Off', 'display' => __('Off', 'icefit') ),
								),
		);

	$settings_options[] = array('type' => 'end_menu');
// END BLOG SETTINGS

// START SIDEBARS
	$settings_options[] = array(
		'name'          => __('Sidebars', 'icefit'),
		'type'          => 'start_menu',
		'id'            => 'sidebars',
		'icon'          => 'list',
	);

		$settings_options[] = array(
			'name'          => __('Additional Sidebars', 'icefit'),
			'desc'          => __('Enter names for your additional sidebars (one per line). You will then find them in Appearence > Widgets', 'icefit'),
			'id'            => 'unlimited_sidebar',
			'type'          => 'textarea',
			'default'       => '',
		);

		$settings_options[] = array(
			'name'          => __('Sidebars Widgets Title', 'icefit'),
			'desc'          => '',
			'id'            => 'sidebar_widget_title_font',
			'type'          => 'font',
			'default'       => 'Lucida Grande',
		);

		$settings_options[] = array(
			'name'          => __('Sidebars Widgets Title Font Size', 'icefit'),
			'desc'          => __('In pixel. Default to 11.', 'icefit'),
			'id'            => 'sidebar_widget_title_font_size',
			'type'          => 'text',
			'default'       => '11',
		);

	$settings_options[] = array('type' => 'end_menu');
// END SIDEBARS

// START PORTFOLIO
	$settings_options[] = array(
		'name'          => __('Portfolio Settings', 'icefit'),
		'type'          => 'start_menu',
		'id'            => 'portfolio',
		'icon'          => 'tv',
	);

		$settings_options[] = array(
			'name'          => __('Main Portfolio Page', 'icefit'),
			'desc'          => '',
			'id'            => 'portfolio_page_content',
			'type'          => 'radio',
			'default'       => 'grid_only',
			'values'		=> array (
								array( 'value' => 'grid_only', 'display' => __('Portfolio Grid Only', 'icefit') ),
								array( 'value' => 'content_above', 'display' => __('Page Content above Grid', 'icefit') ),
								array( 'value' => 'content_below', 'display' => __('Page Content below Grid', 'icefit') ),
								),
		);


		$settings_options[] = array(
			'name'          => __('Portfolio entries Slug', 'icefit'),
			'desc'          => __('Warning: Using the same slug for portfolio entries and for your main portfolio page will cause conflicts! Note: When you change this setting, please go to Settings > Permalinks and hit "Save Changes" to update your permalink structure.', 'icefit'),
			'id'            => 'portfolio_entries_slug',
			'type'          => 'text',
			'default'       => 'portfolio',
		);

		$settings_options[] = array(
			'name'          => __('Enable Sidebar for single portfolio entries', 'icefit'),
			'desc'          => '',
			'id'            => 'single_portfolio_sidebar_side',
			'type'          => 'radio',
			'default'       => 'off',
			'values'		=> array (
								array( 'value' => 'off', 'display' => __('No sidebar', 'icefit') ),
								array( 'value' => 'left', 'display' => __('Left Sidebar', 'icefit') ),
								array( 'value' => 'right', 'display' => __('Right Sidebar', 'icefit') ),
								),
		);

		$settings_options[] = array(
			'name'          => __('Select Sidebar for single portfolio entries', 'icefit'),
			'desc'          => __('If you have just created new sidebars and cannot see them in this list, please reload this page.', 'icefit'),
			'id'            => 'single_portfolio_sidebar',
			'type'          => 'radio',
			'default'       => 'sidebar',
			'values'		=> $sidebar_options,
		);

		$settings_options[] = array(
			'name'          => __('Display Date meta in single portfolio entries', 'icefit'),
			'desc'          => '',
			'id'            => 'single_portfolio_meta_date_display',
			'type'          => 'radio',
			'default'       => 'on',
			'values'		=> array (
								array( 'value' => 'on', 'display' => __('On', 'icefit') ),
								array( 'value' => 'off', 'display' => __('Off', 'icefit') ),
								),
		);

		$settings_options[] = array(
			'name'          => __('Display Category meta in single portfolio entries', 'icefit'),
			'desc'          => '',
			'id'            => 'single_portfolio_meta_category_display',
			'type'          => 'radio',
			'default'       => 'on',
			'values'		=> array (
								array( 'value' => 'on', 'display' => __('On', 'icefit') ),
								array( 'value' => 'off', 'display' => __('Off', 'icefit') ),
								),
		);

		$settings_options[] = array(
			'name'          => __('Display Client meta in single portfolio entries', 'icefit'),
			'desc'          => '',
			'id'            => 'single_portfolio_meta_client_display',
			'type'          => 'radio',
			'default'       => 'on',
			'values'		=> array (
								array( 'value' => 'on', 'display' => __('On', 'icefit') ),
								array( 'value' => 'off', 'display' => __('Off', 'icefit') ),
								),
		);

		$settings_options[] = array(
			'name'          => __('Display "Related projects" below portfolio entries', 'icefit'),
			'desc'          => '',
			'id'            => 'single_portfolio_related_projects',
			'type'          => 'radio',
			'default'       => 'on',
			'values'		=> array (
								array( 'value' => 'on', 'display' => __('On', 'icefit') ),
								array( 'value' => 'off', 'display' => __('Off', 'icefit') ),
								),
		);

		$settings_options[] = array(
			'name'          => __('"Related projects" section title', 'icefit'),
			'desc'          => '',
			'id'            => 'single_portfolio_related_projects_title',
			'type'          => 'text',
			'default'       => 'Related Projects',
		);


	$settings_options[] = array('type' => 'end_menu');
// END PORTFOLIO

// START FOOTER SETTINGS
	$settings_options[] = array(
		'name'          => __('Footer Settings', 'icefit'),
		'type'          => 'start_menu',
		'id'            => 'footer',
		'icon'          => 'down',
	);


		$settings_options[] = array(
			'name'          => __('Additional Footer Widget groups', 'icefit'),
			'desc'          => __('Enter names for your additional widgetized footer (one per line). You will then find them in Appearence > Widgets', 'icefit'),
			'id'            => 'additional_footer_sidebars',
			'type'          => 'textarea',
			'default'       => '',
		);

		$settings_options[] = array(
			'name'          => __('Footer Widgets Columns', 'icefit'),
			'desc'          => '',
			'id'            => 'footer_widget_colums',
			'type'          => 'radio',
			'default'       => 'one-fourth',
			'values'		=> array (
								array( 'value' => 'one-third', 'display' => __('3 Columns', 'icefit') ),
								array( 'value' => 'one-fourth', 'display' => __('4 Columns', 'icefit') ),
								),
		);

		$settings_options[] = array(
			'name'          => __('Footer Widgets Title Font', 'icefit'),
			'desc'          => '',
			'id'            => 'footer_widget_title_font',
			'type'          => 'font',
			'default'       => 'Lucida Grande',
		);

		$settings_options[] = array(
			'name'          => __('Footer Widgets Title Font Size', 'icefit'),
			'desc'          => __('In pixel. Default to 12.', 'icefit'),
			'id'            => 'footer_widget_title_font_size',
			'type'          => 'text',
			'default'       => '12',
		);

		$settings_options[] = array(
			'name'          => __('Footer Widgets Title Font Color', 'icefit'),
			'desc'          => __('In hex. Default to #666666.', 'icefit'),
			'id'            => 'footer_widget_title_font_color',
			'type'          => 'color',
			'default'       => '#666666',
		);

		$settings_options[] = array(
			'name'          => __('Footer note (Copyright line)', 'icefit'),
			'desc'          => __('Customize the copyright note at the bottom of your site - or leave it as is and give credits to the author :)<br />You can use the following dynamic tokens: %date%, %sitename%', 'icefit'),
			'id'            => 'footer_note',
			'default'       => 'Copyright © %date% %sitename% | Powered by WordPress | Design by &lt;a href=&quot;http://www.iceablethemes.com&quot;&gt;Iceable Themes&lt;/a&gt;',
			'type'          => 'text',
		);

		$settings_options[] = array(
			'name'          => __('Copyright start year', 'icefit'),
			'desc'          => __('Enter the year this website was created to compute the copyright %date% token.', 'icefit'),
			'id'            => 'copyright_start_year',
			'default'       => '2013',
			'type'          => 'text',
		);

	$settings_options[] = array('type' => 'end_menu');
// END FOOTER SETTINGS

// START SOCIAL MEDIA
	$settings_options[] = array(
		'name'          => __('Social Media', 'icefit'),
		'type'          => 'start_menu',
		'id'            => 'socialmedia',
		'icon'          => 'target',
	);

		$settings_options[] = array(
			'name'          => __('Facebook URL', 'icefit'),
			'desc'          => __('Make sure to enter a full URL (starting with http or https)', 'icefit'),
			'id'            => 'facebook_url',
			'type'          => 'text',
			'default'       => '',
		);

		$settings_options[] = array(
			'name'          => __('Twitter URL', 'icefit'),
			'desc'          => __('Make sure to enter a full URL (starting with http or https)', 'icefit'),
			'id'            => 'twitter_url',
			'type'          => 'text',
			'default'       => '',
		);

		$settings_options[] = array(
			'name'          => __('Google Plus URL', 'icefit'),
			'desc'          => __('Make sure to enter a full URL (starting with http or https)', 'icefit'),
			'id'            => 'googleplus_url',
			'type'          => 'text',
			'default'       => '',
		);

		$settings_options[] = array(
			'name'          => __('Linkedin URL', 'icefit'),
			'desc'          => __('Make sure to enter a full URL (starting with http or https)', 'icefit'),
			'id'            => 'linkedin_url',
			'type'          => 'text',
			'default'       => '',
		);

		$settings_options[] = array(
			'name'          => __('Instagram URL', 'icefit'),
			'desc'          => __('Make sure to enter a full URL (starting with http or https)', 'icefit'),
			'id'            => 'instagram_url',
			'type'          => 'text',
			'default'       => '',
		);

		$settings_options[] = array(
			'name'          => __('Pinterest URL', 'icefit'),
			'desc'          => __('Make sure to enter a full URL (starting with http or https)', 'icefit'),
			'id'            => 'pinterest_url',
			'type'          => 'text',
			'default'       => '',
		);

		$settings_options[] = array(
			'name'          => __('Tumblr URL', 'icefit'),
			'desc'          => __('Make sure to enter a full URL (starting with http or https)', 'icefit'),
			'id'            => 'tumblr_url',
			'type'          => 'text',
			'default'       => '',
		);

		$settings_options[] = array(
			'name'          => __('StumbleUpon URL', 'icefit'),
			'desc'          => __('Make sure to enter a full URL (starting with http or https)', 'icefit'),
			'id'            => 'stumbleupon_url',
			'type'          => 'text',
			'default'       => '',
		);

		$settings_options[] = array(
			'name'          => __('Dribbble URL', 'icefit'),
			'desc'          => __('Make sure to enter a full URL (starting with http or https)', 'icefit'),
			'id'            => 'dribbble_url',
			'type'          => 'text',
			'default'       => '',
		);

		$settings_options[] = array(
			'name'          => __('Behance URL', 'icefit'),
			'desc'          => __('Make sure to enter a full URL (starting with http or https)', 'icefit'),
			'id'            => 'behance_url',
			'type'          => 'text',
			'default'       => '',
		);

		$settings_options[] = array(
			'name'          => __('Deviantart URL', 'icefit'),
			'desc'          => __('Make sure to enter a full URL (starting with http or https)', 'icefit'),
			'id'            => 'deviantart_url',
			'type'          => 'text',
			'default'       => '',
		);

		$settings_options[] = array(
			'name'          => __('Flickr URL', 'icefit'),
			'desc'          => __('Make sure to enter a full URL (starting with http or https)', 'icefit'),
			'id'            => 'flickr_url',
			'type'          => 'text',
			'default'       => '',
		);

		$settings_options[] = array(
			'name'          => __('Youtube URL', 'icefit'),
			'desc'          => __('Make sure to enter a full URL (starting with http or https)', 'icefit'),
			'id'            => 'youtube_url',
			'type'          => 'text',
			'default'       => '',
		);

		$settings_options[] = array(
			'name'          => __('Vimeo URL', 'icefit'),
			'desc'          => __('Make sure to enter a full URL (starting with http or https)', 'icefit'),
			'id'            => 'vimeo_url',
			'type'          => 'text',
			'default'       => '',
		);

		$settings_options[] = array(
			'name'          => __('Yelp URL', 'icefit'),
			'desc'          => __('Make sure to enter a full URL (starting with http or https)', 'icefit'),
			'id'            => 'yelp_url',
			'type'          => 'text',
			'default'       => '',
		);

		$settings_options[] = array(
			'name'          => __('RSS Feed URL', 'icefit'),
			'desc'          => __('Make sure to enter a full URL (starting with http or https). Your default RSS feed is at: ', 'icefit') . get_bloginfo('rss2_url'),
			'id'            => 'rss_url',
			'type'          => 'text',
			'default'       => '',
		);

	$settings_options[] = array('type' => 'end_menu');
// END SOCIAL MEDIA

// START 404
	$settings_options[] = array(
		'name'          => __('404 Handling', 'icefit'),
		'type'          => 'start_menu',
		'id'            => '404',
		'icon'          => 'recycle',
	);

		$settings_options[] = array(
			'name'          => __('404 Errors Handling', 'icefit'),
			'desc'          => '',
			'id'            => 'fourofour_handling',
			'type'          => 'radio',
			'default'       => 'default_template',
			'values'		=> array (
								array( 'value' => 'default_template', 'display' => __('Default template (404.php)', 'icefit') ),
								array( 'value' => 'custom_redirect', 'display' => __('Custom Redirect (set below)', 'icefit') ),
							),
		);

		$settings_options[] = array(
			'name'          => __('404 Custom Redirect', 'icefit'),
			'desc'          => __('Input any URL to redirect all caught 404 errors to. Please make sure to enter a full URL, starting with http or https.', 'icefit'),
			'id'            => 'fourofour_redirect',
			'type'          => 'text',
			'default'       => '',
		);


	$settings_options[] = array('type' => 'end_menu');
// END 404

// START MISC
	$settings_options[] = array(
		'name'          => __('Misc Settings', 'icefit'),
		'type'          => 'start_menu',
		'id'            => 'misc',
		'icon'          => 'work',
	);

		$settings_options[] = array(
			'name'          => __('Third party gallery implementation support', 'icefit'),
			'desc'          => __('Enable support for 3rd party plugins gallery display (such as Jetpack\'s tiled gallery) by disabling the theme\'s custom gallery implementation.', 'icefit'),
			'id'            => 'thirdparty_gallery_support',
			'type'          => 'radio',
			'default'       => 'off',
			'values'		=> array (
								array( 'value' => 'on', 'display' => __('On', 'icefit') ),
								array( 'value' => 'off', 'display' => __('Off', 'icefit') ),
							),
		);
		
		$settings_options[] = array(
			'name'          => __('Webfonts subset', 'icefit'),
			'desc'          => __('Loads webfonts with the chosen subset. Latin-ext improves support for special characters not included in the standard latin subset, especially used in Czech, Dutch, Polish and Turkish.'),
			'id'            => 'webfont_subset',
			'type'          => 'radio',
			'default'       => 'latin',
			'values'		=> array (
								array( 'value' => 'latin', 'display' => __('Latin', 'icefit') ),
								array( 'value' => 'latin-ext', 'display' => __('Latin Extended', 'icefit') ),
							),
		);		

	$settings_options[] = array('type' => 'end_menu');
// END MISC

// START IMPORT / EXPORT
	$settings_options[] = array(
		'name'          => __('Import/Export', 'icefit'),
		'type'          => 'start_menu',
		'id'            => 'importexport',
		'icon'          => 'network',
	);

		$settings_options[] = array(
			'name'          => __('Export Settings', 'icefit'),
			'desc'          => __('Copy this string to export all current settings (excluding uploaded images) to another site using SilverClean Pro. If you have just made changes to your settings, make sure to hit "save all changes" and reload this page.', 'icefit'),
			'id'            => 'export',
			'type'          => 'export',
		);

		$settings_options[] = array(
			'name'          => __('Import Settings', 'icefit'),
			'desc'          => __('To import your own settings from another site using Silverclean Pro, paste the export string here and click "Import". Warning: this will override all current settings (except uploaded images).', 'icefit'),
			'id'            => 'import',
			'type'          => 'import',
		);

	$settings_options[] = array('type' => 'end_menu');
// END IMPORT / EXPORT

// STRAT INDEX PAGE
	$settings_options[] = array(
		'name'          => __('Options Index', 'icefit'),
		'type'          => 'start_menu',
		'id'            => 'optionsindex',
		'icon'          => 'bulb',
	);
	
		$settings_options[] = array(
			'name'          => __('Option Index', 'icefit'),
			'desc'          => __('This index allows you to see every available settings at a glance, to help you find the setting you are looking for quickly and easily.', 'icefit'),
			'id'            => 'index',
			'type'          => 'index',
		);

	$settings_options[] = array('type' => 'end_menu');
// END INDEX

		$settings_options[] = array(
			'name'          => 'Update Available',
			'id'            => 'update',
			'type'          => 'update',
			'default'       => array('available' => false, 'version' => '', 'changelog' => '', 'lastchecked' => ''),
		);

	return $settings_options;
}
?>
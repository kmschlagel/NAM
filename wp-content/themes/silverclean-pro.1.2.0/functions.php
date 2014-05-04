<?php
/**
 *
 * Silverclean Pro WordPress Theme by Iceable Themes | http://www.iceablethemes.com
 *
 * Copyright 2013 - 2014 Mathieu Sarrasin - Iceable Media
 *
 * Theme's Function
 *
 */
 
/*
 * Framework Elements
 */
include_once('functions/icefit-options/settings.php'); // Admin Settings Panel
$icefit_options = icefit_get_all_options(); // Load settings once in a global variable (array)
include_once('functions/shortcodes.php'); // Shortcodes
include_once('functions/icefit-page-settings/icefit-page-settings.php'); // Page Settings Metabox
include_once('functions/icefit-cta/icefit-cta.php'); // Call to Action shortcode
include_once('functions/icefit-contactinfo/icefit-contactinfo.php'); // Contact Info Widget
include_once('functions/icefit-contactform/icefit-contactform.php'); // AJAX Contact Form
include_once('functions/icefit-blogposts/icefit-blogposts.php'); // Blogposts shortcode and widget
include_once('functions/icefit-slides/icefit-slides.php'); // Slides custom post type
include_once('functions/icefit-portfolio/icefit-portfolio.php'); // Portfolio custom post type
include_once('functions/icefit-testimonials/icefit-testimonials.php'); // Testimonials custom post type
include_once('functions/icefit-features/icefit-features.php'); // Features custom post type
include_once('functions/icefit-partners/icefit-partners.php'); // Partners/Clients custom post type
include_once('functions/icefit-maps/icefit-maps.php'); // Google Maps API integration
include_once('functions/icefit-socialmedia/icefit-socialmedia.php'); // Social Media Widget

// Since 1.2: TinyMCE was updated to 4.0 in WP 3.9, the old implementation of visual shortcode doesn't work anymore
global $wp_version;
if ( $wp_version >= 3.9 ): // If WP 3.9 or later: use updated visual shortcodes (icefit_mce4)
	include_once('functions/icefit-mce4/editor-buttons.php'); // Editor Buttons
else: // If WP 3.8.X or lesser: use previous visual shortcodes (icefit_mce3)
	include_once('functions/icefit-mce3/editor-buttons.php'); // Editor Buttons
endif;

/*
 * Content Width
 */
if ( ! isset( $content_width ) ) $content_width = 450;

function icefit_content_width() {
	global $content_width;
	if ( is_singular() && !is_page() )
		$content_width = 450; // Content Width for single posts
	if ( is_page() ):
		$sidebar_side = get_post_meta(get_the_ID(), 'icefit_pagesettings_sidebar_side', true);
		if ($sidebar_side == 'left' || $sidebar_side == 'right')
			$content_width = 698; // Content Width for page with sidebar
		else
			$content_width = 920; // Content Width for full-width page
	endif;
}
add_action( 'template_redirect', 'icefit_content_width' );

/*
 * Page Title
 */
function icefit_wp_title( $title, $sep ) {
	global $paged, $page;
	if ( is_feed() )
		return $title;
	// Add the site name.
	$title .= get_bloginfo( 'name' );
	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title = "$title $sep $site_description";
	// Add a page number if necessary.
	if ( $paged >= 2 || $page >= 2 )
		$title = "$title $sep " . sprintf( __( 'Page %s', 'icefit' ), max( $paged, $page ) );
	return $title;
}
add_filter( 'wp_title', 'icefit_wp_title', 10, 2 );


/*
 * Theme activation
 */
function icefit_theme_activation() {
	/* Legacy Support: Copy theme_mods from previous version on activation
	 * to keep menus and widgets set while using the previous version */
	 if ( false !== get_option('theme_mods_silverclean-pro') )
	 	update_option( 'theme_mods_' . get_stylesheet() , get_option('theme_mods_silverclean-pro') );
	
	// Reset update check
	global $icefit_settings_slug;
	$icefit_settings = get_option($icefit_settings_slug);
	$icefit_settings['update'] = array('available' => false, 'version' => '', 'changelog' => '', 'lastchecked' => '');
	update_option($icefit_settings_slug,$icefit_settings);
	
	// Flush permalink structure
	flush_rewrite_rules();

	// Redirect users to theme options page
	wp_redirect(admin_url("themes.php?page=icefit-settings"));
}
add_action('after_switch_theme', 'icefit_theme_activation', 999);


/*
 * Setup and registration functions
 */
function icefit_setup(){
	/* Translation support
	 * Translations can be added to the /languages directory.
	 * A .pot template file is included to get you started
	 */
	load_theme_textdomain('icefit', get_template_directory() . '/languages');

	/* Feed links support */
	add_theme_support( 'automatic-feed-links' );

	/* Register menus */
	register_nav_menu( 'primary', 'Navigation menu' );

	/* Post Thumbnails Support */
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 220, 150, true );
	add_image_size( 'portfolio-thumb', 280, 168, true );
}
add_action('after_setup_theme', 'icefit_setup');

/*
 * Enqueue styles
 */
function icefit_styles() {
	/*
	 * Register styles
	 */

	/* Child theme support:
	 * For each of the 4 stylesheets in the /css folder:
	 * register the copy in the child-theme's /css folder if it exists
	 * otherwise, register the stylesheet from the parent theme.
	 * As of 1.3.0: All CSS files have been merged in silverclean.dev.css and compressed in silverclean.min.css
	 */

	global $icefit_options;

	if ($icefit_options['responsive_mode'] == 'off' ):
		$main_styleesheet = "silverclean-unresponsive";
	else:
		$main_styleesheet = "silverclean";
	endif;

	$template_directory_uri = get_template_directory_uri(); // Parent theme URI
	$stylesheet_directory_uri = get_stylesheet_directory_uri(); // Current theme URI
	$stylesheet_directory = get_stylesheet_directory(); // Current theme directory

	$child_theme_used = ( $template_directory_uri != $stylesheet_directory_uri ) ? true : false;

	if ( $child_theme_used && @file_exists( $stylesheet_directory . '/css/'.$main_styleesheet.'.min.css' ) ):
		wp_register_style( 'silverclean', $stylesheet_directory_uri . '/css/'.$main_styleesheet.'.min.css' );
	elseif ( $child_theme_used && @file_exists( $stylesheet_directory . '/css/'.$main_styleesheet.'.dev.css' ) ):
		wp_register_style( 'silverclean', $stylesheet_directory_uri . '/css/'.$main_styleesheet.'.dev.css' );
	else:
		wp_register_style( 'silverclean', $template_directory_uri . '/css/'.$main_styleesheet.'.min.css' );
	/* Developpers: Comment the above and uncomment the line below to use silverclean.dev.css instead */
	//	wp_register_style( 'silverclean', $template_directory_uri . '/css/'.$main_styleesheet.'.dev.css' );
	endif;
	
	wp_enqueue_style( 'silverclean' );
	/* Legacy support for child-themes:
	 * Enqueue previous versions' .css files from
	 * child-theme's /css folder if they exists.
	 */

	if ($child_theme_used): // If a child-theme is being used
		$stylesheets_to_register = array(
			array ('handle' => 'icefit',		'path' => '/css/icefit.css'),
			array ('handle' => 'theme-style',	'path' => '/css/theme-style.css'),
			array ('handle' => 'media-queries',	'path' => '/css/media-queries.css'),
			array ('handle' => 'prettyPhoto',	'path' => '/css/prettyPhoto.css'),
			);
		foreach ( $stylesheets_to_register as $stylesheet):
			if ( @file_exists( $stylesheet_directory . $stylesheet['path'] ) ):
				wp_register_style( $stylesheet['handle'],
					$stylesheet_directory_uri . $stylesheet['path'] );
			endif;
		endforeach;
		// Add style.css for child-themes		
		wp_register_style( 'style', $stylesheet_directory_uri . '/style.css'); 
		wp_enqueue_style( 'style' );
	endif;

	/* Dynamic styles to reflect user settings in "theme options"
	 * generated at the end of this file */
	global $wp_query;
	if ( !$wp_query->is_404 && !is_search() ) $id = get_the_id(); else $id = 0;
	$dynamic_css = icefit_generate_dynamiccss($id);
	wp_add_inline_style('silverclean', $dynamic_css);

	/*
	 * List all fonts that will need to be used in an array
	 */

	$fonts_to_use = array();
	$fonts_to_use[] = $icefit_options['headings_font'];
	$fonts_to_use[] = $icefit_options['menu_font'];
	$fonts_to_use[] = $icefit_options['content_font'];
	$fonts_to_use[] = $icefit_options['header_title_font_family'];
	$fonts_to_use[] = $icefit_options['header_tagline_font_family'];
	$fonts_to_use[] = $icefit_options['sidebar_widget_title_font'];
	$fonts_to_use[] = $icefit_options['footer_widget_title_font'];
	
	// Remove duplicates
	$fonts_to_use = array_unique( $fonts_to_use );
	
	// List fonts that do not need to be loaded ("websafe" fonts)
	$websafe_fonts = array("Georgia", "Times New Roman", "Andale Mono", "Arial", "Arial Black", "Impact", "Trebuchet MS", "Verdana", "Webdings", "Comic Sans MS", "Courier New", "Century Gothic", "Lucida", "Lucida Grande", "Palatino", "Tahoma");

	// SSL (https) support
	$protocol = is_ssl() ? 'https' : 'http';

	// Latin-ext subset
	$webfont_subset = "";
	if ($icefit_options['webfont_subset'] == 'latin-ext') $webfont_subset = "&subset=latin,latin-ext";

	// Process each fonts_to_use, and load them if necessary (if they are not "websafe")
	foreach($fonts_to_use as $font_to_load):
		if ("" != $font_to_load && !in_array($font_to_load, $websafe_fonts))
			wp_enqueue_style( str_replace(" ", "-", $font_to_load), "$protocol://fonts.googleapis.com/css?family=" . str_replace(" ", "+", $font_to_load) . ':400italic,700italic,400,700'.$webfont_subset, array(), null );
	endforeach;
}
add_action('wp_enqueue_scripts', 'icefit_styles');

/*
 * Register editor style
 */
function icefit_editor_styles() {
	add_editor_style();
}
add_action( 'init', 'icefit_editor_styles' );

/*
 * Enqueue javascripts
 */
function icefit_scripts() {
	/*
	 * As of 1.1.0: All JS files have been merged in silverclean.dev.js and compressed in silverclean.min.js
	 */

	wp_enqueue_script('silverclean', get_template_directory_uri() . '/js/silverclean.min.js', array('jquery'));
	/* Developpers: Comment the above and uncomment the line below to use silverclean.dev.js instead */
	// wp_enqueue_script('silverclean', get_template_directory_uri() . '/js/silverclean.dev.js', array('jquery'));

    /* Threaded comments support */
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

}
add_action('wp_enqueue_scripts', 'icefit_scripts');

/*
 * WP unwanted behaviors fix
 */

// Remove rel tags in category links (HTML5 invalid)
function remove_rel_cat( $text ) {
	$text = str_replace(' rel="category"', "", $text);
	$text = str_replace(' rel="category tag"', "", $text);
	return $text;
}
add_filter( 'the_category', 'remove_rel_cat' ); 

// Fix for a known issue with enclosing shortcodes and wpauto
// Credits : Johann Heyne
function shortcode_empty_paragraph_fix($content) {
	$array = array (
		'<p>['    => '[', 
		']</p>'   => ']', 
		']<br />' => ']',
	);
	$content = strtr($content, $array);
	return $content;
}
add_filter('the_content', 'shortcode_empty_paragraph_fix');

// Improved version of clean_pre
// Based on a work by Emrah Gunduz
function protect_pre($pee) {
	$pee = preg_replace_callback('!(<pre[^>]*>)(.*?)</pre>!is', 'eg_clean_pre', $pee );
	return $pee;
}

function eg_clean_pre($matches) {
	if ( is_array($matches) )
		$text = $matches[1] . $matches[2] . "</pre>";
	else
		$text = $matches;
	$text = str_replace('<br />', '', $text);
	return $text;
}
add_filter( 'the_content', 'protect_pre' );

/*
 * Finds whether post page needs comments pagination links
 */
function page_has_comments_nav() {
	global $wp_query;
	return ($wp_query->max_num_comment_pages > 1);
}

function page_has_next_comments_link() {
	global $wp_query;
	$max_cpage = $wp_query->max_num_comment_pages;
	$cpage = get_query_var( 'cpage' );	
	return ( $max_cpage > $cpage );
}

function page_has_previous_comments_link() {
	$cpage = get_query_var( 'cpage' );	
	return ($cpage > 1);
}

/*
 * Rewrite and replace wp_trim_excerpt() so it adds a relevant read more link
 * when the <!--more--> or <!--nextpage--> quicktags are used
 * This new function preserves every features and filters from the original wp_trim_excerpt
 */
function icefit_trim_excerpt($text = '') {
	global $post;
	$raw_excerpt = $text;
	if ( '' == $text ) {
		$text = get_the_content('');
		$text = strip_shortcodes( $text );
		$text = apply_filters('the_content', $text);
		$text = str_replace(']]>', ']]&gt;', $text);
		$excerpt_length = apply_filters('excerpt_length', 55);
		$excerpt_more = apply_filters('excerpt_more', ' ' . '[...]');
		$text = wp_trim_words( $text, $excerpt_length, $excerpt_more );

		/* If the post_content contains a <!--more--> OR a <!--nextpage--> quicktag
		 * AND the more link has not been added already
		 * then we add it now
		 */
		if ( ( preg_match('/<!--more(.*?)?-->/', $post->post_content ) || preg_match('/<!--nextpage-->/', $post->post_content ) ) && strpos($text,$excerpt_more) === false ) {
		 $text .= $excerpt_more;
		}
		
	}
	return apply_filters('icefit_trim_excerpt', $text, $raw_excerpt);
}

/*
 * Set the excerpt length according to user setting
 */
function icefit_excerpt_length( $length ) {
	global $icefit_options;
	$blog_excerpt_lenght = $icefit_options['blog_excerpt_lenght'];
	if ( !$blog_excerpt_lenght || !is_numeric($blog_excerpt_lenght) ) $blog_excerpt_lenght = 55;
	return $blog_excerpt_lenght;
}
add_filter( 'excerpt_length', 'icefit_excerpt_length', 999 );

/*
 * Customize "read more" links on index view
 */
function icefit_excerpt_more( $more ) {
	global $post;
	return '<div class="read-more"><a href="'. get_permalink( get_the_ID() ) . '">'. __("Read More", 'icefit') .'</a></div>';
}
add_filter( 'excerpt_more', 'icefit_excerpt_more' );

/*
 * Improved excerpt
 */
function icefit_improved_trim_excerpt($text) {
	global $post, $icefit_options;
	if ( '' == $text ) {
		$text = get_the_content('');
		$text = preg_replace( '|\[(.+?)\](.+?\[/\\1\])?|s', '', $text); // Remove shortcodes
    	$text = apply_filters('the_content', $text);
		$text = preg_replace('@<script[^>]*?>.*?</script>@si', '', $text);
		$text = preg_replace('@<style[^>]*?>.*?</style>@si', '', $text);
		$text = preg_replace('@<p class="wp-caption-text"[^>]*?>.*?</p>@si', '', $text);
    	$text = str_replace('\]\]\>', ']]&gt;', $text);
		$excerpt_length = apply_filters('excerpt_length', 55);
    	$words = explode(' ', $text, $excerpt_length + 1);
    	if (count($words)> $excerpt_length) {
			array_pop($words);
			$text = implode(' ', $words)."...";
		}

		$preserve_tags = html_entity_decode( $icefit_options['blog_improved_excerpt_preserved_tags'] );
		if ( !$preserve_tags ) $preserve_tags = '<br><p><i><em><b><a><strong>';
		
    	$text = strip_tags($text, $preserve_tags);

    	if ( extension_loaded('tidy') ) {
	    	$tidy = new tidy();
	    	$tidy->parseString($text,array('show-body-only'=>true,'wrap'=>'0'),'utf8');
	    	$tidy->cleanRepair();
	    	$text = $tidy;
    	}
    	
    	$excerpt_more = apply_filters('excerpt_more', ' ' . '[...]');

		$text .= $excerpt_more;
	}
	return $text;
}
remove_filter('get_the_excerpt', 'wp_trim_excerpt');
if ( $icefit_options['blog_index_content'] == "Icefit Improved Excerpt" ) add_filter('get_the_excerpt', 'icefit_improved_trim_excerpt');
else add_filter( 'get_the_excerpt', 'icefit_trim_excerpt' );

/*
 * Rewrite Gallery Shortcode
 */
function icefit_gallery_shortcode($attr) {
	$post = get_post();

	static $instance = 0;
	$instance++;

	if ( ! empty( $attr['ids'] ) ) {
		// 'ids' is explicitly ordered, unless you specify otherwise.
		if ( empty( $attr['orderby'] ) )
			$attr['orderby'] = 'post__in';
		$attr['include'] = $attr['ids'];
	}

	// Allow plugins/themes to override the default gallery template.
	$output = apply_filters('post_gallery', '', $attr);
	if ( $output != '' )
		return $output;

	// We're trusting author input, so let's at least make sure it looks like a valid orderby statement
	if ( isset( $attr['orderby'] ) ) {
		$attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
		if ( !$attr['orderby'] )
			unset( $attr['orderby'] );
	}

	extract(shortcode_atts(array(
		'order'      => 'ASC',
		'orderby'    => 'menu_order ID',
		'id'         => $post->ID,
		'itemtag'    => 'dl',
		'icontag'    => 'dt',
		'captiontag' => 'dd',
		'columns'    => 3,
		'size'       => 'thumbnail',
		'include'    => '',
		'exclude'    => ''
	), $attr));

	$id = intval($id);
	if ( 'RAND' == $order )
		$orderby = 'none';

	if ( !empty($include) ) {
		$_attachments = get_posts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );

		$attachments = array();
		foreach ( $_attachments as $key => $val ) {
			$attachments[$val->ID] = $_attachments[$key];
		}
	} elseif ( !empty($exclude) ) {
		$attachments = get_children( array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	} else {
		$attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	}

	if ( empty($attachments) )
		return '';

	if ( is_feed() ) {
		$output = "\n";
		foreach ( $attachments as $att_id => $attachment )
			$output .= wp_get_attachment_link($att_id, $size, true) . "\n";
		return $output;
	}

	$itemtag = tag_escape($itemtag);
	$captiontag = tag_escape($captiontag);
	$icontag = tag_escape($icontag);
	$valid_tags = wp_kses_allowed_html( 'post' );
	if ( ! isset( $valid_tags[ $itemtag ] ) )
		$itemtag = 'dl';
	if ( ! isset( $valid_tags[ $captiontag ] ) )
		$captiontag = 'dd';
	if ( ! isset( $valid_tags[ $icontag ] ) )
		$icontag = 'dt';

	$columns = intval($columns);
	$itemwidth = $columns > 0 ? floor(100/$columns) : 100;
	$float = is_rtl() ? 'right' : 'left';

	$selector = "gallery-{$instance}";

	$gallery_style = $gallery_div = '';
	if ( apply_filters( 'use_default_gallery_style', true ) )
		$gallery_style = "<style type='text/css'>#{$selector} {margin:auto;}#{$selector} .gallery-item {float:{$float};margin-top:10px;text-align:center;width:{$itemwidth}%;}#{$selector} .gallery-caption{margin-left:0;}</style><!-- see icefit_gallery_shortcode() in theme's /functions.php -->";
	$size_class = sanitize_html_class( $size );
	$gallery_div = "<div id='$selector' class='gallery galleryid-{$id} gallery-columns-{$columns} gallery-size-{$size_class}'>";
	$output = apply_filters( 'gallery_style', $gallery_style . "\n\t\t" . $gallery_div );

	$i = 0;
	foreach ( $attachments as $id => $attachment ) {
		// Force linking to image file 
		$link = wp_get_attachment_link($id, $size, false, false);

		$output .= "<{$itemtag} class='gallery-item'>";
		$output .= "<{$icontag} class='gallery-icon'>$link</{$icontag}>";
		if ( $captiontag && trim($attachment->post_excerpt) ) {
			$output .= "<{$captiontag} class='wp-caption-text gallery-caption'>"
						. wptexturize($attachment->post_excerpt)
						. "</{$captiontag}>";
		}
		$output .= "</{$itemtag}>";
		if ( $columns > 0 && ++$i % $columns == 0 )
			$output .= '<br style="clear: both" />';
	}

	$output .= '<br style="clear:both;" /></div>';

	return $output;
}
if ($icefit_options['thirdparty_gallery_support'] != 'on'):
	remove_shortcode('gallery', 'gallery_shortcode');
	add_shortcode('gallery', 'icefit_gallery_shortcode');
endif;


/*
 * Add prettyPhoto tag to the gallery
 */
function icefit_sant_prettyadd ($content) {
	$content = preg_replace("/<a/","<a rel=\"prettyPhoto[gal]\"",$content,1);
	return $content;
}
add_filter( 'wp_get_attachment_link', 'icefit_sant_prettyadd');

/*
 * Add parent Class to parent menu items
 */
function icefit_add_menu_parent_class( $items ) {
	$parents = array();
	foreach ( $items as $item ) {
		if ( $item->menu_item_parent && $item->menu_item_parent > 0 ) {
			$parents[] = $item->menu_item_parent;
		}
	}
	foreach ( $items as $item ) {
		if ( in_array( $item->ID, $parents ) ) {
			$item->classes[] = 'menu-parent-item'; 
		}
	}
	return $items;    
}
add_filter( 'wp_nav_menu_objects', 'icefit_add_menu_parent_class' );

/*
 * Create dropdown menu (used in responsive mode)
 */
function icefit_dropdown_nav_menu() {
	$menu_name = 'primary';
	if ( ( $locations = get_nav_menu_locations() ) && isset( $locations[ $menu_name ] ) ) {
		if ($menu = wp_get_nav_menu_object( $locations[ $menu_name ] ) ) {
		$menu_items = wp_get_nav_menu_items($menu->term_id);
		$menu_list = '<select id="dropdown-menu">';
		$menu_list .= '<option value="">Menu</option>';
		foreach ( (array) $menu_items as $key => $menu_item ) {
			$title = $menu_item->title;
			$url = $menu_item->url;
			if($url != "#" ) $menu_list .= '<option value="' . $url . '">' . $title . '</option>';
		}
		$menu_list .= '</select>';
   		// $menu_list now ready to output
   		echo $menu_list;    
		}
    } 
}

/*
 * Sidebar and Widgetized areas
 */
function icefit_widgets_init() {

	global $icefit_options;

	$footer_widget_colums = $icefit_options['footer_widget_colums'];
	if (!$footer_widget_colums) $footer_widget_colums = 'one-fourth';

	register_sidebar( array(
		'name'          => __( 'Default Sidebar', 'icefit' ),
		'id'            => 'sidebar',
		'description'   => '',
	    'class'         => '',
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget'  => '</li>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
		)
	);

	register_sidebar( array(
		'name'          => __( 'Footer', 'icefit' ),
		'id'            => 'footer-sidebar',
		'description'   => '',
	    'class'         => '',
		'before_widget' => '<li id="%1$s" class="'.$footer_widget_colums.' widget %2$s">',
		'after_widget'  => '</li>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
		)
	);

	// Register additional custom sidebars

	$icefit_unlimited_sidebars = $icefit_options['unlimited_sidebar'];
	$icefit_sidebars_list = explode("\n", $icefit_unlimited_sidebars);
	foreach ($icefit_sidebars_list as $additional_sidebar):
		if($additional_sidebar != ""):
		register_sidebar( array(
		'name'          => $additional_sidebar,
		'id'            => sanitize_title($additional_sidebar),
		'description'   => '',
	    'class'         => '',
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget'  => '</li>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>' )
		);
		endif;
	endforeach;

	$additional_footer_sidebars = $icefit_options['additional_footer_sidebars'];
	$icefit_footers_list = explode("\n", $additional_footer_sidebars);
	foreach ($icefit_footers_list as $additional_footer):
		if($additional_footer != ""):
		register_sidebar( array(
		'name'          => $additional_footer,
		'id'            => sanitize_title($additional_footer),
		'description'   => '',
	    'class'         => '',
		'before_widget' => '<li id="%1$s" class="'.$footer_widget_colums.' widget %2$s">',
		'after_widget'  => '</li>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>' )
		);
		endif;
	endforeach;

}
add_action( 'widgets_init', 'icefit_widgets_init' );

/*
 * Custom 404 error handling
 */
function icefit_redirect_404s() {
	global $wp_query;
	if ($wp_query->is_404):
		global $icefit_options;
		if ( "custom_redirect" == $icefit_options['fourofour_handling']):
			wp_redirect($icefit_options['fourofour_redirect'],301);
			exit;
		endif;
	endif;
}
add_action('wp', 'icefit_redirect_404s', 1);


/*
 * Update Check
 */
function icefit_check_for_update() {
	// Only check for update if the last check is more than 12 hours old (43200 seconds)
	global $icefit_options;

	$update = $icefit_options['update'];
	if ( !is_array($update) ) $update = array();
	
	if ( array_key_exists('lastchecked', $update) ):
		$lastchecked = $update['lastchecked'];
	else:
		$lastchecked = 0;
	endif;
	
	$now = time();
	$elapsed = $now - $lastchecked;
	if ( $elapsed > 43200 ):
		$theme = wp_get_theme();
		$version = $theme->Version;
		$announce_url = "http://www.iceablethemes.com/theme-updates.php?theme=silverclean-pro";
		$fetched = icefit_url_get_contents($announce_url);
		if($fetched):
			$update_check = @unserialize($fetched);
			if ( false !== $update_check ):			
				$latest_version = $update_check['latest-version'];
				$changelog = $update_check['changelog'];
				$update_available = version_compare($version, $latest_version, '<');
				global $icefit_settings_slug;
				$icefit_settings = get_option($icefit_settings_slug);
				if ($update_available):
					// Update update-available in settings array
					$icefit_settings['update']['available'] = true;
					$icefit_settings['update']['version'] = $latest_version;
					$icefit_settings['update']['changelog'] = $changelog;
				endif;
				$icefit_settings['update']['lastchecked'] = time();
				update_option($icefit_settings_slug,$icefit_settings);
			endif;
		endif;
	endif;
}
add_action('admin_init', 'icefit_check_for_update');

function icefit_url_get_contents($url) {
    if (function_exists('curl_init')):
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    $output = curl_exec($ch);
	    curl_close($ch);
	    return $output;
    else:
    	return false;
    endif;
}

/*
 * Admin notices
 */
function icefit_admin_notices(){

	global $icefit_options;

	// Update notice
	$update = $icefit_options['update'];
	if ( !is_array($update) ) $update = array();

	if ( array_key_exists('available', $update) ):
		if ( current_user_can( 'edit_theme_options' ) && $update['available'] ):
			$theme = wp_get_theme();
			$version = $theme->Version;
			echo '<div class="updated">
	       <p>Thank you for using SilverClean Pro '.$version.'! A new update (version '.$update['version'].') is available to download from your member area on <a href="http://www.iceablethemes.com/my-account/" target="_blank">Iceable Themes</a>. <a href="'.$update['changelog'].'" target="_blank">View changelog</a></p></div>';
	    endif;
    endif;
    
    // No menu set notice
    if  ( !has_nav_menu( 'primary' ) ) {
     echo '<div class="updated">
       <p>Notice: you have not set your primary menu yet, and your site is currently using a fallback menu which is missing some functionalities. Please take a minute to <a href="'.admin_url('nav-menus.php').'">set your menu now</a>!</p>
    </div>';
     }

}
add_action('admin_notices', 'icefit_admin_notices');

/*
 * Custom Header Code
 */
function icefit_custom_header_code() {
	global $icefit_options;
	echo $icefit_options['custom_header_code'];
}
add_action('wp_head', 'icefit_custom_header_code');

/*
 * Custom Footer Code
 */
function icefit_custom_footer_code() {
	global $wp_query, $icefit_options;
	/* Adds dynamic JavaScript + User defined footer code to the page's footer */
	if ( !$wp_query->is_404 && !is_search() ) $id = get_the_id(); else $id = 0;
	echo '<script type="text/javascript"><!--//--><![CDATA[//><!--', "\n",
		icefit_generate_dynamicjs($id), "\n", '//--><!]]></script>',
		$icefit_options['tracking_code'];
}
add_action('wp_footer', 'icefit_custom_footer_code');

/*
 * Dynamic JS and CSS
 */

// Generate dynamic JS
function icefit_generate_dynamicjs($id) {

	global $icefit_options;

	/*
	 * Slider Settings
	 */
	$flexslider_animation_values = array ('slide', 'fade');
	$flexslider_animation = $icefit_options['flexslider_animation'];
	if ( !in_array($flexslider_animation, $flexslider_animation_values) )
		$flexslider_animation = "slide";
				
	$flexslider_delay = $icefit_options['flexslider_delay'];
	if ( !is_numeric($flexslider_delay) )
		$flexslider_delay = "4000";

	$flexslider_animationspeed = $icefit_options['flexslider_animationspeed'];
	if ( !is_numeric($flexslider_animationspeed) )
		$flexslider_animationspeed = "600";

	$flexslider_direction_values = array ('horizontal', 'vertical');
	$flexslider_direction = $icefit_options['flexslider_direction'];
	if ( !in_array($flexslider_direction, $flexslider_direction_values) )
		$flexslider_direction = "horizontal";

	$flexslider_reverse = $icefit_options['flexslider_reverse'];
	if ( $flexslider_reverse != "true") $flexslider_reverse = "false";

	$flexslider_slideshow = $icefit_options['flexslider_slideshow'];
	if ($flexslider_slideshow != "false") $flexslider_slideshow = "true";

	$flexslider_initdelay = $icefit_options['flexslider_initdelay'];
	if ( !is_numeric($flexslider_initdelay) )
		$flexslider_initdelay = "0";

	$flexslider_randomize = $icefit_options['flexslider_randomize'];
	if ( $flexslider_randomize != "true") $flexslider_randomize = "false";

	$custom_js = 'jQuery(window).load(function() {
		jQuery(\'.flexslider\').flexslider({
		controlsContainer: ".flexslider-container",
		animation: "'.$flexslider_animation.'",
		easing: "swing",
		direction: "'.$flexslider_direction.'",
		reverse: '.$flexslider_reverse.',
		smoothHeight: true,
		slideshow: '.$flexslider_slideshow.',
		slideshowSpeed: '.$flexslider_delay.',
		animationSpeed: '.$flexslider_animationspeed.',
		initDelay: '.$flexslider_initdelay.',
		randomize: '.$flexslider_randomize.',
		directionNav: false,
		prevText: "",
		nextText: "",
		});
	});';

	/*
	 * Sticky Header
	 */
	$sticky_header = $icefit_options['sticky_header'];
	
	if ( get_post_type($id) == "page" ):
		$sticky_header_page_setting = get_post_meta($id, 'icefit_pagesettings_sticky_header', true);
		if ( $sticky_header_page_setting != "" && $sticky_header_page_setting != "Use Global Setting")
			$sticky_header = $sticky_header_page_setting;
	endif;	
	
	$custom_js .= 'jQuery(document).ready(function($){';
	switch ($sticky_header):
		case 'Sticky Header on scroll':
			$custom_js .= '$(function(){
				var navbarTop=$("#navbar").offset().top;
				var headerWrapHeight=$("#header-wrap").outerHeight();
				$(window).scroll(function(){
					if(/Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent)){return;}
					if($(window).scrollTop()>navbarTop){
						$("#header").appendTo("#sticky-header");
						$("#navbar").appendTo("#sticky-header");
						$("#sticky-header").slideDown();
						var headerHeight=$("#header").outerHeight();
						var navbarHeight=$("#navbar").outerHeight();
						var headerWrapPush=parseInt(headerHeight+navbarHeight+headerWrapHeight);
						$("#header-wrap").css("padding-top",headerWrapPush+"px");
						$("#header-wrap").attr("data-push",headerHeight+"+"+navbarHeight+"+"+headerWrapHeight);
					}else{
						$("#header-wrap").css("padding-top","0");
						$("#sticky-header").slideUp();
						$("#header").appendTo("#header-wrap");
						$("#navbar").appendTo("#header-wrap");
					}
				});
			});';
		    break;
		case 'Sticky Navbar on scroll':
			$custom_js .= '$(function(){
				var navbarTop=$("#navbar").offset().top;
				var headerHeight=$("#header-wrap").outerHeight();
				$(window).scroll(function(){
					if(/Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent)){return;}
					if($(window).scrollTop()>navbarTop){
						$("#header").hide();
						$("#navbar").appendTo("#sticky-header");
						$("#sticky-header").slideDown();
						var navbarHeight=$("#navbar").outerHeight();
						$("#header-wrap").css("padding-top",parseInt(headerHeight+navbarHeight)+"px");
					}else{
						$("#header-wrap").css("padding-top", "0");
						$("#sticky-header").slideUp();
						$("#header").show();
						$("#navbar").appendTo("#header-wrap");
					}
				});
			});';
			break;
		case 'Sticky Navbar on scroll + Logo':
			$custom_js .= '$(function(){
				var navbarTop=$("#navbar").offset().top;
				var headerHeight=$("#header-wrap").outerHeight();
				$(window).scroll(function(){
					if(/Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent)){return;}
					if($(window).scrollTop()>navbarTop){
						$("#header").hide();
						$("#navbar").appendTo("#sticky-header");
						$("#logo").prependTo("#sticky-header .menu-container");
						$("#sticky-header").slideDown();
						var navbarHeight=$("#navbar").outerHeight();
						$("#header-wrap").css("padding-top",parseInt(headerHeight+navbarHeight)+"px");
					}else{
						$("#header-wrap").css("padding-top", "0");
						$("#sticky-header").slideUp();
						$("#header").show();
						$("#navbar").appendTo("#header-wrap");
						jQuery("#logo").prependTo("#header-wrap #header .container:first-child");
					}
				});
			});';
			break;
		case 'Sticky Header Only':
			$custom_js .= '$(function(){
				var stickyHeight=$("#sticky-header").height();
				var marginTop=parseInt(stickyHeight);
				$("#main-wrap").css("margin-top",marginTop+"px");
			});';
			break;
		case 'Sticky Navbar Only':
			$custom_js .= '$(function(){
				var stickyHeight=$("#sticky-header").height();
				$("#main-wrap").css("margin-top",stickyHeight);
			});';
			break;
		case 'Sticky Navbar Only + Logo':
			$custom_js .= '$(function(){
				var stickyHeight=$("#sticky-header").height();
				$("#main-content").css("padding-top",stickyHeight+20);
			});';
			break;
	endswitch;
    $custom_js .= '});';

    // Remove tabs, newlines, etc.
    $custom_js = str_replace(array("\r\n", "\r", "\n", "\t"), '', $custom_js);
    $custom_js = str_replace(array("    ", "   ", "  "), " ", $custom_js);

	return $custom_js;
}

// Generate dynamic CSS
function icefit_generate_dynamiccss($id) {

	// Load all options in an array
	global $icefit_options;

	// Copy settings to a local variable to avoid altering the global one
	$current_settings = $icefit_options;

	$websafe_fonts = array( "Andale Mono", "Arial", "Arial Black", "Century Gothic", "Comic Sans MS", "Courier New", "Georgia", "Impact", "Lucida Grande", "Palatino", "Tahoma", "Times New Roman", "Trebuchet MS", "Verdana", "Webdings" );
	$websafe_full = array("'Andale Mono', 'Lucida Console', Monaco, monospace", "Arial, Helvetica, sans-serif", "'Arial Black', Gadget, sans-serif", "'Century Gothic', CenturyGothic, Geneva, AppleGothic, sans-serif", "'Comic Sans MS', cursive, sans-serif", "'Courier New', Courier, monospace", "Georgia, serif", "Impact, Charcoal, sans-serif", "'Lucida Grande', 'Lucida Sans Unicode', 'Lucida Sans', 'Trebuchet MS', sans-serif", "'Palatino Linotype', 'Book Antiqua', Palatino, serif", "Tahoma, Geneva, sans-serif", "'Times New Roman', Times, serif", "'Trebuchet MS', 'Lucida Grande', 'Lucida Sans Unicode', 'Lucida Sans', Helvetica, sans-serif", "Verdana, Geneva, sans-serif", "Webdings" );

	$template = icefit_settings_template();
	$export_array = array();
	foreach($template as $template_item):
		if ($template_item['type'] == 'font' ):
			$id = $template_item['id'];
			// Replace websafe fonts with full font family
			if ( in_array($current_settings[$id],$websafe_fonts) ):
				$current_settings[$id] = str_replace($websafe_fonts, $websafe_full, $current_settings[$id]);
			else: // Wrap webfonts in quotes
				$current_settings[$id] = "'" . $current_settings[$id] . "'";
			endif;
		endif;
	endforeach;

	// Init $custom_css variable
	$custom_css = "";

	// Boxed layout
	if ('Boxed' == $current_settings['layout'] ):

		if ($current_settings['background_image_size'] == 'cover'):
			$background_size = 'cover';
			$background_repeat = 'no-repeat';
			$background_image_position = 'center center';
			$background_image_attachment = 'fixed';
		else:
			$background_size = 'auto';
	
			switch ( $current_settings['background_image_repeat'] ):
				case 'No Repeat': $background_repeat = 'no-repeat'; break;
				case 'Tile': $background_repeat = 'repeat'; break;
				case 'Tile Horizontally': $background_repeat = 'repeat-x'; break;
				case 'Tile Vertically': $background_repeat = 'repeat-y'; break;
				default: $background_repeat = 'no-repeat'; break;
			endswitch;
			
			switch ( $current_settings['background_image_position'] ):
				case 'Left': $background_image_position = 'top left'; break;
				case 'Center': $background_image_position = 'top center'; break;
				case 'Right': $background_image_position = 'top right'; break;
				default: $background_image_position = 'top left'; break;
			endswitch;
	
			switch ( $current_settings['background_image_attachment'] ):
				case 'Scroll': $background_image_attachment = 'scroll'; break;
				case 'Fixed': $background_image_attachment = 'fixed'; break;
				default: $background_image_attachment = 'scroll'; break;
			endswitch;

		endif;

		$background_color = $current_settings['background_color'];
		$background_image = $current_settings['background_image'];
		
		$custom_css .= "body {";
		if ("" != $current_settings['background_image'] && "null.png" != substr($current_settings['background_image'], -8) )
			$custom_css .= "background-image: url('".$background_image."');";
		$custom_css .= "background-color: ".$current_settings['background_color'].";
				background-size: ".$background_size.";
				background-repeat: ".$background_repeat.";
				background-position: ".$background_image_position.";
				background-attachment: ".$background_image_attachment.";
			}";

	endif;

	// Sticky Header
	$sticky_header = $current_settings['sticky_header'];	
	if ( get_post_type($id) == "page" ):
		$sticky_header_page_setting = get_post_meta($id, 'icefit_pagesettings_sticky_header', true);
		if ( $sticky_header_page_setting != "" && $sticky_header_page_setting != "Use Global Setting")
			$sticky_header = $sticky_header_page_setting;
	endif;

	if ( $sticky_header == 'Sticky Header Only'
		|| $sticky_header == 'Sticky Navbar Only'
		|| $sticky_header == 'No Header')
			$custom_css .= "#main-wrap { padding-top: 30px; }";

	 if ( $sticky_header == 'Sticky Header Only'
	 	|| $sticky_header == 'Sticky Navbar Only'
	 	|| $sticky_header == 'Sticky Navbar Only + Logo')
	 		$custom_css .= "#sticky-header { display: block; }  #header-wrap { display: none; }";
	 		
	 if ( $sticky_header == 'Sticky Navbar on scroll + Logo' 
	 		|| $sticky_header == 'Sticky Navbar Only + Logo' )
	 	$custom_css .= "#sticky-header #logo { padding: 5px 10px; float:left; }
#sticky-header #logo img { height: 30px; width: auto; }";

	// Header settings
	$header_layout = $current_settings['header_layout'];
	if ( $header_layout == 'Logo Center' )
		$custom_css .= '#logo { text-align: center; float: none; max-width: 100%; }';
	
	if ( $header_layout == 'Logo Left' )
		$custom_css .= '#logo { max-width: 100%; }';

	$custom_css .= '#header {
	padding-top: '.$current_settings['header_padding_top'].'px;
	padding-bottom: '.$current_settings['header_padding_bottom'].'px; }';
	
	if ( $current_settings['header_title'] == 'Display Title' )
		$custom_css .= '#logo .site-title {
	font-family: '.$current_settings['header_title_font_family'].';
	color: '.$current_settings['header_title_font_color'].';
	font-size: '.$current_settings['header_title_font_size'].'px;
	}';

	if ( $current_settings['header_layout'] == 'Logo left + Tagline' )
		$custom_css .= '#tagline {
			font-family: '.$current_settings['header_tagline_font_family'].';
			color: '.$current_settings['header_tagline_font_color'].';
			font-size: '.$current_settings['header_tagline_font_size'].'px;
			}';


	// Navbar
	$custom_css .= '#navbar ul li * { font-family:'.$current_settings['menu_font'].'; font-size: '.$current_settings['menu_font_size'].'px; }';
	
	if ( $current_settings['menu_layout'] == 'Center No Search' )
		$custom_css .= '#navbar { text-align: center; } #navbar ul li:first-child { box-shadow: -1px 0 rgba(0,0,0,0.1) } #navbar ul li:first-child:hover { border-radius: 0; }';

	// Headings font
	$custom_css .= "#page-container h1, #page-container h2, #page-container h3, #page-container h4, #page-container h5, #page-container h6 { font-family: ".$current_settings['headings_font'].'; }';

	// Headings Styling
	$custom_css .= 'h1, h1 a, h1 a:visited {
		font-size: '.$current_settings['h1_font_size'].'px;
		color: '.$current_settings['h1_font_color'].'; }
	h2, h2 a, h2 a:visited {
		font-size: '.$current_settings['h2_font_size'].'px;
		color: '.$current_settings['h2_font_color'].'; }
	h3, h3 a, h3 a:visited {
		font-size: '.$current_settings['h3_font_size'].'px;
		color: '.$current_settings['h3_font_color'].'; }
	h4, h4 a, h4 a:visited {
		font-size: '.$current_settings['h4_font_size'].'px;
		color: '.$current_settings['h4_font_color'].'; }
	h5, h5 a, h5 a:visited {
		font-size: '.$current_settings['h5_font_size'].'px;
		color: '.$current_settings['h5_font_color'].'; }
	h6, h6 a, h6 a:visited {
		font-size: '.$current_settings['h6_font_size'].'px;
		color: '.$current_settings['h6_font_color'].'; }
	
	h1.page-title {
		font-size: '.$current_settings['page_title_font_size'].'px;
		color: '.$current_settings['page_title_font_color'].'; }

	#page-container h3.entry-title a,
	#page-container h3.entry-title a:visited,
	.post-content h1.entry-title {
		font-size: '.$current_settings['entry_title_font_size'].'px;
		color: '.$current_settings['entry_title_font_color'].'; }';

	 // Content font
	 $custom_css .= '#page-container > div, #page-container > span, #page-container > p, #page-container > table, #page-container > form, #page-container > figure, #page-container > ul, #page-container > ol, #page-container > dl, #page-container > address, #page-container > pre, #footer .container ul > *, #sidebar .textwidget, #sidebar p, #sidebar .widget_rss ul li, #footer p, #footer .container .widget_rss ul li { font-family: '.$current_settings['content_font'].';
	 font-size: '.$current_settings['content_font_size'].'px;  }';

	 $custom_css .= '#sub-footer { font-family: '.$current_settings['content_font'].'; }';
	 
	 // Sidebar Widgets
	 $custom_css .= '#sidebar h3.widget-title {
	 	font-family: '.$current_settings['sidebar_widget_title_font'].';
	 	font-size: '.$current_settings['sidebar_widget_title_font_size'].'px; }';

	 // Footer Widgets
	 $custom_css .= '#footer .widget-title {
	 	font-family: '.$current_settings['footer_widget_title_font'].';
	 	font-size: '.$current_settings['footer_widget_title_font_size'].'px;
	 	color: '.$current_settings['footer_widget_title_font_color'].'; }';


	// User defined additional custom CSS
	$custom_css .= $current_settings['custom_css'];

	/* Minify Dynamic CSS  */
    $custom_css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $custom_css); // Remove comments
    $custom_css = str_replace(array("\r\n", "\r", "\n", "\t"), '', $custom_css); // Remove tabs, newlines, etc.
	$custom_css = str_replace(
		array ("	", "  ", "{ ", " {", "} ", " }", ", ", " ,", ": ", " :", ";}"),
		array("", " ", "{", "{", "}", "}", ",", ",", ":", ":", "}"),
		$custom_css); // Remove unnecessary spaces and semicolumns beofre closing brackets
	$custom_css = trim( $custom_css ); // Trim to finish the job

	return $custom_css;
}

?>
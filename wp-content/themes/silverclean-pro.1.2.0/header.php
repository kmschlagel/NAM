<?php
/**
 *
 * Silverclean Pro WordPress Theme by Iceable Themes | http://www.iceablethemes.com
 *
 * Copyright 2013 - 2014 Mathieu Sarrasin - Iceable Media
 *
 * Header Template
 *
 */
?><!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" <?php language_attributes(); ?>> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html <?php language_attributes(); ?>> <!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php // Loads HTML5 JavaScript file to add support for HTML5 elements in older IE versions. ?>
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->
<?php global $icefit_options;
$favicon = $icefit_options['favicon'];
if ($favicon && "null.png" != substr($favicon, -8) ): ?><link rel="shortcut icon" href="<?php echo esc_url($favicon); ?>" /><?php endif; ?>
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php

	$sticky_header = $icefit_options['sticky_header'];

	if ( is_page() ):
		$sticky_header_page_setting = get_post_meta(get_the_ID(), 'icefit_pagesettings_sticky_header', true);
		if ( $sticky_header_page_setting != "" && $sticky_header_page_setting != "Use Global Setting")
			$sticky_header = $sticky_header_page_setting;
	endif;

		switch ($sticky_header):
			case 'Sticky Header Only':
				echo '<div id="sticky-header">';
				get_template_part('header', 'content');
				get_template_part('navbar', 'content');
				echo '</div>';
				break;
			case 'Sticky Navbar Only':
				echo '<div id="sticky-header">';
				get_template_part('navbar', 'content');
				echo '</div>';
				break;
			case 'Sticky Navbar Only + Logo':
				echo '<div id="sticky-header">';
				get_template_part('navbar', 'content');
				echo '</div>';
				break;				
			case 'Sticky Header on scroll':
				echo '<div id="sticky-header"></div>';
				break;
			case 'Sticky Navbar on scroll':
				echo '<div id="sticky-header"></div>';
				break;
			case 'Sticky Navbar on scroll + Logo':
				echo '<div id="sticky-header"></div>';
				break;
		endswitch;
	?>
<div id="main-wrap"<?php if ('Boxed' == $icefit_options['layout'] ) echo ' class="boxed"'; ?>>
<?php
		if ($sticky_header == 'Normal Header Only'
			|| $sticky_header == 'Sticky Header on scroll'
			|| $sticky_header == 'Sticky Navbar on scroll'
			|| $sticky_header == 'Sticky Navbar on scroll + Logo'):
			echo '<div id="header-wrap">';
			get_template_part('header', 'content');
			get_template_part('navbar', 'content');
			echo '</div>';
		endif;
	?>